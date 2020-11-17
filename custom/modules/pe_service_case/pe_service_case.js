$(document).ready(function() {
    'use strict';
    /**Check folder installation photos */
    function generateUUID() {
        var d = new Date().getTime();
        if (window.performance && typeof window.performance.now === "function") {
            d += performance.now();
            ; //use high-precision timer if available
        }
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
        return uuid;
    };

    if ($('input[name="installation_photos_c"]').val() == "") {
        $('input[name="installation_photos_c"]').val(generateUUID());
    }

    /**Create Save and Edit button */
    $('input[id="SAVE"]').next().after(
        '&nbsp;<button type="button" id="save_and_edit" class="button saveAndEdit" title="Save and Edit" onClick="SUGAR.saveAndEdit(this);">Save and Edit</button>'
    )

    SUGAR.saveAndEdit = function (elem) {
        SUGAR.ajaxUI.showLoadingPanel();
        $("#EditView input[name='action']").val('Save');
        $.ajax({
            type: $("#EditView").attr('method'),
            url: $("#EditView").attr('action'),
            data: $("#EditView").serialize(),
            success: function (data) {
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        });
        return false;
    }

    /**Show link Account , Contact, Invoice,Lead */
    function display_link() {
        $("#link_account").remove();
        if ($('#billing_account_id').val() != '') {
            $("#billing_account").parent().append("<p id='link_account'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#billing_account_id").val()+ "' target='_blank'>Open Account</a></p>");
        }
        $("#link_contact").remove();
        if ($('#billing_contact_id').val() != '') {
            $("#billing_contact").parent().append("<p id='link_contact'><a  href='/index.php?module=Contacts&action=EditView&record=" + $("#billing_contact_id").val()+ "' target='_blank'>Open Contact</a></p>");
        }
        $("#link_invoice").remove();
        if ($('#aos_invoices_pe_service_case_1aos_invoices_ida').val() != '') {
            $("#aos_invoices_pe_service_case_1aos_invoices_ida").parent().append("<p id='link_invoice'><a  href='/index.php?module=AOS_Invoices&action=EditView&record=" + $("#aos_invoices_pe_service_case_1aos_invoices_ida").val()+ "' target='_blank'>Open Invoice</a></p>");
        }
        $("#link_lead").remove();
        if ($('#leads_pe_service_case_1leads_ida').val() != '') {
            $("#leads_pe_service_case_1leads_ida").parent().append("<p id='link_invoice'><a  href='/index.php?module=Leads&action=EditView&record=" + $("#leads_pe_service_case_1leads_ida").val()+ "' target='_blank'>Open Lead</a></p>");
        }
    }
    display_link();
    YAHOO.util.Event.addListener(["billing_account_id", "aos_invoices_pe_service_case_1aos_invoices_ida", "leads_pe_service_case_1leads_ida"], "change", display_link);

    /**Populate Contact-Invoice fields */
    var contact_fields = [
        'shipping_address_street',
        'shipping_address_city',
        'shipping_address_state',
        'shipping_address_postalcode',
        'shipping_address_country',
    ];
    var invoice_fields = [
        'invoice_billing_address_street',
        'invoice_billing_address_city',
        'invoice_billing_address_state',
        'invoice_billing_address_postalcode',
        'invoice_billing_address_country',
        'invoice_site_address_street',
        'invoice_site_address_city',
        'invoice_site_address_state',
        'invoice_site_address_postalcode',
        'invoice_site_address_country',
    ];
    function populate_fields() {
        var contact_id = $('#billing_contact_id').val().trim();
        var invoice_id = $('#aos_invoices_pe_service_case_1aos_invoices_ida').val().trim();
        var check = $(this).attr('id');
        if (check == 'aos_invoices_pe_service_case_1aos_invoices_ida' && invoice_id != '') {
            var url = '?entryPoint=populateAddress&module=AOS_Invoices&record_id='+invoice_id;
        } else {
            var url = '?entryPoint=populateAddress&module=Contacts&record_id='+contact_id;
        }
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                if (data == 'no data') return false;
                var result = JSON.parse(data);
                if (result['module'] == 'Contacts') {
                    $.each(result, function(key, value){
                        if (contact_fields.includes(key)) {
                            $('div[field="shipping_address_street"]').find('#'+key).val(value);
                        }
                    });
                }
                if (result['module'] == 'AOS_Invoices') {
                    $.each(result, function(key, value){
                        if (invoice_fields.includes(key)) {
                            $('#aos_invoices_pe_service_case_1aos_invoices_ida').closest('.row.edit-view-row').find('#'+key).val(value);
                        }
                    });
                }

            }
        });
    }
    YAHOO.util.Event.addListener("aos_invoices_pe_service_case_1aos_invoices_ida", "change", populate_fields);
    var i = 1;
    YAHOO.util.Event.addListener("billing_contact_id", "change", function() {
        if (i==3) {
            populate_fields();
            display_link();
            i=0;
        }
        console.log($('#billing_contact_id').val());
        i=i+1;
    });
    /**Add title address for Invoice */
    $('#invoice_billing_address_street').parent().parent().before('<div id="invoice_address_fieldset"><table style="width:100%;"><tr><td style="text-align: center;font-size: 16px;font-weight: 700;"><span>Billing address</span></td><td style="text-align: center;font-size: 16px;font-weight: 700;"><span>Site address</span></td></tr></table></div>');

    /**Sanden Equipment Type */
    if ($('#quote_type_c').val() == 'quote_type_sanden') {
        showSandenQuoteType();
    } else {
        hideSandenQuoteType();
    }

    $('#quote_type_c').change(function(){
        if ($('#quote_type_c').val() == 'quote_type_sanden') {
            showSandenQuoteType();
        } else {
            hideSandenQuoteType();
        }
    });

    /**S-Autocomplete  address */
    $("#billing_address_street, #shipping_address_street, #invoice_billing_address_street, #invoice_site_address_street").autocomplete({
        source: function( request, response ) {
            console.log(request["term"]);
            Math.floor((Math.random() * 3) + 1);

            if(request["term"].length > 3){
                $.ajax({
                    url: "/index.php?entryPoint=customGetAddress&address="+request["term"],
                    //data: {q:request.term},
                    //crossOrigin: true,
                    type: 'GET',
                    //async: false,
                    //crossDomain: true,
                    //dataType: 'jsonp',

                    success: function(data)
                    {
                        if(data == '' && typeof data == undefined)return;
                        var suggest =[];
                        var jsonObject = $.parseJSON(data);
                        for (i = 1; i < jsonObject.length; i++) {
                            suggest.push(jsonObject[i].name);
                        }
                        console.log(jsonObject);
                        response(suggest);
                    },
                    error: function(response){console.log("Fail");},
                });
            }
        },
        select: function( event, ui ) {
            // debugger;
            console.log(ui.item.value);
            var value =  ui.item.value.split(",");
            var address1 = value[0];
            var address2 = value[1];
            var key ='';

            // $("#billing_address_street").val(address1);
            $(this).val(address1);

            if (this.id.includes('invoice_billing')) {
                key = 'invoice_billing';
            } else if (this.id.includes('invoice_site')) {
                key = 'invoice_site';
            } else if (this.id.includes('shipping')) {
                key = 'shipping';
            } else {
                key = 'billing';
            }
            var address3 = address2.split("  ");

            $("#"+key+"_address_city").val(address3[0].trim());
            $("#"+key+"_address_state").val(address3[1].trim());
            $("#"+key+"_address_postalcode").val(address3[2].trim());

            return false;
        }
    });
    /**E-Autocomplete  address */

    $('#sanden_equipment_type_c').change(function(){
        $("#link_sanden_error").remove();
        $('#id_error_code_sanden_c').val('').change();
        if ($('#sanden_equipment_type_c').val() != '') {
            $('#is_error_code_sanden_c[value="bool_true"]').prop('checked', true).change();
            render_sanden_error_code($('#sanden_equipment_type_c').val());
        } else {
            $('#is_error_code_sanden_c[value="bool_false"]').prop('checked', true).change();
        }
    });

    $('#id_error_code_sanden_c').on('change',function (){
        // debugger;
        // alert('asdasdasd');
        $("#link_sanden_error").remove();
        if ($('#id_error_code_sanden_c').val() != '') {
            $("#slb_sanden_error_code").after("<a id='link_sanden_error' href='/index.php?module=pe_message_servicecase&action=EditView&record=" + $('#id_error_code_sanden_c').val()+ "' target='_blank'>Open Message</a>");
        }
    });

    // var selectedError = '<input name="slb_sanden_error_code" id="slb_sanden_error_code" style="width: 15%; margin-right: 10px;"></input>';
    var selectedError = '<select name="slb_sanden_error_code" data-live-search="true" id="slb_sanden_error_code" style="width: 25%; margin-right: 10px;"><option></option></select>';

    $('#is_error_code_sanden_c[value="bool_true"]').parent().after(selectedError);

    if ($('#is_error_code_sanden_c:checked').val() == 'bool_true') {
        $('#slb_sanden_error_code').show();
    } 
    if ($('#is_error_code_sanden_c:checked').val() == 'bool_false') {
        $('#slb_sanden_error_code').val('').hide();
        $('#id_error_code_sanden_c').val('').change();
        $("#link_sanden_error").remove();
    }
    $('input[id="is_error_code_sanden_c"]').on('change', function(){
        if ($('#is_error_code_sanden_c:checked').val() == 'bool_true') {
            $('#slb_sanden_error_code').show();
        } 
        if ($('#is_error_code_sanden_c:checked').val() == 'bool_false') {
            $('#slb_sanden_error_code').val('').hide();
            $('#error_content_c').val('');
            $('#manufacturer_judgement_c').val('');
            $('#manufacturer_diagnostic_c').val('');
            $('#id_error_code_sanden_c').val('').change();
            $("#link_sanden_error").remove();
            autosize.update($('#manufacturer_diagnostic_c'));
            autosize.update($('#manufacturer_judgement_c'));
        }
    });

    /**Page load - show Error code  */
    if ($('#id_error_code_sanden_c').val() !== undefined && $('#sanden_equipment_type_c').val() != '') {
        render_sanden_error_code($('#sanden_equipment_type_c').val(), $('#id_error_code_sanden_c').val());
        $("#link_sanden_error").remove();
        if ($('#id_error_code_sanden_c').val() != '') {
            $("#slb_sanden_error_code").after("<a id='link_sanden_error' href='/index.php?module=pe_message_servicecase&action=EditView&record=" + $('#id_error_code_sanden_c').val()+ "' target='_blank'>Open Message</a>");
            // $('#slb_sanden_error_code').find("option[id='"+$('#id_error_code_sanden_c').val()+"']").attr('selected', true);
        }
    }
    /**Page load - show Error code  */

    /**Sanden - Autocomplete Error code */
    // var content_sanden_error;
    // $('#slb_sanden_error_code').autocomplete({
    //     minLength: 0,
    //     source: function (request, response) {
    //             $.ajax({
    //                 url: '?entryPoint=getMessageServiceCase',
    //                 type: 'POST',
    //                 data: {
    //                     sanden_equipment_type: $('#sanden_equipment_type_c').val(),
    //                     is_sanden_error_code: request['term'],
    //                     page_load: '0',
    //                 },
    //                 success: function(result) {
    //                     if(result == '' || typeof result == undefined) return;
    //                     var suggest =[];
    //                     var jsonObject = $.parseJSON(result);
    //                     for (i = 0; i < jsonObject.length; i++) {
    //                         suggest.push(jsonObject[i].error_code);
    //                     }
    //                     response(suggest);
    //                     content_sanden_error = jsonObject;
    //                 },
    //                 error: function(response) {console.log("Fail");}
    //             });
    //     },
    //     select: function(event, ui) {
    //         var error_code = ui.item.value;
    //         for (let index = 0 ;index < content_sanden_error.length ; index++ ) {
    //             if (error_code == content_sanden_error[index].error_code) {
    //                 $('#id_error_code_sanden_c').val(content_sanden_error[index].id).change();
    //                 $('#error_content_c').val(content_sanden_error[index].error_content);
    //                 $('#manufacturer_diagnostic_c').val(content_sanden_error[index].manufacturer_diagnostic);
    //                 $('#manufacturer_judgement_c').val(content_sanden_error[index].manufacturer_judgement);
    //                 autosize.update($('#manufacturer_diagnostic_c'));
    //                 autosize.update($('#manufacturer_judgement_c'));
    //             }
    //         }
    //     },
    // });

    $('#slb_sanden_error_code').change(function() {
        // debugger;
        var id_messsage_sanden_errorcode = $('#slb_sanden_error_code').children("option:selected").attr('id');
        var content_sanden_error = window.sanden_error;
        if (id_messsage_sanden_errorcode !== undefined) {
            $.each(content_sanden_error,function(index,v){
                if (content_sanden_error[index].id == id_messsage_sanden_errorcode) {
                    $('#id_error_code_sanden_c').val(content_sanden_error[index].id).change();
                    $('#error_content_c').val(content_sanden_error[index].error_content);
                    $('#manufacturer_diagnostic_c').val(content_sanden_error[index].manufacturer_diagnostic);
                    $('#manufacturer_judgement_c').val(content_sanden_error[index].manufacturer_judgement);
                    autosize.update($('#manufacturer_diagnostic_c'));
                    autosize.update($('#manufacturer_judgement_c'));
                    return;
                } 
            });
        } else {
            $('#id_error_code_sanden_c').val('');
        }
    });

    function showSandenQuoteType() {
        $('div[field="sanden_equipment_type_c"]').parent().show();
        $('div[field="is_error_code_sanden_c"]').parent().show();
        $('div[field="possible_solution_sanden_c"]').parent().show();
        $('div[field="error_content_c"]').parent().show();
        $('div[field="manufacturer_diagnostic_c"]').parent().show();
        $('div[field="manufacturer_judgement_c"]').parent().show();
    }

    function hideSandenQuoteType() {
        $('div[field="sanden_equipment_type_c"]').parent().hide();
        $('div[field="is_error_code_sanden_c"]').parent().hide();
        $('div[field="manufacturer_diagnostic_c"]').parent().hide();
        $('div[field="error_content_c"]').parent().hide();
        $('div[field="possible_solution_sanden_c"]').parent().hide();
        $('div[field="manufacturer_judgement_c"]').parent().hide();
        $('#id_error_code_sanden_c').val('').change();
        $("#link_sanden_error").remove();
        $('#error_content_c').val('');
        $('#slb_sanden_error_code').val('');
        $('#manufacturer_judgement_c').val('');
        $('#manufacturer_diagnostic_c').val('');
        $('#sanden_equipment_type_c').find('option[value=""]').attr('selected', true);
    }

    function render_sanden_error_code(sanden_equipment_type, id_messsage_sanden_errorcode){
        SUGAR.ajaxUI.showLoadingPanel();
        $.ajax({
            url: '?entryPoint=getMessageServiceCase',
            type: 'POST',
            data: {
                sanden_equipment_type: sanden_equipment_type,
            },
            success: function(result) {
                // debugger;
                if(result == '' || typeof result == undefined) {
                    SUGAR.ajaxUI.hideLoadingPanel();
                    return;
                }
                var jsonObject = $.parseJSON(result);
                    window.sanden_error = jsonObject;
                $(document).find("#slb_sanden_error_code").empty();
                $('#slb_sanden_error_code').append($('<option>', {
                    value: '',
                    text: ''
                }));
                $.each(jsonObject,function(k,v){
                    $(document).find("#slb_sanden_error_code").append($('<option>', {
                        value: v.error_code,
                        text: v.error_code,
                        id: v.id
                    }));
                });
                if (id_messsage_sanden_errorcode != '') {
                    $('#slb_sanden_error_code').find("option[id='"+id_messsage_sanden_errorcode+"']").attr('selected', true);
                }
                SUGAR.ajaxUI.hideLoadingPanel();
            },
        });
    }
})

