<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLevel extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = ['LevelName'];

    public function users()
    {
        return $this->hasMany(User::class, 'AccessLevelID');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'access_level_id');
    }
}