<?php
namespace App\Http\Interfaces;


interface TeachersInterface {


    public function addTeacher($request);

    public function allTeachers();

    public function updateTeacher($request);

    public function deleteTeacher($request);

    public function specificTeacher($request);


    public function addFile($request);


    public function allFiles($request);
    public function specificFile($request);
    public function deleteFile($request);

}
