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
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Управление курсом</h1>
        <a href="{{ route('admin.modules.create') }}" class="btn btn-primary">+ Новый модуль</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush