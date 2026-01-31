<?php
// HOTEL
Route::group([
  'prefix'=>'hotel',
  'as' => 'hotel.'
],function () {
  Route::controller(HomeController::class)->group(function () {
  Route::get('/index', 'show');
  Route::get('/', 'show')->name('index');
  Route::get('login', 'login')->name('login');
  Route::post('login', 'doLogin');
  });
});

