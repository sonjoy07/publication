<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author MD. Mashfiq
 */
//define('DASHBOARD', "$baseurl");
class Admin extends CI_Controller {

    //put your code here
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

    function index() {
        redirect('admin/memo_management');
    }

    function dashboard() {
        $this->load->model('account/account');
        $data['account_today'] = $this->account->today();
        $data['account_monthly'] = $this->account->monthly();
        $data['total'] = $this->account->total();
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['Title'] = 'Dashboard';
        $data['base_url'] = base_url();
        $this->load->view($this->config->item('ADMIN_THEME') . 'dashboard', $data);
    }

    function account($cmd = false) {
        $this->load->model('Memo');
        $this->load->library('session');
        $this->load->model('account/account');


        $data['date_range'] = $this->input->post('date_range');
        if ($data['date_range'] != '') {
            $this->session->set_userdata('date_range', $data['date_range']);
        }
        if ($cmd == 'reset_date_range') {
            $this->session->unset_userdata('date_range');
            redirect("admin/account");
        }
        if ($this->session->userdata('date_range') != '') {
            $range = $this->Memo->dateformatter($this->session->userdata('date_range'));
            $data['date_range'] = $this->session->userdata('date_range');
        }
        if (isset($range)) {
            $data['today_detail_table'] = $this->account->today_detail_table($range);
        }





        $data['account_today'] = $this->account->today();
        $data['account_monthly'] = $this->account->monthly();
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['Title'] = 'Account Information';
        $data['today_monthly_account_detail_table'] = $this->account->today_monthly_account_detail_table();
        $data['total_account_detail_table'] = $this->account->total_account_detail_table();



        $data['base_url'] = base_url();
        $this->load->view($this->config->item('ADMIN_THEME') . 'account', $data);
    }

    function report_sold_book_today($cmd = false) {

        $this->load->library('session');

//        basic info initialization
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['Title'] = 'Roport';
        $data['base_url'] = base_url();

//        Getting main content
        $this->load->model('Report');
        //$data['main_content'] = $this->Report->sold_book_today();


        $data['date_range'] = $this->input->post('date_range');
        if ($data['date_range'] != '') {
            $this->session->set_userdata('date_range', $data['date_range']);
        }
        if ($cmd == 'reset_date_range') {
            $this->session->unset_userdata('date_range');
            redirect("admin/report_sold_book_today");
        }
        if ($this->session->userdata('date_range') != '') {
            $range = $this->Report->dateformatter($this->session->userdata('date_range'));
            $data['date_range'] = $this->session->userdata('date_range');
        }
        if (isset($range)) {
            $data['main_content'] = $this->Report->sold_book_today($range);
        } else {

            $data['main_content'] = $this->Report->sold_book_today();
        }


        $this->load->view($this->config->item('ADMIN_THEME') . 'page-book-sold-quantity', $data);
    }

    function manage_book() {
        $crud = new grocery_CRUD();
        $crud->set_table('pub_books')->set_subject('Book')->order_by('book_ID', 'desc')->display_as('price', 'Sales Price');
        $crud->callback_add_field('catagory', function () {
            return form_dropdown('catagory', $this->config->item('book_categories'), '0');
        });
        $crud->callback_column('name', function ($value, $row) {
            return $row->name;
        });
        $crud->callback_add_field('storing_place', function () {
            return form_dropdown('storing_place', $this->config->item('storing_place'));
        });
        $output = $crud->render();
        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Manage Book';
        $this->load->view($this->config->item('ADMIN_THEME') . 'manage_book', $data);
    }

