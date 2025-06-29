@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 >Progresso de {{ $character->name }}</h1>
        <a href="{{ route('characters.show', $character->id) }}" class="btn btn-outline-light">Voltar ao Personagem</a>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informações do Personagem</h5>
                </div>
                <div class="card-body">
                    <div class="character-stats">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge-level">Nível {{ $character->level }}</span>
                        </div>
                        
                        <div class="mt-3">
                            <span style="color: blanchedalmond">Experiência: {{ $character->experience }}/{{ $character->level * 1000 }}</span>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ ($character->experience / ($character->level * 1000)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Ações</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('characters.inventory', $character->id) }}" class="btn btn-outline-light d-block mb-2">Ver Inventário</a>
                    <a href="{{ route('game.index') }}" class="btn btn-primary d-block">Jogar</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
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
                                        <th>Ações</th>
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
                                            <td>
                                                <a href="{{ route('game.phase', $progress->phase_id) }}" class="btn btn-sm btn-primary">Jogar</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Desafios Completados</h5>
                </div>
                <div class="card-body">
                    @php
                        $completedChallenges = $character->progress->where('status', 'completed')->where('challenge_id', '!=', null);
                    @endphp
                    
                    @if($completedChallenges->isEmpty())
                        <p style="color: blanchedalmond">Nenhum desafio completado ainda.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr style="color: blanchedalmond">
                                        <th>Desafio</th>
                                        <th>Fase</th>
                                        <th>Pontos</th>
                                        <th>Completado em</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedChallenges as $progress)
                                        <tr>
                                            <td>{{ $progress->challenge->name }}</td>
                                            <td>{{ $progress->phase->name }}</td>
                                            <td>{{ $progress->challenge->points }}</td>
                                            <td>{{ $progress->completed_at->format('d/m/Y H:i') }}</td>
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

