<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->_check_session();
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function auth()
	{
		$this->load->model("DAO");
		if ($this->input->post("inpEmail") && $this->input->post("inpPassword")) {
			$user_exist = $dats = $this->DAO->login($this->input->post("inpEmail"), $this->input->post("inpPassword"));
			if ($user_exist["status"] == "success") {
				$this->session->set_userdata("up_sess", $user_exist["data"]);
				redirect("");
			} else {
				$this->session->set_flashdata("error_login", $user_exist["message"]);
				redirect("login");
			}
		} else {
			$this->session->set_flashdata("error_login", "The information is needed");
			redirect("login");
		}
	}

	private function _check_session()
	{
		$session = $this->session->userdata('up_sess');
		if (@$session->email_user) {
			redirect('');
		}
	}
}