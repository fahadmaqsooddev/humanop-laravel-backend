<?php

namespace App\Helpers\v4\Practitioner;

use App\Models\v4\Upload\Upload;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\v4\Assessment;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\v4\Client\Plan\Plan;
use Stripe\BaseStripeClient;
use Stripe\Stripe;
use Stripe\StripeClient;
use App\Models\v4\User;

class PractitionerHelpers
{

    public static function makePractitionerUrl($url, $id = null){

        $request_segments = request()->segments();

        if (count($request_segments) > 0){

            $segment_first = isset($request_segments[0]) ? $request_segments[0] : null;

            $segment_second = isset($request_segments[1]) ? $request_segments[1] : null;

            if ($segment_first && $segment_second){

                if (!empty($id))
                {
                    return (url('/') . '/' . $segment_first . '/' . $segment_second . '/' . $url. '/' . $id['id']);
                }
                else
                {
                    return (url('/') . '/' . $segment_first . '/' . $segment_second . '/' . $url);
                }

            }

        }

        return (url($url));

    }

}

