<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

// Страница урока
Route::get('/lesson/{lesson}', [LessonController::class, 'show'])->name('lesson.show');

// API для проверки задания
Route::post('/api/check/{task}', [LessonController::class, 'check'])->name('api.check');

// Профиль пользователя
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProgressController::class, 'index'])->name('profile');
    Route::get('/course', [CourseController::class, 'index'])->name('course');
    Route::get('/lesson/{lesson}/content', [CourseController::class, 'getLessonContent'])->name('lesson.content');
    Route::post('/api/check/{task}', [CourseController::class, 'checkTask'])->name('api.check');
    Route::get('/api/task/{task}/reset', [CourseController::class, 'resetTask'])->name('api.task.reset');
    // Отметить урок как пройденный (без проверки задания)
Route::post('/api/lesson/{lesson}/complete', [CourseController::class, 'completeLesson'])->name('api.lesson.complete');
});

// Административная панель
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('modules', AdminController::class)->except(['show']);
});

// Получение контента урока (AJAX)
Route::get('/lesson/{lesson}/content', [CourseController::class, 'getLessonContent'])->name('lesson.content');

// Проверка задания
Route::post('/api/check/{task}', [CourseController::class, 'checkTask'])->name('api.check');

// Сброс кода задания
Route::get('/api/task/{task}/reset', [CourseController::class, 'resetTask'])->name('api.task.reset');


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // Модули
    Route::get('/modules/create', [AdminController::class, 'createModule'])->name('modules.create');
    Route::post('/modules', [AdminController::class, 'storeModule'])->name('modules.store');
    Route::get('/modules/{module}/edit', [AdminController::class, 'editModule'])->name('modules.edit');
    Route::put('/modules/{module}', [AdminController::class, 'updateModule'])->name('modules.update');
    Route::delete('/modules/{module}', [AdminController::class, 'destroyModule'])->name('modules.destroy');

    // Уроки
    Route::get('/modules/{module}/lessons/create', [AdminController::class, 'createLesson'])->name('lessons.create');
    Route::post('/modules/{module}/lessons', [AdminController::class, 'storeLesson'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [AdminController::class, 'editLesson'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [AdminController::class, 'updateLesson'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [AdminController::class, 'destroyLesson'])->name('lessons.destroy');

    // Задания
    Route::get('/lessons/{lesson}/task/edit', [AdminController::class, 'editTask'])->name('tasks.edit');
    Route::put('/lessons/{lesson}/task', [AdminController::class, 'updateTask'])->name('tasks.update');
    
    Route::get('/lessons/{lesson}/task/create', [AdminController::class, 'createTask'])->name('tasks.create');
    Route::post('/lessons/{lesson}/task', [AdminController::class, 'storeTask'])->name('tasks.store');

    // Пользователи
Route::get('/users', [AdminController::class, 'users'])->name('users');
});

require __DIR__ . '/auth.php';
