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
        <div class="col-md-12 col-xxl-12">
            <div class="row gy-6">

                <div class="col-12">
                    <div class="card">
                        <div class="card-widget-separator-wrapper">
                            <div class="card-body">
                                <div class="row gy-4 gy-sm-1">

                                    <h4 class="card-title mb-0 text-center">
                                        Actividades
                                    </h4>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
</div>

<?= $this->endsection('content') ?>

<?= $this->section('javaScripts') ?>
    <script src="<?= base_url(['assets/vendor/libs/select2/select2.js']) ?>"></script>
    <script src="<?= base_url(['assets/vendor/libs/flatpickr/flatpickr.js']) ?>"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <?= $this->include('layouts/js_datatables') ?>

    <script src="<?= base_url(['master/js/movements/activities.js?v='.getCommit()]) ?>"></script>
<?= $this->endsection('javaScript') ?>