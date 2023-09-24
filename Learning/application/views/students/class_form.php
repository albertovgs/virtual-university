<div class="modal-content">
    <form id="form_classwork">
        <?php
        if (@$option == "edit") {
            $title = "Edit"; ?>
            <input type="text" hidden value="<?= @$option; ?>" name="option" id="option">
            <input type="text" hidden value="<?= @$class->id_classwork; ?>" name="inpCls" id="inpCls">
            <?php
        } else {
            $title = "Assign new";
        }
        ?>
        <input type="text" hidden value="<?= @$cls; ?>" name="cls" id="cls">
        <div class="modal-header">
            <h5 class="modal-title">
                <?= $title; ?> classwork
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 form-group">
                    <label>Title:</label>
                    <input type="text" class="form-control" value="<?= @$class->title_classwork; ?>" name="inpTitle"
                        id="inpTitle">
                </div>
                <div class="col-12 form-group">
                    <label>Description:</label>
                    <textarea type="text" class="form-control" name="inpDesc"
                        id="inpDesc"><?= @$class->content_classwork; ?></textarea>
                </div>
                <div class="col-6 form-group">
                    <label>Due date:</label>
                    <?= @$class->date_end_classwork; ?>
                    <input type="date" class="form-control datetimepicker-input"
                        value="<?= @$class->date_end_classwork; ?>" name="inpDueDate" id="inpDueDate"
                        min="<?= date("Y") . "-" . date("m") . "-" . date("d"); ?>">
                </div>
                <div class="col-6 form-group">
                    <label>Due time:</label>
                    <input type="time" class="form-control datetimepicker-input"
                        value="<?= @$class->time_end_classwork; ?>" name="inpDueTime" id="inpDueTime">
                </div>
                <div class="col-6 form-group">
                    <label>Partial:</label>
                    <select type="text" class="form-control" name="inpPart" id="inpPart">
                        <option value="<?= @$class->part_classwork; ?>" hidden>
                            <?= @$class->part_classwork ? $class->part_classwork : 'Select one'; ?>
                        </option>
                        <option>Firts</option>
                        <option>Second</option>
                    </select>
                </div>
                <div class="col-6 form-group">
                    <label>Rate by:</label>
                    <select type="text" class="form-control" name="inpRate" id="inpRate">
                        <option value="<?= @$class->type_classwork; ?>" hidden>
                            <?= @$class->type_classwork ? $class->type_classwork : 'Select one'; ?>
                        </option>
                        <option>To be</option>
                        <option>To do</option>
                        <option>To know</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>