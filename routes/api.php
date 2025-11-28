<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HealthProfessionalController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('services', [ServiceController::class, 'index']);
Route::get('services/{service}/health_professionals', [ServiceController::class, 'healthProfessionals']);
Route::get('health_professionals', [HealthProfessionalController::class, 'index']);

Route::post('appointment', [AppointmentController::class, 'create']);
