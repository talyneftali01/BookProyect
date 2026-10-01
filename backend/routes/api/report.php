<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportController;

Route::prefix('/reports')-> group(function(){

    Route::middleware('auth:sanctum')->group(function () {
        // Operaciones de Libros
        Route::get('/', [ReportController::class, 'listReport']);
        Route::get('/denuncias',[ReportController::class,'listReportBook']);
    });
});