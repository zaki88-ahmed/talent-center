<?php
namespace App\Http\Interfaces;


interface QuestionInterface {



    public function allQuestions($request);

    public function addQuestion($request);

    public function updateQuestion($request);

    public function deleteQuestion($request);
    public function questionImages($request);


}