    function cost($cmd = false) {
        $this->load->library('session');
        $db_tables = $this->config->item('db_tables');
        $this->load->model('Office_cost'); //load 
        $this->load->model('Report');





        $crud = new grocery_CRUD();
        $crud->set_table($db_tables['pub_cost'])
                ->set_subject('Cost')
                ->order_by('cost_ID', 'desc');


        $data['scriptInline'] = ""
                . "<script>"
                . "var CurrentDate = '" . date("m/d/Y h:i:s a") . "';"
                . "</script>\n"
                . '<script type="text/javascript" src="' . base_url() . $this->config->item('ASSET_FOLDER') . 'js/Custom-main.js"></script>';

        $data['date_range'] = $this->input->post('date_range');
        if ($data['date_range'] != '') {
            $this->session->set_userdata('date_range', $data['date_range']);
        }
        if ($cmd == 'reset_date_range') {
            $this->session->unset_userdata('date_range');
            redirect("admin/cost");
        }
        if ($this->session->userdata('date_range') != '') {
            $range = $this->Report->dateformatter($this->session->userdata('date_range'));
            $data['date_range'] = $this->session->userdata('date_range');
        }
        if (isset($range)) {
            $data['main_content'] = $this->Office_cost->search_result($range);
        } else {
            $output = $crud->render();
            $data['glosary'] = $output;
        }



        $data['today_office_cost'] = $this->Common->taka_format($this->Office_cost->today_office_cost());

        $data['monthly_office_cost'] = $this->Common->taka_format($this->Office_cost->monthly_office_cost());

        $data['previous_month_office_cost'] = $this->Common->taka_format($this->Office_cost->previous_month_office_cost());


        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Manage Cost';
        $this->load->view($this->config->item('ADMIN_THEME') . 'manage_cost', $data);
    }

    function stock_transfer_log() {
        $this->load->library('session');
        $this->load->model('Stock_manages'); //load 

        $data['book_dropdown'] = $this->Stock_manages->book_dropdown();
        $data['transfer_log_From_dropdown'] = $this->Stock_manages->transfer_log_From_dropdown();
        $data['transfer_log_to_dropdown'] = $this->Stock_manages->transfer_log_to_dropdown();

        if ($this->input->post('submit_single')) {
            $post['book_name'] = $this->input->post('book_name');
            $post['from_contact_id'] = $this->input->post('from_contact_id');
            $post['to_contact_id'] = $this->input->post('to_contact_id');
            $post['date_range'] = $this->input->post('date_range');

            $data['date_range'] = $post['date_range'];

            if (!empty($post)) {
                //$filter_session=$this->session->set_userdata('transfer_filter',$post);

                $data['transfer_log_table'] = $this->Stock_manages->result_stock_table($post);
            }
        }


        if ($this->input->post('submit_sum')) {
            $post['book_name'] = $this->input->post('book_name');
            $post['from_contact_id'] = $this->input->post('from_contact_id');
            $post['to_contact_id'] = $this->input->post('to_contact_id');
            $post['date_range'] = $this->input->post('date_range');
            $data['date_range'] = $post['date_range'];

            if (!empty($post)) {
                //$filter_session=$this->session->set_userdata('transfer_filter',$post);

                $data['transfer_log_table'] = $this->Stock_manages->result_stock_table_total($post);
                $data['script'] = '<script>$("th:nth-child(5)").hide();$("td:nth-child(5)").hide();</script>';
            }
        }


        $crud = new grocery_CRUD();
        $crud->display_as('book_ID', 'Book Name')
                ->display_as('form_cotact_ID', 'From ')
                ->display_as('to_contact_ID', 'To');
        $crud->set_table('pub_stock_transfer_log')->set_subject('Stock Transfer Log')->order_by('stock_process_step_ID', 'desc');

        $crud->set_relation('form_cotact_ID', 'pub_contacts', 'name')
                ->set_relation('to_contact_ID', 'pub_contacts', 'name')
                ->set_relation('book_ID', 'pub_books', 'name');
        $crud->unset_add()->unset_edit()->unset_delete();

        $output = $crud->render();
        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Stock Transfer Log';
        $this->load->view($this->config->item('ADMIN_THEME') . 'stock_transfer_log', $data);
    }

