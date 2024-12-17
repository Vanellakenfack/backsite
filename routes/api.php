<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\admin\ProjectController ;
use App\Http\Controllers\admin\TestimonialController ;
use App\Http\Controllers\admin\ArticleController ;
use App\Http\Controllers\front\ProjectsController  as FrontProjectController;
use App\Http\Controllers\front\ArticleController  as FrontArticleController;

// Route pour l'authentification
Route::post('authentificate', [AuthentificationController::class, 'authentificate'])->name('api.authentificate');
//Route::get('get-services',[FrontServiceController ::class,'index']);

//route non protegeees
Route::get('latest-testimùonials', [FrontTestimonialController::class, 'latesttestimonials']);
Route::get('latest-projects', [FrontProjectController::class, 'latestprojects']);
Route::get('get-projects', [FrontProjectController::class, 'index']);
Route::get('latest-articles', [FrontArticleController::class, 'latestparticles']);
Route::get('get-articles', [FrontArticleController::class, 'index']);
// Route protégée, nécessite une authentification
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('api.index');
    
    Route::get('logout', [AuthentificationController::class, 'logout']);
    //services routes
    Route::post('services', [ServiceController::class, 'store']);
    Route::get('services', [ServiceController::class, 'index']);
    Route::put ('services/{id}', [ServiceController::class, 'update']);
    Route::get ('services/{id}', [ServiceController::class, 'show']);
    Route::delete ('services/{id}', [ServiceController::class, 'destroy']);

   //projects routes
   Route::post('projects', [ProjectController::class, 'store']);
   Route::get('projects', [ProjectController::class, 'index']);
   Route::put('projects/{id}', [ProjectController::class, 'update']);
   Route::get('/projets/{id}', [ProjectController::class, 'show']);
   Route::delete('projects/{id}', [ProjectController::class, 'destroy']);
  
   // route articles
   Route::get('articles', [ArticleController::class, 'index']);
   Route::post('articles', [ArticleController::class, 'store']);
   Route::put('articles/{id}', [ArticleController::class, 'update']);
   Route::get('/articles/{id}', [ArticleController::class, 'show']);
   Route::delete('articles/{id}', [ArticleController::class, 'destroy']);

     // route testimonial
     Route::get('testimonials', [TestimonialController::class, 'index']);
     Route::post('testimonials', [TestimonialController::class, 'store']);
     Route::put('testimonials/{id}', [TestimonialController::class, 'update']);
     Route::get('/testimonials/{id}', [TestimonialController::class, 'show']);
     Route::delete('testimonials/{id}', [TestimonialController::class, 'destroy']);
  
    //routes images
    Route::post('temp-images', [TempImageController::class, 'store']);


});