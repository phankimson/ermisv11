<?php
// HRM
Route::group([
  'prefix'=>'hrm',
  'as' => 'hrm.'
],function () {
  Route::controller(HomeController::class)->group(function () {
  Route::get('/index', 'show');
  Route::get('/', 'show')->name('index');
  Route::get('login', 'login')->name('login');
  Route::post('login', 'doLogin');
  });
});
