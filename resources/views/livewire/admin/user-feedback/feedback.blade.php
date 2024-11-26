<div class="table-responsive table-orange-color">
    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr class="table-text-color">
{{--            <th>#</th>--}}
            <th>Username</th>
            <th>Feedback</th>
            <th>Approve</th>

        </tr>
        </thead>
        <tbody>
        @if(!isset($feedbacks[0]))
            <tr class="table-text-color">
                <td>No any feedback...</td>
            </tr>
        @endif
        @foreach($feedbacks as $key => $feedback)
            <tr class="table-text-color">
{{--                <td class="text-md font-weight-normal">{{$key + 1}}</td>--}}
                <td class="text-md font-weight-normal">{{$feedback['user'] ? $feedback['user']['first_name'] . ' ' . $feedback['user']['last_name'] : ""}}</td>
                <td class="text-md font-weight-normal">{{$feedback['comment']}}</td>
                <td class="text-md font-weight-normal">
                    <a wire:click="approveFeedback({{$feedback['id']}})" class="rainbow-border-user-nav-btn btn-sm mt-2 mb-0">
                        Approve
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
