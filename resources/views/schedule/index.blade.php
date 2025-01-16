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
            timeZone: 'local',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            editable: true,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            },

            eventContent: function(info) {
                var eventTitle = info.event.title;
                var eventTime = info.event.start ? info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                var eventElement = document.createElement('div');
                eventElement.innerHTML = `<span style="cursor: pointer;">❌</span> ${eventTime} ${eventTitle}`;

                eventElement.querySelector('span').addEventListener('click', function() {
                    if (confirm("Você tem certeza que gostaria de deletar esse evento?")) {
                        var eventId = info.event.id;
                        $.ajax({
                            method: 'DELETE',
                            url: '/schedule/' + eventId,
                            success: function() {
                                console.log('Evento deletado com sucesso.');
                                calendar.refetchEvents();
                            },
                            error: function(error) {
                                console.error('Erro ao deletar o evento', error);
                            }
                        });
                    }
                });
                return { domNodes: [eventElement] };
            },

            eventDrop: function(info) {
                var eventId = info.event.id;
                var newStartDate = info.event.start;
                var newEndDate = info.event.end || newStartDate;

                $.ajax({
                    method: 'PUT',
                    url: `/schedule/${eventId}`,
                    data: {
                        start_date: newStartDate.toISOString(),
                        end_date: newEndDate.toISOString()
                    },
                    success: function() {
                        console.log('Evento movido com sucesso.');
                    },
                    error: function(error) {
                        console.error('Erro ao mover o evento:', error);
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

        document.getElementById('exportButton').addEventListener('click', function() {
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
</body>
</html>
