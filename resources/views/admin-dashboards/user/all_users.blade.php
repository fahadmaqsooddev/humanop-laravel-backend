@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .borderAdd {
        border-bottom: none;
    }

    .green {
        background-color: green !important;
    }

    .red {
        background-color: red !important;
    }

    .yellow {
        background-color: yellow !important;
    }

    .bg-green {
        background-color: lightgreen !important;
    }

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

    .pagination {
        float: right;
        margin-right: 24px;
    }

    .page-link {
        background: none !important;
    }

    .page-link:hover {
        background: #f2661c !important;
        color: white !important;
    }

    .page-item.active .page-link {
        background: #f2661c !important;
        color: white !important;
        border-color: #f2661c !important;
    }

</style>
@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                @livewire('admin.user.all-user')
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
            searchable: true,
            fixedHeight: true
        });

        document.querySelector('.clickBtn').addEventListener('click', function () {
            const advanceFilterSearch = document.querySelector('.advanceFilterSearch');
            advanceFilterSearch.classList.toggle('d-none');
        });

        $(document).ready(function () {
            $('.form-check-input').on('click', function () {
                const colors = ['green', 'red', 'yellow', 'bg-green', ''];
                const currentIndex = $(this).data('color-index') || 0;
                const nextIndex = (currentIndex + 1) % colors.length;

                $(this).removeClass(colors[currentIndex]);
                $(this).addClass(colors[nextIndex]);
                $(this).data('color-index', nextIndex);
            });
        });

    </script>
@endpush
