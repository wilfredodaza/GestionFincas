<?= view('layouts/header') ?>
<?= view('layouts/navbar_horizontal') ?>
<?= view('layouts/navbar_vertical') ?>

    <!-- BEGIN: Page Main-->
    <div id="main">
        <div class="row">
            <div class="col s12">

                <div class="container">

                    <div class="section">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-title">Listado de Versiones</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <ul class="collapsible collapsible-accordion">
                                            <li>
                                                <div class="collapsible-header"><i class="material-icons">arrow_upward</i> Version 2.0
                                                    <span class="badge new orange" data-badge-caption="23 de junio 2020"></span>
                                                </div>
                                                <div class="collapsible-body" style="display: block;">
                                                    <ul>
                                                        <li>Se implementa plantilla materialize LTE.</li>
                                                        <li>Se instala modulo de notificaciones.</li>
                                                        <li>Se relacionan las tablas de configuracion del cms.</li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="collapsible-header"><i class="material-icons">arrow_upward</i> Version 1.0
                                                    <span class="badge new green" data-badge-caption="28 de mayo 2020"></span>
                                                </div>
                                                <div class="collapsible-body">
                                                    <ul>
                                                        <li>Se implementa grocery crud enterprise.</li>
                                                        <li>Actualizacion de codeigniter 3 a codeigniter 4.</li>
                                                        <li>Se implementa plantilla gentella.</li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= view('layouts/footer') ?>