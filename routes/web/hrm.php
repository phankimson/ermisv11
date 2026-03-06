<?php
// HRM
Route::group([
  'prefix'=>'hrm',
  'as' => 'hrm.'
],function () {
  Route::controller(HomeController::class)->group(function () {
    Route::get('login', 'login' )->name('login');
  });

  Route::controller(UserController::class)->group(function () {
  Route::post('login', 'doLogin' );
  Route::post('/avatar-profile', 'updateAvatar' );
  Route::post('/change-password', 'changePassword' );
  });

  Route::controller(PosHomeController::class)->group(function () {
    Route::get('/index', 'show');
    Route::get('/', 'show')->name('index');
  });
});
