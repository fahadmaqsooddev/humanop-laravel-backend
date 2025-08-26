<?php

namespace App\Helpers;

use App\Enums\Admin\Admin;
use App\Models\Admin\Notification\Notification;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\B2B\UserCandidateInvite;
use App\Models\Client\Gamification\GamificationPerformanceLevel;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Client\Plan\Plan;
use App\Models\Client\Point\Point;
use App\Models\Upload\Upload;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Assessment;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\User;
use GuzzleHttp\Client;

class Helpers
{
    # ====================================
    # *            Responses             *
    # ====================================

    public static function successResponse($message, $data = [], $pagination = false)
    {
        if ($pagination) {
            return response()->json(['status' => true, 'message' => $message, 'result' => $data], config('httpstatuscodes.ok_status'));
        } else {
            return response()->json(['status' => true, 'message' => $message, 'result' => array('data' => $data)], config('httpstatuscodes.ok_status'));
        }
    }

    public static function validationResponse($errors, $request = null)
    {
        return response(['status' => false, 'message' => $errors], config('httpstatuscodes.not_acceptable_status'));
    }

    public static function upgradePackageResponse($errors, $request = null)
    {
        return response(['status' => false, 'message' => $errors], config('httpstatuscodes.package_upgrade_required'));
    }

    public static function unauthResponse($errors)
    {
        return response(['status' => false, 'message' => $errors], config('httpstatuscodes.unauthorized_status'));
    }

    public static function forbiddenResponse($errors)
    {
        return response(['status' => false, 'message' => $errors], config('httpstatuscodes.forbidden_status'));
    }

    public static function notFoundResponse($errors)
    {
        return response(['status' => false, 'message' => $errors], config('httpstatuscodes.not_found_status'));
    }

    public static function serverErrorResponse($errors)
    {
        $message = 'Something went wrong. Please contact technical support';
        if (config('app.env') == 'production') {
            return response()->json(['status' => false, 'message' => $message], config('httpstatuscodes.internal_server_error'));
        } else {
            return response()->json(['status' => false, 'message' => $message, 'errors' => $errors], config('httpstatuscodes.internal_server_error'));
        }
    }

    public static function pagination($all, $pagination = false, $per_page = null)
    {
        if ($pagination && ($pagination === true || $pagination === "true")) {

            if ($per_page) {

                $all = $all->paginate($per_page);

            } else {

                $all = $all->paginate(10);
            }

            return $all;

        } else {

            return $all->get();

        }

    }

    public static function paginateForCollectionsAndArrays($items, $perPage = 1, $page = 1, $options = [], $pagination = false)
    {
        $page = $page == "null" ? 1 : $page; //This line we add because front-end team send page = "null" in payload which cause error.

        $perPage = $perPage ?: 10;

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        if ($pagination && ($pagination === true || $pagination === "true")) {

            $currentItems = array_slice($items->toArray(), $perPage * ($page - 1), $perPage);

            return new LengthAwarePaginator($currentItems, $items->count(), $perPage, $page, ['path' => LengthAwarePaginator::resolveCurrentPath()]);

        } else {

            return $items;
        }
    }

    public static function paginationForGroupByCollection($all, $pagination = false, $per_page = null, $column_name = null)
    {

        if ($pagination && ($pagination === true || $pagination === "true")) {

            $all = $all->get()->groupBy(function ($val) use ($column_name) {

                return Carbon::parse($val->$column_name)->format('F Y');

            })->toArray();

            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            $currentItems = array_slice($all, $per_page * ($currentPage - 1), $per_page);

            return new LengthAwarePaginator($currentItems, count($all), $per_page, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);
        } else {

            $all = $all->get()->groupBy(function ($val) use ($column_name) {

                return Carbon::parse($val->$column_name)->format('F Y');

            });

            return $all;
        }
    }

    public static function removeStorageUploadsAndThumbnails($object = null)
    {

        if ($object) {

            if (File::exists($object->path) || File::exists(base_path() . config('urls.thumbnail') . $object->pre_fill . $object->name)) {

                File::delete($object->path);

                File::delete(base_path() . config('urls.thumbnail') . $object->pre_fill . $object->name);
            }
        }

    }

    public static function getUser()
    {

        return Auth::guard('api')->user();

    }

