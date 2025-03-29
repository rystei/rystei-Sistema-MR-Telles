<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MR TELLES')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bege: #E6E0D6;
            --marrom-escuro: #B08968;
            --marrom-claro: #B29463;
            --marrom-destaque: #DDB892;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes linkHover {
            from { transform: scale(1); }
            to { transform: scale(1.02); }
        }

        body {
            background-color: var(--bege);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }
        .sidebar .nav-link {
        color: white !important;
        transition: color 0.3s ease !important;
    }

    .sidebar .nav-link:hover {
        color: #1F140A !important;
    }
        /* Sidebar Estilizado */
        .sidebar {
            background: var(--marrom-escuro);
            color: white;
            min-height: 100vh;
            position: fixed;
            width: 250px;
            transition: all 0.3s ease;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: white;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            animation: fadeIn 0.5s ease forwards;
        }

        .sidebar .nav-link:hover {
            background: var(--marrom-claro);
            padding-left: 2rem;
            animation: linkHover 0.3s ease forwards;
        }

        .sidebar .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1.5rem;
            width: 0;
            height: 2px;
            background: var(--marrom-destaque);
            transition: width 0.3s ease;
        }

        .sidebar .nav-link:hover::after {
            width: calc(100% - 3rem);
        }

        .sidebar .nav-link.active {
            background: var(--marrom-claro);
            font-weight: 500;
        }

        .content {
            margin-left: 250px;
            padding: 2rem;
            animation: fadeIn 0.8s ease;
        }

        .card-custom {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .table-custom {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .btn-primary-custom {
            background: var(--marrom-claro);
            border: none;
            padding: 0.8rem 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom:hover {
            background: var(--marrom-escuro);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }

            .content {
                margin-left: 0;
                padding: 1rem;
            }

            .sidebar .nav-link {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar de Navegação -->
        <nav class="col-md-2 sidebar">
            <div class="sidebar-sticky pt-4">
                <h4 class="text-center mb-4">Menu</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    @unless (!request()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('controle_financeiro.index') ? 'active' : '' }}" href="{{ route('controle_financeiro.index') }}">Parcelas</a>
                        </li>
                    @endunless
                    @unless (request()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('controle_financeiro.minhas') ? 'active' : '' }}" href="{{ route('controle_financeiro.minhas') }}">Minhas Parcelas</a>
                        </li>
                    @endunless
                    @unless (!request()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('agendar_compromissos') ? 'active' : '' }}" href="{{ route('agendar_compromissos') }}">Eventos</a>
                        </li>
                    @endunless
                    @unless (request()->user()->is_admin)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('marcar_consulta') ? 'active' : '' }}" href="{{ route('marcar_consulta') }}">Marcar consulta</a>
                    </li>
                    @endunless
                    @unless (!request()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('financeiro') ? 'active' : '' }}" href="{{ route('financeiro') }}">Pix calculo</a>
                        </li>
                    @endunless
                    @unless (request()->user()->is_admin)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pagamento') ? 'active' : '' }}" href="{{ route('pagamento') }}">Pagamento</a>
                    </li>
                    @endunless
                    @unless (!request()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('processos.index') ? 'active' : '' }}" href="{{ route('processos.index') }}">Gerenciamento processos</a>
                        </li>
                    @endunless
                    @unless (request()->user()->is_admin)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('processos.meus') ? 'active' : '' }}" href="{{ route('processos.meus') }}">Acompanhar processos</a>
                    </li>
                    @endunless
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Conteúdo Principal -->
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">    
@yield('scripts')
</body>
</html>