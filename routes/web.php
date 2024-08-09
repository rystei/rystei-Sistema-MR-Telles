<?php

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TesteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GerenciarRecursosFinanceiro; 


Route::get('/', function () {
    return view('welcome');
});

// Rota para a view de gerenciamento de recursos financeiros
Route::get('/financeiro', function () {
    return view('financeiro.index');
});
Route::post('/financeiro/calculate', [GerenciarRecursosFinanceiro::class, 'calculate']);

//Gerenciar compromissos
Route::get('/AgendarCompromissos',[ScheduleController::class, 'index']);
Route::get('/AgendarCompromissos', [ScheduleController::class, 'index'])->name('agendar_compromissos');
Route::get('/events', [ScheduleController::class, 'getEvents']);

Route::delete('/schedule/{id}',[ScheduleController::class, 'deleteEvent']);

Route::put('/schedule/{id}', [ScheduleController::class, 'update']);

Route::put('/schedule/{id}/resize', [ScheduleController::class, 'resize']);

Route::get('/events/search',[ScheduleController::class, 'search']);

Route::view('add-schedule', 'schedule.add');
Route::post('create-schedule', [ScheduleController::class, 'create']);

Route::get('/adicionar',[ScheduleController::class, 'add']);
