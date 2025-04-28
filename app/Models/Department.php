<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'DepartmentName',
        'description'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'DepartmentID');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'department_id');
    }
}
