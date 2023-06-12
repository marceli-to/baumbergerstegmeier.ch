<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\JobArticleController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmployeeCategoryController;
use App\Http\Controllers\Api\CvCategoryController;
use App\Http\Controllers\Api\CvController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware('auth:sanctum')->group(function() {
  Route::get('user', [UserController::class, 'find']);

  // Images
  Route::get('images', [ImageController::class, 'get']);
  Route::post('images/order', [ImageController::class, 'order']);
  Route::get('image/{image}', [ImageController::class, 'find']);
  Route::post('image/upload', [ImageController::class, 'upload']);
  Route::post('image', [ImageController::class, 'store']);
  Route::put('image/coords/{image}', [ImageController::class, 'coords']);
  Route::put('image/{image}', [ImageController::class, 'update']);
  Route::get('image/state/{image}', [ImageController::class, 'toggle']);
  Route::get('image/preview/state/{image}', [ImageController::class, 'preview']);
  Route::delete('image/{image}', [ImageController::class, 'destroy']);

  // Files
  Route::get('files', [FileController::class, 'get']);
  Route::post('files/order', [FileController::class, 'order']);
  Route::get('file/{file}', [FileController::class, 'find']);
  Route::post('file/upload', [FileController::class, 'upload']);
  Route::post('file', [FileController::class, 'store']);
  Route::put('file/{file}', [FileController::class, 'update']);
  Route::get('file/state/{file}', [FileController::class, 'toggle']);
  Route::delete('file/{file}', [FileController::class, 'destroy']);

  // Contact
  Route::controller(ContactController::class)->group(function () {
    Route::get('contact', 'get');
    Route::get('contact/{contact}', 'find');
    Route::post('contact', 'store');
    Route::put('contact/{contact}', 'update');
    Route::get('contact/state/{contact}', 'toggle');
    Route::delete('contact/{contact}', 'destroy');
  });

  // Job page
  Route::controller(JobController::class)->group(function () {
    Route::get('job', 'get');
    Route::get('job/{job}', 'find');
    Route::post('job', 'store');
    Route::put('job/{job}', 'update');
    Route::get('job/state/{job}', 'toggle');
    Route::delete('job/{job}', 'destroy');
  });

  // Job articles
  Route::controller(JobArticleController::class)->group(function () {
    Route::get('job-articles', 'get');
    Route::get('job-article/{jobArticle}', 'find');
    Route::post('job-article', 'store');
    Route::put('job-article/{jobArticle}', 'update');
    Route::get('job-article/state/{jobArticle}', 'toggle');
    Route::post('job-article/order', 'order');
    Route::delete('job-article/{jobArticle}', 'destroy');
  });

  // Awards
  Route::controller(AwardController::class)->group(function () {
    Route::get('awards', 'get');
    Route::get('award/{award}', 'find');
    Route::post('award', 'store');
    Route::put('award/{award}', 'update');
    Route::get('award/state/{award}', 'toggle');
    Route::post('award/order', 'order');
    Route::delete('award/{award}', 'destroy');
  });

  // Publications
  Route::controller(PublicationController::class)->group(function () {
    Route::get('publications', 'get');
    Route::get('publication/{publication}', 'find');
    Route::post('publication', 'store');
    Route::put('publication/{publication}', 'update');
    Route::get('publication/state/{publication}', 'toggle');
    Route::post('publication/order', 'order');
    Route::delete('publication/{publication}', 'destroy');
  });

  // Profile page
  Route::controller(ProfileController::class)->group(function () {
    Route::get('profile', 'get');
    Route::get('profile/{profile}', 'find');
    Route::post('profile', 'store');
    Route::put('profile/{profile}', 'update');
    Route::get('profile/state/{profile}', 'toggle');
    Route::delete('profile/{profile}', 'destroy');
  });

  // Teams
  Route::controller(TeamController::class)->group(function () {
    Route::get('teams', 'get');
    Route::get('team/{team}', 'find');
    Route::post('team', 'store');
    Route::put('team/{team}', 'update');
    Route::get('team/state/{team}', 'toggle');
    Route::post('team/order', 'order');
    Route::delete('team/{team}', 'destroy');
  });

  // Employee categories
  Route::controller(EmployeeCategoryController::class)->group(function () {
    Route::get('employee/categories', 'get');
    Route::get('employee/category/{employeeCategory}', 'find');
    Route::post('employee/category', 'store');
    Route::put('employee/category/{employeeCategory}', 'update');
    Route::get('employee/category/state/{employeeCategory}', 'toggle');
    Route::delete('employee/category/{employeeCategory}', 'destroy');
  });

  // Employees
  Route::controller(EmployeeController::class)->group(function () {
    Route::get('employees', 'get');
    Route::get('employee/{employee}', 'find');
    Route::post('employee', 'store');
    Route::put('employee/{employee}', 'update');
    Route::get('employee/state/{employee}', 'toggle');
    Route::post('employee/order', 'order');
    Route::delete('employee/{employee}', 'destroy');
  });

  // CV Categories
  Route::controller(CvCategoryController::class)->group(function () {
    Route::get('cv/categories', 'get');
    Route::get('cv/category/{cvCategory}', 'find');
    Route::post('cv/category', 'store');
    Route::put('cv/category/{cvCategory}', 'update');
    Route::get('cv/category/state/{cvCategory}', 'toggle');
    Route::post('cv/category/order', 'order');
    Route::delete('cv/category/{cvCategory}', 'destroy');
  });

  // CV
  Route::controller(CvController::class)->group(function () {
    Route::get('cv/{employee}', 'get');
    Route::get('cvs/{cv}', 'find');
    Route::post('cv', 'store');
    Route::put('cv/{cv}', 'update');
    Route::get('cv/state/{cv}', 'toggle');
    Route::post('cv/order', 'order');
    Route::delete('cv/{cv}', 'destroy');
  });

});



