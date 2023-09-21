<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Select2 extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    //$this->_check_session();
    $this->load->model('DAO');
  }

  public function Cordination()
  {
    $filter          = array(
      "type_user" => "Cordi",
      "status_user" => "Active",
    );
    $data['results'] = $this->DAO->getCordination($filter, FALSE);
    echo json_encode($data);
  }
}