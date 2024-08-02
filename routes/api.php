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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware' => ['auth:sanctum', 'cors']], function(){
    
    Route::apiResources([
        'categories' => CategoryController::class,
        'places'     => PlaceController::class,
        'posts'      => PostController::class,
        'comments'   => CommentController::class
    ]);    

    // Route::get('/posts', [PostController::class, 'index']);
    // Route::get('/posts', [PostController::class, 'store']);
    // Route::get('/posts', [PostController::class, 'index']);
    // Route::get('/posts', [PostController::class, 'index']);
    // Route::get('/posts', [PostController::class, 'index']);
    
    Route::get('posts/{post}',  [PostController::class, 'show'])        ->withoutMiddleware(['auth:sanctum']);
    Route::get('posts/',        [PostController::class, 'index'])       ->withoutMiddleware(['auth:sanctum']);
    Route::get('comments/',     [CommentController::class, 'index'])    ->withoutMiddleware(['auth:sanctum']);
    Route::get('categories/',   [CommentController::class, 'index'])    ->withoutMiddleware(['auth:sanctum']);
    
    Route::get('/user/token/{token}', [UserController::class, 'getUserByToken'])->withoutMiddleware(['auth:sanctum']);;



    Route::get('/icons',            [UserController::class,        'icons']) ->withoutMiddleware(['auth:sanctum']);;
    Route::get('/user/{id}',        [UserController::class,        'show']);   
    Route::put('/user/{id}',        [UserController::class,        'update']);  
        
    
    Route::get('/logged', function () {
        return response()->json([
            'status' => true
        ],200);
    });
});

Route::post('/posts/image',       [PostController::class,  'upload']);
Route::post('/user/image',        [UserController::class,  'upload']);  // this route needs to be here

// it can't use Sanctum authentication, because this route just returns some image
Route::get('/user/image/{name}',  [UserController::class,        'getImage']);
Route::get('/post/image/{name}',  [PostController::class,        'getImage']);

Route::post('/register', [UserController::class,  'store'])   ->middleware('cors');
Route::post('/login',    [AuthController::class, 'login'])    ->middleware('cors');


Route::get('/places/comments/{name?}', [PostController::class, 'getAllCommentsByName'])  ->middleware('cors');
Route::get('/posts/people/{name}',     [PostController::class, 'getPeopleByName'])          ->middleware('cors');

