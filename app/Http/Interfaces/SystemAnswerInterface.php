<?php
namespace App\Http\Interfaces;


interface SystemAnswerInterface {




    public function getAnswer();

    public function addAnswer($request);

    public function upadteAnswer($request);
    public function deleteAnswer($request);




}
