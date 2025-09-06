<?= view('layouts/header') ?>

<?= $this->renderSection('styles') ?>

<?= view('layouts/navbar_horizontal') ?>
<?= view('layouts/navbar_vertical') ?>

<!-- BEGIN: Page Main-->
<div id="main">
  <div class="row">
    <?= $this->renderSection('content') ?>
  </div>
</div>
<?= view('layouts/footer') ?>

<?= $this->renderSection('scripts') ?>