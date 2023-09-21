<div class="modal-content">
    <form id="grade_form">
        <input type="text" hidden value="<?= @$clsWrk; ?>" name="clsWrk" id="clsWrk">
        <div class="modal-header">
            <h5 class="modal-title">Grade classwork.</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 form-group">
                    <label>Grade:</label>
                    <input type="number" class="form-control" name="inpGrade" id="inpGrade" min="0" max="100">
                </div>
                <div class="col-12 form-group">
                    <label>Comments:</label>
                    <textarea type="text" class="form-control" name="inpComment" id="inpComment"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">save</button>
        </div>
    </form>
</div>