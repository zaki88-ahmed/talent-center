<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Interfaces\TeachersInterface;
use App\Http\Traits\ApiDesignTrait;
//use App\Models\role;
use App\Http\Traits\FileUploaderTrait;
use App\Models\FileUpload;
use App\Models\Group;
use App\Models\GroupFile;
use App\Models\Role;
use App\Models\User;

use App\Http\Interfaces\StaffInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class TeachersRepository implements TeachersInterface {

    use ApiDesignTrait;

    use FileUploaderTrait;


    private $userModel;
    private $roleModel;
    private $fileUpload;
    private $groupFile;
    private $group;



    public function __construct(User $user, Role $role, FileUpload $fileUpload, GroupFile $groupFile, Group $group) {

        $this->userModel = $user;
        $this->roleModel = $role;
        $this->fileUpload = $fileUpload;
        $this->groupFile = $groupFile;
        $this->group = $group;
    }




    public function addTeacher($request){

//        dd('aa');
        $roleTeacherId = $this->roleModel->where('name', 'Teacher')->first()->id;
//        dd($roleTeacherId);

       $validation = Validator::make($request->all(),[
           'name' => 'required|min:3',
           'phone' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:8',
//           'role_id' => 'required|exists:roles,id',
       ]);

       if($validation->fails())
       {
           return $this->ApiResponse(422,'Validation Error', $validation->errors());
       }

       $this->userModel::create([
           'name' => $request->name,
           'phone' => $request->phone,
           'email' => $request->email,
           'password' => Hash::make($request->password),
           'role_id' => $roleTeacherId,
           'status' => 0,
       ]);

       return $this->ApiResponse(200, 'Teacher Was Created');

    }



    public function allTeachers(){

        $is_teacher = 1;

        $teachers = $this->userModel::whereHas('roleName', function ($query) use ($is_teacher){
            return $query->where('is_teacher', $is_teacher);
        })->with('roleName')->get();

        return $this->ApiResponse(200, 'Done', null, $teachers);
    }


    public function deleteTeacher($request){

        $validation = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:users,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $teacher = $this->userModel::whereHas('roleName', function ($query){
            return $query->where('is_teacher', 1);
        })->find($request->teacher_id);

//        dd($teacher->id);

        //dd($staff);

        if($teacher){

            $teacher->delete();
            return $this->ApiResponse(200, 'Teacher Was Deleted', null, $teacher);

        }

        return $this->ApiResponse(422, 'This User Not Teacher');

    }






    public function updateTeacher($request){

        $roleTeacherId = $this->roleModel->where('name', 'Teacher')->first()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->teacher_id,
            'password' => 'required|min:8',
//            'role_id' => 'required|exists:roles,id',
            'teacher_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->ApiResponse(422, 'Validation Errors', $validator->errors());
        }

        $teacher = $this->userModel::whereHas('roleName', function ($query){
            return $query->where('is_teacher', 1);
        })->find($request->teacher_id);

        if($teacher){

            $teacher->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $roleTeacherId,
                'status' => 0,
            ]);


            return $this->ApiResponse(200, 'Teacher Was Updated', null, $teacher);
        }

        return $this->ApiResponse(404, 'Teacher Not Found');



    }



    public function specificTeacher($request){

        $validation = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:users,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(200, 'Validation Error', $validation->errors());
        }

        $teacher = $this->userModel::whereHas('roleName', function ($teacher_id){
            return $teacher_id->where('is_teacher', 1);
        })->find($request->teacher_id);

        if($teacher){
            return  $this->ApiResponse(200, 'Done', null, $teacher);
        }

        return  $this->ApiResponse(404, 'Teacher Not Found');


    }

    public function addFile($request)
    {
        // TODO: Implement addFile() method.

//        dd(Auth::user());
        $validation = Validator::make($request->all(), [
            'file' => 'required',
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(200, 'Validation Error', $validation->errors());
        }

//        $path = Storage::disk('s3')->put('talent-center', $request->file);
//        dd($path);

//        $filePath = $this->uploadFile($request->file, 'talent-center');
//        $fileName = $request->file('file')->getClientOriginalName();

//        dd($fileName);

//        dd('cc');
//        $path =  $request->file('file')->storePublicity('public/images');
//        dd('dd');
//        dd($path);
//
//        $file = $this->groupFile::create([
//           'group_id' => $request->group_id,
//           'file' => 's3',
//           'name' => $path,
//           'teacher_id' => auth()->user()->id,
//        ]);

        if($request->file){
            $file = $request->file;
            $fileName = $file->getClientOriginalName();
            $file->storeAs('group_file' . $request->group_id, $fileName, 's3');

//            dd($fileName);
        }
        $pivotRow = DB::table('group_files')->insert([
            'name' => $fileName,
            'teacher_id' => Auth::user()->id,
            'group_id' => $request->group_id,
            ]);
        $groupFile = DB::table('group_files')->where([['group_id', $request->group_id], ['teacher_id', Auth::user()->id]])->orderBy('id', 'DESC')->first();
//        $groupFile = DB::table('group_files')->where('name', $fileName)->get();

        return $this->ApiResponse(200, 'Done', null, $groupFile);
    }





    public function allFiles($request)
    {
        // TODO: Implement addFile() method.

        $validation = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(200, 'Validation Error', $validation->errors());
        }


//        $files = $this->groupFile::where('group_id', $request->group_id)->get();
        $files = DB::table('group_files')->where([['group_id', $request->group_id], ['teacher_id', Auth::user()->id]])->get();
//        dd($files);
//        return $this->ApiResponse(404, 'Done', null, $files);
        if($files){
//            return $this->ApiResponse(404, 'No Files Found', null, null);
            return $this->ApiResponse(200, 'Done', null, $files);
        }

//        foreach ($files as $file){
//            $fileArray = explode('.', $file->file);
//            $fileFormat = $fileArray[array_key_last($fileArray)];
//            $file['format'] = $fileFormat;
//        }

//        return $this->ApiResponse(404, 'No Files Found', null, null);

    }

    public function specificFile($request){

    }

    public function deleteFile($request){
//        $directories = Storage::allDirectories('https://us-east-1.console.aws.amazon.com/s3/buckets/talent-center-project?region=us-east-1&bucketType=general&prefix=group_file1/&showversions=false');
//        dd($directories);
//        Storage::disk('s3')->delete('https://us-east-1.console.aws.amazon.com/s3/object/talent-center-project?region=us-east-1&bucketType=general&prefix=group_file1/Screenshot+from+2023-08-21+19-44-40.png');
    }
}
