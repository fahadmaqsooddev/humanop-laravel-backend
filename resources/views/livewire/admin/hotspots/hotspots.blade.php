<div>
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="text-color-blue text-center">
                <th>#</th>
                <th>Hot Spot</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($hotspots as $key => $hotspot)
                <tr class="text-color-blue">
                    <td class="text-sm  font-weight-normal">{{ $key + 1 }}</td>
                    <td class="text-sm font-weight-normal">{{ $hotspot['hotspot'] }}</td>
                    <td class="text-sm font-weight-normal">{{ $hotspot['name'] }}</td>
                    <td class="text-sm font-weight-normal">
                        <a
                            href="{{ route('admin_edit_hotspot', $hotspot['id']) }}"
                            class="btn-sm mt-2 mb-0"
                            style="background:#1b3a62;color:white;font-weight:bolder;"
                        >
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>


@push('js')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script>
        const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
            searchable: true,
            fixedHeight: true
        });
    </script>
@endpush
