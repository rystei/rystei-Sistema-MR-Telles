<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Senha</title>
    <style>
        :root {
            --bege: #E6E0D6;
            --marrom-escuro: #B08968;
            --marrom-claro: #B29463;
            --marrom-destaque: #DDB892;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: var(--bege);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            animation: fadeIn 1s ease-in;
        }

        .password-card {
            background: white;
            width: 100%;
            max-width: 600px;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            animation: slideUp 0.8s ease-out;
        }

        .password-title {
            text-align: center;
            color: var(--marrom-escuro);
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .instructions {
            color: var(--marrom-escuro);
            margin-bottom: 2rem;
            line-height: 1.6;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--marrom-escuro);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--marrom-claro);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--marrom-escuro);
            box-shadow: 0 0 10px rgba(176, 137, 104, 0.2);
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .submit-button {
            padding: 1rem 2rem;
            background: var(--marrom-claro);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .submit-button:hover {
            background: var(--marrom-escuro);
            transform: translateY(-2px);
        }

        .login-link {
            color: var(--marrom-escuro);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: var(--marrom-claro);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .password-card {
                padding: 2rem;
            }

            .button-container {
                flex-direction: column;
                gap: 1rem;
            }

            .submit-button {
                width: 100%;
                order: 2;
            }

            .login-link {
                order: 1;
            }
        }
    </style>
</head>
<body>
    <div class="password-card">
        <h1 class="password-title">Recuperar Senha</h1>
        
        <div class="instructions">
            Esqueceu sua senha? Sem problemas. Informe seu endereço de email e enviaremos um link para redefinição.
        </div>

        @if(session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    class="form-input"
                >
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="button-container">
                <a class="login-link" href="{{ route('login') }}">
                    Voltar para login
                </a>
                <button type="submit" class="submit-button">
                    Enviar Link de Redefinição
                </button>
            </div>
        </form>
    </div>
</body>
</html>