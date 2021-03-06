<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Memo extends CI_Model {

    private $subtotal;
    private $discount;
    private $total;
    private $due;
    private $cash;
    private $book_return;
    private $dues_unpaid;

    function memogenerat($id) {
        $this->load->library('table');
        $query = $this->db->query("SELECT pub_books.name as book_name,
            book_price,
            quantity,
            price,
            cash,
            bank_pay,
            sub_total,
            pub_memos.total as total_p,
            pub_memos_selected_books.total,
            pub_memos.dues_unpaid as dues_unpaid,
            discount,
            due,
            pub_contacts.name as party_name,
            pub_contacts.district as party_district,
            pub_contacts.address as party_address,
            pub_contacts.phone as party_phone,
            pub_memos.book_return as book_return
            FROM `pub_memos_selected_books`
			LEFT JOIN pub_books on pub_memos_selected_books.book_ID=pub_books.book_ID
			LEFT JOIN pub_memos on pub_memos.memo_ID=pub_memos_selected_books.memo_ID
            LEFT JOIN pub_contacts on pub_memos.contact_ID=pub_contacts.contact_ID

			WHERE pub_memos_selected_books.memo_ID='$id'");
        $tmpl = array(
            'table_open' => '<table class="table table-bordered table-striped">',
            'heading_row_start' => '<tr class="success">',
            'heading_row_end' => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end' => '</th>',
            'row_start' => '<tr>',
            'row_end' => '</tr>',
            'cell_start' => '<td>',
            'cell_end' => '</td>',
            'row_alt_start' => '<tr>',
            'row_alt_end' => '</tr>',
            'cell_alt_start' => '<td>',
            'cell_alt_end' => '</td>',
            'table_close' => '</table>'
        );
        $this->table->set_template($tmpl);
        $this->table->set_heading('Quantity','Book Name', 'Book Price', 'Sales Price', 'Total Price');
        foreach ($query->result() as $value) {
            $this->subtotal = $value->sub_total;
            $this->discount = $value->discount;
            $this->table->add_row($value->quantity,$value->book_name, $value->book_price, $value->price,  $value->total);
            $this->due = $value->due;
            $this->cash = $value->cash;
            $this->total = $value->total_p;
            $this->dues_unpaid = $value->dues_unpaid;
            $this->book_return = $value->book_return;
            $this->bank_pay = $value->bank_pay;
        }
        $query1 = $this->db->query("SELECT pub_contacts.name as cname,
            pub_contacts.district as cdistrict,
            pub_contacts.address as caddress,
            pub_contacts.phone as cphone,
            pub_memos.memo_ID as memoid
            FROM `pub_contacts`
            LEFT join pub_memos on pub_contacts.contact_ID=pub_memos.contact_ID
            where pub_memos.memo_ID='$id'");

        foreach ($query1->result() as $value) {
            $data['party_name'] = $value->cname;
            $data['phone'] = $value->cphone;
            $data['address'] = $value->caddress;
            $data['district'] = $value->cdistrict;
            $data['memoid'] = $value->memoid;
        }




        $cell = array('border' => 0, 'colspan' => 4);
        $c3 = array('data' => '<strong><span class="upper">(' . $this->convert_number($this->subtotal) . ')</span></strong>', 'colspan' => 3);
        $c3r = array('data' => '', 'colspan' => 3);
        $this->table->add_row($cell, '');

        $this->table->add_row($c3, '<strong>বই মূল্য :</strong>', '<span id="number">' . $this->subtotal . '</span>');
        $this->table->add_row(
                '<strong>বই ফেরত :</strong>', '(-) ' . $this->book_return, '', '<strong>পূর্বের বাকি:</strong>', $this->dues_unpaid);
        $this->table->add_row('<strong>বোনাস :</strong>', '(-) ' . $this->discount, '', '<strong>মোট:</strong>', $this->total);


        $this->table->add_row($c3r, '<strong>নগদ জমা:</strong>', $this->cash);
        $this->table->add_row($c3r, '<strong>ব্যাংক জমা:</strong>', $this->bank_pay);

        $this->table->add_row($c3r, '<strong>বাকি :</strong>', $this->due);
        $this->table->add_row();
        $data['table'] = $this->table->generate();
        return $data;
    }

    function convert_number($number) {
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }
        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */
        $res = "";
        if ($Gn) {
            $res .= $this->convert_number($Gn) . "Million";
        }
        if ($kn) {
            $res .= (empty($res) ? "" : " ") . $this->convert_number($kn) . " Thousand";
        }
        if ($Hn) {
            $res .= (empty($res) ? "" : " ") . $this->convert_number($Hn) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }
            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];
                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }
        if (empty($res)) {
            $res = "zero";
        }
        return $res;
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

    // comma separated
    function last_memo_ID_of_each_contact_ID() {
        $this->load->library('table');
        $db_tables = $this->config->item('db_tables');

        $contact_array = $this->db->select('distinct(contact_ID)')
                        ->from($db_tables['pub_memos'])
                        ->get()->result_array();
        $distict_contact_IDs = $Last_Memo_IDs = array();
        foreach ($contact_array as $value) {
            array_push($distict_contact_IDs, $value['contact_ID']);
            $Memo_ID = $this->db->select('max(memo_ID)')
                            ->from($db_tables['pub_memos'])
                            ->where('contact_ID', $value['contact_ID'])
                            ->get()->result_array()[0]['max(memo_ID)'];
            array_push($Last_Memo_IDs, $Memo_ID);
        }
        return $Last_Memo_IDs;
    }

    function clean_pub_memos_selected_books_db() {
        $db_tables = $this->config->item('db_tables');
        $this->db->delete($db_tables['pub_memos_selected_books'], "`memo_ID` not in (SELECT `memo_ID` FROM `pub_memos` WHERE 1)");
    }

    function add_book_selector_table() {
        $this->load->library('table');
        // Getting Data
//        $query = $this->db->query("SELECT * FROM `pub_books`");
        $db_tables = $this->config->item('db_tables');
        $this->db->select("{$db_tables['pub_books']}.* ,{$db_tables['pub_stock']}.stock_ID ,{$db_tables['pub_stock']}.Quantity  ");
        $query = $this->db->from($db_tables['pub_books'])
                ->join("{$db_tables['pub_stock']}", "{$db_tables['pub_stock']}.book_ID = {$db_tables['pub_books']}.book_ID")
                ->join("{$db_tables['pub_contacts']}", "{$db_tables['pub_contacts']}.contact_ID = {$db_tables['pub_stock']}.printing_press_ID")
                ->where('contact_type', 'Sales Store')
                ->order_by('book_ID', 'ASC')
                ->get();
        $data = array();
        foreach ($query->result_array() as $index => $row) {
            array_push($data, [$row['name'], $row['book_price'],
                $row['price'], $row['Quantity'],
                '<input style="width: 100px;" data-index="' . $index . '" data-price="' . $row['price'] . '" name="quantity[' . $row['book_ID'] . ']" min="0" max="' . $row['Quantity'] . '" value="0" class="numeric form-control" type="number">'
                . '     <input name="price[' . $row['book_ID'] . ']" value="' . $row['price'] . '" type="hidden">'
                . '     <input name="stock_ID[' . $row['book_ID'] . ']" value="' . $row['stock_ID'] . '" type="hidden">'
            ]);
        }
        $this->table->set_heading('Book Name', 'Book Price', 'Sales Price', 'Book Available', 'Quantity');
        //Setting table template
        $tmpl = array(
            'table_open' => '<table class="table table-bordered table-striped">',
            'heading_cell_start' => '<th class="success">'
        );
        $this->table->set_template($tmpl);
        $output = '<label>Select Book Quantity:</label><div style="overflow-y:scroll;max-height:335px;">
                    ' . $this->table->generate($data) . '</div>
                   <label>Sub Total :</label><span id="sub_total">0</span><span>Tk</span> <input type="hidden" maxlength="50  " name="sub_total">';
        $output.=""
                . "<script>"
                . "var memo_ID='';"
                . "</script>\n";
        return $output;
    }

    function edit_book_selector_table($value, $primary_key) {
        $this->load->library('table');
        // Getting Data
        $where = array('memo_ID' => $primary_key);
        $book_selection_query = $this->db->get_where('pub_memos_selected_books', $where);
        $book_selection = $book_selection_query->result_array();
        foreach ($book_selection as $index => $row) {
            $book_ID = $row['book_ID'];
            $quantity = $row['quantity'];
            $book_quantity_by_id[$book_ID] = $quantity;
        }

//        Getting the books (not stock restricted)
//        $query = $this->db->query("SELECT * FROM `pub_books`");
//        Getting the books (stock restricted)
        $db_tables = $this->config->item('db_tables');
        $this->db->select("{$db_tables['pub_books']}.*  ,{$db_tables['pub_stock']}.stock_ID ,{$db_tables['pub_stock']}.Quantity  ");
        $query = $this->db->from($db_tables['pub_books'])
                ->join("{$db_tables['pub_stock']}", "{$db_tables['pub_stock']}.book_ID = {$db_tables['pub_books']}.book_ID")
                ->join("{$db_tables['pub_contacts']}", "{$db_tables['pub_contacts']}.contact_ID = {$db_tables['pub_stock']}.printing_press_ID")
                ->where('contact_type', 'Sales Store')
                ->get();

        $data = array();
        foreach ($query->result_array() as $index => $row) {
            $book_quantity_by_id[$row['book_ID']] = isset($book_quantity_by_id[$row['book_ID']]) ? $book_quantity_by_id[$row['book_ID']] : 0;
            array_push($data, [$row['name'], $row['book_price'],
                $row['price'], $book_quantity_by_id[$row['book_ID']] + $row['Quantity'],
                '<input style="width: 100px;" data-index="' . $index . '" data-price="' . $row['price'] . '" name="quantity[' . $row['book_ID'] . ']" value="' . $book_quantity_by_id[$row['book_ID']] . '" min="0" max="' . ($book_quantity_by_id[$row['book_ID']] + $row['Quantity'] ) . '"  class="numeric form-control" type="number">'
                . '     <input name="price[' . $row['book_ID'] . ']" value="' . $row['price'] . '" type="hidden">'
                . '     <input name="stock_ID[' . $row['book_ID'] . ']" value="' . $row['stock_ID'] . '" type="hidden">'
            ]);
        }
        $this->table->set_heading('Book Name', 'Book Price', 'Sales Price', 'Book Available', 'Quantity');
        //Setting table template
        $tmpl = array(
            'table_open' => '<table class="table table-bordered table-striped">',
            'heading_cell_start' => '<th class="success">'
        );
        $this->table->set_template($tmpl);
        $output = '<label>Select Book Quantity:</label><div style="overflow-y:scroll;max-height:335px;">
                    ' . $this->table->generate($data) . '</div>
                   <label>Sub Total :</label><span id="sub_total">' . $value . '</span><span>Tk</span> <input type="hidden" maxlength="50" value="' . $value . '" name="sub_total">';
        $output.=""
                . "<script>"
                . "var memo_ID='" . $primary_key . "';"
                . "</script>\n";
        return $output;
    }

    function updating_memo_serial($new_memo_serial, $memo_ID) {
        $data = array(
            'memo_serial' => $new_memo_serial,
        );

        $this->db->where('memo_ID', $memo_ID);
        $this->db->update('pub_memos', $data);
    }

    function after_adding_memo($post_array, $primary_key) {
        $this->load->model('Stock_manages');
        $this->updating_memo_serial($primary_key, $primary_key);

        $pub_memos_selected_books_data = array();
        foreach ($post_array['quantity'] as $book_ID => $quantity) {
            if ($quantity > 0) {
                array_push($pub_memos_selected_books_data, array(
                    "memo_ID" => $primary_key,
                    "book_ID" => $book_ID,
                    "stock_ID" => $post_array['stock_ID'][$book_ID],
                    "quantity" => $quantity,
                    "price_per_book" => $post_array['price'][$book_ID],
                    "total" => $quantity * $post_array['price'][$book_ID]
                ));
            }
            $this->Stock_manages->reduce_stock($post_array['stock_ID'][$book_ID], $quantity);
        }
        if ($pub_memos_selected_books_data != array())
            $this->db->insert_batch('pub_memos_selected_books', $pub_memos_selected_books_data);

        return TRUE;
    }

    function selected_book_quantity($memo_ID, $book_ID) {
        $where = array(
            'memo_ID' => $memo_ID,
            'book_ID' => $book_ID
        );
        $data = $this->db->select('quantity')
                        ->from('pub_memos_selected_books')
                        ->where($where)->get()->result_array();
        return isset($data[0]['quantity']) ? $data[0]['quantity'] : 0;
    }

    function after_editing_memo($post_array, $primary_key) {
        $this->load->model('Stock_manages');
        $pub_memos_selected_books_data = array();
        foreach ($post_array['quantity'] as $book_ID => $quantity) {
            $previous_quantity = $this->selected_book_quantity($primary_key, $book_ID);
            if ($quantity > 0) {
                array_push($pub_memos_selected_books_data, array(
                    "memo_ID" => $primary_key,
                    "book_ID" => $book_ID,
                    "stock_ID" => $post_array['stock_ID'][$book_ID],
                    "quantity" => $quantity,
                    "price_per_book" => $post_array['price'][$book_ID],
                    "total" => $quantity * $post_array['price'][$book_ID]
                ));
            }
            $quantity = $previous_quantity - $quantity;
            $this->Stock_manages->increase_stock($post_array['stock_ID'][$book_ID], $quantity);
        }
        $this->db->where('memo_ID', $primary_key);
        $this->db->delete('pub_memos_selected_books');

        $this->db->insert_batch('pub_memos_selected_books', $pub_memos_selected_books_data);

        return TRUE;
    }

    function after_deleting_memo($primary_key) {
        $this->load->model('Stock_manages');
        $where = array(
            'memo_ID' => $primary_key
        );
        $rows = $this->db->select('*')
                        ->from('pub_memos_selected_books')
                        ->where($where)->get()->result_array();

        foreach ($rows as $index => $row) {

            $this->Stock_manages->increase_stock($row['stock_ID'], $row['quantity']);
        }

        $this->db->delete('pub_memos_selected_books', $where);
        return TRUE;
    }

}
