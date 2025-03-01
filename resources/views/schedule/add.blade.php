<!--Bootstrap 5 e Flatpickr-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    body {
        background-color: #f8f9fa;
    }

    .form-container {
        max-width: 500px;
        margin: 50px auto;
        background: #ffffff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        font-weight: bold;
        margin-top: 15px;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px;
    }

    .btn-success {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        font-weight: bold;
        transition: background 0.3s;
    }

    .btn-success:hover {
        background: #28a745;
    }

    .color-picker {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid #ddd;
    }
</style>

<div class="container">
    <div class="form-container">
        <h4 class="text-center mb-4">📅 Criar Novo Evento</h4>
        
        <form action="{{ url('create-schedule') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="title">Título</label>
                <input type="text" class="form-control shadow-sm" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="all_day">
                    <input type="checkbox" id="all_day" name="all_day" value="1"> Evento o dia todo
                </label>
            </div>

            <div class="mb-3">
                <label for="start">Início</label>
                <input type="text" class="form-control shadow-sm" id="start" name="start">
            </div>

            <div class="mb-3">
                <label for="end">Fim</label>
                <input type="text" class="form-control shadow-sm" id="end" name="end">
            </div>

            <div class="mb-3">
                <label for="description">Descrição</label>
                <textarea class="form-control shadow-sm" id="description" name="description"></textarea>
            </div>

            <div class="mb-3">
                        <label for="edit_color">Tipo de Evento</label>
                        <select id="edit_color" class="form-control">
                            <option value="#28a745">Normal</option>  <!-- Verde -->
                            <option value="#ffc107">Importante</option>  <!-- Amarelo -->
                            <option value="#dc3545">Muito Importante</option>  <!-- Vermelho -->
                        </select>
            </div>

            <button type="submit" class="btn btn-success">Salvar Evento</button>
        </form>
    </div>
</div>

<!-- Scripts do Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#start", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        time_24hr: true,
        onChange: function(selectedDates, dateStr) {
            document.getElementById('end')._flatpickr.set('minDate', dateStr);
        }
    });

    flatpickr("#end", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        time_24hr: true
    });

    document.getElementById("color").addEventListener("input", function() {
        document.getElementById("colorPreview").style.backgroundColor = this.value;
    });

    // Desativa os campos de data/hora quando "All Day" for marcado
    document.getElementById('all_day').addEventListener('change', function() {
    let isChecked = this.checked;
    let today = new Date().toISOString().split('T')[0];

    document.getElementById('start').disabled = isChecked;
    document.getElementById('end').disabled = isChecked;

    if (isChecked) {
        document.getElementById('start').value = today;
        document.getElementById('end').value = today;
    } else {
        document.getElementById('start').value = "";
        document.getElementById('end').value = "";
    }
});

</script>