{{-- <div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 mb-4">
    <div class="card daily-tip-card"
         style="height: 530px;position: relative;background: #8BB1AB !important;border-radius: 40px !important;">
        <div class="card-body" data-step="2" style="cursor: pointer;">
            <div class="d-flex justify-content-center mb-3"
            >
                <h5 class="mb-0 text-center w-auto" data-bs-toggle="modal"
                    data-bs-target="#dailyTipModel"
                    style="border: 2px solid #F4E3C7;border-radius: 32px;padding: 10px 30px;color:#F4E3C7 ">
                    <strong>DAILY TIP</strong>
                    <i
                        class="fa-regular fa-circle-question fa-lg"
                        ></i>
                </h5>
            </div>
            <div class="description-container " style="height: 335px;text-align:left;">

                {{$hide_button = false}}

                @if($tip && !empty($tip['description']))
                    <h6 class="traitHeading ">{{$tip['title']}}</h6>
                    @if(strlen($tip['description']) > 290)
                        <?php
                        $hide_button = true;
                        ?>
                        <span id="daily-tip-text">
            <p style="font-size: 15px;" class="fw-bold text-color-blue">{!! substr($tip['description'], 0, 305) !!}</p>
            <a href="javascript:void(0)"
               onclick="showDailyTipCompleteText(`{{$tip['description']}}`, `{{$tip['userTip']['is_read'] ?? 1}}`)"
               style="color: #f2661c;">read more...</a>
        </span>
                    @else
                        <p style="font-size: 15px;"
                           class="fw-bold text-color-blue">{!! $tip['description'] !!}</p>
                    @endif
                @else
                    <p>Click here to:
                        <a href="{{ url('client/intro-assessment') }}" target="_self"
                           style="color: orange;">Take the Assessment</a>
                    </p>
                @endif

            </div>

            @if($tip)
                @if(($tip['userTip']['is_read'] ?? 1) == 0)
                    <div class="dailyTipButton">
                        <div class="d-flex justify-content-center mt-2" id="read_all_tip" >
                            <button
                                style="background-color: #f2661c;color: white !important;"
                                class="connection-btn btn-sm" id="daily_tip_read_btn"
                                data-bs-toggle="modal"
                                data-bs-target="#daily-tip-completed"
                                onclick="onDailyTipAllRead()">
                                Complete Daily Tip
                            </button>
                        </div>
                        <div class="d-flex justify-content-center mt-2">
                            <div id="remaining_time"></div>
                        </div>
                    </div>
                @else
                    <div class="d-flex justify-content-center mt-2" >
                        <button
                            style="background-color: #f2661c;color: white !important;"
                            class="connection-btn btn-sm">
                            Completed

                        </button>

                    </div>
                    <div class="d-flex justify-content-center mt-2">
                        <div id="remaining_time"></div>
                    </div>
                @endif
            @endif
        </div>
        <div class="image-container float-end">
            <img src="{{asset('assets/new-design/icon/dashboard/leave.svg')}}" width="270"
                 alt="Leaves">
        </div>
    </div>
</div>
@push('js')
    <script>
    const tipCreatedAt = @json($userTipCreatedAt);
    const createdAtDate = new Date(tipCreatedAt);

    if (isNaN(createdAtDate)) {
    console.error("Invalid date format:", tipCreatedAt);
    } else {
    const updateCountdown = () => {
    const now = new Date();
    const nextTipTime = new Date(createdAtDate.getTime() + 24 * 60 * 60 * 1000);
    let timeRemaining = nextTipTime - now;
    if (timeRemaining <= 0) {
        window.livewire.emit('updateTip');
        createdAtDate.setTime(now.getTime());
        timeRemaining = 24 * 60 * 60 * 1000;
    }
    const hours = Math.floor(timeRemaining / (1000 * 60 * 60));
    const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
    document.getElementById('remaining_time').innerHTML =
    `Next tip In: ${hours}:${minutes}:${seconds}`;
    };

    // Update the countdown every second
    setInterval(updateCountdown, 1000);
    updateCountdown();
    }
    </script>
@endpush --}}
