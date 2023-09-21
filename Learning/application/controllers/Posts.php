<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Posts extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->_check_session();
		$this->load->model('DAO');
	}

	function showContent()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get('status') == "Adver") {
				$data['posts'] = $this->DAO->getAdvertisements();
				echo $this->load->view('admin/coordinators/posts/posts_page', $data, TRUE);
			} else if ($this->input->get('status') == "Major") {
				$session        = $this->session->userdata('up_sess');
				$filter         = array(
					"cordi_major" => $session->id_user,
					"status_major" => "Active",
				);
				$data['majors'] = $this->DAO->queryEntity("tb_majors", $filter, FALSE);
				$n              = 0;
				foreach ($data['majors'] as $mj) {
					$n += 1;
					$filter = array(
						"major_student" => $mj->id_major,
						"status_user" => "Active",
					);

					$data["num"][$n] = count($this->DAO->StudentsTable($filter, FALSE));
				}
				echo $this->load->view('admin/coordinators/majors/major_content', $data, TRUE);
			} else if ($this->input->get('status') == "Request") {
				$session          = $this->session->userdata('up_sess');
				$filter           = array(
					"id_user_request" => $session->id_user,
				);
				$data["requests"] = $this->DAO->queryEntity("tb_requests", $filter, False);
				echo $this->load->view('admin/request_cordinators', $data, TRUE);
			}
		} else {
			redirect('home');
		}
	}

	function request_form()
	{
		if ($this->input->is_ajax_request()) {
			echo json_encode($this->load->view('admin/request_form'));
		} else {
			redirect('home');
		}
	}

	function request_pro()
	{
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('inpTitle', 'Title', 'required|min_length[5]|max_length[120]');
			$this->form_validation->set_rules('inpRequest', 'Request', 'required');
			if ($this->form_validation->run()) {
				$session = $this->session->userdata('up_sess');
				$data    = array(
					"title_request" => $this->input->post("inpTitle"),
					"request" => $this->input->post("inpRequest"),
					"status_request" => "Pending",
					"id_user_request" => $session->id_user,
				);
				$this->DAO->saveAndEditDats('tb_requests', $data);
				$response = array(
					"status" => "success",
					"message" => "The request was sent.",
				);
			} else {
				$response = array(
					"status" => "error",
					"errors" => $this->form_validation->error_array(),
				);
			}
			echo JSON_encode($response);
		} else {
			redirect('home');
		}
	}

	function showStudentsContent()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get('status') == "Adver") {
				$data['posts'] = $this->DAO->getAdvertisements();
				echo $this->load->view('admin/coordinators/posts/posts_content', $data, TRUE);
			} else if ($this->input->get('status') == "Major") {
				$data['majors'] = $this->DAO->getAdvertisements();
				echo $this->load->view('admin/coordinators/majors/major_content', $data, TRUE);
			}
		} else {
			redirect('home');
		}
	}

	function open_form()
	{
		$session = $this->session->userdata('up_sess');
		if ($this->input->is_ajax_request()) {
			if ($this->input->get("op") == "edit") {
				$data["title"]          = "Edit";
				$data["accion"]         = "edit";
				$filter                 = array(
					"id_advertisement" => $this->input->get("ps"),
				);
				$data["advertisements"] = $this->DAO->getAdvertisements($filter, TRUE);
				$filter                 = array(
					"cordi_major" => $session->id_user,
				);
				$data['majors']         = $this->DAO->queryEntity("tb_majors", $filter, FALSE);
				echo $this->load->view('admin/coordinators/posts/post_form', $data, TRUE);
			} else {
				$data["title"]  = "Create";
				$data['majors'] = $this->DAO->queryEntity("tb_majors", array(), FALSE);
				echo $this->load->view('admin/coordinators/posts/post_form', $data, TRUE);
			}
		}
	}

	function proccessForm()
	{
		$session = $this->session->userdata('up_sess');
		if ($this->input->post('accion')) {
			$this->form_validation->set_rules('codigo_p', 'Clave Post', 'required');
			if ($this->input->post('accion') == 'borrar') {
				$validar_extra = FALSE;
			} elseif ($this->input->post('accion') == 'edit') {
				$validar_extra = TRUE;
			}
		} else {
			$validar_extra = TRUE;
		}

		if ($validar_extra) {
			$this->form_validation->set_rules('inpTitle', 'Title', 'required|min_length[5]|max_length[120]');
			$this->form_validation->set_rules('inpCont', 'Content', 'required');
			$this->form_validation->set_rules('inpFG', 'Focus Group', 'required|min_length[1]');
		}

		if ($this->form_validation->run()) {
			if ($validar_extra) {
				$flujo = [];
				$this->load->library('upload');
				if (isset($_FILES["inpImgFile"]) && $_FILES["inpImgFile"]["name"]) {
					$config['upload_path']   = "./resources/images/";
					$config['allowed_types'] = "jpg|png|jpeg|webp";
					//$config['max_size'] = 4096;
					$config['file_name'] = uniqid();
					$this->upload->initialize($config);
					if ($this->upload->do_upload('inpImgFile')) {
						$image   = base_url() . $config['upload_path'] . $this->upload->data()['file_name'];
						$flujo[] = $config;
					} else {
						$response = array(
							"status" => "error",
							"errors" => "Error in image: " . $this->upload->display_errors()
						);
						echo json_encode($response);
					}
				} else {
					$image = "";
				}
				if (isset($_FILES["inpVidFile"]) && $_FILES["inpVidFile"]["name"]) {
					$confi['upload_path']   = "./resources/videos/";
					$confi['allowed_types'] = "mp4|mkv|mov";
					//$config['max_size'] = 128000;
					$confi['file_name'] = uniqid();
					$this->upload->initialize($confi);
					if ($this->upload->do_upload('inpVidFile')) {
						$video   = base_url() . $confi['upload_path'] . $this->upload->data()['file_name'];
						$flujo[] = $confi;
					} else {
						$response = array(
							"status" => "error",
							"errors" => "Error in video: " . $this->upload->display_errors()
						);
						echo json_encode($response);
						return 0;
					}
				} else {
					$video = "";
				}
				if (isset($_FILES["inpDocFile"]) && $_FILES["inpDocFile"]["name"]) {
					$conf['upload_path']   = "./resources/files/";
					$conf['allowed_types'] = "docx|odt|pdf|csv|xlsx";
					//$config['max_size'] = 25600;
					$conf['file_name'] = uniqid();
					$this->upload->initialize($conf);
					if ($this->upload->do_upload('inpDocFile')) {
						$doc     = base_url() . $conf['upload_path'] . $this->upload->data()['file_name'];
						$flujo[] = $conf;
					} else {
						$response = array(
							"status" => "error",
							"errors" => "Error in document: " . $this->upload->display_errors()
						);
						echo json_encode($response);
						return 0;
					}
				} else {
					$doc = "";
				}
				$data = array(
					"title_advertisement" => $this->input->post('inpTitle'),
					"content_advertisement" => $this->input->post('inpCont'),
					"img_path_advertisement" => $image,
					"vid_path_advertisement" => $video,
					"doc_path_advertisement" => $doc,
					"show_to_advertisement" => $this->input->post('inpFG'),
					"status_advertisement" => "Active",
					"fk_user_advertisement" => $session->id_user,
				);

				$filtro = array();
				if (@$this->input->post('accion')) {
					$filtro = array(
						"post_id" => $this->input->post('codigo_p'),
					);
				}
				$response = $this->DAO->saveAndEditDats('tb_advertisements', $data, $filtro);

				if ($response["status"] == "success") {
					$response = array(
						"status" => "success",
						"message" => 'Advertisement was register.',
					);
				} else {
					$response = array(
						"status" => "Incorrecto",
						"errors" => $this->form_validation->error_array(),
						"test" => "" //$test
					);
				}
			} else {
				$data = array(
					"post_status" => "Inactive",
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

	function confirmation()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get("ps") && $this->input->get("op")) {
				if ($this->input->get("itm") == "post") {
					if ($this->input->get("op") == "delete") {
						$data = array(
							"status_advertisement" => "Inactive",
						);
					} else if ($this->input->get("op") == "active") {
						$data = array(
							"status_advertisement" => "Active",
						);
					}
					$filter = array(
						"id_advertisement" => $this->input->get("ps"),
					);
					$table  = "tb_advertisements";
				} else if ($this->input->get("itm") == "comment") {
					if ($this->input->get("op") == "delete") {
						$data = array(
							"status_comment" => "Inactive",
						);
					} else if ($this->input->get("op") == "active") {
						$data = array(
							"status_comment" => "Active",
						);
					}
					$filter = array(
						"id_comment" => $this->input->get("ps"),
					);
					$table  = "tb_comments";
				}
				$response = $this->DAO->saveAndEditDats($table, $data, $filter);
				echo JSON_encode($response);
			} else {
				redirect('');
			}
		} else {
			redirect('');
		}
	}

	function openCommentForm()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get('ps') && $this->input->get('us')) {
				$filtro        = array(
					'id_advertisement' => $this->input->get('ps'),
					'fk_user_advertisement' => $this->input->get('us'),
				);
				$advertisement = $this->DAO->queryEntity('tb_advertisements', $filtro, TRUE);

				if ($advertisement) {
					$data['accion']        = $this->input->get('accion');
					$data['advertisement'] = $advertisement;
					echo $this->load->view('admin/coordinators/posts/comments/comments_page', $data, TRUE);
				} else {
					echo 'error';
				}
			} else {
				echo 'error';
			}
		} else {
			redirect('home');
		}
	}
	function showComments()
	{
		if ($this->input->is_ajax_request()) {
			$data['validation'] = $this->input->get('ps');
			$filter             = array(
				"fk_ad" => $this->input->get('ps'),
			);
			$data['comments']   = $this->DAO->commentPosts($filter);
			echo $this->load->view('admin/coordinators/posts/comments/comments_coment', $data, TRUE);
		} else {
			redirect('home');
		}
	}

	function editComent()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->get('ps') && $this->input->get('op')) {
				$filter             = array(
					"id_comment" => $this->input->get("ps"),
				);
				$comment["comment"] = $this->DAO->queryEntity('tb_comments', $filter, TRUE);
				echo json_encode($this->load->view('admin/coordinators/posts/comments/comments_edit', $comment, TRUE));
			}
		} else {
			redirect('home');
		}
	}

	function proccEditComent()
	{
		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('inpCom', 'Comment id', 'required');
			$this->form_validation->set_rules('inpCont', 'Comment content', 'required');
			if ($this->form_validation->run()) {
				$data     = array(
					"content_comment" => $this->input->post("inpCont"),
					"update_date_comment" => Null,
				);
				$filter   = array(
					"id_comment" => $this->input->post("inpCom"),
				);
				$response = $this->DAO->saveAndEditDats("tb_comments", $data, $filter);
			} else {
				$response = array(
					"status" => "error",
					"errors" => $this->form_validation->error_array(),
				);
			}
			echo JSON_encode($response);
		} else {
			redirect('home');
		}
	}

	function proccessFormComments()
	{
		$session = $this->session->userdata('up_sess');
		if ($this->input->post('eComm')) {
			$data = array(
				"content_comment" => $this->input->post('eComm'),
				"fk_user_comment" => $session->id_user,
			);

			$this->DAO->trans_begin();
			$response = $this->DAO->saveAndEditDats('tb_comments', $data, );
			$id       = $this->DAO->obtain_id();
			$data     = array(
				"fk_ad" => $this->input->post('ePos'),
				"fk_comment" => $id,
			);
			$response = $this->DAO->saveAndEditDats('tb_advertisements_comments', $data, );
			$complete = $this->DAO->trans_end();
			if ($complete) {
				$response = array(
					"status" => "success",
					"message" => "Register successfuly",
				);
			}
			echo json_encode($response);
		} else {
			$response = array(
				"status" => "error",
				"errors" => $this->form_validation->error_array(),
			);
		}
	}

	// function usuario_foto()
	// {
	// 	$this->form_validation->set_rules('usuario_id', 'Usuario', 'required');
	// 	if ($this->form_validation->run() == FALSE) {
	// 		$response = array(
	// 			"status" => "error",
	// 			"message" => "Validaciones fallidas",
	// 			"validations" => $this->form_validation->error_array()
	// 		);
	// 	} else {
	// 		$config['upload_path'] = "./files/";
	// 		$config['allowed_types'] = "jpg|npg|jpeg|webp";
	// 		$config['max_size'] = 2048;
	// 		$config['file_name'] = uniqid();
	// 		$this->load->library('upload', $config);
	// 		if ($this->upload->do_upload('fichero')) {
	// 			$data = array(
	// 				"usuario_foto" => $this->upload->data()['file_name'],
	// 			);
	// 			$response = $this->DAO->registrarFoto($data, $this->input->post('usuario_id'));
	// 		} else {
	// 			$response =  array(
	// 				"status" => "error",
	// 				"message" => "Error al subir el archivo debido a: " . $this->upload->display_errors()
	// 			);
	// 		}
	// 	}
	// 	echo "";
	// }

	private function _check_session()
	{
		$session = $this->session->userdata('up_sess');
		if (@!$session->email_user) {
			redirect('login');
		}
	}
}