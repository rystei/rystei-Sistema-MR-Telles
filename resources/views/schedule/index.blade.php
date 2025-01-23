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
    <link rel="stylesheet" href="estilos/schedules.css">
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">MR TELLES</a>
    </nav>

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
                <div class="btn-group mb-3" role="group">
                    <button id="exportButton" class="btn btn-success">{{ __('Exportar Calendário') }}</button>
                </div>
                <div class="btn-group mb-3" role="group">
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

    <!-- MODAL PARA DETALHES DO EVENTO -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Início:</strong> <span id="eventStart"></span></p>
                    <p><strong>Fim:</strong> <span id="eventEnd"></span></p>
                    <p><strong>Descrição:</strong> <span id="eventDescription"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
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
            timeZone: 'local',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            editable: true,
            eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: false },

            eventClick: function(info) {
                $('#eventTitle').text(info.event.title);

                if (info.event.allDay) {
                    $('#eventStart').text('Evento de dia inteiro');
                    $('#eventEnd').text('');
                } else {
                    $('#eventStart').text(info.event.start.toLocaleString());
                    $('#eventEnd').text(info.event.end ? info.event.end.toLocaleString() : 'Sem horário definido');
                }

                $('#eventDescription').text(info.event.extendedProps.description || 'Sem descrição');
                $('#eventModal').modal('show');
            },

            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '/events',
                    method: 'GET',
                    success: function(response) {
                        response = response.map(event => {
                            if (!event.start) {
                                event.start = new Date().toISOString().split('T')[0];
                                event.allDay = true;
                            } else {
                                event.allDay = event.allDay ?? false;
                            }
                            return event;
                        });
                        successCallback(response);
                    },
                    error: function(error) {
                        failureCallback(error);
                    }
                });
            }
        });

        calendar.render();

        $('#searchButton').on('click', function() {
            var searchKeywords = $('#searchInput').val().toLowerCase();
            $.ajax({
                method: 'GET',
                url: `/events/search?title=${searchKeywords}`,
                success: function(response) {
                    calendar.removeAllEvents();
                    calendar.addEventSource(response);
                    calendar.gotoDate(response.length > 0 ? response[0].start : new Date());
                },
                error: function(xhr) {
                    console.error('Erro ao buscar eventos:', xhr);
                }
            });
        });

        $('#exportButton').on('click', function() {
            var events = calendar.getEvents().map(function(event) {
                return {
                    title: event.title,
                    start: event.start ? event.start.toISOString() : '',
                    end: event.end ? event.end.toISOString() : '',
                    color: event.backgroundColor
                };
            });

            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.json_to_sheet(events);
            XLSX.utils.book_append_sheet(wb, ws, 'Events');
            XLSX.writeFile(wb, 'events.xlsx');
        });
    </script>

    <!-- Scripts do Bootstrap para funcionamento do modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
