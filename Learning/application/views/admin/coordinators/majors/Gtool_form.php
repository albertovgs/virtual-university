<?php
if ($this->input->get('option') == "level-up") {
    ?>
    <div class="modal-content">
        <form id="Gtool_form" data-id="<?= $id; ?>">
            <input hidden type="number" value="<?= $group->id_group; ?>" id="inpIdGrp" name="inpIdGrp">
            <div class="modal-header">
                <h4 class="modal-title">Upgrade quarter group
                    <?= $group->clave_group; ?>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label for="exampleInputBorderWidth2">Clave group:</label>
                        <input readonly="" type="text" class="form-control" value="<?= @$group->clave_group; ?>" id="inpGrp"
                            name="inpGrp">
                    </div>
                    <div class="form-group col-3">
                        <label for="exampleInputBorderWidth2">Quarters:</label>
                        <input readonly="" type="number" class="form-control" value="<?= @$quarters; ?>">
                    </div>
                    <div class="form-group col-3">
                        <label for="exampleInputBorderWidth2">Next quarter:</label>
                        <input readonly="" type="number" class="form-control" value="<?= @$quarters + 1; ?>">
                    </div>
                    <div class="form-group col-6">
                        <label for="exampleInputBorderWidth2">Last period:</label>
                        <select readonly="" class="form-control input-group" name="inpLastPeriod" id="inpLastPeriod">
                            <option value="<?= @$group->last_quarter; ?>">
                                <?= @$group->last_quarter; ?>
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="exampleInputBorderWidth2">New period:</label>
                        <select readonly="" class="form-control input-group" name="inpCurrPeriod" id="inpCurrPeriod">
                            <option value="<?= @$currentQua->id_period; ?>">
                                <?= @$currentQua->name_period; ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save group</button>
            </div>
        </form>
    </div>

    <?php
} elseif ($this->input->get('option' == "shutdown")) { ?>
<?php
} ?>

<script>
    $(function () {
        $('[data-mask]').inputmask()
    });
</script>