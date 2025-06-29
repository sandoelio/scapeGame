@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Inventário de {{ $character->name }}</h1>
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
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: blanchedalmond">Vida: {{ $character->health_points }}/{{ $character->max_health_points }}</span>
                            <span>{{ round(($character->health_points / $character->max_health_points) * 100) }}%</span>
                        </div>
                        <div class="health-bar">
                            <div class="health-fill" style="width: {{ ($character->health_points / $character->max_health_points) * 100 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Ações</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('characters.progress', $character->id) }}" class="btn btn-outline-light d-block mb-2">Ver Progresso</a>
                    <a href="{{ route('game.index') }}" class="btn btn-primary d-block">Jogar</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Itens no Inventário</h5>
                </div>
                <div class="card-body">
                    @if($character->inventory->isEmpty())
                        <p style="color: blanchedalmond">Seu inventário está vazio.</p>
                    @else
                        <div class="row">
                            @foreach($character->inventory as $inventoryItem)
                                <div class="col-md-6 mb-4">
                                    <div style="color: blanchedalmond; border-radius: 5px; padding: 10px; margin-bottom: 10px; align-items: center ; background-color: rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); transition: transform 0.2s; ">
                                        <h4>{{ $inventoryItem->item->name }}</h4>
                                        <p>{{ $inventoryItem->item->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-secondary">{{ ucfirst($inventoryItem->item->type) }}</span>
                                            <span style="color: blanchedalmond">Quantidade: {{ $inventoryItem->quantity }}</span>
                                        </div>
                                        <div class="mt-2">
                                            <strong style="color: blanchedalmond">Efeito:</strong> {{ $inventoryItem->item->effect }}
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
                    <h5 class="mb-0">Tipos de Itens</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 style="color: blanchedalmond">Chaves</h5>
                                    <p style="color: blanchedalmond">Usadas para abrir portas e acessar novas áreas.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 style="color: blanchedalmond">Ferramentas</h5>
                                    <p style="color: blanchedalmond">Ajudam a resolver quebra-cabeças e superar obstáculos.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 style="color: blanchedalmond">Consumíveis</h5>
                                    <p style="color: blanchedalmond">Itens de uso único que fornecem efeitos temporários.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 style="color: blanchedalmond">Artefatos</h5>
                                    <p style="color: blanchedalmond">Itens raros com efeitos especiais e permanentes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

