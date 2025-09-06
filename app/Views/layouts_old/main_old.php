<?php $navbars = navbar() ?>
<?php $head = head() ?>
<?php $piePagina = footer() ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="<?=base_url('assets/js/talwindcss.min.js') ?>"></script>
  <link href="<?= base_url('assets/js/flowbite.min.css') ?>" rel="stylesheet" />
  <title> <?= isset($head->title)? $head->title : '' ?><?= $this->renderSection('title', "Home") ?></title>
  <meta name="description" content="<?= $this->renderSection('meta_descripcion',  $head->description) ?>">
  <meta name="keywords" content="<?= $this->renderSection('keywords', $head->key_words) ?>">
<link rel="canonical" href="https://landing.mawii.com.co/" />
  <link rel="icon" href="<?= base_url('assets/img/head/' . $head->favicon) ?>" type="image/png" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Libre+Franklin:ital,wght@0,100..900;1,100..900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link href="<?= base_url('assets/css/styles-landing.css') ?>" rel="stylesheet">
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@mawii" />
  <meta name="twitter:title" content="<?= !empty($head->title) ? $head->title : 'Iplanet' ?>" />
  <meta name="twitter:description" content="<?= isset($head->description) ? $head->description : 'Name' ?>" />
  <meta name="twitter:image" content="assets/Page/head/<?= !empty($head->favicon) ? $head->favicon : './assets/Page/fondo-ingresar/icono-01.png' ?>" />
  <meta property="og:url"                content="https://mawii.com.co" />
  <meta property="og:type"               content="article" />
  <meta property="og:title"              content="<?= !empty($head->title) ? $head->title : 'Iplanet' ?>" />
  <meta property="og:description"        content="<?= isset($head->description) ? $head->description : 'Name' ?>" />
  <meta property="og:image"              content="assets/Page/head/<?= !empty($head->favicon) ? $head->favicon  : './assets/Page/fondo-ingresar/icono-01.png'  ?>" />
  <style>
      
      html {
    scroll-padding-top: 80px; /* Ajusta este valor según la altura de tu navbar */
}

.responsive-div {
    width: 50% !important; /* Por defecto, para pantallas más grandes */
}

/* Estilos para dispositivos con pantallas de al menos 768px de ancho (portátiles) */
@media (min-width: 768px) {
    .responsive-div {
        width: 50% !important; /* Reafirmar el estilo para pantallas grandes */
    }
}

/* Estilos para dispositivos con pantallas menores a 768px de ancho (móviles) */
@media (max-width: 768px) {
    .responsive-div {
        width: 100% !important; /* Ajustar para pantallas móviles */
    }
}
    
  </style>
  <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MB573MD4');</script>
<!-- End Google Tag Manager -->

</head>
<body class="<?= $this->renderSection('bg', 'bg-white') ?>">
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MB573MD4"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!--- Navbar ---->

<nav class="bg-gray-50 fixed w-full z-40 top-0 start-0 shadow-md">
  <div class="max-auto container flex flex-wrap items-center justify-between mx-auto p-1 ">
  <a href="<?= base_url() ?>" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img data-src="<?= base_url('assets/img/head/' . $head->logo) ?>" class=" lazyload" width="115px" height="75px" alt="Logo Mawii" />
    </a>
    <button data-collapse-toggle="navbar-default" type="button" aria-label="Menu"
      class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-pink-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
      aria-controls="navbar-default" aria-expanded="false">
      <span class="sr-only">Open main menu</span>
      <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M1 1h15M1 7h15M1 13h15" />
      </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto"  id="navbar-default">
      <ul
        class="font-medium flex flex-col md:items-center p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <?php foreach ($navbars as $navbar): ?>
          <li>
            <?php if ($navbar->tipo == "Opcion"): ?>
              <a href="<?= $navbar->url ?>"
                class="block py-2 px-3 text-blue-950 rounded font-bold hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent"><?= $navbar->opcion ?></a>
            <?php else: ?>
              <button aria-label="Facturacion electronica"  aria-labelledby="Facturacion-electronica" onclick="location.href = '<?= $navbar->url ?>'"
                class="text-white bg-blue-950 hover:bg-gradient-to-bl font-bold focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800  rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                aria-current="page"><?= $navbar->opcion ?><button />
              <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</nav>
<!---- End Navbar --->


<?= $this->renderSection('content') ?>
<br>

