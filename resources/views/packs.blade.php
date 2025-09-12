@extends('layouts.app')

@section('content')
<h2 class="mb-4">Packs</h2>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
    @foreach($packs as $pack)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">{{ $pack->name }}</h5>
                        <p class="card-text"><small>{{ $pack->start_date }} - {{ $pack->end_date }}</small></p>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <a href="{{ route('packs.days', $pack->id) }}">Ver</a>
                        <form method="POST" action="/packs/{{ $pack->id }}/activate">
                            @csrf
                            <button class="btn btn-success btn-sm">Activar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<h3>Crear pack (14 días)</h3>
<form method="POST" action="/packs" class="mb-5">
    @csrf
    <div class="mb-3">
        <input type="text" name="name" placeholder="Nombre" class="form-control" required>
    </div>
    <div class="mb-3">
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <button class="btn btn-primary">Crear pack</button>
</form>
@endsection
