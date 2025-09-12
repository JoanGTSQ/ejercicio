<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Day;

class DayController extends Controller
{
    public function index($packId)
    {
        $pack = Pack::findOrFail($packId);

        // Número de semanas a repetir (por defecto 2 si no está definido en BD)
        $weeks = $pack->weeks ?? 2;

        // Los días base (1 a 7)
        $daysBase = Day::where('pack_id', $pack->id)
            ->orderBy('day_number')
            ->get();

        // Generar "días virtuales"
        $virtualDays = [];
        for ($w = 1; $w <= $weeks; $w++) {
            foreach ($daysBase as $day) {
                $virtualDays[] = (object) [
                    'id'              => $day->id,
                    'day_number'      => $day->day_number,
                    'week_number'     => $w,
                    'absolute_number' => ($w - 1) * 7 + $day->day_number,
                    'exercises'       => $day->exercises, // relación desde el modelo
                ];
            }
        }

        return view('days', compact('pack', 'virtualDays'));
    }
}
