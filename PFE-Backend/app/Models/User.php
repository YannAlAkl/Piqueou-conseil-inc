<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'company_name',
        'phone',
        'account_status',
        'activated_at',
        'wants_newsletter',
        'newsletter_category'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'activated_at' => 'datetime',
            'wants_newsletter' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(UserQuestionnaire::class, 'user_id');
    }

    public function assignedSubmissions(): HasMany
    {
        return $this->hasMany(UserQuestionnaire::class, 'analyst_id');
    }

    public function questionnaireAnswers(): HasMany
    {
        return $this->hasMany(UserQuestionnaireAnswer::class);
    }

    public function role(): ?Role
    {
        return $this->roles()->first();
    }

    public function assignRole(string $roleName): self
    {
        $role = Role::firstOrCreate(['name' => $roleName]);

        $this->roles()->syncWithoutDetaching([$role->id]);

        return $this;
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

}
