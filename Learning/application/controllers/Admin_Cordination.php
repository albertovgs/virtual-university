<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Cordination extends CI_Controller
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
		$this->load->view('admin/coordinators/coordinators_page');
		$this->load->view('includes/footer');
		$this->load->view('admin/coordinators/coordinators_js');
	}

	function showTable()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get('status')) {
				$filter = array(
					"type_user" => "Cordi",
					"status_user" => $this->input->get('status'),
				);
			}
			$data["coordinator"] = $this->DAO->profesorsTable($filter, FALSE);
			echo $this->load->view('admin/coordinators/coordinators_table', $data, TRUE);
		}
	}

	function processForm()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get('option')) {
				switch ($this->input->get('option')) {
					case 'edit':
						$filter = array(
							"id_person" => $this->input->get('code'),
						);
						$data['option'] = $this->input->get('option');
						$data["profesorFinded"] = $this->DAO->profesorsTable($filter, TRUE);
						echo $this->load->view('admin/coordinators/coordinators_registration_form', $data, TRUE);
						break;
					case 'reactive':
						$filter = array(
							"id_person" => $this->input->get('code'),
						);
						$data['code'] = $this->input->get('code');
						$data['option'] = $this->input->get('option');
						$data["profesorFinded"] = $this->DAO->profesorsTable($filter, TRUE);
						echo $this->load->view('admin/coordinators/coordinators_registration_form', $data, TRUE);
						break;
					case 'inactivate':
						$filter = array(
							"id_person" => $this->input->get('code'),
						);
						$data['option'] = $this->input->get('option');
						$data["profesorFinded"] = $this->DAO->profesorsTable($filter, TRUE);
						echo $this->load->view('admin/coordinators/coordinators_registration_form', $data, TRUE);
						break;
					default:
						$filter = array(
							"id_person" => $this->input->get('code'),
						);
						$data['option'] = $this->input->get('option');
						$data["profesorFinded"] = $this->DAO->profesorsDetails($filter, TRUE);
						echo $this->load->view('admin/coordinators/coordinators_registration_form', $data, TRUE);
						break;
				}
			} else {
				echo $this->load->view('admin/coordinators/coordinators_registration_form', null, TRUE);
			}
		} else {
			return array(
				"status" => "error",
				"message" => "There was an error",
			);
		}
	}

	function proces_coordinator_form()
	{
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules("inpName", "Name", "required|min_length[3]|max_length[60]");
			$this->form_validation->set_rules("inpLastname", "Last Name", "required|min_length[5]|max_length[60]");
			$this->form_validation->set_rules("inpBirthday", "Birthday", "required|date");
			$this->form_validation->set_rules("inpGender", "Gender", "required");
			$this->form_validation->set_rules("inpID", "ID", "required|min_length[8]|max_length[12]");
			if (!$this->form_validation->run()) {
				$response = array(
					"status" => "error",
					"errors" => $this->form_validation->error_array(),
				);
			}
			if ($this->input->post("option") && $this->input->post("code")) {
				$exist = $this->DAO->queryEntity("tb_users", $filter = array("IDUser" => $this->input->post('inpID')), TRUE);
				if (!$exist) {
					$data  = array(
						"name_person" => $this->input->post('inpName'),
						"lastname_person" => $this->input->post('inpLastname'),
						"birthday_person" => $this->input->post('inpBirthday'),
						"gender_person" => $this->input->post('inpGender'),
						"creation_date_person" => "default",
						"update_date_person" => "default",
					);
					$email = $this->email();
					$this->DAO->trans_begin();
					$this->DAO->saveAndEditDats("tb_people", $data, NULL);
					$user_id  = $this->DAO->obtain_id();
					$password = $this->generatePassword(6);
					if ($this->input->post('inpGender') == "M") {
						$user_img = "/resources/dist/img/user_boy_one.webp";
					} else {
						$user_img = "/resources/dist/img/user_girl_one.webp";
					}
					$data = array(
						"id_user" => $user_id,
						"IDUser" => $this->input->post('inpID'),
						"email_user" => $email,
						"img_user" => $user_img,
						"password_tem_user" => $password,
						"password_user" => $password,
						"type_user" => "Cordi",
					);
					$this->DAO->saveAndEditDats("tb_users", $data, NULL);
					$complete = $this->DAO->trans_end();
					if ($complete) {
						$response = array(
							"status" => "success",
							"message" => "Register successful, this is the temporary password-> " . $password
						);
					} else {
						$response = array(
							"status" => "error",
							"errors" => "Something went wrong.",
						);
					}
				} else {
					$response = array(
						"status" => "error",
						"message" => "User already exist.",
					);
				}
			} else if ($this->input->post("option") && $this->input->post("option") == "edit") {
				$data     = array(
					"name_person" => $this->input->post('inpName'),
					"lastname_person" => $this->input->post('inpLastname'),
					"birthday_person" => $this->input->post('inpBirthday'),
					"gender_person" => $this->input->post('inpGender'),
					"update_date_person" => "default",
				);
				$filter   = array(
					"id_person" => $this->input->post('code'),
				);
				$complete = $this->DAO->saveAndEditDats("tb_people", $data, $filter);
				$data     = array(
					"IDUser" => $this->input->post('inpID'),
				);
				$filter   = array(
					"id_user" => $this->input->post('code'),
				);
				$complete = $this->DAO->saveAndEditDats("tb_users", $data, $filter);
				if ($complete) {
					$response = array(
						"status" => "success",
						"message" => "Register successful",
					);
				} else {
					$response = array(
						"status" => "error",
						"errors" => $this->form_validation->error_array(),
					);
				}
			} else {
				$response = $this->optionsProccess();
			}
			echo JSON_encode($response);
		} else {
			redirect('home');
		}
	}

	function optionsProccess()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->post("code")) {
				$this->form_validation->set_rules("code", "Code", "required");

				if ($this->form_validation->run()) {

					$data   = array(
						"status_user" => $this->input->post("option") == "inactivate" ? 'Inactive' : 'Active',
						"update_date_user" => "default",
					);
					$filter = array(
						"id_user" => $this->input->post("code"),
					);
					$this->DAO->saveAndEditDats("tb_users", $data, $filter);
					$complete = $this->DAO->trans_end();
					if ($complete) {
						$response = array(
							"status" => "success",
							"message" => "Action complete successful",
						);
					} else {
						$response = array(
							"status" => "error",
							"errors" => "Something went wrong.",
						);
					}
				} else {
					$response = array(
						"status" => "error",
						"errors" => $this->form_validation->error_array(),
					);
				}
				return $response;
			}
		} else {
			return ('home');
		}
	}

	function email()
	{
		$email = '';
		$at    = "@learning.edu";

		$array = explode(' ', $this->input->post('inpName'));
		$i     = 0;
		foreach ($array as $palabra) {
			$arr1[$i] = str_split($palabra);
			$i        = $i + 1;
		}
		$email = $arr1[0][0];
		if (sizeof($arr1) > 1) {
			$email = $email . $arr1[1][0];
		}
		$array1 = explode(' ', $this->input->post('inpLastname'));
		foreach ($array1 as $palabra) {
			$arr2 = $palabra;
			break;
		}
		$email = $email . $arr2;
		$temp  = $email . $at;
		$temp  = strtolower($temp);
		$exist = $this->DAO->queryEntity("tb_users", $filter = array("email_user" => $temp), TRUE);
		if ($exist) {
			$email = '';
			$email = $arr1[0][0] . $arr1[0][1] . $arr1[0][2];
			if (sizeof($arr1) > 1) {
				$email = $email . $arr1[1][0];
			}
			$email = $email . $arr2;
			$test  = $email . $at;
			$exist = $this->DAO->queryEntity("tb_users", array("email_user" => $test), TRUE);
			if ($exist) {
				$date = explode('-', $this->input->post('inpBirthday'));
				foreach ($date as $d) {
					$dt = $d;
					break;
				}
				$test = $email . $dt . $at;
			} else {
				$email = $email . $at;
			}
		} else {
			$email = $email . $at;
		}
		$email = strtolower($email);
		return $email;
	}

	private function generatePassword($length)
	{
		$key     = "";
		$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
		$max     = strlen($pattern) - 1;
		for ($i = 0; $i < $length; $i++) {
			$key .= substr($pattern, mt_rand(0, $max), 1);
		}
		return $key;
	}

	private function _check_session()
	{
		$session = $this->session->userdata('up_sess');
		if (!@$session->email_user || $session->type_user != "Admin") {
			redirect('login');
		}
	}
}