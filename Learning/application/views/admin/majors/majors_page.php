<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Majors</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
            <li class="breadcrumb-item active">Majors</li>
          </ol>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-md-6">
        <!-- small card -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>Registrations</h3>
            <p>Major</p>
          </div>
          <div class="icon">
            <i class="fa fa-book-medical"></i>
          </div>
          <a href="javascript::" class="small-box-footer" data-toggle="modal" data-target="#majors_modal"
            id="regMajors">
            Register one <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>Show Active</h3>

            <p>Majors</p>
          </div>
          <div class="icon">
            <i class="fas fa-book-open"></i>
          </div>
          <a href="javascript::" class="small-box-footer" id="active">
            show them <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>Show Inactive</h3>

            <p>Majors</p>
          </div>
          <div class="icon">
            <i class="fas fa-book"></i>
          </div>
          <a href="javascript::" class="small-box-footer" id="inactive">
            show them <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <!-- ./col -->
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div id="wraper">
    </div>
    <div class="modals">
      <div class="modal fade" id="majors_modal">
        <div class="modal-dialog" id="mjrContent">

        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->