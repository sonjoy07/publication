$(function(){

var objDate = new Date();
if ($('#field-date').val() == "")
    $('#field-date').val(CurrentDate + " " + objDate.getHours() + ":" + objDate.getMinutes() + ":" + objDate.getSeconds());
$('#field-date').change(function () {
    var objDate = new Date();
    var date_selected = $('#field-date').val();
    $('#field-date').val(date_selected + " " + objDate.getHours() + ":" + objDate.getMinutes() + ":" + objDate.getSeconds());
});


   
});