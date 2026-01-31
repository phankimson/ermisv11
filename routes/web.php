<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Test -  Thử nghiệm

Route::controller(TestController::class)->group(function () {
Route::get('/test1', 'test1');
Route::get('/test1', 'test2');
Route::get('/test', 'test');
Route::post('/test-get','get');
});

Route::controller(HomeController::class)->group(function () {
Route::get('/','index')->name('index');
Route::get('/index','index');
Route::get('welcome/{name}','welcome')->name('welcome');
Route::get('register','register')->name('register');
});

Route::controller(UserController::class)->group(function () {
Route::get('logout','doLogout')->name('logout');
Route::post('register', 'doRegister');
Route::post('check-register', 'checkRegister');
});

Route::controller(DatabaseController::class)->group(function () {
Route::get('create_database', 'create_database');
});


$files = glob(__DIR__ .'/web/*.php', GLOB_BRACE);
foreach ($files as $filename) {
  require_once $filename; // Nạp file manage.php
}
