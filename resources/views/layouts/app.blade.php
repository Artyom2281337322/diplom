<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Вёрстка-онлайн | @yield('title', 'Обучение вёрстке с нуля')</title>

    <!-- Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            line-height: 1.5;
        }

        /* Шапка */
        .header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #007bff;
            text-decoration: none;
        }

        .logo span {
            color: #212529;
        }

        .nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav a {
            text-decoration: none;
            color: #495057;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav a:hover {
            color: #007bff;
        }

        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #dee2e6;
            color: #495057;
        }

        .btn-outline:hover {
            border-color: #007bff;
            color: #007bff;
        }

        /* Контейнер */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Hero секция */
        .hero {
            padding: 4rem 0;
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }

        /* Прогресс-бар */
        .progress-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .progress-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .progress-percent {
            font-size: 2rem;
            font-weight: 700;
            color: #007bff;
        }

        .progress-bar-container {
            flex: 1;
            height: 10px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: #007bff;
            border-radius: 10px;
            transition: width 0.3s;
        }

        /* Карточки модулей */
        .section-title {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: #212529;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .module-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .module-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .module-icon {
            font-size: 2rem;
            color: #007bff;
            margin-bottom: 1rem;
        }

        .module-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .module-description {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .module-lessons {
            font-size: 0.8rem;
            color: #adb5bd;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .lesson-badge {
            background: #e9ecef;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
        }

        /* Подвал */
        .footer {
            background: white;
            border-top: 1px solid #dee2e6;
            padding: 2rem;
            text-align: center;
            color: #6c757d;
            margin-top: 3rem;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .container {
                padding: 0 1rem;
            }

            .modules-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ url('/') }}" class="logo">
                <span>Вёрстка</span>-онлайн
            </a>
            <div class="nav">
                <a href="{{ url('/') }}">Главная</a>
                <a href="{{ route('course') }}">Курс</a>
                @auth
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.index') }}">Админ-панель</a>
                @endif
               
                @endauth
                @auth

                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">Выйти</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline">Вход</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Регистрация</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <p>© {{ date('Y') }} Вёрстка-онлайн. Бесплатный курс по обучению веб-вёрстке.</p>
    </footer>

    @stack('scripts')
</body>

</html>