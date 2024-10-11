<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamImage extends Model
{
    use HasFactory;

    protected $table = 'question_images';

    protected $fillable = ['image', 'exam_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public  function exam(){
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }
}
