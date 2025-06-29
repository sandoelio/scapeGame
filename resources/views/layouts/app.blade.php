<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Escape Room Game') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary-color: #6a1b9a;
            --secondary-color: #4a148c;
            --accent-color: #9c27b0;
            --background-color: #121212;
            --text-color: #e0e0e0;
            --danger-color: #c62828;
            --success-color: #2e7d32;
            --warning-color: #f9a825;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            min-height: 100vh;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Orbitron', sans-serif;
        }
        
        .navbar {
            background-color: var(--secondary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }
        
        .navbar-brand {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            color: white !important;
        }
        
        .card {
            background-color: #1e1e1e;
            border: 1px solid #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        .card-header {
            background-color: var(--secondary-color);
            color: white;
            font-family: 'Orbitron', sans-serif;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .game-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .phase-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            cursor: pointer;
        }
        
        .phase-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }
        
        .character-stats {
            background-color: #2d2d2d;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .health-bar {
            height: 20px;
            background-color: #333;
            border-radius: 10px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .health-fill {
            height: 100%;
            background-color: var(--danger-color);
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        
        .modal-content {
            background-color: #1e1e1e;
            color: var(--text-color);
            border: 1px solid #333;
        }
        
        .modal-header {
            background-color: var(--secondary-color);
            color: white;
            border-bottom: 1px solid #333;
        }
        
        .modal-footer {
            border-top: 1px solid #333;
        }
        
        .challenge-container {
            background-color: #2d2d2d;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .npc-container {
            background-color: #2d2d2d;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .inventory-item {
            background-color: #2d2d2d;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: transform 0.2s ease;
        }
        
        .inventory-item:hover {
            transform: scale(1.02);
            background-color: #333;
        }
        
        .trap-warning {
            color: var(--warning-color);
            font-weight: bold;
        }
        
        .code-input {
            background-color: #333;
            color: var(--text-color);
            border: 1px solid #444;
            padding: 10px;
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 2px;
        }
        
        .badge-level {
            background-color: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-family: 'Orbitron', sans-serif;
        }
        
        .progress-bar {
            background-color: var(--primary-color);
        }
        
        .completed {
            color: var(--success-color);
        }
        
        .in-progress {
            color: var(--warning-color);
        }
        
        .not-started {
            color: #777;
        }
        
        .dialog-box {
            background-color: #2d2d2d;
            border-left: 4px solid var(--accent-color);
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .npc-name {
            color: var(--accent-color);
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .action-history {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .action-item {
            padding: 10px;
            border-bottom: 1px solid #333;
        }
        
        .action-item:last-child {
            border-bottom: none;
        }
        
        .action-time {
            font-size: 0.8rem;
            color: #777;
        }
        
        .welcome-header {
            text-align: center;
            padding: 50px 0;
        }
        
        .welcome-header h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 0 0 10px rgba(156, 39, 176, 0.7);
        }
        
        .welcome-features {
            padding: 30px 0;
        }
        
        .feature-card {
            height: 100%;
        }
        
        .footer {
            background-color: #1a1a1a;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Escape Room Game') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('game.index') }}">{{ __('Jogar') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('characters.index') }}">{{ __('Personagens') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('phases.index') }}">{{ __('Fases') }}</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            
            @yield('content')
        </main>
        
        <footer class="footer">
            <div class="container text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Escape Room Game. Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>
    
    <!-- Scripts personalizados -->
    <script src="{{ asset('js/game.js') }}"></script>
    @yield('scripts')
</body>
</html>

