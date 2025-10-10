<?php

use App\Http\Controllers\MRoleApi;
use App\Http\Controllers\MRoleWeb;
use Illuminate\Support\Facades\Route;

Route::apiResource('/api/m-roles', MRoleApi::class);
Route::controller(MRoleWeb::class)->group(function () {
    Route::get('/web/m-roles', 'index')->name('web-m-roles.index');
    Route::get('/web/m-roles/create', 'create')->name('web-m-roles.create');
    Route::get('/web/m-roles/edit/{id}', 'edit')->name('web-m-roles.edit');
    Route::get('/web/m-roles/detail/{id}', 'detail')->name('web-m-roles.detail');
});