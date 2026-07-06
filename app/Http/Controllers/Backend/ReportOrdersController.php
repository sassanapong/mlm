<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class ReportOrdersController extends Controller
{


    public function index()
    {


        return view('backend/orders_report/index');
    }

    public function order_report_datable(Request $request)
    {
        $business_location_id = 1;

        $orders = DB::table('db_orders')
            ->select(
                'db_orders.*',
                'db_order_products_list.product_name',
                'db_order_products_list.product_name',
                'db_order_products_list.amt',
                'db_order_products_list.total_pv',
                'db_order_products_list.total_price',
                'customers.id_card',
                'customers_address_card.address',
                'customers_address_card.moo',
                'customers_address_card.soi',
                'customers_address_card.road',
                'district_name as district',
                'province_name as province',
                'tambon_name as tambon',
                'customers_address_card.zipcode',
                'customers.name as c_name',
                'customers.last_name'
            )
            ->leftjoin('db_order_products_list', 'db_order_products_list.code_order', '=', 'db_orders.code_order')
            ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
            ->leftjoin('customers_address_card', 'db_orders.customers_user_name', '=', 'customers_address_card.user_name')
            ->leftjoin('address_districts', 'address_districts.district_id', 'customers_address_card.district')
            ->leftjoin('address_provinces', 'address_provinces.province_id', 'customers_address_card.province')
            ->leftjoin('address_tambons', 'address_tambons.tambon_id', 'customers_address_card.tambon')
            ->wherein('db_orders.order_status_id_fk', [4, 5, 6, 7])
            ->when($request->s_date && !$request->e_date, function ($query) use ($request) {
                $query->whereDate('db_orders.created_at', $request->s_date);
            })
            ->when($request->s_date && $request->e_date, function ($query) use ($request) {
                $query->whereDate('db_orders.created_at', '>=', $request->s_date)
                    ->whereDate('db_orders.created_at', '<=', $request->e_date);
            })
            ->when(!$request->s_date && $request->e_date, function ($query) use ($request) {
                $query->whereDate('db_orders.created_at', $request->e_date);
            })
            ->when($request->user_name, function ($query) use ($request) {
                $query->where('db_orders.customers_user_name', $request->user_name);
            })
            ->when($request->code_order, function ($query) use ($request) {
                $query->where('db_orders.code_order', $request->code_order);
            })
            ->when($request->payment_type === 'payso', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('db_orders.payment_gateway', 'payso')
                        ->orWhere('db_orders.pay_type', 'payso');
                });
            })
            ->when($request->payment_type === 'ewallet', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('db_orders.pay_type', 'e-wallet')
                        ->orWhereNull('db_orders.pay_type')
                        ->orWhere('db_orders.pay_type', '');
                })->where(function ($subQuery) {
                    $subQuery->whereNull('db_orders.payment_gateway')
                        ->orWhere('db_orders.payment_gateway', '')
                        ->orWhere('db_orders.payment_gateway', '!=', 'payso');
                });
            });

        $paymentSummary = (clone $orders)
            ->select(
                DB::raw("
                    CASE
                        WHEN db_orders.payment_gateway = 'payso' OR db_orders.pay_type = 'payso' THEN 'payso'
                        WHEN db_orders.pay_type = 'e-wallet' OR db_orders.pay_type IS NULL OR db_orders.pay_type = '' THEN 'ewallet'
                        ELSE 'other'
                    END as payment_key
                "),
                DB::raw('COUNT(DISTINCT db_orders.code_order) as order_count'),
                DB::raw('SUM(db_order_products_list.total_price - (db_order_products_list.total_pv * db_orders.bonus_percent / 100)) as net_total')
            )
            ->groupBy('payment_key')
            ->get()
            ->keyBy('payment_key');

        // $orders = DB::table('db_orders')
        //     ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class')
        //     ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')

        //     ->where('dataset_order_status.lang_id', '=', $business_location_id)
        //     ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"));
        //     // ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
        //     // ->orwhere('db_orders.customers_sent_id_fk', '=', Auth::guard('c_user')->user()->id);
        //     // ->orderby('db_orders.updated_at', 'DESC')
        //     // ->get();
        // // dd($orders);

        $sQuery = Datatables::of($orders);
        return $sQuery

            ->setRowClass('intro-x py-4 h-24 zoom-in')
            ->addColumn('date', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })
            ->addColumn('code_order', function ($row) {
                $data =  $row->code_order;

                return $data;
            })

            ->addColumn('payment_type', function ($row) {
                return $this->paymentTypeLabel($row);
            })

            ->addColumn('position', function ($row) {
                return $row->position;

                // $dataset_qualification = DB::table('dataset_qualification')
                //     ->where('code', $row->position)
                //     ->first();

                // if ($dataset_qualification) {
                //     return $dataset_qualification->business_qualifications;
                // } else {
                //     return '-';
                // }
            })

            ->addColumn('name', function ($row) {

                return $row->c_name . ' ' . $row->last_name;
            })


            ->addColumn('product_name', function ($row) {
                return  $row->product_name;
            })

            ->addColumn('amt', function ($row) {
                return  number_format($row->amt);
            })
            ->addColumn('total_pv', function ($row) {
                return  number_format($row->total_pv);
            })

            ->addColumn('total_price', function ($row) {
                return  number_format($row->total_price);
            })

            ->addColumn('discount', function ($row) {
                $discount = $row->total_pv * $row->bonus_percent / 100;
                return  number_format($discount);
            })

            ->addColumn('total', function ($row) {
                $total = $row->total_price - ($row->total_pv * $row->bonus_percent / 100);
                return  number_format($total);
            })
            // ->addColumn('id_card', function ($row) {
            //     $id_card = '';
            //     return  $id_card;
            // })

            ->addColumn('address', function ($row) {
                if ($row->district) {
                    $address = $row->address . ' ม.' . $row->moo . ' ซอย.' . $row->soi . ' ถนน.' . $row->road . ' ต.' . $row->tambon . ' อ.' . $row->district . ' จ.' . $row->province . ' ' . $row->zipcode;
                } else {
                    $address = '';
                }
                return $address;
            })




            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->with('payment_summary', [
                'ewallet' => [
                    'label' => 'หักเงิน eWallet',
                    'order_count' => (int) optional($paymentSummary->get('ewallet'))->order_count,
                    'net_total' => round((float) optional($paymentSummary->get('ewallet'))->net_total, 2),
                ],
                'payso' => [
                    'label' => 'ชำระผ่าน Payment',
                    'order_count' => (int) optional($paymentSummary->get('payso'))->order_count,
                    'net_total' => round((float) optional($paymentSummary->get('payso'))->net_total, 2),
                ],
                'other' => [
                    'label' => 'อื่นๆ',
                    'order_count' => (int) optional($paymentSummary->get('other'))->order_count,
                    'net_total' => round((float) optional($paymentSummary->get('other'))->net_total, 2),
                ],
            ])
            ->make(true);
    }

    private function paymentTypeLabel($row)
    {
        if ($row->payment_gateway == 'payso' || $row->pay_type == 'payso') {
            return 'ชำระผ่าน Payment';
        }

        if ($row->pay_type == 'e-wallet' || empty($row->pay_type)) {
            return 'หักเงิน eWallet';
        }

        return $row->pay_type;
    }
}
