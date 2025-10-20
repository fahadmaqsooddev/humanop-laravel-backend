<div>
    <div class="table-responsive table-orange-color">
        @include('layouts.message')
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>#</th>
                <th>Optimization Plan</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr class="table-text-color">
                <td class="text-md font-weight-normal">1</td>
                <td class="text-md font-weight-normal">14 Days Optimization Action Plan</td>
                <td>
                    <a href="{{route('admin_fourteen_days_optimization_plan')}}" class="btn btn-sm text-white" style="background-color: #1b3a62;">View</a>
                </td>
            </tr>
            <tr class="table-text-color">
                <td class="text-md font-weight-normal">2</td>
                <td class="text-md font-weight-normal">90 Days Optimization Action Plan</td>
                <td>
                    <a href="{{route('admin_ninty_days_optimization_plan')}}" class="btn btn-sm text-white" style="background-color: #1b3a62;">View</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
