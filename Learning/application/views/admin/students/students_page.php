<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Students</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
            <li class="breadcrumb-item active">Students</li>
          </ol>
        </div>
      </div>
    </div>
    <?php
    $session = $this->session->userdata("up_sess");
    if ($session->type_user == "Cordi") { ?>
      <div class="row">
        <div class="col-lg-6 col-4">
          <!-- small card -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>Registrations</h3>

              <p>Students</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <a href="javascript::" class="small-box-footer" data-toggle="modal" data-target="#students_modal"
              id="regStudents">
              Register one <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-4">
          <!-- small card -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>Show Active</h3>

              <p>Students</p>
            </div>
            <div class="icon">
              <i class="fas fa-user"></i>
            </div>
            <a href="javascript::" class="small-box-footer" id="active">
              show them <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-4">
          <!-- small card -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>Show Inactive</h3>

              <p>Students</p>
            </div>
            <div class="icon">
              <i class="fa fa-user-times"></i>
            </div>
            <a href="javascript::" class="small-box-footer" id="inactive">
              show them <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->

      </div>
    <?php } ?>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="card">
      <!-- /.card-header -->
      <div class="card-body" id="wraper">

      </div>
      <!-- /.card-body -->
    </div>
    <div class="modals">
      <div class="modal fade" id="students_modal">
        <div class="modal-dialog" id="stuContent">

        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->