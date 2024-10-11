<?php
namespace App\Http\Interfaces;


interface StudentInterface {


    public function addStudent($request);

    public function allStudents();

    public function updateStudent($request);

    public function deleteStudent($request);

    public function specificStudent($request);


    public function updateStudentRequest($request);

    public function deleteStudentRequest($request);

}
