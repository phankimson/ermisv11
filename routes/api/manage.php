<?php

Route::group([
  "prefix"=>"manage",
  "as" => "manage."
],function () {
    // DropDownList
    Route::group([
      "as" => "dropdownlist.",
      "controller" => DropDownListController::class
      ],function () {
      Route::get(env("URL_DROPDOWN").'/country', 'country_dropdown_list')->name('country');
      Route::get(env("URL_DROPDOWN").'/regions', 'regions_dropdown_list')->name('regions');
      Route::get(env("URL_DROPDOWN").'/area', 'area_dropdown_list')->name('area');
      Route::get(env("URL_DROPDOWN").'/distric', 'distric_dropdown_list')->name('distric');
      Route::get(env("URL_DROPDOWN").'/software', 'software_dropdown_list')->name('software');
      Route::get(env("URL_DROPDOWN").'/company', 'company_dropdown_list')->name('company');
      Route::get(env("URL_DROPDOWN").'/group-users', 'group_users_dropdown_list')->name('group-users');
      Route::get(env("URL_DROPDOWN").'/document-type', 'document_type_dropdown_list')->name('document-type');
      Route::get(env("URL_DROPDOWN").'/menu', 'menu_dropdown_list')->name('menu');
      Route::get(env("URL_DROPDOWN").'/menu-all', 'menu_all_dropdown_list')->name('menu-all');
      Route::get(env("URL_DROPDOWN").'/license', 'license_dropdown_list')->name('license');
      Route::get(env("URL_DROPDOWN").'/user', 'user_dropdown_list')->name('user');
      });
    }); 