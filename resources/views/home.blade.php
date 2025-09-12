@extends('layouts.app')

@section('content')
<h1 class="mb-4">Entrenamiento de Hoy</h1>

@if($pack)
<div class="mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">{{ $pack->name }}</h5>
            <p class="card-text">Día {{ $dayNumber }} de 14</p>
        </div>
    </div>
</div>

<h4 class="mb-3">Ejercicios:</h4>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @foreach($exercises as $exercise)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $exercise->name }}</h5>

                    @if($exercise->notes)
                        <p class="card-text mb-2"><strong>Notas:</strong> {{ $exercise->notes }}</p>
                    @endif

                    <p class="card-text mb-2">
                        <strong>Series x Reps:</strong> {{ $exercise->series }} x {{ $exercise->reps }}<br>
                        @if($exercise->weight)<strong>Peso:</strong> {{ $exercise->weight }} kg<br>@endif
                        <strong>Tipo:</strong> {{ ucfirst($exercise->type) }}
                    </p>

                    {{-- HIIT --}}
                    @if($exercise->type === 'hiit' && $exercise->hiitExercises->count())
                        <div class="mb-2">
                            <strong>Ejercicios HIIT:</strong>
                            <ul class="list-group list-group-flush small">
                                @foreach($exercise->hiitExercises as $h)
                                    <li class="list-group-item">
                                        {{ $h->name }}
                                        @if($h->duration) • {{ $h->duration }}s @endif
                                        @if($h->reps) • {{ $h->reps }} reps @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-auto">
                        @if(!$exercise->completed)
                            <form method="POST" action="/exercise/{{ $exercise->id }}/complete" class="mb-2">
                                @csrf
                                <button class="btn btn-success btn-sm w-100">Completar</button>
                            </form>
                        @else
                            <span class="badge bg-success mb-2">✔ Completado</span>
                        @endif

                        <button class="btn btn-outline-secondary btn-sm w-100 mb-2" onclick="toggle('edit-{{ $exercise->id }}')">Editar</button>

                        <div id="edit-{{ $exercise->id }}" class="editor" style="display:none;">
                            <form method="POST" action="/exercise/{{ $exercise->id }}/update">
                                @csrf
                                <div class="mb-2">
                                    <input type="text" name="notes" placeholder="Notas" value="{{ $exercise->notes }}" class="form-control form-control-sm">
                                </div>
                                <div class="mb-2 d-flex gap-2">
                                    <input type="number" name="weight_done" placeholder="Peso usado (kg)" value="{{ $exercise->weight_done }}" class="form-control form-control-sm">
                                    <input type="number" name="reps_done" placeholder="Reps reales" value="{{ $exercise->reps_done }}" class="form-control form-control-sm">
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="time_done" placeholder="Tiempo (mm:ss) o rondas" value="{{ $exercise->time_done }}" class="form-control form-control-sm">
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
@else
<div class="alert alert-info">No hay pack activo. Activa uno desde la sección Packs.</div>
@endif

<script>
function toggle(id){
    const el = document.getElementById(id);
    el.style.display = (el.style.display === 'none') ? 'block' : 'none';
}
</script>
@endsection
