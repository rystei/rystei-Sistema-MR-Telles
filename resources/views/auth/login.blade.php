<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
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

        .split-container {
            display: flex;
            min-height: 100vh;
            animation: fadeIn 1s ease-in;
        }

        .logo-section {
            flex: 1;
            background: var(--marrom-destaque);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .login-section {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem;
            background: var(--bege);
            position: relative;
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 720px; /* Alterado */
            padding: 3rem; /* Alterado */
            border-radius: 24px; /* Alterado */
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            animation: slideUp 0.8s ease-out;
            max-height: 95vh;
            overflow-y: auto;
        }

        .login-title {
            text-align: center;
            color: var(--marrom-escuro);
            margin-bottom: 2.4rem; /* Alterado */
            font-size: 2.2rem; /* Alterado */
        }

        .form-group {
            margin-bottom: 1.8rem; /* Alterado */
        }

        .form-label {
            display: block;
            margin-bottom: 0.6rem; /* Ajuste fino */
            color: var(--marrom-escuro);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 1.2rem; /* Alterado */
            border: 2px solid var(--marrom-claro);
            border-radius: 8px;
            font-size: 1.1rem; /* Alterado */
            transition: all 0.3s ease;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin: 1.8rem 0; /* Alterado */
        }

        .login-button {
            width: 100%;
            padding: 1.2rem; /* Alterado */
            background: var(--marrom-claro);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem; /* Alterado */
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .links-container {
            margin-top: 1.8rem; /* Alterado */
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 1.2rem; /* Alterado */
        }

        @media (max-width: 768px) {
            .split-container {
                flex-direction: column;
            }

            .logo-section {
                padding: 2rem;
                min-height: 30vh; /* Reduzido */
            }

            .login-section {
                padding: 1rem;
                min-height: 70vh; /* Ajustado */
            }

            .login-card {
                padding: 1.5rem;
                margin: 1rem;
                max-height: none;
            }

            .login-title {
                font-size: 1.5rem;
            }
        }

        /* Restante das animações permanece igual */
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
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Seção do Logo -->
        <div class="logo-section">
            <img src="{{ asset('img/Logo_Final.png') }}" alt="Logo" style="max-width: 250px; width: 100%">
        </div>

        <!-- Seção de Login -->
        <div class="login-section">
            <div class="login-card">
                <h1 class="login-title">Bem-vindo</h1>

                @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
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

                    <!-- Senha -->
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

                    <!-- Lembre-me -->
                    <div class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="remember_me" 
                            name="remember"
                        >
                        <label for="remember_me">Lembre de mim</label>
                    </div>

                    <!-- Botão de Login -->
                    <button type="submit" class="login-button">ENTRAR</button>

                    <!-- Links -->
                    <div class="links-container">
                        @if (Route::has('password.request'))
                            <a class="link" href="{{ route('password.request') }}">
                                Esqueceu a senha?
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <div>
                                Não tem conta? 
                                <a class="link" href="{{ route('register') }}">
                                    Cadastre-se
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>