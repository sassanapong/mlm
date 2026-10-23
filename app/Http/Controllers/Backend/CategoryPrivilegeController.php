<?php

namespace App\Http\Controllers\Backend;

use App\Exports\LogPvPerDayExport;
use App\Http\Controllers\Controller;
use App\Imports\CategoryPrivilegeImport;
use DataTables;
use DB;
use Excel;
use Illuminate\Http\Request;

// รหัสสมาชิกที่ได้รับสิทธิ์ซื้อสินค้าในหมวด (access_mode = whitelist)
class CategoryPrivilegeController extends Controller
{
    protected function category($id)
    {
        $category = DB::table('dataset_categories')->where('id', $id)->first();
        abort_if(!$category, 404);
        return $category;
    }

    public function index($id)
    {
        $category = $this->category($id);
        $total = DB::table('category_privileges')->where('category_id', $id)->count();
        return view('backend/product/category_privileges', compact('category', 'total'));
    }

    public function datable($id)
    {
        $bought = DB::table('db_order_products_list as l')
            ->join('db_orders as o', 'o.code_order', '=', 'l.code_order')
            ->join('products as p', 'p.id', '=', 'l.product_id_fk')
            ->where('p.category_id', $id)
            ->where('o.order_status_id_fk', '!=', 8)
            ->whereNull('o.deleted_at')
            ->whereNull('l.deleted_at')
            ->whereColumn('o.customers_user_name', 'category_privileges.user_name')
            ->selectRaw('COALESCE(SUM(l.amt),0)');

        $query = DB::table('category_privileges')
            ->leftJoin('customers', 'customers.user_name', '=', 'category_privileges.user_name')
            ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('category_privileges.category_id', $id)
            ->select(
                'category_privileges.id',
                'category_privileges.user_name',
                'category_privileges.created_at',
                'customers.name',
                'customers.last_name',
                'dataset_qualification.business_qualifications',
                DB::raw('(' . $bought->toSql() . ') as bought_qty')
            )
            ->addBinding($bought->getBindings(), 'select');

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('full_name', function ($row) {
                return trim(($row->name ?? '') . ' ' . ($row->last_name ?? '')) ?: '<span class="text-danger">ไม่พบสมาชิก</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? date('d/m/Y H:i', strtotime($row->created_at)) : '';
            })
            ->addColumn('action', function ($row) use ($id) {
                $url = url('admin/product_category/privileges/' . $id . '/delete/' . $row->id);
                return '<a href="' . e($url) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'ลบสิทธิ์ของรหัส ' . e($row->user_name) . ' ?\')">ลบ</a>';
            })
            ->rawColumns(['full_name', 'action'])
            ->orderColumn('full_name', 'customers.name $1')
            ->filterColumn('full_name', function ($q, $keyword) {
                $q->where('customers.name', 'like', "%{$keyword}%");
            })
            ->make(true);
    }

