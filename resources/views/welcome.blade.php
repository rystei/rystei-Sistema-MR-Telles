<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../img/Logo_Final.png" type="image/x-icon">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <title>MR TELLES</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../styles.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700&family=Barlow:wght@400;600&display=swap"
    rel="stylesheet">
</head>

<body>

  <header>
    <nav class="navbar-expand-lg navbar bg-body-tertiary fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="../img/Logo_Final.png" alt="Logo do escritório MR TELLES"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
          aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><img src="./img/Logo_Final.png"
                alt="Logo do escritório MR TELLES"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
              <li class="nav-item">
                <a class="nav-link " aria-current="page" href="#">Início</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#servicos">Sobre</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#contato">Contato</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <div class="form-check form-switch">
                  </div>
                </a>
              </li>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <section class="banners banner-1 d-flex flex-column justify-content-center text-center">
      <div class="banners-titulo bg-body-secondary py-5">
        <h2 class="fw-bold">Bem vindo a MR TELLES Sociedade Individual de Advocacia.</h2>
        <p>Na MR TELLES, sua busca por soluções legais termina aqui. Encontre-a conosco!</p>
      </div>
    </section>
    <section class="py-5">
      <h2 id="servicos" class="text-center fw-bold pb-1">Nossos serviços</h2>

      <div class="container d-flex justify-content-around flex-wrap">

        <div class="card m-4" style="width: 20rem;">
          <img src="./img/divorcio.jpg" class="card-img-top" alt="divorcio">
          <div class="card-body">
            <h5 class="card-title py-3 fw-bold">Direito Civil</h5>
            <p class="card-text">Nosso escritório trata das questões legais que envolvem as relações entre pessoas
              e empresas privadas. Isso inclui tudo, desde conflitos familiares, como divórcio e guarda de crianças,
              até questões contratuais e disputas sobre propriedade ou responsabilidade civil.</p>
            <a href="#" class="btn botao-padrao w-100 fw-bold" data-bs-toggle="offcanvas" data-bs-target="#canvas-1"
              aria-controls="offcanvasRight">Quero detalhes</a>
          </div>
        </div>

        <div class="card m-4" style="width: 20rem;">
          <img src="./img/aposentadoria.jpg" class="card-img-top" alt="aposentadoria do individuo">
          <div class="card-body">
            <h5 class="card-title py-2 fw-bold"> Direito Previdenciário</h5>
            <p class="card-text">Lidamos com questões relacionadas à seguridade social e aos direitos dos trabalhadores.
              Isso abrange benefícios como aposentadoria por idade, aposentadoria por tempo de contribuição,
              por invalidez, auxílio-doença, auxílio-acidente, entre outros.</p>
            <a href="#" class="btn botao-padrao w-100 fw-bold mt-3" data-bs-toggle="offcanvas"
              data-bs-target="#canvas-2" aria-controls="offcanvasRight">Quero detalhes</a>
          </div>
        </div>

        <div class="card m-4" style="width: 20rem;">
          <img src="./img/familia.jpg" class="card-img-top" alt="Direito Família">
          <div class="card-body">
            <h5 class="card-title py-2 fw-bold">Direito Família</h5>
            <p class="card-text">Abordamos questões relacionadas aos laços familiares e aos direitos das pessoas dentro
              desse contexto. Isso inclui assuntos como casamento, divórcio, guarda de filhos, pensão alimentícia,
              adoção, paternidade, entre outros.</p>
            <a href="#" class="btn botao-padrao w-100 fw-bold mt-3" data-bs-toggle="offcanvas"
              data-bs-target="#canvas-3" aria-controls="offcanvasRight">Quero detalhes</a>
          </div>
        </div>

        <div class="card m-4" style="width: 20rem;">
          <img src="./img/trabalhista.jpg" class="card-img-top" alt="Direito Trabalhista">
          <div class="card-body">
            <h5 class="card-title py-2 fw-bold">Direito Trabalhista</h5>
            <p class="card-text">Atuamos no que diz respeito às leis e regulamentos que protegem os direitos
              dos trabalhadores e regulam as relações de trabalho entre empregadores e empregados. Isso
              abrange questões como contratos de trabalho, jornada de trabalho, salário mínimo,
              horas extras, férias, demissões, segurança no trabalho, entre outros.</p>
            <a href="#" class="btn botao-padrao w-100 fw-bold mt-3" data-bs-toggle="offcanvas"
              data-bs-target="#canvas-4" aria-controls="offcanvasRight">Quero detalhes</a>
          </div>
        </div>
      </div>
    </section>
    <section class="banners banner-2 d-flex flex-column justify-content-center text-center">
      <div class="banners-titulo bg-body-secondary py-5">
        <h2 class="fw-bold">Portas abertas para todos os públicos.</h2>
        <p>Nosso espaço é aconchegante, preparado para receber todos.</p>
      </div>
    </section>
    <section class="banners banner-3 d-flex flex-column justify-content-center text-center">
      <div class="banners-titulo bg-body-secondary py-5">
        <h2 class="fw-bold">Marque uma consulta com a gente.</h2>
        <p>Definitivamente vamos encontrar uma solução para suas necessidades.</p>
      </div>
    </section>
  </main>

  <!-- canvas 1 -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="canvas-1" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasRightLabel">Serviços</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="accordion" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Direito Civil
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Quando você ou sua empresa precisam resolver um problema relacionado a contratos, propriedade,
              danos ou qualquer outra questão que não envolva crimes, é aí que entra a área civil da advocacia.
              <strong> A MR TELLES está aqui para ajudá-lo </strong> a entender seus direitos, resolver conflitos
              de forma justa e alcançar as melhores soluções possíveis para seus problemas legais.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Questões contratuais
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Quando há contratos envolvidos em transações comerciais ou pessoais, problemas podem surgir em relação ao
              cumprimento dos termos, quebra de contrato, interpretação de cláusulas, entre outros.</div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              Disputas sobre propriedade
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Isso pode incluir litígios relacionados a propriedades imobiliárias, como questões de posse, direitos de
              passagem,
              limites de propriedade e disputas de locação.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
              Responsabilidade civil
            </button>
          </h2>
          <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              No momento ocorrem danos a uma pessoa ou propriedade devido à negligência de outra pessoa ou entidade,
              pode surgir uma
              questão de responsabilidade civil.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- canvas 2 -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="canvas-2" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasRightLabel">Serviços</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="accordion" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Direito Previdenciário
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              No momento que você precisa entender seus direitos em relação à previdência social, solicitar um
              benefício ou recorrer contra uma decisão negativa do INSS (Instituto Nacional do Seguro Social), é a
              área previdenciária da advocacia que entra em ação. Nós estamos aqui para orientá-lo pelo sistema
              previdenciário, garantir que você receba os benefícios aos quais tem direito e defender seus interesses
              diante de qualquer problema ou contestação.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Aposentadoria por Idade
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Um trabalhador que completou a idade mínima exigida pela lei para se aposentar, mas enfrenta dificuldades
              para
              obter o benefício junto ao INSS devido a inconsistências em sua documentação.</div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              Aposentadoria por Invalidez
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Um indivíduo que sofreu um acidente de trabalho e ficou incapacitado permanentemente para o exercício de
              suas
              atividades laborais, buscando obter o benefício de aposentadoria por invalidez.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
              Auxílio-Doença
            </button>
          </h2>
          <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Um trabalhador que está temporariamente incapacitado para o trabalho devido a uma doença ou lesão e
              precisa de
              assistência financeira durante o período de afastamento.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- canvas 3 -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="canvas-3" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasRightLabel">Serviços</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="accordion" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Direito Família
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              No caso de surgir problemas familiares que exigem assistência legal, como decidir sobre a custódia dos
              filhos após
              o divórcio ou garantir que os direitos dos pais sejam respeitados, é a área de direito da família que
              entra em ação.
              Nosso advogado especializado nessa área vai auxiliar você a entender seus direitos, resolver conflitos de
              maneira
              justa e buscar soluções que protejam o bem-estar e os interesses de sua família.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Divórcio
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Um casal que decidiu se divorciar e precisa resolver questões como partilha de bens, guarda dos filhos e
              pensão
              alimentícia.</div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              Guarda de Filhos
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Pais que estão em processo de divórcio e buscam chegar a um acordo sobre a guarda dos filhos, levando em
              consideração
              o melhor interesse das crianças.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
              Pensão Alimentícia
            </button>
          </h2>
          <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Um pai ou mãe que busca obter pensão alimentícia para sustentar os filhos após o divórcio ou separação.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- canvas 4 -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="canvas-4" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasRightLabel">Serviços</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="accordion" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Direito Trabalhista
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Ao deparar-se com problemas no local de trabalho, como demissão injusta, discriminação, assédio ou falta
              de pagamento
              de salário, é a área de direito trabalhista que se mobiliza. Profissional especializado nesse campo está à
              sua
              disposição para auxiliá-lo a compreender seus direitos como trabalhador, assegurar que você seja tratado
              de forma
              justa pelo empregador e buscar reparação caso seus direitos tenham sido infringidos.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Demissão Injusta
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Um funcionário que foi demitido sem justa causa e busca orientação legal para avaliar se seus direitos
              foram violados e se há possibilidade de buscar indenização ou reintegração ao emprego.</div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              Discriminação
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Um trabalhador que foi vítima de discriminação no ambiente de trabalho com base em sua raça, gênero,
              religião,
              orientação sexual, entre outros aspectos, e deseja tomar medidas legais contra o empregador.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed botao-padrao" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
              Não Pagamento de Salário
            </button>
          </h2>
          <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Um empregado que não recebeu seu salário conforme acordado e precisa de assistência legal para reivindicar
              os
              valores devidos junto ao empregador.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer id="contato" class="text-center py-5">
  <div class="modal fade" id="modal-1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title fs-5 fw-bold" id="exampleModalLabel">MR TELLES</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><a href="https://wa.me/5543988355501">MR TELLES</a></p>
        </div>
        <div class="modal-footer mx-auto">
          <button type="button" class="btn btn-secondary botao-padrao border-0" data-bs-dismiss="modal">Fechar janela</button>
        </div>
      </div>
    </div>
  </div>
  <section class="container p-5">
  <a href="#!" class="btn" data-bs-toggle="modal" data-bs-target="#modal-1"><i class="bi bi-whatsapp whatsapp-icon"></i></a>
  </section>
  <div class="p-5">
    2024 <i class="bi bi-c-circle"></i> Desenvolvido por MR Telles Sociedade Individual de Advocacia.
  </div>
</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>

</html>