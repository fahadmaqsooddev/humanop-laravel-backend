<?php

namespace App\Http\Livewire\Admin\EmailTemplate;

use App\Models\Email\EmailTemplate;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class GetB2cEmailTemplate extends Component
{

    use WithFileUploads;
    public $body,$title,$editTemplateId,$subject;

    public function EditTemplate($id)
    {
        $editFormat=  DB::table('email_templates')->where('id', $id)->first();
        $this->editTemplateId=$id;
        $this->body=$editFormat->body;
        $this->subject=$editFormat->subject;

        $this->title=$editFormat->name;

        $this->dispatchBrowserEvent('update-editors', [
            'body' => $this->body
        ]);

    }

    public function UpdateTemplate()
    {

        try{
//            if (isset($this->logo)) {
//                $upload_id = Upload::uploadFile($this->logo, 200, 200, 'base64Image', 'png', true);
//
//
//            }
            DB::table('email_templates')->where('id', $this->editTemplateId)->update([
                'body' => $this->body,
//                'logo_upload_id'=>$upload_id ?? null,
                'name' => $this->title,
                'subject'=>$this->subject,
            ]);

            $this->reset(['body', 'title', 'editTemplateId','subject']);

            $this->dispatchBrowserEvent('closeModal');

            session()->flash('success', 'Template updated successfully.');
        }catch (\Exception $e){
            session()->flash('error', $e->getMessage());
        }

    }
    public function render()
    {

        $b2cTemplates=  EmailTemplate::getTemplatesForB2C();

        return view('livewire.admin.email-template.get-b2c-email-template',compact('b2cTemplates'));
    }
}
