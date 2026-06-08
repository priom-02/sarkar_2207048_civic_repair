<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'user_id',
        'body',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    /**
     * Get the issue this comment belongs to.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the user who posted this comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
