<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\WorklistController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TestController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

// Auth routes
Auth::routes(['verify' => true, 'register' => false]);
Route::get('/logout', 'Auth\LoginController@logout');

// Frontend - Home
Route::get('/', [HomeController::class, 'index'])->name('page.home');

Route::get('/projekte', [ProjectController::class, 'index'])->name('page.projects');
Route::get('/projekte/{state:slug}/{category:slug}/{project:slug}', [ProjectController::class, 'show'])->name('page.project.show');
Route::get('/projekt/vorschau/{project}', [ProjectController::class, 'preview'])->name('page.project.preview');

Route::get('/werkliste', [WorklistController::class, 'index'])->name('page.worklist');
Route::get('/werkliste/jahr', [WorklistController::class, 'byYear'])->name('page.worklist.year');
Route::get('/werkliste/suche/{searchTerm}', [WorklistController::class, 'search'])->name('page.worklist.search');
Route::get('/werkliste/status/{state:slug}', [WorklistController::class, 'byState'])->name('page.worklist.state');
Route::get('/werkliste/programm/{category:slug}', [WorklistController::class, 'byCategory'])->name('page.worklist.category');

Route::get('/buero/profil', [OfficeController::class, 'profile'])->name('page.office.profile');
Route::get('/buero/team', [OfficeController::class, 'team'])->name('page.office.team');
Route::get('/buero/publikationen', [OfficeController::class, 'publications'])->name('page.office.publications');
Route::get('/buero/auszeichnungen', [OfficeController::class, 'awards'])->name('page.office.awards');
Route::get('/buero/jobs', [OfficeController::class, 'jobs'])->name('page.office.jobs');

Route::get('/kontakt', [ContactController::class, 'index'])->name('page.contact');


// Frontend - url based images
Route::get('/img/{template}/{filename}/{maxSize?}/{coords?}', [ImageController::class, 'getResponse']);

// Testing
Route::get('/test', [TestController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:sanctum', 'verified')->group(function() {
  Route::get('administration/{any?}', function () {
    return view('layout.authenticated');
  })->where('any', '.*')->middleware('role:admin')->name('authenticated');
});


