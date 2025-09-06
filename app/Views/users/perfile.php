<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
          content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google.">
    <meta name="keywords"
          content="materialize, admin template, dashboard template, flat admin template, responsive admin template, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
    <title><?= isset(configInfo()['name_app']) ? configInfo()['name_app'] : '' ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="apple-touch-icon" href="<?= !isset(configInfo()['favicon']) ||  empty(configInfo()['favicon']) ? base_url().'/assets/img/logo.png' :  base_url().'/assets/upload/images/'.configInfo()['favicon']   ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= !isset(configInfo()['favicon']) ||  empty(configInfo()['favicon']) ? base_url().'/assets/img/logo.png' :  base_url().'/assets/img/'.configInfo()['favicon']   ?>">

    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/vendors.min.css">
    <!-- END: VENDOR CSS-->
    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/materialize2.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/style.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/dashboard.css">
    <!-- END: Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/custom.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/page-users.css">
    <link rel="stylesheet" href="<?= base_url() ?>/dropify/css/dropify.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/flag-icon.min.css">


    <!-- END: Custom CSS-->


</head>

<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-dark-menu preload-transitions 2-columns   "
      data-open="click" data-menu="vertical-dark-menu" data-col="2-columns">
<?= view('layouts/navbar_horizontal') ?>
<?= view('layouts/navbar_vertical') ?>

<!-- BEGIN: Page Main-->
<div id="main">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <?php session() ?>
                <!-- users edit start -->
                <div class="section users-edit">
                    <div class="card">
                        <div class="col s12">
                            <?php if (session('success')): ?>
                                <div class="card-alert card green">
                                    <div class="card-content white-text">
                                        <p><?= session('success') ?></p>
                                    </div>
                                    <button type="button" class="close white-text" data-dismiss="alert"
                                            aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>

                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <!-- <div class="card-body"> -->
                            <div class="card-title">Perfil</div>
                            <div class="divider mb-3"></div>
                            <div class="row">

                                <div class="col s12" id="account">
                                    <!-- users edit media object start -->
                                    <div class="media display-flex align-items-center mb-2">
                                        <a class="mr-2" href="#">
                                            <img src="<?= !empty(session('user')->photo) ? base_url() . '/upload/images/' . session('user')->photo : base_url() . '/assets/img/user.png' ?>"
                                                 alt="users avatar" class="z-depth-4 circle" height="64" width="64">
                                        </a>
                                        <div class="media-body">
                                            <h5 class="media-heading mt-0">Foto</h5>
                                            <div class="user-edit-btns display-flex">
                                                <a href="#update-file"
                                                   class="btn-small indigo  modal-trigger" data-toggle="modal">Cambiar</a>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="/update_photo" method="post" enctype="multipart/form-data">

                                        <div id="update-file" class="modal" id="update-file" role="dialog" style="height: 400px;">
                                            <div class="modal-content">
                                                <h4>Subir Imagen</h4>
                                                <div class="col s12">

                                                    <div class="container">
                                                        <div class="section">
                                                            <div class="divider mb-1"></div>
                                                            <!--file-upload-->
                                                            <div id="file-upload" class="section">
                                                                <!--Default version-->
                                                                <div class="row section">
                                                                    <div class="col s12 m12 l12">
                                                                        <input type="file" name="photo"
                                                                               id="input-file-now" class="dropify"
                                                                               data-default-file=""/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="content-overlay"></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="#!"
                                                   class="modal-action modal-close waves-effect waves-red btn-flat mb-5 ">Cerrar</a>
                                                <button
                                                   class="modal-action modal-close waves-effect waves-green btn indigo mb-5">Guardar</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit media object ends -->
                                    <!-- users edit account form start -->
                                    <form id="accountForm" action="/update_user" method="post">
                                        <div class="row">
                                            <div class="col s12 m6">
                                                <div class="row">
                                                    <div class="col s12 input-field">
                                                        <input id="name" name="name" type="text"
                                                               value="<?= $data->name ?>">
                                                        <label for="name">Nombre y Apellidos</label>
                                                        <small class=" red-text text-darken-4"><?= $validation->getError('name') ?></small>

                                                    </div>
                                                    <small class=" red-text text-darken-4"><?= $validation->getError('name') ?></small>
                                                    <div class="col s12 input-field">
                                                        <input id="email" name="email" type="email"
                                                               value="<?= $data->email ?>">
                                                        <label for="email">Correo electronico</label>
                                                        <small class=" red-text text-darken-4"><?= $validation->getError('email') ?></small>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col s12 m6">
                                                <div class="row">
                                                    <div class="col s12 input-field">
                                                        <input id="username" name="username" type="text"
                                                               value="<?= $data->username ?>">
                                                        <label for="username">Nombre de usuario</label>
                                                        <small class=" red-text text-darken-4"><?= $validation->getError('username') ?></small>
                                                    </div>
                                                    <div class="col s12 input-field  disabled">
                                                        <input id="role_id" name="rol" value="<?= $data->role_name ?>" type="text" disabled>
                                                        <label for="role_id">Rol</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col s12 display-flex justify-content-end mt-3">
                                                <button type="submit" class="btn indigo">Actualizar</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- users edit account form ends -->
                                </div>

                            </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <!-- users edit ends -->


            </div>
            <div class="content-overlay"></div>
        </div>
    </div>
</div>

<footer class="page-footer footer footer-static footer-light navbar-border navbar-shadow">
    <div class="footer-copyright">
        <div class="container"><span>&copy;<?= date('Y') ?> <a href="https://www.iplanetcolombia.com/"
                                                               target="_blank">IPlanet Colombia</a>Todos los derechos reservados.</span>
        </div>
    </div>
</footer>

<script src="<?= base_url() ?>/assets/js/vendors.min.js"></script>
<script src="<?= base_url() ?>/assets/js/plugins.min.js"></script>
<script src="<?= base_url() ?>/assets/js/search.min.js"></script>
<script src="<?= base_url() ?>/assets/js/custom-script.min.js"></script>
<script src="<?= base_url() ?>/assets/js/advance-ui-modals.js"></script>
<script src="<?= base_url() ?>/dropify/js/dropify.min.js"></script>
<script src="<?= base_url() ?>/assets/js/form-file-uploads.js"></script>
<script src="<?= base_url() ?>/assets/js/ui-alerts.js"></script>
</body>
</html>

