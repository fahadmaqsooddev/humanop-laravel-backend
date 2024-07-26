@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .borderAdd {
        border-bottom: none;
    }

    .bg-green {
        background-color: green !important;
    }
    .bg-none {
        background-color: transparent  !important;
    }

    .bg-red {
        background-color: red !important;
    }

    .bg-yellow {
        background-color: yellow !important;
    }
    .border-green {
        border: 1px solid green !important;
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

        function changeStyleBackgroundColor(element, code) {
            if (!element.clickCount) {
                element.clickCount = 0;
            }

            element.clickCount++;

            let color;
            switch (element.clickCount % 4) {
                case 1:
                    element.className = "text-center border border-white cursor-pointer bg-green";
                    color = 'green';
                    break;
                case 2:
                    element.className = "text-center border border-white cursor-pointer bg-red";
                    color = 'red';
                    break;
                case 3:
                    element.className = "text-center border cursor-pointer border-success";
                    color = 'border-green';
                    break;
                case 4:
                    element.className = "text-center border border-white cursor-pointer bg-none";
                    color = 'none';
                    break;
            }

            element.setAttribute('data_color', color);
            Livewire.emit('selectCode', code, color);
        }

        function changeFeatureBackgroundColor(element, code) {
            if (!element.clickCount) {
                element.clickCount = 0;
            }

            element.clickCount++;

            let color;
            switch (element.clickCount % 4) {
                case 1:
                    element.className = "text-center border border-white cursor-pointer bg-green";
                    color = 'green';
                    break;
                case 2:
                    element.className = "text-center border border-white cursor-pointer bg-yellow";
                    color = 'yellow';
                    break;
                case 3:
                    element.className = "text-center border cursor-pointer bg-red";
                    color = 'red';
                    break;
                case 4:
                    element.className = "text-center border border-white cursor-pointer bg-none";
                    color = 'none';
                    break;
            }

            element.setAttribute('data_color', color);
            Livewire.emit('selectCode', code, color);
        }

    </script>
@endpush
