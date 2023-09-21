<div class="modal-content">
    <form id="period_form" data-id="<?= @$major->id_major; ?>">
        <div class="modal-header">
            <h4 class="modal-title">New Period</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <?php if (@$id) { ?>
                    <div class="form-group col-7">
                        <label for="exampleInputBorderWidth2">Major:</label>
                        <select class="form-control input-group" name="inpMajor" id="inpMajor">
                            <?php if (@$major) { ?>
                                <option value="<?= @$major->id_major; ?>">
                                    <?= @$major->name_major; ?> (
                                    <?= @$major->clave_major; ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-5">
                        <label for="name">Name:</label>
                        <input type="text" readonly class="form-control" id="inpNameP" name="inpNameP"
                            value="<?= @$period->name_period; ?>">
                    </div>
                    <div class="form-group col-12">
                        <label for="exampleInputBorderWidth2">Period Data:</label>
                        <div class="row">
                            <div class="input-group col-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" readonly class="form-control" data-inputmask-alias="datetime"
                                    data-inputmask-inputformat="yyyy/mm/dd" data-mask="" inputmode="numeric"
                                    id="inpDateStart" name="inpDateStart" value="<?= @$period->start_date_period; ?>">
                            </div>
                            <div class="input-group col-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" readonly class="form-control" data-inputmask-alias="datetime"
                                    data-inputmask-inputformat="yyyy/mm/dd" data-mask="" inputmode="numeric" id="inpDateEnd"
                                    name="inpDateEnd" value="<?= @$period->end_date_period; ?>">
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="form-group col-12">
                        <label for="exampleInputBorderWidth2">Start Date Period:</label>
                        <div class="row">
                            <div class="input-group col-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" data-inputmask-alias="datetime"
                                    data-inputmask-inputformat="dd" data-mask="" inputmode="numeric" id="inpDDStart"
                                    name="inpDDStart">
                            </div>
                            <div class="input-group col-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" data-inputmask-alias="datetime"
                                    data-inputmask-inputformat="mm" data-mask="" inputmode="numeric" id="inpMMStart"
                                    name="inpMMStart">
                            </div>
                            <div class="input-group col-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" data-inputmask-alias="datetime"
                                    data-inputmask-inputformat="yyyy" data-mask="" inputmode="numeric" id="inpYYYYStart"
                                    name="inpYYYYStart">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label for="exampleInputBorderWidth2">End Date Period:</label>
                        <div class="row">
                            <div class="input-group col-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" data-inputmask-alias="datetime"
                                    data-inputmask-inputformat="dd" data-mask="" inputmode="numeric" id="inpDDEnd"
                                    name="inpDDEnd">
                            </div>
                            <div class="input-group col-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" data-inputmask-alias="datetime"
                                    data-inputmask-inputformat="mm" data-mask="" inputmode="numeric" id="inpMMEnd"
                                    name="inpMMEnd">
                            </div>
                            <div class="input-group col-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" data-inputmask-alias="datetime"
                                    data-inputmask-inputformat="yyyy" data-mask="" inputmode="numeric" id="inpYYYYEnd"
                                    name="inpYYYYEnd">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save period</button>
        </div>
    </form>
</div>

<script>
    $(function () {
        $('[data-mask]').inputmask()
    });
</script>