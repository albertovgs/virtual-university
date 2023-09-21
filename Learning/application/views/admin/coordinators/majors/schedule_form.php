<div class="modal-content">
    <form id="schedule_form">
        <?php if (@$classFinded) { ?>
            <input type="hidden" name="code" id="code" value="<?= @$classFinded->id_class; ?>">
            <input type="hidden" name="option" id="option" value="<?= @$option; ?>">
        <?php } ?>
        <div class="modal-header">
            <h4 class="modal-title">Schedule Class</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-8">
                    <label for="exampleInputBorderWidth2">Class name:</label>
                    <select class="form-control input-group" name="ClassName" id="ClassName">
                        <option value="<?= @$classFinded->id_class; ?>">
                            <?= @$classFinded->name_class; ?>
                        </option>
                    </select>
                </div>
                <div class=" form-group col-4">
                    <label for="exampleInputBorderWidth2">Group:</label>
                    <select class="form-control input-group" name="inpGroup" id="inpGroup">
                        <option value="<?= @$classFinded->id_group; ?>">
                            <?= @$classFinded->clave_group; ?>
                        </option>
                    </select>
                </div>
                <div class="form-group col-4">
                    <label for="exampleInputBorderWidth2">Day:</label>
                    <select class="form-control input-group" name="inpDAy" id="inpDAy">
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Monday">Saturday</option>
                    </select>
                </div>
                <div class="form-group col-4">
                    <label for="exampleInputBorderWidth2">Start time:</label>
                    <input type="time" class="form-control input-group-append" id="startTime" name="startTime"
                        min="08:00" max="20:00" required>
                </div>
                <div class="form-group col-4">
                    <label for="exampleInputBorderWidth2">Start time:</label>
                    <input type="time" class="form-control input-group-append" id="endTime" name="endTime" min="08:00"
                        max="20:00" required>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save schedule</button>
        </div>
    </form>
</div>

<script>
    $(function () {
        $('[data-mask]').inputmask();
    });
</script>