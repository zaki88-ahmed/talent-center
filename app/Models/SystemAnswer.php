<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemAnswer extends Model
{
    use HasFactory;


    protected $fillable = ['question_id', 'answer'];
    protected $hidden = ['created_at', 'updated_at'];


    public function question() {

        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
