<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
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
            $this->load->view('profile');
            $this->load->view('includes/footer2');
        } else if ($session->type_user == "Admin") {
            $this->load->view('includes/header');
            $this->load->view('includes/navbar');
            $this->load->view('includes/menu');
            $this->load->view('profile');
            $this->load->view('includes/footer');
        } else {
            $this->load->view('includes/header');
            $this->load->view('includes/navbar');
            $this->load->view('includes/menu');
            $this->load->view('profile');
            $this->load->view('includes/footer');
        }
    }

    function confirmationProc()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post("inpId") && $this->input->post("inpOp")) {
                $filter = array(
                    "id_request" => $this->input->post("inpId"),
                );
                if ($this->input->post("inpOp") == "active") {
                    $data = array(
                        "status_request" => "Accepted",
                    );
                } elseif ($this->input->post("inpOp") == "delete") {
                    $data = array(
                        "status_request" => "Rejected",
                    );
                } else {
                    redirect('');
                }
                $success = $this->DAO->saveAndEditDats("tb_requests", $data, $filter);
                if ($success) {
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
                return json_encode($response);
            }
        }
    }

    function confirmation()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get("code") && $this->input->get("option")) {
                if ($this->input->get("option") == "active") {
                    $data["id"]      = "confirmationProc";
                    $data["message"] = "Are you sure about confirm this request.";
                    $data["code"]    = $this->input->get('code');
                    $data["option"]  = $this->input->get('option');
                } elseif ($this->input->get("option") == "delete") {
                    $data["id"]      = "confirmationProc";
                    $data["message"] = "Are you sure about reject this request.";
                    $data["option"]  = $this->input->get('option');
                    $data["code"]    = $this->input->get('code');
                    $data["option"]  = $this->input->get('option');
                } else {
                    redirect('');
                }
                echo $this->load->view("includes/confirmation", $data, TRUE);
            } else {
                $this;
            }
        } else {
            redirect('');
        }
    }

    private function _check_session()
    {
        $session = $this->session->userdata('up_sess');
        if (@!$session->email_user) {
            redirect('login');
        }
    }
}