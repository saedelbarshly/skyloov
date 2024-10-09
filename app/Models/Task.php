<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\TaskStatus;
use App\Filters\TaskFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 
        'description', 
        'status', 
        'due_date'
    ];

    protected $casts = [
        'status' => TaskStatus::class,
    ];

    protected function dueDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
            set: fn ($value) => Carbon::parse($value)->toDateString()
        );
    }

    public function scopeFilter($query, TaskFilter $filter)
    {
        return $filter->apply($query);
    }

}
