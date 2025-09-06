<?= $this->extend('layouts/page'); ?>

<?= $this->section('content'); ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <!-- <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                <li class="nav-item">
                <a class="nav-link" href="pages-account-settings-account.html"
                    ><i class="ri-group-line me-2"></i> Account</a
                >
                </li>
                <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);"
                    ><i class="ri-lock-line me-2"></i> Security</a
                >
                </li>
                <li class="nav-item">
                <a class="nav-link" href="pages-account-settings-billing.html"
                    ><i class="ri-bookmark-line me-2"></i> Billing & Plans</a
                >
                </li>
                <li class="nav-item">
                <a class="nav-link" href="pages-account-settings-notifications.html"
                    ><i class="ri-notification-4-line me-2"></i> Notifications</a
                >
                </li>
                <li class="nav-item">
                <a class="nav-link" href="pages-account-settings-connections.html"
                    ><i class="ri-link-m me-2"></i> Connections</a
                >
                </li>
            </ul>
            </div> -->
            <!-- Change Password -->
            <div class="card mb-6">
                <h5 class="card-header">Renovación de contraseña</h5>
                <div class="card shadow-none bg-transparent border border-danger mx-4 my-2" style="display:none;" id="card-error">
                    <div class="card-body text-danger">
                        <h5 class="card-title text-danger"></h5>
                        <p class="card-text"></p>
                    </div>
                </div>

                <div class="card shadow-none bg-transparent border border-success mx-4 my-2" style="display:none;" id="card-success">
                    <div class="card-body text-success">
                        <h5 class="card-title text-success"></h5>
                        <p class="card-text"></p>
                    </div>
                </div>
                <div class="card-body pt-1">
                    <form id="formAccountSettings" method="GET" onsubmit="sendPassword(event)">
                        <div class="row">
                            <div class="mb-5 col-md-4 form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                    <input
                                        class="form-control"
                                        type="password"
                                        name="currentPassword"
                                        id="currentPassword"
                                        placeholder="" />
                                    <label for="currentPassword">Contraseña actual</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                                </div>
                            </div>
                            <div class="mb-5 col-md-4 form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                    <input
                                        class="form-control"
                                        type="password"
                                        id="newPassword"
                                        name="newPassword"
                                        placeholder="" />
                                    <label for="newPassword">Nueva contraseña</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                                </div>
                            </div>
                            <div class="mb-5 col-md-4 form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                    <input
                                        class="form-control"
                                        type="password"
                                        name="confirmPassword"
                                        id="confirmPassword"
                                        placeholder="" />
                                    <label for="confirmPassword">Confirmar nueva contraseña</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-body">Requisitos de la Nueva Contraseña</h6>
                        <ul class="ps-4 mb-0">
                            <li class="">Validez de la contraseña: 90 dias.</li>
                            <li class="">No se permite repetir la contraseña actual ni las ultimas 5.</li>
                            <li class="">Debe de tener un mínimo de 6 caracteres.</li>
                            <li class="">Debe introducir al menos 1 letra mayúscula.</li>
                            <li class="">Debe introducir al menos 1 letra minúscula.</li>
                            <li class="">Debe introducir al menos 1 carácter especial que no sea una letra o un número.</li>
                        </ul>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3" id="btn-send">Renovar contraseña</button>
                            <button type="reset" class="btn btn-outline-secondary">Reiniciar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Change Password -->
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('javaScripts'); ?>
  <!-- Page JS -->
  <script src="<?= base_url(["master/js/password/password.js"]) ?>"></script>
<?= $this->endSection() ?>