<?php

namespace App\Http\Livewire\Admin\WebPage;

use App\Models\Admin\Pages\Page;
use Livewire\Component;
use Livewire\WithPagination;

class WebPageList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;

    protected $listeners = ['refreshPages' => '$refresh'];

    public function render()
    {
        $pages = Page::paginate($this->perPage);
        return view('livewire.admin.web-page.web-page-list', ['pages' => $pages]);
    }
}
