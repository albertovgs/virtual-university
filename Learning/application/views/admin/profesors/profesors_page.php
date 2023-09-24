<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Profesors</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
            <li class="breadcrumb-item active">Profesors</li>
          </ol>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-4">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>Registrations</h3>
            <p>Profesors</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-plus"></i>
          </div>
          <a href="javascript::" class="small-box-footer" data-toggle="modal" data-target="#profesors_modal"
            id="regProfesor">
            Register one <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-3 col-4">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>Show Active</h3>
            <p>Profesors</p>
          </div>
          <div class="icon">
            <i class="fas fa-user"></i>
          </div>
          <a href="javascript::" class="small-box-footer" id="active">
            show them <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-3 col-4">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>Show Inactive</h3>
            <p>Profesor</p>
          </div>
          <div class="icon">
            <i class="fa fa-user-times"></i>
          </div>
          <a href="javascript::" class="small-box-footer" id="inactive">
            show them <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="card">
      <div class="card-body" id="wraper">

      </div>
    </div>
    <div class="modals">
      <div class="modal fade" id="profesors_modal">
        <div class="modal-dialog" id="prfContent">

        </div>
      </div>
    </div>
  </section>
</div>