<?php

namespace App\Models\Admin\VersionControl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionControlDescription extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function createDescription($versionId=null,$description=null,$platform=null,$versionHeading=null){
        if (is_array($platform)) {
            $platform = implode(',', $platform);
        }
    
        // Store in database
        $data= self::create([
            'version_id' => $versionId,
            'description' => $description,
            'platform' => $platform, 
            'version_heading'=>$versionHeading
        ]);
        return $data;
    }

    public static function deleteDescription($id=null){
        return self::where('id',$id)->delete();
    }
    public static function editDescription($id=null,$versionId=null,$description=null,$platform=null,$versionHeading=null){
        if (is_array($platform)) {
            $platform = implode(',', $platform);
        }
        return self::where('id',$id)->update([
            'version_id' => $versionId,
            'description' => $description,
            'platform' => $platform,
            'version_heading'=>$versionHeading
        ]);
    }
}
