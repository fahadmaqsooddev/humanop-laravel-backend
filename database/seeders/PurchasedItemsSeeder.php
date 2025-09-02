<?php

namespace Database\Seeders;

use App\Enums\Admin\Admin;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\ShopCategoryResource;
use App\Models\Client\PurchasedItems;
use App\Models\Libraries\HumanOpLibraries;
use Illuminate\Database\Seeder;

class PurchasedItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $purchasedItems = HumanOpLibraries::all();

        foreach ($purchasedItems as $purchasedItem) {

            if (!empty($purchasedItem['item_id'])){

                $resourceName = ShopCategoryResource::singleLibraryResource($purchasedItem['item_id']);

                if (!empty($resourceName['price'])){

                    $name = "You have purchased Suggested item {$resourceName['heading']}";

                    PurchasedItems::createItem($purchasedItem['user_id'], $name, $resourceName['price'], Admin::B2C_PURCHASED_ITEM);

                }

            }
        }

        foreach ($purchasedItems as $purchasedItem) {

            if (!empty($purchasedItem['library_resource_id'])){

                $resourceName = LibraryResource::singleLibraryResource($purchasedItem['library_resource_id']);

                if ($resourceName['libraryPermissions']){

                    if ($resourceName['libraryPermissions']['price']){

                        $name = "You have purchased Suggested item {$resourceName['heading']}";

                        PurchasedItems::createItem($purchasedItem['user_id'], $name, $resourceName['libraryPermissions']['price'], Admin::B2C_PURCHASED_ITEM);


                    }

                }
            }
        }
    }
}
