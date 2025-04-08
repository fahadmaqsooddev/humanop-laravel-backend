<?php

namespace App\Helpers\BlueHelper;

use Illuminate\Support\Facades\Http;

class BlueHelpers
{

    public static function createBlueRecord($title, $description, $platform, $userEmail, $url=null)
    {
        $apiUrl = 'https://api.blue.cc/graphql';

        $headers = [
            'Content-Type' => 'application/json',
            'X-Bloo-Token-ID' => '8056f4df0cd44b12bc7ee6636fec0acc',
            'X-Bloo-Token-Secret' => 'pat_54328abd7e3545479c2614420588650b',
            'X-Bloo-Company-ID' => 'humanop',
            'X-Bloo-Project-ID' => 'cm2kkgox801y52lukch5g2yxs',
        ];

        $profileLink = is_array($url) && isset($url['url']) ? $url['url'] : 'N/A';
        $fullDescription = addslashes($description) . ' from Email Address: ' . addslashes($userEmail) . '<br>'.'image: ' . addslashes($profileLink);
        $query = '
            mutation CreateRecord {
                createTodo(
                    input: {
                        todoListId: "cm3hnnh510g781042xyy6z5ns",
                        title: "' . addslashes($title) . '",
                          description: "' . $fullDescription . '",
                        position: 65535,
                        placement: TOP
                        customFields: [
                           {
                               customFieldId: "cm3hnhh150fzi1042zm0zcxjs",
                               value: "' . addslashes($platform) . '"
                             }
                        ]
                    }
                ) {
                    id
                    title
                    position
                }
            }
        ';

        $response = Http::withHeaders($headers)->post($apiUrl, ['query' => $query]);
        

        return $response->json();
    }
    // public static function createBlueRecord($title, $description, $platform, $userEmail)
    // {
    //     $apiUrl = 'https://api.blue.cc/graphql';

    //     $headers = [
    //         'Content-Type' => 'application/json',
    //         'X-Bloo-Token-ID' => '8056f4df0cd44b12bc7ee6636fec0acc',
    //         'X-Bloo-Token-Secret' => 'pat_54328abd7e3545479c2614420588650b',
    //         'X-Bloo-Company-ID' => 'humanop',
    //         'X-Bloo-Project-ID' => 'cm2kkgox801y52lukch5g2yxs',
    //     ];

    //     $query = '
    //         mutation CreateRecord {
    //             createTodo(
    //                 input: {
    //                     todoListId: "cm3hnnh510g781042xyy6z5ns",
    //                     title: "' . addslashes($title) . '",
    //                     description: "' . addslashes($description) . ' from Email Address:  '. addslashes($userEmail) .'",
    //                     position: 65535,
    //                     placement: TOP
    //                     customFields: [
    //                        {
    //                            customFieldId: "cm3hnhh150fzi1042zm0zcxjs",
    //                            value: "' . addslashes($platform) . '"
    //                          }
    //                     ]
    //                 }
    //             ) {
    //                 id
    //                 title
    //                 position
    //             }
    //         }
    //     ';

    //     $response = Http::withHeaders($headers)->post($apiUrl, ['query' => $query]);

    //     return $response->json();
    // }
}

