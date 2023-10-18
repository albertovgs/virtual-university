<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Majors extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->_check_session();
		$this->load->model('DAO');
	}

	public function index()
	{
		$this->load->view('includes/header');
		$this->load->view('includes/navbar');
		$this->load->view('includes/menu');
		$this->load->view('admin/majors/majors_page');
		$this->load->view('includes/footer');
		$this->load->view('admin/majors/majors_js');
	}

	function showMajors()
	{
		if ($this->input->is_ajax_request()) {
			$filter         = array(
				"status_major" => $this->input->get('status'),
			);
			$data["status"] = $this->input->get('status');
			$data["majors"] = $this->DAO->getMajors($filter, False);
			$n              = 0;
			foreach ($data['majors'] as $mj) {
				$n += 1;
				$filter          = array(
					"major_student" => $mj->id_major,
					"status_user" => "Active",
				);
				$data["num"][$n] = count($this->DAO->StudentsTable($filter, FALSE));
			}
			echo $this->load->view('admin/majors/majors', $data, TRUE);
		}
	}

	function register_form()
	{
		if ($this->input->is_ajax_request()) {
			$filter         = array(
				"type_user" => "Cordi",
				"status_user" => "Active",
			);
			$data['cordis'] = $this->DAO->getCordination($filter, FALSE);
			if ($this->input->get('option') && $this->input->get('code')) {
				$filter            = array(
					"id_major" => $this->input->get('code'),
				);
				$data['form']      = $this->input->get('option');
				$data['majorFind'] = $this->DAO->getMajors($filter, TRUE);
			}
			echo $this->load->view('admin/majors/major_format', $data, True);
		} else {
			redirect('home');
		}
	}

	function register_major()
	{
		if (!$this->input->is_ajax_request()) {
			redirect('');
		}
		if ($this->input->post("inpOp")) {
			if ($this->input->post("inpOp") == "edit") {
				$this->update_major(TRUE);
			} elseif ($this->input->post("inpOp") == "delete") {
				$status = "Inactive";
				$this->delete_major($status);
			} elseif ($this->input->post("inpOp") == "active") {
				$status = "Active";
				$this->delete_major($status);
			}
		} else {
			$this->update_major(FALSE);
		}
	}

	function delete_major($status)
	{
		$this->form_validation->set_rules("inpCordi", "Cordination", "required|max_length[1]");
		$this->form_validation->set_rules("inpId", "Major", "required");
		if ($this->form_validation->run()) {
			$data   = array(
				"status_major" => $status,
			);
			$filter = array(
				"id_user" => $this->input->post("inpCordi"),
				"type_user" => "Cordi",
			);
			if (!$this->DAO->queryEntity("tb_users", $filter, TRUE)) {
				echo JSON_encode(
					array(
						"status" => "error",
						"message" => "Cordination is not valid.",
					)
				);
				return;
			}
			$filter = array(
				"id_major" => $this->input->post("inpId"),
			);
			$query  = $this->DAO->saveAndEditDats("tb_majors", $data, $filter);
			if ($query["status"] == "success") {
				$response = array(
					"status" => "success",
					"message" => "Data was charged successfuly.",
				);
			} else {
				$response = array(
					"status" => "error",
					"message" => $query->message,
				);
			}
		} else {
			$response = array(
				"status" => "errores",
				"errors" => $this->form_validation->error_array(),
			);
		}
		echo JSON_encode($response);
	}
	function update_major($opt)
	{
		$this->form_validation->set_rules("inpName", "Name", "required|min_length[5]|max_length[60]");
		$this->form_validation->set_rules("inpClave", "Clave Name", "required|min_length[3]|max_length[8]");
		$this->form_validation->set_rules("inpCordi", "Cordination", "required|max_length[1]");
		$this->form_validation->set_rules("inpDesc", "Description", "required");
		if ($this->form_validation->run()) {
			$filter = array(
				"id_user" => $this->input->post("inpCordi"),
				"type_user" => "Cordi",
				"status_user" => "Active",
			);
			if ($this->DAO->queryEntity("tb_users", $filter, TRUE)) {
				$data = array(
					"name_major" => $this->input->post("inpName"),
					"desc_major" => $this->input->post("inpDesc"),
					"clave_major" => $this->input->post("inpClave"),
					"cordi_major" => $this->input->post("inpCordi"),
				);
				if ($opt) {
					$filter = array(
						"id_major" => $this->input->post("inpId"),
					);
					$query  = $this->DAO->saveAndEditDats("tb_majors", $data, $filter);
				} else {
					$query = $this->DAO->saveAndEditDats("tb_majors", $data);
				}
				if ($query["status"] == "success") {
					$response = array(
						"status" => "success",
						"message" => "Data was charged successfuly.",
					);
				} else {
					$response = array(
						"status" => "error",
						"message" => $query->message,
					);
				}
			} else {
				$response = array(
					"status" => "error",
					"message" => "Cordination must be valid.",
				);
			}
		} else {
			$response = array(
				"status" => "errores",
				"errors" => $this->form_validation->error_array(),
			);
		}
		echo JSON_encode($response);
	}

	private function _check_session()
	{
		$session = $this->session->userdata('up_sess');
		if (!@$session->email_user || $session->type_user != "Admin") {
			redirect('login');
		}
	}
}