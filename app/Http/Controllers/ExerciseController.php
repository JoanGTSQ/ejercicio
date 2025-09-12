<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pack;
use App\Models\Day;
use App\Models\Exercise;
use App\Models\HiitExercise;

class ExerciseController extends Controller
{
    // Mostrar lista de ejercicios de un día
    public function index($packId, $dayId)
    {
        $pack = Pack::findOrFail($packId);
        $day = Day::findOrFail($dayId);

        // Cargar ejercicios con posibles hiit_exercises asociados
        $exercises = Exercise::with('hiitExercises')
            ->where('day_id', $day->id)
            ->get();

        return view('exercises', compact('pack', 'day', 'exercises'));
    }

    // Añadir un ejercicio normal
    public function store(Request $request, $pack_id, $day_id)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'series' => 'nullable|integer|min:1',
            'reps'   => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric',
            'type'   => 'required|string|in:normal,emom,amrap,for_time,hiit',
        ]);

        Exercise::create([
            'day_id'     => $day_id,
            'name'       => $validated['name'],
            'series'     => $validated['series'] ?? 1,
            'reps'       => $validated['reps'] ?? 1,
            'weight'     => $validated['weight'] ?? null,
            'type'       => $validated['type'],
            'completed'  => 0,
        ]);

        return redirect("/packs/$pack_id/day/$day_id");
    }

    // Añadir un sub-ejercicio HIIT a un ejercicio principal
    public function addHiitExercise(Request $request, $exerciseId)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'duration' => 'nullable|string|max:50',
            'reps'     => 'nullable|integer|min:1',
        ]);

        HiitExercise::create([
            'exercise_id' => $exerciseId,
            'name'        => $validated['name'],
            'duration'    => $validated['duration'] ?? null,
            'reps'        => $validated['reps'] ?? null,
        ]);

        return back();
    }
    public function destroy($id)
{
    $exercise = Exercise::findOrFail($id);
    $exercise->delete();
    return back()->with('success', 'Ejercicio eliminado correctamente');
}


    // Marcar ejercicio como completado
    public function markComplete($id)
    {
        Exercise::where('id', $id)->update([
            'completed'   => 1,
            'updated_at'  => now()
        ]);

        return back();
    }

    // Guardar métricas de ejecución de un ejercicio
    public function updateMetrics(Request $request, $id)
    {
        $data = $request->only(['weight_done', 'reps_done', 'time_done', 'notes']);

        if (!empty($data)) {
            $data['updated_at'] = now();
            Exercise::where('id', $id)->update($data);
        }

        return back();
    }
}
