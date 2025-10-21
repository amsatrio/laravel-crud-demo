<?php

use App\Http\Controllers\MBiodataApi;
use App\Http\Controllers\MMenuController;
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
Route::controller(MRoleApi::class)->group(function () {
    Route::get("/api/m-role", "index")->name("api-mrole.index");
    Route::get("/api/m-role/{id}", "show")->name("api-mrole.show");
    Route::post("/api/m-role", "store")->name("api-mrole.store");
    Route::put("/api/m-role/{id}", "update")->name("api-mrole.update");
    Route::delete("/api/m-role/{id}", "destroy")->name("api-mrole.destroy");
});


// Route::apiResource('/api/m-biodata', MBiodataApi::class);
// Route::controller(MBiodataWeb::class)->group(function () {
//     Route::get('/web/m-biodatas', 'index')->name('web-m-biodatas.index');
//     Route::get('/web/m-biodatas/create', 'create')->name('web-m-biodatas.create');
//     Route::get('/web/m-biodatas/edit/{id}', 'edit')->name('web-m-biodatas.edit');
//     Route::get('/web/m-biodatas/detail/{id}', 'detail')->name('web-m-biodatas.detail');
// });
Route::controller(MBiodataApi::class)->group(function () {
    Route::get("/api/m-biodata", "index")->name("api-mbiodata.index");
    Route::get("/api/m-biodata/{id}", "show")->name("api-mbiodata.show");
    Route::post("/api/m-biodata", "store")->name("api-mbiodata.store");
    Route::put("/api/m-biodata/{id}", "update")->name("api-mbiodata.update");
    Route::delete("/api/m-biodata/{id}", "destroy")->name("api-mbiodata.destroy");
});


Route::controller(MMenuController::class)->group(function () {
    Route::get("{slug}/{action}", 'slug');
    Route::get("debug", 'debug');
    Route::get("debug-update", 'debug_update');
});