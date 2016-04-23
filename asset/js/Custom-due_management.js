/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('[name="buyer_id"]').change(function () {
    $.ajax({
        url: "http://thejamunapub.com/Publication/index.php/admin/total_due/" + $('[name="buyer_id"]').val(),
        beforeSend: function (xhr) {
            xhr.overrideMimeType("text/plain; charset=x-user-defined");
        }
    })
            .done(function (data) {
                $("#total_due").html(data);
            });
});
