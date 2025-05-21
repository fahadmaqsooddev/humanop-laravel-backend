
@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])


<style>
    .pagination {
        float: right;
        margin-right: 24px;
    }

    .page-link {
        background: none !important;
    }

    .page-link:hover {
        background: #f2661c !important;
        color: white !important;
    }

    .dataTable-pagination-list .active a {
        background: #f2661c !important;
        color: white !important;
        border-color: #f2661c !important;
    }

</style>


@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">Questions / Answers</h5>
                </div>
                <div class="table-responsive table-orange-color">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                            <tr class="text-color-blue">
                                <th class="text-color-blue">Question</th>
                                <th class="text-color-blue">Answer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assessment_details as $index => $assessment)
                                <tr class="text-color-blue">
                                    <td class="text-sm font-weight-normal">{{ $index + 1 }} -
                                        {{ $assessment['question'] }}</td>

                                    <td class="text-sm font-weight-normal">
                                        @if (is_array($assessment['answer']))
                                            <ul class="mb-0 pl-3">

                                                @foreach ($assessment['answer'] as $answer)
                                                    <li>{{ $answer }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ $assessment['answer'] }}
                                        @endif
                                    </td>
                                    
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
