(function ($) {
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        var module_name = $(source).attr('data-module');
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();

        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1' ;
        url_email += ((record_id!="")? ("&record_id="+record_id):"");
        url_email += ((email_type!="")? ("&email_type="+email_type):"") ;
        url_email += ((module_name!="")? ("&module_name="+module_name):"") ;
        
        $.ajax({
            type: "GET",
            cache: false,
            url: url_email,
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();
  
            var email_address_to = '';
            var name_address_to = 'COPE Sensitive Freight';
            if(email_type == 'authority_to_leave') {
                var state_Destination_Address =($("#destination_address_state").val().trim() != '' )? $("#destination_address_state").val().trim().toLowerCase() : '';
                switch (state_Destination_Address) {
                    case "act":
                        email_address_to = "actops@cope.com.au";
                    break;
                    case "nsw":
                        email_address_to = "nsw@cope.com.au";
                    break;
                    case "qld":
                        email_address_to = "qldcust@cope.com.au";
                    break;
                    case "sa":
                        email_address_to = "sa@cope.com.au";
                    break;
                    case "vic":
                    case "victory":
                        email_address_to = "vic@cope.com.au";
                    break;
                    case "wa":
                        email_address_to = "wa@cope.com.au";
                    break;
                 }
            }

            $(self.emailComposeView).find('input[name="to_addrs_names"]').val(name_address_to+'<'+email_address_to + '>');    
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('input[name="return_id"]').val(record_id);
            $(self.emailComposeView).find('input[name="return_module"]').val(module_name);
            $(self.emailComposeView).on('sentEmail', function (event, composeView) {
                composeBox.hide();
                composeBox.remove();
            });
            $(self.emailComposeView).on('disregardDraft', function (event, composeView) {
            if (typeof messageBox !== "undefined") {
                var mb = messageBox({size: 'lg'});
                mb.setTitle(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
                mb.setBody(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
                mb.on('ok', function () {
                mb.remove();
                composeBox.hide();
                composeBox.remove();
                });
                mb.on('cancel', function () {
                mb.remove();
                });
                mb.show();
            } else {
                if (confirm(self.translatedErrorMessage)) {
                composeBox.hide();
                composeBox.remove();
                }
            }
            });


            composeBox.on('cancel', function () {
            composeBox.remove();
            });
            composeBox.on('hide.bs.modal', function () {
            composeBox.remove();
            });
        }).fail(function (data) {
            composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
        });
        return $(self);
    };
}(jQuery));

$(function () {
    'use strict';
    //Generate uinique id
    $( document ).ready(function() {
        // button send email authority_to_leave
        var html_button_authority_to_leave = '&nbsp;<button style="background:green;" data-record-id="'+$('input[name="record"]').val()+'" data-email-type="authority_to_leave" data-module-name="pe_warehouse_log" type="button" id="authority_to_leave" class="button authority_to_leave" title="Authority To Leave"  onClick="$(document).openComposeViewModal(this);" ><span class="glyphicon glyphicon-envelope"></span> Authority To Leave </button>&nbsp;';
        $("#btn_view_change_log").before(html_button_authority_to_leave);

        var existed = false;
        $('#SAVE').attr('onclick','return false;');
        $('#SAVE').click(function(e){
            $.ajax({
                url: 'index.php?entryPoint=checkDuplicateDD&name='+$("#name").val()+'&record='+$("input[name='record']").val(),
                async : false,
                success: function(result){
                    if(result == 'existed'){
                        alert("Delivery Docket is existed.");
                        return false;
                    }else{
                        if($("#destination_warehouse_id").val() ==''){
                            var dialog_message = '<span>Destination Warehouse is blank. Do you want to save this!</span>';
                            var dialog = $(dialog_message).dialog({
                                buttons: {
                                    "Yes": function() {
                                        dialog.dialog('close');
                                        var _form = document.getElementById('EditView');
                                        _form.action.value='Save';
                                        SUGAR.ajaxUI.submitForm(_form);
                                        return check_form('EditView');
                                    },
                                    "Cancel":  function() {
                                        dialog.dialog('close');
                                    }
                                }
                            });
                        }else{
                            var _form = document.getElementById('EditView');
                            _form.action.value='Save';
                            SUGAR.ajaxUI.submitForm(_form);
                            return check_form('EditView');
                        }
                    }
                }
            });
        })
        
        $('.product_group').sortable({
            cancel: ".product_group0_head",
            axis :"y",		
            handle: '.handle',
            update: function(event, ui) {
                var i = 1;
                $("input[name^=product_number]").each(function(){
                    $(this).val(i);
                    i++;
                });
            }
        });
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
        if ($('input[name="installation_pdf_c"]').val() == "") {
            $('input[name="installation_pdf_c"]').val(generateUUID());
        }

        functionQS();
        function functionQS()
        {
            sqs_objects['EditView_billing_account']= {
                "form":"EditView",
                "method":"query",
                "modules":[
                "Accounts"
                ],
                "group":"or",
                "field_list":[
                "name",
                "id",
                "shipping_address_street",
                "shipping_address_city",
                "shipping_address_state",
                "shipping_address_postalcode",
                "shipping_address_country",
                
                ],
                "populate_list":[
                "EditView_billing_account",
                "billing_account_id",
                "billing_address_street",
                "billing_address_city",
                "billing_address_state",
                "billing_address_postalcode",
                "billing_address_country",
                ],
                "conditions":[
                {
                "name":"name",
                "op":"like_custom",
                "end":"%",
                "value":""
                }
                ],
                "required_list":[
                "billing_account_id"
                ],
                "order":"name",
                "limit":"30",
                "no_match_text":"No Match"
            };
            enableQS();
        }

        SUGAR.util.doWhen(
            "typeof(sqs_objects) != 'undefined' && typeof(sqs_objects['EditView_billing_account']) != 'undefined'",
            enableQS
        );

    })

    $( document ).ready(function() {
        $('#status_c').parent().siblings('.label').append('<br> <button type="button" class="button primary" id="getWLStatus"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Get Status</button>');
        $("#getWLStatus").click(function(){   
            $.ajax({
                url: 'index.php?entryPoint=getWarehouseLogStatus&connot='+$('#connote').val() +"&carrier="+$('#carrier').val(),
                success: function(data){
    
                    if(data !== '') {
                        var json_data = JSON.parse(data);
                        $('#status_c').val(json_data.status);
                        $('#delivery_status_date_c').val(json_data.date);
                        $('#delivery_status_location_c').val(json_data.location);
                    }else {
                        alert('Con. note number is wrong !');
                    }
                }
            });
        });

        //dung code - show link to http://tracking.cope.com.au/
        function show_link_tracking(){
            if($('#connote').val() !== ''){
                if($("#carrier").val() == "Australia Post"){
                    //https://digitalapi.auspost.com.au/shipmentsgatewayapi/watchlist/shipments?trackingIds=
                    $('#connote').parent().append('<a target="_blank" href="https://auspost.com.au/mypost/track/#/details/' +$('#connote').val() +'">Open Link</a>');
                } else if($("#carrier").val() == "COPE"){
                    $('#connote').parent().append('<a target="_blank" href="http://tracking.cope.com.au/track.php?consignment=' +$('#connote').val() +'">Open Link</a>');
                }else if($("#carrier").val() == "TNT"){
                    $('#connote').parent().append('<a target="_blank" href=https://www.tnt.com/express/en_gc/site/shipping-tools/track.html?searchType=con&cons=' +$('#connote').val() +'">Open Link</a>');
                }
            }
        };
        show_link_tracking();
        $('#connote').change(function(){
            show_link_tracking();
        })
        $('#connote').parent().siblings('.label').append('<br> <button  onClick="SUGAR.Get_Connote(this);" type="button" class="button primary" id="GetConnote" onClick=""> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Get Connote</button>');
        
        SUGAR.Get_Connote = function(element){
            $.ajax({
                url: 'index.php?entryPoint=API_Auspost&aupost_shipping_id='+$('#aupost_shipping_id').val(),
                success: function(data){
                    if(data != 'Error') {
                        $('#connote').val(data);
                    }else {
                        alert('AuPost Shipping ID is wrong !');
                    }
                }
            });
        }

        if( $('#connote').val()== ''){
            SUGAR.Get_Connote();
        }
        // show Invoice Link
        function show_link_invoice(){

            if($('#sold_to_invoice_id').val() !== ''){
                $('div[field="sold_to_invoice"').find('a.invoice-link').remove();
                $('div[field="sold_to_invoice"').append('<a class="invoice-link" target="_blank" href="/index.php?module=AOS_Invoices&offset=1&stamp=1557198792054964100&return_module=AOS_Invoices&action=EditView&record=' +$('#sold_to_invoice_id').val() +'">Open Invoice</a>');
            }
        };
        show_link_invoice();

        // Thienpb code for fill line item
        function wareHourLog_LineItems(po_number,po_id){
            $.ajax({
                url: "/index.php?entryPoint=customGetPOByNumber&PO_number="+po_number+"&PO_ID="+po_id+"&record="+$("input[name='record']").val(),
                type: 'GET',
                success: function(data){
                        $("#lineItems").empty();
                        lineno;
                        prodln = 0;
                        servln = 0;
                        groupn = 0;
                        group_ids = {};
                    if(typeof(data) !== 'undefined' && data.length > 0){

                        var jsonObject = $.parseJSON(data);
                        var groups = jsonObject.groups;
                        var products = jsonObject.products;
                        $("#pe_purchase_order_no_c").val(jsonObject.po_number);
                        // $("#po_purchase_order_pe_warehouse_log_1po_purchase_order_ida").val(jsonObject.po_id);
                        // $("#po_purchase_order_pe_warehouse_log_1_name").val(jsonObject.po_name);
                        if(groups.length > 0){
                            groups.forEach(function(group_e){
                                if(products.length > 0){
                                    products.forEach(function(product_e){
                                        insertLineItems(product_e,group_e);
                                        if(!jsonObject.is_stock){
                                            $("input[name='group_id[]'").val('');
                                        }
                                    });
                                }
                            });
                        }else{
                            return;
                        }
                    
                        $('#lineItems .product_description').css("margin-bottom","30px");
                        $('#lineItems .product_item_description').css("margin-bottom","30px");
                    }
                }
            });
        }
        
        //Tri Copy From Left Value Warehouse Log

        var billing_address_street = $('#billing_address_street').val(),
                billing_address_city   = $('#billing_address_city').val(),
                billing_address_state  = $('#billing_address_state').val(),
                billing_address_postalcode = $('#billing_address_postalcode').val(),
                billing_address_country = $('#billing_address_country').val();
        var copyleft = '<tr><td scope="row" nowrap="">Copy address from left:</td><td><input id="shipping_checkbox_warehouse" name="shipping_checkbox_warehouse" type="checkbox"></td></tr>'
        $('#destination_address_street_label').parent().parent().after(copyleft);
        $('#shipping_checkbox_warehouse').on('click', function() {
            if($('#shipping_checkbox_warehouse').is(':checked')) {
                $('#destination_address_street').val(billing_address_street);
                $('#destination_address_city').val(billing_address_city);
                $('#destination_address_state').val(billing_address_state);
                $('#destination_address_postalcode').val(billing_address_postalcode);
                $('#destination_address_country').val(billing_address_country);
                $('#destination_address_street, #destination_address_country, #destination_address_postalcode, #destination_address_state, #destination_address_city').attr('readonly', 'true');
            } else {
                $('#destination_address_street, #destination_address_country, #destination_address_postalcode, #destination_address_state, #destination_address_city').removeAttr('readonly');
            }
        })

        //Thienpb code for split whlog
        $("#btn_view_change_log").before('<button type="button" class="button primary" id="splitWHLog"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Split WHLog</button>');
        $("#splitWHLog").click(function(){
            $('#splitWHLog span.glyphicon-refresh').removeClass('hidden');
            splitWareHouseLog();
        })
        function splitWareHouseLog(group_id){
            var record = $("input[name='record']").val();
            var group_id = $("#group0id").val();
            var url = "index.php?entryPoint=customSplitWHLog&record="+record+"&group_id="+group_id;
            $.ajax({
                url : url,
                method : 'GET',
                success : function(data) {
                    if(typeof(data) !== 'undefined' && data.length > 0){
                        var jsonObject = $.parseJSON(data);
                        var WHLogs = jsonObject.WHLog;
                        var error = jsonObject.error;
                        if(error == ''){
                            WHLogs.forEach(function(WHLog_id){
                                var loc = 'index.php?module=pe_warehouse_log&action=EditView&record='+WHLog_id;
                                window.open(loc);
                                $('#splitWHLog span.glyphicon-refresh').addClass('hidden');
                            });
                        }else if(error == 'status'){
                            alert('Can\'t split because WareHouseLog splitted!');
                            $('#splitWHLog span.glyphicon-refresh').addClass('hidden');
                        }else{
                            alert('Can\'t split because product quantity = 1');
                            $('#splitWHLog span.glyphicon-refresh').addClass('hidden');

                        }
                    }
                }
            });
        }
    
        //dung code - display link PO
        $('#po_purchase_order_pe_warehouse_log_1_name').parent().append('<br>');
        var check_default = false;
        function show_link_PO(){
            SUGAR.ajaxUI.showLoadingPanel();
            setTimeout(function(){
                if($('#po_purchase_order_pe_warehouse_log_1po_purchase_order_ida').val() !== ''){
                    $('#open_link_po').remove();
                    $('#po_purchase_order_pe_warehouse_log_1_name').parent().append('<a id="open_link_po" target="_blank" href="/index.php?module=PO_purchase_order&action=EditView&record=' +$('#po_purchase_order_pe_warehouse_log_1po_purchase_order_ida').val() +'">Open Link</a>');
                    
                    //Thienpb code -- get group line items
                    if($("#group0name").val() == '' || typeof $("#group0name").val() === "undefined"){
                        wareHourLog_LineItems("",$('#po_purchase_order_pe_warehouse_log_1po_purchase_order_ida').val());
                        check_default = true;
                    }else{
                        check_default = false;
                    }              
                }else{
                    check_default = false;
                }
                SUGAR.ajaxUI.hideLoadingPanel();
            },100)
            
        };
        show_link_PO();
        YAHOO.util.Event.addListener("po_purchase_order_pe_warehouse_log_1po_purchase_order_ida", "change", function(){
            $("#ajaxloading_mask").css("position",'fixed');

            show_link_PO();
            if($("input[name='record']").val() != ''){
                if($(document).hasClass('group_body')){
                    markGroupDeleted(0);
                }
            }
            if(check_default == false){
                wareHourLog_LineItems("",$('#po_purchase_order_pe_warehouse_log_1po_purchase_order_ida').val());
            }
        });

        //dung code -trigger event change Source Warehouse
        YAHOO.util.Event.addListener("pe_warehouse_log_pe_warehousepe_warehouse_ida", "change", function(){
            if($('#pe_warehouse_log_pe_warehousepe_warehouse_ida').val() !== ''){
                $.ajax({
                    url: 'index.php?entryPoint=customDeliverInformation&destination_warehouse_id='+$('#pe_warehouse_log_pe_warehousepe_warehouse_ida').val(),
                    async:false,
                    success: function(data){
                        if(typeof(data) !== null && data.length > 0){
                            var result = JSON.parse(data);
                            $('#billing_account').val(result['billing_account']);
                            $('#billing_account_id').val(result['billing_account_id']);
                            $('#billing_address_street').val(result['billing_address_street']);
                            $('#billing_address_city').val(result['billing_address_city']);
                            $('#billing_address_state').val(result['billing_address_state']);
                            $('#billing_address_postalcode').val(result['billing_address_postalcode']);
                            $('#billing_address_country').val(result['billing_address_country']);
                        }
                    }
                })
            }else{
                console.log('not have destination_warehouse_id.')
            }
        });

        //dung code -trigger event change  Sold To Invoice
        YAHOO.util.Event.addListener("sold_to_invoice_id", "change", function(){
            if($('#sold_to_invoice_id').val() !== ''){
                show_link_invoice();
                $.ajax({
                    url: 'index.php?entryPoint=customDeliverInformation&sold_to_invoice_id='+$('#sold_to_invoice_id').val(),
                    success: function(data){
                        var result = JSON.parse(data);
                        $('#shipping_account').val(result['shipping_account']);
                        $('#shipping_account_id').val(result['shipping_account_id']);
                        $('#shipping_address_street').val(result['shipping_address_street']);
                        $('#shipping_address_city').val(result['shipping_address_city']);
                        $('#shipping_address_state').val(result['shipping_address_state']);
                        $('#shipping_address_postalcode').val(result['shipping_address_postalcode']);
                        $('#shipping_address_country').val(result['shipping_address_country']);
                    }
                })
            }else{
                console.log('not have sold_to_invoice_id.')
            }
        });

        //Thienpb code - trigger event change Sold To client
        YAHOO.util.Event.addListener("billing_account_id", "change", function(){
            if($('#pe_warehouse_log_pe_warehouse_name').val() == '' && $("#billing_account_id").val() != ''){
                $.ajax({
                    url: 'index.php?entryPoint=customDeliverInformation&billing_account_id='+$('#billing_account_id').val(),
                    success: function(data){
                        try {
                            var result = JSON.parse(data);
                            $('#pe_warehouse_log_pe_warehouse_name').val(result['name']);
                            $('#pe_warehouse_log_pe_warehousepe_warehouse_ida').val(result['id']);
                        }catch(err) {
                            console.log(err);
                        }
                    }
                });
            }
        });

        //Thienpb code - trigger event change Sold To Client
        YAHOO.util.Event.addListener("shipping_account_id", "change", function(){
            if($('#sold_to_invoice_id').val() == '' && $("#shipping_account_id").val() != ''){
                $.ajax({
                    url: 'index.php?entryPoint=customDeliverInformation&shipping_account_id='+$('#shipping_account_id').val(),
                    success: function(data){
                        try {
                            var result = JSON.parse(data);
                            $('#sold_to_invoice').val(result['name']);
                            $('#sold_to_invoice_id').val(result['id']);
                        }catch (err) {
                            console.log(err);
                        }
                    }
                });
            }
        });

        //dung code -trigger event change Destination Warehouse 
        YAHOO.util.Event.addListener("destination_warehouse_id", "change", function(){
            if($('#destination_warehouse_id').val() !== ''){
                console.log($('#destination_warehouse_id').val());
                $.ajax({
                    url: 'index.php?entryPoint=customDeliverInformation&destination_warehouse_id_new_logic='+$('#destination_warehouse_id').val(),
                    success: function(data){
                        var result = JSON.parse(data);
                        $('#account_id_c').val(result['account_id_c']);
                        $('#destination_warehouse_owner_c').val(result['destination_warehouse_owner_c']);
                        $('#destination_address_street').val(result['destination_address_street']);
                        $('#destination_address_city').val(result['destination_address_city']);
                        $('#destination_address_state').val(result['destination_address_state']);
                        $('#destination_address_postalcode').val(result['destination_address_postalcode']);
                        $('#destination_address_country').val(result['destination_address_country']);
                    }
                })
            }else{
                console.log('not have destination_warehouse_id.')
            }
        });
        $(".product_serial_number").change(function(){
            var id = $(this).attr('id');
            var value = $(this).val().trim();
            if(value !=''){
                $.ajax({
                    url: 'index.php?entryPoint=check_serial_number_stock_item&serial_number='+value,
                    success: function(data){
                        if(data == 'exits'){
                            var dialog_message = '<span>This Serial already exist in system, Do you want to update this item?</span>';
                            var dialog = $(dialog_message).dialog({
                                buttons: {
                                    "Yes": function() {
                                        dialog.dialog('close');
                                        return;
                                    },
                                    "Cancel":  function() {
                                        $("#"+id).val('');
                                        dialog.dialog('close');
                                        return;
                                    }
                                }
                                });
                        }
                    }
                });
            }
        })
        // tuan creat meeting warehouse log
        function createMeetingWHLog(record_id, dispatch_date,arrival_date) {
            $('#create_dispatch_date_WH_log span.glyphicon-refresh').removeClass('hidden');
            if(dispatch_date== ""){
                alert("Please enter dispatch date.");
                return false;
            }
            if(arrival_date== ""){
                alert("Please enter arrival date.");
                return false;
            }
            var ok = confirm('Are you sure want to create a MEETING and SAVE ?');
            if(ok ==  true){
                $.ajax({
                    url: "index.php?entryPoint=createMettingWHLog&record_id=" + record_id +"&dispatch_date="+dispatch_date+"&arrival_date="+arrival_date+"&purchase_id="+ $("#po_purchase_order_pe_warehouse_log_1po_purchase_order_ida").val()+"&update_met=create_met",
                    type: 'GET',
                    async: false,
                    success: function(data){
                        var meeting_date = $.parseJSON(data);
                        $('#create_dispatch_date_WH_log span.glyphicon-refresh').addClass('hidden');
                        $("#meeting_dispatch_date_c").val(meeting_date[0][0]);
                        $("#meeting_arrival_date_c").val(meeting_date[0][1]);
                        $('div[field=meeting_dispatch_date_c] br,div[field=meeting_dispatch_date_c] a').remove();
                        $('div[field=meeting_arrival_date_c] br,div[field=meeting_arrival_date_c] a').remove();
                        if ($("#meeting_dispatch_date_c").val() != "") {
                            var href_dispatch = "<br><a target='_blank' href='/index.php?module=Meetings&action=DetailView&record="+meeting_date[0][0]+"'>Open Meeting Dispatch</a>";
                            $('#meeting_dispatch_date_c').parent().append(href_dispatch);
                        }
                        if ($("#meeting_arrival_date_c").val() != "") {
                            var href_arrival = "<br><a target='_blank' href='/index.php?module=Meetings&action=DetailView&record="+meeting_date[0][1]+"'>Open Meeting Arrival</a>";
                            $('#meeting_arrival_date_c').parent().append(href_arrival);
                        }
                    }
                });
            }
        }
        //tuan update meetting
        function UpdateMeetingWHLog(record_id,id_dispatch,id_arrival, dispatch_date,arrival_date) {
            $('#create_dispatch_date_WH_log span.glyphicon-refresh').removeClass('hidden');
            if(dispatch_date== ""){
                alert("Please enter dispatch date.");
                return false;
            }
            if(arrival_date== ""){
                alert("Please enter arrival date.");
                return false;
            }
            var ok = confirm('Are you sure want to UPDATE MEETING and SAVE ?');
            if(ok ==  true){
                $.ajax({
                    url: "index.php?entryPoint=createMettingWHLog&record_id=" + record_id +"&id_dispatch=" + id_dispatch +"&id_arrival=" + id_arrival +"&dispatch_date="+dispatch_date+"&arrival_date="+arrival_date+"&update_met=update_met",
                    type: 'GET',
                    async: false,
                    success: function(data){
                        $('#create_dispatch_date_WH_log span.glyphicon-refresh').addClass('hidden');
                        console.log('update seccess!')
                    }
                });
            }
        }
        if ($("#meeting_dispatch_date_c").val() != "") {
            var href_dispatch = "<br><a target='_blank' href='/index.php?module=Meetings&action=DetailView&record="+$("#meeting_dispatch_date_c").val()+"'>Open Meeting Dispatch</a>";
            $('#meeting_dispatch_date_c').parent().append(href_dispatch);
            var href_arrival = "<br><a target='_blank' href='/index.php?module=Meetings&action=DetailView&record="+$("#meeting_arrival_date_c").val()+"'>Open Meeting Arrival</a>";
            $('#meeting_arrival_date_c').parent().append(href_arrival);
        }
        $('#meeting_dispatch_date_c').parent().siblings('.label').append(
            '</br>&nbsp;<button type="button" id="create_dispatch_date_WH_log" class="button create_dispatch_date_WH_log" title="Metting With Installer" style= "font-size: smaller" >Create Meeting<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );
        $("#create_dispatch_date_WH_log").click(function(){
            var record_id = $("input[name='record']").val();
            var dispatch_date = $("input[name='dispatch_ship_date_c']").val();
            var arrival_date = $("input[name='arrival_date_c']").val();
            var id_dispatch = $('#meeting_dispatch_date_c').val();
            var id_arrival = $('#meeting_arrival_date_c').val();
             if( id_dispatch == "" ){
                createMeetingWHLog(record_id, dispatch_date,arrival_date)
             }else {
                UpdateMeetingWHLog(record_id,id_dispatch,id_arrival, dispatch_date,arrival_date)
             }
            // createMettingFromWHLog(record_id,estimate_ship_date);
        });
    })
    $( document ).ready(function(){ 
        //tu-code css Destination Address
         $("#BILLING_address_fieldset").parent().parent().removeClass('col-sm-6');
         $("#BILLING_address_fieldset").parent().parent().addClass('col-sm-4');
         $("#SHIPPING_address_fieldset").parent().parent().removeClass('col-sm-6');
         $("#SHIPPING_address_fieldset").parent().parent().addClass('col-sm-4');
         $("#DESTINATION_address_fieldset").parent().parent().removeClass('col-xs-12 col-sm-12 edit-view-row-item col-sm-4');
         $("#DESTINATION_address_fieldset").parent().parent().addClass('col-xs-12 edit-view-row-item col-sm-4');
         $("#DESTINATION_address_fieldset").parent().removeClass('col-xs-12 col-sm-8 edit-view-field ');
         $("#DESTINATION_address_fieldset").parent().addClass('col-xs-12 col-sm-12 edit-view-field ');
         $("fieldset label").css("width","90px");
         $("#detailpanel_1").find('.clear').removeClass('clear');
    })
    $( "#purchaseorder" ).focus(function() {
        if($(this).val()==''){
            $("#lineItems").empty();
            $("#open_link_po").remove();
            calculateTotal();
            return;
        }else{
            return;
        }
    });
    $("#btn_clr_purchaseorder").click(function(){
            $("#lineItems").empty();
            $("#open_link_po").remove();
            calculateTotal();
            return;
    })

    function addOpenMapView_Billing_Address() {
        var address = $("#billing_address_street").val()+','+$("#billing_address_city").val()+','+$("#billing_address_state").val()+','+$("#billing_address_postalcode").val();     
        var address_realestate = address.toLowerCase().replace(/ |,/g,'-');
        var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&q=' + $("#billing_address_street").val() +", " + $("#billing_address_city").val() + ", " + $("#billing_address_state").val() + ", " + $("#billing_address_postalcode").val();
        $("#billing_address_street_label label").after(
            '<a style="float: right;cursor:pointer;" id="open_map_billing_warehouselog" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        $("#billing_address_street").before( 
            '<div style="background-color: white;display:none;border:1px solid;position:absolute;padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_billing_warehouselog" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a id="open_map_billing_address" target="_blank" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a></li>'+
                '<li><a style="cursor:pointer;" target="_blank" href="http://maps.nearmap.com?addr='+ address+'&z=22&t=roadmap">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_billing">Realestate</a></li>'+
                '</ul>'+
            '</div>'
            );
        $('#open_map_billing_warehouselog').click(function(){
                $('#open_map_popup_billing_warehouselog').fadeToggle()
        })
        $(".various").fancybox({
            maxWidth	: 800,
            maxHeight	: 600,
            fitToView	: false,
            width		: '70%',
            height		: '70%',
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
        });
    
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + $("#billing_address_street").val() +", " + $("#billing_address_city").val() + ", " + $("#billing_address_state").val() + ", " + $("#billing_address_postalcode").val() + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
            type: 'GET',
            success: function(result) {
                if (result.status != "OK")
                    return;
    
                var location = result.results[0].geometry.location;
    
                $.ajax({
                    url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&size=600x300&location=' + location.lat + ',' + location.lng,
                    type: 'GET',
                    success: function(result) {
                        if (result.status == "OK") {
                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                            $("#open_map_billing_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                        }
                        else {
                            var astorPlace = new google.maps.LatLng(location.lat, location.lng);
                            var webService = new google.maps.StreetViewService();
                            var checkaround = 500;
    
                            webService.getPanoramaByLocation(astorPlace, checkaround, checkNearestStreetView);
    
                            function checkNearestStreetView(panoData){
                                if(panoData) {
                                     if(panoData.location) {
                                        if(panoData.location.latLng) {
                                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&pano=' + panoData.location.pano;
                                            $("#open_map_billing_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }
    addOpenMapView_Billing_Address();
    $("#link_realestate_billing").click(function(){
        // address = address.toLowerCase().replace(/ /g, '-');
        SUGAR.ajaxUI.showLoadingPanel();
        $.ajax({
            url: "?entryPoint=getLinkRealestate",
            type: 'POST',
            data: {
                street    : $("#billing_address_street").val(),
                city      : $("#billing_address_city").val(),
                state     : $("#billing_address_state").val(),
                postcode  : $("#billing_address_postalcode").val(),
            },
            success: function(data){
                if(data !== 'Not Find Address On Realestate'){
                    window.open(data,'_blank');
                }else {
                    alert(data);
                }
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        })
    })


    function addOpenMapView_Shipping_Address() {
        var address = $("#shipping_address_street").val()+','+$("#shipping_address_city").val()+','+$("#shipping_address_state").val()+','+$("#shipping_address_postalcode").val();     
        var address_realestate = address.toLowerCase().replace(/ |,/g,'-');
            address_realestate = address_realestate.replace('street', 'st');
        var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&q=' + $("#shipping_address_street").val() +", " + $("#shipping_address_city").val() + ", " + $("#shipping_address_state").val() + ", " + $("#shipping_address_postalcode").val();
        $("#shipping_address_street_label label").after(
            '<a style="float: right;cursor:pointer;" id="open_map_shipping_address_warehouselog" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        $("#shipping_address_street").before( 
            '<div style="background-color: white;display:none;border:1px solid;position:absolute;padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_shipping_address_warehouselog" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a id="open_map_shipping_address" target="_blank" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a></li>'+
                '<li><a style="cursor:pointer;" target="_blank" href="http://maps.nearmap.com?addr='+ address+'&z=22&t=roadmap">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_shipping" >Realestate</a></li>'+
                '</ul>'+
            '</div>'
            );
        $('#open_map_shipping_address_warehouselog').click(function(){
                $('#open_map_popup_shipping_address_warehouselog').fadeToggle()
        })
        $(".various").fancybox({
            maxWidth	: 800,
            maxHeight	: 600,
            fitToView	: false,
            width		: '70%',
            height		: '70%',
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
        });
    
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + $("#shipping_address_street").val() +", " + $("#shipping_address_city").val() + ", " + $("#shipping_address_state").val() + ", " + $("#shipping_address_postalcode").val() + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
            type: 'GET',
            success: function(result) {
                if (result.status != "OK")
                    return;
    
                var location = result.results[0].geometry.location;
    
                $.ajax({
                    url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&size=600x300&location=' + location.lat + ',' + location.lng,
                    type: 'GET',
                    success: function(result) {
                        if (result.status == "OK") {
                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                            $("#open_map_shipping_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                        }
                        else {
                            var astorPlace = new google.maps.LatLng(location.lat, location.lng);
                            var webService = new google.maps.StreetViewService();
                            var checkaround = 500;
    
                            webService.getPanoramaByLocation(astorPlace, checkaround, checkNearestStreetView);
    
                            function checkNearestStreetView(panoData){
                                if(panoData) {
                                     if(panoData.location) {
                                        if(panoData.location.latLng) {
                                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&pano=' + panoData.location.pano;
                                            $("#open_map_shipping_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }
    addOpenMapView_Shipping_Address();
    $("#link_realestate_shipping").click(function(){
        // address = address.toLowerCase().replace(/ /g, '-');
        SUGAR.ajaxUI.showLoadingPanel();
        $.ajax({
            url: "?entryPoint=getLinkRealestate",
            type: 'POST',
            data: {
                street    : $("#shipping_address_street").val(),
                city      : $("#shipping_address_city").val(),
                state     : $("#shipping_address_state").val(),
                postcode  : $("#shipping_address_postalcode").val(),
            },
            success: function(data){
                if(data !== 'Not Find Address On Realestate'){
                    window.open(data,'_blank');
                }else {
                    alert(data);
                }
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        })
    })



    function addOpenMapView_destination_Address() {
        var address = $("#destination_address_street").val()+','+$("#destination_address_city").val()+','+$("#destination_address_state").val()+','+$("#destination_address_postalcode").val();     
        var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&q=' + $("#destination_address_street").val() +", " + $("#destination_address_city").val() + ", " + $("#destination_address_state").val() + ", " + $("#destination_address_postalcode").val();
        $("#destination_address_street_label label").after(
            '<a style="float: right;cursor:pointer;" id="open_map_destination_address_warehouselog" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        $("#destination_address_street").before( 
            '<div style="background-color: white;display:none;border:1px solid;position:absolute;padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_destination_address_warehouselog" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a id="open_map_destination_address" target="_blank" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a></li>'+
                '<li><a style="cursor:pointer;" target="_blank" href="http://maps.nearmap.com?addr='+ address+'&z=22&t=roadmap">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_destination">Realestate</a></li>'+
                '</ul>'+
            '</div>'
            );
        $('#open_map_destination_address_warehouselog').click(function(){
                $('#open_map_popup_destination_address_warehouselog').fadeToggle()
        })
        $(".various").fancybox({
            maxWidth	: 800,
            maxHeight	: 600,
            fitToView	: false,
            width		: '70%',
            height		: '70%',
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
        });
    
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + $("#destination_address_street").val() +", " + $("#destination_address_city").val() + ", " + $("#destination_address_state").val() + ", " + $("#destination_address_postalcode").val() + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
            type: 'GET',
            success: function(result) {
                if (result.status != "OK")
                    return;
    
                var location = result.results[0].geometry.location;
    
                $.ajax({
                    url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&size=600x300&location=' + location.lat + ',' + location.lng,
                    type: 'GET',
                    success: function(result) {
                        if (result.status == "OK") {
                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                            $("#open_map_destination_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                        }
                        else {
                            var astorPlace = new google.maps.LatLng(location.lat, location.lng);
                            var webService = new google.maps.StreetViewService();
                            var checkaround = 500;
    
                            webService.getPanoramaByLocation(astorPlace, checkaround, checkNearestStreetView);
    
                            function checkNearestStreetView(panoData){
                                if(panoData) {
                                     if(panoData.location) {
                                        if(panoData.location.latLng) {
                                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&pano=' + panoData.location.pano;
                                            $("#open_map_destination_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }
    addOpenMapView_destination_Address();
    $("#link_realestate_destination").click(function(){
        // address = address.toLowerCase().replace(/ /g, '-');
        SUGAR.ajaxUI.showLoadingPanel();
        $.ajax({
            url: "?entryPoint=getLinkRealestate",
            type: 'POST',
            data: {
                street    : $("#destination_address_street").val(),
                city      : $("#destination_address_city").val(),
                state     : $("#destination_address_state").val(),
                postcode  : $("#destination_address_postalcode").val(),
            },
            success: function(data){
                if(data !== 'Not Find Address On Realestate'){
                    window.open(data,'_blank');
                }else {
                    alert(data);
                }
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        })
    })

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

})