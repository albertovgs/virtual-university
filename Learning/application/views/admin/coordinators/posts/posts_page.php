<div class="col-12">
    <div class="card">
        <div class="card-header">
            <button type="button" id="btn_new_post" data-toggle="modal" data-target="#modal_posts"
                class="btn btn-primary btn-sm"><i class="fas fa-pen-nib"></i>New Advertisement</button>
        </div>
        <div class="card-body">
            <section class="content">
                <div class="row">
                    <?php foreach ($posts as $epost) {
                        $session = $this->session->userdata('up_sess');
                        if ($session->id_user == $epost->fk_user_advertisement || $epost->show_to_advertisement == "All" || $epost->show_to_advertisement == $session->type_user) {
                            if ($epost->status_advertisement == 'Active') {
                                ?>
                                <div class="container col-sm-12 col-lg-10">
                                    <div class="card card-widget collapsed-card">
                                        <div class="card-header">
                                            <div class="user-block">
                                                <?php if ($epost->img_user) { ?>
                                                    <img src="<?= $epost->img_user; ?>" class="img-circle elevation-2" alt="User Image">
                                                <?php } else { ?>
                                                    <img src="<?= base_url('resources/dist/img/user3-128x128.jpg'); ?>"
                                                        class="img-circle elevation-2" alt="User Image">
                                                <?php } ?>
                                                <span class="username">
                                                    <a href="#" class="btn_profileExt" data-us="<?= $epost->id_user; ?>">
                                                        <?= $epost->name_person . " " . $epost->lastname_person; ?> |
                                                        <?= $epost->title_advertisement . " To " . $epost->show_to_advertisement; ?>
                                                    </a>
                                                </span>
                                                <span class="description">
                                                    <?= $epost->title_advertisement; ?> -
                                                    <?= $epost->show_to_advertisement; ?> -
                                                    <?= $epost->creation_date_advertisement; ?>
                                                </span>
                                            </div>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool btn_edit_post" data-opt="edit"
                                                    data-ps="<?= $epost->id_advertisement; ?>" data-toggle="modal"
                                                    data-target="#modal_posts" title="Edit"><i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool btn_delete_post"
                                                    data-ps="<?= $epost->id_advertisement; ?>" data-op="delete" data-item="post"
                                                    title="Delete"><i class="fas fa-trash"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                    title="Expand"><i class="fas fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Hide"><i
                                                        class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body" style="display: none;">
                                            <p>
                                                <?= $epost->content_advertisement; ?>
                                            </p>
                                            <div class=" row">
                                                <?php $elem = 0;
                                                @$epost->img_path_advertisement ? $elem = $elem + 1 : '';
                                                @$epost->vid_path_advertisement ? $elem = $elem + 1 : '';
                                                @$epost->doc_path_advertisement ? $elem = $elem + 1 : '';
                                                if ($elem == 1) {
                                                    $col = "col-12";
                                                }
                                                if ($elem == 2) {
                                                    $col = "col-6";
                                                }
                                                if ($elem == 3) {
                                                    $col = "col-4";
                                                }
                                                ?>
                                                <?php if (@$epost->img_path_advertisement) { ?>
                                                    <img class="img-fluid pad <?= $col ?>" src="<?= @$epost->img_path_advertisement; ?>"
                                                        alt="Photo" loading="lazy">
                                                <?php }
                                                if (@$epost->vid_path_advertisement) { ?>
                                                    <video class="img-fluid pad <?= $col ?>"
                                                        src="<?= @$epost->vid_path_advertisement; ?>" controls loading="lazy">
                                                    </video>
                                                <?php }
                                                if (@$epost->doc_path_advertisement) { ?>
                                                    <object frameborder="0" data="<?= @$epost->doc_path_advertisement ?>"
                                                        type="application/pdf" class="attachment-block clearfix <?= $col ?>"
                                                        height="600px">
                                                        <p>You do not have a plugin to visualize the document.</p>
                                                        <p>You can download the document <a
                                                                href="<?= @$epost->doc_path_advertisement ?>">here</a></p>
                                                    </object>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="card-footer img-push" style="display: none;"">
                                            <button type=" button" class="form-control form-control-sm btn_commt"
                                            data-us="<?= $epost->id_user; ?>" data-ps="<?= $epost->id_advertisement; ?>"
                                            data-toggle="modal" data-target="#modal_comment">Comentar</button>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                    } ?>
            </section>
        </div>
        <div class=" modal fade" id="modal_posts" style="display: none;" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="content_modal">

                </div>
            </div>
        </div>
        <div class="modal fade" id="modal_comment" style="display: none;" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="content_comment_modal">

                </div>
            </div>
        </div>
    </div>
</div>