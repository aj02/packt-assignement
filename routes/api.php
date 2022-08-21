<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/books', function (Request $request) {
    $response = Http::get('https://api.packt.com/api/v1/products?token=iWBfwxzpfggY5kunlL70pOsQg1oUiX5JeOXCTWCy');

    $response = json_decode($response->getBody());

    return $response;

});