    public static function timeZone()
    {

        $preferred_zones = array(
            'America/Los_Angeles',
            'America/Denver',
            'America/Chicago',
            'America/New_York'
        );

        $zones_array = array();

        $timestamp = time();

        foreach ($preferred_zones as $zone) {

            date_default_timezone_set($zone);

            $zones_array[] = 'UTC/GMT ' . date('P', $timestamp) . ' - ' . $zone;

        }

        foreach (timezone_identifiers_list() as $key => $zone) {

            if (!in_array($zone, $preferred_zones)) {

                date_default_timezone_set($zone);

                $zones_array[] = 'UTC/GMT ' . date('P', $timestamp) . ' - ' . $zone;

            }

        }

        return $zones_array;

    }

    public static function explodeAgeRangeIntoAge($request = null)
    {

        if (isset($request['age_range']) && !empty($request['age_range'])) {

            $age = explode('-', $request['age_range']);

            $request['age_min'] = isset($age[0]) ? $age[0] : 0;

            $request['age_max'] = isset($age[1]) ? $age[1] : 0;

            return $request;

        }

        return $request;
    }

    public static function getWebUser()
    {

        return Auth::guard('web')->user();
    }

    public static function createCustomerAndSubscriptionOnStripe($user = null)
    {

        $key = StripeSetting::getSingle();

        if (!$user->hasStripeId()) {

            User::createCustomerOnStripe($user, $key);
        }

    }

    public static function checkAssessment($user_id = null)
    {
        return Assessment::checkAssessment($user_id);
    }

    public static function getImage($pic, $original_default = null, $is_original_name = 0)
    {

        if (!empty($pic)) {

            $upload = Upload::find($pic);

            if ($upload->extension === 'mp4' || $upload->extension === 'mp3') {

                return [];

            }

            if (!empty($upload)) {

                $path = url('/') . '/media/files/' . $upload->hash . '/' . $upload->name;
                $path_thumbnail = url('/') . '/media/thumbnails/' . $upload->hash . '/' . $upload->name;

                if ($is_original_name) {

                    $original_name = $upload['original_name'];

                    return array('url' => $path, 'thumbnail_url' => $path_thumbnail, 'original_name' => $original_name);

                }

                return array('url' => $path, 'thumbnail_url' => $path_thumbnail);

            } else { // if upload not found then return the default url

                if ($original_default == "profile_pic.png" || $original_default == "cover_pic.png" || $original_default == "ind-database-default.jpg" || $original_default == "gin_logo.png" || $original_default == "hand_shake.png" || $original_default == "calender.png" || $original_default == "female_profile_pic.png") {

                    $path = url('/') . '/media/files/' . 'original_default' . '/' . $original_default;

                    $path_thumbnail = url('/') . '/media/thumbnails/' . 'thumbnail_default' . '/' . $original_default;

                    return array('url' => $path, 'thumbnail_url' => $path_thumbnail);
                }
            }

        } else {

            if ($original_default == "female_profile_pic.png" || $original_default == "profile_pic.png" || $original_default == "cover_pic.png" || $original_default == "ind-database-default.jpg" || $original_default == "image_placeholder.png" || $original_default == "humanop_default_image.png"

                || $original_default == "gin_logo.png" || $original_default == "hand_shake.png" || $original_default == "calender.png") {

                $path = url('/') . '/media/files/' . 'original_default' . '/' . $original_default;

                $path_thumbnail = url('/') . '/media/thumbnails/' . 'thumbnail_default' . '/' . $original_default;

                return array('url' => $path, 'thumbnail_url' => $path_thumbnail);

            }

        }

    }

    public static function getVideo($video, $is_original_name = 0, $sourceUrl = null, $embedLink = null)
    {

        if (!empty($sourceUrl)) {

            return array('path' => $sourceUrl, 'original_name' => $sourceUrl);

        }

        if (!empty($embedLink)) {

            return array('path' => $embedLink, 'original_name' => $embedLink);

        }

        if (!empty($video)) {

            $upload = Upload::find($video);

            if (!empty($upload)){

                if ($upload->extension != 'mp4') {

                    return [];
                }

                $path = url('/') . '/media/videos/' . $upload->hash . '/' . $upload->name;

                if ($is_original_name) {

                    $original_name = $upload['original_name'];

                    return array('path' => $path, 'original_name' => $original_name);

                }

            }else{

                return [];
            }

        }
    }