    function manage_contact() {
        $crud = new grocery_CRUD();
        $crud->columns(
                'contact_ID', 'name', 'contact_type', 'division', 'district', 'upazila', 'address', 'phone'
        );
        $crud->display_as('contact_ID', 'Contact code');
        $crud->set_table('pub_contacts')->set_subject('Contact')->order_by('contact_ID', 'desc');
        $crud->callback_add_field('contact_type', function () {
            return form_dropdown('contact_type', $this->config->item('contact_type'));
        })->callback_edit_field('contact_type', function ($value, $primary_key) {
            return form_dropdown('contact_type', $this->config->item('contact_type'), $value);
        });
        $crud->callback_add_field('division', function () {
            return form_dropdown('division', $this->config->item('division'));
        })->callback_edit_field('division', function ($value, $primary_key) {
            return form_dropdown('division', $this->config->item('division'), $value);
        });
        $crud->callback_add_field('district', function () {
            return form_dropdown('district', $this->config->item('districts_english'), '', 'class="form-control select2 dropdown-width" ');
        })->callback_edit_field('district', function ($value, $primary_key) {
            return form_dropdown('district', $this->config->item('districts_english'), $value);
        });

        $crud->callback_add_field('upazila', function () {
            return form_dropdown('upazila', $this->config->item('upazila_english'), '', 'class="form-control select2 dropdown-width" ');
        })->callback_edit_field('upazila', function ($value, $primary_key) {
            return form_dropdown('upazila', $this->config->item('upazila_english'), $value);
        });


        $crud->callback_add_field('subject', function () {
            return form_dropdown('subject', $this->config->item('teacher_subject'), '', 'class="form-control select2 dropdown-width" ');
        })->callback_edit_field('subject', function ($value, $primary_key) {
            return form_dropdown('subject', $this->config->item('teacher_subject'), $value);
        });

        $output = $crud->render();
        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'General Contact';
        $this->load->view($this->config->item('ADMIN_THEME') . 'manage_contact', $data);
    }

    function manage_contact_teacher($cmd = FALSE) {
        $this->load->model('Contact');

        $crud = new grocery_CRUD();

        $crud->set_table('pub_contacts_teacher')
                ->set_subject('Teacher Contact')
                ->display_as('teacher_name', "Teacher Name")
                ->display_as('designation', "Designation")
                ->display_as('institute_name', "Institute Name")
                ->order_by('teacher_ID', 'desc');

        $crud->callback_add_field('division', function () {
            return form_dropdown('division', $this->config->item('division'));
        })->callback_edit_field('division', function ($value, $primary_key) {
            return form_dropdown('division', $this->config->item('division'), $value);
        });
        $crud->callback_add_field('district', function () {
            return form_dropdown('district', $this->config->item('districts_english'), '', 'class="form-control select2 dropdown-width" ');
        })->callback_edit_field('district', function ($value, $primary_key) {
            return form_dropdown('district', $this->config->item('districts_english'), $value);
        });

        $crud->callback_add_field('upazila', function () {
            return form_dropdown('upazila', $this->config->item('upazila_english'), '', 'class="form-control select2 dropdown-width" ');
        })->callback_edit_field('upazila', function ($value, $primary_key) {
            return form_dropdown('upazila', $this->config->item('upazila_english'), $value);
        });


        $crud->callback_add_field('subject', function () {
            return form_dropdown('subject', $this->config->item('teacher_subject'), '', 'class="form-control select2 dropdown-width" ');
        })->callback_edit_field('subject', function ($value, $primary_key) {
            return form_dropdown('subject', $this->config->item('teacher_subject'), $value);
        });

        $crud = $this->Contact->set_filter($crud, $cmd);
        $data['filter_elements'] = $this->Contact->filter_elements();
        if (current_url() == site_url('admin/manage_contact_teacher') || $cmd == "success" || $cmd == "update") {
            $data['filter_form_enabled'] = TRUE;
        }

        $output = $crud->render();
        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Teacher Contact';
        $this->load->view($this->config->item('ADMIN_THEME') . 'manage_contact', $data);
    }

