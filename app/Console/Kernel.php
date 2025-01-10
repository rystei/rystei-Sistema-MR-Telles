<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define as tarefas agendadas do aplicativo.
     */
    protected function schedule(Schedule $schedule)
    {
        // Executa o comando de notificação diariamente
        $schedule->command('notificacoes:enviar')->daily();
    }
    
    

    /**
     * Registra os comandos personalizados do console.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    
}
