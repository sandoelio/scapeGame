@extends('layouts.app')

@section('content')
<div class="container">
    <div class="welcome-header">
        <h1>Escape Room Game</h1>
        <p class="lead">Bem-vindo ao mundo dos enigmas, desafios e mistérios. Você consegue escapar?</p>
        @auth
            <a href="{{ route('game.index') }}" class="btn btn-primary btn-lg mt-3">Começar a Jogar</a>
        @else
            <div class="mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light">Registrar</a>
            </div>
        @endauth
    </div>

    <div class="welcome-features">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card feature-card">
                    <div class="card-body">
                        <h3 style="color: blanchedalmond">Desafios Intrigantes</h3>
                        <p style="color: blanchedalmond">Enfrente quebra-cabeças e enigmas que testarão sua inteligência e criatividade. Cada fase traz novos desafios para superar.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-card">
                    <div class="card-body">
                        <h3 style="color: blanchedalmond">Personagens Únicos</h3>
                        <p style="color: blanchedalmond">Crie e desenvolva seu personagem, coletando itens, ganhando experiência e subindo de nível conforme progride no jogo.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-card">
                    <div class="card-body">
                        <h3 style="color: blanchedalmond">Mundo Imersivo</h3>
                        <p style="color: blanchedalmond">Explore ambientes detalhados, interaja com NPCs e descubra segredos ocultos em cada fase do jogo.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card feature-card">
                    <div class="card-body">
                        <h3 style="color: blanchedalmond">Cuidado com Armadilhas</h3>
                        <p style="color: blanchedalmond">Fique atento às armadilhas espalhadas pelo jogo. Elas podem causar dano ao seu personagem e dificultar sua jornada.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card feature-card">
                    <div class="card-body">
                        <h3 style="color: blanchedalmond">Itens e Inventário</h3>
                        <p style="color: blanchedalmond">Colete e utilize diversos itens que ajudarão na sua jornada, desde chaves e ferramentas até poções e artefatos mágicos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

