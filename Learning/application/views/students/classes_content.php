<?php //echo json_encode(@$classesStd);
if (@$classesStd) {
    foreach ($classesStd as $key => $Cls) {
        ?>
        <a href="<?= base_url('Classes?id=' . $Cls->clave_class); ?>">
            <div class="card card-widget widget-user">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">
                        <?= $Cls->name_class; ?>
                    </h3>
                    <h5 class="widget-user-desc">
                        <?= $Cls->name_person; ?>
                    </h5>
                    <h5 class="widget-user-desc">
                        <?= $Cls->clave_group; ?>
                    </h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Clave</h5>
                                <span class="description-text">
                                    <?= $Cls->clave_class; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Period</h5>
                                <span class="description-text">
                                    <?= $Cls->name_period; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">Classroom</h5>
                                <span class="description-text">
                                    <?= $Cls->name_classroom; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    <?php }
} ?>