@extends('user_type.guest', ['parentFolder' => 'pages', 'childFolder' => 'none'])


@section('content')
  <div class="page-header position-relative m-3 border-radius-xl">
    <img src="{{ URL::asset('assets/img/login.webp') }}" alt="pattern-lines" class="position-absolute opacity-6 start-0 top-0 w-100">
    <div class="container pb-lg-9 pb-10 pt-7 postion-relative z-index-2">
      <div class="row">
        <div class="col-md-6 mx-auto text-center">
          <h3 class="text-white">See our pricing</h3>
          <p class="text-white">You have Free Unlimited Updates and Premium Support on each package.</p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-6 col-7 mx-auto text-center">
          <div class="nav-wrapper mt-5 position-relative z-index-2">
            <ul class="nav nav-pills nav-fill flex-row p-1" id="tabs-pricing" role="tablist">
              <li class="nav-item">
                <a class="nav-link mb-0 active" id="tabs-iconpricing-tab-1" data-bs-toggle="tab" href="#monthly" role="tab" aria-controls="monthly" aria-selected="true">
                  Monthly
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link mb-0" id="tabs-iconpricing-tab-2" data-bs-toggle="tab" href="#annual" role="tab" aria-controls="annual" aria-selected="false">
                  Annual
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-n8">
    <div class="container">
      <div class="tab-content tab-space">
        <div class="tab-pane active" id="monthly">
          <div class="row">
            <div class="col-lg-4 mb-lg-0 mb-4">
              <div class="card">
                <div class="card-header text-center pt-4 pb-3">
                  <span class="badge rounded-pill bg-gradient text-dark">Starter</span>
                  <h1 class="font-weight-bold mt-2 text-white">
                    <small>$</small>59
                  </h1>
                </div>
                <div class="card-body text-lg-start text-center pt-0">
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">2 team members</span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">20GB Cloud storage </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">Integration help </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">Sketch Files </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">API Access </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">Complete documentation </span>
                    </div>
                  </div>
                  <a href="javascript:;" class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">
                    Join
                    <i class="fas fa-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mb-lg-0 mb-4">
              <div class="card">
                <div class="card-header text-center pt-4 pb-3">
                  <span class="badge rounded-pill bg-gradient text-dark">Premium</span>
                  <h1 class="font-weight-bold mt-2 text-white">
                    <small>$</small>89
                  </h1>
                </div>
                <div class="card-body text-lg-start text-center pt-0">
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">10 team members</span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">40GB Cloud storage </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Integration help </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Sketch Files </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">API Access </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">Complete documentation </span>
                    </div>
                  </div>
                  <a href="javascript:;" class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">
                    Try Premium
                    <i class="fas fa-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mb-lg-0 mb-4">
              <div class="card">
                <div class="card-header text-center pt-4 pb-3">
                  <span class="badge rounded-pill bg-gradient text-dark">Enterprise</span>
                  <h1 class="font-weight-bold mt-2 text-white">
                    <small>$</small>99
                  </h1>
                </div>
                <div class="card-body text-lg-start text-center pt-0">
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Unlimited team members</span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">100GB Cloud storage </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Integration help </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Sketch Files </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">API Access </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Complete documentation </span>
                    </div>
                  </div>
                  <a href="javascript:;" class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">
                    Join
                    <i class="fas fa-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="annual">
          <div class="row">
            <div class="col-lg-4 mb-lg-0 mb-4">
              <div class="card">
                <div class="card-header text-center pt-4 pb-3">
                  <span class="badge rounded-pill bg-gradient text-dark">Starter</span>
                  <h1 class="font-weight-bold mt-2 text-white">
                    <small>$</small>119
                  </h1>
                </div>
                <div class="card-body text-lg-start text-center pt-0">
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">2 team members</span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">20GB Cloud storage </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">Integration help </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">Sketch Files </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">API Access </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">Complete documentation </span>
                    </div>
                  </div>
                  <a href="javascript:;" class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">
                    Join
                    <i class="fas fa-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mb-lg-0 mb-4">
              <div class="card">
                <div class="card-header text-center pt-4 pb-3">
                  <span class="badge rounded-pill bg-gradient text-dark">Premium</span>
                  <h1 class="font-weight-bold mt-2 text-white">
                    <small>$</small>159
                  </h1>
                </div>
                <div class="card-body text-lg-start text-center pt-0">
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">10 team members</span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">40GB Cloud storage </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Integration help </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Sketch Files </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">API Access </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-secondary shadow text-center">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div>
                      <span class="ps-3">Complete documentation </span>
                    </div>
                  </div>
                  <a href="javascript:;" class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">
                    Try Premium
                    <i class="fas fa-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mb-lg-0 mb-4">
              <div class="card">
                <div class="card-header text-center pt-4 pb-3">
                  <span class="badge rounded-pill bg-gradient text-dark">Enterprise</span>
                  <h1 class="font-weight-bold mt-2 text-white">
                    <small>$</small>399
                  </h1>
                </div>
                <div class="card-body text-lg-start text-center pt-0">
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Unlimited team members</span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">100GB Cloud storage </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Integration help </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Sketch Files </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">API Access </span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-lg-start justify-content-center p-2">
                    <div class="icon icon-shape icon-xs rounded-circle bg-gradient-primary shadow text-center">
                      <i class="fas fa-check opacity-10"></i>
                    </div>
                    <div>
                      <span class="ps-3">Complete documentation </span>
                    </div>
                  </div>
                  <a href="javascript:;" class="btn btn-icon bg-gradient-primary d-lg-block mt-3 mb-0">
                    Join
                    <i class="fas fa-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 
@endsection