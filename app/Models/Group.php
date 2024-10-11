<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'body', 'image', 'teacher_id', 'created_by'];



//    public function groupStudents(){
//
//        return $this->hasMany(StudentGroup::class, 'group_id', 'id');
//    }

    protected $hidden = ['created_at', 'updated_at', 'created_by'];



    public function examTypes(){

        return $this->hasOne(ExamType::class, 'type_id', 'id');
    }

    public function groupStudents()
    {
        return $this->belongsToMany(User::class, 'student_groups', 'group_id', 'student_id')
            ->withPivot('count', 'price')
            ->withTimestamps();
    }
}
