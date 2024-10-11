<?php


namespace App\Http\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait UploadImageTrait{



//    public function uploadImage(Request $request, $path){

//        if($image = $request->file('image')){
//            $image = $request->file('image')->store($path);
//        }
//        return $request->file('image')->hashName();
//    }
//
//    public function deleteImage($path, $file){
//        unlink(storage_path('app/public/' .  $path . '/' . $file));

//    }



        public function uploadImage(Request $request, $path){
            if($image = $request->file('image')){
                $image = $request->file('image')->store($path);
            }
            return $request->file('image')->hashName();
        }


        public function deleteImage($path, $file){
            unlink(storage_path('app/public/' .  $path . '/' . $file));
        }

}
