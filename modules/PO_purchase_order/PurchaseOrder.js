function  pushToXero(el){
    if($("#bill_status_c").val() != 'Billed'){
        $(el).find('span.glyphicon-refresh').removeClass('hidden');
        $.ajax({
            url: "/index.php?entryPoint=xeroAPI&type=PurchaseOrder&method=create&record="+ encodeURIComponent($('input[name="record"]').val()),
            success: function (data) {
                if(typeof data !== 'undefined'){
                    var data_parse = $.parseJSON(data);
                    $(el).find('span.glyphicon-refresh').addClass('hidden');
                    setTimeout(() => {
                        if(data_parse.status.trim("") == 'Ok'){
                            alert('Push PO to XERO Successfully.');
                            loadButton();
                            $('#xero_po_id_c').val(data_parse.xeroID);
                            showLinkXero(data_parse.xeroID);
                        }else{
                            alert('We can\'t push PO to XERO. Please check all fields.');
                        }
                    }, 1000);
                }
            }
        });
    }else{
        alert('PO has been pushed to XERO one time');
    }
    return false;
}

function  updateToXero(el){
    $(el).find('span.glyphicon-refresh').removeClass('hidden');
    $.ajax({
        url: "/index.php?entryPoint=xeroAPI&type=PurchaseOrder&method=update&record="+ encodeURIComponent($('input[name="record"]').val()),
        success: function (data) {
            if(typeof data !== undefined){
                var data_parse = $.parseJSON(data);
                $(el).find('span.glyphicon-refresh').addClass('hidden');
                if(data_parse.status.trim("") == 'Ok'){
                    alert('Update XERO PO Successfully.');
                    $('#xero_po_id_c').val(data_parse.xeroID);
                    showLinkXero(data_parse.xeroID);
                }else{
                    alert('We can\'t update XERO PO. Please check all fields.');
                }    
            }        
        }
    });
}

function showLinkXero($xeroID){
    $("#xeroLink").remove();
    if($xeroID != ''){
        $('#xero_po_id_c').after("<div id='xeroLink'><a target='_blank' href='https://go.xero.com/Accounts/Payable/PurchaseOrders/Edit/"+$('#xero_po_id_c').val()+"'>Go to Xero Link</a></div>");
    }
}

function showLinkMeeting(id, meeting_id) {
    let link_meeting = "<div id='open_"+id+"'><a target='_blank' href='/index.php?module=Meetings&action=EditView&record=" + meeting_id.trim() + "'>" + "Open Meeting" + "</a></div>";
    $(`#open_${id}`).remove();
    $(`#${id}`).parent().append(link_meeting);
}
function get_supplier_order_number(){
    var supplier_order_number = '';
    var name = $("#name").val().trim();
    var array_name =  name.split(' ');
     supplier_order_number = array_name[array_name.length - 1];
    return supplier_order_number;
}

