<div class="modal-content">
    <form id="deliver_classwork">
        <input type="text" hidden value="<?= @$dats["cls"]; ?>" name="cls" id="cls">
        <input type="text" hidden value="<?= @$dats["grp"]; ?>" name="grp" id="grp">
        <input type="text" hidden value="<?= @$dats["wrk"]; ?>" name="wrk" id="wrk">
        <div class="modal-header">
            <h5 class="modal-title">Deliver classwork</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 form-group">
                    <label>File:</label>
                    <div class="custom-file">
                        <input type="file" accept="*" class="custom-file-input" name="inpFile" id="inpFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose a file to upload</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">upload</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        bsCustomFileInput.init()
    })
</script>