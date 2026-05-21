@extends('layouts.app')

@section('title', 'Изучай вёрстку бесплатно')

@section('content')
    <!-- Hero секция -->
    <section class="hero">
        <div class="container">
            <h1>Изучай веб-вёрстку с нуля</h1>
            <p>Практический курс по HTML и CSS. Интерактивный редактор кода, мгновенная проверка заданий и отслеживание прогресса.</p>
            @guest
                <a href="{{ route('register') }}" class="btn" style="background: white; color: #007bff; padding: 0.75rem 2rem;">
                    Начать бесплатно <i class="fas fa-arrow-right"></i>
                </a>
            @else
                <a href="{{ route('course') }}" class="btn" style="background: white; color: #007bff; padding: 0.75rem 2rem;">
                    Продолжить обучение <i class="fas fa-arrow-right"></i>
                </a>
            @endguest
        </div>
    </section>

    <div class="container">
        <!-- Блок прогресса (для авторизованных пользователей) -->
        @auth
            @php
                $totalLessons = \App\Models\Lesson::count();
                $completedLessons = \App\Models\Progress::where('user_id', auth()->id())
                    ->where('is_completed', true)
                    ->count();
                $progressPercent = $totalLessons > 0 ? round($completedLessons / $totalLessons * 100) : 0;
            @endphp
            
            <div class="progress-card">
                <div class="progress-stats">
                    <div>
                        <div class="progress-percent">{{ $progressPercent }}%</div>
                        <div style="color: #6c757d; font-size: 0.85rem;">пройдено</div>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: {{ $progressPercent }}%;"></div>
                    </div>
                    <div style="color: #6c757d;">
                        <i class="fas fa-check-circle" style="color: #28a745;"></i>
                        {{ $completedLessons }} из {{ $totalLessons }} уроков
                    </div>
                </div>
            </div>
        @endauth

        <!-- Описание курса -->
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 class="section-title">Что вы изучите</h2>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; margin-top: 1.5rem;">
                <div><i class="fab fa-html5" style="font-size: 2rem; color: #e34f26;"></i><br>HTML5</div>
                <div><i class="fab fa-css3-alt" style="font-size: 2rem; color: #1572b6;"></i><br>CSS3</div>
                <div><i class="fas fa-th-large" style="font-size: 2rem; color: #007bff;"></i><br>Flexbox & Grid</div>
            </div>
        </div>

        <!-- Список модулей -->
        <h2 class="section-title">Программа курса</h2>
        <div class="modules-grid">
            @php
                $modules = \App\Models\Module::with('lessons')->orderBy('order_position')->get();
            @endphp
            
            @forelse($modules as $module)
                <a href="{{ route('course') }}" class="module-card">
                    <div class="module-icon">
                        @if($loop->iteration == 1)
                            <i class="fab fa-html5"></i>
                        @elseif($loop->iteration == 2)
                            <i class="fab fa-css3-alt"></i>
                        @elseif($loop->iteration == 3)
                            <i class="fas fa-th-large"></i>
                        @else
                            <i class="fas fa-code"></i>
                        @endif
                    </div>
                    <div class="module-title">{{ $module->title }}</div>
                    <div class="module-description">{{ Str::limit($module->description, 100) }}</div>
                    <div class="module-lessons">
                        <i class="fas fa-book-open"></i> {{ $module->lessons->count() }} уроков
                    </div>
                </a>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: #6c757d; padding: 3rem;">
                    Модули загружаются. Пожалуйста, зайдите позже.
                </div>
            @endforelse
        </div>

        <!-- Призыв к регистрации (для неавторизованных) -->
        @guest
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 2rem; text-align: center; color: white; margin-bottom: 2rem;">
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Готовы начать?</h3>
                <p style="margin-bottom: 1rem;">Зарегистрируйтесь и получите доступ к полному курсу бесплатно</p>
                <a href="{{ route('register') }}" class="btn" style="background: white; color: #667eea; padding: 0.75rem 2rem;">
                    Создать аккаунт <i class="fas fa-user-plus"></i>
                </a>
            </div>
        @endguest
    </div>
@endsection