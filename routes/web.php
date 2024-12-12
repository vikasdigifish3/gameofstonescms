<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ContinentController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CountryContentController;
use App\Http\Controllers\ContentCountryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageContentController;
use App\Http\Controllers\CategoryLanguageController;
use App\Http\Controllers\ContentAPI;

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


Route::get('delivery/api', [ContentAPI::class, 'index']);


Route::prefix('categories/{categoryId}')->group(function () {
    Route::get('category_language/create', [CategoryLanguageController::class, 'create'])->name('category_language.create');
    Route::post('category_language', [CategoryLanguageController::class, 'store'])->name('category_language.store');
    Route::get('category_language/edit', [CategoryLanguageController::class, 'edit'])->name('category_language.edit');
    Route::put('category_language', [CategoryLanguageController::class, 'update'])->name('category_language.update');
});


Route::get('/portals/{id}/edit-categories', [PortalController::class, 'editCategories'])->name('portals.editCategories');
Route::put('/portals/{id}/update-categories', [PortalController::class, 'updateCategories'])->name('portals.updateCategories');


Route::put('categories/{id}/update-portals', [CategoryController::class, 'updatePortals'])->name('categories.updatePortals');
Route::get('categories/{id}/portals', [CategoryController::class, 'allPortals'])->name('categories.allPortals');
Route::post('categories/{id}/multi-attach', [CategoryController::class, 'multiAttach'])->name('categories.multiAttach');
Route::post('categories/{id}/multi-detach', [CategoryController::class, 'multiDetach'])->name('categories.multiDetach');


Route::resource('continents', ContinentController::class);
Route::resource('countries', CountryController::class);

Route::post('countries/{country}/contents/attach', [CountryContentController::class, 'attach'])->name('countries.contents.attach');

// Route for detaching content from a country
Route::post('countries/{country}/contents/detach', [CountryContentController::class, 'detach'])->name('countries.contents.detach');

Route::resource('contents', ContentController::class);
Route::post('countries/{country}/contents/attachMultiple', [CountryContentController::class, 'attachMultiple'])
    ->name('countries.contents.attachMultiple');
Route::get('countries/{country}/contents', [CountryContentController::class, 'attachMultiple'])
    ->name('countries.contents');

Route::get('contents/{content}/countries', [ContentCountryController::class, 'edit'])->name('contents.countries.edit');
Route::post('contents/{content}/countries', [ContentCountryController::class, 'update'])->name('contents.countries.update');

Route::get('/portals/{id}/edit-contents', [PortalController::class, 'editContents'])->name('portals.editContents');
//Route::post('/portals/{id}/attach-contents', [PortalController::class, 'attachContents'])->name('portals.attachContents');


Route::put('/portals/{id}/update-contents', [PortalController::class, 'updateContents'])->name('portals.updateContents');
Route::post('/portals/{id}/multi-attach-contents', [PortalController::class, 'multiAttachContents'])->name('portals.multiAttachContents');
Route::post('/portals/{id}/multi-detach-contents', [PortalController::class, 'multiDetachContents'])->name('portals.multiDetachContents');


Route::get('/contents/{id}/edit-portals', [ContentController::class, 'editPortals'])->name('contents.editPortals');
Route::put('/contents/{id}/update-portals', [ContentController::class, 'updatePortals'])->name('contents.updatePortals');
Route::post('/contents/{id}/multi-attach-portals', [ContentController::class, 'multiAttachPortals'])->name('contents.multiAttachPortals');
Route::post('/contents/{id}/multi-detach-portals', [ContentController::class, 'multiDetachPortals'])->name('contents.multiDetachPortals');


Route::get('/contents/{content}/create-language-contents', [LanguageContentController::class, 'create'])->name('languageContents.create');
Route::post('/contents/{contentId}/store-language-contents', [LanguageContentController::class, 'store'])->name('languageContents.store');


Route::get('contents/{contentId}/language-contents/edit', [LanguageContentController::class, 'edit'])->name('languageContents.edit');
Route::any('contents/{contentId}/language-contents/update', [LanguageContentController::class, 'update'])->name('languageContents.update');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
});


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('categories', CategoryController::class)->names('categories');
    Route::resource('contents', ContentController::class)->names('contents');
    Route::resource('languages', LanguageController::class)->names('languages');
    Route::resource('portals', PortalController::class)->names('portals');

});


Route::get('/notifications/create', [NotificationController::class, 'showForm'])->name('notifications.create');
Route::post('/notifications/send', [NotificationController::class, 'sendNotification'])->name('notifications.send');
Route::resource('tags', TagController::class);
