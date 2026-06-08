<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_name',
        'city',
        'latitude_center',
        'longitude_center',
    ];

    protected $casts = [
        'latitude_center' => 'float',
        'longitude_center' => 'float',
    ];

    /**
     * Get all issues in this area.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
}
