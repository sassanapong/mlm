<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class ApiFunction5Controller extends Controller
{


    public function dataset_categories()
    {
        $categories = DB::table('dataset_categories')
            ->where('lang_id', '=', 1)
            ->where('status', '=', 1)
            ->get();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $categories,
            'message' => 'categories successfully',
        ], 200);
    }

    public function productList(Request $request)
    {
        // Get the category from the request query parameters
        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'status' => 'error',
                'code' => 'ER01',
                'data' => null,
            ], 404);
        }

        $category_id = $request->input('category_id', null);

        // Build the query based on whether a category is provided
        $query = DB::table('products')
            ->select(
                'products.id as products_id',
                'products.category_id as category_id',
                'products_details.*',
                'products_images.img_url',
                'products_images.product_img',
                'products_images.image_default',
                'products_cost.*',
                'dataset_currency.*'
            )
            ->leftJoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
            ->leftJoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
            ->leftJoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
            ->leftJoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
            ->where('products_images.image_default', '=', 1)
            ->where('products_details.lang_id', '=', 1)
            ->where('products.status', '=', 1)
            ->where('products_cost.business_location_id', '=', 1)
            ->orderBy('products.id');

        if (!is_null($category_id)) {
            $query->where('products.category_id', '=', $category_id);
        }

        $products = $query->get();

        // Update the image URL to include the full path wrapped with asset()
        foreach ($products as $product) {
            $product->full_image_url = asset($product->img_url . $product->product_img);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $products,
            'message' => 'Products fetched successfully',
        ], 200);
    }
}
