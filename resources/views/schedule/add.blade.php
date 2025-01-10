
    <style>
        form {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 20px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: green;
            color: white;
        }
    </style>

    <form action="{{ URL('/create-schedule') }}" method="POST">
        @csrf
        <label for='title'>{{ __('Título') }}</label>
        <input type='text' class='form-control' id='title' name='title' required>

        <label for="start">{{__('Início')}}</label>
        <input type='datetime-local' class='form-control' id='start' name='start' 
                required value="{{ now()->format('Y-m-d\TH:i') }}">

        <label for="end">{{__('Fim')}}</label>
        <input type='datetime-local' class='form-control' id='end' name='end' 
                required value="{{ now()->format('Y-m-d\TH:i') }}">


        <label for="description">{{__('Descrição')}}</label>
        <textarea id="description" name="description"></textarea>

        <label for="color">{{__('Cor')}}</label>
        <input type="color" id="color" name="color" />

        <input type="submit" value="Save" class="btn btn-success" />
    </form>
