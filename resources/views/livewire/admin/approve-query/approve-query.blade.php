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
                <td class="text-sm font-weight-normal">

                    @if($query['question'] && strlen($query['question']['query']) > 40)

                        {{substr($query['question']['query'], 0, 40)}}
                        &nbsp;&nbsp;<a data-bs-toggle="modal"
                                       data-bs-target="#viewQueryModal{{$query['id']}}" style="color: #f2661c; cursor: pointer;"
                                       class="mt-2 mb-0">
                            view more...
                        </a>
                    @else

                        {{$query['question']['query'] ?? null}}
                    @endif
                </td>
                <td class="text-sm font-weight-normal">

                    @if(strlen($query['answer']) > 40)

                        {{substr($query['answer'], 0, 40)}}
                        &nbsp;&nbsp;<a data-bs-toggle="modal"
                           data-bs-target="#viewQueryModal{{$query['id']}}" style="color: #f2661c; cursor: pointer;"
                                       class="mt-2 mb-0">
                            view more...
                        </a>
                    @else

                        {{$query['answer']}}
                    @endif

                </td>
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

{{--            edit query answer--}}
            <livewire:admin.approve-query.edit-query :queryId="$query->id" :question="$query['question']"
                                                     :answer="$query['answer']" :wire:key="'edit-query-modal-'.$query->id">


{{--            view full question/answer--}}
            @if(strlen($query['answer']) > 30 || strlen($query['question']['query'] ?? null) > 30)
                <livewire:admin.approve-query.view-query :queryId="$query->id" :question="$query['question']"
                                                     :answer="$query['answer']" :wire:key="'view-query-modal-'.$query->id">
            @endif

        @endforeach
        </tbody>
    </table>

    {{$unApprovedQueries->links('pagination.table-pagination')}}

</div>
