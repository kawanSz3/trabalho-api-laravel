<?php

use App\Http\Controllers\Api\V1\ClimaController;
use App\Http\Controllers\Api\V1\TarefaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::apiResource('tarefas', TarefaController::class);
    Route::get('clima', ClimaController::class)->name('clima.show');
});
