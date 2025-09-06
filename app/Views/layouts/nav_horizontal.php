<nav
  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
      <i class="ri-menu-fill ri-22px"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <ul class="navbar-nav flex-row align-items-center ms-auto">

      <!-- Style Switcher -->
      <li class="nav-item dropdown-style-switcher dropdown me-1 me-xl-0">
        <a
          class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
          href="javascript:void(0);"
          data-bs-toggle="dropdown">
          <i class="ri-22px"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
              <span class="align-middle"><i class="ri-sun-line ri-22px me-3"></i>Light</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
              <span class="align-middle"><i class="ri-moon-clear-line ri-22px me-3"></i>Dark</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
              <span class="align-middle"><i class="ri-computer-line ri-22px me-3"></i>System</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- / Style Switcher-->

      <!-- Quick links  -->
      <!-- <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-1 me-xl-0">
        <a
          class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
          href="javascript:void(0);"
          data-bs-toggle="dropdown"
          data-bs-auto-close="outside"
          aria-expanded="false">
          <i class="ri-star-smile-line ri-22px"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end py-0">
          <div class="dropdown-menu-header border-bottom py-50">
            <div class="dropdown-header d-flex align-items-center py-2">
              <h6 class="mb-0 me-auto">Shortcuts</h6>
              <a
                href="javascript:void(0)"
                class="btn btn-text-secondary rounded-pill btn-icon dropdown-shortcuts-add text-heading"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="Add shortcuts"
                ><i class="ri-add-line ri-24px"></i
              ></a>
            </div>
          </div>
          <div class="dropdown-shortcuts-list scrollable-container">
            <div class="row row-bordered overflow-visible g-0">
              <div class="dropdown-shortcuts-item col">
                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                  <i class="ri-calendar-line ri-26px text-heading"></i>
                </span>
                <a href="app-calendar.html" class="stretched-link">Calendar</a>
                <small class="mb-0">Appointments</small>
              </div>
              <div class="dropdown-shortcuts-item col">
                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                  <i class="ri-file-text-line ri-26px text-heading"></i>
                </span>
                <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
                <small class="mb-0">Manage Accounts</small>
              </div>
            </div>
            <div class="row row-bordered overflow-visible g-0">
              <div class="dropdown-shortcuts-item col">
                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                  <i class="ri-user-line ri-26px text-heading"></i>
                </span>
                <a href="app-user-list.html" class="stretched-link">User App</a>
                <small class="mb-0">Manage Users</small>
              </div>
              <div class="dropdown-shortcuts-item col">
                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                  <i class="ri-computer-line ri-26px text-heading"></i>
                </span>
                <a href="app-access-roles.html" class="stretched-link">Role Management</a>
                <small class="mb-0">Permission</small>
              </div>
            </div>
            <div class="row row-bordered overflow-visible g-0">
              <div class="dropdown-shortcuts-item col">
                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                  <i class="ri-pie-chart-2-line ri-26px text-heading"></i>
                </span>
                <a href="index.html" class="stretched-link">Dashboard</a>
                <small class="mb-0">Analytics</small>
              </div>
              <div class="dropdown-shortcuts-item col">
                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                  <i class="ri-settings-4-line ri-26px text-heading"></i>
                </span>
                <a href="pages-account-settings-account.html" class="stretched-link">Setting</a>
                <small class="mb-0">Account Settings</small>
              </div>
            </div>
            <div class="row row-bordered overflow-visible g-0">
              <div class="dropdown-shortcuts-item col">
                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                  <i class="ri-question-line ri-26px text-heading"></i>
                </span>
                <a href="pages-faq.html" class="stretched-link">FAQs</a>
                <small class="mb-0">FAQs & Articles</small>
              </div>
              <div class="dropdown-shortcuts-item col">
                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                  <i class="ri-tv-2-line ri-26px text-heading"></i>
                </span>
                <a href="modal-examples.html" class="stretched-link">Modals</a>
                <small class="mb-0">Useful Popups</small>
              </div>
            </div>
          </div>
        </div>
      </li> -->
      <!-- Quick links -->

      <!-- Notification -->

      <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-4 me-xl-1">
        <a
          class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
          href="javascript:void(0);"
          data-bs-toggle="dropdown"
          data-bs-auto-close="outside"
          aria-expanded="false">
          <i class="ri-notification-2-line ri-22px"></i>
          <span
            class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end py-0">
          <li class="dropdown-menu-header border-bottom py-50">
            <div class="dropdown-header d-flex align-items-center py-2">
              <h6 class="mb-0 me-auto">Notification</h6>
              <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-label-primary fs-xsmall me-2"><?= countNotification() ?></span>
                <a
                  href="javascript:void(0)"
                  class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  title="Mark all as read"
                  ><i class="ri-mail-open-line text-heading ri-20px"></i
                ></a>
              </div>
            </div>
          </li>
          <li class="dropdown-notifications-list scrollable-container">
            <ul class="list-group list-group-flush">
              <?php foreach (notification() as $item): ?>
                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <img src="../../assets/img/avatars/1.png" alt class="rounded-circle" />
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="small mb-1"><?= $item->title ?></h6>
                      <small class="mb-1 d-block text-body"><?= $item->body ?></small>
                      <small class="text-muted"><?= different($item->created_at) ?></small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a href="javascript:void(0)" class="dropdown-notifications-read"
                        ><span class="badge badge-dot"></span
                      ></a>
                      <a href="javascript:void(0)" class="dropdown-notifications-archive"
                        ><span class="ri-close-line ri-20px"></span
                      ></a>
                    </div>
                  </div>
                </li>
              <?php endforeach ?>
            </ul>
          </li>
          <li class="border-top">
            <div class="d-grid p-4">
              <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                <small class="align-middle">View all notifications</small>
              </a>
            </div>
          </li>
        </ul>
      </li>
      <!--/ Notification -->
      
      <?php $image = mt_rand(1, 20).".png" ?>

      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="<?= session('user') && session('user')->photo ? base_url(["assets/upload/images/", session('user')->photo]) : base_url(["assets/img/avatars", $image]) ?>" alt class="rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="pages-account-settings-account.html">
              <div class="d-flex">
                <div class="flex-shrink-0 me-2">
                  <div class="avatar avatar-online">
                    <img src="<?= session('user') && session('user')->photo ? base_url(["assets/upload/images/", session('user')->photo]) : base_url(["assets/img/avatars", $image]) ?>" alt class="rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-medium d-block small"><?= session('user')->username ?></span>
                  <small class="text-muted"><?= session('user')->role_name ?></small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="pages-profile-user.html">
              <i class="ri-user-3-line ri-22px me-3"></i><span class="align-middle">My Profile</span>
            </a>
          </li>
          <?php  if(session()->get('user')->role_id == 1 || session()->get('user')->role_id == 2): ?>
              <li><a class="dropdown-item" href="<?= base_url(["config/configurations"]) ?>"><i class="ri-settings-4-line ri-22px me-3"></i>
              <span class="align-middle">Configuración</span></a></li>
          <?php endif ?>
          <?php  if(session()->get('user')->role_id == 1): ?>
              <li><a class="dropdown-item" href="<?= base_url(["config/roles"]) ?>"><i class="ri-user-2-line ri-22px me-3"></i>
                      Roles</a></li>
              <li><a class="dropdown-item" href="<?= base_url(["config/users"]) ?>"><i class="ri-group-line ri-22px me-3"></i>
                      Usuarios</a></li>
              <li><a class="dropdown-item" href="<?= base_url(["config/menus"]) ?>"><i class="ri-align-justify ri-22px me-3"></i>
                      Menu</a></li>
              <li><a class="dropdown-item" href="<?= base_url(["config/permissions"]) ?>"><i class="ri-lock-2-line ri-22px me-3"></i>
                      Permisos</a></li>
              <li><a class="dropdown-item" href="<?= base_url(["config/notifications"]) ?>"><i class="ri-notification-4-line ri-22px me-3"></i>
                      Notificar</a></li>
              <li class="divider"></li>
          <?php  endif; ?>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <div class="d-grid px-4">
              <a class="btn btn-sm btn-danger d-flex" href="<?= base_url(["logout"]) ?>">
                <small class="align-middle">Logout</small>
                <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
              </a>
            </div>
          </li>
        </ul>
      </li>
      <!--/ User -->
    </ul>
  </div>
</nav>