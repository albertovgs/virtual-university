<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Students extends CI_Controller
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
		$this->load->view('admin/students/students_page');
		$this->load->view('includes/footer');
		$this->load->view('admin/students/students_js');
	}

	function showTable()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get('status')) {
				$session = $this->session->userdata('up_sess');
				if ($session->type_user == "Admin") {
					$filter = array(
						"type_user" => "Studen",
						"status_user" => $this->input->get('status'),
					);
				} else if ($session->type_user == "Cordi" && $this->input->get('id')) {
					$filter = array(
						"type_user" => "Studen",
						"status_user" => $this->input->get('status'),
						"cordi_major" => $session->id_user,
						"id_major" => $this->input->get('id'),
					);
				}
			}
			$data["students"] = $this->DAO->StudentsTable($filter, FALSE);
			echo $this->load->view('admin/students/students_table', $data, TRUE);
		}
	}
	function resetPass()
	{
		if ($this->input->is_ajax_request()) {
			$data["id"]      = "reset_password";
			$data["message"] = "To reset the Password Confirm this modal.";
			$data["code"]    = $this->input->get('code');
			echo $this->load->view('includes/confirmation', $data, TRUE);
		}
	}

	function reset_password()
	{
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules("inpId", "ID", "required");
			if ($this->form_validation->run()) {
				$filter   = array(
					"id_user" => $this->input->post('inpId'),
				);
				$password = $this->generatePassword(6);
				$data     = array(
					"force_change_user" => "Y",
					"password_tem_user" => $password,
					"password_user" => $password,
				);
				if ($this->DAO->saveAndEditDats("tb_users", $data, $filter)) {
					$response = array(
						"status" => "success",
						"message" => "Reset the password complete, password: " . $password
					);
				} else {
					$response = array(
						"status" => "error",
						"message" => "There was a error, contact with the admin.",
					);
				}
			} else {
				$response = array(
					"status" => "error",
					"errors" => $this->form_validation->error_array(),
				);
			}
		}
		echo JSON_encode($response);
	}

	function processForm()
	{
		if ($this->input->is_ajax_request()) {
			$session    = $this->session->userdata('up_sess');
			$data["id"] = $this->input->get('major');
			if ($session->type_user == "Admin") {
				$data['majors'] = $this->DAO->queryEntity("tb_majors", array("status_major" => "Active"), FALSE);
			} else {
				$filter         = array(
					"id_major" => $this->input->get('major'),
					"status_major" => "Active",
				);
				$data['majors'] = $this->DAO->queryEntity("tb_majors", $filter, FALSE);
			}

			$filter         = array(
				"major_group" => $this->input->get('major'),
			);
			$data["groups"] = $this->DAO->queryEntity("tb_groups", $filter, FALSE);
			if ($this->input->get('option')) {
				$data['option']        = $this->input->get('option');
				$filter                = array(
					"id_user" => $this->input->get('code'),
				);
				$data["studentFinded"] = $this->DAO->StudentsTable($filter, TRUE);
				switch ($this->input->get('option')) {
					case 'edit':
						$filter = array(
							"id_group" => $data["studentFinded"]->group_student,
						);
						$data["group"] = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
						$filter = array(
							"major_group" => $data["studentFinded"]->id_major,
						);
						$data["groups"] = $this->DAO->queryEntity("tb_groups", $filter, FALSE);
						break;
					case 'inactivate':
						$filter = array(
							"id_group" => $data["studentFinded"]->group_student,
						);
						$data["group"] = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
						$filter = array(
							"id_student" => $this->input->get('code'),
						);
						$major = $this->DAO->queryEntity("tb_students", $filter, TRUE);
						$filter = array(
							"id_major" => $major->major_student,
						);
						$data["majorStd"] = $this->DAO->queryEntity("tb_majors", $filter, TRUE);
						break;
					case 'reactive':
						$data['code'] = $this->input->get('code');
						$filter = array(
							"major_group" => $data["studentFinded"]->id_major,
						);
						$data["groups"] = $this->DAO->queryEntity("tb_groups", $filter, FALSE);
						break;
				}
			}
			echo $this->load->view('admin/students/students_register_form', $data, TRUE);
		} else {
			return array(
				"status" => "error",
				"message" => "There was an error",
			);
		}
	}

	function proces_students_form()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->post("code") && $this->input->post("option") != "edit") {
				$this->form_validation->set_rules("code", "Code", "required");
				if ($this->form_validation->run()) {
					$this->DAO->trans_begin();
					$data   = array(
						"status_user" => $this->input->post("option") == "inactivate" ? 'Inactive' : 'Active',
						"update_date_user" => "default",
					);
					$filter = array(
						"id_user" => $this->input->post("code"),
					);
					$this->DAO->saveAndEditDats("tb_users", $data, $filter);
					$filter = array("id_group" => $this->input->post('inpGroup'));
					$nmb    = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
					if ($this->input->post("option") == "inactivate") {
						$data   = array(
							"nmb_students" => $nmb->nmb_students - 1
						);
						$filter = array("id_group" => $this->input->post('inpGroup'));
						$this->DAO->saveAndEditDats("tb_groups", $data, $filter);
					} else {
						$filter = array(
							"id_student" => $this->input->post("code"),
						);
						$data   = array(
							"group_student" => $this->input->post("inpGroup"),
						);
						$this->DAO->saveAndEditDats("tb_students", $data, $filter);
						$data   = array(
							"nmb_students" => $nmb->nmb_students + 1
						);
						$filter = array("id_group" => $this->input->post('inpGroup'));
						$errors = $this->DAO->saveAndEditDats("tb_groups", $data, $filter);
					}

					if ($this->DAO->trans_end()) {
						$response = array(
							"status" => "success",
							"message" => "Action complete successful",
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
						"errors" => $this->form_validation->error_array(),
					);
				}
			} else {
				$this->form_validation->set_rules("inpName", "Name", "required|min_length[5]|max_length[60]");
				$this->form_validation->set_rules("inpLastname", "Last Name", "required|min_length[5]|max_length[60]");
				$this->form_validation->set_rules("inpBirthday", "Birthday", "required|date");
				$this->form_validation->set_rules("inpGender", "Gender", "required");
				$this->form_validation->set_rules("inpID", "ID", "required|min_length[8]|max_length[12]");
				$this->form_validation->set_rules("inpMajor", "Major", "required");
				$this->form_validation->set_rules("inpGroup", "Group", "required");
				if ($this->form_validation->run()) {
					if ($this->input->post("option") != "edit") {
						$exist = $this->DAO->queryEntity(
							"tb_users",
							$filter = array(
								"IDUser" => $this->input->post("inpID"),
							)
						);
						if ($exist) {
							$response = array(
								"status" => "error",
								"message" => "The ID is already register.",
							);
						} else {
							$data = array(
								"name_person" => $this->input->post('inpName'),
								"lastname_person" => $this->input->post('inpLastname'),
								"birthday_person" => $this->input->post('inpBirthday'),
								"gender_person" => $this->input->post('inpGender'),
								"creation_date_person" => "default",
								"update_date_person" => "default",
							);
							$this->DAO->trans_begin();
							$this->DAO->saveAndEditDats("tb_people", $data, NULL);
							$user_id  = $this->DAO->obtain_id();
							$password = $this->generatePassword(6);
							$user_img = $this->input->post('inpGender') == "M" ? "user_boy_one.webp" : "user_girl_one.webp";
							$data     = array(
								"id_user" => $user_id,
								"IDUser" => $this->input->post('inpID'),
								"email_user" => $this->input->post('inpID') . "@learning.edu",
								"img_user" => $user_img,
								"password_tem_user" => $password,
								"password_user" => $password,
								"type_user" => "Studen",
							);
							$this->DAO->saveAndEditDats("tb_users", $data, NULL);
							$data = array(
								"id_student" => $user_id,
								"major_student" => $this->input->post('inpMajor'),
								"group_student" => $this->input->post('inpGroup'),
							);
							$this->DAO->saveAndEditDats("tb_students", $data, NULL);
							$filter = array("id_group" => $this->input->post('inpGroup'));
							$nmb    = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
							$data   = array(
								"nmb_students" => $nmb->nmb_students + 1
							);
							$this->DAO->saveAndEditDats("tb_groups", $data, $filter);
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
						}
					} else {
						$this->DAO->trans_begin();
						$data   = array(
							"name_person" => $this->input->post('inpName'),
							"lastname_person" => $this->input->post('inpLastname'),
							"birthday_person" => $this->input->post('inpBirthday'),
							"gender_person" => $this->input->post('inpGender'),
							"update_date_person" => "default",
						);
						$filter = array(
							"id_person" => $this->input->post('code'),
						);
						$this->DAO->saveAndEditDats("tb_people", $data, $filter);
						$filter                = array(
							"id_student" => $this->input->post('code'),
						);
						$data["studentFinded"] = $this->DAO->StudentsTable($filter, TRUE);
						if ($data["studentFinded"]->group_student != $this->input->post('inpGroup')) {
							$filter = array("id_group" => $data["studentFinded"]->group_student);
							$nmb    = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
							$data   = array(
								"nmb_students" => $nmb->nmb_students - 1
							);
							$test   = $this->DAO->saveAndEditDats("tb_groups", $data, $filter);
							$filter = array(
								"id_student" => $this->input->post('code'),
							);
							$data   = array("group_student" => $this->input->post('inpGroup'));
							$this->DAO->saveAndEditDats("tb_students", $data, $filter);

							$filter = array("id_group" => $this->input->post('inpGroup'));
							$nmb    = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
							$data   = array(
								"nmb_students" => $nmb->nmb_students + 1
							);
							$test   = $this->DAO->saveAndEditDats("tb_groups", $data, $filter);
						}
						if ($this->DAO->trans_end()) {
							$response = array(
								"status" => "success",
								"message" => "Register successful",
							);
						} else {
							$response = array(
								"status" => "error",
								"errors" => "There was an error, contac the admin.",
							);
						}
					}
				} else {
					$response = array(
						"status" => "error",
						"errors" => $this->form_validation->error_array(),
					);
				}
			}
			echo JSON_encode($response);
		} else {
			redirect('home');
		}
	}

	function optionsProccess()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->post("option") == "inactivate") {
				$extValidation = FALSE;
			} else {
				$extValidation = TRUE;
			}
			$this->form_validation->set_rules("code", "Code", "required");

			if ($this->form_validation->run()) {

				$data     = array(
					"status_user" => $extValidation ? 'Active' : 'Inactive',
					"update_date_user" => "default",
				);
				$filter   = array(
					"id_user" => $this->input->post("code"),
				);
				$test     = $this->DAO->saveAndEditDats("tb_users", $data, $filter);
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
		} else {
			return ('home');
		}
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