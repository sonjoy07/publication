<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common extends CI_Model {

    function int_to_bangla_int($int) {
        $bangla_int_char = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        $product = '';
        if (is_int($int)) {
//           for(i=)
            return $product;
        } else
            return 'FALSE';
    }
    
    function taka_format($amount = 0) {
        $tmp = explode(".", $amount);       // for float or double values
        $strMoney = "";
        $amount = $tmp[0];
        $strMoney .= substr($amount, -3, 3);
        $amount = substr($amount, 0, -3);
        while (strlen($amount) > 0) {
            $strMoney = substr($amount, -2, 2) . "," . $strMoney;
            $amount = substr($amount, 0, -2);
        }
        if (isset($tmp[1])) {         // if float and double add the decimal digits here.
            $tmp[1] = number_format($tmp[1], 2, '.', '');
            return $strMoney . "." . $tmp[1];
        }
        return $strMoney;
    }
    
}
