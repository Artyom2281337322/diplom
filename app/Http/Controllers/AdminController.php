<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Lesson;
use App\Models\Task;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Главная страница админки
    public function index()
    {
        $modules = Module::with('lessons.task')->orderBy('order_position')->get();
        return view('admin.index', compact('modules'));
    }

    // ========== Модули ==========
    public function createModule()
    {
        return view('admin.modules.create');
    }

    public function storeModule(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_position' => 'required|integer'
        ]);
        Module::create($request->all());
        return redirect()->route('admin.index')->with('success', 'Модуль создан');
    }

    public function editModule(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    public function updateModule(Request $request, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_position' => 'required|integer'
        ]);
        $module->update($request->all());
        return redirect()->route('admin.index')->with('success', 'Модуль обновлён');
    }

    public function destroyModule(Module $module)
    {
        $module->delete();
        return redirect()->route('admin.index')->with('success', 'Модуль удалён');
    }

    // ========== Уроки ==========
    public function createLesson(Module $module)
    {
        return view('admin.lessons.create', compact('module'));
    }

    public function storeLesson(Request $request, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'order_position' => 'required|integer',
            'has_task' => 'boolean'
        ]);
        $module->lessons()->create($request->all());
        return redirect()->route('admin.index')->with('success', 'Урок создан');
    }

    public function editLesson(Lesson $lesson)
    {
        $modules = Module::orderBy('order_position')->get();
        return view('admin.lessons.edit', compact('lesson', 'modules'));
    }

    public function updateLesson(Request $request, Lesson $lesson)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'order_position' => 'required|integer',
            'has_task' => 'boolean'
        ]);
        $lesson->update($request->all());
        return redirect()->route('admin.index')->with('success', 'Урок обновлён');
    }

    public function destroyLesson(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('admin.index')->with('success', 'Урок удалён');
    }

    // ========== Задания (Task) ==========
    
    /**
     * Показать форму создания задания для урока
     */
    public function createTask(Lesson $lesson)
    {
        $task = new Task(['lesson_id' => $lesson->id]);
        return view('admin.tasks.create', compact('lesson', 'task'));
    }

    /**
     * Сохранить новое задание
     */
    public function storeTask(Request $request, Lesson $lesson)
    {
        $request->validate([
            'description' => 'nullable|string',
            'initial_html' => 'nullable|string',
            'initial_css' => 'nullable|string',
            'validation_rules' => 'nullable|json'
        ]);
        
        $lesson->task()->create([
            'description' => $request->description,
            'initial_html' => $request->initial_html,
            'initial_css' => $request->initial_css,
            'validation_rules' => $request->validation_rules,
        ]);
        
        return redirect()->route('admin.index')->with('success', 'Задание создано');
    }

    /**
     * Показать форму редактирования задания
     */
    public function editTask(Lesson $lesson)
    {
        $task = $lesson->task ?? new Task(['lesson_id' => $lesson->id]);
        $refHtml = '';
        $refCss = '';
        if ($task->validation_rules) {
            $rules = json_decode($task->validation_rules, true);
            if (isset($rules['required_tags']) && is_array($rules['required_tags'])) {
                $tags = array_filter($rules['required_tags'], 'is_string');
                if (!empty($tags)) {
                    $refHtml = '<' . implode('></', $tags) . '>';
                }
            }
            if (isset($rules['required_css']) && is_array($rules['required_css'])) {
                $selectors = [];
                foreach ($rules['required_css'] as $key => $value) {
                    if (is_string($key) && !is_numeric($key)) {
                        $selectors[] = $key;
                    } elseif (is_string($value)) {
                        $selectors[] = $value;
                    }
                }
                $selectors = array_unique($selectors);
                if (!empty($selectors)) {
                    $refCss = implode(' { } ', $selectors) . ' { }';
                }
            }
        }
        return view('admin.tasks.edit', compact('lesson', 'task', 'refHtml', 'refCss'));
    }

    /**
     * Обновить задание
     */
    public function updateTask(Request $request, Lesson $lesson)
    {
        $request->validate([
            'description' => 'nullable|string',
            'initial_html' => 'nullable|string',
            'initial_css' => 'nullable|string',
            'validation_rules' => 'nullable|json'
        ]);
        
        $task = $lesson->task;
        if ($task) {
            $task->update([
                'description' => $request->description,
                'initial_html' => $request->initial_html,
                'initial_css' => $request->initial_css,
                'validation_rules' => $request->validation_rules,
            ]);
        } else {
            $lesson->task()->create([
                'description' => $request->description,
                'initial_html' => $request->initial_html,
                'initial_css' => $request->initial_css,
                'validation_rules' => $request->validation_rules,
            ]);
        }
        
        return redirect()->route('admin.index')->with('success', 'Задание сохранено');
    }

    /**
     * Удалить задание
     */
    public function destroyTask(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Задание удалено');
    }
}