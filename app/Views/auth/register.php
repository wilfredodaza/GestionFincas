<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="<?= base_url(['assets/']) ?>" data-template="vertical-menu-template" data-style="light">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
          content="<?= isset(configInfo()['meta_description']) ? configInfo()['meta_description'] : 'Name' ?>">
    <meta name="keywords"
          content="<?= isset(configInfo()['meta_keywords']) ? configInfo()['meta_keywords'] : 'Name' ?>">
    <meta name="author" content="IPlanet Colombia S.A.S">
    <title><?= isset(configInfo()['name_app']) ? configInfo()['name_app'] : 'Name' ?></title>
    <title><?= isset(configInfo()['name_app']) ? configInfo()['name_app'] : 'Name' ?></title>
    <link rel="apple-touch-icon" href="<?= !isset(configInfo()['favicon']) ||  empty(configInfo()['favicon']) ? base_url().'/assets/img/logo.png' :  base_url().'/assets/upload/images/'.configInfo()['favicon']   ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= !isset(configInfo()['favicon']) ||  empty(configInfo()['favicon']) ? base_url().'/assets/img/logo.png' :  base_url().'/assets/img/'.configInfo()['favicon']   ?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/fonts/remixicon/remixicon.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/fonts/flag-icons.css"]) ?>" />

    <?php
      $color_primary = isset(configInfo()['primary_color']) && !empty(configInfo()['primary_color']) ? configInfo()['primary_color'] : '8e24aa';
      $secondary_color = isset(configInfo()['secundary_color']) && !empty(configInfo()['secundary_color']) ? configInfo()['secundary_color'] : 'ff6e40';
    ?>

    <style>
      :root {
        --primary-color: #<?= $color_primary ?>;
        --secondary-color: #<?= $secondary_color ?>;
        --primary-rgb: <?= hexToRgb($color_primary)?>;
        --secondary-rgb: <?= hexToRgb($secondary_color) ?>;
        --primary-ligth: <?= lightenColor($color_primary, 90) ?>;
        --primary-ligth-2: <?= lightenColor($color_primary, 80) ?>;
        --primary-ligth-3: <?= lightenColor($color_primary, 70) ?>;
        --primary-darken: <?= darkenColor($color_primary, 30) ?>;
      }   
    </style>

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/node-waves/node-waves.css"]) ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/rtl/core.css"]) ?>"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/rtl/theme-default.css"]) ?>"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url(["assets/css/demo.css"]) ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/typeahead-js/typeahead.css"]) ?>" />
    <!-- Vendor -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/@form-validation/form-validation.css"]) ?>" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/pages/page-auth.css"]) ?>" />

    <!-- Helpers -->
    <script src="<?= base_url(["assets/vendor/js/helpers.js"]) ?>"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?= base_url(["assets/vendor/js/template-customizer.js"]) ?>"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= base_url(["assets/js/config.js"]) ?>"></script>
</head>
<!-- END: Head-->

<body>
    <!-- Content -->

    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
            <div class="authentication-inner py-3">
                <!-- Register Card -->
                <div class="card p-md-7 p-1">
                    <!-- Logo -->
                    <!-- /Logo -->
                    <div class="divider mb-2">
                        <div class="divider-text">
                            <?= isset(configInfo()['name_app']) ? configInfo()['name_app'] : 'Name' ?>
                        </div>
                    </div>

                    <div class="card shadow-none bg-transparent border border-danger" style="display:none" id="card-error">
                        <div class="card-body text-danger">
                            <h5 class="card-title text-danger"></h5>
                            <p class="card-text"></p>
                        </div>
                    </div>

                    <div class="card shadow-none bg-transparent border border-success" style="display:none" id="card-success">
                        <div class="card-body text-success">
                            <h5 class="card-title text-success"></h5>
                            <p class="card-text"></p>
                        </div>
                    </div>

                    <div class="card-body">
                        <h4 class="mb-5">Registrate</h4>

                        <form id="formAuthentication" class="mb-5" action="index.html" method="GET"  onsubmit="onSubmit(event)">
                            <div class="form-floating form-floating-outline mb-5">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder=""
                                    autofocus />
                                <label for="name">Nombres y Apellidos</label>
                            </div>
                            <div class="form-floating form-floating-outline mb-5">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="username"
                                    name="username"
                                    placeholder=""
                                    autofocus />
                                <label for="username">Nombre de usuario</label>
                            </div>
                            <div class="form-floating form-floating-outline mb-5">
                                <input type="text" class="form-control" id="email" name="email" placeholder="" />
                                <label for="email">Correo electrónico</label>
                            </div>
                            <div class="mb-5 form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input
                                            type="password"
                                            id="password"
                                            class="form-control"
                                            name="password"
                                            placeholder=""
                                            aria-describedby="password" />
                                        <label for="password">Contraseña</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                                </div>
                            </div>

                            <div class="mb-5 form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input
                                            type="password"
                                            id="confirm-password"
                                            class="form-control"
                                            name="confirm-password"
                                            placeholder=""
                                            aria-describedby="confirm-password" />
                                        <label for="confirm-password">Confirmar Contraseña</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                                </div>
                            </div>
                            
                            <button class="btn btn-primary d-grid w-100">Registrarme</button>
                        </form>

                        <p class="text-center">
                            <span>¿Ya tienes una cuenta?</span>
                            <a href="<?= base_url(['login']) ?>">
                            <span>Inicia Sesión</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- Register Card -->
            </div>
        </div>
    </div>

<script>
    localStorage.setItem('url', '<?= base_url() ?>')
    </script>

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

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?= base_url(["assets/vendor/libs/@form-validation/popular.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/@form-validation/bootstrap5.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/@form-validation/auto-focus.js"]) ?>"></script>

    <!-- Main JS -->
    <script src="<?= base_url(["assets/js/main.js"]) ?>"></script>

    <!-- Page JS -->
    <!-- <script src="<?= base_url(["assets/js/pages-auth.js"]) ?>"></script> -->
    <script src="<?= base_url(["master/js/functions/functions.js"]) ?>"></script>
    <script src="<?= base_url(["master/js/auth/register.js"]) ?>"></script>
</body>

</html>