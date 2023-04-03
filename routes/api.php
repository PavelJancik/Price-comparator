<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\ShopRecommendationController;
use App\Http\Controllers\ReviewController;

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

// Category
Route::get('/getCategoryTree', [CategoryController::class, 'getCategoryTree']);
Route::get('/getCategoryPath/{currentCategory}', [CategoryController::class, 'getCategoryPath']);
Route::get('/getDeepestProductCategory/{productName}', [CategoryController::class, 'getDeepestProductCategory']);
// Product
Route::get('/products/{category}', [ProductController::class, 'getProductsByCategory']);
Route::get('/getProductsByName/{name}', [ProductController::class, 'getProductsByName']);
Route::get('/getRandomProduct/{nameLike}', [ProductController::class, 'getRandomProductLike']);
Route::get('/getRandomProduct', [ProductController::class, 'getRandomProduct']);
// Manufacturers
Route::get('/getManufacturers/{category}', [ManufacturerController::class, 'getManufacturersInCategory']);
// Shop
Route::get('/item/getShopOptions/{productName}', [ShopController::class, 'getShopOptions']);
Route::get('/item/getSingleShopOption/{productName}/{shopName}', [ShopController::class, 'getSingleShopOption']);
// Shop Recommendation
Route::get('/solve', [ShopRecommendationController::class, 'solve']);
// Cart
Route::post('/cart/addItem/{productName}', [CartController::class, 'addProduct']);
Route::post('/cart/new', [CartController::class, 'createNewList']);
Route::delete('/cart/removeItem/{productName}', [CartController::class, 'removeProduct']);
// Parameter
Route::get('/getParams/{productName}', [ParameterController::class, 'getProductParams']);
Route::get('/getColors', [ParameterController::class, 'getColors']);
Route::get('/getSurface/{category}', [ParameterController::class, 'getSurface']);
Route::get('/getTypes/{category}', [ParameterController::class, 'getTypes']);
Route::get('/getMaterials/{paramName}/{category}', [ParameterController::class, 'getMaterials']);
// Review
Route::get('/getReviews/{productName}', [ReviewController::class, 'getReviews']);


// NOT FINAL
Route::get('/updateReviews', [ReviewController::class, 'updateReviews']);
Route::get('/generateFeeds', [FeedController::class, 'generateFeeds']);
Route::post('/feedUpdate', [FeedController::class, 'feedUpdate']);
// Route::get('/getParentCategory/{childCategoryName}', [CategoryController::class, 'getParentCategory']);

