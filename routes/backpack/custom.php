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
    Route::crud('article', 'ArticleCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('tag', 'TagCrudController');
    Route::crud('comment', 'CommentCrudController');
    Route::crud('user', 'UserCrudController');
});

// Custom Auth override
Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => (array) config('backpack.base.web_middleware', 'web'),
    'namespace' => 'App\Http\Controllers\Admin\Auth',
], function () {
    Route::post('login', 'LoginController@login');
});
