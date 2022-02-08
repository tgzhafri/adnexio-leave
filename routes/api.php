<?php

use App\Http\Controllers\ApprovalConfigController;
use App\Http\Controllers\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveEligibilityController;

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

Route::apiResource('/employee', EmployeeController::class);
Route::apiResource('/company', CompanyController::class);
Route::apiResource('/leaveEligibility', LeaveEligibilityController::class);
Route::apiResource('/approvalConfig', ApprovalConfigController::class);
