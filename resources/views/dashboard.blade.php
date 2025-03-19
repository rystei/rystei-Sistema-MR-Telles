@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@section('title', 'Dashboard - MR Telles Advocacia')

@section('content')
<div class="container my-4">
    <!-- Card Principal para Unificar o Conteúdo -->
    <div class="card main-card p-4">
        <div class="card-body">
            <!-- Cabeçalho de Boas-Vindas Personalizado -->
            <div class="welcome-header p-4 rounded shadow-sm mb-4">
                <h1 class="display-5 fw-bold">
                    <i class="fas fa-user-circle me-2"></i>
                    Olá, <span class="user-name">{{ Auth::user()->name }}</span>!
                </h1>
                <p class="lead">Bem-vindo ao MR Telles Advocacia – Sua central de soluções jurídicas.</p>
            </div>

            <!-- Seção de Propaganda dos Serviços -->
            <h2 class="fw-bold text-center mb-3">Conheça Nossos Serviços</h2>
            <div class="row">
                <!-- Card: Direito Civil -->
                <div class="col-md-3 mb-4">
                    <div class="card service-card h-100">
                        <img src="{{ asset('img/divorcio.jpg') }}" class="card-img-top" alt="Direito Civil">
                        <div class="card-body">
                            <h5 class="card-title">Direito Civil</h5>
                            <p class="card-text">Soluções para conflitos familiares, contratos e disputas civis, garantindo seus direitos.</p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCivil">Quero detalhes</button>
                        </div>
                    </div>
                </div>
                <!-- Card: Direito Previdenciário -->
                <div class="col-md-3 mb-4">
                    <div class="card service-card h-100">
                        <img src="{{ asset('img/aposentadoria.jpg') }}" class="card-img-top" alt="Direito Previdenciário">
                        <div class="card-body">
                            <h5 class="card-title">Direito Previdenciário</h5>
                            <p class="card-text">Orientação sobre benefícios, aposentadoria e auxílio-doença para garantir seus direitos.</p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPrevidenciario">Quero detalhes</button>
                        </div>
                    </div>
                </div>
                <!-- Card: Direito Família -->
                <div class="col-md-3 mb-4">
                    <div class="card service-card h-100">
                        <img src="{{ asset('img/familia.jpg') }}" class="card-img-top" alt="Direito Família">
                        <div class="card-body">
                            <h5 class="card-title">Direito Família</h5>
                            <p class="card-text">Assistência em divórcios, guarda e pensão alimentícia, sempre buscando a melhor solução.</p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFamilia">Quero detalhes</button>
                        </div>
                    </div>
                </div>
                <!-- Card: Direito Trabalhista -->
                <div class="col-md-3 mb-4">
                    <div class="card service-card h-100">
                        <img src="{{ asset('img/trabalhista.jpg') }}" class="card-img-top" alt="Direito Trabalhista">
                        <div class="card-body">
                            <h5 class="card-title">Direito Trabalhista</h5>
                            <p class="card-text">Protegemos seus direitos no ambiente de trabalho em casos de demissão, discriminação e mais.</p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTrabalhista">Quero detalhes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modais dos Serviços -->
<!-- Modal Direito Civil -->
<div class="modal fade" id="modalCivil" tabindex="-1" aria-labelledby="modalCivilLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCivilLabel">Detalhes - Direito Civil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>Quando você ou sua empresa precisam resolver problemas relacionados a contratos, propriedade ou danos, a área de Direito Civil entra em ação. Na MR Telles, ajudamos você a entender seus direitos, resolver conflitos de forma justa e encontrar as melhores soluções para as questões civis do seu dia a dia.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Direito Previdenciário -->
<div class="modal fade" id="modalPrevidenciario" tabindex="-1" aria-labelledby="modalPrevidenciarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPrevidenciarioLabel">Detalhes - Direito Previdenciário</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>Na área previdenciária, orientamos e auxiliamos clientes em questões relacionadas a benefícios, aposentadoria e auxílio-doença. Nosso objetivo é garantir que você receba todos os benefícios a que tem direito, ajudando na organização da documentação e na contestação de decisões negativas do INSS.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Direito Família -->
<div class="modal fade" id="modalFamilia" tabindex="-1" aria-labelledby="modalFamiliaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFamiliaLabel">Detalhes - Direito Família</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>Enfrentar questões familiares, como divórcios, guarda de filhos e pensão alimentícia, pode ser um processo delicado. Nossa equipe especializada em Direito de Família está pronta para oferecer a assistência necessária, buscando sempre a melhor solução para proteger os interesses e o bem-estar de todos os envolvidos.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Direito Trabalhista -->
<div class="modal fade" id="modalTrabalhista" tabindex="-1" aria-labelledby="modalTrabalhistaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTrabalhistaLabel">Detalhes - Direito Trabalhista</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>Em situações de demissão injusta, discriminação ou não pagamento de salário, nosso time de Direito Trabalhista atua para proteger seus direitos. Estamos preparados para oferecer suporte e buscar a reparação adequada, assegurando que seus direitos sejam respeitados no ambiente de trabalho.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<style>
    /* Variáveis de Cores */
    :root {
        --color1: #775c1c; /* marrom */
        --color2: #907332;
        --color3: #aa8b49;
        --color4: #c3a25f;
        --color5: #dcb975;
        --text-dark: rgb(10, 10, 10);
    }

    /* Card Principal para Unificar o Conteúdo */
    .main-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background: #fff;
    }

    /* Cabeçalho de Boas-Vindas */
    .welcome-header {
        background: linear-gradient(135deg, var(--color1), var(--color2), var(--color3), var(--color4));
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        color: var(--color5);
        position: relative;
        overflow: hidden;
    }
    .welcome-header::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--color5);
        opacity: 0.05;
        pointer-events: none;
    }
    .welcome-header h1 {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .welcome-header .user-name {
        color: var(--text-dark);
    }
    .welcome-header p {
        font-size: 1.25rem;
    }

    /* Cartões de Serviço */
    .service-card {
        border: none;
        border-radius: 0.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .service-card .card-img-top {
        height: 180px;
        object-fit: cover;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .btn-primary {
        background: linear-gradient(to right, var(--color5), var(--color1));
        border: none;
        transition: background 0.3s ease;
    }
    .btn-primary:hover {
        background: linear-gradient(to right, var(--color1), var(--color5));
    }

    /* Modais */
    .modal-header {
        background: var(--color1); /* Cabeçalho com cor marrom de destaque */
        color: var(--color4);
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .modal-header .btn-close {
        filter: invert(1);
    }
    .modal-content {
        border-radius: 0.5rem;
    }
</style>
@endsection
