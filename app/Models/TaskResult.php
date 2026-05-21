<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskResult extends Model
{
    protected $table = 'task_results';
    
    protected $fillable = [
        'user_id', 'task_id', 'user_html', 'user_css', 
        'is_success', 'message', 'submitted_at'
    ];
    
    protected $casts = [
        'is_success' => 'boolean',
        'submitted_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}