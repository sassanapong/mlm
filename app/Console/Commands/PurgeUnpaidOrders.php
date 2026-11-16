<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/*
| กวาดลบบิลสถานะ 1 (รอส่งเอกสารการชำระ) ที่ค้างเกิน N วัน (ค่าเริ่มต้น 7)
| บิลสถานะนี้ยังไม่ตัดสต็อก/eWallet/PV (ตัดตอน run_payment) จึงลบได้โดยไม่ต้องคืนค่า
| ลบ db_orders พร้อมรายการสินค้า db_order_products_list
|
| php artisan orders:purge-unpaid --dry-run   (ดูรายการอย่างเดียว)
*/
class PurgeUnpaidOrders extends Command
{
    protected $signature = 'orders:purge-unpaid {--days=7} {--dry-run}';

    protected $description = 'ลบบิลสถานะรอส่งเอกสารการชำระ (1) ที่ค้างเกิน N วัน';

    public function handle()
    {
        $days = (int) $this->option('days');
        if ($days < 1) {
            $this->error('--days ต้องมากกว่า 0');
            return 1;
        }

        $orders = DB::table('db_orders')
            ->where('order_status_id_fk', 1)
            ->where('created_at', '<', now()->subDays($days))
            ->get(['id', 'code_order', 'customers_user_name', 'total_price', 'created_at']);

        if ($orders->isEmpty()) {
            $this->info('ไม่มีบิลที่ต้องลบ');
            return 0;
        }

        if ($this->option('dry-run')) {
            foreach ($orders as $o) {
                $this->line("{$o->code_order}  {$o->customers_user_name}  {$o->total_price}  {$o->created_at}");
            }
            $this->info('dry-run: จะลบ ' . $orders->count() . ' บิล');
            return 0;
        }

        $deleted = 0;
        foreach ($orders->chunk(200) as $chunk) {
            $ids = $chunk->pluck('id')->all();
            DB::transaction(function () use ($ids, &$deleted) {
                DB::table('db_order_products_list')->whereIn('order_id_fk', $ids)->delete();
                // ลบซ้ำด้วยเงื่อนไขสถานะ กันบิลที่เพิ่งชำระระหว่างรัน
                $deleted += DB::table('db_orders')->whereIn('id', $ids)->where('order_status_id_fk', 1)->delete();
            });
        }

        Log::info('orders:purge-unpaid', ['days' => $days, 'deleted' => $deleted]);
        $this->info("ลบแล้ว {$deleted} บิล");
        return 0;
    }
}
