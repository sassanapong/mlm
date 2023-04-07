<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\External\Tool;
use App\Products;
use App\Product_Category;
use App\Product_Cost;
use App\Product_Details;
use App\Product_Images;
use App\Product_Size;
use App\ProductsUnit;
use App\Matreials;
use App\ProductMaterals;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $product = Products::all();
        $product = DB::table('products_details')
            ->join('products', 'products_details.product_id_fk', 'products.id')
            ->select(
                'products.id',
                'products_details.product_name',
                'products_details.title',
                'products_details.descriptions',
                'products_details.products_details',
                'products.status',
            )->where('products_details.lang_id', '=', '1')
            ->get();

        $pro_cate = Product_Category::all()->where('status', '=', '1');
        $pro_size = Product_Size::all()->where('status', '=', '1');
        $pro_unit = ProductsUnit::all()->where('status', '=', '1')->where('lang_id', '=', '1');
        $materials = Matreials::where('status', 1)->get();
        // dd($product);
        $data = array(
            'Product' => $product,
            'Product_cate' => $pro_cate,
            'Product_size' => $pro_size,
            'Product_unit' => $pro_unit,
            'materials' => $materials
        );
        return view('backend/product/product', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $pro_id = Products::orderBy('id', 'DESC')->first();
        $num_length = strlen((string)$pro_id->id);
        $pro = new Products;
        switch ($num_length) {
            case ('2'):
                $cal_len = str_pad($pro_id->id, 3, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len + 1;
                break;
            case ('3'):
                $cal_len = str_pad($pro_id->id, 4, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len + 1;
                break;
            case ('4'):
                $cal_len = str_pad($pro_id->id, 5, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len + 1;
                break;
            case ('5'):
                $cal_len = str_pad($pro_id->id, 6, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len + 1;
                break;
            case ('6'):
                $cal_len = str_pad($pro_id->id, 7, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len + 1;
                break;
            case ('7'):
                $cal_len = str_pad($pro_id->id, 8, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len + 1;
                break;
            case ('8'):
                $cal_len = str_pad($pro_id->id, 9, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len + 1;
                break;
            case ('9'):
                $cal_len = str_pad($pro_id->id, 10, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len + 1;
                break;
            case ('10'):
                $cal_len = str_pad($pro_id->id, 11, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len + 1;
                break;
        }
        $pro->category_id = $request->select_category;
        $pro->unit_id = $request->select_unit;
        $pro->size_id = $request->select_size;
        $pro->product_voucher = '';
        $pro->status = $request->status;
        $pro->save();

        $pro_cost = new Product_Cost;
        $pro_cost->product_id_fk = $pro_id->id + 1;
        $pro_cost->business_location_id = '1';
        $pro_cost->currency_id = '1';
        $pro_cost->cost_price = $request->cost_price;
        $pro_cost->selling_price = $request->selling_price;
        $pro_cost->member_price = $request->member_price;
        $pro_cost->pv = $request->product_pv;
        $pro_cost->status = $request->status;
        $pro_cost->status_shipping = $request->status_shipping;
        $pro_cost->save();

        $pro_det = new Product_Details();
        $pro_det->product_id_fk = $pro_id->id + 1;
        $pro_det->product_name = $request->product_name;
        $pro_det->title = $request->product_title;
        $pro_det->descriptions = $request->product_descrip;
        $pro_det->products_details = $request->products_details;
        $pro_det->lang_id = '1';
        $pro_det->save();

        $pro_img = new Product_Images();
        $pro_img->product_id_fk = $pro_id->id + 1;
        $pro_img->img_url = 'local/public/products/';
        $path = public_path() . '/products/';
        if (!empty($request->file('product_img'))) {
            if (isset($pro_img->product_img)) {
                // unlink($path . $pro_img->product_img);
            }
            $imgwidth = 1920;
            $filename = '_image_product';
            $request_file = $request->file('product_img');
            $name_pic = Tool::upload_picture($path, $imgwidth, $filename, $request_file);
            $pro_img->product_img = $name_pic;
        }
        $pro_img->image_default = '1';
        $pro_img->save();




        foreach ($request->materials as $val) {

            if ($val['id'] != null) {
                $dataPrepare_materials = [
                    'product_id' => $pro_id->id + 1,
                    'matreials_id' => $val['id'],
                    'matreials_count' => $val['count']
                ];
                $query_ProductMaterals = ProductMaterals::create($dataPrepare_materials);
            }
        }




        return redirect('admin/product');
    }

    public function Pulldata(Request $request)
    {

        $id_pro = $request->id;

        $sql_product = DB::table('products')
            ->leftjoin('products_details', 'products.id', 'products_details.product_id_fk')
            ->leftjoin('products_cost', 'products.id', 'products_cost.product_id_fk')
            ->leftjoin('products_images', 'products.id', 'products_images.product_id_fk')
            ->leftjoin('dataset_categories', 'products.category_id', 'dataset_categories.id')
            ->leftjoin('dataset_product_unit', 'products.unit_id', 'dataset_product_unit.id')
            ->leftjoin('dataset_size', 'products.size_id', 'dataset_size.id')
            ->select(
                'products.id',
                'products.category_id',
                'products.unit_id',
                'products.size_id',
                'products.status',
                'products_details.product_name',
                'products_details.title',
                'products_details.descriptions',
                'products_details.products_details',
                'products_details.lang_id',
                'dataset_categories.category_name',
                'dataset_product_unit.product_unit',
                'dataset_size.size',
                'products_cost.cost_price',
                'products_cost.selling_price',
                'products_cost.member_price',
                'products_cost.pv',
                'products_cost.status_shipping',
                'products_images.product_img',
            )->where('products.id', $id_pro)
            ->where('products_details.lang_id', '=', '1')
            // ->where('products.status', '=', '1')
            // ->where('products_cost.status', '=', '1')
            ->where('products_images.image_default', '=', '1')
            // ->where('dataset_categories.status', '=', '1')
            ->where('dataset_product_unit.status', '=', '1')
            ->where('dataset_size.status', '=', '1')
            ->first();

            // dd($sql_product);


        $materials = ProductMaterals::where('product_id', $id_pro)
            ->join('matreials', 'matreials.id', 'matreials_id')
            ->get();


        return response()->json(['sql_product' => $sql_product, 'materials' => $materials], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        $num_length = strlen((string)$request->id);
        $pro = Products::find($request->id);
        switch ($num_length) {
            case ('2'):
                $cal_len = str_pad($request->id, 3, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len;
                break;
            case ('3'):
                $cal_len = str_pad($request->id, 4, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len;
                break;
            case ('4'):
                $cal_len = str_pad($request->id, 5, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len;
                break;
            case ('5'):
                $cal_len = str_pad($request->id, 6, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len;
                break;
            case ('6'):
                $cal_len = str_pad($request->id, 7, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len;
                break;
            case ('7'):
                $cal_len = str_pad($request->id, 8, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len;
                break;
            case ('8'):
                $cal_len = str_pad($request->id, 9, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len;
                break;
            case ('9'):
                $cal_len = str_pad($request->id, 10, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len;
                break;
            case ('10'):
                $cal_len = str_pad($request->id, 11, "0", STR_PAD_LEFT);
                $pro->product_code = $cal_len;
                break;
        }
        $pro->category_id = $request->select_category_update;
        $pro->unit_id = $request->select_unit_update;
        $pro->size_id = $request->select_size_update;
        $pro->product_voucher = '';
        $pro->status = $request->status_update;
        $pro->update();

        $pro_cost = Product_Cost::find($request->id);
        $pro_cost->product_id_fk = $pro->id;
        $pro_cost->business_location_id = '1';
        $pro_cost->currency_id = '1';
        $pro_cost->cost_price = $request->cost_price_update;
        $pro_cost->selling_price = $request->selling_price_update;
        $pro_cost->member_price = $request->member_price_update;
        $pro_cost->pv = $request->product_pv_update;
        $pro_cost->status = $request->status_update;
        $pro_cost->status_shipping = $request->status_shipping_update;
        $pro_cost->update();

        $pro_det = Product_Details::where('product_id_fk', $request->id)->first();
        $pro_det->product_id_fk = $pro->id;
        $pro_det->product_name = $request->product_name_update;
        $pro_det->title = $request->product_title_update;
        $pro_det->descriptions = $request->product_descrip_update;
        $pro_det->products_details = $request->products_details_update;
        $pro_det->lang_id = '1';
        $pro_det->update();

        $pro_img = Product_Images::where('product_id_fk', $request->id)->first();
        $pro_img->product_id_fk = $pro->id;
        $pro_img->img_url = 'local/public/products/';
        $path = public_path() . '/products/';
        if (!empty($request->file('product_img_update'))) {
            if (isset($pro_img->product_img_update)) {
                // unlink($path . $pro_img->product_img_update);
            }
            $imgwidth = 1920;
            $filename = '_image_product';
            $request_file = $request->file('product_img_update');
            $name_pic = Tool::upload_picture($path, $imgwidth, $filename, $request_file);
            $pro_img->product_img = $name_pic;
        }
        $pro_img->image_default = '1';
        $pro_img->update();
        $query_del_materials = ProductMaterals::where('product_id', $pro->id)->delete();



        foreach ($request->materials as $val) {
            if ($val['id'] != null) {
                $dataPrepare_materials = [
                    'product_id' => $pro->id,
                    'matreials_id' => $val['id'],
                    'matreials_count' => $val['count']
                ];
                $query_ProductMaterals = ProductMaterals::create($dataPrepare_materials);
            }
        }

        return redirect('admin/product');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        dd('ไม่ามารถลบสินค้าได้กรุณาติดต่อ Programmer');
        try {
            // $pro = Products::find($id);
            // $pro->delete();
            // $pro_cost = Product_Cost::where('product_id_fk', $id)->delete();
            // $pro_det = Product_Details::where('product_id_fk', $id)->delete();
            // $pro_img = Product_Images::where('product_id_fk', $id)->delete();
            // $path = public_path() . '/upload/products/';
            // unlink($path . $pro_img->product_img);

            DB::table('products')
                ->where('products.id', $id)
                ->delete();
            $max1 = DB::table('products')->max('id') + 1;
            DB::statement("ALTER TABLE products AUTO_INCREMENT =  $max1");

            DB::table('products_cost')
                ->where('products_cost.product_id_fk', $id)
                ->delete();
            $max2 = DB::table('products_cost')->max('id') + 1;
            DB::statement("ALTER TABLE products_cost AUTO_INCREMENT =  $max2");

            DB::table('products_details')
                ->where('products_details.product_id_fk', $id)
                ->delete();
            $max3 = DB::table('products_details')->max('id') + 1;
            DB::statement("ALTER TABLE products_details AUTO_INCREMENT =  $max3");

            $pro_img = DB::table('products_images')
                ->where('products_images.product_id_fk', $id)
                ->first();
            $path = public_path() . '/products/' . $pro_img->product_img;
            $img = realpath($path);
            unlink($img);
            DB::table('products_images')
                ->where('products_images.product_id_fk', $id)
                ->delete();
            $max4 = DB::table('products_images')->max('id') + 1;
            DB::statement("ALTER TABLE products_images AUTO_INCREMENT =  $max4");
        } catch (Throwable $e) {
        }

        return back();
    }

    public function get_slide_product(Request $request)
    {
        $pro = Products::where('id', '=', $request->id)->first();
        $count = Products::count();
        $data = array(
            'Product' => $pro,
            'count' => $count
        );
        echo json_encode($data);
    }


    public function slide_update(Request $request)
    {
        $pro = Products::find($request->show_id);
        $pro->orderby = $request->slide_no;
        $pro->update();
        return back();
    }
}
