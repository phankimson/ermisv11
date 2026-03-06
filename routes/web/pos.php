<?php
// POS
Route::group([
  'prefix'=>'pos',
  'as' => 'pos.'
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

  Route::controller(PosSaleController::class)->group(function () {
    Route::post('/sales', 'store')->name('sales.store');
  });

  Route::controller(PosReturnController::class)->group(function () {
    Route::post('/returns', 'store')->name('returns.store');
  });

  Route::controller(PosStockInController::class)->group(function () {
    Route::post('/stock-in', 'store')->name('stock-in.store');
  });

  Route::controller(PosStockOutController::class)->group(function () {
    Route::post('/stock-out', 'store')->name('stock-out.store');
  });

  Route::controller(PosStockTransferController::class)->group(function () {
    Route::post('/stock-transfer', 'store')->name('stock-transfer.store');
  });

  Route::controller(PosCashCounterController::class)->group(function () {
    Route::post('/cash-counter', 'store')->name('cash-counter.store');
  });

  Route::controller(PosReportController::class)->group(function () {
    Route::get('/report/daily', 'daily')->name('report.daily');
  });
});
