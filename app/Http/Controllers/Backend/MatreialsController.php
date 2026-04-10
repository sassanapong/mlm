<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Matreials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatreialsController extends Controller
{

    public function index()
    {

        $materials = Matreials::get();

        return view('backend.materials.index')
            ->with('materials', $materials);
    }


    public function store_materials(Request $request)
    {
        $date_validator = [
            'materials_name' => 'required|unique:matreials',
        ];
        $err_validator =            [
            'materials_name.required' => 'กรุณากรอกชื่อวัตถุดิบ',
            'materials_name.unique' => 'ชื่อวัตถุดิบนี้ถูกมีในระบบแล้ว',

        ];
        $validator = Validator::make(
            $request->all(),
            $date_validator,
            $err_validator
        );

        if (!$validator->fails()) {
            $dataPrepare = [
                'materials_name' => $request->materials_name,
                'status' => $request->status,
            ];

            $query = Matreials::create($dataPrepare);
            if ($query) {
                return response()->json(['status' => 'success'], 200);
            }
        }
        return response()->json(['error' => $validator->errors()]);
    }


    public function get_materials(Request $request)
    {

        $query = Matreials::where('id', $request->id)->first();


        return response()->json($query);
    }


    public function update_materials(Request $request)
    {


        $date_validator = [
            'materials_name' => 'required|unique:matreials,materials_name,' . $request->materials_id,
        ];
        $err_validator =            [
            'materials_name.required' => 'กรุณากรอกชื่อวัตถุดิบ',
            'materials_name.unique' => 'ชื่อวัตถุดิบนี้ถูกมีในระบบแล้ว',

        ];
        $validator = Validator::make(
            $request->all(),
            $date_validator,
            $err_validator
        );

        if (!$validator->fails()) {

            $dataPrepare = [
                'materials_name' => $request->materials_name,
                'status' => $request->status,
            ];

            $query = Matreials::where('id', $request->materials_id)->update($dataPrepare);

            if ($query) {
                return response()->json(['status' => 'success'], 200);
            }
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
