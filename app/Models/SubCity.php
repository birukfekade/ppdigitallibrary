<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCity extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = ['SubCityName', 'CityID'];

    public function city()
    {
        return $this->belongsTo(City::class, 'CityID');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'SubCityID');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'SubCityID');
    }
}