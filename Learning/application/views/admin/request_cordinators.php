<button type="button" id="btn_new_rqst" data-toggle="modal" data-target="#req" class="btn btn-primary btn-sm">
    <i class="fas fa-pen-nib"></i>New Request
</button>
<br /><br />
<section class="content row">
    <?php if (@$requests) {
        foreach (@$requests as $key => $req) {
            ?>
            <div class="col-lg-4 col-md-3 col-12">
                <div class="card card-primary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?= $req->title_request ?> -
                            <?= $req->status_request ?>
                        </h3>
                        <div class="card-tools">
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?= $req->request ?>
                    </div>
                </div>
            </div>
        <?php }
    } ?>

    <div class="modals">
        <div class="modal fade" id="req">
            <div class="modal-dialog" id="cnfContent">

            </div>
        </div>
    </div>
</section>

<script>
    $(document).on('click', '#btn_new_rqst', function () {
        event.preventDefault();
        $.ajax({
            url: "<?= base_url('Posts/request_form') ?>",
            method: "get",
            success: function (response) {
                $(document).find('#req').show();
                $(document).find("#cnfContent").empty().append(response);
            }
        });
    });
</script>