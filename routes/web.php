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
// Manage -  Quản lý
Route::group([
  'prefix'=>'manage',
  'as' => 'manage.'
],function () {

  // Globals - Quản lý chung
  Route::controller(HomeController::class)->group(function () {
  Route::get('/index', 'show');
  Route::get('/block','block' )->name('block');
  Route::get('/profile','profile' )->name('profile');
  Route::get('/', 'show')->name('index');
  Route::get('login', 'login' )->name('login');
  });

  Route::controller(ChatTimelineController::class)->group(function () {
  Route::post('timeline', 'timeline' );
  Route::post('view-more-timeline', 'viewMore');
  Route::post('load-chat-user','loadChatUser');
  Route::post('chat', 'doChat');
  });

  Route::controller(ChatBotAiController::class)->group(function () {
  Route::post('chat-ai', 'doChatBotAI' );
  });

  Route::controller(UserController::class)->group(function () {
  Route::post('login','doLogin');
  Route::post('/profile','updateProfile' );
  Route::post('/avatar-profile','updateAvatar' );
  Route::post('/change-password', 'changePassword');
  Route::post('/load-history-action', 'loadHistoryAction' );
  });

  // Setting - Cài đặt
  Route::group([
    'as' => 'setting',
    'controller' => SettingController::class
  ],function () {
  Route::get('/setting', 'show')->name('');
  Route::post('/setting-change',  'changeSetting')->name('-change');
  });

  // Query -  Truy vấn
  Route::group([
    'as' => 'query',
    'controller' => QueryController::class
  ],function () {
  Route::get('/query','show')->name('');
  Route::post('/query','query')->name('-run');
  Route::post('/query-change-database','ChangeDatabase')->name('-change-database');
  });

   // Update Database - Cập nhật cơ sở dữ liệu
  Route::group([
    'as' => 'update-database',
    'controller' => UpdateDatabaseController::class
  ],function () {
  Route::get('/update-database','show')->name('');
  Route::post('/update-database','start')->name('-start');
  Route::post('/load-database','GetTableDatabase')->name('-load-database');
  });

  // Notes - Ghi chú
  Route::group([
    'as' => 'notes',
    'controller' => NotesController::class
  ],function () {
  Route::get('/notes','show' )->name('');
  Route::post('/notes-load','load' )->name('-load');
  Route::post('/notes-save', 'save' )->name('-save');
  Route::post('/notes-delete', 'delete' )->name('-delete');
  });

  // Permission - Quyền
  Route::group([
    'as' => 'permission',
    'controller' => PermissionController::class
  ],function () {
  Route::get('/permission','show' )->name('');
  Route::post('/permission-load','load' )->name('-load');
  Route::post('/permission-group','group')->name('-group');
  Route::post('/permission-save','save')->name('-save');
  });

  // History Action - Lịch sử hoạt động
  Route::group([
    'as' => 'history-action',
    'controller' => HistoryActionController::class
  ],function () {
  Route::get('/history-action', 'show')->name('');
  Route::get('/history-action-data', 'data')->name('-data');
  Route::post('/history-action-get', 'get')->name('-get');
  Route::post('/history-action-save','save')->name('-save');
  Route::post('/history-action-delete','delete')->name('-delete');
  Route::any('/history-action-import', 'import')->name('-import');
  Route::get('/history-action-export', 'export' )->name('-export');
  Route::get('/history-action-DownloadExcel','DownloadExcel')->name('-DownloadExcel');
  });

  // Error - Lỗi 
  Route::group([
    'as' => 'error',
    'controller' => ErrorController::class
  ],function () {
  Route::get('/error','show')->name('');
  Route::get('/error-data', 'data')->name('-data');
  Route::post('/error-get', 'get')->name('-get');
  Route::post('/error-save', 'save')->name('-save');
  Route::post('/error-delete', 'delete' )->name('-delete');
  Route::any('/error-import', 'import' )->name('-import');
  Route::get('/error-export', 'export' )->name('-export');
  Route::get('/error-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

   // Jobs - Công việc
   Route::group([
    'as' => 'jobs',
    'controller' => JobsController::class
  ],function () {
  Route::get('/jobs','show')->name('');
  Route::get('/jobs-data', 'data')->name('-data');
  Route::post('/jobs-save', 'save')->name('-save');
  Route::post('/jobs-delete', 'delete' )->name('-delete');
  Route::any('/jobs-import', 'import' )->name('-import');
  Route::get('/jobs-export', 'export' )->name('-export');
  Route::get('/jobs-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

  // Menu - Danh mục
  Route::group([
    'as' => 'menu',
    'controller' => MenuController::class
  ],function () {
  Route::get('/menu', 'show')->name('');
  Route::get('/menu-data', 'data')->name('-data');
  Route::post('/menu-get', 'get')->name('-get');
  Route::post('/menu-save', 'save' )->name('-save');
  Route::post('/menu-delete', 'delete' )->name('-delete');
  Route::any('/menu-import','import')->name('-import');
  Route::get('/menu-export', 'export' )->name('-export');
  Route::get('/menu-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  });

  // Software - Phần mềm
  Route::group([
    'as' => 'software',
    'controller' => SoftwareController::class
  ],function () {
  Route::get('/software', 'show')->name('');
  //Route::post('/software-get', 'SoftwareController@get');
  Route::get('/software-data', 'data')->name('-data');
  Route::post('/software-save', 'save')->name('-save');
  Route::post('/software-delete','delete')->name('-delete');
  Route::any('/software-import', 'import' )->name('-import');
  Route::get('/software-export','export' )->name('-export');
  Route::get('/software-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  });

  // License - Giấy phép
  Route::group([
    'as' => 'license',
    'controller' => LicenseController::class
  ],function () {
  Route::get('/license', 'show' )->name('');
  //Route::post('/license-get', 'LicenseController@get');
  Route::get('/license-data', 'data' )->name('-data');
  Route::post('/license-save', 'save' )->name('-save');
  Route::post('/license-delete',  'delete' )->name('-delete');
  Route::any('/license-import', 'import' )->name('-import');
  Route::get('/license-export','export' )->name('-export');
  Route::get('/license-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  Route::post('/license-load',  'load' )->name('-load');
  });

  // System - Hệ thống
  Route::group([
    'as' => 'systems',
    'controller' => SystemsController::class
  ],function () {
  Route::get('/systems', 'show')->name('');
  Route::get('/systems-data', 'data')->name('-data');
  Route::post('/systems-get', 'get')->name('-get');
  Route::post('/systems-save', 'save' )->name('-save');
  Route::post('/systems-delete', 'delete')->name('-delete');
  Route::any('/systems-import', 'import')->name('-import');
  Route::get('/systems-export', 'export' )->name('-export');
  Route::get('/systems-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  });

  // GroupUsers - Nhóm người dùng
  Route::group([
    'as' => 'group-users',
    'controller' => GroupUsersController::class
  ],function () {
  Route::get('/group-users', 'show' )->name('');
  //Route::post('/country-get', 'CountryController@get');
  Route::get('/group-users-data', 'data')->name('-data');
  Route::post('/group-users-save', 'save')->name('-save');
  Route::post('/group-users-delete', 'delete' )->name('-delete');
  Route::any('/group-users-import', 'import' )->name('-import');
  Route::get('/group-users-export', 'export' )->name('-export');
  Route::get('/group-users-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

  // Country - Quốc gia
  Route::group([
    'as' => 'country',
    'controller' => CountryController::class
  ],function () {
  Route::get('/country','show' )->name('');
  //Route::post('/country-get', 'CountryController@get');
  Route::get('/country-data','data' )->name('-data');
  Route::post('/country-save','save' )->name('-save');
  Route::post('/country-delete', 'delete')->name('-delete');
  Route::any('/country-import', 'import')->name('-import');
  Route::get('/country-export', 'export')->name('-export');
  Route::get('/country-DownloadExcel','DownloadExcel')->name('-DownloadExcel');
  });

  // Regions - Vùng miền
  Route::group([
    'as' => 'regions',
    'controller' => RegionsController::class
  ],function () {
  Route::get('/regions', 'show' )->name('');
  //Route::post('/regions-get', 'RegionsController@get');
  Route::get('/regions-data', 'data')->name('-data');
  Route::post('/regions-save', 'save')->name('-save');
  Route::post('/regions-delete', 'delete')->name('-delete');
  Route::any('/regions-import', 'import' )->name('-import');
  Route::get('/regions-export', 'export' )->name('-export');
  Route::get('/regions-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

  // Key Ai - Khóa AI
  Route::group([
    'as' => 'key-ai',
    'controller' => KeyAiController::class
  ],function () {
  Route::get('/key-ai', 'show' )->name('');
  //Route::post('/area-get', 'AreaController@get');
  Route::get('/key-ai-data', 'data')->name('-data');
  Route::post('/key-ai-save', 'save')->name('-save');
  Route::post('/key-ai-delete', 'delete' )->name('-delete');
  Route::any('/key-ai-import', 'import')->name('-import');
  Route::get('/key-ai-export','export' )->name('-export');
  Route::get('/key-ai-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

  // Area - Khu vực
  Route::group([
    'as' => 'area',
    'controller' => AreaController::class
  ],function () {
  Route::get('/area', 'show' )->name('');
  Route::get('/area-data', 'data')->name('-data');
  //Route::post('/area-get', 'AreaController@get');
  Route::post('/area-save', 'save')->name('-save');
  Route::post('/area-delete', 'delete')->name('-delete');
  Route::any('/area-import','import')->name('-import');
  Route::get('/area-export','export')->name('-export');
  Route::get('/area-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

  // Distric - Quận huyện
  Route::group([
    'as' => 'distric',
    'controller' => DistricController::class
  ],function () {
  Route::get('/distric', 'show')->name('');
  //Route::post('/distric-get', 'DistricController@get');
  Route::get('/distric-data', 'data')->name('-data');
  Route::post('/distric-save', 'save')->name('-save');
  Route::post('/distric-delete', 'delete')->name('-delete');
  Route::any('/distric-import', 'import' )->name('-import');
  Route::get('/distric-export',  'export')->name('-export');
  Route::get('/distric-DownloadExcel',  'DownloadExcel' )->name('-DownloadExcel');
  });

  // Document Type - Loại tài liệu
  Route::group([
    'as' => 'document-type',
    'controller' => DocumentTypeController::class
  ],function () {
  Route::get('/document-type','show')->name('');
  Route::get('/document-type-data', 'data')->name('-data');
  Route::post('/document-type-save', 'save')->name('-save');
  Route::post('/document-type-delete','delete' )->name('-delete');
  Route::any('/document-type-import', 'import' )->name('-import');
  Route::get('/document-type-export', 'export' )->name('-export');
  Route::get('/document-type-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

  // Document - Tài liệu
  Route::group([
    'as' => 'document',
    'controller' => DocumentController::class
  ],function () {
  Route::get('/document', 'show' )->name('');
  Route::get('/document-data', 'data')->name('-data');
  Route::post('/document-save', 'save')->name('-save');
  Route::post('/document-delete', 'delete' )->name('-delete');
  Route::any('/document-import', 'import' )->name('-import');
  Route::get('/document-export',  'export' )->name('-export');
  Route::get('/document-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

  // Company - Công ty
  Route::group([
    'as' => 'company',
    'controller' => CompanyController::class
  ],function () {
  Route::get('/company', 'show')->name('');
  //Route::post('/company-get', 'CompanyController@get');
  Route::get('/company-data', 'data')->name('-data');
  Route::post('/company-save', 'save')->name('-save');
  Route::post('/company-delete','delete' )->name('-delete');
  Route::any('/company-import', 'import' )->name('-import');
  Route::get('/company-export', 'export' )->name('-export');
  Route::get('/company-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

  // Company Software - Phần mềm công ty
  Route::group([
    'as' => 'company-software',
    'controller' => CompanySoftwareController::class
  ],function () {
  Route::get('/company-software', 'show')->name('');
  Route::get('/company-software-data', 'data')->name('-data');
  Route::post('/company-software-get', 'get')->name('-get');
  Route::post('/company-software-save', 'save' )->name('-save');
  Route::post('/company-software-delete', 'delete' )->name('-delete');
  Route::any('/company-software-import', 'import' )->name('-import');
  Route::get('/company-software-export', 'export' )->name('-export');
  Route::get('/company-software-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

  // Users - Người dùng
  Route::group([
    'as' => 'users',
    'controller' => UserManagerController::class
  ],function () {
  Route::get('/users', 'show' )->name('');
  Route::get('/users-data', 'data' )->name('-data');
  Route::post('/users-save', 'save' )->name('-save');
  Route::post('/users-delete','delete')->name('-delete');
  Route::any('/users-import', 'import' )->name('-import');
  Route::get('/users-export', 'export' )->name('-export');
  Route::get('/users-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  });

});
Route::group([
  'prefix'=>'acc',
  'as' => 'acc.'
],function () {
  Route::controller(AccHomeController::class)->group(function () {
  Route::get('/index', 'show' );
  Route::get('/', 'show')->name('index');
  Route::get('/profile','profile' )->name('profile');
  });

  Route::controller(HomeController::class)->group(function () {
  Route::get('login', 'login' )->name('login');
  });

  Route::controller(AccUserController::class)->group(function () {
  Route::post('/load-history-action', 'loadHistoryAction' );
  });

  Route::controller(UserController::class)->group(function () {
  Route::post('login', 'doLogin' );
  Route::post('/avatar-profile', 'updateAvatar' );
  Route::post('/change-password', 'changePassword' );
  });

  Route::controller(ChatTimelineController::class)->group(function () {
  Route::post('timeline', 'timeline' );
  Route::post('view-more-timeline', 'viewMore' );
  Route::post('load-chat-user', 'loadChatUser' );
  Route::post('chat', 'doChat' );
  });

  Route::controller(ChatBotAiController::class)->group(function () {
  Route::post('chat-ai', 'doChatBotAI' );
  });

  // Permission - Quyền
  Route::group([
    'as' => 'permission',
    'controller' => PermissionController::class
  ],function () {
  Route::get('/permission', 'show')->name('');
  Route::post('/permission-load', 'load')->name('-load');
  Route::post('/permission-group', 'group' )->name('-group');
  Route::post('/permission-save', 'save' )->name('-save');
  });

  // System - Hệ thống
  Route::group([
    'as' => 'systems',
    'controller' => AccSystemsController::class
  ],function () {
  Route::get('/systems','show' )->name('');
  Route::get('/systems-data','data')->name('-data');
  Route::post('/systems-get','get')->name('-get');
  Route::post('/systems-save', 'save' )->name('-save');
  Route::post('/systems-delete', 'delete' )->name('-delete');
  Route::any('/systems-import', 'import')->name('-import');
  Route::get('/systems-export', 'export')->name('-export');
  Route::get('/systems-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::post('/systems-change-database', 'ChangeDatabase' )->name('-change-database');
  });

  // Number Voucher - Số chứng từ
  Route::group([
    'as' => 'number-voucher',
    'controller' => AccNumberVoucherController::class
  ],function () {
  Route::get('/number-voucher', 'show')->name('');
  Route::get('/number-voucher-data', 'data')->name('-data');
  Route::post('/number-voucher-get', 'get')->name('-get');
  Route::post('/number-voucher-save', 'save')->name('-save');
  Route::post('/number-voucher-delete', 'delete')->name('-delete');
  Route::any('/number-voucher-import', 'import')->name('-import');
  Route::get('/number-voucher-export', 'export')->name('-export');
  Route::get('/number-voucher-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/number-voucher-change-database', 'ChangeDatabase')->name('-change-database');
  });

    // Number Voucher Format - Định dạng số chứng từ
    Route::group([
      'as' => 'count-voucher',
      'controller' => AccCountVoucherController::class
    ],function () {
    Route::get('/count-voucher', 'show')->name('');
    Route::get('/count-voucher-data', 'data')->name('-data');
    Route::post('/count-voucher-get', 'get')->name('-get');
    Route::post('/count-voucher-save', 'save')->name('-save');
    Route::post('/count-voucher-delete', 'delete')->name('-delete');
    Route::any('/count-voucher-import', 'import')->name('-import');
    Route::get('/count-voucher-export', 'export')->name('-export');
    Route::get('/count-voucher-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
    Route::post('/count-voucher-change-database', 'ChangeDatabase')->name('-change-database');
    });

  // Number Code -  Mã số
  Route::group([
    'as' => 'number-code',
    'controller' => AccNumberCodeController::class
  ],function () {
  Route::get('/number-code', 'show')->name('');
  Route::get('/number-code-data', 'data' )->name('-data');
  Route::post('/number-code-get', 'get' )->name('-get');
  Route::post('/number-code-save', 'save' )->name('-save');
  Route::post('/number-code-delete',  'delete' )->name('-delete');
  Route::any('/number-code-import',  'import' )->name('-import');
  Route::get('/number-code-export',  'export' )->name('-export');
  Route::get('/number-code-DownloadExcel', 'DownloadExcel'  )->name('-DownloadExcel');
  Route::post('/number-code-change-database',  'ChangeDatabase' )->name('-change-database');
  });

  // Setting Voucher - Cài đặt bút toán
  Route::group([
    'as' => 'setting-voucher',
    'controller' => AccSettingVoucherController::class
  ],function () {
  Route::get('/setting-voucher', 'show' )->name('');
  Route::get('/setting-voucher-data', 'data' )->name('-data');
  Route::post('/setting-voucher-get', 'get' )->name('-get');
  Route::post('/setting-voucher-save', 'save' )->name('-save');
  Route::post('/setting-voucher-delete', 'delete' )->name('-delete');
  Route::any('/setting-voucher-import', 'import' )->name('-import');
  Route::get('/setting-voucher-export', 'export' )->name('-export');
  Route::get('/setting-voucher-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  Route::post('/setting-voucher-change-database',  'ChangeDatabase' )->name('-change-database');
  });

  // Excise Tax - Thuế tiêu thụ đặc biệt
  Route::group([
    'as' => 'excise',
    'controller' => AccExciseController::class
  ],function () {
  Route::get('/excise','show')->name('');
  Route::get('/excise-data',  'data')->name('-data');
  Route::post('/excise-load', 'load')->name('-load');
  Route::post('/excise-get', 'get' )->name('-get');
  Route::post('/excise-save', 'save' )->name('-save');
  Route::post('/excise-delete', 'delete' )->name('-delete');
  Route::any('/excise-import', 'import' )->name('-import');
  Route::get('/excise-export', 'export' )->name('-export');
  Route::get('/excise-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::post('/excise-change-database', 'ChangeDatabase' )->name('-change-database');
  });

  // Natural Resources Tax - Thuế tài nguyên
  Route::group([
    'as' => 'natural-resources',
    'controller' => AccNaturalResourcesController::class
  ],function () {
  Route::get('/natural-resources','show' )->name('');
  Route::get('/natural-resources-data', 'data' )->name('-data');
  Route::post('/natural-resources-load', 'load' )->name('-load');
  Route::post('/natural-resources-get', 'get' )->name('-get');
  Route::post('/natural-resources-save', 'save' )->name('-save');
  Route::post('/natural-resources-delete', 'delete' )->name('-delete');
  Route::any('/natural-resources-import', 'import' )->name('-import');
  Route::get('/natural-resources-export', 'export' )->name('-export');
  Route::get('/natural-resources-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::post('/natural-resources-change-database', 'ChangeDatabase' )->name('-change-database');
  });

  // Vat - Thuế giá trị gia tăng
  Route::group([
    'as' => 'vat',
    'controller' => AccVatController::class
  ],function () {
  Route::get('/vat',  'show' )->name('');
  Route::get('/vat-data',  'data')->name('-data');
  Route::post('/vat-load',  'load')->name('-load');
  Route::post('/vat-get', 'get')->name('-get');
  Route::post('/vat-save', 'save')->name('-save');
  Route::post('/vat-delete', 'delete')->name('-delete');
  Route::any('/vat-import', 'import')->name('-import');
  Route::get('/vat-export', 'export')->name('-export');
  Route::get('/vat-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/vat-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Bank - Ngân hàng
  Route::group([
    'as' => 'bank',
    'controller' => AccBankController::class
  ],function () {
  Route::get('/bank', 'show')->name('');
  Route::get('/bank-data', 'data')->name('-data');
  Route::post('/bank-load', 'load')->name('-load');
  Route::post('/bank-get', 'get')->name('-get');
  Route::post('/bank-save', 'save')->name('-save');
  Route::post('/bank-delete', 'delete')->name('-delete');
  Route::any('/bank-import', 'import')->name('-import');
  Route::get('/bank-export', 'export')->name('-export');
  Route::get('/bank-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/bank-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Bank Account - Tài khoản ngân hàng
  Route::group([
    'as' => 'bank-account',
    'controller' => AccBankAccountController::class
  ],function () {
  Route::get('/bank-account', 'show' )->name('');
  Route::get('/bank-account-data', 'data')->name('-data');
  Route::post('/bank-account-get', 'get')->name('-get');
  Route::post('/bank-account-save','save')->name('-save');
  Route::post('/bank-account-delete','delete' )->name('-delete');
  Route::any('/bank-account-import', 'import' )->name('-import');
  Route::get('/bank-account-export', 'export')->name('-export');
  Route::get('/bank-account-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/bank-account-change-database','ChangeDatabase')->name('-change-database');
  });

  // Case Code - Mã trường hợp
  Route::group([
    'as' => 'case-code',
    'controller' => AccCaseCodeController::class
  ],function () {
  Route::get('/case-code', 'show' )->name('');
  Route::get('/case-code-data', 'data')->name('-data');
  Route::post('/case-code-load', 'load')->name('-load');
  Route::post('/case-code-get', 'get')->name('-get');
  Route::post('/case-code-save', 'save')->name('-save');
  Route::post('/case-code-delete','delete')->name('-delete');
  Route::any('/case-code-import', 'import')->name('-import');
  Route::get('/case-code-export', 'export')->name('-export');
  Route::get('/case-code-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/case-code-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Cost Code - Mã chi phí
  Route::group([
    'as' => 'cost-code',
    'controller' => AccCostCodeController::class
  ],function () {
  Route::get('/cost-code', 'show' )->name('');
  Route::get('/cost-code-data','data' )->name('-data');
  Route::post('/cost-code-load','load' )->name('-load');
  Route::post('/cost-code-get','get' )->name('-get');
  Route::post('/cost-code-save', 'save')->name('-save');
  Route::post('/cost-code-delete', 'delete')->name('-delete');
  Route::any('/cost-code-import', 'import')->name('-import');
  Route::get('/cost-code-export', 'export')->name('-export');
  Route::get('/cost-code-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/cost-code-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Statistical Code - Mã thống kê
  Route::group([
    'as' => 'statistical-code',
    'controller' => AccStatisticalCodeController::class
  ],function () {
  Route::get('/statistical-code', 'show' )->name('');
  Route::get('/statistical-code-data',  'data')->name('-data');
  Route::post('/statistical-code-load',  'load')->name('-load');
  Route::post('/statistical-code-get',  'get')->name('-get');
  Route::post('/statistical-code-save',  'save')->name('-save');
  Route::post('/statistical-code-delete', 'delete')->name('-delete');
  Route::any('/statistical-code-import',  'import')->name('-import');
  Route::get('/statistical-code-export', 'export')->name('-export');
  Route::get('/statistical-code-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::post('/statistical-code-change-database',  'ChangeDatabase')->name('-change-database');
  });

  // Work Code - Mã công việc
  Route::group([
    'as' => 'work-code',
    'controller' => AccWorkCodeController::class
  ],function () {
  Route::get('/work-code', 'show' )->name('');
  Route::get('/work-code-data', 'data' )->name('-data');
  Route::post('/work-code-load', 'load' )->name('-load');
  Route::post('/work-code-get', 'get' )->name('-get');
  Route::post('/work-code-save', 'save' )->name('-save');
  Route::post('/work-code-delete', 'delete')->name('-delete');
  Route::any('/work-code-import', 'import' )->name('-import');
  Route::get('/work-code-export', 'export' )->name('-export');
  Route::get('/work-code-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::post('/work-code-change-database', 'ChangeDatabase' )->name('-change-database');
  });

  // Revenue Expenditure Type - Loại thu chi
  Route::group([
    'as' => 'revenue-expenditure-type',
    'controller' => AccRevenueExpenditureTypeController::class
  ],function () {
  Route::get('/revenue-expenditure-type', 'show' )->name('');
  Route::get('/revenue-expenditure-type-data','data' )->name('-data');
  Route::post('/revenue-expenditure-type-load','load' )->name('-load');
  Route::post('/revenue-expenditure-type-get','get' )->name('-get');
  Route::post('/revenue-expenditure-type-save', 'save')->name('-save');
  Route::post('/revenue-expenditure-type-delete', 'delete')->name('-delete');
  Route::any('/revenue-expenditure-type-import', 'import')->name('-import');
  Route::get('/revenue-expenditure-type-export', 'export')->name('-export');
  Route::get('/revenue-expenditure-type-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/revenue-expenditure-type-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Revenue Expenditure - Thu chi
  Route::group([
    'as' => 'revenue-expenditure',
    'controller' => AccRevenueExpenditureController::class
  ],function () {
  Route::get('/revenue-expenditure', 'show')->name('');
  Route::get('/revenue-expenditure-data', 'data')->name('-data');
  Route::post('/revenue-expenditure-load', 'load')->name('-load');
  Route::post('/revenue-expenditure-get', 'get')->name('-get');
  Route::post('/revenue-expenditure-save', 'save')->name('-save');
  Route::post('/revenue-expenditure-delete', 'delete')->name('-delete');
  Route::any('/revenue-expenditure-import', 'import')->name('-import');
  Route::get('/revenue-expenditure-export', 'export')->name('-export');
  Route::get('/revenue-expenditure-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/revenue-expenditure-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Unit - Đơn vị
  Route::group([
    'as' => 'unit',
    'controller' => AccUnitController::class
  ],function () {
  Route::get('/unit',  'show')->name('');
  Route::get('/unit-data', 'data')->name('-data');
  Route::post('/unit-load', 'load')->name('-load');
  Route::post('/unit-get', 'get')->name('-get');
  Route::post('/unit-save', 'save')->name('-save');
  Route::post('/unit-delete', 'delete')->name('-delete');
  Route::any('/unit-import', 'import')->name('-import');
  Route::get('/unit-export', 'export')->name('-export');
  Route::get('/unit-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/unit-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Warranty Period - Thời gian bảo hành
  Route::group([
    'as' => 'warranty-period',
    'controller' => AccWarrantyPeriodController::class
  ],function () {
  Route::get('/warranty-period', 'show')->name('');
  Route::get('/warranty-period-data', 'data')->name('-data');
  Route::post('/warranty-period-load', 'load')->name('-load');
  Route::post('/warranty-period-get', 'get')->name('-get');
  Route::post('/warranty-period-save', 'save')->name('-save');
  Route::post('/warranty-period-delete', 'delete')->name('-delete');
  Route::any('/warranty-period-import', 'import')->name('-import');
  Route::get('/warranty-period-export', 'export')->name('-export');
  Route::get('/warranty-period-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/warranty-period-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Stock - Kho
  Route::group([
    'as' => 'stock',
    'controller' => AccStockController::class
  ],function () {
  Route::get('/stock', 'show')->name('');
  Route::get('/stock-data','data')->name('-data');
  Route::post('/stock-load','load')->name('-load');
  Route::post('/stock-get','get')->name('-get');
  Route::post('/stock-save', 'save')->name('-save');
  Route::post('/stock-delete','delete')->name('-delete');
  Route::any('/stock-import','import')->name('-import');
  Route::get('/stock-export','export')->name('-export');
  Route::get('/stock-DownloadExcel','DownloadExcel')->name('-DownloadExcel');
  Route::post('/stock-change-database','ChangeDatabase')->name('-change-database');
  });

  // Supplies Goods Type - Quản lý loại hàng hóa
  Route::group([
    'as' => 'supplies-goods-type',
    'controller' => AccSuppliesGoodsTypeController::class
  ],function () {
  Route::get('/supplies-goods-type', 'show')->name('');
  Route::get('/supplies-goods-type-data', 'data')->name('-data');
  Route::post('/supplies-goods-type-load', 'load')->name('-load');
  Route::post('/supplies-goods-type-get', 'get')->name('-get');
  Route::post('/supplies-goods-type-save', 'save')->name('-save');
  Route::post('/supplies-goods-type-delete', 'delete')->name('-delete');
  Route::any('/supplies-goods-type-import', 'import')->name('-import');
  Route::get('/supplies-goods-type-export', 'export')->name('-export');
  Route::get('/supplies-goods-type-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/supplies-goods-type-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Supplies Goods - Quản lý hàng hóa
  Route::group([
    'as' => 'supplies-goods',
    'controller' => AccSuppliesGoodsController::class
  ],function () {
  Route::get('/supplies-goods', 'show')->name('');
  Route::get('/supplies-goods-data', 'data')->name('-data');
  Route::post('/supplies-goods-load', 'load')->name('-load');
  Route::post('/supplies-goods-load-change', 'load_change')->name('-load-change');
  Route::post('/supplies-goods-get', 'get')->name('-get');
  Route::post('/supplies-goods-save', 'save')->name('-save');
  Route::post('/supplies-goods-delete','delete')->name('-delete');
  Route::any('/supplies-goods-import', 'import')->name('-import');
  Route::get('/supplies-goods-export', 'export')->name('-export');
  Route::get('/supplies-goods-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/supplies-goods-change-database','ChangeDatabase')->name('-change-database');
  });

  // Supplies Goods Group - Quản lý nhóm hàng hóa
  Route::group([
    'as' => 'supplies-goods-group',
    'controller' => AccSuppliesGoodsGroupController::class
  ],function () {
  Route::get('/supplies-goods-group', 'show' )->name('');
  Route::get('/supplies-goods-group-data','data')->name('-data');
  Route::post('/supplies-goods-group-load','load')->name('-load');
  Route::post('/supplies-goods-group-get', 'get' )->name('-get');
  Route::post('/supplies-goods-group-save', 'save')->name('-save');
  Route::post('/supplies-goods-group-delete', 'delete')->name('-delete');
  Route::any('/supplies-goods-group-import', 'import')->name('-import');
  Route::get('/supplies-goods-group-export', 'export')->name('-export');
  Route::get('/supplies-goods-group-DownloadExcel','DownloadExcel')->name('-DownloadExcel');
  Route::post('/supplies-goods-group-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Object Type - Quản lý loại đối tượng
  Route::group([
    'as' => 'object-type',
    'controller' => AccObjectTypeController::class
  ],function () {
  Route::get('/object-type', 'show' )->name('');
  Route::get('/object-type-data',  'data' )->name('-data');
  Route::post('/object-type-load',  'load' )->name('-load');
  Route::post('/object-type-get',  'get' )->name('-get');
  Route::post('/object-type-save',  'save' )->name('-save');
  Route::post('/object-type-delete',  'delete')->name('-delete');
  Route::any('/object-type-import',  'import' )->name('-import');
  Route::get('/object-type-export', 'export' )->name('-export');
  Route::get('/object-type-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  Route::post('/object-type-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Object Group - Quản lý nhóm đối tượng
  Route::group([
    'as' => 'object-group',
    'controller' => AccObjectGroupController::class
  ],function () {
  Route::get('/object-group', 'show')->name('');
  Route::get('/object-group-data', 'data')->name('-data');
  Route::post('/object-group-load', 'load')->name('-load');
  Route::post('/object-group-get', 'get')->name('-get');
  Route::post('/object-group-save', 'save')->name('-save');
  Route::post('/object-group-delete', 'delete')->name('-delete');
  Route::any('/object-group-import', 'import')->name('-import');
  Route::get('/object-group-export', 'export')->name('-export');
  Route::get('/object-group-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/object-group-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Department - Quản lý phòng ban
  Route::group([
    'as' => 'department',
    'controller' => AccDepartmentController::class
  ],function () {
  Route::get('/department', 'show')->name('');
  Route::get('/department-data','data')->name('-data');
  Route::post('/department-load','load')->name('-load');
  Route::post('/department-get', 'get')->name('-get');
  Route::post('/department-save', 'save')->name('-save');
  Route::post('/department-delete', 'delete')->name('-delete');
  Route::any('/department-import', 'import')->name('-import');
  Route::get('/department-export', 'export')->name('-export');
  Route::get('/department-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/department-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Object - Quản lý đối tượng
  Route::group([
    'as' => 'object',
    'controller' => AccObjectController::class
  ],function () {
  Route::get('/object', 'show')->name('');
  Route::get('/object-data', 'data')->name('-data');
  Route::post('/object-load', 'load')->name('-load');
  Route::post('/object-load-change', 'load_change')->name('-load-change');
  Route::post('/object-get', 'get')->name('-get');
  Route::post('/object-save', 'save')->name('-save');
  Route::post('/object-delete', 'delete')->name('-delete');
  Route::any('/object-import', 'import')->name('-import');
  Route::get('/object-export', 'export')->name('-export');
  Route::get('/object-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/object-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Currency - Quản lý loại tiền tệ
  Route::group([
    'as' => 'currency',
    'controller' => AccCurrencyController::class
  ],function () {
  Route::get('/currency','show' )->name('');
  Route::get('/currency-data', 'data' )->name('-data');
  Route::post('/currency-load', 'load' )->name('-load');
  Route::post('/currency-get', 'get' )->name('-get');
  Route::post('/currency-save', 'save' )->name('-save');
  Route::post('/currency-delete', 'delete' )->name('-delete');
  Route::any('/currency-import', 'import' )->name('-import');
  Route::get('/currency-export', 'export' )->name('-export');
  Route::get('/currency-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::post('/currency-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // GroupUsers - Quản lý nhóm người dùng
  Route::group([
    'as' => 'group-users',
    'controller' => AccGroupUsersController::class
  ],function () {
  Route::get('/group-users','show')->name('');
  Route::get('/group-users-data', 'data' )->name('-data');
  Route::post('/group-users-save', 'save')->name('-save');
  Route::post('/group-users-delete', 'delete')->name('-delete');
  Route::any('/group-users-import', 'import')->name('-import');
  Route::get('/group-users-export', 'export')->name('-export');
  Route::get('/group-users-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  });

  // Users - Quản lý người dùng
  Route::group([
    'as' => 'users',
    'controller' => AccUserManagerController::class
  ],function () {
  Route::get('/users', 'show')->name('');
  Route::get('/users-data', 'data')->name('-data');
  Route::post('/users-save', 'save')->name('-save');
  Route::post('/users-delete', 'delete')->name('-delete');
  Route::any('/users-import', 'import')->name('-import');
  Route::get('/users-export', 'export')->name('-export');
  Route::get('/users-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  });

  // System - Hệ thông
  Route::group([
    'as' => 'account-systems',
    'controller' => AccAccountSystemsController::class
  ],function () {
  Route::get('/account-systems', 'show')->name('');
  Route::get('/account-systems-data', 'data' )->name('-data');
  Route::post('/account-systems-get', 'get' )->name('-get');
  Route::post('/account-systems-save', 'save' )->name('-save');
  Route::post('/account-systems-delete', 'delete' )->name('-delete');
  Route::any('/account-systems-import','import' )->name('-import');
  Route::get('/account-systems-export', 'export')->name('-export');
  Route::get('/account-systems-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::post('/account-systems-change-database', 'ChangeDatabase')->name('-change-database');
  });

    // Account transfer - Kết chuyển tài khoản
  Route::group([
    'as' => 'account-transfer',
    'controller' => AccAccountTransferController::class
  ],function () {
  Route::get('/account-transfer', 'show')->name('');
  Route::get('/account-transfer-data', 'data')->name('-data');
  Route::post('/account-transfer-load', 'load')->name('-load');
  Route::post('/account-transfer-get', 'get')->name('-get');
  Route::post('/account-transfer-save', 'save' )->name('-save');
  Route::post('/account-transfer-delete','delete')->name('-delete');
  Route::any('/account-transfer-import', 'import')->name('-import');
  Route::get('/account-transfer-export', 'export' )->name('-export');
  Route::get('/account-transfer-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  Route::post('/account-transfer-change-database','ChangeDatabase' )->name('-change-database');
  });

  // Account Fast - Tài khoản nhanh
  Route::group([
    'as' => 'accounted-fast',
    'controller' => AccAccountedFastController::class
  ],function () {
  Route::get('/accounted-fast', 'show')->name('');
  Route::get('/accounted-fast-data', 'data')->name('-data');
  Route::post('/accounted-fast-load', 'load')->name('-load');
  Route::post('/accounted-fast-get', 'get')->name('-get');
  Route::post('/accounted-fast-save', 'save' )->name('-save');
  Route::post('/accounted-fast-delete','delete')->name('-delete');
  Route::any('/accounted-fast-import', 'import')->name('-import');
  Route::get('/accounted-fast-export', 'export' )->name('-export');
  Route::get('/accounted-fast-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  Route::post('/accounted-fast-change-database','ChangeDatabase' )->name('-change-database');
  });

  // Account Auto - Tài khoản tự động
  Route::group([
    'as' => 'accounted-auto',
    'controller' => AccAccountedAutoController::class
  ],function () {
  Route::get('/accounted-auto', 'show')->name('');
  Route::get('/accounted-auto-data', 'data')->name('-data');
  Route::post('/accounted-auto-load', 'load')->name('-load');
  Route::post('/accounted-auto-get', 'get')->name('-get');
  Route::post('/accounted-auto-save','save' )->name('-save');
  Route::post('/accounted-auto-delete','delete')->name('-delete');
  Route::any('/accounted-auto-import', 'import')->name('-import');
  Route::get('/accounted-auto-export','export' )->name('-export');
  Route::get('/accounted-auto-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  Route::post('/accounted-auto-change-database','ChangeDatabase' )->name('-change-database');
  });

  // Print Template - Mẫu in
  Route::group([
    'as' => 'print-template',
    'controller' => AccPrintTemplateController::class
  ],function () {
  Route::get('/print-template', 'show')->name('');
  Route::get('/print-template-data', 'data')->name('-data');
  Route::post('/print-template-load', 'load')->name('-load');
  Route::post('/print-template-get', 'get')->name('-get');
  Route::post('/print-template-save','save' )->name('-save');
  Route::post('/print-template-delete','delete')->name('-delete');
  Route::any('/print-template-import', 'import')->name('-import');
  Route::get('/print-template-export','export' )->name('-export');
  Route::get('/print-template-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  Route::post('/print-template-change-database','ChangeDatabase' )->name('-change-database');
  });

  // Setting Account Group - Cài đặt nhóm tài khoản
  Route::group([
    'as' => 'setting-account-group',
    'controller' => AccSettingAccountGroupController::class
  ],function () {
  Route::get('/setting-account-group', 'show')->name('');
  Route::get('/setting-account-group-data','data' )->name('-data');
  Route::post('/setting-account-group-get','get' )->name('-get');
  Route::post('/setting-account-group-save','save' )->name('-save');
  Route::post('/setting-account-group-delete','delete' )->name('-delete');
  Route::any('/setting-account-group-import','import' )->name('-import');
  Route::get('/setting-account-group-export','export' )->name('-export');
  Route::get('/setting-account-group-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  Route::post('/setting-account-group-change-database','ChangeDatabase' )->name('-change-database');
  });

  // AccountType - Loại tài khoản
  Route::group([
    'as' => 'account-type',
    'controller' => AccAccountTypeController::class
  ],function () {
  Route::get('/account-type', 'show')->name('');
  Route::get('/account-type-data', 'data')->name('-data');
  Route::post('/account-type-load', 'load')->name('-load');
  Route::post('/account-type-get', 'get')->name('-get');
  Route::post('/account-type-save','save' )->name('-save');
  Route::post('/account-type-delete','delete')->name('-delete');
  Route::any('/account-type-import', 'import')->name('-import');
  Route::get('/account-type-export','export' )->name('-export');
  Route::get('/account-type-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
  Route::post('/account-type-change-database','ChangeDatabase' )->name('-change-database');
  });

  // Account Nature - Bản chất tài khoản
  Route::group([
    'as' => 'account-nature',
    'controller' => AccAccountNatureController::class
  ],function () {
    Route::get('/account-nature', 'show')->name('');
    Route::get('/account-nature-data', 'data')->name('-data');
    Route::post('/account-nature-load', 'load')->name('-load');
    Route::post('/account-nature-get', 'get')->name('-get');
    Route::post('/account-nature-save','save' )->name('-save');
    Route::post('/account-nature-delete','delete')->name('-delete');
    Route::any('/account-nature-import', 'import')->name('-import');
    Route::get('/account-nature-export','export' )->name('-export');
    Route::get('/account-nature-DownloadExcel','DownloadExcel' )->name('-DownloadExcel');
    Route::post('/account-nature-change-database','ChangeDatabase' )->name('-change-database');
  });

  // Period - Kỳ kế toán
  Route::group([
    'as' => 'period',
    'controller' => AccPeriodController::class
  ],function () {
  Route::get('/period', 'show' )->name('');
  //Route::post('/period-load',[AccPeriodController::class,'load']);
  Route::get('/period-data','data')->name('-data');
  Route::post('/period-get','get')->name('-get');
  Route::post('/period-save','save' )->name('-save');
  Route::post('/period-save-detail','saveDetail' )->name('-save-detail');
  Route::post('/period-delete','delete')->name('-delete');
  //Route::any('/period-import',[AccPeriodController::class,'import']);
  //Route::get('/period-export',[AccPeriodController::class,'export'] );
  //Route::get('/period-DownloadExcel',[AccPeriodController::class,'DownloadExcel'] );
  Route::post('/period-change-database','ChangeDatabase')->name('-change-database');
  });

  // Receipt Cash General - Phiếu thu tiền
  Route::controller(AccGeneralController::class)->group(function () {




 
 

  // In Phiếu trang thu tiền theo hóa đơn 
  });

  // Receipt Cash General - Tổng hợp thu tiền
  Route::group([
    'as' => 'cash-receipts-general',
    'controller' => AccCashReceiptsGeneralController::class
  ],function () {

    Route::controller(AccCashReceiptsGeneralController::class)->group(function () { 
    Route::get('/cash-receipts-general', 'show' )->name('');
    Route::post('/cash-receipts-general-get','find' )->name('-find');
    Route::post('/cash-receipts-general-unwrite','unwrite' )->name('-unwrite');
    Route::post('/cash-receipts-general-write','write' )->name('-write');
    Route::post('/cash-receipts-general-revoucher', 'revoucher' )->name('-revoucher');
    Route::post('/cash-receipts-general-start-voucher', 'start_voucher' )->name('-start-voucher');
    Route::post('/cash-receipts-general-change-voucher', 'change_voucher' )->name('-change-voucher');
    Route::get('/cash-receipts-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/cash-receipts-general-import', 'import')->name('-import');
    Route::any('/cash-receipts-general-delete', 'delete')->name('-delete');
    });  

    Route::controller(AccGeneralController::class)->group(function () { 
    Route::post('/cash-receipts-general-detail','detail');  
        // In Phiếu trang tổng hợp
    Route::post('/cash-receipts-general-print','prints');  
    });
   });

  // Receipt Cash Voucher - Phiếu thu tiền
  Route::group([
    'as' => 'cash-receipts-voucher'
  ],function () {

    Route::controller(AccCashReceiptsVoucherController::class)->group(function () { 
    Route::get('/cash-receipts-voucher', 'show' )->name('');
    Route::post('/cash-receipts-voucher-save', 'save' )->name('-save');
    Route::post('/cash-receipts-voucher-bind', 'bind' )->name('-bind');
    Route::get('/cash-receipts-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/cash-receipts-voucher-import', 'import')->name('-import');
    });

    Route::controller(AccCashReceiptsGeneralController::class)->group(function () {  
    // Ghi , ko ghi , tìm chứng từ trang thu tiền
    Route::post('/cash-receipts-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/cash-receipts-voucher-write','write' )->name('-write');
    Route::post('/cash-receipts-voucher-find', 'find' )->name('-find');
    Route::post('/cash-receipts-voucher-delete', 'delete' )->name('-delete'); 
    });

    Route::controller(AccVoucherController::class)->group(function () { 
    Route::post('/cash-receipts-voucher-get', 'get' )->name('-get');
    Route::post('/cash-receipts-voucher-auto', 'auto' )->name('-auto');
    Route::post('/cash-receipts-voucher-ai', 'ai' )->name('-ai');
    Route::post('/cash-receipts-voucher-currency', 'currency' )->name('-currency');
    Route::post('/cash-receipts-voucher-reference', 'reference' )->name('-reference');
    Route::post('/cash-receipts-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/cash-receipts-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
      // Kiểm tra mst doanh nghiệp
    Route::post('/cash-receipts-voucher-check-subject', 'check_subject' )->name('-check-subject');
      // Xóa đính kèm
    Route::post('/cash-receipts-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
    });

    Route::controller(AccGeneralController::class)->group(function () {
      // In Phiếu trang chi tiết
    Route::post('/cash-receipts-voucher-print','prints');  
    });
  });  

  // Receipt Cash Detail By Invoice - Phiếu thu tiền theo hóa đơn
  Route::group([
    'as' => 'cash-receipts-voucher-by-invoice'
  ],function () {
    Route::controller(AccCashReceiptsVoucherByInvoiceController::class)->group(function () {
    Route::get('/cash-receipts-voucher-by-invoice', 'show' )->name('');
    Route::post('/cash-receipts-voucher-by-invoice-get-data', 'get_data' )->name('-get-data');
    Route::post('/cash-receipts-voucher-by-invoice-save', 'save' )->name('-save');
    Route::post('/cash-receipts-voucher-by-invoice-bind', 'bind' )->name('-bind');
    });

    Route::controller(AccCashReceiptsGeneralController::class)->group(function () {
    // Ghi , ko ghi , tìm chứng từ trang thu tiền theo hóa đơn
    Route::post('/cash-receipts-voucher-by-invoice-unwrite','unwrite' )->name('-by-invoice-unwrite');
    Route::post('/cash-receipts-voucher-by-invoice-write','write' )->name('-by-invoice-write');
    Route::post('/cash-receipts-voucher-by-invoice-find', 'find' )->name('-by-invoice-find');
    Route::post('/cash-receipts-voucher-by-invoice-delete', 'delete' )->name('-by-invoice-delete'); 
    });

    Route::controller(AccVoucherController::class)->group(function () {
        // Tìm chứng từ trang thu tiền theo hóa đơn
    Route::post('/cash-receipts-voucher-by-invoice-get', 'get' )->name('-by-invoice-get');
    Route::post('/cash-receipts-voucher-by-invoice-currency', 'currency' )->name('-by-invoice-currency');
    Route::post('/cash-receipts-voucher-by-invoice-reference', 'reference' )->name('-by-invoice-reference');  
    Route::post('/cash-receipts-voucher-by-invoice-check-subject', 'check_subject' )->name('-check-subject');
      // Xóa đính kèm
    Route::post('/cash-receipts-voucher-by-invoice-delete-attach', 'delete_attach' )->name('-delete-attach');  
    });

    Route::controller(AccGeneralController::class)->group(function () {
       Route::post('/cash-receipts-voucher-by-invoice-print','prints');  
    }); 
  });  

  // Payment Cash General - Tổng hợp chi tiền
  Route::group([
    'as' => 'cash-payment-general'
  ],function () {
  Route::controller(AccCashPaymentGeneralController::class)->group(function () {
    Route::get('/cash-payment-general', 'show' )->name('');
    Route::post('/cash-payment-general-get','find' )->name('-find');
    Route::post('/cash-payment-general-unwrite','unwrite' )->name('-unwrite');
    Route::post('/cash-payment-general-write','write' )->name('-write');
    Route::post('/cash-payment-general-revoucher', 'revoucher' )->name('-revoucher');
    Route::post('/cash-payment-general-start-voucher', 'start_voucher' )->name('-start-voucher');
    Route::post('/cash-payment-general-change-voucher', 'change_voucher' )->name('-change-voucher');
    Route::get('/cash-payment-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/cash-payment-general-import', 'import')->name('-import');
    Route::any('/cash-payment-general-delete', 'delete')->name('-delete');
    }); 
    Route::controller(AccGeneralController::class)->group(function () {
        Route::post('/cash-payment-general-detail','detail');  
        Route::post('/cash-payment-general-print','prints'); 
    }); 
  });  

  // Payment Cash Voucher - Phiếu chi tiền
   Route::group([
    'as' => 'cash-payment-voucher'
  ],function () {
    Route::controller(AccCashPaymentVoucherController::class)->group(function () {
      Route::get('/cash-payment-voucher', 'show' )->name('');
      Route::post('/cash-payment-voucher-save', 'save' )->name('-save');
      Route::post('/cash-payment-voucher-bind', 'bind' )->name('-bind');
      Route::get('/cash-payment-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
      Route::any('/cash-payment-voucher-import', 'import')->name('-import');
    });  
    
    Route::controller(AccCashPaymentGeneralController::class)->group(function () {
      // Ghi , ko ghi , tìm chứng từ trang chi tiền
      Route::post('/cash-payment-voucher-unwrite','unwrite' )->name('-unwrite');
      Route::post('/cash-payment-voucher-write','write' )->name('-write');
      Route::post('/cash-payment-voucher-find', 'find' )->name('-find');
      Route::post('/cash-payment-voucher-delete', 'delete' )->name('-delete'); 
    });  

    Route::controller(AccVoucherController::class)->group(function () {
        Route::post('/cash-payment-voucher-get', 'get' )->name('-get');
        Route::post('/cash-payment-voucher-auto', 'auto' )->name('-auto');
        Route::post('/cash-payment-voucher-ai', 'ai' )->name('-ai');
        Route::post('/cash-payment-voucher-currency', 'currency' )->name('-currency');
        Route::post('/cash-payment-voucher-reference', 'reference' )->name('-reference');
        Route::post('/cash-payment-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
        Route::post('/cash-payment-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
        // Kiểm tra mst doanh nghiệp
        Route::post('/cash-payment-voucher-check-subject', 'check_subject' )->name('-check-subject');
          // Xóa đính kèm
        Route::post('/cash-payment-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
     });

     Route::controller(AccGeneralController::class)->group(function () {
        Route::post('/cash-payment-voucher-print','prints');
      }); 
  }); 

  // Payment Cash Voucher by Invoice - Chi tiền theo hóa đơn
     Route::group([
    'as' => 'cash-payment-voucher-by-invoice'
  ],function () {
    Route::controller(AccCashPaymentVoucherByInvoiceController::class)->group(function () {
    Route::get('/cash-payment-voucher-by-invoice', 'show' )->name('');
    Route::post('/cash-payment-voucher-by-invoice-get-data', 'get_data' )->name('-get-data');
    Route::post('/cash-payment-voucher-by-invoice-save', 'save' )->name('-save');
    Route::post('/cash-payment-voucher-by-invoice-bind', 'bind' )->name('-bind');
    }); 
    Route::controller(AccCashPaymentGeneralController::class)->group(function () {
      // Ghi , ko ghi , tìm chứng từ trang chi tiền theo hóa đơn
    Route::post('/cash-payment-voucher-by-invoice-unwrite','unwrite' )->name('-by-invoice-unwrite');
    Route::post('/cash-payment-voucher-by-invoice-write','write' )->name('-by-invoice-write');
    Route::post('/cash-payment-voucher-by-invoice-find', 'find' )->name('-by-invoice-find');
    Route::post('/cash-payment-voucher-by-invoice-delete', 'delete' )->name('-by-invoice-delete'); 
    }); 

     Route::controller(AccVoucherController::class)->group(function () {
    // Tìm chứng từ trang thu tiền theo hóa đơn
    Route::post('/cash-payment-voucher-by-invoice-get', 'get' )->name('-by-invoice-get');
    Route::post('/cash-payment-voucher-by-invoice-currency', 'currency' )->name('-by-invoice-currency');
    Route::post('/cash-payment-voucher-by-invoice-reference', 'reference' )->name('-by-invoice-reference');
    Route::post('/cash-payment-voucher-by-invoice-check-subject', 'check_subject' )->name('-check-subject');
      // Xóa đính kèm
    Route::post('/cash-payment-voucher-by-invoice-delete-attach', 'delete_attach' )->name('-delete-attach');  
    }); 

    Route::controller(AccGeneralController::class)->group(function () {
         Route::post('/cash-payment-voucher-by-invoice-print','prints');
      }); 
 }); 

   // Receipt Bank General - Phiếu thu tiền ngân hàng
  Route::group([
    'as' => 'bank-receipts-general'
  ],function () {

    Route::controller(AccBankReceiptsGeneralController::class)->group(function () {
    Route::get('/bank-receipts-general', 'show' )->name('');
    Route::post('/bank-receipts-general-get','find' )->name('-find');
    Route::post('/bank-receipts-general-unwrite','unwrite' )->name('-unwrite');
    Route::post('/bank-receipts-general-write','write' )->name('-write');
    Route::post('/bank-receipts-general-revoucher', 'revoucher' )->name('-revoucher');
    Route::post('/bank-receipts-general-start-voucher', 'start_voucher' )->name('-start-voucher');
    Route::post('/bank-receipts-general-change-voucher', 'change_voucher' )->name('-change-voucher');
    Route::get('/bank-receipts-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/bank-receipts-general-import', 'import')->name('-import');
    Route::any('/bank-receipts-general-delete', 'delete')->name('-delete');
     }); 

    Route::controller(AccGeneralController::class)->group(function () {
    Route::post('/bank-receipts-general-detail','detail'); 
    Route::post('/bank-receipts-general-print','prints');  
    }); 

  });  

    // Receipt Bank General - Phiếu thu tiền ngân hàng
  Route::group([
    'as' => 'bank-receipts-voucher'
  ],function () {

    Route::controller(AccBankReceiptsVoucherController::class)->group(function () {
    Route::get('/bank-receipts-voucher', 'show' )->name('');
    Route::post('/bank-receipts-voucher-save', 'save' )->name('-save');
    Route::post('/bank-receipts-voucher-bind', 'bind' )->name('-bind');
    Route::get('/bank-receipts-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/bank-receipts-voucher-import', 'import')->name('-import');  
    });  

    Route::controller(AccBankReceiptsGeneralController::class)->group(function () {
      // Ghi , ko ghi , tìm chứng từ trang thu tiền
    Route::post('/bank-receipts-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/bank-receipts-voucher-write','write' )->name('-write');
    Route::post('/bank-receipts-voucher-find', 'find' )->name('-find');
    Route::post('/bank-receipts-voucher-delete', 'delete' )->name('-delete'); 
    }); 

    Route::controller(AccVoucherController::class)->group(function () {
    Route::post('/bank-receipts-voucher-get', 'get' )->name('-get');
    Route::post('/bank-receipts-voucher-auto', 'auto' )->name('-auto');
    Route::post('/bank-receipts-voucher-ai', 'ai' )->name('-ai');
    Route::post('/bank-receipts-voucher-currency', 'currency' )->name('-currency');
    Route::post('/bank-receipts-voucher-reference', 'reference' )->name('-reference');
    Route::post('/bank-receipts-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/bank-receipts-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change'); 
    
      // Tìm tài khoản mặc định thay đổi ngân hàng
    Route::post('/bank-receipts-voucher-change-bank', 'change_bank' )->name('-change-bank');
      // Kiểm tra mst doanh nghiệp
    Route::post('/bank-receipts-voucher-check-subject', 'check_subject' )->name('-check-subject');  
     // Xóa đính kèm
    Route::post('/bank-receipts-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
    });

    Route::controller(AccGeneralController::class)->group(function () {  
    Route::post('/bank-receipts-voucher-print','prints');  
   });

  }); 

     // Receipt Bank General - Phiếu thu tiền ngân hàng
  Route::group([
    'as' => 'bank-receipts-voucher-by-invoice'
  ],function () {
    
    Route::controller(AccBankReceiptsVoucherByInvoiceController::class)->group(function () {
    Route::get('/bank-receipts-voucher-by-invoice', 'show' )->name('');
    Route::post('/bank-receipts-voucher-by-invoice-get-data', 'get_data' )->name('-get-data');
    Route::post('/bank-receipts-voucher-by-invoice-save', 'save' )->name('-save');
    Route::post('/bank-receipts-voucher-by-invoice-bind', 'bind' )->name('-bind');
    });

    Route::controller(AccBankReceiptsGeneralController::class)->group(function () {
     // Ghi , ko ghi , tìm chứng từ trang thu tiền theo hóa đơn
    Route::post('/bank-receipts-voucher-by-invoice-unwrite','unwrite' )->name('-by-invoice-unwrite');
    Route::post('/bank-receipts-voucher-by-invoice-write','write' )->name('-by-invoice-write');
    Route::post('/bank-receipts-voucher-by-invoice-find', 'find' )->name('-by-invoice-find');
    Route::post('/bank-receipts-voucher-by-invoice-delete', 'delete' )->name('-by-invoice-delete'); 
    });

    Route::controller(AccVoucherController::class)->group(function () {
     // Tìm chứng từ trang thu tiền theo hóa đơn
    Route::post('/bank-receipts-voucher-by-invoice-get', 'get' )->name('-by-invoice-get');
    Route::post('/bank-receipts-voucher-by-invoice-currency', 'currency' )->name('-by-invoice-currency');
    Route::post('/bank-receipts-voucher-by-invoice-reference', 'reference' )->name('-by-invoice-reference');
    Route::post('/bank-receipts-voucher-by-invoice-check-subject', 'check_subject' )->name('-check-subject'); 
     // Xóa đính kèm
    Route::post('/bank-receipts-voucher-by-invoice-delete-attach', 'delete_attach' )->name('-delete-attach');  
    });

    Route::controller(AccGeneralController::class)->group(function () {  
    Route::post('/bank-receipts-voucher-by-invoice-print','prints');  
    });
  });

  // Payment Bank General - Tổng hợp phiếu chi tiền ngân hàng
  Route::group([
    'as' => 'bank-payment-general'
  ],function () {

    Route::controller(AccBankPaymentGeneralController::class)->group(function () {
    Route::get('/bank-payment-general', 'show' )->name('');
    Route::post('/bank-payment-general-get','find' )->name('-find');
    Route::post('/bank-payment-general-unwrite','unwrite' )->name('-unwrite');
    Route::post('/bank-payment-general-write','write' )->name('-write');
    Route::post('/bank-payment-general-revoucher', 'revoucher' )->name('-revoucher');
    Route::post('/bank-payment-general-start-voucher', 'start_voucher' )->name('-start-voucher');
    Route::post('/bank-payment-general-change-voucher', 'change_voucher' )->name('-change-voucher');
    Route::get('/bank-payment-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/bank-payment-general-import', 'import')->name('-import');
    Route::any('/bank-payment-general-delete', 'delete')->name('-delete');

    Route::controller(AccGeneralController::class)->group(function () {
      Route::post('/bank-payment-general-detail','detail');    
      Route::post('/bank-payment-general-print','prints'); 
    });
   });

  });  

  // Payment Bank Voucher - Phiếu chi tiền ngân hàng
  Route::group([
    'as' => 'bank-payment-voucher'
  ],function () {

    Route::controller(AccBankPaymentVoucherController::class)->group(function () { 
    Route::get('/bank-payment-voucher', 'show' )->name('');
    Route::post('/bank-payment-voucher-save', 'save' )->name('-save');
    Route::post('/bank-payment-voucher-bind', 'bind' )->name('-bind');
    Route::get('/bank-payment-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/bank-payment-voucher-import', 'import')->name('-import');
    });

    Route::controller(AccBankPaymentGeneralController::class)->group(function () {  
        // Ghi , ko ghi , tìm chứng từ trang chi tiền
    Route::post('/bank-payment-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/bank-payment-voucher-write','write' )->name('-write');
    Route::post('/bank-payment-voucher-find', 'find' )->name('-find');
    Route::post('/bank-payment-voucher-delete', 'delete' )->name('-delete'); 
   }); 

   Route::controller(AccVoucherController::class)->group(function () {  
    Route::post('/bank-payment-voucher-get', 'get' )->name('-get');
    Route::post('/bank-payment-voucher-auto', 'auto' )->name('-auto');
    Route::post('/bank-payment-voucher-ai', 'ai' )->name('-ai');
    Route::post('/bank-payment-voucher-currency', 'currency' )->name('-currency');
    Route::post('/bank-payment-voucher-reference', 'reference' )->name('-reference');
    Route::post('/bank-payment-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/bank-payment-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
    // Tìm tài khoản mặc định thay đổi ngân hàng
    Route::post('/bank-payment-voucher-change-bank', 'change_bank' )->name('-change-bank');
    // Kiểm tra mst doanh nghiệp
    Route::post('/bank-payment-voucher-check-subject', 'check_subject' )->name('-check-subject');
     // Xóa đính kèm
    Route::post('/bank-payment-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
  });  

    Route::controller(AccGeneralController::class)->group(function () {
    Route::post('/bank-payment-voucher-print','prints');  
    });
  });  

  // Payment Bank Voucher by Invoice - Chi tiền ngân hàng theo hóa đơn
    Route::group([
    'as' => 'bank-payment-voucher-by-invoice'
  ],function () {  

    Route::controller(AccBankPaymentVoucherByInvoiceController::class)->group(function () { 
    Route::get('/bank-payment-voucher-by-invoice', 'show' )->name('');
    Route::post('/bank-payment-voucher-by-invoice-get-data', 'get_data' )->name('-get-data');
    Route::post('/bank-payment-voucher-by-invoice-save', 'save' )->name('-save');
    Route::post('/bank-payment-voucher-by-invoice-bind', 'bind' )->name('-bind');
    });  

    Route::controller(AccBankPaymentGeneralController::class)->group(function () { 
    // Ghi , ko ghi , tìm chứng từ trang chi tiền theo hóa đơn
    Route::post('/bank-payment-voucher-by-invoice-unwrite','unwrite' )->name('-by-invoice-unwrite');
    Route::post('/bank-payment-voucher-by-invoice-write','write' )->name('-by-invoice-write');
    Route::post('/bank-payment-voucher-by-invoice-find', 'find' )->name('-by-invoice-find');
    Route::post('/bank-payment-voucher-by-invoice-delete', 'delete' )->name('-by-invoice-delete'); 
    }); 

    Route::controller(AccVoucherController::class)->group(function () { 
      // Tìm chứng từ trang thu tiền theo hóa đơn
    Route::post('/bank-payment-voucher-by-invoice-get', 'get' )->name('-by-invoice-get');
    Route::post('/bank-payment-voucher-by-invoice-currency', 'currency' )->name('-by-invoice-currency');
    Route::post('/bank-payment-voucher-by-invoice-reference', 'reference' )->name('-by-invoice-reference');
    Route::post('/bank-payment-voucher-by-invoice-check-subject', 'check_subject' )->name('-check-subject');  
     // Xóa đính kèm
    Route::post('/bank-payment-voucher-by-invoice-delete-attach', 'delete_attach' )->name('-delete-attach');  
    }); 

    Route::controller(AccGeneralController::class)->group(function () {      
    Route::post('/bank-payment-voucher-by-invoice-print','prints');   
    });

   });  

// Transfer Bank General - Chuyển khoản ngân hàng
  Route::group([
    'as' => 'bank-transfer-general'
  ],function () {

  Route::controller(AccBankTransferGeneralController::class)->group(function () {    
  Route::get('/bank-transfer-general', 'show' )->name('');
  Route::post('/bank-transfer-general-get','find' )->name('-find');
  Route::post('/bank-transfer-general-unwrite','unwrite' )->name('-unwrite');
  Route::post('/bank-transfer-general-write','write' )->name('-write');
  Route::post('/bank-transfer-general-revoucher', 'revoucher' )->name('-revoucher');
  Route::post('/bank-transfer-general-start-voucher', 'start_voucher' )->name('-start-voucher');
  Route::post('/bank-transfer-general-change-voucher', 'change_voucher' )->name('-change-voucher');
  Route::get('/bank-transfer-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::any('/bank-transfer-general-import', 'import')->name('-import');
  Route::any('/bank-transfer-general-delete', 'delete')->name('-delete');
  });

   Route::controller(AccGeneralController::class)->group(function () {   
   Route::post('/bank-transfer-general-detail','detail');  
   Route::post('/bank-transfer-general-print','prints'); 
   }); 

  });  

   Route::group([
    'as' => 'bank-transfer-voucher'
  ],function () {

    Route::controller(AccBankTransferVoucherController::class)->group(function () {
    Route::get('/bank-transfer-voucher', 'show' )->name('');
    Route::post('/bank-transfer-voucher-save', 'save' )->name('-save');
    Route::post('/bank-transfer-voucher-bind', 'bind' )->name('-bind');
    Route::get('/bank-transfer-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/bank-transfer-voucher-import', 'import')->name('-import');  
    });

    Route::controller(AccBankTransferGeneralController::class)->group(function () {    
    // Ghi , ko ghi , tìm chứng từ trang
    Route::post('/bank-transfer-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/bank-transfer-voucher-write','write' )->name('-write');
    Route::post('/bank-transfer-voucher-find', 'find' )->name('-find');
    Route::post('/bank-transfer-voucher-delete', 'delete' )->name('-delete'); 
     }); 

    Route::controller(AccVoucherController::class)->group(function () {  
    Route::post('/bank-transfer-voucher-get', 'get' )->name('-get');
    Route::post('/bank-transfer-voucher-auto', 'auto' )->name('-auto');
    Route::post('/bank-transfer-voucher-ai', 'ai' )->name('-ai');
    Route::post('/bank-transfer-voucher-currency', 'currency' )->name('-currency');
    Route::post('/bank-transfer-voucher-reference', 'reference' )->name('-reference');
    Route::post('/bank-transfer-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/bank-transfer-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
    // Tìm tài khoản mặc định thay đổi ngân hàng
    Route::post('/bank-transfer-voucher-change-bank', 'change_bank' )->name('-change-bank');  
    // Xóa đính kèm
    Route::post('/bank-transfer-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
    }); 

    Route::controller(AccGeneralController::class)->group(function () {   
      Route::post('/bank-transfer-voucher-print','prints'); 
    }); 

    });  

  // Entry General - Phiếu kế toán chung
  Route::group([
    'as' => 'entry-general'
  ],function () {

   Route::controller(AccEntryGeneralController::class)->group(function () {
    Route::get('/entry-general', 'show' )->name('');
    Route::post('/entry-general-get','find' )->name('-find');
    Route::post('/entry-general-unwrite','unwrite' )->name('-unwrite');
    Route::post('/entry-general-write','write' )->name('-write');
    Route::post('/entry-general-revoucher', 'revoucher' )->name('-revoucher');
    Route::post('/entry-general-start-voucher', 'start_voucher' )->name('-start-voucher');
    Route::post('/entry-general-change-voucher', 'change_voucher' )->name('-change-voucher');
    Route::get('/entry-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/entry-general-import', 'import')->name('-import');
    Route::any('/entry-general-delete', 'delete')->name('-delete');
  });
    Route::controller(AccGeneralController::class)->group(function () {    
    Route::post('/entry-general-detail','detail');  
    Route::post('/entry-general-print','prints'); 
    });
    
  });  

  // Entry General Voucher - Chứng từ kế toán tổng hợp
   Route::group([
    'as' => 'entry-general-voucher'
    //'controller' => AccEntryGeneralVoucherController::class
  ],function () {

   Route::controller(AccEntryGeneralVoucherController::class)->group(function () {   
   Route::get('/entry-general-voucher', 'show' )->name('');
   Route::post('/entry-general-voucher-save', 'save' )->name('-save');
   Route::post('/entry-general-voucher-bind', 'bind' )->name('-bind');
   Route::get('/entry-general-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
   Route::any('/entry-general-voucher-import', 'import')->name('-import');
   });

    Route::controller(AccEntryGeneralController::class)->group(function () {    
     // Ghi , ko ghi , tìm chứng từ trang
    Route::post('/entry-general-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/entry-general-voucher-write','write' )->name('-write');
    Route::post('/entry-general-voucher-find', 'find' )->name('-find');
    Route::post('/entry-general-voucher-delete', 'delete' )->name('-delete'); 
   });

   Route::controller(AccVoucherController::class)->group(function () {  
    Route::post('/entry-general-voucher-get', 'get' )->name('-get');
    Route::post('/entry-general-voucher-auto', 'auto' )->name('-auto');
    Route::post('/entry-general-voucher-ai', 'ai' )->name('-ai');
    Route::post('/entry-general-voucher-currency', 'currency' )->name('-currency');
    Route::post('/entry-general-voucher-reference', 'reference' )->name('-reference');
    Route::post('/entry-general-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/entry-general-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
    // Kiểm tra mst doanh nghiệp
    Route::post('/entry-general-voucher-check-subject', 'check_subject' )->name('-check-subject');
     // Xóa đính kèm
    Route::post('/entry-general-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
   });

   Route::controller(AccGeneralController::class)->group(function () {  
     Route::post('/entry-general-voucher-print','prints');  
  });

  });  

  // Inventory Issue General - Xuất kho
  Route::group([
    'as' => 'inventory-issue-general'
  ],function () {

  Route::controller(AccInventoryIssueGeneralController::class)->group(function () {  
  Route::get('/inventory-issue-general', 'show' )->name('');
  Route::post('/inventory-issue-general-get','find' )->name('-find');
  Route::post('/inventory-issue-general-unwrite','unwrite' )->name('-unwrite');
  Route::post('/inventory-issue-general-write','write' )->name('-write');
  Route::post('/inventory-issue-general-revoucher', 'revoucher' )->name('-revoucher');
  Route::post('/inventory-issue-general-start-voucher', 'start_voucher' )->name('-start-voucher');
  Route::post('/inventory-issue-general-change-voucher', 'change_voucher' )->name('-change-voucher');
  Route::get('/inventory-issue-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::any('/inventory-issue-general-import', 'import')->name('-import');
  Route::any('/inventory-issue-general-delete', 'delete')->name('-delete');
  });

  Route::controller(AccGeneralController::class)->group(function () {    
  Route::post('/inventory-issue-general-detail','detail');  
  Route::post('/inventory-issue-general-print','prints'); 
  });

  });  

  // Inventory Issue Detail - Phiếu xuất kho chi tiết
  Route::group([
    'as' => 'inventory-issue-voucher'
  ],function () {

    Route::controller(AccInventoryIssueVoucherController::class)->group(function () {   
    Route::get('/inventory-issue-voucher', 'show' )->name('');
    Route::post('/inventory-issue-voucher-save', 'save' )->name('-save');
    Route::post('/inventory-issue-voucher-bind', 'bind' )->name('-bind');
    Route::get('/inventory-issue-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/inventory-issue-voucher-import', 'import')->name('-import');
    });

    Route::controller(AccInventoryIssueGeneralController::class)->group(function () {   
    // Ghi , ko ghi , tìm chứng từ trang
    Route::post('/inventory-issue-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/inventory-issue-voucher-write','write' )->name('-write');
    Route::post('/inventory-issue-voucher-find', 'find' )->name('-find');
    Route::post('/inventory-issue-voucher-delete', 'delete' )->name('-delete'); 
    });

    Route::controller(AccVoucherController::class)->group(function () {   
    Route::post('/inventory-issue-voucher-get', 'get' )->name('-get');
    Route::post('/inventory-issue-voucher-auto', 'auto' )->name('-auto');
    Route::post('/inventory-issue-voucher-ai', 'ai' )->name('-ai');
    Route::post('/inventory-issue-voucher-currency', 'currency' )->name('-currency');
    Route::post('/inventory-issue-voucher-reference', 'reference' )->name('-reference');
    Route::post('/inventory-issue-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/inventory-issue-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
    // Tìm vật tư hàng hóa
    Route::post('/inventory-issue-voucher-load', 'load' )->name('-load');
    Route::post('/inventory-issue-voucher-scan', 'scan' )->name('-scan');
    // Kiểm tra mst doanh nghiệp
    Route::post('/inventory-issue-voucher-check-subject', 'check_subject' )->name('-check-subject');
     // Xóa đính kèm
    Route::post('/inventory-issue-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
    });

    Route::controller(AccGeneralController::class)->group(function () {  
      Route::post('/inventory-issue-voucher-print','prints');  
    });

  });  


  // Inventory Receipt General - Tổng hợp nhập kho
  Route::group([
    'as' => 'inventory-receipt-general'
  ],function () {

      Route::controller(AccInventoryReceiptGeneralController::class)->group(function () {  
      Route::get('/inventory-receipt-general', 'show' )->name('');
      Route::post('/inventory-receipt-general-get','find' )->name('-find');
      Route::post('/inventory-receipt-general-unwrite','unwrite' )->name('-unwrite');
      Route::post('/inventory-receipt-general-write','write' )->name('-write');
      Route::post('/inventory-receipt-general-revoucher', 'revoucher' )->name('-revoucher');
      Route::post('/inventory-receipt-general-start-voucher', 'start_voucher' )->name('-start-voucher');
      Route::post('/inventory-receipt-general-change-voucher', 'change_voucher' )->name('-change-voucher');
      Route::get('/inventory-receipt-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
      Route::any('/inventory-receipt-general-import', 'import')->name('-import');
      Route::any('/inventory-receipt-general-delete', 'delete')->name('-delete');
      });

      Route::controller(AccGeneralController::class)->group(function () {    
      Route::post('/inventory-receipt-general-detail','detail');  
      Route::post('/inventory-receipt-general-print','prints'); 
      });

  });  

  // Inventory Receipt Detail - Phiếu nhập kho chi tiết
    Route::group([
    'as' => 'inventory-receipt-voucher'
  ],function () {

    Route::controller(AccInventoryReceiptVoucherController::class)->group(function () { 
    Route::get('/inventory-receipt-voucher', 'show' )->name('');
    Route::post('/inventory-receipt-voucher-save', 'save' )->name('-save');
    Route::post('/inventory-receipt-voucher-bind', 'bind' )->name('-bind');
    Route::get('/inventory-receipt-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/inventory-receipt-voucher-import', 'import')->name('-import');
    });   

    Route::controller(AccInventoryReceiptGeneralController::class)->group(function () {   
    // Ghi , ko ghi , tìm chứng từ trang
    Route::post('/inventory-receipt-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/inventory-receipt-voucher-write','write' )->name('-write');
    Route::post('/inventory-receipt-voucher-find', 'find' )->name('-find');
    Route::post('/inventory-receipt-voucher-delete', 'delete' )->name('-delete'); 
    });  

    Route::controller(AccVoucherController::class)->group(function () { 
    Route::post('/inventory-receipt-voucher-get', 'get' )->name('-get');
    Route::post('/inventory-receipt-voucher-auto', 'auto' )->name('-auto');
    Route::post('/inventory-receipt-voucher-ai', 'ai' )->name('-ai');
    Route::post('/inventory-receipt-voucher-currency', 'currency' )->name('-currency');
    Route::post('/inventory-receipt-voucher-reference', 'reference' )->name('-reference');
    Route::post('/inventory-receipt-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/inventory-receipt-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
      // Tìm vật tư hàng hóa
    Route::post('/inventory-receipt-voucher-load', 'load' )->name('-load');
    Route::post('/inventory-receipt-voucher-scan', 'scan' )->name('-scan');
    // Kiểm tra mst doanh nghiệp
    Route::post('/inventory-receipt-voucher-check-subject', 'check_subject' )->name('-check-subject');
    // Xóa đính kèm
    Route::post('/inventory-receipt-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
   });

     Route::controller(AccGeneralController::class)->group(function () {  
      Route::post('/inventory-receipt-voucher-print','prints');  
    });

  });

  // Inventory Transfer General - Tổng hợp chuyển kho
  Route::group([
    'as' => 'inventory-transfer-general'
  ],function () {

      Route::controller(AccInventoryTransferGeneralController::class)->group(function () {  
      Route::get('/inventory-transfer-general', 'show' )->name('');
      Route::post('/inventory-transfer-general-get','find' )->name('-find');
      Route::post('/inventory-transfer-general-unwrite','unwrite' )->name('-unwrite');
      Route::post('/inventory-transfer-general-write','write' )->name('-write');
      Route::post('/inventory-transfer-general-revoucher', 'revoucher' )->name('-revoucher');
      Route::post('/inventory-transfer-general-start-voucher', 'start_voucher' )->name('-start-voucher');
      Route::post('/inventory-transfer-general-change-voucher', 'change_voucher' )->name('-change-voucher');
      Route::get('/inventory-transfer-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
      Route::any('/inventory-transfer-general-import', 'import')->name('-import');
      Route::any('/inventory-transfer-general-delete', 'delete')->name('-delete');
      });

      Route::controller(AccGeneralController::class)->group(function () {    
      Route::post('/inventory-transfer-general-detail','detail');  
      Route::post('/inventory-transfer-general-print','prints'); 
      });

  });  

  // Inventory Receipt Detail - Phiếu nhập kho chi tiết
    Route::group([
    'as' => 'inventory-transfer-voucher'
  ],function () {

    Route::controller(AccInventoryTransferVoucherController::class)->group(function () { 
    Route::get('/inventory-transfer-voucher', 'show' )->name('');
    Route::post('/inventory-transfer-voucher-save', 'save' )->name('-save');
    Route::post('/inventory-transfer-voucher-bind', 'bind' )->name('-bind');
    Route::get('/inventory-transfer-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/inventory-transfer-voucher-import', 'import')->name('-import');
    });   

    Route::controller(AccInventoryTransferGeneralController::class)->group(function () {   
    // Ghi , ko ghi , tìm chứng từ trang
    Route::post('/inventory-transfer-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/inventory-transfer-voucher-write','write' )->name('-write');
    Route::post('/inventory-transfer-voucher-find', 'find' )->name('-find');
    Route::post('/inventory-transfer-voucher-delete', 'delete' )->name('-delete'); 
    });  

    Route::controller(AccVoucherController::class)->group(function () { 
    Route::post('/inventory-transfer-voucher-get', 'get' )->name('-get');
    Route::post('/inventory-transfer-voucher-auto', 'auto' )->name('-auto');
    Route::post('/inventory-transfer-voucher-ai', 'ai' )->name('-ai');
    Route::post('/inventory-transfer-voucher-currency', 'currency' )->name('-currency');
    Route::post('/inventory-transfer-voucher-reference', 'reference' )->name('-reference');
    Route::post('/inventory-transfer-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/inventory-transfer-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
      // Tìm vật tư hàng hóa
    Route::post('/inventory-transfer-voucher-load', 'load' )->name('-load');
    Route::post('/inventory-transfer-voucher-scan', 'scan' )->name('-scan');
    // Kiểm tra mst doanh nghiệp
    Route::post('/inventory-transfer-voucher-check-subject', 'check_subject' )->name('-check-subject');
    // Xóa đính kèm
    Route::post('/inventory-transfer-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
   });

     Route::controller(AccGeneralController::class)->group(function () {  
      Route::post('/inventory-transfer-voucher-print','prints');  
    });

  });

  // Purchase General - Tổng hợp mua hàng
  Route::group([
    'as' => 'purchase-general'
  ],function () {

      Route::controller(AccPurchaseGeneralController::class)->group(function () {  
      Route::get('/purchase-general', 'show' )->name('');
      Route::post('/purchase-general-get','find' )->name('-find');
      Route::post('/purchase-general-unwrite','unwrite' )->name('-unwrite');
      Route::post('/purchase-general-write','write' )->name('-write');
      Route::post('/purchase-general-revoucher', 'revoucher' )->name('-revoucher');
      Route::post('/purchase-general-start-voucher', 'start_voucher' )->name('-start-voucher');
      Route::post('/purchase-general-change-voucher', 'change_voucher' )->name('-change-voucher');
      Route::get('/purchase-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
      Route::any('/purchase-general-import', 'import')->name('-import');
      Route::any('/purchase-general-delete', 'delete')->name('-delete');
      });

      Route::controller(AccGeneralController::class)->group(function () {    
      Route::post('/purchase-general-detail','detail');  
      Route::post('/purchase-general-print','prints'); 
      });

  });  

    // Purchase voucher - Phiếu mua hàng
    Route::group([
    'as' => 'purchase-voucher'
  ],function () {

    Route::controller(AccPurchaseVoucherController::class)->group(function () { 
    Route::get('/purchase-voucher', 'show' )->name('');
    Route::post('/purchase-voucher-save', 'save' )->name('-save');
    Route::post('/purchase-voucher-bind', 'bind' )->name('-bind');
    Route::get('/purchase-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/purchase-voucher-import', 'import')->name('-import');
    });   

    Route::controller(AccPurchaseGeneralController::class)->group(function () {   
    // Ghi , ko ghi , tìm chứng từ trang
    Route::post('/purchase-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/purchase-voucher-write','write' )->name('-write');
    Route::post('/purchase-voucher-find', 'find' )->name('-find');
    Route::post('/purchase-voucher-delete', 'delete' )->name('-delete'); 
    });  

    Route::controller(AccVoucherController::class)->group(function () { 
    Route::post('/purchase-voucher-get', 'get' )->name('-get');
    Route::post('/purchase-voucher-auto', 'auto' )->name('-auto');
    Route::post('/purchase-voucher-ai', 'ai' )->name('-ai');
    Route::post('/purchase-voucher-currency', 'currency' )->name('-currency');
    Route::post('/purchase-voucher-reference', 'reference' )->name('-reference');
    Route::post('/purchase-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/purchase-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
      // Tìm vật tư hàng hóa
    Route::post('/purchase-voucher-load', 'load' )->name('-load');
    Route::post('/purchase-voucher-scan', 'scan' )->name('-scan');
    // Kiểm tra mst doanh nghiệp
    Route::post('/purchase-voucher-check-subject', 'check_subject' )->name('-check-subject');
    // Xóa đính kèm
    Route::post('/purchase-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
   });

     Route::controller(AccGeneralController::class)->group(function () {  
      Route::post('/purchase-voucher-print','prints');  
    });

  });


  // Purchase General - Tổng hợp mua hàng
  Route::group([
    'as' => 'sales-general'
  ],function () {

      Route::controller(AccSalesGeneralController::class)->group(function () {  
      Route::get('/sales-general', 'show' )->name('');
      Route::post('/sales-general-get','find' )->name('-find');
      Route::post('/sales-general-unwrite','unwrite' )->name('-unwrite');
      Route::post('/sales-general-write','write' )->name('-write');
      Route::post('/sales-general-revoucher', 'revoucher' )->name('-revoucher');
      Route::post('/sales-general-start-voucher', 'start_voucher' )->name('-start-voucher');
      Route::post('/sales-general-change-voucher', 'change_voucher' )->name('-change-voucher');
      Route::get('/sales-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
      Route::any('/sales-general-import', 'import')->name('-import');
      Route::any('/sales-general-delete', 'delete')->name('-delete');
      });

      Route::controller(AccGeneralController::class)->group(function () {    
      Route::post('/sales-general-detail','detail');  
      Route::post('/sales-general-print','prints'); 
      });

  });  

    // Purchase voucher - Phiếu mua hàng
    Route::group([
    'as' => 'sales-voucher'
  ],function () {

    Route::controller(AccSalesVoucherController::class)->group(function () { 
    Route::get('/sales-voucher', 'show' )->name('');
    Route::post('/sales-voucher-save', 'save' )->name('-save');
    Route::post('/sales-voucher-bind', 'bind' )->name('-bind');
    Route::get('/sales-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
    Route::any('/sales-voucher-import', 'import')->name('-import');
    });   

    Route::controller(AccSalesGeneralController::class)->group(function () {   
    // Ghi , ko ghi , tìm chứng từ trang
    Route::post('/sales-voucher-unwrite','unwrite' )->name('-unwrite');
    Route::post('/sales-voucher-write','write' )->name('-write');
    Route::post('/sales-voucher-find', 'find' )->name('-find');
    Route::post('/sales-voucher-delete', 'delete' )->name('-delete'); 
    });  

    Route::controller(AccVoucherController::class)->group(function () { 
    Route::post('/sales-voucher-get', 'get' )->name('-get');
    Route::post('/sales-voucher-auto', 'auto' )->name('-auto');
    Route::post('/sales-voucher-ai', 'ai' )->name('-ai');
    Route::post('/sales-voucher-currency', 'currency' )->name('-currency');
    Route::post('/sales-voucher-reference', 'reference' )->name('-reference');
    Route::post('/sales-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
    Route::post('/sales-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');
      // Tìm vật tư hàng hóa
    Route::post('/sales-voucher-load', 'load' )->name('-load');
    Route::post('/sales-voucher-scan', 'scan' )->name('-scan');
    // Kiểm tra mst doanh nghiệp
    Route::post('/sales-voucher-check-subject', 'check_subject' )->name('-check-subject');
    // Xóa đính kèm
    Route::post('/sales-voucher-delete-attach', 'delete_attach' )->name('-delete-attach');  
   });

     Route::controller(AccGeneralController::class)->group(function () {  
      Route::post('/sales-voucher-print','prints');  
    });

  });


// Bank compare auto -  So sánh ngân hàng tự động
Route::group([
  'as' => 'bank-compare',
  'controller' => AccBankCompareController::class
],function () {
Route::get('/bank-compare', 'show' )->name('');
Route::post('/bank-compare-load', 'load' )->name('-load');
Route::post('/bank-compare-import', 'import' )->name('-import');
Route::post('/bank-compare-check', 'check' )->name('-check');
Route::post('/bank-compare-uncheck', 'uncheck' )->name('-uncheck');
Route::post('/bank-compare-create-voucher', 'create_voucher' )->name('-create-voucher');
});

// open balance -  Số dư đầu kỳ
Route::group([
  'as' => 'open-balance',
  'controller' => AccOpenBalanceController::class
],function () {
Route::get('/open-balance', 'show' )->name('');
Route::get('/open-balance-data', 'data' )->name('-data');
Route::post('/open-balance-save', 'save' )->name('-save');
Route::any('/open-balance-import', 'import')->name('-import');
Route::get('/open-balance-export', 'export')->name('-export');
Route::get('/open-balance-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
});

});



// POS
Route::group([
  'prefix'=>'pos',
  'as' => 'pos.'
],function () {
  Route::controller(HomeController::class)->group(function () {
  Route::get('/index', 'show');
  Route::get('/', 'show')->name('index');
  Route::get('login', 'login')->name('login');
  Route::post('login', 'doLogin');
  });
});

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


// EDU
Route::group([
  'prefix'=>'edu',
  'as' => 'edu.'
],function () {
  Route::controller(HomeController::class)->group(function () {
  Route::get('/index', 'show');
  Route::get('/', 'show')->name('index');
  Route::get('login', 'login')->name('login');
  Route::post('login', 'doLogin');
  });
});

Route::controller(DatabaseController::class)->group(function () {
Route::get('create_database', 'create_database');
});
