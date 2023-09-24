<div class="content-wrapper">
  <div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Change your password</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="col-md-6 mx-auto d-block">
      <div class="card card-primary card-outline">
        <br />
        <div class="text-center">
          <img src="http://localhost/Learning/resources/dist/img/isotype.png" class="mx-auto d-block" alt="isotype">
          <p class="login-box-msg text-danger">
            <?php
            if (@$this->session->flashdata("error_chng")) {
              echo $this->session->flashdata("error_chng");
            }
            ?>
          </p>
        </div>
        <div class="card-body login-card-body">
          <p class="login-box-msg">You are only one step a way from your new password, create your password now to
            finish and start to use the system.</p>
          <p class="login-box-msg">Remember you have to login againg after change the password.</p>
          <form action="<?= base_url('home/changePWD'); ?>" method="post">
            <div class="input-group mb-3">
              <input type="password" class="form-control" placeholder="Password" name="inpPWD" id="inpPWD">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" placeholder="Confirm Password" name="inpConfPWD"
                id="inpConfPWD">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Change password</button>
              </div>
            </div>
          </form>
        </div>
      </div>
  </section>
</div>