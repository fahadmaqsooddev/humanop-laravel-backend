<?php

namespace App\Models\Upload;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Upload extends Model
{
    use HasFactory;


    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // public static function escapeFileUrl($url)
    // {
    //     $parts = parse_url($url);
    //     $path_parts = array_map('rawurldecode', explode('/', $parts['path']));

    //     return
    //         $parts['scheme'] . '://' .
    //         $parts['host'] .
    //         implode('/', array_map('rawurlencode', $path_parts));
    // }

    public static function uploadFile($file,$thumbnail_width,$thumbnail_height, $type = null, $ext = null, $resize = 0){

        $extension = 'png';

        $original_name = 'link';

        // if file is an binary then this piece of code runs
        if (!filter_var($file, FILTER_VALIDATE_URL)) {

            $file_path = pathinfo($file);

            if (!empty($file_path['filename']) && !empty($file_path['basename'])){

                $type = !empty($file->extension()) ? $file->extension() == 'gif' ? $file->extension() : $type : $type ; // for gif

                $type = !empty($file->extension()) ? $file->extension()  == 'svg' ? 'svg' : $type : $type; // for svg image

                $extension = $file->extension();

                $original_name = $file->getClientOriginalName();

            }

        }

        if ($type === 'video'){

            $folder = storage_path('videos');
        }elseif ($type === 'audio'){

            $folder = storage_path('audios');
        }elseif ($type === 'document'){
            $folder = storage_path('documents');
        }
        else{

            $folder = storage_path('uploads');
        }

        if (empty($ext))
        {
            $filename = $file->getClientOriginalName();

        }else{

            $filename = $ext;
        }

        if ($type === 'base64Image'){

            $filename = Str::random(10) . '.' . $extension;

        }else if ($type === 'video'){

            $filename = Str::random(10) . '.mp4';

        }else if ($type === 'audio'){

            $filename = Str::random(10) . '.mp3';

        }else if ($type === 'gif'){

            $filename = Str::random(10) . '.gif';
        }else if ($type === 'document'){
            $filename = Str::random(10) . '.pdf';
        }

        if (!File::isDirectory($folder)){
            File::makeDirectory($folder,0777,true,true);
        }
        if (!File::isDirectory(storage_path('upload_thumbnails'))){
            File::makeDirectory(storage_path('upload_thumbnails'),0777,true,true);
        }

        $date_append = date('Y-m-d-His');

        if($type === 'base64Image'){

            $file = Image::make($file);

            $upload_success = $file->save($folder . '/' . ($date_append . $filename));

        }else if ($type === 'video'){

//            $upload_success = Storage::disk('videos')->put($date_append . $filename, file_get_contents($file));

            $upload_success = $file->storeAs("",$date_append . $filename ,['disk' => 'videos']);

        }else if ($type === 'audio'){

            $upload_success = $file->storeAs("",$date_append . $filename ,['disk' => 'audios']);

        }else if($type === 'document'){
            $upload_success = $file->storeAs("",$date_append . $filename ,['disk' => 'documents']);
        }
        else{

            $upload_success = $file->move($folder, $date_append . $filename);
        }

        $original_path = base_path() . config('urls.image') . $date_append . $filename;

        $thumbnail_path = base_path() . config('urls.thumbnail') . $date_append . $filename;

        if ($type != 'svg' && $type != 'gif' && $type != 'video' && $type != 'audio' && $type!= 'document'){ // if original is present but thumbnail not present

            if (!$resize){

                $thumbnail = self::resizeImage(File::get($original_path), $thumbnail_width,$thumbnail_height);

            }else{

                $thumbnail = $file;
            }


            $thumbnail->save(storage_path('upload_thumbnails') . '/' . $date_append . $filename);

        }
        else if ($type == 'svg' || $type == 'gif'){

            File::copy($folder .'/'. ($date_append . $filename),storage_path('upload_thumbnails') . '/' . $date_append . $filename);

        }

        if ($upload_success){

            $upload = self::create([
                "name" => $filename,
                "path" => $folder . DIRECTORY_SEPARATOR . $date_append . $filename,
                "extension" => pathinfo($filename, PATHINFO_EXTENSION),
                "pre_fill" => $date_append,
                "original_name" => $original_name,
                "hash" => "",
            ]);


            // apply unique random hash to file
            while(true){

                $hash = strtolower(str::random(20));

                if (!Upload::where('hash',$hash)->count()){

                    $upload->hash = $hash;
                    break;
                }
            }

            $upload->save();

            return $upload->id;
        }
    }

    /**
     * Upload an MP3 file to public storage and create Upload record.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return int|null   Upload ID
     */
    public static function uploadMp3(UploadedFile $file): ?array
    {
        // 1) Guard: only .mp3
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: '');
        if ($ext !== 'mp3') {
            return null;
        }
        // 2) Names & paths
        $original   = $file->getClientOriginalName() ?: 'audio.mp3';
        $timestamp  = now()->format('Y-m-d-His');      // keeps your pre_fill style
        $hash       = Str::lower(Str::random(20));     // folder-level hash
        $filename   = $timestamp . '_' . Str::random(10) . '.mp3';
        $folder     = "audios/{$hash}";                // storage/app/public/audios/{hash}
        // 3) Store the file (IMPORTANT: folder, filename, 'public')
        $stored = $file->storeAs($folder, $filename, 'public');
        if (!$stored) {
            return null;
        }
        // 4) DB row (path points to "storage/..." for direct public URL building)
        $upload = Upload::create([
            'name'          => $filename,
            'path'          => "storage/{$stored}",   // e.g. storage/audios/{hash}/{filename}
            'extension'     => 'mp3',
            'pre_fill'      => $timestamp,
            'original_name' => $original,
            'hash'          => $hash,
        ]);
        if (!$upload) {
            // Roll back file if you want:
            // Storage::disk('public')->delete($stored);
            return null;
        }
        return [
            'id'  => $upload->id,
            'url' => url("storage/{$stored}"),        // https://your-domain/storage/...
        ];
        // (No cookies / PHP handling; Apache serves it statically → seeking works)
    }

    public static function resizeImage($image, $width = 200, $height = 200)
    {
        return Image::make($image)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
    }

    // public static function deleteUploadFile($id = null)
    // {
    //     return self::whereId($id)->delete();
    // }
}
