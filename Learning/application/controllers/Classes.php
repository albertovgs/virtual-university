<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Classes extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_check_session();
        $this->load->model('DAO');
    }

    function index()
    {
        if ($this->input->get('id')) {
            $filter             = array(
                "clave_class" => $this->input->get('id'),
            );
            $data["class"]      = $this->DAO->getClasses($filter, TRUE);
            $filter             = array(
                "fk_period" => $data["class"]->id_period,
                "fk_class" => $data["class"]->id_class,
                "fk_classroom" => $data["class"]->id_classroom,
            );
            $data["schuedules"] = $this->DAO->queryEntity("tb_schedules", $filter, FALSE);
            $filter             = array(
                "fk_class" => $data["class"]->id_class,
            );
            $data["rate"]       = $this->DAO->queryEntity("tb_class_rates", $filter, TRUE);
            $session            = $this->session->userdata('up_sess');
            if ($session->type_user == "Studen") {
                $filter         = array(
                    "fk_class" => $data["class"]->id_class,
                    "fk_student" => $session->id_user,
                );
                $data["grades"] = $this->DAO->queryEntity("tb_std_cls_clf", $filter, TRUE);
            }
            $this->load->view('includes/header2');
            $this->load->view('includes/navbar2');
            $this->load->view('students/class_page', $data);
            $this->load->view('includes/footer2');
            $this->load->view('students/classes_js');
        } else {
            redirect("Home");
        }
    }

    function ClsWork()
    {
        $session = $this->session->userdata('up_sess');
        if ($session->type_user == "Teacher") {
            $filter             = array(
                "fk_classwork" => $this->input->get('clswkid'),
            );
            $data["classworks"] = $this->DAO->clsWrkStd($filter, False);
            $filter             = array(
                "id_gpc" => $this->input->get('gpc'),
                "fk_profesor" => $session->id_user,
            );
            $data["class"]      = $this->DAO->getClasses($filter, TRUE);
            $data["count"]      = $this->DAO->count(
                "tb_std_classworks",
                array(
                    "fk_classwork" => $this->input->get('clswkid'),
                )
            );
        } elseif ($session->type_user == "Studen") {
            $filter               = array(
                "fk_classwork" => $this->input->get('clswkid'),
                "fk_student" => $session->id_user,
            );
            $data["classworkStd"] = $this->DAO->clsWrkStd($filter, TRUE);
            $filter               = array(
                "id_classwork" => $this->input->get('clswkid'),
            );
            $data["classwork"]    = $this->DAO->queryEntity("tb_classworks", $filter, TRUE);
            $filter               = array(
                "id_gpc" => $this->input->get('gpc'),
                "fk_group" => $session->group_student,
            );
            $data["class"]        = $this->DAO->getClasses($filter, TRUE);
            $filter               = array(
                "fk_student" => $session->id_user,
                "status_comment" => "Active",
                "fk_classwork" => $this->input->get('clswkid'),
            );
            $data["comments"]     = $this->DAO->commentclsWrk($filter);
        }
        if ($data["class"]) {
            $this->load->view('includes/header2');
            $this->load->view('includes/navbar2');
            $this->load->view('students/classwork_page', $data);
            $this->load->view('includes/footer2');
            $this->load->view('students/classes_js');
        } else {

            $this->load->view('error_404');
        }
    }

    function openCnfRates()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('rate')) {
                $filter          = array(
                    "id_class_rate" => $this->input->get('rate'),
                );
                $data["rateCls"] = $this->DAO->queryEntity("tb_class_rates", $filter, TRUE);
                echo $this->load->view('students/rates_conf_form', $data, TRUE);
            }
        }
    }

    function proccCnfRates()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('classRte', '', 'required');
            $this->form_validation->set_rules('inpBe', 'To be', 'required');
            $this->form_validation->set_rules('inpDo', 'To do', 'required');
            $this->form_validation->set_rules('inpKnow', 'To know', 'required');
            if ($this->form_validation->run()) {
                if (100 == ($this->input->post("inpBe") + $this->input->post("inpDo") + $this->input->post("inpKnow"))) {
                    $filter   = array("id_class_rate" => $this->input->post("classRte"));
                    $data     = array(
                        "be_rate" => $this->input->post("inpBe"),
                        "do_rate" => $this->input->post("inpDo"),
                        "know_rate" => $this->input->post("inpKnow"),
                    );
                    $response = $this->DAO->saveAndEditDats("tb_class_rates", $data, $filter);
                    if ($response["status"] == "success") {
                        $response = array(
                            "status" => "success",
                            "message" => "The rate was updated successfuly.",
                        );
                    }
                } else {
                    $response = array(
                        "status" => "error",
                        "message" => "The rate must sum 100%.",
                    );
                }
            } else {
                $response = array(
                    "status" => "error",
                    "errors" => $this->form_validation->error_array(),
                );
            }
            echo json_encode($response);
        }
    }

    public function openClsForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('cls')) {
                if (!$this->input->get('opt')) {
                    $data["cls"] = $this->input->get('cls');
                    echo $this->load->view('students/class_form', $data, TRUE);
                } else {
                    $filter         = array(
                        "id_classwork" => $this->input->get('wrk'),
                    );
                    $data["wrk"]    = $this->input->get('wrk');
                    $data['class']  = $this->DAO->queryEntity("tb_classworks", $filter, TRUE);
                    $data["option"] = $this->input->get('opt');
                    echo $this->load->view('students/class_form', $data, TRUE);
                }
            } else {
                redirect("Home");
            }
        } else {
            redirect("Home");
        }
    }

    function showClsWorks()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('id')) {
                $filter             = array(
                    "fk_gpc" => $this->input->get('id'),
                    "status_classwork" => "Active",
                );
                $data["id"]         = $this->input->get('id');
                $data["classworks"] = $this->DAO->queryEntity("tb_classworks", $filter, FALSE);
                echo $this->load->view('students/classworks', $data, TRUE);
            } else {
                redirect("Home");
            }
        } else {
            redirect("Home");
        }
    }

    function proccessClassworsForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('cls')) {
                $filter        = array("clave_class" => $this->input->post('cls'));
                $data['class'] = $this->DAO->queryEntity("tb_classes", $filter, TRUE);
                if (!$this->input->post('option')) {
                    $this->form_validation->set_rules('inpTitle', 'Title', 'required');
                    $this->form_validation->set_rules('inpDesc', 'Description', 'required');
                    $this->form_validation->set_rules('inpDueDate', 'Due date', 'required');
                    $this->form_validation->set_rules('inpDueTime', 'Due time', 'required');
                    $this->form_validation->set_rules('inpPart', 'Partial', 'required');
                    $this->form_validation->set_rules('inpRate', 'Rate', 'required');

                    if (!$this->form_validation->run()) {
                        echo json_encode(
                            array(
                                "status" => "error",
                                "errors" => $this->form_validation->error_array(),
                            )
                        );
                        return 0;
                    }
                }

                $session = $this->session->userdata('up_sess');
                $filter  = array(
                    "id_class" => $data['class']->id_class,
                    "id_person" => $session->id_user,
                    "type_period" => "Current",
                );

                $class = $this->DAO->getClasses($filter, TRUE);
                if (!$class) {
                    echo json_encode(
                        array(
                            "status" => "error",
                            "message" => "You do not have access to this class.",
                        )
                    );
                    return 0;
                }
                $data = array(
                    "title_classwork" => $this->input->post("inpTitle"),
                    "content_classwork" => $this->input->post("inpDesc"),
                    "part_classwork" => $this->input->post("inpPart"),
                    "type_classwork" => $this->input->post("inpRate"),
                    "fk_gpc" => $class->id_gpc,
                    "date_end_classwork" => $this->input->post("inpDueDate"),
                    "time_end_classwork" => $this->input->post("inpDueTime"),
                );
                if ($this->input->post('option')) {
                    $filter = array(
                        "id_classwork" => $this->input->post('wrk'),
                    );
                } else {
                    $filter = array();
                }
                $save = $this->DAO->saveAndEditDats("tb_classworks", $data, $filter);
                if ($save["status"] == "success") {
                    $response = array(
                        "status" => "success",
                        "message" => "The Classwok " . $this->input->post("inpTitle") . " was added successfuly."
                    );
                } else {
                    $response = array(
                        "status" => "error",
                        "message" => "There was a problem.",
                    );
                }
                echo json_encode($response);
            }
        }
    }

    function delClassworsForm()
    {
        if (!$this->input->is_ajax_request()) {
            redirect("Home");
        }
        if ($this->input->post('inpOp') == "drop" && $this->input->post('inpId') && $this->input->get('cls')) {
            $session       = $this->session->userdata('up_sess');
            $filter        = array(
                "id_class" => $this->input->get('cls'),
                "id_person" => $session->id_user,
            );
            $data["class"] = $this->DAO->getClasses($filter, TRUE);
            if ($data["class"]) {
                $filter = array(
                    "id_classwork" => $this->input->post('inpId'),
                );
                $data   = array(
                    "status_classwork" => "Inactive",
                );
                $save   = $this->DAO->saveAndEditDats("tb_classworks", $data, $filter);
                if ($save["status"] == "success") {
                    $response = array(
                        "status" => "success",
                        "message" => "The Classwok " . $this->input->post("inpTitle") . " was removed successfuly."
                    );
                } else {
                    $response = array(
                        "status" => "error",
                        "message" => "There was a problem.",
                    );
                }
            } else {
                $response = array(
                    "status" => "error",
                    "message" => "You do not have access to this class.",
                );
            }
            echo json_encode($response);
        } else {
            redirect("Home");
        }
    }

    function prcBtn()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('cls') && $this->input->get('opt')) {
                if ($this->input->get('opt') == "edit") {
                    $this->openClsForm();
                } elseif ($this->input->get('opt') == "drop") {
                    $data["id"]      = "drop_classwork";
                    $data["option"]  = $this->input->get('opt');
                    $data["message"] = "To delete the classwork Confirm this modal.";
                    $data["code"]    = $this->input->get('wrk');
                    $data["cls"]     = $this->input->get('cls');
                    echo $this->load->view('includes/confirmation', $data, TRUE);
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

    function DeliverForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('cls') && $this->input->get('grp') && $this->input->get('wrk')) {
                $data["dats"] = array(
                    "cls" => $this->input->get('cls'),
                    "grp" => $this->input->get('grp'),
                    "wrk" => $this->input->get('wrk'),
                );
                echo $this->load->view('students/classwork_form', $data, TRUE);
            }
        }
    }

    function DeliverProccess()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('cls') && $this->input->post('grp') && $this->input->post('wrk')) {
                $this->form_validation->set_rules('inpFile', 'File', 'callback_file_check');
                $this->form_validation->set_rules('cls', '', 'required');
                $this->form_validation->set_rules('grp', '', 'required');
                $this->form_validation->set_rules('wrk', '', 'required');
                if ($this->form_validation->run()) {
                    $filter  = array(
                        "id_gpc" => $this->input->post('grp'),
                        "clave_class" => $this->input->post('cls'),
                    );
                    $class   = $this->DAO->getClasses($filter, TRUE);
                    $session = $this->session->userdata('up_sess');
                    if ($class && $class->id_group == $session->group_student) {
                        $config['upload_path']   = "./resources/files/clsWrk";
                        $config['allowed_types'] = "*";
                        $config['file_name']     = uniqid();
                        $this->load->library('upload', $config);
                        if ($this->upload->do_upload('inpFile')) {
                            $image   = base_url('') . $config['upload_path'] . "/" . $this->upload->data()['file_name'];
                            $flujo[] = $config;
                        } else {
                            $response = array(
                                "status" => "error",
                                "errors" => "Error in File: " . json_encode($config['file_name']) . $this->upload->display_errors()
                            );
                            echo json_encode($response);
                            return 0;
                        }
                        if ($image) {
                            $data     = array(
                                "file_classwork" => $image,
                                "fk_student" => $session->id_user,
                                "fk_classwork" => $this->input->post('wrk'),
                            );
                            $response = $this->DAO->saveAndEditDats("tb_std_classworks", $data, null);
                        }
                        if ($response["status"] == "success") {
                            $response = array(
                                "status" => "success",
                                "message" => "The Classwork was added successfuly.",
                            );
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
                        "errors" => $this->form_validation->error_array(),
                    );
                }
                echo json_encode($response);
            }
        }
    }

    function gradeForm()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->get('clsWrk')) {
                $data["clsWrk"] = $this->input->get('clsWrk');
                echo $this->load->view('students/grade_form', $data, TRUE);
            }
        }
    }

    function GradeProccess()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('clsWrk')) {
                $this->form_validation->set_rules('inpGrade', 'Grade', 'required');
                if ($this->form_validation->run()) {
                    $filter   = array(
                        "id_std_classwork" => $this->input->post('clsWrk'),
                    );
                    $data     = array(
                        "calf_classwork" => $this->input->post('inpGrade'),
                    );
                    $complete = $this->DAO->saveAndEditDats("tb_std_classworks", $data, $filter);
                    if ($complete["status"] == "success") {
                        $response = array(
                            "status" => "success",
                            "message" => "Grade was asign successfuly.",
                        );
                    } else {
                        $response = array(
                            "status" => "error",
                            "message" => "There was a problem.",
                        );
                    }
                } else {
                    $response = array(
                        "status" => "error",
                        "errors" => $this->form_validation->error_array(),
                    );
                }
                if ($this->input->post('inpComment')) {
                    $this->DAO->trans_begin();
                    $session = $this->session->userdata('up_sess');
                    $data    = array(
                        "content_comment" => $this->input->post('inpComment'),
                        "fk_user_comment" => $session->id_user,
                    );
                    $this->DAO->saveAndEditDats("tb_comments", $data);
                    $id   = $this->DAO->obtain_id();
                    $data = array(
                        "fk_cw" => $this->input->post('clsWrk'),
                        "fk_comment" => $id,
                    );
                    $this->DAO->saveAndEditDats("tb_classwork_comments", $data);
                    $complete = $this->DAO->trans_end();
                    if ($complete) {
                        $response = array(
                            "status" => "success",
                            "message" => "Grade was asign successfuly.",
                        );
                    } else {
                        $response = array(
                            "status" => "error",
                            "message" => "There was a problem.",
                        );
                    }
                }
                echo json_encode($response);
            }
        }
    }

    public function file_check()
    {
        if (isset($_FILES["inpFile"]) && $_FILES["inpFile"]["name"]) {
            return TRUE;
        } else {
            $this->form_validation->set_message('File', 'The File can not be empty.');
            return FALSE;
        }
    }

    function GenGradesPart()
    {
        if (!$this->input->is_ajax_request())
            return;
        if (!$this->input->get('opt') && !$this->input->get('gpc'))
            return;
        $part_class = $this->input->get('opt') == "first" ? "Firts" : "Second";

        $session = $this->session->userdata('up_sess');

        $filter        = array(
            "id_gpc" => $this->input->get('gpc'),
            "fk_profesor" => $session->id_user,
        );
        $data["class"] = $this->DAO->getClasses($filter, TRUE);
        if (!$data["class"])
            return;

        $filter           = array(
            "fk_gpc" => $data["class"]->id_gpc,
            "part_classwork" => $part_class,
            "type_classwork" => "To do",
        );
        $data["TodoNumb"] = $this->DAO->count("tb_classworks", $filter);

        $filter["type_classwork"] = "To be";
        $data["TobeNumb"]         = $this->DAO->count("tb_classworks", $filter);

        $filter["type_classwork"] = "To know";
        $data["ToknowNumb"]       = $this->DAO->count("tb_classworks", $filter);

        $filter        = array(
            "fk_class" => $data["class"]->id_class,
        );
        $data["rates"] = $this->DAO->queryEntity("tb_class_rates", $filter, TRUE);


        $filter           = array(
            "clave_group" => $data["class"]->clave_group,
        );
        $data["students"] = $this->DAO->StudentsTable($filter, FALSE);

        $countPCC = 0;
        foreach ($data["students"] as $std) {
            $gradePart        = [];
            $filter           = array(
                "fk_gpc" => $data["class"]->id_gpc,
                "part_classwork" => $part_class,
                "fk_student" => $std->id_student,
            );
            $data["stdWorks"] = $this->DAO->studentsclsWrk($filter, FALSE);
            $data["tdWorks"]  = 0;
            $data["tbWorks"]  = 0;
            $data["tkWorks"]  = 0;
            $data["clfDo"]    = 0;
            $data["clfBE"]    = 0;
            $data["clfKnow"]  = 0;
            foreach ($data["stdWorks"] as $stdWorks) {
                if ($stdWorks->type_classwork == "To do") {
                    $data["tdWorks"] = $data["tdWorks"] + 1;
                    $data["clfDo"]   = $data["clfDo"] + $stdWorks->calf_classwork;
                }
                if ($stdWorks->type_classwork == "To be") {
                    $data["tbWorks"] = $data["tdWorks"] + 1;
                    $data["clfBE"]   = $data["clfBE"] + $stdWorks->calf_classwork;
                }
                if ($stdWorks->type_classwork == "To know") {
                    $data["tkWorks"] = $data["tdWorks"] + 1;
                    $data["clfKnow"] = $data["clfKnow"] + $stdWorks->calf_classwork;
                }
            }
            $ToDOgrade   = 0;
            $ToKnowgrade = 0;
            $ToBEgrade   = 0;
            if ($data["rates"]->be_rate != 0) {
                $ToBEgrade = ($data["clfBE"] / $data["TobeNumb"]) * (($data["rates"]->be_rate) / 100);
            }
            if ($data["rates"]->do_rate != 0) {
                $ToDOgrade = ($data["clfDo"] / $data["TodoNumb"]) * (($data["rates"]->do_rate) / 100);
            }
            if ($data["rates"]->know_rate != 0) {
                $ToKnowgrade = ($data["clfKnow"] / $data["ToknowNumb"]) * (($data["rates"]->know_rate) / 100);
            }
            $gradePart = $ToBEgrade + $ToDOgrade + $ToKnowgrade;

            $datasv  = array(
                "calf_f_class" => $gradePart,
                "fk_student" => $std->id_user,
                "fk_class" => $data["class"]->id_class,
            );
            $filter  = array(
                "fk_student" => $std->id_user,
                "fk_class" => $data["class"]->id_class,
            );
            $stdCalf = $this->DAO->queryEntity("tb_std_cls_clf", $filter, TRUE);
            if ($stdCalf) {
                $filter = array("id_std_cls_clf" => $stdCalf->id_std_cls_clf);
                $this->DAO->saveAndEditDats("tb_std_cls_clf", $datasv, $filter);
            } else {
                $this->DAO->saveAndEditDats("tb_std_cls_clf", $datasv);
            }
            $countPCC += 1;
        }
        if ($countPCC == count($data["students"])) {
            $response       = array(
                "status" => "success",
                "message" => "Grades was asigned successfuly.",
            );
            $filter         = array(
                "id_class" => $data["class"]->id_class,
                "class_part" => $part_class,
            );
            $part_class_new = $part_class == "Firts" ? $part_class = "Second" : $part_class = "Graded";
            $part_class_new = $part_class == "Second" ? $part_class = "Graded" : $part_class = "First";
            $datasv = array(
                "class_part" => $part_class_new,
            );
            $this->DAO->saveAndEditDats("tb_classes", $datasv, $filter);
        } else {
            $response = array(
                "status" => "error",
                "message" => "There was a problem.",
            );
        }
        echo json_encode($response);
    }

    function GradClass()
    {
        if (!$this->input->is_ajax_request())
            return;
        if (!$this->input->get('gpc'))
            return;
        $session          = $this->session->userdata('up_sess');
        $filter           = array("id_gpc" => $this->input->get('gpc'), "fk_profesor" => $session->id_user);
        $data["class"]    = $this->DAO->getClasses($filter, TRUE);
        $filter           = array(
            "clave_group" => $data["class"]->clave_group,
        );
        $data["students"] = $this->DAO->StudentsTable($filter, FALSE);
        foreach ($data["students"] as $std) {
            $filter  = array(
                "fk_student" => $std->id_user,
                "fk_class" => $data["class"]->id_class,
            );
            $stdCalf = $this->DAO->queryEntity("tb_std_cls_clf", $filter, TRUE);
            if ($stdCalf) {
                $classClf = ($stdCalf->calf_f_class + $stdCalf->calf_s_class) / 2;

                $datasv = array(
                    "calf_class" => $classClf,
                );
                $filter = array("id_std_cls_clf" => $stdCalf->id_std_cls_clf);
                $comple = $this->DAO->saveAndEditDats("tb_std_cls_clf", $datasv, $filter);
                if ($comple["status"] == "success") {
                    $response = array(
                        "status" => "success",
                        "message" => "Grades was asigned successfuly.",
                    );
                } else {
                    $response = array(
                        "status" => "error",
                        "message" => "There was a problem.",
                    );
                }
            } else {
                $response = array(
                    "status" => "error",
                    "message" => "You can not grade the class before grade the first and second part.",
                );
            }
            echo json_encode($response);
        }
    }

    private function _check_session()
    {
        $session = $this->session->userdata('up_sess');
        if (!@$session->email_user || $session->type_user == "Admin" || $session->type_user == "Cordi") {
            redirect('login');
        }
    }
}