<?php

class Office_cost extends CI_Model {
    
function today_office_cost($date=''){
        
        if (empty($date)) {
            $date = date('Y-m-d');
        }
     
        $db_tables = $this->config->item('db_tables');
        $this->db->select('SUM(amount) as cost');
        $this->db->from($db_tables['pub_cost']);
        $this->db->where('DATE(date)="'.$date.'"');
        
        $query = $this->db->get();
        
        $db_rows = $query->result_array();
        foreach ($db_rows as $row) {
            $cost=$row['cost'];
        }
     
      return $cost;
}

function monthly_office_cost(){
    
      $date =date('Y-m-d');
     
        $db_tables = $this->config->item('db_tables');
        $this->db->select('SUM(amount) as cost');
        $this->db->from($db_tables['pub_cost']);
        $this->db->where('MONTH(date) = MONTH(CURDATE()) && YEAR(date) = YEAR(CURDATE())');
        
        $query = $this->db->get();
        
        $db_rows = $query->result_array();
        foreach ($db_rows as $row) {
            $cost=$row['cost'];
        }

      return $cost;
    
}

function previous_month_office_cost(){
    
      $date =date('Y-m-d');
     
        $db_tables = $this->config->item('db_tables');
        $this->db->select('SUM(amount) as cost');
        $this->db->from($db_tables['pub_cost']);
        $this->db->where('MONTH(date) = MONTH(CURDATE())-1 && YEAR(date) = YEAR(CURDATE())');
        
        $query = $this->db->get();
        
        $db_rows = $query->result_array();
        foreach ($db_rows as $row) {
            $cost=$row['cost'];
        }

      return $cost;
    
}


function search_result($range){
    $db_tables = $this->config->item('db_tables');
    $this->load->library('table');
    $range = "DATE(date) BETWEEN $range";
    
     $table_template = array(
                'table_open' => '<table class="table table-bordered table-striped ">',
                'heading_cell_start' => '<th class="success" >'
            );
     $this->table->set_template($table_template);
     $this->table->set_heading("ID","Title", "Description", "Amount", "Date");
     
     $sql = "SELECT * from pub_cost where $range UNION ALL "
             . "SELECT '','','<strong>Total:</strong>',sum(amount),'' from pub_cost where $range";

    $data = $this->db->query($sql)->result_array();
    
    $data_table = $this->table->generate($data);
    return $data_table;
    
}

}