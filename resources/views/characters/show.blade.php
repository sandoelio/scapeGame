@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $character->name }}</h1>
        <a href="{{ route('characters.index') }}" class="btn btn-outline-light">Voltar aos Personagens</a>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informações do Personagem</h5>
                </div>
                <div class="card-body">
                    <div class="character-stats">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-level">Nível {{ $character->level }}</span>
                            <a href="{{ route('characters.edit', $character->id) }}" class="btn btn-sm btn-outline-light">Editar</a>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: blanchedalmond">Vida: {{ $character->health_points }}/{{ $character->max_health_points }}</span>
                            <span style="color: blanchedalmond">{{ round(($character->health_points / $character->max_health_points) * 100) }}%</span>
                        </div>
                        <div class="health-bar">
                            <div class="health-fill" style="width: {{ ($character->health_points / $character->max_health_points) * 100 }}%;"></div>
                        </div>
                        
                        <div class="mt-3">
                            <span style="color: blanchedalmond">Experiência: {{ $character->experience }}/{{ $character->level * 1000 }}</span>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ ($character->experience / ($character->level * 1000)) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <p style="color: blanchedalmond"><strong>Criado em:</strong> {{ $character->created_at->format('d/m/Y') }}</p>
                            <p style="color: blanchedalmond"><strong>Última atualização:</strong> {{ $character->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0" >Ações</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('characters.inventory', $character->id) }}" class="btn btn-primary d-block mb-2">Ver Inventário</a>
                    <a href="{{ route('characters.progress', $character->id) }}" class="btn btn-primary d-block mb-2">Ver Progresso</a>
                    <a href="{{ route('game.index') }}" class="btn btn-outline-light d-block">Jogar</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Histórico de Ações</h5>
                </div>
                <div class="card-body">
                    <div class="action-history">
                        @if($actionHistory->isEmpty())
                            <p style="color: blanchedalmond">Nenhuma ação registrada ainda.</p>
                        @else
                            @foreach($actionHistory as $action)
                                <div class="action-item">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $action->description }}</strong>
                                        <span class="action-time">{{ $action->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>{{ $action->result }}</div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Progresso nas Fases</h5>
                </div>
                <div class="card-body">
                    @if($character->progress->isEmpty())
                        <p style="color: blanchedalmond">Nenhuma fase iniciada ainda.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr style="color: blanchedalmond">
                                        <th>Fase</th>
                                        <th>Status</th>
                                        <th>Completada em</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($character->progress->sortBy('phase.order') as $progress)
                                        <tr>
                                            <td>{{ $progress->phase->name }}</td>
                                            <td>
                                                <span class="
                                                    @if($progress->status == 'completed') completed
                                                    @elseif($progress->status == 'in_progress') in-progress
                                                    @else not-started @endif
                                                ">
                                                    @if($progress->status == 'completed')
                                                        Completada
                                                    @elseif($progress->status == 'in_progress')
                                                        Em Progresso
                                                    @else
                                                        Não Iniciada
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @if($progress->completed_at)
                                                    {{ $progress->completed_at->format('d/m/Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

