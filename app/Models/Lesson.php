<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['module_id', 'title', 'content', 'order_position', 'has_task'];
    
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    
    public function task()
    {
        return $this->hasOne(Task::class);
    }
}