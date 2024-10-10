<!--
=========================================================
* UI Dashboard PRO - v1.0.4
=========================================================

* Product Page:  https://www.creative-tim.com/product/soft-ui-dashboard-pro
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
@if (\Request::is('pages-rtl'))
  <html dir="rtl" lang="ar">
@else
  <html lang="en" >
@endif
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  @if (env('IS_DEMO'))
      <x-demo-metas></x-demo-metas>
  @endif

  <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ URL::asset('assets/img/favicon.png') }}">
  <title>HumanOp Tech</title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  @stack('css')
  <link href="{{ URL::asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ URL::asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <link href="{{ URL::asset('assets/css/custom.css') }}" rel="stylesheet" />
  <link href="{{ URL::asset('css/soft-ui-dashboard.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{ URL::asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ URL::asset('assets/css/soft-ui-dashboard.css?v=1.0.4') }}" rel="stylesheet" />
    @livewireStyles
</head>

<body class="body-background  g-sidenav-show bg-gray-100 g-sidenav-pinned {{ (\Request::is('pages-rtl') ? 'rtl' : (Request::is('dashboard-virtual-default')||Request::is('dashboard-virtual-info') ? 'virtual-reality' : (Request::is('authentication-error404')||Request::is('authentication-error500') ? 'error-page' : ''))) }} ">
  @auth
    @yield('auth')
  @endauth
  @guest
    @yield('guest')
  @endguest

  <!--   Core JS Files   -->
  <script src="{{ URL::asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ URL::asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ URL::asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ URL::asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

  <!-- Kanban scripts -->
  <script src="{{ URL::asset('assets/js/plugins/dragula/dragula.min.js') }}"></script>
  <script src="{{ URL::asset('assets/js/plugins/jkanban/jkanban.js') }}"></script>
  <script src="{{ URL::asset('assets/js/plugins/chartjs.min.js') }}"></script>
  <script src="{{ URL::asset('assets/js/plugins/threejs.js') }}"></script>
  <script src="{{ URL::asset('assets/js/plugins/orbit-controls.js') }}"></script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ URL::asset('assets/js/soft-ui-dashboard.min.js?v=1.0.4') }}"></script>

  <script>

      function changeColorStyleSA(style){
          document.getElementById("style_sa").style.backgroundColor = "#ffff00";
          document.getElementById("style_sa").style.color = "black";

          document.getElementById("feature_dom").style.backgroundColor = "#ffff00";
          document.getElementById("feature_dom").style.color = "black";

          document.getElementById("feature_ne").style.backgroundColor = "#ffff00";
          document.getElementById("feature_ne").style.color = "black";
      }

      function clearColorStyleSA() {
          document.getElementById("style_sa").style.backgroundColor = "";
          document.getElementById("style_sa").style.color = "";

          document.getElementById("feature_dom").style.backgroundColor = "";
          document.getElementById("feature_dom").style.color = "";

          document.getElementById("feature_ne").style.backgroundColor = "";
          document.getElementById("feature_ne").style.color = "";
      }

      function changeColorStyleMA(){
          document.getElementById("style_ma").style.backgroundColor = "#ffff00";
          document.getElementById("style_ma").style.color = "black";

          document.getElementById("feature_de").style.backgroundColor = "#ffff00";
          document.getElementById("feature_de").style.color = "black";

          document.getElementById("feature_dom").style.backgroundColor = "#ffff00";
          document.getElementById("feature_dom").style.color = "black";

          document.getElementById("feature_fe").style.backgroundColor = "#ffff00";
          document.getElementById("feature_fe").style.color = "black";

          document.getElementById("feature_wil").style.backgroundColor = "#ffff00";
          document.getElementById("feature_wil").style.color = "black";
      }

      function clearColorStyleMA(){
          document.getElementById("style_ma").style.backgroundColor = "";
          document.getElementById("style_ma").style.color = "";

          document.getElementById("feature_de").style.backgroundColor = "";
          document.getElementById("feature_de").style.color = "";

          document.getElementById("feature_dom").style.backgroundColor = "";
          document.getElementById("feature_dom").style.color = "";

          document.getElementById("feature_fe").style.backgroundColor = "";
          document.getElementById("feature_fe").style.color = "";

          document.getElementById("feature_wil").style.backgroundColor = "";
          document.getElementById("feature_wil").style.color = "";
      }

      function changeColorStyleJO(){
          document.getElementById("style_jo").style.backgroundColor = "#ffff00";
          document.getElementById("style_jo").style.color = "black";

          document.getElementById("feature_pow").style.backgroundColor = "#ffff00";
          document.getElementById("feature_pow").style.color = "black";

          document.getElementById("feature_sp").style.backgroundColor = "#ffff00";
          document.getElementById("feature_sp").style.color = "black";

          document.getElementById("feature_tra").style.backgroundColor = "#ffff00";
          document.getElementById("feature_tra").style.color = "black";

          document.getElementById("feature_van").style.backgroundColor = "#ffff00";
          document.getElementById("feature_van").style.color = "black";
      }

      function clearColorStyleJO(){
          document.getElementById("style_jo").style.backgroundColor = "";
          document.getElementById("style_jo").style.color = "";

          document.getElementById("feature_pow").style.backgroundColor = "";
          document.getElementById("feature_pow").style.color = "";

          document.getElementById("feature_sp").style.backgroundColor = "";
          document.getElementById("feature_sp").style.color = "";

          document.getElementById("feature_tra").style.backgroundColor = "";
          document.getElementById("feature_tra").style.color = "";

          document.getElementById("feature_van").style.backgroundColor = "";
          document.getElementById("feature_van").style.color = "";
      }

      function changeColorStyleLU(){
          document.getElementById("style_lu").style.backgroundColor = "#ffff00";
          document.getElementById("style_lu").style.color = "black";

          document.getElementById("feature_fe").style.backgroundColor = "#ffff00";
          document.getElementById("feature_fe").style.color = "black";

          document.getElementById("feature_lun").style.backgroundColor = "#ffff00";
          document.getElementById("feature_lun").style.color = "black";

          document.getElementById("feature_ne").style.backgroundColor = "#ffff00";
          document.getElementById("feature_ne").style.color = "black";

          document.getElementById("feature_wil").style.backgroundColor = "#ffff00";
          document.getElementById("feature_wil").style.color = "black";
      }

      function clearColorStyleLU(){
          document.getElementById("style_lu").style.backgroundColor = "";
          document.getElementById("style_lu").style.color = "";

          document.getElementById("feature_fe").style.backgroundColor = "";
          document.getElementById("feature_fe").style.color = "";

          document.getElementById("feature_lun").style.backgroundColor = "";
          document.getElementById("feature_lun").style.color = "";

          document.getElementById("feature_ne").style.backgroundColor = "";
          document.getElementById("feature_ne").style.color = "";

          document.getElementById("feature_wil").style.backgroundColor = "";
          document.getElementById("feature_wil").style.color = "";
      }

      function changeColorStyleVEN(){
          document.getElementById("style_ven").style.backgroundColor = "#ffff00";
          document.getElementById("style_ven").style.color = "black";

          document.getElementById("feature_fe").style.backgroundColor = "#ffff00";
          document.getElementById("feature_fe").style.color = "black";

          document.getElementById("feature_lun").style.backgroundColor = "#ffff00";
          document.getElementById("feature_lun").style.color = "black";

          document.getElementById("feature_tra").style.backgroundColor = "#ffff00";
          document.getElementById("feature_tra").style.color = "black";

          document.getElementById("feature_van").style.backgroundColor = "#ffff00";
          document.getElementById("feature_van").style.color = "black";
      }

      function clearColorStyleVEN(){
          document.getElementById("style_ven").style.backgroundColor = "";
          document.getElementById("style_ven").style.color = "";

          document.getElementById("feature_fe").style.backgroundColor = "";
          document.getElementById("feature_fe").style.color = "";

          document.getElementById("feature_lun").style.backgroundColor = "";
          document.getElementById("feature_lun").style.color = "";

          document.getElementById("feature_tra").style.backgroundColor = "";
          document.getElementById("feature_tra").style.color = "";

          document.getElementById("feature_van").style.backgroundColor = "";
          document.getElementById("feature_van").style.color = "";
      }

      function changeColorStyleMER(){
          document.getElementById("style_mer").style.backgroundColor = "#ffff00";
          document.getElementById("style_mer").style.color = "black";

          document.getElementById("feature_gre").style.backgroundColor = "#ffff00";
          document.getElementById("feature_gre").style.color = "black";

          document.getElementById("feature_pow").style.backgroundColor = "#ffff00";
          document.getElementById("feature_pow").style.color = "black";

          document.getElementById("feature_van").style.backgroundColor = "#ffff00";
          document.getElementById("feature_van").style.color = "black";
      }

      function clearColorStyleMER(){
          document.getElementById("style_mer").style.backgroundColor = "";
          document.getElementById("style_mer").style.color = "";

          document.getElementById("feature_gre").style.backgroundColor = "";
          document.getElementById("feature_gre").style.color = "";

          document.getElementById("feature_pow").style.backgroundColor = "";
          document.getElementById("feature_pow").style.color = "";

          document.getElementById("feature_van").style.backgroundColor = "";
          document.getElementById("feature_van").style.color = "";
      }

      function changeColorStyleSO(){
          document.getElementById("style_so").style.backgroundColor = "#ffff00";
          document.getElementById("style_so").style.color = "black";

          document.getElementById("feature_nai").style.backgroundColor = "#ffff00";
          document.getElementById("feature_nai").style.color = "black";

          document.getElementById("feature_van").style.backgroundColor = "#ffff00";
          document.getElementById("feature_van").style.color = "black";
      }

      function clearColorStyleSO(){
          document.getElementById("style_so").style.backgroundColor = "";
          document.getElementById("style_so").style.color = "";

          document.getElementById("feature_nai").style.backgroundColor = "";
          document.getElementById("feature_nai").style.color = "";

          document.getElementById("feature_van").style.backgroundColor = "";
          document.getElementById("feature_van").style.color = "";
      }

      function changeColorFeatureDE(){
          document.getElementById("feature_de").style.backgroundColor = "#ffff00";
          document.getElementById("feature_de").style.color = "black";

          document.getElementById("style_ma").style.backgroundColor = "#ffff00";
          document.getElementById("style_ma").style.color = "black";
      }

      function clearColorFeatureDE(){
          document.getElementById("feature_de").style.backgroundColor = "";
          document.getElementById("feature_de").style.color = "";

          document.getElementById("style_ma").style.backgroundColor = "";
          document.getElementById("style_ma").style.color = "";
      }

      function changeColorFeatureDOM(){
          document.getElementById("feature_dom").style.backgroundColor = "#ffff00";
          document.getElementById("feature_dom").style.color = "black";

          document.getElementById("style_sa").style.backgroundColor = "#ffff00";
          document.getElementById("style_sa").style.color = "black";

          document.getElementById("style_ma").style.backgroundColor = "#ffff00";
          document.getElementById("style_ma").style.color = "black";
      }

      function clearColorFeatureDOM(){
          document.getElementById("feature_dom").style.backgroundColor = "";
          document.getElementById("feature_dom").style.color = "";

          document.getElementById("style_sa").style.backgroundColor = "";
          document.getElementById("style_sa").style.color = "";

          document.getElementById("style_ma").style.backgroundColor = "";
          document.getElementById("style_ma").style.color = "";
      }

      function changeColorFeatureFE(){
          document.getElementById("feature_fe").style.backgroundColor = "#ffff00";
          document.getElementById("feature_fe").style.color = "black";

          document.getElementById("style_ma").style.backgroundColor = "#ffff00";
          document.getElementById("style_ma").style.color = "black";

          document.getElementById("style_lu").style.backgroundColor = "#ffff00";
          document.getElementById("style_lu").style.color = "black";

          document.getElementById("style_ven").style.backgroundColor = "#ffff00";
          document.getElementById("style_ven").style.color = "black";
      }

      function clearColorFeatureFE(){
          document.getElementById("feature_fe").style.backgroundColor = "";
          document.getElementById("feature_fe").style.color = "";

          document.getElementById("style_ma").style.backgroundColor = "";
          document.getElementById("style_ma").style.color = "";

          document.getElementById("style_lu").style.backgroundColor = "";
          document.getElementById("style_lu").style.color = "";

          document.getElementById("style_ven").style.backgroundColor = "";
          document.getElementById("style_ven").style.color = "";
      }

      function changeColorFeatureGRE(){
          document.getElementById("feature_gre").style.backgroundColor = "#ffff00";
          document.getElementById("feature_gre").style.color = "black";

          document.getElementById("style_mer").style.backgroundColor = "#ffff00";
          document.getElementById("style_mer").style.color = "black";
      }

      function clearColorFeatureGRE(){
          document.getElementById("feature_gre").style.backgroundColor = "";
          document.getElementById("feature_gre").style.color = "";

          document.getElementById("style_mer").style.backgroundColor = "";
          document.getElementById("style_mer").style.color = "";
      }

      function changeColorFeatureLUN(){
          document.getElementById("feature_lun").style.backgroundColor = "#ffff00";
          document.getElementById("feature_lun").style.color = "black";

          document.getElementById("style_lu").style.backgroundColor = "#ffff00";
          document.getElementById("style_lu").style.color = "black";
      }

      function clearColorFeatureLUN(){
          document.getElementById("feature_lun").style.backgroundColor = "";
          document.getElementById("feature_lun").style.color = "";

          document.getElementById("style_lu").style.backgroundColor = "";
          document.getElementById("style_lu").style.color = "";
      }

      function changeColorFeatureNAI(){
          document.getElementById("feature_nai").style.backgroundColor = "#ffff00";
          document.getElementById("feature_nai").style.color = "black";

          document.getElementById("style_so").style.backgroundColor = "#ffff00";
          document.getElementById("style_so").style.color = "black";
      }

      function clearColorFeatureNAI(){
          document.getElementById("feature_nai").style.backgroundColor = "";
          document.getElementById("feature_nai").style.color = "";

          document.getElementById("style_so").style.backgroundColor = "";
          document.getElementById("style_so").style.color = "";
      }

      function changeColorFeatureNE(){
          document.getElementById("feature_ne").style.backgroundColor = "#ffff00";
          document.getElementById("feature_ne").style.color = "black";

          document.getElementById("style_sa").style.backgroundColor = "#ffff00";
          document.getElementById("style_sa").style.color = "black";

          document.getElementById("style_lu").style.backgroundColor = "#ffff00";
          document.getElementById("style_lu").style.color = "black";

          document.getElementById("style_ven").style.backgroundColor = "#ffff00";
          document.getElementById("style_ven").style.color = "black";
      }

      function clearColorFeatureNE(){
          document.getElementById("feature_ne").style.backgroundColor = "";
          document.getElementById("feature_ne").style.color = "";

          document.getElementById("style_sa").style.backgroundColor = "";
          document.getElementById("style_sa").style.color = "";

          document.getElementById("style_lu").style.backgroundColor = "";
          document.getElementById("style_lu").style.color = "";

          document.getElementById("style_ven").style.backgroundColor = "";
          document.getElementById("style_ven").style.color = "";
      }

      function changeColorFeaturePOW(){
          document.getElementById("feature_pow").style.backgroundColor = "#ffff00";
          document.getElementById("feature_pow").style.color = "black";

          document.getElementById("style_jo").style.backgroundColor = "#ffff00";
          document.getElementById("style_jo").style.color = "black";

          document.getElementById("style_mer").style.backgroundColor = "#ffff00";
          document.getElementById("style_mer").style.color = "black";
      }

      function clearColorFeaturePOW(){
          document.getElementById("feature_pow").style.backgroundColor = "";
          document.getElementById("feature_pow").style.color = "";

          document.getElementById("style_jo").style.backgroundColor = "";
          document.getElementById("style_jo").style.color = "";

          document.getElementById("style_mer").style.backgroundColor = "";
          document.getElementById("style_mer").style.color = "";
      }

      function changeColorFeatureSP(){
          document.getElementById("feature_sp").style.backgroundColor = "#ffff00";
          document.getElementById("feature_sp").style.color = "black";

          document.getElementById("style_jo").style.backgroundColor = "#ffff00";
          document.getElementById("style_jo").style.color = "black";
      }

      function clearColorFeatureSP(){
          document.getElementById("feature_sp").style.backgroundColor = "";
          document.getElementById("feature_sp").style.color = "";

          document.getElementById("style_jo").style.backgroundColor = "";
          document.getElementById("style_jo").style.color = "";
      }

      function changeColorFeatureTRA(){
          document.getElementById("feature_tra").style.backgroundColor = "#ffff00";
          document.getElementById("feature_tra").style.color = "black";

          document.getElementById("style_jo").style.backgroundColor = "#ffff00";
          document.getElementById("style_jo").style.color = "black";

          document.getElementById("style_ven").style.backgroundColor = "#ffff00";
          document.getElementById("style_ven").style.color = "black";
      }

      function clearColorFeatureTRA(){
          document.getElementById("feature_tra").style.backgroundColor = "";
          document.getElementById("feature_tra").style.color = "";

          document.getElementById("style_jo").style.backgroundColor = "";
          document.getElementById("style_jo").style.color = "";

          document.getElementById("style_ven").style.backgroundColor = "";
          document.getElementById("style_ven").style.color = "";
      }

      function changeColorFeatureVAN(){
          document.getElementById("feature_van").style.backgroundColor = "#ffff00";
          document.getElementById("feature_van").style.color = "black";

          document.getElementById("style_jo").style.backgroundColor = "#ffff00";
          document.getElementById("style_jo").style.color = "black";

          document.getElementById("style_ven").style.backgroundColor = "#ffff00";
          document.getElementById("style_ven").style.color = "black";

          document.getElementById("style_mer").style.backgroundColor = "#ffff00";
          document.getElementById("style_mer").style.color = "black";

          document.getElementById("style_so").style.backgroundColor = "#ffff00";
          document.getElementById("style_so").style.color = "black";
      }

      function clearColorFeatureVAN(){
          document.getElementById("feature_van").style.backgroundColor = "";
          document.getElementById("feature_van").style.color = "";

          document.getElementById("style_jo").style.backgroundColor = "";
          document.getElementById("style_jo").style.color = "";

          document.getElementById("style_ven").style.backgroundColor = "";
          document.getElementById("style_ven").style.color = "";

          document.getElementById("style_mer").style.backgroundColor = "";
          document.getElementById("style_mer").style.color = "";

          document.getElementById("style_so").style.backgroundColor = "";
          document.getElementById("style_so").style.color = "";
      }

      function changeColorFeatureWIL(){
          document.getElementById("feature_wil").style.backgroundColor = "#ffff00";
          document.getElementById("feature_wil").style.color = "black";

          document.getElementById("style_ma").style.backgroundColor = "#ffff00";
          document.getElementById("style_ma").style.color = "black";

          document.getElementById("style_lu").style.backgroundColor = "#ffff00";
          document.getElementById("style_lu").style.color = "black";
      }

      function clearColorFeatureWIL(){
          document.getElementById("feature_wil").style.backgroundColor = "";
          document.getElementById("feature_wil").style.color = "";

          document.getElementById("style_ma").style.backgroundColor = "";
          document.getElementById("style_ma").style.color = "";

          document.getElementById("style_lu").style.backgroundColor = "";
          document.getElementById("style_lu").style.color = "";
      }

      (function() {
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
{{--  @livewireScripts--}}
  @stack('js')
  <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
  <livewire:scripts />
  @stack('javascript')
</body>

</html>
