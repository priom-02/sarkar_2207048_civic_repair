<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'user_id',
    ];

    /**
     * Get the issue this vote belongs to.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the user who voted.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
