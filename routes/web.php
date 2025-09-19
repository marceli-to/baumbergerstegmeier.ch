<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
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

// Auth routes
Auth::routes(['verify' => true, 'register' => false]);
Route::get('/logout', 'Auth\LoginController@logout');

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

Route::middleware('auth:sanctum', 'verified')->group(function() {
  Route::get('administration/{any?}', function () {
    return view('layout.authenticated');
  })->where('any', '.*')->middleware('role:admin')->name('authenticated');
});
