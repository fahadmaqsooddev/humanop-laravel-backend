@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'profile'])

@section('content')

    @livewire('client.human-network.human-network')
    
@endsection

