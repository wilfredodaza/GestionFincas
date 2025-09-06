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
    <link rel="apple-touch-icon"
        href="<?= !isset(configInfo()['favicon']) ||  empty(configInfo()['favicon']) ? base_url().'/assets/img/logo.png' :  base_url().'/assets/upload/images/'.configInfo()['favicon']   ?>">
    <link rel="shortcut icon" type="image/x-icon"
        href="<?= !isset(configInfo()['favicon']) ||  empty(configInfo()['favicon']) ? base_url().'/assets/img/logo.png' :  base_url().'/assets/img/'.configInfo()['favicon']   ?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- BEGIN: VENDOR CSS-->

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

    <!-- Icons -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/fonts/remixicon/remixicon.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/fonts/flag-icons.css"]) ?>" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/node-waves/node-waves.css"]) ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/rtl/core.css"]) ?>"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/rtl/theme-default.css"]) ?>"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url(["assets/css/demo.css"]) ?>" />
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/toastr/toastr.css']) ?>" />
    <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/sweetalert2/sweetalert2.css"]) ?>" />

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
    <!-- END: Custom CSS-->
    <style>
    /* .login-bg
        {
            background-image: url(  <?= !isset(configInfo()['background_image']) ||  empty(configInfo()['background_image'])  ? 'https://image.freepik.com/foto-gratis/imagen-paisaje-urbano-parque-benchakitti-al-amanecer-bangkok-tailandia_29505-853.jpg' : base_url().'/assets/img/'.configInfo()['background_image'] ?>);
            background-repeat: no-repeat;
            background-size: cover;
        } */
    </style>

</head>
<!-- END: Head-->

<body>

    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
            <div class="authentication-inner py-6">
                <!-- Login -->
                <div class="card p-md-7">

                    <div class="card-body">
                        <div class="divider mb-5">
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

                        <form id="formAuthentication" class="mb-5 mt-5" action="index.html" method="GET" onsubmit="onSubmit(event)">
                            <div class="form-floating form-floating-outline mb-5">
                                <input type="text" class="form-control" id="email-username" name="email-username"
                                    placeholder="Ingrese su email o usuario" autofocus />
                                <label for="email-username">Email o Usuario</label>
                            </div>

                            <div class="form-password-toggle mb-5">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="" />
                                        <label for="password">Contraseña</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="ri-eye-off-line"></i></span>
                                </div>
                            </div>
                            <div class="form-floating form-floating-outline mb-5">
                                <input type="text" class="form-control" id="captcha" name="captcha"
                                    placeholder="Ingresar la respuesta"/>
                                <label for="captcha">
                                    Cuanto es: <?= 
                                    session('captcha')->number_a
                                    ." ".
                                        (session('captcha')->operacion == 'mas' ? "+" : (session('captcha')->operacion == 'menos' ? "-" : '*'))
                                    ." ".
                                    session('captcha')->number_b
                                    ?></label>
                            </div>
                            <div class="mb-5 d-flex justify-content-between mt-5">
                                <a href="<?= base_url(['reset_password']) ?>" class="float-end mb-1 mt-2">
                                    <span>¿Olvidó su contraseña? </span>
                                </a>
                            </div>
                            <div class="mb-5">
                                <button class="btn btn-primary d-grid w-100" type="submit" id="btn-send">Iniciar sesión</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <span>¿Eres nuevo en la plataforma?</span>
                            <a href="<?= base_url(['register']) ?>">
                                <span>Crear cuenta</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- /Login -->
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
    <script src="<?= base_url(['assets/vendor/libs/toastr/toastr.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/sweetalert2/sweetalert2.js']) ?>"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?= base_url(["assets/vendor/libs/@form-validation/popular.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/@form-validation/bootstrap5.js"]) ?>"></script>
    <script src="<?= base_url(["assets/vendor/libs/@form-validation/auto-focus.js"]) ?>"></script>

    <!-- Main JS -->
    <script src="<?= base_url(["assets/js/main.js"]) ?>"></script>

    <!-- Page JS -->
    <!-- <script src="<?= base_url(["assets/js/pages-auth.js"]) ?>"></script> -->
    <script src="<?= base_url(["master/js/functions/functions.js?v=".getCommit()]) ?>"></script>
    <script src="<?= base_url(["master/js/functions/fetchHelper.js?v=".getCommit()]) ?>"></script>
    <script src="<?= base_url(["master/js/auth/login.js?v=".getCommit()]) ?>"></script>
</body>

</html>