    function book_return($cmd = false) {
        $this->load->library('session');
        $this->load->model('Stock_manages');
        $crud = new grocery_CRUD();
        $crud->set_table('pub_books_return')->set_subject('Returned Book')
                ->display_as('contact_ID', 'Party Name')
                ->display_as('book_ID', 'Book')
                ->display_as('issue_date', 'Issue Date')->order_by('issue_date', 'desc');

        $crud->set_relation('contact_ID', 'pub_contacts', 'name')
                ->set_relation('book_ID', 'pub_books', 'name')
                ->unset_add();

//        date range---------------------------

        $data['date_range'] = $this->input->post('date_range');
        if ($data['date_range'] != '') {
            $this->session->set_userdata('date_range', $data['date_range']);
        }
        if ($cmd == 'reset_date_range') {
            $this->session->unset_userdata('date_range');
            redirect("admin/book_return");
        }
        if ($this->session->userdata('date_range') != '') {
            $range = $this->Stock_manages->dateformatter($this->session->userdata('date_range'));
            $data['date_range'] = $this->session->userdata('date_range');
        }

//        end date range----------------------
//        if(isset($range)){
//            $data['total_return_book_price']=$this->session->userdata('total_book_return_value');
//            $this->session->unset_userdata('total_book_return_value');
//        }
        //$crud->callback_after_insert(array($this->Stock_manages, 'marge_insert_book'));
        $output = $crud->render();

        $data['scriptInline'] = "<script>"
                . "var CurrentDate = '" . date("m/d/Y") . "';"
                . "var webServiceUrlTotal_book_return = '" . site_url("admin/total_book_return/") . "/';"
                . "</script>"
                . '<script type="text/javascript" src="' . base_url() . $this->config->item('ASSET_FOLDER') . 'js/Custom-book_return.js"></script>';
        $data['contact_dropdown'] = $this->Stock_manages->get_due_holder_dropdown();

        $data['glosary'] = $output;

        $data['total_book_return_section'] = true;
        $data['return_book_page'] = true;

        $data['book_returned_dropdown'] = $this->Stock_manages->get_book_returned_dropdown();


        if (isset($range)) {
            $data['main_content'] = $this->Stock_manages->total_book_returned($range);
            $data['total_return_book_price'] = $this->Stock_manages->total_return_book_price($range);
            // $data['total_book_returned'] = $this->Stock_manages->total_book_returned($range);
        } else {
            $data['total_book_returned'] = $this->Stock_manages->total_book_returned();
        }

        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Book Return';
        $this->load->view($this->config->item('ADMIN_THEME') . 'book_return', $data);
    }

    function return_book_dashboard() {

        $this->load->model('Stock_manages');

        $data['total_book_returned'] = $this->Stock_manages->total_book_returned();
        $data['total_book_send'] = $this->Stock_manages->total_book_send();

        $data['remining_book'] = $data['total_book_returned'] - $data['total_book_send'];

        $data['report'] = $this->Stock_manages->difference_between_return_send_book();

        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Return/Send Book Dashboard';
        $this->load->view($this->config->item('ADMIN_THEME') . 'return_book_dashboard', $data);
    }

    function send_book_rebind($cmd = false) {
        $this->load->library('session');
        $this->load->model('Stock_manages');
        $crud = new grocery_CRUD();
        $crud->set_table('pub_send_to_rebind')->set_subject('Send Book To Rebind')
                ->display_as('contact_ID', 'Binder Name')
                ->display_as('book_ID', 'Book')
                ->display_as('issue_date', 'Issue Date')->order_by('issue_date', 'desc');

        $crud->set_relation('contact_ID', 'pub_contacts', 'name')
                ->set_relation('book_ID', 'pub_books', 'name')
                ->unset_add();

//        date range---------------------------

        $data['date_range'] = $this->input->post('date_range');
        if ($data['date_range'] != '') {
            $this->session->set_userdata('date_range', $data['date_range']);
        }
        if ($cmd == 'reset_date_range') {
            $this->session->unset_userdata('date_range');
            redirect("admin/send_book_rebind");
        }
        if ($this->session->userdata('date_range') != '') {
            $range = $this->Stock_manages->dateformatter($this->session->userdata('date_range'));
            $data['date_range'] = $this->session->userdata('date_range');
        }

//        end date range----------------------
//        if(isset($range)){
//            $data['total_return_book_price']=$this->session->userdata('total_book_return_value');
//            $this->session->unset_userdata('total_book_return_value');
//        }
        //$crud->callback_after_insert(array($this->Stock_manages, 'marge_insert_book'));
        $output = $crud->render();

        $data['scriptInline'] = "<script>"
                . "var CurrentDate = '" . date("m/d/Y") . "';"
                . "var webServiceUrlTotal_book_return = '" . site_url("admin/total_book_send/") . "/';"
                . "</script>"
                . '<script type="text/javascript" src="' . base_url() . $this->config->item('ASSET_FOLDER') . 'js/Custom-book_return.js"></script>';
        $data['contact_dropdown'] = $this->Stock_manages->get_due_holder_dropdown();

        $data['glosary'] = $output;

        $data['total_book_return_section'] = true;
        $data['return_book_page'] = true;

        $data['book_send_dropdown'] = $this->Stock_manages->get_book_send_dropdown();


        if (isset($range)) {
            $data['main_content'] = $this->Stock_manages->total_book_send($range);
            //$data['total_book_send_price']=$this->Stock_manages->total_book_send($range);
            // $data['total_book_returned'] = $this->Stock_manages->total_book_returned($range);
        } else {
            $data['total_book_send'] = $this->Stock_manages->total_book_send();
        }

        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Book Send to Re-binding';
        $this->load->view($this->config->item('ADMIN_THEME') . 'send_to_rebind', $data);
    }

