<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of report
 *
 * @author MD. Mashfiq
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends CI_Model {

    private $range;

    //put your code here
    function sold_book_today($range = false) {

        $this->load->library('table');

        if ($range) {
            $range = "BETWEEN $range";
        } else {
            $date = date('Y-m-d');
            $range = "=DATE('$date')";
        }
        $this->range = $range;

        $sql = "SELECT pub_memos_selected_books.book_ID,name,sum(quantity) as quantity
            FROM `pub_memos_selected_books`
            JOIN `pub_memos` ON `pub_memos_selected_books`.memo_ID = `pub_memos`.memo_ID
            JOIN `pub_books` on `pub_memos_selected_books`.`book_ID` = `pub_books`.`book_ID`
            WHERE DATE(issue_date) $range
            GROUP BY `pub_memos_selected_books`.`book_ID`";

        $data = $this->db->query($sql)->result_array();

        $table_data = array();

        $total_quantity = 0;
        $total_speciment_book_copy = 0;
        $total_book_sold = 0;

        foreach ($data as $rowIndex => $rowValue) {
            $have_speciment_book_copy = $this->get_book_quantity($rowValue["book_ID"]);
            if ($have_speciment_book_copy > 0) {
                $quantity = "{$rowValue["quantity"]} - {$have_speciment_book_copy} = "
                        . ( $rowValue["quantity"] - $have_speciment_book_copy);
                $total_speciment_book_copy = $total_speciment_book_copy + $have_speciment_book_copy;
                $total_book_sold = $total_book_sold + $rowValue["quantity"] - $have_speciment_book_copy;
            } else {
                $quantity = $rowValue["quantity"];
                $total_book_sold = $total_book_sold + $rowValue["quantity"];
            }
            $total_quantity = $total_quantity + $rowValue["quantity"];
            array_push($table_data, array(
                $rowValue["name"], $quantity
            ));
        }
        array_push($table_data, array(
            '<strong>মোট :</strong>', "<strong>$total_quantity - $total_speciment_book_copy =  $total_book_sold</strong>"
        ));
//- interval 2 day
        $table_template = array(
            'table_open' => '<table class="table table-bordered table-striped move-tk-to-right-for-soldbook">',
            'heading_cell_start' => '<th class="success" >'
        );
        $this->table->set_template($table_template);

        $this->table->set_heading("বইয়ের নাম", array('data' => " (সর্বমোট - সৌজন্য) = বিক্রিত সংখ্যা",
            'style' => "text-align: right;"
        ));




        if ($data != array()) {
            return $this->table->generate($table_data);
        } else {
            return "আজ কোন মেমো তৈরী হয়নি ।";
        }
    }

    function dateformatter($range_string, $formate = 'Mysql') {
        $date = explode(' - ', $range_string);
        $date[0] = explode('/', $date[0]);
        $date[1] = explode('/', $date[1]);

        if ($formate == 'Mysql') {
            return "'{$date[0][2]}-{$date[0][0]}-{$date[0][1]}' and '{$date[1][2]}-{$date[1][0]}-{$date[1][1]}'";
        } else if ($formate == "2_string") {
            $date = explode(' - ', $range_string);
            $from_date = date('Y-m-d', strtotime($date[0]));
            $to_date = date('Y-m-d', strtotime($date[1]));
            return compact(array('from_date', 'to_date'));
        } else {
            return $date;
        }
    }

    function get_book_quantity($book_ID, $speciment_contact_id = FALSE) {
        if (!$speciment_contact_id)
            $speciment_contact_id = $this->config->item('speciment_contact_id');

        $sql = "SELECT pub_memos_selected_books.book_ID,
                        pub_memos.contact_ID,sum(quantity) as quantity
                    FROM `pub_memos_selected_books`
                    JOIN `pub_memos` ON `pub_memos_selected_books`.memo_ID = `pub_memos`.memo_ID
                    WHERE pub_memos_selected_books.`book_ID`= {$book_ID} and 
                         DATE(issue_date) {$this->range} and
                        pub_memos.contact_ID in ({$speciment_contact_id})";
        return $this->db->query($sql)->result_array()[0]["quantity"];
    }

    function due_remaining_table($date = FALSE) {

        $db_tables = $this->config->item('db_tables');

        if (!$date) {
            //getting all data
            $sql = "SELECT tbl.contact_ID,
                    tbl2.name,
                    SUM(tbl.total_due) total_due, 
                    SUM(tbl.total_due_payment) total_due_payment,
                    if(SUM(tbl.total_due) < SUM(tbl.total_due_payment) , 0 , SUM(tbl.total_due)-SUM(tbl.total_due_payment)) as due_remaining
                    FROM (
                            SELECT contact_ID,SUM(0) total_due,sum(due_payment_amount) total_due_payment 
                            FROM `{$db_tables['pub_due_payment_ledger']}`
                            Group by `contact_ID`
                        UNION ALL
                            SELECT contact_ID,sum(due_amount ) total_due,SUM(0) total_due_payment 
                            FROM `{$db_tables['pub_due_log']}`
                            Group by `contact_ID`
                    ) as tbl
                    Natural join
                    {$db_tables['pub_contacts']} as tbl2
                    GROUP BY tbl.contact_ID";
        } else if ($date == "today") {
            $today = date('Y-m-d');
            $sql = "SELECT tbl.contact_ID,
                    tbl2.name,
                    SUM(tbl.total_due) total_due, 
                    SUM(tbl.total_due_payment) total_due_payment,
                    if(SUM(tbl.total_due) < SUM(tbl.total_due_payment) , 0 , SUM(tbl.total_due)-SUM(tbl.total_due_payment)) as due_remaining
                    FROM (
                            SELECT contact_ID,SUM(0) total_due,sum(due_payment_amount) total_due_payment 
                            FROM `{$db_tables['pub_due_payment_ledger']}`
                            WHERE DATE(payment_date) = DATE('$today')
                            Group by `contact_ID`
                        UNION ALL
                            SELECT contact_ID,sum(due_amount ) total_due,SUM(0) total_due_payment 
                            FROM `{$db_tables['pub_due_log']}`
                            WHERE DATE(due_date) = DATE('$today')
                            Group by `contact_ID`
                    ) as tbl
                    Natural join
                    {$db_tables['pub_contacts']} as tbl2
                    GROUP BY tbl.contact_ID";
        } else {
            if ($date == "this_month") {
                $from_date = date('Y-m-1');
                $to_date = date('Y-m-t');
            } else {
                extract($this->dateformatter($date, '2_string'));
            }
            $sql = "SELECT tbl.contact_ID,
                    tbl2.name,
                    SUM(tbl.total_due) total_due, 
                    SUM(tbl.total_due_payment) total_due_payment,
                    if(SUM(tbl.total_due) < SUM(tbl.total_due_payment) , 0 , SUM(tbl.total_due)-SUM(tbl.total_due_payment)) as due_remaining
                    FROM (
                            SELECT contact_ID,SUM(0) total_due,sum(due_payment_amount) total_due_payment 
                            FROM `{$db_tables['pub_due_payment_ledger']}`
                            WHERE DATE(payment_date) between DATE('$from_date') and Date('$to_date') 
                            Group by `contact_ID`
                        UNION ALL
                            SELECT contact_ID,sum(due_amount ) total_due,SUM(0) total_due_payment 
                            FROM `{$db_tables['pub_due_log']}`
                            WHERE DATE(due_date) between DATE('$from_date') and Date('$to_date')
                            Group by `contact_ID`
                    ) as tbl
                    Natural join
                    {$db_tables['pub_contacts']} as tbl2
                    GROUP BY tbl.contact_ID";
        }
//        die($sql);
        $rows_as_array = $this->db->query($sql)->result_array();

        $total_due = $total_payment = $total_remaining = 0;
        foreach ($rows_as_array as $row) {
            $total_due += $row['total_due'];
            $total_payment += $row['total_due_payment'];
            $total_remaining += $row['due_remaining'];
        }

        $this->load->library('table');
        //Setting table template
        $tmpl = array(
            'table_open' => '<table class="table table-striped right-text-for-account">',
            'heading_cell_start' => '<th class="success heading-right-for-page">'
        );
        $this->table->set_template($tmpl);
        $this->table->set_heading('Contact ID', 'Party Name', 'Opening Due', ' Due payment', 'Due Remaining');

        array_push($rows_as_array, array(
            '', '<strong>Total</strong>',
            "<strong>$total_due</strong>",
            "<strong>$total_payment</strong>",
            "<strong>$total_remaining</strong>"
                )
        );

        $due_remaining_table = $this->table->generate($rows_as_array);
        return $due_remaining_table;
    }

}
