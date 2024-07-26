<div>
    <!-- Search Filters -->
    <div>
        <div class="d-flex mt-4">
            <div class="input-group ms-md-4 pe-md-4">
        <span style="background-color: #0f1534;" class="input-group-text text-body"><i class="fas fa-search"
                                                                                       aria-hidden="true"></i></span>
                <input type="text" style="background-color: #0f1534;" name="name" wire:model.debounce="name"
                       class="form-control text-white" placeholder="Search Name">
            </div>
            <div class="input-group ms-md-4 pe-md-4">
        <span style="background-color: #0f1534;" class="input-group-text text-body"><i class="fas fa-search"
                                                                                       aria-hidden="true"></i></span>
                <input type="email" style="background-color: #0f1534;" name="email" wire:model.debounce="email"
                       class="form-control text-white" placeholder="Search Email">
            </div>
            <div class="input-group ms-md-4 pe-md-4">
                <select style="background-color: #0f1535" class="form-control text-white" name="age"
                        wire:model.debounce="age">
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
            <button class="btn btn-sm float-end mt-4 mb-4 text-white clickBtn" style="background-color: #f2661c">Advance
                Filters
            </button>
        </div>
        <div class="advanceFilterSearch d-none" style="padding-top: 50px !important;">
            <div class="row mt-4 ms-md-2 pe-md-2">
                <div class="col-8">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-flush" style="border-collapse: separate">
                                <thead class="thead-light">
                                <tr>
                                    @foreach(['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'] as $select_code)
                                        @php
                                            $colorClass = 'bg-none'; // Default to no background color
                                            if (isset($selectedCells[$select_code])) {
                                                switch ($selectedCells[$select_code]) {
                                                    case 'green':
                                                        $colorClass = 'bg-green';
                                                        break;
                                                    case 'red':
                                                        $colorClass = 'bg-red';
                                                        break;
                                                    case 'border-green':
                                                        $colorClass = 'border-success';
                                                        break;
                                                    case 'none':
                                                        $colorClass = 'bg-none';
                                                        break;
                                                }
                                            }
                                        @endphp
                                        <th class="text-center border cursor-pointer {{ $colorClass }}"
                                            onclick="changeStyleBackgroundColor(this, '{{ $select_code }}')">
                                            {{ strtoupper($select_code) }}
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2 ms-md-2 pe-md-2">
                <div class="col-11">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-flush" style="border-collapse: separate">
                                <thead class="thead-light">
                                <tr>
                                    @foreach(['de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil'] as $select_code)
                                        @php
                                            $colorClass = 'bg-none'; // Default to no background color
                                            if (isset($selectedCells[$select_code])) {
                                                switch ($selectedCells[$select_code]) {
                                                    case 'green':
                                                        $colorClass = 'bg-green';
                                                        break;
                                                    case 'red':
                                                        $colorClass = 'bg-red';
                                                        break;
                                                    case 'yellow':
                                                        $colorClass = 'bg-yellow';
                                                        break;
                                                    case 'none':
                                                        $colorClass = 'bg-none';
                                                        break;
                                                }
                                            }
                                        @endphp
                                        <th class="text-center border cursor-pointer {{ $colorClass }}"
                                            onclick="changeFeatureBackgroundColor(this, '{{ $select_code }}')">
                                            {{ strtoupper($select_code) }}
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive w-100">
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
                            href="{{ route('admin_user_detail',['id' => $assessment['id']]) }}" type="submit"
                            style="background-color: #f2661c; color: white"
                            class="btn btn-sm float-end mt-2 mb-0">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{--        {{ $assessments->links() }}--}}
    </div>
</div>