    function total_book_send($book_ID) {
        $data = $this->db->select('sum(quantity)')
                ->from('pub_send_to_rebind')
                ->where('book_ID', $book_ID)
                ->get()
                ->result_array();
        echo $data[0]['sum(quantity)'];
    }

    function total_book_return($book_ID) {
        $data = $this->db->select('sum(quantity)')
                ->from('pub_books_return')
                ->where('book_ID', $book_ID)
                ->get()
                ->result_array();
        echo $data[0]['sum(quantity)'];
    }

    function print_last_memo() {
        $this->db->select('LAST_INSERT_ID(`memo_ID`)');
//        $this->db->select('LAST_INSERT_ID()');
//        $this->db->insert_id('memo_ID');
        $this->db->from('pub_memos');
        $data = $this->db->get()->result_array();
//        print_r($data);
//        echo sizeof($data) - 1;
        $last_inserted_memo_id = $data[sizeof($data) - 1]['LAST_INSERT_ID(`memo_ID`)'];
        redirect('admin/memo/' . $last_inserted_memo_id);
    }

    function add_stock($process = false) {
//         $crud = new grocery_CRUD();
//         $crud->set_table('pub_stock')->set_subject('Stock');
//         $crud->set_relation('book_ID', 'pub_books', 'name');
//         $crud->set_relation('printing_press_ID', 'pub_contacts', 'name');
// //        $crud->set_relation('binding_store_ID', 'pub_contacts', 'name');
// //        $crud->set_relation('sales_store_ID', 'pub_contacts', 'name');
//         $output = $crud->render();
//         $data['glosary'] = $output;
        //  'admin/ass_stock/true' aso ?

        $this->load->model('stock_manages');

        if ($process) {
            $book_id = $this->input->post('book_id');
            $printingpress_id = $this->input->post('printingpress_id');
            $quantity = $this->input->post('quantity');
            $this->stock_manages->append_new_stock($book_id, $printingpress_id, $quantity);
            redirect('admin/manage_stocks');
        }


        $data['bookname'] = $this->stock_manages->get_bookid_dropdown();
        $data['printingpress'] = $this->stock_manages->get_printingpress_dropdown();

        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Manage Book';
        $this->load->view($this->config->item('ADMIN_THEME') . 'stock_manage', $data);
    }

    function transfer_stock() {
        $this->load->model('Stock_manages');
        $this->Stock_manages->transfer_stock();
        redirect('admin/manage_stocks');
    }

