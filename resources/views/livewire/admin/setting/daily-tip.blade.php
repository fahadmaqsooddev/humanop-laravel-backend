<div>

    <div class="table-responsive table-orange-color">
        @include('layouts.message')
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>Code</th>
                <th>Title</th>
                <th>Subscription</th>
                <th>Interval of Life</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dailyTips as $tip)
                <tr class="table-text-color">
                    <td class="text-md font-weight-normal">{{$tip['code']}} </td>
                    <td class="text-md font-weight-normal">{{$tip['title']}} </td>
                    <td class="text-md font-weight-normal">{{$tip['subscription_type'] ?? 'Freemium'}} </td>
                    <td class="text-md font-weight-normal">{{$tip['interval_of_life'] ?? 'Interval of Life'}} </td>
                    <td>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal"
                                wire:click="editTip({{ $tip['id'] }}, `{{ $tip['code'] }}`, `{{ $tip['title'] }}`, `{{ $tip['description'] }}`,`{{$tip['interval_of_life']}}`,`{{$tip['subscription_type']}}`,`{{$tip['min_point']}}`,`{{$tip['max_point']}}`)"
                                data-bs-target="#dailyTipModel"  style="background-color: #f2661c;" >
                            update
                        </button>
                        <button class="btn btn-sm btn-danger text-white"
                                onclick="confirmBoxForPermanentDelete({{$tip['id'] ?? null}})" >
                            delete
                        </button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        {{ $dailyTips->links() }}
    </div>

    <!-- Coupon Discount -->
    @livewire('admin.setting.daily-tip-create-form')
</div>

@push('js')

    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script>


        function confirmBoxForPermanentDelete(tip_id){

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
                html: "<span style='color: white;'>Want to delete tip!</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if(result.isConfirmed){
                    window.livewire.emit('deleteTip', [tip_id])
                }
            })
        }

    </script>

@endpush
