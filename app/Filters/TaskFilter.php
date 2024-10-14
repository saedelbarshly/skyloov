<?php

namespace App\Filters;

class TaskFilter extends Filters
{
    protected $var_filters = ['title','status','due_date'];

    public function title($title)
    {
        return $this->builder->whereFullText('title',$title); 

    }
    public function status($status)
    {
        return $this->builder->where('status', $status); 
    }
    public function due_date($due_date)
    {
        return $this->builder->whereDate('due_date', $due_date); 
    }
}
