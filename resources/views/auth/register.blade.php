<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro</title>
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
        align-items: flex-start; /* Alterado para melhor ajuste */
        justify-content: center;
        padding: 2rem;
        animation: fadeIn 1s ease-in;
    }

    .register-container {
        width: 100%;
        max-width: 600px;
    }

    .register-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: slideUp 0.8s ease-out;
        max-height: 95vh; /* Limite máximo de altura */
        overflow-y: auto; /* Scroll apenas se necessário */
    }

    .register-title {
        text-align: center;
        color: var(--marrom-escuro);
        margin-bottom: 2rem;
        font-size: 1.8rem;
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
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .button-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: space-between;
    }

    .register-button {
        padding: 1rem 2rem;
        background: var(--marrom-claro);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-grow: 1;
    }

    @media (max-width: 768px) {
        body {
            padding: 1rem;
            align-items: flex-start;
        }

        .register-card {
            padding: 1.5rem;
            max-height: none;
        }

        .register-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-input {
            padding: 0.8rem;
            font-size: 0.9rem;
        }

        .button-container {
            flex-direction: column;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .register-button {
            width: 100%;
            order: 2; /* Botão fica embaixo */
        }

        .login-link {
            order: 1; /* Link fica acima */
            text-align: center;
        }
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
            .register-card {
                padding: 2rem;
            }

            .button-container {
                flex-direction: column;
                gap: 1.5rem;
                align-items: flex-start;
            }

            .register-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <h1 class="register-title">Criar Conta</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label class="form-label" for="name">Nome</label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        class="form-input"
                    >
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        class="form-input"
                    >
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Senha</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        class="form-input"
                    >
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar Senha</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        class="form-input"
                    >
                    @error('password_confirmation')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="button-container">
                    <a class="login-link" href="{{ route('login') }}">
                        Já tem uma conta?
                    </a>
                    <button type="submit" class="register-button">
                        Cadastrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>