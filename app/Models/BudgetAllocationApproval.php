<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetAllocationApproval extends Model
{
    use HasFactory;

    protected $table = 'budget_allocation_approval';

    protected $fillable = [
        'project_id',
        'project_name',
        'department',
        'budget_requested',  // decimal field
        'budget_approved',   // decimal field (nullable)
        'approval_status',   // enum field
        'approval_date',     // date field (nullable)
        'comments',          // text field
        'requested_by',      // string or relation
        'approved_by'        // string or relation
    ];

    protected $casts = [
        'budget_requested' => 'decimal:2',  // Cast to decimal with 2 places
        'budget_approved' => 'decimal:2',   // Cast to decimal with 2 places
        'approval_date' => 'date',          // Cast to date
        'approval_status' => 'string'       // Explicit cast for enum
    ];

    protected $attributes = [
        'approval_status' => 'pending'      // Default value
    ];

    // Validation rules for creating/updating
    public static $rules = [
        'project_name' => 'required|string|max:100',
        'department' => 'required|string|max:100',
        'budget_requested' => 'required|numeric|min:0',
        'budget_approved' => 'nullable|numeric|min:0',
        'approval_status' => 'required|in:pending,approved,rejected',
        'approval_date' => 'nullable|date',
        'comments' => 'nullable|string'
    ];

    // Business logic methods
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    // Amount formatting
    public function getFormattedRequestedAmountAttribute(): string
    {
        return number_format($this->budget_requested, 2);
    }

    public function getFormattedApprovedAmountAttribute(): ?string
    {
        return $this->budget_approved ? number_format($this->budget_approved, 2) : null;
    }
}