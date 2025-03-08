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
        body {
            background-color: #f8f9fa;
        }
        /* Estilo do Sidebar */
        .sidebar {
            background: #343a40;
            color: #fff;
            min-height: 100vh;
            position: fixed;
            width: 250px;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar de Navegação -->
            <nav class="col-md-2 sidebar">
                <div class="sidebar-sticky pt-3">
                    <h4 class="text-center">Menu</h4>
                    <ul class="nav flex-column">
                    <li class="nav-item">
              <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('controle_financeiro.index') }}">Parcelas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('controle_financeiro.minhas') }}">Minhas Parcelas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('agendar_compromissos') }}">Eventos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('marcar_consulta') }}">Marcar consulta</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('financeiro') }}">Pagamento Pix</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('processos.index') }}">Gerenciamento processos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('processos.meus') }}">Acompanhar processos</a>
            </li>
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
                @yield('content')
            </main>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">    
    @yield('scripts')
</body>
</html>
