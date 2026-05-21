<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Task;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Модуль 1: Основы HTML
        $htmlModule = Module::create([
            'title' => 'Основы HTML',
            'description' => 'Изучите структуру веб-страниц, основные теги и атрибуты HTML.',
            'order_position' => 1
        ]);
        
        Lesson::create([
            'module_id' => $htmlModule->id,
            'title' => 'Введение в HTML',
            'content' => '<p>HTML (HyperText Markup Language) — это язык разметки, который используется для создания структуры веб-страниц.</p>
<h3>Что вы узнаете:</h3>
<ul>
<li>Что такое HTML и как работают теги</li>
<li>Структуру HTML-документа</li>
<li>Основные теги для текста и изображений</li>
</ul>
<pre><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Моя первая страница&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;Привет, мир!&lt;/h1&gt;
    &lt;p&gt;Это мой первый абзац.&lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>',
            'order_position' => 1,
            'has_task' => false
        ]);
        
        Lesson::create([
            'module_id' => $htmlModule->id,
            'title' => 'Заголовки и параграфы',
            'content' => '<p>В HTML есть теги для заголовков от h1 до h6 и тег p для параграфов.</p>
<h3>Пример:</h3>
<pre><code>&lt;h1&gt;Самый важный заголовок&lt;/h1&gt;
&lt;h2&gt;Подзаголовок&lt;/h2&gt;
&lt;p&gt;Это обычный текст параграфа.&lt;/p&gt;</code></pre>',
            'order_position' => 2,
            'has_task' => true
        ]);
        
        Task::create([
            'lesson_id' => 2,
            'description' => 'Создайте заголовок первого уровня с текстом "Моё резюме" и параграф с текстом "Привет, я учусь вёрстке!"',
            'initial_html' => '<!-- Напишите ваш код здесь -->',
            'initial_css' => '',
            'validation_rules' => json_encode([
                'required_tags' => ['h1', 'p'],
                'required_content' => [
                    'h1' => 'Моё резюме',
                    'p' => 'Привет, я учусь вёрстке!'
                ]
            ])
        ]);
        
        // Модуль 2: Основы CSS
        $cssModule = Module::create([
            'title' => 'Основы CSS',
            'description' => 'Оформляйте веб-страницы с помощью каскадных таблиц стилей.',
            'order_position' => 2
        ]);
        
        Lesson::create([
            'module_id' => $cssModule->id,
            'title' => 'Введение в CSS',
            'content' => '<p>CSS (Cascading Style Sheets) — это язык стилей, который отвечает за внешний вид веб-страницы.</p>
<h3>Синтаксис CSS:</h3>
<pre><code>селектор {
    свойство: значение;
}</code></pre>',
            'order_position' => 1,
            'has_task' => false
        ]);
        
        Lesson::create([
            'module_id' => $cssModule->id,
            'title' => 'Цвета и фон',
            'content' => '<p>В CSS можно задавать цвет текста и фона с помощью свойств color и background-color.</p>
<h3>Пример:</h3>
<pre><code>h1 {
    color: blue;
    background-color: lightgray;
}</code></pre>',
            'order_position' => 2,
            'has_task' => true
        ]);
        
        Task::create([
            'lesson_id' => 4,
            'description' => 'Сделайте фон страницы синим, а текст заголовка h1 белым.',
            'initial_html' => '<h1>Привет, мир!</h1>\n<p>Текст на странице</p>',
            'initial_css' => '/* Напишите CSS здесь */',
            'validation_rules' => json_encode([
                'required_css' => [
                    'body' => ['background-color']
                ],
                'css_values' => [
                    'body' => [
                        'background-color' => ['blue', '#0000ff', 'rgb(0,0,255)']
                    ],
                    'h1' => [
                        'color' => ['white', '#ffffff', 'rgb(255,255,255)']
                    ]
                ]
            ])
        ]);
    }
}