<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use Illuminate\Http\Request;

class CodeController extends Controller
{

    protected $code = null;

    public function __construct(CodeDetail $code)
    {
        $this->code = $code;
    }

    public function ManageCode()
    {
        try {

            $codes = CodeDetail::allCodes();

            return view('admin-dashboards.manage-codes.index', compact('codes'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }

    public function editCode($id)
    {
        try {

            $code = CodeDetail::getSingleCode($id);

            return view('admin-dashboards.manage-codes.edit', compact('code'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }

}