    // เพิ่มรหัสเดียว
    public function store(Request $request, $id)
    {
        $this->category($id);
        $user_name = trim((string) $request->user_name);
        if ($user_name === '') {
            return back()->withError('กรุณากรอกรหัสสมาชิก');
        }
        if (!DB::table('customers')->where('user_name', $user_name)->exists()) {
            return back()->withError('ไม่พบรหัสสมาชิก ' . $user_name);
        }

        $exists = DB::table('category_privileges')->where('category_id', $id)->where('user_name', $user_name)->exists();
        if ($exists) {
            return back()->withError('รหัส ' . $user_name . ' มีสิทธิ์อยู่แล้ว');
        }

        DB::table('category_privileges')->insert([
            'category_id' => $id,
            'user_name' => $user_name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->withSuccess('เพิ่มสิทธิ์ให้รหัส ' . $user_name . ' เรียบร้อย');
    }

    public function destroy($id, $privilege_id)
    {
        DB::table('category_privileges')->where('category_id', $id)->where('id', $privilege_id)->delete();
        return back()->withSuccess('ลบสิทธิ์เรียบร้อย');
    }

    public function clear($id)
    {
        $this->category($id);
        DB::table('category_privileges')->where('category_id', $id)->delete();
        return back()->withSuccess('ลบสิทธิ์ทั้งหมดของหมวดนี้เรียบร้อย');
    }

    // อิมพอร์ต Excel/CSV: คอลัมน์แรก = รหัสสมาชิก (แถวหัวตารางจะข้ามให้อัตโนมัติ)
    public function import(Request $request, $id)
    {
        $this->category($id);
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240']);

        $sheets = Excel::toArray(new CategoryPrivilegeImport, $request->file('file'));
        $rows = $sheets[0] ?? [];

        $codes = [];
        foreach ($rows as $i => $row) {
            $cell = trim((string) (is_array($row) ? reset($row) : $row));
            if ($cell === '') {
                continue;
            }
            if ($i === 0 && in_array(mb_strtolower($cell), ['user_name', 'username', 'รหัสสมาชิก', 'รหัส'])) {
                continue;
            }
            $codes[$cell] = true;
        }
        $codes = array_keys($codes);

        if (!$codes) {
            return back()->withError('ไม่พบรหัสสมาชิกในไฟล์ (ต้องอยู่คอลัมน์แรก)');
        }

        $valid = [];
        foreach (array_chunk($codes, 1000) as $chunk) {
            foreach (DB::table('customers')->whereIn('user_name', $chunk)->pluck('user_name') as $u) {
                $valid[strtolower($u)] = $u;
            }
        }
        $found = [];
        $not_found = [];
        foreach ($codes as $c) {
            if (isset($valid[strtolower($c)])) {
                $found[$valid[strtolower($c)]] = true;
            } else {
                $not_found[] = $c;
            }
        }
        $found = array_keys($found);

        DB::transaction(function () use ($request, $id, $found) {
            if ($request->replace_all) {
                DB::table('category_privileges')->where('category_id', $id)->delete();
            }
            $existing = DB::table('category_privileges')->where('category_id', $id)->pluck('user_name')->map(function ($u) {
                return strtolower($u);
            })->flip();
            $now = now();
            $insert = [];
            foreach ($found as $u) {
                if (!isset($existing[strtolower($u)])) {
                    $insert[] = ['category_id' => $id, 'user_name' => $u, 'created_at' => $now, 'updated_at' => $now];
                }
            }
            foreach (array_chunk($insert, 500) as $chunk) {
                DB::table('category_privileges')->insert($chunk);
            }
        });

        $msg = 'นำเข้าสำเร็จ ' . count($found) . ' รหัส';
        if ($not_found) {
            $msg .= ' | ไม่พบในระบบ ' . count($not_found) . ' รหัส: ' . implode(', ', array_slice($not_found, 0, 20)) . (count($not_found) > 20 ? ' ...' : '');
        }
        return back()->withSuccess($msg);
    }

    public function export($id)
    {
        $category = $this->category($id);
        $rows = DB::table('category_privileges')
            ->leftJoin('customers', 'customers.user_name', '=', 'category_privileges.user_name')
            ->where('category_privileges.category_id', $id)
            ->orderBy('category_privileges.id')
            ->get(['category_privileges.user_name', 'customers.name', 'customers.last_name']);

        $logArray[] = ['user_name', 'ชื่อ-สกุล'];
        foreach ($rows as $r) {
            $logArray[] = [$r->user_name, trim(($r->name ?? '') . ' ' . ($r->last_name ?? ''))];
        }
        return Excel::download(new LogPvPerDayExport($logArray), 'category_privileges_' . $category->id . '-' . date('d-m-Y') . '.xlsx');
    }

    public function template()
    {
        return Excel::download(new LogPvPerDayExport([['user_name'], ['A000001']]), 'category_privileges_template.xlsx');
    }
}
