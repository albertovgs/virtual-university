<?php $session = $this->session->userdata('up_sess'); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <?= @$class->name_class; ?> <small>Class</small>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <?php if ($session->type_user == "Teacher") { ?>
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>Classwork</h3>
                            </div>
                            <div class="icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <a href="javascript::" class="small-box-footer" id="clsWork"
                                data-cls="<?= @$class->clave_class ?>">
                                Create a new one. <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Grades</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>First part</th>
                                        <th>Second part</th>
                                        <th>Final Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php if ($session->type_user == "Teacher") { ?>
                                                <button type="button" class="btn btn-block btn-info btn-sm btn_grades"
                                                    data-opt="first">Grade first part.</button>
                                            <?php } else {
                                                echo @$grades->calf_f_class;
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($session->type_user == "Teacher") { ?>
                                                <button type="button" class="btn btn-block btn-info btn-sm btn_grade"
                                                    data-opt="second">Grade first part.</button>
                                            <?php } else {
                                                echo @$grades->calf_s_class;
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($session->type_user == "Teacher") { ?>
                                                <button type="button"
                                                    class="btn btn-block btn-info btn-sm btn_grade_cls">Grade
                                                    Class.</button>
                                            <?php } else {
                                                echo @$grades->calf_class;
                                            } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Ratings</h3>
                            <?php if ($session->type_user == "Teacher") { ?>
                                <button type="button" class="float-right btn badge bg-primary" title="Config" id="cnf_rate"
                                    data-id="<?= @$rate->id_class_rate; ?>"><i class="fa fa-cog"></i>
                                </button>
                            <?php } ?>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>To be</td>
                                        <td>
                                            <?= @$rate->be_rate; ?><sup style="font-size: 10px">%</sup>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>To do</td>
                                        <td>
                                            <?= @$rate->do_rate; ?><sup style="font-size: 10px">%</sup>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>To know</td>
                                        <td>
                                            <?= @$rate->know_rate; ?><sup style="font-size: 10px">%</sup>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Schedules</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Start</th>
                                        <th>End</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (@$schuedules) {
                                        foreach ($schuedules as $Sche) { ?>
                                            <tr>
                                                <td>
                                                    <?= @$Sche->day_schedule; ?>
                                                </td>
                                                <td>
                                                    <?= @$Sche->start_schedule; ?>
                                                </td>
                                                <td>
                                                    <?= @$Sche->end_schedule; ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8" id="classworks">

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