    public static function getAudio($audio, $is_original_name = 0)
    {

        if (!empty($audio)) {

            $upload = Upload::find($audio);


            if ($upload->extension != 'mp3') {

                return [];
            }

            $path = url('/') . '/media/audios/' . $upload->hash . '/' . $upload->name;

            if ($is_original_name) {

                $original_name = $upload['original_name'];

                return array('path' => $path, 'original_name' => $original_name);

            }
        }
    }

    public static function getDocument($documentId, $is_original_name = 0, $sourceUrl = null)
    {

        if (!empty($sourceUrl)) {

            return array('path' => $sourceUrl, 'original_name' => $sourceUrl);

        }

        if (!empty($embedLink)) {

            return array('path' => $embedLink, 'original_name' => $embedLink);

        }

        if (!empty($documentId)) {

            $upload = Upload::find($documentId);

            if ($upload->extension != 'pdf') {
                return [];
            }

            $path = url('/') . '/media/documents/' . $upload->hash . '/' . $upload->name;

            if ($is_original_name) {

                $original_name = $upload['original_name'];

                return array('path' => $path, 'original_name' => $original_name);

            }

        }
    }

    public static function explodeTimezoneWithHours($userTimezone = null)
    {
        $timezone_string = $userTimezone;

        if (isset($timezone_string) && !empty($timezone_string)) {

            $timezone = explode(' ', $timezone_string);

            $standard_time = isset($timezone[2]) ? $timezone[2] : "+00:00";

            $exploded_value = explode(':', $standard_time);

            if (isset($exploded_value[1]) && $exploded_value[1] !== "00") {

                $standard_time = (intval($exploded_value[0]) + (intval($exploded_value[1]) / 60));

            }

            return intval($standard_time);

        }
    }

    public static function explodeTimezoneWithHoursAndMinutes($userTimezone = null)
    {
        if (!empty($userTimezone)) {

            $timezone = explode(' ', $userTimezone);

            $standard_time = isset($timezone[1]) ? $timezone[1] : "+00:00";

            $standard_time = preg_replace('/[^\+\-0-9:]/', '', $standard_time);

            $exploded_value = explode(':', $standard_time);

            $hours = isset($exploded_value[0]) ? intval($exploded_value[0]) : 0;

            $minutes = isset($exploded_value[1]) ? intval($exploded_value[1]) : 0;
            $totalMinutes = ($hours * 60) + ($hours < 0 ? -$minutes : $minutes);

            return $totalMinutes;
        }

        return 0;
    }

