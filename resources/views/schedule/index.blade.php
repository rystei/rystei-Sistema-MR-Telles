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

    <!-- MODAL PARA EDIÇÃO DE EVENTO -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventLabel">Editar Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm">
                        <input type="hidden" id="event_id">
                        
                        <div class="mb-3">
                            <label for="edit_title">Título</label>
                            <input type="text" class="form-control" id="edit_title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_start">Início</label>
                            <input type="datetime-local" class="form-control" id="edit_start">
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_end">Fim</label>
                            <input type="datetime-local" class="form-control" id="edit_end">
                        </div>

                        <div class="mb-3">
                            <label for="edit_description">Descrição</label>
                            <textarea class="form-control" id="edit_description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_color">Cor</label>
                            <input type="color" id="edit_color" class="form-control" value="#ff0000">
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="button" id="deleteEvent" class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        $(document).ready(function() {
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
                eventClick: function(info) {
                    $('#event_id').val(info.event.id);
                    $('#edit_title').val(info.event.title);
                    $('#edit_start').val(info.event.start.toISOString().slice(0, 16));
                    $('#edit_end').val(info.event.end ? info.event.end.toISOString().slice(0, 16) : '');
                    $('#edit_description').val(info.event.extendedProps.description || '');
                    $('#edit_color').val(info.event.backgroundColor);
                    $('#editEventModal').modal('show');
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

            $('#editEventForm').submit(function(event) {
                event.preventDefault();
                var eventData = {
                    id: $('#event_id').val(),
                    title: $('#edit_title').val(),
                    start: $('#edit_start').val(),
                    end: $('#edit_end').val(),
                    description: $('#edit_description').val(),
                    color: $('#edit_color').val()
                };

                $.ajax({
                    url: '/events/update/' + eventData.id,
                    method: 'PUT',
                    data: eventData,
                    success: function(response) {
                        $('#editEventModal').modal('hide');
                        calendar.refetchEvents();
                    },
                    error: function(xhr) {
                        console.error('Erro ao atualizar evento:', xhr);
                    }
                });
            });

            $('#deleteEvent').click(function() {
                var eventId = $('#event_id').val();

                if (confirm('Tem certeza que deseja excluir este evento?')) {
                    $.ajax({
                        url: '/events/delete/' + eventId,
                        method: 'DELETE',
                        success: function(response) {
                            $('#editEventModal').modal('hide');
                            calendar.refetchEvents();
                        },
                        error: function(xhr) {
                            console.error('Erro ao excluir evento:', xhr);
                        }
                    });
                }
            });

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
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
