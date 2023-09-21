<?php //echo json_encode($classworks);
if (@$classworks) {
    foreach ($classworks as $clsWk) {
        ?>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h1 class="card-title">
                    <?= $clsWk->title_classwork; ?>
                </h1>
                <span class="float-right badge bg-primary">
                    <?= $clsWk->time_end_classwork; ?>
                </span>
                <span class="float-right badge bg-primary">Due to
                    <?= $clsWk->date_end_classwork; ?>
                </span>
            </div>
            <div class="card-body">
                <h6 class="card-title">For the
                    <?= $clsWk->part_classwork; ?> part of period
                </h6>
                <p class="card-text">
                    <?= $clsWk->content_classwork; ?>
                </p>
                <?php $session = $this->session->userdata("up_sess");
                if ($session->type_user == "Teacher") { ?>
                    <div class="btn-group float-right">
                        <a href="<?= base_url('Classes/ClsWork?clswkid=' . $clsWk->id_classwork . "&gpc=" . $clsWk->fk_gpc); ?>"
                            class="btn btn-info" title="Rate">
                            <i class="fa fa-clipboard-check"></i>
                        </a>
                        <button type="button" class="btn btn-info btn_opt" data-opt="edit" data-clsw="<?= $clsWk->id_classwork; ?>"
                            title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-info btn_opt" data-opt="drop" data-clsw="<?= $clsWk->id_classwork; ?>"
                            title="Shutdown">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php } else { ?>
                    <a href="<?= base_url('Classes/ClsWork?clswkid=' . $clsWk->id_classwork . "&gpc=" . $clsWk->fk_gpc); ?>"
                        class="float-right btn btn-info" title="Rate">
                        <i class="fas fa-file-upload"></i> Deliver
                    </a>
                <?php } ?>
            </div>
        </div>
        <?php
    }
}
?>