<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ScheduleController extends Controller
{
    // Exibe a página principal do calendário
    public function index()
    {
        return view('schedule.index');
    }

    public function consultationIndex()
    {
        return view('schedule.consultations');
    }

    // Criação de um evento
    public function create(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $schedule = new Schedule($validated);
        $schedule->save();

        return redirect('/AgendarCompromissos');
    }

    // Retorna todos os eventos em formato JSON para o FullCalendar
    public function getEvents()
    {
        $schedules = Schedule::all();
    
        $schedules->transform(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->all_day ? Carbon::parse($event->start)->format('Y-m-d') : $event->start,
                'end' => $event->all_day ? Carbon::parse($event->start)->addDay()->format('Y-m-d') : $event->end,
                'allDay' => (bool) $event->all_day,
                'description' => $event->description,
                'color' => $event->color,
            ];
        });
    
        return response()->json($schedules);
    }
    
    
    

    // Deleta um evento pelo ID
    public function deleteEvent($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
    
        return response()->json(['message' => 'Evento deletado com sucesso']);
    }

        // Atualiza um evento
            public function updateEvent(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        // Determine se o evento é all-day com base no checkbox
        $allDay = filter_var($request->input('all_day', false), FILTER_VALIDATE_BOOLEAN);

        // Defina regras de validação de acordo com o tipo de evento
        if ($allDay) {
            $rules = [
                'title'       => 'required|string|max:255',
                'start'       => 'required|date_format:Y-m-d', // Ex: "2025-02-10"
                'end'         => 'nullable|date_format:Y-m-d|after_or_equal:start',
                'description' => 'nullable|string',
                'color'       => 'nullable|string',
                'all_day'     => 'sometimes|boolean'
            ];
        } else {
            $rules = [
                'title'       => 'required|string|max:255',
                'start'       => 'required|date_format:Y-m-d\TH:i', // Ex: "2025-02-10T14:30"
                'end'         => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start',
                'description' => 'nullable|string',
                'color'       => 'nullable|string',
                'all_day'     => 'sometimes|boolean'
            ];
        }

        $validated = $request->validate($rules);

        // Converte as datas usando Carbon
        if ($allDay) {
            // Para evento all-day, usa o formato "Y-m-d"
            $start = Carbon::createFromFormat('Y-m-d', $validated['start'])->startOfDay();
            $end = !empty($validated['end'])
                ? Carbon::createFromFormat('Y-m-d', $validated['end'])->endOfDay()
                : $start->copy()->endOfDay();
        } else {
            // Para evento com horário, usa o formato "Y-m-d\TH:i"
            $start = Carbon::createFromFormat('Y-m-d\TH:i', $validated['start']);
            $end = !empty($validated['end'])
                ? Carbon::createFromFormat('Y-m-d\TH:i', $validated['end'])
                : $start->copy();
        }

        $schedule->update([
            'title'       => $validated['title'],
            'start'       => $start->toDateTimeString(), // "Y-m-d H:i:s"
            'end'         => $end->toDateTimeString(),
            'description' => $validated['description'] ?? null,
            'color'       => $validated['color'],
            'all_day'     => $allDay ? 1 : 0,
        ]);

        return response()->json(['message' => 'Evento atualizado com sucesso']);
    }    

    // Atualiza as datas de um evento arrastado
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'nullable|date_format:Y-m-d H:i',
            'end' => 'nullable|date_format:Y-m-d H:i|after_or_equal:start',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'all_day' => 'sometimes|boolean' // Usa 'sometimes' para evitar erro caso o campo não seja enviado
        ]);
        
        $validated['all_day'] = $request->has('all_day') ? 1 : 0; // Converte checkbox para booleano (1 ou 0)
        
        $schedule->update([
            'start' => Carbon::parse($validated['start_date'])->setTimezone('UTC'),
            'end' => isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->setTimezone('UTC') : null,
        ]);

        return response()->json(['message' => 'Evento movido com sucesso']);
    }

    // Redimensiona a duração de um evento
    public function resize(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'end_date' => 'required|date|after:start_date',
        ]);

        $schedule->update(['end' => Carbon::parse($validated['end_date'])->setTimezone('UTC')]);

        return response()->json(['message' => 'Evento redimensionado com sucesso']);
    }

    // Busca eventos com base no título
    public function search(Request $request)
    {
        $searchKeywords = strtolower($request->input('title')); // converte para minúsculas
    
        $matchingEvents = Schedule::whereRaw("LOWER(title) LIKE ?", ['%' . $searchKeywords . '%'])
            ->orWhereRaw("LOWER(description) LIKE ?", ['%' . $searchKeywords . '%'])
            ->get();
    
        return response()->json($matchingEvents);
    }
    

    // Criação de um evento via requisição AJAX
    public function availableSlots(Request $request)
    {
        $timezone = config('app.timezone'); // Ex: 'America/Sao_Paulo'
        $date = Carbon::parse($request->input('date'))->setTimezone($timezone);
    
        $booked = Schedule::whereDate('start', $date->toDateString())
            ->where('title', 'Consulta')
            ->get()
            ->map(function ($s) use ($timezone) {
                return Carbon::parse($s->start)
                    ->setTimezone($timezone)
                    ->format('H:i');
            })
            ->toArray();
    
        $allSlots = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00'];
        $available = array_diff($allSlots, $booked);
    
        return response()->json([
            'available' => array_values($available),
            'booked' => $booked
        ]);
    }


    // ScheduleController.php
    public function listConsultationsByDate(Request $request)
    {
        $consultations = Schedule::where('title', 'Consulta')
        ->where('user_id', auth()->id())
        ->get();
        return response()->json($consultations);
    }
    
    
    // Método de criação de evento (incluindo regras específicas para consultas)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'start'       => 'required|date_format:Y-m-d H:i',
            'end'         => 'required|date_format:Y-m-d H:i|after_or_equal:start',
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:7',
            'all_day'     => 'sometimes|boolean',
        ]);
    
        // Se for uma "Consulta", aplica as regras específicas
        if ($validated['title'] === 'Consulta') {
            $validated['user_id'] = auth()->id();
            $timezone = config('app.timezone'); // Pega o fuso do config/app.php
            $start = Carbon::createFromFormat('Y-m-d H:i', $validated['start'], $timezone);
            $end = Carbon::createFromFormat('Y-m-d H:i', $validated['end'], $timezone);
    
            // Validação: somente dias úteis
            if ($start->isWeekend()) {
                return response()->json(['error' => 'Consultas só podem ser marcadas de segunda a sexta.'], 422);
            }
    
            // Validação: horário comercial
            $hour = $start->hour;
            $validMorning = ($hour >= 8 && $hour < 12);
            $validAfternoon = ($hour >= 14 && $hour < 18);
            if (!($validMorning || $validAfternoon)) {
                return response()->json(['error' => 'Horários permitidos: 08:00-11:00 ou 14:00-17:00.'], 422);
            }
    
            // Validação de sobreposição CORRIGIDA
            $exists = Schedule::where('title', 'Consulta')
                ->where(function ($query) use ($start, $end) {
                    $query->where('start', '<', $end)
                          ->where('end', '>', $start);
                })
                ->exists();
    
            if ($exists) {
                return response()->json(['error' => 'Já existe uma consulta neste horário.'], 422);
            }
    
            // Força os valores para consulta
            $validated['title'] = 'Consulta';
            $validated['color'] = '#000000';
            $validated['all_day'] = false;
            $validated['end'] = $start->copy()->addHour()->format('Y-m-d H:i');
        }
    
        // Cria o evento no banco de dados
        Schedule::create($validated);
    
        // Se a requisição não for AJAX, redireciona
        if (!($request->ajax() || $request->wantsJson())) {
            return redirect()->route('agendar_compromissos')
                ->with('status', ($validated['title'] === 'Consulta')
                    ? 'Consulta agendada com sucesso!'
                    : 'Evento criado com sucesso!');
        }
    
        // Caso contrário, retorna a resposta JSON
        return response()->json([
            'message' => ($validated['title'] === 'Consulta')
                ? 'Consulta agendada com sucesso!'
                : 'Evento criado com sucesso!'
        ]);
    }
    


}