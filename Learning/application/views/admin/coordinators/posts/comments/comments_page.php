<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Comments</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="col-md-12">
        <div class="card card-primary collapsed-card">
          <div class="card-header">
            <h3 class="card-title">Show all the Comments</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body" style="display: none;">
            <div class="card-footer card-comments" id="registered_comment" style="display: block;">

            </div>
          </div>
        </div>
      </div>
      <form id="form_comments">
        <div class="input-group">
          <input type="text" hidden name="ePos" id="ePos" value="<?= $advertisement->id_advertisement; ?>">
          <input type="text" name="eComm" id="eComm" placeholder="Write a comment..." class="form-control">
          <span class="input-group-append">
            <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i>send</button>
          </span>
        </div>
      </form>
    </div>
  </div>
</div>