<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Src\Service;


class ApiShippingController extends Controller
{
    public function index()
    {
        return response()->json('done');
    }

    public function shippingCosts(Request $request)
    {
        $data = new Service();
        $result = $data->getShippingCosts($request->get('option'));
        return response()->json($result);

    }
}
