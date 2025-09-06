<?= $this->extend('layouts/page'); ?>

<?= $this->section('title'); ?> - Compras<?= $this->endSection(); ?>


<?= $this->section('styles') ?>
    <?= $this->include('layouts/css_datatables') ?>
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/select2/select2.css']) ?>" />
    <link rel="stylesheet" href="<?= base_url(['assets/vendor/libs/flatpickr/flatpickr.css']) ?>" />
<?= $this->endsection('styles') ?>

<?= $this->section('content') ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-6">
        <div class="col-md-12 col-xxl-12 mt-0">
            <div class="row gy-6">

                <div class="col-12">
                    <div class="card">
                        <div class="card-widget-separator-wrapper">
                            <div class="card-body">
                                <div class="row gy-4 gy-sm-1">

                                    <h4 class="card-title mb-0 text-center">
                                        <?= $resource->name ?>
                                    </h4>
                                    <h5 class="card-title mb-0 text-center">
                                        <b>Unidad de Medida:</b> <?= "{$resource->measurement_unit->name} - {$resource->measurement_unit->code}" ?>
                                        <?php if($resource->id == 1): ?>
                                            <br><b>Total Jornales:</b> <?= "{$resource->suma_jornales}" ?>
                                        <?php endif ?>
                                    </h5>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($resource->id != 1): ?>

            <div class="col-sm-12 col-lg-4">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-success">
                            <i class="ri-arrow-left-down-line ri-24px"></i>
                            </span>
                            </div>
                            <h4 class="mb-0">Entrada</h4>
                        </div>
                        <div class="row g-6">
                            <div class="col-sm-12 col-lg-12 d-flex justify-content-center">
                            <p class="mb-0">
                                <span class="me-1 fw-medium"><?= number_format($resource->suma_entrada, 0, ',', '.') ?></span>
                            </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-4">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-danger">
                                <i class="ri-arrow-right-up-line ri-24px"></i>
                            </span>
                            </div>
                            <h4 class="mb-0">Salida</h4>
                        </div>
                        <div class="row g-6">
                            <div class="col-sm-12 col-lg-12 d-flex justify-content-center">
                            <p class="mb-0">
                                <span class="me-1 fw-medium"><?= number_format($resource->suma_salida, 0, ',', '.') ?></span>
                            </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-4">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-info">
                                <i class="ri-arrow-right-up-line ri-24px"></i>
                            </span>
                            </div>
                            <h4 class="mb-0">Balance</h4>
                        </div>
                        <div class="row g-6">
                            <div class="col-sm-12 col-lg-12 d-flex justify-content-center">
                            <p class="mb-0">
                                <span class="me-1 fw-medium"><?= number_format(((float)$resource->suma_entrada - (float)$resource->suma_salida), 0, ',', '.') ?></span>
                            </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif ?>

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
</div>

<?= $this->endsection('content') ?>

<?= $this->section('javaScripts') ?>
    <script src="<?= base_url(['assets/vendor/libs/select2/select2.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/flatpickr/flatpickr.js']) ?>"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script>
        const resource = (<?= json_encode($resource) ?>);
        const infoPage = () => {
            return {title: "Kardex <?= $resource->name ?>"}
        };
    </script>
    <?= $this->include('layouts/js_datatables') ?>


    <script src="<?= base_url(['master/js/resources/kardex.js?v='.getCommit()]) ?>"></script>
<?= $this->endsection('javaScript') ?>