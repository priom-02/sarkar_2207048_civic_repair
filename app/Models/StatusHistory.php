<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusHistory extends Model
{
    use HasFactory;

    protected $table = 'status_history';

    protected $fillable = [
        'issue_id',
        'old_status_id',
        'new_status_id',
        'changed_by',
        'remark',
    ];

    /**
     * Get the issue this status change belongs to.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the old status.
     */
    public function oldStatus(): BelongsTo
    {
        return $this->belongsTo(IssueStatus::class, 'old_status_id');
    }

    /**
     * Get the new status.
     */
    public function newStatus(): BelongsTo
    {
        return $this->belongsTo(IssueStatus::class, 'new_status_id');
    }

    /**
     * Get the user who changed the status.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
