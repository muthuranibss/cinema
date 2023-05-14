<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/v1/longest-duration-movies', [ApiController::class,'longest_duration_movies']);
Route::get('/v1/top-rated-movies', [ApiController::class,'top_rated_movies']);
Route::get('/v1/genre-movies-with-subtotals', [ApiController::class,'genre_movies_with_subtotals']);
Route::post('/v1/new-movie', [ApiController::class,'new_movie']);
Route::post('/v1/update-runtime-minutes', [ApiController::class,'update_runtime_minutes']);

