@extends('layouts.app')

@section('content')
<h2 class="mb-4">Días — {{ $pack->name }}</h2>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @foreach($days as $day)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Día {{ $day->absolute_number ?? $day->day_number }}</h5>
                    <p class="card-text mb-2"><strong>Semana:</strong> {{ $day->week_number ?? ceil($day->day_number / 7) }}</p>

                    {{-- Previsualización ejercicios --}}
                    @if($day->exercises && $day->exercises->count())
                        <ul class="list-group list-group-flush mb-2 small">
                            @foreach($day->exercises as $ex)
                                <li class="list-group-item p-1">
                                    {{ $ex->name }} 
                                    @if($ex->series) • {{ $ex->series }}x{{ $ex->reps }} @endif
                                    @if($ex->weight) • {{ $ex->weight }}kg @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-2">No hay ejercicios asignados</p>
                    @endif

                    <a href="{{ url("/packs/{$pack->id}/day/{$day->id}") }}" class="btn btn-primary mt-auto btn-sm w-100">Ver día completo</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<a href="{{ route('packs.index') }}" class="btn btn-secondary mt-4">Volver a Packs</a>
@endsection
