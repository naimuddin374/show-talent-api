<?php

namespace App\Http\Utils;

use Storage;
use File;

trait ImageHandleTraits{

    public function uploadImage($image, $path)
    {
        $imageNewName = time() . "." . $this->checkValidImage($image);
        Storage::disk('public')->putFileAs("images/$path/", $image, $imageNewName);
        return $imageNewName;
    }

    public function checkValidImage($image)
    {
        $extention = $image->getClientOriginalExtension();
        if ($extention === 'jpeg' || $extention === 'jpg' || $extention === 'png' || $extention === 'gif' || $extention === 'svg') {
            return $extention;
        } else {
            return 'Invalid image format. Please try again';
        }
    }

    public function deleteImage($image, $path)
    {
        $oldImagePath = public_path("/images/$path/$image");
        if (File::exists($oldImagePath)) {
            return File::delete($oldImagePath);
        } else {
            return 'no image';
        }
    }
}