<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Feedback\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BlueWebhookController extends Controller
{

    public function ticketUpdated(Request $request)
    {
        Log::info("Blue Payload:\n" . json_encode($request['currentValue'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


//        $signature = $request->header('X-Blue-Signature');
//        $secret = config('services.blue.webhook_secret');
//
//        if (!$this->isValidSignature($request->getContent(), $signature, $secret)) {
//            Log::warning('Blue webhook invalid signature', ['sig' => $signature]);
//            return response()->json(['error' => 'invalid signature'], Response::HTTP_FORBIDDEN);
//        }

        $blueTicketId = $request['currentValue']['id'];
        $newStatus = $request['currentValue']['todoListId'];
        $lastUpdate = $request['currentValue']['updatedAt'];

        if (!$blueTicketId) {

            return Helpers::validationResponse('missing ticket_id');

        }

        Log::info("Blue Ticket ID: " . $blueTicketId);

        $ticket = Feedback::where('blue_ticket_id', $blueTicketId)->first();

        if (!$ticket) {

            Log::info('Webhook for unknown ticket', ['blue_ticket_id' => $blueTicketId]);


            return response()->json(['ok' => true], Response::HTTP_OK);

        }

        $statusMap = [
            'cm8t4dqaf12k7sf2l7sldqzfl' => 'Pending',
            'pbs0tgcttploiho8btqedd4j' => 'Pending',
            'dfx3tycxzx09o9xer0d9yxl3' => 'In Progress',
            'cm3gddo3y2gf3r08u9ykpfxl0' => 'Pending',
            'cm394w4uz2w9tjmxal80nhmp1' => 'Pending',
            'cm2klw9lk1l8vyid5hn7uwchg' => 'Pending',
            'z1x6cnmocs77j8asys1wvaei' => 'Pending',
            'nyytmgbfakxmjc9o4nixnsdk' => 'Resolved',
        ];

        $status = $statusMap[$newStatus] ?? 'Pending';

        $ticket->update([
            'blue_status' => $status,
            'blue_last_update' => $lastUpdate,
            'blue_last_synced_at' => now(),
        ]);


        return Helpers::successResponse('Ticket Updated');

    }

    protected function isValidSignature(string $payload, ?string $headerSig, string $secret): bool
    {
        if (!$headerSig) {
            return false;
        }

        // HMAC SHA256 example
        $expected = hash_hmac('sha256', $payload, $secret);

        // timing safe compare
        return hash_equals($expected, $headerSig);
    }
}
