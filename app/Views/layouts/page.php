<!doctype html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?= base_url(['assets/']) ?>"
  data-template="vertical-menu-template"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?= isset(configInfo()['name_app']) ? configInfo()['name_app'] : 'Name' ?><?= $this->renderSection('title') ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url(["assets/img/favicon/favicon.ico"]) ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <?php
      $color_primary = isset(configInfo()['primary_color']) && !empty(configInfo()['primary_color']) ? (string) configInfo()['primary_color'] : '8e24aa';
      $secondary_color = isset(configInfo()['secundary_color']) && !empty(configInfo()['secundary_color']) ? (string) configInfo()['secundary_color'] : 'ff6e40';
      $color_primary = "$color_primary";
    ?>

    <script>
      console.log("#<?= $color_primary ?>");
    </script>

    <style>
      :root {
        --primary-color: #<?= $color_primary ?>;
        --secondary-color: #<?= $secondary_color ?>;
        --primary-rgb: <?= hexToRgb($color_primary)?>;
        --secondary-rgb: <?= hexToRgb($secondary_color) ?>;

        --primary-ligth: <?= lightenColor($color_primary, 90) ?>;
        --primary-ligth-2: <?= lightenColor($color_primary, 80) ?>;
        --primary-ligth-3: <?= lightenColor($color_primary, 70) ?>;
        
        --primary-darken: <?= darkenColor($color_primary, 50) ?>;
        --primary-darken-2: <?= darkenColor($color_primary, 60) ?>;
        --primary-darken-3: <?= darkenColor($color_primary, 70) ?>;
        --primary-darken-4: <?= darkenColor($color_primary, 80) ?>;
      }   
    </style>
    <link rel="stylesheet" href="<?= base_url(['assets/css/colors.css?v='.getCommit()]) ?>" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/fonts/remixicon/remixicon.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/fonts/flag-icons.css"]) ?>" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/node-waves/node-waves.css"]) ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/rtl/core.cs"]) ?>s" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/rtl/theme-default.css"]) ?>" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url(["assets/css/demo.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/css/style.css?v=".getCommit()]) ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/typeahead-js/typeahead.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/apex-charts/apex-charts.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/swiper/swiper.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/toastr/toastr.css']) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/sweetalert2/sweetalert2.css"]) ?>" />

    <!-- Page CSS -->
    <?= $this->renderSection('styles') ?>

    <!-- Helpers -->
    <script src="<?= base_url(["assets/vendor/js/helpers.js"]) ?>"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?= base_url(["assets/vendor/js/template-customizer.js"]) ?>"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= base_url(["assets/js/config.js"]) ?>"></script>

  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?= view('layouts/menu') ?>
        
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?= view('layouts/nav_horizontal') ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <?= $this->renderSection('content'); ?>

            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                  <div class="text-body mb-2 mb-md-0">
                    © 2024, Diseñado por <a href="https://www.iplanetcolombia.com/" target="_blank" class="footer-link">IplanetColombia</a>
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <!-- <?= isset(configInfo()['footer']) ? configInfo()['footer'] : '' ?> -->
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?= base_url(["assets/vendor/libs/jquery/jquery.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/popper/popper.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/js/bootstrap.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/node-waves/node-waves.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/hammer/hammer.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/i18n/i18n.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/typeahead-js/typeahead.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/js/menu.js"]) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/toastr/toastr.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/sweetalert2/sweetalert2.js']) ?>"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?= base_url(['assets/vendor/libs/fullcalendar/fullcalendar.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/@form-validation/popular.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/@form-validation/bootstrap5.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/@form-validation/auto-focus.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/select2/select2.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/moment/moment.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/flatpickr/flatpickr.js']) ?>"></script>


    <!-- endbuild -->
  <!-- Main JS -->
    <script src="<?= base_url(["assets/js/main.js"]) ?>"></script>

    <!-- Vendors JS -->
    <script src="<?= base_url(["assets/vendor/libs/apex-charts/apexcharts.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/swiper/swiper.js"]) ?>"></script>

    
    <script>
        const user = <?= json_encode(session('user')) ?>;
    </script>

    <!-- Page JS -->
    <script src="<?= base_url(["master/js/functions/functions.js?v=".getCommit()]) ?>"></script>
    <script src="<?= base_url(["master/js/functions/fetchHelper.js?v=".getCommit()]) ?>"></script>
    
    <?= $this->renderSection('javaScripts') ?>
  </body>
</html>
