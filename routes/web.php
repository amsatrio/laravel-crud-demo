<?php

use App\Http\Controllers\MRoleController;
use App\Http\Controllers\MRoleWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::apiResource('/api/m-roles', MRoleController::class);
Route::controller(MRoleWebController::class)->group(function () {
    Route::get('/web/m-roles', 'index')->name('web-m-roles.index');
    Route::get('/web/m-roles/create', 'create')->name('web-m-roles.create');
    Route::get('/web/m-roles/edit/{id}', 'edit')->name('web-m-roles.edit');
    Route::get('/web/m-roles/detail/{id}', 'detail')->name('web-m-roles.detail');
});