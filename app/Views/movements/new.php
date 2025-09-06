<?= $this->extend('layouts/page'); ?>

<?= $this->section('title'); ?> - Crear <?= $movement_type->name ?><?= $this->endSection(); ?>


<?= $this->section('styles') ?>
    <?= $this->include('layouts/css_datatables') ?>
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/select2/select2.css']) ?>" />
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/flatpickr/flatpickr.css']) ?>" />
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/dropzone/dropzone.css']) ?>" />
<?= $this->endsection('styles') ?>

<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
    <div class="col-md-12 col-xxl-12">
            <div class="card">
                <div class="d-flex align-items-end row mb-1">
                    <div class="col-md-12">
                        <div class="card-body">
                            <h4 class="card-title mb-4 text-center">Crear <?= $movement_type->name ?></h4>
                            <form action="javascript:void(0);" id="form_bill" onsubmit="createActivity(event)">
                                <div class="row">

                                    <div class="col-sm-12 col-lg-4 col-md-6 mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control required" placeholder="YYYY-MM-DD" id="movement_date">
                                            <label for="movement_date">* Fecha de <?= $movement_type->name ?></label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12 col-lg-4 col-md-6 mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <select
                                            onchange="reloadTable()"
                                            class="form-select form-select-lg newSelect required" placeholder="Seleccionar finca"
                                            id="farm_id" name="farm_id">
                                                <!-- <option value="" disabled selected>Seleccione una finca</option> -->
                                                <?php foreach(session('user')->farms as $farm): ?>
                                                    <option value="<?= $farm->id ?>" <?= $movement_type->id == 3 ? ($movement->farm_id == $farm->id ? "selected" : "") : "" ?>><?= $farm->name ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <label for="farm_id">* Añadir finca</label>
                                        </div>
                                    </div>

                                    <?php
                                        $lg_p = 4;
                                        if($movement_type->id == 1): ?>
                                        <?php $lg_p = 5; ?>
                                        <div class="col-sm-12 col-lg-4 col-md-6 mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <select
                                                class="form-select form-select-lg newSelect" placeholder="Seleccionar proveedor"
                                                id="provider_id" name="provider_id">
                                                    <option value="" disabled selected>Seleccione proveedor</option>
                                                    <?php foreach($providers as $provider): ?>
                                                        <option value="<?= $provider->id ?>"><?= "$provider->name - $provider->number" ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label for="provider_id">Añadir proveedor</label>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-lg-5 col-md-6 mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control required" placeholder="Numero de factura" id="number_bill">
                                                <label for="number_bill">* Numero de referencia</label>
                                            </div>
                                        </div>
                                    <?php elseif($movement_type->id == 2): ?>
                                        <div class="col-sm-12 col-lg-4 col-md-6 mb-2">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control required" placeholder="" id="title">
                                                <label for="title">* Titulo de <?= $movement_type->name ?></label>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    
                                    <div class="col-sm-12 col-lg-<?= $lg_p ?> col-md-6 mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <select
                                            onchange="changeResource(this.value)"
                                            class="form-select form-select-lg newSelect" placeholder="Seleccionar producto"
                                            id="resource_id" name="resource_id">
                                                <option value="" disabled selected>Seleccione un producto</option>
                                                <?php foreach($resources as $resource): ?>
                                                    <option value="<?= "$resource->id" ?>"><?= $resource->name ?></option>
                                                    
                                                <?php endforeach ?>
                                            </select>
                                            <label for="resource_id">Producto</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-lg-2 col-md-12 mb-2 d-flex align-items-center">
                                        <button class="btn light-blue lighten-5 text-light-blue text-darken-5 w-100 waves-effect waves-light mx-2" onclick="uploadSupport()" type="button">
                                            <span><i class="ri-upload-cloud-2-line"></i> <span class="d-none d-sm-inline-block">Soporte</span></span>
                                        </button>
                                    </div>
                                    
                                    <div class="form-floating form-floating-outline col-sm-12 col-lg-12 col-md-12 mb-2">
                                        <textarea class="form-control h-px-100" id="notes" placeholder=""></textarea>
                                        <label for="notes">Observación: </label>
                                    </div>

                                    <input type="hidden" id="support_file">
                                    <input type="hidden" id="support_name">

                                    <?php if($movement_type->id == 3): ?>
                                        <input type="hidden" id="movement_reference" value="<?= $movement->id ?>">
                                    <?php endif ?>

                                    <!-- Basic  -->

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xxl-12">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-md-12">
                        <div class="card-body py-0 pt-4">
                            <div class="col s12 card-datatable bills">
                                <table class="datatables-basic table table-bordered text-center" id="table_datatable">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection('content') ?>

<?= $this->section('javaScripts') ?>
    <script src="<?= base_url(['assets/vendor/libs/select2/select2.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/flatpickr/flatpickr.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/dropzone/dropzone.js']) ?>"></script>
    <script src="<?= base_url(['assets/js/forms-file-upload.js']) ?>"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <?= $this->include('layouts/js_datatables') ?>

    <script>
        const getResources      = () => <?= json_encode($resources) ?>;
        const getMovementType   = () => (<?= json_encode($movement_type) ?>);
        const getMovement       = () => (<?= json_encode($movement) ?>);
    </script>

    <script src="<?= base_url(['master/js/movements/new.js?v='.getCommit()]) ?>"></script>
<?= $this->endsection('javaScript') ?>