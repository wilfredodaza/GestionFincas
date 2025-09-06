<?php $menus = menus(); ?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?= base_url(['dashboard']) ?>" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-semibold ms-2">Materialize</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z"
                    fill-opacity="0.9" />
                <path
                    d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z"
                    fill-opacity="0.4" />
            </svg>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- Apps & Pages -->
        <li class="menu-header mt-5">
            <span class="menu-header-text" data-i18n="Menú">Menú</span>
        </li>

        <li class="menu-item <?= isActive(base_url(['dashboard'])) ?>">
            <a href="<?= base_url(['dashboard']) ?>" class="menu-link">
                <i class="menu-icon tf-icons ri-home-4-fill"></i>
                <div data-i18n="Home">Home</div>
            </a>
        </li>

        <?php foreach($menus as $key => $menu): ?>
            <?php if(count($menu->sub_menu) == 0): ?>
                <li class="menu-item <?= isActive($menu->base_url) ?>">
                    <a href="<?= $menu->base_url ?>" class="menu-link">
                        <?php if(!empty($menu->icon)): ?>
                            <i class="menu-icon tf-icons <?= $menu->icon ?>"></i>
                        <?php else: ?>
                            <i class="menu-icon tf-icons ri-radio-button-line"></i>
                        <?php endif ?>
                        <div data-i18n="<?= $menu->option ?>"><?= $menu->option ?></div>
                    </a>
                </li>
            <?php else: ?>
                <li class="menu-item <?= subActive($menu->id) ?>">
                    <a href="<?= $menu->base_url ?>" class="menu-link menu-toggle">
                        <?php if(!empty($menu->icon)): ?>
                            <i class="menu-icon tf-icons <?= $menu->icon ?>"></i>
                        <?php else: ?>
                            <i class="menu-icon tf-icons ri-radio-button-line"></i>
                        <?php endif ?>
                        <div data-i18n="<?= $menu->option ?>"><?= $menu->option ?></div>
                    </a>
                    <ul class="menu-sub">
                        <?php foreach ($menu->sub_menu as $key => $sub_menu): ?>
                            <li class="menu-item  <?= isActive($sub_menu->base_url) ?>">
                                <a href="<?= $sub_menu->base_url ?>" class="menu-link">
                                    <div data-i18n="<?= $sub_menu->option ?>"><?= $sub_menu->option ?></div>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </li>
            <?php endif ?>
        <?php endforeach ?>

        <li class="menu-item <?= isActive(base_url(['password'])) ?>">
            <a href="<?= base_url(['password']) ?>" class="menu-link">
                <i class="menu-icon tf-icons ri-lock-password-line"></i>
                <div data-i18n="Renovar Contraseña">Renovar Contraseña</div>
            </a>
        </li>
    </ul>
</aside>