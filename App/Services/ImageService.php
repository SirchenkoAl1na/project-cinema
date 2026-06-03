<?php

namespace App\Services;

use App\Data;
use App\DB;
use App\Models\Place;
use App\Models\Seanse;
use App\Models\Ticket;
use App\Models\User;

class ImageService
{
    public static function saveImage($field_name="image",$path="resources/img/")
    {
        $image_title = null;
        if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] == 0) {
            $target_dir = $path;
            $imageFileType = strtolower(pathinfo($_FILES[$field_name]["name"], PATHINFO_EXTENSION));
            $filename = uniqid() . "." . $imageFileType;
            $target_file = $target_dir . $filename;
    
            // Перевірка типу
            $check = getimagesize($_FILES[$field_name]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES[$field_name]["tmp_name"], $target_file)) {
                    $image_title = $filename;
                }
                return $image_title;
            }
            else return false;
        }
        return null; // Якщо файл не завантажено або сталася помилка
        
    }
}
