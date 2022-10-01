<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product_Category;
use DB;

class Product_CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pro_cate = Product_Category::all();
        $data = array(
            'Product_cate' => $pro_cate
        );
        return view('backend/product/product_category', $data);
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
        $pro_cate_id = Product_Category::orderBy('id', 'DESC')->first();
        $pro_cate = new Product_Category;
        $pro_cate->category_id = $pro_cate_id + 1;
        $pro_cate->category_name = $request->category_name;
        $pro_cate->lang_id = '1';
        $pro_cate->status = $request->status;
        $pro_cate->save();

        return redirect('admin/product_category');
    }

    public function Pulldata(Request $request)
    {
        $id_pro_cate = $request->id;
        $sql_pro_cate = DB::table('dataset_categories')
            ->select(
                'dataset_categories.*',
            )->where('id', $id_pro_cate)
            ->first();

        return response()->json($sql_pro_cate, 200);
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
        $pro_cate = Product_Category::find($request->id);
        $pro_cate->category_id = $request->id;
        $pro_cate->category_name = $request->category_name_update;
        $pro_cate->lang_id = $request->select_pro_cate_lang_update;
        $pro_cate->status = $request->status_update;
        $pro_cate->update();

        return redirect('admin/product_category');
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
    public function destroy($id)
    {
        $pro_cate = Product_Category::find($id);
        $pro_cate->delete();
        $max1 = DB::table('dataset_categories')->max('id') + 1;
        DB::statement("ALTER TABLE dataset_categories AUTO_INCREMENT =  $max1");
        return back();
    }
}
