<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
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
    <title><?= isset(configInfo()['name_app']) ? !empty(configInfo()['name_app']) : 'Name' ?></title>
    <link rel="apple-touch-icon" href="<?= !isset(configInfo()['favicon']) ||  empty(configInfo()['favicon']) ? base_url(['/assets/img/logo.png']) :  base_url(['/assets/upload/images/'.configInfo()['favicon']])   ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= !isset(configInfo()['favicon']) ||  empty(configInfo()['favicon']) ? base_url(['/assets/img/logo.png']) :  base_url(['/assets/img/'.configInfo()['favicon']])   ?>">
    <title><?= isset(configInfo()['name_app']) ? configInfo()['name_app'] : '' ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(["assets/css/vendors.min.css"]) ?> ">
    <!-- END: VENDOR CSS-->


    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(["assets/css/materialize.min.css"]) ?> ">
    <link rel="stylesheet" type="text/css" href="<?= base_url(["assets/css/style.min.css"]) ?> ">
    <link rel="stylesheet" type="text/css" href="<?= base_url(["assets/css/dashboard.css"]) ?> ">
    <!-- END: Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(["assets/css/custom.min.css"]) ?> ">
    <!-- END: Custom CSS-->

    <link rel="stylesheet" href="<?= base_url(["grocery-crud/css/jquery-ui/jquery-ui.css"]) ?> ">
    <link rel="stylesheet" href="<?= base_url(["grocery-crud/css/grocery-crud-v2.8.1.0659b25.css"]) ?> ">
    <link rel="stylesheet" href="<?= base_url(["grocery-crud/css/bootstrap/bootstrap.css"]) ?> ">
    <link rel="stylesheet" href="<?= base_url(["assets/css/iplanet.css"]) ?> ">
    <script src="<?= base_url(["assets/ckeditor/ckeditor.js"]) ?> "></script>

    

    <style>
        :root {
            --primary-color: #<?= isset(configInfo()['primary_color']) && !empty(configInfo()['primary_color']) ? configInfo()['primary_color'] : '8e24aa' ?>;
            --secondary-color: #<?= isset(configInfo()['secundary_color']) && !empty(configInfo()['secundary_color']) ? configInfo()['secundary_color'] : 'ff6e40' ?>;
            --primary-rgb: <?= isset(configInfo()['primary_color']) && !empty(configInfo()['primary_color']) ? hexToRgb(configInfo()['primary_color']) : hexToRgb('8e24aa') ?>;
            --secondary-rgb: <?= isset(configInfo()['secundary_color']) && !empty(configInfo()['secundary_color']) ? hexToRgb(configInfo()['secundary_color']) : hexToRgb('ff6e40') ?>;
        }   
    </style>

</head>
<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-dark-menu preload-transitions 2-columns   "
      data-open="click" data-menu="vertical-dark-menu" data-col="2-columns">
