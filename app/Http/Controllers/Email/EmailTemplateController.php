<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\Email\EmailTemplate;

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
}
