<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionImage extends Model
{
    use HasFactory;

    protected $table = 'question_images';

    protected $fillable = ['image', 'question_id'];
    protected $hidden = ['created_at', 'updated_at', 'id'];

    public  function question(){
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
