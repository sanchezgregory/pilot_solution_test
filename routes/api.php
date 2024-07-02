<?php

use App\Http\Controllers\Api\ApiPilotSolutionController;
use Illuminate\Support\Facades\Route;
Route::get('question_1', [ApiPilotSolutionController::class, 'question1']);
Route::post('store-payment', [ApiPilotSolutionController::class, 'storePayment']);
Route::post('process-payment', [ApiPilotSolutionController::class, 'processPayment']);
