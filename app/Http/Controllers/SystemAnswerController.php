<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\ExamInterface;
use App\Http\Interfaces\StudentExamInterface;
use App\Http\Interfaces\SystemAnswerInterface;
use App\Models\User;
use Illuminate\Http\Request;




class SystemAnswerController extends Controller
{


    private $systemAnswerInterface;

    public function __construct(SystemAnswerInterface $systemAnswerInterface)
    {
        $this->systemAnswerInterface = $systemAnswerInterface;
    }



    public function addAnswer(Request $request){

        return $this->systemAnswerInterface->addAnswer($request);
    }

    public function upadteAnswer(Request $request){

        return $this->systemAnswerInterface->upadteAnswer($request);
    }


    public function deleteAnswer(Request $request)
    {
        return $this->systemAnswerInterface->deleteAnswer($request);
    }

    public function getAnswer()
    {
        return $this->systemAnswerInterface->getAnswer();
    }








}
