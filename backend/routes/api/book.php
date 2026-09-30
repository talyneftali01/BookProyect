<?php

use App\Http\Controllers\Api\BookController;
use Illuminate\Support\Facades\Route;

Route::prefix('/books')-> group(function(){

    Route::middleware('auth:sanctum')->group(function () {
        // Operaciones de Libros
        Route::get('/', [BookController::class, 'listActive']);
        Route::get('/listgeners',[BookController::class,'listGeners']);
        Route::get('/all', [BookController::class, 'listAll']); // Historial para Activity
        Route::post('/', [BookController::class, 'addBook']);
        Route::put('/{id}', [BookController::class, 'updateBook']);
        Route::delete('/{id}', [BookController::class, 'deleteBook']);

        // Interacciones operativas
        Route::post('/{id}/calificar', [BookController::class, 'calificar']);
        Route::post('/{id}/favorite', [BookController::class, 'favorite']);
        Route::post('/{id}/report', [BookController::class, 'report']);
    });
});
