<div>
    <div class="table-responsive">
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr>
                <th>Discount</th>
                <th>Discount Limit</th>
                <th>Coupon Code</th>
            </tr>
            </thead>
            <tbody>
            @foreach($coupons as $coupon)
                <tr>
                    <td class="text-sm font-weight-normal">{{$coupon['discount']}}% </td>
                    <td class="text-sm font-weight-normal">{{$coupon['limit']}} </td>
                    <td class="text-sm font-weight-normal">{{$coupon['coupon']}} </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        {{ $coupons->links() }}
    </div>

    <!-- Coupon Discount -->
    @livewire('admin.setting.discount-setting-form')
</div>
