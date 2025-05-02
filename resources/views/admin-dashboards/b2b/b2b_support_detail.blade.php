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

    .box-grid-size {
        border: 2px solid white;
        padding: 12px 25px;
        text-align: center;
        width: 60px; /* Set fixed width */
        height: 60px; /* Set fixed height */
        display: inline-flex; /* Flexbox for equal sizing */
        justify-content: center; /* Center content horizontally */
        align-items: center; /* Center content vertically */
    }
    .greenBox
    {
        background-color: green !important;
    }
    .redBox
    {
        background-color: red !important;
    }
    .lightGreenBox
    {
        background-color: yellow !important;
        color: black !important;
        font-weight: bold !important;
    }
    .border-green
    {
        border: 2px solid green !important;
    }

    .table-text-color{
        color: #1c365e !important;
    }

    .dataTable-table th a{
        color: #1c365e !important;
    }
</style>
@section('content')
    <div class="row mt-4 container-fluid mainDivClass">
        <div class="col-12">
            <div class="card table-orange-color">
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">B2B Support Detail</h5>

                </div>

                {{-- <table class="table table-flush">
                    <thead class="thead-light">
                    <tr class="table-text-color">
                        <th>id</th>
                        <th>title</th>
                        <th>image</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($support as $index => $support)
                        <tr class="table-text-color">
                            <td class="text-md font-weight-normal">{{$support['id']}} </td>
                            <td class="text-md font-weight-normal">{{$support['title']}} </td>


                                <td>


                                    @if($support['image_id'] != null)
                                    @if(!empty($support['photo_url']))
                                        <img style="width: 80px; max-height: 80px;"
                                             src="{{ $support['photo_url'] ? $support['photo_url']['url'] : '' }}">
                                    </td>
                                    @endif
                                    @endif
                                </td>


                            <td>

                                <a href="{{ route('admin_b2b_support_detail', ['id' => $support->id]) }}"
                                    class="btn mb-0 text-white"
                                    style="background-color: #f2661c; border-radius: 0px 5px 5px 0px">
                                     View Detail
                                 </a>


                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table> --}}
                {{-- @livewire('admin.client-invites.client-invite') --}}

<div class="col-12" style="color: black; margin:10px;">
    {{$support['title']}}
</div>

<div class="col-12">
    @if($support['image_id'] != null)
                                    @if(!empty($support['photo_url']))
                                        <img style="width: 50%; height:50%; margin:10px;" class="image-fluid"
                                             src="{{ $support['photo_url'] ? $support['photo_url']['url'] : '' }}">
                                    </td>
                                    @endif
                                    @endif
</div>

<div class="col-12">
    <p id="short-description" style="margin:10px;color:black;">
        {{ \Illuminate\Support\Str::words($support['description'], 30, '...') }}
        <br>
        <a href="javascript:void(0);" class="btn mb-0 text-white" style="background-color: #f2661c; border-radius: 0px 5px 5px 0px;margin-top:1rem" onclick="toggleDescription()">Read More</a>
    </p>
    <p id="full-description" style="display: none;margin:10px;color:black;">
        {{ $support['description'] }}
        <br>
        <a href="javascript:void(0);" class="btn mb-0 text-white" style="margin-top:1rem;background-color: #f2661c; border-radius: 0px 5px 5px 0px" onclick="toggleDescription()">Read Less</a>
    </p>
</div>

            </div>
        </div>
    </div>


<script>
    function toggleDescription() {
        var shortDesc = document.getElementById("short-description");
        var fullDesc = document.getElementById("full-description");

        if (shortDesc.style.display === "none") {
            shortDesc.style.display = "block";
            fullDesc.style.display = "none";
        } else {
            shortDesc.style.display = "none";
            fullDesc.style.display = "block";
        }
    }
</script>
@endsection
