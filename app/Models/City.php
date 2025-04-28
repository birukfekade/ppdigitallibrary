<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = ['CityName'];

    public function users()
    {
        return $this->hasMany(User::class, 'CityID');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'city_id', 'id');
    }

    public function subCities()
    {
        return $this->hasMany(SubCity::class, 'CityID', 'id');
    }
}