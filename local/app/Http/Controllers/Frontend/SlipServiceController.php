<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;

class SlipServiceController extends Controller
{


    public static function processSlip($branchId, $apiKey, $file)
    {
        $url = 'https://api.slipok.com/api/line/apikey/' . $branchId;

        // Prepare headers
        $headers = [
            'x-authorization' => $apiKey,
        ];

        // Prepare form data
        $fields = [
            'files' => \Illuminate\Support\Facades\Storage::disk('local')->get($file), // Assuming you upload from storage
            'log' => true,
        ];

        // Make the API request using Laravel's HTTP client
        $response = Http::withHeaders($headers)
            ->attach('files', fopen(storage_path('app/' . $file), 'r')) // If file is stored in local disk
            ->post($url, $fields);

        // Handle the response
        if ($response->successful()) {
            $jsonResponse = $response->json();

            // Check if the success key is true
            if ($jsonResponse['success']) {
                // Handle correct slip
                return response()->json($jsonResponse['data']);
            } else {
                // Handle incorrect slip
                return response()->json([
                    'code' => $jsonResponse['code'],
                    'message' => $jsonResponse['message']
                ], 400);
            }
        } else {
            // Handle HTTP error
            return response()->json([
                'error' => 'Request failed',
                'details' => $response->body()
            ], $response->status());
        }
    }
}
