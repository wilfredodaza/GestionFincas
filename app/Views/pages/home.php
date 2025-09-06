<?= $this->extend('layouts/page'); ?>

<?= $this->section('styles'); ?>
  <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/select2/select2.css"]) ?>" />
  <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/pages/cards-statistics.css"]) ?>" />
  <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/pages/cards-analytics.css"]) ?>" />
  <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/fullcalendar/fullcalendar.css"]) ?>" />
  <link rel="stylesheet" href="<?= base_url(["assets/vendor/libs/flatpickr/flatpickr.css"]) ?>" />
  <link rel="stylesheet" href="<?= base_url(["assets/vendor/css/pages/app-calendar.css"]) ?>" />
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row g-6">

  <?php foreach ($kpis as $kpi) : ?>

    <div class="col-sm-6 col-lg-6">
      <div class="card card-border-shadow-<?= $kpi->movement_type_id == 1 ? 'primary' : 'warning' ?> h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-center mb-2">
            <div class="avatar me-4">
              <span class="avatar-initial rounded-3 bg-label-<?= $kpi->movement_type_id == 1 ? 'primary' : 'warning' ?>">
                <?php if($kpi->movement_type_id == 1): ?>
                  <i class="ri-draft-line ri-24px"></i>
                <?php elseif($kpi->movement_type_id == 3): ?>
                  <i class="ri-currency-line ri-24px"></i>
                <?php endif; ?>
              </span>
            </div>
            <h4 class="mb-0">$ <?= number_format($kpi->total->total, 0, ',', '.') ?> | <?= $kpi->name ?></h4>
          </div>
          <div class="row g-6">
            <div class="col-sm-12 col-lg-12 d-flex justify-content-center">
              <p class="mb-0" id="detail_<?= $kpi->movement_type_id ?>">
                <span class="me-1 fw-medium">$ <?= number_format($kpi->total_month->total, 0, ',', '.') ?></span>
                <small class="text-muted">Este mes</small>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php endforeach; ?>

  <div class="col-sm-12 col-lg-12">
    <div class="card app-calendar-wrapper">
      <div class="row g-0">

                  
                  <!-- Calendar Sidebar -->
                  <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
                    <div class="p-5 my-sm-0 mb-4 border-bottom d-none">
                      <button
                        class="btn btn-primary btn-toggle-sidebar w-100"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#addEventSidebar"
                        aria-controls="addEventSidebar">
                        <i class="ri-add-line ri-16px me-1_5"></i>
                        <span class="align-middle">Add Event</span>
                      </button>
                    </div>
                    <div class="px-4">
                      <!-- inline calendar (flatpicker) -->
                      <!-- <div class="inline-calendar"></div> -->

                      <!-- <hr class="mb-5 mx-n4 mt-3" /> -->
                      <!-- Filter -->
                      <div class="mb-4 mt-4 ms-1">
                        <h5>Event Filters</h5>
                      </div>

                      <div class="form-check form-check-secondary mb-5 ms-3">
                        <input
                          class="form-check-input select-all"
                          type="checkbox"
                          id="selectAll"
                          data-value="all"
                          checked />
                        <label class="form-check-label" for="selectAll">View All</label>
                      </div>

                      <div class="app-calendar-events-filter text-heading">
                        <?php foreach($movement_types as $key => $movement_type): ?>
                          <div class="form-check mb-5 ms-3">
                            <input
                              class="form-check-input input-filter"
                              type="checkbox"
                              id="<?= "select-$movement_type->id" ?>"
                              data-value="<?= "$movement_type->id" ?>"
                              checked />
                            <label class="form-check-label" for="<?= "select-$movement_type->id" ?>"><?= "$movement_type->name" ?></label>
                          </div>
                        <?php endforeach ?>
                      </div>
                    </div>
                  </div>
                  <!-- /Calendar Sidebar -->

        <div class="col app-calendar-content">
          <div class="card shadow-none border-0">
            <div class="card-body pb-0">
              <!-- FullCalendar -->
              <div id="calendar"></div>
            </div>
          </div>
          <div class="app-overlay"></div>
          <div
            class="offcanvas offcanvas-end event-sidebar"
            tabindex="-1"
            id="addEventSidebar"
            aria-labelledby="addEventSidebarLabel">
            <div class="offcanvas-header border-bottom">
              <h5 class="offcanvas-title" id="addEventSidebarLabel">Add Event</h5>
              <button
                type="button"
                class="btn-close text-reset"
                data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="info_movement">

              </div>
              <form class="event-form pt-0" id="eventForm" onsubmit="return false">
                <div class="mb-5 d-flex justify-content-sm-between justify-content-start my-6 gap-2">
                  <div class="d-flex">
                    <button
                      type="reset"
                      class="btn btn-outline-secondary btn-cancel me-sm-0 me-1"
                      data-bs-dismiss="offcanvas">
                      Cancel
                    </button>
                    <button
                    
                      class="btn btn-label-info btn-edit-event me-sm-0 me-1 mx-1">
                      Editar
                    </button>
                  </div>
                  <button class="btn btn-outline-danger btn-delete-event d-none">Delete</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>


<?php $this->endSection() ?>

<?= $this->section('javaScripts'); ?>
<!-- Page JS -->


  <script>

    const movement_types = <?= json_encode($movement_types) ?>;
    const states = <?= json_encode($states) ?>;

  </script>

  <script src="<?= base_url(["assets/js/dashboards-analytics.js"]) ?>"></script>
  <script src="<?= base_url(["assets/js/app-calendar-events.js?v=".getCommit()]) ?>"></script>
  <script src="<?= base_url(["master/js/dashboard/calendar.js?v=".getCommit()]) ?>"></script>
<?= $this->endSection() ?>