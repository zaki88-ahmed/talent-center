<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'is_staff'];


    public  function roleUsers(){
        return $this->hasMany(User::class, "role_id", "id");
    }

    protected $hidden = ['created_at', 'updated_at'];
}
