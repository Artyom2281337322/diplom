@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
<style>
    .users-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: bold;
    }
    
    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .users-table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .users-table th {
        background: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        border-bottom: 1px solid #dee2e6;
    }
    
    .users-table td {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .users-table tr:hover {
        background: #f8f9fa;
    }
    
    .progress-bar-container {
        width: 150px;
        height: 8px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar-fill {
        height: 100%;
        background: #28a745;
        border-radius: 10px;
        transition: width 0.3s;
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
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #007bff;
        text-decoration: none;
        margin-bottom: 1rem;
    }
    
    .back-link:hover {
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .users-table {
            font-size: 0.85rem;
        }
        
        .users-table th,
        .users-table td {
            padding: 0.75rem;
        }
        
        .progress-bar-container {
            width: 100px;
        }
    }
</style>

<div class="container">
    <a href="{{ route('admin.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Назад в админ-панель
    </a>
    
    <div class="users-header">
        <h1>👥 Пользователи</h1>
    </div>
    
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
                                <div class="progress-bar-fill" style="width: {{ $user['progress_percent'] }}%;"></div>
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
@endsection