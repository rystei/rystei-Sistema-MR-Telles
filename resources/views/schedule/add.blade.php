<!--Bootstrap 5 e Flatpickr-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
/* ========== ESTILOS GLOBAIS ========== */
body {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

/* ========== CONTAINER DO FORMULÁRIO ========== */
.form-container {
    max-width: 600px;
    margin: 2rem auto;
    background: white;
    padding: 2.5rem;
    border-radius: 1.25rem;
    box-shadow: 0 0.75rem 2rem rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
}

h4 {
    color: #2c3e50;
    font-weight: 700;
    text-align: center;
    margin-bottom: 2.5rem;
    position: relative;
    padding-bottom: 1rem;
}

h4:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, #0d6efd 0%, #0b5ed7 100%);
    border-radius: 2px;
}

/* ========== COMPONENTES DO FORMULÁRIO ========== */
.form-control {
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    padding: 0.875rem 1.25rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(to right, #f8f9fa, #ffffff);
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
    background: white;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
    display: block;
}

/* ========== CHECKBOX PERSONALIZADO ========== */
.form-check-input {
    width: 1.25em;
    height: 1.25em;
    margin-top: 0.25em;
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
}

/* ========== SELECT DE TIPO DE EVENTO ========== */
.event-type-select {
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    padding: 0.875rem 1.25rem;
    background: linear-gradient(to right, #f8f9fa, #ffffff);
    transition: all 0.3s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px 12px;
}

.event-type-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
}

/* ========== BOTÃO PRIMÁRIO ========== */
.btn-success {
    background: linear-gradient(135deg, #198754 0%, #157347 100%);
    border: none;
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(25, 135, 84, 0.3);
}

.btn-success:active {
    transform: translateY(0);
    box-shadow: 0 0.25rem 0.75rem rgba(25, 135, 84, 0.2);
}

/* ========== RESPONSIVIDADE ========== */
@media (max-width: 768px) {
    .form-container {
        margin: 1.5rem;
        padding: 1.75rem;
        border-radius: 1rem;
    }
    
    h4 {
        font-size: 1.5rem;
        padding-bottom: 0.75rem;
    }
    
    .form-control {
        padding: 0.75rem 1rem;
    }
}

/* ========== ANIMAÇÕES ========== */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.form-container {
    animation: fadeIn 0.6s ease-out;
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