/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//
//$(document).ready(function(){
//
//   $('[data-price]').change(function(){
//      var total = 0;
//      $('[data-index]').each(function(index,value){
//        quantity = $('[data-index="'+index+'"]').val();
//        price = $('[data-index="'+index+'"]').attr('data-price');
//        total += parseInt(quantity)*parseInt(price);
//      });
//      $('[name="sub_total"]').val(total);
//alert('Done');
//    });
//
//});

jQuery('[data-price]').change(function(){
var total = 0;
    $('[data-index]').each(function(index,value){
      quantity = $('[data-index="'+index+'"]').val();
      price = $('[data-index="'+index+'"]').attr('data-price');
      total += parseInt(quantity)*parseInt(price);
    });
    $('[name="sub_total"]').val(total);
    $('#sub_total').html(total);
});