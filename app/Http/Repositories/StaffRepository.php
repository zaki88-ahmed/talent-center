<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Traits\ApiDesignTrait;
//use App\Models\role;
use App\Models\Role;
use App\Models\User;

use App\Http\Interfaces\StaffInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StaffRepository implements StaffInterface{

    use ApiDesignTrait;



    private $userModel;
    private $roleModel;



    public function __construct(User $user, Role $role) {

        $this->userModel = $user;
        $this->roleModel = $role;
    }




    public function addStaff($request)
    {

        $is_staff = 1;
        $roleStaffId = [];

        $AllroleStaff = $this->roleModel->where('is_staff', $is_staff)->get();
        foreach ($AllroleStaff as $roleStaff){
            $roleStaffId[] = $roleStaff->id;
        }

       $validation = Validator::make($request->all(),[
           'name' => 'required|min:3',
           'phone' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:8',
           'role_id' => 'required|exists:roles,id',
       ]);

       if($validation->fails())
       {
           return $this->ApiResponse(422,'Validation Error', $validation->errors());
       }

       if(!in_array($request->role_id, $roleStaffId)){
           return $this->ApiResponse(422,'Validation Error', 'Not Staff Role');
       }else{
           $staff = $this->userModel::create([
               'name' => $request->name,
               'phone' => $request->phone,
               'email' => $request->email,
               'password' => Hash::make($request->password),
               'role_id' => $request->role_id,
               'status' => 0,
           ]);
       }

       return $this->ApiResponse(200, 'Staff Was Created', null, $staff);

    }



    public function allStaff(){

//        $roles = ['Admin', 'Secretary', 'Support'];
//       $staff = $this->roleModel::whereIn('name', $roles)->get();
//       dd($staff);

//        $staff = $this->roleModel::where('is_staff', 1)->with('roleUsers')->get();


        $is_staff = 1;

        $staff = $this->userModel::whereHas('roleName', function ($query) use ($is_staff){
            return $query->where('is_staff', $is_staff);
        })->with('roleName')->get();

//        $staff = $this->userModel::get();
        return $this->ApiResponse(200, 'Done', null, $staff);

    }



    public function deleteStaff($request){

        $validation = Validator::make($request->all(), [
            'staff_id' => 'required|exists:users,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $staff = $this->userModel::whereHas('roleName', function ($query){
            return $query->where('is_staff', 1);
        })->find($request->staff_id);

        //dd($staff);

        if($staff){

            $staff->delete();
            return $this->ApiResponse(200, 'Staff Was Deleted', null, $staff);

        }

        return $this->ApiResponse(422, 'This User Not Staff');

    }






    public function updateStaff($request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->staff_id,
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
            'staff_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->ApiResponse(422, 'Validation Errors', $validator->errors());
        }

        $is_staff = 1;
        $roleStaffId = [];

        $AllroleStaff = $this->roleModel->where('is_staff', $is_staff)->get();
        foreach ($AllroleStaff as $roleStaff){
            $roleStaffId[] = $roleStaff->id;
        }

        $staff = $this->userModel::whereHas('roleName', function ($query) use ($is_staff){
            return $query->where('is_staff', $is_staff);
        })->find($request->staff_id);

        if($staff){
            if(!in_array($request->role_id, $roleStaffId)){
                return $this->ApiResponse(422,'Validation Error', 'Not Staff Role');
            }else {

                $staff->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => $request->role_id,
                    'status' => 0,
                ]);

            }
            return $this->ApiResponse(200, 'Staff Was Updated', null, $staff);
        }

        return $this->ApiResponse(404, 'Not Found');



    }





    public function specificStaff($request){

        $validation = Validator::make($request->all(), [
            'staff_id' => 'required|exists:users,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(200, 'Validation Error', $validation->errors());
        }

        $staff = $this->userModel::whereHas('roleName', function ($staff_id){
            return $staff_id->where('is_staff', 1);
        })->find($request->staff_id);

        if($staff){
            return  $this->ApiResponse(200, 'Done', null, $staff);
        }

        return  $this->ApiResponse(404, 'Not Found');


    }
}
