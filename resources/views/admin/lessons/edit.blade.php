@extends('layouts.app')

@section('title', 'Редактирование урока')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Редактирование урока: {{ $lesson->title }}</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Модуль</label>
                            <select name="module_id" class="form-select" required>
                                @foreach($modules as $module)
                                    <option value="{{ $module->id }}" @if($module->id == $lesson->module_id) selected @endif>
                                        {{ $module->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Заголовок урока</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $lesson->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Содержание (HTML)</label>
                            <textarea name="content" class="form-control" rows="10">{{ old('content', $lesson->content) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Порядок</label>
                            <input type="number" name="order_position" class="form-control" value="{{ old('order_position', $lesson->order_position) }}" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="has_task" value="1" class="form-check-input" id="hasTask" @if($lesson->has_task) checked @endif>
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