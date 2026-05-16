<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'QuickSell')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap-grid.css') }}">
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2E7D32;
            --accent-color: #FFC107;
            --background-color: #F5F6FA;
            --card-color: #FFFFFF;
            --text-color: #2D3436;
            --text-muted: #636E72;
            --border-color: #DFE6E9;
            --hover-color: #00CEC9;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1 0 auto;
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
        }

        .nav-link:hover {
            color: white !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-primary {
            color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card:hover {
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .price-tag {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--accent-color);
            color: black;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .text-primary {
            color: var(--accent-color) !important;
        }

        .bg-primary {
            background-color: var(--accent-color) !important;
        }

        .border-primary {
            border-color: var(--accent-color) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0;
            flex-shrink: 0;
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background-color: var(--background-color);
        }

        .profile-image-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--background-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .badge.bg-success {
            background-color: var(--accent-color) !important;
        }

        .pagination .page-link {
            color: var(--accent-color);
            border-color: var(--border-color);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .dropdown-item:hover {
            background-color: var(--background-color);
        }

        .dropdown-item.text-primary:hover {
            background-color: rgba(76, 175, 80, 0.1);
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(0,184,148,0.25);
        }

        /* Стили для основного контейнера */
        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Адаптивность для разных экранов */
        @media (max-width: 1200px) {
            .container {
                max-width: 960px;
            }
        }

        @media (max-width: 992px) {
            .container {
                max-width: 720px;
            }
        }

        @media (max-width: 768px) {
            .container {
                max-width: 540px;
            }
        }

        @media (max-width: 576px) {
            .container {
                max-width: 100%;
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-shopping-bag me-2"></i>QuickSell
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ads.index') }}">Все объявления</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ads.create') }}">Разместить объявление</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Войти</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="fas fa-user me-2"></i>Профиль
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('my-ads') }}">
                                        <i class="fas fa-list me-2"></i>Мои объявления
                                    </a>
                                </li>
                                @if(!Auth::user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('messages.index') }}">
                                        <i class="fas fa-comments me-2"></i>Сообщения
                                    </a>
                                </li>
                                @endif
                                @if(Auth::user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-primary fw-bold" href="{{ route('admin.ads.index') }}">
                                        <i class="fas fa-shield-alt me-2"></i>Панель администратора
                                    </a>
                                </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Выйти
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @if (session('success'))
            <div id="flash-message" class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4" style="z-index: 1050; transition: opacity 0.3s ease-in-out;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div id="flash-message" class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4" style="z-index: 1050; transition: opacity 0.3s ease-in-out;">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="footer mt-auto">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4 text-center">
                    <h5>О нас</h5>
                    <p>QuickSell - платформа для размещения объявлений о продаже товаров.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Ссылки</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('ads.index') }}" class="text-white">Все объявления</a></li>
                        <li><a href="{{ route('ads.create') }}" class="text-white">Разместить объявление</a></li>
                    </ul>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Контакты</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i>support@quicksell.com</li>
                        <li><i class="fas fa-phone me-2"></i>8-800-555-35-35</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} QuickSell. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.js') }}"></script>
    <script>
        // Сохранение позиции прокрутки перед обновлением страницы
        window.addEventListener('beforeunload', function() {
            localStorage.setItem('scrollPosition', window.scrollY);
        });

        // Восстановление позиции прокрутки после загрузки страницы
        window.addEventListener('load', function() {
            const scrollPosition = localStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                localStorage.removeItem('scrollPosition');
            }
        });

        // Показ flash-сообщений
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(function() {
                    flashMessage.style.opacity = '0';
                    setTimeout(function() {
                        flashMessage.remove();
                    }, 300);
                }, 3000);
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