    function manage_stocks($transfer = false) {
        $this->load->model('Stock_manages');

        $crud = new grocery_CRUD();
        $crud->set_table('pub_stock')->set_subject('Stock');
        $crud->set_relation('book_ID', 'pub_books', 'name');
        $crud->set_relation('printing_press_ID', 'pub_contacts', 'name');
//        $crud->set_relation('binding_store_ID', 'pub_contacts', 'name');
//
//        $crud->set_relation('sales_store_ID', 'pub_contacts', 'name');
        $output = $crud->render();
        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Manage Stock';

        $data['scriptInline'] = '<script>
            jQuery(\'[data-StockId]\').click(function () {
                var stock_id = $(this).attr("data-StockId");
                var maxQuantity = $(this).attr("data-maxQuantity");
                //console.log(stock_id);
                jQuery(\'[name="stock_id_from"]\').val(stock_id);
                jQuery(\'[name="Quantity"]\').attr("max",maxQuantity);
            });
        </script>';
        $data['transfer_from_contact_dropdown'] = $this->Stock_manages->get_contact_dropdown();


        $data['printing_table'] = $this->Stock_manages->get_stock_table();
        $data['binding_table'] = $this->Stock_manages->get_stock_table('Binding Store');
        $data['store_table'] = $this->Stock_manages->get_stock_table('Sales Store');
        $this->load->view($this->config->item('ADMIN_THEME') . 'manage_stock', $data);
    }

    function manage_employee() {
        $crud = new grocery_CRUD();
        $crud->set_table('employee')
                ->set_subject('Employee');
        $output = $crud->render();
        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Manage Employee';
        $this->load->view($this->config->item('ADMIN_THEME') . 'manage_employee', $data);
    }

    function test() {
        $a_date = "12/07/2016";
        die(date('Y-m-d', strtotime($a_date)));
        $this->load->model('account/Account');
        echo $this->Account->today_due() . "\n<br>\n";
        echo $this->Account->monthly_due() . "\n<br>\n";

        $this->load->model('Report');
        echo $this->Report->due_remaining_table();
    }

    function memo($memo_id) {
        $this->load->model('Memo');
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['Title'] = 'Memo Generation';
        $data['base_url'] = base_url();
        $data['Book_selection_table'] = $this->Memo->memogenerat($memo_id);
        $data['edit_btn_url'] = site_url('admin/memo_management/edit/' . $memo_id);

        $this->load->view($this->config->item('ADMIN_THEME') . 'memo', $data);
    }

    function memo_management($cmd = false, $primary_id = false) {
        $this->load->model('Memo');
        $this->load->library('session');

        $crud = new grocery_CRUD();
        $crud->set_table('pub_memos')
                ->set_subject('Memo')
                ->display_as('contact_ID', 'Party Name')
                ->display_as('issue_date', 'Issue Date (mm/dd/yyyy)')
                ->display_as('bank_info', 'DD/TT/Cheque')
                ->display_as('bank_pay', 'Bank Collection')->order_by('memo_ID', 'desc')
                ->required_fields('contact_ID', 'issue_date');
        $crud->set_relation('contact_ID', 'pub_contacts', 'name');
        $crud->unset_add_fields('memo_serial');
        $crud->Set_save_and_print(TRUE);
        $crud->unset_back_to_list();
        $crud->unset_delete();
        if ($primary_id) {
            if (!in_array($primary_id, $this->Memo->last_memo_ID_of_each_contact_ID())) {
                if ($cmd == 'edit') {
                    die("<script>"
                            . "alert(' আপনি এই মেমোটি এডিট করতে পারবেন না । প্রয়োজনে এই  ক্রেতার সর্বশেষ  মেমোটি এডিট  করুন । ধন্যবাদ ।   ');"
                            . "window.location.assign( '" . site_url('admin/memo_management') . "');"
                            . "</script>");
                    $crud->unset_edit();
                }
                if ($cmd == 'delete') {
                    die("<script>"
                            . "alert('আপনি এই মেমোটি ডিলিট করতে পারবেন না । প্রয়োজনে এই  ক্রেতার সর্বশেষ  মেমোটি ডিলিট  করুন । ধন্যবাদ ।  ');"
                            . "window.location.assign( '" . site_url('admin/memo_management') . "');"
                            . "</script>");
                    $crud->unset_delete();
                }
            }
        }

        //date range config
        $data['date_range'] = $this->input->post('date_range');
        if ($data['date_range'] != '') {
            $this->session->set_userdata('date_range', $data['date_range']);
        }
        if ($cmd == 'reset_date_range') {
            $this->session->unset_userdata('date_range');
            redirect("admin/memo_management");
        }
        if ($this->session->userdata('date_range') != '') {
            $crud->where("DATE(issue_date) BETWEEN " . $this->Memo->dateformatter($this->session->userdata('date_range')));
            $data['date_range'] = $this->session->userdata('date_range');
        }

        $crud->callback_edit_field('memo_serial', function ($value, $primary_key) {
            $unique_id = $value;
            return '<label>' . $unique_id . '</label><input type="hidden" maxlength="50" value="' . $unique_id . '" name="memo_serial" >';
        });
        $crud->callback_add_field('sub_total', array($this->Memo, 'add_book_selector_table'))
                ->callback_edit_field('sub_total', array($this->Memo, 'edit_book_selector_table'))
                ->callback_after_insert(array($this->Memo, 'after_adding_memo'))
                ->callback_after_update(array($this->Memo, 'after_editing_memo'))
                ->callback_after_delete(array($this->Memo, 'after_deleting_memo'))
                ->add_action('Print', '', site_url('admin/memo/1'), 'fa fa-print', function ($primary_key, $row) {
                    return site_url('admin/memo/' . $row->memo_ID);
                });

        $crud->callback_column('sub_total', function ($value, $row) {
            return $this->Common->taka_format($row->sub_total);
        })->callback_column('discount', function ($value, $row) {
            return $this->Common->taka_format($row->discount);
        })->callback_column('book_return', function ($value, $row) {
            return $this->Common->taka_format($row->book_return);
        })->callback_column('dues_unpaid', function ($value, $row) {
            return $this->Common->taka_format($row->dues_unpaid);
        })->callback_column('total', function ($value, $row) {
            return $this->Common->taka_format($row->total);
        })->callback_column('cash', function ($value, $row) {
            return $this->Common->taka_format($row->cash);
        })->callback_column('bank_pay', function ($value, $row) {
            return $this->Common->taka_format($row->bank_pay);
        })->callback_column('due', function ($value, $row) {
            return $this->Common->taka_format($row->due);
        });

        $addContactButtonContent = anchor('admin/manage_contact/add', '<i class="fa fa-plus-circle"></i> Add New Contact', 'class="btn btn-default" style="margin-left: 15px;"');
        $data['scriptInline'] = ""
                . "<script>"
                . "var addContactButtonContent = '$addContactButtonContent';\n "
                . "var CurrentDate = '" . date("m/d/Y h:i:s a") . "';"
                . "var previousDueFinderUrl = '" . site_url("admin/previousDue/") . "';"
                . "</script>\n"
                . '<script type="text/javascript" src="' . base_url() . $this->config->item('ASSET_FOLDER') . 'js/Custom-main.js"></script>';
        $output = $crud->render();
//        $this->grocery_crud->set_table('pub_memos')->set_subject('Memo');
//        $output =  $this->grocery_crud->render();
        $data['date_filter'] = $cmd;
        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['Title'] = 'Memo Management';
        $data['base_url'] = base_url();
        $this->load->view($this->config->item('ADMIN_THEME') . 'memo_management', $data);

        $this->Memo->clean_pub_memos_selected_books_db();
    }

    function due_log() {
        $db_tables = $this->config->item('db_tables');
        $crud = new grocery_CRUD();
        $crud->set_table($db_tables['pub_due_log'])
                ->set_subject('Due Log')
                ->display_as('contact_ID', 'Party Name')
                ->order_by('memo_ID', 'desc')
                ->set_relation('contact_ID', 'pub_contacts', 'name')
                ->unset_add()
                ->unset_edit()
                ->unset_delete();
        $output = $crud->render();
        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Due Log';
        $this->load->view($this->config->item('ADMIN_THEME') . 'template-report/due_log', $data);
    }

    function due_payment_ledger() {
        $db_tables = $this->config->item('db_tables');
        $crud = new grocery_CRUD();
        $crud->set_table($db_tables['pub_due_payment_ledger'])
                ->set_subject('Due Log')
                ->display_as('contact_ID', 'Party Name')
                ->order_by('memo_ID', 'desc')
                ->set_relation('contact_ID', 'pub_contacts', 'name')
                ->unset_add()
                ->unset_edit()
                ->unset_delete();
        $output = $crud->render();
        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Due Payment Ledger';
        $this->load->view($this->config->item('ADMIN_THEME') . 'template-report/due_payment_ledger', $data);
    }

    function due_total_report() {
        $this->load->model('Report');

        $data['date_range'] = $this->input->get('date_range');

        $data['due_remaining_table'] = $this->Report->due_remaining_table($data['date_range']);
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['base_url'] = base_url();
        $data['Title'] = 'Total Due Report';
        $data['subTitle'] = 'Showing the due report by party wise';
        $this->load->view($this->config->item('ADMIN_THEME') . 'template-report/due_total_report', $data);
    }

    function due_management($cmd = false) {
        $this->load->model('Stock_manages');
        $this->load->model('Memo');

        $last_memo_ID_of_each_contact_ID = implode(',', $this->Memo->last_memo_ID_of_each_contact_ID());
        if ($last_memo_ID_of_each_contact_ID === '') {
            die("<script>alert('কোন মেমো ডাটাবেজে নেই । দয়া করে মেমো যোগ করুন । ');"
                    . "window.location.assign( '" . site_url('admin/memo_management/add') . "');</script>");
        }

        $crud = new grocery_CRUD();
        $crud->set_table('pub_memos')
                ->set_subject('Memo')
                ->display_as('contact_ID', 'Party Name')
                ->display_as('issue_date', 'Issue Date (mm/dd/yyyy)')
                ->display_as('bank_pay', 'Bank Collection')->order_by('memo_ID', 'desc')
                ->display_as('bank_info', 'DD/TT/Cheque')
                ->unset_add()->unset_edit()->unset_delete()
                ->where('memo_ID in', '(' . $last_memo_ID_of_each_contact_ID . ')', false)
                ->where('due >', '0');
        //date range config
        $data['date_range'] = $this->input->post('date_range');
        if ($data['date_range'] != '') {
            $this->session->set_userdata('date_range', $data['date_range']);
        }
        if ($cmd == 'reset_date_range') {
            $this->session->unset_userdata('date_range');
            redirect("admin/due_management");
        }
        if ($this->session->userdata('date_range') != '') {
            $crud->where("DATE(issue_date) BETWEEN " . $this->Memo->dateformatter($this->session->userdata('date_range')));
            //$crud->where("issue_date BETWEEN " . $this->Memo->dateformatter($this->session->userdata('date_range')));
            $data['date_range'] = $this->session->userdata('date_range');
        }

        $crud->set_relation('contact_ID', 'pub_contacts', 'name');

//        $crud->add_action('Update', '', site_url('admin/memo/'), 'fa fa-edit', function ($primary_key, $row) {
//            return site_url('admin/memo_management/edit/' . $row->memo_ID);
//        });
        $crud->add_action('Print', '', site_url('admin/memo/'), 'fa fa-print', function ($primary_key, $row) {
            return site_url('admin/memo/' . $row->memo_ID);
        });

        $crud->callback_column('sub_total', function ($value, $row) {
            return $this->Common->taka_format($row->sub_total);
        })->callback_column('discount', function ($value, $row) {
            return $this->Common->taka_format($row->discount);
        })->callback_column('book_return', function ($value, $row) {
            return $this->Common->taka_format($row->book_return);
        })->callback_column('dues_unpaid', function ($value, $row) {
            return $this->Common->taka_format($row->dues_unpaid);
        })->callback_column('total', function ($value, $row) {
            return $this->Common->taka_format($row->total);
        })->callback_column('cash', function ($value, $row) {
            return $this->Common->taka_format($row->cash);
        })->callback_column('bank_pay', function ($value, $row) {
            return $this->Common->taka_format($row->bank_pay);
        })->callback_column('due', function ($value, $row) {
            return $this->Common->taka_format($row->due);
        });

        $output = $crud->render();

        $data['date_filter'] = $cmd;

        $data['glosary'] = $output;
        $data['theme_asset_url'] = base_url() . $this->config->item('THEME_ASSET');
        $data['Title'] = 'Due  Management';
        $data['base_url'] = base_url();
        $this->load->view($this->config->item('ADMIN_THEME') . 'due_management', $data);
    }

    //    Getting the previous due and make other row's due 0
    function previousDue($contact_ID = 2, $memo_ID = FALSE) {
        $db_tables = $this->config->item('db_tables');
        if ($memo_ID) {
            $db_rows = $this->db->select("contact_ID,due")->from($db_tables['pub_memos'])
                            ->where('contact_ID', $contact_ID)
                            ->where('memo_ID <', $memo_ID)
                            ->get()->result_array();
            $previousDue = isset($db_rows[sizeof($db_rows) - 1]['due']) ? $db_rows[sizeof($db_rows) - 1]['due'] : 0;
            echo $previousDue;
        } else {
            $db_rows = $this->db->select("contact_ID,due")->from($db_tables['pub_memos'])
                            ->where('contact_ID', $contact_ID)
                            ->get()->result_array();
            $previousDue = isset($db_rows[sizeof($db_rows) - 1]['due']) ? $db_rows[sizeof($db_rows) - 1]['due'] : 0;
            echo $previousDue;
        }
    }

}
