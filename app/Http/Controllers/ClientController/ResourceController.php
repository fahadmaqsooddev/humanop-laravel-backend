<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\PermissionResource;
use App\Helpers\Helpers;
class ResourceController extends Controller
{

    public function resource()
    {
        try {
            $user = Helpers::getWebUser();
            $permission = PermissionResource::getPermission($user['plan_name']);

            return view('client-dashboard.resource.index');

        }catch (\Exception $exception)
        {
            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

}
