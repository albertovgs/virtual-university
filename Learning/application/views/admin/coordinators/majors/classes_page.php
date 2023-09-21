<div class="row">
    <div class="col-lg-4 col-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Registrations</h3>
                <p>Classes</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <a href="javascript::" class="small-box-footer" data-toggle="modal" data-target="#major_admin_modal"
                id="regClasses" data-id="<?= $id; ?>">
                Register one <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Show Active</h3>
                <p>Classes</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <a href="javascript::" class="small-box-footer load_class" data-status="active" data-id="<?= $id; ?>">
                show them <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Show Inactive</h3>
                <p>Classes</p>
            </div>
            <div class="icon">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <a href="javascript::" class="small-box-footer load_class" data-status="inactive" data-id="<?= $id; ?>">
                show them <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
<section class="content">
    <div class="card">
        <div class="card-body" id="classes_content">

        </div>
    </div>
</section>