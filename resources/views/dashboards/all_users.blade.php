@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

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
                            <th>Name</th>
                            <th>Date & Time</th>
                            <th>Practitioner</th>
                            <th>Project</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                        </tr>
                        <tr>
                            <td class="text-sm font-weight-normal">Tiger Nixon</td>
                            <td class="text-sm font-weight-normal">2011/04/25</td>
                            <td class="text-sm font-weight-normal">System Architect</td>
                            <td class="text-sm font-weight-normal">Edinburgh</td>
                            <td class="text-sm font-weight-normal">user@gmail.com</td>
                            <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}" type="submit" style="background-color: #582CFF; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
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
