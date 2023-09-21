<?php $session = $this->session->userdata('up_sess');
if ($session->type_user == "Studen") {
  $title = $session->name_major;
} else {
  $title = $session->name_person . " " . $session->lastname_person;
}
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">
            <?= @$title; ?> <small>Classes</small>
          </h1>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-4" id="contentCls">

        </div>
        <div class="col-lg-8" id="contentAdv">
        </div>
      </div>
    </div>
  </div>
</div>