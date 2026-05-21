<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'lesson_id',
        'description',
        'initial_html',
        'initial_css',
        'validation_rules'
    ];
    
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    
    public function results()
    {
        return $this->hasMany(TaskResult::class);
    }
}