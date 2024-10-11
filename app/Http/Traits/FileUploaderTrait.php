<?php


namespace App\Http\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUploaderTrait{


    public function uploadFile($file, $pathName): string{

//        dd('v');
//        dd($file->store($pathName));

        $fileName = time() . '.' . $file->extension();
//        dd($fileName);

//        $path = Storage::disk('s3')->put($pathName, $file);
//        dd($path);

        $path = $file->storePublicly($pathName, 's3');
        dd($path);
        Storage::disk('s3')->url($path);

        $file = explode('/', $path);

        $fileName = $file[array_key_last($file)];
        return $fileName;

    }







}
