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
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\TeaserController;
use App\Http\Controllers\Api\SettingsController;

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
  Route::get('image/cover/state/{image}', [ImageController::class, 'cover']);
  Route::get('image/worklist/state/{image}', [ImageController::class, 'worklist']);
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

  // Project type
  Route::controller(TypeController::class)->group(function () {
    Route::get('project/types', 'get');
    Route::get('project/type/{type}', 'find');
    Route::post('project/type', 'store');
    Route::put('project/type/{type}', 'update');
    Route::get('project/type/state/{type}', 'toggle');
    Route::post('project/type/order', 'order');
    Route::delete('project/type/{type}', 'destroy');
  });

  // Project state
  Route::controller(StateController::class)->group(function () {
    Route::get('project/states', 'get');
    Route::get('project/state/{state}', 'find');
    Route::post('project/state', 'store');
    Route::put('project/state/{state}', 'update');
    Route::get('project/state/state/{state}', 'toggle');
    Route::post('project/state/order', 'order');
    Route::delete('project/state/{state}', 'destroy');
  });

  // Project category
  Route::controller(CategoryController::class)->group(function () {
    Route::get('project/categories', 'get');
    Route::get('project/category/{category}', 'find');
    Route::post('project/category', 'store');
    Route::put('project/category/{category}', 'update');
    Route::get('project/category/state/{category}', 'toggle');
    Route::post('project/category/order', 'order');
    Route::delete('project/category/{category}', 'destroy');
  });

  // Projects
  Route::controller(ProjectController::class)->group(function () {
    Route::get('projects', 'get');
    Route::get('project/{project}', 'find');
    Route::post('project', 'store');
    Route::put('project/{project}', 'update');
    Route::get('project/toggle/{project}', 'toggle');
    Route::post('project/order', 'order');
    Route::delete('project/{project}', 'destroy');
  });

  // Articles
  Route::controller(ArticleController::class)->group(function () {
    Route::get('articles', 'get');
    Route::get('article/{article}', 'find');
    Route::post('article', 'store');
    Route::put('article/{article}', 'update');
    Route::get('article/state/{article}', 'toggle');
    Route::delete('article/{article}', 'destroy');
  });

  // Articles
  Route::controller(TeaserController::class)->group(function () {
    Route::get('teasers/{type}/{projectId?}', 'get');
    Route::post('teaser', 'store');
    Route::get('teaser/state/{teaser}', 'toggle');
    Route::post('teaser/order', 'order');
    Route::delete('teaser/{teaser}', 'destroy');
  });

  // Publications
  Route::controller(SettingsController::class)->group(function () {
    Route::get('linklist', 'get');
  });


});



