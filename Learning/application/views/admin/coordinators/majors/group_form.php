<div class="modal-content">
    <form id="group_form" data-id="<?= @$major->id_major; ?>">
        <div class="modal-header">
            <h4 class="modal-title">New Group</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-12">
                    <label for="exampleInputBorderWidth2">Major:</label>
                    <select readonly class="form-control input-group" name="inpMajor" id="inpMajor">
                        <?php if (@$major) { ?>
                            <option value="<?= @$major->id_major; ?>">
                                <?= @$major->name_major; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label for="exampleInputBorderWidth2">Clave major:</label>
                    <input readonly type="text" class="form-control" value="<?= @$major->clave_major; ?>">
                </div>
                <div class="form-group col-6">
                    <label for="exampleInputBorderWidth2">The new group's clave:</label>
                    <input readonly type="text" class="form-control" value="<?= @$clave_group; ?>">
                </div>
                <div class="form-group col-12">
                    <label for="exampleInputBorderWidth2">Period:</label>
                    <select readonly class="form-control input-group" name="inpPeriod" id="inpPeriod">
                        <?php if (@$period) { ?>
                            <option value="<?= @$period->id_period; ?>">
                                <?= @$period->name_period; ?>
                            </option>
                        <?php } ?>
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

<script>
    $(function () {
        $('[data-mask]').inputmask()
    });
</script>