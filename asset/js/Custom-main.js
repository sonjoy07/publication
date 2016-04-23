/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*Memo Management*/
addContactButtonContent = "<span style='margin: 0px 13px;'>or Party Code* :</span><input type='number' id='fake_contact_ID' />" + addContactButtonContent;
$('#contact_ID_input_box').after(addContactButtonContent);
$("#fake_contact_ID").change(function () {
    var fake_contact_ID = $("#fake_contact_ID").val();
    $("#field-contact_ID").val(fake_contact_ID);
    //setting dropdown value
    var PartyName = $("#field-contact_ID [value=\"" + fake_contact_ID + "\"]").html();
    $("#contact_ID_input_box .chzn-single").attr("class", "chzn-single");
    $('#contact_ID_input_box .chzn-single>span').html(PartyName);

    var contact_ID = $("#field-contact_ID").val();
    if (!contact_ID) {
        $("#field-contact_ID").val("");
        $("#fake_contact_ID").val('');
        $("#contact_ID_input_box .chzn-single").attr("class", "chzn-single  chzn-default");
        $('#contact_ID_input_box .chzn-single>span').html("Select Party Name");
        alert(fake_contact_ID + " is not a valid Party Code");
    }
    set_dues_unpaid();
});


// setting issue date
var objDate = new Date();
if ($('#field-issue_date').val() == "")
    $('#field-issue_date').val(CurrentDate + " " + objDate.getHours() + ":" + objDate.getMinutes() + ":" + objDate.getSeconds());
$('#field-issue_date').change(function () {
    var objDate = new Date();
    var date_selected = $('#field-issue_date').val();
    $('#field-issue_date').val(date_selected + " " + objDate.getHours() + ":" + objDate.getMinutes() + ":" + objDate.getSeconds());
});

$('[name="dues_unpaid"]').after('<span id="dues_unpaid" style="line-height: 2.3;">0</span>');
$('[name="total"]').after('<span id="total" style="line-height: 2.3;">0</span>');
$('[name="due"]').after('<span id="due" style="line-height: 2.3;">0</span>');
$('[name="dues_unpaid"],[name="total"],[name="due"]').attr('type', 'hidden');
function set_dues_unpaid() {
    var contact_ID = $('[name="contact_ID"]').val();
    if (contact_ID == '') {
        $('[name="dues_unpaid"]').val('');
        $("#dues_unpaid").html('0');
        return 0;
    }
    $.ajax({
        url: previousDueFinderUrl + '/' + contact_ID + '/' + memo_ID
    })
            .done(function (data) {
                $('[name="dues_unpaid"]').val(data);
                $("#dues_unpaid").html(data);
            });
}
set_dues_unpaid();
$('[name="contact_ID"]').change(function () {
    set_dues_unpaid();
});
function TotalCal() {
    var discount = parseInt($('[name="discount"]').val());
    discount = (isNaN(discount)) ? 0 : discount;
    var book_return = parseInt($('[name="book_return"]').val());
    book_return = (isNaN(book_return)) ? 0 : book_return;
    var sub_total = parseInt($('[name="sub_total"]').val());
    sub_total = (isNaN(sub_total)) ? 0 : sub_total;
    var dues_unpaid = parseInt($('[name="dues_unpaid"]').val());
    dues_unpaid = (isNaN(dues_unpaid)) ? 0 : dues_unpaid;
    var total = sub_total - discount - book_return + dues_unpaid;
    $('[name="total"]').val(total);
    $("#total").html(total);
//    alert(sub_total+"+"+discount+"-"+dues_unpaid+"= " + total);
}
function DueCal() {
    var cash = parseInt($('[name="cash"]').val());
    cash = (isNaN(cash)) ? 0 : cash;
    var bank_pay = parseInt($('[name="bank_pay"]').val());
    bank_pay = (isNaN(bank_pay)) ? 0 : bank_pay;
    var total = parseInt($('[name="total"]').val());
    total = (isNaN(total)) ? 0 : total;
    var due = total - cash - bank_pay;
    $('[name="due"]').val(due);
    $("#due").html(due);
}
setInterval(function () {
    TotalCal();
    DueCal();
}, 1000);

