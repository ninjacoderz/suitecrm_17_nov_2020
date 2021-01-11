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

    //thienpb code event change
    $("body").on("change","#repeating_c",function(e){
        e.preventDefault();
        if($(this).val() != ''){
            var date_end = $("#date_end_date").attr("value").split(" ");
            var day = new Date(date_end[0].split("/").reverse().join("/"));
            $("#date_end_date").val(day.getFullYear() +1);

            switch ( $(this).val()) {
                case 'every_day':
                    $("#date_end_date").val(day.getDate()+'/'+day.getMonth()+'/'+(day.getFullYear() +1)).change();
                    break;
                case 'every_week':
                    $("#date_end_date").val((day.getDate() + 7)+'/'+(day.getMonth()+1)+'/'+(day.getFullYear())).change();
                    break;
                case 'every_month':
                    $("#date_end_date").val(day.getDate()+'/'+(day.getMonth() +2)+'/'+(day.getFullYear())).change();
                    break;
                case 'every_3_month':
                    $("#date_end_date").val(day.getDate()+'/'+(day.getMonth() +4)+'/'+(day.getFullYear())).change();
                    break;
            }
        }
    })
});
//VUT