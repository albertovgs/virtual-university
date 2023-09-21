<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Periods</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(''); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Majors</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <button type="button" id="regPeriods" data-toggle="modal" data-target="#major_admin_modal"
                class="btn btn-primary btn-lg">
                <i class="fas fa-pen-nib"></i> New Period
            </button>
        </div>
        <br />
        <section class="content">
            <div class="card">
                <div class="card-body" id="periods_content">

                </div>
            </div>
        </section>
        <div class="modal fade" id="major_admin_modal" style="display: none;" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog" id="mjContent">

            </div>
        </div>
    </section>
</div>