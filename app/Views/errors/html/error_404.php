<!doctype html>

<html
  lang="en"
  class="light-style layout-wide customizer-hide"
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

    <title>Pagina no encontrada</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url(['assets/img/favicon/favicon.ico']) ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/fonts/remixicon/remixicon.css']) ?>" />
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/fonts/flag-icons.css']) ?>" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/node-waves/node-waves.css']) ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/css/rtl/core.css']) ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/css/rtl/theme-default.css']) ?>" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url(['assets/css/demo.css']) ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css']) ?>" />
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/typeahead-js/typeahead.css']) ?>" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/css/pages/page-misc.css']) ?>" />

    <!-- Helpers -->
    <script src="<?= base_url(['assets/vendor/js/helpers.js"']) ?>"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?= base_url(['assets/vendor/js/template-customizer.js']) ?>"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= base_url(['assets/js/config.js']) ?>"></script>
  </head>

  <body>
    <!-- Content -->

    <!-- Error -->
    <div class="misc-wrapper">
      <h1 class="mb-2 mx-2" style="font-size: 6rem; line-height: 6rem">404</h1>
      <h4 class="mb-2">Página no encontrada ⚠️</h4>
      <p class="mb-6 mx-2">No pudimos encontrar la página que estás buscando</p>
      <div class="d-flex justify-content-center mt-9">
        <img
          src="<?= base_url(['assets/img/illustrations/misc-error-object.png']) ?>"
          alt="misc-error"
          class="img-fluid misc-object d-none d-lg-inline-block"
          width="160" />
        <div class="d-flex flex-column align-items-center">
          <div>
            <a href="<?= base_url() ?>" class="btn btn-primary text-center my-10">Volver al inicio</a>
          </div>
        </div>
      </div>
    </div>
    <!-- /Error -->

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?= base_url(['assets/vendor/libs/jquery/jquery.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/popper/popper.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/js/bootstrap.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/node-waves/node-waves.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/hammer/hammer.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/i18n/i18n.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/typeahead-js/typeahead.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/js/menu.js']) ?>"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="<?= base_url(['assets/js/main.js']) ?>"></script>

    <!-- Page JS -->
  </body>
</html>
