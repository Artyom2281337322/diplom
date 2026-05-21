@extends('layouts.app')

@section('title', 'Курс по вёрстке')

@section('content')
<style>
     * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Основной контент растягивается */
        main {
            flex: 1;
        }

        .footer {
            background: white;
            border-top: 1px solid #dee2e6;
            padding: 2rem;
            text-align: center;
            color: #6c757d;
            margin-top: 2rem;
        }
    .course-layout {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    /* Боковая панель с уроками выбранного модуля */
    .modules-sidebar {
        flex: 1;
        min-width: 300px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        height: fit-content;
        position: sticky;
        top: 80px;
    }
    
    /* Горизонтальное меню модулей */
    .modules-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 2rem;
    margin-top: 2rem;        /* добавляем отступ сверху */
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 0.5rem;
}
    
    .module-tab {
        padding: 0.5rem 1.25rem;
        background: transparent;
        border: none;
        border-radius: 30px;
        font-weight: 500;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .module-tab:hover {
        background: #e9ecef;
        color: #212529;
    }
    
    .module-tab.active {
        background: #007bff;
        color: white;
    }
    
    /* Список уроков внутри боковой панели */
    .lessons-list {
        display: flex;
        flex-direction: column;
    }
    
    .lesson-item {
        padding: 0.75rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: background 0.2s;
        border-left: 2px solid transparent;
    }
    
    .lesson-item:hover {
        background: #f8f9fa;
    }
    
    .lesson-item.active {
        background: #e7f1ff;
        border-left-color: #007bff;
    }
    
    .lesson-status {
        width: 20px;
        text-align: center;
    }
    
    .lesson-status.completed {
        color: #28a745;
    }
    
    .lesson-status.pending {
        color: #adb5bd;
    }
    
    .lesson-title {
        flex: 1;
        font-size: 0.9rem;
    }
    
    .lesson-badge {
        font-size: 0.7rem;
        background: #e9ecef;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        color: #6c757d;
    }
    
    /* Основной контент (урок) */
    .lesson-content-area {
        flex: 3;
        min-width: 300px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 2rem;
    }
    
    .lesson-title-large {
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
        color: #212529;
    }
    
    .lesson-meta {
        color: #6c757d;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .lesson-theory {
        line-height: 1.6;
        color: #212529;
    }
    
    .lesson-theory pre {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1rem 0;
    }
    
    .task-section {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid #e9ecef;
    }
    
    .task-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .task-description {
        background: #f0f7ff;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 3px solid #007bff;
    }
    
    .editor-container {
        margin-bottom: 1rem;
    }
    
    .editor-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .editor-tab {
        padding: 0.5rem 1rem;
        background: #e9ecef;
        border: none;
        cursor: pointer;
        border-radius: 6px 6px 0 0;
        font-size: 0.85rem;
    }
    
    .editor-tab.active {
        background: #1e1e1e;
        color: white;
    }
    
    .editor-pane {
        display: none;
        background: #1e1e1e;
        border-radius: 0 8px 8px 8px;
        overflow: hidden;
    }
    
    .editor-pane.active {
        display: block;
    }
    
    .code-editor {
        width: 100%;
        min-height: 300px;
        font-family: 'Fira Code', monospace;
        font-size: 14px;
        background: #1e1e1e;
        color: #d4d4d4;
        border: none;
        padding: 1rem;
        resize: vertical;
        outline: none;
    }
    
    .preview-pane {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        min-height: 200px;
    }
    
    .preview-frame {
        width: 100%;
        min-height: 200px;
        border: none;
        background: white;
    }
    
    .task-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .check-result {
        margin-top: 1rem;
        padding: 1rem;
        border-radius: 8px;
        display: none;
    }
    
    .check-result.success {
        display: block;
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .check-result.error {
        display: block;
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .lesson-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }
    
    @media (max-width: 768px) {
        .modules-sidebar {
            position: static;
        }
        .lesson-content-area {
            padding: 1rem;
        }
    }
</style>

<div class="container">
    <!-- Горизонтальное меню модулей -->
    <div class="modules-tabs" id="modulesTabs"></div>

    <div class="course-layout">
        <!-- Боковая панель с уроками выбранного модуля -->
        <div class="modules-sidebar" id="lessonsSidebar">
            <div style="text-align: center; padding: 2rem; color: #6c757d;">
                <i class="fas fa-spinner fa-pulse"></i> Загрузка...
            </div>
        </div>

        <!-- Основной контент (урок) -->
        <div class="lesson-content-area" id="lessonContent">
            <div style="text-align: center; padding: 3rem; color: #6c757d;">
                <i class="fas fa-hand-point-left" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>Выберите раздел и урок, чтобы начать обучение</p>
            </div>
        </div>
    </div>
</div>

<script>
// Данные модулей и уроков из контроллера
const modulesData = @json($modulesData);

let currentModuleId = null;
let currentLessonId = null;

// Рендер горизонтальных вкладок
function renderTabs() {
    const container = document.getElementById('modulesTabs');
    container.innerHTML = modulesData.map(module => `
        <button class="module-tab" data-module-id="${module.id}">
            ${escapeHtml(module.title)}
        </button>
    `).join('');

    // Подписка на клики
    document.querySelectorAll('.module-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const moduleId = parseInt(tab.dataset.moduleId);
            if (currentModuleId !== moduleId) {
                setActiveModule(moduleId);
            }
        });
    });
}

