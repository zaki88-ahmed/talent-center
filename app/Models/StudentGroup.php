<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'group_id', 'count', 'price'];

//    public function groups(){
//
//        return $this->belongsTo(Group::class, 'group_id', 'id');
//    }
}
