<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pack;
use App\Models\Day;
use App\Models\Exercise;

class PackController extends Controller
{
    // Landing / Home
   public function home()
{
    $pack = Pack::where('is_active', 1)->first();

    if (!$pack) {
        return view('home', [
            'pack' => null,
            'day' => null,
            'exercises' => [],
            'dayNumber' => null
        ]);
    }

    $start = strtotime($pack->start_date);
    $today = strtotime(date('Y-m-d'));
    $diff = intdiv($today - $start, 86400);
    $dayNumber = max(1, min(7, $diff % 7 + 1)); // si quieres semana de 7 días repetida

    $day = $pack->days()->where('day_number', $dayNumber)->first();
    $exercises = $day ? $day->exercises : collect();

    // **Aquí definimos todas las variables correctamente**
    return view('home', [
        'pack' => $pack,
        'day' => $day,
        'exercises' => $exercises,
        'dayNumber' => $dayNumber
    ]);
}




    // Listar todos los packs
    public function index()
    {
        $packs = Pack::orderBy('created_at', 'desc')->get();
        return view('packs', compact('packs'));
    }

    // Mostrar días de un pack con ejercicios
    public function showDays($packId)
{
    $pack = Pack::with(['days.exercises'])->findOrFail($packId);
    $days = $pack->days;

    return view('days', compact('pack', 'days'));
}


    // Crear pack
    public function store(Request $request)
    {
        $weeks = $request->input('weeks', 2); // valor por defecto 2 semanas

        $pack = Pack::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => date('Y-m-d', strtotime($request->start_date . ' +' . (7 * $weeks - 1) . ' days')),
            'is_active' => false,
            'weeks' => $weeks
        ]);

        // Crear solo 7 días base
        for ($i = 1; $i <= 7; $i++) {
            $pack->days()->create(['day_number' => $i]);
        }

        return redirect()->back()->with('success', 'Pack creado correctamente');
    }


    // Activar pack (desactiva otros)
    public function activate($packId)
    {
        Pack::where('is_active', 1)->update(['is_active' => 0]);
        $pack = Pack::findOrFail($packId);
        $pack->is_active = 1;
        $pack->save();

        return redirect()->back()->with('success', 'Pack activado');
    }
}
