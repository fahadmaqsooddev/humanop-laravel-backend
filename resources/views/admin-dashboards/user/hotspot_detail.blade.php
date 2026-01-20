@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .hotspot-box {
        border: 1px solid #e5e7eb;
        padding: 12px;
        border-radius: 8px;
        background: #f9fafb;
        text-align: center;
        font-weight: 500;
        height: 100%;
    }
    .hotspot-box .fw-bold {
        color: #000 !important;
    }
    .page-heading {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #000;
        text-align: center;
    }
    .tab-content {
        margin-top: 20px;
    }
</style>
@endpush

<div class="container-fluid mt-4">

    <!-- Top Grid Section (HotSpot Cards) -->
    <div class="page-heading">HotSpot Detail</div>
    <div class="row g-4 mt-3">
        @foreach($assessments as $key => $assessment)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header fw-bold text-center" style="color: #000;">
                        Assessment {{ $key + 1 }}
                        <br>
                        <small class="text-muted">{{ $assessment['date'] ?? '' }}</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($assessment['hotspots'] as $hotspot)
                                <div class="col-md-12">
                                    <div class="hotspot-box">
                                        <div class="fw-bold" style="color: #000;">
                                            HotSpot Name: {{ $hotspot['name'] }}
                                        </div>
                                        <small class="text-muted">
                                            HotSpot Priority: {{ $hotspot['priority'] }} <br>
                                            Shift: {{ $hotspot['shift_interval'] }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tabs Section -->
    <div class="page-heading mt-5">Additional Analysis</div>

    <ul class="nav nav-tabs mb-3" id="gridDetailTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="trend-tab" data-bs-toggle="tab" data-bs-target="#trend" type="button" role="tab">Trend Direction</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="interval-tab" data-bs-toggle="tab" data-bs-target="#interval" type="button" role="tab">Interval Shift</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="delta-tab" data-bs-toggle="tab" data-bs-target="#delta" type="button" role="tab">Hotspot Delta</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="authentic-tab" data-bs-toggle="tab" data-bs-target="#authentic" type="button" role="tab">Authentic Shift</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="analysis-tab" data-bs-toggle="tab" data-bs-target="#analysis" type="button" role="tab">Shai Analysis Prompt Context</button>
        </li>
    </ul>

    <div class="tab-content" id="gridDetailTabsContent">

        <!-- Trend Direction -->
        <div class="tab-pane fade show active" id="trend" role="tabpanel">
            <div class="mt-3">
                @foreach($trendComparisons as $trend)
                    <div class="alert 
                        @if($trend['trend'] == 'Positive Trend') alert-success
                        @elseif($trend['trend'] == 'Negative Trend') alert-danger
                        @else alert-warning @endif">
                        <strong>{{ $trend['trend'] }}:</strong> {{ $trend['message'] }} 
                        <br>
                        <small>
                            Assessment {{ $trend['previous_assessment_number'] }} ({{ $trend['date_previous'] }})
                            → Assessment {{ $trend['current_assessment_number'] }} ({{ $trend['date_current'] }})
                        </small>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Interval Shift -->
        <div class="tab-pane fade" id="interval" role="tabpanel">
            <div class="mt-3">
                @foreach($trendComparisons as $trend)
                    @if($trend['interval_shift'])
                        <div class="alert alert-info">
                            <strong>Interval Shift Detected:</strong>
                            Assessment {{ $trend['previous_assessment_number'] }} ({{ $trend['date_previous'] }})
                            → Assessment {{ $trend['current_assessment_number'] }} ({{ $trend['date_current'] }})
                            <br>
                            <ul>
                                @php
                                    $currShifts = collect($assessments[$trend['current_assessment_number'] - 1]['hotspots'])->pluck('shift_interval', 'name');
                                    $prevShifts = collect($assessments[$trend['previous_assessment_number'] - 1]['hotspots'])->pluck('shift_interval', 'name');
                                @endphp
                                @foreach($currShifts as $name => $currShift)
                                    @if(isset($prevShifts[$name]) && $prevShifts[$name] !== $currShift)
                                        <li>{{ $name }}: Previous Shift → {{ $prevShifts[$name] }}, Current Shift → {{ $currShift }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-secondary">
                            No Interval Shift between Assessment {{ $trend['previous_assessment_number'] }} → Assessment {{ $trend['current_assessment_number'] }}.
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Hotspot Delta -->
        <div class="tab-pane fade" id="delta" role="tabpanel">
            <div class="mt-3">
                @foreach($trendComparisons as $trend)
                    @php
                        $currHotspots = collect($assessments[$trend['current_assessment_number'] - 1]['hotspots'])->pluck('priority', 'name');
                        $prevHotspots = collect($assessments[$trend['previous_assessment_number'] - 1]['hotspots'])->pluck('priority', 'name');

                        $resolved = $prevHotspots->keys()->diff($currHotspots->keys());
                        $new = $currHotspots->keys()->diff($prevHotspots->keys());
                        $persistent = $currHotspots->keys()->intersect($prevHotspots->keys());
                    @endphp

                    <div class="card mb-3">
                        <div class="card-header fw-bold" style="color:black">
                            Hotspot Delta: Assessment {{ $trend['previous_assessment_number'] }} → Assessment {{ $trend['current_assessment_number'] }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Resolved</h6>
                                    <div class="p-2 bg-success text-white rounded">
                                        @if($resolved->isEmpty())
                                            <p class="mb-0">None</p>
                                        @else
                                            <ul class="mb-0">
                                                @foreach($resolved as $name)
                                                    <li>{{ $name }} (#{{ $prevHotspots[$name] }})</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6>New</h6>
                                    <div class="p-2 bg-danger text-white rounded">
                                        @if($new->isEmpty())
                                            <p class="mb-0">None</p>
                                        @else
                                            <ul class="mb-0">
                                                @foreach($new as $name)
                                                    <li>{{ $name }} (#{{ $currHotspots[$name] }})</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6>Persistent</h6>
                                    <div class="p-2 bg-warning text-dark rounded">
                                        @if($persistent->isEmpty())
                                            <p class="mb-0">None</p>
                                        @else
                                            <ul class="mb-0">
                                                @foreach($persistent as $name)
                                                    <li>{{ $name }} (#{{ $currHotspots[$name] }})</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Authentic Shift -->
        <!-- Authentic Shift -->
        <div class="tab-pane fade" id="authentic" role="tabpanel">
            <div class="mt-3">
                @foreach($trendComparisons as $trend)
                    <div class="card mb-3">
                        <div class="card-header fw-bold" style="color:black">
                            Authentic Shifts: Assessment {{ $trend['previous_assessment_number'] }} → Assessment {{ $trend['current_assessment_number'] }}
                        </div>
                        <div class="card-body" style="color:black">

                            <!-- Interval of Life Shift -->
                            <div class="mb-3">
                                <strong>Interval of Life Shift:</strong>
                                @if($trend['interval_shift'])
                                    <span class="badge bg-success">Detected</span>
                                @else
                                    <span class="badge bg-secondary">No Shift</span>
                                @endif
                            </div>

                            <!-- Flattened Authentic Element Shifts -->
                            @if(!empty($trend['authentic_element_shifts']))
                                <ul class="list-group">
                                    @foreach($trend['authentic_element_shifts'] as $shift)
                                        <li class="list-group-item">
                                            <strong>{{ $shift['category'] ?? 'Category' }}</strong>:
                                            {{ $shift['prev_value'] ?? 'None' }} 
                                            → {{ $shift['curr_value'] ?? 'None' }}
                                            <br>
                                            <small class="text-muted">{{ $shift['description'] ?? '' }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No Authentic Shifts detected for this assessment.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- HAI Analysis Prompt Context -->
        <div class="tab-pane fade" id="analysis" role="tabpanel">
            <div class="mt-3">
                @foreach($trendComparisons as $trend)
                    <div class="card mb-3">
                        <div class="card-header fw-bold" style="color:black">
                            HAI Analysis Prompt Context: Assessment {{ $trend['previous_assessment_number'] }} → Assessment {{ $trend['current_assessment_number'] }}
                        </div>
                        <div class="card-body" style="color:black">
                            <ul>
                                <li><strong>Primary Win:</strong> {{ $trend['hai_analysis_prompt_context']['primary_win'] ?? 'None' }}</li>
                                <li><strong>Current Highest Drain:</strong> {{ $trend['hai_analysis_prompt_context']['current_highest_drain'] ?? 'None' }}</li>
                                <li><strong>Context Note:</strong> {{ $trend['hai_analysis_prompt_context']['context_note'] ?? 'None' }}</li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection
