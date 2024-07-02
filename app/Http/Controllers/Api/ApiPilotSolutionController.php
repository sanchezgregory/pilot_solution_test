<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Src\Service;


class ApiPilotSolutionController extends Controller
{
    const ERROR_CODE = 1001;

    public function question1()
    {
        $data = new Service();
        $result = $data->getPrimoNumber();
        return response()->json($result);
    }

    public function storePayment(Request $request)
    {
        $data = new Service();
        $result = $data->storePayment($request);
        return response()->json($result);
    }

    public function processPayment(Request $request)
    {
        $data = new Service();
        try {
            $result = $data->processPayment($request);
        } catch (\Exception $e) {
            $result = [
                "error_code"=> self::ERROR_CODE,
                "error_message"=> "Ha ocurrido un error procesando la solicitud: " . $e->getMessage(),
            ];
        }
        return response()->json($result);
    }
}
