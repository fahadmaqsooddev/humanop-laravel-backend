@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .font-weight-normal
    {
        color: black;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-lg-7 position-relative z-index-2">
            <div class="mb-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column h-100">
                                <h2 class="font-weight-bolder mb-0">Resources</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-sm-5">
                    <a data-bs-toggle="modal" href="#MasterKey">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Master Key</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a data-bs-toggle="modal" href="#Style">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Styles</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-5 col-sm-5 mt-sm-0 mt-4">
                    <a data-bs-toggle="modal" href="#Cycle">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Cycle</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a data-bs-toggle="modal" href="#EnergyCenter">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Energy Centers</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-5 col-sm-5 mt-sm-0 mt-4">
                    <a data-bs-toggle="modal" href="#Alchemy">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Alchemy</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 200px">
        <div class="col-12">
            <div id="globe" class="position-absolute end-0 top-2 mt-sm-3 me-lg-7">
                <canvas width="700" height="600" class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
            </div>
        </div>
    </div>

    {{--    Master Key Modal--}}
    <div class="modal fade" id="MasterKey" aria-hidden="true" aria-labelledby="MasterKeyLabel"
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img style="width: 100%;" src="{{asset('assets/img/Master_Key.jpg')}}">
                </div>
            </div>
        </div>
    </div>
    {{--    Style Modal--}}
    <div class="modal fade" id="Style" aria-hidden="true" aria-labelledby="StyleLabel"
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img style="width: 100%;" src="{{asset('assets/img/Styles_Wheel.jpg')}}">
                </div>
            </div>
        </div>
    </div>
    {{--    Cycle Modal--}}
    <div class="modal fade" id="Cycle" aria-hidden="true" aria-labelledby="CycleLabel"
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <img style="width: 100%;" src="{{asset('assets/img/Cycle_Of_Life.jpg')}}">
                    <div class="table-responsive mt-4">
                        <table class="table table-flush" id="datatable-search">
                            <thead class="thead-light">
                            <tr>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-sm font-weight-normal">1</td>
                                <td class="text-sm font-weight-normal">0-3</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">2</td>
                                <td class="text-sm font-weight-normal">4-6</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">3</td>
                                <td class="text-sm font-weight-normal">7-11</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">4</td>
                                <td class="text-sm font-weight-normal">12-15</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">5</td>
                                <td class="text-sm font-weight-normal">16-20</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">6</td>
                                <td class="text-sm font-weight-normal">21-29</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">7a</td>
                                <td class="text-sm font-weight-normal">30-33</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">7b</td>
                                <td class="text-sm font-weight-normal">34-42</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">8</td>
                                <td class="text-sm font-weight-normal">43-51</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">9</td>
                                <td class="text-sm font-weight-normal">52-65</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">10</td>
                                <td class="text-sm font-weight-normal">66-69</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">11a</td>
                                <td class="text-sm font-weight-normal">70-74</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">11b</td>
                                <td class="text-sm font-weight-normal">75-83</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal">12</td>
                                <td class="text-sm font-weight-normal">84-93</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    Alchemy Modal--}}
    <div class="modal fade" id="Alchemy" aria-hidden="true" aria-labelledby="AlchemyLabel"
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img style="width: 100%;" src="{{asset('assets/img/Alchemy.jpg')}}">
                    <img style="width: 100%;" src="{{asset('assets/img/Periodic_Table_Alchemy.jpg')}}">
                </div>
            </div>
        </div>
    </div>
    {{--    Energy Center Modal--}}
    <div class="modal fade" id="EnergyCenter" aria-hidden="true" aria-labelledby="EnergyCenterLabel"
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img style="width: 100%;" src="{{asset('assets/img/Energy_Center_Doorways.jpg')}}">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ URL::asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/threejs.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/orbit-controls.js') }}"></script>
    <script>


        (function () {
            const container = document.getElementById("globe");
            const canvas = container.getElementsByTagName("canvas")[0];

            const globeRadius = 100;
            const globeWidth = 4098 / 2;
            const globeHeight = 1968 / 2;

            function convertFlatCoordsToSphereCoords(x, y) {
                let latitude = ((x - globeWidth) / globeWidth) * -180;
                let longitude = ((y - globeHeight) / globeHeight) * -90;
                latitude = (latitude * Math.PI) / 180;
                longitude = (longitude * Math.PI) / 180;
                const radius = Math.cos(longitude) * globeRadius;

                return {
                    x: Math.cos(latitude) * radius,
                    y: Math.sin(longitude) * globeRadius,
                    z: Math.sin(latitude) * radius
                };
            }

            function makeMagic(points) {
                const {
                    width,
                    height
                } = container.getBoundingClientRect();

                // 1. Setup scene
                const scene = new THREE.Scene();
                // 2. Setup camera
                const camera = new THREE.PerspectiveCamera(45, width / height);
                // 3. Setup renderer
                const renderer = new THREE.WebGLRenderer({
                    canvas,
                    antialias: true
                });
                renderer.setSize(width, height);
                // 4. Add points to canvas
                // - Single geometry to contain all points.
                const mergedGeometry = new THREE.Geometry();
                // - Material that the dots will be made of.
                const pointGeometry = new THREE.SphereGeometry(0.5, 1, 1);
                const pointMaterial = new THREE.MeshBasicMaterial({
                    color: "#989db5",
                });

                for (let point of points) {
                    const {
                        x,
                        y,
                        z
                    } = convertFlatCoordsToSphereCoords(
                        point.x,
                        point.y,
                        width,
                        height
                    );

                    if (x && y && z) {
                        pointGeometry.translate(x, y, z);
                        mergedGeometry.merge(pointGeometry);
                        pointGeometry.translate(-x, -y, -z);
                    }
                }

                const globeShape = new THREE.Mesh(mergedGeometry, pointMaterial);
                scene.add(globeShape);

                container.classList.add("peekaboo");

                // Setup orbital controls
                camera.orbitControls = new THREE.OrbitControls(camera, canvas);
                camera.orbitControls.enableKeys = false;
                camera.orbitControls.enablePan = false;
                camera.orbitControls.enableZoom = false;
                camera.orbitControls.enableDamping = false;
                camera.orbitControls.enableRotate = true;
                camera.orbitControls.autoRotate = true;
                camera.position.z = -265;

                function animate() {
                    // orbitControls.autoRotate is enabled so orbitControls.update
                    // must be called inside animation loop.
                    camera.orbitControls.update();
                    requestAnimationFrame(animate);
                    renderer.render(scene, camera);
                }

                animate();
            }

            function hasWebGL() {
                const gl =
                    canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
                if (gl && gl instanceof WebGLRenderingContext) {
                    return true;
                } else {
                    return false;
                }
            }

            function init() {
                if (hasWebGL()) {
                    window
                    window.fetch("https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard-pro/assets/js/points.json")
                        .then(response => response.json())
                        .then(data => {
                            makeMagic(data.points);
                        });
                }
            }

            init();
        })();
    </script>
@endpush
