@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .table-text-color{
        color: #1c365e !important;
    }

    .dataTable-table th a{
        color: #1c365e !important;
    }
</style>
@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card" >
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">Payment History</h5>
                </div>
                <div class="table-responsive table-orange-color">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr class="table-text-color">
                            <th>Client Name</th>
                            <th>Assessment ID</th>
                            <th>Assessment Status</th>
                            <th>Coupon ID</th>
                            <th>Discount Payment</th>
                            <th>original Payment</th>
                            <th>Date & Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payment_history as $history)
                            <tr class="table-text-color">
                                <td class="text-md font-weight-normal">{{$history['users'] ? $history['users']['first_name'] . ' ' . $history['users']['last_name'] : ''}} </td>
                                <td class="text-md font-weight-normal">{{$history['assessments'] ? $history['assessments']['id'] : ''}} </td>
                                <td class="text-md font-weight-normal">{{$history['assessments'] ? $history['assessments']['page'] === 0 ? 'Complete' : 'Incomplete' : ''}} </td>
                                <td class="text-md font-weight-normal">{{$history['coupons'] ? $history['coupons']['coupon'] : 'Null'}} </td>
                                <td class="text-md font-weight-normal">{{$history['discount_price']}}</td>
                                <td class="text-md font-weight-normal">{{$history['total_price']}}</td>
                                <td class="text-md font-weight-normal">{{\Carbon\Carbon::parse($history['created_at'])->format('M, d, Y h:i A')}} (GMT)</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script>
        const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
            searchable: true,
            fixedHeight: true
        });
    </script>
@endpush
