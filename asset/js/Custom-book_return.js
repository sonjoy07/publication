/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


// setting issue date
var objDate = new Date();
if ($('#field-issue_date').val() == "")
    $('#field-issue_date').val(CurrentDate + " " + objDate.getHours() + ":" + objDate.getMinutes() + ":" + objDate.getSeconds());
$('#field-issue_date').change(function () {
    var objDate = new Date();
    var date_selected = $('#field-issue_date').val();
    $('#field-issue_date').val(date_selected + " " + objDate.getHours() + ":" + objDate.getMinutes() + ":" + objDate.getSeconds());
});

$('[name="returned_book_ID"]').change(function () {
    $.ajax({
        url: webServiceUrlTotal_book_return + $('[name="returned_book_ID"]').val(),
        beforeSend: function (xhr) {
            xhr.overrideMimeType("text/plain; charset=x-user-defined");
        }
    })
            .done(function (data) {
                $("#total_book_return").html(data);
            });
});
