@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Fases do Jogo</h1>
        <a href="{{ route('game.index') }}" class="btn btn-outline-light">Voltar ao Jogo</a>
    </div>
    
    @if($phases->isEmpty())
        <div class="card">
            <div class="card-body">
                <p style="color: blanchedalmond">Nenhuma fase disponível no momento.</p>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($phases as $phase)
                @if($phase->is_active)
                    <div class="col-md-6 mb-4">
                        <div class="card phase-card" onclick="window.location.href='{{ route('game.phase', $phase->id) }}'">
                            <div class="card-header">
                                <h5 class="mb-0">{{ $phase->name }}</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ Str::limit($phase->description, 150) }}</p>
                                
                                @php
                                    $phaseProgress = isset($progress[$phase->id]) ? $progress[$phase->id] : null;
                                    $status = $phaseProgress ? $phaseProgress->status : 'not_started';
                                    
                                    // Verificar se o jogador pode acessar esta fase
                                    $canAccess = true;
                                    if ($phase->order > 1) {
                                        $previousPhase = $phases->where('order', $phase->order - 1)->first();
                                        if ($previousPhase) {
                                            $previousProgress = isset($progress[$previousPhase->id]) ? $progress[$previousPhase->id] : null;
                                            $canAccess = $previousProgress && $previousProgress->status === 'completed';
                                        }
                                    }
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
                            <div class="card-footer">
                                @if($canAccess)
                                    <a href="{{ route('game.phase', $phase->id) }}" class="btn btn-primary">Jogar</a>
                                @else
                                    <button class="btn btn-secondary" disabled>Bloqueada</button>
                                @endif
                                
                                @if($status == 'completed')
                                    <span class="badge bg-success float-end mt-2">✓</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Informações</h5>
        </div>
        <div class="card-body">
            <p style="color: blanchedalmond">Para avançar no jogo, você precisa completar cada fase na ordem. Cada fase contém desafios, NPCs e possivelmente armadilhas.</p>
            <p style="color: blanchedalmond">Ao completar desafios, você ganha pontos de experiência que ajudam seu personagem a subir de nível.</p>
            <p style="color: blanchedalmond">Fique atento às dicas fornecidas pelos NPCs e use seus itens com sabedoria para progredir.</p>
        </div>
    </div>
</div>
@endsection

