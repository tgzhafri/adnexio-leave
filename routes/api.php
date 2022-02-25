<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ApprovalRouteController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EntitlementController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveEntitlementController;
use App\Http\Controllers\LeavePolicyController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\WorkdayController;

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

Route::get('/employee/organisationChart', [EmployeeController::class, 'orgChart']);
Route::apiResource('/employee', EmployeeController::class);
Route::apiResource('/company', CompanyController::class);
Route::apiResource('/workday', WorkdayController::class);
Route::apiResource('/holiday', HolidayController::class);
Route::apiResource('/leavePolicy', LeavePolicyController::class);
Route::apiResource('/entitlement', EntitlementController::class);
Route::apiResource('/leaveEntitlement', LeaveEntitlementController::class);
Route::apiResource('/approvalRoute', ApprovalRouteController::class);
Route::apiResource('/approval', ApprovalController::class);
Route::apiResource('/leaveRequest', LeaveRequestController::class);
