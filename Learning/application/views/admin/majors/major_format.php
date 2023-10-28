<?php
if (@$form == "edit") {
  $title = "Edit";
} elseif (@$form == "delete") {
  $title   = "Delete";
  $disable = TRUE;
} elseif (@$form == "active") {
  $title   = "Reactive";
  $disable = FALSE;
} else {
  $title = "Register";
}
?>
<div class="modal-content">
  <form id="majors_format">
    <input type="text" hidden name="inpOp" id="inpOp" value="<?= @$form; ?>">
    <input type="text" hidden name="inpId" id="inpId" value="<?= @$majorFind->id_major; ?>">
    <div class="modal-header">
      <h1 class="card-title">
        <?= @$title ?> Major
      </h1>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="form-group col-9">
          <label for="exampleInputBorderWidth2">Name:</label>
          <input type="text" <?= @$disable ? 'disabled' : ''; ?> class="form-control input-group" name="inpName"
            id="inpName" value="<?= @$majorFind->name_major; ?>">
        </div>
        <div class="form-group col-3">
          <label for="exampleInputBorderWidth2">Clave:</label>
          <input type="text" <?= @$disable ? 'disabled' : ''; ?> class="form-control input-group" name="inpClave"
            id="inpClave" value="<?= @$majorFind->clave_major; ?>">
        </div>
        <div class="form-group col-12">
          <label for="exampleInputBorderWidth2">Cordination:</label>
          <select class="form-control input-group" name="inpCordi" id="inpCordi">
            <?php if (@$majorFind) { ?>
              <option value="<?= @$majorFind->cordi_major; ?>" hidden>
                <?= @$majorFind->name_person; ?>
                <?= @$majorFind->lastname_person; ?>
              </option>
            <?php }
            if (@$cordis) {
              foreach (@$cordis as $key => $cordi) {
                ?>
                <option value="<?= @$cordi->id_user; ?>" <?= @$form == "delete" ? 'disabled' : ''; ?>>
                  <?= @$cordi->name_person; ?>
                  <?= @$cordi->lastname_person; ?>
                </option>
              <?php }
            } ?>
          </select>
        </div>
        <div class="form-group col-12">
          <label for="exampleInputBorderWidth2">Description:</label>
          <textarea class="form-control input-group" <?= @$disable ? 'disabled' : ''; ?> name="inpDesc" id="inpDesc"
            cols="30" rows="5"><?= @$majorFind->desc_major; ?></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      <button type="submit" class="btn btn-primary">
        <?= @$form == "delete" ? 'Delete' : (@$form == "active" ? 'Reactive' : 'Save'); ?>
      </button>
    </div>
  </form>
</div>