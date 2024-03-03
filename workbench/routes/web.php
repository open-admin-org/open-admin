<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\FileController;
use Workbench\App\Http\Controllers\ImageController;
use Workbench\App\Http\Controllers\MultipleImageController;
use Workbench\App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//
// Route::get('/', function () {
//     return view('welcome');
// });

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'middleware' => ['web', 'admin'],
], static function (Router $router) {
    $router->resource('images', ImageController::class);
    $router->resource('multiple-images', MultipleImageController::class);
    $router->resource('files', FileController::class);
    $router->resource('users', UserController::class);
});
