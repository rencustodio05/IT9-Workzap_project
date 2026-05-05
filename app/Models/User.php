<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Job;
use App\Models\EmployerSubscription;
use App\Models\SubscriptionPayment;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'contact_number',
        'address',
        'date_of_birth',
        'desired_job_title',
        'skills',
        'work_experience',
        'education',
        'resume_path',
        'profile_photo_path',
        'password',
        'role',
        'is_active',
        'subscription_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
            'subscription_active' => 'boolean',
        ];
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function employerInterviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'employer_id');
    }

    public function applicantInterviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'applicant_id');
    }

    public function savedJobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'saved_jobs')->withTimestamps();
    }

    public function employerSubscriptions(): HasMany
    {
        return $this->hasMany(EmployerSubscription::class, 'employer_id');
    }

    public function subscriptionPayments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class, 'employer_id');
    }

    public function hasActiveEmployerSubscription(): bool
    {
        return $this->employerSubscriptions()
            ->where('status', EmployerSubscription::STATUS_ACTIVE)
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->exists();
    }

    public function getNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }
}
