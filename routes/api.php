<?php

use App\Http\Controllers\api\ApiCalendarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiEventController;
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
//////////////////// ----------Calendar module----------  ////////////////////
Route::get('/calendars', [ApiCalendarController::class, 'index']);
Route::get('/calendars/{id}', [ApiCalendarController::class, 'show']);
Route::get('calendar/{id}/events', [ApiCalendarController::class, 'showEvents']);
Route::patch('/calendars/{id}', [ApiCalendarController::class, 'update']);
Route::post('/calendars', [ApiCalendarController::class, 'store']);
Route::delete('/calendars/{id}', [ApiCalendarController::class, 'destroy']);
//////////////////// ----------Events module----------  ////////////////////
Route::patch('events/{id}', [ApiEventController::class, 'update']);
