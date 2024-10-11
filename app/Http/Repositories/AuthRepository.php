<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Traits\ApiDesignTrait;
use App\Models\role;
use App\Models\User;

class AuthRepository implements AuthInterface{

    use ApiDesignTrait;
    private $userModel;

    public function __construct(User $user) {

        $this->userModel = $user;
    }


    public function login(){

        $credentials = request(['email', 'password']);

        //dd(auth()->attempt($credentials));
        
        if (! $token = auth()->attempt($credentials)) {
            return $this->ApiResponse(422, 'unauthorized');
        }

        
        return $this->respondWithToken($token);
    }




    protected function respondWithToken($token)
    {

        /* $userData = User::where('id', auth()->user()->id)->whereHas('roleName', function($q){
            return $q->where('name', 'admin');
        })->first(); */

         $userData =$this->userModel::find(auth()->user()->id); 

        /* $userRole = auth()->user()->roleName->name;

        dd($userRole);

        dd($userData); */


        $data = [
            'name' =>$userData->name,
            'email' =>$userData->email,
            'phone' =>$userData->phone,
            'role_id' =>$userData->role_id,
            'role' => auth()->user()->roleName->name,
            'access_token' => $token,
        ];

        return $this->ApiResponse(200, 'done', null, $data);

        /* return response()->json([
            'access_token' => $token,
            //'token_type' => 'bearer',
             //'expires_in' => auth()->factory()->getTTL() * 60
             //'expires_in' => auth('api')->factory()->getTTL() * 60
             
        ]);  */
    }
}
