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
        'needs_password_change',
        'signature_path',
    ];

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

    /**
     * RELATIONSHIPS
     */

    /**
     * User belongs to a department
     */
    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class);
    }

    /**
     * User belongs to a designation
     */
    public function designation()
    {
        return $this->belongsTo(\App\Models\Designation::class);
    }

    /**
     * User has many properties
     */
    public function properties()
    {
        return $this->hasMany(\App\Models\Property::class, 'user_id');
    }

    /**
     * User transaction relationships
     */
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

    /**
     * ROLE HELPER METHODS - ENHANCED TO HANDLE ARRAYS
     */

    /**
     * Check if user has a specific role or any of the given roles
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        // Handle single role (string)
        if (is_string($roles)) {
            return $this->role === $roles;
        }

        // Handle multiple roles (array)
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return false;
    }

    /**
     * Check if user has admin-level privileges (admin or cao)
     */
    public function hasAdminPrivileges()
    {
        return in_array($this->role, ['admin', 'cao']);
    }

    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    /**
     * Individual role checkers
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCao()
    {
        return $this->role === 'cao';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * STATUS/ACTIVE METHODS
     * Since your model uses 'status' instead of 'is_active'
     */

    /**
     * Check if user is active
     * Assumes 'active' status means the user is active
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get users by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope to get admin and cao users
     */
    public function scopeAdminAndCao($query)
    {
        return $query->whereIn('role', ['admin', 'cao']);
    }

    /**
     * ACCESSORS
     */

    /**
     * Get the department name
     */
    public function getDepartmentNameAttribute()
    {
        return $this->department ? $this->department->name : 'No Department';
    }

    /**
     * Get the designation name
     */
    public function getDesignationNameAttribute()
    {
        return $this->designation ? $this->designation->name : 'No Designation';
    }

    /**
     * Get the full role name
     */
    public function getRoleNameAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'cao' => 'Chief Administrative Officer',
            'staff' => 'Staff',
            default => 'Unknown Role'
        };
    }

    /**
     * Get user's full name with role for display
     */
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->role_name . ')';
    }
}
