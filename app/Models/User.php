<?php

namespace App\Models;

use App\Models\Document;
use App\Models\AccessLevel;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'role',
        'status',
        'AccessLevelID',
        'DepartmentID',
        'CityID',
        'SubCityID',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user has the specified access level or higher
     *
     * @param int $accessLevelId
     * @return bool
     */
    public function hasAccessLevelOrHigher($accessLevelId)
    {
        return $this->access_level_id >= $accessLevelId;
    }

    public function accessLevel()
    {
        return $this->belongsTo(AccessLevel::class, 'AccessLevelID');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentID');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'CityID');
    }

    public function subCity()
    {
        return $this->belongsTo(SubCity::class, 'SubCityID');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function scopeFilterByDepartment($query, $departmentId)
    {
        return $query->where('DepartmentID', $departmentId);
    }

    public function scopeFilterByCity($query, $cityId)
    {
        return $query->where('CityID', $cityId);
    }

    public function scopeFilterBySubCity($query, $subCityId)
    {
        return $query->where('SubCityID', $subCityId);
    }

    public function scopeFilterByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function hasAccessLevel($level)
    {
        return $this->AccessLevelID === $level;
    }

    // public function hasAccessLevelOrHigher($requiredLevel)
    // {
    //     if ($this->isAdmin()) {
    //         return true;
    //     }

    //     if (!$this->accessLevel) {
    //         return false;
    //     }

    //     $userLevel = $this->accessLevel->level;
    //     $requiredAccessLevel = AccessLevel::find($requiredLevel);

    //     if (!$requiredAccessLevel) {
    //         return false;
    //     }

    //     return $userLevel >= $requiredAccessLevel->level;
    // }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function hasPermission($permission)
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->permissions()->where('name', $permission)->exists();
    }

    public function canEditDocument(Document $document)
    {
        if ($this->is_admin) {
            return true;
        }

        if ($document->user_id === $this->id) {
            return true;
        }

        return $this->hasPermission('edit_documents');
    }

    public function canDeleteDocument(Document $document)
    {
        if ($this->is_admin) {
            return true;
        }

        if ($document->user_id === $this->id) {
            return true;
        }

        return $this->hasPermission('delete_documents');
    }




    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
