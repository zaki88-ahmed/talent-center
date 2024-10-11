<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['id', 'name', 'start', 'end', 'time', 'degree', 'type_id', 'teacher_id', 'group_id', 'is_closed', 'count'];


//    public function studentGroups(){
//
//        return $this->hasOne(StudentGroup::class, 'group_id', 'group_id');
//    }

    public function examTypes(){
        return $this->belongsTo(ExamType::class, 'type_id', 'id');
    }


    public function questions(){
        return $this->hasmany(Question::class, 'question_id', 'id');
    }

    public function groups(){
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function teachers(){
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_exams', 'exam_id', 'student_id')
            ->withPivot('total_degree')
            ->withTimestamps();
    }


    public  function examImage(){
        return $this->hasOne(ExamImage::class, 'exam_id', 'id');
    }


}
