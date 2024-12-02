@extends('user_type.guest', ['parentFolder' => 'session', 'childFolder' => 'none'])
<style>
    .left-nav-blue-light-color {
        background: #2C4C7E !important;
    }
</style>
@section('content')
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg">
        </div>
        @livewire('client.user.email-verification',['token' => $token])
    </main>
@endsection

