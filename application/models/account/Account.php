<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account extends CI_Model {

    private $todaysell = 0;
    private $monthlysell = 0;
    private $today_due = 0;
    private $monthly_due = 0;
    private $total_cash_paid = 0;
    private $total_bank_pay = 0;
    private $total_due = 0;
    private $total_sell = 0;
    private $cost = 0;

    function today($date = '') {

        $todaysell = 0;

        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $query = $this->db->query("SELECT * FROM pub_memos WHERE DATE(issue_date)=DATE('$date')");
//        print_r($query->result_array());
//        exit;
        $data['cash_paid'] = 0;
        $data['bank_pay'] = 0;
        foreach ($query->result() as $value) {
            $todaysell+=$value->sub_total - $value->discount - $value->book_return;

            $data['cash_paid'] += $value->cash;

            $data['bank_pay'] += $value->bank_pay;

            $due = $value->due - $value->dues_unpaid;
        }
        $data['todaysell'] = $todaysell;
        $data['today_due'] = $this->today_due($date);
        return $data;
    }

    function today_due($date = '') {
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $db_tables = $this->config->item('db_tables');

        $this->load->model('Memo');
//        $last_memo_ID_of_each_contact_ID = implode(',', $this->Memo->last_memo_ID_of_each_contact_ID());
        $sql = "SELECT tbl.contact_ID,
            SUM(tbl.total_due) total_due,
            SUM(tbl.total_due_payment) total_due_payment
            FROM (
	SELECT contact_ID,SUM(0) total_due,sum(due_payment_amount) total_due_payment 
	FROM `{$db_tables['pub_due_payment_ledger']}` WHERE DATE(payment_date)=DATE('$date') Group by `contact_ID`
		UNION ALL
	SELECT contact_ID,sum(due_amount ) total_due,SUM(0) total_due_payment 
	FROM `{$db_tables['pub_due_log']}` WHERE DATE(due_date)=DATE('$date') Group by `contact_ID`
            ) as tbl
            GROUP BY tbl.contact_ID";
//        die($sql);
        $result = $this->db->query($sql)->result();
        $today_due = 0;
        foreach ($result as $row) {
            $temp = $row->total_due - $row->total_due_payment;
            If ($temp > 0) {
                $today_due = $today_due + $temp;
            }
        }
        return $today_due;
    }

    function monthly() {
        $this->load->model('Memo');

        $query = $this->db->query("SELECT * FROM pub_memos "
                . "WHERE "
//                . "issue_date BETWEEN "
//                . "(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH) "
//                . "AND (LAST_DAY(CURDATE()) + INTERVAL 1 DAY)");
                . "MONTH(issue_date) = MONTH(CURDATE()) && YEAR(issue_date) = YEAR(CURDATE())");

        $data['cash_paid'] = 0;
        $data['bank_pay'] = 0;
        foreach ($query->result() as $value) {
            $this->monthlysell+=$value->sub_total - $value->discount - $value->book_return;
            $data['cash_paid'] += $value->cash;
            $data['bank_pay'] += $value->bank_pay;
        }
        $data['monthlysell'] = $this->monthlysell;
        $data['monthly_due'] = $this->monthly_due();
        return $data;
    }

// function today_due(){
// 	$query=$this->db->query("SELECT due FROM pub_memos WHERE issue_date=DATE(NOW())");
// 	foreach ($query->result() as $value) {
// 		$this->today_due+=$value->due;
// 	}
// 	return $this->today_due;
// }
// function monthly_due(){
// 	$query=$this->db->query("SELECT due FROM pub_memos WHERE issue_date BETWEEN DATE_ADD(now(),INTERVAL -1 MONTH) AND NOW()");
// 	foreach ($query->result() as $value) {
// 		$this->monthly_due+=$value->due;
// 	}
// 	return $this->monthly_due;
// }
    function total() {
        $data['total_cash_paid'] = 0;
        $data['total_bank_pay'] = 0;
        $query = $this->db->query("SELECT cash,bank_pay,due FROM pub_memos");
        foreach ($query->result() as $value) {
            $data['total_cash_paid']+=$value->cash;
            $data['total_bank_pay']+=$value->bank_pay;
        }
        $data['total_due'] = $this->total_due();
        $data['total_sell'] = $data['total_cash_paid'] + $data['total_bank_pay'] + $data['total_due'];
        return $data;
    }

    function total_due() {
        $this->load->model('Memo');
        $last_memo_ID_of_each_contact_ID = implode(',', $this->Memo->last_memo_ID_of_each_contact_ID());
        $query = $this->db->select_sum('due')
                        ->from('pub_memos')
                        ->where('memo_ID in', '(' . $last_memo_ID_of_each_contact_ID . ')', false)
                        ->where('due >', '0')->get()->result_array();
        return $query[0]['due'];
    }

    function monthly_due() {

        $this->load->model('Memo');
        $last_memo_ID_of_each_contact_ID = implode(',', $this->Memo->last_memo_ID_of_each_contact_ID());

        if ($last_memo_ID_of_each_contact_ID === '') {
            die("<script>alert('কোন মেমো ডাটাবেজে নেই । দয়া করে মেমো যোগ করুন । ');"
                    . "window.location.assign( '" . site_url('admin/memo_management/add') . "');</script>");
        }


        $db_tables = $this->config->item('db_tables');
        $from_date = date('Y-m-1');
        $to_date = date('Y-m-t');
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
        $query = $this->db->query($sql);
        $monthly_due = 0;
        foreach ($query->result() as $value) {
            $monthly_due+=$value->due_remaining;
        }
        return $monthly_due;
    }

    function due_in_date_range($range) {

        $db_tables = $this->config->item('db_tables');
        $sql = "SELECT tbl.contact_ID,
                    tbl2.name,
                    SUM(tbl.total_due) total_due, 
                    SUM(tbl.total_due_payment) total_due_payment,
                    if(SUM(tbl.total_due) < SUM(tbl.total_due_payment) , 0 , SUM(tbl.total_due)-SUM(tbl.total_due_payment)) as due_remaining
                    FROM (
                            SELECT contact_ID,SUM(0) total_due,sum(due_payment_amount) total_due_payment 
                            FROM `{$db_tables['pub_due_payment_ledger']}`
                            WHERE DATE(payment_date) between $range 
                            Group by `contact_ID`
                        UNION ALL
                            SELECT contact_ID,sum(due_amount ) total_due,SUM(0) total_due_payment 
                            FROM `{$db_tables['pub_due_log']}`
                            WHERE DATE(due_date) between $range
                            Group by `contact_ID`
                    ) as tbl
                    Natural join
                    {$db_tables['pub_contacts']} as tbl2
                    GROUP BY tbl.contact_ID";
        $query = $this->db->query($sql);
        $total_due = 0;
        foreach ($query->result() as $value) {
            $total_due+=$value->due_remaining;
        }
        return $total_due;
    }

    function total_account_detail_table() {
        $this->load->library('table');
        $total = $this->total();
        $this->table->set_heading('Description', '<span class="pull-right">(TK)Amount</span>');
        $data = array(
            array('Total Cash Collection:', $this->Common->taka_format($total['total_cash_paid'])),
            array('Total Bank Collection:', $this->Common->taka_format($total['total_bank_pay'])),
            array('Total Due:', $this->Common->taka_format($total['total_due'])),
            array('<strong>Total Sale:<strong>', "<strong>{$this->Common->taka_format($total['total_sell'])}<strong>")
        );
        //Setting table template
        $tmpl = array(
            'table_open' => '<table class="table table-striped right-text-for-account">',
            'heading_cell_start' => '<th class="success">'
        );
        $this->table->set_template($tmpl);
        return $this->table->generate($data);
    }

    function today_monthly_account_detail_table() {

        $this->load->library('table');
        $this->load->model('Office_cost');

        $today_cost = $this->Office_cost->today_office_cost();

        $monthly_cost = $this->Office_cost->monthly_office_cost();

        $account_today = $this->account->today();
        $account_monthly = $this->account->monthly();
        $total = $this->total();

//        calculation for cash minus cost for today and monthly

        $cash_cost = $this->Common->taka_format($account_today['cash_paid'] - $today_cost);
        $t_cash_t_cost = $this->Common->taka_format($account_monthly['cash_paid'] - $monthly_cost);


        $this->table->set_heading('Description', '<span class="pull-right">(TK)Amount</span>');
        $data = array(
            array('Today Cash Collection:', $this->Common->taka_format($account_today['cash_paid'])),
            array('Today Cash in Hand After Cost:', '(' . $this->Common->taka_format($account_today['cash_paid']) . '-' . $this->Common->taka_format($today_cost) . ')=' . ($cash_cost)),
            array('Today Bank Collection:', $this->Common->taka_format($account_today['bank_pay'])),
            array('Monthly Cash Collection:', $this->Common->taka_format($account_monthly['cash_paid'])),
            array('Monthly Cash in Hand After Cost:', '(' . $this->Common->taka_format($account_monthly['cash_paid']) . '-' . $this->Common->taka_format($monthly_cost) . ')=' . ($t_cash_t_cost)),
            array('Monthly Bank Collection:', $this->Common->taka_format($account_monthly['bank_pay']))
        );
        //Setting table template
        $tmpl = array(
            'table_open' => '<table class="table table-striped right-text-for-account">',
            'heading_cell_start' => '<th class="success heading-right-for-page">'
        );
        $this->table->set_template($tmpl);
        return $this->table->generate($data);
    }

    function today_detail_table($range) {
        $this->load->model('Office_cost');

        //$today_cost=$this->Office_cost->today_office_cost();

        $this->load->library('table');
        $t_t_s = 0;
        $t_t_d = 0;
        $t_t_c = 0;
        $t_t_b = 0;
        $t_c = 0;

        $account_today = $this->account->today();
        $account_monthly = $this->account->monthly();
        $total = $this->total();

        $this->table->set_heading('Date', 'Sell', 'Cash Collection', 'Bank Collection', 'Due', 'Office Cost');



        $query = $this->db->query("SELECT DATE(issue_date) as issue_date FROM pub_memos WHERE DATE(issue_date) BETWEEN $range GROUP BY(DATE(issue_date))");

        foreach ($query->result() as $value) {
            $data = $this->today($value->issue_date);

            $today_sell = $data['todaysell'];
            $t_t_s+=$today_sell;

            $today_due = $data['today_due'];
            $t_t_d+=$today_due;

            $today_cash_pay = $data['cash_paid'];
            $t_t_c+=$today_cash_pay;

            $today_bank_pay = $data['bank_pay'];
            $t_t_b+=$today_bank_pay;

            $cost = $this->Office_cost->today_office_cost($value->issue_date);
            $t_c+=$cost;

            $this->table->add_row($value->issue_date, $this->Common->taka_format($today_sell), $this->Common->taka_format($today_cash_pay), $this->Common->taka_format($today_bank_pay), $this->Common->taka_format($today_due), $this->Common->taka_format($cost));
        }
        $cell = array('data' => '', 'class' => 'info pull-right', 'colspan' => 5);
        $this->table->add_row($cell);
        $this->table->add_row(
                '<strong>Last info of searched range of dates : </strong>', $this->Common->taka_format($t_t_s), $this->Common->taka_format($t_t_c), $this->Common->taka_format($t_t_b), $this->Common->taka_format($this->due_in_date_range($range)), $this->Common->taka_format($t_c)
        );


        // $data = array(
        //     array('Today Cash Paid:', $account_today['cash_paid']),
        //     array('Today Bank Pay:', $account_today['bank_pay']),
        //     array('Monthly Cash Paid:', $account_monthly['cash_paid']),
        //     array('Monthly Bank Pay:', $account_monthly['bank_pay'])
        // );
        //Setting table template
        $tmpl = array(
            'table_open' => '<table class="table table-striped right-text-for-account">',
            'heading_cell_start' => '<th class="success">'
        );
        $this->table->set_template($tmpl);
        return $this->table->generate();
    }

// function total_bank_pay(){
// 	$query=$this->db->query("SELECT bank_pay FROM pub_memos");
//  foreach ($query->result() as $value) {
//  	$this->total_bank_pay+=$value->bank_pay;
//  }
//  return $this->total_bank_pay;
// }
// function total_due(){
// 	$query=$this->db->query("SELECT due FROM pub_memos");
//  foreach ($query->result() as $value) {
//  	$this->total_due+=$value->due;
//  }
//  return $this->total_due;
// }
// function totalsell(){
// 	$bankdue=$this->total_bank_pay;
// 	$cashpaid=$this->total_cash_paid;
// 	$totaldue=$this->total_due;
//  	$totalsell=$bankdue+$cashpaid+$totaldue;
//  	return $totalsell;
// }
}
