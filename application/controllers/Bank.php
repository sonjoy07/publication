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
class Bank extends CI_Controller {
            
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
        $data['Title'] = 'Bank Dashboard';
        
        $this->load->view($this->config->item('ADMIN_THEME').'bank/bank_dashboard', $data);
    }
    
     function bank_management() {
        $crud = new grocery_CRUD();
        $crud->set_table('bank_management')
                ->set_relation('id_account', 'bank_account', 'id_bank_account');
        $output = $crud->render();
        $data['glosary'] = $output;
        
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Bank Management';
        $this->load->view($this->config->item('ADMIN_THEME') . 'bank/bank_management', $data);
    }
    
    
    function bank_account() {
        $crud = new grocery_CRUD();
        $crud->set_table('bank_account');
        $output = $crud->render();
        $data['glosary'] = $output;
        
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Bank Account';
        $this->load->view($this->config->item('ADMIN_THEME') . 'bank/bank_account', $data);
    }
    
    function bank_account_type() {
        $crud = new grocery_CRUD();
        $crud->set_table('bank_account_type');
        $output = $crud->render();
        $data['glosary'] = $output;
        
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Bank Account Type';
        $this->load->view($this->config->item('ADMIN_THEME') . 'bank/bank_account_type', $data);
    }
    function bank_balance() {
        $crud = new grocery_CRUD();
        $crud->set_table('bank_balance');
        $output = $crud->render();
        $data['glosary'] = $output;
        
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Bank Balance';
        $this->load->view($this->config->item('ADMIN_THEME') . 'bank/bank_balance', $data);
    }
    function bank_management_status() {
        $crud = new grocery_CRUD();
        $crud->set_table('bank_management_status');
        $output = $crud->render();
        $data['glosary'] = $output;
        
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Bank management status';
        $this->load->view($this->config->item('ADMIN_THEME') . 'bank/bank_management_status', $data);
    }
    
      function bank_transaction_type() {
        $crud = new grocery_CRUD();
        $crud->set_table('bank_transaction_type');
        $output = $crud->render();
        $data['glosary'] = $output;
        
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Bank Transaction Type';
        $this->load->view($this->config->item('ADMIN_THEME') . 'bank/bank_transaction_type', $data);
    }
    
    
}
