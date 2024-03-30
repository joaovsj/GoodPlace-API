<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\PlaceController;
use \App\Http\Controllers\PostController;
use \App\Http\Controllers\CommentController;
use \App\Http\Controllers\UserController;

use \App\Http\Middleware\Cors;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['auth:sanctum', 'cors']], function(){
    
    Route::apiResources([
        'categories' => CategoryController::class,
        'places'     => PlaceController::class,
        'posts'      => PostController::class,
        'comments'   => CommentController::class
    ]);    
});


// Route::get('/', function(){
//     return response()->json(['status'=> true]);
// });

Route::post('/register', [UserController::class, 'store'])    ->middleware('cors');
Route::get('/user/{id}',      [UserController::class, 'show'])     ->middleware('cors');

Route::post('/login',    [AuthController::class, 'login'])    ->middleware('cors');


Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome!'
    ],200);
})->middleware('cors');
