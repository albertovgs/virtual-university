<?php
$session = $this->session->userdata("up_sess");
$menu    = array();
if ($session->force_change_user == "N") {
  if ($session->type_user == "Admin") {
    $menu = array(
      array(
        "icon_menu" => "fa-bell",
        "name_menu" => "Requests",
        "url_menu" => base_url(''),
      ),
      array(
        "icon_menu" => "fa-address-book",
        "name_menu" => "Profesors",
        "url_menu" => "Admin_Profesors",
      ),
      array(
        "icon_menu" => "fa fa-graduation-cap",
        "name_menu" => "Students",
        "url_menu" => "Admin_Students",
      ),
      array(
        "icon_menu" => "fas fa-user",
        "name_menu" => "Cordination",
        "url_menu" => "Admin_Cordination",
      ),
      array(
        "icon_menu" => "fa fa-tasks",
        "name_menu" => "Majors",
        "url_menu" => "Admin_Majors",
      ),
      array(
        "icon_menu" => "fa fa-calendar-plus",
        "name_menu" => "Periods",
        "url_menu" => "Periods",
      ),
    );
  } elseif ($session->type_user == "Cordi") {
    $menu = array(
      array(
        "icon_menu" => "fa fa-home",
        "name_menu" => "Home",
        "url_menu" => "Home",
      ),
      array(
        "icon_menu" => "fa-address-book",
        "name_menu" => "Profesors",
        "url_menu" => "Admin_Profesors",
      ),
    );
  }
}
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= base_url(""); ?>" class="brand-link">
    <img src="<?= base_url("resources/dist/img/isotype.png"); ?>" alt="UPSRJ Logo" class="brand-image"
      style="opacity: .8">
    <span class="brand-text font-weight-light">Learning</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= @$session->img_user;
        ?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="javascript::" class="d-block">
          <?= @$session->email_user; ?>
        </a>
      </div>
    </div>

    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php foreach ($menu as $key => $uMenu) { ?>
          <li class="nav-item">
            <a href="<?= $uMenu['url_menu']; ?>" class="nav-link">
              <i class="nav-icon far <?= $uMenu['icon_menu']; ?>"></i>
              <p>
                <?= $uMenu['name_menu']; ?>
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
        <?php } ?>
        <li class="nav-item">
          <a href="<?= base_url("home/logout"); ?>" class="nav-link">
            <i class="nav-icon fas fa-times text-danger"></i>
            <p class="text">Logout</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>