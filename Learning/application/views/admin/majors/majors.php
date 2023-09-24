<div class="row">
  <?php if (@$majors) {
    $n = 0;
    foreach ($majors as $major) {
      $n += 1;
      ?>
      <div class="col-xl-6 col-md-6 col-sm-12">
        <div class="card l-bg-green-dark">
          <div class="card-header">
            <h3 class="card-title">
              <?= @$major->clave_major; ?> -
              <?= @$major->name_major; ?>
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool btn_operation" data-code="<?= @$major->id_major; ?>" data-opt="edit"
                data-toggle="modal" data-target="#majors_modal" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <?php if (@$status == "Active") { ?>
                <button type="button" class="btn btn-tool btn_operation" data-code="<?= @$major->id_major; ?>"
                  data-opt="delete" data-toggle="modal" data-target="#majors_modal" title="Delete">
                  <i class="fas fa-times"></i>
                </button>
              <?php } else { ?>
                <button type="button" class="btn btn-tool btn_operation" data-code="<?= @$major->id_major; ?>"
                  data-opt="active" data-toggle="modal" data-target="#majors_modal" title="Active">
                  <i class="fas fa-check"></i>
                </button>
              <?php } ?>
            </div>
          </div>
          <div class="card-statistic-6 p-4">
            <div class="col-md-3 col-lg-12">
              <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Details.</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body" style="display: none;">
                  <h3 class="card-title mb-0">Cooridinator -
                    <?= @$major->name_person . ' ' . @$major->lastname_person; ?>
                  </h3><br />
                  <h3 class="card-title mb-0">
                    Active students number - <?= $num[$n] ?>
                  </h3><br />
                  <?= @$major->desc_major; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php }
  } ?>
</div>