<?php
$session = $this->session->userdata("up_sess");
if ($session->type_user == "Cordi") { ?>
    <div class="row">
        <div class="col-lg-4 col-4">
            <!-- small card -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Registrations</h3>

                    <p>Students</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="javascript::" class="small-box-footer" data-toggle="modal" data-target="#students_modal"
                    id="regStudents" data-id="<?= $id; ?>">
                    Register one <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-4">
            <!-- small card -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Show Active</h3>

                    <p>
                        <?= $active ?> Students
                    </p>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <a href="javascript::" class="small-box-footer" id="active">
                    show them <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-4">
            <!-- small card -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Show Inactive</h3>

                    <p>
                        <?= $inactive ?> Students
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-times"></i>
                </div>
                <a href="javascript::" class="small-box-footer" id="inactive">
                    show them <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

    </div>
<?php } ?>
<section class="content">
    <div class="card">
        <div class="card-body" id="wraper">

        </div>
    </div>
    <div class="modals">
        <div class="modal fade" id="students_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" id="stuContent">

            </div>
        </div>
    </div>
</section>