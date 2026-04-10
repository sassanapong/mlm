<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class ShippingLocationtControlle extends Controller
{


    public function index()
    {
        $province = DB::table('address_provinces')
        ->select('*')
        ->get();
    return view('backend/dataset/shipping_location', compact('province'));

    }

    public function add_shipping_location(Request $rs)
    {
        try {
            DB::BeginTransaction();
            $dataPrepare = [
                'province_id_fk' => $rs->same_province,
                'district_id_fk' =>  $rs->same_district,
            ];
            DB::table('dataset_shipping_vicinity')
            ->updateOrInsert(['province_id_fk' =>$rs->same_province, 'district_id_fk' =>$rs->same_district],$dataPrepare);
            DB::commit();
            return redirect('admin/shipping_location')->withSuccess('เพิ่มพื้นที่สำเร็จ');

        } catch (Exception $e) {
            DB::rollback();
            return redirect('admin/shipping_location')->withError('เพิ่มพื้นที่ไม่สำเร็จ');
        }

    }



    public function shipping_location_datable(Request $request)
    {
        $business_location_id = 1;


        $vicinity = DB::table('dataset_shipping_vicinity')
        ->select('*');

        $sQuery = Datatables::of($vicinity);
        return $sQuery
            ->addIndexColumn()
            ->addColumn('province_id_fk', function ($row) {
                $address_provinces = DB::table('address_provinces')
                ->where('province_id','=',$row->province_id_fk)
                ->first();
                return  @$address_provinces->province_name;
            })
            ->addColumn('district_id_fk', function ($row) {
                $district = DB::table('address_districts')
                ->where('district_id','=',$row->district_id_fk)
                ->first();
                return  @$district->district_name;

            })
            ->addColumn('action', function ($row) {
                return  'ลบ';
            })

            // ->addColumn('name', function ($row) {

            //     return $row->c_name.' '.$row->last_name;
            // })





            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
