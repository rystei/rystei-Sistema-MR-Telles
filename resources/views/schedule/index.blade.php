<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Compromisso</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="{{ asset('pt-br.global.min.js') }}"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Procurar eventos">
                    <div class="input-group-append">
                        <button id="searchButton" class="btn btn-primary">{{ __('Procurar') }}</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="btn-group mb-3" role="group" aria-label="Calendar Actions">
                    <button id="exportButton" class="btn btn-success">{{ __('Exportar Calendário') }}</button>
                </div>
                <div class="btn-group mb-3" role="group" aria-label="Calendar Actions">
                    <a href="{{ URL('add-schedule') }}" class="btn btn-success">{{ __('Adicionar Evento') }}</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="calendar" style="width: 100%; height: 100vh;"></div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'pt-br',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            timeZone: 'UTC',
            editable: true,

            eventContent: function(info) {
                var eventTitle = info.event.title;
                var eventElement = document.createElement('div');
                eventElement.innerHTML = '<span style="cursor: pointer;">❌</span> ' + eventTitle;

                eventElement.querySelector('span').addEventListener('click', function() {
                    if (confirm("Você tem certeza que gostaria de deletar esse evento?")) {
                        var eventId = info.event.id;
                        $.ajax({
                            method: 'DELETE',
                            url: '/schedule/' + eventId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log('Evento deletado com sucesso.');
                                calendar.refetchEvents();
                            },
                            error: function(error) {
                                console.error('Erro ao deletar o evento', error);
                            }
                        });
                    }
                });
                return {
                    domNodes: [eventElement]
                };
            },
            
            eventDrop: function(info) {
                var eventId = info.event.id;
                var newStartDate = info.event.start;
                var newEndDate = info.event.end || newStartDate;
                var newStartDateUTC = newStartDate.toISOString().slice(0, 10);
                var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                $.ajax({
                    method: 'PUT',
                    url: `/schedule/${eventId}`,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        start_date: newStartDateUTC,
                        end_date: newEndDateUTC,
                    },
                    success: function() {
                        console.log('Evento movido com sucesso.');
                    },
                    error: function(error) {
                        console.error('Erro ao mover o evento:', error);
                    }
                });
            },

            eventResize: function(info) {
                var eventId = info.event.id;
                var newEndDate = info.event.end;
                var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                $.ajax({
                    method: 'PUT',
                    url: `/schedule/${eventId}/resize`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        end_date: newEndDateUTC
                    },
                    success: function() {
                        console.log('Evento redimensionado com sucesso.');
                    },
                    error: function(error) {
                        console.error('Erro no redimensionamento do evento:', error);
                    }
                });
            },

            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '/events',
                    method: 'GET',
                    success: function(response) {
                        successCallback(response);
                    },
                    error: function(error) {
                        failureCallback(error);
                    }
                });
            }
        });

        calendar.render();

        document.getElementById('searchButton').addEventListener('click', function() {
            var searchKeywords = document.getElementById('searchInput').value.toLowerCase();
            filterAndDisplayEvents(searchKeywords);
        });

        function filterAndDisplayEvents(searchKeywords) {
            console.log(`Procurando por: ${searchKeywords}`);
                 $.ajax({
                 method: 'GET',
                 url: `/events/search?title=${searchKeywords}`,
                success: function(response) {
                  console.log('Resposta da busca:', response);

        // Ir para a data do primeiro evento encontrado
        calendar.gotoDate(new Date(response[0].start));

      // Limpar todos os eventos atuais do calendário
      calendar.removeAllEvents();

      // Se não houver resposta, não há eventos para adicionar
      if (response.length === 0) {
        console.log('Nenhum evento encontrado.');
        return;
      }

      // Adicionar apenas os eventos correspondentes à busca
      $.each(response, function(index, searchedEvent) {
        console.log(`Adicionando evento: ${searchedEvent}`);
        calendar.addEvent(searchedEvent);
      });

    },
    error: function(xhr, status, error) {
      console.error(`Erro ao procurar eventos: ${error} (${status})`);
    }
  });
}  

        document.getElementById('exportButton').addEventListener('click', function() {
            var events = calendar.getEvents().map(function(event) {
                return {
                    title: event.title,
                    start: event.start ? event.start.toISOString() : null,
                    end: event.end ? event.end.toISOString() : null,
                    color: event.backgroundColor,
                };
            });

            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.json_to_sheet(events);
            XLSX.utils.book_append_sheet(wb, ws, 'Events');

            var arrayBuffer = XLSX.write(wb, {
                bookType: 'xlsx',
                type: 'array'
            });

            var blob = new Blob([arrayBuffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });

            var downloadLink = document.createElement('a');
            downloadLink.href = URL.createObjectURL(blob);
            downloadLink.download = 'events.xlsx';
            downloadLink.click();
        });
    </script>
</body>
</html>
