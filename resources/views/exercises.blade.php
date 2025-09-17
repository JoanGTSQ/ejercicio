@extends('layouts.app')

@section('content')
<h2 class="mb-4">Ejercicios — Día {{ $day->day_number }} ({{ $pack->name }})</h2>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
    @foreach($exercises as $ex)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $ex->name }}</h5>
                    @if($ex->type === 'running')
                    <p class="card-text small">
                        <strong>Series:</strong> {{ $ex->series }}<br>
                        @if($ex->distance)<strong>Distancia:</strong> {{ $ex->distance }} m<br>@endif
                        @if($ex->pace)<strong>Ritmo:</strong> {{ $ex->pace }}<br>@endif
                        @if($ex->bpm_min && $ex->bpm_max)
                            <strong>BPM:</strong> {{ $ex->bpm_min }} – {{ $ex->bpm_max }}<br>
                        @endif
                        @if($ex->rest_min && $ex->rest_max)
                            <strong>Descanso:</strong> {{ $ex->rest_min }}–{{ $ex->rest_max }} seg<br>
                        @endif
                    </p>
                @endif

                    @if ($ex->notes)
                        <p class="card-text mb-2"><strong>Notas: </strong>{{ $ex->notes }}</p>
                    @endif

                    <p class="card-text mb-2">
                        @if($ex->type != 'running')
                        <strong>Series x Reps:</strong> {{ $ex->series }} x {{ $ex->reps }}<br>
                        @endif
                        @if($ex->weight)<strong>Peso:</strong> {{ $ex->weight }} kg<br>@endif
                        <strong>Tipo:</strong> {{ ucfirst($ex->type) }}
                    </p>

                    {{-- 👇 Mostrar ejercicios HIIT si corresponde --}}
                    @if($ex->type === 'hiit' && $ex->hiitExercises->count())
                        <div class="mb-2">
                            <strong>Ejercicios HIIT:</strong>
                            <ul class="list-group list-group-flush small">
                                @foreach($ex->hiitExercises as $h)
                                    <li class="list-group-item">
                                        {{ $h->name }}
                                        @if($h->duration) • {{ $h->duration }}s @endif
                                        @if($h->reps) • {{ $h->reps }} reps @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 👇 Formulario para añadir sub-ejercicio HIIT --}}
                    @if($ex->type === 'hiit')
                        <form method="POST" action="/exercise/{{ $ex->id }}/hiit/add" class="mb-2">
                            @csrf
                            <div class="input-group input-group-sm mb-1">
                                <input type="text" name="name" class="form-control" placeholder="Ejercicio HIIT" required>
                            </div>
                            <div class="input-group input-group-sm mb-1">
                                <input type="number" name="duration" class="form-control" placeholder="Duración (s)">
                                <input type="number" name="reps" class="form-control" placeholder="Reps">
                            </div>
                            <button class="btn btn-sm btn-outline-primary w-100">Añadir HIIT</button>
                        </form>
                    @endif

                    <div class="mt-auto">
                        <button class="btn btn-outline-secondary btn-sm w-100 mb-2" onclick="toggle('edit-{{ $ex->id }}')">Editar</button>

                        <form method="POST" action="/exercise/{{ $ex->id }}/delete" class="mb-2">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm w-100" onclick="return confirm('¿Seguro que quieres eliminar este ejercicio?')">Eliminar</button>
                        </form>

                        <div id="edit-{{ $ex->id }}" class="editor" style="display:none;">
                            <form method="POST" action="/exercise/{{ $ex->id }}/update">
                                @csrf
                                <div class="mb-2">
                                    <input type="text" name="notes" placeholder="Notas" value="{{ $ex->notes }}" class="form-control form-control-sm">
                                </div>
                                <div class="mb-2 d-flex gap-2">
                                    <input type="number" name="weight_done" placeholder="Peso usado (kg)" value="{{ $ex->weight_done }}" class="form-control form-control-sm">
                                    <input type="number" name="reps_done" placeholder="Reps reales" value="{{ $ex->reps_done }}" class="form-control form-control-sm">
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="time_done" placeholder="Tiempo (mm:ss) o rondas" value="{{ $ex->time_done }}" class="form-control form-control-sm">
                                </div>
                                <button class="btn btn-primary btn-sm w-100">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<h3 class="mb-3">Añadir ejercicio</h3>
<form method="POST" action="/packs/{{ $pack->id }}/day/{{ $day->id }}/exercise" class="mb-4">
    @csrf
    <div class="mb-2">
        <input type="text" name="name" placeholder="Nombre" class="form-control" required>
    </div>
    <div class="mb-2 d-flex gap-2">
        <input type="number" name="series" placeholder="Series" value="3" class="form-control">
        <input type="number" name="reps" placeholder="Reps" value="8" class="form-control">
        <input type="number" name="weight" placeholder="Peso (kg, opcional)" class="form-control">
    </div>
    <div class="mb-2">
        <select name="type" class="form-select" onchange="toggleFields(this.value)">
            <option value="normal">Normal</option>
            <option value="emom">EMOM</option>
            <option value="amrap">AMRAP</option>
            <option value="running">Running</option>
            <option value="hiit">HIIT</option>
        </select>
    </div>
    {{-- Campos para running --}}
    <div id="running-fields" style="display:none;">
        <div class="mb-2 d-flex gap-2">
            <input type="number" name="distance" placeholder="Distancia (m)" class="form-control">
            <input type="text" name="pace" placeholder="Ritmo (ej: 75% o 5:00/km)" class="form-control">
        </div>
        <div class="mb-2 d-flex gap-2">
            <input type="number" name="bpm_min" placeholder="BPM min" class="form-control">
            <input type="number" name="bpm_max" placeholder="BPM max" class="form-control">
        </div>
        <div class="mb-2 d-flex gap-2">
            <input type="number" name="rest_min" placeholder="Descanso min (s)" class="form-control">
            <input type="number" name="rest_max" placeholder="Descanso max (s)" class="form-control">
        </div>
    </div>
    <button class="btn btn-primary">Añadir ejercicio</button>
</form>

<a href="{{ route('packs.days', $pack->id) }}" class="btn btn-secondary">Volver a días</a>

<script>
function toggle(id){
    const el = document.getElementById(id);
    el.style.display = (el.style.display === 'none') ? 'block' : 'none';
}
function toggleFields(type) {
    document.getElementById('running-fields').style.display =
        (type === 'running') ? 'block' : 'none';
}
</script>
@endsection
