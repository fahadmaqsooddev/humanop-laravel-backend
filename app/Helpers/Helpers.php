<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Helpers
{
    # ====================================
    # *            Responses             *
    # ====================================

    public static function successResponse($message, $data = [], $pagination = false)
    {
        if ($pagination){
            return response()->json(['status' => true, 'message' => $message, 'result' => $data], config('httpstatuscodes.ok_status'));
        }else{
            return response()->json(['status' => true, 'message' => $message, 'result' => array('data' => $data)],config('httpstatuscodes.ok_status'));
        }
    }

    public static function validationResponse($errors, $request = null)
    {
        return response(['status' => false, 'message' => $errors],config('httpstatuscodes.not_acceptable_status'));
    }

    public static function upgradePackageResponse($errors, $request = null)
    {
        return response(['status' => false, 'message' => $errors],config('httpstatuscodes.package_upgrade_required'));
    }

    public static function unauthResponse($errors)
    {
        return response(['status' => false, 'message' => $errors],config('httpstatuscodes.unauthorized_status'));
    }

    public static function forbiddenResponse($errors)
    {
        return response(['status' => false, 'message' => $errors],config('httpstatuscodes.forbidden_status'));
    }

    public static function notFoundResponse($errors)
    {
        return response(['status' => false, 'message' => $errors],config('httpstatuscodes.not_found_status'));
    }

    public static function serverErrorResponse($errors)
    {
        $message = 'Something went wrong. Please contact technical support';
        if (config('app.env') == 'production')
        {
            return response()->json(['status' => false, 'message' => $message], config('httpstatuscodes.internal_server_error'));
        }else{
            return response()->json(['status' => false, 'message' => $message, 'errors' => $errors],config('httpstatuscodes.internal_server_error'));
        }
    }

    public static function pagination($all, $pagination = false, $per_page = null)
    {
        if ($pagination && ($pagination === true || $pagination === "true"))
        {
            if ($per_page)
            {
                $all = $all->paginate($per_page);
            }else{

                $all = $all->paginate(10);
            }

            return $all;

        }else{
            return $all->get();
        }
    }
    public static function paginateForCollectionsAndArrays($items, $perPage = 1, $page = 1, $options = [], $pagination = false)
    {
        $page = $page == "null" ? 1 : $page; //This line we add because front-end team send page = "null" in payload which cause error.

        $perPage = $perPage ?: 10;

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        if ($pagination && ($pagination === true || $pagination === "true")){

            $currentItems = array_slice($items->toArray(), $perPage * ($page - 1), $perPage);

            return new LengthAwarePaginator($currentItems, $items->count(), $perPage, $page, ['path' => LengthAwarePaginator::resolveCurrentPath()]);

        }else{

            return $items;
        }
    }

    public static function paginationForGroupByCollection($all, $pagination = false, $per_page = null, $column_name = null){

        if ($pagination && ($pagination === true || $pagination === "true")) {

            $all = $all->get()->groupBy(function ($val) use ($column_name) {

                return Carbon::parse($val->$column_name)->format('F Y');

            })->toArray();

            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            $currentItems = array_slice($all, $per_page * ($currentPage - 1), $per_page);

            return new LengthAwarePaginator($currentItems, count($all), $per_page, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);
        }else{

            $all = $all->get()->groupBy(function ($val) use ($column_name) {

                return Carbon::parse($val->$column_name)->format('F Y');

            });

            return $all;
        }
    }

    public static function removeStorageUploadsAndThumbnails($object = null){

        if ($object){

            if (File::exists($object->path) || File::exists( base_path() . config('urls.thumbnail') . $object->pre_fill . $object->name)){

                File::delete($object->path);

                File::delete( base_path() . config('urls.thumbnail') . $object->pre_fill . $object->name);
            }
        }

    }

    public static function getUser(){

        return Auth::guard('api')->user();

    }

}

