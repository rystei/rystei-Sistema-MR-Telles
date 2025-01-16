<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Exibe a página principal do calendário
    public function index()
    {
        return view('schedule.index');
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
        $schedules = Schedule::all(['id', 'title', 'start', 'end', 'color']);
        return response()->json($schedules);
    }

    // Deleta um evento pelo ID
    public function deleteEvent($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Evento deletado com sucesso']);
    }

    // Atualiza as datas de um evento arrastado
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

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
        $searchKeywords = $request->input('title');

        $matchingEvents = Schedule::where('title', 'like', '%' . $searchKeywords . '%')->get();

        return response()->json($matchingEvents);
    }

    // Criação de um evento via requisição AJAX
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'allDay' => 'boolean',
        ]);

        Schedule::create($validated);

        return response()->json(['status' => 'Evento criado com sucesso!']);
    }
}
