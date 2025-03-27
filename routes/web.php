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
use App\Http\Middleware\IsAdmin;


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

    
    Route::prefix('financeiro')->group(function () {
        Route::get('/', [App\Http\Controllers\GerenciarRecursosFinanceiro::class, 'index'])->name('financeiro');
        Route::post('/calcular', [App\Http\Controllers\GerenciarRecursosFinanceiro::class, 'calculate'])->name('financeiro.calculate');
        Route::delete('/pix/{id}', [App\Http\Controllers\GerenciarRecursosFinanceiro::class, 'destroy'])->name('financeiro.pix.delete');
    });
    
    Route::prefix('controle-financeiro')->group(function () {
        Route::get('/', [ControleFinanceiroController::class, 'index'])->name('controle_financeiro.index')->middleware(IsAdmin::class);;
        Route::get('/create', [ControleFinanceiroController::class, 'create'])->name('controle_financeiro.create');
        Route::post('/', [ControleFinanceiroController::class, 'store'])->name('controle_financeiro.store');
        Route::patch('/{id}/atualizar-status', [ControleFinanceiroController::class, 'atualizarStatus'])->name('controle_financeiro.atualizarStatus');
        Route::delete('/{id}', [ControleFinanceiroController::class, 'destroy'])->name('controle_financeiro.destroy');
        Route::get('/search', [ControleFinanceiroController::class, 'search'])->name('controle_financeiro.search');
        Route::get('/minhas-parcelas', [ControleFinanceiroController::class, 'minhasParcelas'])->name('controle_financeiro.minhas');
        //pagamento pix parcelas
        Route::get('/pagamento', [ControleFinanceiroController::class, 'pagamento'])->name('pagamento');
        Route::post('/gerar-pix/{parcela}', [ControleFinanceiroController::class, 'gerarPix'])->name('gerar-pix');
    });

        //Gerenciar eventos
        Route::get('/AgendarCompromissos', [ScheduleController::class, 'index'])->name('agendar_compromissos')->middleware(IsAdmin::class);;
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
        Route::get('/processos', [ProcessoController::class, 'index'])->name('processos.index')->middleware(IsAdmin::class);;
        Route::post('/processos', [ProcessoController::class, 'store'])->name('processos.store');
        Route::get('/meus-processos', [ProcessoController::class, 'meusProcessos'])->name('processos.meus');
        Route::get('/meus-processos/{processo}', [ProcessoController::class, 'meusProcessosDetalhes'])->name('processos.meusDetalhes');
        Route::get('/{processo}/status', [ProcessoController::class, 'editStatus'])->name('processos.editStatus');
        Route::put('/{processo}/status', [ProcessoController::class, 'updateStatus'])->name('processos.updateStatus');
        Route::delete('/processos/{processo}/historico/{historico}', [ProcessoController::class, 'deleteHistorico'])->name('processos.deleteHistorico');
        Route::delete('/processos/{processo}', [ProcessoController::class, 'destroy'])->name('processos.destroy');




});

// =============================================
// Rotas Públicas (Fora do grupo de autenticação)
// =============================================
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes/store', [ClienteController::class, 'store'])->name('clientes.store');
require __DIR__.'/auth.php';