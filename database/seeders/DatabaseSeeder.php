<?php

namespace Database\Seeders;

use App\Models\ExamType;
use Illuminate\Database\Seeder;


use App\Models\Role;
use App\Models\User;


use Illuminate\Support\Facades\Hash;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

//        $roles = ['Admin', 'Teacher', 'Student', 'Support', 'Secretary'];
//
//        foreach ($roles as $role){
//            Role::create([
//                'name' => $role,
//            ]);
//        }


        Role::create([
            'name' => 'Admin',
            'is_teacher' => 0,
            'is_staff' => 0,
            'is_admin' => 1,
        ]);
        Role::create([
            'name' => 'Teacher',
            'is_teacher' => 1,
            'is_staff' => 0,
            'is_admin' => 0,
        ]);
        Role::create([
            'name' => 'Student',
            'is_teacher' => 0,
            'is_staff' => 0,
            'is_admin' => 0,
        ]);
        $roles = ['Support', 'Secretary'];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
                'is_teacher' => 0,
                'is_staff' => 1,
                'is_admin' => 0,
            ]);

        }




        $examTypes = ['True&False'=>[0, 1], 'Choices'=>[1, 1], 'Essays'=>[0, 0]];
        foreach ($examTypes as $key => $value){
            examType::create([
                'name' => $key,
                'is_mark' => $value[0],
                'choice' => $value[1],
            ]);
        }






        User::create([
            'name' => 'AdminName',
            'email' => 'admin@gmail.com',
            'phone' => '010000',
            'password' => Hash::make('12345678'),
            'status' => 0,
            'role_id' => 1,
        ]);



        User::create([
            'name' => 'TeacherName',
            'email' => 'teacher@gmail.com',
            'phone' => '010000',
            'password' => Hash::make('12345678'),
            'status' => 0,
            'role_id' => 2,
        ]);



        User::create([
            'name' => 'StudentName',
            'email' => 'student@gmail.com',
            'phone' => '010000',
            'password' => Hash::make('12345678'),
            'status' => 0,
            'role_id' => 3,
        ]);



        User::create([
            'name' => 'SupportName',
            'email' => 'support@gmail.com',
            'phone' => '010000',
            'password' => Hash::make('12345678'),
            'status' => 0,
            'role_id' => 4,
        ]);



        User::create([
            'name' => 'SecretaryName',
            'email' => 'secretary@gmail.com',
            'phone' => '010000',
            'password' => Hash::make('12345678'),
            'status' => 0,
            'role_id' => 5,
        ]);
    }
}
