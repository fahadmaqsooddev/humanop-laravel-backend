@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Edit Questions</h5>
                </div>
                <div class="card-body pt-0">
                    <form action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label text-white">Question</label>
                                <div class="input-group">
                                    <input style="background-color: #eaf3ff;" name="question"
                                           class="form-control" type="text" value="{{$question['question']}}"
                                           placeholder="Alec">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label mt-4 text-white">Gender</label>
                                <select class="form-control" name="gender">
                                    <option value="0">Male</option>
                                    <option value="1">Female</option>
                                    <option value="2">Male & Female</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-4 text-white">Active</label>
                                <select class="form-control" name="active">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-sm float-end mt-6 mb-0 text-white" style="background-color: #1b3a62">Update question</button>
                    </form>
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
