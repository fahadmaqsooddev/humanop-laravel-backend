
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
{{-- {{dd($name)}} --}}
<div>
    <div>
        <div class="d-flex mt-4">
            <div class="input-group ms-md-4 pe-md-4">
                {{--        <span style="background-color: #eaf3ff;" class="input-group-text text-body"><i class="fas fa-search"--}}
                {{--                                                                                       aria-hidden="true"></i></span>--}}
                <input type="text" name="name" wire:model.debounce.500ms="name"
                       class="form-control table-orange-color search-bar" placeholder="Search Name">
                       {{-- <div>{{ $name }}</div> --}}
            </div>
            <div class="input-group ms-md-4 pe-md-4">
                {{--        <span style="background-color: #eaf3ff;" class="input-group-text text-body"><i class="fas fa-search"--}}
                {{--                                                                                       aria-hidden="true"></i></span>--}}
                <input type="email" name="email" wire:model.debounce="email"
                       class="form-control table-orange-color search-bar" placeholder="Search Email">
            </div>
            <div class="input-group ms-md-4 pe-md-4">
                <select class="form-control table-orange-color search-bar custom-text-dark" name="age"
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
            <button class=" btn-sm float-end mt-4 mb-4 text-white clickBtn" style="background:#1B3A62;color:white;font-weight:bolder;border:none;">
                Advance Filters
            </button>
        </div>
        <div class="advanceFilterSearch" style="padding-top: 50px !important;">
            <div class="row mt-4 ms-md-2 pe-md-2 {{ $style_color ? 'd-block' : 'd-none' }}">
                <div class="col-2">
                    <div class="card">
                        <div id="carouselExampleControls1" class="carousel slide" data-bs-interval="false">
                            <div class="carousel-inner">
                                @foreach(array_chunk(['style-0','style-1','style-2','style-3','style-4','style-5','style-6','style-7','style-8','style-9'], 2) as $index => $chunk)
                                    <div
                                        class="carousel-item {{ $index === ($style_carousel_index ? $style_carousel_index : 0) ? 'active' : '' }}">
                                        <div class="table-responsive table-header-text"
                                             style="margin-left: 35px !important;">
                                            <table class="table table-flush" style="border-collapse: separate">
                                                <thead class="thead-light">
                                                <tr>
                                                    @foreach($chunk as $select_num)
                                                        @php
                                                            $parts = explode('-', $select_num);
                                                            $number_part = $parts[1];
                                                        @endphp
                                                        <th class="text-center border cursor-pointer {{ $style_number === $select_num ? 'bg-yellow' : '' }}"
                                                            onclick="changeStyleCodeNumber('{{ $select_num }}', {{$index}})">
                                                            {{ strtoupper($number_part) }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleControls1" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleControls1" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-1 ms-md-2 pe-md-2">
                <div class="col-8">
                    @if(session('success'))
                        <div class="m-3 alert alert-success alert-dismissible fade show text-center" id="alert-success"
                             role="alert">
                        <span class="alert-text text-white">
                            {{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                    <div class="card">
                        <div class="table-responsive table-header-text">
                            <table class="table table-flush" style="border-collapse: separate">
                                <thead class="thead-light">
                                <tr>
                                    @foreach(['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'] as $select_style_code)
                                        @php
                                            $colorClass = 'bg-none'; // Default to no background color
                                            if (isset($selectedStyleCells[$select_style_code])) {
                                                switch ($selectedStyleCells[$select_style_code]) {
                                                    case 'green':
                                                        $colorClass = 'bg-green';
                                                        break;
                                                    case 'red':
                                                        $colorClass = 'bg-red';
                                                        break;
                                                    case 'border-green':
                                                        $colorClass = 'border-success';
                                                        break;
                                                    case 'bg-none':
                                                        $colorClass = 'bg-none';
                                                        break;
                                                }
                                            }
                                        @endphp
                                        <th class="text-center border text-color-blue cursor-pointer {{ $colorClass }}"
                                            onclick="changeStyleBackgroundColor(this, '{{ $select_style_code }}')">
                                            {{ strtoupper($select_style_code) }}
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 ms-md-2 pe-md-2 {{ $feature_color ? 'd-block' : 'd-none' }}">
                <div class="col-2">
                    <div class="card">
                        <div id="carouselExampleControls2" class="carousel slide" data-bs-interval="false">
                            <div class="carousel-inner">
                                @foreach(array_chunk(['feature-0','feature-1','feature-2','feature-3','feature-4','feature-5'], 2) as $index => $chunk)
                                    <div
                                        class="carousel-item {{ $index === ($feature_carousel_index ? $feature_carousel_index : 0) ? 'active' : '' }}">
                                        <div class="table-responsive table-header-text"
                                             style="margin-left: 35px !important;">
                                            <table class="table table-flush" style="border-collapse: separate">
                                                <thead class="thead-light">
                                                <tr>
                                                    @foreach($chunk as $select_num)
                                                        @php
                                                            $parts = explode('-', $select_num);
                                                            $number_part = $parts[1];
                                                        @endphp
                                                        <th class="text-center border cursor-pointer {{ $feature_number === $select_num ? 'bg-yellow' : '' }}"
                                                            onclick="changeFeatureCodeNumber('{{ $select_num }}', {{$index}})">
                                                            {{ strtoupper($number_part) }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleControls2" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleControls2" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-1 ms-md-2 pe-md-2">
                <div class="col-11">
                    <div class="card">
                        <div class="table-responsive table-header-text">
                            <table class="table table-flush" style="border-collapse: separate">
                                <thead class="thead-light">
                                <tr>
                                    @foreach(['de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil'] as $select_feature_code)
                                        @php
                                            $colorClass = 'bg-none'; // Default to no background color
                                            if (isset($selectedFeatureCells[$select_feature_code])) {
                                                switch ($selectedFeatureCells[$select_feature_code]) {
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
                                        <th class="text-center text-color-blue border cursor-pointer {{ $colorClass }}"
                                            onclick="changeFeatureBackgroundColor(this, '{{ $select_feature_code }}')">
                                            {{ strtoupper($select_feature_code) }}
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
    <div class="table-responsive w-100 table-orange-color mt-5">
        <table class="table table-flush" id="">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th>Name</th>
                <th>Date & Time</th>
                <th>Email</th>
                <th>Reset Assessment</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($assessments as $assessment)
                <tr class="text-color-blue">
                    <td class="text-md font-weight-normal">{{$assessment['users'] ? $assessment['users']['first_name'].' '.$assessment['users']['last_name'] : ""}} </td>
                    <td class="text-md font-weight-normal">
                           {{ $assessment['updated_at'] }} (GMT)
                    </td>
                    <td class="text-md font-weight-normal">{{$assessment['users']['email'] ?? null}}</td>
                    <td class="text-md font-weight-normal">
                        <div class="form-check form-switch mb-0 d-flex justify-content-center">
                            @php
                                if($assessment->reset_assessment == 1)
                                    $assessmentStatus = true;
                                else
                                    $assessmentStatus = false;
                            @endphp
                            <input class="form-check-input"
                                   onchange="changeResetAssessmentStatus({{$assessment['id']}}, this , event)"
                                   name="practitioner"
                                   type="checkbox"
                                   @checked($assessmentStatus)
                                   @if($assessmentStatus) disabled @endif>
                        </div>
                    </td>
                    <td class="text-md font-weight-normal"><a
                            href="{{ route('admin_user_answer',['id' => $assessment['id']]) }}" type="submit"
                            class=" btn-sm float-end mt-2 mb-0" style="background:#1B3A62;color:white;font-weight:bolder;border:none;">View Answers</a>
                    </td>
                    <td class="text-md font-weight-normal"><a
                            href="{{ route('admin_profile_overview',['id' => $assessment['id']]) }}" type="submit"
                            class=" btn-sm float-end mt-2 mb-0" style="background:#1B3A62;color:white;font-weight:bolder;border:none;">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $assessments->links() }}
    </div>
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
    <script>
        function changeResetAssessmentStatus(id, checkbox, e) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-primary m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to Reset Assessment Status</span>",
                showCancelButton: true,
                confirmButtonText: 'Reset',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('resetAssessment', id)
                }
            })

        }
    </script>

@endpush
