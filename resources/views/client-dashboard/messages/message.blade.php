@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')

<style>
    @media screen and (min-width: 0px) and (max-width: 700px) {

.sepratediv{
    display: flex;
    flex-direction: column;
    width: 100%;
}
.sepratediv .myprofile{
    padding-top: 1rem;
    width: 100%;
}
.sepratediv .myaccount button{
    margin-right: 20px;
}
}
   
</style>

    <link rel="stylesheet" href="{{asset('css/messages-style.css')}}">

    <livewire:client.message.message />

@endsection
