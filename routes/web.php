<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\WorklistController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

// Auth
Route::middleware('guest')->group(function () {
  Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
  Route::post('/login', [AuthController::class, 'login']);
  Route::get('/password/reset', [AuthController::class, 'showForgotPassword'])->name('password.request');
  Route::post('/password/email', [AuthController::class, 'sendResetLink'])->middleware('throttle:6,1')->name('password.email');
  Route::get('/password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
  Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// The Vue 2 admin header links to GET /logout; goes with the Vue 3 port
Route::get('/logout', [AuthController::class, 'logout']);

// Images (former marceli-to/image-cache URLs, served through Glide)
Route::get('/img/original/{filename}', [ImageController::class, 'original']);
Route::get('/img/thumbnail/{filename}', [ImageController::class, 'thumbnail']);
Route::get('/img/crop/{filename}/{maxSize?}/{coords?}/{ratio?}', [ImageController::class, 'crop']);

// Frontend
Route::get('/', [HomeController::class, 'index'])->name('page.home');

Route::get('/projekte', [ProjectController::class, 'index'])->name('page.projects');

Route::get('/projekte/uebersicht/{state:slug}', [ProjectController::class, 'showLandingByState'])->name('page.project.showLandingByState');
Route::get('/projekte/uebersicht/{state:slug}/{category:slug}', [ProjectController::class, 'showLandingByCategory'])->name('page.project.showLandingByCategory');

Route::get('/projekte/{state:slug}/{project:slug}', [ProjectController::class, 'showByState'])->name('page.project.showByState');
Route::get('/projekte/{state:slug}/{category:slug}/{project:slug}', [ProjectController::class, 'showByStateAndCategory'])->name('page.project.showByStateAndCategory');

Route::get('/projekt/vorschau/{project}', [ProjectController::class, 'preview'])->name('page.project.preview');

Route::get('/werkliste/jahr', [WorklistController::class, 'byYear'])->name('page.worklist.year');
Route::get('/werkliste/{searchTerm?}', [WorklistController::class, 'index'])->name('page.worklist');
Route::get('/werkliste/status/{state:slug}', [WorklistController::class, 'byState'])->name('page.worklist.state');
Route::get('/werkliste/programm/{category:slug}', [WorklistController::class, 'byCategory'])->name('page.worklist.category');
Route::get('/werkliste/programm/{category:slug}/status/{state:slug}', [WorklistController::class, 'byCategoryAndState'])->name('page.worklist.category.state');

Route::get('/buero/profil', [OfficeController::class, 'profile'])->name('page.office.profile');
Route::get('/buero/team', [OfficeController::class, 'team'])->name('page.office.team');
Route::get('/buero/publikationen', [OfficeController::class, 'publications'])->name('page.office.publications');
Route::get('/buero/auszeichnungen', [OfficeController::class, 'awards'])->name('page.office.awards');
Route::get('/buero/jobs', [OfficeController::class, 'jobs'])->name('page.office.jobs');

Route::get('/kontakt', [ContactController::class, 'index'])->name('page.contact');


/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:sanctum')->group(function() {
  Route::get('administration/{any?}', function () {
    return view('layout.authenticated');
  })->where('any', '.*')->middleware('role:admin')->name('authenticated');
});
