<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->_check_session();
		$this->load->model('DAO');
	}

	public function index()
	{
		$session = $this->session->userdata('up_sess');
		if ($session->type_user == "Studen" || $session->type_user == "Teacher") {
			$this->load->view('includes/header2');
			$this->load->view('includes/navbar2');
			if ($session->force_change_user == "Y") {
				$this->load->view('includes/pwdChange');
			} else {
				if ($session->type_user == "Studen") {
					$this->load->view('includes/classes_page');
				} else {
					$this->load->view('includes/classes_page');
				}
			}
			$this->load->view('includes/footer2');
			$this->load->view('students/students_js');
		} else if ($session->type_user == "Admin") {
			$this->load->view('includes/header');
			$this->load->view('includes/navbar');
			$this->load->view('includes/menu');
			if ($session->force_change_user == "Y") {
				$this->load->view('includes/pwdChange');
				$this->load->view('includes/footer');
			} else {
				$filter           = array(
					"status_request" => "Pending",
				);
				$data["requests"] = $this->DAO->queryEntity("tb_requests", $filter, False);
				$this->load->view('admin/requests', $data, False);
				$this->load->view('includes/footer');
				$this->load->view('admin/request_js');
			}
		} else {
			$this->load->view('includes/header');
			$this->load->view('includes/navbar');
			$this->load->view('includes/menu');
			if ($session->force_change_user == "Y") {
				$this->load->view('includes/pwdChange');
			} else {
				$this->load->view('admin/coordinators/home_coordi');
			}
			$this->load->view('includes/footer');
			$this->load->view('admin/coordinators/advertisements_js');
		}
	}

	function confirmationProc()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->post("inpId") && $this->input->post("inpOp")) {
				$filter = array(
					"id_request" => $this->input->post("inpId"),
				);
				$data   = array(
					"status_request" => $this->input->post("inpOp") == "active" ? "Accepted" : "Rejected",
				);
				if ($this->DAO->saveAndEditDats("tb_requests", $data, $filter)) {
					$response = array(
						"status" => "success",
						"message" => "Request was acepted",
					);
				} else {
					$response = array(
						"status" => "error",
						"message" => "There was a error, contact with the admin.",
					);
				}
				echo json_encode($response);
			}
		}
	}

	function confirmation()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get("code") && $this->input->get("option")) {
				$data["id"]      = "confirmationProc";
				$data["message"] = "Are you sure about " .
					$this->input->get("option") == "active" ? "confirm" : "reject" .
					" this request.";
				$data["code"]    = $this->input->get('code');
				$data["option"]  = $this->input->get('option');
				echo $this->load->view("includes/confirmation", $data, TRUE);
			}
		}
	}

	public function changePWD()
	{
		$this->load->model("DAO");
		if ($this->input->post("inpPWD") && $this->input->post("inpConfPWD")) {
			if ($this->input->post("inpPWD") == $this->input->post("inpConfPWD")) {
				$this->form_validation->set_rules("inpPWD", "Password", "required|min_length[6]");
				if ($this->form_validation->run()) {
					$data    = array(
						"password_user" => $this->input->post("inpPWD"),
						"password_tem_user" => $this->input->post("inpPWD"),
						"force_change_user" => "N",
					);
					$session = $this->session->userdata('up_sess');
					$filter  = array(
						"email_user" => $session->email_user,
					);
					$this->DAO->saveAndEditDats("tb_users", $data, $filter);
					$this->session->sess_destroy();
					redirect("");
				} else {
					$this->session->set_flashdata("error_chng", "The Password must be at least 6 characters in length.");
					redirect("");
				}
			} else {
				$this->session->set_flashdata("error_chng", "The passwords does not match.");
				redirect("");
			}
		} else {
			$this->session->set_flashdata("error_chng", "The information is required");
			redirect("");
		}
	}

	function loadClasses()
	{
		if ($this->input->is_ajax_request()) {
			$session = $this->session->userdata('up_sess');
			if ($session->type_user == "Studen") {
				$filter = array(
					"id_group" => $session->group_student,
					"type_period" => "Current",
				);
			} else {
				$filter = array(
					"fk_profesor" => $session->id_user,
					"type_period" => "Current",
				);
			}
			$data["classesStd"] = $this->DAO->getClasses($filter, FALSE);
			echo $this->load->view('students/classes_content', $data, true);
		} else {
			redirect("Home");
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect("");
	}

	private function _check_session()
	{
		$session = $this->session->userdata('up_sess');
		if (@!$session->email_user) {
			redirect('login');
		}
	}
}