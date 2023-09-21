<div class="modal-content">
    <form id="classes_form" data-id="<?= $id; ?>">
        <?php if (@$classFinded) { ?>
            <input type="hidden" name="code" id="code" value="<?= @$classFinded->id_class; ?>">
            <input type="hidden" name="option" id="option" value="<?= @$option; ?>">
        <?php } ?>
        <?php if (@$option) {
            if (@$option == 'edit') {
                $titulo = "Edit " . $classFinded->name_class;
            } else {
                $titulo = "Assign " . $classFinded->name_class;
            }
        } else {
            $titulo = "Register a new";
        } ?>
        <div class="modal-header">
            <h4 class="modal-title">
                <?= $titulo; ?> Class
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-12">
                    <label for="exampleInputBorderWidth2">Class name:</label>
                    <input type="text" class="form-control" value="<?= @$classFinded->name_class; ?>"
                        name="inpClassName" id="inpClassName">
                </div>
                <div class="form-group col-3">
                    <label for="exampleInputBorderWidth2">Group:</label>
                    <select readonly class="form-control input-group" name="inpGroup" id="inpGroup">
                        <option value="<?= @$classFinded->id_group; ?>">
                            <?= @$classFinded->clave_group ? @$classFinded->clave_group : 'Select one'; ?>
                        </option>
                        <?php if (@$groups) {
                            foreach ($groups as $gpr) {
                                if ($classFinded->id_group != $gpr->id_group) {
                                    ?>
                                    <option value="<?= @$gpr->id_group; ?>">
                                        <?= @$gpr->clave_group; ?>
                                    </option>

                                <?php }
                            }
                        } ?>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="exampleInputBorderWidth2">Lab:</label>
                    <select readonly class="form-control input-group" name="inpclassRoom" id="inpclassRoom">
                        <option value="<?= @$classFinded->lab_class; ?>">
                            <?= @$classFinded->name_classroom ? @$classFinded->name_classroom : 'Select one'; ?>
                        </option>
                        <?php if (@$classrooms) {
                            foreach ($classrooms as $room) {
                                ?>
                                <option value="<?= @$room->id_classroom; ?>">
                                    <?= @$room->name_classroom; ?>
                                </option>

                                <?php
                            }
                        } ?>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label for="exampleInputBorderWidth2">Professor:</label>
                    <select readonly class="form-control input-group" name="inpProfessor" id="inpProfessor">
                        <option value="<?= @$classFinded->id_person; ?>">
                            <?= @$classFinded->name_person ? (@$classFinded->name_person . ' ' . @$classFinded->lastname_person) : 'Select one'; ?>
                        </option>
                        <?php if (@$profesors) {
                            foreach ($profesors as $pro) {
                                if ($classFinded->id_person != $pro->id_user) { ?>
                                    <option value="<?= @$pro->id_user; ?>">
                                        <?= @$pro->name_person . ' ' . @$pro->lastname_person; ?>
                                    </option>
                                <?php }
                            }
                        } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save class</button>
        </div>
    </form>
</div>

<script>
    $(function () {
        $('[data-mask]').inputmask()
    });
</script>