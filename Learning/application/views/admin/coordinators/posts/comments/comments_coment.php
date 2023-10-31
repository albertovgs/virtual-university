<?php
$session = $this->session->userdata('up_sess');
if (@$comments) {
  foreach ($comments as $iCom) {
    if ($iCom->status_comment == 'Active') {
      ?>
      <div class="card-comment">
        <?php if ($iCom->img_user) { ?>
          <img src="<?= base_url("/resources/dist/img/" . @$iCom->img_user); ?>" class="img-circle elevation-2"
            alt="User Image">
        <?php } ?>

        <div class="comment-text">
          <span class="username">
            <?= @$iCom->name_person . ' ' . @$iCom->lastname_person; ?> |
            <?= @$iCom->type_user; ?>
            <span class="text-muted float-right">
              <?= @$iCom->update_date_comment; ?>
            </span>
          </span>
          <?= @$iCom->content_comment; ?>

          <?php if ($iCom->id_user == $session->id_user) {
            ?>
            <span class="button-group-append float-right">
              <button type="button" class="btn btn-tool btn_cm_operacion" data-ps="<?= @$iCom->id_comment; ?>" data-opt="edit">
                <i class="fa fa-pen"></i>
              </button>
              <button type="button" class="btn btn-tool btn_delete_post" data-ps="<?= $iCom->id_comment; ?>" data-op="delete"
                data-item="comment">
                <i class="fas fa-times"></i>
              </button></span>
          <?php } else if ($session->type_user == "Cordi") { ?>
              <span class="button-group-append float-right">
                <button type="button" class="btn btn-tool btn_delete_post" data-ps="<?= @$iCom->id_comment; ?>" data-op="delete"
                  data-item="comment">
                  <i class="fas fa-times"></i>
                </button></span>
          <?php } ?>
        </div>
      </div>
    <?php }
  }
} else { ?>
  <h3>There is not comments yet.</h3>
<?php } ?>