// Активация модуля (переключение вкладки + загрузка уроков)
function setActiveModule(moduleId) {
    currentModuleId = moduleId;
    // Обновить активный класс вкладок
    document.querySelectorAll('.module-tab').forEach(tab => {
        const id = parseInt(tab.dataset.moduleId);
        if (id === moduleId) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });
    // Загрузить боковую панель с уроками этого модуля
    renderLessonsSidebar(moduleId);
    // Сбросить центральную область
    document.getElementById('lessonContent').innerHTML = `
        <div style="text-align: center; padding: 3rem; color: #6c757d;">
            <i class="fas fa-hand-point-left" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <p>Выберите урок из списка, чтобы начать обучение</p>
        </div>
    `;
    currentLessonId = null;
}

// Рендер боковой панели с уроками выбранного модуля
function renderLessonsSidebar(moduleId) {
    const module = modulesData.find(m => m.id === moduleId);
    if (!module) return;

    const sidebar = document.getElementById('lessonsSidebar');
    if (!module.lessons.length) {
        sidebar.innerHTML = '<div style="padding: 1rem; text-align: center; color: #6c757d;">Нет уроков</div>';
        return;
    }

    sidebar.innerHTML = `
        <div class="lessons-list">
            ${module.lessons.map(lesson => `
                <div class="lesson-item" data-lesson-id="${lesson.id}" onclick="loadLesson(${lesson.id})">
                    <div class="lesson-status ${lesson.is_completed ? 'completed' : 'pending'}">
                        <i class="fas ${lesson.is_completed ? 'fa-check-circle' : 'fa-circle'}"></i>
                    </div>
                    <div class="lesson-title">${escapeHtml(lesson.title)}</div>
                    ${lesson.has_task ? '<span class="lesson-badge">✏️ задание</span>' : ''}
                </div>
            `).join('')}
        </div>
    `;
}

// Загрузка и отображение урока
function loadLesson(lessonId) {
    currentLessonId = lessonId;
    const contentDiv = document.getElementById('lessonContent');
    contentDiv.innerHTML = `<div style="text-align: center; padding: 3rem;"><i class="fas fa-spinner fa-pulse"></i> Загрузка урока...</div>`;

    fetch(`/lesson/${lessonId}/content`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderLesson(data.lesson);
                updateActiveLessonInSidebar(lessonId);
                
                // Если у урока нет задания - автоматически отмечаем как пройденный
                if (!data.lesson.has_task) {
                    autoCompleteLesson(lessonId);
                }
            } else {
                contentDiv.innerHTML = `<div style="text-align: center; padding: 3rem; color: #dc3545;">Ошибка загрузки урока</div>`;
            }
        })
        .catch(() => {
            contentDiv.innerHTML = `<div style="text-align: center; padding: 3rem; color: #dc3545;">Ошибка соединения</div>`;
        });
}

