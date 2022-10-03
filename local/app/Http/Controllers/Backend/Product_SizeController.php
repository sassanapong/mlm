<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product_Size;
use DB;

class Product_SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pro_size = Product_Size::all();
        $data = array(
            'Product_size' => $pro_size
        );
        return view('backend/product/product_size', $data);
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
        $pro_size = new Product_Size;
        $pro_size->size = $request->size_name;
        $pro_size->status = '1';
        $pro_size->save();

        return redirect('admin/product_size');
    }

    public function Pulldata(Request $request)
    {
        $id_pro_size = $request->id;
        $sql_pro_size = DB::table('dataset_size')
            ->select(
                'dataset_size.*',
            )->where('id', $id_pro_size)
            ->first();

        return response()->json($sql_pro_size, 200);
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
        $pro_size = Product_Size::find($request->id);
        $pro_size->size = $request->size_name_update;
        $pro_size->status = $request->status_update;
        $pro_size->update();

        return redirect('admin/product_size');
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
        $pro_size = Product_Size::find($id);
        $pro_size->delete();
        $max1 = DB::table('dataset_size')->max('id') + 1;
        DB::statement("ALTER TABLE dataset_size AUTO_INCREMENT =  $max1");
        return back();
    }
}
