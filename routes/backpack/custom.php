<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('dashboard', 'DashboardController@dashboard')->name('backpack.dashboard');
    Route::crud('article', 'ArticleCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('tag', 'TagCrudController');
    Route::crud('comment', 'CommentCrudController');
    Route::crud('user', 'UserCrudController');
    Route::get('setting-fe', 'SettingFeController@index')->name('admin.setting_fe');
    Route::post('setting-fe', 'SettingFeController@update')->name('admin.setting_fe.update');
    Route::get('api-docs', 'ApiDocsController@index')->name('admin.api_docs');
    Route::get('api-docs/download-postman', 'ApiDocsController@downloadPostman')->name('admin.api_docs.postman');
});

// Custom Auth override
Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => (array) config('backpack.base.web_middleware', 'web'),
    'namespace' => 'App\Http\Controllers\Admin\Auth',
], function () {
    Route::post('login', 'LoginController@login');
});
