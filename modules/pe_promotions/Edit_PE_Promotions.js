$(function () {
    $( document ).ready(function() {
        YAHOO.util.Event.addListener("type", "change", function(){
            changeLabelValuePEPromocode();
        });

        YAHOO.util.Event.addListener("aos_invoices_pe_promotions_1aos_invoices_ida", "change", function(){
            show_link_invoice();
        });

        function changeLabelValuePEPromocode() {
            var type = $('#type').val();
            $("#value").attr("placeholder", "9.99");
            switch (type) {
                case 'order_fixed_grand_total_off':
                    $('#value').parents('.edit-view-row-item').find('.label').text('Amount off:*');
                    break;
                default:
                    $('#value').parents('.edit-view-row-item').find('.label').text('Percentage off (%):*');
                    break;
            }
          
        }
        function show_link_invoice(){

            if($('#aos_invoices_pe_promotions_1aos_invoices_ida').val() !== ''){
                $('div[field="aos_invoices_pe_promotions_1_name"').find('a.invoice-link').remove();
                $('div[field="aos_invoices_pe_promotions_1_name"').append('<br><a class="invoice-link" target="_blank" href="/index.php?module=AOS_Invoices&offset=1&stamp=1557198792054964100&return_module=AOS_Invoices&action=EditView&record=' +$('#aos_invoices_pe_promotions_1aos_invoices_ida').val() +'">Open Invoice</a>');
            }
        };
        
        show_link_invoice();
        changeLabelValuePEPromocode();
    });
});