// Автоматическое завершение урока без задания
function autoCompleteLesson(lessonId) {
    fetch(`/api/lesson/${lessonId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Обновляем статус урока в боковой панели
            const lessonItem = document.querySelector(`.lesson-item[data-lesson-id="${lessonId}"]`);
            if (lessonItem) {
                const statusDiv = lessonItem.querySelector('.lesson-status');
                if (statusDiv) {
                    statusDiv.className = 'lesson-status completed';
                    statusDiv.innerHTML = '<i class="fas fa-check-circle"></i>';
                }
            }
            // Также обновляем данные в modulesData
            const module = modulesData.find(m => 
                m.lessons.some(l => l.id === lessonId)
            );
            if (module) {
                const lesson = module.lessons.find(l => l.id === lessonId);
                if (lesson) lesson.is_completed = true;
            }
        }
    })
    .catch(error => console.error('Ошибка при авто-завершении урока:', error));
}

// Отрисовка урока в центральной области
function renderLesson(lesson) {
    let taskHtml = '';
    if (lesson.has_task && lesson.task) {
        let initialHtml = lesson.task.initial_html || '';
        let initialCss = lesson.task.initial_css || '';
        if (lesson.task.last_result) {
            if (lesson.task.last_result.user_html) initialHtml = lesson.task.last_result.user_html;
            if (lesson.task.last_result.user_css) initialCss = lesson.task.last_result.user_css;
        }

        taskHtml = `
            <div class="task-section">
                <div class="task-title"><i class="fas fa-code"></i> Практическое задание</div>
                <div class="task-description">${lesson.task.description || 'Выполните задание, следуя инструкциям.'}</div>
                <div class="editor-container">
                    <div class="editor-tabs">
                        <button class="editor-tab active" data-editor="html">HTML</button>
                        <button class="editor-tab" data-editor="css">CSS</button>
                        <button class="editor-tab" data-editor="preview">Предпросмотр</button>
                    </div>
                    <div class="editor-pane active" data-editor-pane="html">
                        <textarea id="html-editor" class="code-editor" rows="12">${escapeHtml(initialHtml)}</textarea>
                    </div>
                    <div class="editor-pane" data-editor-pane="css">
                        <textarea id="css-editor" class="code-editor" rows="12">${escapeHtml(initialCss)}</textarea>
                    </div>
                    <div class="editor-pane" data-editor-pane="preview">
                        <div class="preview-pane"><iframe id="preview-frame" class="preview-frame"></iframe></div>
                    </div>
                </div>
                <div class="task-actions">
                    <button class="btn btn-primary" onclick="checkTask(${lesson.task.id})"><i class="fas fa-check"></i> Проверить</button>
                    <button class="btn btn-outline" onclick="resetCode(${lesson.task.id})"><i class="fas fa-undo"></i> Сбросить</button>
                </div>
                <div id="check-result" class="check-result"></div>
            </div>
        `;
    }

    // Блок навигации УДАЛЁН

    const fullHtml = `
        <h1 class="lesson-title-large">${escapeHtml(lesson.title)}</h1>
        <div class="lesson-meta"><i class="fas fa-book"></i> ${lesson.module_title}</div>
        <div class="lesson-theory">${lesson.content || '<p>Контент урока загружается...</p>'}</div>
        ${taskHtml}
    `;

    document.getElementById('lessonContent').innerHTML = fullHtml;

    if (lesson.has_task && lesson.task) {
        initEditor();
        if (lesson.task.last_result && lesson.task.last_result.is_success) {
            const resultDiv = document.getElementById('check-result');
            if (resultDiv) {
                resultDiv.innerHTML = `<i class="fas fa-check-circle"></i> Задание уже выполнено! ${lesson.task.last_result.message}`;
                resultDiv.className = 'check-result success';
            }
        }
    }
}

// Подсветка активного урока в боковой панели
function updateActiveLessonInSidebar(lessonId) {
    document.querySelectorAll('.lesson-item').forEach(item => {
        const id = parseInt(item.dataset.lessonId);
        if (id === lessonId) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
}

// Вспомогательные функции
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function initEditor() {
    const tabs = document.querySelectorAll('.editor-tab');
    const panes = document.querySelectorAll('.editor-pane');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const type = tab.dataset.editor;
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            panes.forEach(pane => {
                pane.classList.remove('active');
                if (pane.dataset.editorPane === type) pane.classList.add('active');
            });
            if (type === 'preview') updatePreview();
        });
    });
    const htmlEditor = document.getElementById('html-editor');
    const cssEditor = document.getElementById('css-editor');
    if (htmlEditor) htmlEditor.addEventListener('input', updatePreview);
    if (cssEditor) cssEditor.addEventListener('input', updatePreview);
    updatePreview();
}

function updatePreview() {
    const html = document.getElementById('html-editor')?.value || '';
    const css = document.getElementById('css-editor')?.value || '';
    const iframe = document.getElementById('preview-frame');
    if (iframe) {
        iframe.srcdoc = `<!DOCTYPE html><html><head><style>${css}</style></head><body>${html}</body></html>`;
    }
}

function checkTask(taskId) {
    const html = document.getElementById('html-editor')?.value || '';
    const css = document.getElementById('css-editor')?.value || '';
    const resultDiv = document.getElementById('check-result');
    resultDiv.innerHTML = '<div style="text-align: center;"><i class="fas fa-spinner fa-pulse"></i> Проверка...</div>';
    resultDiv.className = 'check-result';

    fetch(`/api/check/${taskId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ html, css })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}`;
            resultDiv.className = 'check-result success';
            // Обновить статус урока в боковой панели
            const activeLesson = document.querySelector('.lesson-item.active');
            if (activeLesson) {
                const statusDiv = activeLesson.querySelector('.lesson-status');
                if (statusDiv) {
                    statusDiv.className = 'lesson-status completed';
                    statusDiv.innerHTML = '<i class="fas fa-check-circle"></i>';
                }
            }
        } else {
            resultDiv.innerHTML = `<i class="fas fa-times-circle"></i> ${data.message}`;
            resultDiv.className = 'check-result error';
        }
    })
    .catch(() => {
        resultDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Ошибка при проверке.';
        resultDiv.className = 'check-result error';
    });
}

function resetCode(taskId) {
    fetch(`/api/task/${taskId}/reset`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (data.html_editor !== undefined) {
                const htmlEditor = document.getElementById('html-editor');
                if (htmlEditor) htmlEditor.value = data.html_editor;
            }
            if (data.css_editor !== undefined) {
                const cssEditor = document.getElementById('css-editor');
                if (cssEditor) cssEditor.value = data.css_editor;
            }
            updatePreview();
            const resultDiv = document.getElementById('check-result');
            if (resultDiv) {
                resultDiv.className = 'check-result';
                resultDiv.innerHTML = '';
            }
        }
    });
}

// Инициализация страницы
renderTabs();
if (modulesData.length) {
    setActiveModule(modulesData[0].id);
}
</script>
@endsection