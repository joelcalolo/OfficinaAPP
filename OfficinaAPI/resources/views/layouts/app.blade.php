<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão de Oficina - @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Oficina Auto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(in_array(Auth::user()->role, ['admin', 'secretario', 'gerente']))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('viaturas.index') }}">
                                    <i class="fas fa-car"></i> Viaturas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('servicos.index') }}">
                                    <i class="fas fa-wrench"></i> Serviços
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ordens-servico.index') }}">
                                    <i class="fas fa-clipboard-list"></i> Ordens de Serviço
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('relatorios.index') }}">
                                    <i class="fas fa-chart-bar"></i> Relatórios
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('usuarios.index') }}">
                                    <i class="fas fa-users"></i> Usuários
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->role === 'tecnico')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ordens-servico.index') }}">
                                    <i class="fas fa-clipboard-list"></i> Ordens de Serviço
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->role === 'cliente')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('viaturas.minhas') }}">
                                    <i class="fas fa-car"></i> Minhas Viaturas
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->nome }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('perfil.index') }}">
                                        <i class="fas fa-user-cog"></i> Perfil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="container my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-3 mt-5">
        <div class="container">
            Sistema de Gestão de Oficina &copy; {{ date('Y') }}
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>