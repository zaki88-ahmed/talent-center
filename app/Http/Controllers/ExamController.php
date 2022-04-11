<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\ExamInterface;
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



class ExamController extends Controller
{


    private $examInterface;

    public function __construct(ExamInterface $examInterface)
    {
        $this->examInterface = $examInterface;
    }

    public function examTypes(){

        return $this->examInterface->examTypes();
    }

    public function allExams(){
        //dd($request);
//        dd('cc');
        return $this->examInterface->allExams();
    }

    public function addExam(Request $request){

        return $this->examInterface->addExam($request);
    }


    public function updateExam(Request $request)
    {
        return $this->examInterface->updateExam($request);
    }

    public function deleteExam(Request $request)
    {
        return $this->examInterface->deleteExam($request);
    }

    public function updateExamStatus(Request $request)
    {
        return $this->examInterface->updateExamStatus($request);
    }



    /*Start Exam Operation*/


    public function examStudents(Request $request)
    {
        return $this->examInterface->examStudents($request);
    }



    public function examStudentDetails(Request $request)
    {
        return $this->examInterface->examStudentDetails($request);
    }

//    public function addStudentExam(Request $request)
//    {
//        return $this->examInterface->addStudentExam($request);
//    }


    public function newExams()
    {
        return $this->examInterface->newExams();
    }


    public function oldExams()
    {
        return $this->examInterface->oldExams();
    }



    public function newStudentExam(Request $request)
    {
        return $this->examInterface->newStudentExam($request);
    }



    public function storeStudentExam(Request $request)
    {
        return $this->examInterface->storeStudentExam($request);
    }




}
