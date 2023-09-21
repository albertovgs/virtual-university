<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_notification extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->_check_session();
	}

	public function index()
	{
		$this->load->view('includes/header');
		$this->load->view('includes/navbar');
		$this->load->view('includes/menu');
		$this->load->view('includes/footer');
	}

	private function _check_session()
	{
		$session = $this->session->userdata('up_sess');
		if (!@$session->email_user) {
			redirect('login');
		}
	}
}
