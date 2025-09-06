<?= $this->extend('layouts/page'); ?>

<?= $this->section('title'); ?> - <?= $data->title ?><?= $this->endSection(); ?>


<?= $this->section('styles') ?>
    <?= $this->include('layouts/css_datatables') ?>
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/select2/select2.css']) ?>" />
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/flatpickr/flatpickr.css']) ?>" />
<?= $this->endsection('styles') ?>

<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
        <div class="col-md-12 col-xxl-12">
            <div class="row gy-6">
                <!-- <h4 class="card-title mb-0 text-center">
                    <?= $data->title ?>
                </h4> -->

                <!-- <div class="indicadores row">
                </div> -->

                <!-- <div class="col-12">
                    <div class="card">
                        <div class="card-widget-separator-wrapper">
                            <div class="card-body">
                                <div class="row gy-4 gy-sm-1">

                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

        <div class="col-md-12 col-xxl-12">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-md-12">
                        <div class="card-body py-0">
                            <div class="col s12 card-datatable ">
                                <table class="datatables-basic table table-bordered text-center h-100" id="table_datatable"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-3">
    
        <div class="col-lg-3 col-md-6">
        
            <div
                class="offcanvas offcanvas-end"
                tabindex="-1"
                id="canvasProductiveUnit"
                aria-labelledby="canvasProductiveUnitLabel">
                    <div class="offcanvas-header">
                        <h5 id="canvasProductiveUnitLabel" class="offcanvas-title"></h5>
                        <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body mx-0 flex-grow-0">
                        <form action="" id="form-productive-unit" onsubmit="saveProductiveUnit(event)">
                            <div class="row">

                                <?php foreach($data->form as $input): ?>
                                    <?php switch($input->type):
                                        case 'hidden': ?>
                                            <input type="hidden" name="<?= $input->name ?>" id="<?= $input->name ?>" value="<?= $input->value ?>">
                                        <?php break;
                                        case 'select': ?>
                                            <div class="col-sm-12 mb-2">
                                                <div class="form-floating form-floating-outline">
                                                    <select class="form-select <?= $input->required ? "required" : "" ?>" id="<?= $input->name ?>" name="<?= $input->name ?>">
                                                        <option value="">Seleccionar</option>
                                                        <?php foreach($input->options as $option): ?>
                                                            <option value="<?= $option->id ?>" <?= $input->value == $option->id ? "selected" : "" ?>><?= "{$option->name}" ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <label for="<?= $input->name ?>"><?= $input->required ? "* " : "" ?><?= $input->title ?></label>
                                                    <span class="form-floating-focused"></span>
                                                </div>
                                            </div>
                                        <?php break;
                                        case 'number': ?>
                                            <div class="col-sm-12 mb-2">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" onkeyup="onlyNumericKeypress(event)" class="form-control <?= $input->required ? "required" : "" ?>" name="<?= $input->name ?>" id="<?= $input->name ?>" placeholder="">
                                                    <label for="<?= $input->name ?>"><?= $input->required ? "* " : "" ?><?= $input->title ?></label>
                                                </div>
                                            </div>
                                        <?php break;
                                        case 'date': ?>
                                            <div class="col-sm-12 mb-2">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" class="form-control date-input <?= $input->required ? "required" : "" ?>" placeholder="YYYY-MM-DD" name="<?= $input->name ?>" id="<?= $input->name ?>" placeholder="">
                                                    <label for="<?= $input->name ?>"><?= $input->required ? "* " : "" ?><?= $input->title ?></label>
                                                </div>
                                            </div>
                                        <?php break;
                                        default: ?>
                                            <div class="col-sm-12 mb-2">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" class="form-control <?= $input->required ? "required" : "" ?>" name="<?= $input->name ?>" id="<?= $input->name ?>" placeholder="">
                                                    <label for="<?= $input->name ?>"><?= $input->required ? "* " : "" ?><?= $input->title ?></label>
                                                </div>
                                            </div>
                                        <?php break; ?>
                                    <?php endswitch ?>
                                <?php endforeach ?>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary mb-2 d-grid w-100 waves-effect waves-light">Guardar</button>
                            <button type="button" class="btn btn-danger mb-2 d-grid w-100 waves-effect waves-light" data-bs-dismiss="offcanvas">Cerrar</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>

</div>



<?= $this->endsection('content') ?>

<?= $this->section('javaScripts') ?>
    <script src="<?= base_url(['assets/vendor/libs/select2/select2.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/flatpickr/flatpickr.js']) ?>"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script>
        const infoPage = () => (<?= json_encode($data) ?>);
        const lotInfo = () => (<?= json_encode($lot) ?>);
    </script>
    <?= $this->include('layouts/js_datatables') ?>


    <script src="<?= base_url(['master/js/productive_units/index.js?v='.getCommit()]) ?>"></script>
<?= $this->endsection('javaScript') ?>