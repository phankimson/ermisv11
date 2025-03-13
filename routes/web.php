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

//Test

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
// Manage
Route::group([
  'prefix'=>'manage',
  'as' => 'manage.'
],function () {

  // Globals
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

  Route::controller(UserController::class)->group(function () {
  Route::post('login','doLogin');
  Route::post('/profile','updateProfile' );
  Route::post('/avatar-profile','updateAvatar' );
  Route::post('/change-password', 'changePassword');
  Route::post('/load-history-action', 'loadHistoryAction' );
  });

  // Setting
  Route::group([
    'as' => 'setting',
    'controller' => SettingController::class
  ],function () {
  Route::get('/setting', 'show')->name('');
  Route::post('/setting-change',  'changeSetting')->name('-change');
  });

  // Query
  Route::group([
    'as' => 'query',
    'controller' => QueryController::class
  ],function () {
  Route::get('/query','show')->name('');
  Route::post('/query','query')->name('-run');
  Route::post('/query-change-database','ChangeDatabase')->name('-change-database');
  });

  // Notes
  Route::group([
    'as' => 'notes',
    'controller' => NotesController::class
  ],function () {
  Route::get('/notes','show' )->name('');
  Route::post('/notes-load','load' )->name('-load');
  Route::post('/notes-save', 'save' )->name('-save');
  Route::post('/notes-delete', 'delete' )->name('-delete');
  });

  // Permission
  Route::group([
    'as' => 'permission',
    'controller' => PermissionController::class
  ],function () {
  Route::get('/permission','show' )->name('');
  Route::post('/permission-load','load' )->name('-load');
  Route::post('/permission-group','group')->name('-group');
  Route::post('/permission-save','save')->name('-save');
  });

  // History Action
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

  // Error
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

   // Jobs
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

  // Menu
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

  // Software
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

  // License
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

  // System
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

  // GroupUsers
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

  // Country
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

  // Regions
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

  // Key Ai
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

  // Area
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

  // Distric
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

  // Document Type
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

  // Document
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

  // Company
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

  // Company Software
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

  // Users
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

  // Permission
  Route::group([
    'as' => 'permission',
    'controller' => PermissionController::class
  ],function () {
  Route::get('/permission', 'show')->name('');
  Route::post('/permission-load', 'load')->name('-load');
  Route::post('/permission-group', 'group' )->name('-group');
  Route::post('/permission-save', 'save' )->name('-save');
  });

  // System
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

  // Number Voucher
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

    // Number Voucher Format
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

  // Number Code
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

  // Setting Voucher
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

  // Excise Tax
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

  // Natural Resources Tax
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

  // Vat
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

  // Bank
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

  // Bank Account
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

  // Case Code
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

  // Cost Code
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

  // Statistical Code
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

  // Work Code
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

  // Revenue Expenditure Type
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

  // Revenue Expenditure
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

  // Unit
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

  // Warranty Period
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

  // Stock
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

  // Supplies Goods Type
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

  // Supplies Goods
  Route::group([
    'as' => 'supplies-goods',
    'controller' => AccSuppliesGoodsController::class
  ],function () {
  Route::get('/supplies-goods', 'show')->name('');
  Route::get('/supplies-goods-data', 'data')->name('-data');
  Route::post('/supplies-goods-load', 'load')->name('-load');
  Route::post('/supplies-goods-get', 'get')->name('-get');
  Route::post('/supplies-goods-save', 'save')->name('-save');
  Route::post('/supplies-goods-delete','delete')->name('-delete');
  Route::any('/supplies-goods-import', 'import')->name('-import');
  Route::get('/supplies-goods-export', 'export')->name('-export');
  Route::get('/supplies-goods-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/supplies-goods-change-database','ChangeDatabase')->name('-change-database');
  });

  // Supplies Goods Group
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

  // Object Type
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

  // Object Group
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

  // Department
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

  // Object
  Route::group([
    'as' => 'object',
    'controller' => AccObjectController::class
  ],function () {
  Route::get('/object', 'show')->name('');
  Route::get('/object-data', 'data')->name('-data');
  Route::post('/object-load', 'load')->name('-load');
  Route::post('/object-get', 'get')->name('-get');
  Route::post('/object-save', 'save')->name('-save');
  Route::post('/object-delete', 'delete')->name('-delete');
  Route::any('/object-import', 'import')->name('-import');
  Route::get('/object-export', 'export')->name('-export');
  Route::get('/object-DownloadExcel', 'DownloadExcel')->name('-DownloadExcel');
  Route::post('/object-change-database', 'ChangeDatabase')->name('-change-database');
  });

  // Currency
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

  // GroupUsers
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

  // Users
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

  // System
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

  // Account Fast
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

  // Account Auto
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

  // Print Template
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

  // Setting Account Group
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

  // AccountType
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

  // Account Nature
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

  // Period
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

  // Receipt Cash General
  Route::controller(AccGeneralController::class)->group(function () {
  Route::post('/cash-receipts-general-detail','detail');
  Route::post('/cash-receipts-voucher-delete', 'delete' )->name('-delete'); 
  });

  // Receipt Cash General
  Route::group([
    'as' => 'cash-receipts-general',
    'controller' => AccCashReceiptsGeneralController::class
  ],function () {
  Route::get('/cash-receipts-general', 'show' )->name('');
  Route::post('/cash-receipts-general-get','find' )->name('-find');
  Route::post('/cash-receipts-general-unwrite','unwrite' )->name('-unwrite');
  Route::post('/cash-receipts-general-write','write' )->name('-write');
  Route::post('/cash-receipts-general-revoucher', 'revoucher' )->name('-revoucher');
  Route::post('/cash-receipts-general-start-voucher', 'start_voucher' )->name('-start-voucher');
  Route::post('/cash-receipts-general-change-voucher', 'change_voucher' )->name('-change-voucher');
  Route::get('/cash-receipts-general-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::any('/cash-receipts-general-import', 'import')->name('-import');

  Route::post('/cash-receipts-voucher-unwrite','unwrite' )->name('-unwrite');
  Route::post('/cash-receipts-voucher-write','write' )->name('-write');
  Route::post('/cash-receipts-voucher-find', 'find' )->name('-find');
  });  

  // Receipt Cash Detail
  Route::group([
    'as' => 'cash-receipts-voucher',
    'controller' => AccCashReceiptsVoucherController::class
  ],function () {
  Route::get('/cash-receipts-voucher', 'show' )->name('');
  Route::post('/cash-receipts-voucher-save', 'save' )->name('-save');
  Route::post('/cash-receipts-voucher-bind', 'bind' )->name('-bind');
  Route::get('/cash-receipts-voucher-DownloadExcel', 'DownloadExcel' )->name('-DownloadExcel');
  Route::any('/cash-receipts-voucher-import', 'import')->name('-import');
  });

  // Receipt Cash Detail Voucher
  Route::group([
    'as' => 'cash-receipts-voucher',
    'controller' => AccVoucherController::class
  ],function () {
  Route::post('/cash-receipts-voucher-get', 'get' )->name('-get');
  Route::post('/cash-receipts-voucher-auto', 'auto' )->name('-auto');
  Route::post('/cash-receipts-voucher-ai', 'ai' )->name('-ai');
  Route::post('/cash-receipts-voucher-currency', 'currency' )->name('-currency');
  Route::post('/cash-receipts-voucher-reference', 'reference' )->name('-reference');
  Route::post('/cash-receipts-voucher-voucher-change', 'voucher_change' )->name('-voucher-change');
  Route::post('/cash-receipts-voucher-load-voucher-change', 'load_voucher_change' )->name('-load-voucher-change');

  Route::post('/cash-receipts-voucher-by-invoice-get', 'get' )->name('-get');
  });

  // Receipt Cash Detail By Invoice
Route::group([
  'as' => 'cash-receipts-voucher-by-invoice',
  'controller' => AccCashReceiptsVoucherByInvoiceController::class
],function () {
Route::get('/cash-receipts-voucher-by-invoice', 'show' )->name('');
Route::post('/cash-receipts-voucher-by-invoice-get-data', 'get_data' )->name('-get-data');
Route::post('/cash-receipts-voucher-by-invoice-save', 'save' )->name('-save');
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
