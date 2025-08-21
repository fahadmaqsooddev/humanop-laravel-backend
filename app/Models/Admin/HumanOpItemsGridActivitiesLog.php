<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanOpItemsGridActivitiesLog extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function storeSuggestedItemTraits($suggestedItemId = null, $traitName = null)
    {

        self::create([
            'suggested_item_id' => $suggestedItemId,
            'grid_name' => $traitName,
        ]);
    }

    public static function storeResourceItemTraits($suggestedItemId = null, $traitName = null)
    {

        self::create([
            'resource_item_id' => $suggestedItemId,
            'grid_name' => $traitName,
        ]);
    }

    public static function storeShopItemTraits($suggestedItemId = null, $traitName = null)
    {

        self::create([
            'shop_item_id' => $suggestedItemId,
            'grid_name' => $traitName,
        ]);
    }

    public static function deleteSuggestItems($suggestedItemId = null)
    {

        return self::where('suggested_item_id', $suggestedItemId)->delete();

    }

    public static function getResourceGrid($resourceItemId = null)
    {
        return self::where('resource_item_id', $resourceItemId)->get();
    }

    public static function getShopGrid($resourceItemId = null)
    {
        return self::where('shop_item_id', $resourceItemId)->get();
    }
}
