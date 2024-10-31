<div>

    <div class="table-responsive table-orange-color">
        @include('layouts.message')
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>#sr</th>
                <th>Version</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($versions as $item)
                <tr class="table-text-color">
                    <td class="text-md font-weight-normal">{{$item['id']}} </td>
                    <td class="text-md font-weight-normal">{{$item['version']}} </td>
                    <td>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal"
                                wire:click="editVersion({{ $item['id'] }}, '{{ $item['version'] }}','{{ $item['details'] }}')"
                                data-bs-target="#versionModel"  style="background-color: #f2661c;" >
                            update
                        </button>
{{--                        <button class="btn btn-sm btn-danger text-white"--}}
{{--                                onclick="confirmBoxForPermanentDelete({{$tip['id'] ?? null}})" >--}}
{{--                            delete--}}
{{--                        </button>--}}
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        {{ $versions->links() }}
    </div>

    <!-- Coupon Discount -->
    @livewire('admin.version-control.create-version-control-form')
</div>

@push('js')

    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

@endpush
