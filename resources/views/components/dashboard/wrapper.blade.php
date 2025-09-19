  <!-- Content wrapper -->
  <div class="content-wrapper" id="warpper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
       @yield('contenido')
    </div>
    <!-- / Content -->

    @include($this->getComponents("dashboard.footer"))
    <!-- / Footer -->

    <div class="content-backdrop fade"></div>

  </div>