@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card" >
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Users</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-sm font-weight-normal">1-What was the hair color closest to your natural hair color at 21?</td>
                                <td class="text-sm font-weight-normal">	Brown</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">2-Which eye color is closest to yours?</td>
                                <td class="text-sm font-weight-normal">Golden Brown</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">3-Are your eyes?</td>
                                <td class="text-sm font-weight-normal">Medium</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">4-Have you been told your eyes sparkle or you have a twinkle in your eye?</td>
                                <td class="text-sm font-weight-normal">no</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">5-Is your nose:</td>
                                <td class="text-sm font-weight-normal">Turned up</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">6-Which is your natural body shape?</td>
                                <td class="text-sm font-weight-normal">	Brown</td>
                            </tr>

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
