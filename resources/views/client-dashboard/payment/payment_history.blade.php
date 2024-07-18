@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card" >
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Payment History</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr>
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
                            <tr>
                                <td class="text-sm font-weight-normal">{{$history['assessments'] ? $history['assessments']['id'] : ''}} </td>
                                <td class="text-sm font-weight-normal">{{$history['assessments'] ? $history['assessments']['page'] === 0 ? 'Complete' : 'Incomplete' : ''}} </td>
                                <td class="text-sm font-weight-normal">{{$history['coupons'] ? $history['coupons']['coupon'] : 'Null'}} </td>
                                <td class="text-sm font-weight-normal">{{$history['discount_price']}}</td>
                                <td class="text-sm font-weight-normal">{{$history['total_price']}}</td>
                                <td class="text-sm font-weight-normal">{{\Carbon\Carbon::parse($history['created_at'])->format('M, d, Y h:i A')}}</td>
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
