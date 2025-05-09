<div class="table-responsive table-orange-color">
    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr class="table-text-color">
            <th>User</th>
            <th>Email</th>
            <th>Query</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($queries as $query)
            <tr class="table-text-color">
                <td class="text-md font-weight-normal">{{$query['users'] ? $query['users']['first_name'] . ' ' . $query['users']['last_name'] : ""}}</td>
                <td class="text-md font-weight-normal">{{$query['users']['email'] ?? null}}</td>
                <td class="text-md font-weight-normal">{{$query['query']}}</td>
                <td class="text-md font-weight-normal">
                    <a type="submit" data-bs-toggle="modal"
                       data-bs-target="#answerQueryModal{{$query['id']}}" class=" btn-sm float-end mt-2 mb-0" style="background:#f2661c !important;color:white;font-weight:bolder;border:none;">Answer</a>
                </td>
            </tr>
            @livewire('admin.client-query.query-answer-form', ['queryId' => $query['id']], key($query->id))

        @endforeach
        
        
    </tbody>
</table>
{{ $queries->links('pagination.table-pagination') }}

</div>
