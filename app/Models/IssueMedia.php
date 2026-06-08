<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueMedia extends Model
{
    use HasFactory;

    protected $table = 'issue_media';

    protected $fillable = [
        'issue_id',
        'file_url',
        'media_type',
        'uploaded_by',
    ];

    /**
     * Get the issue this media belongs to.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the user who uploaded this media.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
