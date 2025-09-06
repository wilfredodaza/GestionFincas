<header class="page-topbar" id="header">
    <div class="navbar navbar-fixed">
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock bg-primary-secondary gradient-shadow">
            <div class="nav-wrapper">
                <ul class="navbar-list right">
                    <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
                    <li><a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);"
                           data-target="notifications-dropdown"><i class="material-icons">notifications_none
                                <?php if(countNotification() > 0): ?>
                                    <small class="notification-badge"><?= countNotification() ?></small>
                                <?php endif; ?>
                            </i></a></li>
                    <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);"
                           data-target="profile-dropdown"><span class="avatar-status avatar-online"><img  style="height: 29px !important;"
                                        src="<?= session('user') && session('user')->photo ? base_url().'/assets/upload/images/'.session('user')->photo : base_url().'/assets/img/'.'user.png' ?>" alt="avatar">
                                <i></i>

                            </span>
                            <small style="float: right; padding-left: 10px; font-size: 16px;"
                                   class="new badge"><?= session('user')->username ?></small>

                          </a>
                    </li>
                </ul>
                <ul class="dropdown-content" id="notifications-dropdown">
                    <li>
                        <h6>NOTIFICACIONES</h6>
                    </li>
                    <li class="divider"></li>
                    <?php foreach (notification() as $item): ?>
                        <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle <?= $item['color'] ?> small"><?= $item['icon'] ?></span>
                                <?= $item['title'] ?></a>
                            <time class="media-meta grey-text darken-2"><?= $item['body'] ?></time>
                            <br>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00"><?= different($item['created_at']) ?>
                            </time>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <!-- profile-dropdown-->
                <ul class="dropdown-content" id="profile-dropdown">
                    <li><a class="grey-text text-darken-1" href="<?= base_url(["perfile"]) ?>"><i class="material-icons">person_outline</i>
                            Perfil</a></li>
                    <li><a class="grey-text text-darken-1" href="<?= base_url(["about"]) ?>"><i class="material-icons">help_outline</i> About</a></li>
                    <?php  if(session()->get('user')->role_id == 1 || session()->get('user')->role_id == 2): ?>
                        <li><a class="grey-text text-darken-1" href="<?= base_url(["config/configurations"]) ?>"><i class="material-icons">settings</i>
                                Configure</a></li>
                    <?php endif ?>
                    <?php  if(session()->get('user')->role_id == 1): ?>
                        <li><a class="grey-text text-darken-1" href="<?= base_url(["config/roles"]) ?>"><i class="material-icons">face</i>
                                Roles</a></li>
                        <li><a class="grey-text text-darken-1" href="<?= base_url(["config/users"]) ?>"><i class="material-icons">peoples</i>
                                Usuarios</a></li>
                        <li><a class="grey-text text-darken-1" href="<?= base_url(["config/menus"]) ?>"><i class="material-icons">menu</i>
                                Menu</a></li>
                        <li><a class="grey-text text-darken-1" href="<?= base_url(["config/permissions"]) ?>"><i class="material-icons">lock_outline</i>
                                Permisos</a></li>
                        <li><a class="grey-text text-darken-1" href="<?= base_url(["config/notifications"]) ?>"><i class="material-icons">contact_mail</i>
                                Notificar</a></li>
                        <li class="divider"></li>
                    <?php  endif; ?>

                    <li><a class="grey-text text-darken-1" href="<?= base_url(["logout"]) ?>"><i
                                    class="material-icons">keyboard_tab</i> Logout</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>
