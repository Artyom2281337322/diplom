@extends('layouts.app')

@section('title', 'Редактирование задания')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .font-monospace { font-family: 'Courier New', monospace; }
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Редактирование задания для урока "{{ $lesson->title }}"</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.tasks.update', $lesson) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Описание задания</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $task->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Исходный HTML (initial_html)</label>
                            <textarea name="initial_html" class="form-control font-monospace" rows="6">{{ old('initial_html', $task->initial_html) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Исходный CSS (initial_css)</label>
                            <textarea name="initial_css" class="form-control font-monospace" rows="6">{{ old('initial_css', $task->initial_css) }}</textarea>
                        </div>

                        <!-- БЛОК АВТОГЕНЕРАЦИИ ПРАВИЛ -->
                        <div class="card bg-light mb-3">
                            <div class="card-header">Автоматическая генерация правил проверки</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Эталонный HTML (ожидаемый результат)</label>
                                    <textarea id="ref_html" class="form-control font-monospace" rows="6">{{ old('ref_html', $refHtml ?? '') }}</textarea>
                                    <small class="text-muted">Введите HTML, который должен получиться у ученика. Система выделит обязательные теги.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Эталонный CSS (ожидаемые стили)</label>
                                    <textarea id="ref_css" class="form-control font-monospace" rows="6">{{ old('ref_css', $refCss ?? '') }}</textarea>
                                    <small class="text-muted">Введите CSS, который должен быть у ученика. Система выделит обязательные селекторы и свойства.</small>
                                </div>
                                <button type="button" id="generateRulesBtn" class="btn btn-secondary">Сгенерировать правила проверки</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Правила проверки (JSON) – можно отредактировать вручную</label>
                            <textarea name="validation_rules" id="validation_rules" class="form-control font-monospace" rows="8">{{ old('validation_rules', $task->validation_rules) }}</textarea>
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
    <script>
    document.getElementById('generateRulesBtn').addEventListener('click', function() {
        const html = document.getElementById('ref_html').value;
        const css = document.getElementById('ref_css').value;
        const rules = {};

        // === HTML проверка (теги + текст + атрибуты) ===
        if (html.trim() !== '') {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const requiredElements = [];

            doc.body.querySelectorAll('*').forEach(el => {
                const tagName = el.tagName.toLowerCase();
                const textContent = el.textContent.trim();
                const attributes = {};
                for (let attr of el.attributes) {
                    attributes[attr.name] = attr.value;
                }
                requiredElements.push({
                    tag: tagName,
                    text: textContent !== '' ? textContent : null,
                    attributes: Object.keys(attributes).length ? attributes : null
                });
            });

            if (requiredElements.length) {
                rules.required_html_elements = requiredElements;
            }
        }

        // === CSS проверка (селектор → свойство → значение) ===
        if (css.trim() !== '') {
            const regex = /([^{]+)\{([^}]*)\}/g;
            let match;
            const cssRules = {};
            while ((match = regex.exec(css)) !== null) {
                let selector = match[1].trim();
                let block = match[2].trim();
                const props = {};
                block.split(';').forEach(decl => {
                    decl = decl.trim();
                    if (decl === '') return;
                    const colon = decl.indexOf(':');
                    if (colon === -1) return;
                    const prop = decl.substring(0, colon).trim();
                    const val = decl.substring(colon + 1).trim();
                    if (prop && val) props[prop] = val;
                });
                if (Object.keys(props).length) cssRules[selector] = props;
            }
            if (Object.keys(cssRules).length) rules.required_css = cssRules;
        }

        document.getElementById('validation_rules').value = JSON.stringify(rules, null, 2);
    });
</script>
@endpush