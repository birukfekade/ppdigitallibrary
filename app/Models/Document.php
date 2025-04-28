<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'file_name',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'user_id',
        'access_level_id',
        'city_id',
        'upload_date',
        'document_category_id',
        'status',
        'access_code',
        'encryption_key',
        'is_public'
    ];

    protected $casts = [
        'upload_date' => 'datetime',
        'file_size' => 'integer',
        'access_level_id' => 'integer',
        'city_id' => 'integer',
        'is_public' => 'boolean'
    ];

    public function accessLevel()
    {
        return $this->belongsTo(AccessLevel::class, 'access_level_id');
    }

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'document_department');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function subCities()
    {
        return $this->belongsToMany(SubCity::class, 'document_subcity');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function isAccessibleToUser(User $user)
    {
        // Admin can access any document
        if ($user->isAdmin()) {
            return true;
        }

        // If document is public, anyone can access
        if ($this->is_public) {
            return true;
        }

        // Document owner can always access
        if ($this->user_id === $user->id) {
            return true;
        }

        // Check if user has required access level
        if ($user->hasAccessLevelOrHigher($this->access_level_id)) {
            // Check department access
            if ($this->departments()->where('department_id', $user->DepartmentID)->exists()) {
                // Check city and subcity access
                if ($this->city_id === $user->CityID || $this->city_id === 'all') {
                    return true;
                }

                if ($this->subCities()->where('subcity_id', $user->SubCityID)->exists()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function canBeEditedByUser(User $user)
    {
        // Admin can edit any document
        if ($user->isAdmin()) {
            return true;
        }

        // Document owner can edit
        if ($this->user_id === $user->id) {
            return true;
        }

        // Users with same or higher access level can edit
        return $user->hasAccessLevelOrHigher($this->access_level_id);
    }

    public function canBeDownloadedByUser(User $user)
    {
        // Admin can download any document
        if ($user->isAdmin()) {
            return true;
        }

        // Document owner can always download
        if ($this->user_id === $user->id) {
            return true;
        }

        // Check if user has access to the document
        return $this->isAccessibleToUser($user);
    }
}
