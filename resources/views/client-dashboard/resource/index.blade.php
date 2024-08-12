@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')
    <div class="row">
        <div class="col-lg-7 position-relative z-index-2">
            <div class="mb-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column h-100">
                                <h2 class="font-weight-bolder mb-0">Results</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-sm-5">
                    <a data-bs-toggle="modal"
                       data-bs-target="#introduction">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Introduction</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a data-bs-toggle="modal"
                       data-bs-target="#trait">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Cycle of Life</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-5 col-sm-5 mt-sm-0 mt-4">
                    <a data-bs-toggle="modal"
                       data-bs-target="#cycle_of_life">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Traits</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a data-bs-toggle="modal"
                       data-bs-target="#motivation">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Motivational Drivers</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-5 col-sm-5">
                    <a data-bs-toggle="modal"
                       data-bs-target="#alchemy">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Alchemic Boundaries</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a data-bs-toggle="modal"
                       data-bs-target="#energy">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Energy Pool</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                <span class="text-success text-sm font-weight-bolder"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-5 col-sm-5">
                    <a data-bs-toggle="modal"
                       data-bs-target="#communication">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Communication Styles</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a data-bs-toggle="modal"
                       data-bs-target="#life">
                        <div class="card mt-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers" style="display: flex; align-items: center; height: 100%;">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Perception of Life</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                <span class="text-success text-sm font-weight-bolder"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{--   Introduction Models   --}}
    <div class="modal fade" id="introduction" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <video id="introduction-video" width="765" height="515" controls>
                        <source src="{{ asset('assets/video/HumanOp ULT Results Intro - Lisa Nelson.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
    {{--   Trait Models   --}}
    <div class="modal fade" id="trait" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <video id="trait-video" width="765" height="515" controls>
                        <source src="{{ asset('assets/video/Intro to Traits.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
    {{--   Cycle of Life Models   --}}
    <div class="modal fade" id="cycle_of_life" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <video id="cycle-of-life-video" width="765" height="515" controls>
                        <source src="{{ asset('assets/video/Intro to The Cycle of Life.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
    {{--   Motivational Drivers Models   --}}
    <div class="modal fade" id="motivation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <video id="motivation-video" width="765" height="515" controls>
                        <source src="{{ asset('assets/video/Intro to Motivation (Drivers).mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
    {{--   Alchemic Boundaries Models   --}}
    <div class="modal fade" id="alchemy" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <video id="alchemy-video" width="765" height="515" controls>
                        <source src="{{ asset('assets/video/Intro to Alchemy.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
    {{--   Communication Styles Models   --}}
    <div class="modal fade" id="communication" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <video id="communication-video" width="765" height="515" controls>
                        <source src="{{ asset('assets/video/Intro to Communication Style.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
    {{--   Energy Pool Models   --}}
    <div class="modal fade" id="energy" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <video id="energy-video" width="765" height="515" controls>
                        <source src="{{ asset('assets/video/Intro to Energy Pool.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
    {{--   Perception of Life Models   --}}
    <div class="modal fade" id="life" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <video id="life-video" width="765" height="515" controls>
                        <source src="{{ asset('assets/video/Perception of Life Intro.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4" style="display: none;">
        <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
    </div>

    <div class="row">
        <div class="col-12">
            <div id="globe" class="position-absolute end-0 top-10 mt-sm-3 mt-7 me-lg-7">
                <canvas width="700" height="600" class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modals = [
                {id: 'introduction', videoId: 'introduction-video'},
                {id: 'trait', videoId: 'trait-video'},
                {id: 'cycle_of_life', videoId: 'cycle-of-life-video'},
                {id: 'motivation', videoId: 'motivation-video'},
                {id: 'alchemy', videoId: 'alchemy-video'},
                {id: 'communication', videoId: 'communication-video'},
                {id: 'energy', videoId: 'energy-video'},
                {id: 'life', videoId: 'life-video'}
            ];

            modals.forEach(function(modal) {
                var modalElement = document.getElementById(modal.id);
                var videoElement = document.getElementById(modal.videoId);

                modalElement.addEventListener('hide.bs.modal', function () {
                    if (videoElement) {
                        videoElement.pause();
                    }
                });
            });
        });
    </script>
@endpush