(function ($) {
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        //  Use ajax to get more information
        
        var record_id= $(source).attr('data-record-id') ;
        var owner_email = "";
        $.ajax({
            url: "?entryPoint=getContactPhoneNumber&module_name=Accounts&action=GetInfoForSendEmail&record_id=" + record_id,
            context: document.body,
            async: false
        }).done(function (data) {
            var json = $.parseJSON(data);
            owner_email = json.email;
        });

        
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var po_id= $(source).attr('data-po-id') ;
        var email_type = $(source).attr('data-email-type');
        var date_seek_install_date = $('#seek_install_time_c_date').val();
        var invoice_id = $('#aos_invoices_po_purchase_order_1aos_invoices_ida').val();
        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1' + ((po_id!="")? ("&po_id="+po_id):"") + ((email_type!="")? ("&email_type="+email_type):"")+ ((date_seek_install_date!="")? ("&date_seek_install_date="+encodeURIComponent(date_seek_install_date)):"")+ ((invoice_id!="")? ("&invoice_id="+invoice_id):"") ,
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();

            // Populate fields
            if ($(source).attr('data-record-id') !== '') {
                var populateModule = $(source).attr('data-module');
                var populateModuleRecord = $(source).attr('data-record-id');
                var populateModuleName = $(source).attr('data-module-name');
                var populateEmailAddress = owner_email;
                
                if (populateModuleName !== '') {
                    populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
                }

                $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
                $(self.emailComposeView).find('#parent_type').val(populateModule);
                $(self.emailComposeView).find('#parent_name').val(populateModuleName);
                $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
                $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
                $(self.emailComposeView).find('input[name=return_id]').val($(source).attr('data-po-id'));
                $(self.emailComposeView).find('input[name=return_module]').val('PO_purchase_order');
                //dung code --- input hidden type seek-install-date-from-po
                if (email_type == 'seek-install-date-from-po' ){
                    var html_checkbox_Convert_Solar_Opportunity = 
                '<div class="col-xs-12 col-sm-12 edit-view-row-item hidden">'
                + '<div class="col-xs-12 col-sm-2 label" data-label="">'
                + 'Seek_Install_Date_From_PO_Check:</div>'
                + '<div class="col-xs-12 col-sm-8 edit-view-field " type="bool" field="send_sms" colspan="3">'
                + '<input type="text" checked id="po_id_email_Seek_Install_Date_From_PO_Check" name="po_id_email_Seek_Install_Date_From_PO_Check" value="'+po_id +'" title="" tabindex="0">'                              
                +'</div>'
                +'</div>';
                    $(self.emailComposeView).find('#EditView_tabs .tab-content .edit-view-row').append(html_checkbox_Convert_Solar_Opportunity);
                }

            }
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
    $.fn.openComposeViewModal_Freight_Company = function (source) {
        "use strict";
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var email_module  =  $(source).attr('data-module');

        var email_address = "";
        var cope = $('#local_freight_company_c').val();
        // var postcode = $('#shipping_address_postalcode').val();
        if( cope == "" ){
            alert("Please select local freight company");
            return;
        }
         switch (cope) {
            case "cope_act":
                email_address = "actops@cope.com.au";
            break;
            case "cope_nsw":
                email_address = "nsw@cope.com.au";
            break;
            case "cope_qld":
                email_address = "qldcust@cope.com.au";
            break;
            case "cope_sa":
                email_address = "sa@cope.com.au";
            break;
            case "cope_vic":
                email_address = "vic@cope.com.au";
            break;
            case "cope_wa":
                email_address = "wa@cope.com.au";
            break;
         }
        
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();

        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"");
                
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

            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');
            var populateModuleName = $(source).attr('data-module-name');
            var populateEmailAddress = $(source).attr('data-email-address');
            // get email address
            if(typeof(pulateEmailAddress) == 'undefined'){
                populateEmailAddress = email_address;
            }
            
            if(typeof($(source).attr('data-contact-name')) != 'undefined'){
                populateEmailAddress = $(source).attr('data-contact-name') + ' <' + populateEmailAddress + '>';
            }else if (populateModuleName !== '' && typeof($(source).attr('data-contact-name')) == 'undefined') {
                populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            }
        
            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_id"]').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_module"]').val(populateModule);         
            
            
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
    // Generate uinique id
    $( document ).ready(function() {

        //thienpb code -- add logic for show link edit product
            createLinkProduct();
            $('#line_items_span').on('change', '.product_name', function (e) {
                setTimeout(function() {
                    createLinkProduct();
                }, 500)
            })
            function createLinkProduct() {
                setTimeout(function() {
                    if($('.product_link').length) {
                        $('.product_link').remove();
                    }
                    $('input[id*=product_product_id]').each(function(index) {
                        var product_id = $(this).val();
                        if($(this).val() != '') {
                            $(this).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ product_id +'">Link</a></div>');
                        }
                    })
                },500);
            }

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
        $('#distance_to_travel').parent().siblings('.label').append('<br> <button class="button primary" id="getDistance"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Get Distance</button>');
        // $('#local_freight_company_c').after('<br> <button type="button" class="button primary" id="email_freight_company" value="FREIGHT COMPANY" class="button primary" data-email-type="freight_company" onclick="$(document).openComposeViewModal_Freight_Company(this);" data-module="PO_purchase_order" data-module-name="'+ $("#name").val() +'" data-contact-name="COPE '+$('#shipping_address_state').val()+'"  data-record-id="'+ $("input[name='record']").val()  +'"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>EMAIL FREIGHT COMPANY</button>');

        // tuan code
        var address = $("#billing_address_street").val()+','+$("#billing_address_city").val()+','+$("#billing_address_state").val()+','+$("#billing_address_postalcode").val();     
        var address_warehouse  = $("#shipping_address_street").val()+','+$("#shipping_address_city").val()+','+$("#shipping_address_state").val()+','+$("#shipping_address_postalcode").val();     

        $("#billing_address_street_label label").after(
            '<a style="float: right;cursor:pointer;" id="open_map_supplier" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        $("#billing_address_street").before(
            '<div style="background-color: white;display:none;border:1px solid;position:absolute; padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_supplier" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a style="cursor:pointer;" onclick="openSupplierMap(); return false;">Google Maps</a></li>'+
                '<li><a style="cursor:pointer;" href="http://maps.nearmap.com?addr='+ address +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_billing">Realestate</a></li>'+
                '</ul>'+
            '</div>'
        )
        $("#shipping_address_street_label label").after(
            '<a style="float: right;cursor:pointer;" id="open_map_warehouse" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        $("#shipping_address_street").before(
            '<div style="background-color: white;display:none;border:1px solid;position:absolute; padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_warehouse" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a style="cursor:pointer;" onclick="openShippingMap(); return false;">Google Maps</a></li>'+
                '<li><a style="cursor:pointer;" href="http://maps.nearmap.com?addr='+ address_warehouse +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_shipping" >Realestate</a></li>'+
                '</ul>'+
            '</div>'
        )
        // Purchare order inputs
        $('#create_sanden_quote_fqs_c').hide();
        var html_checklist_sanden_po_inputs = 
        '<div id="group_custom_checklist_sanden_plumbing" class="row detail-view-row"></div>';
        $("#create_sanden_quote_fqs_c").closest('.tab-content').prepend(html_checklist_sanden_po_inputs);
        //VUT - E - create line item Daikin Supply
        if( $('#po_type_c').val() =="sanden_supply"){
            renderPOInputsHTML($('#po_type_c').val());
        }else if($('#po_type_c').val() =="SolarBOS"){
            $("#create_sanden_quote_fqs_c").closest('.tab-content').prepend(html_checklist_sanden_po_inputs);
            renderPOInputsHTML($('#po_type_c').val());
        } else if ($('#po_type_c').val() =="daikin_supply") {
            renderPOInputsHTML($('#po_type_c').val());
        } else if ($('#po_type_c').val() =="sanden_plumber") {
            renderPOInputsHTML($('#po_type_c').val());
        } else if ($('#po_type_c').val() =="heating_cooling_install") {
            renderPOInputsHTML($('#po_type_c').val());
        }
        $('#po_type_c').change(function(){
            var type = $(this).val(); 
            $(document).find('#group_custom_checklist_sanden_plumbing').remove();
            var html_checklist_sanden_plumbing = 
            '<div id="group_custom_checklist_sanden_plumbing" class="row detail-view-row"></div>';
            $("#create_sanden_quote_fqs_c").closest('.tab-content').prepend(html_checklist_sanden_plumbing);
            if( type == "sanden_supply"){
                renderPOInputsHTML(type);
            }else if(type == "SolarBOS"){
                renderPOInputsHTML(type);
            }else if (type == "daikin_supply") {
                renderPOInputsHTML(type);
            }else if(type == "sanden_plumber"){
                renderPOInputsHTML(type);
            }else if (type =="heating_cooling_install") {
                renderPOInputsHTML(type);
            }
        });

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
            
        $('#open_map_supplier').click(function(){
            $('#open_map_popup_supplier').fadeToggle()
        })
        $('#open_map_warehouse').click(function(){
            $('#open_map_popup_warehouse').fadeToggle()
        })
        
        $('#getDistance').click(function (){
            var from_address =  $("#billing_address_street").val() +", " + 
                                $("#billing_address_city").val() + ", " + 
                                $("#billing_address_state").val() + ", " + 
                                $("#billing_address_postalcode").val();

            var to_address =  $("#shipping_address_street").val() +", " + 
                                $("#shipping_address_city").val() + ", " + 
                                $("#shipping_address_state").val() + ", " + 
                                $("#shipping_address_postalcode").val();

    
            
        
        
            $.ajax({
                url: "/index.php?entryPoint=customDistance&address_from=" + from_address + "&address_to=" + to_address,
                type: 'GET',
    
                success: function(data)
                {   
                    try {
                        var jsonObject = $.parseJSON(data);
                        $("#distance_to_travel").val(jsonObject.routes[0].legs[0].distance.text);
                        //dung code - display text  From ... - To ...
                        $('#distance_to_travel').siblings().empty();
                        $('#distance_to_travel').parent().append('<div> From: ' + from_address + '<br>' + 'To: ' + to_address +'</div>');
                    } catch (error) {
                        console.log('Error');
                    }
                   
                },
    
                error: function(response)
                {
                    alert("Cannot get distance");
                },
            });

            return false;
        });

        $('#getDistance').trigger('click');//dung code -  trigger event getdistance when reload page

        //VUT-S-Check distance >40km and no item
        function checkDistanceTravel() {
            // debugger ;
            let po_type = $('#po_type_c').val();
            let check_distance  = parseFloat($('#distance_to_travel').val());
            let total_amount = $('#total_amount').val();
            // let line_item = $("div[data-id='LBL_LINE_ITEMS']").find("#lineItems tr.group_body").length;
            if (check_distance > 40 && total_amount == '0.00' && po_type == 'installer') {
                return false;
            } else return true;
        }
        var array_save_button = 'input[id="SAVE"], #button_save_nav_left, #button_save_nav';
        $('input[id="SAVE"]').prop('onclick',null).off('click');
        $('#button_save_nav_left, #button_save_nav').off('click');
        $(array_save_button).on('click',function(event){
            let condition = checkDistanceTravel();
            // debugger
            if (condition) { 
                var _form = document.getElementById('EditView');
                _form.action.value = 'Save';
                if (check_form('EditView')) SUGAR.ajaxUI.submitForm(_form);
                return false;
            } else {
                event.preventDefault();
                alert('No save. Because distance travel > 40km & no line item');
                return false;
            }
        });
        //VUT-E-Check distance >40km and no item

        //VUT-Button generate PO's name
        $('#name').parent().siblings('.label').append('<br/><button type="button" class="button" id="generate_po_name">Generate PO name</button>');
        $('#generate_po_name').on('click', function(){
            $('#name').val(generatePOname()).trigger('change');
        });

        /*
        var insertProductLine = window.insertProductLine;
        window.insertProductLine = function (tableid, groupid) {

            if(!enable_groups){
            tableid = "product_group0";
            }
        
            if (document.getElementById(tableid + '_head') !== null) {
            document.getElementById(tableid + '_head').style.display = "";
            }
        
            var vat_hidden = document.getElementById("vathidden").value;
            var discount_hidden = document.getElementById("discounthidden").value;
        
            sqs_objects["product_name[" + prodln + "]"] = {
            "form": "EditView",
            "method": "query",
            "modules": ["AOS_Products"],
            "group": "or",
            "field_list": ["name", "id", "part_number", "cost", "cost", "description", "currency_id"],
            "populate_list": ["product_name[" + prodln + "]", "product_product_id[" + prodln + "]", "product_part_number[" + prodln + "]", "product_product_cost_price[" + prodln + "]", "product_product_list_price[" + prodln + "]", "product_item_description[" + prodln + "]", "product_currency[" + prodln + "]"],
            "required_list": ["product_id[" + prodln + "]"],
            "conditions": [{
                "name": "name",
                "op": "like_custom",
                "end": "%",
                "value": ""
            }],
            "order": "name",
            "limit": "30",
            "post_onblur_function": "formatListPrice(" + prodln + ");",
            "no_match_text": "No Match"
            };
            sqs_objects["product_part_number[" + prodln + "]"] = {
            "form": "EditView",
            "method": "query",
            "modules": ["AOS_Products"],
            "group": "or",
            "field_list": ["part_number", "name", "id","cost", "cost","description","currency_id"],
            "populate_list": ["product_part_number[" + prodln + "]", "product_name[" + prodln + "]", "product_product_id[" + prodln + "]",  "product_product_cost_price[" + prodln + "]", "product_product_list_price[" + prodln + "]", "product_item_description[" + prodln + "]", "product_currency[" + prodln + "]"],
            "required_list": ["product_id[" + prodln + "]"],
            "conditions": [{
                "name": "part_number",
                "op": "like_custom",
                "end": "%",
                "value": ""
            }],
            "order": "name",
            "limit": "30",
            "post_onblur_function": "formatListPrice(" + prodln + ");",
            "no_match_text": "No Match"
            };

            var tablebody = document.createElement("tbody");
            tablebody.id = "product_body" + prodln;
            document.getElementById(tableid).appendChild(tablebody);
        
        
            var x = tablebody.insertRow(-1);
            x.id = 'product_line' + prodln;
        
            var a = x.insertCell(0);
            a.innerHTML = "<input type='text' name='product_product_qty[" + prodln + "]' id='product_product_qty" + prodln + "'  value='' title='' tabindex='116' onblur='Quantity_format2Number(" + prodln + ");calculateLine(" + prodln + ",\"product_\");' class='product_qty'>";
        
            var b = x.insertCell(1);
            b.innerHTML = "<input class='sqsEnabled product_name' autocomplete='off' type='text' name='product_name[" + prodln + "]' id='product_name" + prodln + "' maxlength='50' value='' title='' tabindex='116' value=''><input type='hidden' name='product_product_id[" + prodln + "]' id='product_product_id" + prodln + "'  maxlength='50' value=''>";
        
            var b1 = x.insertCell(2);
            b1.innerHTML = "<input class='sqsEnabled product_part_number' autocomplete='off' type='text' name='product_part_number[" + prodln + "]' id='product_part_number" + prodln + "' maxlength='50' value='' title='' tabindex='116' value=''>";
        
            var b2 = x.insertCell(3);
            b2.innerHTML = "<button title='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_TITLE') + "' accessKey='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_KEY') + "' type='button' tabindex='116' class='button product_part_number_button' value='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_LABEL') + "' name='btn1' onclick='openProductPopup(" + prodln + ");'><img src='themes/"+SUGAR.themes.theme_name+"/images/id-ff-select.png' alt='" + SUGAR.language.get('app_strings', 'LBL_SELECT_BUTTON_LABEL') + "'></button>";
        
            var c = x.insertCell(4);
            c.innerHTML = "<input type='text' name='product_product_list_price[" + prodln + "]' id='product_product_list_price" + prodln + "' maxlength='50' value='' title='' tabindex='116' onblur='calculateLine(" + prodln + ",\"product_\");' class='product_list_price'><input type='hidden' name='product_product_cost_price[" + prodln + "]' id='product_product_cost_price" + prodln + "' value=''  />";
        
            if (typeof currencyFields !== 'undefined'){
        
            currencyFields.push("product_product_list_price" + prodln);
            currencyFields.push("product_product_cost_price" + prodln);
        
            }
        
            var d = x.insertCell(5);
            d.innerHTML = "<input type='text' name='product_product_discount[" + prodln + "]' id='product_product_discount" + prodln + "'  maxlength='50' value='' title='' tabindex='116' onblur='calculateLine(" + prodln + ",\"product_\");' onblur='calculateLine(" + prodln + ",\"product_\");' class='product_discount_text'><input type='hidden' name='product_product_discount_amount[" + prodln + "]' id='product_product_discount_amount" + prodln + "' value=''  />";
            d.innerHTML += "<select tabindex='116' name='product_discount[" + prodln + "]' id='product_discount" + prodln + "' onchange='calculateLine(" + prodln + ",\"product_\");' class='product_discount_amount_select'>" + discount_hidden + "</select>";
        
            var e = x.insertCell(6);
            e.innerHTML = "<input type='text' name='product_product_unit_price[" + prodln + "]' id='product_product_unit_price" + prodln + "' maxlength='50' value='' title='' tabindex='116' readonly='readonly' onblur='calculateLine(" + prodln + ",\"product_\");' onblur='calculateLine(" + prodln + ",\"product_\");' class='product_unit_price'>";
        
            if (typeof currencyFields !== 'undefined'){
            currencyFields.push("product_product_unit_price" + prodln);
            }
        
            var f = x.insertCell(7);
            f.innerHTML = "<input type='text' name='product_vat_amt[" + prodln + "]' id='product_vat_amt" + prodln + "' maxlength='250' value='' title='' tabindex='116' readonly='readonly' class='product_vat_amt_text'>";
            f.innerHTML += "<select tabindex='116' name='product_vat[" + prodln + "]' id='product_vat" + prodln + "' onchange='calculateLine(" + prodln + ",\"product_\");' class='product_vat_amt_select'>" + vat_hidden + "</select>";
        
            if (typeof currencyFields !== 'undefined'){
            currencyFields.push("product_vat_amt" + prodln);
            }
            var g = x.insertCell(8);
            g.innerHTML = "<input type='text' name='product_product_total_price[" + prodln + "]' id='product_product_total_price" + prodln + "' maxlength='50' value='' title='' tabindex='116' readonly='readonly' class='product_total_price'><input type='hidden' name='product_group_number[" + prodln + "]' id='product_group_number" + prodln + "' value='"+groupid+"'>";
        
            if (typeof currencyFields !== 'undefined'){
            currencyFields.push("product_product_total_price" + prodln);
            }
            var h = x.insertCell(9);
            h.innerHTML = "<input type='hidden' name='product_currency[" + prodln + "]' id='product_currency" + prodln + "' value=''><input type='hidden' name='product_deleted[" + prodln + "]' id='product_deleted" + prodln + "' value='0'><input type='hidden' name='product_id[" + prodln + "]' id='product_id" + prodln + "' value=''><button type='button' id='product_delete_line" + prodln + "' class='button product_delete_line' value='" + SUGAR.language.get(module_sugar_grp1, 'LBL_REMOVE_PRODUCT_LINE') + "' tabindex='116' onclick='markLineDeleted(" + prodln + ",\"product_\")'><img src='themes/"+SUGAR.themes.theme_name+"/images/id-ff-clear.png' alt='" + SUGAR.language.get(module_sugar_grp1, 'LBL_REMOVE_PRODUCT_LINE') + "'></button><br>";
        
        
            enableQS(true);
            //QSFieldsArray["EditView_product_name"+prodln].forceSelection = true;
        
            var y = tablebody.insertRow(-1);
            y.id = 'product_note_line' + prodln;
        
            var h1 = y.insertCell(0);
            h1.colSpan = "5";
            h1.style.color = "rgb(68,68,68)";
            h1.innerHTML = "<span style='vertical-align: top;' class='product_item_description_label'>" + SUGAR.language.get(module_sugar_grp1, 'LBL_PRODUCT_DESCRIPTION') + " :&nbsp;&nbsp;</span>";
            h1.innerHTML += "<textarea tabindex='116' name='product_item_description[" + prodln + "]' id='product_item_description" + prodln + "' rows='2' cols='23' class='product_item_description'></textarea>&nbsp;&nbsp;";
        
            var i = y.insertCell(1);
            i.colSpan = "5";
            i.style.color = "rgb(68,68,68)";
            i.innerHTML = "<span style='vertical-align: top;' class='product_description_label'>"  + SUGAR.language.get(module_sugar_grp1, 'LBL_PRODUCT_NOTE') + " :&nbsp;</span>";
            i.innerHTML += "<textarea tabindex='116' name='product_description[" + prodln + "]' id='product_description" + prodln + "' rows='2' cols='23' class='product_description'></textarea>&nbsp;&nbsp;"
        
            addToValidate('EditView','product_product_id'+prodln,'id',true,"Please choose a product");
        
            addAlignedLabels(prodln, 'product');
        
            prodln++;
        
            return prodln - 1;
        } */

        /*$("#detailpanel_1").on('change', ".group_name", function(){
            if($(this).
            $(this).after('&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="button pushToXero" title="" onclick="pushToXero(this);" style="\
            width:  auto;\
            padding:  10px;\
            height: 40px;\
        ">\
        Push to Xero\
        </button>'
        )});
        */
        /*$("#line_items_span .group_name").each( function(){
            if($(this).next('.pushToXero').length == 0)
            $(this).after('&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="button pushToXero" title="" onclick="pushToXero(this);" style="\
            width:  auto;\
            padding:  10px;\
            height: 40px;\
        ">\
        Push to Xero\
        <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>\
        </button>'
        )});
        */
        
        SUGAR.util.doWhen(
        "typeof(sqs_objects) != 'undefined' && typeof(sqs_objects['EditView_billing_account']) != 'undefined'",
        functionQS
        );
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
                "billing_address_street",
                "billing_address_city",
                "billing_address_state",
                "billing_address_postalcode",
                "billing_address_country",
                
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
        
        loadButton();
        showLinkXero($("#xero_po_id_c").val());
        // <a id="sanden-email" data-email-type="first-sanden" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="e83676eb-7cc3-d869-58bc-5a9436684a71" data-module-name="Binhtest -" data-email-address="binhdigipro@gmail.com">Sanden Email</a>
        //THIENPB CODE - button proposed installer install date
        $("#SAVE").after(
            '&nbsp;<button type="button" id="sendProposedInstallDate" class="button sendProposedInstallDate" data-po-id="'+$("input[name='record']").val()+'" data-email-type="proposed-install-date-from-po" data-record-id="'+$("#billing_account_id").val()+'" title="Proposed Installer Install Date" data-module="Accounts" data-module-name="'+$("#billing_account").val()+'" onclick="$(document).openComposeViewModal(this);"> ADVISE INSTALLER INSTALL DATE <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )
        $("#SAVE").after(
            '&nbsp;<button type="button" id="sendSeekInstallDate" class="button sendSeekInstallDate" data-po-id="'+$("input[name='record']").val()+'" data-email-type="seek-install-date-from-po" data-record-id="'+$("#billing_account_id").val()+'" title="Seek Install Date" data-module="Accounts" data-module-name="'+$("#billing_account").val()+'" onclick="$(document).openComposeViewModal(this);" > Seek Installer Install Date <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )
        //END


    
        //dung code
        $("#btn_view_change_log").after(
            '&nbsp;<button style="margin: 1px;" type="button" id="save_and_edit" class="button saveAndEdit" title="Save and Edit" onClick="SUGAR.saveAndEdit(this);">Save and Edit</button>'
        )
        //VUT - clone button Email Freight Company + Save & Email pdf
        $('#save_and_edit').after('<button type="button" class="button primary" id="email_freight_company" value="FREIGHT COMPANY" class="button primary" data-email-type="freight_company" onclick="$(document).openComposeViewModal_Freight_Company(this);" data-module="PO_purchase_order" data-module-name="'+ $("#name").val() +'" data-contact-name="COPE '+$('#shipping_address_state').val()+'"  data-record-id="'+ $("input[name='record']").val()  +'"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>EMAIL FREIGHT COMPANY</button>');
        $('#save_and_edit').after('<button type="button" class="button primary" id="save_and_email_pdf" class="button save_and_email_pdf"><span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Save and email pdf</button>&nbsp;');

        //Start - function for button Save and email pdf
        $("#save_and_email_pdf").click(function () {
            let po_id = '';
            let return_module = $("#EditView input[name='return_module']").val();
            $('#save_and_email_pdf span.glyphicon-refresh').removeClass('hidden');
            $("#EditView input[name='action']").val('Save');
            $("#EditView").append('<input type="hidden" value="save_and_sendpdf" name="save_and_sendpdf"/>');
            let return_id = $("#EditView input[name='return_id']").val();
            $("#EditView input[name='return_module']").val('PO_purchase_order');
            $("#EditView input[name='return_action']").val('DetailView');
            $("#EditView input[name='return_id']").val('');

            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                async: false,
                success: function (data) {
                    window.onbeforeunload = null;
                    var form = $('<form></form>');
                    form.attr("method", "post");
                    form.attr("target", "_blank");
                    // if ($("#quote_type_c").val() == 'quote_type_solar' || $("#quote_type_c").val() == 'quote_type_tesla') {
                    //     form.attr("action", 'index.php?entryPoint=CustomQuoteSolarEmailPDF&quote_type_c=' + $("#quote_type_c").val());
                    // } else {
                    //     form.attr("action", 'index.php?entryPoint=generatePdf');
                    // }
                    form.attr("action", 'index.php?entryPoint=generatePdf');

                    var quote_id_patt = /"record" value="(.*)"/g;
                    po_id = quote_id_patt.exec(data);
                    if (po_id !== null && typeof po_id === 'object') {
                        if (po_id[1] != '') {
                            po_id = po_id[1]
                        }
                    }
                    var html_field = '';
                    
                    if ($("#po_type_c").val() == 'installer') {
                        html_field += '<input type="hidden" name="templateID" value="3bd2f6d5-46f9-d804-9d5b-5a407d37d4c5">' +
                            '<input type="hidden" name="task" value="emailpdf">' +
                            '<input type="hidden" name="module" value="PO_purchase_order">' +
                            '<input type="hidden" name="uid" value="' + po_id + '">';
                    } else {
                        html_field += '<input type="hidden" name="templateID" value="1876bff3-5e6b-e49c-e8a1-5e2530fba9ca">' +
                            '<input type="hidden" name="task" value="emailpdf">' +
                            '<input type="hidden" name="module" value="PO_purchase_order">' +
                            '<input type="hidden" name="uid" value="' + po_id + '">';
                    }
                    form.append(html_field);
                    $(document.body).append(form);
                    form.submit();
                }
            });
            setTimeout(function () {
                if (return_id != '' && return_module == 'Opportunities') {
                    window.location.href = 'index.php?action=DetailView&module=Opportunities&record=' + return_id;
                } else {
                    window.location.href = 'index.php?action=DetailView&module=PO_purchase_order&record=' + po_id;
                }
            }, 1000);

        });
        //End - function for button Save and email pdf

        SUGAR.saveAndEdit = function (elem) {
            let condition = checkDistanceTravel();
            // debugger
            if (condition) { 
                SUGAR.ajaxUI.showLoadingPanel();
                $("#EditView input[name='action']").val('Save');
                $.ajax({
                    type: $("#EditView").attr('method'),
                    url: $("#EditView").attr('action'),
                    data: $("#EditView").serialize(),
                    success: function (data) {
                        if($("input[name='record']").val() == ''){
                            var record_id_patt = /"record" value="(.*)"/g;
                            var records = record_id_patt.exec(data);
                            if(records !== null && typeof records === 'object'){
                                if(records[1] !='')  {
                                    window.onbeforeunload = null;
                                    window.onunload = null;
                                    window.addEventListener('beforeunload', function(e) {
                                        window.onbeforeunload = null;
                                        window.onunload = null;
                                    });
                                    var url = 'https://suitecrm.pure-electric.com.au';
                                    // var url = 'http://locsuitecrm.com/';
                                    window.location.href = url+"index.php?module="+module_sugar_grp1+"&action=EditView&record="+records[1];
                                }
                            }
                            return false;
                        }
                        $(".reload_after_rename").trigger("click");
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }
                });
                return false;
            } else {
                alert('No save. Because distance travel > 40km & no line item');
            }
        }

        //end dung code
        //thien fix

        YAHOO.util.Event.addListener("aos_invoices_po_purchase_order_1aos_invoices_ida", "change", function(){
            if($("input[name='record']").val() != ''){
                if($("div[data-id='LBL_LINE_ITEMS']").find("#lineItems tr.group_body").length > 0 ){
                    for (var index = 0; index <$("div[data-id='LBL_LINE_ITEMS']").find("#lineItems tr.group_body").length; index++) {                        
                        markGroupDeleted(index);
                    }
                }
            }
            var record_id = $(this).val();
            if(record_id != ''){
                $.ajax({
                    url: "?entryPoint=getInvoiceInfo&record_id=" + record_id,
                    context: document.body,
                    //async: true
                }).done(function (data) {
                    var json = $.parseJSON(data);
                    $("#billing_account").val(json.plumber_account);
                    $("#billing_account_id").val(json.plumber_account_id);
                    
                    $("#shipping_account").val(json.billing_account);
                    $("#shipping_account_id").val(json.billing_account_id);

                    if(typeof json.install_address !== 'undefined' ){
                        $('#shipping_address_street').val(json.install_address);
                        $('#shipping_address_city').val(json.install_address_city);
                        $('#shipping_address_state').val(json.install_address_state);
                        $('#shipping_address_postalcode').val(json.install_address_postalcode);
                    }
                    else {
                        alert("Install address isn't set!")
                    }

                    if(typeof json.supplier_address !== 'undefined' ){
                        $('#billing_address_street').val(json.supplier_address);
                        $('#billing_address_city').val(json.supplier_address_city);
                        $('#billing_address_state').val(json.supplierl_address_state);
                        $('#billing_address_postalcode').val(json.supplier_address_postalcode);
                    }
                    else {
                        alert("Install address isn't set!")
                    }

                    $('#aos_quotes_po_purchase_order_1_name').val();
                    $('#aos_quotes_po_purchase_order_1aos_quotes_ida').val();

                    $('#aos_quotes_po_purchase_order_1_name').val(json.quote_name);
                    $('#aos_quotes_po_purchase_order_1aos_quotes_ida').val(json.quote_id);
                    $('#name').val(json.invoice_name + ' Purchase Order');

                    var line_items = json.line_items;
                    var k = 0;
                    for(var line_item_key in line_items){
                        var line_item = line_items[line_item_key];
                        insertGroup(k);
                        $("#group"+k+"name").val(line_item_key);
                        for (var j = 0; j < line_item.length; j++){
                            lineno = j; 
                            insertProductLine("product_group"+k, k+"");
                            var popupReplyData = {}; //
                            popupReplyData.form_name = "EditView";
                            var name_to_value_array = {};
                            name_to_value_array["product_currency"+j] = line_item[j].product_currency;
                            name_to_value_array["product_item_description"+j] = line_item[j].product_item_description;
                            name_to_value_array["product_name"+j] = line_item[j].product_name;
                            name_to_value_array["product_part_number"+j] = line_item[j].product_part_number;
                            name_to_value_array["product_product_cost_price"+j] = line_item[j].product_product_cost_price;

                            name_to_value_array["product_product_id"+j] = line_item[j].product_product_id;
                            name_to_value_array["product_product_list_price"+j] = line_item[j].product_product_list_price;
                            name_to_value_array["product_product_qty"+j] = "" + parseInt(line_item[j].product_qty);
                            popupReplyData["name_to_value_array"] = name_to_value_array;
                            setProductReturn(popupReplyData);
                        }
                        k++; 
                    }
                    
                });
            }
                return false;
        });

        //dung code - css for field product_name 
        $('head').append('<style>.yui-ac-content{width:385px!important}</style>');
    });

    
});
$(document).ready(function(){
    //get supplier order number 
    // Disable this function because Paul change logic.
    // YAHOO.util.Event.addListener("name", "change", function(){
    //     $("#supplier_order_number_c").val(get_supplier_order_number());
    // });

    // if($("#supplier_order_number_c").val() == ''){
    //     $("#supplier_order_number_c").val(get_supplier_order_number());
    // }

    //hide label Create Sanden Quote FQS
    $('div[data-label="LBL_CREATE_SANDEN_QUOTE_FQS"]').hide()
    //tu-code show link invoices
    YAHOO.util.Event.addListener("aos_invoices_po_purchase_order_1_name", "change", function(){
        ShowLinkInvoices();
    });
    ShowLinkInvoices();
    function ShowLinkInvoices() {
        if ($("#aos_invoices_po_purchase_order_1_name").val() == "") return;
        var href = "<div class='show-link-invoices'>Link Invoices:<br/> <a target='_blank' href=' https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=" + $('#aos_invoices_po_purchase_order_1aos_invoices_ida').val()+"'> https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=" + $('#aos_invoices_po_purchase_order_1aos_invoices_ida').val() + "</a></div>";
        $('.show-link-invoices').remove();
        $('#aos_invoices_po_purchase_order_1_name').parent().append(href);
    }

    if ($('#meeting_id').val() != '') {
        showLinkMeeting('meeting_id', $('#meeting_id').val());
    }

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
    autoLookupAddress('billing');
    autoLookupAddress('shipping');
    function autoLookupAddress(field){
        $("#"+field+"_address_street").autocomplete({
            source: function( request, response ) {
                // https://www.agl.com.au/svc/QAS/GetSearchResult?searchKey=26%20Walsh%20Avenue&maxResults=10&searchType=SiteAddressSearch&_=1499225218711
                if(request["term"].length > 3){
                    $.ajax({
                        url: "/index.php?entryPoint=customGetAddress&address="+request["term"],
                        type: 'GET',
                        success: function(data)
                        {
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
                console.log(ui.item.value);
                var value =  ui.item.value.split(",");
                var address1 = value[0];
                var address2 = value[1];
    
                $("#"+field+"_address_street").val(address1);
    
                var address3 = address2.split("  ");
    
                $("#"+field+"_address_city").val(address3[0].trim());
                $("#"+field+"_address_state").val(address3[1].trim());
                $("#"+field+"_address_postalcode").val(address3[2].trim());
    
                return false;
            }
        });
    }
    
});

function loadButton(){
    if($("#xero_po_id_c").val() != ''){
        $("#SAVE").after(
            '&nbsp;<button type="button" id="update_xero_invoice" class="button updateXeroInvoice" title="Update Xero PO" onClick="updateToXero(this);" > Update Xero PO <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );
    }else{
        $("#SAVE").after(
            '&nbsp;<button type="button" id="create_xero_invoice" class="button createXeroInvoice" title="Create Xero PO" onClick="pushToXero(this);" > Create Xero PO <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );
    }
}

//VUT-Generate PO's name
function generatePOname() {
    let order_number = $('#supplier_order_number_c').val();
    let po_type = $('#po_type_c').val();
    let dispatch_date = ($('#dispatch_date_c').val() != '') ? formatTimeforPOname($('#dispatch_date_c').val()) : '';
    let delivery_date = ($('#delivery_date_c').val() != '') ? formatTimeforPOname($('#delivery_date_c').val()) : '';
    let shipping_city = $('#shipping_address_city').val();
    let shipping_state = $('#shipping_address_state').val();
    let invoice_id = $('#aos_invoices_po_purchase_order_1aos_invoices_ida').val();
    let namePO = $('#name').val();
    switch (po_type) {
        case 'sanden_supply':
            let productSanden = getInfoProductSanden();
            let infoSanden='';
            $.each(productSanden,function(k,v){
                infoSanden += `${v['qty']}x${k} `;
            });
            namePO = `Sanden ${infoSanden} to ${shipping_city} ${shipping_state} ${dispatch_date} ${order_number}`;
            break;

        case 'daikin_supply':
            let productdaikin = getInfoProductDaikin();
            let infoDaikin='';
            $.each(productdaikin,function(k,v){
                infoDaikin += `${v['qty']}x${v['product_name']} `;
            });
            namePO = `Daikin ${infoDaikin} to ${shipping_city} ${shipping_state} ${delivery_date} ${order_number}`;
            break;

        case 'SPR_PV_Supply':
            let productSunpower = getInfoProductSunPower();
            let infoSunpower = '';
            $.each(productSunpower, function(k,v){
                infoSunpower+= `${v['qty']}x${k} `;
            });
            namePO = `Sunpower ${infoSunpower} to ${shipping_city} ${shipping_state} ${dispatch_date} ${order_number}`;
            break;
        
        default:
            break;
    }
    return namePO;
}
// function total_price_item (item_old){
//     var pr;
//     var total_pr = 0;
//     var len_line_items = $('#lineItems #product_group0 tbody').length;
//     for( var j = item_old  ; j < len_line_items ; j++ ){
//         if( $('#product_product_list_price'+j).val().indexOf(',') >= 0 ){
//             pr = (parseFloat( $('#product_product_list_price'+j).val().replace(",", ""))) * (parseInt( $('#product_product_qty'+j).val() ));
//         }else {
//             pr = (parseFloat( $('#product_product_list_price'+j).val() )) * (parseInt( $('#product_product_qty'+j).val() ));
//         }
//         total_pr += pr;
//         $('#product_product_list_price'+j).val('0.00').trigger('blur');
//     };
//     $('#product_product_cost_price'+item_old).val(total_pr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

//     $('#product_product_list_price'+item_old).val(total_pr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")).trigger('blur');
// }

// .:nhantv:. Update "LINE ITEM" with the correct order
function autoCreateLineItem(id,total_item){
    return $.ajax({
        url: "/index.php?entryPoint=getInfoProduct&product_id="+id,
        type: 'GET',})
        .then(function(data){
            if(data) {
                var info_pro = JSON.parse(data);
                insertProductLine('product_group0', '0');
                lineno  = prodln-1;  
                var popupReplyData = {}; //
                popupReplyData.form_name = "EditView";
                var name_to_value_array = {};
                name_to_value_array["product_currency"+lineno] = info_pro['product_currency'];
                name_to_value_array["product_item_description"+lineno] = info_pro['product_item_description'];
                name_to_value_array["product_name"+lineno] = info_pro['product_name'];
                name_to_value_array["product_part_number"+lineno] =  info_pro['product_part_number'];
                name_to_value_array["product_product_cost_price"+lineno] = info_pro['product_product_cost_price'];

                name_to_value_array["product_product_id"+lineno] = info_pro['product_product_id'];
                name_to_value_array["product_product_list_price"+lineno] = info_pro['product_product_cost_price'];
                name_to_value_array["product_product_qty"+lineno] = "" + parseInt(total_item);
                popupReplyData["name_to_value_array"] = name_to_value_array;            
                $('#product_product_list_price'+lineno).focus();
                $('#product_product_id'+lineno).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ id +'">Link</a></div>');
                setProductReturn(popupReplyData);
            }
        });
}
//VUT- Get info Product Sanden to create PO's name
function getInfoProductSanden() {
    let products = $('#lineItems').find('.product_group').children('tbody');
    let i; 
    let sanden_groups = {};
    let tank_array=[ "SAN-315SAQA", "SAN-315 VE", "SAN-250SAQA", "SAN-160SAQA", "SAN-300SAQA"];
    for (i=0;i< products.length; i++) {
        if (products[i].getAttribute('style') != "display: none;") {
            let qty = $(`#product_product_qty${i}`).val();
            // let product_id = $(`#product_product_id${i}`).val();
            let partNumber = $(`#product_part_number${i}`).val();
            if (partNumber.indexOf("GAUS-") != -1 || partNumber.indexOf("−HPUMP") != -1 /**|| tank_array.includes(partNumber)*/) {
                if (sanden_groups.hasOwnProperty(partNumber)) {
                    sanden_groups[partNumber].qty += parseInt(qty);
                } else {
                    partNumber = partNumber.replace("GAUS-", "");
                    partNumber = partNumber.replace("−HPUMP", "");
                    // partNumber = partNumber.replace(" ", ""); //part number include white space  

                    sanden_groups[partNumber] = {
                        // 'partNumber': partNumber,
                        'qty': parseInt(qty),
                    };
                }
            }
        }
    }
    return sanden_groups;
}

/**
 * VUT - get info Product Sunpower
 */
function getInfoProductSunPower() {
    let products = $('#lineItems').find('.product_group').children('tbody');
    let i; 
    let sunpower = {};
    for (i=0;i< products.length; i++) {
        if (products[i].getAttribute('style') != "display: none;") {
            let qty = parseInt($(`#product_product_qty${i}`).val());
            let cost = parseInt($(`#product_product_unit_price${i}`).val());
            // let product_id = $(`#product_product_id${i}`).val();
            let partNumber = $(`#product_part_number${i}`).val();
            if (partNumber.indexOf("SPR-") != -1 && cost > 0) {
                if (sunpower.hasOwnProperty(partNumber)) {
                    sunpower[partNumber].qty += qty;
                } else {
                    partNumber = partNumber.replace("SPR-", "");
                    sunpower[partNumber] = {
                        'qty': qty,
                    };
                }
            }
        }
    }
    return sunpower;
}

/**
 * VUT-Format Time
 * @param {String} date : dd/mm/yyyy
 * @param {String} result : dd MMM yyyy
 */
function formatTimeforPOname(date) {
    date = date.split("/");
    let months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
    return `${date[0]} ${months[date[1]-1]} ${date[2]}`;
}

// //VUT- Get info Product Daikin to create PO's name
function getInfoProductDaikin() {
    let products = $('#lineItems').find('.product_group').children('tbody');
    let i; 
    let obj = [];
    for (i=0;i< products.length; i++) {
        if (parseFloat($(`#product_product_list_price${i}`).val()) !== 0) {
            let qty = $(`#product_product_qty${i}`).val();
            let partNumber = $(`#product_part_number${i}`).val();
            let product_name = $(`#product_name${i}`).val();
            if (partNumber.indexOf("FTXM") != -1 || partNumber.indexOf("FVXG") != -1 || partNumber.indexOf("FTXZ") != -1 || partNumber.indexOf("FTXJ") != -1) {
                product_name = product_name.replace("Daikin ", "");
                if(JSON.stringify(obj).includes(product_name) == true){
                    obj.forEach(function (val,key) {
                        if (val['product_name'] == product_name) {
                            obj[key]['qty'] = parseInt(val['qty'])  + parseInt(qty);
                        }
                    });
                }else {
                    let product = {
                        qty: qty,
                        product_name: product_name
                    }
                    obj.push(product);
                }  
            }
            if( partNumber.indexOf("BRP072") != -1){
                product_name = "Wifi";
                let product = {
                    qty: qty,
                    product_name: product_name
                }
                obj.push(product);
            }
        }
    }
    return obj;
}
/**
 * Render HTML for Rebate Provided subpanel
 * @param {String} type quote_type_c
 * @param {String} state state
 */
 function renderPOInputsHTML(type){
    $.ajax({
        url: '/index.php?entryPoint=APIRenderPOInputs&type='+type,
        success: function (result) {
            try {
                var json_data = JSON.parse(result);
                $("#group_custom_checklist_sanden_plumbing").empty().append(json_data['template_html_rebate']);
                var btn_generate_po = '<button type="button" class="button" onclick="generatePOLineItem();">Generate PO Line Items</button>';
                $("#group_custom_checklist_sanden_plumbing").append(btn_generate_po);
            } catch (error) {
                console.log(error)
            }
        }
    }).done(function (data) {
        LoadJSONPOInput();
    });
}
async function generatePOLineItem(){
    for (var i = 0; i < prodln; i++){
        markLineDeleted(i,"product_");
    }
    saveJSONPOInput();
    if( $('#po_type_c').val() =="sanden_supply"){
        switch ($('#shipping_address_state').val()) {
            case "VIC":
                $('#shipping_account').val('PureElectric VIC');
                $('#shipping_account_id').val("7a36afbe-04a1-2830-eaa8-5ae65dc8c4ba"); // Unit 7, 23 Mildura Street,Fyshwick, Australian Capital Territory 2609
            break;
            case "ACT":
                $('#shipping_account').val('PureElectric ACT');
                $('#shipping_account_id').val("8cdef336-2c50-6355-5101-5af0fea48795");//53 Britton Street,Smithfield, New South Wales 2164
            break;
            case "QLD":
                $('#shipping_account').val('PureElectric QLD');
                $('#shipping_account_id').val("4485ecd2-33fa-f128-a171-5add589966cd");//2 Stephens Way,Luscombe, Queensland 4207
            break;
            case "SA":
                $('#shipping_account').val('PureElectric SA');
                $('#shipping_account_id').val("bfdb9053-635c-b212-6958-5af0fe72edd4"); //1A Symonds Street, Royal Park, South Australia 5014
            break;
            case "WA":
                $('#shipping_account').val('PureElectric WA');
                $('#shipping_account_id').val("60a92cda-3c50-018b-73df-5cf613950c25");//40 Fulton Drive,Derrimut, Victoria 3030
            break;
            case "NSW":
                $('#shipping_account').val('PureElectric NSW');
                $('#shipping_account_id').val("13f3384a-36a1-e053-4d9e-5b3c509c427d"); //15 Modal Crescent,Canning Vale, West Australia 6155
            break;
        }
        if($("#lineItems").find(".group_body").length == 0){
            insertGroup(0);
            $("#group0name").val("Sanden");
        }else{
            $("#group_body"+($("#lineItems").find(".group_body").length -1)).show();
        }
        var new_name = "Sanden ";
        var total_item =  parseInt($("#sanden_fqv_315").val()) + parseInt($("#sanden_fqs_315").val()) + parseInt($("#sanden_fqs_300").val()) + parseInt($("#sanden_fqs_250").val()) + parseInt($("#sanden_fqs_160").val());
        // .:nhantv:. Update "LINE ITEM" with the correct order
        var select_inputs = $("#group_custom_checklist_sanden_plumbing").find("select");
        try {
            for (i = 0; i < select_inputs.length; i++){
                var el = select_inputs[i];
                if(parseInt($(el).val()) > 0){
                    console.log('Start ' + $(el).attr('data-name'));
                    await autoCreateLineItem($(el).attr('data-id'),parseInt($(el).val()));
                    
                    switch ($(el).attr("data-name")) {
                        case '315FQV':
                                await autoCreateLineItem('d2548387-59da-2980-cdd5-5cff2e43f980',parseInt($(el).val()));
                            break;
                        case '315FQS':
                                await autoCreateLineItem('d3c83262-2ce5-753a-dae0-5bc566179453',parseInt($(el).val()));//SAN-315SAQA
                            break;
                        case '300FQS':
                                await autoCreateLineItem('81acb57b-442f-f5b3-1027-5cc62cc7c477',parseInt($(el).val()));//SAN-300SAQA
                            break;
                        case '250FQS':
                                await autoCreateLineItem('a3d39983-c54e-e94e-0a2c-5c12e9104a87',parseInt($(el).val()));//SAN-250SAQA
                            break;
                        case '160FQS':
                                await autoCreateLineItem('30eb1628-9f73-272d-5e0a-604ffd855450',parseInt($(el).val()));//SAN-250SAQA
                            break;
                    }
                    new_name += $(el).val()+"x "+$(el).attr("data-name")+" ";
                }
                
            }
            if(total_item > 0){
                await autoCreateLineItem("5c46a474-8d5e-5c3c-6825-5acd51527f3f",total_item); //HPFT-1
                await autoCreateLineItem("eed60347-3e2a-6b64-966d-5c7f509737c5",total_item); //GAU-A45HPC
            }
        } catch(err) {
            console.log(err);
        }
        new_name += " to " + $("#shipping_address_city").val() + " " + $("#shipping_address_state").val() +" "+  (($("#dispatch_date_c").val() != '') ? formatTimeforPOname($("#dispatch_date_c").val()) : "") + " "+$("#supplier_order_number_c").val() ;
        $('#name').val(new_name);
    }else if($('#po_type_c').val() =="SolarBOS"){
        if( $('#standard_solar_PV_install').is(':checked') == true){
            if($("#lineItems").find(".group_body").length == 0){
                insertGroup(0);
                $("#group0name").val("Micro Grid and Solar PV Solution");
            }else{
                $("#group_body"+($("#lineItems").find(".group_body").length -1)).show();
                $("#group0name").val("Micro Grid and Solar PV Solution");
            }
            var new_name = "Solargain PE Solar BOS Including Tilt Frames";
            // var select_inputs = $("#po_sanden_supply_input").find("input");
            try {
                await autoCreateLineItem('2a3a7b2e-df46-9712-3912-5fc599f5f53d',4);
                await autoCreateLineItem('689ee364-86e8-99b1-289e-5fc599512548',1);
                await autoCreateLineItem('3d048a3c-b523-6ea6-fea8-5fc599803325',1);
                await autoCreateLineItem('18813d5c-de30-4662-9ae5-5fc59977f55a',100);
                await autoCreateLineItem('2c08e5b6-2151-e85f-d122-5fc5997b15a5',100);
                await autoCreateLineItem('c26d93d4-4312-2be0-6770-5fc59c8161d9',32);
                await autoCreateLineItem('b77102f9-1d9a-d110-fd80-5fc59c1dd6c8',30);
                await autoCreateLineItem('e23308d1-eee9-f8fc-315d-5fc59c203fd6',1);
                await autoCreateLineItem('12f25884-0872-9df9-bd13-5fc5dca6bfc7',40);
                await autoCreateLineItem('204bae92-63ed-9985-58cc-5fc5ddd20056',4);
                await autoCreateLineItem('11c130f2-4907-96fe-3efc-5fc5dda63e1c',130);
            } catch(err) {
                console.log(err);
            }
            $('#name').val(new_name);
        }
    }else if ($('#po_type_c').val() == "daikin_supply") {

        switch ($('#shipping_address_state').val()) {
            case "VIC":
                $('#shipping_account').val('PureElectric VIC');
                $('#shipping_account_id').val("7a36afbe-04a1-2830-eaa8-5ae65dc8c4ba"); // Unit 7, 23 Mildura Street,Fyshwick, Australian Capital Territory 2609
            break;
            case "ACT":
                $('#shipping_account').val('PureElectric ACT');
                $('#shipping_account_id').val("8cdef336-2c50-6355-5101-5af0fea48795");//53 Britton Street,Smithfield, New South Wales 2164
            break;
            case "QLD":
                $('#shipping_account').val('PureElectric QLD');
                $('#shipping_account_id').val("4485ecd2-33fa-f128-a171-5add589966cd");//2 Stephens Way,Luscombe, Queensland 4207
            break;
            case "SA":
                $('#shipping_account').val('PureElectric SA');
                $('#shipping_account_id').val("bfdb9053-635c-b212-6958-5af0fe72edd4"); //1A Symonds Street, Royal Park, South Australia 5014
            break;
            case "WA":
                $('#shipping_account').val('PureElectric WA');
                $('#shipping_account_id').val("60a92cda-3c50-018b-73df-5cf613950c25");//40 Fulton Drive,Derrimut, Victoria 3030
            break;
            case "NSW":
                $('#shipping_account').val('PureElectric NSW');
                $('#shipping_account_id').val("13f3384a-36a1-e053-4d9e-5b3c509c427d"); //15 Modal Crescent,Canning Vale, West Australia 6155
            break;
        }
        if($("#lineItems").find(".group_body").length == 0){
            insertGroup(0);
            $("#group0name").val("Daikin");
        }else{
            $("#group_body"+($("#lineItems").find(".group_body").length -1)).show();
        }

        var new_name = "Daikin ";
        // var total_item =  parseInt($("#sanden_fqv_315").val()) + parseInt($("#sanden_fqs_315").val()) + parseInt($("#sanden_fqs_300").val()) + parseInt($("#sanden_fqs_250").val()) + parseInt($("#sanden_fqs_160").val());
        var select_inputs = $("#group_custom_checklist_sanden_plumbing").find("select");
        var total_wifi_US7 = 0;
        var total_wifi_alira_Under46 = 0 ;
        var total_wifi_alira_Over46 = 0;
        try {
            // await autoCreateLineItem('c4f1979b-d540-53ca-d95b-56951023eaf9',1); //Add DAIKIN_MEL_METRO_DELIVERY	

            for (i = 0; i < select_inputs.length; i++){
                var el = select_inputs[i];
                if(parseInt($(el).val()) > 0){
                    console.log('Start ' + $(el).attr('data-name'));
                    // await autoCreateLineItem($(el).attr('data-id'),parseInt($(el).val()));
                    
                    switch ($(el).attr("id")) {
                        case 'US7_25':
                                await autoCreateLineItem('3518d3a1-7c11-77c5-b9db-5694fed992e6',parseInt($(el).val())); //US7 2.5
                                total_wifi_US7 += parseInt($(el).val());
                            break;
                        case 'US7_35':
                                await autoCreateLineItem('571aa1b6-9abe-80ec-5cdd-56b4536a29d0',parseInt($(el).val()));//US7 3.5
                                total_wifi_US7 += parseInt($(el).val());
                            break;
                        case 'US7_50':
                                await autoCreateLineItem('ef81036f-9889-234d-02e5-57b2c0c71e79',parseInt($(el).val()));//US7 5
                                total_wifi_US7 += parseInt($(el).val());
                            break;
                        case 'Alira_20':
                                await autoCreateLineItem('a80988f7-9871-05d8-3150-5def2f744305',parseInt($(el).val()));//Alira 2.0
                                total_wifi_alira_Under46 += parseInt($(el).val());
                            break;
                        case 'Alira_25':
                                await autoCreateLineItem('7a163038-6178-7479-65d2-5def2ea594ff',parseInt($(el).val()));//Alira 2.5
                                total_wifi_alira_Under46 += parseInt($(el).val());
                            break;
                        case 'Alira_35':
                            await autoCreateLineItem('c3f9a48a-03d1-8dbf-5f83-5def2f9ed532',parseInt($(el).val()));//Alira 3.5
                            total_wifi_alira_Under46 += parseInt($(el).val());
                        break;
                        case 'Alira_46':
                            await autoCreateLineItem('83e632ff-1a24-2710-3f18-5def30c7192b',parseInt($(el).val()));//Alira 4.6
                            total_wifi_alira_Under46 += parseInt($(el).val());
                        break;
                        case 'Alira_50':
                            await autoCreateLineItem('ae050682-3f62-6925-d056-5def30adfe5e',parseInt($(el).val()));//Alira 5
                            total_wifi_alira_Over46 += parseInt($(el).val());
                        break;
                        case 'Alira_60':
                            await autoCreateLineItem('947e6c72-cfa0-7a95-dc50-5def31501045',parseInt($(el).val()));//Alira 6
                            total_wifi_alira_Over46 += parseInt($(el).val());
                        break;
                        case 'Alira_71':
                            await autoCreateLineItem('4f552ea4-da55-cac2-1069-5def32ef431d',parseInt($(el).val()));//Alira 7.1
                            total_wifi_alira_Over46 += parseInt($(el).val());
                        break;
                    }
                    new_name += $(el).val()+"x "+$(el).attr("data-name")+" ";
                }
            }
            // if (total_wifi_US7 > 0) {
            //     await autoCreateLineItem("4b5032ca-8aab-60eb-2837-56b82333ea38",1); //BRP072C42 - Daikin Wi-Fi Mobile Controller Interface
            // }
            // if (total_wifi_alira_Under46 > 0) {
            //     await autoCreateLineItem("8e92f333-2603-5734-e2fb-5deec69327d0",1); //BRP067A42 - Daikin Wi-Fi Remote control PC board set (to suit Daikin Alira 2 - 4.6kW)
            // }
            // if (total_wifi_alira_Over46 > 0) {
            //     await autoCreateLineItem("e9d1a72a-cf0d-f031-74ec-6003785495a5",1); //BRP980B42 - Daikin Wi-Fi Remote control PC board set (to suit Daikin Alira 5- 7.1kW)
            // }
        } catch(err) {
            console.log(err);
        }
        new_name += " to " + $("#shipping_address_city").val() + " " + $("#shipping_address_state").val() +" "+  (($("#delivery_date_c").val() != '') ? formatTimeforPOname($("#delivery_date_c").val()) : "") + " "+$("#supplier_order_number_c").val() ;
        $('#name').val(new_name);
    }else if($('#po_type_c').val() =="sanden_plumber"){
        var select_inputs = $("#group_custom_checklist_sanden_plumbing").find("input");
        try {
            for (i = 0; i < select_inputs.length; i++){
                var el = select_inputs[i];
                if(el.checked == true){
                    await autoCreateLineItem(el.attributes[2].value,1);
                }
                
            }
        } catch(err) {
            console.log(err);
        }
        var new_name = $('#aos_invoices_po_purchase_order_1_name').val() + " Plumbing";
        $('#name').val(new_name);
    }else if($('#po_type_c').val() =="heating_cooling_install"){
        var select_inputs = $("#group_custom_checklist_sanden_plumbing").find("input");
        try {
            for (i = 0; i < select_inputs.length; i++){
                var el = select_inputs[i];
                if(el.checked == true){
                    await autoCreateLineItem(el.attributes[2].value,1);
                }
                
            }
        } catch(err) {
            console.log(err);
        }
        var new_name = $('#aos_invoices_po_purchase_order_1_name').val() + " Daikin Install";
        $('#name').val(new_name);
        $('#group0name').val('Daikin Install');
    }
    setTimeout(function (){
        $('html, body').animate({
            scrollTop: $('.panel-default').find('a:contains("Line Items")').offset().top - 200
        }, 800);
    },500);
}

function saveJSONPOInput(){
    var values = {};
    if($('#po_type_c').val() =="sanden_supply"){
        $(document).find('#group_custom_checklist_sanden_plumbing select').each(function (){
            var id_name = $(this).attr("id");
            values[id_name] = $(this).val();
        });
    }else if($('#po_type_c').val() =="SolarBOS"){
        $(document).find('#group_custom_checklist_sanden_plumbing input').each(function (){
            var id_name = $(this).attr("id");
            values[id_name] = $(this).val();
        });
    }else if (($('#po_type_c').val() =="daikin_supply")) {
        $(document).find('#group_custom_checklist_sanden_plumbing select').each(function (){
            var id_name = $(this).attr("id");
            values[id_name] = $(this).val();
        });
    }else if (($('#po_type_c').val() =="sanden_plumber")) {
        $(document).find('#group_custom_checklist_sanden_plumbing input').each(function (){
            if( $(this).is(':checked') == true){
                var id_name = $(this).attr("id");
                values[id_name] = 'checked';
            }
        });
    }else if (($('#po_type_c').val() =="heating_cooling_install")) {
        $(document).find('#group_custom_checklist_sanden_plumbing input').each(function (){
            if( $(this).is(':checked') == true){
                var id_name = $(this).attr("id");
                values[id_name] = 'checked';
            }
        });
    }
    $("#create_sanden_quote_fqs_c").val(JSON.stringify(values));
}

function LoadJSONPOInput(){
    if($("#create_sanden_quote_fqs_c").val() != ''){
        var dataJSON = JSON.parse($("#create_sanden_quote_fqs_c").val());
        for (let key in dataJSON) {
            if(dataJSON[key] == 'standard_solar_PV_install'){
                $("#"+key).prop('checked', true);
            }else if(dataJSON[key] == 'checked'){
                $("#"+key).prop('checked', true);
            }{
                $("#"+key).val(dataJSON[key]);
            }
        }
    }
}

// $(document).ready(function(){
//     if ($('#bill_status_c').val().toLowerCase() == 'billed') {
//         var href = "<div id='show-link-xero'>Link Xero:<br/> <a target='_blank' href=/index.php?module=AOS_Invoices&action=EditView&record=1111'> /index.php?module=AOS_Invoices&action=EditView&record=1111</a></div>";
//         $('#show-link-xero').remove();
//         $('#status_c').parent().append(href);
//     }
// });