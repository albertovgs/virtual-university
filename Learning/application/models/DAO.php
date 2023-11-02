<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DAO extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function login($email, $password)
  {
    $this->db->where('email_user', $email);
    $query      = $this->db->get("tb_users");
    $user_exist = $query->row();
    if ($user_exist->type_user == "Studen") {
      $student = True;
    } else {
      $student = False;
    }
    if ($user_exist) {
      if ($user_exist->password_user == $password || $user_exist->password_tem_user == $password) {
        $this->db->where('email_user', $email);
        if ($student) {
          $this->db->select('id_user,name_person,lastname_person,gender_person,birthday_person,email_user,force_change_user,status_user,img_user,type_user,id_student,major_student,group_student,id_major,name_major,clave_major');
        } else {
          $this->db->select('id_user,name_person,lastname_person,gender_person,birthday_person,email_user,force_change_user,status_user,img_user,type_user');
        }
        $this->db->from('tb_users');
        $this->db->join('tb_people', 'tb_users.id_user = tb_people.id_person');
        if ($student) {
          $this->db->join('tb_students', 'tb_users.id_user = tb_students.id_student');
          $this->db->join('tb_majors', 'tb_students.major_student = tb_majors.id_major');
        }
        $query      = $this->db->get("");
        $user_exist = $query->row();
        return array(
          "status" => "success",
          "data" => $user_exist,
        );
      } else {
        return array(
          "status" => "error",
          "message" => "Data does not match",
        );
      }
    } else {
      return array(
        "status" => "error",
        "message" => "User does not exist",
      );
    }
  }

  function saveAndEditDats($entityName, $data, $filter = array())
  {
    if ($filter) {
      $this->db->where($filter);
      $this->db->update($entityName, $data);
    } else {
      $this->db->insert($entityName, $data);
    }
    if ($this->db->error()["message"] != "") {
      return array(
        "status" => "error",
        "message" => $this->db->error()["message"],
      );
    } else {
      return array(
        "status" => "success",
        "message" => "Query successful",
      );
    }
  }



  function queryEntity($entityName, $filter = array(), $unique = FALSE)
  {
    if ($filter) {
      $this->db->where($filter);
    }
    $query = $this->db->get($entityName);
    if ($unique) {
      if ($this->db->error()['message'] != "") {
        return array(
          "status" => "error",
          "message" => $this->db->error()['message'],
        );
      } else {
        return $query->row();
      }
    } else {
      return $query->result();
    }
  }

  function trans_begin()
  {
    $this->db->trans_begin();
  }

  function trans_end()
  {
    $complete = FALSE;
    if ($this->db->trans_status()) {
      $complete = TRUE;
      $this->db->trans_commit();
    } else {
      $this->db->trans_rollback();
    }
    return $complete;
  }

  function obtain_id()
  {
    return $this->db->insert_id();
  }

  function getMajors($filter = array(), $unique = FALSE)
  {
    $this->db->select('*');
    if ($filter) {
      $this->db->where($filter);
    }
    $this->db->from('tb_majors');
    $this->db->join('tb_people', 'tb_majors.cordi_major = tb_people.id_person');
    $query = $this->db->get("");
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }


  function getCordination($filter, $unique = FALSE)
  {
    if ($filter) {
      $this->db->where($filter);
    }
    $this->db->select('id_user,name_person,lastname_person');
    $this->db->from('tb_users');
    $this->db->join('tb_people', 'tb_users.id_user = tb_people.id_person');
    $query = $this->db->get("");
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }

  function profesorsTable($filter = array(), $unique = FALSE)
  {
    $this->db->where($filter);
    $this->db->select('id_person,IDUser,name_person,lastname_person,gender_person,birthday_person,email_user,force_change_user,status_user,id_user');
    $this->db->from('tb_users');
    $this->db->join('tb_people', 'tb_users.id_user = tb_people.id_person');
    $query = $this->db->get("");
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }

  function StudentsTable($filter = array(), $unique = FALSE)
  {
    $this->db->where($filter);
    $this->db->select('IDUser,id_person,name_person,lastname_person,gender_person,birthday_person,email_user,force_change_user,status_user,id_user,id_student,major_student,group_student,id_major,name_major,clave_major,cordi_major,id_group,clave_group,major_group,status_group');
    $this->db->from('tb_users');
    $this->db->join('tb_people', 'tb_users.id_user = tb_people.id_person');
    $this->db->join('tb_students', 'tb_users.id_user = tb_students.id_student');
    $this->db->join('tb_majors', 'tb_students.major_student = tb_majors.id_major');
    $this->db->join('tb_groups', 'tb_students.group_student = tb_groups.id_group');
    $this->db->order_by('IDUser');
    $this->db->group_by('IDUser');
    $this->db->distinct();
    $query = $this->db->get("");
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }

  function studentsDetails($filter = array())
  {
    if ($filter) {
      $this->db->where($filter);
    }
    $this->db->select('IDUser,id_person,name_person,lastname_person,gender_person,birthday_person,email_user,force_change_user,status_user,major_student');
    $this->db->from('tb_users');
    $this->db->join('tb_people', 'tb_users.id_user = tb_people.id_person');
    $this->db->join('tb_students', 'tb_users.id_user = tb_students.id_student');
    $query = $this->db->get("");
    $user  = $query->row();
    if ($user) {
      return $user;
    } else if ($this->db->error()['message'] != "") {
      return array(
        "status" => "error",
        "message" => $this->db->error()['message'],
      );
    } else {
      return array();
    }
  }

  function getAdvertisements($filter = array(), $unique = FALSE)
  {
    $this->db->select('id_user,img_user,email_user,id_person,name_person,lastname_person,id_advertisement,title_advertisement,content_advertisement,img_path_advertisement,vid_path_advertisement,doc_path_advertisement,show_to_advertisement,status_advertisement,fk_user_advertisement,creation_date_advertisement,finish_date_advertisement');
    if ($filter) {
      $this->db->where($filter);
    }
    $this->db->from('tb_advertisements');
    $this->db->join('tb_users', 'tb_advertisements.fk_user_advertisement = tb_users.id_user');
    $this->db->join('tb_people', 'tb_advertisements.fk_user_advertisement = tb_people.id_person');
    $this->db->order_by('creation_date_advertisement', 'DESC');
    $query = $this->db->get();
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }

  function commentPosts($filter = array())
  {
    $this->db->select('id_person,name_person,lastname_person,id_user,img_user,type_user,id_comment,content_comment,status_comment,fk_user_comment,update_date_comment,id_ad_comment,fk_ad,fk_comment');
    $this->db->where($filter);
    $this->db->from('tb_advertisements_comments');
    $this->db->join('tb_comments', 'tb_advertisements_comments.fk_comment = tb_comments.id_comment');
    $this->db->join('tb_users', 'tb_comments.fk_user_comment = tb_users.id_user');
    $this->db->join('tb_people', 'tb_users.id_user = tb_people.id_person');
    $this->db->order_by('update_date_comment', 'DESC');
    $query = $this->db->get();
    return $query->result();
  }

  function getPeriods($filter, $unique = FALSE, $justPeriod = FALSE)
  {
    $this->db->select('*');
    if ($filter) {
      $this->db->where($filter);
    }
    if ($justPeriod) {
      $this->db->from('tb_periods');
    } else {
      $this->db->from('tb_periods_major');
      $this->db->join('tb_periods', 'tb_periods_major.fk_period = tb_periods.id_period');
      $this->db->join('tb_majors', 'tb_periods_major.fk_major = tb_majors.id_major');
      $this->db->order_by('end_date_period', 'DESC');
    }
    $query = $this->db->get();
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }

  function count($table, $filter = array())
  {
    $this->db->where($filter);
    return $this->db->count_all_results($table);
  }

  function getGPS($filter = array(), $unique = FALSE)
  {
    $this->db->select('*');
    if ($filter) {
      $this->db->where($filter);
    }
    $this->db->from('tb_periods_groups');
    $this->db->join('tb_groups', 'tb_groups.id_group = tb_periods_groups.fk_group');
    $this->db->join('tb_periods', 'tb_periods.id_period = tb_periods_groups.fk_period');
    $this->db->group_by("id_group");
    $query = $this->db->get();
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }

  function getClasses($filter, $unique = FALSE)
  {
    $this->db->select('*');
    if ($filter) {
      $this->db->where($filter);
    }
    $this->db->from('tb_group_Pro_Class');
    $this->db->join('tb_classes', 'tb_group_Pro_Class.fk_class = tb_classes.id_class');
    $this->db->join('tb_people', 'tb_group_Pro_Class.fk_profesor = tb_people.id_person');
    $this->db->join('tb_periods', 'tb_group_Pro_Class.fk_period = tb_periods.id_period');
    $this->db->join('tb_groups', 'tb_group_Pro_Class.fk_group = tb_groups.id_group');
    $this->db->join('tb_classrooms', 'tb_classes.lab_class = tb_classrooms.id_classroom');
    $query = $this->db->get();
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }

  function getSchedules($filter, $unique = FALSE)
  {
    $this->db->select('*');
    if ($filter) {
      $this->db->where($filter);
    }
    $this->db->from('tb_schedules');
    $this->db->join('tb_periods', 'tb_periods.id_period = tb_schedules.fk_period');
    $this->db->join('tb_classes', 'tb_classes.id_class = tb_schedules.fk_class');
    $query = $this->db->get();
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }

  function getClsWorks($filter, $unique = FALSE)
  {
    $this->db->select('*');
    if ($filter) {
      $this->db->where($filter);
    }
    $this->db->from('tb_classworks');
    $this->db->join('tb_periods', 'tb_periods.id_period = tb_schedules.fk_period');
    $this->db->join('tb_classes', 'tb_classes.id_class = tb_schedules.fk_class');
    $query = $this->db->get();
    if ($unique) {
      return $query->row();
    } else {
      return $query->result();
    }
  }

  function clsWrkStd($filter = array(), $unique = FALSE)
  {
    $this->db->select('*');

    if ($filter) {
      $this->db->where($filter);
    }
    $this->db->from('tb_std_classworks');
    $this->db->join('tb_students', 'tb_std_classworks.fk_student = tb_students.id_student');
    $this->db->join('tb_people', 'tb_students.id_student = tb_people.id_person');
    $this->db->join('tb_classworks', 'tb_std_classworks.fk_classwork = tb_classworks.id_classwork');
    $this->db->order_by("deliver_classwork", "DESC");
    $query = $this->db->get();
    if ($unique) {
      $this->db->limit(1);
      if ($this->db->error()['message'] != "") {
        return array(
          "status" => "error",
          "message" => $this->db->error()['message'],
        );
      } else {
        return $query->row();
      }
    } else {
      return $query->result();
    }
  }

  function commentclsWrk($filter = array())
  {
    $this->db->select('*');
    $this->db->where($filter);
    $this->db->from('tb_classwork_comments');
    $this->db->join('tb_std_classworks', 'tb_classwork_comments.fk_cw = tb_std_classworks.id_std_classwork');
    $this->db->join('tb_comments', 'tb_classwork_comments.fk_comment = tb_comments.id_comment');
    $this->db->order_by('update_date_comment', 'DESC');
    $query = $this->db->get();
    return $query->result();
  }

  function studentsclsWrk($filter = array())
  {
    $this->db->select('*');
    $this->db->where($filter);
    $this->db->from('tb_std_classworks');
    $this->db->join('tb_classworks', 'tb_std_classworks.fk_classwork = tb_classworks.id_classwork');
    $query = $this->db->get();
    return $query->result();
  }
}