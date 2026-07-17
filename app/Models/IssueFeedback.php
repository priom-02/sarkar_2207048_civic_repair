<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueFeedback extends Model
{
    use HasFactory;

    protected $table = 'issue_feedback';

    protected $fillable = [
        'issue_id',
        'rating',
        'comment',
        'submitted_by',
    ];

    /**
     * Get the issue this feedback belongs to.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the user who submitted this feedback.
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
