@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .modal-close-btn {
        background: #1b3a62;
        border: none;
        color: white;
        font-weight: bold;
        font-size: x-large;
        float: right;
        border-radius: 3px;
        padding: 0px 10px 1px 10px;
    }

    input::placeholder {
        color: white !important;
    }

    /* Custom styles */
    body div {
        font-size: small;
    }

    input {
        font-size: small;
    }

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    .card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    h5 i {
        margin-right: 8px;
    }

    .text-muted i {
        margin-right: 4px;
    }

    button.btn-outline-secondary i,
    button.btn-outline-danger i {
        font-size: 1.2rem;
    }

    .sidebar {
        height: 100vh;
        padding-top: 20px;
    }

    .nav-link {
        font-size: 1.1rem;
    }

    .content-page {
        padding: 20px;
    }

    .content-page h2 {
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .embedding-card {
        width: 100%;
    }

</style>
@section('content')
    <div class="container-fluid mt-4">
        @livewire('admin.hai-chat.embedding',['group_id' => $id])
    </div>
@endsection

