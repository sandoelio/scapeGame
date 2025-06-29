@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Editar Personagem</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('characters.update', $character->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome do Personagem</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $character->name) }}" required autofocus>
                            
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <p>Informações do Personagem:</p>
                            <ul>
                                <li>Nível: {{ $character->level }}</li>
                                <li>Vida: {{ $character->health_points }}/{{ $character->max_health_points }}</li>
                                <li>Experiência: {{ $character->experience }}/{{ $character->level * 1000 }}</li>
                            </ul>
                            <p class="text-muted">Nota: Apenas o nome do personagem pode ser editado.</p>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('characters.show', $character->id) }}" class="btn btn-outline-light">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

