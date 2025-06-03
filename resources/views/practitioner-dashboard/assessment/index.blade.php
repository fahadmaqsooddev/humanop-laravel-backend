@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')

    <style>
        body{
            background-color: #F3DEBA !important;
        }

        .text-color-blue{
            color: #1c365e !important;
        }
    </style>

    <div class="container-fluid">

        <div class="row mt-4">

            <div class="d-none d-sm-inline">

                @if(session('message'))
                    <div class="m-3 alert alert-success alert-dismissible fade show text-center" id="alert-success"
                         role="alert">
                        <span class="alert-text text-white">
                            {{ session('message') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                @endif

                <div class="w-100">
                    <!-- Card header -->
                    <div class="card-header table-header-text">
                        <h5 class="mb-0 text-color-blue">All Assessments</h5>
                    </div>
                    <div class="table-responsive table-orange-color table-orange-color w-100">
                        <table class="table table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-color-blue">Date & Time</th>
                                <th class="text-color-blue">Assessment Status</th>
                                <!-- <th class="text-color-blue">Practitioner</th> -->
                                <!-- <th class="text-color-blue">Reference Code</th> -->
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assessments as $assessment)
                                <tr>
                                    <td class="text-sm text-color-blue font-weight-normal">{{\Carbon\Carbon::parse($assessment['created_at'])->format('m/d/Y h:i A')}} (GMT)</td>
                                    <td class="text-sm text-color-blue font-weight-normal">{{$assessment['page'] === 0 ? 'Complete' : 'Incomplete'}} </td>
                                    <!-- <td class="text-sm text-color-blue font-weight-normal">N/A</td> -->
                                <!-- <td class="text-sm text-color-blue font-weight-normal">{{$assessment['id']}} </td> -->
                                    <td class="text-sm text-color-blue font-weight-normal"><a href="{{ route('practitioner_profile_overview',['id' => $assessment['id'] ]) }}" type="submit" style="background-color: #1B3A62 ; color: white" class="rainbow-border-user-nav-btn btn-sm float-end mt-2 mb-0">View</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-sm-none col-12">

                @foreach($assessments as $assessment)

                    <div class="card" style="background-color: white !important;">

                        <div class="card-body">

                            <h4 style="font-weight: bold;">Date : {{\Carbon\Carbon::parse($assessment['created_at'])->format('m/d/Y h:i A')}} (GMT)</h4>
                            <p style="color: black !important;">Status : Complete</p>
                            <a href="{{ route('practitioner_profile_overview',['id' => $assessment['id'] ]) }}" class="rainbow-border-user-nav-btn text-center btn-sm w-100 mx-auto mt-2 mb-0"
                               style="background-color: white !important; color: black !important;">
                                View
                            </a>

                        </div>

                    </div>

                @endforeach


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
