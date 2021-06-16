$(function () {
    $( document ).ready(function() {
        YAHOO.util.Event.addListener("type", "change", function(){
            changeLabelValuePEPromocode();
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
        changeLabelValuePEPromocode();
    });
});