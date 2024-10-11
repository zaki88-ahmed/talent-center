<?php


namespace App\Http\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait{


    public function uploadFile($request, $path, $model, $modelImage, $modelId){

//        dd($request);
//        dd('c');
        foreach ($request as $file) {

//            dd($file);
//            $file = $media->image;
//            dd($file);
//            $fileName = $request->getClientOriginalName();
            $fileName = $file->getClientOriginalName();
//            $request->storePubliclyAs($path . $model->id, $fileName, 's3');
            $file->storePubliclyAs($path . $model->id, $fileName, 's3');
            $modelImage->create([
                'image' => $fileName,
                $modelId => $model->id
            ]);
        }
    }


    public function deleteFile(){

//        'https://talent-center-project.s3.amazonaws.com/question_image151/Screenshot+from+2024-05-23+04-32-25.png'
//        'https://talent-center-project.s3.amazonaws.com/question_image151/Screenshot+from+2024-05-23+04-32-25.png'
        $url = 'https://talent-center-project.s3.amazonaws.com/question_image101/Screenshot+from+2023-08-21+19-37-55.png';
        $file_path = parse_url($url);
        Storage::disk('s3')->delete($fi
        le_path);
//        Storage::disk('s3')->delete($url);
//        unlink(storage_path($url));
//        $file = $request->image;
//        $fileName = $file->getClientOriginalName();
//        $file->storePubliclyAs($path . $model->id, $fileName, 's3');
//        return $fileName;
    }






}
