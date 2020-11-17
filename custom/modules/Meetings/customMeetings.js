$(document).ready(function(){
    $('#tab-actions').after($('<li></li>').append($("li#tab-actions li:nth-child(1)").clone() ));
});

//VUT
$(document).ready(function(){
    function display_link() {
        $("#link_invoice").remove();
        if ($('#aos_invoices_id_c').val() != '') {
            $("#aos_invoices_id_c").parent().append("<p id='link_invoice'><a  href='/index.php?module=AOS_Invoices&action=EditView&record=" + $("#aos_invoices_id_c").val()+ "' target='_blank'>Open Invoice</a></p>");
        }
    }
    display_link();
    YAHOO.util.Event.addListener("aos_invoices_id_c", "change", display_link);
});
//VUT