<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Periods extends CI_Controller
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
        $this->load->view('admin/periods_page');
        $this->load->view('includes/footer');
        $this->load->view('admin/periods_js');
    }

    public function openPrdForm()
    {
        if ($this->input->is_ajax_request()) {
            $data["id"]    = $this->input->get('id');
            $data['major'] = $this->DAO->queryEntity("tb_majors", null, TRUE);
            echo $this->load->view('admin/coordinators/majors/periods_form', $data, TRUE);
        }
    }

    function showPeriods()
    {
        if ($this->input->is_ajax_request()) {
            $data["periods"] = $this->DAO->getPeriods(null, FALSE, TRUE);
            echo $this->load->view('admin/coordinators/majors/periods_table.php', $data, TRUE);
        }
    }

    function proccessPeriodsForm()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('inpDDStart', 'Day', 'required');
            $this->form_validation->set_rules('inpMMStart', 'Month', 'required');
            $this->form_validation->set_rules('inpYYYYStart', 'Year', 'required');
            $this->form_validation->set_rules('inpDDEnd', 'Day', 'required');
            $this->form_validation->set_rules('inpMMEnd', 'Month', 'required');
            $this->form_validation->set_rules('inpYYYYEnd', 'Year', 'required');
            $start_dat = $this->input->post('inpYYYYStart') . "-" . $this->input->post('inpMMStart') . "-" . $this->input->post('inpDDStart');
            $end_dat   = $this->input->post('inpYYYYEnd') . "-" . $this->input->post('inpMMEnd') . "-" . $this->input->post('inpDDEnd');
            if ($this->form_validation->run()) {
                $filter       = array(
                    "type_period" => "Current",
                );
                $period       = $this->DAO->getPeriods($filter, TRUE, TRUE);
                $current_date = date("Y") . "-" . date("m") . "-" . date("d");
                if ($start_dat <= $current_date) {
                    $response = array(
                        "status" => "error",
                        "message" => "The new period could no be created the same day it start.",
                    );
                    echo json_encode($response);
                    return 0;
                }
                if ($start_dat > $end_dat) {
                    $response = array(
                        "status" => "error",
                        "message" => "The period Date must be aceptable.",
                    );
                    echo json_encode($response);
                    return 0;
                }
                if ($this->input->post('inpMMStart') + 3 >= $this->input->post('inpMMEnd')) {
                    $response = array(
                        "status" => "error",
                        "message" => "The period must be at least 3 months.",
                    );
                    echo json_encode($response);
                    return 0;
                }

                if ($period && $current_date < $period->end_date_period) {
                    $response = array(
                        "status" => "error",
                        "message" => "The period can not be created before the current end.",
                    );
                    echo json_encode($response);
                    return 0;
                }
                if ($period && $period->end_date_period > $start_dat && $period->end_date_period > $current_date) {
                    $response = array(
                        "status" => "error",
                        "message" => "The start date is not valid, the new period couldn't start befor the current period end.",
                    );
                } else {
                    $startM = $this->Month($this->input->post('inpMMStart'));
                    $endM   = $this->Month($this->input->post('inpMMEnd'));
                    if ($this->input->post('inpYYYYStart') == $this->input->post('inpYYYYEnd')) {
                        $name = $startM . "-" . $endM . "-" . $this->input->post('inpYYYYStart');
                    } else {
                        $name = $startM . "-" . $this->input->post('inpYYYYStart') . "-" . $endM . "-" . $this->input->post('inpYYYYEnd');
                    }
                    $this->DAO->trans_begin();
                    $data     = array(
                        "name_period" => $name,
                        "start_date_period" => $start_dat,
                        "end_date_period" => $end_dat,
                        "type_period" => "Current",
                    );
                    $response = $this->DAO->saveAndEditDats("tb_periods", $data);
                    if ($period) {
                        $data     = array(
                            "type_period" => "Past",
                        );
                        $filter   = array(
                            "id_period" => $period->id_period,
                        );
                        $response = $this->DAO->saveAndEditDats("tb_periods", $data, $filter);
                    }
                    $complete = $this->DAO->trans_end();

                    if ($complete) {
                        $response = array(
                            "status" => "success",
                            "message" => "The period was created successfuly.",
                        );
                    } else {
                        $response = array(
                            "status" => "error",
                            "message" => "There was a problem.",
                        );
                    }
                }
            } else {
                $response = array(
                    "status" => "error",
                    "errors" => $this->form_validation->error_array(),
                );
            }
            echo json_encode($response);
        } else {
            redirect("Home");
        }
    }

    function Month($date)
    {
        if ($date == "01") {
            return "January";
        } else if ($date == "02") {
            return "February";
        } else if ($date == "03") {
            return "March";
        } else if ($date == "04") {
            return "April";
        } else if ($date == "05") {
            return "May";
        } else if ($date == "06") {
            return "June";
        } else if ($date == "07") {
            return "July";
        } else if ($date == "08") {
            return "August";
        } else if ($date == "09") {
            return "September";
        } else if ($date == "10") {
            return "October";
        } else if ($date == "11") {
            return "November";
        } else if ($date == "12") {
            return "December";
        }
    }

    private function _check_session()
    {
        $session = $this->session->userdata('up_sess');
        if (!@$session->email_user || $session->type_user != "Admin") {
            redirect('login');
        }
    }
}