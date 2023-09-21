<div class="modal-content">
    <form id="form_ratesConf">
        <input type="text" hidden value="<?= @$rateCls->id_class_rate; ?>" name="classRte" id="classRte">
        <div class="modal-header">
            <h5 class="modal-title">Edit Rates for the classwork</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-4 form-group">
                    <label>To be:</label>
                    <input type="number" class="form-control" value="<?= @$rateCls->be_rate; ?>" name="inpBe"
                        id="inpBe">
                </div>
                <div class="col-4 form-group">
                    <label>To do:</label>
                    <input type="number" class="form-control" value="<?= @$rateCls->do_rate; ?>" name="inpDo"
                        id="inpDo">
                </div>
                <div class="col-4 form-group">
                    <label>To know:</label>
                    <input type="number" class="form-control" value="<?= @$rateCls->know_rate; ?>" name="inpKnow"
                        id="inpKnow">
                </div>
            </div>
        </div>
        <div class="modal-footer modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">save</button>
        </div>
    </form>
</div>