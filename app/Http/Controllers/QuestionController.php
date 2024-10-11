<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\ExamInterface;
use App\Http\Interfaces\QuestionInterface;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Traits\ApiDesignTrait;



use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use App\Http\Interfaces\StaffInterface;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

//use App\Http\Interfaces\StaffInterface;
//use App\Http\Interfaces\StaffInterface;



class QuestionController extends Controller
{


    private $questionInterface;

    public function __construct(QuestionInterface $questionInterface)
    {
        $this->questionInterface = $questionInterface;
    }



    public function allQuestions(Request $request){

        return $this->questionInterface->allQuestions($request);
    }

    public function addQuestion(Request $request){

//        dd('cc');
        return $this->questionInterface->addQuestion($request);
    }


    public function updateQuestion(Request $request)
    {
        return $this->questionInterface->updateQuestion($request);
    }

    public function deleteQuestion(Request $request)
    {
        return $this->questionInterface->deleteQuestion($request);
    }

    public function questionImages(Request $request)
    {
        return $this->questionInterface->questionImages($request);
    }






}
