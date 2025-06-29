@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Criar Novo Personagem</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('characters.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label" style="color: blanchedalmond">Nome do Personagem</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                            
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <p style="color: blanchedalmond">Seu personagem começará com:</p>
                            <ul style="color: blanchedalmond">
                                <li>Nível 1</li>
                                <li>100 pontos de vida</li>
                                <li>0 pontos de experiência</li>
                                <li>Itens iniciais: Chave de Bronze, Lanterna, Poção de Cura</li>
                            </ul>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('characters.index') }}" class="btn btn-outline-light">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Criar Personagem</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

