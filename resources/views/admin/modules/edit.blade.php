@extends('layouts.app')

@section('title', 'Редактирование модуля')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Редактирование модуля</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.modules.update', $module) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $module->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $module->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Порядок (order_position)</label>
                            <input type="number" name="order_position" class="form-control" value="{{ old('order_position', $module->order_position) }}" required>
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