<?php $session = $this->session->userdata("up_sess"); ?>
<div class="wrapper">
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
      <a href="<?= base_url(""); ?>" class="navbar-brand">
        <img src="<?= base_url(@$session->img_user); ?>" alt="Profile" class="brand-image img-circle elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light">
          <?= @$session->name_person; ?>
        </span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="<?= base_url(''); ?>" class="nav-link">Home</a>
          </li>
        </ul>
      </div>

      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <li class="nav-item">
          <a href="<?= base_url("home/logout"); ?>" class="nav-link">
            <i class="nav-icon fas fa-times text-danger"></i>
          </a>
        </li>
      </ul>
  </nav>
</div>