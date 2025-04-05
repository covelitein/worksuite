<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $table = 'tasks_assignment';

    protected $fillable = [
        'date',
        'task',
        'assign_to',
        'product',
        'priority',
        'status',
        'eta'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Priority constants for easy reference
    private const PRIORITY_HIGH = 'High';
    private const PRIORITY_MEDIUM = 'Medium';
    private const PRIORITY_LOW = 'Low';

    // Status constants
    private const STATUS_NOT_STARTED = 'Not Yet Started';
    private const STATUS_IN_PROGRESS = 'In Progress';
    private const STATUS_COMPLETED = 'Completed';

    // Product types
    private const PRODUCT_WEBSITE = 'WEBSITE';
    private const PRODUCT_CRM = 'CRM';

    public function isHighPriority()
    {
        return $this->priority === self::PRIORITY_HIGH;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
