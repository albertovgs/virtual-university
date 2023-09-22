<div class="modal-content <?= @$option == "edit" ? 'bg-green' : 'bg-primary'; ?>">
  <form id="students_form" data-id="<?= $id; ?>">
    <?php if (@$studentFinded) { ?>
      <input type="hidden" name="code" id="code" value="<?= @$studentFinded->id_person; ?>">
      <input type="hidden" name="option" id="option" value="<?= @$option; ?>">
    <?php } ?>
    <?php if (@$option) {
      if (@$option == 'edit') {
        $titulo = "Edit";
      } else if (@$option == 'inactivate') {
        $titulo = "Shutdown";
      } else if (@$option == 'reactive') {
        $titulo = "Reactive";
      }
    } else {
      $titulo = "Register";
    }
    ?>
    <div class="modal-header">
      <h1 class="card-title">
        <?= @$titulo; ?> students
      </h1>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="form-group col-12">
          <label for="exampleInputBorderWidth2">Student ID:</label>
          <input type="text" <?= @$titulo == "Edit" ? 'readonly' : ''; ?> class="form-control input-group"
            value="<?= @$studentFinded->IDUser; ?>" name="inpID" id="inpID">
        </div>
        <div class="form-group col-6">
          <label for="exampleInputBorderWidth2">Name(s):</label>
          <input type="text" class="form-control input-group" value="<?= @$studentFinded->name_person; ?>"
            name="inpName" id="inpName">
        </div>
        <div class="form-group col-6">
          <label for="exampleInputBorderWidth2">Last Name(s):</label>
          <input type="text" class="form-control input-group" value="<?= @$studentFinded->lastname_person; ?>"
            name="inpLastname" id="inpLastname">
        </div>
        <div class="col-6">
          <label for="exampleInputBorderWidth2">Birthday:</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
            </div>
            <input type="text" class="form-control" data-inputmask-alias="datetime"
              data-inputmask-inputformat="yyyy/mm/dd" data-mask="" inputmode="numeric"
              value="<?= @$studentFinded->birthday_person; ?>" name="inpBirthday" id="inpBirthday">
          </div>
        </div>
        <div class="form-group col-6">
          <label for="exampleInputBorderWidth2">Gender:</label>
          <select class="form-control input-group" name="inpGender" id="inpGender">
            <option value="<?= @$studentFinded->gender_person ? $studentFinded->gender_person : ''; ?>">
              <?= @$studentFinded->gender_person ? ($studentFinded->gender_person == "M" ? "Male" : "Female") : 'Select one'; ?>
            </option>
            <option value="M">Male</option>
            <option value="F">Female</option>
          </select>
        </div>
        <div class="form-group col-8">
          <label for="exampleInputBorderWidth2">Major:</label>
          <select class="form-control input-group" name="inpMajor" id="inpMajor">
            <option value="<?= @$studentFinded->major_student; ?>">
              <?= @$studentFinded->major_student ? $studentFinded->name_major : 'Select one'; ?>
            </option>
            <?php if (@$majors) {
              ;
              foreach (@$majors as $key => $major) {
                ?>
                <option value="<?= @$major->id_major; ?>">
                  <?= @$major->name_major; ?> (
                  <?= @$major->clave_major; ?>)
                </option>
              <?php }
            } ?>
          </select>
        </div>
        <div class="form-group col-4" id="groups">
          <?php if (@$option == "edit" || @$option == "inactivate") { ?>
            <label for="exampleInputBorderWidth2">Group:</label>
            <select class="form-control input-group" name="inpGroup" id="inpGroup">
              <option value="<?= @$group->id_group; ?>">
                <?= @$group->clave_group ? $group->clave_group : 'Select one'; ?>
              </option>
              <?php
              if (@$groups) {
                foreach ($groups as $grp) {
                  if ($grp->id_group != $group->id_group) {
                    ?>
                    <option value="<?= $grp->id_group ?>">
                      <?= $grp->clave_group; ?>
                    </option>
                  <?php }
                }
              }
          } else if (@$option == "reactive") { ?>
                <label for="exampleInputBorderWidth2">Group:</label>
                <select class="form-control input-group" name="inpGroup" id="inpGroup">
                  <option value="">Select one</option>
                  <?php
                  foreach ($groups as $grp) {
                    ?>
                    <option value="<?= $grp->id_group ?>">
                    <?= $grp->clave_group; ?>
                    </option>
                <?php }
          } ?>
            </select>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
<script type="text/javascript">
  $(function () {
    $('[data-mask]').inputmask();
    $('#datemask').inputmask("yyyy/mm/dd", {
      placeholder: 'yyyy/mm/dd'
    });
    $("#inpMajor").change(function () {
      var major = $("#inpMajor").val();
      $.ajax({
        url: "<?php echo site_url('Majors/getGroups?major='); ?>" + major,
        type: "POST",
        dataType: 'json',
        success: function (respuesta) {
          $(document).find('#groups').empty().append(respuesta);
        }
      });
    });
  });
</script>