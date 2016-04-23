<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contact
 *
 * @author MD. Mashfiq
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contact extends CI_Model {

//put your code here
    public function set_filter($grocery_crud = false, $cmd) {
        $teacher_name = $this->input->post('filter_teacher_name');
        $district = $this->input->post('filter_district');
        $division = $this->input->post('filter_division');
        $upazila = $this->input->post('filter_upazila');
        $subject = $this->input->post('filter_subject');
        $institute_name = $this->input->post('filter_institute_name');

        //setting session
        if ($teacher_name) {
            $this->session->set_userdata('contact_teacher_name', $teacher_name);
        }
        if ($district) {
            $this->session->set_userdata('contact_district', $district);
        }
        if ($division) {
            $this->session->set_userdata('contact_division', $division);
        }
        if ($upazila) {
            $this->session->set_userdata('contact_upazila', $upazila);
        }
        if ($subject) {
            $this->session->set_userdata('contact_subject', $subject);
        }
        if ($institute_name) {
            $this->session->set_userdata('contact_institute_name', $institute_name);
        }

        if ($cmd == 'reset_filter') {
            $this->session->unset_userdata('contact_teacher_name');
            $this->session->unset_userdata('contact_district');
            $this->session->unset_userdata('contact_division');
            $this->session->unset_userdata('contact_upazila');
            $this->session->unset_userdata('contact_subject');
            $this->session->unset_userdata('contact_institute_name');
            redirect("admin/manage_contact_teacher");
        }

        if ($this->session->userdata('contact_teacher_name') != '') {
//            die("<script>alert('" . $this->session->userdata('contact_teacher_name') . "');</script>");
            $teacher_name = $this->session->userdata('contact_teacher_name');
            $grocery_crud->where("`teacher_name` LIKE  '%$teacher_name%'");
        }
        if ($this->session->userdata('contact_district') != '') {
            $district = $this->session->userdata('contact_district');
            $grocery_crud->where('district', $district);
        }
        if ($this->session->userdata('contact_division') != '') {
            $division = $this->session->userdata('contact_division');
            $grocery_crud->where('division', $division);
        }
        if ($this->session->userdata('contact_upazila') != '') {
            $upazila = $this->session->userdata('contact_upazila');
            $grocery_crud->where('upazila', $upazila);
        }
        if ($this->session->userdata('contact_subject') != '') {
            $subject = $this->session->userdata('contact_subject');
            $grocery_crud->where('subject', $subject);
        }
        if ($this->session->userdata('contact_institute_name') != '') {
            $institute_name = $this->session->userdata('contact_institute_name');
            $grocery_crud->where("`institute_name` LIKE  '%$institute_name%'");
        }

        return $grocery_crud;
    }

    function filter_elements() {
        $teacher_name = $this->input->post('filter_teacher_name');
        $district = $this->input->post('filter_district');
        $division = $this->input->post('filter_division');
        $upazila = $this->input->post('filter_upazila');
        $subject = $this->input->post('filter_subject');
        $institute_name = $this->input->post('filter_institute_name');
        if ($this->session->userdata('contact_teacher_name') != '') {
            $teacher_name = $this->session->userdata('contact_teacher_name');
        }
        if ($this->session->userdata('contact_district') != '') {
            $district = $this->session->userdata('contact_district');
        }
        if ($this->session->userdata('contact_division') != '') {
            $division = $this->session->userdata('contact_division');
        }
        if ($this->session->userdata('contact_upazila') != '') {
            $upazila = $this->session->userdata('contact_upazila');
        }
        if ($this->session->userdata('contact_subject') != '') {
            $subject = $this->session->userdata('contact_subject');
        }
        if ($this->session->userdata('contact_institute_name') != '') {
            $institute_name = $this->session->userdata('contact_institute_name');
        }

        $filter_elements['input_teacher_name'] = form_input('filter_teacher_name', $teacher_name, 'class="form-control" style="width: 200px;"');
        $filter_elements['input_institute_name'] = form_input('filter_institute_name', $institute_name, 'class="form-control" style="width: 200px;"');

        $filter_elements['dropdown_division'] = form_dropdown('filter_division', $this->config->item('division'), $division, 'class="form-control" style="width: 200px;"');
        $filter_elements['dropdown_district'] = form_dropdown('filter_district', $this->config->item('districts_english'), $district, 'class="form-control" style="width: 200px;"');
        $filter_elements['dropdown_upazila'] = form_dropdown('filter_upazila', $this->config->item('upazila_english'), $upazila, 'class="form-control" style="width: 200px;"');
        $filter_elements['dropdown_subject'] = form_dropdown('filter_subject', $this->config->item('teacher_subject'), $subject, 'class="form-control" style="width: 200px;"');

        return $filter_elements;
    }

}
