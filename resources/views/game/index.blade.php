@extends('layouts.app')

@section('content')
<div class="container game-container">
    <h1 class="mb-4">Escape Room Game</h1>
    
    @if($characters->isEmpty())
        <div class="card">
            <div class="card-body">
                <h3>Bem-vindo ao jogo!</h3>
                <p>Para começar sua aventura, você precisa criar um personagem.</p>
                <a href="{{ route('characters.create') }}" class="btn btn-primary">Criar Personagem</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Seu Personagem</h5>
                    </div>
                    <div class="card-body">
                        @foreach($characters as $character)
                            <div class="character-stats mb-3">
                                <h4>{{ $character->name }} <span class="badge-level">Nível {{ $character->level }}</span></h4>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Vida: {{ $character->health_points }}/{{ $character->max_health_points }}</span>
                                    <span>{{ round(($character->health_points / $character->max_health_points) * 100) }}%</span>
                                </div>
                                <div class="health-bar">
                                    <div class="health-fill" style="width: {{ ($character->health_points / $character->max_health_points) * 100 }}%;"></div>
                                </div>
                                <div class="mt-2">
                                    <span>Experiência: {{ $character->experience }}/{{ $character->level * 1000 }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ ($character->experience / ($character->level * 1000)) * 100 }}%"></div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('characters.show', $character->id) }}" class="btn btn-sm btn-primary">Ver Detalhes</a>
                                    <a href="{{ route('characters.inventory', $character->id) }}" class="btn btn-sm btn-outline-light">Inventário</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Ações</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('phases.index') }}" class="btn btn-primary d-block mb-2">Ver Todas as Fases</a>
                        <a href="{{ route('characters.index') }}" class="btn btn-outline-light d-block">Gerenciar Personagens</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Fases Disponíveis</h5>
                    </div>
                    <div style="color: blanchedalmond; border-radius: 5px; padding: 10px; margin-bottom: 10px; align-items: center ; background-color: rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 2px 4px rgba(236, 234, 234, 0.1); transition: transform 0.2s; ">
                        @if($phases  ->isEmpty())
                            <p style="color: blanchedalmond">Nenhuma fase disponível no momento.</p>
                        @else
                            <div class="row">
                                @foreach($phases as $phase)
                                    <div class="col-md-6">
                                        <div style="color: blanchedalmond; border-radius: 5px; padding: 10px; margin-bottom: 10px; align-items: center ; background-color: rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 2px 4px rgba(236, 234, 234, 0.1); transition: transform 0.2s; " onclick="window.location.href='{{ route('game.phase', $phase->id) }}'">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $phase->name }}</h5>
                                                <p class="card-text">{{ Str::limit($phase->description, 100) }}</p>
                                                
                                                @php
                                                    $progress = $playerProgress->where('phase_id', $phase->id)->first();
                                                    $status = $progress ? $progress->status : 'not_started';
                                                @endphp
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="
                                                        @if($status == 'completed') completed
                                                        @elseif($status == 'in_progress') in-progress
                                                        @else not-started @endif
                                                    ">
                                                        @if($status == 'completed')
                                                            Completada
                                                        @elseif($status == 'in_progress')
                                                            Em Progresso
                                                        @else
                                                            Não Iniciada
                                                        @endif
                                                    </span>
                                                    <span class="badge bg-secondary">Fase {{ $phase->order }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Histórico de Ações</h5>
                    </div>
                    <div class="card-body">
                        <div class="action-history">
                            @if(!empty($actionHistory) && $actionHistory->isNotEmpty())

                                <p style="color: blanchedalmond">Nenhuma ação registrada ainda.</p>
                            @else
                                @foreach($actionHistory as $action)
                                    <div style="color: blanchedalmond">
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
            </div>
        </div>
    @endif
</div>
@endsection

