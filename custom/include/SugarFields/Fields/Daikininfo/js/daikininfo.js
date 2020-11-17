
function updateInputDaikinValue(){
    var listDaikinInfoObject = new Array();
    $("#daikininfo tr").each(function() {
        if($( this ).find("select[name='daikin_info_product_name']").val() !== undefined && $( this ).find("select[name='daikin_info_product_name']").val() != "") {

            var productinfo = new Object();
            productinfo.product_name = $(this).find("select[name='daikin_info_product_name']").val();
            productinfo.veet_code = $(this).find("input[name='veet_code']").val();
            productinfo.indoor_model = $(this).find("input[name='indoor_model']").val();
            productinfo.indoor_serial = $(this).find("input[name='indoor_serial']").val();
            productinfo.outdoor_model = $(this).find("input[name='outdoor_model']").val();
            productinfo.outdoor_serial = $(this).find("input[name='outdoor_serial']").val();
            productinfo.date_delivered = $(this).find("input[name='date_delivered']").val();
            productinfo.date_ordered = $(this).find("input[name='date_ordered']").val();
            productinfo.order_confirmed = $(this).find("input[name='order_confirmed']").is(":checked");
            productinfo.wifi = $(this).find("input[name='wifi']").is(":checked");

            listDaikinInfoObject.push(productinfo);
        }
    });
    $('input[name="daikin_product_infomation_c"]').val(encodeURIComponent(JSON.stringify(listDaikinInfoObject)));
}

$(function () {
    'use strict';

    $("#daikininfo").on("focusout", "input", updateInputDaikinValue);
    $("#daikininfo").on("change", "select[name='daikin_info_product_name']",  function(){
        //Thienpb code - add new daikin option
        if($(this).val() == 'Nexura 2.5kW'){
            $(this).parent().parent().find('input[name="veet_code"]').val('FVXG25K / RXG25L');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FVXG25K2V1B');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXG25L2V1B');
        }
        if($(this).val() == 'Nexura 3.5kW'){
            $(this).parent().parent().find('input[name="veet_code"]').val('FVXG35K / RXG35L');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FVXG35K2V1B');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXG35L2V1B');
        }
        if($(this).val() == 'Nexura 4.8kW'){
            $(this).parent().parent().find('input[name="veet_code"]').val('FVXG50k / RXG50L');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FVXG50K2V1B ');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXG50L2V1B');
        }
        //end

        if($(this).val() == "US7 2.5kW small" ) {
            $(this).parent().parent().find('input[name="veet_code"]').val('FTXZ25N / RXZ25N');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FTXZ25NV1B');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXZ25NV1B9');
        }
        if( $(this).val() == "US7 3.5kW medium" ) {
            $(this).parent().parent().find('input[name="veet_code"]').val('FTXZ35N / RXZ35N');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FTXZ35NV1B');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXZ35NV1B9');
        }
        if( $(this).val() == "US7 5.0kW large" ) {
            $(this).parent().parent().find('input[name="veet_code"]').val('FTXZ50N / RXZ50N');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FTXZ50NV1B');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXZ50NV1B9');
        }
        if( $(this).val() == "Cora 2.5kW") {
            $(this).parent().parent().find('input[name="veet_code"]').val('FTXM25Q / RXM25Q');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FTXM25QVMA');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXM25QVMA');
        }

        if( $(this).val() == "Cora 2kW") {
            $(this).parent().parent().find('input[name="veet_code"]').val('FTXM20Q / RXM20Q');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FTXM20QVMA');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXM20QVMA');
        }
        if( $(this).val() == "Cora 3.5kW") {
            $(this).parent().parent().find('input[name="veet_code"]').val('FTXM35Q / RXM35Q');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FTXM35QVMA');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXM35QVMA');
        }

        if( $(this).val() == "Cora 4.6kW") {
            $(this).parent().parent().find('input[name="veet_code"]').val('FTXM46Q / RXM46Q');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FTXM46QVMA');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXM46QVMA');
        }
        if( $(this).val() == "Cora 5kW") {
            $(this).parent().parent().find('input[name="veet_code"]').val('FTXM50Q / RXM50Q');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FTXM50QVMA');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXM50QVMA');
        }

        if( $(this).val() == "Cora 6kW") {
            $(this).parent().parent().find('input[name="veet_code"]').val('FTXM60Q / RXM60Q');
            $(this).parent().parent().find('input[name="indoor_model"]').val('FTXM60QVMA');
            $(this).parent().parent().find('input[name="outdoor_model"]').val('RXM60QVMA');
        }
        updateInputDaikinValue();
    });

    $("#delivery_notes_c").parent().parent().after('<br><button class="button primary" id="sendDaikinInfoEmail"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Send mail </button>');

    $('#sendDaikinInfoEmail').on('click', function (event){
        event.preventDefault();
        //event.append();
        $('#sendDaikinInfoEmail span.glyphicon-refresh').removeClass('hidden');
        var daikinLineItems = $('input[name="daikin_product_infomation_c"]').val();
        var delivery_contact_name = $('#delivery_contact_name_c').val();
        var delivery_contact_address = $("#shipping_address_street").val();
        var delivery_contact_suburb = $("#shipping_address_city").val();
        var delivery_contact_postcode  = $("#shipping_address_postalcode").val();
        var delivery_contact_phone_numbe = $('#delivery_contact_phone_numbe_c').val();
        var delivery_notes = $('#delivery_notes_c').val();
        var invoice_number = $('div[field="number"]').text();
        var build_url=  "?entryPoint=customSendEmail&mail_format=daikin_info&daikinLineItems=" + daikinLineItems ;
        build_url += '&delivery_contact_name='+ delivery_contact_name ;
        build_url += '&delivery_contact_address='+ delivery_contact_address ;

        build_url += '&delivery_contact_suburb='+ delivery_contact_suburb ;
        build_url += '&delivery_contact_postcode='+ delivery_contact_postcode ;

        build_url += '&delivery_contact_phone_numbe='+ delivery_contact_phone_numbe ;
        build_url += '&delivery_notes='+ delivery_notes ;
        build_url += '&delivery_contact_postcode='+ delivery_contact_postcode ;
        build_url += '&invoice_number=' + encodeURIComponent(invoice_number);

        $.ajax({
            url: build_url,
            context: document.body
        }).done(function(data) {
            $('#sendDaikinInfoEmail span.glyphicon-refresh').addClass('hidden');
            alert("Email sent!");
        });

        return false;
    });
});