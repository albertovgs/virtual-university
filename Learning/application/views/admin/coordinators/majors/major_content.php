<div class="col-12">
    <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <div class="row">
                <?php if ($majors)
                    ; {
                    $n = 0;
                    foreach ($majors as $major) {
                        $n += 1;
                        ?>
                        <div class="col-md-4 col-sm-6 col-12">
                            <a href="javascript::" class="btn_major" data-id="<?= $major->id_major; ?>">
                                <div class="info-box bg-gradient-info">
                                    <span class="info-box-icon"><i class="fa fa-book-open"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">
                                            <h2 class="inner">
                                                <?= $major->clave_major; ?>
                                            </h2>
                                        </span>
                                        <span class="progress-description">
                                            <?= $major->name_major; ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>
        <div class="card-body" id="major_content">

        </div>
    </div>
</div>