<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\EndUserInterface;
use App\Http\Traits\ApiDesignTrait;
//use App\Models\role;
use App\Models\Group;
use App\Models\Role;
use App\Models\StudentGroup;
use App\Models\User;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EndUserRepository implements EndUserInterface {

    use ApiDesignTrait;


    private $userModel;
    private $groupModel;
    private $studentGroupModel;



    public function __construct(User $user, Group $group, StudentGroup $studentGroup) {

        $this->userModel = $user;
        $this->groupModel = $group;
        $this->studentGroupModel = $studentGroup;
    }


    public function userGroups()
    {
        // TODO: Implement userGroups() method.


//        dd('zz');

//        dd(auth()->user());
        $userId = auth()->user()->id;
        $userRole = auth()->user()->roleName->name;
//        dd($userId);
//        dd($userRole);


        if($userRole == 'Teacher'){

            $data = $this->groupModel->where('teacher_id', $userId)
//                ->withCount('groupStudents')
                ->get();
//            dd($data);

        }elseif($userRole == 'Student'){

//            $data = $this->studentGroupModel->where('student_id', $userId)->get();

            $data = $this->groupModel->whereHas('groupStudents', function ($query) use($userId){
               return $query->where([['student_id', $userId], ['count', '>=', 1]]);
            })
                ->withCount('groupStudents')
                ->get();

        }

        return $this->ApiResponse(200, 'Done', null, $data);

    }
}
