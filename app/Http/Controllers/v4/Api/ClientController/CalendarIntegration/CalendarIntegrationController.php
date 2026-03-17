<?php

namespace App\Http\Controllers\v4\Api\ClientController\CalendarIntegration;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\v4\Client\CalendarIntegration\UserCalendarIntegration;
use App\Services\v4\GoogleCalendarService\GoogleCalendarService;
use Illuminate\Http\Request;

class CalendarIntegrationController extends Controller
{

    protected $google;

    public function __construct(GoogleCalendarService $google)
    {
        $this->google = $google;
    }

    public function connect(Request $request)
    {
        $user = Helpers::getUser();

        $url = $this->google->authUrl($user->id);

        return Helpers::successResponse('Google authentication URL', $url);

    }

    public function callback(Request $request)
    {
        if (!$request->has(['code', 'state'])) {
            return Helpers::validationResponse('Invalid callback request');
        }

        try {
            $userId = decrypt($request->state);
        } catch (\Exception $e) {
            return Helpers::validationResponse('Invalid state parameter');
        }

        $token = $this->google->fetchToken($request->code);

        if (isset($token['error'])) {
            return Helpers::serverErrorResponse($token['error_description'] ?? 'Google authentication failed');
        }

        UserCalendarIntegration::updateOrCreate(
            [
                'user_id' => $userId,
                'provider' => 'google'
            ],
            [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'connected_at' => now()
            ]
        );

        return redirect()->away(config('client_url.client_dashboard_url_v4') . '/profile');

    }

    public function disconnect(Request $request)
    {
        $user = Helpers::getUser();

        UserCalendarIntegration::where('user_id',$user->id)
            ->where('provider','google')
            ->delete();

        return Helpers::successResponse('Google calendar integration disconnected');

    }

    public function status(Request $request)
    {
        $user = Helpers::getUser();

        $integration = UserCalendarIntegration::where('user_id',$user->id)
            ->where('provider','google')
            ->first();

        $data = [
            'connected' => (bool) $integration,
            'connected_at' => optional($integration)->connected_at
        ];

        return Helpers::successResponse('Google calendar integration status', $data);

    }

}
