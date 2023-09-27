<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Majors extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_check_session();
        $this->load->model('DAO');
    }


    public function loadMajorAdmin()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('id')) {
                $data["id"] = $this->input->get('id');
                echo $this->load->view('admin/coordinators/majors/major_admin', $data, TRUE);
            }
        }
    }

    function loadTabs()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('id')) {
                $data["id"] = $this->input->get('id');
                switch ($this->input->get('op')) {
                    case 'P':
                        echo $this->load->view('admin/coordinators/majors/periods_page', $data, TRUE);
                        break;
                    case 'G':
                        echo $this->load->view('admin/coordinators/majors/groups_page', $data, TRUE);
                        break;
                    case 'S':
                        $filter = array(
                            "major_student" => $this->input->get('id'),
                            "status_user" => "Active",
                        );
                        $data["active"] = count($this->DAO->StudentsTable($filter, FALSE));
                        $filter = array(
                            "major_student" => $this->input->get('id'),
                            "status_user" => "Inactive",
                        );
                        $data["inactive"] = count($this->DAO->StudentsTable($filter, FALSE));
                        echo $this->load->view('admin/coordinators/majors/students_page', $data, TRUE);
                        break;
                    case 'C':
                        echo $this->load->view('admin/coordinators/majors/classes_page', $data, TRUE);
                        break;
                }
            }
        }
    }
    public function openPrdForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('id')) {
                $filter         = array(
                    "id_major" => $this->input->get('id'),
                );
                $data["id"]     = $this->input->get('id');
                $data['major']  = $this->DAO->queryEntity("tb_majors", $filter, TRUE);
                $filter         = array(
                    "type_period" => "Current",
                );
                $data["period"] = $this->DAO->queryEntity("tb_periods", $filter, TRUE);
                echo $this->load->view('admin/coordinators/majors/periods_form', $data, TRUE);
            }
        }
    }

    public function openGroupForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('id')) {
                if ($this->input->post('inpMajor')) {
                    if ($this->input->post('inpMajor') == $this->input->get('id')) {
                        $filter        = array(
                            "id_major" => $this->input->get('id'),
                        );
                        $data['major'] = $this->DAO->queryEntity("tb_majors", $filter, TRUE);
                        $session       = $this->session->userdata('up_sess');
                        if ($data['major']->cordi_major == $session->id_user) {
                            $filter              = array(
                                "major_group" => $data['major']->id_major,
                            );
                            $ng                  = $this->DAO->count("tb_groups", $filter);
                            $filter              = array(
                                "type_period" => "Current",
                            );
                            $data["period"]      = $this->DAO->queryEntity("tb_periods", $filter, TRUE);
                            $data["clave_group"] = $data['major']->clave_major . "-" . ($ng + 1);
                            $this->DAO->trans_begin();
                            $data_grp = array(
                                "clave_group" => $data["clave_group"],
                                "major_group" => $data['major']->id_major,
                                "nmb_students" => 0,
                                "last_quarter" => $data["period"]->name_period,
                            );
                            $this->DAO->saveAndEditDats("tb_groups", $data_grp);
                            $id      = $this->DAO->obtain_id();
                            $data_gp = array(
                                "fk_group" => $id,
                                "fk_period" => $data["period"]->id_period,
                            );
                            $this->DAO->saveAndEditDats("tb_periods_groups", $data_gp);
                            $complete = $this->DAO->trans_end();
                            if ($complete) {
                                $response = array(
                                    "status" => "success",
                                    "message" => "The new group was created successfuly.",
                                );
                            } else {
                                $response = array(
                                    "status" => "error",
                                    "message" => "There was an error, contact the dev.",
                                );
                            }
                        } else {
                            $response = array(
                                "status" => "error",
                                "message" => "You can not create a group in this major.",
                            );
                        }
                    } else {
                        $response = array(
                            "status" => "error",
                            "message" => "There was a problem.",
                        );
                    }
                    echo json_encode($response);
                } else {
                    $filter              = array(
                        "id_major" => $this->input->get('id'),
                    );
                    $data["id"]          = $this->input->get('id');
                    $data['major']       = $this->DAO->queryEntity("tb_majors", $filter, TRUE);
                    $filter              = array(
                        "major_group" => $data['major']->id_major,
                    );
                    $ng                  = $this->DAO->count("tb_groups", $filter);
                    $filter              = array(
                        "type_period" => "Current",
                    );
                    $data["period"]      = $this->DAO->queryEntity("tb_periods", $filter, TRUE);
                    $data["clave_group"] = $data['major']->clave_major . "-" . ($ng + 1);
                    echo $this->load->view('admin/coordinators/majors/group_form', $data, TRUE);
                }
            }
        }
    }

    function showPeriods()
    {
        if ($this->input->is_ajax_request()) {
            $session = $this->session->userdata('up_sess');
            if ($this->input->get('id')) {
                $filter          = array(
                    "cordi_major" => $session->id_user,
                    "id_major" => $this->input->get('id'),
                );
                $data["id"]      = $this->input->get('id');
                $data["periods"] = $this->DAO->getPeriods($filter);
                echo $this->load->view('admin/coordinators/majors/periods_table.php', $data, TRUE);
            } else {
                return $response = array(
                    "status" => "error",
                );
            }
        }
    }

    function showGroups()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('id')) {
                $filter         = array(
                    "major_group" => $this->input->get('id'),
                );
                $data["id"]     = $this->input->get('id');
                $data["groups"] = $this->DAO->getGPS($filter);

                echo $this->load->view('admin/coordinators/majors/groups_table.php', $data, TRUE);
            } else {
                return $response = array(
                    "status" => "error",
                );
            }
        }
    }

    function proccessPeriodsForm()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('inpMajor', 'Major', 'required');
            $this->form_validation->set_rules('inpNameP', 'Name', 'required');
            $this->form_validation->set_rules('inpDateStart', 'Start date', 'required');
            $this->form_validation->set_rules('inpDateEnd', 'End date', 'required');

            if (!$this->form_validation->run()) {
                $response = array(
                    "status" => "error",
                    "errors" => $this->form_validation->error_array(),
                );
                echo json_encode($response);
                return;
            }

            $session = $this->session->userdata('up_sess');
            $filter  = array(
                "cordi_major" => $session->id_user,
                "id_major" => $this->input->post("inpMajor"),
            );
            $major   = $this->DAO->queryEntity("tb_majors", $filter, TRUE);
            if (!$major) {
                $response = array(
                    "status" => "error",
                    "message" => "Data is incorrect.",
                );
                echo json_encode($response);
                return;
            }

            $filter = array(
                "cordi_major" => $session->id_user,
                "type_period" => "Current",
                "fk_major" => $major->id_major,
            );
            $period = $this->DAO->getPeriods($filter, TRUE);
            if ($period) {
                $response = array(
                    "status" => "error",
                    "message" => "Alrready exist a period for this major.",
                );
                echo json_encode($response);
                return;
            }

            $this->DAO->trans_begin();
            $filter   = array(
                "name_period" => $this->input->post("inpNameP"),
                "type_period" => "Current",
            );
            $cperiod  = $this->DAO->queryEntity("tb_periods", $filter, TRUE);
            $data     = array(
                "fk_period" => $cperiod->id_period,
                "fk_major" => $this->input->post("inpMajor"),
            );
            $response = $this->DAO->saveAndEditDats("tb_periods_major", $data);
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
            echo json_encode($response);
        } else {
            redirect("Home");
        }
    }


    public function getGroups()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('major')) {
                $filter         = array(
                    "major_group" => $this->input->get('major'),
                );
                $data["groups"] = $this->DAO->queryEntity("tb_groups", $filter, FALSE);
                echo json_encode($this->load->view('admin/coordinators/majors/groups', $data, TRUE));
            }
        }
    }

    function openGroupsForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('option') && $this->input->get('id')) {
                if ($this->input->get('option') == "level-up") {
                    $data["id"]         = $this->input->get('id');
                    $filter             = array(
                        "id_group" => $this->input->get('id'),
                    );
                    $data["group"]      = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
                    $data["option"]     = $this->input->get('option');
                    $filter             = array(
                        "fk_group" => $data["group"]->id_group,
                    );
                    $data["quarters"]   = $this->DAO->count("tb_periods_groups", $filter);
                    $filter             = array(
                        "type_period" => "Current",
                        "fk_major" => $data["group"]->major_group,
                    );
                    $data["currentQua"] = $this->DAO->getPeriods($filter, TRUE);
                    echo $this->load->view("admin/coordinators/majors/Gtool_form", $data, TRUE);
                } else if ($this->input->get('option') == "shutdown") {
                    $data["id"]      = "shutdownGroup";
                    $filter          = array(
                        "id_group" => $this->input->get('id'),
                    );
                    $data["group"]   = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
                    $data["message"] = "To shutdown the " . $data["group"]->clave_group . " group Confirm this modal.";
                    $data["code"]    = $this->input->get('id');
                    $data["option"]  = $this->input->get('option');
                    echo $this->load->view('includes/confirmation', $data, TRUE);
                }
            } else {
                redirect("Home");
            }
        }
    }

    function processGroupsForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('major')) {
                $this->form_validation->set_rules('inpIdGrp', 'Major', 'required');
                $this->form_validation->set_rules('inpGrp', 'Group', 'required');
                $this->form_validation->set_rules('inpLastPeriod', 'Last Period', 'required');
                $this->form_validation->set_rules('inpCurrPeriod', 'Current Period', 'required');
                if ($this->form_validation->run()) {
                    $session = $this->session->userdata('up_sess');
                    $filter  = array(
                        "id_major" => $this->input->get('major'),
                    );
                    $major   = $this->DAO->queryEntity("tb_majors", $filter, TRUE);
                    if ($session->id_user == $major->cordi_major) {
                        $filter        = array(
                            "id_group" => $this->input->post('inpIdGrp'),
                        );
                        $data["id"]    = $this->input->get('major');
                        $data["group"] = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
                        if ($data["group"]->clave_group == $this->input->post('inpGrp')) {
                            $filter             = array(
                                "type_period" => "Current",
                                "fk_major" => $data["group"]->major_group,
                            );
                            $data["currentQua"] = $this->DAO->getPeriods($filter, TRUE);
                            $fk_period          = $data["currentQua"]->id_period;
                            $last_quarter       = $data["currentQua"]->name_period;
                            if ($data["currentQua"]->name_period == $this->input->post('inpLastPeriod')) {
                                $response = array(
                                    "status" => "error",
                                    "message" => "The group is already in the curent period.",
                                );
                                echo json_encode($response);
                                return 0;
                            } else {
                                $this->DAO->trans_begin();
                                $data = array(
                                    "fk_group" => $this->input->post('inpIdGrp'),
                                    "fk_period" => $fk_period,
                                );
                                $this->DAO->saveAndEditDats("tb_periods_groups", $data);
                                $filter = array("id_group" => $this->input->post('inpIdGrp'), );
                                $data   = array(
                                    "last_quarter" => $last_quarter,
                                );
                                $this->DAO->saveAndEditDats("tb_groups", $data, $filter);
                                $complete = $this->DAO->trans_end();
                                if ($complete) {
                                    $response = array(
                                        "status" => "success",
                                        "message" => $data["group"]->clave_group . " group was upgraded successfuly.",
                                    );
                                } else {
                                    $response = $complete;
                                }
                            }
                        } else {
                            $response = array(
                                "status" => "error",
                                "message" => "There was a problem.",
                            );
                        }
                    } else {
                        $response = array(
                            "status" => "error",
                            "message" => "You can not access to thi Major .",
                        );
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

    function openClassForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get("id")) {
                $data["id"]         = $this->input->get("id");
                $filter             = array(
                    "type_user" => "Teacher",
                    "status_user" => "Active",
                );
                $data["profesors"]  = $this->DAO->profesorsTable($filter, FALSE);
                $data["classrooms"] = $this->DAO->queryEntity("tb_classrooms", null, FALSE);
                if (!$this->input->get("option")) {
                    $filter         = array(
                        "major_group" => $this->input->get("id"),
                        "status_group" => "Active",
                    );
                    $data["groups"] = $this->DAO->queryEntity("tb_groups", $filter, FALSE);
                    echo $this->load->view("admin/coordinators/majors/classes_form", $data, true);
                } else {
                    $data["option"]      = $this->input->get("option");
                    $filter              = array(
                        "id_class" => $this->input->get("id"),
                        "type_period" => "Current",
                    );
                    $data["classFinded"] = $this->DAO->getClasses($filter, TRUE);
                    if ($this->input->get("option") == "edit") {
                        $filter         = array(
                            "major_group" => $data["classFinded"]->major_group,
                            "status_group" => "Active",
                        );
                        $data["groups"] = $this->DAO->queryEntity("tb_groups", $filter, FALSE);
                        echo $this->load->view("admin/coordinators/majors/classes_form", $data, true);
                    } else if ($this->input->get("option") == "schedule") {
                        echo $this->load->view("admin/coordinators/majors/schedule_form", $data, true);
                    } elseif ($this->input->get("option") == "assign") {
                        $filter              = array(
                            "id_class" => $this->input->get("id"),
                            "type_period" => "Past",
                        );
                        $data["classFinded"] = $this->DAO->getClasses($filter, TRUE);
                        $filter              = array(
                            "major_group" => $data["classFinded"]->major_group,
                            "status_group" => "Active",
                        );
                        $data["groups"]      = $this->DAO->queryEntity("tb_groups", $filter, FALSE);
                        $data["id"]          = $data["classFinded"]->major_group;
                        echo $this->load->view("admin/coordinators/majors/classes_form", $data, true);
                    }
                }
            } else {
                redirect("Home");
            }
        } else {
            redirect("Home");
        }
    }

    function proccessClassForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post("option") && $this->input->post("option") == "edit") {
                $this->DAO->trans_begin();
                $filter = array("id_class" => $this->input->post("code"));
                $data   = array(
                    "name_class" => $this->input->post("inpClassName"),
                    "lab_class" => $this->input->post("inpclassRoom"),
                );
                $this->DAO->saveAndEditDats("tb_classes", $data, $filter);
                $filter = array(
                    "fk_class" => $this->input->post("code"),
                    "fk_group" => $this->input->post("inpGroup"),
                );
                $data   = array(
                    "fk_profesor" => $this->input->post("inpProfessor"),
                    "fk_group" => $this->input->post("inpGroup"),
                );
                $this->DAO->saveAndEditDats("tb_group_Pro_Class", $data, $filter);
                $complete = $this->DAO->trans_end();
                if ($complete) {
                    $response = array(
                        "status" => "success",
                        "message" => $this->input->post("inpClassName") . " class was edited successfuly.",
                    );
                } else {
                    $response = $complete;
                }
                echo json_encode($response);
            } else {
                if (!$this->input->get("major")) {
                    redirect("Home");
                    return;
                }
                $this->form_validation->set_rules('inpClassName', 'Name', 'required|min_length[5]');
                $this->form_validation->set_rules('inpGroup', 'Clave group', 'required');
                $this->form_validation->set_rules('inpProfessor', 'Clave group', 'required');
                $this->form_validation->set_rules('inpclassRoom', 'classroom group', 'required');
                if (!$this->form_validation->run()) {
                    $response = array(
                        "status" => "error",
                        "errors" => $this->form_validation->error_array(),
                    );
                    echo json_encode($response);
                    return;
                }
                $session = $this->session->userdata('up_sess');
                $filter  = array(
                    "id_major" => $this->input->get("major"),
                );
                $major   = $this->DAO->queryEntity("tb_majors", $filter, TRUE);
                if ($major->cordi_major != $session->id_user) {
                    $response = array(
                        "status" => "error",
                        "message" => "You dont have access to this major.",
                    );
                    echo json_encode($response);
                    return;
                }
                $filter = array(
                    "id_user" => $this->input->post("inpProfessor"),
                );
                $prof   = $this->DAO->queryEntity("tb_users", $filter, TRUE);
                $filter = array(
                    "id_group" => $this->input->post("inpGroup"),
                );
                $group  = $this->DAO->queryEntity("tb_groups", $filter, TRUE);
                if ($prof->status_user != "Active" && $group->status_group != "Active") {
                    $response = array(
                        "status" => "error",
                        "message" => "The professor or the group are not active.",
                    );
                    echo json_encode($response);
                    return;
                }

                $filter    = array(
                    "type_period" => "Current",
                );
                $fk_period = $this->DAO->queryEntity("tb_periods", $filter, TRUE);
                if (!$fk_period) {
                    $response = array(
                        "status" => "error",
                        "message" => "There are any periods actives to assign.",
                    );
                    echo json_encode($response);
                    return;
                }
                if ($group->last_quarter != $fk_period->name_period) {
                    $response = array(
                        "status" => "error",
                        "message" => "The group must be upgraded to the current period.",
                    );
                    echo json_encode($response);
                    return;
                }
                $filter = array(
                    "name_class" => $this->input->post("inpClassName"),
                    "name_period" => $fk_period->name_period,
                    "fk_group" => $this->input->post("inpGroup"),
                    "fk_profesor" => $this->input->post("inpProfessor"),
                );
                $exist  = $this->DAO->getClasses($filter, TRUE);
                if ($exist) {
                    $response = array(
                        "status" => "error",
                        "message" => "This class already exist.",
                    );
                    echo json_encode($response);
                    return;
                }
                $classClave = $this->generateClave(5);
                $this->DAO->trans_begin();
                $data = array(
                    "name_class" => $this->input->post("inpClassName"),
                    "clave_class" => $classClave,
                    "lab_class" => $this->input->post("inpclassRoom"),
                );
                $this->DAO->saveAndEditDats("tb_classes", $data, null);
                $id   = $this->DAO->obtain_id();
                $data = array(
                    "fk_profesor" => $prof->id_user,
                    "fk_class" => $id,
                    "fk_period" => $fk_period->id_period,
                    "fk_group" => $group->id_group,
                );
                $this->DAO->saveAndEditDats("tb_group_Pro_Class", $data, null);
                $data = array(
                    "be_rate" => 10,
                    "do_rate" => 40,
                    "know_rate" => 50,
                    "fk_class" => $id,
                );
                $this->DAO->saveAndEditDats("tb_class_rates", $data, null);
                $complete = $this->DAO->trans_end();
                if ($complete) {
                    $response = array(
                        "status" => "success",
                        "message" => $this->input->post("inpClassName") . " class was created successfuly.",
                    );
                } else {
                    $response = $complete;
                }
                echo json_encode($response);
            }
        } else {
            redirect("Home");
        }
    }

    function sowClasses()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get("id")) {
                $session = $this->session->userdata('up_sess');
                $filter  = array(
                    "id_major" => $this->input->get("id"),
                );
                $major   = $this->DAO->queryEntity("tb_majors", $filter, TRUE);
                if ($major->cordi_major == $session->id_user) {
                    if ($this->input->get("status") == "inactive") {
                        $type           = "Past";
                        $data["option"] = $this->input->get("status");
                    } else {
                        $type = "Current";
                    }
                    $filter            = array(
                        "major_group" => $this->input->get("id"),
                        "type_period" => $type,
                    );
                    $data["classes"]   = $this->DAO->getClasses($filter, FALSE);
                    $data["schedules"] = $this->DAO->queryEntity("tb_schedules", null, FALSE);
                    $data["id"]        = $this->input->get("id");
                    echo $this->load->view("admin/coordinators/majors/classes_table", $data, true);
                } else {
                    redirect("Home");
                }
            } else {
                redirect("Home");
            }
        } else {
            redirect("Home");
        }
    }

    function proccessScheduleForm()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('code', 'Class', 'required');
            $this->form_validation->set_rules('option', 'Option', 'required');
            $this->form_validation->set_rules('ClassName', 'Class name Period', 'required');
            $this->form_validation->set_rules('inpGroup', 'Group clave', 'required');
            $this->form_validation->set_rules('startTime', 'Start time', 'required');
            $this->form_validation->set_rules('endTime', 'End time', 'required');
            $this->form_validation->set_rules('inpDAy', 'Day', 'required|min_length[5]');
            if ($this->form_validation->run()) {
                if (!$this->input->post("startTime") < "08:00" || !$this->input->post("endTime") > "20:00") {
                    $filter = array(
                        "id_class" => $this->input->post("code"),
                        "id_group" => $this->input->post("inpGroup"),
                        "type_period" => "current",
                    );
                    $classe = $this->DAO->getClasses($filter, TRUE);
                    if ($classe) {
                        $filter    = array(
                            "day_schedule" => $this->input->post("inpDAy"),
                            "fk_classroom" => $classe->id_classroom,
                            "type_period" => "Current",
                        );
                        $schedules = $this->DAO->getSchedules($filter, false);
                        if ($schedules) {
                            foreach ($schedules as $sche) {
                                if ($sche->start_schedule <= $this->input->post("endTime") && $sche->end_schedule >= $this->input->post("startTime")) {
                                    $response = array(
                                        "status" => "error",
                                        "message" => "the schedule for class and classroom can not be at the same time of another one.",
                                    );
                                    echo json_encode($response);
                                    return 0;
                                }
                                if ($sche->start_schedule == $this->input->post("startTime") && $sche->end_schedule == $this->input->post("endTime")) {
                                    $response = array(
                                        "status" => "error",
                                        "message" => "the schedule for class and classroom can not be at the same time of another one.",
                                    );
                                    echo json_encode($response);
                                    return 0;
                                }
                            }
                        } else {
                            $this->DAO->trans_begin();
                            $data = array(
                                "day_schedule" => $this->input->post("inpDAy"),
                                "start_schedule" => $this->input->post("startTime"),
                                "end_schedule" => $this->input->post("endTime"),
                                "fk_period" => $classe->id_period,
                                "fk_class" => $classe->id_class,
                                "fk_classroom" => $classe->id_classroom,
                            );
                            $this->DAO->saveAndEditDats("tb_schedules", $data, null);
                            $complete = $this->DAO->trans_end();
                            if ($complete) {
                                $response = array(
                                    "status" => "success",
                                    "message" => $this->input->post("inpClassName") . " class was edited successfuly.",
                                );
                            } else {
                                $response = $complete;
                            }
                        }
                    } else {
                        $response = array(
                            "status" => "error",
                            "messge" => "There was a problem.",
                        );
                    }
                } else {
                    $response = array(
                        "status" => "error",
                        "messge" => "The time range must be acceptable.",
                    );
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

    private function generateClave($length)
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
        if (!@$session->email_user || $session->type_user == "Studen" || $session->type_user == "Teacher") {
            redirect('login');
        }
    }
}