<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gerenciar Compromisso</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="estilos/schedules.css">
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
  <script src="{{ asset('pt-br.global.min.js') }}"></script>
</head>
<body>


<div class="container mt-5">
  <div class="row mb-3">
    <!-- Coluna da busca -->
    <div class="col-md-6">
      <div class="input-group">
        <input type="text" id="searchInput" class="form-control" placeholder="Procurar eventos">
        <button id="searchButton" class="btn btn-primary">{{ __('Procurar') }}</button>
      </div>
    </div>

    <!-- Coluna dos botões -->
    <div class="col-md-6">
      <div class="d-flex gap-2 align-items-center justify-content-end">
        <div class="btn-group">
          <button id="exportButton" class="btn btn-success">{{ __('Exportar Calendário') }}</button>
          <a href="{{ URL('add-schedule') }}" class="btn btn-success">{{ __('Adicionar Evento') }}</a>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary ms-2">{{ __('Voltar ao painel de controle') }}</a>
      </div>
    </div>
  </div>

  <!-- Calendário -->
  <div class="card">
    <div class="card-body">
      <div id="calendar" style="width: 100%; height: 100vh;"></div>
    </div>
  </div>
</div>

  <!-- Modal para Edição de Evento -->
  <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editEventModalLabel">Editar Evento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <form id="editEventForm">
            <input type="hidden" id="event_id">
            <div class="mb-3">
              <label for="edit_title">Título</label>
              <input type="text" class="form-control" id="edit_title" required>
            </div>
            <div class="mb-3">
              <label>
                <input type="checkbox" id="edit_allDay"> Evento o dia todo
              </label>
            </div>
            <div class="mb-3">
              <label for="edit_start">Início</label>
              <input type="text" class="form-control" id="edit_start">
            </div>
            <div class="mb-3">
              <label for="edit_end">Fim</label>
              <input type="text" class="form-control" id="edit_end">
            </div>
            <div class="mb-3">
              <label for="edit_description">Descrição</label>
              <textarea class="form-control" id="edit_description"></textarea>
            </div>
            <div class="mb-3">
              <label for="edit_color">Tipo de Evento</label>
              <select id="edit_color" class="form-control">
                <option value="#28a745">Normal</option>
                <option value="#ffc107">Importante</option>
                <option value="#dc3545">Muito Importante</option>
              </select>
            </div>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <button type="button" id="deleteEvent" class="btn btn-danger">Excluir Evento</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <style>
        body {
            background-color: #f8f9fa;
            padding-top: 1rem;
        }
        

        .calendar-container {
            margin-top: 1.5rem;
        }

        .header-section {
            margin-bottom: 1.5rem;
        }

        #calendar {
            height: 125vh;
            background: white;
            border-radius: 0.75rem;
            padding: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
        }

        .fc-toolbar-title {
            font-size: 1.25rem;
            color: #2c3e50;
        }

        .fc-button {
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem !important;
        }

        .fc-event {
            padding: 0.5rem !important;
            margin: 0.25rem 0 !important;
            border-left: 4px solid rgba(0,0,0,0.15) !important;
        }

        .modal-content {
            border-radius: 0.75rem;
            border: none;
        }

        @media (max-width: 768px) {
            #calendar {
                height: 65vh;
                padding: 0.5rem;
            }
            
            .header-section {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
  <!-- Scripts do Flatpickr -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    // Função para reinicializar o Flatpickr nos inputs de edição com base no estado do checkbox "Evento o dia todo"
    function initializeEditFlatpickr(isAllDay) {
      if (isAllDay) {
        // Se all-day, inputs no formato "Y-m-d"
        flatpickr("#edit_start", {
          enableTime: false,
          dateFormat: "Y-m-d",
          minDate: "today"
        });
        flatpickr("#edit_end", {
          enableTime: false,
          dateFormat: "Y-m-d",
          minDate: "today"
        });
      } else {
        // Se não for all-day, inputs no formato "Y-m-dTH:i" (compatível com datetime-local)
        flatpickr("#edit_start", {
          enableTime: true,
          dateFormat: "Y-m-d\\TH:i",
          minDate: "today",
          time_24hr: true,
          onChange: function(selectedDates, dateStr) {
            let endPicker = document.getElementById('edit_end')._flatpickr;
            if (endPicker) { endPicker.set('minDate', dateStr); }
          }
        });
        flatpickr("#edit_end", {
          enableTime: true,
          dateFormat: "Y-m-d\\TH:i",
          minDate: "today",
          time_24hr: true
        });
      }
    }

    // Quando o modal é aberto, inicializa o Flatpickr com o estado atual do checkbox
    $('#editEventModal').on('show.bs.modal', function() {
      let isAllDay = $('#edit_allDay').is(':checked');
      initializeEditFlatpickr(isAllDay);
    });

    // Quando o checkbox "Evento o dia todo" é alterado, reinicializa os Flatpickr
    $('#edit_allDay').change(function() {
      let isChecked = $(this).is(':checked');
      initializeEditFlatpickr(isChecked);
    });
  </script>

  <!-- JavaScript do FullCalendar e manipulação do modal -->
  <script>
    // Função auxiliar para formatar uma data local no formato "YYYY-MM-DDTHH:mm"
    function formatLocalDate(date) {
      let year = date.getFullYear();
      let month = ("0" + (date.getMonth() + 1)).slice(-2);
      let day = ("0" + date.getDate()).slice(-2);
      let hours = ("0" + date.getHours()).slice(-2);
      let minutes = ("0" + date.getMinutes()).slice(-2);
      return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    $(document).ready(function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        timeZone: 'America/Sao_Paulo',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        editable: true,
        eventClick: function(info) {
          $('#event_id').val(info.event.id);
          $('#edit_title').val(info.event.title);
          $('#edit_allDay').prop('checked', info.event.allDay);

          if (info.event.allDay) {
            $('#edit_start, #edit_end').attr('type', 'date');
            $('#edit_start').val(info.event.start ? info.event.start.toISOString().split('T')[0] : '');
            $('#edit_end').val(info.event.end ? info.event.end.toISOString().split('T')[0] : '');
          } else {
            $('#edit_start, #edit_end').attr('type', 'datetime-local');
            // Utiliza a função auxiliar para formatar a data local sem conversão UTC
            $('#edit_start').val(formatLocalDate(new Date(info.event.start)));
            $('#edit_end').val(info.event.end ? formatLocalDate(new Date(info.event.end)) : '');
          }
          $('#edit_description').val(info.event.extendedProps.description || '');
          $('#edit_color').val(info.event.backgroundColor || '#28a745');
          $('#editEventModal').modal('show');
        },
        events: function(fetchInfo, successCallback, failureCallback) {
          $.ajax({
            url: '/events',
            method: 'GET',
            success: function(response) {
              // Espera que o backend retorne as datas no formato esperado
              successCallback(response);
            },
            error: function(error) {
              console.error('Erro ao carregar eventos:', error);
              failureCallback(error);
            }
          });
        }
      });

      calendar.render();
      
      // Submeter edição do evento
      $('#editEventForm').submit(function(event) {
          event.preventDefault();

          let allDay = $('#edit_allDay').is(':checked');
          let start = $('#edit_start').val();
          let end = $('#edit_end').val() || start;

          // Se for all-day, os inputs já estão no formato "Y-m-d" – NÃO os altere
          // Se não for all-day, os inputs estão no formato "Y-m-dTH:i" e serão enviados assim
          // Dessa forma, o valor enviado estará no formato esperado pelo controller

          var eventData = {
            id: $('#event_id').val(),
            title: $('#edit_title').val(),
            start: start,
            end: end,
            color: $('#edit_color').val(),
            all_day: allDay,
            description: $('#edit_description').val() || null
          };

          $.ajax({
            url: '/events/update/' + eventData.id,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(eventData),
            success: function(response) {
              let modalEl = document.getElementById('editEventModal');
              let modal = bootstrap.Modal.getInstance(modalEl);
              if (modal) { modal.hide(); }
              calendar.refetchEvents();
            },
            error: function(xhr) {
              console.error('Erro ao atualizar evento:', xhr);
            }
          });
      });


      // Excluir evento
      $('#deleteEvent').click(function() {
        var eventId = $('#event_id').val();
        if (confirm('Tem certeza que deseja excluir este evento?')) {
          $.ajax({
            url: '/events/delete/' + eventId,
            method: 'DELETE',
            success: function() {
              let modalEl = document.getElementById('editEventModal');
              let modal = bootstrap.Modal.getInstance(modalEl);
              if (modal) { modal.hide(); }
              calendar.refetchEvents();
            },
            error: function(xhr) {
              console.error('Erro ao excluir evento:', xhr);
            }
          });
        }
      });

// Função auxiliar para realizar a busca
function fetchOriginalEvents(fetchInfo, successCallback, failureCallback) {
    $.ajax({
      url: '/events',
      method: 'GET',
      success: function(response) {
        successCallback(response);
      },
      error: function(error) {
        console.error('Erro ao carregar eventos:', error);
        failureCallback(error);
      }
    });
  }

  // Inicialize o calendário usando a fonte original
  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'pt-br',
      timeZone: 'America/Sao_Paulo',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      initialView: 'dayGridMonth',
      editable: true,
      // Configuramos a propriedade events para usar nossa função original
      events: fetchOriginalEvents,
      eventClick: function(info) {
        $('#event_id').val(info.event.id);
        $('#edit_title').val(info.event.title);
        $('#edit_allDay').prop('checked', info.event.allDay);

        if (info.event.allDay) {
          $('#edit_start, #edit_end').attr('type', 'date');
          $('#edit_start').val(info.event.start ? info.event.start.toISOString().split('T')[0] : '');
          $('#edit_end').val(info.event.end ? info.event.end.toISOString().split('T')[0] : '');
        } else {
          $('#edit_start, #edit_end').attr('type', 'datetime-local');
          $('#edit_start').val(info.event.start ? info.event.start.toISOString().slice(0,16) : '');
          $('#edit_end').val(info.event.end ? info.event.end.toISOString().slice(0,16) : '');
        }
        $('#edit_description').val(info.event.extendedProps.description || '');
        $('#edit_color').val(info.event.backgroundColor || '#28a745');
        $('#editEventModal').modal('show');
      }
    });

    calendar.render();

    // Função auxiliar para realizar a pesquisa
    function performSearch(searchKeywords) {
      if (searchKeywords.trim() === "") {
        // Se o campo estiver vazio, remova todas as fontes de eventos
        // e adicione a fonte original
        calendar.getEventSources().forEach(function(source) {
          source.remove();
        });
        calendar.addEventSource(fetchOriginalEvents);
        calendar.refetchEvents();
        return;
      }

      // Se há uma busca, remova todas as fontes para não duplicar
      calendar.getEventSources().forEach(function(source) {
        source.remove();
      });

      $.ajax({
        method: 'GET',
        url: `/events/search?title=${encodeURIComponent(searchKeywords)}`,
        success: function(response) {
          console.log("Resposta da busca:", response);
          // Remove os eventos atuais (caso existam)
          calendar.removeAllEvents();
          if (response.length > 0) {
            // Adiciona os eventos retornados da pesquisa como fonte única
            calendar.addEventSource(response);
            // Navega para a data do primeiro evento encontrado
            calendar.gotoDate(response[0].start);
          } else {
            // Se não houver resultados, apenas limpa os eventos
            calendar.removeAllEvents();
          }
        },
        error: function(xhr) {
          console.error('Erro ao buscar eventos:', xhr);
        }
      });
    }

    // Manipulador para clique no botão de busca
    $('#searchButton').on('click', function() {
      let searchKeywords = $('#searchInput').val().toLowerCase();
      console.log("Clique no botão de busca. Palavra-chave:", searchKeywords);
      performSearch(searchKeywords);
    });

    // Debounce para busca enquanto o usuário digita
    let searchTimeout;
    $('#searchInput').on('keyup', function() {
      clearTimeout(searchTimeout);
      let searchKeywords = $(this).val().toLowerCase();
      console.log("Keyup: palavra-chave =", searchKeywords);
      searchTimeout = setTimeout(function() {
        performSearch(searchKeywords);
      }, 300);
    });
  });
});
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
