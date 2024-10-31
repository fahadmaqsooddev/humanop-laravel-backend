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

    public static function getLatestVersion()
    {
        return self::latest()->first();
    }


    public static function getVersions()
    {

        return self::orderBy('id', 'desc')->get();
    }

    public static function createVersion($version = null, $detail = null)
    {

        $version = self::create([
            'version' => $version,
            'details' => $detail,
        ]);

        return $version;
    }

    public static function editVersion($id = null, $version = null, $detail = null)
    {
        $version = self::where('id',$id)->update([
            'version' => $version,
            'details' => $detail,
        ]);

        return $version;
    }

    public static function allVersions(){
        return self::orderBy('created_at', 'asc');
    }
}
