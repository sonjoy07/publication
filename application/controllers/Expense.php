<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Accounting
 *
 * @author MD. Mashfiq
 */
class Expense extends CI_Controller {
            
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->library('tank_auth');
        if (!$this->tank_auth->is_logged_in()) {         //not logged in
            redirect('login');
            return 0;
        }
        $this->load->library('grocery_CRUD');
        $this->load->model('Common');
       
    }
    
    function index(){
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Expense';
        
        $this->load->view($this->config->item('ADMIN_THEME').'expense/expense_dashboard', $data);
    }
    
    function expense() {
        $crud = new grocery_CRUD();
        $crud->set_table('expense');
        $output = $crud->render();
        $data['glosary'] = $output;
        
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Expense';
        $this->load->view($this->config->item('ADMIN_THEME') . 'expense/expense', $data);
    }
    
    function expense_name() {
        $crud = new grocery_CRUD();
        $crud->set_table('expense_name');
        $output = $crud->render();
        $data['glosary'] = $output;
        
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Expense Name';
        $this->load->view($this->config->item('ADMIN_THEME') . 'expense/expense_name', $data);
    }
    
    function expense_category() {
        $crud = new grocery_CRUD();
        $crud->set_table('expense_category');
        $output = $crud->render();
        $data['glosary'] = $output;
        
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Expense Category';
        $this->load->view($this->config->item('ADMIN_THEME') . 'expense/expense_category', $data);
    }
    
}
