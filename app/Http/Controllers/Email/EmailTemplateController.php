<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\Email\EmailTemplate;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
class EmailTemplateController extends Controller
{

    public function index()
    {
        try {

            $templates = EmailTemplate::all();

            return view('admin.email_templates.index', compact('templates'));

        }catch (\Exception $exception)
        {
            return redirect()->route('email_template_index')->with($exception->getMessage());

        }
    }

    public function create()
    {
        try {

            return view('admin.email_templates.create');

        }catch (\Exception $exception)
        {
            return redirect()->route('email_template_index')->with('error', $exception->getMessage());

        }
    }

    public function store(EmailTemplateRequest $request)
    {

        try {
            $dataArry = $request->only($this->template->getFillable());
            EmailTemplate::createTemplate($dataArry);

            return redirect()->route('email_template_index')->with('success','Email Template Create Sucessfully');

        }catch (\Exception $exception)
        {
            return redirect()->route('email_template_index')->with('error', $exception->getMessage());
        }
    }

    public function view($id)
    {
        try {

            $template = EmailTemplate::singleTemplate($id);
            return view('admin.email_templates.view', compact('template'));

        }catch (\Exception $exception)
        {
            return redirect()->route('email_template_index')->with('error', $exception->getMessage());

        }
    }

    public function edit($id)
    {
        try {

            $template = EmailTemplate::singleTemplate($id);
            return view('admin.email_templates.edit', compact('template'));

        }catch (\Exception $exception)
        {
            return redirect()->route('email_template_index')->with('error', $exception->getMessage());

        }
    }

    public function update(EmailTemplateRequest $request, $id)
    {

        try {

            $dataArry = $request->only($this->template->getFillable());
            EmailTemplate::editTemplate($dataArry, $request->id);

            return redirect()->route('email_template_index')->with('success','Email Template Update Successfully');

        }catch (\Exception $exception)
        {
            return redirect()->route('email_template_edit')->with('error', $exception->getMessage());

        }
    }

    public function delete($id)
    {
        try {

            EmailTemplate::deleteTemplate($id);

            return redirect()->route('email_template_index')->with('success','Email Template Delete Successfully');

        }catch (Exception $exception)
        {
            return redirect()->route('email_template_index')->with('error', $exception->getMessage());
        }
    }


    public function b2CTemplates()
    {
        try{

            return view('admin-dashboards.email-template.email-template');

        }catch (\Exception $exception){
            return redirect()->route('email_template_index')->with('error', $exception->getMessage());
        }
    }


    public function getLogsActitvity()
    {

        $logs = Activity::latest()->get();

        foreach ($logs as $log) {
            // Attach user names for display without overwriting ID
            $log->subject_user = User::where('id', $log->subject_id)->select('first_name', 'last_name')->first();
            $log->causer_user = User::where('id', $log->causer_id)->select('first_name', 'last_name')->first();
        }

        return view('admin-dashboards.user-logs-activity.log-activity', compact('logs'));
    }


}
