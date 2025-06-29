@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Seus Personagens</h1>
        <a href="{{ route('characters.create') }}" class="btn btn-primary">Criar Novo Personagem</a>
    </div>
    
    @if($characters->isEmpty())
        <div class="card">
            <div class="card-body">
                <p style="color: blanchedalmond">Você ainda não tem personagens. Crie um para começar a jogar!</p>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($characters as $character)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $character->name }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="character-stats">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge-level">Nível {{ $character->level }}</span>
                                    <span>XP: {{ $character->experience }}/{{ $character->level * 1000 }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Vida: {{ $character->health_points }}/{{ $character->max_health_points }}</span>
                                    <span>{{ round(($character->health_points / $character->max_health_points) * 100) }}%</span>
                                </div>
                                <div class="health-bar">
                                    <div class="health-fill" style="width: {{ ($character->health_points / $character->max_health_points) * 100 }}%;"></div>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="{{ route('characters.show', $character->id) }}" class="btn btn-primary">Ver Detalhes</a>
                                    <a href="{{ route('characters.inventory', $character->id) }}" class="btn btn-outline-light">Inventário</a>
                                    <a href="{{ route('characters.progress', $character->id) }}" class="btn btn-outline-light">Progresso</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('characters.edit', $character->id) }}" class="btn btn-sm btn-outline-light">Editar</a>
                            <form action="{{ route('characters.destroy', $character->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este personagem? Esta ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    <div class="mt-4">
        <a href="{{ route('game.index') }}" class="btn btn-outline-light">Voltar ao Jogo</a>
    </div>
</div>
@endsection

