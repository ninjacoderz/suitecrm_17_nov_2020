
$(function () {
    'use strict';
    // Generate uinique id
    $( document ).ready(function() {
    // default value
    if($("#invoice_note_c").val() == '') {
        $("#invoice_note_c").val('Thank you for choosing Pure Electric!');
    }
    //thienpb code -- add logic for show link edit product
    if(module_sugar_grp1 == 'AOS_Invoices' && typeof(module_sugar_grp1) == 'string' ){
        createLinkProduct();
        showLinkWarehouseLogRelated();
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

        //selector product sanden eco and sanden push to geo when sanden model empty
        if($('#sanden_model_c').val() == ''){
            get_sanden_model();
        }

        display_fields_each_product_type();      
    }

    function change_type_of_water_with_old_tank_fuel(){
        var old_tank_fuel_c = $("#old_tank_fuel_c").val();
        var installType = old_tank_fuel_c;
        switch (old_tank_fuel_c) {
            case "electric_storage":  case "gravity_feed_electric": case "instant_electric":
                installType = "replacedElectricHeater";
                break;
            case "gas_storage": case "gas_instant":
                installType = "replaceGasWh";
                break;
            case "heatpump": 
                installType = "replacedHeatPump";
                break;
             case "solar": 
                installType = "replacedSolarWaterHeater";
                break;
            case "wood": case "other": 
                installType = "other";
                break;
            case "newBuilding":
                installType = "newBuilding";
                break;
            default:
                break;
        }

        $("#geo_type_of_wh_replaced_c").val(installType);
    }
    if(module_sugar_grp1 == 'AOS_Invoices'){
        change_type_of_water_with_old_tank_fuel();
    }  
    YAHOO.util.Event.addListener(["old_tank_fuel_c"], "change", change_type_of_water_with_old_tank_fuel);

    function display_link_PE_order_methven(){
        if( $("#order_number_c").val() != "" ){
            $("#order_number_c").parent().append("<p id='link_order'><a  href='https://pure-electric.com.au/admin/commerce/orders/"+$('#order_number_c').val()+"' target='_blank'>Open Methven Order</a></p>");
        }
    }
    // dung code -- add link href into contact and account for module Invoices and Quotes
    function display_link_account_contact(){
        $("#link_account").remove();
        $("#billing_account").parent().append("<p id='link_account'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#billing_account_id").val()+ "' target='_blank'>Open Account</a></p>");
        $("#link_contact").remove();
        $("#billing_contact").parent().append("<p id='link_contact'><a  href='/index.php?module=Contacts&action=EditView&record=" + $("#billing_contact_id").val()+ "' target='_blank'>Open Contact</a></p>");
        // Ah Tuan ngich
        if($('#plumber_contact_c').val() != ""){
            $("#link_contact_plumber").remove();
            $("#plumber_contact_c").parent().append("<p id='link_contact_plumber'><a  href='/index.php?module=Contacts&action=EditView&record=" + $("#contact_id4_c").val()+ "' target='_blank'>Link To Contact</a></p>");    
            $("#link_account_electrician").remove();
            $("#electrician_contact_c").parent().append("<p id='link_account_electrician'><a  href='/index.php?module=Contacts&action=EditView&record=" + $("#contact_id_c").val()+ "' target='_blank'>Link To Contact</a></p>");    
            if($('#plumber_license_number_c').val() != ""){
                $.ajax({
                    url: "/index.php?entryPoint=create_new_contact&contact_id="+ $("#contact_id4_c").val()+ "&plumber_license_number="+$('#plumber_license_number_c').val(),
                    success: function (data) {
                        console.log(data);
                    }
                });
            }
            if($('#electrician_license_number_c').val() != ""){
                $.ajax({
                    url: "/index.php?entryPoint=create_new_contact&contact_id="+ $("#contact_id_c").val()+ "&electrician_license_number="+$('#electrician_license_number_c').val(),
                    success: function (data) {
                        console.log(data);
                    }
                });
            }
        }
    }
    display_link_PE_order_methven();
    display_link_account_contact();
    YAHOO.util.Event.addListener(["billing_account_id","billing_contact_id"], "change", display_link_account_contact);
    //End dung code -- add link href into contact and account for module Invoices and Quotes
        // show Invoice Link
        function sync_product_type_to_invoice(){
            // Just do only Default is selcted from invoice_type
            if($('#invoice_type_c').val() == 'Default'){
                if($("#quote_type_c").val()!=""){
                    if($("#quote_type_c").val() == "quote_type_daikin")
                        $('#invoice_type_c').val('Daikin'); 

                    if($("#quote_type_c").val() == "quote_type_sanden")
                        $('#invoice_type_c').val('Sanden');
                    
                    if($("#quote_type_c").val() == "quote_type_methven")
                        $('#invoice_type_c').val('Methven');
                }
            }
        };
        sync_product_type_to_invoice();
        $("#quote_type_c").change(function(){
            sync_product_type_to_invoice();
            display_fields_each_product_type();
        });
        //tu-code auto serial number in line items 
        $("#sanden_tank_serial_c").change(function(){
            var sanden_tank_serial = $("#sanden_tank_serial_c").val();
            var sanden_hp_serial   = $("#sanden_hp_serial_c").val();
            if(sanden_tank_serial != '' && sanden_hp_serial != ''){
                $("#product_serial_number0").val(sanden_tank_serial + ',' +sanden_hp_serial );
            }else if(sanden_tank_serial != ''){
                $("#product_serial_number0").val(sanden_tank_serial);
            }else{
                $("#product_serial_number0").val(sanden_hp_serial);
            }
            if(sanden_tank_serial != ''){
                show_link_stock($("#sanden_tank_serial_c").val(),'sanden_tank_serial_c');
            }
        });
        $("#sanden_hp_serial_c").change(function(){
            var sanden_tank_serial = $("#sanden_tank_serial_c").val();
            var sanden_hp_serial   = $("#sanden_hp_serial_c").val();
            if(sanden_tank_serial != '' && sanden_hp_serial != ''){
                $("#product_serial_number0").val(sanden_tank_serial + ',' + sanden_hp_serial);
            }else if(sanden_hp_serial != ''){
                $("#product_serial_number0").val(sanden_hp_serial);
            }else{
                $("#product_serial_number0").val(sanden_tank_serial);
            }
            if(sanden_hp_serial != ''){
                show_link_stock($("#sanden_hp_serial_c").val(),'sanden_hp_serial_c');
            }
        });
        function split(val) {
            return val.split(/,\s*/);
        }
        function extractLast(term) {
            return split(term).pop();
        }

        //button Generate Button Generate Promo Code 
        var html_generate_promo_code = '<div class="clear"></div><br>\
        <button type="button" class="button primary" id="generate_promo_code"> \
        <span class="glyphicon  glyphicon-gift"></span> Generate Promo Code</button>';
           //button Generate Button Generate Promo Code 
           var html_generate_promo_code = '<div class="clear"></div><br>\
           <button type="button" class="button primary" id="generate_promo_code"> \
           <span class="glyphicon  glyphicon-gift"></span> Generate Promo Code</button>';
           html_generate_promo_code += '<br>\
           <button style="background:#009acf;" type="button" class="button primary" id="custom_generate_promo_code"> \
           <span class="glyphicon  glyphicon-gift"></span>Custom Generate Promo Code</button>'; 
           $("#promo_methven_3_c").parent().parent().append(html_generate_promo_code);
           
           $("#custom_generate_promo_code").click(function(){
               var body_modal_html =    
               '<div id="body_customize_promocode"><div class="form-group"> \
                   <label for="name_promotion">Name Promotion:</label> \
                   <input type="name_promotion" class="form-control" id="name_promotion"> \
               </div> \
               <div class="form-group">\
                   <label for="offer_type_promotion">Offer type:</label> \
                   <select onchange="SUGAR.ChangeOptionPromoCode(this);" class="form-control" id="offer_type_promotion">\
                       <option selected="selected" value="order_fixed_grand_total_off">Fixed amount off the order Grand Total</option> \
                       <option value="order_percentage_off_grand_total">Percentage off the order Grand Total</option> \
                   </select>\
               </div> \
               <div class="form-group option_order_fixed_grand_total_off">\
                   <label for="amount_off_promotion">Amount off:*</label> \
                   <input placeholder="9.99" type="amount_off_promotion" class="form-control" id="amount_off_promotion">\
               </div> \
               <div class="form-group option_order_percentage_off_grand_total" hidden>\
                   <label for="percentage_off_promotion">Percentage off (%):*</label> \
                   <input placeholder="9.99" type="percentage_off_promotion" class="form-control" id="percentage_off_promotion">\
               </div> \
               <button disable type="submit_custom_generate" onClick="SUGAR.CustomGeneratePromoCode(this);" class="btn btn-default"><span class="glyphicon glyphicon-refresh"></span> Generate Promo Code</button></div>';
               $('#alert_modal').find('.modal-body').empty();
               $('#alert_modal').find('.modal-body').append(body_modal_html); 
               $('#alert_modal').find('.modal-header').empty();
               $('#alert_modal').find('.modal-header').append('<h3 style="text-align:center;">Create Promotion</h3>');
               $('#alert_modal').modal('show'); 
           })
   
           SUGAR.ChangeOptionPromoCode = function(){
               var val_select = $('#offer_type_promotion').val();
               if(val_select == 'order_fixed_grand_total_off'){
                   $(".option_order_fixed_grand_total_off").show();
                   $(".option_order_percentage_off_grand_total").hide();        
                   $("#percentage_off_promotion").val();
               }else{
                   $(".option_order_fixed_grand_total_off").hide();
                   $(".option_order_percentage_off_grand_total").show();
                   $("#amount_off_promotion").val();
               }
           };
   
           SUGAR.CustomGeneratePromoCode = function(){
              
               var offer_type_promotion = $('#offer_type_promotion').val();
               var amount_off_promotion =  $('#amount_off_promotion').val();
               var percentage_off_promotion =  $('#percentage_off_promotion').val();
               var invoice_id = $("input[name='record']").val();
               var invoice_number = $("div[field='number']").text().trim(); 
               var name_promotion = $("#name_promotion").val().trim(); 
               if(amount_off_promotion == '' && percentage_off_promotion == '' ){
                   alert('Please fill out fields!');
                   return false;
               }
               if(invoice_id !='')  {
                   SUGAR.ajaxUI.showLoadingPanel(); 
                   var data_post = {
                       'selected': 'selected',  
                       'method': 'customize',
                       'invoiceID':invoice_id,
                       'invoiceNum':invoice_number,
                       'offer_type_promotion': offer_type_promotion,
                       'name_promotion': name_promotion,
                       'amount_off_promotion': amount_off_promotion,
                       'percentage_off_promotion': percentage_off_promotion
                   } ;  
                   var data_in = data_post;
                   $.ajax({
                       url:"?entryPoint=APIGeneratePromoCode",
                       type: 'POST',
                       data: data_post,
                       success:function(data){   
                           SUGAR.ajaxUI.hideLoadingPanel();                             
                           try {
                               var json = $.parseJSON(data);
                               var html_result = "";
                               if(json.code_customize != ''){
                                   html_result += "Your Promo Code is <strong>" + json.code_customize + "</strong>";
                                   data_in.promo_code = json.code_customize;
                                   data_in.date_start = json.promotion[0].promotionStartDate;
                                   data_in.date_end = json.promotion[0].promotionEndDate;
                                    GenerateJsonPromoCodeCustom (data_in);
                                    RenderHTMLPromoCodeCustom();
                               }else{
                                   html_result += "Can't Create Promo Code";
                               }
                               $('#alert_modal').find('.modal-header').empty();
                               $('#alert_modal').find('.modal-body').empty();
                               $('#alert_modal').find('.modal-body').append(html_result); 
                               $('#alert_modal').modal('show'); 
                               return false;
                           } catch (e) {
                               return false;
                           }                   
                       }
                   });
               }else{
                   $('#alert_modal').find('.modal-body').empty();
                   $('#alert_modal').find('.modal-body').append('Could you saving Invoice before, please?'); 
                   $('#alert_modal').modal('show'); 
                   SUGAR.ajaxUI.hideLoadingPanel(); 
                   return false;
               }
           };
           
        $("#json_promo_code_custom_c").closest('.edit-view-field').parent().hide();
        if(module_sugar_grp1 == 'AOS_Invoices'){
            RenderHTMLPromoCodeCustom();
        }     
        $("#generate_promo_code").click(function(){

            SUGAR.ajaxUI.showLoadingPanel(); 
            var invoice_id = $("input[name='record']").val();
            var invoice_number = $("div[field='number']").text().trim();  
            var promo_1 =  ($("#promo_methven_1_c").is(":checked") && $("#handheld_1_c").val()=='')  ? '1' : '0';
            var promo_2 =  ($("#promo_methven_2_c").is(":checked") && $("#handheld_2_c").val()=='') ? '1' : '0';
            var promo_3 =  ($("#promo_methven_3_c").is(":checked") && $("#handheld_3_c").val()=='')? '1' : '0';
            if(promo_1 == '0' && promo_2 == '0' && promo_3 == '0'){
                $('#alert_modal').find('.modal-body').empty();
                $('#alert_modal').find('.modal-body').append('You must choose one option to continue'); 
                $('#alert_modal').modal('show'); 
                SUGAR.ajaxUI.hideLoadingPanel(); 
                return false;
            }
            if(invoice_id !='')  {
        
                var url_generate_promo_code =  "?entryPoint=APIGeneratePromoCode&method=create&invoiceID="+invoice_id+"&invoiceNum="+invoice_number;
                url_generate_promo_code += '&promo_1='+promo_1;
                url_generate_promo_code += '&promo_2='+promo_2;
                url_generate_promo_code += '&promo_3='+promo_3;
                $.ajax({
                    url:url_generate_promo_code,
                    success:function(data){   
                        SUGAR.ajaxUI.hideLoadingPanel();                             
                            try {
                                var json = $.parseJSON(data);
                                (json.code1 == '')? '' : $('#handheld_1_c').val(json.code1);                       
                                (json.code2 == '')? '' : $('#handheld_2_c').val(json.code2);
                                (json.code3 == '')? '' : $('#handheld_3_c').val(json.code3);

                                if(json.message != ''){
                                    $('#alert_modal').find('.modal-body').empty();
                                    $('#alert_modal').find('.modal-body').append(json.message); 
                                    $('#alert_modal').modal('show'); 
                                    return false;
                                }

                            } catch (e) {
                                return false;
                            }                   
                    }
                });
            }else{
                $('#alert_modal').find('.modal-body').empty();
                $('#alert_modal').find('.modal-body').append('Could you saving Invoice before, please?'); 
                $('#alert_modal').modal('show'); 
                SUGAR.ajaxUI.hideLoadingPanel(); 
                return false;
            }
        });

        //tu code - show sologain number
        $("#sanden_hp_serial_c").autocomplete({
            source: function( request, response ) {
                var $serial_number_string = $("#sanden_hp_serial_c").val().trim();
                var $serial_number_array = $serial_number_string.split(",");
                var $serial_number = trim( $serial_number_array[$serial_number_array.length - 1]);
                check_serial($serial_number,request, response);
            },
            select: function (event, ui) {
                var terms = split(this.value);
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push(ui.item.value);
                // add placeholder to get the comma-and-space at the end
                this.value = terms;
                //thienpb code
                show_link_stock(this.value,'sanden_hp_serial_c');
                return false;
            }
        });

        $("#sanden_tank_serial_c").autocomplete({
            source: function( request, response ) {
                var $serial_number_string = $("#sanden_tank_serial_c").val().trim();
                var $serial_number_array = $serial_number_string.split(",");
                var $serial_number = trim( $serial_number_array[$serial_number_array.length - 1])
                check_serial($serial_number,request, response);
            },
            select: function (event, ui) {
                var terms = split(this.value);
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push(ui.item.value);
                this.value = terms;
                //thienpb code
                show_link_stock(this.value,'sanden_tank_serial_c');
                return false;
            }
        });
        function show_link_stock(value,field_id){
            $.ajax({
                url : "?entryPoint=getStockItemBySerialNumber&serial_numbers="+value,
                success: function (data) {
                    $("#_"+field_id).remove();
                    $("#"+field_id).after("<div id='_"+field_id+"'>"+data+"</div>");
                    $("#_"+field_id).hide();
                }
            });
        }       
        function check_serial(serialNumber,request, response){
            $.ajax({
                url:"?entryPoint=show_sr_number&id="+serialNumber,
                success: function(data)
                {
                    if(data == '' || typeof data === 'undefined') return;
                    var suggest =[];
                    var jsonObject = JSON.parse(data);
                    for (i = 0; i < jsonObject.length; i++) {
                        suggest.push(jsonObject[i].serial_number);
                    }
                    //console.log(jsonObject);
                    //response(suggest);
                    response($.ui.autocomplete.filter(
                        suggest, request.term.split(/,\s*/).pop()));
                    
                },
                error: function(response){
                    console.log("Fail");
                },
            });
        }
        //dung code -- hidden fields not use
        if(module_sugar_grp1 == 'AOS_Invoices'){
            $('#opportunities_aos_invoices_1_name').closest('.edit-view-row-item').hide();
        }
        //dung code - display open link PO-electric and PO-Plumber
        if(module_sugar_grp1 == 'AOS_Invoices' && typeof(module_sugar_grp1) == 'string' ) $('<div id="add_open_link_PO"></div>').insertAfter('button[onclick="clip_aboard(\'absolute_path\')"]');
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
                /*var record_id = $("input[name='record']").val() ;
                if(record_id!== ""){
                    var line_item_orders = [];
                    $("input[name^='product_product_id']").each(function(){
                        line_item_orders.push($(this).val());
                    });
                    console.log(line_item_orders);
                    var data_to_post = {};
                    data_to_post.record_id = record_id;
                    data_to_post.line_item_orders = line_item_orders;
                    
                    // Ajax calling to update order
                    $.ajax({
                        type: 'POST',
                        url: "?entryPoint=reorderLineItems",
                        data: {
                            json: data_to_post
                        },
                        success: function(msg) {
                            
                        //success
                      }
                    });
                }*/

            }
        });
//tran_tu code
        SolarGainOrderNumberLink();

        $('#solargain_invoices_number_c').change(function () {
            SolarGainOrderNumberLink();
        });
    
        function SolarGainOrderNumberLink() {
            if ($('#solargain_invoices_number_c').val() == "") return;
            var href = "<div class='open-solargain-order-number'>Open Order SolarGain <br/> <a target='_blank' href='https://crm.solargain.com.au/order/edit/" + $('#solargain_invoices_number_c').val()+"'>https://crm.solargain.com.au/order/edit/" + $('#solargain_invoices_number_c').val() + "</a></div>";
            $('.open-solargain-order-number').remove();
            $('#solargain_invoices_number_c').parent().append(href);
        }
        //
        $('#addGroup').click(function(){
            $('.product_group').sortable({
                cancel: ".product_group0_head",
                axis :"y",	
                handle: '.handle',	
                update: function(event, ui) {
                    if($("input[name='record']").val() !== ""){
                        var line_item_orders = {}
                        $("input[name^='product_product_id']").each(function(){
                            line_item_orders.push($(this).val());
                        });
                        console.log(line_item_orders);
                        // Ajax calling to update order
                    }
                }
            });
        })
        if($("#site_contact_c").val() == "" ){
            $("#site_contact_c").val($("#billing_contact").val());
            $("#contact_id3_c").val($("#billing_contact_id").val());
            //
        }
        if($("#lineItems").length){
            $("#lineItems").click(function () {
                if(!($('.product_group').hasClass('ui-sortable'))){
                    $('.product_group').sortable({
                        cancel: ".product_group0_head",
                        axis :"y",	
                        handle: '.handle',	
                        update: function(event, ui) {
                            if($("input[name='record']").val() !== ""){
                                var line_item_orders = {}
                                $("input[name^='product_product_id']").each(function(){
                                    line_item_orders.push($(this).val());
                                });
                            }
                        }
                    });
                }

            })
        }
        if(module_sugar_grp1 != "AOS_Quotes")  
        $('#plumber_c').parent().siblings('.label').append('<br> <button class="button primary" id="seekInstallationDate"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Seek Install Date</button>');

        //dung code - open link contact Plumber, Electric in Invoices
        if(module_sugar_grp1 == 'AOS_Invoices'){
            function display_link_contact_plum_elec_invoice(){
                $("#link_account_plumquote").remove();
                if( $("#account_id1_c").val() != ''){
                    $("#account_id1_c").parent().append("<p id='link_account_plumquote'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#account_id1_c").val()+ "' target='_blank'>Open Account</a></p>");
                }
                $("#link_account_elecquote").remove();
                if( $("#account_id_c").val() != ''){
                    $("#account_id_c").parent().append("<p id='link_account_elecquote'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#account_id_c").val()+ "' target='_blank'>Open Account</a></p>");
                }
                $('.display_link_contact_plum_elec_invoice').remove();
                var electrician_id = $("#account_id_c").val();
                var plumber_id = $("#account_id1_c").val();
                $.ajax({
                    url: "?entryPoint=getContactFromAccount&request=custom_display_link_contact_plum_elec_invoice&electrician_id=" + electrician_id
                     + "&plumber_id=" +plumber_id,
                }).done(function (data) {
                    if(data == '' || typeof data === 'undefined') return;
                    var json = $.parseJSON(data);
                    
                    $("#link_account_plumquote").remove();
                    if( $("#account_id1_c").val() != ''){
                        $("#account_id1_c").parent().append("<p class='display_link_contact_plum_elec_invoice' id='link_account_plumquote'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#account_id1_c").val()+ "' target='_blank'>Open Account</a></p>");
                        if(json.plum_contact_id != '') $("#account_id1_c").parent().append("<p class='display_link_contact_plum_elec_invoice' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.plum_contact_id+ "' target='_blank'>Open Primary Contact</a></p>");
                    }
                    $("#link_account_elecquote").remove();
                    if( $("#account_id_c").val() != ''){
                        $("#account_id_c").parent().append("<p class='display_link_contact_plum_elec_invoice' id='link_account_elecquote'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#account_id_c").val()+ "' target='_blank'>Open Account</a></p>");
                        if(json.elec_contact_id != '')$("#account_id_c").parent().append("<p class='display_link_contact_plum_elec_invoice' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.elec_contact_id+ "' target='_blank'>Open Primary Contact</a></p>");
                    }
                });
            }
            display_link_contact_plum_elec_invoice();
            YAHOO.util.Event.addListener(["account_id1_c","account_id_c"], "change", display_link_contact_plum_elec_invoice);
            $('#plumber_c').parent().siblings('.label').append('<br> <button class="button primary" id="distanceFlumbertoSuite"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>GET DISTANCE</button>');
            $('#plumber_c').parent().siblings('.label').append('<button style="font-size: smaller;margin: 0px 2px;" class="button primary" type="button" id="getDistance_selectedPlumber"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Distance Selected</button>');
        
            //open link account/ contact Solar Installer 
            display_link_account_contact_installer_solar();
            YAHOO.util.Event.addListener(["account_id5_c","contact_id2_c"], "change", display_link_account_contact_installer_solar);
        }

        //VUT - trigger click get distance installer
        if (module_sugar_grp1 == 'AOS_Invoices') {
            setTimeout(function() {
                if($('#account_id1_c').val() != '') {
                    $('#getDistance_selectedPlumber').trigger('click');
                }
                if ($('#account_id_c').val() != '') {
                    $('#getDistance_selectedElectrician').trigger('click');
                }
            },100);
        }
        //Get Distance Selected Plumber
        $('#getDistance_selectedPlumber').click(function(){
            $('#getDistance_selectedPlumber span.glyphicon-refresh').removeClass('hidden');
            var distance_selected = '';
            var id_account = $('#account_id1_c').val().trim();
            distance_selected = get_distance_by_account_id(id_account);

            if (typeof(distance_selected) == 'string') {
                $('#distance_to_suite_c').val(`${distance_selected}`);
            } else {
                $('#distance_to_suite_c').val(`${distance_selected} km`);
            }
            $('#getDistance_selectedPlumber span.glyphicon-refresh').addClass('hidden');            
        });

        

        // distance to plumber tuan
        $('#distanceFlumbertoSuite').click(function (){
            $('#distanceFlumbertoSuite span.glyphicon-refresh').removeClass('hidden');
            if( $('#install_address_c').val() == "" ){
            var from_address =  $("#billing_address_street").val() +", " +
                                    $("#billing_address_city").val() + ", " +
                                    $("#billing_address_state").val() + ", " +
                                    $("#billing_address_postalcode").val();
             
            }else {
                var from_address =  $("#install_address_c").val() +", " +
                                    $("#install_address_city_c").val() + ", " +
                                    $("#install_address_state_c").val() + ", " +
                                    $("#install_address_postalcode_c").val();
            }
            var product_type = $('#quote_type_c').val();
            if($('#quote_type_c').val() == ""){
                alert('Please fill for product type !');
                $('#quote_type_c').focus();
                $('#distanceFlumbertoSuite span.glyphicon-refresh').addClass('hidden');
                return false;
            }
            if( product_type == 'quote_type_daikin' ){
                var url = "?entryPoint=customFilterPlumber&type=daikin_instaler&address_from=" + from_address;
            }else {
                var url = "?entryPoint=customFilterPlumber&type=plumber&address_from=" + from_address;
            }
            // var ac_id = $('#account_id1_c').val();
            if( $('#plumber_c').val() == ""){
                $.ajax({
                    url: url,
                    success: function (data) {
                        try {
                            var infor = $.parseJSON(data);
                            $("#btn_clr_plumber_c").after("<b class='suggest'> Suggest: </b><br/>");
                            $("#btn_clr_plumber_c").after("</br><b class='shortest'> Shortest: </b><br/><b class='shortest-suggest'></b>");
                            infor.sort(function(a,b){
                                return a.distance - b.distance;
                            });
                            for ( var i = 0 ; i < 5 ; i++){  
                                var addr = infor[0][0];
                                var name_lum =infor[0][1];
                                var id_nearest = infor[0][2];
                                var str_dis =  infor[0][3];
                    
                                $("#btn_clr_plumber_c").nextAll('.shortest-suggest').html('<a class="selected-suggest" dist="'+str_dis+'" rel="'+addr+'" href="#">'+name_lum+': '+addr+':<span style="color:green">'+str_dis+'</span></a> <br>');
                                $("#distance_to_suite_c").val(str_dis);
                                $('#plumber_c').val(name_lum);
                                $('#account_id1_c').val(id_nearest);
                                $("#btn_clr_plumber_c").nextAll('.suggest').append('<a class="selected-suggest" dist="'+infor[i][3]+'" rel="'+infor[i][0]+'" href="#">'+infor[i][1]+': '+infor[i][0]+':<span style="color:green">'+infor[i][3]+'</span></a> <br>');
                            }  
                        } catch (error) {
                            console.log(error);
                        }
    
                        $('#distanceFlumbertoSuite span.glyphicon-refresh').addClass('hidden');
                    }
                });
            }else {
                $.ajax({
                    url: url,
                    success: function (data) {
                        try {
                            var infor = $.parseJSON(data);
                            $("#btn_clr_plumber_c").after("<b class='suggest'> Suggest: </b><br/>");
                            $("#btn_clr_plumber_c").after("</br><b class='shortest'> Shortest: </b><br/><b class='shortest-suggest'></b>");
                            infor.sort(function(a,b){
                                return a.distance - b.distance;
                            });
                            for ( var i = 0 ; i < 5 ; i++){  
                                var addr = infor[0][0];
                                var name_lum =infor[0][1];
                                var str_dis =  infor[0][3];
                                $("#btn_clr_plumber_c").nextAll('.shortest-suggest').html('<a class="selected-suggest" dist="'+str_dis+'" rel="'+addr+'" href="#">'+name_lum+': '+addr+':<span style="color:green">'+str_dis+'</span></a> <br>');
                                $("#btn_clr_plumber_c").nextAll('.suggest').append('<a class="selected-suggest" dist="'+infor[i][3]+'" rel="'+infor[i][0]+'" href="#">'+infor[i][1]+': '+infor[i][0]+':<span style="color:green">'+infor[i][3]+'</span></a> <br>');
                            }  
                        } catch (error) {
                            console.log(error);
                        }
    
                        $('#distanceFlumbertoSuite span.glyphicon-refresh').addClass('hidden');
                    }
                });
            }
                return false
        });
                    //-------------------------------
        $("#seekInstallationDate").click(function(){
            $('#seekInstallationDate span.glyphicon-refresh').removeClass('hidden');
            if($("input[name='plumber_c']").val() == ""){
                alert("Please enter plumber name.");
                $('#seekInstallationDate span.glyphicon-refresh').addClass('hidden');
                $("#plumber_c").focus();
                return false;
            }
            var record_id = $("input[name='record']").val();
            setTimeout(function(){
                $.ajax({
                    url: "?entryPoint=seekInstallationDate&record_id=" + record_id + "&account_id="+ $("#account_id1_c").val(),
                    context: document.body,
                    async: true
                }).done(function (data) {
                    if(data == '' || typeof data === 'undefined') {
                        $('#seekInstallationDate span.glyphicon-refresh').addClass('hidden');
                        return;
                    }
                    $('#seekInstallationDate span.glyphicon-refresh').addClass('hidden');
                    window.open(data,'_blank');
                    return false;
                });
                },100);
            return false;
        });

        //dung code --- Create button "Seek Install Date" for Electrician
        if(module_sugar_grp1 == "AOS_Invoices")$('#electrician_c').parent().siblings('.label').append('<br> <button class="button primary" id="seekInstallationDate_elec"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Seek Install Date</button>');
        $("#seekInstallationDate_elec").click(function(){
            $('#seekInstallationDate_elec span.glyphicon-refresh').removeClass('hidden');
            if($("#electrician_c").val() == ""){
                alert("Please enter Electrician name.");
                $('#seekInstallationDate_elec span.glyphicon-refresh').addClass('hidden');
                $("#electrician_c").focus();
                return false;
            }
            var record_id = $("input[name='record']").val();
            setTimeout(function(){
                $.ajax({
                    url: "?entryPoint=seekInstallationDate_elec&record_id=" + record_id + "&account_id="+ $("#account_id_c").val(),
                    context: document.body,
                    async: true
                }).done(function (data) {
                    if(data == '' || typeof data === 'undefined') {
                        $('#seekInstallationDate_elec span.glyphicon-refresh').addClass('hidden');
                        return;
                    }
                    $('#seekInstallationDate_elec span.glyphicon-refresh').addClass('hidden');
                    window.open(data,'_blank');
                    return false;
                });
            },100);
            return false;
        });

        //if(module_sugar_grp1 != "AOS_Quotes")  $('#meeting_c').parent().siblings('.label').append('<br> <button class="button primary" id="mettingWithInstaller"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Metting With Installer</button>');
        if(module_sugar_grp1 == "AOS_Invoices"){
            $("#CANCEL").after(
                '<button type="button" id="mettingWithInstaller" class="button mettingWithInstaller" title="Metting With Installer" >Create Meeting<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            )
            $("#mettingWithInstaller").click(function(){
                $('#mettingWithInstaller span.glyphicon-refresh').removeClass('hidden');
                if($("#plumber_c").val() == ""){
                    alert("Please enter plumber name.");
                    return false;
                }
                var record_id = $("input[name='record']").val();
                var installation_date = $("#installation_date_c").val();
                if(installation_date !=''){
                    $.ajax({
                        url: "?entryPoint=mettingWithInstaller&record_id=" + record_id +"&installation_date="+installation_date,
                        context: document.body,
                        async: true
                    }).done(function (data) {
                        $('#mettingWithInstaller span.glyphicon-refresh').addClass('hidden');
                        $("#meeting_c").val(data);
                        if ($("#meeting_c").val() != "") {
                            
                            var href = "<a target='_blank' href='/index.php?module=Meetings&action=DetailView&record="+data+"'>Open Meeting</a>";
                            $('#meeting_c').parent().siblings('.label').append(href);

                        }
                        return false;
                    });
                }else{
                    alert("Please fill for installation date");
                    $("#installation_date_c_date").focus();
                    return false;
                }
                return false;
            });
            if ($("#meeting_c").val() != "") {
                var href = "<a target='_blank' href='/index.php?module=Meetings&action=DetailView&record="+$("#meeting_c").val()+"'>Open Meeting</a>";
                $('#meeting_c').parent().siblings('.label').append(href);

            }
        }
        // Xero invoice

        if ($("#xero_invoice_c").val() != "") {
            var href = "<a target='_blank' href='https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID="+$("#xero_invoice_c").val()+"'>Xero Invoice</a>";
            $('#xero_invoice_c').parent().siblings('.label').append(href);

        }
        if ($("#xero_veec_rebate_invoice_c").val() != "") {
            var href = "<a target='_blank' href='https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID="+$("#xero_veec_rebate_invoice_c").val()+"'>Xero Invoice</a>";
            $('#xero_veec_rebate_invoice_c').parent().siblings('.label').append(href);

        }
        if ($("#xero_stc_rebate_invoice_c").val() != "") {
            var href = "<a target='_blank' href='https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID="+$("#xero_stc_rebate_invoice_c").val()+"'>Xero Invoice</a>";
            $('#xero_stc_rebate_invoice_c').parent().siblings('.label').append(href);

        }
        if ($("#xero_shw_rebate_invoice_c").val() != "") {
            var href = "<a target='_blank' href='https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID="+$("#xero_shw_rebate_invoice_c").val()+"'>Xero Invoice</a>";
            $('#xero_shw_rebate_invoice_c').parent().siblings('.label').append(href);
        }

        if(module_sugar_grp1 == "AOS_Invoices"){
            $('#plumber_po_c').parent().siblings('.label').append('<br> <button class="button primary" id="createPlumberPO" style="font-size: smaller; height: 100%;"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create Plumber PO</button>');

            $('#electrical_po_c').parent().siblings('.label').append('<br> <button class="button primary" id="createElectricalPO"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create Electrical PO</button>');
            
            if ($("#plumber_po_c").val() != "") {
                //?module=PO_purchase_order&offset=1&stamp=1517294972082855000&return_module=PO_purchase_order&action=EditView&record=3b056120-a5d4-ae50-8e32-5a7015aedb4a
                var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=DetailView&record=" + $("#plumber_po_c").val().trim() + "'>Open Purchase Order</a><a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + $("#plumber_po_c").val().trim() + "'>[E]</a></div>";
                if( $('#quote_type_c').val() =="quote_type_daikin" || $('#quote_type_c').val() =="quote_type_nexura"){
                    href += "<div class='open-upload-install-photos'>Upload Install Photos: <a target='_blank' href='https://pure-electric.com.au/upload_file_daikin/for-daikin-plumbing?invoice_id="+$('input[name="record"]').val()+"'>Open Upload For Daikin Plumber</a></div>";
                }else {
                    href += "<div class='open-upload-install-photos'>Upload Install Photos: <a target='_blank' href='https://pure-electric.com.au/upload_file_sanden/for-plumber?invoice_id="+$('input[name="record"]').val()+"'>Open Upload For Sanden Plumber</a></div>";
                }
                $('#createPlumberPO').siblings().empty();
                $('#createPlumberPO').parent().append(href);
                //dung code- display PO -plumber link
                $('#PO_link_plum').remove();
                $('#add_open_link_PO').append('<a id="PO_link_plum" target="_blank" href="/index.php?module=PO_purchase_order&amp;action=EditView&amp;record='+$('#plumber_po_c').val().trim() +'">Open PO- Plumber </a>');
            }

            if ($("#electrical_po_c").val() != "") {
                //?module=PO_purchase_order&offset=1&stamp=1517294972082855000&return_module=PO_purchase_order&action=EditView&record=3b056120-a5d4-ae50-8e32-5a7015aedb4a
                var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=DetailView&record=" + $("#electrical_po_c").val().trim() + "'>Open Purchase Order</a><a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + $("#electrical_po_c").val().trim() + "'>[E]</a></div>";
                    href += "<div class='open-upload-install-photos'>Upload Install Photos: <a target='_blank' href='https://pure-electric.com.au/upload_file_sanden/for-electrician?invoice_id="+$('input[name="record"]').val()+"'>Open Upload For Electrician</a></div>";
                $('#createElectricalPO').siblings().empty();
                $('#createElectricalPO').parent().append(href);
                //dung code - display PO - electric link
                $('#PO_link_elec').remove();
                $('#add_open_link_PO').append('<a id="PO_link_elec" target="_blank" href="/index.php?module=PO_purchase_order&amp;action=EditView&amp;record='+$('#electrical_po_c').val().trim() +'">Open PO- Electric </a>');
            }
        
        
            $("#createPlumberPO").click(function(){
                if($("#plumber_po_c").val() != ""){
                    alert("please click the link to edit PO");
                    return false;
                }
                if ($('#installation_date_c_date').val() == '' && $('#plumber_install_date_c').val() == '') {
                    alert("please fill 'Installation Date' or 'Plumber Install Date'!");
                    return false;
                }
                $('#createPlumberPO span.glyphicon-refresh').removeClass('hidden');
                var record_id = $("input[name='record']").val();
                var invoice_installation = $('input[name="installation_pictures_c"]').val();
                var purchase_installation = generateUUID();
                // submit form before create PO - plumber by buttom
                SUGAR.ajaxUI.showLoadingPanel();
                $.ajax({
                    type: $("#EditView").attr('method'),
                    url: $("#EditView").attr('action'),
                    data: $("#EditView").serialize(),
                    async:false,
                    success: function () {
                        $.ajax({
                            url: "?entryPoint=createPurchaseOrder&type=plumber&record_id=" + record_id + "&invoice_installation="+invoice_installation+ "&purchase_installation="+purchase_installation,
                            context: document.body,
                            async: true
                        }).done(function (data) {
                            $('#createPlumberPO span.glyphicon-refresh').addClass('hidden');
                            console.log('createPOPlumber' + data);
                            if(data == '') return;
                            var id_link = $.parseJSON(data);
                            $("#plumber_po_c").val(id_link.po_id.trim());
                            if (id_link.meeting_id != '') {
                                $('#meeting_plumber').val(id_link.meeting_id);
                                showLinkMeeting('meeting_plumber',id_link.meeting_id);
                            }
                            // if (data.trim() == "") return false;
                            //?module=PO_purchase_order&offset=1&stamp=1517294972082855000&return_module=PO_purchase_order&action=EditView&record=3b056120-a5d4-ae50-8e32-5a7015aedb4a
                            var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + id_link.po_id.trim() + "'>" + "Open Purchase Order" + "</a></div>";
                            if( $('#quote_type_c').val() =="quote_type_daikin" || $('#quote_type_c').val() =="quote_type_nexura"){
                                href += "<div class='open-upload-install-photos'>Upload Install Photos: <a target='_blank' href='https://pure-electric.com.au/upload_file_daikin/for-daikin-plumbing?invoice_id="+$('input[name="record"]').val()+"'>Open Upload For Daikin Plumber</a></div>";
                            }else {
                                href += "<div class='open-upload-install-photos'>Upload Install Photos: <a target='_blank' href='https://pure-electric.com.au/upload_file_sanden/for-plumber?invoice_id="+$('input[name="record"]').val()+"'>Open Upload For Sanden Plumber</a></div>";
                            }
                            $('#createPlumberPO').siblings().empty();
                            $('#createPlumberPO').parent().append(href);
                            SUGAR.ajaxUI.hideLoadingPanel();
                            //dung codec- display PO -plumber link
                            $('#PO_link_plum').remove();
                            $('#add_open_link_PO').append('<a id="PO_link_plum" target="_blank" href="/index.php?module=PO_purchase_order&amp;action=EditView&amp;record='+$('#plumber_po_c').val().trim() +'">Open PO- Plumber </a>');
                        });
                    }
                });
                return false;
            });
            
            $("#createElectricalPO").click(function(){
                if($("#electrical_po_c").val() != ""){
                    alert("Please click the link to edit PO");
                    return false;
                }
                if ($('#installation_date_c_date').val() == '' && $('#electrician_install_date_c').val() == '') {
                    alert("please fill 'Installation Date' or 'Electrician Install Date'!");
                    return false;
                }
                $('#createElectricalPO span.glyphicon-refresh').removeClass('hidden');
                var record_id = $("input[name='record']").val();
                var invoice_installation = $('input[name="installation_pictures_c"]').val();
                var purchase_installation = generateUUID();
                // submit form before create PO - electric by buttom
                SUGAR.ajaxUI.showLoadingPanel();
                $.ajax({
                    type: $("#EditView").attr('method'),
                    url: $("#EditView").attr('action'),
                    data: $("#EditView").serialize(),
                    async:false,
                    success: function () {
                        $.ajax({
                            url: "?entryPoint=createPurchaseOrder&type=electrical&record_id=" + record_id+"&electrical_account_id=" + $("#account_id_c").val()+ "&invoice_installation="+invoice_installation+ "&purchase_installation="+purchase_installation, 
                            context: document.body,
                            async: true
                        }).done(function (data) {
                            $('#createElectricalPO span.glyphicon-refresh').addClass('hidden');
                            console.log('createPOElectrician' + data);
                            if(data == '') return;
                            var id_link = $.parseJSON(data);
                            $("#electrical_po_c").val(id_link.po_id.trim());
                            if (id_link.meeting_id != '') {
                                $('#meeting_electrician').val(id_link.meeting_id);
                                showLinkMeeting('meeting_electrician',id_link.meeting_id);
                            }
                            // $("#electrical_po_c").val(data.trim());
                            // if (data.trim() == "") return false;
                            //?module=PO_purchase_order&offset=1&stamp=1517294972082855000&return_module=PO_purchase_order&action=EditView&record=3b056120-a5d4-ae50-8e32-5a7015aedb4a
                            var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + id_link.po_id.trim() + "'>" + "Open Purchase Order" + "</a></div>";
                                href += "<div class='open-upload-install-photos'>Upload Install Photos: <a target='_blank' href='https://pure-electric.com.au/upload_file_sanden/for-electrician?invoice_id="+$('input[name="record"]').val()+"'>Open Upload For Electrician</a></div>";
                            $('#createElectricalPO').siblings().empty();
                            $('#createElectricalPO').parent().append(href);
                            SUGAR.ajaxUI.hideLoadingPanel();
                            //dung code - display PO electric link
                            $('#PO_link_elec').remove();
                            $('#add_open_link_PO').append('<a id="PO_link_elec" target="_blank" href="/index.php?module=PO_purchase_order&amp;action=EditView&amp;record='+$('#electrical_po_c').val().trim() +'">Open PO- Electric </a>');
                        });
                    }
                });           
                return false;
            });
        }
        if (module_sugar_grp1 == 'AOS_Invoices') {
            if ($('#meeting_plumber').val() != '') {
                showLinkMeeting('meeting_plumber',$('#meeting_plumber').val());
            }
            if ($('#meeting_electrician').val() != '') {
                showLinkMeeting('meeting_electrician',$('#meeting_electrician').val());
            }
        }

        $('#line_items_span').on('change', 'input.product_part_number', function () {
            if($(this).val()=="VEEC Rebate Certificate" || $(this).val()=="STC Rebate Certificate" || $(this).val()=="SV_SHWR" || $(this).val() == "SA_REES" ){
                $(this).parent().parent().find('.product_vat_amt_select').val("0.0").trigger("change");
            }
            if($(this).val()=="FTXZ25N"){
                $(".daikininfo select").each(function(evt, ele){
                    if($(ele).val()== "") {

                        $(ele).val("US7 2.5kW small").trigger("change");
                        addDaikinTextRow();
                        daikinWifiController(true);
                    };
                });
            }

            if($(this).val()=="FTXZ35N"){
                $(".daikininfo select").each(function(evt, ele){
                    if($(ele).val()== "") {

                        $(ele).val("US7 3.5kW medium").trigger("change");
                        addDaikinTextRow();
                        daikinWifiController(true);
                    };
                });
            }

            if($(this).val()=="FTXZ50N"){
                $(".daikininfo select").each(function(evt, ele){
                    if($(ele).val()== "") {

                        $(ele).val("US7 5.0kW large").trigger("change");
                        addDaikinTextRow();
                        daikinWifiController(true);
                    };
                });
            }

            get_sanden_model();
        });
        //function update PO
        function Update_PO(ID_PurchacheOrder){
            SUGAR.ajaxUI.showLoadingPanel();
            //step 1: Save Invoice  
            $("#EditView input[name='action']").val('Save');
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                async:false,
                success: function (data) {
                    var invoice_id = $("input[name='record']").val();
                        if(invoice_id !='')  {
                            //step 2: Update PO
                            $.ajax({
                                url: "?entryPoint=createPurchaseOrder&action=update&record_id=" + invoice_id +"&ID_PurchacheOrder="+ID_PurchacheOrder,
                                async: true
                            }).done(function (data) {
                                console.log(data);
                                SUGAR.ajaxUI.hideLoadingPanel();
                            });
                        }else{
                            SUGAR.ajaxUI.hideLoadingPanel();
                        }
                }
            });           
        }

        YAHOO.util.Event.addListener("plumber_install_date_c", "change", function(){
            var ID_PurchacheOrder = $("#plumber_po_c").val();
            if(ID_PurchacheOrder != ''){
                Update_PO(ID_PurchacheOrder);
            }
            return false;
        });
        YAHOO.util.Event.addListener("electrician_install_date_c", "change", function(){
            var ID_PurchacheOrder = $("#electrical_po_c").val();
            if(ID_PurchacheOrder != ''){
                Update_PO(ID_PurchacheOrder);
            }
            return false;
        });
        YAHOO.util.Event.addListener("delivery_date_time_c_date", "change", function(){
            var ID_PurchacheOrder = $("#daikin_po_c").val();
            if(ID_PurchacheOrder != ''){
                Update_PO(ID_PurchacheOrder);
            }
            return false;
        });

        // re-add daikin items
        if($("select[name='daikin_info_product_name']").length == 1  && 
            $("select[name='daikin_info_product_name']").val()=="")
        $('#line_items_span input.product_qty').each(function(){
            var product_name = $(this).parent().next().find("input.product_name").val();
            var name_need_add = "";
            if(product_name=="Daikin US7 2.5kW"){
                name_need_add = "US7 2.5kW small";
            }

            if(product_name=="Daikin US7 3.5kW"){
                name_need_add = "US7 3.5kW medium";    
            }

            if(product_name=="Daikin US7 5kW"){
                name_need_add = "US7 5.0kW large";
            }
            var val = $(this).val();
            if(name_need_add != "") for (i = 0; i < val; i++) {
                $(".daikininfo select").filter(function(){
                    return !this.value;
                }).val(name_need_add).trigger("change");
                addDaikinTextRow();
               // daikinWifiController(true); dung code - delete because it auto product_qty when convert from lead 
            }
        });

        $('#line_items_span').on('change', 'input.product_qty', function () {
            var val = $(this).val();
            //console.log(val);
            var product_name = $(this).parent().next().find("input.product_name").val();
            var number_of_element = 0;
           

            var name_need_add = "";
            if(product_name=="Daikin US7 2.5kW"){
                name_need_add = "US7 2.5kW small";
            }

            if(product_name=="Daikin US7 3.5kW"){
                name_need_add = "US7 3.5kW medium";    
            }

            if(product_name=="Daikin US7 5kW"){
                name_need_add = "US7 5.0kW large";
            }

            $("select[name='daikin_info_product_name']").each(function (evt){
                if( $(this).val() == name_need_add ){
                    number_of_element ++;
                };
            });

            if(number_of_element < val && name_need_add != "") for (i = number_of_element; i < val; i++) {
                $(".daikininfo select").filter(function(){
                    return !this.value;
                }).val(name_need_add).trigger("change");
                addDaikinTextRow();
                daikinWifiController(true);
                    //};
                //});
            }

        });

        $('#line_items_span').on('change', 'input.product_name', function () {
			if($(this).val()=="VEECs" || $(this).val()=="STCs" || $(this).val()=="SA REES Rebate" || $(this).val()=="Solar VIC Solar Hot Water Rebate" ) {
                $(this).parent().parent().find('.product_vat_amt_select').val("0.0").trigger("change");
            }
            if($(this).val()=="Daikin US7 2.5kW"){
                $(".daikininfo select").each(function(evt, ele){
                    if($(ele).val()== "") {

                        $(ele).val("US7 2.5kW small").trigger("change");
                        addDaikinTextRow();
                        daikinWifiController(true);
                    };
                });
            }

            if($(this).val()=="Daikin US7 3.5kW"){
                $(".daikininfo select").each(function(evt, ele){
                    if($(ele).val()== "") {

                        $(ele).val("US7 3.5kW medium").trigger("change");
                        addDaikinTextRow();
                        daikinWifiController(true);
                    };
                });
            }

            if($(this).val()=="Daikin US7 5kW"){
                $(".daikininfo select").each(function(evt, ele){
                    if($(ele).val()== "") {

                        $(ele).val("US7 5.0kW large").trigger("change");
                        addDaikinTextRow();
                        daikinWifiController(true);
                    };
                });
            }
        });
             //tuan code --------------------
        if(module_sugar_grp1 == 'AOS_Invoices'){
            $('#electrician_c').parent().siblings('.label').append('<br> <button class="button primary" id="distanceElectrictoSuite"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>GET DISTANCE</button>');
            $('#electrician_c').parent().siblings('.label').append('<button style="font-size: smaller;margin: 0px 2px;" class="button primary" type="button" id="getDistance_selectedElectrician"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Distance Selected</button>');
            // $('#installation_date_c').parent().siblings('.label').append('<input type="button" id="client_warranty_registration" value="Email Client Warranty" class="button primary" data-email-address-id="'+$('#billing_contact_id').val()+'" data-email-type="client_warranty_registration" onclick="$(document).openComposeViewModal_reupload(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#billing_contact').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" /></li>');

            //Get Distance Selected Electrician
            $('#getDistance_selectedElectrician').click(function(){
                $('#getDistance_selectedElectrician span.glyphicon-refresh').removeClass('hidden');
                var distance_selected = '';
                var id_account = $('#account_id_c').val().trim();
                distance_selected = get_distance_by_account_id(id_account);

                if (typeof(distance_selected) == 'string') {
                    $('#distance_to_suitecrm_c').val(`${distance_selected}`);
                } else {
                    $('#distance_to_suitecrm_c').val(`${distance_selected} km`);
                }
                $('#getDistance_selectedElectrician span.glyphicon-refresh').addClass('hidden');            
            });
            // check water quality 
            $('#detail_site_install_address_postalcode_c').css('width','35%');
            $('#detail_site_install_address_postalcode_c').after( '&nbsp;<button  type="button" id="check_water_quality_invoice" class="button primary" title="Check Your Water Quality">CHECK WATER QUALITY<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
            $('#check_water_quality_invoice').click(function () {
                $('#check_water_quality_invoice span.glyphicon-refresh').removeClass('hidden');
                $('#link-sanden-hot-water-invoice').remove();
                var postcode_num = $('#detail_site_install_address_postalcode_c').val();
                $.ajax({
                    url: "?entryPoint=customCheckPostalCodeSandenWater&postcode_num="+postcode_num,
                    type : 'POST',
                    success: function (data) {
                        $('#check_water_quality_invoice span.glyphicon-refresh').addClass('hidden');
                        if( data == "There are no known water quality issues with this postcode."){
                            $('#check_water_quality_invoice').after('<br><a target="_blank" id="link-sanden-hot-water-invoice" title="Link Sanden Hot Water" href="https://www.sanden-hot-water.com.au/check-your-water-quality?r=549&postcode=' + postcode_num +'">Yes good !</a>')
                        }else {
                            $('#check_water_quality_invoice').after('<br><a target="_blank" id="llink-sanden-hot-water-invoice" title="Link Sanden Hot Water" href="https://www.sanden-hot-water.com.au/check-your-water-quality?r=549&postcode=' + postcode_num +'">FQV !</a>');
                        }
                    },
                });
            })
        }     ///////////////////////////////
        if(module_sugar_grp1 == 'AOS_Invoices'){
            // daikin
            $('#supply_bill_c').css('width','35%');
            $('#install_bill_c').css('width','35%');
            $('#total_cost_c').css('width','35%');
            $('#total_revenue_c').css('width','35%');
            $('#gross_profit_c').css('width','35%');
            $('#gross_profit_percent_c').css('width','35%');
            // sanden
            $('#plumbing_bill_c').css('width','35%');
            $('#electrician_bill_c').css('width','35%');
            $('#sanden_supply_bill_c').css('width','35%');
            $('#sanden_total_costs_c').css('width','35%');
            $('#sanden_revenue_c').css('width','35%');
            $('#sanden_stcs_c').css('width','35%');
            $('#sanden_total_revenue_c').css('width','35%');
            $('#sanden_gross_profit_c').css('width','35%');
            $('#sanden_gprofit_percent_c').css('width','35%');
            $('#daikin_supply_bill_c').parent().siblings('.label').append('<br><button type="button" class="button" id="price-revenue-xero"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Get Price</button>');
            $('#price-revenue-xero').on('click',function (){
                if( $('#xero_invoice_c').val() == "" ){
                    alert("Please enter xero invoice id!");
                    $('#xero_invoice_c').focus();
                    return false;
                }
                if( $('#daikin_supply_bill_c').val() == "" ){
                    alert("Please enter supply bill id!");
                    $('#daikin_supply_bill_c').focus();
                    return false;
                }
                $('#link-xero-supply').remove();
                $('#link-xero-install').remove();
                $('#supply_bill_c').val('');
                $('#install_bill_c').val('');
                $('#total_cost_c').val('');
                $('#total_revenue_c').val('');
                $('#gross_profit_c').val('');
                $('#gross_profit_percent_c').val('');

                $('#price-revenue-xero span.glyphicon-refresh').removeClass('hidden');
                var revenue = $('#xero_invoice_c').val();
                var supply = $('#daikin_supply_bill_c').val();
                var install = $('#daikin_install_bill_c').val();
                $.ajax({
                        url: "/xero-php/index.php?type=daikin&supplyID="+supply+"&installID="+install+"&revenueID="+revenue,
                        type : 'POST',
                        success: function (data) {
                            if(data == '' || typeof data === 'undefined') {
                                $('#price-revenue-xero span.glyphicon-refresh').addClass('hidden');
                                return;
                            }
                            var infor = $.parseJSON(data);
                                var pr_install = infor[0].install;
                                var pr_supply = infor[0].supply;
                                var pr_revenue = infor[0].revenue;
                                $('#daikin_supply_bill_c').after('<a target="_blank" id="link-xero-supply" title="Link Xero" href="https://go.xero.com/AccountsPayable/Edit.aspx?InvoiceID=' +supply+'">Open Link Xero</a>');
                                if( pr_install != "0.00" ){
                                    $('#daikin_install_bill_c').after('<a target="_blank" id="link-xero-install" title="Link Xero" href="https://go.xero.com/AccountsPayable/Edit.aspx?InvoiceID=' +install+'">Open Link Xero</a>');
                                }
                                var cost = parseFloat(pr_install) + parseFloat(pr_supply);
                                var rev = parseFloat(pr_revenue)- cost;
                                var gross = ((rev/cost)*100).toFixed(2) ;
                                $('#supply_bill_c').val('$'+pr_supply);
                                $('#install_bill_c').val('$'+pr_install);
                                $('#total_cost_c').val('$'+cost);
                                $('#total_revenue_c').val('$'+pr_revenue);
                                $('#gross_profit_c').val('$'+rev.toFixed(2));
                                $('#gross_profit_percent_c').val(gross+'%');
                                $('#price-revenue-xero span.glyphicon-refresh').addClass('hidden');
                            },
                          error: function(req, err){ 
                             alert('ID enter incorrect!')
                        }   
                    
                });            
            })
            if( $('#daikin_supply_bill_c').val() != ""){
                $('#daikin_supply_bill_c').after('<a target="_blank" id="link-xero-supply" title="Link Xero" href="https://go.xero.com/AccountsPayable/Edit.aspx?InvoiceID=' +$('#daikin_supply_bill_c').val()+'">Open Link Xero</a>');
            }
            if( $('#daikin_install_bill_c').val() != ""){
                $('#daikin_install_bill_c').after('<a target="_blank" id="link-xero-install" title="Link Xero" href="https://go.xero.com/AccountsPayable/Edit.aspx?InvoiceID=' +$('#daikin_install_bill_c').val()+'">Open Link Xero</a>');
            }
        
            $('#sanden_plumbing_install_bill_c').parent().siblings('.label').append('<br><button type="button" class="button" id="price_revenue_xero_sanden"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Get Price</button>');
            $('#price_revenue_xero_sanden').on('click',function (){
                if( $('#sanden_electrician_inst_bill_c').val() == "" ){
                    alert("Please enter plumbing install id!");
                    $('#sanden_electrician_inst_bill_c').focus();
                    return false;
                }
                if( $('#sanden_plumbing_install_bill_c').val() == "" ){
                    alert("Please enter electrician install id!");
                    $('#sanden_plumbing_install_bill_c').focus();
                    return false;
                }
                $('#link-xero-plumbing-install').remove();
                $('#link-xero-electrician-install').remove();
                $('#plumbing_bill_c').val('');
                $('#electrician_bill_c').val('');
                $('#sanden_supply_bill_c').val('');
                $('#sanden_total_costs_c').val('');
                $('#sanden_revenue_c').val('');
                $('#sanden_stcs_c').val('');
                $('#sanden_total_revenue_c').val('');
                $('#sanden_gross_profit_c').val('');
                $('#sanden_gprofit_percent_c').val('');
                $('#price_revenue_xero_sanden span.glyphicon-refresh').removeClass('hidden');
                var stc_number = $('#stc_aggregator_serial_c').val();
                var revenue = $('#xero_invoice_c').val();
                var plumbing = $('#sanden_plumbing_install_bill_c').val();
                var electrician = $('#sanden_electrician_inst_bill_c').val();
                $.ajax({
                        url: "/xero-php/index.php?type=sanden&supplyID="+plumbing+"&installID="+electrician+"&revenueID="+revenue+"&stc_number="+stc_number,
                        type : 'POST',
                        success: function (data) {
                                if(data == '' || typeof data === 'undefined') {
                                    $('#price_revenue_xero_sanden span.glyphicon-refresh').addClass('hidden');
                                    return;
                                }
                                var infor = $.parseJSON(data);
                                var electrician_bill = infor[0].electrician_bill;
                                var plumbing_bill = infor[0].plumbing_bill;
                                var pr_revenue = infor[0].revenue;
                                var sden_supply = parseFloat( (infor[0].sanden_bill_pro).toFixed(2));
                                var sanden_stcs = parseFloat(infor[0].sanden_stc);
                                $('#sanden_plumbing_install_bill_c').after('<a target="_blank" id="link-xero-plumbing-install" title="Link Xero" href="https://go.xero.com/AccountsPayable/Edit.aspx?InvoiceID=' +plumbing+'">Open Link Xero</a>');
                                if( electrician_bill != "0.00" ){
                                    $('#sanden_electrician_inst_bill_c').after('<a target="_blank" id="link-xero-electrician-install" title="Link Xero" href="https://go.xero.com/AccountsPayable/Edit.aspx?InvoiceID=' +electrician+'">Open Link Xero</a>');
                                }
                                var cost = parseFloat(sden_supply) + parseFloat(electrician_bill) + parseFloat(plumbing_bill);
                                var total_rev = parseFloat(pr_revenue.toFixed(2)) +  parseFloat(sanden_stcs.toFixed(2));
                                var gross = (total_rev - cost).toFixed(2);
                                var gross_per = ((gross/cost)*100).toFixed(2);
                                $('#plumbing_bill_c').val("$"+plumbing_bill);
                                $('#electrician_bill_c').val('$'+electrician_bill);
                                $('#sanden_supply_bill_c').val("$"+sden_supply.toFixed(2));
                                $('#sanden_total_costs_c').val('$'+cost);
                                $('#sanden_revenue_c').val('$'+pr_revenue);
                                $('#sanden_stcs_c').val('$'+sanden_stcs);
                                $('#sanden_total_revenue_c').val('$'+total_rev.toFixed(2));
                                $('#sanden_gross_profit_c').val('$'+gross);
                                $('#sanden_gprofit_percent_c').val(gross_per+'%');
                                $('#price_revenue_xero_sanden span.glyphicon-refresh').addClass('hidden');
                            },
                          error: function(req, err){ 
                             alert('ID enter incorrect!')
                        }   
                    
                });            
            })
           
        }
        // tuan code distance to sanden electrician
        $('#distanceElectrictoSuite').click(function (){
            $('#distanceElectrictoSuite span.glyphicon-refresh').removeClass('hidden');
            if( $('#install_address_c').val() == "" ){
            var from_address =  $("#billing_address_street").val() +", " +
                                    $("#billing_address_city").val() + ", " +
                                    $("#billing_address_state").val() + ", " +
                                    $("#billing_address_postalcode").val();
             
            }else {
                var from_address =  $("#install_address_c").val() +", " +
                                    $("#install_address_city_c").val() + ", " +
                                    $("#install_address_state_c").val() + ", " +
                                    $("#install_address_postalcode_c").val();
            }
            // var ac_id = $('#account_id1_c').val();
            if($('#quote_type_c').val() == ""){
                alert('Please fill for product type !');
                $('#quote_type_c').focus();
                $('#distanceElectrictoSuite span.glyphicon-refresh').addClass('hidden');
                return false;
            }
            var product_type = $('#quote_type_c').val();
            if( product_type == 'quote_type_daikin' ){
                var url_e = "?entryPoint=customFilterPlumber&type=daikin_instaler&address_from=" + from_address;
            }else {
                var url_e = "?entryPoint=customFilterPlumber&type=electrician&address_from=" + from_address;
            }
            if( $('#electrician_c').val() == ""){
                $.ajax({
                    url: url_e,
                    success: function (data) {
                        try {
                            var infor = $.parseJSON(data);
                            $("#btn_clr_electrician_c").after("<b class='suggest'> Suggest: </b><br/>");
                            $("#btn_clr_electrician_c").after("</br><b class='shortest'> Shortest: </b><br/><b class='shortest-suggest'></b>");
                            
                            infor.sort(function(a,b){
                                return a.distance - b.distance;
                            });
                            for ( var i = 0 ; i < 5 ; i++){  
                                var addr = infor[0][0];
                                var name_lum =infor[0][1];
                                var id_nearest = infor[0][2];
                                var str_dis =  infor[0][3];
                    
                                $("#btn_clr_electrician_c").nextAll('.shortest-suggest').html('<a class="selected-suggest" dist="'+str_dis+'" rel="'+addr+'" href="#">'+name_lum+': '+addr+':<span style="color:green">'+str_dis+'</span></a> <br>');
                                $("#distance_to_suitecrm_c").val(str_dis);
                                $('#electrician_c').val(name_lum);
                                $('#account_id_c').val(id_nearest);
                                $("#btn_clr_electrician_c").nextAll('.suggest').append('<a class="selected-suggest" dist="'+infor[i][3]+'" rel="'+infor[i][0]+'" href="#">'+infor[i][1]+': '+infor[i][0]+':<span style="color:green">'+infor[i][3]+'</span></a> <br>');
                            }  
                        } catch (error) {
                            console.log(error);
                        }
                        $('#distanceElectrictoSuite span.glyphicon-refresh').addClass('hidden');
                    }
                }); 
            }else {
                $.ajax({
                    url: url_e,
                    success: function (data) {
                        try {
                            var infor = $.parseJSON(data);
                            $("#btn_clr_electrician_c").after("<b class='suggest'> Suggest: </b><br/>");
                            $("#btn_clr_electrician_c").after("</br><b class='shortest'> Shortest: </b><br/><b class='shortest-suggest'></b>");
                            
                            infor.sort(function(a,b){
                                return a.distance - b.distance;
                            });
                            for ( var i = 0 ; i < 5 ; i++){  
                                var addr = infor[0][0];
                                var name_lum =infor[0][1];
                                var str_dis =  infor[0][3];
                    
                                $("#btn_clr_electrician_c").nextAll('.shortest-suggest').html('<a class="selected-suggest" dist="'+str_dis+'" rel="'+addr+'" href="#">'+name_lum+': '+addr+':<span style="color:green">'+str_dis+'</span></a> <br>');
                                $("#btn_clr_electrician_c").nextAll('.suggest').append('<a class="selected-suggest" dist="'+infor[i][3]+'" rel="'+infor[i][0]+'" href="#">'+infor[i][1]+': '+infor[i][0]+':<span style="color:green">'+infor[i][3]+'</span></a> <br>');
                            }  
                        } catch (error) {
                            console.log(error);
                        }
                        $('#distanceElectrictoSuite span.glyphicon-refresh').addClass('hidden');
                    }
                }); 
            }
               
            return false
        });
                       //-------------------------------
        var previous_note;
        $(".product_description").on('focus', function(){
            previous_note = $(this).val();
        })
        .change(function(){
            if($(this).val().indexOf("Total Price") != -1){
                var value = $(this).val().replace("Total Price", "").replace(/[^0-9\.-]+/g,"");
                $(this).parent().parent().prev("tr").find(".product_list_price").val(((value*8/100)/1.1).toFixed(2));

                var oldDesc = $(this).parent().parent().find(".product_item_description").val();
                $(this).parent().parent().find(".product_item_description").val(oldDesc.replace(previous_note,$(this).val()));
            };
        });
        // Autocomplete search address
        //shipping_address_street
        
        //tu-code Near Map
        //tuan code
        var address = $("#billing_address_street").val()+','+$("#billing_address_city").val()+','+$("#billing_address_state").val()+','+$("#billing_address_postalcode").val();     
        var open_map = "<div><a target='_blank' href='http://maps.nearmap.com?addr='"+ address + "'&z=22&t=roadmap'>Near Map</a></div>";
        $("#billing_address_street_label label").after('<a style="float: right;cursor:pointer;" id="open_map_billing" title="Google Maps"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        
        $("#billing_address_street").before(
            '<div style="background-color: white;display:none;border:1px solid;position:absolute; padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_billing" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a style="cursor:pointer;" onclick="openBillingMap(); return false;">Google Maps</a></li>'+
                '<li><a style="cursor:pointer;" href="http://maps.nearmap.com?addr='+ address +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_billing">Realestate</a></li>'+
                '</ul>'+
            '</div>'
            );
        $("#shipping_address_street_label label").after(
            '<a style="float: right;cursor:pointer;" id="open_map_shipping" title="Google Maps"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        $("#shipping_address_street").before(
            '<div style="background-color: white;display:none;border:1px solid;position:absolute; padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_shipping" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a style="cursor:pointer;" onclick="openShippingMap(); return false;">Google Maps</a></li>'+
                '<li><a style="cursor:pointer;"  href="http://maps.nearmap.com?addr='+ address +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_shipping" >Realestate</a></li>'+
                '</ul>'+
            '</div>'
        );
        $("#link_realestate_billing").click(function(){
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
    
        $("#link_realestate_shipping").click(function(){
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

        if(module_sugar_grp1 == 'AOS_Invoices'){
        
            $("div[data-label='LBL_INSTALL_ADDRESS']").append(
                '<a style="float: right;cursor:pointer;" id="open_map_install" title="Google Maps" onClick=" $(document).find(\'#open_map_popup_install\').fadeToggle();"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
            $("#install_address_c").before(
                '<div style="z-index:10;background-color: white;display:none;border:1px solid;position:absolute; padding:3px;margin-top:-15px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_install" class="show-open-map hide_map">'+
                    '<ul>'+
                    '<li><a style="cursor:pointer;" onclick="openInstallMap(); return false;">Google Maps</a></li>'+
                    '<li><a style="cursor:pointer;" href="http://maps.nearmap.com?addr='+ address +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                    '<li><a style="cursor:pointer;" id="link_realestate_install">Realestate</a></li>'+
                    '</ul>'+
                '</div>'
            );
            $("#link_realestate_install").click(function(){
                SUGAR.ajaxUI.showLoadingPanel();
                $.ajax({
                    url: "?entryPoint=getLinkRealestate",
                    type: 'POST',
                    data: {
                        street    : $("#install_address_c").val(),
                        city      : $("#install_address_city_c").val(),
                        state     : $("#install_address_state_c").val(),
                        postcode  : $("#install_address_postalcode_c").val(),
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
    
        }

        $("div[data-label='LBL_SITE_DETAIL_ADDR_']").append(
            '<a style="float: right;cursor:pointer;" id="open_map_site_install" title="Google Maps"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        
        $('#open_map_site_install').click(function(){
            $('#open_map_popup_site').fadeToggle()
        })
        $('#open_map_billing').click(function(){
                $('#open_map_popup_billing').fadeToggle()
            })
        $('#open_map_shipping').click(function(){
                $('#open_map_popup_shipping').fadeToggle()
        })
        $('#open_map_install').click(function(){
            $('#open_map_popup_install').fadeToggle()
        })
      
        $("#shipping_address_street").autocomplete({
            source: function( request, response ) {
                console.log(request["term"]);
                Math.floor((Math.random() * 3) + 1);


                // https://www.agl.com.au/svc/QAS/GetSearchResult?searchKey=26%20Walsh%20Avenue&maxResults=10&searchType=SiteAddressSearch&_=1499225218711
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
                            var suggest =[];
                            if(data == '' || typeof data === 'undefined')return;
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
                //console.log(response);
            },
            select: function( event, ui ) {
                console.log(ui.item.value);
                var value =  ui.item.value.split(",");
                var address1 = value[0];
                var address2 = value[1];

                $("#shipping_address_street").val(address1);

                var address3 = address2.split("  ");

                $("#shipping_address_city").val(address3[0].trim());
                $("#shipping_address_state").val(address3[1].trim());
                $("#shipping_address_postalcode").val(address3[2].trim());

                return false;
            }
        });

        $("#billing_address_street").autocomplete({
            source: function( request, response ) {
                /*var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
                response( $.grep( tags, function( item ){
                return matcher.test( item );
                }) );*/
                console.log(request["term"]);
                Math.floor((Math.random() * 3) + 1);


                // https://www.agl.com.au/svc/QAS/GetSearchResult?searchKey=26%20Walsh%20Avenue&maxResults=10&searchType=SiteAddressSearch&_=1499225218711
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
                            if(data == '' || typeof data === 'undefined')return;
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
                //console.log(response);
            },
            select: function( event, ui ) {
                console.log(ui.item.value);
                var value =  ui.item.value.split(",");
                var address1 = value[0];
                var address2 = value[1];

                $("#billing_address_street").val(address1);

                var address3 = address2.split("  ");

                $("#billing_address_city").val(address3[0].trim());
                $("#billing_address_state").val(address3[1].trim());
                $("#billing_address_postalcode").val(address3[2].trim());
                // $("#solargain_options_c").trigger('change');
                return false;
            }
        });

        $("#install_address_c").autocomplete({
            source: function( request, response ) {
                /*var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
                response( $.grep( tags, function( item ){
                return matcher.test( item );
                }) );*/
                console.log(request["term"]);
                Math.floor((Math.random() * 3) + 1);


                // https://www.agl.com.au/svc/QAS/GetSearchResult?searchKey=26%20Walsh%20Avenue&maxResults=10&searchType=SiteAddressSearch&_=1499225218711
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
                            if(data == '' || typeof data === 'undefined')return;
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
                //console.log(response);
            },
            select: function( event, ui ) {
                console.log(ui.item.value);
                var value =  ui.item.value.split(",");
                var address1 = value[0];
                var address2 = value[1];

                $("#install_address_c").val(address1);

                var address3 = address2.split("  ");

                $("#install_address_city_c").val(address3[0].trim());
                $("#install_address_state_c").val(address3[1].trim()).trigger("change");
                $("#install_address_postalcode_c").val(address3[2].trim());
                return false;
            }
        });
        // Logic for total_amount
        if(module_sugar_grp1 == 'AOS_Invoices'){
            var old_total_amount = $("#total_amount").val().replace(',','');
            var old_first_product_price = 0;
            if(typeof old_total_amount === "undefined") old_total_amount = 0;
            if(typeof $("#product_product_list_price0") !== "undefined" &&  typeof $("#product_product_list_price0").val() !== "undefined"){

                old_first_product_price = $("#product_product_list_price0").val().replace(',','');

            }
        }
        //dung fix run Quote or Invoice
        // var select_detailpanel = "#detailpanel_2";
        // if(module_sugar_grp1 == 'AOS_Invoices'){
        //     select_detailpanel = "#detailpanel_2";
        // }
        $(document).on('focusin', "#total_amount", function(){
            old_total_amount = $("#total_amount").val().replace(',','');
        }).on('change', "#total_amount", function(){
            if(module_sugar_grp1 == 'AOS_Invoices'){
                //$("#total_amount").change(function(){
                if(typeof $("#product_product_list_price0") !== "undefined" &&  typeof $("#product_product_list_price0").val() !== "undefined"){
                    old_first_product_price = $("#product_product_list_price0").val().replace(',','');
                }
                var delta = (old_total_amount - $("#total_amount").val().replace(',','') )/1.1;
                var qty = $('#product_product_qty0').val();
                $("#product_product_list_price0").val(old_first_product_price - (delta/qty));
                $("#product_product_list_price0").trigger("blur");
            }
        });

        $("#CANCEL").after(
            ' <button type="button" id="save_and_edit" class="button saveAndEdit" title="Save and Edit" onClick="SUGAR.saveAndEdit(this);">Save and Edit</button>'
        )
        SUGAR.saveAndEdit = function (elem) {
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
                    loadSelect_CES_Template();
                    loadSelect_PCOC_Template();
                    SUGAR.ajaxUI.hideLoadingPanel();
                }
            });
            return false;
        }
        //VUT - S - add button copy Image from Lead/Quote to Invoice
        if (module_sugar_grp1 == 'AOS_Invoices') {
            $("#get_files_from_s3_invoice").after('<button type="button" style="background: #008000;" id="copy_img_from_quotelead" class="button primary" title="Copy image from Lead and Quote">Get files from Quote & Lead<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
            $("body").on('click','#copy_img_from_quotelead', function() {
                SUGAR.ajaxUI.showLoadingPanel();
                var record_id = $("input[name='record']").val();
                $.ajax({
                    url: "?entryPoint=copyImageQuoteLead2Inv&record_id=" + record_id,
                    async: false
                }).done(function (data) {
                    console.log(data);
                    $(".reload_after_rename").trigger("click");
                    SUGAR.ajaxUI.hideLoadingPanel();
                });
            });
        }
        //VUT - E - add button copy Image from Lead/Quote to Invoice

        $("#save_and_edit").after(
            ' <button type="button" style="background: #00b2e2;" id="create_assignment" class="button createAssignment" title="Create Assignment" onClick="SUGAR.createAssignment(this);" > Create Get Assignment <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )


        SUGAR.createAssignment = function (elem) {
            //thienpb code -- disable button for daikin
            if($("#quote_type_c").val() == "quote_type_daikin" || $("#quote_type_c").val() == "quote_type_nexura"){
                alert("Function disabled for Daikin.");
                return false;
            }
            if (!hasFileAndPhoto('pcoc')) {
                // alert('Noooo');
                var question = confirm("No PCOC attached. Are you sure you want to create the GET Assignment?");
                if (!question) {
                    return false;
                }
            }
            // return false;
            //dung code - alert confirm
            var dialog = $('<p>CONFIRM with PE Contact if this Customer is GST Registered? If UNSURE, click CANCEL</p>').dialog({
                buttons: {
                    "Yes": function() { RunCreateAssignment(); dialog.dialog('close');},
                    "Cancel":  function() {
                        dialog.dialog('close');
                    }
                }
            });
            function RunCreateAssignment() {
                $('#create_assignment span.glyphicon-refresh').removeClass('hidden');
                // encodeURIComponent(plumbing_note);
                var build_url=  "?entryPoint=customCreateAssignment";
                build_url += '&your_reference='+ encodeURIComponent($("#name").val()) ;
                // Name =
                var name = $("#billing_contact").val().split(" ");
                build_url += '&last_name='+ encodeURIComponent(name['0']) ;
                name[1] = $("#billing_contact").val().replace(name['0'], "").trim();
                if (typeof name['1'] === "undefined") name['1'] = name['0'];
                build_url += '&sur_name='+ encodeURIComponent(name['1']) ;
                //dung code - add name companyname = entity name
                build_url += '&companyname='+ encodeURIComponent($('#entity_name_c').val()) ;
                //dung code - add name abn = ABN
                build_url += '&abn='+ encodeURIComponent($('#abn_c').val());
                // dung code -  add System Owner tax invoice to GET total  = STC amount in the Suite Invoice
                var benefitInvoiceTotal = 0;
                $("input[name^=product_product_id]").each(function(){
                    var product_id = $(this).val();
                    if(/*product_id == 'a85d69eb-d43e-64df-d4c2-5a964c707cfe' ||*/ product_id == '4efbea92-c52f-d147-3308-569776823b19'){
                         benefitInvoiceTotal = $(this).parent().parent().find('td:nth-child(9) input.product_total_price').val();
                         benefitInvoiceTotal = benefitInvoiceTotal.replace("-","");
                         benefitInvoiceTotal = benefitInvoiceTotal.replace(",","");
                         benefitInvoiceTotal = benefitInvoiceTotal.slice(0,-3);
                    }
                });
                build_url += '&benefitInvoiceTotal='+ encodeURIComponent(benefitInvoiceTotal);

                // dung code -  add Amount of benefit provided (VEEC) =   Amount VEEC Rebate
                var benefitProvidedVEEC = 0;
                $("input[name^=product_product_id]").each(function(){
                    var product_id = $(this).val();
                    if(product_id == 'cbfafe6b-5e84-d976-8e32-574fc106b13f'){
                        benefitProvidedVEEC = $(this).parent().parent().find('td:nth-child(9) input.product_total_price').val();
                        benefitProvidedVEEC = benefitProvidedVEEC.replace("-","");
                        benefitProvidedVEEC = benefitProvidedVEEC.replace(",","");
                        benefitProvidedVEEC = benefitProvidedVEEC.slice(0,-3);
                    }
                });
                build_url += '&benefitProvidedVEEC='+ encodeURIComponent(benefitProvidedVEEC);


                var date =  $("#installation_date_c_date").val().split("/");
                var newDate = new Date(date[2],date[1]-1,date[0]);
                //if(typeof  newDate != )
                var isoDate = ""; 
                if(!isNaN(newDate.getTime())) {
                    var isoDate = newDate.toISOString().slice(0, 10);
                    build_url += '&date=' + encodeURIComponent(date[2]+"-"+date[1]+"-"+date[0]);
                }

                var system_type = $("#system_owner_type_c").val();
                build_url += '&system_owner_type='+ encodeURIComponent(system_type) ;

                // Installation
                var installation_type_c = $("#installation_type_c").val();
                build_url += '&installation_type_c='+ encodeURIComponent(installation_type_c) ;

                //
                var install_address_state_c = $("#install_address_state_c").val();
                build_url += '&state='+ encodeURIComponent(install_address_state_c) ;
                // Install address
                var install_address = $("#install_address_c").val() +", "+ $("#install_address_city_c").val() + " " +
                    $("#install_address_state_c").val() + " " + $("#install_address_postalcode_c").val();
                build_url += '&install_address='+ encodeURIComponent(install_address) ;

                // installType
                // var old_tank_fuel_c = $("#old_tank_fuel_c").val();
                // var installType = old_tank_fuel_c;
                // if(old_tank_fuel_c == "electric_storage" || old_tank_fuel_c == "gravity_feed_electric" || old_tank_fuel_c == "instant_electric") {
                //     installType = "replacedElectricHeater";
                // }
                // if(old_tank_fuel_c == "gas_storage" || old_tank_fuel_c == "gas_instant" ) {
                //     installType = "replaceGasWh";
                // }
                // if(old_tank_fuel_c == "heatpump" ) {
                //     installType = "replacedHeatPump";
                // }
                // if(old_tank_fuel_c == "solar" ) {
                //     installType = "replacedSolarWaterHeater";
                // }
                // if(old_tank_fuel_c == "wood" || old_tank_fuel_c == "other") {
                //     installType = "other";
                // }

                // if(old_tank_fuel_c == "newBuilding" ) {
                //     installType = "newBuilding";
                // }
                var installType = $("#geo_type_of_wh_replaced_c").val();
                build_url += '&installType='+ encodeURIComponent(installType);
                // Sanden Model

                var sanden_model_c = $("#sanden_model_c").val();
                build_url += '&sanden_model_c='+ encodeURIComponent(sanden_model_c) ;
                var number_of_installations_c = $("input[name='number_of_installations_c']:checked").val();
                build_url += '&number_of_installations_c='+ encodeURIComponent(number_of_installations_c) ;

                var decommissioning_system_locat_c = $("#decommissioning_system_locat_c").val();
                build_url += '&decommissioning_system_locat_c='+ encodeURIComponent(decommissioning_system_locat_c) ;

                var removal_of_decommissioned_pr_c = $("#removal_of_decommissioned_pr_c").val();
                build_url += '&removal_of_decommissioned_pr_c='+ encodeURIComponent(removal_of_decommissioned_pr_c) ;

                var decommissioning_method_c = $("#decommissioning_method_c").val();
                build_url += '&decommissioning_method_c='+ encodeURIComponent(decommissioning_method_c) ;

                var number_of_storeys_c = $("select[name='number_of_storeys_c']").val();
                build_url += '&number_of_storeys_c='+ encodeURIComponent(number_of_storeys_c) ;
                //property_type_c

                var property_type_c = $("select[name='property_type_c']").val();
                build_url += '&property_type_c='+ encodeURIComponent(property_type_c) ;

                var vba_pic_cert_c = $("#vba_pic_cert_c").val();
                build_url += '&vba_pic_cert_c='+ encodeURIComponent(vba_pic_cert_c) ;

                var ces_cert_c = $("#ces_cert_c").val();
                build_url += '&ces_cert_c='+ encodeURIComponent(ces_cert_c) ;

                var sanden_tank_serial_c = $("#sanden_tank_serial_c").val();
                build_url += '&sanden_tank_serial_c='+ encodeURIComponent(sanden_tank_serial_c) ;
                var plumber_id = $("#contact_id4_c").val();
                build_url += '&plumber_id='+ encodeURIComponent(plumber_id) ;

                var electrical_id = $("#contact_id_c").val();
                build_url += '&electrical_id='+ encodeURIComponent(electrical_id) ;

                var plumber_install_date_c = ($('#vba_pic_date_c').val() != "") ? $('#vba_pic_date_c').val().split('/') : $('#plumber_install_date_c').val().split('/');        
                var plumber_install_date = new Date(plumber_install_date_c[2],plumber_install_date_c[1]-1,plumber_install_date_c[0]);
                var electrician_install_date_c = ($('#ces_cert_date_c').val()) ? $('#ces_cert_date_c').val().split('/') : $('#electrician_install_date_c').val().split('/');
                var electrician_install_date = new Date(electrician_install_date_c[2],electrician_install_date_c[1]-1,electrician_install_date_c[0]);

                var installer = "";
                if(!isNaN(plumber_install_date.getTime()) && !isNaN(electrician_install_date.getTime())) {
                    if ( (plumber_install_date.toISOString()) >= (electrician_install_date.toISOString()) ){
                        installer = $("#plumber_c").val();
                    }
                    else {
                        installer = $("#electrician_c").val();
                    }
                } else if(!isNaN(plumber_install_date.getTime())) {
                    installer = $("#plumber_c").val();
                } else if(!isNaN(electrician_install_date.getTime())) {
                    installer = $("#electrician_c").val();
                }

                if (ces_cert_c != "") installer = $("#plumber_c").val();
                build_url += '&installer='+ encodeURIComponent(installer) ;
                //var plumber_name =  $("#plumber_c").val();
                var plumber_name =  $("#plumber_contact_c").val();
                build_url += '&plumber_name='+ encodeURIComponent(plumber_name) ;
                //var electrician_name =  $("#electrician_c").val();
                var electrician_name =  $("#electrician_contact_c").val();
                build_url += '&electrician_name='+ encodeURIComponent(electrician_name) ;


                build_url += '&plumber_cert_date='+ encodeURIComponent(plumber_install_date_c[2] + "-" + plumber_install_date_c[1] + "-" + plumber_install_date_c[0]) ;
                //ces_cert_date_c

                var ces_cert_date_c = $('#ces_cert_date_c').val().split('/');
                //var ces_cert_date = new Date(ces_cert_date_c[2],ces_cert_date_c[1]-1,ces_cert_date_c[0]);
                //var ces_cert_date_par = ces_cert_date.toISOString().slice(0, 10)
                if($('#ces_cert_date_c').val() != "") {
                    build_url += '&ces_cert_date_par=' + encodeURIComponent(ces_cert_date_c[2] + "-" + ces_cert_date_c[1] + "-" + ces_cert_date_c[0]);
                }
                var record_id = $('#billing_contact_id').val();
                var owner_email = "";
                var owner_phone = "";

                $.ajax({
                    url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                    context: document.body,
                    async: false
                }).done(function (data) {
                    if(data == '' || typeof data === 'undefined')return;
                    var json = $.parseJSON(data);
                    owner_email = json.email;
                    owner_phone = json.phone_number;
                });


                /*$.ajax({
                    url: "http://loc.suitecrm.com/index.php?entryPoint=generatePdf&templateID=91964331-fd45-e2d8-3f1b-57bbe4371f9c&task=pdf&module=AOS_Invoices&uid=9bff5445-2426-5ecc-94bf-59013c3b70c3",
                    type: "GET",


                    success: function(result, text, xhr ){
                        //console.log(result);
                        var header = xhr.getResponseHeader('Content-Disposition');var filename = header.match(/filename="(.+)"/)[1]
                        console.log(filename);
                    }
                });*/

                /*var filecontent;
                var filename = "";
                var aBlob;
                $.ajax({
                    url: "http://loc.suitecrm.com/index.php?entryPoint=generatePdf&templateID=91964331-fd45-e2d8-3f1b-57bbe4371f9c&task=pdf&module=AOS_Invoices&uid=9bff5445-2426-5ecc-94bf-59013c3b70c3",
                    type: "GET",
                    async: false,
                    success: function(result, text, xhr){
                        filecontent = result;
                        var header = xhr.getResponseHeader('Content-Disposition');
                        filename = header.match(/filename="(.+)"/)[1];

                        var type = xhr.getResponseHeader('Content-Type');
                        aBlob = new File([result], filename, { type: type });
                    }
                });
                var data = new FormData();

                var blob = new Blob([filecontent] ,{type: "application/pdf" });
                var fileOfBlob = new File([filecontent], filename, {
                    type: "application/pdf",
                });
                data.append("file", fileOfBlob);
                    //build_url += '&file_content='+ encodeURIComponent(filecontent) ;

                build_url += '&filename='+ encodeURIComponent(filename) ;
                */
                build_url += '&record='+ encodeURIComponent($('input[name="record"]').val());
                build_url += '&owner_email='+ encodeURIComponent(owner_email) ;
                build_url += '&owner_phone='+ encodeURIComponent(owner_phone) ;

                var registered_for_gst_c = $('#registered_for_gst_c:checked').val();
                build_url += '&registered_for_gst_c='+ encodeURIComponent(registered_for_gst_c) ;

                var payment_for_cert_c = $('#payment_for_cert_c:checked').val();
                build_url += '&payment_for_cert_c='+ encodeURIComponent(payment_for_cert_c) ;

                // VBA PIC CERT -- change name PCOC
                var vba_link = [];
                var ces_link = "";
                var geo_tag_link = [];
                var System_Owver_Tax_Invoice_link="";
                var CustomerAgreement="";
                $("#fileupload tr p.name a").each(function() {
                    if ($(this).attr("href").toLowerCase().indexOf("pcoc") != -1) {
                        vba_link.push($(this).attr("href"));
                        if ($(this).attr("href").toLowerCase().indexOf("pdf") != -1) {
                            vba_link = [$(this).attr("href")];
                        }
                    }
                });
                
                vba_link = JSON.stringify(vba_link);

                $("#fileupload tr p.name a").each(function() {
                    if ($(this).attr("href").toLowerCase().indexOf("new1") != -1 
                    ||$(this).attr("href").toLowerCase().indexOf("tank_serial") != -1 
                    ||$(this).attr("href").toLowerCase().indexOf("decommission") != -1 
                    ||$(this).attr("href").toLowerCase().indexOf("oldbrand") != -1 
                    ) {
                        geo_tag_link.push($(this).attr("href"));
                        if ($(this).attr("href").toLowerCase().indexOf("pdf") != -1) {
                            geo_tag_link = [$(this).attr("href")];
                        }
                    }
                });
                
                geo_tag_link = JSON.stringify(geo_tag_link);

                $("#fileupload tr p.name a").each(function(){
                    if($(this).attr("href").toLowerCase().indexOf("ces") != -1){
                        ces_link = $(this).attr("href");
                        if($(this).attr("href").toLowerCase().indexOf("pdf") != -1){
                            ces_link = $(this).attr("href");
                        }
                    }
                });

                $("#fileupload tr p.name a").each(function(){
                    if($(this).attr("href").toLowerCase().indexOf("systemownertaxinvoice") != -1){
                        System_Owver_Tax_Invoice_link = $(this).attr("href");
                        if($(this).attr("href").toLowerCase().indexOf("pdf") != -1){
                            System_Owver_Tax_Invoice_link = $(this).attr("href");
                        }
                    }
                });

                // file customer agreement 
                $("#fileupload tr p.name a").each(function(){
                    if($(this).attr("href").toLowerCase().indexOf("customeragreement") != -1){
                        CustomerAgreement = $(this).attr("href");
                        if($(this).attr("href").toLowerCase().indexOf("pdf") != -1){
                            CustomerAgreement = $(this).attr("href");
                        }
                    }
                });
                build_url += '&vba_link='+ encodeURIComponent(vba_link) ;
                build_url += '&geo_tag_link='+ encodeURIComponent(geo_tag_link) ;
                build_url += '&ces_link='+ encodeURIComponent(ces_link) ;
                build_url += '&System_Owver_Tax_Invoice_link='+ encodeURIComponent(System_Owver_Tax_Invoice_link) ;
                build_url += '&CustomerAgreement_link='+ encodeURIComponent(CustomerAgreement) ;
                var recycling_receipt_link="";
                // $("#fileupload tr p.name a").each(function(){
                //     if($(this).attr("href").toLowerCase().indexOf("recyclingreceipt") != -1){
                //         recycling_receipt_link = $(this).attr("href");
                //         if($(this).attr("href").toLowerCase().indexOf("pdf") != -1){
                //             recycling_receipt_link = $(this).attr("href");
                //         }
                //     }
                // });
                //thien fix
                recycling_receipt_link = 'Decommissioning.txt';
                build_url += '&recycling_receipt_link='+ encodeURIComponent(recycling_receipt_link) ;
                //thienpb fix
                var product_product_total_price = '';
                $("#product_group0").find('input').each(function(){
                    if(($(this).val().indexOf("VEEC") >= 0) && ($(this).attr('name').indexOf("product_name") >= 0)){
                        var matches = $(this).attr('id').match(/\d+$/);
                        if(matches){
                            product_product_total_price = $('#product_product_total_price'+matches[0]).val().replace('-','');
                        }
                    }
                });
                
                build_url += '&geo_product_product_total_price='+ encodeURIComponent(product_product_total_price);
                //end fix

                // veec_model
                var check_veet_code = '';
                if ($("input[name='veet_code']").length > 1 ){
                    var number_of_assignment = 1
                    $("input[name='veet_code']").each(function(){
                        if($(this).val()!=""){
                            //thien fix check dublicate push to geo
                            if(check_veet_code.indexOf($(this).val().trim()) < 0){
                                check_veet_code += $(this).val().trim()+','
                                build_url += '&veec_model='+ encodeURIComponent($(this).val()) +'&number_of_assignment=' + encodeURIComponent(number_of_assignment);

                                $.ajax({
                                    url: build_url,
                                    //data : data,
                                    async: false,
                                    type : 'POST',
                                    //contentType: false,
                                    //processData: false,
                                    success: function (data) {
                                        if(data == '' || typeof data === 'undefined') {
                                            $('#create_assignment span.glyphicon-refresh').addClass('hidden');
                                            return;
                                        }
                                        if(data !== ''){
                                            var json_data = $.parseJSON(data);
                                            $('#create_assignment span.glyphicon-refresh').addClass('hidden');
                                            if(json_data['reference1'] != "" && (typeof json_data['reference1']  !== "undefined")){
                                                $("#stc_aggregator_serial_c").val(json_data['reference1']);
                                                $("#stc_aggregator_serial_c").trigger("change");
                                            }
                                            if(json_data['reference2'] != "" && (typeof json_data['reference2']  !== "undefined")){
                                                $("#stc_aggregator_serial_2_c").val(json_data['reference2']);
                                                $("#stc_aggregator_serial_2_c").trigger("change");
                                            }
                                        }
                                        

                                    },
                                });
                                number_of_assignment ++;
                            }
                        }
                        
                    });
                }
                else{
                    var veec_model = $("input[name='veet_code']").val();
                    var number_of_assignment = 1;
                    build_url += '&veec_model='+ encodeURIComponent(veec_model) +'&number_of_assignment=' + encodeURIComponent(number_of_assignment);;

                    $.ajax({
                        url: build_url ,
                        //data : data,
                        type : 'POST',
                        //contentType: false,
                        //processData: false,

                        success: function (data) {
                            if(data == '' || typeof data === 'undefined') {
                                $('#create_assignment span.glyphicon-refresh').addClass('hidden');
                                return;
                            }
                            var json_data = $.parseJSON(data);
                            $('#create_assignment span.glyphicon-refresh').addClass('hidden');
                            $("#stc_aggregator_serial_c").val(json_data['reference1']);
                            createSTCAggregatorSerial();
                        },
                    });
                }
                return false;
            }
        }
        // create call type Immediate Post Install
        if(module_sugar_grp1 == 'AOS_Invoices') {
            $("#create_assignment").after('<button style="background:#46a049;" type="button"  data-module-quote-id="'+ $("input[name='record']").val()+'" name="Create_Call" value="Create Call" id="Create_Call" class="button primary" onclick="$(document).create_call_Immediate_Post_Install(this);"><i class="glyphicon glyphicon-phone-alt"></i> Create Call</button>');
           
            $.fn.create_call_Immediate_Post_Install = function (ele){
                var parent_id = $(ele).attr('data-module-quote-id');
                var parent_module = 'AOS_Invoices';
                $.ajax({
                    url: "index.php?entryPoint=create_call_back_pe_internal_note&call_type=Immediate_Post_Install&parent_id=" +parent_id +"&parent_module="+parent_module,
                    success:function (data) {
                        if(data != 'error'){
                            window.open('/index.php?module=Calls&action=EditView&record='+data.trim(),'_blank');
                        }else{
                            window.open('/index.php?module=Calls&action=EditView&record=','_blank');
                        }
                    }
                })
            }
        }

        if(module_sugar_grp1 == 'AOS_Invoices') {
            $("#create_assignment").after(
                ' <button style="background:#009acf;" type="button" id="CRUD_Xero_Invoice" class="button CRUD_Xero_Invoice" title="Create And Update Xero Invoice" onClick="SUGAR.CRUD_Xero_Invoice(this);" >Create & Update Xero <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            );


        }
        SUGAR.CRUD_Xero_Invoice= function(elemt){
            
            var html_alert = '';
            if($('#due_date').val() == ''){
                html_alert += '<h4 class="text-danger">Please insert Due Date!</h4>';
            }
            if($('#invoice_date').val() == ''){
                html_alert += '<h4 class="text-danger">Please insert Invoice Date!</h4>';
            }
            
            if( html_alert != ''){
                $('#alert_modal').find('.modal-body').empty();
                $('#alert_modal').find('.modal-body').append(html_alert); 
                $('#alert_modal').modal('show'); 
                return false;
            }
            // save invoice
            SUGAR.ajaxUI.showLoadingPanel(); 
            $("#EditView input[name='action']").val('Save');
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                async:false,
                success: function (data) { 
                    
                    var invoice_id = $("input[name='record']").val();
                  
                    if(invoice_id !='')  {
                            // create and update invoice xero
                        var url_xero_invoice = "/index.php?entryPoint=CRUD_Invoice_Xero&invoice=1&method=create&from_action=button" + '&record='+ encodeURIComponent($('input[name="record"]').val());
                        $.ajax({
                            url:url_xero_invoice,
                            success:function(data){   
                                SUGAR.ajaxUI.hideLoadingPanel();                             
                                    try {
                                        var json = $.parseJSON(data);
                                        console.log(json);
                                        if( $('#xero_invoice_c').val() == ''){
                                            $('#xero_invoice_c').val(json.inv_xero_id);
                                        }

                                        if( $('#xero_veec_rebate_invoice_c').val() == ''){
                                            $('#xero_veec_rebate_invoice_c').val(json.inv_xero_veec);
                                        }

                                        if( $('#xero_stc_rebate_invoice_c').val() == ''){
                                            $('#xero_stc_rebate_invoice_c').val(json.inv_xero_stc);
                                        }

                                        if( $('#xero_shw_rebate_invoice_c').val() == ''){
                                            $('#xero_shw_rebate_invoice_c').val(json.inv_xero_shw);
                                        }

                                        if(json.msg != ''){
                                            $('#alert_modal').find('.modal-body').empty();
                                            $('#alert_modal').find('.modal-body').append(json.msg); 
                                            $('#alert_modal').modal('show'); 
                                            return false;
                                        }else{
                                            $('#alert_modal').find('.modal-body').empty();
                                            $('#alert_modal').find('.modal-body').append('Push and update XERO invoice done!'); 
                                            $('#alert_modal').modal('show'); 
                                            return false;
                                        }

                                    } catch (e) {
                                        return false;
                                    }                   
                            }
                        });
                    }
                    
                }
            });

           
        }



 
        // Client GEO REMINDER EMail BinhNT
        $("#create_assignment").after(
            ' <button type="button" id="send_geo_reminder" class="button sendGeoReminder" title="Send GEO Reminder" onClick="SUGAR.sendGeoReminder(this);" > Send Geo Reminder <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )

        SUGAR.sendGeoReminder = function (elem) {
            //thienpb code -- disable button for daikin
            if($("#quote_type_c").val() == "quote_type_daikin" || $("#quote_type_c").val() == "quote_type_nexura"){
                alert("Function disabled for Daikin.");
                return false;
            }
            $('#send_geo_reminder span.glyphicon-refresh').removeClass('hidden');
            var billing_account_id = $('#billing_account_id').val();
            $.ajax({
                url: "/index.php?entryPoint=sendGeoReminder&billing_account_id="+billing_account_id+"&record_id="+ encodeURIComponent($('input[name="record"]').val())+"&productType="+encodeURIComponent($('#group0name').val()),
                success: function (data) {
                    if(data == '' || typeof data === 'undefined') {
                        $('#send_geo_reminder span.glyphicon-refresh').addClass('hidden');
                        return;
                    }
                    $('#send_geo_reminder span.glyphicon-refresh').addClass('hidden');
                    window.open(data,"_blank");
                }
            });
            return false;
        }
        // End 

        function addEmailWarning(){
            //console.log($("#fileupload tr").length);
            if($("#fileupload tr").length == 0){
                $('#plumbing_notes_c').parent().find(".plumbing_notes").remove();
                $('#electrical_notes_c').parent().find(".electrical_notes").remove();
                $('#customer_notes_c').parent().find(".custommer_notes").remove();
                $('#plumbing_notes_c').parent().append('<p class="plumbing_notes" style="color:#f08377">Warning: No photo attachments</p>');
                $('#electrical_notes_c').parent().append('<p class="electrical_notes" style="color:#f08377">Warning: No photo attachments</p>');
                $('#customer_notes_c').parent().append('<p class="custommer_notes" style="color:#f08377">Warning: No photo attachments</p>');
            }
            else{
                $('#plumbing_notes_c').parent().find(".plumbing_notes").remove();
                $('#electrical_notes_c').parent().find(".electrical_notes").remove();
                $('#customer_notes_c').parent().find(".custommer_notes").remove();
            }
        }


        $('#fileupload').bind('fileuploaddestroyed', function(e, data) {

            // If you edit the default template, you can acquire some other
            // information about the uploaded file (for example the file size)
            addEmailWarning();

        });
        $('#fileupload').bind('fileuploaddone', function (e, data) {
            addEmailWarning();
        });
        if(module_sugar_grp1 == 'AOS_Invoices') {
            YAHOO.util.Event.addListener("billing_account", "change", copyToContact);
        }
        function copyToContact(){
            // $("#billing_contact").val($("#billing_account").val());
            // $("#billing_contact_id").val($("#billing_account_id").val());
            //dung code 
            $("#delivery_contact_address_c").val($("#shipping_address_street").val());
            $("#delivery_contact_suburb_c").val($("#shipping_address_city").val());
            $("#delivery_contact_postcode_c").val($("#shipping_address_postalcode").val());
            $('#delivery_contact_name_c').val($('#billing_contact').val());
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Accounts&action=delivery_contact_phone_number&record_id=" + $("#billing_contact_id").val(),
            }).done(function (data) {
                $('#delivery_contact_phone_numbe_c').val(data);  
            });
            //end dung code 
        }
        // For install address.
        function copy_BillingInstall(){
            $("#install_address_c").val($("#billing_address_street").val());
            //$("#install_address_c").prop('disabled', true);
            $("#install_address_city_c").val($("#billing_address_city").val());
            //$("#install_address_city_c").prop('disabled', true);
            $("#install_address_state_c").val($("#billing_address_state").val());
            //$("#install_address_state_c").prop('disabled', true);
            $("#install_address_postalcode_c").val($("#billing_address_postalcode").val());
            //$("#install_address_postalcode_c").prop('disabled', true);
            $("#install_address_country_c").val($("#billing_address_country").val());
            //$("#install_address_country_c").prop('disabled', true);
        }
        function syncFieldsBilling(checked){
            if(checked){
                $("#install_address_c").css({ 'background-color': "background-color: rgb(220, 220, 220)" });
                $("#install_address_city_c").css({ 'background-color': "background-color: rgb(220, 220, 220)" });
                $("#install_address_postalcode_c").css({ 'background-color': "background-color: rgb(220, 220, 220)" });
                $("#install_address_state_c").css({ 'background-color': "background-color: rgb(220, 220, 220)" });
                $("#install_address_country_c").css({ 'background-color': "background-color: rgb(220, 220, 220)" });
                
                $("#install_address_c").attr("readonly", true);
                $("#install_address_city_c").attr("readonly", true);
                $("#install_address_postalcode_c").attr("readonly", true);
                $("#install_address_state_c").attr("readonly", true);
                $("#install_address_country_c").attr("readonly", true);

                $("#install_address_c").val($("#billing_address_street").val());
                $("#install_address_city_c").val($("#billing_address_city").val());
                $("#install_address_state_c").val($("#billing_address_state").val());
                $("#install_address_postalcode_c").val($("#billing_address_postalcode").val());        
            }
            else{
                $("#install_address_c").css({ 'background-color': "" });
                $("#install_address_city_c").css({ 'background-color': "" });
                $("#install_address_postalcode_c").css({ 'background-color': "" });
                $("#install_address_state_c").css({ 'background-color': "" });
                $("#install_address_country_c").css({ 'background-color': "" });
                
                $("#install_address_c").attr("readonly", false);
                $("#install_address_city_c").attr("readonly", false);
                $("#install_address_postalcode_c").attr("readonly", false);
                $("#install_address_state_c").attr("readonly", false);
                $("#install_address_country_c").attr("readonly", false);
            }
        }
       

        YAHOO.util.Event.addListener("billing_contact", "change", function(){
            $("#site_contact_c").val($("#billing_contact").val());
            $("#contact_id3_c").val($("#billing_contact_id").val());    
        });
        // $("#due_date").change(function(evt){
        //     $("#installation_date_c_date").val($(this).val());
        // });
        // Tritruong Compact Code - Create Function CreateInvoice, CreateMeeting
        var old_due_date = $("#date_input").val(),
            billingContact = $('#billing_contact').val(),
            titleInvoice = $('#name').val();
        YAHOO.util.Event.addListener("due_date", "change", function(){
            if (billingContact == 'Solargain Accounts') {
                SUGAR.ajaxUI.showLoadingPanel();
                $("#ajaxloading_mask").css("position",'fixed');
                $("#installation_date_c_date").val($("#due_date").val());
                $("#installation_date_c_hours").val('08');
                $("#installation_date_c_minutes").val('00');
                //Dung code
                $("#plumber_install_date_c").val($('#due_date').val());
                $('#electrician_install_date_c').val($('#due_date').val());
                $('#vba_pic_date_c').val($('#due_date').val());
                $('#ces_cert_date_c').val($('#due_date').val());
                //End Dung code
                $("#installation_date_c").val($("#due_date").val()+ " " + $("#installation_date_c_hours").val() +":"+ $("#installation_date_c_minutes").val());
                //logic update or create new meeting 
                //case1 : new invoice
                //step1: submit get id invoice
                var invoice_id = $('input[name="record"]').val(),
                    installationDate = $("#installation_date_c").val();
                createInvoice(invoice_id, installationDate);
            }
        });
        //dung code -- logic create or update meeting whenc change install date
        YAHOO.util.Event.addListener(["installation_date_c_date","installation_date_c_hours","installation_date_c_minutes"], "change", function(){
            // debugger
            //case1 : new invoice
            //step1: submit get id invoice
            SUGAR.ajaxUI.showLoadingPanel();
            $("#ajaxloading_mask").css("position",'fixed');
            var invoice_id = $('input[name="record"]').val(),
                installationDate = $("#installation_date_c").val();
            createInvoice(invoice_id, installationDate);
            update_field_install_date_plum_and_elec();

        });

        YAHOO.util.Event.addListener(["account_id1_c","account_id_c"], "change", update_field_install_date_plum_and_elec);
        function update_field_install_date_plum_and_elec(){
            //plumber
            if($('#account_id1_c').val() != '') {
                $('#plumber_install_date_c').val($("#installation_date_c_date").val());
            }
            //electric
            if($('#account_id_c').val() != '') {
                $('#electrician_install_date_c').val($("#installation_date_c_date").val());
            }
        }
    
        function createInvoice(invoice_id, installationDate) {
            update_field_install_date_plum_and_elec();
            if( invoice_id == ''){
                var ok = confirm('Invoices is not saved! Do you want Save and Reload Page?');
                if(ok ==  true){
                    $("#EditView input[name='action']").val('Save');
                    $.ajax({
                        type: $("#EditView").attr('method'),
                        url: $("#EditView").attr('action'),
                        data: $("#EditView").serialize(),
                        async:false,
                        success: function (data) { 
                            var invoice_id_patt = /"record" value="(.*)"/g;
                            invoice_id = invoice_id_patt.exec(data);
                            if(invoice_id !== null && typeof invoice_id === 'object'){
                                if(invoice_id[1] !='')  {
                                    createMeeting(invoice_id[1], installationDate);
                                }
                            }
                        }
                    });
                }else{
                    return;
                }
            } else {
                createMeeting(invoice_id, installationDate);
            }
        }
        function createMeeting(recordId, installationDate) {
            if ($("#meeting_c").val() == ''){
                if(installationDate !=''){
                    $.ajax({
                        url: "?entryPoint=mettingWithInstaller&record_id=" + recordId +"&installation_date="+installationDate,
                        context: document.body,
                        async: true
                    }).done(function (data) {
                        //step2: load new invoice 
                        $("#meeting_c").val(data);
                        if ($("#meeting_c").val() != "") {
                            
                            var href = "<a target='_blank' href='/index.php?module=Meetings&action=DetailView&record="+data+"'>Open Meeting</a>";
                            $('#meeting_c').parent().siblings('.label').append(href);

                        }
                        SUGAR.ajaxUI.hideLoadingPanel();
                    });
                }
            } else {
                //case2 : old invoice - it exist before
                let meeting_installers = '';
                if ($('#meeting_plumber').val() != '') {
                    meeting_installers += '&meeting_plumber='+$('#meeting_plumber').val();
                } 
                if ($('#meeting_electrician').val() != '') {
                    meeting_installers += '&meeting_electrician='+$('#meeting_electrician').val();
                }
                $.ajax({
                    url: '?entryPoint=CustomUpdateMeetingFromInvoice&meeting_id='+ $("#meeting_c").val() + '&installation_date_c='+$("#installation_date_c").val()+meeting_installers,
                    success: function(data){
                        console.log(data);
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }
                })
            }
        }
        // End Compact Code
        function syncFieldsShipping(checked){
            if(checked){
                $("#install_address_c").val($("#shipping_address_street").val());
                //$("#install_address_c").prop('disabled', true);
                $("#install_address_city_c").val($("#shipping_address_city").val());
                //$("#install_address_city_c").prop('disabled', true);
                $("#install_address_state_c").val($("#shipping_address_state").val());
                //$("#install_address_state_c").prop('disabled', true);
                $("#install_address_postalcode_c").val($("#shipping_address_postalcode").val());
                //$("#install_address_postalcode_c").prop('disabled', true);
                $("#install_address_country_c").val($("#shipping_address_country").val());
                //$("#install_address_country_c").prop('disabled', true);
            }
            else{
                /*$("#install_address_country_c").prop('disabled', false);
                $("#install_address_postalcode_c").prop('disabled', false);
                $("#install_address_state_c").prop('disabled', false);
                $("#install_address_city_c").prop('disabled', false);
                $("#install_address_c").prop('disabled', false);*/
            }
        }
        if(module_sugar_grp1 == 'AOS_Invoices') {
            $("#group_address_install").append('<div class="col-xs-6 col-sm-4 edit-view-field"> <label>Copy address from billing address:</label><input id="install_address_billing" name="install_address_billing_checkbox"  type="checkbox"><br> <label>Copy address from shipping address:</label><input id="install_address_shipping" name="install_address_shipping_checkbox" type="checkbox"></div>');
        }  else {  
            // //Quote
            // $("#install_address_c").parent().parent().parent().append('<div class="col-xs-12 edit-view-field"> <label>Copy address from billing address:</label><input id="install_address_billing" name="install_address_billing_checkbox"  type="checkbox"></br><label>Copy address from shipping address:</label><input id="install_address_shipping" name="install_address_shipping_checkbox" type="checkbox"></div>');
        }    
        if($("#name").val() == "" || $("input[name='record']").val()==""){
            copy_BillingInstall();
            copy_BillingDaikin();
            $("#shipping_checkbox").prop( "checked", true );
            SUGAR.util.doWhen("typeof(SUGAR.AddressField) != 'undefined'", function () {
                var shipping_address = new SUGAR.AddressField("shipping_checkbox", 'billing', 'shipping');
                shipping_address.syncFields();
            });
            $("#install_address_billing").trigger("click");
            syncFieldsBilling(false);
        }
        $("body").on("click", "#install_address_billing", function(){
            syncFieldsBilling($(this).is(":checked"));
        });

        $("body").on("click", "#install_address_shipping", function(){
            syncFieldsShipping($(this).is(":checked"));
        });

        // Copy address
        function copy_BillingDaikin(){
            $("#delivery_contact_address_c").val($("#billing_address_street").val());
            //$("#install_address_c").prop('disabled', true);
            $("#delivery_contact_suburb_c").val($("#billing_address_city").val());
            //$("#install_address_city_c").prop('disabled', true);
            $("#install_address_state_c").val($("#billing_address_state").val());
            //$("#install_address_state_c").prop('disabled', true);
            $("#delivery_contact_postcode_c").val($("#billing_address_postalcode").val());
            //$("#install_address_postalcode_c").prop('disabled', true);
            $("#install_address_country_c").val($("#billing_address_country").val());
            //$("#install_address_country_c").prop('disabled', true);
        }
        function syncDaikinFieldsBilling(checked){
            if(checked){
                $("#delivery_contact_address_c").val($("#billing_address_street").val());
                //$("#install_address_c").prop('disabled', true);
                $("#delivery_contact_suburb_c").val($("#billing_address_city").val());
                //$("#install_address_city_c").prop('disabled', true);
                //$("#install_address_state_c").val($("#billing_address_state").val());
                //$("#install_address_state_c").prop('disabled', true);
                $("#delivery_contact_postcode_c").val($("#billing_address_postalcode").val());
                //$("#install_address_postalcode_c").prop('disabled', true);
                //$("#install_address_country_c").val($("#billing_address_country").val());
                //$("#install_address_country_c").prop('disabled', true);
            }
            else{
                /*$("#install_address_country_c").prop('disabled', false);
                $("#install_address_postalcode_c").prop('disabled', false);
                $("#install_address_state_c").prop('disabled', false);
                $("#install_address_city_c").prop('disabled', false);
                $("#install_address_c").prop('disabled', false);*/
            }
        }
        function syncDaikinFieldsShipping(checked){
            if(checked){
                $("#delivery_contact_address_c").val($("#shipping_address_street").val());
                //$("#install_address_c").prop('disabled', true);
                $("#delivery_contact_suburb_c").val($("#shipping_address_city").val());
                //$("#install_address_city_c").prop('disabled', true);
                //$("#install_address_state_c").val($("#shipping_address_state").val());
                //$("#install_address_state_c").prop('disabled', true);
                $("#delivery_contact_postcode_c").val($("#shipping_address_postalcode").val());
                //$("#install_address_postalcode_c").prop('disabled', true);
                //$("#install_address_country_c").val($("#shipping_address_country").val());
                //$("#install_address_country_c").prop('disabled', true);
            }
            else{
                /*$("#install_address_country_c").prop('disabled', false);
                $("#install_address_postalcode_c").prop('disabled', false);
                $("#install_address_state_c").prop('disabled', false);
                $("#install_address_city_c").prop('disabled', false);
                $("#install_address_c").prop('disabled', false);*/
            }
        }

        function syncDaikinFieldsInstall(checked){
            if(checked){
                $("#delivery_contact_address_c").val($("#install_address_c").val());
                //$("#install_address_c").prop('disabled', true);
                $("#delivery_contact_suburb_c").val($("#install_address_city_c").val());
                //$("#install_address_city_c").prop('disabled', true);
                //$("#install_address_state_c").val($("#shipping_address_state").val());
                //$("#install_address_state_c").prop('disabled', true);
                $("#delivery_contact_postcode_c").val($("#install_address_postalcode_c").val());
                //$("#install_address_postalcode_c").prop('disabled', true);
                //$("#install_address_country_c").val($("#shipping_address_country").val());
                //$("#install_address_country_c").prop('disabled', true);
            }
            else{
                /*$("#install_address_country_c").prop('disabled', false);
                $("#install_address_postalcode_c").prop('disabled', false);
                $("#install_address_state_c").prop('disabled', false);
                $("#install_address_city_c").prop('disabled', false);
                $("#install_address_c").prop('disabled', false);*/
            }
        }

        $("#delivery_contact_phone_numbe_c").parent().parent().removeClass();
        $("#delivery_contact_phone_numbe_c").parent().parent().children().first().removeClass();
        $("#delivery_contact_phone_numbe_c").parent().parent().children().first().addClass("col-xs-12 col-sm-4 label");
        $("#delivery_contact_phone_numbe_c").parent().parent().addClass("col-xs-12 col-sm-6 edit-view-row-item");

        $("#delivery_contact_phone_numbe_c").parent().parent().after('<div class="col-xs-6 col-sm-6 edit-view-field hidden"> <label>Copy address from billing address:</label><input id="daikin_address_billing" name="daikin_address_billing_checkbox"  type="checkbox"><br> \
            <label>Copy address from shipping address:</label><input id="daikin_address_shipping" name="daikin_address_shipping_checkbox" type="checkbox"><br> \
            <label>Copy address from install address:</label><input id="daikin_address_install_checkbox" name="daikin_address_shipping_checkbox" type="checkbox"></div>'
            );
         //Dung code   
        if ($('#delivery_contact_address_c').val() !== '' || $('#delivery_contact_suburb_c').val() !== '' || $('#delivery_contact_postcode_c') !== '') {
            $("#daikin_address_shipping").prop('checked',true);
        }
        //End Dung code  
        
        $("#daikin_po_c").parent().siblings('.label').append('<br><button class="button primary" id="createDaikinPO"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create Daikin PO</button>');
        $("#daikin_po_c").parent().siblings('.label').append('<br><button class="button primary" id="sendMailToDaikinSupplier"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Send Mail & PO</button>');
        
        $("#createDaikinPO").click(function(){
            if($("#daikin_po_c").val() != ""){
                alert("Please click the link to edit PO.");
                return false;
            }
            $('#createDaikinPO span.glyphicon-refresh').removeClass('hidden');
            var record_id = $("input[name='record']").val();
            var daikin_supplier = $("#account_id2_c").val();
            //VUT-S-Create subject PO
            var daikin_product = $("input[name='daikin_product_infomation_c']").val(); //no use
            var delivery_contact_suburb = $("#delivery_contact_suburb_c").val();
            var delivery_contact_state = $("#delivery_contact_state_c").val();
            var delivery_date = $("#delivery_date_time_c").val();
            //VUT-E-Create subject PO
            // submit form before create PO - daikin by buttom
            SUGAR.ajaxUI.showLoadingPanel();
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                async:false,
                success: function () {
                    $.ajax({
                        url: "?entryPoint=createPurchaseOrder&type=daikin&record_id=" + record_id +"&daikin_supplier="+daikin_supplier+"&daikin_product="+daikin_product+"&delivery_contact_suburb="+delivery_contact_suburb+"&delivery_contact_state="+delivery_contact_state+"&delivery_date="+delivery_date,
                        context: document.body,
                        async: true
                    }).done(function (data) {
                        $('#createDaikinPO span.glyphicon-refresh').addClass('hidden');
                        data = data.trim(); //VUT
                        console.log(data);
                        $("#daikin_po_c").val(data);
                        if (data == "") return false;
                        //?module=PO_purchase_order&offset=1&stamp=1517294972082855000&return_module=PO_purchase_order&action=EditView&record=3b056120-a5d4-ae50-8e32-5a7015aedb4a
                        var href = "<div class='open-purchase-oder'><a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + data + "'>" + "Open Purchase Order" + "</a></div>";
                        // href += "<div class='open-upload-install-photos'>Upload Install Photos: <a target='_blank' href='https://pure-electric.com.au/upload_file_daikin/for-daikin-installer?invoice_id="+$('input[name="record"]').val()+"'>Open Upload For Installer</a></div>";
                        $('#createDaikinPO').parent().append(href);
                        SUGAR.ajaxUI.hideLoadingPanel();
                    });
                }
            });           
            return false;
        });

        if ($("#daikin_po_c").val() != "") {
            //?module=PO_purchase_order&offset=1&stamp=1517294972082855000&return_module=PO_purchase_order&action=EditView&record=3b056120-a5d4-ae50-8e32-5a7015aedb4a
            var href = "<div class='open-purchase-oder'>Open Supply Purchase Order<a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + $("#daikin_po_c").val() + "'>" + "Open Supply Purchase Order" + "</a></div>";
                // href += "<div class='open-upload-install-photos'>Upload Install Photos: <a target='_blank' href='https://pure-electric.com.au/upload_file_daikin/for-daikin-installer?invoice_id="+$('input[name="record"]').val()+"'>Open Upload For Installer</a></div>";

            $('#createDaikinPO').parent().append(href);
        }

        //thien code here    
        $("#daikin_po_1_c").parent().parent().after('<button data-input="1" class="button primary createSupplyPO" id="createSupplyPO1"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create</button>');
        $("#daikin_po_2_c").parent().parent().after('<button data-input="2" class="button primary createSupplyPO" id="createSupplyPO2"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create</button>');
        $("#daikin_po_3_c").parent().parent().after('<button data-input="3" class="button primary createSupplyPO" id="createSupplyPO3"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create</button>');
        
        if(module_sugar_grp1 == 'AOS_Invoices') {
            if($('#daikin_po_3_c').val() != ""){
                Load_idSupplyPO($("input[name='record']").val(),'read');
            }
        }
        // Tuan code add more supply PO
        $("#daikin_po_1_c").closest('.edit-view-row').after('<button type="button" class="button primary" id="addSupplyPo"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Add Supply PO</button>');
        $('#addSupplyPo').on('click', function(){
            var lenght_input = $('#daikin_po_1_c').closest('.edit-view-row').find('input').length + 1;
            var input_supply = '<div class="col-xs-12 col-sm-6 edit-view-row-item">\
                                    <div class="col-xs-12 col-sm-4 label" data-label="LBL_DAIKIN_PO_"'+lenght_input +'>Supply PO '+lenght_input+':</div>\
                                    <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="daikin_po_'+lenght_input+'_c">\
                                        <input type="text" name="daikin_po_'+lenght_input+'_c" id="daikin_po_'+lenght_input+'_c" size="30" maxlength="255" value="" title="">\
                                    </div>\
                                </div>\
                                <button data-input="'+lenght_input+'" class="button primary createSupplyPO" id="createSupplyPO'+lenght_input+'"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create</button><div class="clear"></div><div class="clear"></div>';

            $('#daikin_po_1_c').closest('.edit-view-row').append(input_supply);
        })
        $("body").on("click", ".createSupplyPO", function(){    
            createSupplyPO($(this).attr('data-input'));
            return false;
        });


        function SaveId_SupplyPO(line_number,record_id,type,data){
            $.ajax({
                url: 'index.php?entryPoint=SaveIdSupplyPO',
                type: 'POST',
                data: 
                {
                    record_id: record_id,
                    id_po: data,
                    action: type,
                    line_number: line_number,
                },
                success: function(result) {              
                    console.log('Success!');
                }
            }); 
        }
        function Load_idSupplyPO(record_id,type){
            $.ajax({
                url: 'index.php?entryPoint=SaveIdSupplyPO',
                type: 'POST',
                data: 
                {
                    record_id: record_id,
                    action: type,
                },
                success: function(result) {
                    var dataJson =  JSON.parse(result);
                    if( dataJson[record_id] != "" && typeof(dataJson[record_id]) != 'undefined' ){
                        dataJson[record_id].forEach(element => {
                            var input_supply = '<div class="col-xs-12 col-sm-6 edit-view-row-item">\
                                                    <div class="col-xs-12 col-sm-4 label" data-label="LBL_DAIKIN_PO_"'+element.line_number +'>Supply PO '+element.line_number+':</div>\
                                                    <div class="col-xs-12 col-sm-8 edit-view-field " type="varchar" field="daikin_po_'+element.line_number+'_c">\
                                                        <input type="text" name="daikin_po_'+element.line_number+'_c" id="daikin_po_'+element.line_number+'_c" size="30" maxlength="255" value="'+element.id_supply_po+'" title="">\
                                                    </div>\
                                                </div>\
                                                <button data-input="'+element.line_number+'" class="button primary createSupplyPO" id="createSupplyPO'+element.line_number+'"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create</button><div class="clear"></div><div class="clear"></div>';
            
                            $('#daikin_po_1_c').closest('.edit-view-row').append(input_supply);
                            // $("#daikin_po_"+id+"_c").val(data);
                            var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + element.id_supply_po + "'>" + "Open Purchase Order" + "</a></div>";
                            $("#daikin_po_"+element.line_number+"_c").nextAll('.open-purchase-oder').remove();
                            $("#daikin_po_"+element.line_number+"_c").parent().append(href);
                        });  
                    }      
                }
            }); 
        }

        //    $("body").on("click", "#createSupplyPO2", function(){
        //        createSupplyPO("2");
        //        return false;
        //    });
        //    $("body").on("click", "#createSupplyPO3", function(){
        //        createSupplyPO("3");
        //        return false;
        //    });
           // because reuse field from daikin supply PO => Sanden SupplyPO
        function createSupplyPO(id){
            $('#createSupplyPO'+id+' span.glyphicon-refresh').removeClass('hidden');
            //VUT - create PO supply sunpower
            var type = '';
            if ($('#quote_type_c').val() == 'quote_type_solar') {
                 type = 'solar_supply&button='+id;
    
            } else {
                 if($("#plumber_po_c").val == ""){
                     alert("please click the link to edit PO");
                     return false;
                 }
                 type = 'sanden_supply';
             }
            var record_id = $("input[name='record']").val();
            $.ajax({
                url: "?entryPoint=createPurchaseOrder&type="+type+"&record_id=" + record_id ,
                context: document.body,
                async: true
            }).done(function (data) {
                if( parseInt(id) > 3 ){
                    SaveId_SupplyPO(id,record_id,'create',data);
                    data = data.trim();
                    $('#createSupplyPO'+id+' span.glyphicon-refresh').addClass('hidden');
                    $("#daikin_po_"+id+"_c").val(data);
                    if (data == "") return false;
                    var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + data + "'>" + "Open Purchase Order" + "</a></div>";
                    $("#daikin_po_"+id+"_c").nextAll('.open-purchase-oder').remove();
                    $("#daikin_po_"+id+"_c").parent().append(href);
                }else{
                    data = data.trim();
                    $('#createSupplyPO'+id+' span.glyphicon-refresh').addClass('hidden');
                    $("#daikin_po_"+id+"_c").val(data);
                    if (data == "") return false;
                    var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + data + "'>" + "Open Purchase Order" + "</a></div>";
                    $("#daikin_po_"+id+"_c").nextAll('.open-purchase-oder').remove();
                    $("#daikin_po_"+id+"_c").parent().append(href);
                }
            });
        }
        if ($("#daikin_po_1_c").val() != "") {
            var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + $("#daikin_po_1_c").val() + "'>" + "Open Purchase Order" + "</a></div>";
            $('#daikin_po_1_c').parent().append(href);
        } 
        if ($("#daikin_po_2_c").val() != "") {
            var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + $("#daikin_po_2_c").val() + "'>" + "Open Purchase Order" + "</a></div>";
            $('#daikin_po_2_c').parent().append(href);
        }
        if ($("#daikin_po_3_c").val() != "") {
            var href = "<div class='open-purchase-oder'>Open Purchase Order <a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + $("#daikin_po_3_c").val() + "'>" + "Open Purchase Order" + "</a></div>";
            $('#daikin_po_3_c').parent().append(href);
        }
           //end

        $('#sendMailToDaikinSupplier').on('click', function (event){
            event.preventDefault();
            //event.append();
            $('#sendMailToDaikinSupplier span.glyphicon-refresh').removeClass('hidden');
            var daikinLineItems = $('input[name="daikin_product_infomation_c"]').val();
            var delivery_contact_name = $('#delivery_contact_name_c').val();
            var delivery_contact_address = $("#shipping_address_street").val();
            var delivery_contact_suburb = $("#shipping_address_city").val();
            var delivery_contact_postcode  = $("#shipping_address_postalcode").val();
            var delivery_contact_phone_numbe = $('#delivery_contact_phone_numbe_c').val();
            var delivery_notes = $('#delivery_notes_c').val();
            var invoice_number = $('div[field="number"]').text();
            var supplier = $("#account_id2_c").val();
            var po_record = $("#daikin_po_c").val();
            var invoice_title = $("#name").val();
            var daikin_supplier_c = $("#account_id2_c").val();
            var build_url=  "?entryPoint=customCreateEmailPopupContent&mail_format=daikin_info&daikinLineItems=" + daikinLineItems ;
            build_url += '&delivery_contact_name='+ delivery_contact_name ;
            build_url += '&delivery_contact_address='+ delivery_contact_address ;
    
            build_url += '&delivery_contact_suburb='+ delivery_contact_suburb ;
            build_url += '&delivery_contact_postcode='+ delivery_contact_postcode ;
    
            build_url += '&delivery_contact_phone_numbe='+ delivery_contact_phone_numbe ;
            build_url += '&delivery_notes='+ delivery_notes ;
            build_url += '&delivery_contact_postcode='+ delivery_contact_postcode ;
            build_url += '&invoice_number=' + encodeURIComponent(invoice_number);
            build_url += '&supplier='+ encodeURIComponent(supplier);
            build_url += '&po_record='+ encodeURIComponent(po_record);
            build_url += '&invoice_title='+ encodeURIComponent(invoice_title);
            build_url += '&daikin_supplier_c='+ encodeURIComponent(daikin_supplier_c);
            
            build_url += '&add_po=1';
            $.ajax({
                url: build_url,
                context: document.body
            }).done(function(data) {
                if(data == '' || typeof data === 'undefined') {
                    $('#sendMailToDaikinSupplier span.glyphicon-refresh').addClass('hidden');
                    return;
                }
                $('#sendMailToDaikinSupplier span.glyphicon-refresh').addClass('hidden');
                console.log(data);
                window.open(data,'_blank');
            });
    
            return false;
        });

        $("body").on("click", "#daikin_address_billing", function(){
            syncDaikinFieldsBilling($(this).is(":checked"));
        });

        $("body").on("click", "#daikin_address_shipping", function(){
            syncDaikinFieldsShipping($(this).is(":checked"));
        });

        $("body").on("click", "#daikin_address_install_checkbox", function(){
            syncDaikinFieldsInstall($(this).is(":checked"));
        });
        
        YAHOO.util.Event.addListener(["product_part_number0","product_part_number1"], "change", function(){
            get_sanden_model();
        });

        $('#line_items_span').on('change', 'input', function (e) {
            $('#line_items_span').find('input').each( function(){
                if($(this).val().indexOf("Sanden") != -1 || $(this).val().indexOf("Sanden VIC Elec")){
                    get_sanden_model();
                }
            });
        })
        $('body').on('change', 'input.group_name', function () {
            console.log($(this).val());
            if( $(this).val().toLowerCase().indexOf("daikin") == -1) return;
            $("#installation_type_c").val("spaceHeater");
        });

        function copyToSandenModel(){
            //  console.log('s');
            $('#line_items_span').find('input').each( function(){
                
                if($(this).val().indexOf("Sanden") != -1 || $(this).val().indexOf("Sanden VIC Elec")){
                    $('#sanden_model_c').val($('#product_part_number0').val());
                }
            }); 
        }

        $('#addGroup').click( autoPopulateLineItems);
        function autoPopulateLineItems(){
            $("#line_items_span tfoot tr:nth-child(2) span:last").before('<button type="button" tabindex="116" style="width:auto; height:32px; padding: 0 10px;" class="button" onclick="genRebateNumberFunc(this) " id="genRebateNumber">Gen Rebate Number<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
            $("#service_group0addServiceLine").after('<label style="margin-left:12px">Show Original Price: &nbsp;</label><input type="checkbox" tabindex="116"  class="button"  id="show_original_price">');
            $("#line_items_span tfoot tr:nth-child(1) span:last").before('<button type="button" tabindex="116" style="margin-left:12px;width:auto; height:32px; padding: 0 10px;" class="button" onclick="original_price(this) " id="fake_original_price">Fake Price<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
            var availableTags = [
                "Sanden VIC Elec", 
                "Sanden",
                "Daikin VIC",
            ];

            var populateProducts = {
                "Sanden VIC Elec": {
                    "160L": [
                        "GAUS-160EQTAQ",
                        "Sanden_Plb_Install_Std",
                        "Sanden_Elec_Install_Std",
                        "STC Rebate Certificate",
                        "VEEC Rebate Certificate",
                    ],
                    "250L": [
                        "GAUS-250EQTAQ",
                        "Sanden_Plb_Install_Std",
                        "Sanden_Elec_Install_Std",
                        "STC Rebate Certificate",
                        "VEEC Rebate Certificate",
                    ],
                    "315L": [
                        "GAUS-315EQTAQ",
                        "Sanden_Plb_Install_Std",
                        "Sanden_Elec_Install_Std",
                        "STC Rebate Certificate",
                        "VEEC Rebate Certificate",
                    ]
                },
                "Sanden": {
                    "160L": [
                        "GAUS-160EQTAQ",
                        "Sanden_Plb_Install_Std",
                        "Sanden_Elec_Install_Std",
                        "STC Rebate Certificate",
                    ],
                    "250L": [
                        "GAUS-250EQTAQ",
                        "Sanden_Plb_Install_Std",
                        "Sanden_Elec_Install_Std",
                        "STC Rebate Certificate",
                    ],
                    "315L": [
                        "GAUS-315EQTAQ",
                        "Sanden_Plb_Install_Std",
                        "Sanden_Elec_Install_Std",
                        "STC Rebate Certificate",
                    ]
                },

                "Daikin VIC": {
                    "US7 2.5": [
                        "FTXZ25N",
                        "BRP072A42",
                        "DAIKIN_MEL_METRO_DELIVERY",
                        "JOLLYAIR_STANDARD_INSTALL",
                        "VEEC Rebate Certificate",
                    ],
                    "US7 3.5": [
                        "FTXZ35N",
                        "BRP072A42",
                        "DAIKIN_MEL_METRO_DELIVERY",
                        "JOLLYAIR_STANDARD_INSTALL",
                        "VEEC Rebate Certificate",
                    ],
                    "US7 5": [
                        "FTXZ50N",
                        "BRP072A42",
                        "DAIKIN_MEL_METRO_DELIVERY",
                        "JOLLYAIR_STANDARD_INSTALL",
                        "VEEC Rebate Certificate",
                    ]
                }
            }
            // Sanden VIC Gas ZONE 4 Supply and Install
            $( "input.group_name" ).autocomplete({
                source: availableTags,
                select: function (a, b) {
                    //console.log(b.value);
                    $(this).nextAll('.populate-default-line-items').remove();
                        // 
                    if(b.item.value=="Sanden VIC Elec"){

                        // Populates buttons
                        //$(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='315l-tall'> 315L Tall </button>");
                        $(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='315L'> 315L </button>");
                        $(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='250L'> 250L </button>");
                        $(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='160L'> 160L </button>");

                        
                    }
                    //Sanden NSW Supply and Install
                    if(b.item.value=="Sanden"){

                        // Populates buttons
                        //$(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='315l-tall'> 315L Tall </button>");
                        $(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='315L'> 315L </button>");
                        $(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='250L'> 250L </button>");
                        $(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='160L'> 160L </button>");

                    }

                    if(b.item.value=="Daikin VIC"){

                        //$(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='Cora-2.5kW'> Cora 2.5 </button>");
                        $(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='US7 5'> US7 5.0 </button>");
                        $(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='US7 3.5'> US7 3.5  </button>");
                        $(this).after("&nbsp; &nbsp; <button class='button populate-default-line-items' data='US7 2.5'> US7 2.5 </button>");

                    }
                    $('.populate-default-line-items').on('click', function (e) {
                        var data = populateProducts[b.item.value][$(this).attr('data')];
                        $.post( "?entryPoint=getProductInfos", { part_numbers: data })
                        .done(function( data ) {
                            var products = $.parseJSON(data);
                            for(var k = 0; k < products.length ; k++){
                                lineno = k; 
                                insertProductLine("product_group0", "0");
                                var popupReplyData = {}; //
                                popupReplyData.form_name = "EditView";
                                var name_to_value_array = {};
                                if(products[k] != null){
                                    name_to_value_array["product_currency"+k] = products[k].product_currency;
                                    name_to_value_array["product_item_description"+k] = products[k].product_item_description;
                                    name_to_value_array["product_name"+k] = products[k].product_name;
                                    name_to_value_array["product_part_number"+k] = products[k].product_part_number;
                                    name_to_value_array["product_product_cost_price"+k] = products[k].product_product_cost_price;

                                    name_to_value_array["product_product_id"+k] = products[k].product_product_id;
                                    name_to_value_array["product_product_list_price"+k] = products[k].product_product_list_price;
                                    popupReplyData["name_to_value_array"] = name_to_value_array;
                                    setProductReturn(popupReplyData);
                                    $("#product_part_number"+k).trigger("change");
                                }
                            }
                            copyToSandenModel();
                        });

                        /*var data = {
                            "product_currency": -99,
                            "product_item_description": "",
                            "product_name":"",
                            "product_part_number":"",
                            "product_product_cost_price":"",
                            "product_product_id":"",
                            "product_product_list_price":"",
                        }
                        lineno = 0;
                        var i = 0;
                        insertProductLine("product_group"+i, i);
                        var popupReplyData = {}; //
                        popupReplyData.form_name = "EditView";
                        var name_to_value_array = {};
                        name_to_value_array["product_currency"+i] = "-99";
                        name_to_value_array["product_item_description"+i] = "Highest efficiency heat pump hot water system in Australia with COP 5.0\n315 litre stainless steel tank 1490mm x 680mm, 15 year warranty, made in Australia\nHeat pump 898mm x 754mm x 362mm, 6 year warranty, made in Japan\n15mm AVG Quick Connection Kit and custom fit lagging\nDelivery included when installed by our team";
                        name_to_value_array["product_name"+i] = "Sanden Eco Plus 315L Complete Heat Pump System";
                        name_to_value_array["product_part_number"+i] = "GAUS-315EQTAQ";
                        name_to_value_array["product_product_cost_price"+i] = "4400.000000";

                        name_to_value_array["product_product_id"+i] = "2e3e02ab-596c-aa4d-ec75-59dae3a11c63";
                        name_to_value_array["product_product_list_price"+i] = "4400.000000";
                        popupReplyData["name_to_value_array"] = name_to_value_array;
                        setProductReturn(popupReplyData);
                        
                        copyToSandenModel();*/
                        e.preventDefault();
                    });
                }
            });
        };
        autoPopulateLineItems();
        if( jQuery('#group0name').val() != undefined ){
            if( jQuery('#group0name').val().includes("Daikin") == true ){
                jQuery('#show_original_price').prop('checked',true);
                jQuery('#fake_original_price').trigger('click');
            }
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

        if ($('input[name="installation_pictures_c"]').val() == "") {
            $('input[name="installation_pictures_c"]').val(generateUUID());
        }

        if ($('input[name="pre_install_photos_c"]').val() == "") {
            $('input[name="pre_install_photos_c"]').val(generateUUID());
        }

        // Electrical note
        $('#electrical_notes_c').parent().siblings('.label').append('<br> <button class="button primary" id="sendElectricalEmail"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Send mail & PO</button>');
        $('#sendElectricalEmail').after('<button class="button primary" id="sendElectricalMessage"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Send SMS/MMS </button>');

        $('#plumbing_notes_c').parent().append('<label>Send plumbing test message: &nbsp;</label><input type="checkbox" id="send-plumbing-test" style="color:#f08377"/>');
        $('#electrical_notes_c').parent().append('<label>Send electric test message: &nbsp;</label><input type="checkbox" id="send-electric-test" style="color:#f08377"/>');
        $('#customer_notes_c').parent().append('<label>Send customer test message: &nbsp;</label><input type="checkbox" id="send-customer-test" style="color:#f08377"/>');
        //VUT - create string old_hws for send email Plumber/Electrician POs
        function createOldHWSString() {
            let old_hws_string = '', old_hws_new_date = '';
            let old_hws_fuel = $('#old_tank_fuel_c').find(":selected").text() != '' ? $('#old_tank_fuel_c').find(":selected").text() : '';
            let old_hws_make = $('#old_tank_make_c').val() != '' ? $('#old_tank_make_c').val() : '';
            let old_hws_model = $('#old_tank_model_c').val() != '' ? $('#old_tank_model_c').val() : '';
            let old_hws_serial = $('#old_tank_serial_c').val() != '' ? $('#old_tank_serial_c').val() : '';
            if ($('#old_tank_date_c').val() != '') {
                let old_hws_date = $('#old_tank_date_c').val().trim().split(' ')[0].split('/'); //dd/mm/yyyy
                old_hws_new_date = old_hws_date[1]+'/'+old_hws_date[0]+'/'+old_hws_date[2];
                old_hws_new_date = new Date(old_hws_new_date).toLocaleString('en-AU',{day: '2-digit', month: 'short',year: 'numeric'});
            }
            old_hws_string = old_hws_fuel + ' ' + old_hws_make + ' ' + old_hws_model + ' ' + old_hws_serial + ' ' + old_hws_new_date;
            return old_hws_string;
        }

        function sendElectricalMessage(messagetype,is_testing){
            if(messagetype == "sms/mms"){
                $('#sendElectricalMessage span.glyphicon-refresh').removeClass('hidden');
            } else{
                $('#sendElectricalEmail span.glyphicon-refresh').removeClass('hidden');
            }

            var plumber_contact_name = $('#plumber_contact_c').val();
            var invoice_number = $('div[field="number"]').text();
            var plumber_install_date = $("#plumber_install_date_c").val();
            var electrical_install_date = $("#electrician_install_date_c").val();
            var billing_account = $("#billing_account").val();
            var billing_address = $('#install_address_c').val() + " " + $('#install_address_city_c').val() + " "
                + $('#install_address_state_c').val() + " " + $('#install_address_postalcode_c').val();

            var electrical_notes = $('#electrical_notes_c').val();

            var site_contact_name = $('#site_contact_c').val();
            var site_contact_number = $('div[field="site_contact_c"] p.phone-number').text();

            var alternate_site_contact_name = $('#site_backup_contact_c').val();
            var alternate_site_contact_number = $('div[field="site_backup_contact_c"] p.phone-number').text();

            var invoice_to_email = false;
            // Get contact email
            var record_id = $('#billing_contact_id').val();
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data === 'undefined')return;
                var json = $.parseJSON(data);
                invoice_to_email = json.email;
            });
            var system = $('#sanden_model_c').val();

            var plumbing_contact = $('#plumber_contact_c').val();

            // plumbing
            var plumbing = "";
            var record_id = $('#contact_id4_c').val();
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                plumbing = json.account_name;
            });

            var pe_id = $('#user_id_c').val();
            var pe_email = "";
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Users&action=GetInfoForSendEmail&record_id=" + pe_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                pe_email = json.email;
            });
            // get info of electrical
            var eletrical_email = "";
            var electrical_id = $("#contact_id_c").val();
            var electric_phone_number = "";
            $.ajax({

                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + electrical_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                eletrical_email = json.email;
                electric_phone_number = json.phone_number;
            });

            var electric_contact_number = $('div[field="electrician_contact_c"] p.phone-number').text();

            var electric_name = $('#electrician_contact_c').val();

            var electric_company = "";
            var record_id = $('#contact_id_c').val();
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                electric_company = json.account_name;
            });
            var pe_contact = $('#pe_contact_c').val();
            var pe_contact_number = $('div[field="pe_contact_c"] p.phone-number').text(); //

            var pe_backup_contact_c = $('#pe_backup_contact_c').val();
            var pe_backup_contact_number = $('div[field="pe_backup_contact_c"] p.phone-number').text(); //

            // Check exist photo:
            var photo = "No Photo";
            if ($('#fileupload tr.template-download').length > 0) {
                photo = "Attachments";       
            }
            var file_dir = $('input[name="installation_pictures_c"]').val();
            //var plumbing_note = $('#plumbing_notes_c').val();
            var suburb = $('#install_address_city_c').val()+' '+$('#install_address_state_c').val();
            if(suburb.trim() == ''){
                suburb = $('#shipping_address_city').val()+' '+$('#shipping_address_state').val();           
            }
            var group_name = $('input.group_name').val();
            var plumbing_contact_number = $('#plumber_contact_c').siblings( ".phone-number" ).text();
            var electricial_contact_id = $("#contact_id_c").val();
            var product_c = $('#quote_type_c').val();
            var invoice_id = $('input[name="record"]').val(); 
            var old_hws = createOldHWSString();

            if(messagetype != "sms/mms"){
                var build_url = "?entryPoint=customCreateEmailPopupContent&mail_format=electrical&plumber_contact_name=" + encodeURIComponent(plumber_contact_name) + "&electricial_contact_id="+electricial_contact_id+"&product_type="+product_c;
            } else {
                var build_url = "?entryPoint=customSendEmail&mail_format=electrical&plumber_contact_name=" + encodeURIComponent(plumber_contact_name);
            }
            
            //var build_url = "?entryPoint=customCreateEmailPopupContent&mail_format=electrical&plumber_contact_name=" + encodeURIComponent(plumber_contact_name);
            build_url += '&invoice_id=' + encodeURIComponent(invoice_id);
            build_url += '&electrical_notes=' + encodeURIComponent(electrical_notes);
            build_url += '&invoice_number=' + encodeURIComponent(invoice_number);
            build_url += '&plumber_install_date=' + encodeURIComponent(plumber_install_date);
            build_url += '&electrical_install_date=' + encodeURIComponent(electrical_install_date);
            build_url += '&billing_account=' + encodeURIComponent(billing_account);
            build_url += '&billing_address=' + encodeURIComponent(billing_address);
            build_url += '&site_contact_name=' + encodeURIComponent(site_contact_name);
            build_url += '&site_contact_number=' + encodeURIComponent(site_contact_number);
            build_url += '&alternate_site_contact_name=' + encodeURIComponent(alternate_site_contact_name);
            build_url += '&alternate_site_contact_number=' + encodeURIComponent(alternate_site_contact_number);
            build_url += '&invoice_to_email=' + encodeURIComponent(invoice_to_email);
            build_url += '&system=' + encodeURIComponent(system);
            build_url += '&plumbing_contact=' + encodeURIComponent(plumbing_contact);
            build_url += '&plumbing=' + encodeURIComponent(plumbing);
            build_url += '&electric_contact_number=' + encodeURIComponent(electric_contact_number);
            build_url += '&electric_name=' + encodeURIComponent(electric_name);
            build_url += '&electric_company=' + encodeURIComponent(electric_company);
            build_url += '&pe_contact_c=' + encodeURIComponent(pe_contact);
            build_url += '&pe_contact_number=' + encodeURIComponent(pe_contact_number);
            build_url += '&photo=' + encodeURIComponent(photo);
            build_url += '&pe_backup_contact_c=' + encodeURIComponent(pe_backup_contact_c);
            build_url += '&pe_backup_contact_number=' + encodeURIComponent(pe_backup_contact_number);
            build_url += '&file_dir=' + encodeURIComponent(file_dir);
            //build_url += '&plumbing_note='+ plumbing_note;
            build_url += '&suburb=' + encodeURIComponent(suburb);
            build_url += '&group_name=' + encodeURIComponent(group_name);
            build_url += '&pe_email=' + encodeURIComponent(pe_email);
            build_url += '&eletrical_email=' + encodeURIComponent(eletrical_email);
            build_url += '&plumbing_contact_number=' + encodeURIComponent(plumbing_contact_number);
            build_url += '&messagetype=' + encodeURIComponent(messagetype);
            build_url += '&is_testing=' + encodeURIComponent(is_testing);
            build_url += '&electric_phone_number='+ encodeURIComponent(electric_phone_number);
            build_url += '&po_record='+ encodeURIComponent($("#electrical_po_c").val());
            build_url += '&note_ces_cert='+ encodeURIComponent($("#ces_cert_wording_c").val());
            build_url += '&old_hws=' + encodeURIComponent(old_hws.trim());


            //dung code - show popup sms/mms when click button electrical send sms/mms
            if(messagetype == 'sms/mms') {
                if(electric_phone_number !== ''){
                    $("body").find("#dialog_send_sms_elec").remove();
                    $("body").append(' <div id = "dialog_send_sms_elec" title="Send SMS">'+
                                            '<div id="sms">'+
                                                '<div>'+
                                                    '<div class="label" >Phone Number :'+
                                                    '<span class="input" id="phone_number_customer_elec"></span>'+
                                                '</div>'+
                                                '<div>'+
                                                    '<div class="label" >From Phone Number :</div>'+
                                                    '<select style="width:170px;margin-bottom:2px;" id="from_phone_number_elec" >'+
                                                       '<option label="+61490942067" value="+61490942067">+61490942067</option>'+
                                                       '<option label="+61421616733" value="+61421616733">+61421616733</option>'+
                                                    '</select>'+
                                                '</div>'+
                                                '<div>'+
                                                    '<div class="label">Message :'+
                                                        '<select style="width:500px;margin-bottom:2px;" id="messager_template_elec">'+
                                                            '<option  value=""></option>'+
                                                        '</select>'+
                                                    '</div>'+
                                                    '<div class="input">'+
                                                        '<textarea id="content_messager_elec" style="width:100%;height:200px;">'+
                                                        '</textarea>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>');
                    
                    var phone_number_customer = electric_phone_number;
                    var numberPattern = /\d+/g;
                    phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
                    $('#phone_number_customer_elec').text(phone_number_customer);
                    var link_upload = 'https://pure-electric.com.au/upload_file_sanden/for-electrician?invoice_id='+invoice_id;
                    //content messager
                    electric_name  = electric_name.split(" ");

                    var body = 'Hi '+ electric_name[0] +',' +
                            'Client details below, PO and photos attached.';
                        body += 'Electrical Email: '+ eletrical_email +'.'
                            + 'Electrical Notes: '+ electrical_notes + '';
                        body += 'Client Install ID #: '+invoice_number+''
                         + 'Date of Plumbing install: ' + plumber_install_date +'.' 
                         + 'Date of Electrical install: '+ electrical_install_date +'.'
                         + 'Client: ' + billing_account + '.'
                         + 'Address: '+ billing_address +'.'
                         + 'Site Contact name: '+site_contact_name+'.'
                         + 'Site Contact number: '+site_contact_number+'.';
                         
                         if(alternate_site_contact_name != "") {
                             body +='Alternate contact name: '+alternate_site_contact_name+'.';
                         }
                        
                         if(alternate_site_contact_number != "") {
                            body += 'Alternate contact number: '+alternate_site_contact_number+'.';
                         }
                         body += ' Email: '+invoice_to_email + '.';
                         body += 'Photo: '+photo + ' '+ link_upload+' .';
    
                         body += 'System: '+system+'.'
                            +'Plumbing: '+plumbing+'.'
                            +'Plumbing Contact: '+plumbing_contact+' '
                            +'Plumbing Contact Number: '+ plumbing_contact_number+'.';
    
                        body += 'Electrical: '+electric_company+'.'
                            + 'Electrical Contact: '+electric_name+'.'
                            + 'Electrical Contact Number: '+ electric_contact_number+'.'
                            + 'PureElectric main contact: '+ pe_contact+ ' '+pe_contact_number+' .'
                            + 'PureElectric backup contact: '+pe_backup_contact_c+' '+pe_backup_contact_number +' .';
    
    
    
                    $('#content_messager_elec').val(body);
                    $("#dialog_send_sms_elec").dialog({
                        width: 712,
                        height:478,
                        modal:true,
                        buttons: {
                            Cancel: function(){
                                $(this).dialog('close');
                                $( "#content_messager" ).val("");
                                $('#messager_template_elec').prop('selectedIndex',0);
                                $('#from_phone_number').prop('selectedIndex',0);
                            },
                            Send: function(){
                                click_send_sms_elec();
                                $(this).dialog('close');
                                $( "#content_messager" ).val("");
                                $('#messager_template_elec').prop('selectedIndex',0);
                                $('#from_phone_number_elec').prop('selectedIndex',0);
                            }
                        }
                    });
                }else {
                    alert('Not have number phone !');
                }
                
            }else {
                $.ajax({
                    url: build_url,
                    context: document.body
                }).done(function (data) {
                    if(messagetype == "sms/mms"){
                        $('#sendElectricalMessage span.glyphicon-refresh').addClass('hidden');
                    } else{
                        $('#sendElectricalEmail span.glyphicon-refresh').addClass('hidden');
                        console.log(data);
                        window.open(data,'_blank');
                    }
                });
            }

            return false
        }

        $('#sendElectricalEmail').on('click', function (event) {
            event.preventDefault();
            var testing = 0;
            if($("#send-electric-test").is(':checked'))
            // checked
                testing = 1;
            else
            // unchecked
                testing = 0;
            if($("#ces_cert_wording_c").val() == "") {
                var answer = confirm("No PCOC Cert Wording, Are you sure you want to show the Draft Email?")
                if (answer) {
                    //some code
                }
                else {
                    $("#ces_cert_wording_c").focus();
                    return false;
                }
            }
            var is_proposed_location_ins_photo = check_proposed_ins_localtion();
            if (is_proposed_location_ins_photo) {
                //have >> continue
            } else {
                var question = confirm("No Proposed Install Location photo is attached - are you sure to continue?");
                if (question) {
                    //some code
                }
                else {
                    //some code
                    return false;
                }
            }
    
            sendElectricalMessage("email", testing);
            return false;
        });

        $('#sendElectricalMessage').on('click', function (event) {
            event.preventDefault();
            var testing = 0;
            if($("#send-electric-test").is(':checked'))
            // checked
                testing = 1;
            else
            // unchecked
                testing = 0;
            sendElectricalMessage("sms/mms", testing);
            return false;
        });

        //For Send Email Function

        function sendCustommerMessage(messagetype, is_testing){
            if(messagetype == "sms/mms"){
                $('#sendCustommerrMessage span.glyphicon-refresh').removeClass('hidden');
            } else{
                $('#sendCustommerrEmail span.glyphicon-refresh').removeClass('hidden');
            }

            var plumber_contact_name = $('#plumber_contact_c').val();
            var invoice_number = $('div[field="number"]').text();
            var plumber_install_date = $("#plumber_install_date_c").val();
            var electrical_install_date = $("#electrician_install_date_c").val();
            var billing_account = $("#billing_account").val();
            var billing_address = $('#install_address_c').val() + " " + $('#install_address_city_c').val() + " "
                + $('#install_address_state_c').val() + " " + $('#install_address_postalcode_c').val();

            var site_contact_name = $('#site_contact_c').val();
            var site_contact_number = $('div[field="site_contact_c"] p.phone-number').text();

            var alternate_site_contact_name = $('#site_backup_contact_c').val();
            var alternate_site_contact_number = $('div[field="site_backup_contact_c"] p.phone-number').text();

            var invoice_to_email = false;
            // Get contact email
            var record_id = $('#billing_contact_id').val();
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                invoice_to_email = json.email;
            });
            var system = $('#sanden_model_c').val();

            var plumbing_contact = $('#plumber_contact_c').val();

            // plumbing
            var plumbing = "";
            var record_id = $('#contact_id4_c').val();
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                plumbing = json.account_name;
            });

            var electric_contact_number = $('div[field="electrician_contact_c"] p.phone-number').text();

            var electric_name = $('#electrician_contact_c').val();

            var electric_company = "";
            var record_id = $('#contact_id_c').val();
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                electric_company = json.account_name;
            });

            var pe_id = $('#user_id_c').val();
            var pe_email = "";
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Users&action=GetInfoForSendEmail&record_id=" + pe_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                pe_email = json.email;
            });

            var customer_phone_number ="";
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + $("#contact_id3_c").val(),
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                customer_phone_number = json.phone_number;
            });

            var pe_contact = $('#pe_contact_c').val();
            var pe_contact_number = $('div[field="pe_contact_c"] p.phone-number').text(); //

            var pe_backup_contact_c = $('#pe_backup_contact_c').val();
            var pe_backup_contact_number = $('div[field="pe_backup_contact_c"] p.phone-number').text(); //

            // Check exist photo:
            var photo = "No Photo";
            if ($('#fileupload tr.template-download').length > 0) {
                photo = "Attachments";
            }
            var file_dir = $('input[name="installation_pictures_c"]').val();
            var customer_notes_c = $('#customer_notes_c').val();
            var suburb = $('#install_address_city_c').val()+' '+$('#install_address_state_c').val();
            if(suburb.trim() == ''){
                suburb = $('#shipping_address_city').val()+' '+$('#shipping_address_state').val();           
            }
            var group_name = $('input.group_name').val();
            var plumbing_contact_number = $('#plumber_contact_c').siblings( ".phone-number" ).text();

            var build_url = "?entryPoint=customSendEmail&mail_format=custommer&plumber_contact_name=" + encodeURIComponent(plumber_contact_name);
            build_url += '&invoice_number=' + encodeURIComponent(invoice_number);
            build_url += '&plumber_install_date=' + encodeURIComponent(plumber_install_date);
            build_url += '&electrical_install_date=' + encodeURIComponent(electrical_install_date);
            build_url += '&billing_account=' + encodeURIComponent(billing_account);
            build_url += '&billing_address=' + encodeURIComponent(billing_address);
            build_url += '&site_contact_name=' + encodeURIComponent(site_contact_name);
            build_url += '&site_contact_number=' + encodeURIComponent(site_contact_number);
            build_url += '&alternate_site_contact_name=' + encodeURIComponent(alternate_site_contact_name);
            build_url += '&alternate_site_contact_number=' + encodeURIComponent(alternate_site_contact_number);
            build_url += '&invoice_to_email=' + encodeURIComponent(invoice_to_email);
            build_url += '&system=' + encodeURIComponent(system);
            build_url += '&plumbing_contact=' + encodeURIComponent(plumbing_contact);
            build_url += '&plumbing=' + encodeURIComponent(plumbing);
            build_url += '&electric_contact_number=' + encodeURIComponent(electric_contact_number);
            build_url += '&electric_name=' + encodeURIComponent(electric_name);
            build_url += '&electric_company=' + encodeURIComponent(electric_company);
            build_url += '&pe_contact_c=' + encodeURIComponent(pe_contact);
            build_url += '&pe_contact_number=' + encodeURIComponent(pe_contact_number);
            build_url += '&photo=' + encodeURIComponent(photo);
            build_url += '&pe_backup_contact_c=' + encodeURIComponent(pe_backup_contact_c);
            build_url += '&pe_backup_contact_number=' + encodeURIComponent(pe_backup_contact_number);
            build_url += '&file_dir=' + encodeURIComponent(file_dir);
            build_url += '&customer_notes_c=' + encodeURIComponent(customer_notes_c);
            build_url += '&suburb=' + encodeURIComponent(suburb);
            build_url += '&group_name=' + encodeURIComponent(group_name);
            build_url += '&plumbing_contact_number=' + encodeURIComponent(plumbing_contact_number);
            build_url += '&pe_email=' + encodeURIComponent(pe_email);
            //build_url = encodeURIComponent(build_url);
            build_url += '&messagetype=' + encodeURIComponent(messagetype);
            build_url += '&is_testing=' + encodeURIComponent(is_testing);
            //customer_phone_number
            build_url += '&customer_phone_number=' + encodeURIComponent(customer_phone_number);
            //is_testing
            //build_url = encodeURIComponent(build_url);

            //dung code - show popup sms custommer Message

            if(messagetype == 'sms/mms'){
                if(customer_phone_number !== ''){
                    $("body").find("#dialog_send_sms_customer").remove();
                    $("body").append(' <div id = "dialog_send_sms_customer" title="Send SMS">'+
                                            '<div id="sms">'+
                                                '<div>'+
                                                    '<div class="label" >Phone Number :'+
                                                    '<span class="input" id="phone_number_customer_customer"></span>'+
                                                '</div>'+
                                                '<div>'+
                                                    '<div class="label" >From Phone Number :</div>'+
                                                    '<select style="width:170px;margin-bottom:2px;" id="from_phone_number_customer" >'+
                                                       '<option label="+61490942067" value="+61490942067">+61490942067</option>'+
                                                       '<option label="+61421616733" value="+61421616733">+61421616733</option>'+
                                                    '</select>'+
                                                '</div>'+
                                                '<div>'+
                                                    '<div class="label">Message :'+
                                                        '<select style="width:500px;margin-bottom:2px;" id="messager_template_customer">'+
                                                            '<option  value=""></option>'+
                                                        '</select>'+
                                                    '</div>'+
                                                    '<div class="input">'+
                                                        '<textarea id="content_messager_customer" style="width:100%;height:200px;">'+
                                                        '</textarea>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>');
                    
                    var phone_number_customer = customer_phone_number;
                    var numberPattern = /\d+/g;
                    phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
                    $('#phone_number_customer_customer').text(phone_number_customer);
                    
                    //content messager
                    site_contact_name  = site_contact_name.split(" ");

                    var body = 'Hi '+ site_contact_name[0] +',' +
                            'Client details below, PO and photos attached.';

                        body += 'Customer Note: '+ customer_notes_c +'.';
                        
                        body += 'Client Install ID #: '+invoice_number+''
                         + 'Date of Plumbing install: ' + plumber_install_date +'.' 
                         + 'Date of Electrical install: '+ electrical_install_date +'.'
                         + 'Client: ' + billing_account + '.'
                         + 'Address: '+ billing_address +'.'
                         + 'Site Contact name: '+site_contact_name+'.'
                         + 'Site Contact number: '+site_contact_number+'.';
                         
                         if(alternate_site_contact_name != "") {
                             body +='Alternate contact name: '+alternate_site_contact_name+'.';
                         }
                        
                         if(alternate_site_contact_number != "") {
                            body += 'Alternate contact number: '+alternate_site_contact_number+'.';
                         }
                         body += ' Email: '+invoice_to_email + '.';
                         body += 'Photo: '+photo + '.';
    
                         body += 'System: '+system+'.'
                            +'Plumbing: '+plumbing+'.'
                            +'Plumbing Contact: '+plumbing_contact+' '
                            +'Plumbing Contact Number: '+ plumbing_contact_number+'.';
    
                        body += 'Electrical: '+electric_company+'.'
                            + 'Electrical Contact: '+electric_name+'.'
                            + 'Electrical Contact Number: '+ electric_contact_number+'.'
                            + 'PureElectric main contact: '+ pe_contact+ ' '+pe_contact_number+' .'
                            + 'PureElectric backup contact: '+pe_backup_contact_c+' '+pe_backup_contact_number +' .';
    
    
    
                    $('#content_messager_customer').val(body);
                    $("#dialog_send_sms_customer").dialog({
                        width: 712,
                        height:478,
                        modal:true,
                        buttons: {
                            Cancel: function(){
                                $(this).dialog('close');
                                $( "#content_messager_customer" ).val("");
                                $('#messager_template_customer').prop('selectedIndex',0);
                                $('#from_phone_number_customer').prop('selectedIndex',0);
                            },
                            Send: function(){
                                click_send_sms_customer();
                                $(this).dialog('close');
                                $( "#content_messager_customer" ).val("");
                                $('#messager_template_customer').prop('selectedIndex',0);
                                $('#from_phone_number_customer').prop('selectedIndex',0);
                            }
                        }
                    });
                }else {
                    alert('Not have number phone !');
                }
            }else {
                $.ajax({
                    url: build_url,
                    context: document.body
                }).done(function (data) {
                    if(messagetype == "sms/mms"){
                        $('#sendCustommerMessage span.glyphicon-refresh').addClass('hidden');
                    } else{
                        $('#sendCustommerrEmail span.glyphicon-refresh').addClass('hidden');
                    }
                    alert("A "+messagetype+" sent!");
                });
            }
            return false;
        }

        $('#customer_notes_c').parent().siblings('.label').append('<br> <button class="button primary" id="sendCustommerrEmail"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Send mail </button>');
        $('#sendCustommerrEmail').after('<button class="button primary" id="sendCustommerMessage"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Send SMS/MMS </button>');

        $('#sendCustommerrEmail').on('click', function (event) {
            event.preventDefault();
            //event.append();
            var testing = 0;
            if($("#send-plumbing-test").is(':checked'))
            // checked
                testing = 1;
            else
            // unchecked
                testing = 0;
            sendCustommerMessage("email", testing);
            return false;
        });

        $('#sendCustommerMessage').on('click', function (event) {
            event.preventDefault();
            //event.append();
            var testing = 0;
            if($("#send-customer-test").is(':checked'))
            // checked
                testing = 1;
            else
            // unchecked
                testing = 0;
            sendCustommerMessage("sms/mms", testing);
            return false;
        });

        function sendPlumberMessage(messagetype, is_testing) {
            if(messagetype == "sms/mms"){
                $('#sendPlumberMessage span.glyphicon-refresh').removeClass('hidden');
            } else{
                $('#sendPlumberEmail span.glyphicon-refresh').removeClass('hidden');
            }
            var plumber_contact_name = $('#plumber_contact_c').val();
            var invoice_number = $('div[field="number"]').text();
            var plumber_install_date = $("#plumber_install_date_c").val();
            var electrical_install_date = $("#electrician_install_date_c").val();
            var billing_account = $("#billing_account").val();
            var billing_address = $('#install_address_c').val() + " " + $('#install_address_city_c').val() + " "
                + $('#install_address_state_c').val() + " " + $('#install_address_postalcode_c').val();

            var site_contact_name = $('#site_contact_c').val();
            var site_contact_number = $('div[field="site_contact_c"] p.phone-number').text();

            var alternate_site_contact_name = $('#site_backup_contact_c').val();
            var alternate_site_contact_number = $('div[field="site_backup_contact_c"] p.phone-number').text();

            var invoice_to_email = false;
            // Get contact email
            var record_id = $('#billing_contact_id').val();
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                invoice_to_email = json.email;
            });

            var pe_id = $('#user_id_c').val();
            var pe_email = "";
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Users&action=GetInfoForSendEmail&record_id=" + pe_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                pe_email = json.email;
            });
            var system = $('#sanden_model_c').val();

            var plumbing_contact = $('#plumber_contact_c').val();
            var plumbing_contact_number = $('#plumber_contact_c').siblings( ".phone-number" ).text();
            // plumbing
            var plumbing = "";
            var record_id = $('#contact_id4_c').val();
            var plumber_phone_number = "";
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                plumber_phone_number = json.phone_number;
                plumbing = json.account_name;
            });

            var electric_contact_number = $('div[field="electrician_contact_c"] p.phone-number').text();

            var electric_name = $('#electrician_contact_c').val();

            var electric_company = "";
            var record_id = $('#contact_id_c').val();
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&action=GetInfoForSendEmail&record_id=" + record_id,
                context: document.body,
                async: false
            }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
                var json = $.parseJSON(data);
                electric_company = json.account_name;
            });
            var pe_contact = $('#pe_contact_c').val();
            var pe_contact_number = $('div[field="pe_contact_c"] p.phone-number').text(); //

            var pe_backup_contact_c = $('#pe_backup_contact_c').val();
            var pe_backup_contact_number = $('div[field="pe_backup_contact_c"] p.phone-number').text(); //

            // Check exist photo:
            var photo = "No Photo";
            if ($('#fileupload tr.template-download').length > 0) {
                photo = "Attachments";
            }
            var file_dir = $('input[name="installation_pictures_c"]').val();
            var plumbing_note = $('#plumbing_notes_c').val();
            var suburb = $('#install_address_city_c').val()+' '+$('#install_address_state_c').val();
            if(suburb.trim() == ''){
                suburb = $('#shipping_address_city').val()+' '+$('#shipping_address_state').val();           
            }
            var group_name = $('input.group_name').val();
            // //dung code
            // group_name = group_name.replace('Elec','Plumbing');
            var plumber_contact_id = $("#contact_id4_c").val();
            var product_e = $('#quote_type_c').val();
            var invoice_id = $('input[name="record"]').val(); 
            var old_hws = createOldHWSString();
            if(messagetype != "sms/mms"){
                var build_url = "?entryPoint=customCreateEmailPopupContent&mail_format=plumber&plumber_contact_name=" + encodeURIComponent(plumber_contact_name) + "&plumber_contact_id="+plumber_contact_id+"&product_type="+product_e;
            } else {
                var build_url = "?entryPoint=customSendEmail&mail_format=plumber&plumber_contact_name=" + encodeURIComponent(plumber_contact_name) ;
            }
            build_url += '&invoice_id=' + encodeURIComponent(invoice_id);
            build_url += '&invoice_number=' + encodeURIComponent(invoice_number);
            build_url += '&plumber_install_date=' + encodeURIComponent(plumber_install_date);
            build_url += '&electrical_install_date=' + encodeURIComponent(electrical_install_date);
            build_url += '&billing_account=' + encodeURIComponent(billing_account);
            build_url += '&billing_address=' + encodeURIComponent(billing_address);
            build_url += '&site_contact_name=' + encodeURIComponent(site_contact_name);
            build_url += '&site_contact_number=' + encodeURIComponent(site_contact_number);
            build_url += '&alternate_site_contact_name=' + encodeURIComponent(alternate_site_contact_name);
            build_url += '&alternate_site_contact_number=' + encodeURIComponent(alternate_site_contact_number);
            build_url += '&invoice_to_email=' + encodeURIComponent(invoice_to_email);
            build_url += '&system=' + encodeURIComponent(system);
            build_url += '&plumbing_contact=' + encodeURIComponent(plumbing_contact);
            build_url += '&plumbing_contact_number=' + encodeURIComponent(plumbing_contact_number);
            build_url += '&plumbing=' + encodeURIComponent(plumbing);
            build_url += '&electric_contact_number=' + encodeURIComponent(electric_contact_number);
            build_url += '&electric_name=' + encodeURIComponent(electric_name);
            build_url += '&electric_company=' + encodeURIComponent(electric_company);
            build_url += '&pe_contact_c=' + encodeURIComponent(pe_contact);
            build_url += '&pe_contact_number=' + encodeURIComponent(pe_contact_number);
            build_url += '&photo=' + encodeURIComponent(photo);
            build_url += '&pe_backup_contact_c=' + encodeURIComponent(pe_backup_contact_c);
            build_url += '&pe_backup_contact_number=' + encodeURIComponent(pe_backup_contact_number);
            build_url += '&file_dir=' + encodeURIComponent(file_dir);
            build_url += '&plumbing_note=' + encodeURIComponent(plumbing_note);
            build_url += '&suburb=' + encodeURIComponent(suburb);
            build_url += '&group_name=' + encodeURIComponent(group_name);
            build_url += '&pe_email=' + encodeURIComponent(pe_email);
            build_url += '&messagetype=' + encodeURIComponent(messagetype);
            build_url += '&is_testing=' + encodeURIComponent(is_testing);
            build_url += '&plumber_phone_number=' + encodeURIComponent(plumber_phone_number);
            build_url += '&po_record='+ encodeURIComponent($("#plumber_po_c").val());
            build_url += '&note_pcoc_cert='+ encodeURIComponent($("#pcoc_cert_wording_c").val());
            build_url += '&old_hws=' + encodeURIComponent(old_hws.trim());
            //plumber_phone_number
            //is_testing
            //build_url = encodeURIComponent(build_url);

            //dung code - popup send sms plumbing
             if(messagetype == 'sms/mms') {
                if(plumber_phone_number !== ''){
                    $("body").find("#dialog_send_sms_plum").remove();
                    $("body").append(' <div id = "dialog_send_sms_plum" title="Send SMS">'+
                                            '<div id="sms">'+
                                                '<div>'+
                                                    '<div class="label" >Phone Number :'+
                                                    '<span class="input" id="phone_number_customer_plum"></span>'+
                                                '</div>'+
                                                '<div>'+
                                                    '<div class="label" >From Phone Number :</div>'+
                                                    '<select style="width:170px;margin-bottom:2px;" id="from_phone_number_plum" >'+
                                                       '<option label="+61490942067" value="+61490942067">+61490942067</option>'+
                                                       '<option label="+61421616733" value="+61421616733">+61421616733</option>'+
                                                    '</select>'+
                                                '</div>'+
                                                '<div>'+
                                                    '<div class="label">Message :'+
                                                        '<select style="width:500px;margin-bottom:2px;" id="messager_template_plum">'+
                                                            '<option  value=""></option>'+
                                                        '</select>'+
                                                    '</div>'+
                                                    '<div class="input">'+
                                                        '<textarea id="content_messager_plum" style="width:100%;height:200px;">'+
                                                        '</textarea>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>');
                    
                    var phone_number_customer = plumber_phone_number;
                    var numberPattern = /\d+/g;
                    phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
                    $('#phone_number_customer_plum').text(phone_number_customer);
                    var link_upload = 'https://pure-electric.com.au/upload_file_sanden/for-plumber?invoice_id='+invoice_id;
                    //content messager
                    plumber_contact_name  = plumber_contact_name.split(" ");

                    var body = 'Hi '+ plumber_contact_name[0] +',' +
                            'Client details below, PO and photos attached.';

                        body += 'Plumber Note: '+ plumbing_note +'.';

                        body += 'Client Install ID #: '+invoice_number+''
                         + 'Date of Plumbing install: ' + plumber_install_date +'.' 
                         + 'Date of Electrical install: '+ electrical_install_date +'.'
                         + 'Client: ' + billing_account + '.'
                         + 'Address: '+ billing_address +'.'
                         + 'Site Contact name: '+site_contact_name+'.'
                         + 'Site Contact number: '+site_contact_number+'.';
                         
                         if(alternate_site_contact_name != "") {
                             body +='Alternate contact name: '+alternate_site_contact_name+'.';
                         }
                        
                         if(alternate_site_contact_number != "") {
                            body += 'Alternate contact number: '+alternate_site_contact_number+'.';
                         }
                         body += ' Email: '+invoice_to_email + '.';
                         body += 'Photo: '+photo + ' '+link_upload +' .';
    
                         body += 'System: '+system+'.'
                            +'Plumbing: '+plumbing+'.'
                            +'Plumbing Contact: '+plumbing_contact+' '
                            +'Plumbing Contact Number: '+ plumbing_contact_number+'.';
    
                        body += 'Electrical: '+electric_company+'.'
                            + 'Electrical Contact: '+electric_name+'.'
                            + 'Electrical Contact Number: '+ electric_contact_number+'.'
                            + 'PureElectric main contact: '+ pe_contact+ ' '+pe_contact_number+' .'
                            + 'PureElectric backup contact: '+pe_backup_contact_c+' '+pe_backup_contact_number +' .';
    
    
    
                    $('#content_messager_plum').val(body);
                    $("#dialog_send_sms_plum").dialog({
                        width: 712,
                        height:478,
                        modal:true,
                        buttons: {
                            Cancel: function(){
                                $(this).dialog('close');
                                $( "#content_messager_plum" ).val("");
                                $('#messager_template_plum').prop('selectedIndex',0);
                                $('#from_phone_number_plum').prop('selectedIndex',0);
                            },
                            Send: function(){
                                click_send_sms_plum();
                                $(this).dialog('close');
                                $( "#content_messager_plum" ).val("");
                                $('#messager_template_plum').prop('selectedIndex',0);
                                $('#from_phone_number_plum').prop('selectedIndex',0);
                            }
                        }
                    });
                }else {
                    alert('Not have number phone !');
                }
             }else {
                $.ajax({
                    url: build_url,
                    context: document.body,
                    async: false
                }).done(function (data) {
                    
                    if(messagetype == "sms/mms"){
                        $('#sendPlumberMessage span.glyphicon-refresh').addClass('hidden');
                    } else{
                        $('#sendPlumberEmail span.glyphicon-refresh').addClass('hidden');
                        console.log(data);
                        window.open(data,'_blank');
                    }
                });
             }
            return false;
        }

        $('#plumbing_notes_c').parent().siblings('.label').append('<br> <button class="button primary" id="sendPlumberEmail"><span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Send mail & PO</button>');
        $('#sendPlumberEmail').after('<button class="button primary" id="sendPlumberMessage"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Send SMS/MMS </button>');


        $('#sendPlumberEmail').on('click', function (event) {
            event.preventDefault();
            
            if($("#plumber_po_c").val() == "") {
            var answer = confirm("Dont have PO! Do you still want to send?")
                if (answer) {
                    //some code
                }
                else {
                    //some code
                    return false;
                }
            }
            if($("#pcoc_cert_wording_c").val() == "") {
                var answer = confirm("No PCOC Cert Wording, Are you sure you want to show the Draft Email?")
                if (answer) {
                    //some code
                }
                else {
                    $("#pcoc_cert_wording_c").focus();
                    return false;
                }
            }
            var is_proposed_location_ins_photo = check_proposed_ins_localtion();
            if (is_proposed_location_ins_photo) {
                //have >> continue
            } else {
                var question = confirm("No Proposed Install Location photo is attached - are you sure to continue?");
                if (question) {
                    //some code
                }
                else {
                    //some code
                    return false;
                }
            }
            
            var testing = 0;
            if($("#send-plumbing-test").is(':checked'))
                // checked
                testing = 1;
            else
                // unchecked
                testing = 0;
            sendPlumberMessage("email", testing);
            return false;
        });

        $("#sendPlumberMessage").on('click', function (event) {
            event.preventDefault();
            var testing
            if($("#send-plumbing-test").is(':checked'))
            // checked
                testing = 1;
            else
            // unchecked
                testing = 0;
            sendPlumberMessage("sms/mms", testing);
            return false;
        });

        // For all contacts fields

        /*function setupContactPhoneNumber(){
        if($('#electrician_contact_c').val() != "") return;
        $('#electrician_contact_c').val($('input[name="assigned_user_name"]').val());
        }*/
        if(module_sugar_grp1 == 'AOS_Invoices') {
            function populatePhoneNumberElectricianContact() {
                $.ajax({
                    url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&record_id=" + $("#contact_id_c").val(),
                    context: document.body
                }).done(function (data) {
                    $('#contact_id_c').parent().children('.phone-number').remove();
                    $('#contact_id_c').parent().append('<p class="phone-number">' + data + '</p>');
                });
            };
            populatePhoneNumberElectricianContact();
            YAHOO.util.Event.addListener("contact_id_c", "change", populatePhoneNumberElectricianContact);

            function populatePhoneNumberPlumberContact() {
                $.ajax({
                    url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&record_id=" + $("#contact_id4_c").val(),
                    context: document.body
                }).done(function (data) {
                    $('#contact_id4_c').parent().children('.phone-number').remove();
                    $('#contact_id4_c').parent().append('<p class="phone-number">' + data + '</p>');
                });
            };
            populatePhoneNumberPlumberContact();
            YAHOO.util.Event.addListener("contact_id4_c", "change", populatePhoneNumberPlumberContact);

            YAHOO.util.Event.addListener("account_id_c", "change", function(){
                var record_id = $("#account_id_c").val();
                $.ajax({
                    url: "?entryPoint=getContactFromAccount&record_id=" + record_id,
                    context: document.body,
                    //async: true
                }).done(function (data) {
                    if(data == '' || typeof data == 'undefined')return;
                    var json = $.parseJSON(data);
                    $("#electrician_contact_c").val(json.name);
                    $("#contact_id_c").val(json.record_id);
                    //dung code - trigger event get phone for electrican
                    populatePhoneNumberElectricianContact();
                });
                return false;
            });
            YAHOO.util.Event.addListener("account_id1_c", "change", function(){
                var record_id = $("#account_id1_c").val();
                $.ajax({
                    url: "?entryPoint=getContactFromAccount&record_id=" + record_id,
                    context: document.body,
                    async: false
                }).done(function (data) {
                    if(data == '' || typeof data == 'undefined')return;
                    var json = $.parseJSON(data);
                    $("#plumber_contact_c").val(json.name);
                    $("#contact_id4_c").val(json.record_id);
                    //dung code - trigger event get phone for Plumber
                        populatePhoneNumberPlumberContact();
                });
            });
    
            function populatePhoneNumberSiteBackupContact() {
                $.ajax({
                    url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&record_id=" + $("#contact_id1_c").val(),
                    context: document.body
                }).done(function (data) {
                    $('#contact_id1_c').parent().children('.phone-number').remove();
                    $('#contact_id1_c').parent().append('<p class="phone-number">' + data + '</p>');
                });
            };
            populatePhoneNumberSiteBackupContact();
            YAHOO.util.Event.addListener("contact_id1_c", "change", populatePhoneNumberSiteBackupContact);


            function populatePhoneNumberSiteContact() {
                $.ajax({
                    url: "?entryPoint=getContactPhoneNumber&module_name=Contacts&record_id=" + $("#contact_id3_c").val(),
                    context: document.body
                }).done(function (data) {
                    $('#contact_id3_c').parent().children('.phone-number').remove();
                    $('#contact_id3_c').parent().append('<p class="phone-number">' + data + '</p>');
                });
            };
            populatePhoneNumberSiteContact();
            YAHOO.util.Event.addListener("contact_id3_c", "change", populatePhoneNumberSiteContact);

            function populatePhoneNumberPEContact() {
                $.ajax({
                    url: "?entryPoint=getContactPhoneNumber&module_name=Employees&record_id=" + $("#user_id_c").val(),
                    context: document.body
                }).done(function (data) {
                    $('#user_id_c').parent().children('.phone-number').remove();
                    $('#user_id_c').parent().append('<p class="phone-number">' + data + '</p>');
                });
            };
            populatePhoneNumberPEContact();
            YAHOO.util.Event.addListener("user_id_c", "change", populatePhoneNumberPEContact);

            function populatePhoneNumberPEBackupContact() {
                $.ajax({
                    url: "?entryPoint=getContactPhoneNumber&module_name=Employees&record_id=" + $("#user_id1_c").val(),
                    context: document.body
                }).done(function (data) {
                    $('#user_id1_c').parent().children('.phone-number').remove();
                    $('#user_id1_c').parent().append('<p class="phone-number">' + data + '</p>');
                });
            };
            populatePhoneNumberPEBackupContact();
            YAHOO.util.Event.addListener("user_id1_c", "change", populatePhoneNumberPEBackupContact);
            // For PE Contact Field

            function setupPEContact() {
                if ($('input[name="pe_contact_c"]').val() != "") return;
                $('input[name="pe_contact_c"]').val($('input[name="assigned_user_name"]').val());
                $('input[name="user_id_c"]').val($('input[name="assigned_user_id"]').val());
                //dung code - new logic PE Contact - PE backup Contact
                if($('input[name="assigned_user_id"]').val() == '61e04d4b-86ef-00f2-c669-579eb1bb58fa'){
                    $('input[name="pe_backup_contact_c"]').val('Matthew Wright');
                    $('input[name="user_id1_c"]').val('8d159972-b7ea-8cf9-c9d2-56958d05485e');
                    populatePhoneNumberPEContact();
                    populatePhoneNumberPEBackupContact();
                }else{
                    $('input[name="pe_backup_contact_c"]').val('Paul Szuster');
                    $('input[name="user_id1_c"]').val('61e04d4b-86ef-00f2-c669-579eb1bb58fa');
                    populatePhoneNumberPEContact();
                    populatePhoneNumberPEBackupContact();
                }
            }
        
            setupPEContact();

            //dung code - logic PE contact and PE backup Contact -event change
            $(document).on('change','#pe_contact_c',function(){
                if($('input[name="pe_contact_c"]').val() == 'Paul Szuster'){
                    $('input[name="pe_backup_contact_c"]').val('Matthew Wright');
                    $('input[name="user_id1_c"]').val('8d159972-b7ea-8cf9-c9d2-56958d05485e');
                    populatePhoneNumberPEContact();
                    populatePhoneNumberPEBackupContact();
                }else{
                    $('input[name="pe_backup_contact_c"]').val('Paul Szuster');
                    $('input[name="user_id1_c"]').val('61e04d4b-86ef-00f2-c669-579eb1bb58fa');
                    populatePhoneNumberPEContact();
                    populatePhoneNumberPEBackupContact();
                }
            });
        }
        function randomIntFromInterval(min, max) {
            return Math.floor(Math.random() * (max - min + 1) + min);
        }

        function setupPEBackupContact() {
            if ($('input[name="pe_backup_contact_c"]').val() != "") return;
            if (randomIntFromInterval(0, 1) == 0) {
                $('input[name="pe_backup_contact_c"]').val('Paul Szuster');
                $('input[name="user_id1_c"]').val('61e04d4b-86ef-00f2-c669-579eb1bb58fa');
            }
            else {  
                $('input[name="pe_backup_contact_c"]').val('Matthew Wright');
                $('input[name="user_id1_c"]').val('8d159972-b7ea-8cf9-c9d2-56958d05485e');
            }
        }

        // /setupPEBackupContact(); - change new logic
        // For update date
        var installation_date = $('#installation_date_c_date').val();

        function populateDate() {
            if (installation_date != "") return;

            var l_installation_date = $('#installation_date_c_date').val();
            /*if(l_installation_date == ""){
            $('#vba_pic_date_c').val(l_installation_date);
            $('#ces_cert_date_c').val(l_installation_date);
            $('#plumber_install_date_c').val(l_installation_date);
            $('#electrician_install_date_c').val(l_installation_date);
            return;
            }*/
            if (!$("#tentative_c").is(":checked")) {
                //if($('#vba_pic_date_c').val() == "")
                $('#vba_pic_date_c').val(l_installation_date);
                //if($('#ces_cert_date_c').val() == "" )
                $('#ces_cert_date_c').val(l_installation_date);
                //if($('#plumber_install_date_c').val() == "" )
                $('#plumber_install_date_c').val(l_installation_date);
                //if($('#electrician_install_date_c').val() == "" )
                $('#electrician_install_date_c').val(l_installation_date);
            }
        }

        YAHOO.util.Event.addListener("installation_date_c_date", "change", populateDate);


        // For text bellow STC Aggregator Serial

        function createSTCAggregatorSerial() {
            if ($('#stc_aggregator_serial_c').val() == "") {
                $('#stc_aggregator_serial_c').siblings().empty();
                return;
            }
            var href = "<div class='open-stc-rebate'>Open STC rebate application <a target='_blank' href='https://geocreation.com.au/assignments/" + $('#stc_aggregator_serial_c').val() +
                "/edit'>https://geocreation.com.au/assignments/" + $('#stc_aggregator_serial_c').val() + "/edit</a></div>";
            $('#stc_aggregator_serial_c').siblings().empty();
            $('#stc_aggregator_serial_c').parent().append(href);
        }

        createSTCAggregatorSerial();

        $('#stc_aggregator_serial_c').change(function () {
            createSTCAggregatorSerial();
        });

        function createSTCAggregatorSerial2() {
            if ($('#stc_aggregator_serial_2_c').val() == "") return;
            var href = "<div class='open-stc-rebate'>Open STC rebate application <a target='_blank' href='https://geocreation.com.au/assignments/" + $('#stc_aggregator_serial_2_c').val() +
                "/edit'>https://geocreation.com.au/assignments/" + $('#stc_aggregator_serial_2_c').val() + "/edit</a></div>";
            $('#stc_aggregator_serial_2_c').siblings().empty();
            $('#stc_aggregator_serial_2_c').parent().append(href);
        }

        createSTCAggregatorSerial2();

        $('#stc_aggregator_serial_2_c').change(function () {
            createSTCAggregatorSerial2();
        });

        // For photo uploads
        $('#file_rename_c').val("");

        $('div[field="installation_pictures_c"]').find('table').after($('#save_and_continue_edit').clone());
        $('div[field="installation_pictures_c"]').find('table').after(' ');
        $('div[field="installation_pictures_c"]').find('table').after($('#cancel_and_continue_edit').clone());
    });
});

$( window ).load(function() {
    // auto import image map
    if($("#Map_Template_Image").attr("src") == undefined){
        $("#open_map_google").closest("#import_button").hide();
        SUGAR.ajaxUI.showLoadingPanel();
        $("#ajaxloading_mask").css("position",'fixed');
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' 
            + encodeURIComponent($('#install_address_c').val()) +", " 
            + encodeURIComponent($('#install_address_city_c').val())  + ", " 
            + encodeURIComponent($('#install_address_state_c').val())  
            + ", " +  encodeURIComponent($('#install_address_postalcode_c').val()) 
            + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
            type: 'GET',
            success: function(result) {
                if (result.status == "OK"){
                    var location = result.results[0].geometry.location;
                    $.ajax({
                        url: "index.php?entryPoint=Image_Site_Details_Get_From_Google&lat="
                        +  location.lat
                        + "&lng="  + location.lng,
                        type: 'GET',
                        success: function(result) {
                            SUGAR.ajaxUI.hideLoadingPanel();
                            $("#Map_Template_Image").hide();
                            $("#map").hide();
                            $("#Map_Template_Image").after(result);
                            $("#download").remove();
                        }
                    }).done(function(data){
                        setTimeout(function(){ 
                            CopyToClipboard();
                            if (module_sugar_grp1 == 'AOS_Invoices') {
                                // debugger
                                setTimeout(function(){ 
                                    var files_design = getFileDesign('Design_');
                                    $('#img_design').remove();
                                    if (files_design != '') {
                                        var urlImageDesign = '<a style="margin: 10px;" id="img_design" data-gallery="image" href="'+files_design+'"><img style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:220px;height:auto;margin-top: 5em;" src="'+files_design+'"></a>';
                                        $('#Map_Template_Image').after(urlImageDesign);
                                    }
                                },5000);
                            }
                        },5000);
                    });
                }else{
                    SUGAR.ajaxUI.hideLoadingPanel();
                }
            }
        });
    } else {
        if (module_sugar_grp1 == 'AOS_Invoices') {
            // debugger
            setTimeout(function(){ 
                var files_design = getFileDesign('Design_');
                $('#img_design').remove();
                if (files_design != '') {
                    var urlImageDesign = '<a style="margin: 10px;" id="img_design" data-gallery="image" href="'+files_design+'"><img style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:220px;height:auto;margin-top: 5em;" src="'+files_design+'"></a>';
                    $('#Map_Template_Image').after(urlImageDesign);
                }
            },5000);
        }
}
});

function CopyToClipboard(generateUUID){
    if($(document).find('#map').length == 0)return;
    window.onbeforeunload = null;
    setTimeout(function(){
        html2canvas($(document).find('#map'), {
            useCORS: true,
            allowTaint:false,
            onrendered: function(canvas) {
                document.body.appendChild(canvas);
                if(canvas.width == 0 && canvas.height == 0){
                    console.log("can't create canvas.");
                    return false;
                }
                var image = Canvas2Image.convertToPNG(canvas);
                var image_data = $(image).attr('src');
                var recordId = $("input[name='record']").val();
                $.ajax({
                    type: "POST", 
                    url: "index.php?entryPoint=Image_Site_Details_Clipboard_Popup", 
                    data: { img: image_data,id:generateUUID, record_id: recordId ,nameModule : 'AOS_Invoices' ,action: 'DetailView'}      
                    }).done(function(data_return){                
                        $('body').find("#Map_Template_Image").remove();
                        $('body').find("#google_map").remove();
                        var html_image_site_detail = '<img id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;height:auto;width:100%;max-width:220px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+ generateUUID +'/Image_Site_Detail.jpg?'+Date.now()+'">';
                        $('body').find("#group_custom_invoice_template_col_2_2").append(html_image_site_detail);
                        SUGAR.ajaxUI.hideLoadingPanel();                 
                    });
                document.body.removeChild(canvas);
            }
        });
    },10000);
}


(function ($) {
    $.fn.openComposeViewModal_US7_and_Sanden_Tips = function (source) {
        "use strict";
        var module_name = $(source).attr('data-module');
        var record_id= $(source).attr('data-record-id') ;
        var emailType = $(source).data('email-type');
        var sanden_product = $(source).attr('data-sanden-product');
        let promo_codes_email = $(source).attr('data-promo-code');

        if(record_id == '' && module_name == 'Contacts' ){
            alert('Please Select Contact before !');
            return;
        }

        var self = this;

        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var email_module  =  $(source).attr('data-module');
        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"") ;
        
        if (sanden_product != '' && sanden_product != undefined) {
            url_email += '&sanden_product='+sanden_product;
        }

        if (promo_codes_email != '' && promo_codes_email != undefined) {
            url_email += '&promo_codes='+promo_codes_email;
        }

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

$(document).ready(function(){
    // //VUT - Hidden 2020/11/05
    // var data_post_into_total_price = [];
    // var number_plumber_po = $('#plumber_po_c').val();
    // var number_invoice = $('input[name="record"]').val();
    // var number_electrical_po = $('#electrical_po_c').val();
    // var number_daikin_po = $('#daikin_po_c').val();
    // data_post_into_total_price.push(number_plumber_po);
    // data_post_into_total_price.push(number_electrical_po);
    // data_post_into_total_price.push(number_daikin_po);
    // data_post_into_total_price.push(number_invoice);
    if(module_sugar_grp1 == 'AOS_Invoices'){
        // //VUT - Hidden 2020/11/05
        // $.ajax({
        //     type: 'POST',
        //     url:"?entryPoint=getPOInfo",
        //     data: {
        //         "data":data_post_into_total_price
        //     },
        //     success : function(result){
        //         $('#description').parent().parent().next().append(result);
        //     }
        // });

        $.ajax({
            type: 'POST',
            url:"?entryPoint=Caculation_Profit&InvoiceID="+$('input[name="record"]').val(),
            success : function(result){
                if(result != 'Resource Not Found') {
                    try {
                        var data = JSON.parse(result);
                        $('#total_cost_c').val(data.subtotal_total_cost);
                        $('#total_revenue_c').val(data.subtotal_total_revenue);
                        $('#gross_profit_c').val(data.profit);
                        $('#gross_profit_percent_c').val(data.gp);
                        // //old field  - VUT hidden 2020/11/05
                        // $("#profit_c").val(data.profit);
                        // $("#gp_c").val(data.gp);
                        // $("#subtotal_c").val(data.subtotal_total_cost);
                    } catch (error) {
                        
                    }
                   
                }
            }
        });
    }

    //dung code --- minimise section in invoice
    function minimise_invoice(){
        var panel_usually_open = ['key fields','site details','installation details','invoice to', 'files and photos','supply purchase orders', 'bill','payments', 'internal notes','gp calculation'];
        //solar
        if ($('#name').val().toLowerCase().includes('solar')) {
            // panel_usually_open.push('site details');
            $('.panel-content .panel-default').each(function(){
                var current_selector_panel_id = '#' + $(this).find('.panel-body').attr('id');
                var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
                if(title_panel_default.includes('solar') || panel_usually_open.includes(title_panel_default)){
                    if($("#group0name").val() != '' && title_panel_default == 'line items') {
                        $(current_selector_panel_id).addClass('in');
                        $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                    }
                    $(current_selector_panel_id).addClass('in');
                    $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                }else{
                    $(current_selector_panel_id).removeClass("in");
                    $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');                   
                }
            });
        }
        // daikin
        else if ($('#name').val().toLowerCase().includes('daikin')) {
            panel_usually_open.push('daikin installation detail', 'daikin infomation', 'line items', 'xero invoices');
            $('.panel-content .panel-default').each(function(){
                var current_selector_panel_id = '#' + $(this).find('.panel-body').attr('id');
                var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
                if(title_panel_default.includes('daikin') || panel_usually_open.includes(title_panel_default)){
                    $(current_selector_panel_id).addClass('in');
                    $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                }else{
                    if($("#group0name").val() != '' && title_panel_default == 'line items') {
                        $(current_selector_panel_id).addClass('in');
                        $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                    }else{
                        $(current_selector_panel_id).removeClass("in");
                        $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
                    }
                }
            });
        }
        //sanden
        else if ($('#name').val().toLowerCase().includes('sanden')) {
            panel_usually_open.push('sanden infomation', 'line items' ,'xero invoices');
            $('.panel-content .panel-default').each(function(){
                var current_selector_panel_id = '#' + $(this).find('.panel-body').attr('id');
                var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
                if(title_panel_default.includes('sanden') || panel_usually_open.includes(title_panel_default)){
                    $(current_selector_panel_id).addClass('in');
                    $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                }else{
                    if($("#group0name").val() != '' && title_panel_default == 'line items') {
                        $(current_selector_panel_id).addClass('in');
                        $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                    }else{
                        $(current_selector_panel_id).removeClass("in");
                        $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');                        
                    }
                }
            });
        }
        //default 
        else {
            $('.panel-content .panel-default').each(function(){
                
                var current_selector_panel_id = '#' + $(this).find('.panel-body').attr('id');
                var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
                if( panel_usually_open.includes(title_panel_default)){
                    $(current_selector_panel_id).addClass('in');
                    $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                }else{
                    if($("#group0name").val() != '' && title_panel_default == 'line items') {
                        $(current_selector_panel_id).addClass('in');
                        $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                    }else{
                        $(current_selector_panel_id).removeClass("in");
                        $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
                    }
                }
            });
        }
    }

    if(module_sugar_grp1 == 'AOS_Invoices'){
        minimise_invoice();
        $('#name').change(function(){
            minimise_invoice();
        });
    }


    //thienpb code
    $('#meeting_c').parent().siblings('.label').append(
        '</br>&nbsp;<button type="button" id="mettingWithInstallerClone" class="button mettingWithInstallerClone" title="Metting With Installer" style= "font-size: smaller" >Create Meeting<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
    );
    $("#mettingWithInstallerClone").click(function(){
        $("#mettingWithInstaller").trigger('click');
    })

    // //Dung code

    function convert_delivery_address(){
        $("#delivery_contact_address_c").val($("#shipping_address_street").val()); 
        $("#delivery_contact_suburb_c").val($("#shipping_address_city").val());
        $("#delivery_contact_postcode_c").val($("#shipping_address_postalcode").val());
        $("#delivery_contact_address_c").val($("#shipping_address_street").val());
    }
    YAHOO.util.Event.addListener("shipping_address_street", "change", convert_delivery_address);
    YAHOO.util.Event.addListener("shipping_address_city", "change", convert_delivery_address);
    YAHOO.util.Event.addListener("shipping_address_postalcode", "change", convert_delivery_address);
    YAHOO.util.Event.addListener("billing_address_street", "change", convert_delivery_address);
    YAHOO.util.Event.addListener("billing_address_city", "change", convert_delivery_address);
    YAHOO.util.Event.addListener("billing_address_postalcode", "change", convert_delivery_address);

    $('#billing_contact').blur(function(){
        $('#delivery_contact_name_c').val($('#billing_contact').val());
        var record_id = $('#billing_contact_id').val();
        $.ajax({
            url: "?entryPoint=getContactPhoneNumber&module_name=Accounts&action=delivery_contact_phone_number&record_id=" + $("#billing_contact_id").val(),
            success: function (data) {
                console.log(data);
                $('#delivery_contact_phone_numbe_c').val(data);
            }
        });
    })
    //thien update code for send sms
    var phone_number_customer = "";
	var name_customer_mess = '';
	var user_name_mess = '';
	var module_for_mess = $('#MassUpdate input[name="module"]').val();
	var record_id_mess = '';
    $(document).on('click','.sms_icon_invoice',function(e){

        $("body").find("#dialog_send_sms").remove();
        $("body").append(' <div id = "dialog_send_sms" title="Send SMS">'+
                                '<div id="sms">'+
                                    '<div>'+
                                        '<div class="label" >Phone Number :'+
                                        '<span class="input" id="phone_number_customer"></span>'+
                                    '</div>'+
                                    '<div>'+
                                        '<div class="label" >From Phone Number :</div>'+
                                        '<select style="width:170px;margin-bottom:2px;" id="from_phone_number" >'+
                                           '<option label="+61490942067" value="+61490942067">+61490942067</option>'+
                                           '<option label="+61421616733" value="+61421616733">+61421616733</option>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div>'+
                                        '<div class="label">Message :'+
                                            '<select style="width:500px;margin-bottom:2px;" id="messager_template">'+
                                                '<option  value=""></option>'+
                                                '<option  value="Hi [FirstName], [User] from Solargain. We’d love to prepare a solar quote for you. So we can view aerial photos of your house, can you advise the address? We also need your email address to send a quote to you. If you wish you can call me on [UserMobile]">Hi [FirstName], [User] from Solargain. We’d love to prepare a solar quote for you. So we can view aerial photos of your house, can you advise the address? We also need your email address to send a quote to you. If you wish you can call me on [UserMobile]</option>'+
                                                '<option  value="Hi [FirstName], this is [User] from Solargain. Just checking in to see how your solar decision-making was progressing and how I may assist? If you wish you can call me on [UserMobile].">Hi [FirstName], this is [User] from Solargain. Just checking in to see how your solar decision-making was progressing and how I may assist? If you wish you can call me on [UserMobile].</option>'+
                                                '<option  value="Hi [FirstName], your solar energy system is due for a service. Please reply YES to book your solar health check or STOP to opt out of these notifications. For more information, call 1300 73 93 55 or visit https://www.solargain.com.au/service/book-your-solar-power-maintenance-service.">Hi [FirstName], your solar energy system is due for a service. Please reply YES to book your solar health check or STOP to opt out of these notifications. For more information, call 1300 73 93 55 or visit https://www.solargain.com.au/service/book-your-solar-power-maintenance-service.</option>'+
                                            '</select>'+
                                        '</div>'+
                                        '<div class="input">'+
                                            '<textarea id="content_messager" style="width:100%;height:200px;">'+
                                            '</textarea>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>');
        var phone_number_id = $(this).attr("data-source")+"_phone_number";
        if(typeof($("#"+$(this).parent().parent().attr("field")).val()) !== 'undefined'){
            name_customer_mess = $("#"+$(this).parent().parent().attr("field")).val().split(" ")[1];
        }else{
            name_customer_mess = $('#billing_account').val().split(" ")[1];
        }        
        user_name_mess = $('#assigned_user_name').val();
        phone_number_customer = $(this).parent().children("."+phone_number_id).text();
        if(phone_number_customer == '' && typeof($('#delivery_contact_phone_numbe_c').val()) !== 'undefined'){
            phone_number_customer = $('#delivery_contact_phone_numbe_c').val();
        }
        var numberPattern = /\d+/g;
        phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
        $('#phone_number_customer').text(phone_number_customer);
        record_id_mess = $(this).parent().find('input[name="mass[]"]').val();
        $("#dialog_send_sms").dialog({
            width: 712,
            height:478,
            modal:true,
            buttons: {
                Cancel: function(){
                    $(this).dialog('close');
                    $( "#content_messager" ).val("");
                    $('#messager_template').prop('selectedIndex',0);
                    $('#from_phone_number').prop('selectedIndex',0);
                },
                Send: function(){
                    click_send_sms();
                    $(this).dialog('close');
                    $( "#content_messager" ).val("");
                    $('#messager_template').prop('selectedIndex',0);
                    $('#from_phone_number').prop('selectedIndex',0);
                }
            }
        });
    })
    $(document).on("change",'#messager_template',function(){
        var content_messager_string = "";
        var user_phone_number	= $('#from_phone_number').find('option:selected').val();
        name_customer_mess = name_customer_mess.trim();
        user_name_mess = user_name_mess.trim();
       $( "#messager_template option:selected" ).each(function() {
           content_messager_string += $( this ).text() + " ";
           content_messager_string = content_messager_string.replace("[FirstName]",name_customer_mess);
           content_messager_string = content_messager_string.replace("[User]",user_name_mess);
           content_messager_string = content_messager_string.replace("[UserMobile]",user_phone_number);
       });
       $( "#content_messager" ).val(content_messager_string);
   })

   $('button[class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close"]').click(function(){
       $( "#content_messager" ).val("");
       $('#messager_template').prop('selectedIndex',0);
       $('#from_phone_number').prop('selectedIndex',0);
   });
   //function send sms when click
    function click_send_sms(){
		var content_messager_string = $('#content_messager').val();
		var user_phone_number	= $('#from_phone_number').find('option:selected').val();
		phone_number_customer = phone_number_customer.trim()
		var data_json = {
			'phone_number_customer':phone_number_customer,
			'from_phone_number':user_phone_number,
			'content_messager' :content_messager_string,
			'module': module_for_mess,
			'record_id' : record_id_mess,
		}
		$.ajax({
			url: 'index.php?entryPoint=sendSMS',
			type: 'POST',
			data: data_json,
			success: function(data){
				console.log(data);
			}
		});
	}

    $('div[field="delivery_contact_phone_numbe_c"]').append('<img class="sms_icon_invoice" data-source="account"  src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14">&nbsp;<a target="_blank" href="http://message.pure-electric.com.au/'+phone_number_customer.replace(/^0/g, "#61").replace(/^61/g,"#61").replace(/\s+/g,'')+'" title="Message Portal"><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="14" width="14"></a>');
});

$(document).ready(function(){
    //dung code -button get data ABN
    $('#abn_c').parent().siblings('.label').append('<br> <button type="button" class="button primary" id="getData_ABN"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Get ABN</button>');
    $('#business_name_data_c').hide();
    $('#business_name_data_c').parent().append('<div id="text_business_name"></div>');
    if($('#business_name_data_c').val() !== '' && typeof($('#business_name_data_c').val()) !== 'undefined'){
        var render_data_business = JSON.parse($('#business_name_data_c').val());
        var html_business_name = '';
        if(typeof(render_data_business) !== 'undefined' ){
            $.each(render_data_business,function(key,value){
                if(key !== '' || value[0] !== '' ){
                    if(value[1]){
                        var string_plus = '<input type="radio" checked name="Business_name" class="Business_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                    }else {
                        var string_plus = '<input type="radio" name="Business_name" class="Business_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                    }
                   
                    html_business_name += string_plus;
                }
            });
        }
        $('#text_business_name').html('');
        $('#text_business_name').append(html_business_name);
    }
    $("#getData_ABN").click(function(){   
        $.ajax({
            url: 'index.php?entryPoint=getdata_ABN&number_ABN='+$('#abn_c').val(),
            success: function(data){

                if(data !== '[]') {
                    var data_result =  $.parseJSON(data);
                    $('#entity_name_c').val(data_result['Entiny_name']);
                    $('#abn_status_c').val(data_result['ABN_status']);
                    $('#entity_type_c').val(data_result['Entity_type']);
                    $('#good_services_tax_c').val(data_result['Goods_Services_Tax']);
                    $('#main_business_location_c').val(data_result['Main_business_location']);
                    if (typeof(data_result['Business_name']) !== 'undefined'){
                        $('#business_name_data_c').val(JSON.stringify(data_result['Business_name']));
                    }
                    else {
                        $('#business_name_data_c').val(JSON.stringify(data_result['trading_name']));
                    }

                    $('#trading_name_data_c').val(JSON.stringify(data_result['trading_name']));
                    $('#asic_registation_acn_or_arbn_c').val(data_result['ASIC_registration_ACN_or_ARBN']);
                    var html_business_name = '';
                    if(typeof(data_result['Business_name']) !== 'undefined' ){
                        $.each(data_result['Business_name'],function(key,value){
                            if(key !== '' || value[0] !== '' ){
                                var string_plus = '<input type="radio" name="Business_name" class="Business_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                                html_business_name += string_plus;
                            }
                        });
                    } else if(typeof(data_result['trading_name']) !== 'undefined' ){
                        $.each(data_result['trading_name'],function(key,value){
                            if(key !== '' || value[0] !== '' ){
                                var string_plus = '<input type="radio" name="Business_name" class="Business_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                                html_business_name += string_plus;
                            }
                        });
                    }
                    $('#text_business_name').html('');
                    $('#text_business_name').append(html_business_name);

                    /// Trading name
                    var html_trading_name = '';
                    if(typeof(data_result['trading_name']) !== 'undefined' ){
                        $.each(data_result['trading_name'],function(key,value){
                            if(key !== '' || value[0] !== '' ){
                                var string_plus = '<input type="radio" name="trading_name" class="trading_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                                html_trading_name += string_plus;
                            }
                        });
                    }
                    $('#text_trading_name').html('');
                    $('#text_trading_name').append(html_trading_name);
                }else {
                    alert('ABN is wrong !');
                }
            }
        });
    });

    $(document).on('click','.Business_name',function(){
        var key_change = $(this).val();
        if(key_change != '' && $("input[name='trading_name']").is(':checked') != true){
            var entry_value = $("#entity_name_c").val().split(" T/A ");
            if(entry_value.length > 1){
                $("#entity_name_c").val(entry_value[0] + " T/A " + key_change);
            }else{
                $("#entity_name_c").val($("#entity_name_c").val() + " T/A " + key_change);
            }
        }
        
        if($('#business_name_data_c').val() !== ''){
            var render_data_business = JSON.parse($('#business_name_data_c').val());
            if(typeof(render_data_business) !== 'undefined' ){             
                $.each(render_data_business,function(key,value){
                    if(key !== '' || value[0] !== '' ){
                        if(key ==  key_change){
                            value[1] = true;
                            //thienpb code - lookup abn
                            $("#abn_lookup_c").text(''); 
                            var url_lookupABN = '/index.php?entryPoint=lookupABN&text_search='+key;
                            $.ajax({
                                url:url_lookupABN,
                                type: 'GET',
                                success: function (data) {
                                    data = data.slice(0, -1).split(',').join('\n');
                                    $("#abn_lookup_c").text(data);
                                }
                            });
                        }else {
                            value[1] = false;
                        }
                    }
                });      
            }

            $('#business_name_data_c').val(JSON.stringify(render_data_business));
        }
    });

    // For trading name 
    
    $('#trading_name_data_c').hide();
    $('#trading_name_data_c').parent().append('<div id="text_trading_name"></div>');
    if($('#trading_name_data_c').val() !== '' && typeof($('#trading_name_data_c').val()) !== 'undefined'){
        var render_data_trading = JSON.parse($('#trading_name_data_c').val());
        var html_trading_name = '';
        if(typeof(render_data_trading) !== 'undefined' ){
            $.each(render_data_trading,function(key,value){
                if(key !== '' || value[0] !== '' ){
                    if(value[1]){
                        var string_plus = '<input type="radio" checked name="trading_name" class="trading_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                    }else {
                        var string_plus = '<input type="radio" name="trading_name" class="trading_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                    }
                   
                    html_trading_name += string_plus;
                }
            });
        }
        $('#text_trading_name').html('');
        $('#text_trading_name').append(html_trading_name);
    }

    $(document).on('click','.trading_name',function(){
        var key_change = $(this).val();
        if(key_change != ''){
            var entry_value = $("#entity_name_c").val().split(" T/A ");
            if(entry_value.length > 1){
                $("#entity_name_c").val(entry_value[0] + " T/A " + key_change);
            }else{
                $("#entity_name_c").val($("#entity_name_c").val() + " T/A " + key_change);
            }
        }
        if($('#trading_name_data_c').val() !== ''){
            var render_data_trading = JSON.parse($('#trading_name_data_c').val());
            if(typeof(render_data_trading) !== 'undefined' ){             
                $.each(render_data_trading,function(key,value){
                    if(key !== '' || value[0] !== '' ){
                        if(key ==  key_change){
                            value[1] = true;
                        }else {
                            value[1] = false;
                        }
                    }
                });      
            }
            $('#trading_name_data_c').val(JSON.stringify(render_data_trading));
        }
    });
});

// dung code - function event daikin wifi checkbox
function daikinWifiController(param){
    var i = 0;
    //var check_status = true;
    $("input[name^=product_product_id]").each(function(){
        var product_id = $(this).val();
        if(product_id == '4b5032ca-8aab-60eb-2837-56b82333ea38' && $('#product_body'+i).css('display') !== 'none' ){
            check_status =false; 
            var quantity_DaikinWifiController = parseInt($(this).parent().parent().find('td:nth-child(1) input.product_qty').val());
            if(param){
                quantity_DaikinWifiController +=1;
                $(this).parent().parent().find('td:nth-child(1) input.product_qty').val(quantity_DaikinWifiController);             
            }else {
                if(quantity_DaikinWifiController > 1) {
                    quantity_DaikinWifiController -=1;
                    $(this).parent().parent().find('td:nth-child(1) input.product_qty').val(quantity_DaikinWifiController);              
                }else {
                    markLineDeleted(i,"product_");
                }
            }
        } else if(product_id == '4b5032ca-8aab-60eb-2837-56b82333ea38' && $('#product_body'+i).css('display') == 'none') {
            //check_status = false;
            if(param){
                $('#product_body'+i).css('display','');
            }        
        }  
        i ++;
    });
    // if(check_status) {
    //     if(param){
    //         var k = insertProductLine("product_group0", "0");
    //         var popupReplyData = {}; 
    //         popupReplyData.form_name = "EditView";
    //         var name_to_value_array = {};
    //         name_to_value_array["product_currency"+k] = '-99';
    //         name_to_value_array["product_item_description"+k] = "The Daikin WiFi Controller BRP072A42 enables you to control your Daikin split system air conditioner from anywhere you have an internet connection.\nDaikin WiFi controller lets you turn on and set the temperature in advance of your arrival\nFree iOS & Android apps available for download through Daikin D-Mobile App\nWeight: 0.15 kg; Dimensions: 14.5 x 8 x 8 cm";
    //         name_to_value_array["product_name"+k] = "Daikin WiFi Controller";
    //         name_to_value_array["product_part_number"+k] = "BRP072A42";
    //         name_to_value_array["product_product_cost_price"+k] = "101.200000";
        
    //         name_to_value_array["product_product_id"+k] = "4b5032ca-8aab-60eb-2837-56b82333ea38";
    //         name_to_value_array["product_product_list_price"+k] = "136.360000";
    //         popupReplyData["name_to_value_array"] = name_to_value_array;
    //         setProductReturn(popupReplyData);
    //         $("#product_part_number"+k).trigger("change"); 
    //     }
    // }

}; //tuan-code ------
$(document).ready(function(){
    if(module_sugar_grp1 == 'AOS_Invoices'){
        $('#sanden_tank_serial_c').after("<button value='Add More' id='addMoreSandenTankSerial' type='button' class='button'><i class='glyphicon glyphicon-plus'></i></button>")
        $('#sanden_hp_serial_c').after("<button value='Add More' id='addMoreSandenHPSerial' type='button' class='button'><i class='glyphicon glyphicon-plus'></i></button>")
        $('#sanden_tank_serial_c').hide();
        $('#sanden_hp_serial_c').hide();
        $('#addMoreSandenTankSerial').click(function(){
            var count_element = $(".sanden_tank_serial_more").length;
            $('#addMoreSandenTankSerial').parent().append("<div id='add_more_tank_serial_"+count_element+"'><input type='text'  name='add_sanden_tank_serial' id='add_sanden_tank_serial_"+count_element+"' style='width:300px' size='30' maxlength='1000'  title='' class='sanden_tank_serial_more ui-autocomplete-input' autocomplete='off'><button  type='button' value='"+count_element+"' id='remove_add_sanden_tank_serial_"+count_element+"' class='button remove_senden_tank'><i class='glyphicon glyphicon-minus'></i></button></div>").find('#add_sanden_tank_serial_'+count_element).autocomplete({
                source: function( request, response ) {
                    var $serial_number_string = $("#add_sanden_tank_serial_"+count_element).val().trim();
                    var $serial_number_array = $serial_number_string.split(",");
                    var $serial_number = trim( $serial_number_array[$serial_number_array.length - 1]);
                    check_serial_exist($serial_number,request, response,"add_sanden_tank_serial_"+count_element);
                },
                select: function (event, ui) {
                    var terms = this.value.split(/,\s*/);
                    terms.pop();
                    terms.push(ui.item.value);
                    this.value = terms;
                    show_link_stock_new(this.value,'remove_add_sanden_tank_serial_'+count_element);
                    return false;
                }
            });
        });

        $(document).on('change',".sanden_tank_serial_more",function(e){
            AddorRemoveSandenTank();
        });

        var MoreSandenTankSerial = $('#sanden_tank_serial_c').val()
        if( MoreSandenTankSerial != "" ){
            var number_serial = MoreSandenTankSerial.replace(/\s/g, '');
            var ress = number_serial.split(',');
            var lenght_Ress = ress.length;
            for ( var i = 0 ; i < lenght_Ress ; i++ ) {
                $('#addMoreSandenTankSerial').parent().append("<div id='add_more_tank_serial_"+i+"'><input value='"+ress[i]+"' type='text' name='add_sanden_tank_serial' style='width:300px' id='add_sanden_tank_serial_"+i+"' size='30' maxlength='255'  title='' class='sanden_tank_serial_more ui-autocomplete-input ui-autocomplete-loading' autocomplete='off'><button  type='button' value='"+i+"' id='remove_add_sanden_tank_serial_"+i+"' class='button remove_senden_tank'><i class='glyphicon glyphicon-minus'></i></button></div>");
                var terms = $('#add_sanden_tank_serial_'+i).val().split(/,\s*/);
                this.value = terms;
                show_link_stock_new( this.value,'remove_add_sanden_tank_serial_'+i);
            }
        }

        $(document).on("click",'.remove_senden_tank',function(e){
            $('#add_more_tank_serial_'+$(this).val()).remove();
            AddorRemoveSandenTank();
        })
    
        //HP serial -----
        $('#addMoreSandenHPSerial').click(function(){
            var count_element = $(".sanden_hp_serial_more").length;
            $('#addMoreSandenHPSerial').parent().append("<div id='add_more_hp_serial_"+count_element+"'><input type='text'  name='add_sanden_hp_serial' id='add_sanden_hp_serial_"+count_element+"' style='width:300px' size='30' maxlength='255'  title='' class='sanden_hp_serial_more ui-autocomplete-input' autocomplete='off'><button  type='button' value='"+count_element+"' id='remove_add_sanden_hp_serial_"+count_element+"' class='button remove_senden_hp'><i class='glyphicon glyphicon-minus'></i></button></div>").find('#add_sanden_hp_serial_'+count_element).autocomplete({
                source: function( request, response ) {
                    var $serial_number_string = $("#add_sanden_hp_serial_"+count_element).val().trim();
                    var $serial_number_array = $serial_number_string.split(",");
                    var $serial_number = trim( $serial_number_array[$serial_number_array.length - 1]);
                    check_serial_exist($serial_number,request, response,"add_sanden_hp_serial_"+count_element);
                },
                select: function (event, ui) {
                    var terms = this.value.split(/,\s*/);
                    terms.pop();
                    terms.push(ui.item.value);
                    this.value = terms;
                    show_link_stock_new(this.value,'remove_add_sanden_hp_serial_'+count_element);
                    return false;
                }
            });
        });

        $(document).on('change',".sanden_hp_serial_more",function(e){
            AddorRemoveSandenHp();
        });

        var MoreSandenHpSerial = $('#sanden_hp_serial_c').val()
        if( MoreSandenHpSerial != "" ){
            var number_serial = MoreSandenHpSerial.replace(/\s/g, '');
            var ress = number_serial.split(',');
            var lenght_Ress = ress.length;
            for ( var i = 0 ; i < lenght_Ress ; i++ ) {
                $('#addMoreSandenHPSerial').parent().append("<div id='add_more_hp_serial_"+i+"'><input value='"+ress[i]+"' type='text' name='add_sanden_hp_serial' style='width:300px' id='add_sanden_hp_serial_"+i+"' size='30' maxlength='255'  title='' class='sanden_hp_serial_more ui-autocomplete-input' autocomplete='off'><button  type='button' value='"+i+"' id='remove_add_sanden_hp_serial_"+i+"' class='button remove_senden_hp'><i class='glyphicon glyphicon-minus'></i></button></div>");
                var terms = $('#add_sanden_hp_serial_'+i).val().split(/,\s*/);
                this.value = terms;
                show_link_stock_new( this.value,'remove_add_sanden_hp_serial_'+i);
            }
        }

        $(document).on("click",'.remove_senden_hp',function(e){
            $('#add_more_hp_serial_'+$(this).val()).remove();
            AddorRemoveSandenHp();
        })
    }

    //define function
    function show_link_stock_new(value,field_id){
        $.ajax({
            url : "?entryPoint=getStockItemBySerialNumber&serial_numbers="+value,
            success: function (data) {
                $("#_"+field_id).remove();
                $("#"+field_id).after("<div id='_"+field_id+"'>"+data+"</div>");
            }
        });
    }       
    function check_serial_exist(serialNumber,request, response,fieldID){
        $.ajax({
            url:"?entryPoint=show_sr_number&id="+serialNumber,
            success: function(data)
            {
                if(data == '' || typeof data == 'undefined')return;
                var suggest =[];
                var jsonObject = JSON.parse(data);
                for (i = 0; i < jsonObject.length; i++) {
                    suggest.push(jsonObject[i].serial_number);
                }
                response($.ui.autocomplete.filter(
                    suggest, request.term.split(/,\s*/).pop()
                ));

                if(suggest.length == 0){
                    $("#"+fieldID).before('<span style="color:red" id="error_'+fieldID+'">This serial number is invoiced or wrong.</span>');
                    $("#"+fieldID).val('');
                    $("#"+fieldID).focus();
                }else{
                    $('#error_'+fieldID).remove();
                }
                
            },
            error: function(response){
                console.log("Fail");
            },
        });
    }
    function AddorRemoveSandenTank(){
        var value = "";
        $("#sanden_tank_serial_c").val('');
        $(".sanden_tank_serial_more").each( function(e){
            value += $(this).val()+', ';
        });
        $("#sanden_tank_serial_c").val( value.slice(0, -2) );
        $("#sanden_tank_serial_c").trigger('change');
    }
     
    function AddorRemoveSandenHp(){
        var value = "";
        $("#sanden_hp_serial_c").val('');
        $(".sanden_hp_serial_more").each( function(e){
            value += $(this).val()+', ';
        });
        $("#sanden_hp_serial_c").val( value.slice(0, -2) );
        $("#sanden_hp_serial_c").trigger('change');
    }
    //end define function

    // ---------------------
    $(document).on('change','input[name="wifi"]',function(){
        if($(this).is(':checked')){
            daikinWifiController(true);
        }else {
            daikinWifiController(false);
        }
    })
    $(document).on('click','button[onclick="javascript:delDaikinTextRow(this)"]',function(){
        if($(this).parent().parent().find('input[name="wifi"]').is(':checked')){
            daikinWifiController(false);
        }  
    });
    $('#daikininfo').find('button[onclick="javascript:addDaikinTextRow()"]').on('click',function(){
        daikinWifiController(true);
    });
})

//end dung code - function event daikin wifi checkbox

//dung code - function upload status GEO
$(document).ready(function(){
    $('#stc_aggregator_serial_c').parent().siblings('.label').append('<br> <button type="button" class="button primary" id="getStatusGeo"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Get Geo Status</button>');

    $('#getStatusGeo').click(function(){
        var stc_seri =  $('#stc_aggregator_serial_c').val();
        if(stc_seri == ''){
            stc_seri = $('#stc_aggregator_serial_2_c').val();
            // if(stc_seri == ''){
            //     stc_seri = $('#stc_aggregator_c').val();
            // }
        }
        var url_getStatusGEO = '/index.php?entryPoint=getStatusGEO&stc_seri=' +stc_seri;
        $.ajax({
            url:url_getStatusGEO,
            type: 'GET',
            success: function (data) {
                if((data.trim() == 'complete' ||data.trim() == 'received') &&  ($('#status_geo_c').val() != data) ){
                    $("#product_group0").find('input.product_part_number ').each(function(){
                        if(($(this).val().indexOf("SV_SHWR") >= 0)){   
                            $("#status").val('Solar_VIC_Unpaid');
                        }
                    });
              
                }
                $('#status_geo_c').val(data);            
            }
        })
    })

    //dung code -- function get status order SAM
    $('#solargain_order_status_c').parent().siblings('.label').append('<br> <button type="button" class="button primary" id="getStatusSAM"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Get Order Status</button>');

    $('#getStatusSAM').click(function(){
        var url_getStatusSAM = '/index.php?entryPoint=getStatusSAM&number_order=' + $("#solargain_invoices_number_c").val();
        $.ajax({
            url:url_getStatusSAM,
            success: function (data) {
                $('#solargain_order_status_c').val(data);            
            }
        })
    })
})
$(document).ready(function(){
    //dung code -- button "Send Customer Install date"
    var record_id = $("input[name='record']").val();
    if(module_sugar_grp1 == 'AOS_Invoices') {
        // From detail to edit
        $("#CANCEL").after(
            ' <button type="button" id="EMAIL_GET_ROT_Agreement" \
             data-email-type="EMAIL_GET_ROT_Agreement" onclick="$(document).openComposeViewModalDefault(this);" data-module="Invoices" data-record-id="'+ record_id +'" \
             class="button" title="EMAIL GET ROT Agreement" >EMAIL GET ROT Agreement<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );
        $("#CANCEL").after(
            ' <button type="button" id="Send_Customer_Install_date" \
             data-email-type="Send_Customer_Install_date" onclick="$(document).openComposeViewModal(this);" data-module="Invoices" data-record-id="'+ record_id +'" \
             class="button Send_Customer_Install_date" title="Send_Customer_Install_date" >Send Customer Install Date<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );
        $("#CANCEL").parent().append(
            ' <button type="button" id="invoice_payment_reminder" \
             data-email-type="invoice_payment_reminder" onclick="$(document).openComposeViewModal(this);" data-module="Invoices" data-record-id="'+ record_id +'" \
             class="button invoice_payment_reminder" title="INVOICE PAYMENT REMINDER" >INVOICE PAYMENT REMINDER<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );
        //button US7 TIPS 
        $("#CANCEL").parent().append(
            ' <button type="button" id="email_us7_tips" data-email-address=""\
             data-email-type="us7_tips" onclick="$(document).openComposeViewModal_US7_and_Sanden_Tips(this);" data-module="Contacts" data-record-id="'+ $("input[name='billing_contact_id']").val() +'" data-module-name="'+ $("input[name='billing_contact']").val() +'" \
             class="button" title="US7 Tips" >US7 Tips<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );  
         //button Sanden TIPS 
         $("#CANCEL").parent().append(
            ' <button type="button" id="email_sanden_tips" data-email-address=""\
             data-email-type="sanden_tips" onclick="popupSandenProduct(this);" data-module="Contacts" data-record-id="'+ $("input[name='billing_contact_id']").val() +'" data-module-name="'+ $("input[name='billing_contact']").val() +'" \
             class="button" title="Sanden Tips" >Sanden Tips<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );    
        //Button Email promo code
         $("#CANCEL").parent().append(
            ' <button type="button" id="email_promo_code_methven" \
             data-email-type="email_promo_code_methven" onclick="promo_code_methven(this);" data-module="AOS_Invoices" data-module-name="'+$('#name').val()+'" data-record-id="'+ record_id +'" \
             class="button email_promo_code_methven" title="email_promo_code_methven" >Free Methven with Sanden<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );  
        //Button SA REPS to YESS 
        $("#CANCEL").parent().append(
            ' <button type="button" id="email_sa_reps_to_yes" \
                style="background:#090979;" \
                 data-email-type="email_sa_reps_to_yess" onclick="$(document).openComposeViewModalDefault(this);" data-module="AOS_Invoices" data-module-name="'+$('#name').val()+'" data-record-id="'+ record_id +'" \
                class="button email_sa_reps_to_yess" title="email_sa_reps_to_yess" ><span class="glyphicon glyphicon-envelope"></span>SA REPS to YES<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );    

        //Button SA REPS CUSTOMER EMAIL >> https://trello.com/c/ZoEBdHbg/3128-sa-reps-advise-customer-paperwork-ensure-email-parsing-is-working-sms-template-is-parsing-correctly?menu=filter&filter=*
        $("#CANCEL").parent().append(
            ' <button type="button" id="email_sa_reps_customer" \
                style="background:#090979;" \
                    data-email-type="email_sa_reps_customer" onclick="$(document).openComposeViewModalDefault(this);" data-module="AOS_Invoices" data-module-name="'+$('#name').val()+'" data-record-id="'+ record_id +'" \
                class="button email_sa_reps_customer" title="email_sa_reps_customer" ><span class="glyphicon glyphicon-envelope"></span>SA REPS CUSTOMER EMAIL<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );     
        //$("#CANCEL").parent().append('<input type="button" id="client_warranty_registration" value="Email Client Warranty" class="button primary" data-email-address-id="'+$('#billing_contact_id').val()+'" data-email-type="client_warranty_registration" onclick="$(document).openComposeViewModal_reupload(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#billing_contact').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" /></li>');    
        $("#CANCEL").parent().append('<input type="button" id="client_reuploads_photo" value="EMAIL CLIENT WARRANTY + PHOTOS" class="button primary" data-email-address-id="'+$('#billing_contact_id').val()+'" data-email-type="client_reuploads_photo" onclick="$(document).openComposeViewModal_reupload(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#billing_contact').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" /></li>');
        // From detail to edit
        $('#CANCEL').parent().append('<button type="button" id="better_sg_solar_date" value="BETTER SG SOLAR DATE" class="button primary" data-email-type="better_sg_solar_date" onclick="$(document).openComposeViewModalDefault(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#billing_contact_id').val()+'"  data-record-id="'+ record_id +'">\
                BETTER SG SOLAR DATE<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        $('#CANCEL').parent().append('<input type="button" id="delivery_coming" value="Delivery coming" class="button primary" data-email-type="delivery_coming" onclick="$(document).openComposeViewModalDefault(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#billing_contact_id').val()+'"  data-record-id="'+ record_id +'" />');
        $('#CANCEL').parent().append('<input type="button" id="delivery_schedule" value="Delivery Schedule" class="button primary" data-email-type="delivery_schedule" onclick="$(document).openComposeViewModalDefault(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#billing_contact_id').val()+'"  data-record-id="'+ record_id +'" />');

    }else if(module_sugar_grp1 == 'AOS_Quotes'){
        $("#CANCEL").after(
            ' <button type="button" id="Advise_Install_Date" \
             data-email-type="Advise_Install_Date" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Quotes" data-record-id="'+ record_id +'" \
             class="button Advise_Install_Date" title="Advise_Install_Date" >Advise Customer Install Date<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );
    }
 
    // dung code -- create logic button cancel
    if(module_sugar_grp1 == 'AOS_Invoices'){
        SUGAR.ButtonCancel = function (elem)
        {
            var url = '/index.php?module=AOS_Invoices&action=DetailView&record=' + $('input[name="record"]').val();
            $(location).attr('href', url);
        }
        if($('input[name="record"]').val() != ''){
            $(document).find("input[id='CANCEL']").hide();
            $('input[id="SAVE"]').next().after(
                '&nbsp;<button type="button" id="new_cancel" class="button" title="Cancel" onClick="SUGAR.ButtonCancel(this);">Cancel</button>'
            );
        }
    }else if(module_sugar_grp1 == 'AOS_Quotes') {
        SUGAR.ButtonCancel = function (elem)
        {
            var url = '/index.php?module=AOS_Quotes&action=DetailView&record=' + $('input[name="record"]').val();
            $(location).attr('href', url);
        }
        if($('input[name="record"]').val() != ''){
            $(document).find("input[id='CANCEL']").hide();
            $('input[id="SAVE"]').next().after(
                '&nbsp;<button type="button" id="new_cancel" class="button" title="Cancel" onClick="SUGAR.ButtonCancel(this);">Cancel</button>'
            );
        }
    }

    $('div[field="payments_c"]').before('<b style="color:red"> Do not manually enter payments </b>');

    //tu-code
    $("#fileupload").prepend("<button id='download_iv_pdf' class ='button' title='Get File PDF Invoices'>Get Customer Agreement PDF</button><div id='result'></div>");
    $("#download_iv_pdf").click(function(event){
        $('#get_all_files_invoice span.glyphicon-refresh').removeClass('hidden');
        if($('#state_c').val() != 'VIC' && $("#old_tank_fuel_c").val() != "electric_storage"){
            var contact = $("#billing_contact").val();
            var street =$("#billing_address_street").val();
            var city = $("#install_address_city_c").val();
            var state = $("#install_address_state_c").val();
            var country = $("#install_address_country_c").val();
            var account = $("#billing_account").val();
            var id = $('input[name="record"]').val();
            $.ajax({
                url: 'index.php?entryPoint=CustomPDF&contact='+contact
                +'&account='+account
                +'&id='+id
                +'&street='+street
                +'&city='+city
                +'&state='+state
                +'&country='+country,
                success: function(data){                
                    $(".files").empty();                
                    $.ajax({
                        url: $('#fileupload').fileupload('option', 'url'),
                        dataType: 'json',
                        context: $('#fileupload')[0]
                    }).always(function () {
                        $(this).removeClass('fileupload-processing');
            
                    }).done(function (result) {
                        $('button[type="resize_all"]').trigger('click');
                        $(this).fileupload('option', 'done')
                            .call(this, $.Event('done'), {result: result});
                    });

                    $('#get_all_files_invoice span.glyphicon-refresh').addClass('hidden');
                },
            });
       // }
         
        }else{
            alert("error");
        }
        event.preventDefault();
    });
    //
    
    if(module_sugar_grp1 == 'AOS_Invoices'){
        $("#fileupload").prepend('<button type="button" id="get_all_files_invoice" class="button primary" title="Get All File"> Get All Files<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        $("#get_all_files_invoice").click(function(){
            $('#get_all_files_invoice span.glyphicon-refresh').removeClass('hidden');
            var billing_account_id = $("#billing_account_id").val();
            var billing_contact_id = $("#billing_contact_id").val();
            var installation_pictures_c = $("input[name='installation_pictures_c']").val();
            if(billing_account_id == '86516ff6-0cd7-9ccc-4373-58ad559a8e12'){
                alert("This is Sanden 's account.we ignored this account!");
                $('#get_all_files_invoice span.glyphicon-refresh').addClass('hidden');
                return false;
            }
            $.ajax({
                url: "?entryPoint=getAllFilesAttachments&billing_account_id="+billing_account_id+"&billing_contact_id="+billing_contact_id+"&opportunity_id=&pre_install_photos_c="+installation_pictures_c,
                success: function(data)
                {
                    $(".files").empty();                
                    $.ajax({
                        url: $('#fileupload').fileupload('option', 'url'),
                        dataType: 'json',
                        context: $('#fileupload')[0]
                    }).always(function () {
                        $(this).removeClass('fileupload-processing');
            
                    }).done(function (result) {
                        $.ajax({
                            url: "?entryPoint=getAllFilesMessageApp&invoice_id="+$("input[name='record']").val()+"&installation_pictures_c="+installation_pictures_c,
                            success: function(data)
                            {
                                $(".files").empty();                
                                $.ajax({
                                    url: $('#fileupload').fileupload('option', 'url'),
                                    dataType: 'json',
                                    context: $('#fileupload')[0]
                                }).always(function () {
                                    $(this).removeClass('fileupload-processing');
                        
                                }).done(function (result) {
                                    $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                                    $('#get_all_files_sms span.glyphicon-refresh').addClass('hidden');
                                });
                            }
                        });  
                    });
                },
                error: function(response){},
            });
        });

        //thienpb code
        $("#get_all_files_invoice").after('<button type="button" style="margin-left:2px" id="get_files_from_s3_invoice" class="button primary" title="Get Files From S3">Get Files From S3<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        $("#get_files_from_s3_invoice").click(function(){
            $('#get_files_from_s3 span.glyphicon-refresh').removeClass('hidden');
            var quote_id =  $('input[name="record"]').val();
            if(quote_id == ''){
                alert('Not have number ID Quotes. Please save and edit before.');
                $('#get_files_from_s3_invoice span.glyphicon-refresh').addClass('hidden');
                $("input[name='button']").first().focus();
                return;
            }
            var pre_install_photos_c = $("input[name='installation_pictures_c']").val();

            var url_download = "https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/s3.php?myDirectoryName="+pre_install_photos_c;
            $.ajax({
                url: url_download,
                async:false,
                success: function(data){
                    $(".files").empty();
                    $.ajax({
                        url: $('#fileupload').fileupload('option', 'url'),
                        dataType: 'json',
                        context: $('#fileupload')[0],
                    }).always(function () {
                        $(this).removeClass('fileupload-processing');
                    }).done(function (result) {
                        $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                        $('#get_files_from_s3_invoice span.glyphicon-refresh').addClass('hidden');
                    });           
                }
            })
        });
         // get case study
         $("#get_files_from_s3_invoice").after('<button type="button" id="get_case_study_photos" class="button primary" title="get case study photos">GET SG PV CASE STUDY PHOTOS<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        //  $("#get_files_from_s3_invoice").after('<input type="button" id="client_reuploads_photo" value="Email Client Reuploads Photo" class="button primary" data-email-address-id="'+$('#billing_contact_id').val()+'" data-email-type="client_reuploads_photo" onclick="$(document).openComposeViewModal_reupload(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#billing_contact').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" /></li>');
         $("#get_files_from_s3_invoice").after('<button type="button" id="send_photo_to_electrician" class="button primary send_photo_to_installer" data-installer="electrician" title="send photo to installer">SEND MAIL PHOTOS TO ELECTRICIAN<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
         $("#get_files_from_s3_invoice").after('<button type="button" id="send_photo_to_plumber" class="button primary send_photo_to_installer" data-installer="plumber" title="send photo to installer">SEND MAIL PHOTOS TO PLUMBER<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');

         $("#get_case_study_photos").click(function(){
             $('#get_case_study_photos span.glyphicon-refresh').removeClass('hidden');
             var ordernumber =  $('#solargain_invoices_number_c').val();
             if(ordernumber == ''){
                 alert('Not have number Order. Please save and edit before.');
                 $('#get_case_study_photos span.glyphicon-refresh').addClass('hidden');
                 $("#solargain_invoices_number_c").first().focus();
                 return;
             }
             var generateUUID = $('input[name="installation_pictures_c"]').val();
             var invoice_number = $("div[field='number']").text().trim();  
             $.ajax({
                url:  "/index.php?entryPoint=APIGetPhotoCaseStudy&order_num="+ordernumber+"&generateUUID="+ generateUUID+"&numberInv="+invoice_number,
                success: function (data) {
                    if( data == "success"){
                        $(".files").empty();
                        $.ajax({
                            url: $('#fileupload').fileupload('option', 'url'),
                            dataType: 'json',
                            context: $('#fileupload')[0],
                        }).always(function () {
                            $(this).removeClass('fileupload-processing');
                        }).done(function (result) {
                            $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                            $('#get_case_study_photos span.glyphicon-refresh').addClass('hidden');
                        });   
                    }else {
                        alert( "No photos found !");
                        $('#get_case_study_photos span.glyphicon-refresh').addClass('hidden');
                    }

                }
            })      
         });

         $(".send_photo_to_installer").click(function(){
             var installer =  $(this).attr('data-installer')
             var installer_id;
             var id = $(this).attr('id');
            $('#'+ id +' span.glyphicon-refresh').removeClass('hidden');
            if( installer == "plumber"){
                installer_id =  $('#account_id1_c').val()+"&installer_name=Plumber";
                if(installer_id == ''){
                    alert('Not have Plumber.');
                    $('input[name="plumber_c"]').focus();
                    $('#'+id+' span.glyphicon-refresh').addClass('hidden');
                    return;
                }
            }else {
                installer_id =  $('#account_id_c').val()+"&installer_name=Electrician";
                if(installer_id == ''){
                    alert('Not have Electrician.');
                    $('input[name="electrician_c"]').focus();
                    $('#'+ id+' span.glyphicon-refresh').addClass('hidden');
                    return;
                }
            }
             var record = $("input[name='record']").val();
             var billing_account_id = $('#billing_account_id').val();

             var generateUUID = $('input[name="installation_pictures_c"]').val();
            $.ajax({
                url:  "/index.php?entryPoint=APISendPhotoInstallToInstaller&invoice_id="+record+"&billing_account_id="+billing_account_id+"&installer_id="+installer_id+"&generateUUID="+ generateUUID,
                success: function (data) {
                    alert (data);
                    $('#'+ id +' span.glyphicon-refresh').addClass('hidden');
                }
            })      
         })
         //button create generate REPS_WH1_PDF
         $("#get_all_files_invoice").after('<button type="button" style="margin-left:2px; background: #00b2e2;" id="Generate_REPS_WH1_PDF" class="button primary" title="Generate REPS WH1 PDF"><span class="glyphicon glyphicon-file"></span>SA REPS ACTIVITY RECORD</button>');
         $("#Generate_REPS_WH1_PDF").click(function(){
            Ajax_Generate_File_PDF_REPS('');
         })

        //button create generate Generate_REPS_Information_Statement
        $("#get_all_files_invoice").after('<button type="button" style="margin-left:2px; background: #FF8800;" id="Generate_REPS_Information_Statement" class="button primary" title="Generate REPS Inforamtion Statement"><span class="glyphicon glyphicon-file"></span>SA REPS INFORMATION STATEMENT</button>');
        $("#Generate_REPS_Information_Statement").click(function(){
            Ajax_Generate_File_PDF_REPS('REPS_Infor_State');
        })

        //button create generate Generate_Solar_Hot_Water_Rebate
        $("#get_all_files_invoice").after('<button type="button" style="margin-left:2px; background: #F9B85D;" id="Generate_Solar_Hot_Water_Rebate" class="button primary" title="Generate Solar Hot Water Rebate"><span class="glyphicon glyphicon-file"></span>Generate Solar Hot Water Rebate</button>');
        $("#Generate_Solar_Hot_Water_Rebate").click(function(){
            Ajax_Generate_File_PDF_REPS('Solar_Hot_Water_Rebate');
        })

        //VUT - S - Button create Generate_Solar_Hot_Water_Proof_Install_Rebate
        $("#get_all_files_invoice").after('<button type="button" style="margin-left:2px; background: #ff9900;" id="Generate_Solar_Hot_Water_Proof_Install_Rebate" class="button primary" title="Create Proof of Installation"><span class="glyphicon glyphicon-file"></span>Create Proof of Installation</button>');
        $("#Generate_Solar_Hot_Water_Proof_Install_Rebate").click(function(){
            Ajax_Generate_File_PDF_REPS('Solar_Hot_Water_Proof');
        })
        //VUT - E - Button create Generate_Solar_Hot_Water_Proof_Install_Rebate
         //VUT-S-Get all files sms
         $("#get_files_from_s3_invoice").after('<button type="button" id="get_all_files_sms" class="button primary" title="Get all files from SMS">GET ALL FILES FROM SMS<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
         $('#get_all_files_sms').click(function(){
            // debugger;
            var installation_pictures_c = $('input[name="installation_pictures_c"]').val();
            if (installation_pictures_c != '') {
                $.ajax({
                    url: "?entryPoint=getAllFilesMessageApp&invoice_id="+$("input[name='record']").val()+"&installation_pictures_c="+installation_pictures_c,
                    success: function(data)
                    {
                        // debugger;
                        $(".files").empty();                
                        $.ajax({
                            url: $('#fileupload').fileupload('option', 'url'),
                            dataType: 'json',
                            context: $('#fileupload')[0]
                        }).always(function () {
                            $(this).removeClass('fileupload-processing');
                
                        }).done(function (result) {
                            $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                            $('#get_all_files_sms span.glyphicon-refresh').addClass('hidden');
                        });
                    }
                });
            }
        });
         //VUT-E-Get all files sms
    }
    

    //dung code - get address by field postcode or city
    $("#billing_address_city ,#billing_address_postalcode").autocomplete({
        source: function( request, response ) {
            console.log(request["term"]);
            Math.floor((Math.random() * 3) + 1);
            if(request["term"].length > 3){
                $.ajax({
                    url: "/index.php?entryPoint=customGetAddress&postcode_city="+request["term"],
                    type: 'GET',
                    success: function(data)
                    {
                        var suggest =[];
                        var jsonObject = data.split('\n');
                        for (i = 0; i < jsonObject.length; i++) {
                            var array_child = jsonObject[i].split('|');
                            if(array_child[0] !== ''){
                                suggest[i] = array_child[2] +',' +array_child[3] +',' +array_child[1];
                            }
                        }
                        response(suggest);
                    },
                    error: function(response){console.log("Fail");},
                });
            }
        },
        select: function( event, ui ) {
            var address = ui.item.value;
            var array_value =  address.split(",");
            $("#billing_address_city").val(array_value[0]);
            $("#billing_address_state").val(array_value[1]);
            $("#billing_address_postalcode").val(array_value[2]);
            return false;
        },
    });

    //thienpb code - plumping check
        //dung comment --- not use this function
        $("#practitioner_verification_c").parents('.edit-view-row-item').hide();
    // $("div[field='plumber_c']").after("<br><b>Check Plumber : </b><input type ='checkbox' name='check_plumber' id='check_plumber'>");
    // $("#check_plumber").change(function(){
    //     if($(this).is(':checked') == true){
    //         var uxsurname = $("#plumber_c").val();
    //         $("#practitioner_verification_c").text('');
    //         if(uxsurname != ''){
    //             $.ajax({
    //                 url: '?entryPoint=checkPlumberInvoice&uxsurname='+uxsurname,
    //                 success: function(data)
    //                 {
    //                     $("#practitioner_verification_c").text(data);
    //                 }
    //             });
    //         }else{
    //             $("#practitioner_verification_c").text('');
    //             alert("Plumber is required. Please select Plumber");
    //         }
            
    //     }
    // });

    //thienpb code - check sg order

    $('#solargain_invoices_number_c').after('<br><button type="button" class="button primary" id="check_sg_order"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Check</button></span>');
    $('#check_sg_order').click(function(){
        $('#check_sg_order span.glyphicon-refresh').removeClass('hidden');
        if($('#solargain_invoices_number_c').val() ==''){
            $('#check_sg_order span.glyphicon-refresh').addClass('hidden');
            alert('Solargain order number field empty!');
            $('#solargain_invoices_number_c').focus();
            return;
        }
        $.ajax({
            url: "?entryPoint=check_sg_order&sg_order_number="+ $('#solargain_invoices_number_c').val(),
            success: function(data)
            {
                if(data == '' || typeof data == 'undefined')return;
                var result = JSON.parse(data);
                var number = result['id'];
                //tu-code serch solargain order number
                if(number != ''){
                    $('#check_sg_order span.glyphicon-refresh').addClass('hidden');
                    var href = "<div class='check-solargain-oder-number'><a target = '_blank' href='/index.php?module=AOS_Invoices&action=EditView&record="+number+"'>Link Invoice</a></div>"
                    window.open('/index.php?module=AOS_Invoices&action=EditView&record='+number,'_blank');
                    $('#solargain_invoices_number_c').parent().append(href);
                }else {
                    $('#check_sg_order span.glyphicon-refresh').addClass('hidden');
                    alert(result['alert']);               
                }
            }
        });
    })

    //dung code -- auto fill invoice infomation from SAM
    $('#solargain_invoices_number_c').after('<br><button type="button" class="button primary" id="auto_fill_new_invoice"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Auto Fill And Update Invoice</button></span>');
    $('#auto_fill_new_invoice').click(function(){
        $('#auto_fill_new_invoice span.glyphicon-refresh').removeClass('hidden');
        $.ajax({
            url: "?entryPoint=CustomAutoFillDataFromSAM&sg_order_number="+ $('#solargain_invoices_number_c').val(),
            success: function(data)
            {
                $('#auto_fill_new_invoice span.glyphicon-refresh').addClass('hidden');
                if(data != ''){
                    var result = JSON.parse(data);
                    $('#name').val(result['name']);
                    $('#due_date').val(result['due_date']);
                    $('#assigned_user_name').val(result['assigned_user_name']);
                    $('#assigned_user_id').val(result['assigned_user_id']);
                    $('#billing_account').val(result['billing_account']);
                    $('#billing_account_id').val(result['billing_account_id']);
                    $('#billing_contact').val(result['billing_contact']);
                    $('#billing_contact_id').val(result['billing_contact_id']);
                    $('#billing_address_street').val(result['billing_address_street']);
                    $('#billing_address_city').val(result['billing_address_city']);
                    $('#billing_address_state').val(result['billing_address_state']);
                    $('#billing_address_postalcode').val(result['billing_address_postalcode']);
                    $('#shipping_address_street').val(result['billing_address_street']);
                    $('#shipping_address_city').val(result['billing_address_city']);
                    $('#shipping_address_state').val(result['billing_address_state']);
                    $('#shipping_address_postalcode').val(result['billing_address_postalcode']);
                    $('#install_address_c').val(result['install_address_c']);
                    $('#install_address_city_c').val(result['install_address_city_c']);
                    $('#install_address_postalcode_c').val(result['install_address_postalcode_c']);
                    $('#install_address_state_c').val(result['install_address_state_c']);
                    $('#installation_date_c').val(result['installation_date_c']);
                    $('#installation_date_c_date').val(result['due_date']);
                    $('#installation_date_c_hours').val('12');
                    $('#installation_date_c_minutes').val('00');
                    $('#site_contact_c').val(result['billing_contact']);
                    $('#contact_id3_c').val(result['billing_contact_id']);
                    $('#quote_type_c').val('quote_type_solar');
                    $("#invoice_type_c").val('Solar');
                    $('#solar_installer_c').val(result['solar_installer_c']);
                    $('#account_id5_c').val(result['account_id5_c']);
                    $('#solar_installer_contact_c').val(result['solar_installer_contact_c']);
                    $("#contact_id2_c").val(result['contact_id2_c']);
                    display_link_account_contact_installer_solar();
                    //logic add number quote and link quote
                    $('#quote_number').val(result['number_quote']);
                    if(result['id_quote'] != '') {
                        $("#link_quote").remove();
                        $('#quote_number').parent().append('<p id="link_quote"><a  href="index.php?module=AOS_Quotes&action=EditView&record='+result['id_quote']+'" target="_blank">Open Quote</a></p>');
                    }

                    //logic add group price
                    var total_price_SAM = result['total_sam'];
                    var list_price = (total_price_SAM*0.08*1.1)/1.1;
                    var tax = list_price*0.1;
                    total_price_SAM = parseFloat(total_price_SAM).formatMoney(2,',','.');
                    if($("#group_body0").length == 0){
                        insertGroup(0);
                    } 
                    if($("#product_body0").length == 0){
                        insertProductLine("product_group0","0");
                    } 
                    
                    $("#product_product_qty0").val('1');
                    $("#product_name0").val('Solar sales commission');
                    $("#product_product_id0").val('78b8b420-5003-8249-3dd3-5918dd4d0d06');
                    $("#product_part_number0").val('SolarSales');
                    $("#product_product_list_price0").val(list_price);
                    $("#product_product_discount0").val('0.00');
                    $("#product_discount0").val('Percentage');
                    $("#product_product_unit_price0").val(list_price);
                    $("#product_vat_amt0").val(tax);
                    $("#product_vat0").val('10.0');
                    $('#product_product_total_price0').val(list_price);
                    $("#product_item_description0").val($('#name').val() + '            Discounted \n Total Price: $' +total_price_SAM);
                    $("#product_description0").val('Total Price: $' +total_price_SAM);
                    $("#group0name").val("Solar PV Sales");
                    $("#sanden_model_c").val('SolarSales');
                    $("#product_product_list_price0").trigger("blur");

                    //logic update or create new meeting 
                    //case1 : new invoice
                        //step1: submit get id invoice
                    var invoice_id = $('input[name="record"]').val();
                    if( invoice_id == ''){
                        var ok = confirm('Invoices is not saved! Do you want Save and Reload Page?');
                        if(ok ==  true){
                            $("#EditView input[name='action']").val('Save');
                            $.ajax({
                                type: $("#EditView").attr('method'),
                                url: $("#EditView").attr('action'),
                                data: $("#EditView").serialize(),
                                async:false,
                                success: function (data) {
                                    var invoice_id_patt = /"record" value="(.*)"/g;
                                    invoice_id = invoice_id_patt.exec(data);
                                    if(invoice_id !== null && typeof invoice_id === 'object'){
                                        if(invoice_id[1] !='')  {
                                            invoice_id = invoice_id[1]
                                        }
                                    }
                                }
                            });
                        }else{
                            return;
                        }
                    }
                        //step2: create new meeting
                    if($("#meeting_c").val() == ''){
                        var record_id = invoice_id;
                        var installation_date = $("#installation_date_c").val();
                        if(installation_date !=''){
                            $.ajax({
                                url: "?entryPoint=mettingWithInstaller&record_id=" + record_id +"&installation_date="+installation_date,
                                context: document.body,
                                async: true
                            }).done(function (data) {
                                //step2: load new invoice 
                                SUGAR.ajaxUI.hideLoadingPanel();
                                $("#meeting_c").val(data);
                                if ($("#meeting_c").val() != "") {
                                    
                                    var href = "<a target='_blank' href='/index.php?module=Meetings&action=DetailView&record="+data+"'>Open Meeting</a>";
                                    $('#meeting_c').parent().siblings('.label').append(href);

                                }
                            });
                        }
                    }else{
                    //case2 : old invoice - it exist before
                        $.ajax({
                            url: '?entryPoint=CustomUpdateMeetingFromInvoice&meeting_id='+ $("#meeting_c").val() + '&installation_date_c='+$("#installation_date_c").val(),
                            success: function(data){
                                console.log(data);
                            }
                        })
                    }
                }
            }
        });
    })

    // button calculation with SG Commission  
    $('#sg_commissions_c').after('<br><button type="button" class="button primary" id="recalculation_sg"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Calculation Again</button></span>');
    $('#recalculation_sg').click(function(){
        $('#auto_fill_new_invoice span.glyphicon-refresh').removeClass('hidden');
        $.ajax({
            url: "?entryPoint=CustomAutoFillDataFromSAM&sg_order_number="+ $('#solargain_invoices_number_c').val(),
            success: function(data)
            {
                $('#auto_fill_new_invoice span.glyphicon-refresh').addClass('hidden');
                if(data != ''){
                    var result = JSON.parse(data);
                    
                    //logic add group price
                    var total_price_SAM = result['total_sam'];
                    var sg_commissions_per =  parseFloat($("#sg_commissions_c").val())/100;
                    var list_price = (total_price_SAM*sg_commissions_per*1.1)/1.1;
                    var tax = list_price*0.1;
                    total_price_SAM = parseFloat(total_price_SAM).formatMoney(2,',','.');
                    if($("#group_body0").length == 0){
                        insertGroup(0);
                    } 
                    if($("#product_body0").length == 0){
                        insertProductLine("product_group0","0");
                    } 
                    
                    $("#product_product_qty0").val('1');
                    $("#product_name0").val('Solar sales commission');
                    $("#product_product_id0").val('78b8b420-5003-8249-3dd3-5918dd4d0d06');
                    $("#product_part_number0").val('SolarSales');
                    $("#product_product_list_price0").val(list_price);
                    $("#product_product_discount0").val('0.00');
                    $("#product_discount0").val('Percentage');
                    $("#product_product_unit_price0").val(list_price);
                    $("#product_vat_amt0").val(tax);
                    $("#product_vat0").val('10.0');
                    $('#product_product_total_price0').val(list_price);
                    $("#product_item_description0").val($('#name').val() + '            Discounted \n Total Price: $' +total_price_SAM);
                    $("#product_description0").val('Total Price: $' +total_price_SAM);
                    $("#group0name").val("Solar PV Sales");
                    $("#sanden_model_c").val('SolarSales');
                    $("#product_product_list_price0").trigger("blur");

                }
            }
        });
    });
    
    //dung code --  create link open quote by number quote
    function add_link_quote(){
        if (module_sugar_grp1 != 'AOS_Invoices') return;
        var number_quote = $("#quote_number").val();
        if(number_quote != '' && number_quote != '0') {
            $.ajax({
                url: 'index.php?entryPoint=get_info_quote&number=' +number_quote,
                success: function(data){
                    if(data != 'NotData'){
                        try {
                            data = $.parseJSON(data);
                            $("#link_quote").remove();
                            $('#quote_number').parent().append('<p id="link_quote"><a  href="index.php?module=AOS_Quotes&action=EditView&record='+data['id']+'" target="_blank">Open Quote</a></p>');
                        } catch (error) {
                            $("#link_quote").remove();
                        }
                       
                    }
                } 
            })
        }
    }
    add_link_quote(); //load first time
    $('#quote_number').on('change',function(){add_link_quote();});//event change
    //dung code trigger get default value electric daikin from account
    $("#total_to_contractor_c").after('<button class="button primary" id="calc_price" type="button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Calculator Price</button>');
    $('#calc_price').after('<div id="fomular_price"></div>');
    $("#calc_price").click(function(){
        //$("#fomular_price").empty();
        $("#fomular_price").html($("#fomular_price").html() + cal_total_constractor());
        var account_record_id = $("#account_id3_c").val();
        if(account_record_id != ""){
        $.ajax({
            url: "?entryPoint=customGetValueElectricDefault&record_id=" + account_record_id,
            async: false,
        }).done(function (data) {
            if(data == '' || typeof data == 'undefined')return;
            var default_value = $.parseJSON(data);
            $("#fomular_price").html($("#fomular_price").html() + cal_total_constractor(default_value));
        });
    }
      
    })
});

//dung code - popup send sms electric
function click_send_sms_elec(){
    var content_messager_string = $('#content_messager_elec').val();
    var user_phone_number	= $('#from_phone_number_elec').find('option:selected').val();
    var phone_number_customer = $('#phone_number_customer_elec').text().trim();
    var record_id_mess = $('input[name="record"]').val();

    var data_json = {
        'phone_number_customer':phone_number_customer,
        'from_phone_number':user_phone_number,
        'content_messager' :content_messager_string,
        'module': 'AOS_Invoices',
        'record_id' : record_id_mess,
    }
    $.ajax({
        url: 'index.php?entryPoint=sendSMS',
        type: 'POST',
        data: data_json,
        success: function(data){
            console.log(data);
        }
    });
}
//dung code - popup send sms customer
function click_send_sms_customer(){
    var content_messager_string = $('#content_messager_customer').val();
    var user_phone_number	= $('#from_phone_number_customer').find('option:selected').val();
    var phone_number_customer = $('#phone_number_customer_customer').text().trim();
    var record_id_mess = $('input[name="record"]').val();

    var data_json = {
        'phone_number_customer':phone_number_customer,
        'from_phone_number':user_phone_number,
        'content_messager' :content_messager_string,
        'module': 'AOS_Invoices',
        'record_id' : record_id_mess,
    }
    $.ajax({
        url: 'index.php?entryPoint=sendSMS',
        type: 'POST',
        data: data_json,
        success: function(data){
            console.log(data);
        }
    });
}
//dung code -popup send sms plumbing
function click_send_sms_plum(){
    var content_messager_string = $('#content_messager_plum').val();
    var user_phone_number	= $('#from_phone_number_plum').find('option:selected').val();
    var phone_number_customer = $('#phone_number_customer_plum').text().trim();
    var record_id_mess = $('input[name="record"]').val();

    var data_json = {
        'phone_number_customer':phone_number_customer,
        'from_phone_number':user_phone_number,
        'content_messager' :content_messager_string,
        'module': 'AOS_Invoices',
        'record_id' : record_id_mess,
    }
    $.ajax({
        url: 'index.php?entryPoint=sendSMS',
        type: 'POST',
        data: data_json,
        success: function(data){
            console.log(data);
        }
    });
}


// Dung code -- function get value electric from account


function cal_total_constractor(default_value_input = null) {
    if ( default_value_input == null ){
        default_value = {
            'eul_subfloor_100_c' : 100,
            'eul_sub_floor_diff_200_c' : 200,
            'eul_high_wall_85_c' : 85,
            'eul_low_wall_30_c' : 30,
            'eul_2nd_story_wall_300_c' : 300,
            'eul_2nd_story_walkable_55_c' : 55,
            'fridge_pipe_run_external15_c' : 25,
            'electric_run_ext_wall_c' : 25,
            'electric_run_roof_cavity_c' : 100,
            'refrigeration_pipe_roof100_c' : 100,
            'electric_run_sub_floor_c' : 50,
            'ec_new_circuit_95_c' : 95,
            'ec_local_add_rcd_45_c' : 45
        }
    }
    else {
        default_value = default_value_input;
    }

    try {
        var val_cal_total_constractor = 0;
        var fomula_text = '<div class="col-md-6 col-sm-6"><b> We are using formular: </b></div>';
        if ($("input[name='complicated_drain_run_c']").is(':checked')) {
            val_cal_total_constractor += default_value['complicated_drain_run_c'];
            fomula_text += "<div class='col-md-6 col-sm-6'>([Complicated Drain Run]*"+default_value['complicated_drain_run_c']+") + </div>";
        }

        if ($("input[name='internal_wall_install_c']").is(':checked')) {
            val_cal_total_constractor += default_value['internal_wall_install_c'];
            fomula_text += "<div class='col-md-6 col-sm-6'>([Internal Wall Install]*"+default_value['internal_wall_install_c']+") + </div>";
        }

        if ($("input[name='eul_subfloor_100_c']").val() !== '') {
            val_cal_total_constractor += (default_value['eul_subfloor_100_c'] * Number($("input[name='eul_subfloor_100_c']").val()));
            fomula_text += "<div class='col-md-6 col-sm-6'>([External Unit Location (Subfloor)]*"+default_value['eul_subfloor_100_c']+") + </div>";
        }

        if ($("input[name='eul_sub_floor_diff_200_c']").val() !== '') {
            val_cal_total_constractor += (default_value['eul_sub_floor_diff_200_c'] * Number($("input[name='eul_sub_floor_diff_200_c']").val()));
            fomula_text += "<div class='col-md-6 col-sm-6'>([External Unit Location(Sub Floor Diff)]*"+default_value['eul_sub_floor_diff_200_c']+") + </div>";
        }

        if ($("input[name='eul_high_wall_85_c']").val() !== '') {
            val_cal_total_constractor += (default_value['eul_high_wall_85_c'] * Number($("input[name='eul_high_wall_85_c']").val()));
            fomula_text += "<div class='col-md-6 col-sm-6'>([External Unit Location (High Wall)]*"+default_value['eul_high_wall_85_c']+") + </div>";
        }

        if ($("input[name='eul_low_wall_30_c']").val() !== '') {
            val_cal_total_constractor += (default_value['eul_low_wall_30_c'] * Number($("input[name='eul_low_wall_30_c']").val()));
            fomula_text += "<div class='col-md-6 col-sm-6'>([External Unit Location (Low Wall)]*"+default_value['eul_low_wall_30_c']+") + </div>";
        }

        if ($("input[name='eul_2nd_story_wall_300_c']").val() !== '') {
            val_cal_total_constractor += (default_value['eul_2nd_story_wall_300_c'] * Number($("input[name='eul_2nd_story_wall_300_c']").val()));
            fomula_text += "<div class='col-md-6 col-sm-6'>([External Unit Location (2nd Story Wall)]*"+default_value['eul_2nd_story_wall_300_c']+") + </div>";
        }

        if ($("input[name='eul_2nd_story_walkable_55_c']").val() !== '') {
            val_cal_total_constractor += (default_value['eul_2nd_story_walkable_55_c'] * Number($("input[name='eul_2nd_story_walkable_55_c']").val()));
            fomula_text += "<div class='col-md-6 col-sm-6'>([External Unit Location (2nd Story Walkable)]*"+default_value['eul_2nd_story_walkable_55_c']+") + </div>";
        }

        if ($("input[name='fridge_pipe_run_external15_c']").val() !== '' && Number($("input[name='fridge_pipe_run_external15_c']").val()) > 1.5) {
            val_cal_total_constractor += (default_value['fridge_pipe_run_external15_c'] * ($("input[name='fridge_pipe_run_external15_c']").val() - 1.5));
            fomula_text += "<div class='col-md-6 col-sm-6'>(([Fridge Pipe Run Ext (1.5Metres inc)]-1.5)*"+default_value['fridge_pipe_run_external15_c']+")) + </div>";
        }

        if ($("input[name='electric_run_ext_wall_c']").val() !== '' && Number($("input[name='electric_run_ext_wall_c']").val()) > 4) {
            val_cal_total_constractor += (default_value['electric_run_ext_wall_c'] * (Number($("input[name='fridge_pipe_run_external15_c']").val()) - 4));
            fomula_text += "<div class='col-md-6 col-sm-6'>(([Electric Run Ext Wall (4Metres inc)]-4)*"+default_value['electric_run_ext_wall_c']+")) + </div>";
        }

        if ($("input[name='electric_run_roof_cavity_c']").val() !== '') {
            val_cal_total_constractor += (default_value['electric_run_roof_cavity_c'] + (25 * Number($("input[name='electric_run_roof_cavity_c']").val())));
            fomula_text += "<div class='col-md-6 col-sm-6'>(([Fridge Pipe Run Roof Cavity]*25)+"+default_value['electric_run_roof_cavity_c'] +") + </div>";
        }

        if ($("input[name='refrigeration_pipe_roof100_c']").val() !== '') {
            val_cal_total_constractor += (default_value['refrigeration_pipe_roof100_c'] + (25 * Number($("input[name='refrigeration_pipe_roof100_c']").val())));
            fomula_text += "<div class='col-md-6 col-sm-6'>(([Refrigeration pipe run roof cavity]*25)+"+default_value['refrigeration_pipe_roof100_c']+") + </div>";
        }

        if ($("input[name='electric_run_sub_floor_c']").val() !== '') {
            val_cal_total_constractor += (default_value['electric_run_sub_floor_c'] + (25 * Number($("input[name='electric_run_sub_floor_c']").val())));
            fomula_text += "<div class='col-md-6 col-sm-6'>(([Electric Run Sub Floor]*25)+"+default_value['electric_run_sub_floor_c']+") + </div>";
        }

        if ($("input[name='ec_new_circuit_95_c']").val() !== '') {
            val_cal_total_constractor += (default_value['ec_new_circuit_95_c'] * (Number($("input[name='ec_new_circuit_95_c']").val())));
            fomula_text += "<div class='col-md-6 col-sm-6'>([Electrical connection: New Circuit]*"+default_value['ec_new_circuit_95_c']+") + </div>";
        }

        if ($("input[name='ec_local_add_rcd_45_c']").val() !== '') {
            val_cal_total_constractor += (default_value['ec_local_add_rcd_45_c'] * (Number($("input[name='ec_local_add_rcd_45_c']").val())));
            fomula_text += "<div class='col-md-6 col-sm-6'>([Electric Run Sub Floor]*"+default_value['ec_local_add_rcd_45_c']+") + </div>";
        }

        if ($("input[name='misc_extras_c']").val() !== '') {
            val_cal_total_constractor += Number($("input[name='misc_extras_c']").val());
            fomula_text += "<div class='col-md-6 col-sm-6'>([Misc Extras]) + </div>";
        }

        if($("input[name='travel_additional_km_c']").val()  != ''){
            if($("input[name='travel_additional_km_c']").val() > 150){
                val_cal_total_constractor += Number($("input[name='travel_additional_km_c']").val()) * 4;
            }else if($("input[name='travel_additional_km_c']").val() <= 50){
                val_cal_total_constractor += 0;
            }else{
                val_cal_total_constractor += Number($("input[name='travel_additional_km_c']").val()) * 3;
            }
            fomula_text += "<div class='col-md-6 col-sm-6'>([Travel Additional] = 0-50 free | 50-150 $3/km | 150+ $4/km) </div>";
        }

        if(default_value_input != null){
            return "<div class='col-md-6 col-sm-6'><b> Total calculate for " + $("#plumber_c_daikin_c").val() + val_cal_total_constractor + "</b></div>" ;
        } else {
            if (isNaN(val_cal_total_constractor) || typeof (val_cal_total_constractor) == 'undefined') {
                val_cal_total_constractor = 'Error Here';
            }else{
                $("#total_to_contractor_c").val('$'+val_cal_total_constractor);
            }
            return fomula_text;
        }
        


    }
    catch (err) {
        console.log('Error');
    }

}
(function ($) {//
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        var module_name = $(source).attr('data-module');
        var record_id= $(source).attr('data-record-id') ;
        var emailType = $(source).data('email-type');
        if(record_id == ''){
            alert('Please Save before !');
            return;
        }

        if(module_name == 'Invoices'){
            var order_number = $("#solargain_invoices_number_c").val();
            if(emailType != 'invoice_payment_reminder' && emailType != 'Send_Customer_Install_date') {
                if(order_number == "" ){
                    alert('Please fill Order Number !');
                    $("#solargain_invoices_number_c").focus();
                    return;
                }
            }
        }
        if(module_name == 'AOS_Quotes'){
            var seek_install_date_c = $('#proposed_install_date_c_date').val();
            if(seek_install_date_c == ''){
                alert('Please fill Proposed Install Date !');
                $("#proposed_install_date_c_date").focus();
                return;
            }
        }
        var quote_number= $('#solargain_quote_number_c').val() ;
   
        $('#Advise_Install_Date span.glyphicon-refresh').removeClass('hidden');
        // alert("ok");
        var build_url_quote = "?entryPoint=customProposedInstallDate";
        build_url_quote += "&quoteSG_ID="+quote_number;
        build_url_quote += "&proposed_date="+ $("#proposed_install_date_c").val();
        $.ajax({
            url: build_url_quote,
            type : 'POST',
            success: function (data) {
                $('#Advise_Install_Date span.glyphicon-refresh').addClass('hidden');
            },
        });
    
        var self = this;
        console.log(source);
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        if(module_name == 'Invoices') {
            var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((order_number!="")? ("&order_number="+order_number):"")+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") ;
        }else if(module_name == 'AOS_Quotes'){
            var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((seek_install_date_c!="")? ("&seek_install_date_c="+encodeURIComponent(seek_install_date_c)):"")+  ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") ;
        }else{
            var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1' ;
        }
        
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
           
            // $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_id"]').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_module"]').val(populateModule);         
            $(self.emailComposeView).find('input[name="to_addrs_names"]').val(populateModuleName+'<'+ $(source).attr('data-email-address') + '>');    
            
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
    $.fn.openComposeViewModal_Calendar = function (source) {
        "use strict";
        var record_id= $(source).attr('data-record-id') ;
        var email_id = $(source).attr('data-email-address-id');
        var email;
        $.ajax({
            url: "/index.php?entryPoint=APIGetContactEmail&contact_id="+ email_id ,
            success: function(data){
                email = data;
            }
        })
        var self = this;

        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var email_type = $(source).attr('data-email-type');
        var email_module  =  $(source).attr('data-module');
        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"") ;
                
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
                populateEmailAddress = email;
            }
            
            if(typeof($(source).attr('data-contact-name')) != 'undefined'){
                populateEmailAddress = $(source).attr('data-contact-name') + ' <' + populateEmailAddress + '>';
            }else if (populateModuleName !== '' && typeof($(source).attr('data-contact-name')) == 'undefined') {
                populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            }
        
            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            var email_user_assigned  = $(self.emailComposeView).find('#cc_addrs_names').val() != '' ? $(self.emailComposeView).find('#cc_addrs_names').val() : '';

            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>"+`, ${email_user_assigned}`);
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
    $.fn.openComposeViewModal_reupload = function (source) {
        "use strict";
        var record_id= $(source).attr('data-record-id') ;
        var email_id = $(source).attr('data-email-address-id');
        var email;
        $.ajax({
            url: "/index.php?entryPoint=APIGetContactEmail&contact_id="+ email_id ,
            success: function(data){
                email = data;
            }
        })
        var self = this;

        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var email_type = $(source).attr('data-email-type');
        var email_module  =  $(source).attr('data-module');
        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"") ;
                
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
                populateEmailAddress = email;
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

    
    /**
     *  Popup email default ...
     */
    $.fn.openComposeViewModalDefault = function(source){
        "use strict";
        var module_name = $(source).attr('data-module');
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var populateModule = $(source).attr('data-module');
        var populateModuleRecord = $(source).attr('data-record-id');
        var populateModuleName = $(source).attr('data-module-name');
        var emailAddress = '';
        if(record_id == ''){
            alert('Please Save before !');
            return;
        }
        $.ajax({
            url: "/index.php?entryPoint=APIGetContactEmail&contact_id="+ $('#billing_contact_id').val() ,
            success: function(data){
                emailAddress = data;
            }
        })

        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();

        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'
        + ((record_id!="")? ("&record_id="+record_id):"") 
        + ((email_type!="")? ("&email_type="+email_type):"") ;
        
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
           
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_id"]').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_module"]').val(populateModule);         
            $(self.emailComposeView).find('input[name="to_addrs_names"]').val(populateModuleName+'<'+ emailAddress + '>');    
            
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
    }
}(jQuery));

function original_price(){
        if($("#show_original_price").is(':checked')){
            var pr;
            var total_pr = 0;
            var len_line_items = $('#lineItems #product_group0 tbody').length;
            for( var i = 0; i < len_line_items ; i++ ){
                if( $('#product_product_list_price'+i).val().indexOf(',') >= 0 ){
                    pr = (parseFloat( $('#product_product_list_price'+i).val().replace(",", ""))) * (parseInt( $('#product_product_qty'+i).val() ));
                }else {
                    pr = (parseFloat( $('#product_product_list_price'+i).val() )) * (parseInt( $('#product_product_qty'+i).val() ));
                }
                total_pr += pr;
                $('#product_product_list_price'+i).val('0.00').trigger('blur');
            };
            $('#product_product_cost_price0').val(total_pr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

            $('#product_product_list_price0').val(total_pr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")).trigger('blur');
        }else {
                $('#fake_original_price span.glyphicon-refresh').removeClass('hidden');
                var data = {};
                var len_line_items = $('#lineItems #product_group0 tbody').length;
                for(var i = 0; i < len_line_items ; i++){
                    data[i] = $('#product_product_id'+i).val();
                }
                var id_partNumber = JSON.stringify(data);
                $.ajax({
                type: "GET",
                url: '/index.php?entryPoint=customOriginalPrice&id_partNumber='+id_partNumber,
                success: function(data)
                {
                    if(data == '' || typeof data == 'undefined')return;
                    var products = $.parseJSON(data);
                    for(var k = 0; k < len_line_items ; k++){
                        $("#product_currency"+k).val(products[k].currency_id);
                        $("#product_item_description"+k).val(products[k].description);
                        $("#product_name"+k).val(products[k].product_name);
                        $("#product_part_number"+k).val(products[k].part_number);
                        $("#product_product_cost_price"+k).val( products[k].cost_price);
                        $("#product_product_list_price"+k).val(products[k].price);
                    }
                    $('#fake_original_price span.glyphicon-refresh').addClass('hidden');
                }
            })
        }
    }

function getParameterByName(name, url) {
    if (!url) return null;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$(function () {
    'use strict';

    $( document ).ready(function() {
            //thienpb code
            function loadPhoneNumber(){
                var record_id = $("#billing_account_id").val();
                $.ajax({
                    url: "?entryPoint=getContactFromAccount&record_id=" + record_id,
                    async: false
                }).done(function (data) {
                    if(data == '' || typeof data == 'undefined')return;
                    var json = $.parseJSON(data);
                    var data_return ='';
                    if(json.email != ''){
                        data_return += 'Email: <a onclick="$(document).openComposeViewModal(this);" data-module="Accounts" data-record-id="'+json.record_id+'" data-module-name="'+json.name+'" data-email-address="'+json.email+'">'+json.email+'</a><br>';
                    }
                    if(json.mobile != ''){
                        data_return += "M: <span class='account_phone_number'>"+json.mobile+'</span> <img class="sms_icon_invoice" data-source="account" src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14">&nbsp;<a target="_blank" href="http://message.pure-electric.com.au/'+json.mobile.replace(/^0/g, "#61").replace(/^61/g,"#61").replace(/\s+/g,'')+'" title="Message Portal"><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="14" width="14"></a>';
                    }
                    if(json.mobile_home != ''){
                        data_return += "H: "+json.mobile_home;
                    }
                    if(json.mobile_work != ''){
                        data_return += "W: "+json.mobile_work;
                    }
                    $('#billing_account_id').parent().children('.phone-number').remove();
                    $('#billing_account_id').parent().append('<p class="phone-number">' + data_return + '</p>');
                });
            }
            YAHOO.util.Event.addListener("billing_account_id", "change", function(){
                loadPhoneNumber();
            });
            loadPhoneNumber();

        $("#subpanel_aos_invoices_pe_internal_note_1 tr").each(function() {
            var recordId = getParameterByName('record', $(this).find("td:nth-child(4) a").attr('href'));
            var module_name = getParameterByName('module', $(this).find("td:nth-child(4) a").attr('href'));
            var url = "/index.php?entryPoint=customLeadHoverEmail&record=" + recordId + "&module=" + module_name ;
            if(recordId != null ) {
                $(this).find("td:nth-child(3)").append('<a class="various fancybox.ajax" data-fancybox-type="ajax" href="' + url + '"> Preview</a>');
            }  
        });

        $('.various').fancybox({
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

        //dung code-- button Send Email TrustPilot 
        if(module_sugar_grp1 == 'AOS_Invoices') {
            $("#CANCEL").after(
                ' <button class="button" type="button" id="Send_Email_TrustPilot" >Send TrustPilot</button>'
            );
        }

        $('body').on('click','#Send_Email_TrustPilot',function(){
            var answer = confirm("Are you want to send email TrustPilot to customer?")
            if (answer) {
                $.ajax({
                    url : "?entryPoint=SendEmailTrustPilop&module=Contact&action=EditView&record_id="+$('input[name="billing_contact_id"]').val(),
                    success: function (data) {
                    if(data == 'error'){
                            alert('Error!');
                    }else{
                            alert('Success!');
                    }
                    }
                });
            }
            else {
                return false;
            }
        })

        // button Update Related
        if(module_sugar_grp1 == 'AOS_Invoices') {
            $("#btn_clr_assigned_user_name").after('<button style="width: 150px;" id="update_relates" class="button update_relates">Update Related <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        }
        
        if(typeof record !== "undefined" && record != ""){
            $("#update_relates").click(function(){
                var assigned_id = $("#assigned_user_id").val();
                var record = $("input[name='record']").val();
                $('#update_relates span.glyphicon-refresh').removeClass('hidden');
                $.ajax({
                    url: "/index.php?entryPoint=customUpdateRelated&bean_type=AOS_Invoices&record="+record +"&assigned_id="+assigned_id,
                    type: 'GET',
                    success: function(data)
                    {
                        $('#update_relates span.glyphicon-refresh').addClass('hidden');
                    },
                    error: function(response){console.log("Fail");},
                });
                return false;
            });
        }

        //thienbp code
        if(module_sugar_grp1 == 'AOS_Invoices') {
            $("#new_cancel").after("&nbsp;<button type='button' class='button primary' id='create_installation_calendar_top'><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span>Create Installation Calendar</button>")
            $("#create_installation_calendar_top").on("click",function(e){ $("#create_installation_calendar").trigger("click"); })
            $('#installation_calendar_id_c').parent().siblings('.label').append('<br> <button style="line-height: 15px" type="button" class="button primary" id="create_installation_calendar"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create Installation Calendar</button>');
            
            if($("#installation_calendar_id_c").val() != ''){
                $("#link_installer_calendar").remove();
                $("#installation_calendar_id_c").after('<p id="link_installer_calendar">Link to Admin :<a target="_blank" href="https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/peadmin">https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/peadmin</a></p>');
                if($("#billing_account_id").val().trim() != '')
                    $("#link_installer_calendar").append('<br>Link to Client :\
                    <button type="button" id="clients_calendar" class="button primary" data-email-address-id="'+$('#billing_contact_id').val()+'" data-email-type="clients_calendar" onclick="$(document).openComposeViewModal_Calendar(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#billing_account').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" >Email</button> \
                    <a target="_blank" href="https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/client">https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/client</a>')
                if($("#account_id_c").val().trim() != '')
                    $("#link_installer_calendar").append('<br>Link to Electrician :\
                    <button type="button" id="electrician_calendar" class="button primary" data-email-address-id="'+$('#contact_id_c').val()+'" data-email-type="electrician_calendar" onclick="$(document).openComposeViewModal_Calendar(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#electrician_contact_c').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" >Email</button> \
                    <a target="_blank" href="https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/electrician/'+$("#account_id_c").val().trim()+'">https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/electrician/'+$("#account_id_c").val().trim()+'</a>')
                if($("#account_id1_c").val().trim() != '')
                    $("#link_installer_calendar").append('<br>Link to Plumber :\
                    <button type="button" id="plumber_calendar" class="button primary" data-email-address-id="'+$('#contact_id4_c').val()+'" data-email-type="plumber_calendar" onclick="$(document).openComposeViewModal_Calendar(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#plumber_contact_c').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" >Email</button> \
                    <a target="_blank" href="https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/plumber/'+$("#account_id1_c").val().trim()+'">https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/plumber/'+$("#account_id1_c").val().trim()+'</a>')
            }
            $("#create_installation_calendar").on("click",function(){
                //save before
                SUGAR.ajaxUI.showLoadingPanel();
                $("#EditView input[name='action']").val('Save');
                $.ajax({
                    type: $("#EditView").attr('method'),
                    url: $("#EditView").attr('action'),
                    data: $("#EditView").serialize(),
                    async: false,
                    success: function () {
                        console.log('Save Inv before create Calendar');
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }
                });
                var  invoice_id         = $("input[name='record']").val().trim();
                var  invoice_number     = $("div[field='number']").text().trim();
                var  invoice_title      = $("#name").val().trim();
                var  client_id          = $("#billing_account_id").val();
                var  client_firstname   = $("#billing_account").val().split(/\s(.+)/)[0];
                var  client_lastname    = $("#billing_account").val().split(/\s(.+)/)[1];
                var  electric_id        = $("#account_id_c").val().trim();
                var  electric_firstname = $("#electrician_c").val().split(/\s(.+)/)[0];
                var  electric_lastname  = $("#electrician_c").val().split(/\s(.+)/)[1];
                var  plumber_id         = $("#account_id1_c").val().trim();
                var  plumber_firstname  = $("#plumber_c").val().split(/\s(.+)/)[0];
                var  plumber_lastname   = $("#plumber_c").val().split(/\s(.+)/)[1];
                var  created_by         = $("#user_id_c").val();
                var getDaysArray = function(start) {
                    var dt = new Date(start);
                    var arr=[];
                    for(var i = 0; i < 21 ; i++){ 
                        dt.setDate(dt.getDate()+1);
                        arr.push(new Date(dt));
                    }
                     return arr;
                 };
                 var today = new Date();
                 var pe_available_date = JSON.stringify(getDaysArray(today));
                
                $.ajax({
                    type: "POST",
                    url : "https://calendar.pure-electric.com.au/api/API.php/saveInstallation",
                    data : {
                            "invoice_id"        : invoice_id,
                            "invoice_number"    : invoice_number,
                            "invoice_title"     : invoice_title,
                            "client_id"         : (client_id          != 'undefined') ? client_id          : '',
                            "client_firstname"  : (client_firstname   != 'undefined') ? client_firstname   : '',
                            "client_lastname"   : (client_lastname    != 'undefined') ? client_lastname    : '',
                            "electric_id"       : (electric_id        != 'undefined') ? electric_id        : '',
                            "electric_firstname": (electric_firstname != 'undefined') ? electric_firstname : '',
                            "electric_lastname" : (electric_lastname  != 'undefined') ? electric_lastname  :'',
                            "plumber_id"        : (plumber_id         != 'undefined') ? plumber_id         : '',
                            "plumber_firstname" : (plumber_firstname  != 'undefined') ? plumber_firstname  : '',
                            "plumber_lastname"  : (plumber_lastname   != 'undefined') ? plumber_lastname   : '',
                            "created_by"        : (created_by         != 'undefined') ? created_by         : '',
                            "pe_available_date"        : pe_available_date,
                            "electric_available_date"  : [],
                            "plumber_available_date"   : [],
                            "client_available_date"    : [],
                        },
                    success : function(data){
                        if(data['success']){
                            $("#installation_calendar_id_c").val(data['message']['id']);
                            $("#link_installer_calendar").remove();
                            $("#installation_calendar_id_c").after('<p id="link_installer_calendar">Link to Admin :<a href="https://calendar.pure-electric.com.au/#/installation-booking/'+data['message']['id']+'/peadmin">https://calendar.pure-electric.com.au/#/installation-booking/'+data['message']['id']+'/peadmin</a></p>');
                            window.open('https://calendar.pure-electric.com.au/#/installation-booking/'+data['message']['id']+'/peadmin',"_blank");
                           
                            $.ajax({
                                type: "POST",
                                url: "?entryPoint=generateInstallationCalendarEmail&invoiceID="+invoice_id+"&installation_id=" + data['message']['id'],
                                success : function(data_){
                                    if(data_ != ''){
                                        var urls = JSON.parse(data_);
                                        if(urls.client_url != ''){
                                            $("#link_installer_calendar").append('<br>Link to Client :\
                                                <button type="button" id="clients_calendar" class="button primary" data-email-address-id="'+$('#billing_contact_id').val()+'" data-email-type="clients_calendar" onclick="$(document).openComposeViewModal_Calendar(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#billing_account').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" >Email</button>\
                                                <a href="https://calendar.pure-electric.com.au/#/installation-booking/'+data['message']['id']+'/client">https://calendar.pure-electric.com.au/#/installation-booking/'+data['message']['id']+'/client</a>')
                                            window.open(urls.client_url,"_blank");
                                        }
                                        if(urls.electrician_url != ''){
                                            $("#link_installer_calendar").append('<br>Link to Electrician :\
                                                <button type="button" id="electrician_calendar" class="button primary" data-email-address-id="'+$('#contact_id_c').val()+'" data-email-type="electrician_calendar" onclick="$(document).openComposeViewModal_Calendar(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#electrician_contact_c').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" >Email</button> \
                                                <a href="https://calendar.pure-electric.com.au/#/installation-booking/'+data['message']['id']+'/electrician/'+electric_id+'">https://calendar.pure-electric.com.au/#/installation-booking/'+data['message']['id']+'/electrician/'+electric_id+'</a>')
                                            window.open(urls.electrician_url,"_blank");
                                        }
                                        if(urls.plumber_url != ''){
                                            $("#link_installer_calendar").append('<br>Link to Plumber :\
                                                <button type="button" id="plumber_calendar" class="button primary" data-email-address-id="'+$('#contact_id4_c').val()+'" data-email-type="plumber_calendar" onclick="$(document).openComposeViewModal_Calendar(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").val() +'" data-contact-name="'+$('#plumber_contact_c').val()+'"  data-record-id="'+ $("input[name='record']").val() +'" >Email</button> \
                                                <a href="https://calendar.pure-electric.com.au/#/installation-booking/'+data['message']['id']+'/plumber/'+plumber_id+'">https://calendar.pure-electric.com.au/#/installation-booking/'+data['message']['id']+'/plumber/'+plumber_id+'</a>')
                                            window.open(urls.plumber_url,"_blank");

                                        }
                                        
                                        if($("#installation_calendar_id_c").val() != ''){
                                            $(".link_installer_calendar_in_invoice_checklist").remove();
                                            if($("#billing_account_id").val().trim() != '')
                                                $('#cl_send_customer_install_date').parents().eq(1).find('div.label').append('<a class="link_installer_calendar_in_invoice_checklist" target="_blank" href="https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/client'+'">  Link Calendar Customer</a>'); 
                                            if($("#account_id_c").val().trim() != '')
                                                $('#cl_electrician_seek_install_date').parents().eq(1).find('div.label').append('<a class="link_installer_calendar_in_invoice_checklist" target="_blank" href="https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/electrician/'+$("#account_id_c").val().trim()+'">  Link Calendar Electrian</a>');
                                            if($("#account_id1_c").val().trim() != '')
                                                $('#cl_plumber_seek_install_date').parents().eq(1).find('div.label').append('<a class="link_installer_calendar_in_invoice_checklist" target="_blank" href="https://calendar.pure-electric.com.au/#/installation-booking/'+$("#installation_calendar_id_c").val().trim()+'/plumber/'+$("#account_id1_c").val().trim()+'">  Link Calendar Plumber</a>');
                                        }
                                    }
                                }
                            });
                        }
                    }

                })
            });
        }
//VUT-S-Create button new Service Case
        if (module_sugar_grp1 == 'AOS_Invoices' && action_sugar_grp1 == 'EditView') {
            $("#new_cancel").after("&nbsp;<button type='button' class='button primary' id='create_service_case'><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span>Create Service Case</button>")
            //VUT - S - add button TODAY / TODAY +1 TODAY+7  >> https://trello.com/c/42nirRdb/3048-invoice-add-date-shortcut-buttons
            var array_datefield = 'div[field="quote_date"], div[field="invoice_date"], div[field="due_date"]';
            var array_dateTimefield = 'div[field="next_action_date_c"], div[field="dispatch_date_c"]';
            var html_button_date = '<button style="padding: 0px 5px;margin: 0px 1px;" type="button" class="button get_date_inv"  title="Get Today+7" data-type="7" >T+7</button>'
                                +   '<button style="padding: 0px 5px;margin: 0px 1px;" type="button" class="button get_date_inv" title="Get Today+1" data-type="1" >T+1</button>'
                                +    '<button style="padding: 0px 10px;margin: 0px 1px;" type="button" class="button get_date_inv" title="Get Today" data-type="today" >T</button>';
            $(array_datefield).append(html_button_date);
            $(array_dateTimefield).find('tr[valign="middle"]').append('<td>'+html_button_date+'</td>');
            $('.get_date_inv').click(function(){
                // debugger
                var field_date = $(this).closest('.edit-view-field').attr('field');
                var type_field = $(this).closest('.edit-view-field').attr('type');
                var type_button = $(this).attr('data-type');
                var date_click = getDate_Inv(type_button);
                if (type_field == 'date') {
                    $('#'+field_date).val(date_click);
                } else {
                    var today_date = new Date();
                    var hour_date = ((today_date.getHours() < 10) ? '0' : '') + today_date.getHours();
                    var minutes_date = today_date.getMinutes();
                    if(minutes_date<15){
                        minutes_date = $("#"+field_date+"_minutes option:eq(1)").val();
                    }else if(minutes_date>=15 && minutes_date < 30){
                        minutes_date = $("#"+field_date+"_minutes option:eq(2)").val();
                    }else if(minutes_date>=30 && minutes_date < 45){
                        minutes_date = $("#"+field_date+"_minutes option:eq(3)").val();
                    }else{
                        minutes_date = $("#"+field_date+"_minutes option:eq(4)").val();      
                    }
                    $('#'+field_date+'_date').val(date_click);
                    $('#'+field_date+'_hours').val(hour_date);
                    $('#'+field_date+'_minutes').val(minutes_date);
                    $('#'+field_date).val(date_click+' '+hour_date+':'+minutes_date);
                }
            });     
            //VUT - E - add button TODAY / TODAY +1 TODAY+7  >> https://trello.com/c/42nirRdb/3048-invoice-add-date-shortcut-buttons
            //VUT - S - Get AuPost Shipping id
            var aupost_shipping_id = $(document).find('#aupost_shipping_id').val();
            var invoice_type = $('#quote_type_c').val();
            if (invoice_type != 'quote_type_methven') {
                $(document).find('div[field="aupost_shipping_id"]').parent().hide();
            } else {
                $(document).find('#aupost_shipping_id').hide();
                if (aupost_shipping_id == '') {
                    var  invoice_id= $("input[name='record']").val().trim();
                    $.ajax({
                        url: "?entryPoint=API_Invoice_WarehouseLog&check=aupost&invoice_id=" +encodeURIComponent(invoice_id),
                        success: function(data) {
                            console.log('API_Invoice_WarehouseLog >>'+data);
                            if (data.trim() == 'not id') {
                                $(document).find('div[field="aupost_shipping_id"]').parent().hide(); 
                                return; 
                            }
                            aupost_shipping_id = data.trim();
                            showLink('aupost_shipping_id', 'aupost', aupost_shipping_id);
                        }
                    });
                } else {
                    showLink('aupost_shipping_id', 'aupost', aupost_shipping_id);
                }
            }
            //VUT - E - Get AuPost Shipping id
        }
        $('#create_service_case').click(function() {
            // debugger;
            var  invoice_id= $("input[name='record']").val().trim();
            $.ajax({
                url: "?entryPoint=createServiceCase&module=AOS_Invoices&method=put&record_id=" +encodeURIComponent(invoice_id),
                success: function (data) {
                    // debugger;
                    if (trim(data) == "" || trim(data) == null) {
                        console.log('No data return');
                    } else {
                        window.open('/index.php?module=pe_service_case&action=EditView&record='+trim(data), '_blank');
                        // $('#link_servicecase').remove();
                        // $('<div id="link_servicecase"><a href="/index.php?module=pe_service_case&action=EditView&record='+trim(data)+'" target="_blank">Open link Service Case</></div>').insertAfter('button[onclick="clip_aboard(\'absolute_path\')"]');
                    }
                }
            });
        });
//VUT-E-Create button new Service Case
                //tuan code-- popup template Cert Notes
                $( "#dialog_pcoc_cert_notes" ).dialog({
                    autoOpen: false,
                    width: 712,
                    height:478,
                    buttons: {
                        Save: function(){
                            SUGAR.ajaxUI.showLoadingPanel();
                            $("#ajaxloading_mask").css("position",'fixed');
                            //create new
                            if($("#id_pcoc_template").val() == '') {
                                if($("#title_pcoc_template").val() == ''){
                                    alert('Could you insert title please?');
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                    return false;
                                };
                                $.ajax({
                                    url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=pcoc_type' ,
                                    type: 'POST',
                                    data: 
                                    {
                                        id: $("#id_pcoc_template").val(),
                                        action: 'create',
                                        content: encodeURIComponent($("#content_pcoc_template").val()),
                                        title: encodeURIComponent($("#title_pcoc_template").val()),
                                        module: module_sugar_grp1,
                                        module_id: $("input[name='record']").val(),
                                    },
                                    success: function(result) {              
                                        render_select_pcoc_template(result);
                                        SUGAR.ajaxUI.hideLoadingPanel();
                                    }
                                }); 
                            }   
                            //update
                            else{
                                $.ajax({
                                    url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=pcoc_type' ,
                                    type: 'POST',
                                    data: 
                                    {
                                        id: $("#id_pcoc_template").val(),
                                        action: 'update',
                                        content: encodeURIComponent($("#content_pcoc_template").val()),
                                        title: encodeURIComponent($("#title_pcoc_template").val()),
                                        module: module_sugar_grp1,
                                        module_id: $("input[name='record']").val(),
                                    },
                                    success: function(result) {                         
                                        render_select_pcoc_template(result);
                                        SUGAR.ajaxUI.hideLoadingPanel();
                                    }
                                }); 
                            }
                            $("#pcoc_cert_wording_c").val( $("#content_pcoc_template").val());  
                            autosize.update($("#pcoc_cert_wording_c"));
                            $(this).dialog('close');
                        },
                        Create: function(){
                            $("#id_pcoc_template").val('');
                            $("#title_pcoc_template").val('');
                            $("#content_pcoc_template").val('');
                        },
                        Insert: function(){
                            $("#pcoc_cert_wording_c").val( $("#content_pcoc_template").val()); 
                            autosize.update($("#pcoc_cert_wording_c"));     
                            $(this).dialog('close');
                        },
                        Delete: function(){
                            var ok = confirm('Do you want delete Template !');
                            if (ok){
                                SUGAR.ajaxUI.showLoadingPanel();
                                $("#ajaxloading_mask").css("position",'fixed');
                                $.ajax({
                                    url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=pcoc_type' ,
                                    type: 'POST',
                                    data: 
                                    {
                                        id: $("#id_pcoc_template").val(),
                                        action: 'delete',
                                        content: encodeURIComponent($("#content_pcoc_template").val()),
                                        title: encodeURIComponent($("#title_pcoc_template").val()),
                                        module: module_sugar_grp1,
                                        module_id: $("input[name='record']").val(),
                                    },
                                    success: function(result) {                         
                                        render_select_pcoc_template(result);
                                        SUGAR.ajaxUI.hideLoadingPanel();
                                        $("#content_pcoc_template").val('');
                                        $("#title_pcoc_template").val('');
                                        $("#id_pcoc_template").val('');
                                    }
                                }); 
                            }
                        },
                        Cancel: function(){
                            $(this).dialog('close');
                        },
                    }
                });
                $('#pcoc_cert_wording_c').parent().siblings('.label').append('<br> <button class="button primary" id="dialog_pcoc_notes_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Edit Templates</button>');
                var html_select_pcoc_template = 
                '<div class="col-xs-12 col-sm-12 edit-view-row-item"> \
                    <div class="col-xs-12 col-sm-4 label" data-label="LBL_SELECT_TEMPLATE_PCOC">PCOC Cert Template:</div> \
                        <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="select_template_pcoc"> \
                        <select name="select_template_pcoc" id="select_template_pcoc" title=""> \
                            <option>Select Template PCOC Cert</option> \
                        </select> \
                    </div> \
                </div>';
                $('#pcoc_cert_wording_c').parents('.edit-view-row-item').append(html_select_pcoc_template);
                $("#dialog_pcoc_notes_button").click(function(e){
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    $.ajax({
                        url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=pcoc_type' ,
                        type: 'POST',
                        data: 
                        {
                            action: 'read',
                        },
                        async: true,
                        success: function(result) {                         
                            render_select_pcoc_template(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                            $("#dialog_pcoc_cert_notes" ).dialog("open");
                        }
                    }); 
                    return false;
                })
        
                $('#select_title_template_pcoc_notes').change(function(){
                    var id = $('#select_title_template_pcoc_notes').val();
                    if(id == '') return false;
                    var title = $('#select_title_template_pcoc_notes option:selected').text();
                    $("#title_pcoc_template").val(title);
                    $("#id_pcoc_template").val(id);
                    $("#content_pcoc_template").val(window.data_pcoc_notes[id].content);
                });
        
                // run first time
                loadSelect_PCOC_Template();
        
                $(document).find('#select_template_pcoc').change(function(){
                    $('#pcoc_cert_wording_c').val('');
                    var id = $('#select_template_pcoc').val();
                    if(id == '') return false;
                    if($("#pcoc_cert_wording_c").val() == ''){
                        $("#pcoc_cert_wording_c").val(window.data_pcoc_notes[id].content);
                    }else{
                        var $txt = $("#pcoc_cert_wording_c");
                        var caretPos = $txt[0].selectionStart;
                        var textAreaTxt = $txt.val();
                        var txtToAdd = window.data_pcoc_notes[id].content;
                        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
                    }
                    autosize.update($("#pcoc_cert_wording_c"));
                });
                //tuan code-- popup template CES Notes
                $( "#dialog_ces_cert_notes" ).dialog({
                    autoOpen: false,
                    width: 712,
                    height:478,
                    buttons: {
                        Save: function(){
                            SUGAR.ajaxUI.showLoadingPanel();
                            $("#ajaxloading_mask").css("position",'fixed');
                            //create new
                            if($("#id_ces_template").val() == '') {
                                if($("#title_ces_template").val() == ''){
                                    alert('Could you insert title please?');
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                    return false;
                                };
                                $.ajax({
                                    url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=ces_type' ,
                                    type: 'POST',
                                    data: 
                                    {
                                        id: $("#id_ces_template").val(),
                                        action: 'create',
                                        content: encodeURIComponent($("#content_ces_template").val()),
                                        title: encodeURIComponent($("#title_ces_template").val()),
                                        module: module_sugar_grp1,
                                        module_id: $("input[name='record']").val(),
                                    },
                                    success: function(result) {              
                                        render_select_ces_template(result);
                                        SUGAR.ajaxUI.hideLoadingPanel();
                                    }
                                });
                                $("#ces_cert_wording_c").val( $("#content_ces_template").val());
                            }   
                            //update
                            else{
                                $.ajax({
                                    url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=ces_type' ,
                                    type: 'POST',
                                    data: 
                                    {
                                        id: $("#id_ces_template").val(),
                                        action: 'update',
                                        content: encodeURIComponent($("#content_ces_template").val()),
                                        title: encodeURIComponent($("#title_ces_template").val()),
                                        module: module_sugar_grp1,
                                        module_id: $("input[name='record']").val(),
            
                                    },
                                    success: function(result) {                         
                                        render_select_ces_template(result);
                                        SUGAR.ajaxUI.hideLoadingPanel();
                                        $("#ces_cert_wording_c").val(window.data_ces_notes[$("#id_ces_template").val()].content);
                                    }
                                }); 
                            }
                            autosize.update($("#ces_cert_wording_c"));
                            $(this).dialog('close');
                        },
                        Create: function(){
                            $("#id_ces_template").val('');
                            $("#title_ces_template").val('');
                            $("#content_ces_template").val('');
                        },
                        Insert: function(){
                            if ($("#id_ces_template").val() != '')  {
                                alert('PLease click Save!'); return;
                            } else {
                                $("#ces_cert_wording_c").val( $("#content_ces_template").val()); 
                            }
                            autosize.update($("#ces_cert_wording_c"));     
                            $(this).dialog('close');
                        },
                        Delete: function(){
                            var ok = confirm('Do you want delete Template !');
                            if (ok){
                                SUGAR.ajaxUI.showLoadingPanel();
                                $("#ajaxloading_mask").css("position",'fixed');
                                $.ajax({
                                    url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=ces_type' ,
                                    type: 'POST',
                                    data: 
                                    {
                                        id: $("#id_ces_template").val(),
                                        action: 'delete',
                                        content: encodeURIComponent($("#content_ces_template").val()),
                                        title: encodeURIComponent($("#title_ces_template").val()),
                                        module: module_sugar_grp1,
                                        module_id: $("input[name='record']").val(),
                                    },
                                    success: function(result) {                         
                                        render_select_ces_template(result);
                                        SUGAR.ajaxUI.hideLoadingPanel();
                                        $("#content_ces_template").val('');
                                        $("#title_ces_template").val('');
                                        $("#id_ces_template").val('');
                                    }
                                }); 
                            }
                        },
                        Cancel: function(){
                            render_select_ces_template(JSON.stringify(window.data_ces_note_prev));
                            $(this).dialog('close');
                        },
                    }
                });
                $('#ces_cert_wording_c').parent().siblings('.label').append('<br> <button class="button primary" id="dialog_ces_notes_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Edit Templates</button>');
                var html_select_ces_template = 
                '<div class="col-xs-12 col-sm-12 edit-view-row-item"> \
                    <div class="col-xs-12 col-sm-4 label" data-label="LBL_SELECT_TEMPLATE_CES">CES Cert Template:</div> \
                        <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="select_template_ces"> \
                        <select name="select_template_ces" id="select_template_ces" title=""> \
                            <option>Select Template CES Cert</option> \
                        </select> \
                    </div> \
                </div>';
                $('#ces_cert_wording_c').parents('.edit-view-row-item').append(html_select_ces_template);
                $("#dialog_ces_notes_button").click(function(e){
                    window.data_ces_note_prev = window.data_ces_notes;
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    $.ajax({
                        url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=ces_type' ,
                        type: 'POST',
                        data: 
                        {
                            action: 'read',
                            // module: module_sugar_grp1 ,
                            // module_id: $("input[name='record']").val(),
                        },
                        async: true,
                        success: function(result) {                         
                            render_select_ces_template(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                            $("#dialog_ces_cert_notes" ).dialog("open");
                        }
                    }); 
                    return false;
                })
        
                $('#select_title_template_ces_notes').change(function(){
                    var id = $('#select_title_template_ces_notes').val();
                    if(id == '') return false;
                    var title = $('#select_title_template_ces_notes option:selected').text();
                    $("#title_ces_template").val(title);
                    $("#id_ces_template").val(id);
                    $("#content_ces_template").val(window.data_ces_notes[id].content);
                });
                //VUT >> move to end page
                // function render_select_ces_template(result){
                // }

                // run first time
                loadSelect_CES_Template();
        
                $(document).find('#select_template_ces').change(function(){
                    $('#ces_cert_wording_c').val('');
                    var id = $('#select_template_ces').val();
                    if(id == '') return false;
                    if($("#ces_cert_wording_c").val() == ''){
                        $("#ces_cert_wording_c").val(window.data_ces_notes[id].content);
                    }else{
                        var $txt = $("#ces_cert_wording_c");
                        var caretPos = $txt[0].selectionStart;
                        var textAreaTxt = $txt.val();
                        var txtToAdd = window.data_ces_notes[id].content;
                        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
                    }
                    autosize.update($("#ces_cert_wording_c"));
                });
        //dung code-- popup template Plumbing Notes
        $( "#dialog_plumbing_notes" ).dialog({
            autoOpen: false,
            width: 712,
            height:478,
            buttons: {
                Save: function(){
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    //create new
                    if($("#id_plumbing_template").val() == '') {
                        if($("#title_plumbing_template").val() == ''){
                            alert('Could you insert title please?');
                            SUGAR.ajaxUI.hideLoadingPanel();
                            return false;
                        };
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_Plumbing_Notes' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_plumbing_template").val(),
                                action: 'create',
                                content: encodeURIComponent($("#content_plumbing_template").val()),
                                title: encodeURIComponent($("#title_plumbing_template").val())
                            },
                            success: function(result) {              
                                render_select_plumbing_template(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }
                        }); 
                    }   
                    //update
                    else{
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_Plumbing_Notes' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_plumbing_template").val(),
                                action: 'update',
                                content: encodeURIComponent($("#content_plumbing_template").val()),
                                title: encodeURIComponent($("#title_plumbing_template").val())
                            },
                            success: function(result) {                         
                                render_select_plumbing_template(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }
                        }); 
                    }
                    $("#plumbing_notes_c").val( $("#content_plumbing_template").val());  
                    autosize.update($("#plumbing_notes_c"));
                    $(this).dialog('close');
                },
                Create: function(){
                    $("#id_plumbing_template").val('');
                    $("#title_plumbing_template").val('');
                    $("#content_plumbing_template").val('');
                },
                Insert: function(){
                    $("#plumbing_notes_c").val( $("#content_plumbing_template").val()); 
                    autosize.update($("#plumbing_notes_c"));     
                    $(this).dialog('close');
                },
                Delete: function(){
                    var ok = confirm('Do you want delete Template !');
                    if (ok){
                        SUGAR.ajaxUI.showLoadingPanel();
                        $("#ajaxloading_mask").css("position",'fixed');
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_Plumbing_Notes' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_plumbing_template").val(),
                                action: 'delete',
                                content: encodeURIComponent($("#content_plumbing_template").val()),
                                title: encodeURIComponent($("#title_plumbing_template").val())
                            },
                            success: function(result) {                         
                                render_select_plumbing_template(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                                $("#content_plumbing_template").val('');
                                $("#title_plumbing_template").val('');
                                $("#id_plumbing_template").val('');
                            }
                        }); 
                    }
                },
                Cancel: function(){
                    $(this).dialog('close');
                },
            }
        });
        $('#plumbing_notes_c').parent().siblings('.label').append('<br> <button class="button primary" id="dialog_plumbing_notes_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Edit Templates</button>');
        var html_select_plumbing_template = 
        '<div class="col-xs-12 col-sm-12 edit-view-row-item"> \
            <div class="col-xs-12 col-sm-4 label" data-label="LBL_SELECT_TEMPLATE_PLUMBING">Plumbing Template:</div> \
                <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="select_template_plumbing"> \
                <select name="select_template_plumbing" id="select_template_plumbing" title=""> \
                    <option>Select Template Plumbing</option> \
                </select> \
            </div> \
        </div>';
        $('#plumbing_notes_c').parents('.edit-view-row-item').append(html_select_plumbing_template);
        $("#dialog_plumbing_notes_button").click(function(e){
            SUGAR.ajaxUI.showLoadingPanel();
            $("#ajaxloading_mask").css("position",'fixed');
            $.ajax({
                url: 'index.php?entryPoint=CRUD_Plumbing_Notes' ,
                type: 'POST',
                data: 
                {
                    action: 'read',
                },
                async: true,
                success: function(result) {                         
                    render_select_plumbing_template(result);
                    SUGAR.ajaxUI.hideLoadingPanel();
                    $("#dialog_plumbing_notes" ).dialog("open");
                }
            }); 
            return false;
        })

        $('#select_title_template_plumbing_notes').change(function(){
            var id = $('#select_title_template_plumbing_notes').val();
            if(id == '') return false;
            var title = $('#select_title_template_plumbing_notes option:selected').text();
            $("#title_plumbing_template").val(title);
            $("#id_plumbing_template").val(id);
            $("#content_plumbing_template").val(window.data_plumbing_notes[id].content);
        });

        function render_select_plumbing_template(result){
            if(result == '' || typeof result === 'undefined' ) return;
            try {
                var data_result = JSON.parse(result);
                window.data_plumbing_notes = data_result;
                $('#select_title_template_plumbing_notes').empty();
                $("#select_template_plumbing").empty();
                $('#select_title_template_plumbing_notes').append($('<option>', {
                    value: '',
                    text: ''
                }));
                $('#select_template_plumbing').append($('<option>', {
                    value: '',
                    text: 'Select Template Plumbing'
                }));
                $.each(data_result,function(k,v){
                    $('#select_title_template_plumbing_notes').append($('<option>', {
                        value: k,
                        text: v.title
                    }));
                    $('#select_template_plumbing').append($('<option>', {
                        value: k,
                        text: v.title
                    }));
                });
                autosize.update($("#plumbing_notes_c")); 
            } catch (err) {
                console.log('Invoice-Edit >> render_select_plumbing_template: '+err);
            }
        }
        // run first time
        $.ajax({
            url: 'index.php?entryPoint=CRUD_Plumbing_Notes' ,
            type: 'POST',
            data: 
            {
                action: 'read',
            },
            async: true,
            success: function(result) {   
                if(result == '' || typeof result === 'undefined')return;
                render_select_plumbing_template(result);
            }
        }); 

        $(document).find('#select_template_plumbing').change(function(){
            var id = $('#select_template_plumbing').val();
            if(id == '') return false;
            if($("#plumbing_notes_c").val() == ''){
                $("#plumbing_notes_c").val(window.data_plumbing_notes[id].content);
            }else{
                var $txt = $("#plumbing_notes_c");
                var caretPos = $txt[0].selectionStart;
                var textAreaTxt = $txt.val();
                var txtToAdd = window.data_plumbing_notes[id].content;
                $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
            }
            autosize.update($("#plumbing_notes_c"));
        });

        //end dung code-- popup template Plumbing Notes

        //dung code-- popup template Electrical Notes
        $( "#dialog_electrical_notes" ).dialog({
            autoOpen: false,
            width: 712,
            height:478,
            buttons: {
                Save: function(){
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    //create new
                    if($("#id_electrical_template").val() == '') {
                        if($("#title_electrical_template").val() == ''){
                            alert('Could you insert title please?');
                            SUGAR.ajaxUI.hideLoadingPanel();
                            return false;
                        };
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_Electrical_Notes' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_electrical_template").val(),
                                action: 'create',
                                content: encodeURIComponent($("#content_electrical_template").val()),
                                title: encodeURIComponent($("#title_electrical_template").val())
                            },
                            success: function(result) {              
                                render_select_electrical_template(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }
                        }); 
                    }   
                    //update
                    else{
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_Electrical_Notes' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_electrical_template").val(),
                                action: 'update',
                                content: encodeURIComponent($("#content_electrical_template").val()),
                                title: encodeURIComponent($("#title_electrical_template").val())
                            },
                            success: function(result) {                         
                                render_select_electrical_template(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }
                        }); 
                    }
                    $("#electrical_notes_c").val( $("#content_electrical_template").val());
                    autosize.update($("#electrical_notes_c"));  
                    $(this).dialog('close');
                },
                Create: function(){
                    $("#id_electrical_template").val('');
                    $("#title_electrical_template").val('');
                    $("#content_electrical_template").val('');
                },
                Insert: function(){
                    $("#electrical_notes_c").val( $("#content_electrical_template").val());
                    autosize.update($("#electrical_notes_c"));       
                    $(this).dialog('close');
                },
                Delete: function(){
                    var ok = confirm('Do you want delete Template !');
                    if (ok){
                        SUGAR.ajaxUI.showLoadingPanel();
                        $("#ajaxloading_mask").css("position",'fixed');
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_Electrical_Notes' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_electrical_template").val(),
                                action: 'delete',
                                content: encodeURIComponent($("#content_electrical_template").val()),
                                title: encodeURIComponent($("#title_electrical_template").val())
                            },
                            success: function(result) {                         
                                render_select_electrical_template(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                                $("#content_electrical_template").val('');
                                $("#title_electrical_template").val('');
                                $("#id_electrical_template").val('');
                            }
                        }); 
                    }
                },
                Cancel: function(){
                    $(this).dialog('close');
                },
            }
        });
        $('#electrical_notes_c').parent().siblings('.label').append('<br> <button class="button primary" id="dialog_electrical_notes_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Edit Templates</button>');
        
        var html_select_electrical_template = 
        '<div class="col-xs-12 col-sm-12 edit-view-row-item"> \
            <div class="col-xs-12 col-sm-4 label" data-label="LBL_SELECT_TEMPLATE_ELECTRICAL">Electrical Template:</div> \
                <div class="col-xs-12 col-sm-8 edit-view-field " type="enum" field="select_template_electrical"> \
                <select name="select_template_electrical" id="select_template_electrical" title=""> \
                    <option>Select Template Electrical</option> \
                </select> \
            </div> \
        </div>';
        $('#electrical_notes_c').parents('.edit-view-row-item').append(html_select_electrical_template);

        $("#dialog_electrical_notes_button").click(function(e){
            SUGAR.ajaxUI.showLoadingPanel();
            $("#ajaxloading_mask").css("position",'fixed');
            $.ajax({
                url: 'index.php?entryPoint=CRUD_Electrical_Notes' ,
                type: 'POST',
                data: 
                {
                    action: 'read',
                },
                async: true,
                success: function(result) {                         
                    render_select_electrical_template(result);
                    SUGAR.ajaxUI.hideLoadingPanel();
                    $("#dialog_electrical_notes" ).dialog("open");
                }
            }); 
            return false;
        })

        $('#select_title_template_electrical_notes').change(function(){
            var id = $('#select_title_template_electrical_notes').val();
            if(id == '') return false;
            var title = $('#select_title_template_electrical_notes option:selected').text();
            $("#title_electrical_template").val(title);
            $("#id_electrical_template").val(id);
            $("#content_electrical_template").val(window.data_electrical_notes[id].content);
        });

        function render_select_electrical_template(result){
            if(result == '' || typeof result === 'undefined')return;
            try {
                var data_result = $.parseJSON(result);
                window.data_electrical_notes = data_result;
                $('#select_title_template_electrical_notes').empty();
                $("#select_template_electrical").empty();
                $('#select_title_template_electrical_notes').append($('<option>', {
                    value: '',
                    text: ''
                }));
                $('#select_template_electrical').append($('<option>', {
                    value: '',
                    text: 'Select Template Electrical'
                }));
                $.each(data_result,function(k,v){
                    $('#select_title_template_electrical_notes').append($('<option>', {
                        value: k,
                        text: v.title
                    }));
                    $('#select_template_electrical').append($('<option>', {
                        value: k,
                        text: v.title
                    }));
                });
                autosize.update($("#electrical_notes_c")); 
            } catch(err) {
                console.log('Invoice-Edit >> render_select_electrical_template: '+err);
            }
        }

        // run first time
        $.ajax({
            url: 'index.php?entryPoint=CRUD_Electrical_Notes' ,
            type: 'POST',
            data: 
            {
                action: 'read',
            },
            async: true,
            success: function(result) {                         
                render_select_electrical_template(result);
            }
        }); 

        $(document).find('#select_template_electrical').change(function(){
            var id = $('#select_template_electrical').val();
            if(id == '') return false;
            
            if($("#electrical_notes_c").val() == ''){
                $("#electrical_notes_c").val(window.data_electrical_notes[id].content);
            autosize.update($("#electrical_notes_c")); 
            }else{
                var $txt = $("#electrical_notes_c");
                var caretPos = $txt[0].selectionStart;
                var textAreaTxt = $txt.val();
                var txtToAdd = window.data_electrical_notes[id].content;
                $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
            }
            autosize.update($("#electrical_notes_c")); 
        });

        //end dung code-- popup template Electrical Notes
    });
});


//VUT-S- Quote note for pdf
$(document).ready(function() {
    if (module_sugar_grp1 == 'AOS_Quotes') {
        $('#quote_note_c').parent().siblings('.label').append("<br> <select style='width:68%;' name='slb_quote_note' id='slb_quote_note' style='width:100%;'><option></option></select>");
        $('#quote_note_c').parent().siblings('.label').append('<br> <button class="button primary" id="dialog_quote_notes_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Edit Templates</button>');
        /**Auto first run */
        $.ajax({
            url: 'index.php?entryPoint=CRUD_quote_note' ,
            type: 'POST',
            data: 
            {
                action: 'read',
            },
            async: true,
            success: function(result) {                         
                render_select_template_quote(result);
            }
        }); 
    
        $(document).find("#slb_quote_note").on("change",function(){
            $(document).find("#quote_note_c").val($(this).val());
        });

        $("#dialog_quote_notes_button").click(function(e){
            SUGAR.ajaxUI.showLoadingPanel();
            $("#ajaxloading_mask").css("position",'fixed');
            $.ajax({
                url: 'index.php?entryPoint=CRUD_quote_note' ,
                type: 'POST',
                data: 
                {
                    action: 'read',
                },
                async: true,
                success: function(result) {        
                    // debugger;                 
                    render_select_template_quote(result);
                    SUGAR.ajaxUI.hideLoadingPanel();
                    $("#dialog_quote_note_pdf").dialog("open");
                }
            }); 
            return false;
        })

        $("#dialog_quote_note_pdf").dialog({
            autoOpen: false,
            width: 712,
            height:478,
            buttons: {
                Save: function(){
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    //create new
                    if($("#id_template_quote_note_pdf").val() == '') {
                        if($("#title_quote_note_pdf").val() == ''){
                            alert('Could you insert title please?');
                            SUGAR.ajaxUI.hideLoadingPanel();
                            return false;
                        };
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_quote_note' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_template_quote_note_pdf").val(),
                                action: 'create',
                                content: encodeURIComponent($("#content_quote_note").val()),
                                title: encodeURIComponent($("#title_quote_note_pdf").val())
                            },
                            success: function(result) {              
                                render_select_template_quote(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }
                        }); 
                    }   
                    //update
                    else{
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_quote_note' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_template_quote_note_pdf").val(),
                                action: 'update',
                                content: encodeURIComponent($("#content_quote_note").val()),
                                title: encodeURIComponent($("#title_quote_note_pdf").val())
                            },
                            success: function(result) {                         
                                render_select_template_quote(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }
                        }); 
                    }
                    $("#quote_note_c").val( $("#content_quote_note").val());  
                    $(this).dialog('close');
                },
                Create: function(){
                    $("#id_template_quote_note_pdf").val('');
                    $("#content_quote_note").val('');
                    $("#title_quote_note_pdf").val('');
                },
                Insert: function(){
                    $("#quote_note_c").val( $("#content_quote_note").val());      
                    $(this).dialog('close');
                },
                Delete: function(){
                    var ok = confirm('Do you want delete Template !');
                    if (ok){
                        SUGAR.ajaxUI.showLoadingPanel();
                        $("#ajaxloading_mask").css("position",'fixed');
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_quote_note' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_template_quote_note_pdf").val(),
                                action: 'delete',
                                content: encodeURIComponent($("#content_quote_note").val()),
                                title: encodeURIComponent($("#title_quote_note_pdf").val())
                            },
                            success: function(result) {                         
                                render_select_template_quote(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                                $("#content_quote_note").val('');
                                $("#title_quote_note_pdf").val('');
                                $("#id_template_quote_note_pdf").val('');
                            }
                        }); 
                    }
                },
                Cancel: function(){
                    $(this).dialog('close');
                },
            }
        });

        $('#select_title_quote_note_pdf').change(function(){
            var id = $('#select_title_quote_note_pdf').val();
            if(id == '') return false;
            var title = $('#select_title_quote_note_pdf option:selected').text();
            $("#title_quote_note_pdf").val(title);
            $("#id_template_quote_note_pdf").val(id);
            $("#content_quote_note").val(window.data_quote_comment[id].content);
            $("#quote_note_c").val(window.data_quote_comment[id].content);
            autosize.update($("#quote_note_c")); 
        });

        function render_select_template_quote(result){
            if(result == '' || typeof result === 'undefined') return; //check Json
            try {
                var data_result = JSON.parse(result);
                // debugger;
                window.data_quote_comment = data_result;
                $('#select_title_quote_note_pdf').empty();
                $('#select_title_quote_note_pdf').append($('<option>', {
                    value: '',
                    text: ''
                }));
                $(document).find("#slb_quote_note").empty();
                $('#slb_quote_note').append($('<option>', {
                    value: '',
                    text: ''
                }));
                $.each(data_result,function(k,v){
                    // debugger;
                    $('#select_title_quote_note_pdf').append($('<option>', {
                        value: k,
                        text: v.title
                    }));
                    $(document).find("#slb_quote_note").append($('<option>', {
                        value: v.content,
                        text: v.title,
                        IdTemplate: k
                    }));
                    });
                autosize.update($('#quote_note_c'));
            } catch (err) {
                console.log('Invoice-Edit >> render_select_template_quote: '+err);
            }
        }
    }
});

//VUT-S-Seek install date in Quote
$(document).ready(function(){
    if (module_sugar_grp1 == "AOS_Quotes") {
        function display_link_contact_plum_elec_quote() {
            var sanden_installer_id = $('#account_id3_c').val();
            var sanden_electrician_id = $('#account_id2_c').val();
            var daikin_installer_id = $('#account_id4_c').val();
            var solar_installer_account_id = $('#proposed_solar_installer_acccount_id').val();

            $("#link_account_sanden_installer").remove();
            if( sanden_installer_id != ''){
                $("#account_id3_c").parent().append("<p id='link_account_sanden_installer'><a  href='/index.php?module=Accounts&action=EditView&record=" + sanden_installer_id+ "' target='_blank'>Open Account</a></p>");
            }
            $("#link_account_sanden_electrician").remove();
            if( sanden_electrician_id != ''){
                $("#account_id2_c").parent().append("<p id='link_account_sanden_electrician'><a  href='/index.php?module=Accounts&action=EditView&record=" + sanden_electrician_id+ "' target='_blank'>Open Account</a></p>");
            }
            $("#link_account_daikin_installer").remove();
            if( daikin_installer_id != ''){
                $("#account_id4_c").parent().append("<p id='link_account_daikin_installer'><a  href='/index.php?module=Accounts&action=EditView&record=" + daikin_installer_id+ "' target='_blank'>Open Account</a></p>");
            }
            $('.display_link_contact_plum_elec_quote').remove();
            $.ajax({
                url: "?entryPoint=getContactFromAccount&request=custom_display_link_contact_plum_elec_quote&sanden_electrician_id=" + sanden_electrician_id+"&sanden_installer_id="+sanden_installer_id+"&daikin_installer_id="+daikin_installer_id+"&solar_installer_id="+solar_installer_account_id,
           }).done(function (data) {
                if(data == '' || typeof data == 'undefined')return;
               var json = $.parseJSON(data);
               
               $("#link_account_sanden_installer").remove();
               if( $("#account_id3_c").val() != ''){
                   $("#account_id3_c").parent().append("<p class='display_link_contact_plum_elec_quote' id='link_account_sanden_installer'><a  href='/index.php?module=Accounts&action=EditView&record=" + $('#account_id3_c').val()+ "' target='_blank'>Open Account</a></p>");
                   if(json.sanden_installer_contact != '') $("#account_id3_c").parent().append("<p class='display_link_contact_plum_elec_quote' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.sanden_installer_contact+ "' target='_blank'>Open Primary Contact</a><input type='hidden' id='sanden_installer_contact_id' value='"+json.sanden_installer_contact+"'></p>");
               }

               $("#link_account_sanden_electrician").remove();
               if( $("#account_id2_c").val() != ''){
                   $("#account_id2_c").parent().append("<p class='display_link_contact_plum_elec_quote' id='link_account_sanden_electrician'><a  href='/index.php?module=Accounts&action=EditView&record=" + $('#account_id2_c').val()+ "' target='_blank'>Open Account</a></p>");
                   if(json.sanden_electrician_contact != '') $("#account_id2_c").parent().append("<p class='display_link_contact_plum_elec_quote' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.sanden_electrician_contact+ "' target='_blank'>Open Primary Contact</a><input type='hidden' id='sanden_electrician_contact_id' value='"+json.sanden_electrician_contact+"'></p>");
               }

               $("#link_account_daikin_installer").remove();
               if( $("#account_id4_c").val() != ''){
                   $("#account_id4_c").parent().append("<p class='display_link_contact_plum_elec_quote' id='link_account_daikin_installer'><a  href='/index.php?module=Accounts&action=EditView&record=" + $('#account_id4_c').val()+ "' target='_blank'>Open Account</a></p>");
                   if(json.daikin_installer_contact != '') $("#account_id4_c").parent().append("<p class='display_link_contact_plum_elec_quote' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.daikin_installer_contact+ "' target='_blank'>Open Primary Contact</a><input type='hidden' id='daikin_installer_contact_id' value='"+json.daikin_installer_contact+"'></p>");
               }
               $("#link_account_solar_installer").remove();
               if( $('#proposed_solar_installer_acccount_id').val() != ''){
                $("#proposed_solar_installer_acccount_id").parent().append("<p class='display_link_contact_plum_elec_quote' id='link_account_solar_installer'><a  href='/index.php?module=Accounts&action=EditView&record=" + $('#proposed_solar_installer_acccount_id').val()+ "' target='_blank'>Open Account</a></p>");
                if(json.solar_installer_contact != '') $("#proposed_solar_installer_acccount_id").parent().append("<p class='display_link_contact_plum_elec_quote' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.solar_installer_contact+ "' target='_blank'>Open Primary Contact</a><input type='hidden' id='solar_installer_contact_id' value='"+json.solar_installer_contact+"'></p>");
            }
     
            });
        }
        display_link_contact_plum_elec_quote();
        YAHOO.util.Event.addListener(["account_id2_c","account_id3_c","account_id4_c", "proposed_solar_installer_acccount_id"], "change", display_link_contact_plum_elec_quote);
    
        $('#plumber_new_c').parent().siblings('.label').append('<br> <button class="button primary" id="seekInstallationDate_SandenInstaller"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Seek Install Date</button>');
        $('#plumber_electrician_c').parent().siblings('.label').append('<br> <button class="button primary" id="seekInstallationDate_SandenElec"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Seek Install Date</button>');
        $('#daikin_installer_c').parent().siblings('.label').append('<br> <button class="button primary" id="seekInstallationDate_DaikinInstaller"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Seek Install Date</button>');
        $('#proposed_solar_installer_acccount').parent().siblings('.label').append('<br> <button class="button primary" id="seekInstallationDate_SolarInstaller"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Seek Install Date</button>');
        /**Sanden Installer - Seek Install Date Button */
        $("#seekInstallationDate_SandenInstaller").click(function(){
            $('#seekInstallationDate_SandenInstaller span.glyphicon-refresh').removeClass('hidden');
            if($("#plumber_new_c").val() == ""){
                alert("Please enter Sanden Installer name.");
                $('#seekInstallationDate_SandenInstaller span.glyphicon-refresh').addClass('hidden');
                $("#plumber_new_c").focus();
                return false;
            }
            var record_id = $("input[name='record']").val();
            setTimeout(function(){
                $.ajax({
                    url: "?entryPoint=seekInstallationDate_Quote&button=sanden_installer&record_id=" + record_id + "&account_id="+ $("#account_id3_c").val()+ "&contact_id="+$('#sanden_installer_contact_id').val(),
                    context: document.body,
                    async: true
                }).done(function (data) {
                    $('#seekInstallationDate_SandenInstaller span.glyphicon-refresh').addClass('hidden');
                    window.open(data,'_blank');
                    return false;
                });
            },100);
            return false;
        });
        /**Sanden Electrician - Seek Install Date Button */
        $("#seekInstallationDate_SandenElec").click(function(){
            $('#seekInstallationDate_SandenElec span.glyphicon-refresh').removeClass('hidden');
            if($("#plumber_electrician_c").val() == ""){
                alert("Please enter Sanden Electrician name.");
                $('#seekInstallationDate_SandenElec span.glyphicon-refresh').addClass('hidden');
                $("#plumber_electrician_c").focus();
                return false;
            }
            var record_id = $("input[name='record']").val();
            setTimeout(function(){
                $.ajax({
                    url: "?entryPoint=seekInstallationDate_Quote&button=sanden_electrician&record_id=" + record_id + "&account_id="+ $("#account_id2_c").val()+ "&contact_id="+$('#sanden_electrician_contact_id').val(),
                    context: document.body,
                    async: true
                }).done(function (data) {
                    if(data == '' || typeof data == 'undefined') {
                        $('#seekInstallationDate_SandenElec span.glyphicon-refresh').addClass('hidden');
                        return;
                    }
                    $('#seekInstallationDate_SandenElec span.glyphicon-refresh').addClass('hidden');
                    window.open(data,'_blank');
                    return false;
                });
            },100);
            return false;
        });
        /**Daikin Electrician - Seek Install Date Button */
        $("#seekInstallationDate_DaikinInstaller").click(function(){
            $('#seekInstallationDate_DaikinInstaller span.glyphicon-refresh').removeClass('hidden');
            if($("#daikin_installer_c").val() == ""){
                alert("Please enter Daikin Installer name.");
                $('#seekInstallationDate_DaikinInstaller span.glyphicon-refresh').addClass('hidden');
                $("#daikin_installer_c").focus();
                return false;
            }
            var record_id = $("input[name='record']").val();
            setTimeout(function(){
                $.ajax({
                    url: "?entryPoint=seekInstallationDate_Quote&button=daikin_installer&record_id=" + record_id + "&account_id="+ $("#account_id4_c").val()+ "&contact_id="+$('#daikin_installer_contact_id').val(),
                    context: document.body,
                    async: true
                }).done(function (data) {
                    if(data == '' || typeof data == 'undefined') {
                        $('#seekInstallationDate_DaikinInstaller span.glyphicon-refresh').addClass('hidden');
                        return;
                    }
                    $('#seekInstallationDate_DaikinInstaller span.glyphicon-refresh').addClass('hidden');
                    window.open(data,'_blank');
                    return false;
                });
            },100);
            return false;
        });
        /**Solar installer - Seek Install Date Button */
        $("#seekInstallationDate_SolarInstaller").click(function(){
            $('#seekInstallationDate_SolarInstaller span.glyphicon-refresh').removeClass('hidden');
            if($("#proposed_solar_installer_acccount").val() == ""){
                alert("Please enter Solar Installer name.");
                $('#seekInstallationDate_SolarInstaller span.glyphicon-refresh').addClass('hidden');
                $("#proposed_solar_installer_acccount").focus();
                return false;
            }
            var record_id = $("input[name='record']").val();
            setTimeout(function(){
                $.ajax({
                    url: "?entryPoint=seekInstallationDate_Quote&button=solar_installer&record_id=" + record_id + "&account_id="+ $("#proposed_solar_installer_acccount_id").val()+ "&contact_id="+$('#solar_installer_contact_id').val(),
                    context: document.body,
                    async: true
                }).done(function (data) {
                    if(data == '' || typeof data == 'undefined') {
                        $('#seekInstallationDate_SolarInstaller span.glyphicon-refresh').addClass('hidden');
                        return;
                    }
                    $('#seekInstallationDate_SolarInstaller span.glyphicon-refresh').addClass('hidden');
                    window.open(data,'_blank');
                    return false;
                });
            },100);
            return false;
        });

    }

    /**Formbay solargain */
    if (module_sugar_grp1 == "AOS_Invoices")  {
        if ($('#quote_type_c').val()=="quote_type_solar") {
            $('#formbay_c').parent().parent().show();
            $('#formbay_c').after('<button type="button" class="button primary" id="check_formbay" style="margin-left: 10px;"><span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Check Formbay</button>');
        } else {
            $('#formbay_c').parent().parent().hide();
        }
        $('#quote_type_c').change(function(){
            if ($('#quote_type_c').val()=="quote_type_solar") {
                $('#formbay_c').parent().parent().show();
                $('#check_formbay').remove();
                $('#formbay_c').after('<button type="button" class="button primary" id="check_formbay" style="margin-left: 10px;"><span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Check Formbay</button>');
            } else {
                $('#formbay_c').parent().parent().hide();
            }
        });
        $(document).on("click","#check_formbay", function() {
            var sg_order_number = $('#solargain_invoices_number_c').val();
            // debugger;
            if (sg_order_number == "") {
                alert('Please enter Solargain Order Number!');
                $("#solargain_invoices_number_c").first().focus();
            } else {
                $.ajax({
                    url: "index.php?entryPoint=checkFormBayTabSG&sg_order_number="+sg_order_number,
                    type:"GET",
                    success:function (data) {
                        // debugger;
                        if(data == '' || typeof data == 'undefined') return;
                        var result = JSON.parse(data);
                        if (result['formbayID'] !== null) {
                            $('#formbay_c').prop('checked', true);
                            // alert('Formbay ID is '+result['formbayID']+'. User login: '+result['user']);
                        } else if (result['formbayID'] == null && result['message'] == null ) {
                            $('#formbay_c').prop('checked', false);
                            alert('Don\'t have Formbay Tab.');
                        }
                        else {
                            $('#formbay_c').prop('checked', false);
                            alert('Solargain '+result['message']);
                        }
                    }
                })
            }
        });  
    }
    /**End-Formbay SG */

    //Thienpb - code change status.
    if (module_sugar_grp1 == "AOS_Invoices") {
        var previous = '';
        $("#status").on('focus', function () {
            previous = this.value;
        }).change(function() {
            // debugger;
            var xeroType = "";
            if(previous == 'STC_Unpaid' && $(this).val() == 'Paid'){
                xeroType = "STC";
            }else if(previous == 'STC_VEEC_Unpaid' && $(this).val() == 'STC_Unpaid'){
                xeroType = "VEEC";
            }
            if(xeroType != ""){
                SUGAR.ajaxUI.showLoadingPanel();
                $.ajax({
                    url: "index.php?entryPoint=xeroInvoiceSTC&method=create&xeroType="+xeroType+"&record="+ encodeURIComponent($('input[name="record"]').val()),
                    success: function (data) {
                        if(typeof data !== 'undefined'){
                            var data_parse = $.parseJSON(data);
                            setTimeout(() => {
                                if(data_parse.status.trim("") == 'Ok'){
                                    alert('Push PO to XERO Successfully.');
                                    window.open('https://go.xero.com/AccountsReceivable/View.aspx?InvoiceID='+data_parse.xeroID);
                                    if($("#xero_stc_rebate_invoice_c").val() != '' && xeroType == "STC"){
                                        window.open('https://go.xero.com/AccountsReceivable/View.aspx?InvoiceID='+$("#xero_stc_rebate_invoice_c").val());
                                    }
                                    if($("#xero_veec_rebate_invoice_c").val() != '' && xeroType == "VEEC"){
                                        window.open('https://go.xero.com/AccountsReceivable/View.aspx?InvoiceID='+$("#xero_veec_rebate_invoice_c").val());
                                    }
                                }else{
                                    if(data_parse.status.trim("") == 'Fail'){
                                        alert('We can\'t push to XERO.');
                                    }else{
                                        alert(data_parse.status.trim(""));
                                    }
                                }
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }, 300);
                        }
                    }
                });
            }
        });
        //hide field not use 
        $('#delivery_contact_address_c,#delivery_contact_suburb_c,#delivery_contact_state_c,#delivery_contact_postcode_c').closest('.edit-view-row-item').hide();
        
        //thienpb
        $('#order_number_c').parent().siblings('.label').append('<br> <button type="button" class="button primary" id="createLabelAuspost"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create Label Auspost</button>');
        $('#createLabelAuspost').on("click", function(e){
            if($("#order_number_c").val() != ""){
                var url_getStatusSAM = '/index.php?entryPoint=ManuallyCreateLabel&order_number=' + $("#order_number_c").val();
                $.ajax({
                    url:url_getStatusSAM,
                    success: function (data) {
                        if(data.replace(/\s/g, '') != ''){
                            alert("Successfully created Auspost Label.");
                            $("#order_number_c").parent().append("<p id='link_order'><a  href='https://auspost.com.au/mypost-business/shipping-and-tracking/orders/edit/retail/"+data+"' target='_blank'>Open detail the newly created auspost</a></p>");
                        }else{
                            alert("We can not create label manually.");
                        }   
                    }
                })
            }else if($('#quote_number').val() != '') {
                var url_getStatusSAM = '/index.php?entryPoint=ManuallyCreateLabel&quote_number=' + $("#quote_number").val();
                $.ajax({
                    url:url_getStatusSAM,
                    success: function (data) {
                        if(data.replace(/\s/g, '') != ''){
                            alert("Successfully created Auspost Label.");
                            $("#order_number_c").parent().append("<p id='link_order'><a  href='https://auspost.com.au/mypost-business/shipping-and-tracking/orders/edit/retail/"+data+"' target='_blank'>Open detail the newly created auspost</a></p>");
                        }else{
                            alert("We can not create label manually.");
                        }   
                    }
                })
            } else{
                alert("Order Number and Quote number is not found!");
            }
            
        })
    }
});
//VUT-E-Seek install date in Quote

////VUT <<< Invoice - Customer Invoice Note Look up  field invoice_note_c
$(document).ready(function() {
    if (module_sugar_grp1 == 'AOS_Invoices') {
        $( "#dialog_customer_inv_note_pdf" ).dialog({
            autoOpen: false,
            width: 712,
            height:478,
            buttons: {
                Save: function(){
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    //create new
                    if($("#id_customer_inv_template").val() == '') {
                        if($("#title_custome_inv_template").val() == ''){
                            alert('Could you insert title please?');
                            SUGAR.ajaxUI.hideLoadingPanel();
                            return false;
                        };
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_Customer_Invoice_Notes' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_customer_inv_template").val(),
                                action: 'create',
                                content: encodeURIComponent($("#content_customer_inv_template").val()),
                                title: encodeURIComponent($("#title_custome_inv_template").val())
                            },
                            success: function(result) {              
                                render_select_customer_inv_template(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }
                        }); 
                    }   
                    //update
                    else{
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_Customer_Invoice_Notes' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_customer_inv_template").val(),
                                action: 'update',
                                content: encodeURIComponent($("#content_customer_inv_template").val()),
                                title: encodeURIComponent($("#title_custome_inv_template").val())
                            },
                            success: function(result) {                         
                                render_select_customer_inv_template(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }
                        }); 
                    }
                    $("#invoice_note_c").val( $("#content_customer_inv_template").val());
                    autosize.update($("#invoice_note_c"));  
                    $(this).dialog('close');
                },
                Create: function(){
                    $("#id_customer_inv_template").val('');
                    $("#title_custome_inv_template").val('');
                    $("#content_customer_inv_template").val('');
                },
                Insert: function(){
                    $("#invoice_note_c").val( $("#content_customer_inv_template").val());
                    autosize.update($("#invoice_note_c"));       
                    $(this).dialog('close');
                },
                Delete: function(){
                    var ok = confirm('Do you want delete Template !');
                    if (ok){
                        SUGAR.ajaxUI.showLoadingPanel();
                        $("#ajaxloading_mask").css("position",'fixed');
                        $.ajax({
                            url: 'index.php?entryPoint=CRUD_Customer_Invoice_Notes' ,
                            type: 'POST',
                            data: 
                            {
                                id: $("#id_customer_inv_template").val(),
                                action: 'delete',
                                content: encodeURIComponent($("#content_customer_inv_template").val()),
                                title: encodeURIComponent($("#title_custome_inv_template").val())
                            },
                            success: function(result) {                         
                                render_select_customer_inv_template(result);
                                SUGAR.ajaxUI.hideLoadingPanel();
                                $("#content_customer_inv_template").val('');
                                $("#title_custome_inv_template").val('');
                                $("#id_customer_inv_template").val('');
                            }
                        }); 
                    }
                },
                Cancel: function(){
                    $(this).dialog('close');
                },
            }
        });
        $('#invoice_note_c').parent().siblings('.label').append("<br> <select style='width:68%;' name='select_template_customer_inv' id='select_template_customer_inv' style='width:100%;'><option>Select Template Customer</option></select>");
        $('#invoice_note_c').parent().siblings('.label').append('<br> <button class="button primary" id="dialog_customer_inv_note_pdf_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Edit Templates</button>');

        $("#dialog_customer_inv_note_pdf_button").click(function(e){
            SUGAR.ajaxUI.showLoadingPanel();
            $("#ajaxloading_mask").css("position",'fixed');
            $.ajax({
                url: 'index.php?entryPoint=CRUD_Customer_Invoice_Notes' ,
                type: 'POST',
                data: 
                {
                    action: 'read',
                },
                async: true,
                success: function(result) {                         
                    render_select_customer_inv_template(result);
                    SUGAR.ajaxUI.hideLoadingPanel();
                    $("#dialog_customer_inv_note_pdf" ).dialog("open");
                }
            }); 
            return false;
        })

        $('#select_title_template_customer_inv_notes').change(function(){
            var id = $('#select_title_template_customer_inv_notes').val();
            if(id == '') return false;
            var title = $('#select_title_template_customer_inv_notes option:selected').text();
            $("#title_custome_inv_template").val(title);
            $("#id_customer_inv_template").val(id);
            $("#content_customer_inv_template").val(window.data_custome_inv_notes[id].content);
        });

        function render_select_customer_inv_template(result){
            if(result == '' || typeof result === 'undefined')return;
            try {
                var data_result = JSON.parse(result);
                window.data_custome_inv_notes = data_result;
                $('#select_title_template_customer_inv_notes').empty();
                $("#select_template_customer_inv").empty();
                $('#select_title_template_customer_inv_notes').append($('<option>', {
                    value: '',
                    text: ''
                }));
                $('#select_template_customer_inv').append($('<option>', {
                    value: '',
                    text: 'Select Template Customer'
                }));
                $.each(data_result,function(k,v){
                    $('#select_title_template_customer_inv_notes').append($('<option>', {
                        value: k,
                        text: v.title
                    }));
                    $('#select_template_customer_inv').append($('<option>', {
                        value: k,
                        text: v.title
                    }));
                });
                autosize.update($("#invoice_note_c")); 
            } catch (err) {
                console.log('Invoice-Edit >> select_customer_inv_template: '+err);
            }
        }

        // run first time
        $.ajax({
            url: 'index.php?entryPoint=CRUD_Customer_Invoice_Notes' ,
            type: 'POST',
            data: 
            {
                action: 'read',
            },
            async: true,
            success: function(result) {                         
                render_select_customer_inv_template(result);
            }
        }); 

        $(document).find('#select_template_customer_inv').change(function(){
            var id = $('#select_template_customer_inv').val();
            if(id == '') return false;
            $("#invoice_note_c").val(window.data_custome_inv_notes[id].content);
            // if($("#invoice_note_c").val() == ''){
            //     $("#invoice_note_c").val(window.data_custome_inv_notes[id].content);
            //     autosize.update($("#invoice_note_c")); 
            // }else{
            //     var $txt = $("#invoice_note_c");
            //     var caretPos = $txt[0].selectionStart;
            //     var textAreaTxt = $txt.val();
            //     var txtToAdd = window.data_custome_inv_notes[id].content;
            //     $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
            // }
            autosize.update($("#invoice_note_c")); 
        });
    }
});
//VUT >>> Invoice - Customer Invoice Note Look up  field invoice_note_c

//VUT-S-Create popup when click Sandan Tip
function popupSandenProduct(e) {
    var popupList = $('<div id="popupSanden" title="Sanden Product">'
                    + '<input name="sandenProduct" type="radio" value="G2">G2<br>'
                    + '<input name="sandenProduct" type="radio" value="G3">G3<br>'
                    + '<input name="sandenProduct" type="radio" value="G4">G4<br>'
                    + '</div>');
    popupList.dialog({
        modal:true,
        buttons: {
            Cancel : function(){
                $(this).dialog("close");
            },
            OK : function() {
                $(e).attr('data-sanden-product', $('input[name="sandenProduct"]:checked').val());
                $(document).openComposeViewModal_US7_and_Sanden_Tips(e);
                $(this).dialog("close");

            }
        }
    });
}
//VUT-S-Create popup when click Sandan Tip

function get_sanden_model(){    
    var returnSandenModel='';     
    $('#lineItems').find('input').each( function(index){                            
        var selector = $('#product_part_number'+index).val();
        if(typeof(selector) == 'string') {
            if(selector.includes('GAUS-')){
                returnSandenModel = selector;
                return false;
            }    
        }
    });
    $('#sanden_model_c').val(returnSandenModel);
    return returnSandenModel;
}

/**
 * VUT- check have proposed install location photo
 */
function check_proposed_ins_localtion() {
    var files = $('#fileupload .files').find('p.name');
    var check = false;
    files.each(function(evt, ele){
        // debugger;
        const filename = $(this).text();
        if (filename.indexOf("Proposed_Install_Location") != -1) {
            check = true
            return false;
        }
        return true;
    });
    return check;
}

/**
 * VUT- Send email promo code Methven
 */
 function promo_code_methven(e) {
    // debugger
    let i;
    let promo_codes = [];
    var html_radio_promo_code = '';
    for (i = 0 ; i < 3 ; i++ ) {
    //    if ($(`#promo_methven_${i+1}_c`).prop('checked'))
        if ($(`#promo_methven_${i+1}_c`).is(':checked')) {
            promo_codes.push($(`#handheld_${i+1}_c`).val());
           // html_radio_promo_code +='<input type="radio" checked name="radio_promo_code" class="radio_promo_code" value="'+$(`#handheld_${i+1}_c`).val()+'">' + $(`#handheld_${i+1}_c`).parents().eq(1).find('div.label').text() + ': ' + $(`#handheld_${i+1}_c`).val() +'<br>'; 
            html_radio_promo_code += '<div class="radio">\
                <label><input type="radio" name="radio_promo_code" class="radio_promo_code" value="'+$(`#handheld_${i+1}_c`).val()+'">' + $(`#handheld_${i+1}_c`).parents().eq(1).find('div.label').text()  + $(`#handheld_${i+1}_c`).val()  +'</label>\
            </div>';
        }
        // if ($(`#handheld_${i+1}_c`).val() != '') {
        //     promo_codes.push($(`#handheld_${i+1}_c`).val());
        // }
    }
    
    $(".checkbox_promo_code_customize").each(function() {
        if ($(this).is(':checked')) {
            promo_codes.push($(this).attr('id'));
            var label_discount = $(this).parents().eq(1).find('.label-discount').text();
            html_radio_promo_code += '<div class="radio">\
                                            <label><input type="radio" name="radio_promo_code" class="radio_promo_code" value="'+$(this).attr('id')+'">' + label_discount + ': ' + $(this).attr('id') +'</label>\
                                        </div>';
            //html_radio_promo_code +='<input type="radio" checked name="radio_promo_code" class="radio_promo_code" value="'+$(this).attr('id')+'">' + label_discount + ': ' + $(this).attr('id') +'<br>'; 
        }
    });

    if(promo_codes.length == 1) {
        $(e).attr('data-promo-code', promo_codes.join("_"));
        $(document).openComposeViewModal_US7_and_Sanden_Tips(e);
    }else{

        var popupList = $('<div id="popupSelectPromoCode" title="Please Select Promo Code">'
               + html_radio_promo_code +
                        + '</div>');
        popupList.dialog({
            modal:true,
            width: 500,
            buttons: {
                Cancel : function(){
                    $(this).dialog("close");
                },
                OK : function() {
                    $(e).attr('data-promo-code', $('input[name="radio_promo_code"]:checked').val());
                    $(document).openComposeViewModal_US7_and_Sanden_Tips(e);
                    $(this).dialog("close");
    
                }
            }
        });
    }

    // if (promo_codes.length != 0) {
    //     //have >> continue
    //     $(e).attr('data-promo-code', promo_codes.join("_"));
    //     $(document).openComposeViewModal_US7_and_Sanden_Tips(e);
    // } else {
    //     var question = confirm("No Promo Code has been generated - sure you want to continue?");
    //     if (question) {
    //         $(e).attr('data-promo-code', '');
    //         $(document).openComposeViewModal_US7_and_Sanden_Tips(e);
    //     }
    //     else {
    //         // $('html, body').animate({
    //         //     scrollTop: (($('#generate_promo_code').closest(".panel-default").offset().top) - 100)
    //         // });
    //         return false;
    //     }
    // }
}

/**
 * VUT - check File/Photo name include {string}
 * @param {string} str
 * @returns {boolean} true is has
 */
function hasFileAndPhoto(str) {
    let res = false;
    $("#fileupload tr p.name a").each(function() {
        if ($(this).attr("href").toLowerCase().indexOf(str) != -1) {
            res= true;
            return false;
        }
    });
    return res;
}

/**
 * VUT - load select CES note template
 */
function loadSelect_CES_Template() {
    SUGAR.ajaxUI.showLoadingPanel();
    $.ajax({
        url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=ces_type' ,
        type: 'POST',
        data: 
        {
            action: 'read',
            module: module_sugar_grp1,
            module_id: $("input[name='record']").val(),
        },
        async: false,
        success: function(result) {   
            if(result == '' || typeof result === 'undefined')return;
            render_select_ces_template(result);
            SUGAR.ajaxUI.hideLoadingPanel();
        }
    }); 
}

/**
 * VUT - move to here
 * @param {JSON_ENCODE} result 
 */
function render_select_ces_template(result){
    try {
        var data_result = JSON.parse(result);
        window.data_ces_notes = data_result;
        $('#select_title_template_ces_notes').empty();
        $("#select_template_ces").empty();
        $('#select_title_template_ces_notes').append($('<option>', {
            value: '',
            text: ''
        }));
        $('#select_template_ces').append($('<option>', {
            value: '',
            text: 'Select Template CES'
        }));
        $.each(data_result,function(k,v){
            $('#select_title_template_ces_notes').append($('<option>', {
                value: k,
                text: v.title
            }));
            $('#select_template_ces').append($('<option>', {
                value: k,
                text: v.title
            }));
        });
        autosize.update($("#ces_cert_wording_c")); 
    } catch (err) {
        console.log('Invoice-Edit >> render_select_ces_template: '+err);
    }
}


function loadSelect_PCOC_Template() {
    SUGAR.ajaxUI.showLoadingPanel();
    $("#ajaxloading_mask").css("position",'fixed');
    $.ajax({
        url: 'index.php?entryPoint=CRUD_Cert_Template&type_template=pcoc_type' ,
        type: 'POST',
        data: 
        {
            action: 'read',
            module: module_sugar_grp1,
            module_id: $("input[name='record']").val(),
        },
        async: true,
        success: function(result) {  
            if(result == '' || typeof result === 'undefined')return;
            render_select_pcoc_template(result);
            SUGAR.ajaxUI.hideLoadingPanel();
        }
    }); 
}

//VUT - move to here
function render_select_pcoc_template(result){
    try {
        var data_result = JSON.parse(result);
        window.data_pcoc_notes = data_result;
        $('#select_title_template_pcoc_notes').empty();
        $("#select_template_pcoc").empty();
        $('#select_title_template_pcoc_notes').append($('<option>', {
            value: '',
            text: ''
        }));
        $('#select_template_pcoc').append($('<option>', {
            value: '',
            text: 'Select Template PCOC'
        }));
        $.each(data_result,function(k,v){
            $('#select_title_template_pcoc_notes').append($('<option>', {
                value: k,
                text: v.title
            }));
            $('#select_template_pcoc').append($('<option>', {
                value: k,
                text: v.title
            }));
        });
        autosize.update($("#pcoc_cert_wording_c")); 
    } catch (err) {
        console.log('Invoice-Edit >> render_select_pcoc_template: '+err);
    }
}
/**
 * VUT - get distance selected for Quote/Invoice
 * @param {account_id} id_account 
 */
function get_distance_by_account_id(id_account){
    if(id_account == '') return '';
    if( $('#install_address_c').val() == "" ){  
        var from_address =  $("#billing_address_street").val() +", " +
                            $("#billing_address_city").val() + ", " +
                            $("#billing_address_state").val() + ", " +
                            $("#billing_address_postalcode").val();
     
    }else {
        var from_address =  $("#install_address_c").val() +", " +
                            $("#install_address_city_c").val() + ", " +
                            $("#install_address_state_c").val() + ", " +
                            $("#install_address_postalcode_c").val();
    }
    var result_distance = '';
    $.ajax({
        url: "/index.php?entryPoint=getdistance_Flum_or_Elec_to_Suite&ac_id="+id_account,
        type: 'GET',
        async:false,
        success: function(data)
            {
                if(data == ', , , '){
                    alert('Sorry! - Not see address');
                }else {       
                    $.ajax({
                            url: "/index.php?entryPoint=customDistance&address_from=" + from_address + "&address_to=" + data,
                            type: 'GET',
                            async:false,
                            success: function(result)
                            {
                                try {
                                    var jsonObject = $.parseJSON(result);
                                    var l_distance = parseFloat(jsonObject.routes[0].legs[0].distance.text.replace(/[^\d.-]/g, ''));
                                    result_distance = l_distance;
                                } catch (error) {
                                    result_distance = 'not found';
                                }
                                
                            } 
                        
                    });
                }
            },
    })
    return result_distance;
};

function getFileDesign(str) {
    let files_design = [];
    $("#fileupload tr p.name a").each(function(e) {
        if ($(this).attr("href").indexOf(str) != -1) {
            files_design.push($(this).attr("href").split('/').pop());
        }
    });
    if (files_design.length > 0) {
        $.ajax({
            url: 'index.php?entryPoint=checkFileDesign',
            type: 'POST',
            data: 
            {
                module: module_sugar_grp1,
                module_id: $("input[name='record']").val(),
                installation_pictures_c: $('input[name="installation_pictures_c"]').val(),
                files: files_design,
            },
            async: false,
            success: function(result) {   
                link = result;
            }
        }); 
    }
    return typeof link === "undefined" ? '' : link.trim() ;
}

//change description STCs product
function change_description_STCs(){
    if($('#registered_for_gst_c:checked').val() == 'true'){
        var company_name = $("#billing_account").val();
        var template_default = 'Small-scale Technology Certificates (STC) - Financial \
        Incentive provided as a point of sale discount* Title and property in the equipment (including full legal and beneficial ownership) does not pass to Customer until ' + company_name +' has received payment in full of the amount specified in the contract for the supply and installation of the equipment (including any amount to be generated through the creation and sale of Smallscale Technology Certificates (STCs), or 60 days has passed';
            
        $('#lineItems').find('input').each( function(index){                            
            var selector = $('#product_part_number'+index).val();
            if(typeof(selector) == 'string') {
                if(selector.includes('STC Rebate Certificate')){
                    $('#product_item_description'+index).val(template_default);
                    return false;
                }    
            }
        });
        $invoice_date = $("#invoice_date").val();
        if($invoice_date == '') {
            $invoice_date = $("#due_date").val();
        }
        $("#invoice_date").val($invoice_date);
        $("#due_date").val($invoice_date);
        $('#installation_date_c').val($invoice_date +' 12:00');
        $('#installation_date_c_date').val($invoice_date);
        $('#installation_date_c_hours').val('12');
        $('#installation_date_c_minutes').val('00');
    }

}

YAHOO.util.Event.addListener(["registered_for_gst_c"], "change", change_description_STCs);

function Ajax_Generate_File_PDF_REPS(action=''){
    if($("input[name='record']").val().trim() != ''){
        SUGAR.ajaxUI.showLoadingPanel(); 
        $("#EditView input[name='action']").val('Save');
        var url_generate_pdf = 'index.php?entryPoint=Generate_REPS_WH1_PDF&InvoiceID='+$("input[name='record']").val().trim();
        switch (action) {
            case 'REPS_Infor_State':
                url_generate_pdf += '&action=REPS_Infor_State';
                break;
            case 'Solar_Hot_Water_Rebate':
                url_generate_pdf += '&action=Solar_Hot_Water_Rebate';
                break;
            case 'Solar_Hot_Water_Proof':
                url_generate_pdf += '&action=Solar_Hot_Water_Proof';
            default:
                break;
        }
        
        $.ajax({
            type: $("#EditView").attr('method'),
            url: $("#EditView").attr('action'),
            data: $("#EditView").serialize(),
            async:false,
            success: function (data) {
                $.ajax({
                    type: 'POST',
                    url: url_generate_pdf,
                }).done(function(data) {
                    $(".files").empty();
                    $.ajax({
                        url: $('#fileupload').fileupload('option', 'url'),
                        dataType: 'json',
                        context: $('#fileupload')[0]
                    }).always(function () {
                        $(this).removeClass('fileupload-processing');
                    }).done(function (result) {
                        $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                    });
                    SUGAR.ajaxUI.hideLoadingPanel(); 
                })
            }
        });  

    }else{
        $('#alert_modal').find('.modal-body').empty();
        $('#alert_modal').find('.modal-body').append('Could you saving Invoice before, please?'); 
        $('#alert_modal').modal('show'); 
    }
}

function GenerateJsonPromoCodeCustom (data_in){
    var json_promo_code_custom_c = $("#json_promo_code_custom_c").val();
    var check_exist_promocode = false;
    var index_exist_promocode = 0 ;
    if(json_promo_code_custom_c == ''){
         json_promo_code_custom_c = [];
    }else{
         json_promo_code_custom_c = $.parseJSON(json_promo_code_custom_c);
    }

    var data_insert = { 
        'selected': data_in.selected,  
        'offer_type_promotion':data_in.offer_type_promotion,
        'name_promotion': data_in.name_promotion,
        'amount_off_promotion': data_in.amount_off_promotion,
        'percentage_off_promotion': data_in.percentage_off_promotion,
        'promo_code': data_in.promo_code,
        'date_start':data_in.date_start,
        'date_end': data_in.date_end,
    };
    $.each(json_promo_code_custom_c,function(key,value){
        if(value.promo_code == data_insert.promo_code){
            check_exist_promocode = true;
            index_exist_promocode = key;
        }
    });
    if(check_exist_promocode) {
        json_promo_code_custom_c[index_exist_promocode].selected = data_insert.selected;
    }else{
        json_promo_code_custom_c.push(data_insert);
    }
    $("#json_promo_code_custom_c").val(JSON.stringify(json_promo_code_custom_c));
};

function RenderHTMLPromoCodeCustom(){
    var json_promo_code_custom_c = $("#json_promo_code_custom_c").val();
    if(json_promo_code_custom_c == ''){
         json_promo_code_custom_c = [];
    }else{
         json_promo_code_custom_c = $.parseJSON(json_promo_code_custom_c);
    }
    var html = "<div class='group_list_promo_code_customize'><ul>";
    if(json_promo_code_custom_c.length != 0 ){
        $.each(json_promo_code_custom_c,function(key,value){
            if(value.promo_code !== '' ){
                html += '<li>';
                if(value.selected == 'selected'){
                    html +=  '<span><input onClick="SUGAR.EventChange_checkbox_promo_code_customize(this);"  class="checkbox_promo_code_customize" checked type="checkbox" id="'+value.promo_code+'" name="'+value.promo_code+'" value="" title="" tabindex="0"> - </span>'; 
                }else { 
                    html +=  '<span><input onClick="SUGAR.EventChange_checkbox_promo_code_customize(this);" class="checkbox_promo_code_customize" type="checkbox" id="'+value.promo_code+'" name="'+value.promo_code+'"  value="" title="" tabindex="0"> - </span>';  
                }
                    html +=  '<span>'+ value.name_promotion + ' -</span>'; 
                if(value.offer_type_promotion == 'order_fixed_grand_total_off'){
                    html +=  '<span class="label-discount"> Discount ($ '+value.amount_off_promotion + ')  </span>'; 
                }else {
                    html +=  '<span class="label-discount"> Discount ( '+value.percentage_off_promotion + ' %)  </span>';  
                }
                    html +=  '<strong>- '+value.promo_code + '</strong>'; 
                if(value.date_start) {
                    html +=  '<strong>- Start Date: '+value.date_start + '</strong>'; 
                    html +=  '<strong>- End Date: '+value.date_end + '</strong>'; 
                }

                html += '</li>';
            }
        });
    }
    html += "</ul></div'>";
    $('.group_list_promo_code_customize').remove();
    $('#custom_generate_promo_code').after(html);
}

SUGAR.EventChange_checkbox_promo_code_customize = function(element){
    let promo_code =  $(element).attr('id');
    let selected = '';
    if( $(element).is(":checked")){
        selected = 'selected';
    }

    var data_insert = { 
        'selected': selected,  
        'offer_type_promotion':'',
        'name_promotion': '',
        'amount_off_promotion': '',
        'percentage_off_promotion': '',
        'promo_code': promo_code,
        'date_start':'',
        'date_end': '',
    };
    GenerateJsonPromoCodeCustom (data_insert);

}
/**
 * 3 button TODAY +7 , TODAY +1, TODAY
 * @param {STRING} type  'data-type' of element
 */
function getDate_Inv(type) {
    var date_return = '';
    var date = new Date();
    let day = date.getDay();
    switch(type){
        case 'today':
            var data = defaultDateTime_Inv(new Date());
            if(data['day'] < 10) {
                data['day'] = '0'+data['day'];
            }
            if(data['month'] < 10) {
                data['month'] = '0' + data['month'];
            }
            date_return = data['day']+'/'+data['month']+'/'+data['year']; 
            break;
        case '1':
            if (day === 5) {
                var data = defaultDateTime_Inv(new Date(date.getTime() + 3*(24*60*60*1000)));
            } else {
                var data = defaultDateTime_Inv(new Date(date.getTime() + (24*60*60*1000)));
            }
            if(data['day'] < 10) {
                data['day'] = '0'+data['day'];
            }
            if(data['month'] < 10) {
                data['month'] = '0' + data['month'];
            }
            date_return = data['day']+'/'+data['month']+'/'+data['year']; 
            break;
        case '7':
            var data = defaultDateTime_Inv(new Date(date.getTime() + 7*(24*60*60*1000)));
            if(data['day'] < 10) {
                data['day'] = '0'+data['day'];
            }
            if(data['month'] < 10) {
                data['month'] = '0' + data['month'];
            }
            date_return = data['day']+'/'+data['month']+'/'+data['year']; 
            break;
    }
    return date_return;
}

function defaultDateTime_Inv(date){
    var now     = date;
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    return {'day':day,'month':month,'year':year,}
}

function showLinkMeeting(id, meeting_id) {
    // debugger
    let link_meeting = "<div id='open_"+id+"'><a target='_blank' href='/index.php?module=Meetings&action=EditView&record=" + meeting_id + "'>" + "Open Meeting" + "</a></div>";
    $(`#open_${id}`).remove();
    $(`#${id}`).parent().append(link_meeting);
}

/**
 * show link 
 * @param {*} id_ele 
 * @param {*} module_name 
 * @param {*} record 
 */
function showLink(id_ele, module_name, record) {
    let link = '';
    switch (module_name) {
        case 'aupost':
            link += `<div id="open_${id_ele}"><a target="_blank" href="https://auspost.com.au/mypost-business/shipping-and-tracking/orders/view/retail/${record}">Open ${module_name.toUpperCase()}</a></div>`;
            break;

        default:
            break;
    }
    $(document).find(`#open_${id_ele}`).remove();
    $(document).find(`#${id_ele}`).parent().append(link);
}

/**
 * show link related warehouse log
 */
 function showLinkWarehouseLogRelated() {
    $("#link_ware_house_log_c").hide();
    var InvoiceID = $("input[name='record']").val();
    if(InvoiceID !== '') {
        $.ajax({
            url: "/index.php?entryPoint=showLinkWarehouseLogRelated&InvoiceID="+ InvoiceID,
            success: function (data) {
                $("#link_ware_house_log_c").parent().append(data);
            }
        });
    }
}

function display_link_account_contact_installer_solar(){
    $("#link_account_solar_installer").remove();
    if( $("#account_id5_c").val() != ''){
        $("#account_id5_c").parent().append("<p id='link_account_solar_installer'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#account_id5_c").val()+ "' target='_blank'>Open Account</a></p>");
    }
    $("#link_contact_solar_installer").remove();
    if( $("#contact_id2_c").val() != ''){
        $("#contact_id2_c").parent().append("<p id='link_contact_solar_installer'><a  href='/index.php?module=Contacts&action=EditView&record=" + $("#contact_id2_c").val()+ "' target='_blank'>Open Contact</a></p>");
    }

}

function display_fields_each_product_type(){
    var let_array_hide_for_solar = '#plumber_c,#electrician_c,#distance_to_suite_c,#distance_to_suitecrm_c,#practitioner_verification_c,#plumber_install_date_c,#electrician_install_date_c,#meeting_plumber,#meeting_electrician,#plumber_po_c,#electrical_po_c,#plumbing_notes_c,#electrical_notes_c,#pcoc_cert_wording_c,#ces_cert_wording_c,#plumber_contact_c,#electrician_contact_c,#plumber_license_number_c,#electrician_license_number_c,#vba_pic_date_c,#ces_cert_date_c,#vba_pic_cert_c,#ces_cert_c';
    
    var let_array_show_for_solar = '#solar_installer_c,#solar_installer_contact_c';
    
    var invoice_type = $("#quote_type_c").val();
    switch (invoice_type) {
        case 'quote_type_solar':
            $(let_array_hide_for_solar).closest('.edit-view-field').parent().hide();
            $(let_array_show_for_solar).closest('.edit-view-field').parent().show();
            break;
    
        default:
            $(let_array_hide_for_solar).closest('.edit-view-field').parent().show();
            $(let_array_show_for_solar).closest('.edit-view-field').parent().hide();
            break;
    }
}