<?php

use App\Http\Controllers\MBiodataApi;
use App\Http\Controllers\MRoleApi;
use App\Http\Controllers\MRoleWeb;
use Illuminate\Support\Facades\Route;

// Route::apiResource('/api/m-role', MRoleApi::class);
// Route::controller(MRoleWeb::class)->group(function () {
//     Route::get('/web/m-roles', 'index')->name('web-m-roles.index');
//     Route::get('/web/m-roles/create', 'create')->name('web-m-roles.create');
//     Route::get('/web/m-roles/edit/{id}', 'edit')->name('web-m-roles.edit');
//     Route::get('/web/m-roles/detail/{id}', 'detail')->name('web-m-roles.detail');
// });


Route::apiResource('/api/m-biodata', MBiodataApi::class);
// Route::controller(MBiodataWeb::class)->group(function () {
//     Route::get('/web/m-biodatas', 'index')->name('web-m-biodatas.index');
//     Route::get('/web/m-biodatas/create', 'create')->name('web-m-biodatas.create');
//     Route::get('/web/m-biodatas/edit/{id}', 'edit')->name('web-m-biodatas.edit');
//     Route::get('/web/m-biodatas/detail/{id}', 'detail')->name('web-m-biodatas.detail');
// });