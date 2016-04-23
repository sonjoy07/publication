<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of SalesReturn
 *
 * @author MD. Mashfiq
 */
class Sales_return_model extends CI_Model {

    function get_buyer_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_contacts']);
        $this->db->where_in('contact_type', array('Buyer'));
        $this->db->order_by('name', "asc");
        $query = $this->db->get();
        $db_rows = $query->result_array();
        $options[''] = "Select Party Name";
        foreach ($db_rows as $index => $row) {
            $options[$row['contact_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }

        return form_dropdown('buyer_id', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" required="true" aria-hidden="true"');
    }

    function get_binding_store_dropdown() {
        $db_tables = $this->config->item('db_tables');
        $this->db->select('*');
        $this->db->from($db_tables['pub_contacts']);
        $this->db->where_in('contact_type', array('Binding Store'));
        $this->db->order_by('name', "asc");
        $query = $this->db->get();
        $db_rows = $query->result_array();
        $options[''] = "Select Party Name";
        foreach ($db_rows as $index => $row) {
            $options[$row['contact_ID']] = $row['name'];
        }
        if (!isset($options)) {
            $options[''] = "";
        }

        return form_dropdown('buyer_id', $options, '', 'class="form-control select2 select2-hidden-accessible" tabindex="-1" required="true" aria-hidden="true"');
    }

    function book_selector_table() {

        $this->load->library('table');

        // Getting Data
//        $query = $this->db->query("SELECT * FROM `pub_books`");

        $db_tables = $this->config->item('db_tables');

        $query = $this->db->select("*")
                ->from($db_tables['pub_books'])
                ->order_by('book_ID', 'ASC')
                ->get();

        $data = array();

        foreach ($query->result_array() as $index => $row) {

            array_push($data, [$row['name'],
                '<input name="book_section[' . $row['book_ID'] . ']" value="" type="number" min="0">'
            ]);
        }

        $this->table->set_heading('Book Name', 'Quantity');

        //Setting table template

        $tmpl = array(
            'table_open' => '<table class="table table-bordered table-striped">',
            'heading_cell_start' => '<th class="success">'
        );

        $this->table->set_template($tmpl);

        $output = '<div style="overflow-y:scroll;max-height:333px;">

                    ' . $this->table->generate($data) . '</div>';



        return $output;
    }

    function current_sates_return_insert_processor() {
        $buyer_id = $this->input->post('buyer_id');

        $return_date = $this->input->post('return_date');
        $return_date = date_format(date_create($return_date), 'Y-m-d H:i:s');
        $book_selected = $this->input->post('book_section');
        $data_to_be_inserted = array();
        foreach ($book_selected as $index => $value) {
            if (!empty($book_selected[$index]) && $book_selected[$index] != 0) {
                array_push($data_to_be_inserted, array(
                    'contact_ID' => $buyer_id,
                    'book_ID' => $index,
                    'quantity' => $value,
                    'issue_date' => $return_date
                ));
            }
        }
//        die(print_r($data_to_be_inserted));
        if (!empty($data_to_be_inserted)) {
            $this->db->insert_batch('pub_books_return', $data_to_be_inserted);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function book_rebind_insert_processor() {
        $buyer_id = $this->input->post('buyer_id');

        $return_date = $this->input->post('return_date');
        $return_date = date_format(date_create($return_date), 'Y-m-d H:i:s');
        $book_selected = $this->input->post('book_section');
        $data_to_be_inserted = array();
        foreach ($book_selected as $index => $value) {
            if (!empty($book_selected[$index]) && $book_selected[$index] != 0) {
                array_push($data_to_be_inserted, array(
                    'contact_ID' => $buyer_id,
                    'book_ID' => $index,
                    'quantity' => $value,
                    'issue_date' => $return_date
                ));
            }
        }
//        die(print_r($data_to_be_inserted));
        if (!empty($data_to_be_inserted)) {
            $this->db->insert_batch('pub_send_to_rebind', $data_to_be_inserted);
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