<footer class="bg-blue-950 mt-12 hidden md:block">
  <div class="container mx-auto  grid grid-cols-3 flex justify-center p-8">
    <div>
      <p class="text-white text-center font-bold pb-5"><?= $piePagina->titulo_1 ?></p>
      <?php if (!empty($piePagina->logo)): ?>
        <div class="flex justify-center">
          <img  class="lazyload"  width="200" height="110"  data-src="<?= base_url("assets/img/pie_pagina/" . $piePagina->logo) ?>" alt="logo-mawii">
        </div>
      <?php endif; ?>
    </div>
    <div class="">
      <div>
      <h1 class="text-white font-bold text-center"><?= $piePagina->titulo_2 ?></h1>
      </div>
      <div class="mx-auto">
        <div class="flex jusfity-center" style="justify-content:center;">
          <?php if (!empty($piePagina->link_instagram)): ?>
            <a href="<?= $piePagina->link_instagram ?>" target="_blank" aria-label="instagram">
              <svg class="w-12 h-12 text-white dark:text-white m-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="50" height="50" fill="none" viewBox="0 0 24 24">
                <path fill="currentColor" fill-rule="evenodd"
                  d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                  clip-rule="evenodd" />
              </svg>
            </a>
          <?php endif; ?>
          <?php if (!empty($piePagina->link_linkeding)): ?>
            <a href="<?= $piePagina->link_linkeding ?>" target="_blank" aria-label="linkeding">
              <svg  class="w-12 h-12 text-white  dark:text-white m-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
            </a>
          <?php endif; ?>
          <?php if (!empty($piePagina->link_whatsapp)): ?>
            <a href="<?= $piePagina->link_whatsapp ?>" target="_blank" aria-label="whatsapp">
              <svg class="w-12 h-12 text-white  dark:text-white m-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path fill="currentColor" fill-rule="evenodd"
                  d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z"
                  clip-rule="evenodd" />
                <path fill="currentColor"
                  d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z" />
              </svg>
            </a>
          <?php endif; ?>
          <?php if (!empty($piePagina->link_facebook)): ?>
             <a href="<?= $piePagina->link_facebook ?>" target="_blank" aria-label="facebook">
            <svg class="w-12 h-12 text-white  dark:text-white m-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64h98.2V334.2H109.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H255V480H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"/></svg> </a>
              </a>
          <?php endif; ?>

        </div>
      </div>
      <div class="flex justify-center mt-10">
        <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-white dark:text-white sm:mt-0">
          <li>
          <a href="<?= $piePagina->terminos_y_condiciones_link ?>" class="hover:underline text-center px-1"><?= $piePagina->terminos_y_condiciones_texto ?></a>
          </li>
          <li>
          <a href="<?= $piePagina->politicas_de_privacidad_link ?>" class="hover:underline text-center px-1"><?= $piePagina->politicas_de_privacidad_texto ?></a>
          </li>
        </ul>
        </div>
  <p class="hover:underline me-4 md:me-3 text-center text-white text-sm">&copy;<?= date('Y') ?>. Mawii</p>
  
 </div>
    <div class="text-center text-white">
    <h1 class="font-bold mb-3"><?= $piePagina->titulo_3 ?></h1>
      <p class="mb-2 font-bold"><?= $piePagina->ciudad ?></p>
      <?php if (!empty($piePagina->telefono)): ?>
        <p class="mb-2 font-bold"><?= $piePagina->telefono ?></p>
      <?php endif; ?>
      <?php if (!empty($piePagina->email)): ?>
        <p class="mb-2 font-bold"><?= $piePagina->email ?></p>
      <?php endif; ?>
    </div>
  </div>
</footer>

