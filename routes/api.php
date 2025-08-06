<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EntityController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\MailController;
use App\Http\Controllers\Api\PartenerController;
use App\Http\Controllers\Api\PartenerLogoController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TypeProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Newsletter;
use App\Models\Partener;
use App\Models\PartenerLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

///-------------- Dashboard --------------///
Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
    Route::get('/', 'index');
});

///-------------- User --------------///
Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::get('all', 'index')->middleware(['auth:sanctum', 'role:Administrateur']);
    Route::get('currentUser', 'currentUser')->middleware(['auth:sanctum']);
    Route::post('register', 'register')->middleware(['auth:sanctum', 'role:Administrateur']);
    Route::post('login', 'login');
    Route::post('updateImage/{id}', 'updateImage')->middleware(['auth:sanctum']);
    Route::post('logout', 'logout')->middleware(['auth:sanctum']);
    Route::post('{id}', 'update')->middleware(['auth:sanctum']);
    Route::delete('deleteImage/{id}', 'deleteImage')->middleware(['auth:sanctum']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum', 'role:Administrateur']);
});

///-------------- Type Product --------------///
Route::controller(TypeProductController::class)->prefix('typeProduct')->group(function () {
    Route::get('all', 'index')->middleware(['auth:sanctum']);
    Route::post('create', 'store')->middleware(['auth:sanctum']);
    Route::post('{id}', 'update')->middleware(['auth:sanctum']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum']);
});


///-------------- Category --------------///
Route::controller(CategoryController::class)->prefix('category')->group(function () {
    Route::get('all', 'index');
    Route::post('create', 'store')->middleware(['auth:sanctum']);
    Route::post('{id}', 'update')->middleware(['auth:sanctum']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum']);
});

///-------------- Product --------------///
Route::controller(ProductController::class)->prefix('product')->group(function () {
    Route::get('all', 'index');
    Route::get('{id}', 'show');
    Route::post('create', 'store')->middleware(['auth:sanctum']);
    Route::post('published/{id}', 'published')->middleware(['auth:sanctum', 'role:Administrateur']);
    Route::post('updateImage/{id}', 'updateImage')->middleware(['auth:sanctum']);
    Route::post('addImage/{id}', 'addImage')->middleware(['auth:sanctum']);
    Route::post('{id}', 'update')->middleware(['auth:sanctum']);
    Route::delete('deleteImage/{id}', 'deleteImage')->middleware(['auth:sanctum']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum']);
});

///-------------- Blog --------------///
Route::controller(BlogController::class)->prefix('blog')->group(function () {
    Route::get('all', 'index');
    Route::get('{id}', 'show');
    Route::post('create', 'store')->middleware(['auth:sanctum']);
    Route::post('published/{id}', 'published')->middleware(['auth:sanctum', 'role:Administrateur']);
    Route::post('updateImage/{id}', 'updateImage')->middleware(['auth:sanctum']);
    Route::post('addImage/{id}', 'addImage')->middleware(['auth:sanctum']);
    Route::post('{id}', 'update')->middleware(['auth:sanctum']);
    Route::delete('deleteImage/{id}', 'deleteImage')->middleware(['auth:sanctum']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum']);
});



///-------------- Commentaire --------------///
Route::controller(CommentController::class)->prefix('comment')->group(function () {
    Route::get('all', 'index');
    Route::get('{id}', 'show');
    Route::post('create', 'store');
    Route::post('published/{id}', 'published')->middleware(['auth:sanctum', 'role:Administrateur']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum']);
});


///-------------- Contact --------------///
Route::controller(ContactController::class)->prefix('contact')->group(function () {
    Route::get('all', 'index')->middleware(['auth:sanctum']);
    Route::post('create', 'store');
    Route::post('read/{id}', 'read')->middleware(['auth:sanctum']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum']);
});


///-------------- Service --------------///
Route::controller(ServiceController::class)->prefix('service')->group(function () {
    Route::get('all', 'index');
    Route::get('{id}', 'show');
    Route::post('create', 'store')->middleware(['auth:sanctum']);
    Route::post('published/{id}', 'published')->middleware(['auth:sanctum', 'role:Administrateur']);
    Route::post('updateImage/{id}', 'updateImage')->middleware(['auth:sanctum']);
    Route::post('{id}', 'update')->middleware(['auth:sanctum']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum']);
});

///-------------- partenaire Logo --------------///
Route::controller(PartenerLogoController::class)->prefix('partenerLogo')->group(function () {
    Route::get('all', 'index');
    Route::post('create', 'store')->middleware(['auth:sanctum']);
    Route::post('published/{id}', 'published')->middleware(['auth:sanctum', 'role:Administrateur']);
    Route::post('updateImage/{id}', 'updateImage')->middleware(['auth:sanctum']);
    Route::post('{id}', 'update')->middleware(['auth:sanctum']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum']);
});

///-------------- client --------------///
Route::controller(ClientController::class)->prefix('client')->group(function () {
    Route::get('all', 'index');
    Route::post('create', 'store')->middleware(['auth:sanctum']);
    Route::post('published/{id}', 'published')->middleware(['auth:sanctum', 'role:Administrateur']);
    Route::post('updateImage/{id}', 'updateImage')->middleware(['auth:sanctum']);
    Route::post('{id}', 'update')->middleware(['auth:sanctum']);
    Route::delete('{id}', 'destroy')->middleware(['auth:sanctum']);
});


///-------------- Log --------------///
Route::controller(LogController::class)->prefix('log')->group(function () {
    Route::get('all', 'index')->middleware(['auth:sanctum']);
    // Route::delete('{id}', 'destroy')->middleware(['auth:sanctum', 'role:Administrateur']);
});


///-------------- Newsletter --------------///
Route::controller(Newsletter::class)->prefix('newsletter')->group(function () {
    Route::get('all', 'index')->middleware(['auth:sanctum']);
    Route::post('/subscribe', 'subscribe');
    Route::delete('/unsubscribe', 'unsubscribe');
});


///-------------- Mail --------------///
Route::controller(MailController::class)->prefix('mail')->group(function () {
    Route::post('/send', 'sendNewsletter');
});

///-------------- Entities --------------///
Route::get('/entities-by-type', [EntityController::class, 'getEntitiesByType']);



