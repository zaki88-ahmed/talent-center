<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['student_exam_id', 'question_id', 'degree', 'answer'];

    protected $hidden = ['created_at', 'updated_at'];


    public function questionData(){

        return $this->belongsTo(Question::class, 'question_id', 'id')->with(['questionImage', 'answer']);
    }


//    public function questionAnswer(){
//        return $this->hasOne(SystemAnswer::class, 'question_id', 'question_id');
//    }
}
