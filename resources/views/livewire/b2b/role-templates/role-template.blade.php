<div>

    <div class="table-responsive table-orange-color">
        @include('layouts.message')
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color ">
                <th>Id</th>
                <th>Code</th>
                <th>Role Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dailyTemplates as $template)
                <tr class="table-text-color ">
                    <td class="text-md font-weight-normal">{{$template['id']}} </td>
                    <td class="text-md font-weight-normal">{{$template['code']}} </td>
                    <td class="text-md font-weight-normal">{{$template['role_name'] ?? ''}} </td>

                    <td>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal"
                                wire:click="editTemplate({{ $template['id'] }}, `{{ $template['code'] }}`, `{{ $template['title'] }}`, `{{ $template['description'] }}`,`{{$template['role_name']}}`,`{{$template['subscription_type']}}`,`{{$template['min_point']}}`,`{{$template['max_point']}}`)"
                                data-bs-target="#roleModel"  style="background-color: #f2661c;" >
                            update
                        </button>
                        <button class="btn btn-sm btn-danger text-white"
                                onclick="confirmBoxForPermanentDelete({{$template['id'] ?? null}})" >
                            delete
                        </button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
{{--        {{ $dailyTemplates->links() }}--}}
    </div>

    <!-- Coupon Discount -->
    @livewire('b2b.role-templates.role-template-create-form')
</div>

@push('js')

    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script>


        function confirmBoxForPermanentDelete(template_id){

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                    cancelButton:  'btn bg-gradient-primary m-2',
                },
                buttonsStyling: false,
                background : '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to delete template!</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if(result.isConfirmed){
                    window.livewire.emit('deleteTemplate', [template_id])
                }
            })
        }

    </script>

@endpush
