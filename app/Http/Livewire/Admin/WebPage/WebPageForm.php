<?php

namespace App\Http\Livewire\Admin\WebPage;
use App\Models\Admin\Pages\Page;
use Livewire\Component;

class WebPageForm extends Component
{

    public $page;

    public function mount($page)
    {

        $this->page = $page->toArray();

    }

    public function updateWebPage()
    {
        try {

            dd($this->page);

            $page = $this->only(['page']);

            Page::updatePage($page['page']);

            $this->emit('refreshPages');

            session()->flash('success', 'Web Page updated successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function render()
    {
        return view('livewire.admin.web-page.web-page-form');
    }
}
