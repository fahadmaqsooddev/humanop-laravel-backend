@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')

    <link rel="stylesheet" href="{{asset('css/messages-style.css')}}">

    <livewire:client.message.message />

@endsection
