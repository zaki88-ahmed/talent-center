<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'exam_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public  function questionImage(){
        return $this->hasOne(QuestionImage::class, 'question_id', 'id');
    }



    public function answer() {

        return $this->hasOne(SystemAnswer::class, 'question_id', 'id');
    }

    public function exams(){
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function images(){
        return $this->hasmany(QuestionImage::class, 'question_id', 'id');
    }



}

