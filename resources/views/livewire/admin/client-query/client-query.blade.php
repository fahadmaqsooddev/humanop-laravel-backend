<div class="table-responsive table-orange-color">
    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr>
            <th>User</th>
            <th>Email</th>
            <th>Query</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($queries as $query)
            <tr>
                <td class="text-sm font-weight-normal">{{$query['users'] ? $query['users']['first_name'] . ' ' . $query['users']['last_name'] : ""}}</td>
                <td class="text-sm font-weight-normal">{{$query['users']['email'] ?? null}}</td>
                <td class="text-sm font-weight-normal">{{$query['query']}}</td>
                <td class="text-sm font-weight-normal">
                    <a type="submit" data-bs-toggle="modal"
                       data-bs-target="#answerQueryModal{{$query['id']}}" class="rainbow-border-user-nav-btn btn-sm float-end mt-2 mb-0">Answer</a>
                </td>
            </tr>
            @livewire('admin.client-query.query-answer-form', ['queryId' => $query['id']], key($query->id))

        @endforeach
        </tbody>
    </table>

</div>
