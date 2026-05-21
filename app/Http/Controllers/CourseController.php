<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Task;
use App\Models\Progress;
use App\Models\TaskResult;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // Главная страница курса
    public function index()
    {
        $modules = Module::with(['lessons.task'])->orderBy('order_position')->get();

        $totalLessons = Lesson::count();
        $completedLessons = collect();
        $progressPercent = 0;

        if (Auth::check()) {
            $completedLessons = Progress::where('user_id', Auth::id())
                ->where('is_completed', true)
                ->pluck('lesson_id');
            $progressPercent = $totalLessons > 0 ? round($completedLessons->count() / $totalLessons * 100) : 0;
        }

        // Подготавливаем данные для JavaScript
        $modulesData = $modules->map(function ($module) use ($completedLessons) {
            return [
                'id' => $module->id,
                'title' => $module->title,
                'lessons' => $module->lessons->map(function ($lesson) use ($completedLessons) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'has_task' => $lesson->has_task,
                        'is_completed' => Auth::check() && $completedLessons->contains($lesson->id),
                    ];
                })
            ];
        });

        $currentLesson = null;

        return view('course.index', compact('modules', 'totalLessons', 'completedLessons', 'progressPercent', 'currentLesson', 'modulesData'));
    }

    // Получение контента урока через AJAX
    public function getLessonContent(Lesson $lesson)
    {
        $module = $lesson->module;

        // Проверяем, пройден ли урок
        $isCompleted = false;
        if (Auth::check()) {
            $isCompleted = Progress::where('user_id', Auth::id())
                ->where('lesson_id', $lesson->id)
                ->where('is_completed', true)
                ->exists();
        }

        // Получаем соседние уроки для навигации
        $prevLesson = Lesson::where('module_id', $module->id)
            ->where('order_position', '<', $lesson->order_position)
            ->orderBy('order_position', 'desc')
            ->first();

        $nextLesson = Lesson::where('module_id', $module->id)
            ->where('order_position', '>', $lesson->order_position)
            ->orderBy('order_position', 'asc')
            ->first();

        // Если нет следующего урока в модуле, ищем первый урок следующего модуля
        if (!$nextLesson) {
            $nextModule = Module::where('order_position', '>', $module->order_position)
                ->orderBy('order_position', 'asc')
                ->first();
            if ($nextModule) {
                $nextLesson = Lesson::where('module_id', $nextModule->id)
                    ->orderBy('order_position', 'asc')
                    ->first();
            }
        }

        // Если нет предыдущего урока в модуле, ищем последний урок предыдущего модуля
        if (!$prevLesson) {
            $prevModule = Module::where('order_position', '<', $module->order_position)
                ->orderBy('order_position', 'desc')
                ->first();
            if ($prevModule) {
                $prevLesson = Lesson::where('module_id', $prevModule->id)
                    ->orderBy('order_position', 'desc')
                    ->first();
            }
        }

        // Получаем последний результат проверки для этого задания (если есть)
        $lastResult = null;
        if ($lesson->has_task && $lesson->task && Auth::check()) {
            $lastResult = TaskResult::where('user_id', Auth::id())
                ->where('task_id', $lesson->task->id)
                ->orderBy('submitted_at', 'desc')
                ->first();
        }

        $data = [
            'success' => true,
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'content' => $lesson->content,
                'has_task' => $lesson->has_task,
                'is_completed' => $isCompleted,
                'module_title' => $module->title,
                'prev_lesson' => $prevLesson ? ['id' => $prevLesson->id, 'title' => $prevLesson->title] : null,
                'next_lesson' => $nextLesson ? ['id' => $nextLesson->id, 'title' => $nextLesson->title] : null,
                'task' => $lesson->task ? [
                    'id' => $lesson->task->id,
                    'description' => $lesson->task->description,
                    'initial_html' => $lesson->task->initial_html,
                    'initial_css' => $lesson->task->initial_css,
                    'validation_rules' => json_decode($lesson->task->validation_rules ?? '{}', true),
                    'last_result' => $lastResult ? [
                        'is_success' => $lastResult->is_success,
                        'message' => $lastResult->message,
                        'user_html' => $lastResult->user_html,
                        'user_css' => $lastResult->user_css
                    ] : null
                ] : null
            ]
        ];

        return response()->json($data);
    }

    // Проверка задания (улучшенная версия)
    public function checkTask(Request $request, Task $task)
    {
        $html = $request->input('html');
        $css = $request->input('css');
        $rules = json_decode($task->validation_rules ?? '{}', true);
        $errors = [];

        // ========== 1. ПРОВЕРКА HTML ==========
        if (isset($rules['required_html_elements']) && is_array($rules['required_html_elements'])) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);

            foreach ($rules['required_html_elements'] as $required) {
                $tag = $required['tag'];

                // Ищем все элементы с таким тегом
                $elements = $xpath->query("//{$tag}");
                if ($elements->length === 0) {
                    $errors[] = "Отсутствует обязательный тег &lt;{$tag}&gt;";
                    continue;
                }

                $found = false;
                foreach ($elements as $element) {
                    $ok = true;

                    // Проверка текстового содержимого
                    if (isset($required['text']) && $required['text'] !== null) {
                        $elementText = trim($element->textContent);
                        if ($elementText !== $required['text']) {
                            $ok = false;
                        }
                    }

                    // Проверка атрибутов
                    if (isset($required['attributes']) && $required['attributes'] !== null) {
                        foreach ($required['attributes'] as $attrName => $attrValue) {
                            if (!$element->hasAttribute($attrName)) {
                                $ok = false;
                                break;
                            }
                            if ($element->getAttribute($attrName) !== $attrValue) {
                                $ok = false;
                                break;
                            }
                        }
                    }

                    if ($ok) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $msg = "Тег &lt;{$tag}&gt; не соответствует эталону";
                    if (isset($required['text']) && $required['text']) {
                        $msg .= " (ожидаемый текст: \"{$required['text']}\")";
                    }
                    if (isset($required['attributes']) && $required['attributes']) {
                        $attrs = json_encode($required['attributes']);
                        $msg .= " (ожидаемые атрибуты: {$attrs})";
                    }
                    $errors[] = $msg;
                }
            }
        }

        // ========== 2. ПРОВЕРКА CSS ==========
        if (isset($rules['required_css']) && is_array($rules['required_css'])) {
            foreach ($rules['required_css'] as $selector => $requiredProps) {
                // Поиск селектора в CSS ученика
                if (strpos($css, $selector) === false) {
                    $errors[] = "Отсутствует CSS-селектор '{$selector}'";
                    continue;
                }

                // Извлечение блока правил для этого селектора
                $pattern = '/' . preg_quote($selector, '/') . '\s*\{([^}]*)\}/is';
                preg_match($pattern, $css, $match);
                if (!isset($match[1])) {
                    $errors[] = "Не удалось разобрать CSS-правило для '{$selector}'";
                    continue;
                }

                $declarations = $match[1];
                $studentProps = [];
                foreach (explode(';', $declarations) as $decl) {
                    $decl = trim($decl);
                    if ($decl === '') continue;
                    $colonPos = strpos($decl, ':');
                    if ($colonPos === false) continue;
                    $propName = trim(substr($decl, 0, $colonPos));
                    $propValue = trim(substr($decl, $colonPos + 1));
                    $propValue = trim($propValue, "'\"");
                    $studentProps[$propName] = $propValue;
                }

                // Сравнение требуемых свойств и значений
                foreach ($requiredProps as $propName => $requiredValue) {
                    if (!isset($studentProps[$propName])) {
                        $errors[] = "В селекторе '{$selector}' отсутствует свойство '{$propName}'";
                        continue;
                    }
                    $studentNorm = $this->normalizeCssValue($studentProps[$propName]);
                    $requiredNorm = $this->normalizeCssValue($requiredValue);
                    if ($studentNorm !== $requiredNorm) {
                        $errors[] = "В селекторе '{$selector}' свойство '{$propName}' должно иметь значение '{$requiredValue}', а не '{$studentProps[$propName]}'";
                    }
                }
            }
        }

        $isSuccess = empty($errors);
        $message = $isSuccess ? '✓ Отлично! Задание выполнено верно.' : '✗ Ошибка: ' . implode('; ', $errors);

        // Сохранение результата
        if (Auth::check()) {
            \App\Models\TaskResult::create([
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'user_html' => $html,
                'user_css' => $css,
                'is_success' => $isSuccess,
                'message' => $message,
                'submitted_at' => now(),
            ]);

            if ($isSuccess) {
                \App\Models\Progress::updateOrCreate(
                    ['user_id' => Auth::id(), 'lesson_id' => $task->lesson_id],
                    ['is_completed' => true, 'completed_at' => now()]
                );
            }
        }

        return response()->json(['success' => $isSuccess, 'message' => $message]);
    }

    // Добавьте этот вспомогательный метод в тот же контроллер (после checkTask)
    private function normalizeCssValue($value)
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/\s+/', '', $value);
        return $value;
    }

    /**
 * Сброс кода задания к исходному состоянию
 */
public function resetTask(Task $task)
{
    return response()->json([
        'success' => true,
        'html_editor' => $task->initial_html,
        'css_editor' => $task->initial_css
    ]);
}

/**
 * Отметить урок как пройденный (для уроков без заданий)
 */
public function completeLesson(Lesson $lesson)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'message' => 'Не авторизован'], 401);
    }

    // Проверяем, есть ли у урока задание
    if ($lesson->has_task && $lesson->task) {
        return response()->json(['success' => false, 'message' => 'Этот урок имеет задание. Выполните задание для завершения.'], 400);
    }

    // Отмечаем урок как пройденный
    Progress::updateOrCreate(
        ['user_id' => Auth::id(), 'lesson_id' => $lesson->id],
        ['is_completed' => true, 'completed_at' => now()]
    );

    return response()->json(['success' => true, 'message' => 'Урок отмечен как пройденный']);
}
}
