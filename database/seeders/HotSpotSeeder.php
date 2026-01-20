<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotSpotSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hotspots')->insert([
            [
                'id' => 1,
                'hotspot' => 'HOTSPOT_01',
                'name' => 'NO-AES',
            ],
            [
                'id' => 2,
                'hotspot' => 'HOTSPOT_02',
                'name' => 'ZERO-TRAIT',
            ],
            [
                'id' => 3,
                'hotspot' => 'HOTSPOT_03',
                'name' => 'NO-THINK',
            ],
            [
                'id' => 4,
                'hotspot' => 'HOTSPOT_04',
                'name' => 'TWO-TRAITS',
            ],
            [
                'id' => 5,
                'hotspot' => 'HOTSPOT_05',
                'name' => 'NO-DO',
            ],
            [
                'id' => 6,
                'hotspot' => 'HOTSPOT_06',
                'name' => 'NO-SEE',
            ],
            [
                'id' => 7,
                'hotspot' => 'HOTSPOT_07',
                'name' => '4PLUS-RED',
            ],
            [
                'id' => 8,
                'hotspot' => 'HOTSPOT_08',
                'name' => 'THREE-RED',
            ],
            [
                'id' => 9,
                'hotspot' => 'HOTSPOT_09',
                'name' => 'TWO-RED',
            ],
            [
                'id' => 10,
                'hotspot' => 'HOTSPOT_10',
                'name' => 'ONE-RED',
            ],
            [
                'id' => 11,
                'hotspot' => 'HOTSPOT_11',
                'name' => '2DRIVER-LOW',
            ],
            [
                'id' => 12,
                'hotspot' => 'HOTSPOT_12',
                'name' => '1DRIVER-LOW',
            ],
            [
                'id' => 13,
                'hotspot' => 'HOTSPOT_13',
                'name' => 'TOO-LOUD',
            ],
            [
                'id' => 14,
                'hotspot' => 'HOTSPOT_14',
                'name' => 'CHECKED-OUT',
            ],
            [
                'id' => 15,
                'hotspot' => 'HOTSPOT_15',
                'name' => 'HIGH-ALCHEMY',
            ],
            [
                'id' => 16,
                'hotspot' => 'HOTSPOT_16',
                'name' => 'LOW-ALCHEMY',
            ],
            [
                'id' => 17,
                'hotspot' => 'HOTSPOT_17',
                'name' => '2HYPER-COMM',
            ],
            [
                'id' => 18,
                'hotspot' => 'HOTSPOT_18',
                'name' => '1HYPER-COMM',
            ],
            [
                'id' => 19,
                'hotspot' => 'HOTSPOT_19',
                'name' => '2ATRO-COMM',
            ],
            [
                'id' => 20,
                'hotspot' => 'HOTSPOT_20',
                'name' => '1ATRO-COMM',
            ],
            [
                'id' => 21,
                'hotspot' => 'HOTSPOT_21',
                'name' => 'FLATLINE',
            ],
            [
                'id' => 22,
                'hotspot' => 'HOTSPOT_22',
                'name' => 'NEGCHARGE',
            ],
            [
                'id' => 23,
                'hotspot' => 'HOTSPOT_23',
                'name' => 'VERYPOSITIVE',
            ],
            [
                'id' => 24,
                'hotspot' => 'HOTSPOT_24',
                'name' => 'LOWENERGY',
            ],
            [
                'id' => 25,
                'hotspot' => 'HOTSPOT_25',
                'name' => 'SUPERENERGY',
            ],
            [
                'id' => 26,
                'hotspot' => 'HOTSPOT_26',
                'name' => 'UNIVERSAL',
            ],
        ]);
    }
}
