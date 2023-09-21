<div class="modal-content">
    <form id="<?= $id ?>">
        <input type="text" hidden name="inpId" id="inpId" value="<?= @$code; ?>">
        <input type="text" hidden name="inpOp" id="inpOp" value="<?= @$option; ?>">
        <div class="modal-header">
            <h1 class="card-title text-danger">Cormfirmation</h1>
        </div>
        <div class="modal-body">
            <div class="row">
                <h5 class="text-danger">
                    <?= $message; ?>
                </h5>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Confirm</button>
        </div>
    </form>
</div>