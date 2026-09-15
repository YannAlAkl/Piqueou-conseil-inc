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

    /**
     * Les attributs qui sont autorisés pour l'assignation en masse.
     * Le champ 'name' reste ici pour valider l'injection depuis le contrôleur.
     */
    protected $fillable = [
        'name',
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

    /**
     * Les attributs qui doivent être masqués pour les tableaux.
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Les attributs qui doivent être convertis (castés).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'activated_at' => 'datetime',
            'wants_newsletter' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Accesseur : Récupère et combine le prénom et le nom de famille sous l'attribut 'name'.
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Mutateur : Intercepte la valeur 'name', la découpe, remplit les vraies colonnes,
     * et retire la clé virtuelle pour empêcher l'erreur de colonne SQL manquante.
     */
    public function setNameAttribute(string $value): void
    {
        // On sépare la chaîne de caractères au premier espace trouvé
        $parts = explode(' ', trim($value), 2);

        // On assigne les données aux vraies colonnes de la base de données
        $this->attributes['first_name'] = $parts[0] ?? '';
        $this->attributes['last_name']  = $parts[1] ?? '';

        // Supprime l'attribut virtuel 'name' pour éviter qu'il soit envoyé dans la requête SQL d'UPDATE
        unset($this->attributes['name']);
    }

    /**
     * Relations de rôles.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Relations avec les questionnaires soumis.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(UserQuestionnaire::class, 'user_id');
    }

    /**
     * Relations avec les questionnaires assignés.
     */
    public function assignedSubmissions(): HasMany
    {
        return $this->hasMany(UserQuestionnaire::class, 'analyst_id');
    }

    /**
     * Relations avec les réponses de questionnaires.
     */
    public function questionnaireAnswers(): HasMany
    {
        return $this->hasMany(UserQuestionnaireAnswer::class);
    }

    /**
     * Récupère le premier rôle de l'utilisateur.
     */
    public function role(): ?Role
    {
        return $this->roles()->first();
    }

    /**
     * Assigne un rôle à l'utilisateur.
     */
    public function assignRole(string $roleName): self
    {
        $role = Role::firstOrCreate(['name' => $roleName]);

        $this->roles()->syncWithoutDetaching([$role->id]);

        return $this;
    }

    /**
         * Vérifie si l'utilisateur possède un rôle spécifique.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
}
