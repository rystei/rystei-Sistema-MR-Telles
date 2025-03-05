<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TesteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GerenciarRecursosFinanceiro;
use App\Http\Controllers\PixWebhookController;
use App\Http\Controllers\ControleFinanceiroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ProcessoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grupo principal para rotas autenticadas e verificadas
Route::middleware(['auth', 'verified'])->group(function () {

    // Rotas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Rotas financeiras
    Route::get('/financeiro', function () {
        return view('financeiro.index');
    })->name('financeiro');
    
    Route::post('/financeiro/calculate', [GerenciarRecursosFinanceiro::class, 'calculate']);
    
    Route::prefix('controle-financeiro')->group(function () {
        Route::get('/', [ControleFinanceiroController::class, 'index'])->name('controle_financeiro.index');
        Route::get('/create', [ControleFinanceiroController::class, 'create'])->name('controle_financeiro.create');
        Route::post('/', [ControleFinanceiroController::class, 'store'])->name('controle_financeiro.store');
        Route::patch('/{id}/atualizar-status', [ControleFinanceiroController::class, 'atualizarStatus'])->name('controle_financeiro.update_status');
    });

        //Gerenciar eventos
        Route::get('/AgendarCompromissos', [ScheduleController::class, 'index'])->name('agendar_compromissos');
        Route::get('/events', [ScheduleController::class, 'getEvents']);
        Route::put('/events/update/{id}', [ScheduleController::class, 'updateEvent']);
        Route::delete('/events/delete/{id}', [ScheduleController::class, 'deleteEvent']);
        Route::put('/schedule/{id}', [ScheduleController::class, 'update']);
        Route::put('/schedule/{id}/resize', [ScheduleController::class, 'resize']);
        Route::get('/events/search', [ScheduleController::class, 'search']);
        Route:: view('add-schedule', 'schedule.add');
        Route::post('create-schedule', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::get('/adicionar', [ScheduleController::class, 'add']);



        // Marcar consulta
        Route::get('/marcar_consulta', [ScheduleController::class, 'consultationIndex'])->name('marcar_consulta');
        Route::get('/consultations', [ScheduleController::class, 'listConsultationsByDate'])->name('consultations.get');
        Route::post('/consultations', [ScheduleController::class, 'store'])->name('consultations.store');
        Route::put('/consultations/{id}', [ScheduleController::class, 'updateEvent'])->name('consultations.update');
        Route::delete('/consultations/{id}', [ScheduleController::class, 'deleteEvent'])->name('consultations.delete');
        Route::get('/consultations/list', [ScheduleController::class, 'listConsultationsByDate'])->name('consultations.list'); // se necessário
        Route::get('/api/available-slots', [ScheduleController::class, 'availableSlots'])->name('consultations.available');

        
        // Listar todos os processos
        Route::get('/processos', [ProcessoController::class, 'index'])->name('processos.index');
        Route::post('/processos', [ProcessoController::class, 'store'])->name('processos.store');
        Route::get('/meus-processos', [ProcessoController::class, 'meusProcessos'])->name('processos.meus');



});

// =============================================
// Rotas Públicas (Fora do grupo de autenticação)
// =============================================
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes/store', [ClienteController::class, 'store'])->name('clientes.store');
require __DIR__.'/auth.php';