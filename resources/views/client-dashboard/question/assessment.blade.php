@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])


<!-- Your custom styles for sortable list -->
<style>
    .sortable {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 60%;
    }
    .sortable li {
        margin: 0 3px 3px 3px;
        padding: 0.4em;
        padding-left: 1.5em;
        font-size: 1.4em;
        height: 18px;
        cursor: move;
    }
    .sortable li span {
        position: absolute;
        margin-left: -1.3em;
    }
    .form-check-input:checked[type="checkbox"] {
        background-image: linear-gradient(310deg, #f2661c 0%, #f2661c 100%);
    }
</style>
@section('content')

    <div class="container mt-9 " >
        @livewire('client.question.assessment')
    </div>


@endsection

