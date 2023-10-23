<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Profesors extends CI_Controller
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
		$this->load->view('admin/profesors/profesors_page');
		$this->load->view('includes/footer');
		$this->load->view('admin/profesors/profesors_js');
	}

	function showTable()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get('status')) {
				$filter = array(
					"type_user" => "Teacher",
					"status_user" => $this->input->get('status'),
				);
			}
			$data["profesors"] = $this->DAO->profesorsTable($filter, FALSE);
			echo $this->load->view('admin/profesors/profesors_table', $data, TRUE);
		}
	}

	function processForm()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get('option')) {
				$filter = array(
					"id_person" => $this->input->get('code'),
				);
				if ($this->input->get('option') == "reactive") {
					$data['code'] = $this->input->get('code');
				}
				$data['option']         = $this->input->get('option');
				$data["profesorFinded"] = $this->DAO->profesorsTable($filter, TRUE);
				echo $this->load->view('admin/profesors/profesors_registration_form', $data, TRUE);
			} else {
				echo $this->load->view('admin/profesors/profesors_registration_form', null, TRUE);
			}
		} else {
			return array(
				"status" => "error",
				"message" => "There was an error",
			);
		}
	}

	function proces_profesors_form()
	{
		if (!$this->input->is_ajax_request()) {
			redirect('home');
		}
		$this->input->post("option") && $this->input->post("code") ? $extValidation = False : $extValidation = True;

		$this->form_validation->set_rules("inpName", "Name", "required|min_length[3]|max_length[60]");
		$this->form_validation->set_rules("inpLastname", "Last Name", "required|min_length[5]|max_length[60]");
		$this->form_validation->set_rules("inpBirthday", "Birthday", "required|date");
		$this->form_validation->set_rules("inpGender", "Gender", "required");
		$this->form_validation->set_rules("inpID", "ID", "required|min_length[8]|max_length[12]");
		if ($this->form_validation->run()) {
			if ($extValidation) {
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
					$password = $this->generatePassword(6);
					$user_img = "/resources/dist/img/" . $this->input->post('inpGender') == "M" ? "user_boy_one.webp" : "user_girl_one.webp";
					$data     = array(
						"id_user" => $this->DAO->obtain_id(),
						"IDUser" => $this->input->post('inpID'),
						"email_user" => $email,
						"img_user" => $user_img,
						"password_tem_user" => $password,
						"password_user" => $password,
						"type_user" => "Teacher",
					);
					$this->DAO->saveAndEditDats("tb_users", $data, NULL);
					if ($this->DAO->trans_end()) {
						$response = array(
							"status" => "success",
							"message" => "Register successful, password: " . $password
						);
					} else {
						$response = array(
							"status" => "error",
							"errors" => "Contact to the admin.",
						);
					}
				} else {
					$response = array(
						"status" => "error",
						"message" => "User already exist.",
					);
				}
			} else {
				switch ($this->input->post("option")) {
					case 'edit':
						$data = array(
							"name_person" => $this->input->post('inpName'),
							"lastname_person" => $this->input->post('inpLastname'),
							"birthday_person" => $this->input->post('inpBirthday'),
							"gender_person" => $this->input->post('inpGender'),
							"update_date_person" => "default",
						);
						$filter = array(
							"id_person" => $this->input->post('code'),
						);
						$complete = $this->DAO->saveAndEditDats("tb_people", $data, $filter);
						break;
					case 'inactivate':
						$data = array(
							"status_user" => "Inactive",
						);
						$filter = array(
							"id_user" => $this->input->post('code'),
						);
						$complete = $this->DAO->saveAndEditDats("tb_users", $data, $filter);
						break;
					case 'reactive':
						$data = array(
							"status_user" => "Active",
						);
						$filter = array(
							"id_user" => $this->input->post('code'),
						);
						$complete = $this->DAO->saveAndEditDats("tb_users", $data, $filter);
						break;
				}
				if ($complete) {
					$response = array(
						"status" => "success",
						"message" => "Action completed successfuly",
					);
				} else {
					$response = array(
						"status" => "error",
						"message" => "There is a problem.",
					);
				}
			}
		} else {
			$response = array(
				"status" => "error",
				"errors" => $this->form_validation->error_array(),
			);
		}
		echo JSON_encode($response);
	}

	function optionsProccess()
	{
		if (!$this->input->is_ajax_request()) {
			return ('home');
		}
		$this->input->post("option") == "inactivate" ? $extValidation = FALSE : $extValidation = TRUE;
		$this->form_validation->set_rules("code", "Code", "required");

		if ($this->form_validation->run()) {

			$data   = array(
				"status_user" => $extValidation ? 'Active' : 'Inactive',
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
					"errors" => $test,
				);
			}
		} else {
			$response = array(
				"status" => "error",
				"errors" => $this->form_validation->error_array(),
			);
		}
		echo JSON_encode($response);
	}

	function email()
	{
		$email = '';
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
		$temp  = $email . "@learning.edu";
		$temp  = strtolower($temp);
		$exist = $this->DAO->queryEntity("tb_users", $filter = array("email_user" => $temp), TRUE);
		if ($exist) {
			$email = '';
			$email = $arr1[0][0] . $arr1[0][1] . $arr1[0][2];
			if (sizeof($arr1) > 1) {
				$email = $email . $arr1[1][0];
			}
			$email = $email . $arr2;
			$email = $email . "@learning.edu";
		} else {
			$email = $email . "@learning.edu";
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
		if (!@$session->email_user || $session->type_user != "Admin" && $session->type_user != "Cordi") {
			redirect('login');
		}
	}
}