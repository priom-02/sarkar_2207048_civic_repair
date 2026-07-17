<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'reported_by',
        'category_id',
        'area_id',
        'status_id',
        'latitude',
        'longitude',
        'upvote_count',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'upvote_count' => 'integer',
    ];

    /**
     * Get the user who reported this issue.
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the category of this issue.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(IssueCategory::class, 'category_id');
    }

    /**
     * Get the area of this issue.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the status of this issue.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(IssueStatus::class, 'status_id');
    }

    /**
     * Get all media files for this issue.
     */
    public function media(): HasMany
    {
        return $this->hasMany(IssueMedia::class);
    }

    /**
     * Get all votes for this issue.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(IssueVote::class);
    }

    /**
     * Get all assignments for this issue.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(IssueAssignment::class);
    }

    /**
     * Get all status history records for this issue.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(StatusHistory::class);
    }

    /**
     * Get all comments for this issue.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(IssueComment::class);
    }

    /**
     * Get all notifications for this issue.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the feedback rating for this issue.
     */
    public function feedback(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(IssueFeedback::class);
    }
}
