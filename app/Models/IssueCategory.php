<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description',
    ];

    /**
     * Get all issues with this category.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class, 'category_id');
    }
}