    public static function NinetyDaysActionPlan($assessmentDetails = null, $authenticTraitCount = null, $inAuthenticDriverCount = null, $pilotDriverCount = null, $countFirstRowDriver = null, $countGreaterThan12 = null, $countLessThan7 = null, $values = null)
    {

        if ($assessmentDetails['firstRow']['van'] == 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_1'),
                'priority' => 'priority 1'
            ];

        } elseif ($assessmentDetails['firstRow']['sa'] == 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_2.regal'),
                'priority' => 'priority 2 regal'
            ];

        } elseif ($assessmentDetails['firstRow']['ma'] == 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_2.energetic'),
                'priority' => 'priority 2 energetic'
            ];

        } elseif ($assessmentDetails['firstRow']['jo'] == 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_2.absorptive'),
                'priority' => 'priority 2 absorptive'
            ];

        } elseif ($assessmentDetails['firstRow']['lu'] == 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_2.romantic'),
                'priority' => 'priority 2 romantic'
            ];

        } elseif ($assessmentDetails['firstRow']['ven'] == 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_2.sympathetic'),
                'priority' => 'priority 2 sympathetic'
            ];

        } elseif ($assessmentDetails['firstRow']['mer'] == 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_2.perceptive'),
                'priority' => 'priority 2 perceptive'
            ];

        } elseif ($assessmentDetails['firstRow']['so'] == 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_2.effervescent'),
                'priority' => 'priority 2 effervescent'
            ];

        } elseif (
            ($assessmentDetails['firstRow']['jo'] < 5 && $assessmentDetails['firstRow']['mer'] < 5 && $assessmentDetails['firstRow']['so'] < 5) &&
            ($assessmentDetails['thirdRow']['jo'] < 30 && $assessmentDetails['thirdRow']['mer'] < 30) &&
            ($assessmentDetails['firstRow']['so'] < 3)
        ) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_3'),
                'priority' => 'priority 3'
            ];

        } elseif ($authenticTraitCount < 3) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_4'),
                'priority' => 'priority 4'
            ];

        } elseif
        (

            ($assessmentDetails['firstRow']['ma'] < 5 && $assessmentDetails['firstRow']['lu'] < 5) &&
            ($assessmentDetails['thirdRow']['ma'] < 30 && $assessmentDetails['thirdRow']['lu'] < 30)
        ) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_5'),
                'priority' => 'priority 5'
            ];

        } elseif (

            ($assessmentDetails['firstRow']['sa'] < 5 && $assessmentDetails['firstRow']['ven'] < 5) &&
            ($assessmentDetails['thirdRow']['sa'] < 30 && $assessmentDetails['thirdRow']['ven'] < 30)
        ) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_6'),
                'priority' => 'priority 6'
            ];

        } elseif ($inAuthenticDriverCount > 4) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_7'),
                'priority' => 'priority 7'
            ];

        } elseif ($inAuthenticDriverCount == 3) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_8'),
                'priority' => 'priority 8'
            ];

        } elseif ($inAuthenticDriverCount == 2) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_9'),
                'priority' => 'priority 9'
            ];

        } elseif ($inAuthenticDriverCount == 1) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_10'),
                'priority' => 'priority 10'
            ];

        } elseif ($pilotDriverCount == 2) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_11'),
                'priority' => 'priority 11'
            ];

        } elseif ($pilotDriverCount == 1) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_12'),
                'priority' => 'priority 12'
            ];

        } elseif ($countFirstRowDriver > 21) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_13'),
                'priority' => 'priority 13'
            ];

        } elseif ($countFirstRowDriver < 16) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_14'),
                'priority' => 'priority 14'
            ];

        } elseif (in_array($assessmentDetails['alchemy'], [700, 610, 601, 520, 511, 502, 430])) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_15'),
                'priority' => 'priority 15'
            ];

        } elseif (in_array($assessmentDetails['alchemy'], [223, 133, 043, 214, 124, 115, 034, 007])) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_16'),
                'priority' => 'priority 16'
            ];

        } elseif ($countGreaterThan12 >= 2) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_17'),
                'priority' => 'priority 17'
            ];

        } elseif (count(array_filter($values, function ($value) {
                return $value > 12;
            })) == 1) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_18'),
                'priority' => 'priority 18'
            ];

        } elseif ($countLessThan7 == 2) {

            return config('90DaysActionPlan.priority_19');

        } elseif (count(array_filter($values, function ($value) {
                return $value < 7;
            })) == 1) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_20'),
                'priority' => 'priority 20'
            ];

        } elseif ($assessmentDetails['firstRow']['pv'] == 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_21'),
                'priority' => 'priority 21'
            ];

        } elseif ($assessmentDetails['firstRow']['pv'] < 0) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_22'),
                'priority' => 'priority 22'
            ];

        } elseif ($assessmentDetails['firstRow']['pv'] > 12) {

            return [
                'plan_text' => config('90DaysActionPlan.priority_23'),
                'priority' => 'priority 23'
            ];

        } elseif ($assessmentDetails['firstRow']['ep'] < 25) {


            return [
                'plan_text' => config('90DaysActionPlan.priority_24'),
                'priority' => 'priority 24'
            ];

        } elseif ($assessmentDetails['firstRow']['ep'] > 35) {


            return [
                'plan_text' => config('90DaysActionPlan.priority_25'),
                'priority' => 'priority 25'
            ];

        } else {


            return [
                'plan_text' => config('90DaysActionPlan.priority_26'),
                'priority' => 'priority 26'
            ];

        }

    }

    public static function fourteenDaysActionPlan($assessmentDetails = null, $authenticTraitCount = null, $inAuthenticDriverCount = null, $pilotDriverCount = null, $countFirstRowDriver = null, $countGreaterThan12 = null, $countLessThan7 = null, $values = null)
    {

        if ($assessmentDetails['firstRow']['van'] == 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_1'),
                'priority' => 'priority 1'
            ];

        } elseif ($assessmentDetails['firstRow']['sa'] == 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_2.regal'),
                'priority' => 'priority 2 regal'
            ];

        } elseif ($assessmentDetails['firstRow']['ma'] == 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_2.energetic'),
                'priority' => 'priority 2 energetic'
            ];

        } elseif ($assessmentDetails['firstRow']['jo'] == 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_2.absorptive'),
                'priority' => 'priority 2 absorptive'
            ];

        } elseif ($assessmentDetails['firstRow']['lu'] == 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_2.romantic'),
                'priority' => 'priority 2 romantic'
            ];

        } elseif ($assessmentDetails['firstRow']['ven'] == 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_2.sympathetic'),
                'priority' => 'priority 2 sympathetic'
            ];

        } elseif ($assessmentDetails['firstRow']['mer'] == 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_2.perceptive'),
                'priority' => 'priority 2 perceptive'
            ];

        } elseif ($assessmentDetails['firstRow']['so'] == 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_2.effervescent'),
                'priority' => 'priority 2 effervescent'
            ];

        } elseif (
            ($assessmentDetails['firstRow']['jo'] < 5 && $assessmentDetails['firstRow']['mer'] < 5 && $assessmentDetails['firstRow']['so'] < 5) &&
            ($assessmentDetails['thirdRow']['jo'] < 30 && $assessmentDetails['thirdRow']['mer'] < 30) &&
            ($assessmentDetails['firstRow']['so'] < 3)
        ) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_3'),
                'priority' => 'priority 3'
            ];

        } elseif ($authenticTraitCount < 3) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_4'),
                'priority' => 'priority 4'
            ];

        } elseif
        (

            ($assessmentDetails['firstRow']['ma'] < 5 && $assessmentDetails['firstRow']['lu'] < 5) &&
            ($assessmentDetails['thirdRow']['ma'] < 30 && $assessmentDetails['thirdRow']['lu'] < 30)
        ) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_5'),
                'priority' => 'priority 5'
            ];

        } elseif (

            ($assessmentDetails['firstRow']['sa'] < 5 && $assessmentDetails['firstRow']['ven'] < 5) &&
            ($assessmentDetails['thirdRow']['sa'] < 30 && $assessmentDetails['thirdRow']['ven'] < 30)
        ) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_6'),
                'priority' => 'priority 6'
            ];

        } elseif ($inAuthenticDriverCount > 4) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_7'),
                'priority' => 'priority 7'
            ];

        } elseif ($inAuthenticDriverCount == 3) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_8'),
                'priority' => 'priority 8'
            ];

        } elseif ($inAuthenticDriverCount == 2) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_9'),
                'priority' => 'priority 9'
            ];

        } elseif ($inAuthenticDriverCount == 1) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_10'),
                'priority' => 'priority 10'
            ];

        } elseif ($pilotDriverCount == 2) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_11'),
                'priority' => 'priority 11'
            ];

        } elseif ($pilotDriverCount == 1) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_12'),
                'priority' => 'priority 12'
            ];

        } elseif ($countFirstRowDriver > 21) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_13'),
                'priority' => 'priority 13'
            ];

        } elseif ($countFirstRowDriver < 16) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_14'),
                'priority' => 'priority 14'
            ];

        } elseif (in_array($assessmentDetails['alchemy'], [700, 610, 601, 520, 511, 502, 430])) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_15'),
                'priority' => 'priority 15'
            ];

        } elseif (in_array($assessmentDetails['alchemy'], [223, 133, 043, 214, 124, 115, 034, 007])) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_16'),
                'priority' => 'priority 16'
            ];

        } elseif ($countGreaterThan12 >= 2) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_17'),
                'priority' => 'priority 17'
            ];

        } elseif (count(array_filter($values, function ($value) {
                return $value > 12;
            })) == 1) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_18'),
                'priority' => 'priority 18'
            ];

        } elseif ($countLessThan7 == 2) {

            return config('14DaysActionPlan.priority_19');

        } elseif (count(array_filter($values, function ($value) {
                return $value < 7;
            })) == 1) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_20'),
                'priority' => 'priority 20'
            ];

        } elseif ($assessmentDetails['firstRow']['pv'] == 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_21'),
                'priority' => 'priority 21'
            ];

        } elseif ($assessmentDetails['firstRow']['pv'] < 0) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_22'),
                'priority' => 'priority 22'
            ];

        } elseif ($assessmentDetails['firstRow']['pv'] > 12) {

            return [
                'plan_text' => config('14DaysActionPlan.priority_23'),
                'priority' => 'priority 23'
            ];

        } elseif ($assessmentDetails['firstRow']['ep'] < 25) {


            return [
                'plan_text' => config('14DaysActionPlan.priority_24'),
                'priority' => 'priority 24'
            ];

        } elseif ($assessmentDetails['firstRow']['ep'] > 35) {


            return [
                'plan_text' => config('14DaysActionPlan.priority_25'),
                'priority' => 'priority 25'
            ];

        } else {


            return [
                'plan_text' => config('14DaysActionPlan.priority_26'),
                'priority' => 'priority 26'
            ];

        }

    }

    public static function getOptionalTrait($timezone = null, $traits = null, $features = null)
    {
        $stylesAndDrivers = array_merge($traits, $features);

        $minutes = Helpers::explodeTimezoneWithHours($timezone);

        $currentTime = Carbon::now()->addMinutes($minutes * 60);

        $morningStart = Carbon::createFromTimeString('05:00 AM');

        $morningEnd = Carbon::createFromTimeString('12:00 PM');

        $afternoonStart = Carbon::createFromTimeString('12:00 PM');

        $eveningStart = Carbon::createFromTimeString('05:00 PM');

        if (count($stylesAndDrivers) > 2) {

            if ($currentTime->between($morningStart, $morningEnd)) {

                $status = Admin::MORNING_STATUS;

                $optionalTrait = $stylesAndDrivers[0]['public_name'] ?? null;

            } elseif ($currentTime->between($afternoonStart, $eveningStart)) {

                $status = Admin::AFTERNOON_STATUS;

                $optionalTrait = $stylesAndDrivers[1]['public_name'] ?? null;

            } else {

                $status = Admin::NIGHT_STATUS;

                $optionalTrait = $stylesAndDrivers[2]['public_name'] ?? null;

            }

        } else {

            if ($currentTime->between($morningStart, $morningEnd)) {

                $status = Admin::MORNING_STATUS;

                $optionalTrait = $stylesAndDrivers[0]['public_name'] ?? null;

            } elseif ($currentTime->between($afternoonStart, $eveningStart)) {

                $status = Admin::AFTERNOON_STATUS;

                $optionalTrait = $stylesAndDrivers[1]['public_name'] ?? null;

            } else {

                $status = Admin::NIGHT_STATUS;

                $optionalTrait = $stylesAndDrivers[1]['public_name'] ?? null;

            }
        }

        return [
            'trait' => $optionalTrait,
            'status' => $status,
        ];

    }

    public static function explodeAssessmentTimezoneWithHours($userTimezone = null, $assessmentUpdatedAt = null)
    {
        $timezone_string = $userTimezone ? $userTimezone : 'UTC/GMT -07:00 - America/Los_Angeles';

        if (isset($timezone_string) && !empty($timezone_string)) {

            $timezone = explode(' ', $timezone_string);

            $standard_time = isset($timezone[1]) ? $timezone[1] : "+00:00";

            $exploded_value = explode(':', $standard_time);

            if (isset($exploded_value[1]) && $exploded_value[1] !== "00") {

                $standard_time = (intval($exploded_value[0]) + (intval($exploded_value[1]) / 60));

            }

            $minutes = intval($standard_time);

            $userTime = \Carbon\Carbon::parse($assessmentUpdatedAt)->addMinutes($minutes * 60)->toDateTimeString();

            $difference = \Carbon\Carbon::now()->diffInDays($userTime);

            return $difference;
        }
    }

    public static function stringFromPdfOrTextFile($text)
    {

        return str_split($text, 5000);
    }

    public static function createClientsOnOneSignal($userId = null)
    {

        $client = new Client();

        $response = $client->request('POST', 'https://api.onesignal.com/apps/' . config('oneSignal.app_id') . '/users', [
            'body' => '{"identity":{"external_id":"' . $userId . '"}}',
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);

        json_decode($response->getBody()->getContents(), true);

    }

    public static function OneSignalApiUsed($userId = null, $heading = null, $message = null, $all = null)
    {
        $client = new Client();

        $headers = [
            'Authorization' => 'Key ' . config('oneSignal.auth_key'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ];

        if ($all === true) {

            $body = json_encode([
                'app_id' => config('oneSignal.app_id'),
                'contents' => ['en' => $message],
                'headings' => ['en' => $heading],
                'included_segments' => ['All']
            ]);

            $client->request('POST', 'https://api.onesignal.com/notifications?c=push', [
                'body' => $body,
                'headers' => $headers,
            ]);

        } else {
            $response = $client->request('GET', 'https://api.onesignal.com/apps/' . config('oneSignal.app_id') . '/users/by/external_id/' . $userId, [
                'headers' => $headers,
            ]);

            $response_body = json_decode($response->getBody()->getContents(), true);

            if (!empty($response_body['subscriptions'])) {

                $notification = Notification::notReadNotification();

                foreach ($response_body['subscriptions'] as $responseId) {
                    $body = json_encode([
                        'app_id' => config('oneSignal.app_id'),
                        'contents' => ['en' => $message],
                        'headings' => ['en' => $heading],
                        'include_player_ids' => [$responseId['id']]
                    ]);

                    $client->request('POST', 'https://api.onesignal.com/notifications?c=push', [
                        'body' => $body,
                        'headers' => $headers,
                        "ios_badgeType" => "Increase",
                        "ios_badgeCount" => $notification->count()
                    ]);
                }
            }
        }
    }

    public static function findEmailFromString($string)
    {

        preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $string, $matches);

        return ($matches[0] ?? null);

    }


    public static function packageLimitation($companyId = null)
    {

        $user = User::getSingleUser($companyId);

        if ($plan_id = $user->getsubscription()->first()) {

            $plan_id = $plan_id->stripe_price;

        } else {

            return Helpers::validationResponse('Please subscribe your plan first');

        }

        $limitations = Plan::singlePlan($plan_id);

        $getExistingMembers = B2BBusinessCandidates::where('business_id', $companyId)
            ->where('role', Admin::IS_TEAM_MEMBER)
            ->where('future_consideration', Admin::NOT_IN_FUTURE)
            ->where('is_permanently_deleted', 0)
            ->with(['users' => function ($q) {
                $q->where('step', 3);
            }])
            ->get();

        $existingMemberCounts = 0;

        foreach ($getExistingMembers as $member) {

            if (!empty($member->users)) {

                $existingMemberCounts += 1;

            }

        }

        if (($existingMemberCounts < (int)$limitations['no_of_team_members'])) {

            return true;

        } else {

            return false;

        }

    }

    public static function checkAndAddBonusCredits($user = null, $currentTime = null)
    {

        $credits_log = $user['credits_log'] + 1;

        if ($currentTime->diffInDays($user['last_login']) == 1) {

            $user->update(['credits_log' => $credits_log]);

        } elseif ($currentTime->diffInDays($user['last_login']) > 1) {

            $user->update(['credits_log' => 1]);

        } else {

            if ($user['credits_log'] == 5) {

                $user->update(['credits_log' => 0]);

                $point = match ($user['plan_name']) {
                    'Freemium' => 1,
                    'Core' => 2,
                    default => 3,
                };

                Point::addPoints($point);

                $message = 'THEY GOT ONE ' . $point . ' BONUS CREDIT.';

                Helpers::OneSignalApiUsed($user['id'], 'Credit Bonus', $message);

                Notification::createNotification('Credit Bonus', $message, $user['device_token'], $user['id'], 1, Admin::CREDIT_BONUS, Admin::B2C_NOTIFICATION);

            }
        }

    }

    public static function checkAndAddHumanOpPoints($user = null, $currentTime = null)
    {

        HumanOpPoints::createOrUpdateUserPoints($user, $currentTime);

    }

    public static function checkAndTakePerformanceLevel($user = null)
    {

        $points = HumanOpPoints::getUserPoints($user)['points'];

        if ($points > 0 || $points < 500)
        {
            GamificationPerformanceLevel::addFirstPerformanceLevel($user['id']);

        }elseif ($points > 499 || $points < 1000){

            GamificationPerformanceLevel::addSecondPerformanceLevel($user['id']);

        }

    }

}

