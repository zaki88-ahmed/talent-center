<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\StudentInterface;
use App\Http\Interfaces\TeachersInterface;
use App\Http\Traits\ApiDesignTrait;
//use App\Models\role;
use App\Models\Role;
use App\Models\StudentGroup;
use App\Models\User;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StudentRepository implements StudentInterface {

    use ApiDesignTrait;


    private $userModel;
    private $roleModel;
    private $studentGroupModel;



    public function __construct(User $user, Role $role, StudentGroup $studentGroup) {

        $this->userModel = $user;
        $this->roleModel = $role;
        $this->studentGroupModel = $studentGroup;
    }


    public function addStudent($request)
    {
        // TODO: Implement addStudent() method.

        $groupValidation = Validator::make($request->groups, [
            'group1.group_id' =>'min:3',
        ]);

        if($groupValidation->fails()){
            return $this->ApiResponse(200, 'Validation Error', $groupValidation->errors());
        }


        $validation = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:10',
            'password' => 'required',
            'groups.*' => 'required',
//            'groups' => ['required', new ValidGroupId()],
        ]);


        if($validation->fails()){
            return $this->ApiResponse(200, 'Validation Error', $validation->errors());
        }

        $groups = $request->groups;


        for ($i = 0; $i <= count($groups); $i++) {
            for ($j = $i+1; $j <= count($groups) -1; $j++) {

                if($groups[$i][0] == $groups[$j][0]){
                    return $this->ApiResponse(200, 'Validation Error', 'Group Is Exist');
                }

            }
        }

//        $array = [];
//
//        foreach ($request->groups as $group) {
//            $requestGroup = explode(',', $group);
//
//            if (count($requestGroup) != 3) {
//                return $this->ApiResponse(422, 'Validation Error', 'Reformat Group Data');
//            }
//
//            if (in_array($requestGroup[0], $array)) {
//
//                return $this->ApiResponse(422, 'Validation Error', 'Group is Exist');
//
//            }
//
//                $array[] = $requestGroup[0];
//
//        }



        $studentRole = $this->roleModel::where([['is_teacher', 0], ['is_staff', 0], ['is_admin', 0]])->first();

        $student = $this->userModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $studentRole->id,
        ]);



        //dd(count($groups));

            for ($i = 0; $i < count($groups); $i++) {
//            for ($j = $i+1; $j <= count($groups) -1; $j++) {


//            dd($groups[$i+2]);

                $this->studentGroupModel::create([
                    'student_id' =>$student->id,
                    'group_id' => $groups[$i][0],
                    'count' => $groups[$i][1],
                    'price' => $groups[$i][2],
                ]);
//            }
        }
        return $this->ApiResponse(200, 'Student Was Created', null, $student);


//        foreach ($request->groups as $group){
//            $requestGroup = explode(',', $group);
//
////            if(count($requestGroup) != 3){
////                return $this->ApiResponse(422, 'Validation Error', 'Reformat Group Data');
////            }
////
////
////            if($this->studentGroupModel::where([['group_id', $requestGroup[0]], ['student_id', $student->id]])->first()){
////
////                return $this->ApiResponse(422, 'Validation Error', 'This Group Exists');
////
////            }
//
//
//
//            $this->studentGroupModel::create([
//                'student_id' =>$student->id,
//                'group_id' =>$requestGroup[0],
//                'count' =>$requestGroup[1],
//                'price' =>$requestGroup[2],
//            ]);
//        }

    }


    public function allStudents()
    {
        // TODO: Implement allStudent() method.

        $students = $this->userModel::whereHas('roleName', function ($q){
            return $q->where([['is_teacher', 0], ['is_staff', 0], ['is_admin', 0]]);
        })->withCount('studentGroups')->get();


        return $this->ApiResponse(200, 'Done', null, $students);
    }


    public function updateStudent($request)
    {
        // TODO: Implement updateStudent() method.
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->student_id,
            'password' => 'required|min:8',
            'student_id' => 'required|exists:users,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Errors', $validation->errors());
        }

       if($request->has('groups')){

           $groups = $request->groups;

           for ($i = 0; $i <= count($groups); $i++) {
               for ($j = $i+1; $j <= count($groups) -1; $j++) {

                   if($groups[$i][0] == $groups[$j][0]){
                       return $this->ApiResponse(200, 'Validation Error', 'Group Is Exist');
                   }
               }
           }
       }

//        dd('x');
        $requestedGroups = [];

        $student = $this->userModel::find($request->student_id);
        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);


        if($request->has('groups')){

//            foreach ($request->groups as $group){
//                $requestGroup = explode(',', $group);
//
//                $this->studentGroupModel::create([
//                    'student_id' =>$request->student_id,
//                    'group_id' =>$requestGroup[0],
//                    'count' =>$requestGroup[1],
//                    'price' =>$requestGroup[2],
//                ]);
//            }

            $groups = $request->groups;

            for ($i = 0; $i < count($groups); $i++) {

                $requestedGroups[] = $groups[$i][0];

                $StudentGroup = $this->studentGroupModel::where([['student_id', $request->student_id], ['group_id', $groups[$i][0]]])->first();

                if($StudentGroup){
                    $StudentGroup->update([
                       'count' => $groups[$i][1],
                        'price' => $groups[$i][2],
                    ]);
                }else{

//                    dd($groups[$i][0]);
                    $this->studentGroupModel->create([
                        'student_id' =>$request->student_id,
                        'group_id' => $groups[$i][0],
                        'count' => $groups[$i][1],
                        'price' => $groups[$i][2],
                    ]);
                }
            }

        }

        $studentGroup = $this->studentGroupModel::whereNotIn('group_id', $requestedGroups)->where('student_id', $request->student_id)->get();

        if($studentGroup){
            $studentGroup->each->delete();
        }

        return $this->ApiResponse(200, 'Student Was Updated', null,  $student);
    }


    public function deleteStudent($request)
    {
        // TODO: Implement deleteStudentT() method.
        $validation = Validator::make($request->all(), [

            'student_id' => 'required|exists:users,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(200, 'Validation Error', $validation->errors());
        }

        $student = $this->userModel::find($request->student_id);

        if($student){
            $student->delete();
        }

        return $this->ApiResponse(200, 'Student Was Deleted', null,  $student);


    }


    public function specificStudent($request)
    {
        // TODO: Implement specificStudent() method.

        $validation = Validator::make($request->all(), [

            'student_id' => 'required|exists:users,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(200, 'Validation Error', $validation->errors());
        }

        $student = $this->userModel::find($request->student_id);

        return $this->ApiResponse(200, null, null,  $student);


    }





    public function updateStudentRequest($request)
    {
        // TODO: Implement updateStudentRequest() method.
    }

    public function deleteStudentRequest($request)
    {
        // TODO: Implement deleteStudentRequest() method.
    }
}
