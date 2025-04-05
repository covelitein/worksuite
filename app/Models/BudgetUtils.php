<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetUtils extends Model
{
    use HasFactory;
    protected $table = 'budget_utils';

    protected $fillable = [
        'project_id',
        'project_name',
        'budget_approved_usd',
        'category',
        'planned_cost_usd',
        'actual_cost_usd',
        'variance_usd',
        'remaining_budget_usd',
        'comments'
    ];
    protected $casts = [
        'budget_approved_usd' => 'decimal:2',
        'planned_cost_usd' => 'decimal:2',
        'actual_cost_usd' => 'decimal:2',
        'variance_usd' => 'decimal:2',
        'remaining_budget_usd' => 'decimal:2'
    ];
}
