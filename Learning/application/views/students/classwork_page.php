<?php $session = $this->session->userdata('up_sess');
//echo json_encode($session);
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="<?= base_url('Classes?id=' . $class->clave_class); ?>">
                        <h1 class="m-0"> <i class="fa fa-arrow-alt-circle-left"></i>
                            <?= $class->name_class; ?> <small>Class</small>
                        </h1>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php if ($session->type_user == "Teacher") {
                        foreach ($classworks as $clswrk) {
                            ?>
                            <div class="card card-widget collapsed-card">
                                <div class="card-header">
                                    <div class="user-block">
                                        <span class="username">
                                            <h5>
                                                <?= $clswrk->name_person; ?>
                                                <?= $clswrk->lastname_person; ?> ->
                                                <?= $clswrk->calf_classwork; ?>
                                            </h5>
                                        </span>
                                        <span class="description">
                                            <?= $clswrk->deliver_classwork; ?>
                                        </span>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool btn_grade" data-opt="grade"
                                            data-clsWrk="<?= @$clswrk->id_std_classwork; ?>" data-toggle="modal"
                                            data-target="#modal_classwork" title="grade"><i class="fa fa-file-signature"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Expand"><i
                                                class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Hide"><i
                                                class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" style="display: none;">
                                    <div class=" row">
                                        <object frameborder="0" data="<?= $clswrk->file_classwork; ?>" type="application/pdf"
                                            class="attachment-block clearfix col-12" height="800px">
                                            <p>You do not have a plugin to visualize the document.</p>
                                            <p>You can download the document<a href="<?= @$classworkStd->file_classwork; ?>"
                                                    class="btn btn-primary" download="classWork">
                                                    <i class="fas fa-download"></i> Download File.
                                                </a></p>
                                        </object>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="callout callout-info">
                            <h5>
                                <?= @$classwork->title_classwork; ?> -
                                <?= @$classwork->type_classwork; ?> -
                                <?= @$classwork->part_classwork; ?>
                            </h5>
                            <span class="float-right badge bg-primary">
                                <?= @$classwork->time_end_classwork; ?>
                            </span>
                            <span class="float-right badge bg-primary">Due to
                                <?= @$classwork->date_end_classwork; ?>
                            </span>

                            <p>
                                <?= @$classwork->content_classwork; ?>
                            </p>
                        </div>
                        <?php //echo json_encode($classworkStd);
                            if (@$classworkStd) { ?>
                            <div class="callout callout-info">
                                <h5>Delivered</h5>
                                <span class="float-right badge bg-primary">Delivered at
                                    <?= @$classworkStd->deliver_classwork; ?>
                                </span>
                                <p>
                                </p>
                                <object frameborder="0" data="<?= $classworkStd->file_classwork; ?>" type="application/pdf"
                                    class="attachment-block clearfix col-12" height="800px">
                                    <p>You do not have a plugin to visualize the document.</p>
                                    <p>You can download the document <a href="./resources/files/623f97f8da2c4.pdf">here</a></p>
                                </object>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="col-lg-4">
                    <?php if ($session->type_user != "Teacher") { ?>
                        <?php if (@$classwork->date_end_classwork >= (date("Y") . "-" . date("m") . "-" . date("d"))) {
                            if (@$classwork->date_end_classwork == (date("Y") . "-" . date("m") . "-" . date("d"))) {
                                @$classwork->time_end_classwork >= (date("H") . ":" . date("i") . ":" . "00") ? $true = TRUE : $true = FALSE;
                            } ?>
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>Classwork</h3>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-cloud-upload-alt"></i>
                                </div>
                                <a href="javascript::" class="small-box-footer" id="clsWorkStd"
                                    data-cls="<?= @$class->clave_class ?>" data-wrk="<?= @$this->input->get("clswkid"); ?>"
                                    data-toggle="modal" data-target="#modal_classwork">
                                    Upload work. <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        <?php } ?>

                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">Feedback</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <div class="col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
                                        <div class="info-box-content">
                                            <h1>
                                                <?= @$classworkStd->calf_classwork ? $classworkStd->calf_classwork : "0" ?>
                                            </h1>
                                        </div>
                                    </div>
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <?php if (@$comments) {
                                                foreach ($comments as $comment) { ?>
                                                    <span class="info-box-text">
                                                        <?= $comment->content_comment ?>
                                                    </span>
                                                <?php }
                                            } else { ?>
                                                <span class="info-box-text">Any Comments yet.</span>
                                            <?php } ?>
                                            <?php ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">classworks delivered</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <div class="col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fa fa-file-invoice"></i></span>
                                        <div class="info-box-content">
                                            <h1>
                                                <?= @$count ? @$count : "0"; ?>
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>

<div class=" modal fade" id="modal_classwork" style="display: none;" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" id="content_modal">

        </div>
    </div>
</div>