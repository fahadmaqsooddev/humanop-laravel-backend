@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .modal-close-btn {
        background: #f2661c;
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
        @livewire('admin.hai-chat.group')
    </div>

{{--    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>--}}

@endsection

