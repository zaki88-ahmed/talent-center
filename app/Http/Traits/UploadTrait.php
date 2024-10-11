<?php


namespace App\Http\Traits;

use Illuminate\Support\Facades\File;

trait UploadTrait{


    public function upload($folderName, $image): string
    {

        $imageName = $folderName . '_' . time() . $image -> getClientOriginalExtension();

        $image -> move(public_path('images/' . $folderName), $imageName);

        return $imageName;

    }



    public function deleteFile($imagePath)
    {
        if(File::exists($imagePath)){
            File::delete($imagePath);
        }
    }

}
