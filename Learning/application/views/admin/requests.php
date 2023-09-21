<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Requests</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url(""); ?>">Home</a></li>
            <li class="breadcrumb-item active">Requests</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content row">
    <?php
    if (@$requests) {
      foreach (@$requests as $key => $req) {
        ?>
        <!-- Default box -->
        <div class="col-lg-4 col-md-3 col-12">
          <div class="card card-primary collapsed-card">
            <div class="card-header">
              <h3 class="card-title">
                <?= $req->title_request ?> -
                <?= $req->status_request ?>
              </h3>
              <div class="card-tools">
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-plus"></i>
                  </button>
                  <button type="button" class="btn btn-tool btn_operation" data-code="<?= @$req->id_request; ?>"
                    data-opt="active" data-toggle="modal" data-target="#confirm_modal" title="Accepted">
                    <i class="fas fa-check"></i>
                  </button>
                  <button type="button" class="btn btn-tool btn_operation" data-code="<?= @$req->id_request; ?>"
                    data-opt="delete" data-toggle="modal" data-target="#confirm_modal" title="Rejected">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <?= $req->request ?>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      <?php
      }
    }
    ?>

    <div class="modals">
      <div class="modal fade" id="confirm_modal">
        <div class="modal-dialog" id="cnfContent">

        </div>
      </div>
    </div>
  </section>
</div>