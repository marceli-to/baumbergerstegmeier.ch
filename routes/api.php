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

  // Job articles
  Route::controller(AwardController::class)->group(function () {
    Route::get('awards', 'get');
    Route::get('award/{award}', 'find');
    Route::post('award', 'store');
    Route::put('award/{award}', 'update');
    Route::get('award/state/{award}', 'toggle');
    Route::post('award/order', 'order');
    Route::delete('award/{award}', 'destroy');
  });

});



