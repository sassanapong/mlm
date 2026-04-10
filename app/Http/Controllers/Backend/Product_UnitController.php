<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductsUnit;
use DB;

class Product_UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pro_unit = ProductsUnit::all();
        $data = array(
            'Product_unit' => $pro_unit
        );
        return view('backend/product/product_unit', $data);
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
        $pro_u = ProductsUnit::orderBy('id', 'DESC')->first();
        $pro_unit = new ProductsUnit;
        $pro_unit->product_unit_id = $pro_u->id + 1;
        $pro_unit->product_unit = $request->product_unit;
        $pro_unit->lang_id = '1';
        $pro_unit->status = $request->status;
        $pro_unit->save();

        return redirect('admin/product_unit');
    }

    public function Pulldata(Request $request)
    {
        $id_pro_unit = $request->id;
        $sql_pro_unit = DB::table('dataset_product_unit')
            ->select(
                'dataset_product_unit.*',
            )->where('id', $id_pro_unit)
            ->first();

        return response()->json($sql_pro_unit, 200);
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
        $pro_unit = ProductsUnit::find($request->id);
        $pro_unit->product_unit = $request->product_unit_update;
        $pro_unit->product_unit_id = $request->id;
        $pro_unit->lang_id = $request->select_pro_unit_lang_update;
        $pro_unit->status = $request->status_update;
        $pro_unit->update();

        return redirect('admin/product_unit');
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
        $pro_unit = ProductsUnit::find($id);
        $pro_unit->delete();
        $max1 = DB::table('dataset_product_unit')->max('id') + 1;
        DB::statement("ALTER TABLE dataset_product_unit AUTO_INCREMENT =  $max1");
        return back();
    }
}
