<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role_id',
        'phone',
        'is_active',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all issues reported by this user.
     */
    public function reportedIssues(): HasMany
    {
        return $this->hasMany(Issue::class, 'reported_by');
    }

    /**
     * Get all media uploaded by this user.
     */
    public function uploadedMedia(): HasMany
    {
        return $this->hasMany(IssueMedia::class, 'uploaded_by');
    }

    /**
     * Get all votes by this user.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(IssueVote::class);
    }

    /**
     * Get all work assignments for this user.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(IssueAssignment::class, 'worker_id');
    }

    /**
     * Get all assignments made by this user.
     */
    public function assignmentsMade(): HasMany
    {
        return $this->hasMany(IssueAssignment::class, 'assigned_by');
    }

    /**
     * Get all status changes made by this user.
     */
    public function statusChanges(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'changed_by');
    }

    /**
     * Get all comments made by this user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(IssueComment::class);
    }

    /**
     * Get all notifications for this user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
