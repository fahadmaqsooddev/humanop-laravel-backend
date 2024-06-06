<div class="table-responsive">
    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr>
            <th>Name</th>
            <th>Title</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td class="text-sm font-weight-normal">{{ $page->name }}</td>
                <td class="text-sm font-weight-normal">{{ $page->title }}</td>
                <td class="text-sm font-weight-normal">
                    <button data-bs-toggle="modal"
                            href="#page-{{ $page->id }}"
                            class="btn btn-sm updateBtn float-end mt-2 mb-0">Edit
                    </button>
                </td>
            </tr>
            @livewire('admin.web-page.web-page-form', ['page' => $page], key($page->id))
        @endforeach
        </tbody>
    </table>
    {{ $pages->links() }}
</div>
