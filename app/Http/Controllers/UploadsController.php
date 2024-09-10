<?php

namespace App\Http\Controllers;

use App\Models\Upload\Upload;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class UploadsController extends Controller
{
    public function get_file($hash, $name){

        $upload = Upload::where('hash',$hash)->first();

        if ($hash == "original_default"){
            $file_name = $name;
            if ($name == "profile_pic.png"){
                $path = storage_path() . config('default_images.profile_original');
            }
            elseif ($name == "cover_pic.png"){
                $path = storage_path() . config('default_images.cover_photo_original');
            }
            elseif ($name == "ind-database-default.jpg"){
                $path = storage_path() . config('default_images.industry_database_original');
            }
            elseif ($name == "gin_logo.png"){
                $path = storage_path() . config('default_images.gin_news_thumbnail');
            }
            elseif ($name == "hand_shake.png"){
                $path = storage_path() . config('default_images.hand_shake');
            }
            elseif ($name == "calender.png"){
                $path = storage_path() . config('default_images.calender');
            }
        }else{
            // validate upload hash & filename
            if (!isset($upload->id) || $upload->name != $name){
                return response()->json([
                    'status' => "Failure",
                    'message' => "Unauthorized Access 1"
                ]);
            }
        }

        $file_name = !empty($file_name) ? $file_name : $upload->pre_fill . $upload->name;
        $path = !empty($path) ? $path : storage_path() . '/uploads/' . $file_name;

        if (!File::exists($path)){
            // abort 404;
//            $path = base_path() . '/public/assets/image/image_placeholder.png';
            $path = storage_path() . '/default_images/default-user-image.png';
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file,200);
            return $response->header("Content-Type", $type);
        }

        $hash = sha1($path);
        $base64Image = Cache::get("image-$hash");
        if (empty($base64Image)){
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file,200);
            $image = $response->header("Content-Type", $type);
            $base64Image = $image;
        }

        return $base64Image;
    }

    public function get_file_thumbnail($hash, $name){

        $upload = Upload::where('hash',$hash)->first();

        if ($hash == "thumbnail_default"){
            $file_name = $name;
            if ($name == "profile_pic.png"){
                $path = storage_path() . config('default_images.profile_thumbnail');
            }
            elseif ($name == "cover_pic.png"){
                $path = storage_path() . config('default_images.cover_photo_thumbnail');
            }
            elseif ($name == "ind-database-default.jpg"){
                $path = storage_path() . config('default_images.industry_database_thumbnail');
            }
            elseif ($name == "gin_logo.png"){
                $path = storage_path() . config('default_images.gin_news_thumbnail');
            }
        }else{
            // validate upload hash & filename
            if (!isset($upload->id) || $upload->name != $name){
                return response()->json([
                    'status' => "Failure",
                    'message' => "Unauthorized Access 1"
                ]);
            }
        }

        $file_name = !empty($file_name) ? $file_name : $upload->pre_fill . $upload->name;
        $path = !empty($path) ? $path : storage_path() . '/upload_thumbnails/' . $file_name;

        if (!File::exists($path)){
            // abort 404;
//            $path = base_path() . '/public/assets/image/image_placeholder.png';
            $path = storage_path() . '/default_images/default-user-image.png';
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file,200);
            return $response->header("Content-Type", $type);
        }

        $hash = sha1($path);
        $base64Image = Cache::get("image-$hash");
        if (empty($base64Image)){
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file,200);
            $image = $response->header("Content-Type", $type);
            $base64Image = $image;
        }

        return $base64Image;
    }

    public function get_video($hash,$name){

        $upload = Upload::where('hash',$hash)->first();

        // validate upload hash & filename
        if (!isset($upload->id) || $upload->name != $name){
            return response()->json([
                'status' => "Failure",
                'message' => "Unauthorized Access 1"
            ]);
        }

        $file_name = $upload->pre_fill . $upload->name;
        $path = storage_path() . '/videos/' . $file_name;

        if (!File::exists($path)){
            // abort 404;
            $path = base_path() . '/public/assets/image/image_placeholder.png';
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file,200);
            return $response->header("Content-Type", $type);
        }

        $hash = sha1($path);
        $base64Image = Cache::get("image-$hash");
        if (empty($base64Image)){
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file,200);
            $image = $response->header("Content-Type", $type);
            $base64Image = $image;
        }

        return $base64Image;
    }

    public function get_audio($hash,$name){

        $upload = Upload::where('hash',$hash)->first();

        // validate upload hash & filename
        if (!isset($upload->id) || $upload->name != $name){
            return response()->json([
                'status' => "Failure",
                'message' => "Unauthorized Access 1"
            ]);
        }

        $file_name = $upload->pre_fill . $upload->name;
        $path = storage_path() . '/audios/' . $file_name;

        if (!File::exists($path)){
            // abort 404;
            $path = base_path() . '/public/assets/image/image_placeholder.png';
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file,200);
            return $response->header("Content-Type", $type);
        }

        $hash = sha1($path);
        $base64Image = Cache::get("image-$hash");
        if (empty($base64Image)){
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file,200);
            $image = $response->header("Content-Type", $type);
            $base64Image = $image;
        }

        return $base64Image;
    }
}
