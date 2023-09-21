<form id="form_posts">
    <?php if (@$advertisements) { ?>
        <input type="hidden" name="inpCode" id="inpCode" value="<?= @$find_post->id_advertisement; ?>">
        <input type="hidden" name="inpOpt" id="inpOpt" value="<?= @$accion; ?>">
    <?php } ?>
    <div class="modal-header">
        <h5 class="modal-title">
            <?= @$title ?> - Advertisement
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">

        <div class="row">
            <div class="col-12 form-group">
                <label>Title:</label>
                <input type="text" <?= @$accion == "trash" ? 'readonly' : ''; ?> class="form-control" name="inpTitle"
                    id="inpTitle" value="<?= @$advertisements->title_advertisement; ?>">
            </div>
            <div class="col-12 form-group">
                <label>Contenido:</label>
                <textarea <?= @$accion == "trash" ? 'readonly' : ''; ?> class="form-control" name="inpCont"
                    id="inpCont"><?= @$advertisements->content_advertisement; ?></textarea>
            </div>
            <div class="col-6 form-group">
                <label>Image:</label>
                <div class="custom-file">
                    <input type="file" accept="image/png, .jpeg, .jpg, .webp" class="custom-file-input"
                        name="inpImgFile" id="inpImgFile">
                    <label class="custom-file-label" for="exampleInputFile">Choose Image</label>
                </div>
            </div>
            <div class="col-6 form-group">
                <label>Video:</label>
                <div class="custom-file">
                    <input type="file" accept="video/mkv, .mp4, .mov" class="custom-file-input" name="inpVidFile"
                        id="inpVidFile">
                    <label class="custom-file-label" for="exampleInputFile">Choose Video</label>
                </div>
            </div>
            <div class="col-12 form-group">
                <label>Document:</label>
                <div class="custom-file">
                    <input type="file" accept="document/docx, .odt, .pdf, .csv, .xlsx" class="custom-file-input"
                        name="inpDocFile" id="inpDocFile">
                    <label class="custom-file-label" for="exampleInputFile">Choose Document</label>
                </div>
            </div>
            <label for="focus group" class="col-3 col-form-label">Focus group:</label>
            <div class="col-9">
                <select class="form-control" <?= @$accion == "borrar" ? 'disabled' : ''; ?> name="inpFG" id="inpFG">
                    <?php if (@$advertisements) { ?>
                        <option value="<?= @$advertisements->show_to_advertisement; ?>">
                            <?= @$advertisements->show_to_advertisement; ?>
                        </option>
                    <?php } else { ?>
                        <option value="">Select one.</option>
                    <?php } ?>

                    <option value="All">All</option>
                    <option value="Theachers">Only theachers</option>
                    <?php if (@$majors) { /*echo json_encode($majors)*/
                        ;
                        foreach (@$majors as $key => $major) {
                            ?>
                            <option value="<?= @$major->clave_major; ?>">
                                <?= @$major->name_major; ?> (
                                <?= @$major->clave_major; ?>)
                            </option>
                        <?php }
                    } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        bsCustomFileInput.init()
    })
</script>