<?php if (session('success')): ?>
    <div class="card-alert card green lighten-5">
        <div class="card-content green-text">
            <?= session('success') ?>
        </div>
        <button type="button" class="close green-text" data-dismiss="alert"
                aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
<?php endif; ?>
<?php if (session('errors')): ?>
    <div class="card-alert card red lighten-5">
        <div class="card-content red-text">
            <?= session('errors') ?>
        </div>
        <button type="button" class="close red-text" data-dismiss="alert"
                aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
<?php endif; ?>
<?php if (session('warning')): ?>
    <div class="card-alert card amber lighten-5 darken-2">
        <div class="card-content amber-text">
            <?= session('warning') ?>
        </div>
        <button type="button" class="close amber-text" data-dismiss="alert"
                aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
<?php endif; ?>
