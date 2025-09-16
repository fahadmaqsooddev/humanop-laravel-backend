<?php

namespace App\Models\Admin\Resources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Resources\LibraryResource;

class PermissionResource extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function resource()
    {
        return $this->belongsTo(LibraryResource::class,'resource_id', 'id');
    }

    public static function getPermission($planName = null)
    {
        if ($planName == 'Freemium') {

            $permission = self::has('resource')->with('resource')->whereIn('permission', [1,4])->get();

        }elseif ($planName == 'Premium') {

            $permission = self::has('resource')->with('resource')->whereIn('permission', [2, 4])->get();

        }
//        elseif ($planName == 'Premium'){
//
//            $permission = self::has('resource')->with('resource')->whereIn('permission', [3, 4])->get();
//        }

        return $permission ?? [];
    }

    public static function createResourcePermission($resourceId = null, $permissions = null,$priceValue=null,$pointValue=null)
    {

        if ($permissions && !empty($permissions)){

            self::where('resource_id', $resourceId)->delete();

            foreach ($permissions as $permission)
            {
                self::create([
                    'resource_id' => $resourceId,
                    'permission' => $permission,
                    'price' => $priceValue,
                    'point' => $pointValue,
                ]);
            }

        }
    }

    public static function deleteResourcePermission($resourceId = null)
    {
        return self::where('resource_id', $resourceId)->delete();
    }
}
