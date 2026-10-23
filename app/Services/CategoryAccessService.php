<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * สิทธิ์การมองเห็น/สั่งซื้อตามหมวดสินค้า (ตั้งค่าที่ admin/product_category)
 *
 * เงื่อนไขทุกข้อต้องผ่านพร้อมกัน (AND) เฉพาะข้อที่มีการตั้งค่า:
 *  - ช่วงเวลาแสดง (show_start_at / show_end_at)
 *  - ตำแหน่งที่มองเห็น (allowed_positions)
 *  - เฉพาะรหัสที่ได้รับสิทธิ์ (access_mode = whitelist)
 *  - จำนวนที่ซื้อได้ต่อคน (max_qty_per_user) — ใช้ตอนสั่งซื้อ ไม่ซ่อนหมวด
 *
 * $user = ผู้ใช้ที่ login (มี user_name, qualification_id) หรือ null = ผู้เยี่ยมชม
 */
class CategoryAccessService
{
    protected static $cache = null;

    // หมวดที่มีการตั้งสิทธิ์ (key = id)
    public static function restrictedCategories()
    {
        if (self::$cache === null) {
            self::$cache = DB::table('dataset_categories')
                ->where(function ($q) {
                    $q->where('access_mode', 'whitelist')
                        ->orWhereNotNull('allowed_positions')
                        ->orWhereNotNull('max_qty_per_user')
                        ->orWhereNotNull('show_start_at')
                        ->orWhereNotNull('show_end_at');
                })
                ->get()
                ->keyBy('id');
        }
        return self::$cache;
    }

    public static function positions($category): array
    {
        $list = json_decode((string) ($category->allowed_positions ?? ''), true);
        return is_array($list) ? array_values(array_filter($list)) : [];
    }

    // มองเห็น/เข้าถึงหมวดนี้ได้ไหม
    public static function canAccess($category, $user, $now = null): bool
    {
        $now = $now ?: date('Y-m-d H:i:s');

        if (!empty($category->show_start_at) && $now < $category->show_start_at) {
            return false;
        }
        if (!empty($category->show_end_at) && $now > $category->show_end_at) {
            return false;
        }

        $positions = self::positions($category);
        if ($positions && (!$user || !in_array($user->qualification_id, $positions))) {
            return false;
        }

        if (($category->access_mode ?? 'public') === 'whitelist') {
            if (!$user) {
                return false;
            }
            return DB::table('category_privileges')
                ->where('category_id', $category->id)
                ->where('user_name', $user->user_name)
                ->exists();
        }

        return true;
    }

    // id หมวดที่ผู้ใช้นี้ไม่มีสิทธิ์เห็น
    public static function hiddenCategoryIds($user): array
    {
        $hidden = [];
        foreach (self::restrictedCategories() as $category) {
            if (!self::canAccess($category, $user)) {
                $hidden[] = (int) $category->id;
            }
        }
        return $hidden;
    }

    // จำนวนที่เคยสั่งซื้อในหมวดนี้ (ไม่นับออเดอร์ที่ยกเลิก)
    public static function purchasedQty($userName, $categoryId): int
    {
        return (int) DB::table('db_order_products_list as l')
            ->join('db_orders as o', 'o.code_order', '=', 'l.code_order')
            ->join('products as p', 'p.id', '=', 'l.product_id_fk')
            ->where('o.customers_user_name', $userName)
            ->where('p.category_id', $categoryId)
            ->where('o.order_status_id_fk', '!=', 8)
            ->whereNull('o.deleted_at')
            ->whereNull('l.deleted_at')
            ->sum('l.amt');
    }

    /**
     * ตรวจรายการในตะกร้า คืนข้อความ error หรือ null ถ้าผ่าน
     *
     * @param array $items [product_id => quantity]
     */
    public static function validateItems($user, array $items): ?string
    {
        if (!$items) {
            return null;
        }

        $restricted = self::restrictedCategories();
        if ($restricted->isEmpty()) {
            return null;
        }

        $categoryOf = DB::table('products')->whereIn('id', array_keys($items))->pluck('category_id', 'id');

        $qtyByCategory = [];
        foreach ($items as $productId => $qty) {
            $cid = $categoryOf[$productId] ?? null;
            if ($cid !== null && $restricted->has($cid)) {
                $qtyByCategory[$cid] = ($qtyByCategory[$cid] ?? 0) + (int) $qty;
            }
        }

        foreach ($qtyByCategory as $cid => $qty) {
            $category = $restricted[$cid];

            if (!self::canAccess($category, $user)) {
                return 'ท่านไม่มีสิทธิ์สั่งซื้อสินค้าในหมวด "' . $category->category_name . '"';
            }

            if (!empty($category->max_qty_per_user)) {
                $max = (int) $category->max_qty_per_user;
                $bought = $user ? self::purchasedQty($user->user_name, $cid) : 0;
                if ($bought + $qty > $max) {
                    $left = max($max - $bought, 0);
                    return 'สินค้าหมวด "' . $category->category_name . '" ซื้อได้ไม่เกิน ' . $max
                        . ' ชิ้น/ท่าน (ซื้อไปแล้ว ' . $bought . ' ชิ้น, ซื้อเพิ่มได้อีก ' . $left . ' ชิ้น)';
                }
            }
        }

        return null;
    }

    // รายการในตะกร้า [product_id => qty]
    public static function cartItems(): array
    {
        $items = [];
        foreach (\Cart::session(1)->getContent() as $row) {
            $items[$row['id']] = ($items[$row['id']] ?? 0) + (int) $row['quantity'];
        }
        return $items;
    }
}
