<?php

use Ihtisham467\LaravelPermissionEditor\Http\Controllers\RoleController;
use Ihtisham467\LaravelPermissionEditor\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);
