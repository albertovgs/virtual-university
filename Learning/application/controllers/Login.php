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
			$user_exist = $this->DAO->login($this->input->post("inpEmail"), $this->input->post("inpPassword"));
			echo json_encode($user_exist);
			if ($user_exist["status"] == "success") {
				if ($user_exist["data"]->status_user != "Inactive") {
					$this->session->set_userdata("up_sess", $user_exist["data"]);
					redirect("");
				}
				$this->session->set_flashdata("error_login", "Your account isn't active.");
				redirect("login");
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