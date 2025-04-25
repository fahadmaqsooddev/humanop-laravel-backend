<?php

namespace App\Models\Admin\VersionControl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function versionDescriptions(){

        return $this->hasMany(VersionControlDescription::class,'version_id','id')->whereNotNull('version_id');
    }
    public static function getLatestVersion()
    {
        return self::latest()->first();
    }


    public static function getVersions()
    {

        return self::orderBy('id', 'desc')->get();
    }

    public static function createVersion($version = null, $note = null)
    {

        $version = self::create([
            'version' => $version,
            'note' => $note,
        ]);

        return $version;
    }

    public static function editVersion($id = null, $version = null, $detail = null)
    {

        $version = self::where('id',$id)->update([
            'version' => $version,
            'note' => $detail,
        ]);
        

        return $version;
    }


    public static function deleteVersion($id){
        return self::where('id',$id)->delete();
    }

    public static function allVersions(){
        return self::with('versionDescriptions')->orderBy('created_at', 'asc');
    }
    
}
