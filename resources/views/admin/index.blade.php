@extends('layouts.app')

@section('title', 'Админ-панель')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .footer {
            margin-top: auto;
        }
        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: #007bff;
            font-weight: 600;
        }
        .progress-bar-container {
            width: 120px;
            height: 8px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: #28a745;
            border-radius: 10px;
        }
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .role-admin {
            background: #dc3545;
            color: white;
        }
        .role-user {
            background: #28a745;
            color: white;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .users-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
        .users-table th {
            background: #f8f9fa;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6;
        }
        .users-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e9ecef;
        }
        .users-table tr:hover {
            background: #f8f9fa;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Админ-панель</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Вкладки -->
    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="course-tab" data-bs-toggle="tab" data-bs-target="#course" type="button" role="tab">
                <i class="fas fa-book"></i> Управление курсом
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                <i class="fas fa-users"></i> Пользователи
            </button>
        </li>
    </ul>

    <!-- Содержимое вкладок -->
    <div class="tab-content" id="adminTabsContent">
        
        <!-- Вкладка: Управление курсом -->
        <div class="tab-pane fade show active" id="course" role="tabpanel">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('admin.modules.create') }}" class="btn btn-primary">+ Новый модуль</a>
            </div>

            @foreach($modules as $module)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>{{ $module->title }}</strong>
                        <div>
                            <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-sm btn-outline-primary">Редактировать модуль</a>
                            <form action="{{ route('admin.modules.destroy', $module) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить модуль?')">Удалить модуль</button>
                            </form>
                            <a href="{{ route('admin.lessons.create', $module) }}" class="btn btn-sm btn-success">+ Урок</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($module->lessons->count())
                            <ul class="list-group">
                            @foreach($module->lessons as $lesson)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        {{ $lesson->title }}
                                        @if($lesson->has_task)
                                            <span class="badge bg-info">задание</span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.lessons.edit', $lesson) }}" class="btn btn-sm btn-outline-secondary">Ред. урок</a>
                                        <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить урок?')">Удалить урок</button>
                                        </form>
                                        @if($lesson->has_task)
                                            <a href="{{ route('admin.tasks.edit', $lesson) }}" class="btn btn-sm btn-outline-info">Редактировать задание</a>
                                        @else
                                            <a href="{{ route('admin.tasks.create', $lesson) }}" class="btn btn-sm btn-outline-success">Создать задание</a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Нет уроков</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Вкладка: Пользователи -->
        <div class="tab-pane fade" id="users" role="tabpanel">
            <!-- Статистика -->
            <div class="stats-card">
                <div>
                    <div class="stats-number">{{ $usersData->count() }}</div>
                    <div class="stats-label">Всего пользователей</div>
                </div>
                <div>
                    <div class="stats-number">{{ $totalLessons }}</div>
                    <div class="stats-label">Всего уроков</div>
                </div>
                <div>
                    <i class="fas fa-users" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>

            <!-- Таблица пользователей -->
            <div class="table-responsive">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Дата регистрации</th>
                            <th>Прогресс</th>
                            <th>Уроки</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usersData as $user)
                            <tr>
                                <td>{{ $user['id'] }}</td>
                                <td><strong>{{ $user['name'] }}</strong></td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    <span class="role-badge {{ $user['role'] === 'admin' ? 'role-admin' : 'role-user' }}">
                                        {{ $user['role'] === 'admin' ? 'Админ' : 'Пользователь' }}
                                    </span>
                                </td>
                                <td>{{ $user['registered_at']->format('d.m.Y') }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="progress-bar-container">
                                            <div class="progress-bar-fill"></div>
                                        </div>
                                        <span style="font-size: 0.85rem; min-width: 45px;">{{ $user['progress_percent'] }}%</span>
                                    </div>
                                </td>
                                <td>{{ $user['completed_lessons'] }} / {{ $user['total_lessons'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-user-slash" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                                    <p>Нет зарегистрированных пользователей</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush