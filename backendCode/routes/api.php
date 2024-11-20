<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\Spa\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckRolesAndPermissions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Route::post('auth/spa/login', LoginController::class);
Route::get('auth/spa/logout', [LoginController::class, 'logout']);


Route::middleware(['auth:sanctum', CheckRolesAndPermissions::class])->group(function () {
    Route::resources([
        'users' => UserController::class
    ]);
});

Route::get('/user', function (Request $request) {
    // $role = Role::create(['name' => 'admin']);
    // $permission = Permission::create(['name' => 'viewAny User']);
    // $role->givePermissionTo($permission);
    $user = $request->user();
    $user->assignRole('admin');
})->middleware('auth:sanctum');
