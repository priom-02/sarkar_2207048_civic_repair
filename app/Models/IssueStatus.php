<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_name',
    ];

    /**
     * Get all issues with this status.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'status_id');
    }

    /**
     * Get all status history records for this status.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'new_status_id');
    }

    /**
     * Get all old status history records for this status.
     */
    public function oldStatusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'old_status_id');
    }
}
