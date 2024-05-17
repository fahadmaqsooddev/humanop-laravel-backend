@extends('user_type.auth', ['parentFolder' => 'mcq', 'childFolder' => 'none'])



@section('content')
<main class="main-content">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 border-radius-lg"
        style="background-image: url('assets/img/login.webp');">
        {{-- <span class="mask bg-gradient-dark opacity-6"></span> --}}
        <div class="container">
            <div class="row d-flex flex-column justify-content-center">
                <div class="col-lg-5 text-center mx-auto">
                    <p class="text-white mb-2 text-2xl text-bold">Payment Details</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
            <div class="col-xl-8 col-lg-5 col-md-4">
                <div class="card z-index-0">
                    <div class="card-body">
                        <form action="{{url('client-user-detail')}}">
                          
                            <div class="mb-3">
                                <label for="" class="text-white">Name</label>
                                <input type="text" class="form-control" placeholder="Please Enter Your Name" 
                                     style="background-color: #0F1535; color: white; border-radius: 15px;">
                                
                            </div>
                            <div class="mb-3">
                                <label for="" class="text-white">Card Number</label>
                                <input type="text" class="form-control" placeholder="Enter You Card Number" aria-label="Password"
                                     name="password" id="password"  style="background-color: #0F1535; color: white; border-radius: 15px;">
                            </div>
                           <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="" class="text-white">Expiry Date</label>
                                    <input type="number"  class="form-control" placeholder="00/00"  aria-label="Password"
                                         name="password" id="password"  style="background-color: #0F1535; color: white; border-radius: 15px;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="" class="text-white">CVC</label>
                                    <input type="text" class="form-control" placeholder="xxx" aria-label="Password"
                                         name="password" id="password"  style="background-color: #0F1535; color: white; border-radius: 15px;">
                                </div>
                            </div>
                            
                           </div>
                    
                            <div class="text-center">
                                <button type="submit" class="btn w-100 my-4 mb-2" style="background-color: #f2661c;color:white">Pay Now</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
{{-- <script>
    document.getElementById('expiryDate').addEventListener('input', function () {
        if (this.value.length > 14) {
            this.value = this.value.slice(0, 14);
        }
    });
</script> --}}
@endsection