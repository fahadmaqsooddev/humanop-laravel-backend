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
        color: black !important;
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

    .carousel-control-next {
        margin-right: -35px !important;
        width: 36px !important;
        background-color: #f2661c !important;
        height: 52px !important;
    }

    .carousel-control-prev {
        margin-right: -35px !important;
        width: 36px !important;
        background-color: #f2661c !important;
        height: 52px !important;
    }

    .table-text-color{
        color: #1c365e !important;
    }

    .dataTable-table th a{
        color: #1c365e !important;
    }

    .border{
        border: 1px solid #1c365e !important;
    }

</style>
@section('content')
    <div class="row mt-4 container-fluid ">
        <div class="col-12">
            <div class="card table-orange-color">
                <div class="table-header-text" style="border-radius: 20px;">
                    @livewire('admin.assessment.assessment')
                </div>
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

        document.addEventListener('livewire:load', function () {
            // Initial jQuery logic
            $('.clickBtn').on('click', function () {
                $('.advanceFilterSearch').toggle();
            });

            // Reapply jQuery logic after Livewire updates
            Livewire.hook('message.processed', (message, component) => {
                $('.clickBtn').off('click').on('click', function () {
                    $('.advanceFilterSearch').toggle();
                });
            });
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
            localStorage.setItem('color',color);
            localStorage.setItem('code',code);
            Livewire.emit('selectStyleCode', code, color);
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
            localStorage.setItem('color',color);
            localStorage.setItem('code',code);
            Livewire.emit('selectFeatureCode', code, color);
        }

        function changeStyleCodeNumber(selectNum, index) {

            let currentColor = localStorage.getItem('color');
            let currentCode = localStorage.getItem('code');

            Livewire.emit('selectStyleNumber', selectNum, index, currentColor, currentCode);
        }

        function changeFeatureCodeNumber(select_num, index) {
            let currentColor = localStorage.getItem('color');
            let currentCode = localStorage.getItem('code');
            Livewire.emit('selectFeatureNumber', select_num, index, currentColor, currentCode);
        }

    </script>
@endpush
