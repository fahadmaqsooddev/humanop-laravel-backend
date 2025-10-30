<?php

namespace App\Helpers\BlueHelper;

use Illuminate\Support\Facades\Http;

class BlueHelpers
{

    public static function createBlueRecord($title = null, $description = null, $platform = null, $userEmail = null, $supportCategory = null, $imageUrl = null, $videoUrl = null)
    {

        $description = trim(preg_replace('/\s+/', ' ', $description));

        $apiUrl = 'https://api.blue.cc/graphql';

        $headers = [
            'Content-Type' => 'application/json',
            'X-Bloo-Token-ID' => '8056f4df0cd44b12bc7ee6636fec0acc',
            'X-Bloo-Token-Secret' => 'pat_54328abd7e3545479c2614420588650b',
            'X-Bloo-Company-ID' => 'humanop',
            'X-Bloo-Project-ID' => 'cm2kkgox801y52lukch5g2yxs',
        ];

        if (!empty($imageUrl)){

            $profileLink = $imageUrl['url'] ?? '';

        }elseif (!empty($videoUrl)){

            $profileLink = $videoUrl['path'] ?? '';
        }

        $fullDescription = addslashes($description) . ' from Email Address: ' . addslashes($userEmail);

        if (!empty($profileLink)) {
            $fullDescription .= '<br>Screen Capture: ' . addslashes($profileLink);
        }

        $query = '
            mutation CreateRecord {
                createTodo(
                    input: {
                        todoListId: "cm8t4dqaf12k7sf2l7sldqzfl",
                        title: "' . addslashes($title) . '",
                          description: "' . $fullDescription . '",
                        position: 65535,
                        placement: TOP
                        customFields: [
                           {
                               customFieldId: "cm3hnhh150fzi1042zm0zcxjs",
                               value: "' . addslashes($platform) . '"
                             },
                             {
                               customFieldId: "cmggk80590ta5ob1erh9h8g4d",
                               value: "' . addslashes($supportCategory) . '"
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
        $responseData = $response->json();

        if (isset($responseData['data']['createTodo'])) {
            $ticket = $responseData['data']['createTodo'];

            dd($ticket);
            
            return [
                'blue_ticket_id' => $ticket['id'] ?? null,
                'blue_ticket_status' => $ticket['status'] ?? null,
                'blue_last_synced_at' => $ticket['lastSyncedAt'] ?? null,
            ];
        }

        return [
            'error' => $responseData['errors'][0]['message'] ?? 'Unknown error',
            'raw' => $responseData,
        ];
    }

}

