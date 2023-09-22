<table id="coordinatorTable" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Name</th>
      <th>Last Name</th>
      <th>Gender</th>
      <th>Birthday</th>
      <th>Email</th>
      <th>Empoyee ID</th>
      <th>options</th>
    </tr>
  </thead>
  <tbody>
    <?php if (@$coordinator) {
      foreach ($coordinator as $iProf) { ?>
        <tr>
          <td>
            <?= @$iProf->name_person; ?>
          </td>
          <td>
            <?= @$iProf->lastname_person; ?>
          </td>
          <td>
            <?= @$iProf->gender_person == "M" ? 'Male' : ''; ?>
            <?= @$iProf->gender_person == "F" ? 'Female' : ''; ?>
          </td>
          <td>
            <?= @$iProf->birthday_person; ?>
          </td>
          <td>
            <?= @$iProf->email_user; ?>
          </td>
          <td>
            <?= @$iProf->IDUser; ?>
          </td>
          <td>
            <?php if (@$iProf->status_user == "Active") { ?>
              <button type="button" class="btn btn-success btn_rst_pass" data-code="<?= @$iProf->id_user; ?>" data-opt="reset"
                data-toggle="modal" data-target="#coordinator_modal">
                <i class="fas fa-undo"></i> Password
              </button>
              <button type="button" class="btn btn-success btn_operation" data-code="<?= @$iProf->id_user; ?>" data-opt="edit"
                data-toggle="modal" data-target="#coordinator_modal">
                <i class="fas fa-edit"></i>
              </button>
              <button type="button" class="btn btn-danger btn_operation" data-code="<?= @$iProf->id_user; ?>"
                data-opt="inactivate" data-toggle="modal" data-target="#coordinator_modal">
                <i class="fa fa-user-times"></i>
              </button>
            <?php } else if (@$iProf->status_user == "Inactive") { ?>
                <button type="button" class="btn btn-success btn_operation" data-code="<?= @$iProf->id_user; ?>"
                  data-opt="reactive" data-toggle="modal" data-target="#coordinator_modal">
                  <i class="fas fa-edit"></i>
                  Reactive
                </button>
            <?php } ?>
          </td>
        </tr>
      <?php }
    } ?>
  </tbody>
</table>