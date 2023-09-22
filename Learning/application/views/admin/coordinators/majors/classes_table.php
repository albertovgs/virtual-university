<table id="classesTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Clave</th>
            <th>Professor</th>
            <th>Group</th>
            <th>Period</th>
            <th>Classroom</th>
            <th>Schedules</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (@$classes) {
            foreach (@$classes as $iCls) { ?>
                <tr>
                    <td>
                        <?= @$iCls->name_class; ?>
                    </td>
                    <td>
                        <?= @$iCls->clave_class; ?>
                    </td>
                    <td>
                        <?= @$iCls->name_person . " " . @$iCls->lastname_person; ?>
                    </td>
                    <td>
                        <?= @$iCls->clave_group; ?>
                    </td>
                    <td>
                        <?= @$iCls->name_period; ?>
                    </td>
                    <td>
                        <?= @$iCls->name_classroom; ?>
                    </td>
                    <td>
                        <?php if (@$schedules) {
                            foreach (@$schedules as $sche) {
                                if ($sche->fk_class == $iCls->id_class) {
                                    echo ($sche->day_schedule . " " . $sche->start_schedule . " " . $sche->end_schedule);
                                    ?>
                                    <br />
                                    <?php
                                }
                            }
                        } ?>
                    </td>
                    <td>
                        <?php if (@$option) { ?>
                            <button type=" button" class="btn btn-success btn_Ctool" data-id="<?= @$iCls->id_class; ?>"
                                data-opt="assign" data-major="<?= @$iCls->id_major; ?>" data-toggle="modal"
                                data-target="#major_admin_modal" title="re-assign">
                                <i class="fas fa-cogs"></i>
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-success btn_Ctool" data-id="<?= @$iCls->id_class; ?>"
                                data-opt="schedule" data-major="<?= @$iCls->id_major; ?>" data-toggle="modal"
                                data-target="#major_admin_modal" title="add schedule">
                                <i class="fas fa-calendar-alt"></i>
                            </button>
                            <button type="button" class="btn btn-success btn_Ctool" data-id="<?= @$iCls->id_class; ?>"
                                data-opt="edit" data-major="<?= @$iCls->major_group; ?>" data-toggle="modal"
                                data-target="#major_admin_modal" title="edit class">
                                <i class="fas fa-edit"></i>
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php }
        } ?>
    </tbody>
</table>