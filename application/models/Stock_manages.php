<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stock_manages extends CI_Model {

    public $transfer_query;

    function book_details_by_id($id) {
        $query = $this->db->query("SELECT * FROM pub_books WHERE book_ID='$id'");
        foreach ($query->result_array() as $rowid => $rowdata) {
            $data[$rowid] = $rowdata;
        }
        return $data;
    }

    function contact_details_by_type($id) {
        $query = $this->db->query("SELECT * FROM pub_contacts WHERE contact_ID='$id'");
        foreach ($query->result_array() as $contactsid => $contact) {
            $data[$contactsid] = $contact;
        }
        return $data;
    }

    function find_contactsid_by_type($type1 = '', $type2 = '') {
        $query = $this->db->query("SELECT contact_ID FROM pub_contacts WHERE contact_type=='$type1' && contact_type=='$type2'");
        foreach ($query->result_array() as $contactsid => $contact) {
            $data[$contactsid] = $contact;
        }
        return $data;
    }

    function get_stock_table($contact_type = 'Printing Press') {
        $this->load->library('table');
        $db_tables = $this->config->item('db_tables');
        $this->db->select(
                'stock_id,'
                . $db_tables['pub_books'] . '.name as book_name,'
                . $db_tables['pub_contacts'] . '.name as contact_name,'
//                . 'contact_type,'
                . 'Quantity');
//        $this->db->select('*');
        $this->db->from($db_tables['pub_stock']);
        $this->db->join("{$db_tables['pub_books']}", "{$db_tables['pub_books']}.book_ID = {$db_tables['pub_stock']}.book_ID");
        $this->db->join("{$db_tables['pub_contacts']}", "{$db_tables['pub_contacts']}.contact_ID = {$db_tables['pub_stock']}.printing_press_ID");
        $this->db->where('contact_type', $contact_type)->order_by($db_tables['pub_books'] . '.book_ID', 'ASC');
        $query = $this->db->get();
        $db_rows = $query->result_array();

//        setting table settings

        $this->table->set_heading('#', 'Book Name', 'Store Name', 'Quantity', 'Action');
        //Setting table template
        $tmpl = array(
            'table_open' => '<table class="table table-bordered">',
            'heading_cell_start' => '<th class="bg-primary" style="vertical-align: top;">'
        );
        $this->table->set_template($tmpl);
        $table_rows = $db_rows;
        foreach ($db_rows as $index => $row) {
            $table_rows[$index]['stock_id'] = $index + 1;
            $table_rows[$index]['action'] = '<button id="buttonTransfer" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-StockId="' . $db_rows[$index]['stock_id'] . '" data-maxQuantity="' . $db_rows[$index]['Quantity'] . '" data-target="#myModal"><span class="glyphicon glyphicon-transfer"></span></button>';
        }

        return $this->table->generate($table_rows);
    }

    function append_new_stock($book_id, $printingpress_id, $quantity) {
        $destination_stock_details = $this->get_stock_details_by($book_id, $printingpress_id);

        // checking if we have a blank destination stock
        $destination_stock_details = (sizeof($destination_stock_details) < 1) ? false : $destination_stock_details;

        if ($destination_stock_details) {       //if we have existing destination stock , we update the stock
            $destination_stock_id = $destination_stock_details[0]['stock_id'];
            $this->increase_stock($destination_stock_id, $quantity);
        } else {        //if we have a blank destination stock , we insert new stock
            $this->insert_stock($book_id, $printingpress_id, $quantity);
        }
        $this->stock_transfer_logger($book_id, $this->config->item('new_stock_ordering_contact_id'), $printingpress_id, $quantity);
    }

    function stock_transfer_logger($book_ID, $form_cotact_ID, $to_contact_ID, $quantity) {
        $data = array(
            'book_ID' => $book_ID,
            'form_cotact_ID' => $form_cotact_ID,
            'to_contact_ID' => $to_contact_ID,
            'quantity' => $quantity
        );


        $this->db->insert('pub_stock_transfer_log', $data);
    }

    function transfer_stock() {
        $from_stock_id = $this->input->post('stock_id_from');
        $to_contact_id = $this->input->post('to_contact_id');
        $Quantity = $this->input->post('Quantity');

        if (!$from_stock_id) {
            return 0;
        }
        $source_stock_details = $this->get_stock_details($from_stock_id)[0];
        /* @var $destination_stock_details array */
        $destination_stock_details = $this->get_stock_details_by($source_stock_details['book_ID'], $to_contact_id);

        // checking if we have a blank destination stock
        $destination_stock_details = (sizeof($destination_stock_details) < 1) ? false : $destination_stock_details;

        if ($destination_stock_details) {       //if we have existing destination stock , we update the stock
            $destination_stock_id = $destination_stock_details[0]['stock_id'];
            $this->reduce_stock($from_stock_id, $Quantity);
            $this->increase_stock($destination_stock_id, $Quantity);
        } else {        //if we have a blank destination stock , we insert new stock
            $this->reduce_stock($from_stock_id, $Quantity);
            $this->insert_stock($source_stock_details['book_ID'], $to_contact_id, $Quantity);
        }
        $this->stock_transfer_logger($source_stock_details['book_ID'], $source_stock_details['printing_press_ID'], $to_contact_id, $Quantity);
        $this->remove_null_stock();
    }

    function remove_null_stock() {
        $db_tables = $this->config->item('db_tables');
        $this->db->delete($db_tables['pub_stock'], array('Quantity<=' => 0));
    }

    function reduce_stock($stock_id, $Quantity) {
        $db_tables = $this->config->item('db_tables');
        $sql = "UPDATE `{$db_tables['pub_stock']}` SET `Quantity`=`Quantity` - $Quantity WHERE `stock_id` = $stock_id";
        $this->db->query($sql);
    }

    function increase_stock($stock_id, $Quantity) {
        $db_tables = $this->config->item('db_tables');
        $sql = "UPDATE `{$db_tables['pub_stock']}` SET `Quantity`=`Quantity` + $Quantity WHERE `stock_id` = $stock_id";
        $this->db->query($sql);
    }

    function marge_insert_book($post_array, $primary_key) {

        $contact_ID = $post_array['contact_ID'];
        $book_ID = $post_array['book_ID'];



        $this->db->select_sum('quantity');
        $this->db->where('contact_ID', $contact_ID);
        $this->db->where('book_ID', $book_ID);
        $new_quantity = $this->db->get('pub_books_return')->result_array()[0]['quantity'];
//        die($new_quantity);
        $this->db->where('contact_ID', $contact_ID);
        $this->db->where('book_ID', $book_ID);
        $this->db->delete('pub_books_return');

        $data = array(
            'contact_ID' => $contact_ID,
            'book_ID' => $book_ID,
            'quantity' => $new_quantity
        );


        $this->db->insert('pub_books_return', $data);

        return TRUE;
    }

    function insert_stock($book_ID, $printing_press_ID, $Quantity) {
        $db_tables = $this->config->item('db_tables');
        $data = array(
            'book_ID' => $book_ID,
            'printing_press_ID' => $printing_press_ID,
            'Quantity' => $Quantity
        );

        $this->db->insert($db_tables['pub_stock'], $data);
    }

    function get_stock_details($stock_id) {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_stock']);
        $this->db->where('stock_id', $stock_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_stock_details_by($book_ID, $printing_press_ID) {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_stock']);
        $where = array(
            'book_ID' => $book_ID,
            'printing_press_ID' => $printing_press_ID
        );
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_contact_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_contacts']);
        $this->db->where_in('contact_type', array('Printing Press', 'Binding Store', 'Sales Store',));
        $this->db->order_by('contact_type', "desc");
        $query = $this->db->get();
        $db_rows = $query->result_array();
        foreach ($db_rows as $index => $row) {
            $options[$row['contact_ID']] = $row['name'] . "('{$row['contact_type']}')";
        }

        if (!isset($options)) {
            $options[''] = "";
        }

        return form_dropdown('to_contact_id', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

    function get_bookid_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_books']);
        $this->db->order_by('book_ID', 'desc');

        $query = $this->db->get();
        $db_rows = $query->result_array();
        foreach ($db_rows as $index => $row) {
            $options[$row['book_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }

        return form_dropdown('book_id', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

    function get_buyer_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_contacts']);
        $this->db->where_in('contact_type', array('Buyer'));
        $this->db->order_by('contact_type', "asc");
        $query = $this->db->get();
        $db_rows = $query->result_array();
        $options['Select'] = "Select Party Name";
        foreach ($db_rows as $index => $row) {
            $options[$row['contact_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }

        return form_dropdown('buyer_id', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

    function get_book_returned_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_books']);
        $this->db->join($db_tables['pub_books_return'], 'pub_books.book_ID = pub_books_return.book_ID');
        $this->db->order_by('name', "asc");
        $query = $this->db->get();
        $db_rows = $query->result_array();
        $options['Select'] = "Select Book Name";
        foreach ($db_rows as $index => $row) {
            $options[$row['book_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }

        return form_dropdown('returned_book_ID', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

    function get_book_send_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_books']);
        $this->db->join($db_tables['pub_send_to_rebind'], 'pub_books.book_ID = pub_send_to_rebind.book_ID');
        $this->db->order_by('name', "asc");
        $query = $this->db->get();
        $db_rows = $query->result_array();
        $options['Select'] = "Select Book Name";
        foreach ($db_rows as $index => $row) {
            $options[$row['book_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }

        return form_dropdown('returned_book_ID', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

    function get_due_holder_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_contacts']);
        $this->db->join('pub_memos', 'pub_contacts.contact_ID = pub_memos.contact_ID');
        $this->db->where_in('contact_type', array('Buyer'))
                ->where('due >', 0);
        $this->db->order_by('contact_type', "asc");
        $query = $this->db->get();
        $db_rows = $query->result_array();
        $options['Select'] = "Select Party Name";
        foreach ($db_rows as $index => $row) {
            $options[$row['contact_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }

        return form_dropdown('buyer_id', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

    function get_printingpress_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_contacts']);
        $this->db->where_in('contact_type', array('Printing Press'));
        $this->db->order_by('contact_type', "asc");
        $query = $this->db->get();
        $db_rows = $query->result_array();
        foreach ($db_rows as $index => $row) {
            $options[$row['contact_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }

        return form_dropdown('printingpress_id', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

//    function get_max_sales_stock($book_ID) {
//        $db_tables = $this->config->item('db_tables');
//        $this->db->select(
//                'stock_id,'
//                . $db_tables['pub_books'] . '.name as book_name,'
//                . $db_tables['pub_contacts'] . '.name as contact_name,'
////                . 'contact_type,'
//                . 'Quantity');
////        $this->db->select('*');
//        $this->db->from($db_tables['pub_stock']);
//        $this->db->join("{$db_tables['pub_books']}", "{$db_tables['pub_books']}.book_ID = {$db_tables['pub_stock']}.book_ID");
//        $this->db->join("{$db_tables['pub_contacts']}", "{$db_tables['pub_contacts']}.contact_ID = {$db_tables['pub_stock']}.printing_press_ID");
//        $this->db->where('contact_type', $contact_type);
//        $query = $this->db->get();
//
//        $db_rows = $query->result_array();
//    }
    function get_max_sales_stock($book_ID) {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('Quantity');
        $db_rows = $this->db->from($db_tables['pub_stock'])
                        ->join("{$db_tables['pub_contacts']}", "{$db_tables['pub_contacts']}.contact_ID = {$db_tables['pub_stock']}.printing_press_ID")
                        ->where('contact_type', 'Sales Store')
                        ->where('book_ID', $book_ID)
                        ->get()->result_array();
        return isset($db_rows[0]['Quantity']) ? $db_rows[0]['Quantity'] : false;
    }

    function total_book_send($range = false) {
        $this->load->library('table');
        if ($range) {
            $db_tables = $this->config->item('db_tables');
            $range = "DATE(issue_date) BETWEEN $range";

            $table_template = array(
                'table_open' => '<table class="table table-bordered table-striped ">',
                'heading_cell_start' => '<th class="success" >'
            );
            $this->table->set_template($table_template);
            $this->table->set_heading("Party Name", "Book", "Quantity", "Issue Date");



            $data['query1'] = $this->db->select('pub_contacts.name as party_name, pub_books.name as book_name, pub_send_to_rebind.quantity as quantity ,pub_send_to_rebind.issue_date')
                            ->from($db_tables['pub_send_to_rebind'])
                            ->join('pub_books', 'pub_books.book_ID=pub_send_to_rebind.book_ID', 'left')
                            ->join('pub_contacts', 'pub_contacts.contact_ID=pub_send_to_rebind.contact_ID', 'left')
                            ->where($range)
                            ->get()->result_array();

            //$data['query1']=$this->db->query('SELECT pub_contacts.name as party_name,
            // pub_books.name as book_name, pub_books_return.quantity as quantity ,
            // pub_books_return.issue_date  FROM `pub_books_return` 
            // LEFT JOIN pub_contacts on pub_contacts.contact_ID=pub_books_return.contact_ID 
            // LEFT join pub_books on pub_books.book_ID=pub_books_return.book_ID');




            $data_table = $this->table->generate($data['query1']);
            return $data_table;
        } else {
            $db_tables = $this->config->item('db_tables');
            $db_rows = $this->db->select_sum('quantity')->from($db_tables['pub_send_to_rebind'])
                            ->get()->result_array();

            return isset($db_rows[0]['quantity']) ? $db_rows[0]['quantity'] : 0;
        }
    }

    function total_book_returned($range = false) {

        $this->load->library('table');
        $this->load->library('session');

        if ($range) {
            $db_tables = $this->config->item('db_tables');
            $range = "DATE(issue_date) BETWEEN $range";

            $table_template = array(
                'table_open' => '<table class="table table-bordered table-striped ">',
                'heading_cell_start' => '<th class="success" >'
            );
            $this->table->set_template($table_template);
            //$this->table->set_heading("Party Name","Book","Quantity","Issue Date");
            $this->table->set_heading("Book", "Quantity");



            $data['query1'] = $this->db->select('pub_books.name as book_name, sum(pub_books_return.quantity) as quantity ')
                            ->from($db_tables['pub_books_return'])
                            ->join('pub_books', 'pub_books.book_ID=pub_books_return.book_ID', 'left')
                            //->join('pub_contacts','pub_contacts.contact_ID=pub_books_return.contact_ID','left')
                            ->group_by('pub_books_return.book_ID')
                            ->where($range)
                            ->get()->result_array();


            //$data['query1']=$this->db->query('SELECT pub_contacts.name as party_name,
            // pub_books.name as book_name, pub_books_return.quantity as quantity ,
            // pub_books_return.issue_date  FROM `pub_books_return` 
            // LEFT JOIN pub_contacts on pub_contacts.contact_ID=pub_books_return.contact_ID 
            // LEFT join pub_books on pub_books.book_ID=pub_books_return.book_ID');




            $data_table = $this->table->generate($data['query1']);
            return $data_table;
            //$list_returnd_book=
        } else {

            $db_tables = $this->config->item('db_tables');
            $db_rows = $this->db->select_sum('quantity')->from($db_tables['pub_books_return'])
                            ->get()->result_array();

            return isset($db_rows[0]['quantity']) ? $db_rows[0]['quantity'] : 0;
        }
    }

    function difference_between_return_send_book() {
        $db_tables = $this->config->item('db_tables');
        $this->load->library('table');
        $table_template = array(
            'table_open' => '<table class="table table-bordered table-striped ">',
            'heading_cell_start' => '<th class="success" >'
        );
        $this->table->set_template($table_template);
        //$this->table->set_heading("Party Name","Book","Quantity","Issue Date");

        $this->table->set_heading("Book Name", "Received  Returned Book Quantity", "Send to Re-bind Book Quantity", "Remaining Book Quantity on Store");

        $sql = 'select
                            (SELECT name  FROM `pub_books` WHERE pub_books.`book_ID` = tbl1.book_ID) as book_name,
                            sum(tbl1.total_returned) total_returned,
                            sum(tbl1.total_sent_to_rebind) total_sent_to_rebind,
                            (sum(tbl1.total_returned) - sum(tbl1.total_sent_to_rebind)) as remaining_book_on_store
                    FROM
                    (
                            SELECT `book_ID`,sum(0) as total_returned,sum(`quantity`) as total_sent_to_rebind 
                            FROM `pub_send_to_rebind` WHERE 1 group by `book_ID`
                    union all
                            SELECT `book_ID`,sum(`quantity`) as total_returned,sum(0) as total_sent_to_rebind 
                            FROM `pub_books_return` WHERE 1 group by `book_ID`
                    )as tbl1
                    group by tbl1.`book_ID`';
        
        $data['query1'] = $this->db->query($sql)->result_array();


        $data_table = $this->table->generate($data['query1']);
        return $data_table;
    }

    function total_return_book_price($range = false) {
        $db_tables = $this->config->item('db_tables');
        $range = "DATE(issue_date) BETWEEN $range";

        $memo_sum = $this->db->select_sum('book_return')->from($db_tables['pub_memos'])
                ->where($range)
                ->get();

        foreach ($memo_sum->result() as $sum) {
            //$this->session->set_userdata('total_book_return_value', $sum->book_return);
            return $sum->book_return;
        }
    }

    function dateformatter($range_string, $formate = 'Mysql') {
        $date = explode(' - ', $range_string);
        $date[0] = explode('/', $date[0]);
        $date[1] = explode('/', $date[1]);

        if ($formate == 'Mysql')
            return "'{$date[0][2]}-{$date[0][0]}-{$date[0][1]}' and '{$date[1][2]}-{$date[1][0]}-{$date[1][1]}'";
        else
            return $date;
    }

    function book_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $db_rows = $this->db->select('name,pub_stock_transfer_log.book_ID')
                        ->from($db_tables['pub_stock_transfer_log'])
                        ->join($db_tables['pub_books'], 'pub_books.book_ID = pub_stock_transfer_log.book_ID')
                        ->order_by('name', "asc")
                        ->get()->result_array();
        //$db_rows = $query->result_array();
        $options[''] = "Select Book Name";
        foreach ($db_rows as $index => $row) {
            $options[$row['book_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }


        return form_dropdown('book_name', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

    function transfer_log_From_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $db_rows = $this->db->select('name,pub_stock_transfer_log.form_cotact_ID')
                        ->from($db_tables['pub_stock_transfer_log'])
                        ->join($db_tables['pub_contacts'], 'pub_contacts.contact_ID = pub_stock_transfer_log.form_cotact_ID')
                        ->order_by('name', "asc")
                        ->get()->result_array();
        //$db_rows = $query->result_array();
        $options[''] = "Select From";
        foreach ($db_rows as $index => $row) {
            $options[$row['form_cotact_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }


        return form_dropdown('from_contact_id', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

    function transfer_log_to_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $db_rows = $this->db->select('name,pub_stock_transfer_log.to_contact_ID')
                        ->from($db_tables['pub_stock_transfer_log'])
                        ->join($db_tables['pub_contacts'], 'pub_contacts.contact_ID = pub_stock_transfer_log.to_contact_ID')
                        ->order_by('name', "asc")
                        ->get()->result_array();
        //$db_rows = $query->result_array();
        $options[''] = "Select From";
        foreach ($db_rows as $index => $row) {
            $options[$row['to_contact_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }


        return form_dropdown('to_contact_id', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true"');
    }

    function result_stock_table($data) {
        $db_tables = $this->config->item('db_tables');
        $this->load->library('table');


        $book_name = $data['book_name'];
        $from = $data['from_contact_id'];
        $to = $data['to_contact_id'];
        $date_range = $data['date_range'];

        if (!empty($date_range)) {
            $range = $this->dateformatter($date_range);
            $range = "DATE(transfer_date) BETWEEN $range";
        }


        $table_template = array(
            'table_open' => '<table class="table table-bordered table-striped ">',
            'heading_cell_start' => '<th class="success" >'
        );
        $this->table->set_template($table_template);
        $this->table->set_heading("Book Name", "From", "To", "Quantity", "Transfer Date");


        $condition_array = array();
        if (!empty($book_name)) {
            array_push($condition_array, "pub_stock_transfer_log.book_ID=$book_name");
        }
        if (!empty($to)) {
            array_push($condition_array, "pub_stock_transfer_log.to_contact_ID=$to");
        }
        if (!empty($from)) {
            array_push($condition_array, "pub_stock_transfer_log.form_cotact_ID=$from");
        }
        if (!empty($date_range)) {
            array_push($condition_array, $range);
        }
        if (!empty($condition_array)) {
            $con = implode(" AND ", $condition_array);
        } else {
            $con = ' 1';
        }

        if (isset($con)) {
            $this->transfer_query = $this->db->select('pub_books.name as book_name,form_name.name as form_name,To.name as to_name,pub_stock_transfer_log.quantity,pub_stock_transfer_log.transfer_date')
                            ->from($db_tables['pub_stock_transfer_log'])
                            ->join($db_tables['pub_books'], 'pub_books.book_ID=pub_stock_transfer_log.book_ID')
                            ->join('pub_contacts as form_name', 'form_name.contact_ID=pub_stock_transfer_log.form_cotact_ID')
                            ->join('pub_contacts as To', 'To.contact_ID=pub_stock_transfer_log.to_contact_ID')
                            ->where($con)
                            ->get()->result_array();
        }





        $data_table = $this->table->generate($this->transfer_query);
        return $data_table;
    }
    
    function result_stock_table_total($data) {
        $db_tables = $this->config->item('db_tables');
        $this->load->library('table');


        $book_name = $data['book_name'];
        $from = $data['from_contact_id'];
        $to = $data['to_contact_id'];
        $date_range = $data['date_range'];

        if (!empty($date_range)) {
            $range = $this->dateformatter($date_range);
            $range = "DATE(transfer_date) BETWEEN $range";
        }


        $table_template = array(
            'table_open' => '<table class="table table-bordered table-striped ">',
            'heading_cell_start' => '<th class="success" >'
        );
        $this->table->set_template($table_template);
        $this->table->set_heading("Book Name", "From", "To", "Quantity", "Transfer Date");


        $condition_array = array();
        if (!empty($book_name)) {
            array_push($condition_array, "pub_stock_transfer_log.book_ID=$book_name");
        }
        if (!empty($to)) {
            array_push($condition_array, "pub_stock_transfer_log.to_contact_ID=$to");
        }
        if (!empty($from)) {
            array_push($condition_array, "pub_stock_transfer_log.form_cotact_ID=$from");
        }
        if (!empty($date_range)) {
            array_push($condition_array, $range);
        }
        if (!empty($condition_array)) {
            $con = implode(" AND ", $condition_array);
        } else {
            $con = ' 1';
        }

        if (isset($con)) {
            $this->transfer_query = $this->db->select('pub_books.name as book_name,form_name.name as form_name,To.name as to_name,SUM(pub_stock_transfer_log.quantity),pub_stock_transfer_log.transfer_date')
                            ->from($db_tables['pub_stock_transfer_log'])
                            ->join($db_tables['pub_books'], 'pub_books.book_ID=pub_stock_transfer_log.book_ID')
                            ->join('pub_contacts as form_name', 'form_name.contact_ID=pub_stock_transfer_log.form_cotact_ID')
                            ->join('pub_contacts as To', 'To.contact_ID=pub_stock_transfer_log.to_contact_ID')
                            ->where($con)
                            ->Group_by('pub_stock_transfer_log.book_ID,pub_stock_transfer_log.form_cotact_ID,pub_stock_transfer_log.to_contact_ID')
                            ->get()->result_array();
        }





        $data_table = $this->table->generate($this->transfer_query);
        return $data_table;
    }

}
