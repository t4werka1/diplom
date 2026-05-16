@extends('layouts.app')

@section('title', 'Условия использования')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0"><i class="fas fa-file-contract me-2"></i>Условия использования</h3>
                </div>
                <div class="card-body p-4">
                    <h5 class="text-primary mb-3">1. Общие положения</h5>
                    <p>Настоящие Условия использования регулируют отношения между администрацией сервиса «QuickSell» и пользователями сайта. Используя сайт, вы соглашаетесь с данными условиями.</p>

                    <h5 class="text-primary mb-3 mt-4">2. Регистрация</h5>
                    <p>Для размещения объявлений необходима регистрация. Пользователь обязуется предоставить достоверную информацию и нести ответственность за её актуальность.</p>

                    <h5 class="text-primary mb-3 mt-4">3. Правила размещения объявлений</h5>
                    <ul>
                        <li>Запрещено размещение запрещённых законом товаров</li>
                        <li>Объявления должны содержать достоверную информацию</li>
                        <li>Запрещён спам и дублирование объявлений</li>
                        <li>Все объявления проходят модерацию</li>
                    </ul>

                    <h5 class="text-primary mb-3 mt-4">4. Модерация</h5>
                    <p>Администрация оставляет за собой право отклонить или удалить объявление без объяснения причин в случае нарушения правил сервиса.</p>

                    <h5 class="text-primary mb-3 mt-4">5. Ответственность</h5>
                    <p>Сервис «QuickSell» является площадкой для размещения объявлений и не несёт ответственности за сделки между пользователями.</p>

                    <h5 class="text-primary mb-3 mt-4">6. Конфиденциальность</h5>
                    <p>Мы заботимся о защите ваших персональных данных и не передаём их третьим лицам без вашего согласия.</p>

                    <div class="alert alert-success mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Продолжая использовать сайт, вы подтверждаете своё согласие с данными условиями.
                    </div>
                </div>
                <div class="card-footer text-center py-3">
                    <a href="{{ url()->previous() }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Вернуться назад
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
