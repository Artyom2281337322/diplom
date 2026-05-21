@extends('layouts.app')

@section('title', 'Новый урок')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Новый урок в модуле "{{ $module->title }}"</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.lessons.store', $module) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Заголовок урока</label>
                            <input type="text" name="title" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Содержание (HTML)</label>
                            <textarea name="content" class="form-control" rows="10"></textarea>
                            <small class="text-muted">Вы можете вставлять HTML-теги, примеры кода и изображения.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Порядок</label>
                            <input type="number" name="order_position" class="form-control" value="0" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="has_task" value="1" class="form-check-input" id="hasTask">
                            <label class="form-check-label" for="hasTask">Есть практическое задание</label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                            <a href="{{ route('admin.index') }}" class="btn btn-secondary">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush