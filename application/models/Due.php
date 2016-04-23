<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Due
 *
 * @author MD. Mashfiq
 */
class Due extends CI_Model {
    /*
     * adding due pamente to the due 
     * 
     * @param int
     * @param int
     * @return void
     */

    function add_payment($memo_ID, $amount,$date) {
        $db_tables = $this->config->item('db_tables');
        $data = array(
            'momo_ID' => $memo_ID,
            'due_paid_amount' => $amount
        );
        $this->db->insert($db_tables['pub_due_payment_ledger'], $data);
    }

    /*
     * updating due pamente to the due 
     * 
     * @param int
     * @param int
     * @return void
     */

    function update_payment($memo_ID, $amount) {
        $db_tables = $this->config->item('db_tables');
        $data = array(
            'due_paid_amount' => $amount,
        );
        $this->db->where('momo_ID', $memo_ID);
        $this->db->update($db_tables['pub_due_payment_ledger'], $data);
    }

}
