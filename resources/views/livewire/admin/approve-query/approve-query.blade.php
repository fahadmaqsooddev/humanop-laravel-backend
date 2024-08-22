<div class="table-responsive">
    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr>
            <th>Question</th>
            <th>Answer</th>
            <th>Edit</th>
            <th>Approve</th>
        </tr>
        </thead>
        <tbody>
        @foreach($unApprovedQueries as $query)
            <tr>
                <td class="text-sm font-weight-normal">{{$query['question']['query'] ?? null}}</td>
                <td class="text-sm font-weight-normal">{{$query['answer']}}</td>
                <td class="text-sm font-weight-normal">
                    <a type="submit" data-bs-toggle="modal"
                       data-bs-target="#editQueryModal{{$query['id']}}" style="background-color: #f2661c; color: white" class="btn btn-sm mt-2 mb-0">
                        Edit
                    </a>
                </td>
                <td class="text-sm font-weight-normal">
                    <a style="background-color: #f2661c; color: white" wire:click="approveAnswer({{$query['id']}})" class="btn btn-sm mt-2 mb-0">
                        Approve
                    </a>
                </td>
            </tr>

            <livewire:admin.approve-query.edit-query :queryId="$query->id" :question="$query['question']"
                                                     :answer="$query['answer']" :wire:key="'edit-query-modal-'.$query->id">

        @endforeach
        </tbody>
    </table>

    {{$unApprovedQueries->links('pagination.table-pagination')}}

</div>

{{--@push('js')--}}
{{--    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>--}}
{{--    <script>--}}
{{--        const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {--}}
{{--            searchable: true,--}}
{{--            fixedHeight: true--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
