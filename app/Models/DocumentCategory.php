<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function accessLevels()
    {
        return $this->belongsToMany(AccessLevel::class, 'category_access_level');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}