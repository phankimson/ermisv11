<?php

//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
//Route::get('/test', function () {
//    return 'Hello World';
//});
Route::group(["as" => "api."],function () {
  $files = glob(__DIR__ .'/api/*.php', GLOB_BRACE);
  foreach ($files as $filename) {
    include_once $filename; // Include api routes
  }
});