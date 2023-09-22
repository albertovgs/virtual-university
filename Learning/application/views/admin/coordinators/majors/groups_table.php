<table id="groupsTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Last Period</th>
            <th>Nmber of Students</th>
            <th>Status Group</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (@$groups) {
            foreach (@$groups as $iGrp) { ?>
                <tr>
                    <td>
                        <?= @$iGrp->clave_group; ?>
                    </td>
                    <td>
                        <?= @$iGrp->last_quarter; ?>
                    </td>
                    <td>
                        <?= @$iGrp->nmb_students; ?>
                    </td>
                    <td>
                        <?= @$iGrp->status_group; ?>
                    </td>
                    <td>
                        <button type="button" class="btn btn-success btn_Gtool" data-code="<?= @$iGrp->id_group; ?>"
                            data-opt="level-up" data-id="<?= @$iGrp->id_group; ?>" data-toggle="modal"
                            data-target="#major_admin_modal">
                            <i class="fas fa-sort-numeric-up-alt"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn_Gtool" data-code="<?= @$iGrp->id_group; ?>"
                            data-opt="shutdown" data-id="<?= @$iGrp->id_group; ?>" data-toggle="modal"
                            data-target="#major_admin_modal">
                            <i class="fa fa-users-slash"></i>
                        </button>
                    </td>
                </tr>
            <?php }
        } ?>
    </tbody>
</table>