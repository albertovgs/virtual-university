<table id="studentsTable" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Name</th>
      <th>Last Name</th>
      <th>Gender</th>
      <th>Birthday</th>
      <th>Email</th>
      <th>Group</th>
      <?php $session = $this->session->userdata("up_sess");
      if ($session->type_user != "Cordi") { ?>
        <th>Major</th>
        <th>Status group</th>
        <th>Temporary Password </th>
      <?php } ?>
      <th>options</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (@$students) {
      foreach (@$students as $iStud) { ?>
        <tr>
          <td>
            <?= @$iStud->name_person; ?>
          </td>
          <td>
            <?= @$iStud->lastname_person; ?>
          </td>
          <td>
            <?= @$iStud->gender_person == "M" ? 'Male' : ''; ?>
            <?= @$iStud->gender_person == "F" ? 'Female' : ''; ?>
          </td>
          <td>
            <?= @$iStud->birthday_person; ?>
          </td>
          <td>
            <?= @$iStud->email_user; ?>
          </td>
          <td>
            <?= @$iStud->clave_group; ?>
          </td>
          <?php $session = $this->session->userdata("up_sess");
          if ($session->type_user != "Cordi") { ?>
            <td>
              <?= @$iStud->name_major; ?>
            </td>
            <td>
              <?= @$iStud->status_group; ?>
            </td>
            <th>
              <?= @$iStud->force_change_user; ?>
            </th>
          <?php } ?>
          <td>
            <?php
            if ($session->type_user == "Cordi") {
              if (@$iStud->status_user == "Active") { ?>
                <button type="button" class="btn btn-success btn_operation" data-code="<?= @$iStud->id_user; ?>" data-opt="edit"
                  data-toggle="modal" data-target="#students_modal">
                  <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-danger btn_operation" data-code="<?= @$iStud->id_user; ?>"
                  data-opt="inactivate" data-toggle="modal" data-target="#students_modal">
                  <i class="fa fa-user-times"></i>
                </button>
              <?php } else if (@$iStud->status_user == "Inactive") { ?>
                  <button type="button" class="btn btn-success btn_operation" data-code="<?= @$iStud->id_user; ?>"
                    data-opt="reactive" data-toggle="modal" data-target="#students_modal">
                    <i class="fas fa-edit"></i>
                    Reactive
                  </button>
              <?php }
            } else { ?>
              <button type="button" class="btn btn-success btn_operation" data-code="<?= @$iStud->id_user; ?>" data-opt="edit"
                data-toggle="modal" data-target="#students_modal">
                <i class="fas fa-edit"></i>
              </button>
              <button type="button" class="btn btn-success btn_rst_pass" data-code="<?= @$iStud->id_user; ?>" data-opt="reset"
                data-toggle="modal" data-target="#students_modal">
                <i class="fas fa-undo"></i> Password
              </button>
            <?php } ?>
          </td>
        </tr>
      <?php }
    } ?>
  </tbody>
</table>