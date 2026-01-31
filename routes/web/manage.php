<?php
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