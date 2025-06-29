@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4 style="color: blanchedalmond">Bem-vindo ao Escape Room Game!</h4>
                    <p style="color: blanchedalmond">Você está logado e pronto para começar sua aventura.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('game.index') }}" class="btn btn-primary">Iniciar Jogo</a>
                        <a href="{{ route('characters.index') }}" class="btn btn-outline-light">Gerenciar Personagens</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

