<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TesteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GerenciarRecursosFinanceiro;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PixWebhookController;
use App\Http\Controllers\ControleFinanceiroController;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // Rotas relacionadas ao perfil de usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rota para a view financeiros
    Route::get('/financeiro', function () {
        return view('financeiro.index');
    });
    Route::post('/financeiro/calculate', [GerenciarRecursosFinanceiro::class, 'calculate']);

    Route::get('/controle-financeiro', [ControleFinanceiroController::class, 'index'])->name('controle_financeiro.index');
    Route::get('/controle-financeiro/create', [ControleFinanceiroController::class, 'create'])->name('controle_financeiro.create');
    Route::post('/controle-financeiro', [ControleFinanceiroController::class, 'store'])->name('controle_financeiro.store');
    Route::patch('/controle-financeiro/{id}/atualizar-status', [ControleFinanceiroController::class, 'atualizarStatus'])->name('controle_financeiro.update_status');


    // Gerenciar compromissos
    Route::get('/AgendarCompromissos', [ScheduleController::class, 'index'])->name('agendar_compromissos');
    Route::get('/events', [ScheduleController::class, 'getEvents']);
    Route::delete('/schedule/{id}', [ScheduleController::class, 'deleteEvent']);
    Route::put('/schedule/{id}', [ScheduleController::class, 'update']);
    Route::put('/schedule/{id}/resize', [ScheduleController::class, 'resize']);
    Route::get('/events/search', [ScheduleController::class, 'search']);
    Route::view('add-schedule', 'schedule.add');
    Route::post('create-schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::get('/adicionar', [ScheduleController::class, 'add']);
});

    // teste cliente
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes/store', [ClienteController::class, 'store'])->name('clientes.store');


require __DIR__.'/auth.php';