//Relate Message Service Case
$(document).ready(function(){
    'use strict';
    /**Hidden Panel "Hidden" */
    $('#id_message_servicecase_c').closest('.panel.panel-default').hide();
    /**Change select Message */
    YAHOO.util.Event.addListener("slb_fault_type", "change", function(){
    var message_servicecase_id = $('#slb_fault_type').children("option:selected").attr('id');
        $(document).find("#message_c").val($(this).val());
        if (message_servicecase_id !== undefined) {
            $('#id_message_servicecase_c').val(message_servicecase_id);
        } else {
            $('#id_message_servicecase_c').val('');
        }
        display_link_message_template();
    });
    function display_link_message_template(){
        $('.link_open_service_message').remove();
        if($('#id_message_servicecase_c').val() != ''){
            $('div[field="fault_type_c"]').append('<a class="link_open_service_message" target="_blank" href="/index.php?module=pe_message_servicecase&action=EditView&record='+ $('#id_message_servicecase_c').val()+'">Open Link Message</a>');
        }
    }
    /**Open Dialog Edit message */
    $("#fault_type_c").hide();
    $('#fault_type_c').parent().append("<br><select style='width:68%;' name='slb_fault_type' id='slb_fault_type' style='width:100%;'><option></option></select>");
    $('div[field="fault_type_c"]').append('<button type="button" id="dialog_message_servicecase_button" class="button sg-change-owner-user" title="" onClick="return false;" >Quick Edit Message <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button><br>');
    YAHOO.util.Event.addListener("dialog_message_servicecase_button","click", function(){
        SUGAR.ajaxUI.showLoadingPanel();
        $("#ajaxloading_mask").css("position",'fixed');
        $.ajax({
            url: 'index.php?entryPoint=getMessageServiceCase' ,
            type: 'POST',
            data: 
            {
                action: 'read',
            },
            async: true,
            success: function(result) {        
                render_select_message(result);
                SUGAR.ajaxUI.hideLoadingPanel();
                $('#select_title_message_servicecase').find("option[value='"+$('#id_message_servicecase_c').val()+"']").attr('selected', true);
                change_select_popup_fault_type();
                $("#dialog_message_servicecase").dialog("open");
            }
        }); 
        return false;
    });
    /**Dialog Edit Message */
    $("#dialog_message_servicecase").dialog({
        autoOpen: false,
        width: 712,
        height:478,
        buttons: {
            Save: function(){
                SUGAR.ajaxUI.showLoadingPanel();
                $("#ajaxloading_mask").css("position",'fixed');
                /**New */
                if ($("#id_message_service").val() =='') {
                    if ($("#title_message_servicecase").val() == '') {
                        alert('Could you insert title please?');
                        SUGAR.ajaxUI.hideLoadingPanel();
                        return false;
                    }
                    $.ajax({
                        url: 'index.php?entryPoint=getMessageServiceCase',
                        type: 'POST',
                        data: {
                            id: $("#id_message_service").val(),
                            action: 'create',
                            message: encodeURIComponent($("#content_message_servicecase").val()),
                            title: encodeURIComponent($("#title_message_servicecase").val())
                        },
                        success: function(result) {
                            render_select_message(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                            $("#message_c").val($("#content_message_servicecase").val());
                            $('#slb_fault_type').find("option[id='"+$('#id_message_servicecase_c').val()+"']").attr('selected', true);
                        }
                    });
                } 
                /**update */
                else {
                    $.ajax({
                        url: 'index.php?entryPoint=getMessageServiceCase' ,
                        type: 'POST',
                        data: 
                        {
                            id: $("#id_message_service").val(),
                            action: 'update',
                            message: encodeURIComponent($("#content_message_servicecase").val()),
                            title: encodeURIComponent($("#title_message_servicecase").val())
                        },
                        success: function(result) {                         
                            render_select_message(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                            $('#id_message_servicecase_c').val($("#id_message_service").val());
                            $("#message_c").val($("#content_message_servicecase").val());
                            $('#slb_fault_type').find("option[id='"+$('#id_message_servicecase_c').val()+"']").attr('selected', true);
                        }
                    }); 
                }
                display_link_message_template();    
                $(this).dialog('close');
            },
            Create: function(){
                $('#select_title_message_servicecase').find("option[value='']").attr('selected', true);
                $("#id_message_service").val('');
                $("#content_message_servicecase").val('');
                $("#title_message_servicecase").val('');
            },
            Insert: function(){  
                $('#id_message_servicecase_c').val($("#id_message_service").val());
                $('#slb_fault_type').find("option[id='"+$("#id_message_service").val()+"']").attr('selected', true);
                $("#message_c").val($("#content_message_servicecase").val());
                display_link_message_template();
                $(this).dialog('close');
            },
            Delete: function(){
                var ok = confirm('Do you want delete this message !');
                if (ok){
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    $.ajax({
                        url: 'index.php?entryPoint=getMessageServiceCase' ,
                        type: 'POST',
                        data: 
                        {
                            id: $("#id_message_service").val(),
                            action: 'delete',
                            content: encodeURIComponent($("#content_message_servicecase").val()),
                            title: encodeURIComponent($("#title_message_servicecase").val())
                        },
                        success: function(result) {                         
                            render_select_message(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                            $("#content_message_servicecase").val('');
                            $("#title_message_servicecase").val('');
                            $("#id_message_service").val('');
                        }
                    }); 
                }
            },
            Cancel: function(){
                $('#slb_fault_type').find("option[id='"+$('#id_message_servicecase_c').val()+"']").attr('selected', true);
                $(this).dialog('close');
            },
        }
    });

    /**Auto first time: load page */
    $.ajax({
        url: 'index.php?entryPoint=getMessageServiceCase',
        type: 'POST',
        data: {
            action: 'read',
        },
        success: function(data){
            render_select_message(data);
            display_link_message_template();
            $('#slb_fault_type').find("option[id='"+$('#id_message_servicecase_c').val()+"']").attr('selected', true);
        }
    });

    YAHOO.util.Event.addListener("select_title_message_servicecase", "change", change_select_popup_fault_type);

    /**change Select popup Fault type */
    function change_select_popup_fault_type() {
        var id = $('#select_title_message_servicecase').val();
        if(id == '') return false;
        var title = $('#select_title_message_servicecase option:selected').text();
        $("#title_message_servicecase").val(title);
        $("#id_message_service").val(id);
        $("#content_message_servicecase").val(window.data_message[id].message);
    }
    /**Render message Fault Type*/
    function render_select_message(result){
        var data_result = $.parseJSON(result);
        if(typeof(window.data_message) == 'undefined') {
            window.data_message = data_result;
        }
        var id_selected = '';
        /**Popup */
        $('#select_title_message_servicecase').empty();
        $('#select_title_message_servicecase').append($('<option>', {
            value: '',
            text: ''
        }));
        /**Select box */
        $(document).find("#slb_fault_type").empty();
        $('#slb_fault_type').append($('<option>', {
            value: '',
            text: ''
        }));

        $.each(data_result,function(k,v){
            $(document).find("#slb_fault_type").append($('<option>', {
                value: v.message,
                text: v.name,
                id: k
            }));
            $('#select_title_message_servicecase').append($('<option>', {
                value: k,
                text: v.name
            }));
            if(typeof(window.data_message[k]) == 'undefined'){
                id_selected = k;
            }
        });
        if(id_selected != '') {
            $('#slb_fault_type').find("option[id='"+id_selected+"']").attr('selected', true);
            $('#id_message_servicecase_c').val(id_selected);
            display_link_message_template();
        }
        window.data_message = data_result;
    }

})

