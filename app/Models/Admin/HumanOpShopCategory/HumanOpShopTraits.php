<?php

namespace App\Models\Admin\HumanOpShopCategory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanOpShopTraits extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }


    public  static function storeTraits($resourceId=null,$traitName=null)
    {

        self::create([
            'humanop_shop_resource_id' => $resourceId,
            'trait_name' => $traitName,
        ]);
    }

    public static function updateTraits($resourceId=null,$traitName=null)
    {
        self::whereId('humanop_shop_resource_id',$resourceId)->update([
            'trait_name' => $traitName,
        ]);
    }
}
