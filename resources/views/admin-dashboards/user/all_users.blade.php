@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .borderAdd{
        border-bottom: none;
    }
</style>
@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <!-- Search Filters -->
                <div class="d-flex mt-4">
                    <div class="input-group ms-md-4 pe-md-4">
                        <span style="background-color: #0f1534;" class="input-group-text text-body"><i
                                class="fas fa-search" aria-hidden="true"></i></span>
                        <input type="text" style="background-color: #0f1534;" class="form-control text-white"
                               placeholder="Search Name">
                    </div>
                    <div class="input-group ms-md-4 pe-md-4">
                        <span style="background-color: #0f1534;" class="input-group-text text-body"><i
                                class="fas fa-search" aria-hidden="true"></i></span>
                        <input type="email" style="background-color: #0f1534;" class="form-control text-white"
                               placeholder="Search Email">
                    </div>
                    <div class="input-group ms-md-4 pe-md-4">
                        <select style="background-color: #0f1535" class="form-control text-white"
                                wire:model.defer="currentUser.age_range">
                            <option value="">Select Age</option>
                            <option value="5-6">5-6</option>
                            <option value="7-11">7-11</option>
                            <option value="12-15">12-15</option>
                            <option value="16-20">16-20</option>
                            <option value="21-29">21-29</option>
                            <option value="30-33">30-33</option>
                            <option value="34-42">34-42</option>
                            <option value="43-51">43-51</option>
                            <option value="52-65">52-65</option>
                            <option value="66-69">66-69</option>
                            <option value="70-74">70-74</option>
                            <option value="75-83">75-83</option>
                            <option value="84-93">84-93</option>
                            <option value="94-101">94&up</option>
                        </select>
                    </div>
                </div>
                <div class="pe-md-4">
                    <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white clickBtn"
                            style="background-color: #f2661c">Advance Search
                    </button>
                </div>
                <div class="d-flex mt-4 advanceFilterSearch d-none">
                    <div class="input-group ms-md-4 pe-md-4">
                        <table class="table table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center borderAdd">Styles</th>
                                <th class="text-center borderAdd">Green</th>
                                <th class="text-center borderAdd">Red</th>
                                <th class="text-center borderAdd">B-Green</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center borderAdd">SA</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">MA</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">JO</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">LU</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">VEN</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">MER</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">SO</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="input-group ms-md-4 pe-md-4">
                        <table class="table table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center borderAdd">Features</th>
                                <th class="text-center borderAdd">Green</th>
                                <th class="text-center borderAdd">Red</th>
                                <th class="text-center borderAdd">Yellow</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center borderAdd">DE</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">DOM</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">FE</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">GRE</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">LUN</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">NAI</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">NE</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">POW</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">SP</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">TRA</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">VAN</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            <tr>
                                <td class="text-center borderAdd">WIL</td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                                <td class="text-center borderAdd"><input type="checkbox" value="" class="form-check-input"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
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
                        @foreach($assessments as $assessment)
                            <tr>
                                <td class="text-sm font-weight-normal">{{$assessment['users']['first_name'].' '.$assessment['users']['last_name']}} </td>
                                <td class="text-sm font-weight-normal">{{\Carbon\Carbon::parse($assessment['users']['signup_date'])->format('Y/m/d')}}</td>
                                <td class="text-sm font-weight-normal">Null</td>
                                <td class="text-sm font-weight-normal">Null</td>
                                <td class="text-sm font-weight-normal">{{$assessment['users']['email']}}</td>
                                <td class="text-sm font-weight-normal"><a
                                        href="{{ route('admin_user_detail',['id' => $assessment['id']]) }}"
                                        type="submit" style="background-color: #f2661c; color: white"
                                        class="btn btn-sm float-end mt-2 mb-0">View</a></td>
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

        document.querySelector('.clickBtn').addEventListener('click', function () {
            const advanceFilterSearch = document.querySelector('.advanceFilterSearch');
            advanceFilterSearch.classList.toggle('d-none');
        });

    </script>
@endpush
