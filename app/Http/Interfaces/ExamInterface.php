<?php
namespace App\Http\Interfaces;


interface ExamInterface {


    public function examTypes();

    public function allExams();

    public function addExam($request);

    public function updateExam($request);

    public function deleteExam($request);

    public function updateExamStatus($request);


    /*Start Exam Operation*/

    public function examStudents($request);

    public function examStudentDetails($request);

    public function newExams();
    public function oldExams();

    public function newStudentExam($request);
    public function storeStudentExam($request);


}
