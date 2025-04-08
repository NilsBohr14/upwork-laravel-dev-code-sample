<?php

declare(strict_types=1);

use App\Http\Controllers\ClinicBudgetSettingsController;
use Illuminate\Support\Facades\Route;

Route::put('/clinics/{clinic}/budget-settings', [ClinicBudgetSettingsController::class, 'update'])->middleware(['auth:sanctum']);
Route::get('/clinics/{clinic}/budget-settings', [ClinicBudgetSettingsController::class, 'show'])->middleware(['auth:sanctum']);