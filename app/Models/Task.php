<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'priority',
        'due_date',
        'description',
        'category_id',
        'user_id',
    ];

    protected $casts = [
        'due_date'=>'date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    //Scope
    public function scopeByPriority($query, $priority){
        return $query->where('priority', $priority);
    }

    public function scopeCompleted($query){
        return $query->where('status', 'completed');
    }

    public function scopePending($query){
        return $query->where('status', 'pending');
    }

    public function scopeDueToday($query){
        return $query->whereDate('due_date', now()->toDateString());
    }

    public function scopeOverdue($query){
        return $query->whereDate('due_date', '<', now()->toDateString())
        ->where('status','pending');
    }
}
