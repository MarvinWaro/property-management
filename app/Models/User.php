<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Property;


class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'designation_id',
        'status',
        'needs_password_change', // <— Add here
        'signature_path', // Add this new field
    ];

    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    //     'password' => 'hashed',
    //     'needs_password_change' => 'boolean',
    // ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
            'needs_password_change' => 'boolean',
        ];
    }

    // In App\Models\User.php
    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(\App\Models\Designation::class);
    }

    // In app/Models/User.php
    public function properties()
    {
        return $this->hasMany(\App\Models\Property::class, 'user_id');
    }

    // In User.php model
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Check if user has admin-level privileges (admin or cao)
    public function hasAdminPrivileges()
    {
        return in_array($this->role, ['admin', 'cao']);
    }

    // Check if user has any of the specified roles
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    public function transactions()
    {
        return $this->belongsToMany(SupplyTransaction::class, 'user_transactions', 'user_id', 'transaction_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function requestedTransactions()
    {
        return $this->transactions()->wherePivot('role', 'requester');
    }

    public function receivedTransactions()
    {
        return $this->transactions()->wherePivot('role', 'receiver');
    }

}