<footer class="bg-blue-950  mt-12  md:hidden">
  <div class="pt-8">
    <div>
    <h1 class="text-white font-bold text-center"><?= $piePagina->titulo_2 ?></h1>
    </div>
    <div class="mx-auto pb-8">
      <div class="flex jusfity-center" style="justify-content:center;">
        <?php if (!empty($piePagina->link_instagram)): ?>
          <a href="<?= $piePagina->link_instagram ?>" target="_blank" aria-label="instagram">
            <svg class="w-12 h-12 text-white dark:text-white m-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="50" height="50" fill="none" viewBox="0 0 24 24">
              <path fill="currentColor" fill-rule="evenodd"
                d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                clip-rule="evenodd" />
            </svg>
          </a>
        <?php endif; ?>
        <?php if (!empty($piePagina->link_linkeding)): ?>
          <a href="<?= $piePagina->link_linkeding ?>" target="_blank" aria-label="linkeding">
            <svg class="w-12 h-12 text-white  dark:text-white m-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd"
                d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                clip-rule="evenodd" />
              <path d="M7.2 8.809H4V19.5h3.2V8.809Z" />
            </svg>
          </a>
        <?php endif; ?>
        <?php if (!empty($piePagina->link_whatsapp)): ?>
          <a href="<?= $piePagina->link_whatsapp ?>" target="_blank" aria-label="whatsapp">
            <svg class="w-12 h-12 text-white  dark:text-white m-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path fill="currentColor" fill-rule="evenodd"
                d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z"
                clip-rule="evenodd" />
              <path fill="currentColor"
                d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z" />
            </svg>
          </a>
        <?php endif; ?>
        <?php if (!empty($piePagina->link_facebook)): ?>
          <a href="<?= $piePagina->link_facebook ?>" target="_blank" aria-label="facebook">
            <svg class="w-12 h-12 text-white  dark:text-white m-3 aria-hidden=" true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd"
                d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z"
                clip-rule="evenodd" />
            </svg>
          </a>
        <?php endif; ?>

      </div>
    </div>

  </div>
  <div class="text-center text-white">
  <h1 class="font-bold mb-3"><?= $piePagina->titulo_3 ?></h1>
  <p class="mb-2 font-bold"><?= $piePagina->ciudad ?></p>
    <?php if (!empty($piePagina->telefono)): ?>
      <p class="mb-2 font-bold"><?= $piePagina->telefono ?></p>
    <?php endif; ?>
    <?php if (!empty($piePagina->email)): ?>
      <p class="mb-2 font-bold"><?= $piePagina->email ?></p>
    <?php endif; ?>
  </div>
  </div>

  <div class="container mx-auto flex justify-center p-8">
    <div>
      <p class="text-white text-center font-bold pb-5"><?= $piePagina->titulo_1 ?></p>
      <?php if (!empty($piePagina->logo)): ?>
        <div class="flex justify-center">
          <img class="lazyload" width="200" height="110" data-src="<?= base_url("assets/img/pie_pagina/" . $piePagina->logo) ?>" alt="logo-mawii">
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="flex justify-center">
    <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-white dark:text-white sm:mt-0">
      <li>
      <a href="<?= $piePagina->terminos_y_condiciones_link ?>" class="hover:underline text-center me-4 md:me-6"><?= $piePagina->terminos_y_condiciones_texto ?></a>
      </li>
      <li>
      <a href="<?= $piePagina->politicas_de_privacidad_link ?>" class="hover:underline text-center me-4 md:me-6"><?= $piePagina->politicas_de_privacidad_texto ?></a>
      </li>
    </ul>
  </div>
  <p class="text-center text-white pb-10"> &copy; <?= date('Y') ?>. Mawii</p>
</footer>
<div class="fixed bottom-5 right-5 rounded-full">
<a href="<?= $piePagina->link_whatsapp ?>" target="_blank" aria-label="whatsapp"
    class="block bg-green-500  rounded-full">
                <svg class="w-16 h-16 text-white  dark:text-white " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path fill="currentColor" fill-rule="evenodd"
                d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z"
                clip-rule="evenodd" />
              <path fill="currentColor"
                d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z" />
            </svg>
  </a>
</div>


<script src="<?= base_url('assets/js/flowbite.min.js') ?>"></script>
<script>
    function shareOnFacebook(addUrl) {
        var url = encodeURIComponent(addUrl)
        var shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
        window.open(shareUrl, '_blank');
    }

    function shareOnTwitter(addUrl) {
        var url = encodeURIComponent(addUrl);
        var shareUrl = 'https://twitter.com/intent/tweet?url=' + url;
        window.open(shareUrl, '_blank');
    }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var lazyImages = [].slice.call(document.querySelectorAll("img.lazyload"));

    if ("IntersectionObserver" in window) {
      let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            let lazyImage = entry.target;
            lazyImage.src = lazyImage.dataset.src;
            lazyImage.classList.remove("lazyload");
            lazyImageObserver.unobserve(lazyImage);
          }
        });
      });

      lazyImages.forEach(function(lazyImage) {
        lazyImageObserver.observe(lazyImage);
      });
    } else {
      // Fallback for browsers that do not support IntersectionObserver
    }
  });
</script>
    <script>
     window.dataLayer = window.dataLayer || [];
     function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-58KVMND4BR');
     </script>
</body>
</html>

