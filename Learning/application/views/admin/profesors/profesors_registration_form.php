<div class="modal-content <?= @$option == "edit" ? 'bg-green' : 'bg-primary'; ?>">
  <form id="profesors_form">
    <?php if (@$profesorFinded) { ?>
      <input type="hidden" name="code" id="code" value="<?= @$profesorFinded->id_person; ?>">
      <input type="hidden" name="option" id="option" value="<?= @$option; ?>">
    <?php } ?>
    <?php if (@$option) {
      if (@$option == 'edit') {
        $titulo = "Edit";
      } else if (@$option == 'inactivate') {
        $titulo = "Shutdown";
      }
    } else {
      $titulo = "Register";
    } ?>
    <div class="modal-header">
      <h1 class="card-title">
        <?= @$titulo; ?> profesor
      </h1>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="form-group col-12">
          <label for="exampleInputBorderWidth2">Empoyee ID:</label>
          <input type="text" <?= @$titulo == "Edit" || @$titulo == "Shutdown" ? 'readonly' : ''; ?>
            class="form-control input-group" value="<?= @$profesorFinded->IDUser; ?>" name="inpID" id="inpID">
        </div>
        <div class="form-group col-6">
          <label for="exampleInputBorderWidth2">Name(s):</label>
          <input type="text" <?= @$titulo == "Shutdown" ? 'readonly' : ''; ?> class="form-control input-group"
            value="<?= @$profesorFinded->name_person; ?>" name="inpName" id="inpName">
        </div>
        <div class="form-group col-6">
          <label for="exampleInputBorderWidth2">Last Name(s):</label>
          <input type="text" <?= @$titulo == "Shutdown" ? 'readonly' : ''; ?> class="form-control input-group"
            value="<?= @$profesorFinded->lastname_person; ?>" name="inpLastname" id="inpLastname">
        </div>
        <div class="col-6">
          <label for="exampleInputBorderWidth2">Birthday:</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
            </div>
            <input type="text" <?= @$titulo == "Shutdown" ? 'readonly' : ''; ?> class="form-control input-group"
              data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy/mm/dd" data-mask inputmode="numeric"
              value="<?= @$profesorFinded->birthday_person; ?>" name="inpBirthday" id="inpBirthday">
          </div>
        </div>
        <div class="form-group col-6">
          <label for="exampleInputBorderWidth2">Gender:</label>
          <select <?= @$titulo == "Shutdown" ? 'hidden' : ''; ?> class="form-control input-group" name="inpGender"
            id="inpGender">
            <option value="<?= @$profesorFinded->gender_person ? $profesorFinded->gender_person : ''; ?>">
              <?= @$profesorFinded->gender_person ? ($profesorFinded->gender_person == "M" ? "Male" : "Female") : 'Select one'; ?>
            </option>
            <option value="M">Male</option>
            <option value="F">Female</option>
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
<script>
  $(function () {
    $('#datemask').inputmask("yyyy/mm/dd", {
      placeholder: 'yyyy/mm/dd'
    });
    $('[data-mask]').inputmask()
  });
</script>