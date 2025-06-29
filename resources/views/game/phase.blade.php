@extends('layouts.app')

@section('content')
<div class="container">
    <div id="alert-container"></div>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="phase-title">{{ $phase->name }}</h1>
        <a href="{{ route('game.index') }}" class="btn btn-outline-light">Voltar ao Jogo</a>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card" id="character-info" data-character-id="{{ $character->id }}">
                <div class="card-header">
                    <h5 class="mb-0">{{ $character->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="character-stats">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge-level">Nível {{ $character->level }}</span>
                            <span>XP: {{ $character->experience }}/{{ $character->level * 1000 }}</span>
                        </div>
                        
                        <div class="mt-3">
                            <div class="d-flex justify-content-between">
                                <span style="color: blanchedalmond">Vida:</span>
                                <span id="health-text">{{ $character->health_points }}/{{ $character->max_health_points }}</span>
                            </div>
                            <div class="health-bar-container">
                                <div id="health-bar" class="health-bar" style="width: {{ ($character->health_points / $character->max_health_points) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button class="btn btn-outline-light w-100 mb-2" data-bs-toggle="modal" data-bs-target="#inventory-modal">
                            <i class="fas fa-backpack me-2"></i> Inventário
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Descrição da Fase</h5>
                </div>
                <div style="color: blanchedalmond; border-radius: 5px; padding: 10px; margin-bottom: 10px; align-items: center ; background-color: rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 2px 4px rgba(236, 234, 234, 0.1); transition: transform 0.2s;">
                    <p>{{ $phase->description }}</p>
                    
                    @if($phase->hint)
                        <div class="mt-3">
                            <h6 style="color: blanchedalmond">Dica:</h6>
                            <p class="text-warning">{{ $phase->hint }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
           @if(isset($phase->npcs) && $phase->npcs->count())
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">NPCs</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($phase->npcs as $npc)
                                <button class="list-group-item list-group-item-action bg-dark text-light border-secondary mb-2 npc-card"
                                        data-bs-toggle="modal"
                                        data-bs-target="#npc-modal"
                                        data-npc-id="{{ $npc->id }}">
                                    <h6 class="mb-1">{{ $npc->name }}</h6>
                                    <small>{{ Str::limit($npc->description, 50) }}</small>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-md-8">
            <div id="phase-container" data-phase-id="{{ $phase->id }}">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Desafios</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($phase->challenges) && $phase->challenges->count())
                            <div class="row">
                                @foreach($phase->challenges as $challenge)
                                    @php
                                        $isCompleted = $progress->where('challenge_id', $challenge->id)->where('status', 'completed')->count() > 0;
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="card challenge-card {{ $isCompleted ? 'completed' : '' }}"
                                             data-bs-toggle="modal"
                                             data-bs-target="#challenge-modal"
                                             data-challenge-id="{{ $challenge->id }}"
                                             data-challenge-name="{{ $challenge->name }}"
                                             data-challenge-description="{{ $challenge->description }}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $challenge->name }}</h5>
                                                <p class="card-text">{{ Str::limit($challenge->description, 100) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-secondary">{{ ucfirst($challenge->difficulty) }}</span>
                                                    <span class="badge {{ $isCompleted ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $isCompleted ? 'Concluído' : 'Pendente' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="color: blanchedalmond">Nenhum desafio disponível nesta fase.</p>
                        @endif
                    </div>
                </div>
                
              @if(isset($phase->items) && $phase->items->count())
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Itens Disponíveis</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($phase->items as $item)
                                    <div class="col-md-4 mb-3">
                                        <div class="card item-card" data-item-id="{{ $item->id }}">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $item->name }}</h6>
                                                <p class="card-text small">{{ Str::limit($item->description, 50) }}</p>
                                                <button class="btn btn-sm btn-primary w-100" onclick="collectItem({{ $item->id }})">
                                                    Coletar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
               @if(isset($phase->traps) && $phase->traps->count())
                    <div class="card mt-4">
                        <div class="card-header bg-danger">
                            <h5 class="mb-0">Armadilhas</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Cuidado! Esta área contém armadilhas que podem causar dano ao seu personagem.
                            </div>
                            
                            <div class="row">
                                @foreach($phase->traps as $trap)
                                    <div class="col-md-6 mb-3">
                                        <div class="card trap-card">
                                            <div class="card-body">
                                                <h5 class="card-title text-danger">{{ $trap->name }}</h5>
                                                <p class="card-text">{{ $trap->description }}</p>
                                                <p class="card-text"><small class="text-danger">Dano potencial: {{ $trap->damage }} pontos</small></p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mt-4 text-center">
                    <button id="advance-phase-btn" class="btn btn-lg btn-primary {{ $allChallengesCompleted ? '' : 'd-none' }}" onclick="advancePhase()">
                        Avançar para a próxima fase
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Desafio -->
<div class="modal fade" id="challenge-modal" tabindex="-1" aria-labelledby="challenge-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="challenge-title">Título do Desafio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p id="challenge-description">Descrição do desafio...</p>
                
                <form id="challenge-form" class="mt-4">
                    <div class="mb-3">
                        <label for="challenge-code" class="form-label">Insira o código:</label>
                        <input type="text" class="form-control code-input" id="challenge-code" placeholder="Digite o código aqui">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Resolver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de NPC -->
<div class="modal fade" id="npc-modal" tabindex="-1" aria-labelledby="npc-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="npc-name">Nome do NPC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p id="npc-description">Descrição do NPC...</p>
                
                <div class="dialog-box mt-4">
                    <p id="npc-dialog">Diálogo do NPC...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Inventário -->
<div class="modal fade" id="inventory-modal" tabindex="-1" aria-labelledby="inventory-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inventário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="inventory-items">
                    @if(isset($inventory) && $inventory->count())
                        @foreach($inventory as $inv)
                            <div class="inventory-item d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6>{{ $inv->item->name }}</h6>
                                    <p class="mb-0 small">{{ Str::limit($inv->item->description, 50) }}</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2">x{{ $inv->quantity }}</span>
                                    <button class="btn btn-sm btn-primary use-item-btn" data-item-id="{{ $inv->item_id }}">
                                        Usar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>Nenhum item no inventário.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Reviver -->
<div class="modal fade" id="revive-modal" tabindex="-1" aria-labelledby="revive-modal-label" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title">Seu personagem morreu!</h5>
            </div>
            <div class="modal-body">
                <p>Seu personagem foi derrotado por uma armadilha. Você pode revivê-lo com metade da vida máxima.</p>
                
                <div class="d-grid mt-4">
                    <button class="btn btn-primary" onclick="reviveCharacter()">Reviver Personagem</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Inicializar variáveis do jogo
    playerHealth = {{ $character->health_points }};
    playerMaxHealth = {{ $character->max_health_points }};
</script>
@endsection

