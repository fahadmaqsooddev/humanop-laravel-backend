@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')

    <div class="container mt-9" style="padding-left: 200px;">
        @livewire('client.question.assessment')

    </div>


@endsection

