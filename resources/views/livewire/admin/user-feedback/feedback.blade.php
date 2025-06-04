<div class="table-responsive table-orange-color">
    <div class="d-flex mt-4">

        <div class=" col-lg-4 ms-md-4 pe-md-4">
            <input type="text" name="name" wire:model.debounce="name"
                   class="form-control table-orange-color search-bar" placeholder="Search Name">
        </div>
    </div>
    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr class="table-text-color">
            {{--            <th>#</th>--}}
            <th>Username</th>
            <th>Feedback</th>
            <th>Submitted At</th>
            <th>Rating</th>
            <th>Image</th>
            <th>Approve</th>

        </tr>
        </thead>
        <tbody>
        @if(!isset($feedbacks[0]))
            <tr class="table-text-color">
                <td>No any feedback...</td>
            </tr>
        @endif
        <?php
        ?>


        @foreach($feedbacks as $key => $feedback)

            <tr class="table-text-color">
                <td class="text-md font-weight-normal">{{$feedback['user'] ? $feedback['user']['first_name'] . ' ' . $feedback['user']['last_name'] : ""}}</td>
                <td class="text-md font-weight-normal">
                    @if($feedback['comment'] && strlen($feedback['comment']) > 40)
                        {{substr($feedback['comment'], 0, 40)}}
                        &nbsp;&nbsp;<a data-bs-toggle="modal"
                                       data-bs-target="#viewQueryModal{{$feedback['id']}}"
                                       style="color: #1b3a62; cursor: pointer;"
                                       class="mt-2 mb-0">
                            view more...
                        </a>
                    @else
                        {{$feedback['comment'] ?? null}}
                    @endif
                </td>

                <td class="text-md font-weight-normal">{{$feedback['created_at'] ?? ""}}</td>

                <td>
                    @php
                        $rating = $feedback['rating'] ?? 0;
                    @endphp

                    @foreach(range(1,5) as $i)
                        <span class="fa-stack" style="width:1em">
                            <i class="far fa-star fa-stack-1x"></i>

                            @if($rating > 0)
                                <i class="fas fa-star fa-stack-1x" style="color: #1b3a62"></i>
                            @endif
                            @php $rating--; @endphp
                        </span>
                @endforeach

                <td class="text-md font-weight-normal " style="padding-top: 1rem !important;">
                    @if(!empty($feedback['image_id']))
                        <a href="{{ $feedback['photo_url']['url'] }}" target="_blank"
                           class="btn-sm"
                           style="background:#1b3a62;color:white;font-weight:bolder;border:none;">
                            View
                        </a>
                    @else
                        N/A
                    @endif

                </td>

                <td class="text-md font-weight-normal " style="padding-top: 1rem !important;">
                    @if($feedback['approve']==1)
                        <a class="btn-sm mt-2 mb-0"
                           style="background:#1b3a62;color:white;font-weight:bolder;border:none; pointer-events: none; cursor: not-allowed;">
                            Approved
                        </a>

                    @else
                        <a wire:click="approveFeedback({{$feedback['id']}})"
                           class=" btn-sm mt-2 mb-0"
                           style="background:#1b3a62;color:white;font-weight:bolder;border:none;cursor: pointer">
                            Approve
                        </a>
                    @endif
                    {{--                    <a wire:click="approveFeedback({{$feedback['id']}})"--}}
                    {{--                       class=" btn-sm mt-2 mb-0" style="background:#1b3a62;color:white;font-weight:bolder;border:none;">--}}
                    {{--                        Approve--}}
                    {{--                    </a>--}}
                </td>
            </tr>
            @if($feedback['comment'] && strlen($feedback['comment']) > 40)
                <div wire:ignore.self class="modal fade" id="viewQueryModal{{ $feedback['id'] }}" tabindex="-1"
                     role="dialog"
                     aria-labelledby="viewQueryModal{{ $feedback['id'] }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body" style=" border-radius: 9px">
                                <form wire:submit.prevent="">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" class="close modal-close-btn"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"
                                                        id="close-query-view-modal-{{ $feedback['id'] }}">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>

                                                <label class="form-label fs-6 "
                                                       style="font-size: 24px !important;font-weight: 800 !important;color: #1b3a62;"><strong>FeedBack:</strong></label>
                                                <span class="mt-3"
                                                      style="color: white;font-size: 20px;font-weight: 800;display: flex;">{{ $feedback['comment']  ?? null}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        {{--        @if(isset($approved_feedbacks))--}}
        {{--            @foreach($approved_feedbacks as $key => $approvedFeedBack)--}}
        {{--                <tr class="table-text-color">--}}
        {{--                    --}}{{--                <td class="text-md font-weight-normal">{{$key + 1}}</td>--}}
        {{--                    <td class="text-md font-weight-normal">{{$approvedFeedBack['user'] ? $approvedFeedBack['user']['first_name'] . ' ' . $approvedFeedBack['user']['last_name'] : ""}}</td>--}}
        {{--                    <td class="text-md font-weight-normal">--}}
        {{--                        @if($approvedFeedBack['comment'] && strlen($approvedFeedBack['comment']) > 40)--}}

        {{--                            {{substr($approvedFeedBack['comment'], 0, 40)}}--}}
        {{--                            &nbsp;&nbsp;<a data-bs-toggle="modal"--}}
        {{--                                           data-bs-target="#viewQueryModal{{'approved_'.$approvedFeedBack['id']}}"--}}
        {{--                                           style="color: #1b3a62; cursor: pointer;"--}}
        {{--                                           class="mt-2 mb-0">--}}
        {{--                                view more...--}}
        {{--                            </a>--}}
        {{--                        @else--}}

        {{--                            {{$approvedFeedBack['comment'] ?? null}}--}}
        {{--                        @endif--}}
        {{--                    </td>--}}
        {{--                    <td class="text-md font-weight-normal">--}}
        {{--                        <button class="rainbow-border-user-nav-btn btn-sm mt-2 mb-0">--}}
        {{--                            Approved--}}
        {{--                        </button>--}}
        {{--                    </td>--}}
        {{--                </tr>--}}

        {{--                @if($approvedFeedBack['comment'] && strlen($approvedFeedBack['comment']) > 40)--}}
        {{--                    <div wire:ignore.self class="modal fade" id="viewQueryModal{{'approved_'.$approvedFeedBack['id'] }}"--}}
        {{--                         tabindex="-1" role="dialog"--}}
        {{--                         aria-labelledby="viewQueryModal{{ 'approved_'.$approvedFeedBack['id'] }}" aria-hidden="true">--}}
        {{--                        <div class="modal-dialog modal-lg" role="document">--}}
        {{--                            <div class="modal-content">--}}
        {{--                                <div class="modal-body" style=" border-radius: 9px">--}}
        {{--                                    <form wire:submit.prevent="">--}}
        {{--                                        @csrf--}}
        {{--                                        <div class="card-body">--}}
        {{--                                            <div class="row">--}}
        {{--                                                <div class="col-12">--}}
        {{--                                                    <button type="button" class="close modal-close-btn"--}}
        {{--                                                            data-bs-dismiss="modal"--}}
        {{--                                                            aria-label="Close"--}}
        {{--                                                            id="close-query-view-modal-{{ 'approved_'.$approvedFeedBack['id'] }}">--}}
        {{--                                                        <span aria-hidden="true">&times;</span>--}}
        {{--                                                    </button>--}}

        {{--                                                    <label class="form-label fs-6 "--}}
        {{--                                                           style="font-size: 24px !important;font-weight: 800 !important;color: #1b3a62;"><strong>FeedBack:</strong></label>--}}
        {{--                                                    <span class="mt-3"--}}
        {{--                                                          style="color: white;font-size: 20px;font-weight: 800;display: flex;">{{ $approvedFeedBack['comment']  ?? null}}</span>--}}
        {{--                                                </div>--}}
        {{--                                            </div>--}}
        {{--                                        </div>--}}
        {{--                                    </form>--}}
        {{--                                </div>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                @endif--}}
        {{--            @endforeach--}}
        {{--        @endif--}}
        </tbody>
    </table>
    {{ $feedbacks->links() }}
</div>
