function genExtraDaikinItemFunc(elem){
    var external_unit_location = {
        'Sub_Floor_100':100,
        'Sub_Floor_Diff_200':200,
        'High_Wall_85':85,
        'Low_Wall_30':30,
        '2nd_Story_Wall_300':300,
        '2nd_Story_Walkable_55':55,
        'Ground_Standard':0,
    }
    var electrical_connection = {
        'Local_standard': 0,
        'Local_add_rcd_45': 45,
        'New_Circuit_95': 95
    }
    var external_unit_location_price = external_unit_location[$("#external_unit_location_c").val()];
    console.log("external_unit_location_price:"+external_unit_location_price);
    // For line 4
    var c4 = $("#electric_run_ext_wall_c").val();
    var i4 = 4;
    var h4 = 0;
    var g4 = 25;
    
    var Electric_Run_Ext_Wall_price = (c4 <=  i4 ) ? 0: (h4+(c4-i4)*g4);
    console.log("Electric_Run_Ext_Wall_price:"+Electric_Run_Ext_Wall_price);
 
     var c5 = $("#electric_run_roof_cavity_c").val();
     var i5 = 0;
     var h5 = 100;
     var g5 = 25;
     var Fridge_Pipe_Run_Roof_price = (c5 <=  i5 ) ? 0:(h5+(c5-i5)*g5);
     console.log("Fridge_Pipe_Run_Roof_price:"+Fridge_Pipe_Run_Roof_price);
 
     var c6 = $("#refrigeration_pipe_roof100_c").val();
     var i6 = 0;
     var h6 = 100;
     var g6 = 25;
    var Refrigeration_pipe_run_roof_cavity_price = (c6 <=  i6 ) ? 0: (h6+(c6-i6)*g6);
    console.log("Refrigeration_pipe_run_roof_cavity_price:"+Refrigeration_pipe_run_roof_cavity_price);
 
     //  6
     var Electrical_connection_price = electrical_connection[$("#electrical_connection_c").val()];
     console.log("Electrical_connection_price:"+Electrical_connection_price);
     // 7
     var c8 = $("#electric_run_sub_floor_c").val();
     var i8 = 0;
     var h8 = 50;
     var g8 = 25;
     var Electric_Run_Sub_Floor_price = (c8 <=  i8 ) ? 0: (h8+(c8-i8)*g8);
     console.log("Electric_Run_Sub_Floor_price:"+Electric_Run_Sub_Floor_price);
 
     var c9 = $("#fridge_pipe_run_external15_c").val();
     var i9 = 1.5;
     var h9 = 0;
     var g9 = 25;
     var Fridger_Pipe_Run_Ex_price = (c9 <=  i9 ) ? 0: (h9+(c9-i9)*g9);
     console.log("Fridger_Pipe_Run_Ex_price:"+Fridger_Pipe_Run_Ex_price);
 
     var c10 = $("#internal_wall_install_c").is(":checked");
     var i10 = 0;
     var h10 = 200;
     var g10 = 0;
     var Internal_Wall_Install_price = c10*h10;
     var cost_total = 
         Internal_Wall_Install_price + 
         Fridger_Pipe_Run_Ex_price + 
         Electric_Run_Sub_Floor_price +
         Electrical_connection_price +
         Refrigeration_pipe_run_roof_cavity_price +
         Fridge_Pipe_Run_Roof_price +
         Electric_Run_Ext_Wall_price +
         external_unit_location_price
         ;
 
     // Add line items
     lineno = $('.product_name').length; 
     insertProductLine("product_group0", "0");
     var popupReplyData = {}; //
     popupReplyData.form_name = "EditView";
     var name_to_value_array = {};
 
     name_to_value_array["product_currency"+lineno] = "-99";
     name_to_value_array["product_item_description"+lineno] = "Complicated install - long pipe run, rooftop installation, difficult wall penetration or difficult sub floor access";
     name_to_value_array["product_name"+lineno] = "Daikin Install Extra";
     name_to_value_array["product_part_number"+lineno] = "JOLLYAIR_DIFFICUL_INSTALL";
     name_to_value_array["product_product_cost_price"+lineno] = ""+cost_total;
 
     name_to_value_array["product_product_id"+lineno] = "e3124bc6-0cd9-88ec-9da6-56951c2dafbb";
     name_to_value_array["product_product_list_price"+lineno] = ""+(cost_total * 1.2);
     popupReplyData["name_to_value_array"] = name_to_value_array;
     setProductReturn(popupReplyData);
     $("#product_part_number"+lineno).trigger("change");
 
 }
 
 function addButton_owner(){
     $("#change_Owner").remove();
     $('div[field="sg_assigned_user_c"]').append(
         '<button type="button" id="change_Owner" class="button sg-change-owner-user" title="" onClick="SUGAR.Sg_ChangeOwnerAssignedQuote(this);" > Change Owner <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
     ) 
 }
 
 $(function () {
     'use strict';
 // Generate uinique id
     createLinkProduct();
     $(document).find('#line_items_span').on('change', '.product_name', function (e) {
         setTimeout(function() {
             createLinkProduct();
         }, 500)
         //VUT-S-Add "Veecs" for Sanden then change "Old Tank Fuel" = "Electric storage"
         // debugger;
         if ($(this).siblings('.yui-ac-container').find('.yui-ac-content .yui-ac-bd ul li:first').text().toLowerCase() == "veecs" && $('#quote_type_c').val() == "quote_type_sanden")  {
             $('#old_tank_fuel_c').val('electric_storage');
         }
         //VUT-E-Add "Veecs" for Sanden then change "Old Tank Fuel" = "Electric storage"
     })
     function createLinkProduct() {
         if($('.product_link').length) {
             $('.product_link').remove();
         }
         $('.product_product_id').each(function(index) {
             var product_id = $(this).val();
             if($(this).val() != '') {
                 $(this).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ product_id +'">Link</a></div>');
             }
             // product is VEEC
             var name_product = $(this).closest('tr').find('.product_name').val();
             var product_style = $(this).closest('tbody').attr('style');
             console.log(name_product);
             if ( typeof(name_product) != 'undefined' && name_product.toLowerCase().includes('veec') && product_style != 'display: none;')  {
                 $('#old_tank_fuel_c').val('electric_storage');
             }
             console.log(product_id);
         })
     }
     if($("#opportunity").val()!="" && $("#name").val() == ""){
         $("#name").val($("#opportunity").val());
         
         // more logic
         if($("#opportunity").val().indexOf("Sanden")!= -1 && $("input[name='record']").val()==""){
             insertGroup(0);
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
             }  
             var data = populateProducts['Sanden VIC Elec']['315L']
             if($("#billing_address_state").val() == "VIC"){
                 // populate vic 
                 $("#group0name").val("Sanden VIC Elec");
                 data = populateProducts['Sanden VIC Elec']['315L'];
                 
             }
             else {
                 $("#group0name").val("Sanden");
                 data = populateProducts['Sanden']['315L'];
             }
             
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
                     $("#genRebateNumber").trigger("click");
                 });
         }
     }
     if($("#quote_date_c").val() == ""){
         var today = new Date();
 
         var strDate = 'd/m/Y'
             .replace('Y', today.getFullYear())
             .replace('m', today.getMonth()+1)
             .replace('d', today.getDate());
         $("#quote_date_c").val(strDate);
     }
     
     //fix bug: duplicate Save auto return parent quote 
     if(module_sugar_grp1 == 'AOS_Quotes'){
         if(typeof($("input[name='duplicateSave']").val()) != 'undefined' && $("input[name='duplicateSave']").val() == 'true' ){
             $("input[name=return_id]").val('');
         }
     }
     // dung code -- change logic :  account_firstname_c, account_lastname_c get from contacts 
     if(module_sugar_grp1 == 'AOS_Quotes') {
         YAHOO.util.Event.addListener("billing_contact_id", "change", function(){
            // render_info_contact();
         });
 
         YAHOO.util.Event.addListener("billing_account_id", "change", function(){
             if($("#billing_account_id").val() != ""){
                 var record_id = $("#billing_account_id").val();
                 $.ajax({
                     url: "?entryPoint=getContactFromAccount&record_id=" + record_id ,
                     context: document.body,
                     async: false,
                     success:function(data){
                         if(!data) return;
                         var json = $.parseJSON(data);
                         $('#billing_contact').val(json.first_name + ' ' + json.last_name );
                         $('#billing_contact_id').val(json.record_id);
                         $("#link_account").remove();
                         $("#billing_account").parent().append("<p id='link_account'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#billing_account_id").val()+ "' target='_blank'>Open Account</a></p>");
                         $("#link_contact").remove();
                         $("#billing_contact").parent().append("<p id='link_contact'><a  href='/index.php?module=Contacts&action=EditView&record=" + $("#billing_contact_id").val()+ "' target='_blank'>Open Contact</a></p>");
                        // render_info_contact();
                     }
                 })
         
             }
         });
     }
 
 
 
 
     //render_info_contact();
     var customer_phone_number ;
     function render_info_contact(){
         $('#text_contact_detail').remove();
         $('#account_firstname_c').after('<div id="text_contact_detail"></div>');
         if($("#billing_contact_id").val() !== ""){
             var record_id = $("#billing_contact_id").val();
             var full_name = $("#billing_contact").val();
             var record_id_account = $("#billing_account_id").val();
             $.ajax({
                 url: "?entryPoint=getContactFromAccount&request=get_info_contact&record_id=" + record_id + "&record_id_account="+record_id_account,
                 context: document.body,
                 async: false,
                 success:function(data){
                     if(!data) return;
                     var record_id = $("#billing_contact_id").val();          
                     var json = $.parseJSON(data);
                     if(json.mobile == ""){
                         if(json.mobile_home !== ""){
                             var html_append = '';
                                 html_append += '<p>*Home Number :<a onclick="SUGAR.detail_phone(this)" style="cursor:pointer;">'+ json.mobile_home +'</a></p>';
                                 html_append += '<p>*Email :<a id="detail_email"  style="cursor:pointer;"  onclick="$(document).openComposeViewModal_quote(this);" data-module-name="'+ full_name +'" data-record-id="'+ record_id +'" data-email-address="'+ json.email +'" data-email-type="send-address-infor">'+  json.email +'</a></p>';
                                 customer_phone_number = json.mobile_home;
                                 $('#text_contact_detail').append(html_append);
                                 
                         }else {
                             if( json.mobile_work !== ""){
                                 var html_append = '';
                                 html_append += '<p>*Work Number :<a onclick="SUGAR.detail_phone(this)" style="cursor:pointer;">'+ json.mobile_work +'</a></p>';
                                 html_append += '<p>*Email :<a id="detail_email"  style="cursor:pointer;"  onclick="$(document).openComposeViewModal_quote(this);" data-module-name="'+ full_name +'" data-record-id="'+ record_id +'" data-email-address="'+ json.email +'" data-email-type="send-address-infor">'+  json.email +'</a></p>';
                                 customer_phone_number = json.mobile_work;
                                 $('#text_contact_detail').append(html_append);
                             }else {
                                 var html_append = '';
                                 html_append += '<p>*Phone Number :No number</p>';
                                 html_append += '<p>*Email :<a id="detail_email"  style="cursor:pointer;" onclick="$(document).openComposeViewModal_quote(this);" data-module-name="'+ full_name +'" data-record-id="'+ record_id +'" data-email-address="'+  json.email +'" data-email-type="send-address-infor">'+  json.email +'</a></p>';
                                 $('#text_contact_detail').append(html_append);
                             }
                         }
                     }else if( json.email == ""){
                         var html_append = '';
                         html_append += '<p>*Phone Number :<a onclick="SUGAR.detail_phone(this)" style="cursor:pointer;">'+ json.mobile +'</a></p>';
                         html_append += '<p>*Email :No value</p>';
                         customer_phone_number = json.mobile;
                         $('#text_contact_detail').append(html_append);
                     }else {
                         var html_append = '';
                         html_append += '<p>*Phone Number :<a onclick="SUGAR.detail_phone(this)" style="cursor:pointer;">'+ json.mobile +'</a></p>';
                         html_append += '<p>*Email :<a id="detail_email"  style="cursor:pointer;" onclick="$(document).openComposeViewModal_quote(this);" data-module-name="'+ full_name +'" data-record-id="'+ record_id +'" data-email-address="'+  json.email +'" data-email-type="send-address-infor">'+  json.email +'</a></p>';
                         customer_phone_number = json.mobile;
                         $('#text_contact_detail').append(html_append);
                     }
                     // var html_name_append = '';
                     // html_name_append += '<p>*First Name:' + json.first_name + '</p>';
                     // html_name_append += '<p>*Last Name:' + json.last_name + '</p>';
                     // $('#text_contact_detail').append(html_name_append);
                     $('#billing_address_street').val(json.street);
                     $('#billing_address_city').val(json.city);
                     $('#billing_address_state').val(json.state);
                     $('#billing_address_postalcode').val(json.postalcode);
                     $('#account_firstname_c').val(json.first_name);
                     $('#account_lastname_c').val(json.last_name);
                     return false;
                 }
             });
         }
     }
     $.fn.openComposeViewModal_quote = function (source) {
         var self = this;
         self.emailComposeView = null;
         var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
         var composeBox = $('<div></div>').appendTo(opts.contentSelector);
         composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
         composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
         composeBox.show();
         var record_id= $(source).attr('data-record-id') ;
         var email_type = $(source).attr('data-email-type');
         $.ajax({
             type: "GET",
             cache: false,
             url: 'index.php?module=Emails&action=ComposeView&in_popup=1' + ((record_id!="")? ("&lead_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):""),
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
             var populateEmailAddress = $(source).attr('data-email-address');
 
             if (populateModuleName !== '') {
                 populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
             }
 
             $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
             $(self.emailComposeView).find('#parent_type').val(populateModule);
             $(self.emailComposeView).find('#parent_name').val(populateModuleName);
             $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
             $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
 
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
     }
     SUGAR.detail_phone = function(elem){
         var site_contact_name = $("#billing_account").val()
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
     }
     if($("#billing_contact").val() == "" && $("#billing_account").val() !== ""){
         $("#billing_contact").val($("#billing_account").val());
         var record_id = $("#billing_account_id").val();
         $.ajax({
             url: "?entryPoint=getContactFromAccount&record_id=" + record_id,
             context: document.body,
             async: false
         }).done(function (data) {
             if(typeof data == 'undefined' || data == ''){
                 return;
             }
             var json = $.parseJSON(data);
             $("#billing_contact_id").val(json.record_id);
         });
 
     }
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
             return false;
         }
     });
     
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
                        if(data == '' && typeof data == undefined)return;
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
 
     $('#file_rename_c').val("");
 
     
     $( document ).ready(function() {
         //dung code  -- GET DISTANCE button Plumber and Electric In Module Quote
         if(module_sugar_grp1 != 'AOS_Invoices'){
            $('#plumber_c, #electrician_c').closest('.edit-view-row-item').hide();
             $('#plumber_c').parent().siblings('.label').append('<br> <button class="button primary" id="distanceFlumbertoSuite_Quotes"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>GET DISTANCE</button>');
             $('#electrician_c').parent().siblings('.label').append('<br> <button class="button primary" id="distanceElectrictoSuite_Quotes"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>GET DISTANCE</button>');
         }
         // button Plumber  
         $('#distanceFlumbertoSuite_Quotes').click(function (){
             var distance_value = get_distance_by_account_id($('#account_id_c').val());
             $('#text_plumber_distance').remove();
             if(distance_value == 'not found') {
                 $('#distanceFlumbertoSuite_Quotes').parent().after('<p id="text_plumber_distance">Address Not Right</p>');
             }else{
                 $('#distanceFlumbertoSuite_Quotes').parent().after('<p id="text_plumber_distance">'+ distance_value +' km</p>');
             }
             
             return false;   
         });
 
         //button Electric 
         $('#distanceElectrictoSuite_Quotes').click(function(){
             var distance_value = get_distance_by_account_id($('#account_id1_c').val());
             $('#text_electric_distance').remove();
             if(distance_value == 'not found') {
                 $('#distanceElectrictoSuite_Quotes').parent().after('<p id="text_electric_distance">Address Not Right</p>');
             }else{
                 $('#distanceElectrictoSuite_Quotes').parent().after('<p id="text_electric_distance">'+ distance_value +' km</p>');
             }
             return false;
         })
 
          //Thienpb code - auto gen rebate numberP
         $(".product_group").children('tbody').each(function(e){
             var index = $(this).index();
             if($("#product_product_id"+index).val() == 'cbfafe6b-5e84-d976-8e32-574fc106b13f' || $("#product_product_id"+index).val() == '4efbea92-c52f-d147-3308-569776823b19'){
                 if($("#product_product_qty"+index).val() == 1){
                     genRebateNumberFunc();
                 }
             }
         })
         
         if($("input[name='record']").val() == "" && $('#name').val() !== ""){
             if($('#name').val().indexOf("Daikin") !== -1){
                 $("#addGroup").trigger("click");
                 $("#group0name").val("Daikin VIC");
                 var data = [
                     "FTXZ25N",
                     "Daikin_Wifi",
                     "DAIKIN_MEL_METRO_DELIVERY",
                     "JOLLYAIR_STANDARD_INSTALL",
                     "VEEC Rebate Certificate",
                 ];
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
                             if($("#install_address_state_c").val() == "VIC")
                                 $("#genRebateNumber").trigger("click");
                             //copyToSandenModel();
                         });
             }
         }
 
         $("#state_c").after('<button type="button" tabindex="116" style="width:auto; height:40px; padding: 0 10px;" class="button" onclick="genExtraDaikinItemFunc(this) " id="genExtraDaikinItem">Gen Extra Daikin Item<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
         
         if ($("input[name='record']").val()==""){
             /*if(!$("#shipping_checkbox").is(":checked")){
                 $("#shipping_checkbox").trigger("click");
             };*/
             
             $("#shipping_checkbox").prop( "checked", true );
             SUGAR.util.doWhen("typeof(SUGAR.AddressField) != 'undefined'", function () {
                 var shipping_address = new SUGAR.AddressField("shipping_checkbox", 'billing', 'shipping');
                 shipping_address.syncFields();
             });
         }
         if($("#name").val() == ""){
             //$("#shipping_checkbox").prop( "checked", true );
             
             /*SUGAR.util.doWhen("typeof(SUGAR.AddressField) != 'undefined'", function () {
                 var shipping_address = new SUGAR.AddressField("shipping_checkbox", 'billing', 'shipping');
                 shipping_address.syncFields();
             });*/
             /*$("#install_address_billing").prop( "checked", true );
 
             $("#billing_address_street").change(function(){
                 if($("#install_address_billing").is(':checked')){
                     $("#install_address_c").val($(this).val());
                 }
             });
             //billing_address_city
             $("#billing_address_city").change(function(){
                 if($("#install_address_billing").is(':checked')){
                     $("#install_address_city_c").val($(this).val());
                 }
             });
             //billing_address_state
             $("#billing_address_state").change(function(){
                 if($("#install_address_billing").is(':checked')){
                     $("#install_address_state_c").val($(this).val());
                 }
             });
 
             $("#billing_address_postalcode").change(function(){
                 if($("#install_address_billing").is(':checked')){
                     $("#install_address_postalcode_c").val($(this).val());
                 }
             });
             */
         }
     });
 });
 
 $(document).ready(function(){
     if(module_sugar_grp1 == 'AOS_Quotes'){
          //hidden -- not use all field
         $('#opportunity ,#approval_status, #approval_issue').closest('.edit-view-row-item').hide();
        //VUT - hidden subpanel SOLAR PV PRICING >> https://trello.com/c/W3QKyBI7/3023-suite-solar-quote-look-for-hiding-the-inputs-coding?menu=filter&filter=member:paulszuster1,mode:and
        $('#solar_pv_pricing_input_c').closest('.panel.panel-default').hide();
     }
     $('input[id="SAVE"]').prop('onclick',null).off('click');
     $('input[id="SAVE"]').click(function(event){
         var _form = document.getElementById('EditView');
         _form.action.value='Save'; 
         if(check_form('EditView'))SUGAR.ajaxUI.submitForm(_form);
         return false;
     })
 
    
     if(module_sugar_grp1 == 'AOS_Quotes'){
         $("#fileupload").prepend('<button type="button" id="get_all_files" class="button primary" title="Get All File"> Get All Files From Email and SMS<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
         $("#get_all_files").click(function(){
             $('#get_all_files span.glyphicon-refresh').removeClass('hidden');
             var billing_account_id = $("#billing_account_id").val();
             var billing_contact_id = $("#billing_contact_id").val();
             var opportunity_id = $("#opportunity_id").val();
             var pre_install_photos_c = $("input[name='pre_install_photos_c']").val();
             
             $.ajax({
                url: "?entryPoint=getAllFilesAttachments&billing_account_id="+billing_account_id+"&billing_contact_id="+billing_contact_id+"&opportunity_id="+opportunity_id+"&pre_install_photos_c="+pre_install_photos_c,
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
                        $('button[type="resize_all"]').trigger('click');
                        // $(this).fileupload('option', 'done')
                        //     .call(this, $.Event('done'), {result: result});
                        $('#get_all_files span.glyphicon-refresh').addClass('hidden');
                    });
                },
                error: function(response){},
            });
            
             $.ajax({
                 url: "?entryPoint=getAllFilesMessageApp&quote_id="+$("input[name='record']").val()+"&pre_install_photos_c="+pre_install_photos_c,
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
                         $('#get_all_files span.glyphicon-refresh').addClass('hidden');
                     });
                 }
             });
         })
     }
 
     // Thienpb code for add button Save and email pdf
     $('input[id="SAVE"]').next().after('&nbsp;<button type="button" id="save_and_email_pdf" class="button save_and_email_pdf" title="Metting With Installer" >Save and Email PDF<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
     $(".save_and_email_pdf").click(function(){
            var quote_id ='';
            var return_module = $("#EditView input[name='return_module']").val();
            $('.save_and_email_pdf span.glyphicon-refresh').removeClass('hidden');
            $("#EditView input[name='action']").val('Save');
            $("#EditView").append('<input type="hidden" value="save_and_sendpdf" name="save_and_sendpdf" />');
            var return_id =  $("#EditView input[name='return_id']").val();
            $("#EditView input[name='return_module']").val('AOS_Quotes');
            $("#EditView input[name='return_action']").val('DetailView');
            $("#EditView input[name='return_id']").val('');
         
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                async:false,
                success: function (data) {
                    window.onbeforeunload = null;
                    var form = $('<form></form>');
                    form.attr("method", "post");
                    form.attr("target","_blank");
                    if($("#quote_type_c").val() == 'quote_type_solar' || $("#quote_type_c").val() == 'quote_type_tesla' ){
                        form.attr("action", 'index.php?entryPoint=CustomQuoteSolarEmailPDF&quote_type_c='+$("#quote_type_c").val());
                    }else{
                        form.attr("action", 'index.php?entryPoint=generatePdf');
                    }
                    
                    var quote_id_patt = /"record" value="(.*)"/g;
                    quote_id = quote_id_patt.exec(data);
                    if(quote_id !== null && typeof quote_id === 'object'){
                        if(quote_id[1] !='')  {
                            quote_id = quote_id[1]
                        }
                    }
                    //var quote_id  = $("input[name='record']").val();
                    var html_field = '';
                    if($("#quote_type_c").val() == 'quote_type_solar'){
                        html_field +=    '<input type="hidden" name="quote_id" value="'+quote_id+'">';
                    }else{
                        html_field +=    '<input type="hidden" name="templateID" value="4fbfbfa6-0bc9-3dbb-0d5e-57ce330802c5">'+
                                        '<input type="hidden" name="task" value="emailpdf">'+
                                        '<input type="hidden" name="module" value="AOS_Quotes">'+
                                        '<input type="hidden" name="uid" value="'+quote_id+'">';
                    }
                    form.append(html_field);
                    $(document.body).append(form);
                    form.submit();
                }
            });
         setTimeout(function(){
             if(return_id !='' && return_module == 'Opportunities'){
                 window.location.href = 'index.php?action=DetailView&module=Opportunities&record='+return_id;
             }else{
                 window.location.href = 'index.php?action=DetailView&module=AOS_Quotes&record='+quote_id;
             }
         }, 1000);
         
     });
     //tuan code -
     $('#billing_address_state').on('change',function(){
         $('#phases').trigger('change');
     })
    //  $('#solargain_options_c').on('click',function(){
    //      $('#phases').trigger('change');
    //  })
     $('#phases').on('change', function (){
         if( $('#phases').find(":selected").val() == 'Three Phases' ){
             for( var i = 1 ; i <= 6 ; i++){
                     $('#extra_1_'+i).val('Fro. Smart Meter (3P)');       
                 }
         }
         if($('#phases').find(":selected").val() == 'Single Phase') {
             for( var i = 1 ; i <= 6 ; i++){
                     $('#extra_1_'+i).val('Fro. Smart Meter (1P)');
             }
         }
     })
     $("#meter_number_c").after('<br><button type="button" class="button primary" id="getMeter"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Meter </button>');
     var nmi_number_meter = '';
     var distributor_meter ='';
     $("#getMeter").on("click",function(){
         nmi_number_meter =  $("#nmi_c").val();
         if(nmi_number_meter !== ''){
             ajax_get_number_meter();
         }else{
             get_number_NMI();
             ajax_get_number_meter();
             $('#getMeter span.glyphicon-refresh').addClass('hidden');
         }
     });
     function ajax_get_number_meter(){
        nmi_number_meter =  $("#nmi_c").val();
        distributor_meter =  $("#distributor_c").val();
        var record_id = $("input[name='record']").val();
        var customer_name = $("#first_name").val() +' ' + $('#last_name').val();
        if($("#phases").val() == ''){
           alert("Please select Meter Pharse");
           $("html, body").animate({
               scrollTop: $("#phases").offset().top - 300
           }, 1000);
            return;
        }
        var meter_phase_c = (($("#phases").val() == 'Three Phases' && $("#phases").val() != 'Unsure') ? 3 : (($("#phases").val() == 'Single Phase') ? 1 : (($("#phases").val() != 'Unsure') ? 2 : ''))) ;
         if(distributor_meter == 4 ||  distributor_meter == 6){
           SUGAR.ajaxUI.showLoadingPanel();
           $.ajax({
               url: "/index.php?entryPoint=customGetMeter&nmi_number=" + nmi_number_meter +"&record="+record_id+"&meter_phase_c="+meter_phase_c+"&type=GET_METER",
               type: 'GET',
                success: function(data)
                {
                    if(data != ''){
                       SUGAR.ajaxUI.hideLoadingPanel();
                        $("#meter_number_c").val(data);
                    }else{
                    //tuan cope thien code ===================
                    SUGAR.ajaxUI.hideLoadingPanel();
                    $(".modal_meter_number").remove();
                        var html = '<div class="modal fade modal_meter_number" tabindex="-1" role="dialog">'+
                                        '<div class="modal-dialog">'+
                                            '<div class="modal-content">'+
                                                '<div class="modal-header">'+
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                    '<h5 class="modal-title" id="title-generic"><center>The address you have nominated cannot be found in our system. Please check your address and Search again.</center></h5>'+
                                                '</div>'+
                                                '<div class="modal-body">'+
                                                    '<div class="container-fluid" style="margin-left:30px;font-size:13px;text-align:center;"><a target="_blank" href="https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage"> Citipower Powercor\'s Link</a>'
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                        $("body").append(html);
                        $(".modal_meter_number").modal('show');
                    // tuan and ==============================
                        //alert ("The address you have nominated cannot be found in our system. Please check your address and Search again.");
                        $("#meter_number_c").val('');
                    }
                },
                error: function(response){
                    alert('Get Meter Number Fail! Please check NMI Number and try again.');
                    SUGAR.ajaxUI.hideLoadingPanel();
                    $("#meter_number_c").val('');
                }
            })
        }else{
            alert("Please sure Distributor option is 'Citipower' OR 'Powercor'");
            SUGAR.ajaxUI.hideLoadingPanel();
            $("#meter_number_c").val('');
        }
        
    }
     $("#getMeter").after('<button type="button" class="button primary" id="checkMeter"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Check Meter </button>');
     $('#checkMeter').after('<div id="text_check_meter"></div>');
     $('#checkMeter').on('click',function(){
         var meter_number = $('#meter_number_c').val();
         if(meter_number == '') {
             alert('We have not Meter Number.');
             return false;
         }else {
             $('#checkMeter span.glyphicon-refresh').removeClass('hidden');
             $.ajax({
                 url: "/index.php?entryPoint=CustomCheckNumberMeter&meter_number_c=" + meter_number,
                 type: 'GET',
                 success: function(data)
                 {
                    if(data == '' && typeof data == undefined)return;
                     var data_json =  $.parseJSON(data);
                     if(data_json['Quote For'] == null && data_json['Network Distributor'] == null && data_json['NMI'] == null ) {
                         var html_append = '<p>* Number Meter Wrong *</p>';
                         $('#text_check_meter').empty();
                         $('#text_check_meter').append(html_append);
                     }else {
                         var html_append = '';
                         html_append += '<p>*Quote For : ' + data_json['Quote For'] +'</p>';
                         html_append += '<p>*Network Distributor : ' + data_json['Network Distributor'] +'</p>';
                         html_append += '<p>*NMI : ' + data_json['NMI'] +'</p>';
                        
                         $('#text_check_meter').empty();
                         $('#text_check_meter').append(html_append);
                     }
                     $('#checkMeter span.glyphicon-refresh').addClass('hidden');
                 },
             })
         }
         return false;
     })
     //tuan and -----------------
     //dung code -- copy function get NMI  from Lead
     $("#nmi_c").after('<br><button class="button primary" id="getnmi"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get NMI </button>');
     $('#getnmi').on('click', function(event) {
         
         get_number_NMI();
         return false;
     });
     //dung code - open link contact Plumber, Electric in Quotes
     if(module_sugar_grp1 == 'AOS_Quotes'){
         function display_link_contact_plum_elec(){
             $("#link_account_plumquote").remove();
             if( $("#account_id_c").val() != ''){
                 $("#btn_clr_plumber_c").parent().append("<p id='link_account_plumquote'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#account_id_c").val()+ "' target='_blank'>Open Contact</a></p>");
             }
             $("#link_account_elecquote").remove();
             if( $("#account_id1_c").val() != ''){
                 $("#btn_clr_electrician_c").parent().append("<p id='link_account_elecquote'><a  href='/index.php?module=Accounts&action=EditView&record=" + $("#account_id1_c").val()+ "' target='_blank'>Open Contact</a></p>");
             }
         }
         display_link_contact_plum_elec();
         YAHOO.util.Event.addListener(["account_id1_c","account_id_c"], "change", display_link_contact_plum_elec);
     }
     $("#getnmi").after('<button class="button primary" id="checkNMI"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Check NMI </button>');
     $('#checkNMI').after('<div id="text_check_nmi"></div>');
     $('#checkNMI').on('click',function(){
         var nmi_number = $('#nmi_c').val();
         if(nmi_number == '') {
             alert('We have not NMI Number.');
             return false;
         }else {
             $('#checkNMI span.glyphicon-refresh').removeClass('hidden');
             $.ajax({
                 url: "/index.php?entryPoint=CustomCheckNumberNMI&nmi_c=" + nmi_number,
                 type: 'GET',
                 success: function(data)
                 {
                    if(data == '' && typeof data == undefined)return;
                     var data_json =  $.parseJSON(data);
                     if(data_json['Quote For'] == null && data_json['Network Distributor'] == null && data_json['NMI'] == null ) {
                         var html_append = '<p>* Number Meter Wrong *</p>';
                         $('#text_check_nmi').empty();
                         $('#text_check_nmi').append(html_append);
                     }else {
                         var html_append = '';
                         html_append += '<p>*Quote For : ' + data_json['Quote For'] +'</p>';
                         html_append += '<p>*Network Distributor : ' + data_json['Network Distributor'] +'</p>';
                         html_append += '<p>*NMI : ' + data_json['NMI'] +'</p>';
                         $('#address_nmi_c').val(data_json['Quote For']);
                         $('#text_check_nmi').empty();
                         $('#text_check_nmi').append(html_append);
                     }
                     $('#checkNMI span.glyphicon-refresh').addClass('hidden');
                 },
             })
         }
         return false;
     })
     //tuan and ========
 
     if(module_sugar_grp1 == 'AOS_Quotes'){
         var address = $("#install_address_c").val()+','+$("#install_address_city_c").val()+','+$("#install_address_state_c").val()+','+$("#install_address_postalcode_c").val();     
         $("div[data-label='LBL_INSTALL_ADDRESS']").append(
             '<a style="float: right;cursor:pointer;" id="open_map_install_quote" title="open map" onClick=" $(document).find(\'#open_map_popup_install_quote\').fadeToggle();"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
         $("#install_address_c").before(
             '<div style="z-index:10;background-color: white;display:none;border:1px solid;position:absolute; padding:3px;margin-top:-15px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_install_quote" class="show-open-map hide_map">'+
                 '<ul>'+
                     '<li><a style="cursor:pointer;" onclick="openInstallMap(); return false;">Open Map</a></li>'+
                     '<li><a style="cursor:pointer;" href="http://maps.nearmap.com?addr='+ address +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                     '<li><a style="cursor:pointer;" id="get_link_realestate_and_domain" onclick="return false;">Realestate + Domain</a></li>'+
                 '</ul>'+
             '</div>'
         );
     }
     // --------------TriTruong---------------//
     $("#get_link_realestate_and_domain").click(function(){
         var address = $("#install_address_c").val() + " " + $("#install_address_city_c").val() + " " + $("#install_address_state_c").val() + " " + $("#install_address_postalcode_c").val() ;     
         address = address.toLowerCase().replace(/ /g, '-');
         var record_id = $("input[name='record']").val();
         SUGAR.ajaxUI.showLoadingPanel();
         $.ajax({
             url: "?entryPoint=Button_Get_Link_Realestate&record_id="+record_id+"&address="+address,
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
     // -----------------END------------------//
 
     function get_number_NMI(){
         $('#getnmi span.glyphicon-refresh').removeClass('hidden');
         if($("#install_address_c").val() == '' && $("#install_address_city_c").val() == ''
         && $("#install_address_state_c").val() == ''  && $("#install_address_postalcode_c").val() == '') {
             alert('Could you enter "Site Detail Address" please ?');
             $("#install_address_c").focus();
             return false;
         }
         //thien fix for get nmi
         var address = $("#install_address_c").val() + ',' +
                     $("#install_address_city_c").val() + ' ' +
                     $("#install_address_state_c").val() + ' ' +
                     $("#install_address_postalcode_c").val();      
 
         var value =  address.split(",");
         var valueLen = value.length;
         var address1 = value[0];
         for (var i = 1; i < valueLen - 1; i++) {
             address1 = address1 + value[i];
         }
         var address2 = value[valueLen - 1].trim();
 
         var address3 = address2.split(" ");
 
         var address1Items = address1.split(",");
         var address1Len = address1Items.length;
         var addarr = address1Items[address1Len - 1].trim().split(" ");
         var a_first_addres = "";
         var unit = "";
         var unit_num = "";
         var address_number = "";
         var address_name = "";
         if(addarr.length == 2){
             //a_first_addres = addarr[0].replace("Unit","U")+ "/";
             //a_first_addres += addarr[1].replace(/ /,"/");
 
             // Unit param
             var unit_numbers = addarr[0].split(" ");
             unit = unit_numbers[0];
             unit_num = unit_numbers[1];
             var address_numbers =addarr[1].split(" ");
             address_number = address_numbers[0];
             address_name = address_numbers[1].replace(" ", "+");
 
         }
         else{
             //a_first_addres  = "NA/"
             //a_first_addres +=  address1.replace(/ /,"/");
 
             var address_numbers = addarr;
             address_number = address_numbers[0];
             address_name = address_numbers[1].replace(" ", "+");
         }
         var requestString ; //= a_first_addres + "/" + address3[0].trim() +"/"+ address3[1].trim() +"/"+ address3[2].trim();
         //var street = explode()
         requestString = encodeURIComponent("unit=" + unit + "/unit_num=" + unit_num + "/streetNumber="+address_number+"/streetName="+address_name+"/city="+ address3[0] + "/state="+address3[1]+"/customerType=residential/searchByPostcode=false/postcode="+address3[2]+"/fuelType=dual&hasSolarPanels=false/connectionScenario=PROS_SWT");
 
         $.ajax({
             url: "/index.php?entryPoint=customGetRetailer&address=" + address + "&momentumenergy=1&requestString="+requestString,
             type: 'GET',
             async: false, 
             success: function(data)
             {
                 if(data.indexOf("ChooseExactMeter_Nmis_0__MeterNumber") >= 0){
                     $(".modal_nmi").remove();
                     var html = '<div class="modal fade modal_nmi" tabindex="-1" role="dialog">'+
                                     '<div class="modal-dialog">'+
                                         '<div class="modal-content">'+
                                             '<div class="modal-header">'+
                                                 '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                 '<h4 class="modal-title" id="title-generic">Select NMI</h4>'+
                                             '</div>'+
                                             '<div class="modal-body">'+
                                                 '<div class="container-fluid" style="margin-left:30px;">'+data+
                                                 '</div>'+
                                             '</div>'+
                                         '</div>'+
                                     '</div>'+
                                 '</div>';
                     $("body").append(html);
                     $(".modal_nmi").modal('show');
                     $(".modal_nmi").find("input").click(function(){
                         $("#nmi_c").val($(this).val());
                         $("#address_nmi_c").val($("#ChooseExactMeter_Nmis_"+$(this).index()+"__Address").val());
                         $(".modal_nmi").modal('hide');
                     })
                 }else{
                     $("#nmi_c").val(data);
                     if(data !== ""){
                         getNMI();
                     }
                     if (data == '')
                     {
                         var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
                         if (ok)
                             window.open('https://www.momentumenergy.com.au/', '_blank');
                         $("#nmi_c").val('');
                         $("#address_nmi_c").val('');
                     }
                 }
                 $('#getnmi span.glyphicon-refresh').addClass('hidden');
             },
 
             error: function(response)
             {
                 $('#getnmi span.glyphicon-refresh').addClass('hidden');
 
                 var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
                 if (ok)
                     window.open('https://www.momentumenergy.com.au/', '_blank');
                 $("#nmi_c").val('');
                 $("#address_nmi_c").val('');
             },
         });
 
     }
 
     function getNMI(){
         var nmi = $("#nmi_c").val();
         if (parseInt(nmi) != nmi)
         {
             $("#nmi_c").val('');
             $("#address_nmi_c").val('');
             alert("Invalid NMI!");
             return false;
         }
 
         $('#getDistributor span.glyphicon-refresh').removeClass('hidden');
 
         nmi = parseInt(nmi);
 
         var NSP = [
             {
                 name: "Citipower",
                 value: 4,
                 range: [{min: 6102000000, max: 6103999999}]
             },
             {
                 name: "Jemena",
                 value: 5,
                 range: [{min: 6001000000, max: 6001999999}]
             },
             {
                 name: "Powercor",
                 value: 6,
                 range: [{min: 6203000000, max: 6204999999}]
             },
             {
                 name: "Ausnet",
                 value: 7,
                 range: [{min: 6305000000, max: 6306999999},
                         {min: 6509000000, max: 6509009999}]
             },
             {
                 value: "United",
                 value: 8,
                 range: [{min: 6407000000, max: 6408999999}]
             },
             {
                 name: "Western Power",
                 value: 1,
                 range: [{min: 8001000000, max: 8020999999}]
             },
             {
                 name: "SA Power Networks - NSP",
                 value: 13,
                 range: [{min: 2001000000, max: 2002999999}]
             },
             {
                 name: "Energex",
                 value: 2,
                 range: [{min: 3100000000, max: 3199999999}]
             },
             {
                 name: "Ergon",
                 value: 3,
                 range: [{min: 3000000000, max: 3099999999}]
             },
             {
                 name: "Essential Energy",
                 value: 9,
                 range: [{min: 4001000000, max: 4001999999}, {min: 4508000000, max: 4508099999},
                         {min: 4204000000, max: 4204999999}, {min: 4407000000, max: 4407999999}]
             },
             {
                 name: "Ausgrid",
                 value: 10,
                 range: [{min: 4102000000, max: 4104999999}]
             },
             {
                 name: "Endeavour Energy",
                 value: 12,
                 range: [{min: 4310000000, max: 4319999999}]
             },
             {
                 name: "ActewAGL",
                 value: 11,
                 range: [{min: 7001000000, max: 7001999999}]
             },
         ];
 
         var NSPLen = NSP.length;
         for (var i = 0; i < NSPLen; i++)
         {
             var range = NSP[i].range
             var rangeLen = range.length;
             for (var j = 0; j < rangeLen; j++)
             {
                 if ((nmi >= range[j].min && nmi <= range[j].max) ||
                     (nmi >= range[j].min * 10 && nmi <= range[j].max * 10 + 9))
                 {
                     $("#distributor_c").val(NSP[i].value);
                     //thien fix show Ausnet_Approval button
                     if(NSP[i].value == 7){
                        $('#Ausnet_Approval').show(); 
                     }else{
                         $('#Ausnet_Approval').hide();
                     }
 
                     //thien fix show register jemena button
                     if(NSP[i].value == 5){
                         $('#register_jemena_account').show();
                      }else{
                          $('#register_jemena_account').hide();
                      }
                     $('#getDistributor span.glyphicon-refresh').addClass('hidden');
 
                     return false;
                 }
             }
         }
 
         $('#getDistributor span.glyphicon-refresh').addClass('hidden');
 
         return false;
     }
     // END dung code - copy function get NMI  from Lead
 
     //dung code - copy button get price SG from module lead
     var sgPrices = {
         "VIC":{
             "option1":7490,
             "option2":8590,
             "option3":10690,
             "option4":9790,
             "option5":11390,
             "option6":14590},
         "SA":{
             "option1":6890,
             "option2":7790,
             "option3":9490,
             "option4":8990,
             "option5":10590,
             "option6":13290},
         "NSW":{
             "option1":7290,
             "option2":8290,
             "option3":9990,
             "option4":9690,
             "option5":10990,
             "option6":14490},
         "ACT":{
             "option1":7890,
             "option2":8790,
             "option3":10590,
             "option4":9990,
             "option5":11590,
             "option6":14490},
         "QLD":{
             "option1":6390,
             "option2":7290,
             "option3":8990,
             "option4":8690,
             "option5":9990,
             "option6":12990}
         };
     var state_ =  $('#billing_address_state').val();
     if(typeof sgPrices[state_] !== 'undefined'){
         // do nothings
 
     }
     else {
         switch (state_.toLowerCase()) {
             case 'victoria':
                 state_ = 'VIC';
                 break;
             case 'queensland':
                 state_ = 'QLD';
                 break;
             case 'new south wales':
                 state_ = 'NSW' ;
                 break;
             case 'australian capital territory':
                 state_ = 'ACT';
                 break;
             case 'south australia':
                 state_ = 'SA';
                 break;
             default:
                 state_ = 'not value support';
                 break;
         }
     }
     var div_html = '';
     if(state_ !== 'not value support' ){
         for(i=1;i<=6;i++){
             div_html += 'Price option '+i+": "+sgPrices[state_]['option'+i]+'</br>';
         }
     }else {
         div_html = 'Not data for this state ';
     }
    //  $('#solargain_options_c').after("<div id='state_option_price' style='position: absolute;left: 150px;top:-5px;font-size:13px'>"+div_html+"</div>");
     // Thienpb code for get state option price 
     $(document).find('#state_option_price').after('</br><br> <button type="button" class="button primary" id="getSGPrice"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get SG Price </button>');
 
     $("#getSGPrice").click(function(){
         $('#getSGPrice span.glyphicon-refresh').removeClass('hidden');
         $(".div_option").remove();
         var sg_quote = $("#solargain_quote_number_c").val();
         if(sg_quote != ''){
             $.ajax({
                 url: '?entryPoint=customGetSolarGainStateOptionPrice&sg_quote_id='+sg_quote,
                 type : 'GET',
                 dataType : 'json',
                 success: function (data) {
                    if(data == '' && typeof data == undefined){$('#getSGPrice span.glyphicon-refresh').addClass('hidden');return;}
                     $('#getSGPrice').after("<div class='div_option' style='position: absolute;left: 285px;top:-5px;font-size:13px'><div style='margin-right: 20px;float: left; font-weight: bold;font-size: 14px;'>SG Price:</div><div style='float:left;'>"+data.html+"</div><div class='clear'></div></div>");
                     $('#getSGPrice').after("<div class='div_option' style='position: absolute;left: 465px;top:-5px;font-size:13px'><div style='margin-right: 20px;float: left; font-weight: bold;font-size: 14px;'>Inverter Model:</div><div style='float:left;'>"+data.html_inverter+"</div><div class='clear'></div></div>");
                     if(data.error == 'error'){
                         alert("Different Inverter Model in Options");
                         $("#solargain_inverter_model_c").val('');
                     }else{
                         $("#solargain_inverter_model_c").val(data.model);
                     }
                     $('#getSGPrice span.glyphicon-refresh').addClass('hidden');
                 },
             })
         }else{
             alert("Solargain Quote Number is required!");
             $('#getSGPrice span.glyphicon-refresh').addClass('hidden');
         }
     })
     //end dung code -- copy button get price SG from module lead
 
     //dung code - copy function open link into SG From Lead
         // SG Lead
         SolarGainLeadNumberLink();
 
         $('#solargain_lead_number_c').change(function () {
             SolarGainLeadNumberLink();
         });
 
         //SG Quote
         SolarGainQuoteNumberLink();
         $('#solargain_quote_number_c').change(function () {
             SolarGainQuoteNumberLink();
         });
         SolarGainQuoteNumberLinkTesla();
         $('#solargain_tesla_quote_number_c').change(function () {
             SolarGainQuoteNumberLinkTesla();
         });
     //end dung code - copy function open link into SG from Lead
 
     // dung code --- copy function get Notes Default 
         //button get value default
         $('#special_notes_c').parent().siblings('.label').append('<br> <button class="button primary" id="get_value_default"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Notes Default </button>');
         var primary_address_state = '';
         if($("#install_address_state_c").val() == ''){
             primary_address_state = $("#billing_address_state").val().toUpperCase();
         }else{
             primary_address_state = $("#install_address_state_c").val().toUpperCase();
         }
         if(primary_address_state !== '') {
             switch (primary_address_state) {
                 case 'VICTORIA':
                     primary_address_state = 'VIC';
                     break;
                 case 'QUEENSLAND':
                     primary_address_state = 'QLD';
                     break;
                 case 'NEW SOUTH WALES':
                     primary_address_state = 'NSW' ;
                     break;
                 case 'AUSTRALIAN CAPITAL TERRITORY':
                     primary_address_state = 'ACT';
                     break;
                 case 'SOUTH AUSTRALIA':
                     primary_address_state = 'SA';
                     break;
             } 
         }
         //button get default specail notes value
         window.special_notes_value_default = '';
         $.ajax({
             url: 'index.php?entryPoint=CRUD_Special_Notes' ,
             type: 'POST',
             async: false,
             data: 
             {
                 action: 'read',
             },
             success: function(result) {   
                if(result == '' && typeof result == undefined)return;
                 var data_result = $.parseJSON(result);
                 $.each(data_result, function(k,v){
                     if(v.title == 'No Upgrade Options') {
                         window.special_notes_value_default = v.content;
                         if($('input[name="record"]').val() == ''){
                             $('#special_notes_c').val(window.special_notes_value_default);
                             $('#special_notes_c').css('width','100%');
                             $('#special_notes_c').css('height','25%');
                         }
                     }
                 })
             }
         }); 
         
 
 
         $("#get_value_default").click(function(){
             $('#special_notes_c').val(window.special_notes_value_default);
             $('#special_notes_c').css('width','100%');
             $('#special_notes_c').css('height','25%');
             return false;
         });
 
         //button push special note into SG
         $('#special_notes_c').parent().siblings('.label').append('<br> <button class="button primary" id="pushNoteToSG"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Push to Solargain </button>');
         $("#pushNoteToSG").click(function(){
             $('#pushNoteToSG span.glyphicon-refresh').removeClass('hidden');
             var build_url_quote = "?entryPoint=CustomUploadSpecialNoteQuoteToSolarGain";
             build_url_quote += '&record='+ encodeURIComponent($('input[name="record"]').val());
             build_url_quote += "&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
             build_url_quote += "&specialNotes="+encodeURIComponent($("#special_notes_c").val());
             $.ajax({
                 url: build_url_quote,
                 type : 'POST', 
                 success: function (data) {
                     $('#pushNoteToSG span.glyphicon-refresh').addClass('hidden');
                 },
             });
             return false;
         });
     //end code  -- copy function get notes default
 
     //dung code -- copy function  Distributor
             $("#distributor_c").after('<br><button class="button primary" id="getDistributor"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Distributor </button>'); 
             $("#getDistributor").after("<br><button class='button primary' style='display:none' type='button'  id='Ausnet_Approval' name = 'Ausnet_Approval' ><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span> Ausnet Approval </button>");
             $("#getDistributor").after("<br><button class='button primary' style='display:none' type='button'  id='register_jemena_account' name = 'register_jemena_account' ><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span> Register Jemena Account </button>");     
             $('#distributor_c').change(function(){
                 if($("#distributor_c").val() == 5){
                     $('#register_jemena_account').show();
                 }else{
                     $('#register_jemena_account').hide();
                 }
 
                 if($("#distributor_c").val() == 7){
                     $('#Ausnet_Approval').show();
                 }else{
                     $('#Ausnet_Approval').hide();
                 }
 
             });
             //thien add button  register jemena account   
                 $("#register_jemena_account").click(function(){
                     $('#register_jemena_account span.glyphicon-refresh').removeClass('hidden');
                     var nmi_number_jemena = $("#nmi_c").val();
                     var meter_number_jemena = $("#meter_number_c").val();
                     var AddressLineOne = $("#billing_address_street").val().trim()+' '+$("#billing_address_city").val();
                     var subrb = $("#billing_address_state").val();
                     var postcode = $("#billing_address_postalcode").val();
                     var account_id = $('#billing_contact_id').val();
                     if(nmi_number_jemena == '' || meter_number_jemena == ''){
                         alert("Don\'t have number NMI or Meter Number");
                         $('#Ausnet_Approval span.glyphicon-refresh').addClass('hidden');
                         return false;
                     }
                     $.ajax({
                         url: "/index.php?entryPoint=CustomQuoteRegistrationJemena&nmiNumber=" +nmi_number_jemena+"&meterNumber="+meter_number_jemena+"&AddressLineOne="+AddressLineOne+"&postcode="+postcode+"&subrb="+subrb+"&contact_id="+account_id,
                         type: 'GET',
                         success: function(data){
                            if(data == '' && typeof data == undefined)return;
                             if(data.indexOf("@sharklasers.com") > 0){
                                 $("#jemena_account_c").val(data);
                                 $('#register_jemena_account span.glyphicon-refresh').addClass('hidden');
                             }else{
                                 $(".modal_jemena").remove();
                                 var html = '<div class="modal fade modal_jemena" tabindex="-1" role="dialog">'+
                                                 '<div class="modal-dialog">'+
                                                     '<div class="modal-content">'+
                                                         '<div class="modal-header">'+
                                                             '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                             '<h4 class="modal-title" id="title-generic"><center>Jemena Account Error</center></h4>'+
                                                         '</div>'+
                                                         '<div class="modal-body">'+
                                                             '<div class="container-fluid" style="margin-left:30px;font-size:13px;text-align:center;">'+data+
                                                             '</div>'+
                                                         '</div>'+
                                                     '</div>'+
                                                 '</div>'+
                                             '</div>';
                                 $("body").append(html);
                                 $(".modal_jemena").modal('show');
                                 $('#register_jemena_account span.glyphicon-refresh').addClass('hidden');
                             }
                         }
                     });
         
                 });        
             //thien add button Ausnet approval
                 $("#Ausnet_Approval").click(function(){
                     $('#Ausnet_Approval span.glyphicon-refresh').removeClass('hidden');
                     var nmi_number_meter = $("#nmi_c").val();
                     var meter_number = $("#meter_number_c").val();
                     var pre_install_photos_c_id = $('input[name="pre_install_photos_c"]').val();
                     if(meter_number == '' || nmi_number_meter == ''){
                         alert("Don\'t have number NMI or Meter Number");
                         $('#Ausnet_Approval span.glyphicon-refresh').addClass('hidden');
                         return false;
                     }
                     $.ajax({
                         url: "/index.php?entryPoint=getAusnetApproval&nmi_number=" + nmi_number_meter +"&meter_number="+meter_number+"&pre_install_photos_c_id="+pre_install_photos_c_id,
                         type: 'GET',
                         success: function(data){
         
                             if(data=='done'){
                                 alert('Get PDF file successfuly!');
                             }else{
                                 alert('Get PDF file Fail! Please check NMI Number and Meter Number.');
                             }
         
                             $('#Ausnet_Approval span.glyphicon-refresh').addClass('hidden');
                             $(".files").empty();                
                             $.ajax({
                                 url: $('#fileupload').fileupload('option', 'url'),
                                 dataType: 'json',
                                 context: $('#fileupload')[0]
                             }).always(function () {
                                 $(this).removeClass('fileupload-processing');
                     
                             }).done(function (result) {
                                 $(this).fileupload('option', 'done')
                                     .call(this, $.Event('done'), {result: result});
                             });
                         },
                         error: function(response){
                             alert('Get PDF file Fail! Please check NMI Number and Meter Number.');
                             $('#Ausnet_Approval span.glyphicon-refresh').addClass('hidden');
                         },
                     });
                 })
 
             //button getDistributor
                 function getNMI(){
                     var nmi = $("#nmi_c").val();
                     if (parseInt(nmi) != nmi)
                     {
                         $("#nmi_c").val('');
                         $("#address_nmi_c").val('');
                         alert("Invalid NMI!");
                         return false;
                     }
         
                     $('#getDistributor span.glyphicon-refresh').removeClass('hidden');
         
                     nmi = parseInt(nmi);
         
                     var NSP = [
                         {
                             name: "Citipower",
                             value: 4,
                             range: [{min: 6102000000, max: 6103999999}]
                         },
                         {
                             name: "Jemena",
                             value: 5,
                             range: [{min: 6001000000, max: 6001999999}]
                         },
                         {
                             name: "Powercor",
                             value: 6,
                             range: [{min: 6203000000, max: 6204999999}]
                         },
                         {
                             name: "Ausnet",
                             value: 7,
                             range: [{min: 6305000000, max: 6306999999},
                                     {min: 6509000000, max: 6509009999}]
                         },
                         {
                             value: "United",
                             value: 8,
                             range: [{min: 6407000000, max: 6408999999}]
                         },
                         {
                             name: "Western Power",
                             value: 1,
                             range: [{min: 8001000000, max: 8020999999}]
                         },
                         {
                             name: "SA Power Networks - NSP",
                             value: 13,
                             range: [{min: 2001000000, max: 2002999999}]
                         },
                         {
                             name: "Energex",
                             value: 2,
                             range: [{min: 3100000000, max: 3199999999}]
                         },
                         {
                             name: "Ergon",
                             value: 3,
                             range: [{min: 3000000000, max: 3099999999}]
                         },
                         {
                             name: "Essential Energy",
                             value: 9,
                             range: [{min: 4001000000, max: 4001999999}, {min: 4508000000, max: 4508099999},
                                     {min: 4204000000, max: 4204999999}, {min: 4407000000, max: 4407999999}]
                         },
                         {
                             name: "Ausgrid",
                             value: 10,
                             range: [{min: 4102000000, max: 4104999999}]
                         },
                         {
                             name: "Endeavour Energy",
                             value: 12,
                             range: [{min: 4310000000, max: 4319999999}]
                         },
                         {
                             name: "ActewAGL",
                             value: 11,
                             range: [{min: 7001000000, max: 7001999999}]
                         },
                     ];
         
                     var NSPLen = NSP.length;
                     for (var i = 0; i < NSPLen; i++)
                     {
                         var range = NSP[i].range
                         var rangeLen = range.length;
                         for (var j = 0; j < rangeLen; j++)
                         {
                             if ((nmi >= range[j].min && nmi <= range[j].max) ||
                                 (nmi >= range[j].min * 10 && nmi <= range[j].max * 10 + 9))
                             {
                                 $("#distributor_c").val(NSP[i].value);
                                 //thien fix show Ausnet_Approval button
                                 if(NSP[i].value == 7){
                                 $('#Ausnet_Approval').show(); 
                                 }else{
                                     $('#Ausnet_Approval').hide();
                                 }
         
                                 //thien fix show register jemena button
                                 if(NSP[i].value == 5){
                                     $('#register_jemena_account').show();
                                 }else{
                                     $('#register_jemena_account').hide();
                                 }
                                 $('#getDistributor span.glyphicon-refresh').addClass('hidden');
         
                                 return false;
                             }
                         }
                     }
         
                     $('#getDistributor span.glyphicon-refresh').addClass('hidden');
         
                     return false;
                 }
                 $('#getDistributor').on('click', function (event){
                     return getNMI();
                 });
 
     //end dung code -- copy function Distributor
     //thienpb code -- minimise sub panel
     
     function minimise(){
         var panel_usually_open = ['overview','customer details','job site information','key fields'];
         //solar
         if ($('#name').val().toLowerCase().includes('solar')) {
             panel_usually_open.push('pricing pv section');
             $('.panel-content .panel-default').each(function(){
                 var current_selector_panel_id = '#' + $(this).find('.panel-body').attr('id');
                 var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
                 if(title_panel_default.includes('solar') || panel_usually_open.includes(title_panel_default) || title_panel_default == 'site details'){
                     if($("#group0name").val() != '' && title_panel_default == 'line items') {
                         $(current_selector_panel_id).addClass('in');
                         $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                     }
                     $(current_selector_panel_id).addClass('in');
                     $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                 }else{
                     if(check_exist_value($(this))) {
                         $(current_selector_panel_id).addClass('in');
                         $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');    
                     }else{
                         $(current_selector_panel_id).removeClass("in");
                         $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
                     }
                 }
             });
         }
         // daikin
         else if ($('#name').val().toLowerCase().includes('daikin')) {
             $('.panel-content .panel-default').each(function(){
                 var current_selector_panel_id = '#' + $(this).find('.panel-body').attr('id');
                 var panel_close = ['solargain infomation', 'pricing pv section', 'solar pv pricing' , 'solar victoria provider statement'];
                 var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
                 if(title_panel_default.includes('daikin') || panel_usually_open.includes(title_panel_default)){
                     $(current_selector_panel_id).addClass('in');
                     $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                 }else{
                     if($("#group0name").val() != '' && typeof($("#group0name").val()) != 'undefined' && title_panel_default == 'line items') {
                         $(current_selector_panel_id).addClass('in');
                         $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                     }else{
                         if(check_exist_value($(this)) && !panel_close.includes(title_panel_default)) {
                             $(current_selector_panel_id).addClass('in');
                             $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');    
                         }else{
                             $(current_selector_panel_id).removeClass("in");
                             $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
                         }
                     }
                 }
             });
         }
         //sanden
         else if ($('#name').val().toLowerCase().includes('sanden')) {
             $('.panel-content .panel-default').each(function(){
                 var current_selector_panel_id = '#' + $(this).find('.panel-body').attr('id');
                 var panel_close = ['solargain infomation', 'pricing pv section', 'solar pv pricing' , 'solar victoria provider statement'];
                 var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
                 if(title_panel_default.includes('sanden') || panel_usually_open.includes(title_panel_default)){
                     $(current_selector_panel_id).addClass('in');
                     $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                 }else{
                     if($("#group0name").val() != '' && typeof($("#group0name").val()) != 'undefined' && title_panel_default == 'line items') {
                         $(current_selector_panel_id).addClass('in');
                         $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                     }else{
                         if(check_exist_value($(this)) && !panel_close.includes(title_panel_default)) {
                             $(current_selector_panel_id).addClass('in');
                             $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');    
                         }else{
                             $(current_selector_panel_id).removeClass("in");
                             $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
                         }
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
                     if($("#group0name").val() != '' && typeof($("#group0name").val()) != 'undefined' && title_panel_default == 'line items') {
                         $(current_selector_panel_id).addClass('in');
                         $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                     }else{
                         if(check_exist_value($(this))) {
                             $(current_selector_panel_id).addClass('in');
                             $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');    
                         }else{
                             $(current_selector_panel_id).removeClass("in");
                             $(current_selector_panel_id).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
                         }
                     }
                 }
             });
         }
     }
     if(module_sugar_grp1 == 'AOS_Quotes'){
         minimise();
         $('#name').change(function(){
             minimise();
         });
     }
 
     function check_exist_value(e){
         var result = false;
         e.find("input[type='text']").each (function(){
             if($(this).val() != ''){
                 result = true;
             }
         });
         return result;
     }
     //tu-code --minse sub panel
 //     if(module_sugar_grp1 == 'AOS_Quotes'){
 //     function minimise_sub(option){
 //         var check;
 //         $("#detailpanel_"+option).find("input[type='text']").each(function(){
 //             if($(this).val() != ''){ 
 //                 check = true;
 //                 return false;  
 //             }else{
 //                 check = false;   
 //             }
 //         });
 //         if(check == false){
 //             $('#detailpanel_'+option).removeClass('in');
 //             $('#detailpanel_'+option).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
 //         }
 //     }
 //     for(var i = -1; i < 6;i++){
 //         if( i != 4){
 //             minimise_sub(i);
 //         }
 //     }
 //     $('#detailpanel_0,#detailpanel_1').addClass('in');
 //     $('#detailpanel_0,#detailpanel_1').siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
 //     $("#solargain_quote_number_c").change(function(){
 //         if($("#solargain_quote_number_c").val() != ''){
 //             $('#detailpanel_4').addClass('in');
 //             $('#detailpanel_4').siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
 //         }else{
 //             $('#detailpanel_4').removeClass('in');
 //             $('#detailpanel_4').siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
 //         }
 //     })
 //     //tu-code auto minimis solar PV pricing
 //     if($("#panel_type_1" ).val() == '' && $("#panel_type_1" ).val() == '' && $("#panel_type_1" ).val() == ''){
 //         if($("#panel_type_4" ).val() == '' && $("#panel_type_5" ).val() == '' && $("#panel_type_6" ).val() == ''){
 //             $('#detailpanel_6').removeClass('in');
 //             $('#detailpanel_6').siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
 //         }
 //     }
 //     if ($('#solar_pv_pricing_input_c').val() == '') {
 //         $('#detailpanel_7').removeClass('in');
 //         $('#detailpanel_7').siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
 //     } 
 // }
     // dung code ---  Button request solar design
 
     $("#SAVE").after(
         '&nbsp;<button type="button" id="sendMailToAdmin" class="button sendMailToAdmin" title="Request Designs" > Request Designs <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
     );
 
     $("#sendMailToAdmin").click(function(){
         if ($("#billing_address_street").val() == '' ) {
             alert("Cannot send email No Street address entered");
             return;
         }
         if($('#time_request_design_c').val() !== ''){
             alert("You can't send email because it has sent.");
             return;
         }
     
         
         var quote_id = '';
         if($('input[name="record"]').val() == ''){
             var ok = confirm('Quote is not saved! Do you want Save and send Request Designs?');
             if(ok ==  true){
                 $('#sendMailToAdmin span.glyphicon-refresh').removeClass('hidden');
                 $("#EditView input[name='action']").val('Save');
                 $.ajax({
                     type: $("#EditView").attr('method'),
                     url: $("#EditView").attr('action'),
                     data: $("#EditView").serialize(),
                     async:false,
                     success: function (data) {
                        if(data == '' && typeof data == undefined)return;
                         var quote_id_patt = /"record" value="(.*)"/g;
                         quote_id = quote_id_patt.exec(data);
                         if(quote_id !== null && typeof quote_id === 'object'){
                             if(quote_id[1] !='')  {
                                 quote_id = quote_id[1];
                             }
                         }
                     }
                 });
             }else{
                 return;
             }
         }else{
             $('#sendMailToAdmin span.glyphicon-refresh').removeClass('hidden');
             quote_id = $("#EditView input[name='record']").val();
         }
         var _url = "?entryPoint=customQuoteSendEmailToAdmin&record_id="
                     + quote_id
                     + "&billing_address_street=" + $("#billing_address_street").val()
                     + "&billing_address_city=" + $("#billing_address_city").val()
                     + "&billing_address_state=" + $("#billing_address_state").val()
                     + "&billing_address_postalcode=" + $("#billing_address_postalcode").val();
     
         $.ajax({
             url: _url,
             type: 'GET',
     
         }).done(function (data) {
             $('#sendMailToAdmin span.glyphicon-refresh').addClass('hidden');
             console.log(data);
             //dung code - display time click "Request Design"
             var value = data.split(' ');
 
             var date = value[0];
             $('#time_request_design_c_date').val(date);
 
             var times = value[1].split(':');
             $('#time_request_design_c_hours').val(times[0]);
 
             var minutes = times[1] - (times[1] % 15);
             if (minutes == 0)
             {
                 minutes = '00';
             }
             $('#time_request_design_c_minutes').val(minutes);
             $('#time_request_design_c').val(date + ' ' + times[0] + ':' + minutes);
             //VUT change status to "Request_Designs"
             $("#stage").val("Request_Designs");
         });
     })
 
     //dung code --- button get all file SAM in module AOS_Quotes (Copy logic from module Leads)
     $("#get_all_files").after('<button type="button" id="get_sg_image_options" class="button primary" title="Get ALL File From SG">Get ALL File From SG<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
     $("#get_sg_image_options").click(function(){
 
        SUGAR.ajaxUI.showLoadingPanel();
         var quote_id =  $('input[name="record"]').val();
         if(quote_id == ''){
             alert('Not have number ID Quotes. Please save and edit before.');
             $('#get_sg_image_options span.glyphicon-refresh').addClass('hidden');
             $("input[name='button']").first().focus();
             return;
         }
 
         var pre_install_photos_c = $("input[name='pre_install_photos_c']").val();
         var quote_sg = $("#solargain_quote_number_c").val();
         var lead_sg = $("#solargain_lead_number_c").val();
         var check_download = false;
         setTimeout(function(){
             if($('#solargain_quote_number_c').val() !== ''){
                 var url_download = "?entryPoint=CustomDownloadPDF&module=AOS_Quotes&type=quote&record_id="
                 + $('input[name="record"]').val()
                 + '&quote_solorgain='+$('#solargain_quote_number_c').val()
                 + '&SGleadID='+lead_sg
                 + '&folder_id='+$("input[name='pre_install_photos_c']").val();
                 $.ajax({
                     url: url_download,
                     async:false,
                     success: function(data){
                         $(".files").empty();
                         check_download = true;             
                     }
                 })
             }
 
             if($('#solargain_tesla_quote_number_c').val() !== ''){
                 $('#get_sg_image_options span.glyphicon-refresh').removeClass('hidden');
                 var url_download = "?entryPoint=CustomDownloadPDF&module=AOS_Quotes&type=tesla&record_id="
                 + $('input[name="record"]').val()
                 + '&quote_solorgain='+$('#solargain_tesla_quote_number_c').val()
                 + '&SGleadID='+lead_sg
                 + '&folder_id='+$("input[name='pre_install_photos_c']").val();
                 $.ajax({
                     url: url_download,
                     type: 'GET',
                     async:false,
                     success: function(data){
                         $(".files").empty();
                         check_download = true;      
                     }
                 });
             }
             if(check_download == true) {
                 $.ajax({
                     url: "?entryPoint=downloadSGImageOptions&module=AOS_Quotes&quote_solorgain="+quote_sg+"&pre_install_photos_c="+pre_install_photos_c+'&SGleadID='+lead_sg,
                     async:false,
                     success: function(data)
                     {
                         $(".files").empty();
                     }
                 });
                 $.ajax({
                     url: $('#fileupload').fileupload('option', 'url'),
                     dataType: 'json',
                     context: $('#fileupload')[0],
                 }).always(function () {
                     $(this).removeClass('fileupload-processing');
                 }).done(function (result) {
                     $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                     SUGAR.ajaxUI.hideLoadingPanel();                 
                });
             }
         },1000);
 
     });
    //thienbp code Remove istore
    //$("#get_all_files").after('<button type="button" id="remove_istore" class="button primary" title="Remove Istore">Remove Istore<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
    $("#get_all_files").after('<button type="button" id="get_photos_floorplan" class="button primary" title="get photo floorplan">Get Photos Floor Plan<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');

    // $("#remove_istore").click(function(){
    //     var pre_install_photos_c = $("input[name='pre_install_photos_c']").val();
    //     var url_download = "?entryPoint=removeIstoreFromPDF&folder_id="+pre_install_photos_c;
    //     SUGAR.ajaxUI.showLoadingPanel();
    //     setTimeout(function(){
    //         $.ajax({
    //             url: url_download,
    //             async:false,
    //             success: function(data){
    //                 SUGAR.ajaxUI.hideLoadingPanel();
    //             }
    //         });
    //     },1000)
    // });
     //end
     $("#get_photos_floorplan").click(function(){
        var address_site = $("#install_address_c").val()+'-'+$("#install_address_city_c").val()+'-'+$("#install_address_state_c").val()+'-'+$("#install_address_postalcode_c").val();  
        var generateUUID = $('input[name="pre_install_photos_c"]').val();
        SUGAR.ajaxUI.showLoadingPanel();
        $.ajax({
           url:  "/index.php?entryPoint=getPhotosFloorPlan&address="+address_site.replace(/\s/g,"-")+"&generateUUID="+ generateUUID,
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
                        SUGAR.ajaxUI.hideLoadingPanel();                   
                    });   
                }else {
                   alert( "No floor plan available !");
                   SUGAR.ajaxUI.hideLoadingPanel();               
                }

           }
       })      
    });
     //thienpb code
     $("#get_all_files").after('<button type="button" id="get_files_from_s3" class="button primary" title="Get Files From S3">Get Files From S3<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
     $("#get_files_from_s3").click(function(){
         $('#get_files_from_s3 span.glyphicon-refresh').removeClass('hidden');
         var quote_id =  $('input[name="record"]').val();
         if(quote_id == ''){
             alert('Not have number ID Quotes. Please save and edit before.');
             $('#get_files_from_s3 span.glyphicon-refresh').addClass('hidden');
             $("input[name='button']").first().focus();
             return;
         }
         var pre_install_photos_c = $("input[name='pre_install_photos_c']").val();
 
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
                     $('#get_files_from_s3 span.glyphicon-refresh').addClass('hidden');
                 });           
             }
         })
     });
 
 });
 /**VUT - Check SSI and SSO for Quote type Sanden to create PO Sanden Supply*/
 function isSandenSupply() {
    let products = $('#lineItems').find('.product_group').children('tbody');
    let state = $('#install_address_state_c').val();
    let states = ['NSW', 'QLD', 'SA', 'WA'];
    let i=0, SSI=false; 
    let sanden_groups = {
        SSI : false,
        SSO : false,
    };
    for (i=0;i< products.length; i++) {
        let partNumber = $(`#product_part_number${i}`).val();
        if (partNumber.indexOf("SSI") != -1) {
            SSI = true;
        } else if (partNumber.indexOf("SANDEN_SUPPLY_ONLY") != -1) {
            sanden_groups.SSO = true;
        }
    }
    if (SSI && states.includes(state)) {
        sanden_groups.SSI = true;
    }
    return sanden_groups;
} 
 function convertToInvoice(){ /////
    //VUT - S - check VEEC && Old HWS Subpanel >> https://trello.com/c/92TactmT/2881-quote-converting-to-invoice-old-hws-information-fields-when-convert-and-if-they-are-not-filled-in-create-warning-box 
    if (checkLineItem('Veecs')) {
        var warning_oldHWS = [];
        $('#old_tank_serial_c, #old_tank_model_c, #old_tank_make_c').each(function(){
            if ($(this).val() == '') {
                warning_oldHWS.push($(this).parent().siblings('div.label').text().trim().replace(":","")+ " isn't filled! \n");
            }
        });
        if (warning_oldHWS.length > 0) {
            // alert(warning_oldHWS + 'are you sure to continue?');
            var question = confirm('VEECs are included in the line item \nBUT: \n'+warning_oldHWS.join('') + 'ARE YOU SURE TO CONTINUE?');
            if (!question) {
                return false;
            }
        }
    }
    //VUT - E - check VEEC && Old HWS Subpanel >> https://trello.com/c/92TactmT/2881-quote-converting-to-invoice-old-hws-information-fields-when-convert-and-if-they-are-not-filled-in-create-warning-box 
    var check = isSandenSupply();
    if ($('#quote_type_c').val() == "quote_type_sanden") {
        // dispatch date  and install date require for sanden 
        // dispatch date require for sanden supply not need install date
        if( $('#proposed_install_date_c').val() == '') {
            if(check.SSO == false){
                var question = confirm("Field Proposed Install Date is not filled - are you sure to continue?");
                if (question) {} 
                else {
                    $('#proposed_install_date_c_date').css('border', '1px solid #a5e8d6');
                    return false;
                }
            }
        }   

        if( $('#proposed_dispatch_date_c').val() == '') {
                // igore when state = VIC
                var address_quote = $("#install_address_state_c").val().trim().toUpperCase();
                var check_display_warning = true;
                if(address_quote != '') {
                    if(address_quote.indexOf('VIC') != -1){
                        check_display_warning = false;
                    }
                }
                
                if(check_display_warning || check.SSO == true) {
                    var question = confirm("Field Proposed Dispatch Date is not filled - are you sure to continue?");
                    if (question) {} 
                    else {
                        $('#proposed_dispatch_date_c_date').css('border', '1px solid #a5e8d6');
                        return false;
                    }
                }
        }  
    }else if ($('#quote_type_c').val() == "quote_type_daikin") {
        if ($('#proposed_delivery_date_c').val() == '') {
            var question = confirm("Field Proposed Delivery Date is not filled - are you sure to continue?");
            if (question) {
            }
            else {
                return false;
            }
        } else {
            $('#proposed_delivery_date_c_date').css('border', '1px solid #a5e8d6');
        }
        if($('#proposed_install_date_c').val() == '') {
            var question = confirm("Field Proposed Install Date is not filled - are you sure to continue?");
            if (!question) {
                return false;
            }
        } else {
            $('#proposed_install_date_c_date').css('border', '1px solid #a5e8d6');
        }
    }
    /**Save before*/
    $('#save_and_edit').trigger('click');
    
    var record = encodeURIComponent($("input[name='record']").val());
     $.ajax({
             url: '/index.php?entryPoint=checkSwitchBoardAttached&record='+record,                
             success: function (data) {                    
                 console.log(data);                    
                 if(data){                        
                    //  var _form = document.getElementById('EditView');
                    var _form = document.getElementById('DetailView');
                    _form.action.value='converToInvoice';
                     _form.submit();
                 }else{
                     alert('Please add Switchboard photo to Attachment!'); 
                 }
             }            
         });            
 }
 $(document).ready(function(){
     //tu-code add field covert to invoices
     $('input[id="CANCEL"]').after(' <input title="Save" accesskey="a" class="button primary" type="button" name="button" value="Convert To Invoice" id="convert_to_invoice">');
     
     $("#convert_to_invoice").click(function(e) {
         e.preventDefault();
         var check = isSandenSupply();
         if ($('#quote_type_c').val() == "quote_type_sanden") {
            if( $('#proposed_dispatch_date_c').val() == "" && (check.SSO == true || check.SSI == true) ){
                $('#proposed_dispatch_date_c_date').focus();
                $('#proposed_dispatch_date_c_date').css('border', '1px solid #ff0000');
            }else if ( $('#proposed_install_date_c').val() == ''  && check.SSO == false && check.SSI == false ) {
                $('#proposed_install_date_c_date').focus();
                $('#proposed_install_date_c_date').css('border', '1px solid #ff0000');
            }
        }else if ($('#quote_type_c').val() == "quote_type_daikin") {
            if ($('#proposed_delivery_date_c').val() == '') {
                $('#proposed_delivery_date_c_date').focus();
                $('#proposed_delivery_date_c_date').css('border', '1px solid #ff0000');
            }
            if($('#proposed_install_date_c').val() == '') {
                $('#proposed_install_date_c_date').focus();
                $('#proposed_install_date_c_date').css('border', '1px solid #ff0000');
            }
        }
        setTimeout(function(){
            convertToInvoice();
        },100)
        
     })
     //tu-code Add the Suite LEAD number and hyperlink it 
     // if($("#billing_account_id").val() != ''){
     //    var account_id = $("#billing_account_id").val();
     //    $.ajax({
     //     url: '/index.php?entryPoint=ShowLeadNumberAndLink&account_id='+account_id,
     //     success: function (data){
     //         if(data != ''){
     //             var result = JSON.parse(data);
     //             var number = result['number'];
     //             var id     = result['id'];
     //             var href = "<div class='link_lead'><a target = '_blank' href='/index.php?module=Leads&action=EditView&record="+id+"'>Link Lead</a></div>"
     //             $("#lead_number_c").val(number);
     //             $('#lead_number_c').after(href);            
     //         }
     //     }  
     //    });
     // }
     //dung code -- display link lead in quote
     if($('#leads_aos_quotes_1leads_ida').val() != ''){
         $('#leads_aos_quotes_1leads_ida').parent().after("<a id='open_link_lead' target = '_blank' href='/index.php?module=Leads&action=EditView&record="+$('#leads_aos_quotes_1leads_ida').val()+"'>Link Lead</a>");
     }
     YAHOO.util.Event.addListener("leads_aos_quotes_1leads_ida", "change", function(){
 
         $('#open_link_lead').remove();
         if($('#leads_aos_quotes_1leads_ida').val() != ''){
             $('#leads_aos_quotes_1leads_ida').parent().after("<a id='open_link_lead' target = '_blank' href='/index.php?module=Leads&action=EditView&record="+$('#leads_aos_quotes_1leads_ida').val()+"'>Link Lead</a>");
         }
 
         $.ajax({
             type: "GET",
             cache: false,
             url: "?entryPoint=getLeadFromAccount&account_id="+$("#billing_account_id").val()+'&lead_id='+$("#leads_aos_quotes_1leads_ida").val(),
         }).done(function (data) {
            if(data == '' && typeof data == undefined)return;
             $("#send_solar_design").remove();
             $("#seekInstallDate").remove();
             $("#sendRequestClient").remove();
             var json = $.parseJSON(data);
             window.lead_source = json.lead_source; 
             //    invoice_to_email = json.email;
             //thien
             if(json.id != ''){
                 // $("#CANCEL").after(
                 //     '<button type="button" id="send_solar_design" class="button send_solar_design" title="send_solar_design" data-email-type="solar_design_complete"  data-module="Leads"' 
                 //     +' data-record-id="'+ json.id // Need changes
                 //     +'" data-module-name="' + json.first_name + ' ' + json.last_name // // Need changes
                 //     +'" data-email-address="' + json.email // Need changes
                 //     +'" data-sg-inverter-model="'+ $("#solargain_inverter_model_c").val() // Need changes -May be no!
                 //     +'" data-module-quote-id="'+ $("input[name='record']").val()
                 //     +'" >Send Solar Design <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                 // );
                 $("#save_and_email_pdf").after(
                     '&nbsp;<button  data-record-id="'+$('input[name="record"]').val()+'" data-lead-id="'+json.id+'" data-module-name="'+ json.first_name + ' ' + json.last_name +'" data-phone-number="'+json.phone_number+'" type="button" id="seekInstallDate" class="button seekInstallDate" title="Seek Install Date" data-module="Leads" onClick="SUGAR.seekInstallDate(this);" >Seek Install Date<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                 );
                 $("#CANCEL").after('&nbsp;<button type="button" id="sendRequestClient" class="button sendRequestClient" data-email-address="' + json.email +'" data-email-type="requestClientInfo" data-record-id="'+ $('input[name="record"]').val() +'" data-module="AOS_Quotes" title="Request Client Infomation" onclick="$(document).openComposeViewModalRequestClient(this);" >Request Client Infomation<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
             }else{
                 // $("#CANCEL").after(
                 //     '<button type="button" id="send_solar_design" class="button send_solar_design" title="send_solar_design" data-email-type="solar_design_complete" onclick="alert(\'Please Fill Lead source related.\');$(\'#leads_aos_quotes_1_name\').focus();javascript:void(0);"' 
                 //     +'" >Send Solar Design <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                 // );
                 $("#save_and_email_pdf").after(
                     '<button  data-record-id="" data-lead-id="" data-module-name="" data-phone-number="" type="button" id="seekInstallDate" class="button seekInstallDate" title="Seek Install Date" data-module="Leads" onClick="alert(\'Please Fill Lead source related.\');$(\'#leads_aos_quotes_1_name\').focus();javascript:void(0);" >Seek Install Date<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                 );
                 $("#CANCEL").after('&nbsp;<button type="button" id="sendRequestClient" class="button sendRequestClient" data-email-type="requestClientInfo" data-record-id="'+ $('input[name="record"]').val() +'" data-module="AOS_Quotes" title="Request Client Infomation" onClick="alert(\'Please Fill Lead source related.\');$(\'#leads_aos_quotes_1_name\').focus();javascript:void(0);" >Request Client Infomation<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
             }
             
         });
     });

    //VUT-S
        if($("#quote_type_c").val() == 'quote_type_solar'){
            $("#btn_view_change_log").after('<button type="button" style="margin:4px;" class="button" id="send_solar_pricing" class="button send_solar_pricing" onclick="$(document).openComposeViewModal_SendSolarPricing(this);" data-email-type="send_solar_pricing"  data-module="AOS_Quotes" data-module-name="'+$("#name").val()+'" data-record-id="'+$("input[name='record']").val()+'">Send Solar Pricing Options</button>')
        }
        $(document).on('click','#send_solar_pricing_option',function(){
            $('#send_solar_pricing').trigger('click');
        });

        /**Copy from CustomQuoteDetailView.js */ //onClick="$(document).openComposeViewModal_SendSolarPricing(this);"
        $.fn.openComposeViewModal_SendSolarPricing = function (source) {
            "use strict";
            var module_name = $(source).attr('data-module');
            var record_id= $(source).attr('data-record-id') ;
            var emailType = $(source).data('email-type');
            if(record_id == ''){
                alert('Please Save before !');
                return;
            }

            /**Save before*/
            $('#save_and_edit').trigger('click');

            var self = this;
    
            self.emailComposeView = null;
            var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
            var composeBox = $('<div></div>').appendTo(opts.contentSelector);
            composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
            composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
            composeBox.show();
            var record_id= $(source).attr('data-record-id') ;
            var email_type = $(source).attr('data-email-type');
            var  email_module  =  $(source).attr('data-module');
            var address = $('#install_address_c').val() + ' ' + $('#install_address_city_c').val() + ' ' + $('#install_address_state_c').val() +' '+ $('#install_address_postalcode_c').val()  ;
            if( $('#Vic_Rebate').prop('checked') == true ){
                var vic_rebate = "Yes";
            }else {
                var vic_rebate = "No";
            }
            if( $('#Loan_Rebate').prop('checked') == true ){
                var vic_loan = "Yes";
            }else {
                var vic_loan = "No";
            }
            if( $('#Double_Storey').prop('checked') == true ){
                var storey = "Double Storey";
            }else {
                var storey = "Single Storey";
            }
            var url_email = 'index.php?module=Emails&action=ComposeView&address='+ address +'&storey='+storey+'&vic_rebate='+vic_rebate+'&vic_loan='+vic_loan+'&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"") ;
                    
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
               
                //$(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
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
        
        //VUT-E

 });
 
 //Tri Truong - Only allow assigned user to send email
 window.onload = function () {
     var userUrl = $('.user-dropdown li:first-child a').attr('href'),
         userId  = userUrl.substr(userUrl.length - 36),
         assignedId = $('#assigned_user_id').val();
     $('body').on("click","#send_solar_design",function() {
         if($('#assigned_user_c').prop("checked" ) == false) {
             $(document).openComposeViewModal_sendSolarDesign(this);
         } else {
             if(userId == assignedId) {
                 $(document).openComposeViewModal_sendSolarDesign(this);
             } else {
                 alert('Only allow assigned user to send email');
                 return false;
             }
         }
     })
     // Dung code -- Alert Assigned User Lock
     if(module_sugar_grp1 == 'AOS_Quotes'){
         var confirm_Alert_Assigned_User_Lock = Alert_Assigned_User_Lock();
         if(confirm_Alert_Assigned_User_Lock != ''){   
             var dialog = $(confirm_Alert_Assigned_User_Lock).dialog({
                 buttons: {
                     "Yes": function() { 
                         dialog.dialog('close');
                     },
                     "Cancel":  function() {
                         dialog.dialog('close');
                         $(".buttons").hide();
                         $(".buttons").before("<button type='button' class='button' id='return_edit'>Return</button>");
                         return;
                     }
                 }
             });
         } 
     }
 }
     $(document).on('click','#return_edit',function(){
         window.location.href = "/index.php?module=AOS_Quotes&action=DetailView&record="+$("input[name='record']").val();
     });
 
 function Alert_Assigned_User_Lock(){
     var userName = $('input[name="current_user_name"]').val(),
         userId  = $('input[name="current_user_id"]').val(),
         assignedId = $('#assigned_user_id').val(),
         assignedName = $('#assigned_user_name').val(),
         confirm_content = '',
         checkbox_Assigned_User_Lock = $('#assigned_user_lockout_c').is(':checked'); 
      
     if(checkbox_Assigned_User_Lock && userId != assignedId){
         confirm_content = "<h4>Hi "+userName + ", This Quote is assigned to user " + assignedName + ".Do you want edit it?</h4>";
     }
     return confirm_content;
 };
 
 (function ($) {
     $.fn.openComposeViewModal_sendSolarDesign = function (source) {
         "use strict";
 
         var self = this;
         self.emailComposeView = null;
         var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
         var composeBox = $('<div></div>').appendTo(opts.contentSelector);
         composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
         composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
         composeBox.show();
         var record_id= $(source).attr('data-record-id') ;
         var email_type = $(source).attr('data-email-type');
         //thienpb code
         var sg_inverter_model = $(source).attr('data-sg-inverter-model');
         var quote_id = $("input[name='record']").val();
         $.ajax({
             type: "GET",
             cache: false,
             url: 'index.php?module=Emails&action=ComposeView&in_popup=1' + ((record_id!="")? ("&lead_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") 
                     + ((sg_inverter_model!='')?("&inverter_model="+sg_inverter_model):"")+ ((quote_id!='')?("&quote_id="+quote_id):""),
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
             var populateEmailAddress = $(source).attr('data-email-address');
             var populateModuleQuoteID = $(source).attr('data-module-quote-id');
 
             if (populateModuleName !== '') {
                 populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
             }
 
             $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
             $(self.emailComposeView).find('#parent_type').val(populateModule);
             $(self.emailComposeView).find('#parent_name').val(populateModuleName);
             $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
             $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
             $(self.emailComposeView).find('input[name="return_id"]').val(populateModuleRecord);
             $(self.emailComposeView).find('input[name="return_module"]').val(populateModule);
             $(self.emailComposeView).find('input[name="return_module"]').after("<input type='hidden' name = 'quote_parent_id' id = 'quote_parent_id' value='"+populateModuleQuoteID+"' >");
 
             // dung code - add checkbox Convert Solar Opportunity for popup "Send Solar Design"
             if(email_type == 'solar_design_complete'){
                 var html_checkbox_Convert_Solar_Opportunity = 
                 '<div class="col-xs-12 col-sm-12 edit-view-row-item">'
                 + '<div class="col-xs-12 col-sm-2 label" data-label="">'
                 + 'Convert Solar Opportunity:</div>'
                 + '<div class="col-xs-12 col-sm-8 edit-view-field " type="bool" field="send_sms" colspan="3">'
                 + ' <input type="hidden" name="send_sms" value="0"> '
                 + '<input type="checkbox" checked id="Convert_Solar_Opportunity" name="Convert_Solar_Opportunity" value="1" title="" tabindex="0">'                  
                 +'</div>'
                 +'</div>';
                 $(self.emailComposeView).find('#EditView_tabs .tab-content .edit-view-row').append(html_checkbox_Convert_Solar_Opportunity);
             }
             if(email_type == 'solar_design_complete'){
                 var number_quote_number = $('#solargain_quote_number_c').text().trim();
             }else{
                 var number_quote_number = $('#solargain_tesla_quote_number_c').text().trim();
             }
             if(typeof(number_quote_number) == 'undefined' || number_quote_number == ''
             || typeof(module_sugar_grp1) == 'undefined' || module_sugar_grp1 == ''
             || typeof(action_sugar_grp1) == 'undefined' || action_sugar_grp1 == ''){
             
             }else {
                 if(module_sugar_grp1 == 'Leads' && action_sugar_grp1 == 'DetailView'){
                     var href = "<a target='_blank' href='https://crm.solargain.com.au/quote/edit/" + number_quote_number +"'>Open Quote Solargain</a>";
                     $(self.emailComposeView).find('#parent_type').parent().parent().append(href);
                 }
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
 }(jQuery));
 
 $(document).ready(function(){
     $.ajax({
         type: "GET",
         cache: false,
         url: "?entryPoint=getLeadFromAccount&account_id="+$("#billing_account_id").val()+'&lead_id='+$("#leads_aos_quotes_1leads_ida").val(),
     }).done(function (data) {
        if(data == '' && typeof data == undefined)return;
         var json = $.parseJSON(data);
         window.lead_source = json.lead_source; 
         //    invoice_to_email = json.email;
         //thien
         if(json.id != ''){
             // $("#CANCEL").after(
             //     '&nbsp;<button type="button" id="send_solar_design" class="button send_solar_design" title="send_solar_design" data-email-type="solar_design_complete" data-module="Leads"' 
             //     +' data-record-id="'+ json.id // Need changes
             //     +'" data-module-name="' + json.first_name + ' ' + json.last_name // // Need changes
             //     +'" data-email-address="' + json.email // Need changes
             //     +'" data-sg-inverter-model="'+ $("#solargain_inverter_model_c").val() // Need changes -May be no!
             //     +'" data-module-quote-id="'+ $("input[name='record']").val()
             //     +'" >Send Solar Design <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
             // ) 
             $("#save_and_email_pdf").after(
                 '&nbsp;<button  data-record-id="'+$('input[name="record"]').val()+'" data-lead-id="'+json.id+'" data-module-name="'+ json.first_name + ' ' + json.last_name +'" data-phone-number="'+json.phone_number+'" type="button" id="seekInstallDate" class="button seekInstallDate" title="Seek Install Date" data-module="Leads" onClick="SUGAR.seekInstallDate(this);" >Seek Install Date<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
             );
             $("#CANCEL").after('&nbsp;<button type="button" id="sendRequestClient" class="button sendRequestClient" data-email-address="' + json.email +'" data-email-type="requestClientInfo" data-record-id="'+ record_id +'" data-module="AOS_Quotes" title="Request Client Infomation" onclick="$(document).openComposeViewModalRequestClient(this);" >Request Client Infomation<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
         }else{
             // $("#CANCEL").after(
             //     '&nbsp;<button type="button" id="send_solar_design" class="button send_solar_design" title="send_solar_design" data-email-type="solar_design_complete" onclick="alert(\'Please Fill Lead source related.\');$(\'#leads_aos_quotes_1_name\').focus();javascript:void(0);"' 
             //     +'" >Send Solar Design <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
             // )
             $("#save_and_email_pdf").after(
                 '&nbsp;<button  data-record-id="" data-lead-id="" data-module-name="" data-phone-number="" type="button" id="seekInstallDate" class="button seekInstallDate" title="Seek Install Date" data-module="Leads" onClick="alert(\'Please Fill Lead source related.\');$(\'#leads_aos_quotes_1_name\').focus();javascript:void(0);" >Seek Install Date<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
             )
             $("#CANCEL").after('&nbsp;<button type="button" id="sendRequestClient" class="button sendRequestClient" data-email-type="requestClientInfo" data-record-id="'+ record_id +'" data-module="AOS_Quotes" title="Request Client Infomation" onClick="alert(\'Please Fill Lead source related.\');$(\'#leads_aos_quotes_1_name\').focus();javascript:void(0);" >Request Client Infomation<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
         }
         
     });
 
     
 
         //dung code css address in quote
         // if(module_sugar_grp1 == 'AOS_Quotes') {
         //     setTimeout(function() {
         //         // $("#BILLING_address_fieldset").parent().parent().removeClass('col-sm-6');
         //         // $("#BILLING_address_fieldset").parent().parent().addClass('col-sm-4');
         //         // $("#SHIPPING_address_fieldset").parent().parent().removeClass('col-sm-6');
         //         // $("#SHIPPING_address_fieldset").parent().parent().addClass('col-sm-4');
         //         var group_address_install = '<div id="group_address_install" class="col-xs-12 edit-view-row-item col-sm-6">';
         //         group_address_install += '<fieldset> <legend> Install Address </legend></fieldset>';
         //         group_address_install += '</div>';
         //         $("#SHIPPING_address_fieldset").parent().parent().after(group_address_install);
         //         $("#install_address_c").parent().parent().parent().children().each(function(index,item){
         //             if(index >=4 && index <= 20){
         //                 //     group_address_install += this.innerHTML; 
         //                 //     item.remove();
         //                 $(this).removeClass('col-sm-6').detach().appendTo("#group_address_install");
         //             }
         //         });
                 
         //         $("div[data-label='LBL_INSTALL_ADDRESS']").removeClass('col-sm-2');
         //         $("div[data-label='LBL_INSTALL_ADDRESS']").addClass('col-sm-4');
         //         $("div[field='distance_to_travel_c']").after('<div class="clear"></div>');
         //         $("#group_address_install div.col-sm-8").css('margin-bottom','10px');
         //         $("div[data-label='LBL_DISTANCE_TO_TRAVEL'] button").css('margin-top', '5px');
         //         //$("div[data-label='LBL_PLUMBER_ELECTRICIAN']").parent().removeClass('col-sm-6').detach().appendTo("#group_address_install");                
 
         //     }, 3000)
         // }
 
         //check box sync address in quote from billing address to site detail address
         if(module_sugar_grp1 == 'AOS_Quotes') { 
             $("#install_address_c").parent().parent().before('<div class="col-xs-12 edit-view-field"> <label>Copy address from billing address:</label><input id="check_addr_site_detail_c" name="check_addr_site_detail_c"  type="checkbox"></div>');
         }
         function syncFieldsBillingQuotes(check){
             if(check){
                 $("#install_address_c").val($("#billing_address_street").val());    
                 $("#install_address_city_c").val($("#billing_address_city").val());         
                 $("#install_address_state_c").val($("#billing_address_state").val());
                 $("#install_address_postalcode_c").val($("#billing_address_postalcode").val());
                 $("#install_address_country_c").val($("#billing_address_country").val());
             }
         }
         $("body").on("click", "#check_addr_site_detail_c", function(){
             
             syncFieldsBillingQuotes($(this).is(":checked"));
         });
 
     //tu-code get date today
     function addZero(i){
         if (i < 10) {
           i = "0" + i;
         }
         return i; 
       }
     var today   = new Date();
     var month   = addZero(today.getMonth() + 1); 
     var day     = addZero(today.getDate());
     var year    = today.getFullYear();
     var hours   = addZero(today.getHours());
     var minutes = today.getMinutes();
     newdate     = day + "/" + month + "/" + year;   
     var dialogBody = '<button style="padding: 0px 10px;margin: 0px 1px;" type="button" id="get_today" class="button" title="Get Today" >T</button>';
     $('input[name="quote_date_c"]').after(dialogBody);
    //  $('div[field="quote_date_c"]').find('tr[valign="middle"]').append('<td>'+dialogBody+'</td>')
     $("#get_today").click(function(){
         if(minutes<15){
             minutes = $("#quote_date_c_minutes option:eq(1)").val();
        }else if(minutes>=15 && minutes < 30){
             minutes = $("#quote_date_c_minutes option:eq(2)").val();
        }else if(minutes>=30 && minutes < 45){
             minutes = $("#quote_date_c_minutes option:eq(3)").val();
        }else{
             minutes = $("#quote_date_c_minutes option:eq(4)").val();      
        }
        $("#quote_date_c_minutes").val(minutes);
        $("#quote_date_c_date").val(newdate);
        $("#quote_date_c_hours").val(hours);
        $("#quote_date_c").val(newdate+"  "+hours+":"+minutes);
        $("#quote_date_c_date").val(newdate);
        $("#quote_date_c_hours").val(hours);
        $("#next_action_date_c").val(getDateTime('7'));
        $("#expiration").val(getDateTime('7'));
     });
 
     //Thienpb code -- copy all function with solar from Lead to Quote
 
     //ADD BUTTON PUSH AND UPDATE INFO TO SOLAR
     if($("#solargain_quote_number_c").val() == "" && $("#solargain_tesla_quote_number_c").val() == ''){
         $("#save_and_email_pdf").after(
         '&nbsp;<button type="button" id="createsolargainLead" class="button createsolargainLead" title="" onClick="SUGAR.addAllEventPushSGButton(this);" > Push To SG <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
         )
     }else{ 
        if($("#quote_type_c").val() == 'quote_type_solar'){
            $("#save_and_email_pdf").after(
                '&nbsp;<button type="button" id="updateQuoteToSolargain" class="button updateQuoteToSolargain" title="Update SG Quote Info" onClick="SUGAR.addAllEventUpdateSGButton(this);" > Update SG Quote Info<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                )
        }else if($("#quote_type_c").val() == 'quote_type_tesla'){ 
            $("#save_and_email_pdf").after(
                '&nbsp;<button type="button" id="updateQuoteToSolargain" class="button updateQuoteToSolargain" title="Update Tesla Quote Info" onClick="SUGAR.addAllEventUpdateSGButton(this);" > Update Tesla Quote Info<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                )
        }        
        //  $("#save_and_email_pdf").after(
        //  '&nbsp;<button type="button" id="updateQuoteToSolargain" class="button updateQuoteToSolargain" title="Update SG Quote Info" onClick="SUGAR.addAllEventUpdateSGButton(this);" > Update SG Quote Info<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        //  )
     }
     //TriTruong Add Button Get Today, Get 7 Days
     $('div[field="expiration"]').append('<button style="padding: 0px 10px;margin: 0px 1px;" type="button" id="get_today_valid_until" class="button button-get-day" title="Get Today" data-type="today" >T</button>');
     $('#get_today_valid_until').before(
         '&nbsp;<button style="padding: 0px 5px;margin: 0px 1px;" type="button" id="get_seven_days" class="button-get-day button get-seven-days" title="" data-type="7">T+7</button>'
     );
     $('.button-get-day').click(function() {
         var type  = $(this).attr('data-type');
         $("#expiration").val(getDateTime(type));
     });
 
     var defaultDateTime = function(date){
         var now     = date;
         var year    = now.getFullYear();
         var month   = now.getMonth()+1; 
         var day     = now.getDate();
         return {'day':day,'month':month,'year':year,}
     }
     //TriTruong Function Get Day
     var getDateTime = function(type){
         var date_return = '';
         var date = new Date();
         switch(type){
             case 'today':
                 var data = defaultDateTime(new Date());
                 if(data['day'] < 10) {
                     data['day'] = '0'+data['day'];
                 }
                 if(data['month'] < 10) {
                     data['month'] = '0' + data['month'];
                 }
                 date_return = data['day']+'/'+data['month']+'/'+data['year']; 
                 break;
             case '7':
                 var data = defaultDateTime(new Date(date.getTime() + 7*(24*60*60*1000)));
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
     //End Task
     // tuan code
     $('#solargain_inverter_model_c').parent().parent().hide();
     // if( $('#solargain_tesla_quote_number_c').val() == "" ){
     //     $('#solargain_tesla_quote_number_c').hide();
     //     $('div[data-label="LBL_SOLARGAIN_TESLA_QUOTE_NUMBER"]').hide()
     // }else {
     //     $('#solargain_tesla_quote_number_c').show();
     //     $('div[data-label="LBL_SOLARGAIN_TESLA_QUOTE_NUMBER"]').show()
     // }
     $('div[field="next_action_date_c"]').append('<button style="padding: 0px 5px;margin: 0px 1px;" type="button" id="get_today_action_date" class="button" title="Get Today" data-type="7" >T+7</button>');
     $('#get_today_action_date').after(
         '&nbsp;<button type="button" id="push_action_date_toSG" class="button push_action_date_toSG" title="" onClick="SUGAR.pushactiondatetoSG_quote(this);" > PUSH TO SG <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
     )  
     $("#get_today_action_date").click(function(){
        var type  = $(this).attr('data-type');
        $("#next_action_date_c").val(getDateTime(type));
     });
     SUGAR.pushactiondatetoSG_quote = function (elem) {
         if($("#next_action_date_c").val() == '') {
             alert('Not value Next Action Date!');
             return false;
         }
         $('#push_action_date_toSG span.glyphicon-refresh').removeClass('hidden');
         var build_url_quote = "?entryPoint=customPushNextActionDate";
         build_url_quote += '&record='+ encodeURIComponent($('input[name="record"]').val());
         build_url_quote += "&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
         build_url_quote += "&nextactiondate="+encodeURIComponent($("#next_action_date_c").val());
         $.ajax({
             url: build_url_quote,
             type : 'POST',
             success: function (data) {
                 alert ('Success Push!');
                 $('#push_action_date_toSG span.glyphicon-refresh').addClass('hidden');
             },
         });
         return false;
     }
     $('div[field="proposed_install_date_c"]').append(
         '&nbsp;<button type="button" id="proposedInstallDate" class="button proposed-Install-Date" title="" onClick="SUGAR.proposedInstallDate(this);" > Update To SG  <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
     )
     $('div[field="sg_assigned_user_c"]').append(
         '&nbsp;<button type="button" id="sg_assigned_user" class="button sg-assigned-user" title="" onClick="SUGAR.sgAssignedUserQuote(this);" > Get Assigned User  <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
     )
     if($("#sg_assigned_user_c").val() != ""){
        addButton_owner();
     } 
         SUGAR.proposedInstallDate = function (elem) {
             // var seek_install_date_c = $('#proposed_install_date_c').val();
             // if(seek_install_date_c == ''){
             //     alert('Please fill Proposed Install Date !');
             //     $("#proposed_install_date_c_date").focus();
             //     return;
             // }
             $('#proposedInstallDate span.glyphicon-refresh').removeClass('hidden');
             var quote_number= $('#solargain_quote_number_c').val() ;
 
             var build_url_quote = "?entryPoint=customProposedInstallDate";
             build_url_quote += "&quoteSG_ID="+quote_number;
             build_url_quote += "&proposed_date="+ $("#proposed_install_date_c").val();
             $.ajax({
                 url: build_url_quote,
                 type : 'POST',
                 success: function (data) {
                     alert ('Success Update!')
                 },
             });
             $("#EditView input[name='action']").val('Save');
             $.ajax({
                 type: $("#EditView").attr('method'),
                 url: $("#EditView").attr('action'),
                 data: $("#EditView").serialize(),
                 success: function (data) {
                     $('#proposedInstallDate span.glyphicon-refresh').addClass('hidden');  
                 }
             });
         }
         SUGAR.sgAssignedUserQuote = function (elem) {
             $('#sg_assigned_user span.glyphicon-refresh').removeClass('hidden');
             $('#change_Owner').remove();
             $('#sg_assigned_user_c').val("");
                 if($('#solargain_quote_number_c').val() !== ''){
                     var url_assigned_user = "?entryPoint=customGetSGAssignedUser&quote_solorgain=" + $('#solargain_quote_number_c').val();
                     $.ajax({
                         url: url_assigned_user,
                         async: false,
                         type: 'GET',
                         success: function(data){
                            if(data == '' && typeof data == undefined){$('#sg_assigned_user span.glyphicon-refresh').addClass('hidden');return;}
                             if( data == "Matthew Wright"){
                                 $('#sg_assigned_user_c').val(data);
                                 $('#user_id2_c').val('8d159972-b7ea-8cf9-c9d2-56958d05485e') ; 
                                 setTimeout(function(){
                                 $('#sg_assigned_user span.glyphicon-refresh').addClass('hidden')},1000);
                             }else if( data == "Paul Szuster"){
                                 $('#sg_assigned_user_c').val(data);
                                 $('#user_id2_c').val('61e04d4b-86ef-00f2-c669-579eb1bb58fa') ;   
                                 setTimeout(function(){
                                 $('#sg_assigned_user span.glyphicon-refresh').addClass('hidden')},1000);
                             }    
                         }
                     })
                 }else{
                     alert('Not have number quote solargain!');
                     $('#sg_assigned_user span.glyphicon-refresh').addClass('hidden');
                 }
                 if($("#sg_assigned_user_c").val() != ""){
                     $('div[field="sg_assigned_user_c"]').append(
                         '<button type="button" id="change_Owner" class="button sg-change-owner-user" title="" onClick="SUGAR.Sg_ChangeOwnerAssignedQuote(this);" > Change Owner <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                     )  
                 }  
         }
     SUGAR.Sg_ChangeOwnerAssignedQuote = function (elem) {
         $('#change_Owner span.glyphicon-refresh').removeClass('hidden');
         if( $("#sg_assigned_user_c").val() != 'Matthew Wright' && $("#sg_assigned_user_c").val() != 'Paul Szuster'){
             $('#change_Owner span.glyphicon-refresh').addClass('hidden');
             alert ('Can not change.');
             return;
         }
         if($('#solargain_quote_number_c').val() !== ''){
             var url_assigned_user = "?entryPoint=customGetSGAssignedUser&quote_solorgain=" + $('#solargain_quote_number_c').val();
             $.ajax({
                 url: url_assigned_user,
                 async: false,
                 type: 'GET',
                 success: function(data){
                     if( $('#sg_assigned_user_c').val() == data ){
                         alert ("The current owner is "+$('#sg_assigned_user_c').val());
                         $('#change_Owner span.glyphicon-refresh').addClass('hidden'); 
                         return false;
                     }else {
                         var url_change_assigned_user = "?entryPoint=customChangeQuotedByUser";
                         url_change_assigned_user += '&quote_solorgain=' + $('#solargain_quote_number_c').val();
                         url_change_assigned_user += '&assigned_name='+ $("#sg_assigned_user_c").val() ;
                         $.ajax({
                             url: url_change_assigned_user,
                             async: false,
                             type: 'GET',
                             success: function(data){
                                 $('#change_Owner span.glyphicon-refresh').addClass('hidden'); 
                                 alert ('Success change!')             
                             }
                         })  
                     } 
                 }
             })
         }
     }
 
     //FUNCTION EVENT LISTENER FOR LOGIC PUSH SG
     SUGAR.addAllEventPushSGButton = function(elem){
         if($("#quote_type_c").val() == 'quote_type_solar'){
             SUGAR.createsolargainLead(elem);
         }else if($("#quote_type_c").val() == 'quote_type_tesla'){ 
             SUGAR.quoteCreateSGTeslaLead(elem);
         }else{
             alert("Product type isn't a solar or tesla.");
             $("#quote_type_c").focus();
         }
     }
     //END
 
     //FUNCTION EVENT LISTENER FOR LOGIC PUSH SG
     SUGAR.addAllEventUpdateSGButton = function(elem){
         if($("#quote_type_c").val() == 'quote_type_solar'){
             SUGAR.updateQuoteToSolargain(elem);
         }else if($("#quote_type_c").val() == 'quote_type_tesla'){ 
             SUGAR.updateTeslaQuoteToSolargain(elem);
         }else{
             alert("Product type isn't a solar or tesla.");
             $("#quote_type_c").focus();
         }
     }
     //END
 
     //FUNCTION CREATE LEAD
     SUGAR.createsolargainLead = function (elem) {
         if($("input[name='record']").val() == ''){
             alert('Please save this Lead before push to SG!');
             return;
         }
 
         if($("#solargain_lead_number_c").val() !== ""){
             SUGAR.createsolargainQuote(elem);
             return;
         }
 
         state_ = $("#install_address_state_c").val();
         
         if(state_ == ''){
             alert('Please fill up State for Auto populate pricing section');
             $("#install_address_state_c").focus();
             $('#updateTeslaQuoteSG span.glyphicon-refresh').addClass('hidden');
             return;
         }
 
         //dung code - new logic - auto save before push SolarGain
         $("#EditView input[name='action']").val('Save');
         $.ajax({
             type: $("#EditView").attr('method'),
             url: $("#EditView").attr('action'),
             data: $("#EditView").serialize(),
             async: false, 
             success: function () {
                 console.log('Good');
             }
         });
 
         $('#createsolargainLead span.glyphicon-refresh').removeClass('hidden');
 
         var build_url=  "?entryPoint=quoteCreateSGQuote";
         build_url += '&process=lead';
         build_url += '&notes='+ encodeURIComponent($("#description").val()) ;
         build_url += '&record='+ encodeURIComponent($('input[name="record"]').val());
         build_url += '&system_size='+ encodeURIComponent($("#system_size_c").val()) ;
         build_url += '&unit_per_day='+ encodeURIComponent($("#units_per_day_c").val()) ;
         build_url += '&dolar_month='+ encodeURIComponent($("#dolar_month_c").val()) ;
         build_url += '&number_of_people='+ encodeURIComponent($("#number_of_people_c").val());
 
         var customer_type = $('input[name=customer_type_c]:checked').val();
         build_url += '&customer_type='+ encodeURIComponent(customer_type) ;
 
         build_url += '&billing_address_street='+ encodeURIComponent($("#install_address_c").val());
         build_url += '&billing_address_city='+ encodeURIComponent($("#install_address_city_c").val());
         build_url += '&state='+ encodeURIComponent($("#install_address_state_c").val());
         build_url += '&postalcode='+ encodeURIComponent($("#install_address_postalcode_c").val());
         build_url += '&build_height='+ encodeURIComponent($("#gutter_height_c").val());
 
         var connection_type = $("#connection_type_c").val();
         if (connection_type == "Semi_Rural_Remote_Meter")  connection_type =  "Semi Rural/Remote Meter";
 
         build_url += '&connection_type='+ encodeURIComponent($("#connection_type_c").val());
         build_url += '&main_type='+ encodeURIComponent($("#main_type_c").val());
         build_url += '&meter_number='+ encodeURIComponent($("#meter_number_c").val());
         build_url += '&nmi_number='+ encodeURIComponent($("#nmi_c").val());
         build_url += '&account_number='+ encodeURIComponent($("#account_number_c").val());
         build_url += '&billing_name='+ encodeURIComponent($("#name_on_billing_account_c").val());
         build_url += '&energy_retailer='+ encodeURIComponent($("#energy_retailer_c").val());
         build_url += '&distributor='+ encodeURIComponent($("#distributor_c").val());
         var roof_type = $("#roof_type").val();
         var roof_type_arr ={"TIN/COLORBOND":2,
                             "CONCRETE TILE":3,
                             "KLIPLOC"      :4,
                             "SLATE ROOF"   :1,
                             "ASBESTOS ROOF":8,
                             "TERRACOTTA"   :10,
                             "UNSURE"       :1};
         build_url += '&roof_type='+ encodeURIComponent(roof_type_arr[roof_type]);
         $.ajax({
             url: build_url,
             type : 'POST',
             success: function (data) {
                 console.log(data);
                 leadID = data;
 
                 $("#solargain_lead_number_c").val(data);
                 $("#solargain_lead_number_c").trigger("change");
                 SUGAR.createsolargainQuote(elem);
                 $("#designer_c").val($('input[name="current_user_name"]').val());
                 $("#user_id_c").val($('input[name="current_user_id"]').val());
             },
         });
     }
     //END
 
     //FUNCTION CREATE QUOTE
     SUGAR.createsolargainQuote = function (elem) {
 
         state_ = $("#install_address_state_c").val();
         
         if(state_ == ''){
             alert('Please fill up State for Auto populate pricing section');
             $("#install_address_state_c").focus();
             $('#updateTeslaQuoteSG span.glyphicon-refresh').addClass('hidden');
             return;
         }
 
         $('#createsolargainLead span.glyphicon-refresh').removeClass('hidden');
         $("#calculatePrice").trigger('click');
         SGleadID = $("#solargain_lead_number_c").val();
         if (SGleadID == 0 ) return;
         var build_url=  "?entryPoint=quoteCreateSGQuote";
         build_url += '&SGleadID='+ encodeURIComponent(SGleadID);
 
         var k =0;
         $("input[id*='base_price_']").each(function(i, e) {
             if ($(e).val() != "") k++;
           });
         build_url += '&sgoption='+ k;
 
         build_url += '&process=quote';
 
         build_url += '&record='+ encodeURIComponent($('input[name="record"]').val())
                     + "&billing_address_street=" + $("#install_address_c").val()
                     + "&billing_address_city=" + $("#install_address_city_c").val()
                     + "&billing_address_state=" + $("#install_address_state_c").val()
                     + "&billing_address_postalcode=" + $("#install_address_postalcode_c").val();
         build_url += "&specialNotes="+encodeURIComponent($("#special_notes_c").val());
 
         //Travel option and Price option
         var count_option = 0;
         var option_models =    {
                                    'Jinko 330W Mono PERC HC':'149',
                                    // 'Jinko 370W Cheetah Plus JKM370M-66H' : '171',
                                    //'Longi Hi-MO X 350W':'162',
                                    // 'Q CELLS Q.MAXX 330W':'156',
                                    'Q CELLS Q.MAXX-G2 350W':'185',
                                    // 'Q CELLS Q.PEAK DUO G6+ 350W':'173',
                                    // 'Sunpower Maxeon 2 350':'144',
                                    // 'Sunpower Maxeon 3 395':'167',
                                    // 'Sunpower X22 360W':'110',
                                    'Sunpower Maxeon 3 400W':'145',
                                    // 'Sunpower P3 325 BLACK':'174',
                                    'Sunpower P3 370 BLACK':'193',                               
                                 }
          var option_inverters = {'Primo 3':'274',
                                'Primo 4':'275',
                                'Primo 5':'269',
                                'Primo 6':'277',
                                'Primo 8.2':'278',
                                'Symo 5':'273',
                                'Symo 6':'282',
                                'Symo 8.2':'284',
                                'Symo 10':'285',
                                'Symo 15':'287',
                                'SYMO 20':'289',
                                'S Edge 3G':'292',
                                'S Edge 5G':'292',
                                'S Edge 6G':'292',
                                'S Edge 8G':'292',
                                'S Edge 8 3P':'292',
                                'S Edge 10G':'292',
                                'IQ7 plus':'201',
                                //'IQ7':'200',
                                'IQ7X':'229',
                                'SolarEdge with P500':'168',
                                'SolarEdge with P401':'292',
                                'SolarEdge with P370':'203',
                                //'Growatt 3':'233',
                                // 'Growatt 5':'213',
                                // 'Growatt 6':'230',
                                // 'Growatt 8.2':'247',
                                'Sungrow 3':'223',
                                'Sungrow 5':'259',
                                'Sungrow 8':'257',
                                'Sungrow 10 3P':'226',
                                'Sungrow 15 3P':'241',
                             };
         var option_extras = {  'Fro. Smart Meter (1P)':'1',
                                'Fro. Smart Meter (3P)':'2',
                                'Fronius Service Partner Plus 10YR Warranty':'387',
                                'Switchboard UPG':'',
                                'ENPHS Envoy-S Met.':'13',
                                'SE Smart Meter':'22',
                                'SE Wifi': '17',
                                'Sungrow Smart Meter (1P)': '413',
                                // 'Sungrow Smart Meter (3P)': '414'
                                'Sungrow Three Phase Smart Meter DTSU666' : '524'
                            };
             //thienpb code 
             var option_battery = {  "LG Chem RESU 10H SolarEdge & Fronius":'40',};
 
         for(var i = 1; i<=6 ;i++){
             var travel_km    = $("#travel_km_"+i).val().trim();
             var price_option = $("#customer_price_"+i).val().trim();
             if(price_option != ''){
                 price_option = price_option;
             }else{
                 price_option = $("#suggest_price_"+i).val().trim();
             }
             var number_double_storey_panel = $("#number_double_storey_panel_"+i).val();
             var groups_of_panels  = $("#groups_of_panels_"+i).val().trim();
             var option_battery_type = $("#battery_"+i).val();
 
 
             var option_panel_type = $("#panel_type_"+i).val().trim();
             var option_inverter_type = $("#inverter_type_"+i).val().trim();
             var option_total_panels = $("#total_panels_"+i).val().trim();
             //tuan code
             var additional;
             var groups ;
             // var option_extra_1 = option_extras($("#extra_1_"+i).val().trim());
             // var option_extra_2 = option_extras($("#extra_2_"+i).val().trim());
             // var option_extra_3 = option_extras($("#extra_3_"+i).val().trim());
 
             if($("#base_price_"+i).val().trim() != ''){
                 if(travel_km == ''){
                     travel_km = 0;
                 }else if(parseInt(travel_km) > 50) {
                     travel_km = travel_km - 50;
                 }else {
                     travel_km = 0;
                 }
                 debugger;
                 if(number_double_storey_panel == "" ){
                     additional = 0 ;
                 }else if(parseInt(number_double_storey_panel) > 0 ){
                     additional = 1;
                 }else {
                     additional = 0;
                 }
                 if(parseInt(groups_of_panels) > 2){
                     groups = parseInt(groups_of_panels) - 2;
                 }else{
                     groups = 0;
                 }
                 build_url +=  '&splits_'+ count_option + '='+ encodeURIComponent(parseInt(groups));
                 build_url += '&additional_'+ count_option + '='+additional;
                 //if( (option_panel_type == 'Sunpower Maxeon 2 350' && option_panel_type == 'Sunpower P3 325 BLACK') && (option_inverter_type == 'S Edge 3' || option_inverter_type == 'S Edge 5'|| option_inverter_type == 'S Edge 6' || option_inverter_type == 'S Edge 8' || option_inverter_type == 'S Edge 8 3P' || option_inverter_type == 'S Edge 10')){
                 if(option_panel_type == 'Sunpower Maxeon 3 400W' && (option_inverter_type == 'S Edge 3G' || option_inverter_type == 'S Edge 5G' || option_inverter_type == 'S Edge 6G' || option_inverter_type == 'S Edge 8G' || option_inverter_type == 'S Edge 8 3P' || option_inverter_type == 'S Edge 10G') ){
                     build_url += '&option_inverter_'+ count_option +'='+ encodeURIComponent(option_inverters['SolarEdge with P500']);
                 }else{
                     build_url += '&option_inverter_'+ count_option +'='+ encodeURIComponent(option_inverters[option_inverter_type]);
                 }
 
                 build_url += '&option_model_'+ count_option +'='+ encodeURIComponent(option_models[option_panel_type])
                 build_url += '&option_total_panel_'+ count_option +'='+ encodeURIComponent(option_total_panels);
                 build_url += '&price_option_'+ count_option +'='+ encodeURIComponent(price_option);
                 build_url += '&travel_km_'+ count_option +'='+ encodeURIComponent(parseInt(travel_km));
                 build_url += '&number_double_storey_panel_'+ count_option +'='+ encodeURIComponent(parseInt(number_double_storey_panel));
                 //build_url += '&groups_of_panels_'+ count_option +'='+ encodeURIComponent(parseInt(groups_of_panels));
 
                 // build_url += '&option_extra_1_'+count_option +'='+ encodeURIComponent(option_extra_1);
                 // build_url += '&option_extra_2_'+count_option +'='+ encodeURIComponent(option_extra_2);
                 // build_url += '&option_extra_3_'+count_option +'='+ encodeURIComponent(option_extra_3);
                 build_url += '&option_inverter_type_name_'+ count_option +'='+ encodeURIComponent(option_inverter_type);
                 var tilting_frame = $("#tilting_"+i).val().trim();
                 if(tilting_frame != ''){
                     build_url += '&option_tilting_'+ count_option +'='+parseInt(tilting_frame);
                 }else{
                     build_url += '&option_tilting_'+ count_option +'=0';
                 }
                 //thienpb code for battery
                 build_url += '&option_battery_'+ count_option +'='+ ((option_battery_type != '') ? encodeURIComponent(option_battery[option_battery_type]) : 0);
 
                 count_option++;
             }
         }

         if($('#Vic_Rebate').prop('checked') == true){
            build_url += "&vicRebate=yes";
        }else{
            build_url += "&vicRebate=no";
        }

        if( $('#Loan_Rebate').prop('checked') == true ){
            build_url += "&loanRebate=yes";
        }else {
            build_url += "&loanRebate=no";
        }
        SUGAR.ajaxUI.showLoadingPanel();
         $.ajax({
             url: build_url,
             type : 'POST',
             async : false,
             success: function (data) {
                if(data == '' && typeof data == undefined){$('#createsolargainLead span.glyphicon-refresh').addClass('hidden');return;}
                 var jsonData = $.parseJSON(data);
                 $('#createsolargainLead span.glyphicon-refresh').addClass('hidden');
                 $("#solargain_quote_number_c").val(jsonData.QuoteNumber);
                 $("#sg_site_details_no_c").val(jsonData.SiteDetailNumber);
                 $("#solargain_quote_number_c").trigger("change");
                 SUGAR.ajaxUI.hideLoadingPanel();
                 alert("Push to SG successfully.")
             },
         });
     }
     //end
 
     //FUNCTION UPDATE INFO TO SOLAR
     SUGAR.updateQuoteToSolargain = function(elem) {
 
         $('#updateQuoteToSolargain span.glyphicon-refresh').removeClass('hidden');
 
         state_ = $("#install_address_state_c").val();
         
         if(state_ == ''){
             alert('Please fill up State for Auto populate pricing section');
             $("#install_address_state_c").focus();
             $('#updateTeslaQuoteSG span.glyphicon-refresh').addClass('hidden');
             return;
         }
 
         if($("#solargain_lead_number_c").val() == ''){
             alert('Please fill up SG Lead');
             $("#solargain_lead_number_c").focus();
             $('#updateTeslaQuoteSG span.glyphicon-refresh').addClass('hidden');
             return;
         }
         
         var build_url_quote = "?entryPoint=updateQuoteToSGQuote";
         build_url_quote += '&acction_type=updateQuoteToSGQuote';
         build_url_quote += '&record='+ encodeURIComponent($('input[name="record"]').val());
         build_url_quote += "&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
         build_url_quote += "&leadSG_ID="+encodeURIComponent($("#solargain_lead_number_c").val());
         build_url_quote += "&meter_number="+encodeURIComponent($("#meter_number_c").val());
         build_url_quote += "&meter_phase="+encodeURIComponent((($("#phases").val() == 'Three Phases' && $("#phases").val() != 'Unsure') ? 3 : (($("#phases").val() == 'Single Phase') ? 1 : (($("#phases").val() != 'Unsure') ? 2 : ''))));
         build_url_quote += '&account_number='+ encodeURIComponent($("#account_number_c").val());
         build_url_quote += "&nmi_number="+encodeURIComponent($("#nmi_c").val());
         build_url_quote += "&name_on_billing_account="+encodeURIComponent($("#name_on_billing_account_c").val());
         build_url_quote += '&billing_name='+ encodeURIComponent($("#name_on_billing_account_c").val());
         build_url_quote += "&energy_retailer="+encodeURIComponent($("#energy_retailer_c").val());
         build_url_quote += "&distributor="+encodeURIComponent($("#distributor_c").val());
         build_url_quote += '&billing_address_street='+ encodeURIComponent($("#install_address_c").val());
         build_url_quote += '&billing_address_city='+ encodeURIComponent($("#install_address_city_c").val());
         build_url_quote += '&state='+ encodeURIComponent($("#install_address_state_c").val());
         build_url_quote += '&postalcode='+ encodeURIComponent($("#install_address_postalcode_c").val());
         build_url_quote += '&address_nmi='+ encodeURIComponent($("#address_nmi_c").val());
         var customer_type = $('input[name=customer_type_c]:checked').val();
         build_url_quote += '&customer_type='+ encodeURIComponent(customer_type) ;
 
         var roof_type = $("#roof_type").val();
         var roof_type_arr ={"TIN/COLORBOND":2,
                             "CONCRETE TILE":3,
                             "KLIPLOC"      :4,
                             "SLATE ROOF"   :0,
                             "ASBESTOS ROOF":8,
                             "TERRACOTTA"   :10,
                             "UNSURE"       :1};
         build_url_quote += '&roof_type='+ encodeURIComponent(roof_type_arr[roof_type]);
         build_url_quote += '&build_height='+ encodeURIComponent($("#gutter_height_c").val());
         build_url_quote += '&connection_type='+ encodeURIComponent($("#connection_type_c").val());
         build_url_quote += '&main_type='+ encodeURIComponent($("#main_type_c").val());
         for(var i = 1; i<=6 ;i++){
             var travel_km    = $("#travel_km_"+i).val().trim();
             var number_double_storey_panel = $("#number_double_storey_panel_"+i).val().trim();
             var groups_of_panels  = $("#groups_of_panels_"+i).val().trim();
 
             if(travel_km == ''){
                 travel_km = 0;
             }else if(parseInt(travel_km) > 50) {
                 travel_km = parseInt(travel_km) - 50;
             } else{
                 travel_km = 0;
             }
             if(number_double_storey_panel == ''){
                 number_double_storey_panel = 0;
             }
             if(parseInt(groups_of_panels) >= 2){
                 groups_of_panels = groups_of_panels - 2;
             }else{
                 groups_of_panels = 0;
             }
             
             build_url_quote += '&travel_km_'+ i +'='+ encodeURIComponent(parseInt(travel_km));
             build_url_quote += '&number_double_storey_panel_'+ i +'='+ encodeURIComponent(parseInt(number_double_storey_panel));
             build_url_quote += '&groups_of_panels_'+ i +'='+ encodeURIComponent(parseInt(groups_of_panels));
 
         }
 
         $.ajax({
             url: build_url_quote,
             type : 'POST',
             success: function (data) {
                 $('#updateQuoteToSolargain span.glyphicon-refresh').addClass('hidden');
             },
         });
     }
     //end
 
     //FUNCTION CREATE SG TESLA LEAD
     SUGAR.quoteCreateSGTeslaLead = function (element){
 
         //auto save before push SolarGain
         $("#EditView input[name='action']").val('Save');
         $.ajax({
             type: $("#EditView").attr('method'),
             url: $("#EditView").attr('action'),
             data: $("#EditView").serialize(),
             async: false, 
             success: function () {
             }
         });
 
         state_ = $("#install_address_state_c").val();
         
         if(state_ == ''){
             alert('Please fill up State for Auto populate pricing section');
             $("#install_address_state_c").focus();
             $('#updateTeslaQuoteSG span.glyphicon-refresh').addClass('hidden');
             return;
         }
 
         if($("#solargain_lead_number_c").val() !== ""){
             SUGAR.quoteCreateSGTeslaQuote(element);
             return;
         }
         var build_url=  "?entryPoint=quoteCreateSGQuote";
         build_url += '&process=lead';
         build_url += '&notes='+ encodeURIComponent($("#description").val()) ;
         build_url += '&record='+ encodeURIComponent($('input[name="record"]').val());
         build_url += '&system_size='+ encodeURIComponent($("#system_size_c").val()) ;
         build_url += '&unit_per_day='+ encodeURIComponent($("#units_per_day_c").val()) ;
         build_url += '&dolar_month='+ encodeURIComponent($("#dolar_month_c").val()) ;
         build_url += '&number_of_people='+ encodeURIComponent($("#number_of_people_c").val());
 
         var customer_type = $('input[name=customer_type_c]:checked').val();
         build_url += '&customer_type='+ encodeURIComponent(customer_type) ;
         build_url += '&billing_address_street='+ encodeURIComponent($("#install_address_c").val());
         build_url += '&billing_address_city='+ encodeURIComponent($("#install_address_city_c").val());
         build_url += '&state='+ encodeURIComponent($("#install_address_state_c").val());
         build_url += '&postalcode='+ encodeURIComponent($("#install_address_postalcode_c").val());
         build_url += '&build_height='+ encodeURIComponent($("#gutter_height_c").val());
         var connection_type = $("#connection_type_c").val();
         if (connection_type == "Semi_Rural_Remote_Meter")  connection_type =  "Semi Rural/Remote Meter";
         build_url += '&connection_type='+ encodeURIComponent($("#connection_type_c").val());
         build_url += '&main_type='+ encodeURIComponent($("#main_type_c").val());
         build_url += '&meter_number='+ encodeURIComponent($("#meter_number_c").val());
         build_url += '&nmi_number='+ encodeURIComponent($("#nmi_c").val());
         build_url += '&account_number='+ encodeURIComponent($("#account_number_c").val());
         build_url += '&billing_name='+ encodeURIComponent($("#name_on_billing_account_c").val());
         build_url += '&energy_retailer='+ encodeURIComponent($("#energy_retailer_c").val());
         build_url += '&distributor='+ encodeURIComponent($("#distributor_c").val());
         var roof_type = $("#roof_type").val();
         var roof_type_arr ={"TIN/COLORBOND":2,
                             "CONCRETE TILE":3,
                             "KLIPLOC"      :4,
                             "SLATE ROOF"   :1,
                             "ASBESTOS ROOF":8,
                             "TERRACOTTA"   :10,
                             "UNSURE"       :1};
         build_url += '&roof_type='+ encodeURIComponent(roof_type_arr[roof_type]);
 
         $.ajax({
             url: build_url,
             type : 'POST',
             success: function (data) {
                 $("#solargain_lead_number_c").val(data);
                 $("#solargain_lead_number_c").trigger("change");
                 SUGAR.quoteCreateSGTeslaQuote(element);
                 $("#designer_c").val($('input[name="current_user_name"]').val()); 
                 $("#user_id_c").val($('input[name="current_user_id"]').val());
             },
         });
     }
     //end 
 
     //FUNCTION QUOTECREATESGTESLAQUOTE
     SUGAR.quoteCreateSGTeslaQuote = function (element){
         
         state_ = $("#billing_address_state").val();
         
         if(state_ == ''){
             alert('Please fill up State for Auto populate pricing section');
             $("#billing_address_state").focus();
             $('#updateTeslaQuoteSG span.glyphicon-refresh').addClass('hidden');
             return;
         }
 
         leadID = $("#solargain_lead_number_c").val();
         if (leadID == 0 ) return;
         var build_url=  "?entryPoint=quoteCreateSGTeslaQuote";
         build_url += '&leadID='+ encodeURIComponent(leadID);
 
         build_url += '&record='+ encodeURIComponent($('input[name="record"]').val())
                     + "&billing_address_street=" + $("#install_address_c").val()
                     + "&billing_address_city=" + $("#install_address_city_c").val()
                     + "&billing_address_state=" + $("#install_address_state_c").val()
                     + "&billing_address_postalcode=" + $("#install_address_postalcode_c").val();
         build_url += '&meter_phase_c='+ (($("#phases").val() == 'Three Phases' && $("#phases").val() != 'Unsure') ? 3 : (($("#phases").val() == 'Single Phase') ? 1 : (($("#phases").val() != 'Unsure') ? 2 : '')));
         build_url += '&solargain_inverter_model='+ encodeURIComponent($("#solargain_inverter_model_c").val());
 
         $.ajax({
             url: build_url,
             type : 'POST',
             success: function (data) {
                if(data == '' && typeof data == undefined){$('#createsolargainLead span.glyphicon-refresh').addClass('hidden');return;}
                 var jsonData = $.parseJSON(JSON.stringify(data));
                 $('#createsolargainLead span.glyphicon-refresh').addClass('hidden');
                 $("#solargain_tesla_quote_number_c").val(jsonData.QuoteNumber);
                 $("#sg_site_details_no_c").val(jsonData.SiteDetailNumber);
                 SolarGainQuoteNumberLinkTesla();
             },
         });
     }
     //END
 
     //FUNCTION UPDATE TESLA SG QUOTE
      SUGAR.updateTeslaQuoteToSolargain = function(elem) {
 
         $('#updateQuoteToSolargain span.glyphicon-refresh').removeClass('hidden');
         state_ = $("#install_address_state_c").val();
 
         if(state_ == ''){
             alert('Please fill up State for Auto populate pricing section');
             $("#install_address_state_c").focus();
             $('#updateQuoteToSolargain span.glyphicon-refresh').addClass('hidden');
             return;
         } 
 
         var tesla_quote_id = $("#solargain_tesla_quote_number_c").val();
         if(tesla_quote_id == ''){
             alert('Please enter tesla quote number before clicking this button.');
             $("#solargain_tesla_quote_number_c").focus();
             $('#updateQuoteToSolargain span.glyphicon-refresh').addClass('hidden');
             return;
         }
 
         var build_url_quote_tesla = "?entryPoint=quoteUpdateToSGTeslaQuote";
         build_url_quote_tesla += '&record='+ encodeURIComponent($('input[name="record"]').val());
         build_url_quote_tesla += '&leadID='+ encodeURIComponent($("#solargain_lead_number_c").val());
         //thienpb code
         build_url_quote_tesla += '&solargain_inverter_model='+ encodeURIComponent($("#solargain_inverter_model_c").val());
 
         build_url_quote_tesla += "&tesla_quote_id="+tesla_quote_id;
         build_url_quote_tesla += "&meter_number="+encodeURIComponent($("#meter_number_c").val());
         build_url_quote_tesla += "&meter_phase="+encodeURIComponent((($("#phases").val() == 'Three Phases' && $("#phases").val() != 'Unsure') ? 3 : (($("#phases").val() == 'Single Phase') ? 1 : (($("#phases").val() != 'Unsure') ? 2 : ''))));
         build_url_quote_tesla += '&account_number='+ encodeURIComponent($("#account_number_c").val());
         build_url_quote_tesla += "&nmi_number="+encodeURIComponent($("#nmi_c").val());
         build_url_quote_tesla += "&name_on_billing_account="+encodeURIComponent($("#name_on_billing_account_c").val());
         build_url_quote_tesla += '&billing_name='+ encodeURIComponent($("#name_on_billing_account_c").val());
         build_url_quote_tesla += "&energy_retailer="+encodeURIComponent($("#energy_retailer_c").val());
         build_url_quote_tesla += "&distributor="+encodeURIComponent($("#distributor_c").val());
         build_url_quote_tesla += '&billing_address_street='+ encodeURIComponent($("#install_address_c").val());
         build_url_quote_tesla += '&billing_address_city='+ encodeURIComponent($("#install_address_city_c").val());
         build_url_quote_tesla += '&state='+ encodeURIComponent($("#install_address_state_c").val());
         build_url_quote_tesla += '&postalcode='+ encodeURIComponent($("#install_address_postalcode_c").val());
         build_url_quote_tesla += '&address_nmi='+ encodeURIComponent($("#address_nmi_c").val());
         var customer_type = $('input[name=customer_type_c]:checked').val();
         build_url_quote_tesla += '&customer_type='+ encodeURIComponent(customer_type) ;
 
         var roof_type = $("#roof_type").val();
         var roof_type_arr ={"TIN/COLORBOND":2,
                             "CONCRETE TILE":3,
                             "KLIPLOC"      :4,
                             "SLATE ROOF"   :1,
                             "ASBESTOS ROOF":8,
                             "TERRACOTTA"   :10,
                             "UNSURE"       :1};
         build_url_quote_tesla += '&roof_type='+ encodeURIComponent(roof_type_arr[roof_type]);
         build_url_quote_tesla += '&build_height='+ encodeURIComponent($("#gutter_height_c").val());
         build_url_quote_tesla += '&connection_type='+ encodeURIComponent($("#connection_type_c").val());
         build_url_quote_tesla += '&main_type='+ encodeURIComponent($("#main_type_c").val());
         // for(var i = 1; i<=6 ;i++){
         //     var travel_km    = $("#travel_km_"+i).val().trim();
         //     //var price_option = $("#suggest_price_"+i).val().trim();
         //     var number_double_storey_panel = $("#number_double_storey_panel_"+i).val().trim();
         //     var groups_of_panels  = $("#groups_of_panels_"+i).val().trim();
 
         //     if(travel_km == ''){
         //         travel_km = 0;
         //     }else if(parseInt(travel_km) > 50) {
         //         travel_km = parseInt(travel_km) - 50;
         //     } else{
         //         travel_km = 0;
         //     }
         //     if(number_double_storey_panel == ''){
         //         number_double_storey_panel = 0;
         //     }
         //     if(parseInt(groups_of_panels) >= 2){
         //         groups_of_panels = groups_of_panels - 2;
         //     }else{
         //         groups_of_panels = 0;
         //     }
 
         //     //build_url_quote_tesla += '&price_option_'+ i +'='+ encodeURIComponent(price_option);
         //     build_url_quote_tesla += '&travel_km_'+ i +'='+ encodeURIComponent(parseInt(travel_km));
         //     build_url_quote_tesla += '&number_double_storey_panel_'+ i +'='+ encodeURIComponent(parseInt(number_double_storey_panel));
         //     build_url_quote_tesla += '&groups_of_panels_'+ i +'='+ encodeURIComponent(parseInt(groups_of_panels));
         //     build_url_quote_tesla += '&groups_of_panels_'+ i +'='+ encodeURIComponent(parseInt(groups_of_panels));
         // }
 
         $.ajax({
             url: build_url_quote_tesla,
             type : 'POST',
             success: function (data) {
                 $('#updateQuoteToSolargain span.glyphicon-refresh').addClass('hidden');
             },
         });
          
      }
      //END
 
     //Thienpb code - seek install date button\
     SUGAR.seekInstallDate = function(elem) {
         if($("#leads_aos_quotes_1leads_ida").val() == ''){
             alert('Please fill Leads!');
             $("#leads_aos_quotes_1_name").focus();
             return;
         }
         $(document).openComposeViewModal_seekInstallDate(elem);
     }
 
     $.fn.openComposeViewModal_seekInstallDate = function (source) {
         "use strict";
         var self = this;
         self.emailComposeView = null;
         var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
         var composeBox = $('<div></div>').appendTo(opts.contentSelector);
         composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
         composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
         composeBox.show();
         var record_id = $("input[name='record']").val();
         var lead_id = $(source).attr('data-lead-id');
         $.ajax({
             type: "GET",
             cache: false,
             url: 'index.php?module=Emails&action=ComposeView&in_popup=1&seek_install_date=1&' + ((record_id!="")? ("&quote_id="+record_id):"") + ((lead_id!="")? ("&lead_id="+lead_id):""),
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
 
             // change address email new
            //  var solar_install_contact = {
            //      'VIC': 'sg.vic.installadmin@solargain.com.au', // update 12/3/2019 -change address email for state VIC
            //      'SA':  "sg.wa.installadmin@solargain.com.au",
            //      'ACT': "act.pv@solargain.com.au",
            //      'NSW': "act.pv@solargain.com.au",
            //      'WA':  "sg.wa.installadmin@solargain.com.au",
            //      'QLD': "Rebecca Rodgers <Rebecca.Rodgers@solargain.com.au>",
            //  }
            // change Ensure all 3x addresses are included  (regardless of state)
            var solar_install_contact = "<sg.vic.installadmin@solargain.com.au>" + ", " + "<lauren.patmore@solargain.com.au>" + ", " + "<joel.walsh@solargain.com.au>";
             var populateModuleName = $(source).attr('data-module-name');
             var populateEmailAddress = $(source).attr('data-email-address');
 
             /*if (populateModuleName !== '') {
                 populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
             }*/
             var primary_address_state = $("#billing_address_state").val().toUpperCase();
             if(primary_address_state !== '') {
                 switch (primary_address_state) {
                     case 'VICTORIA':
                         primary_address_state = 'VIC';
                         break;
                     case 'QUEENSLAND':
                         primary_address_state = 'QLD';
                         break;
                     case 'NEW SOUTH WALES':
                         primary_address_state = 'NSW' ;
                         break;
                     case 'AUSTRALIAN CAPITAL TERRITORY':
                         primary_address_state = 'ACT';
                         break;
                     case 'SOUTH AUSTRALIA':
                         primary_address_state = 'SA';
                         break;
                 }
               
             }
 
             var populateEmailAddress = solar_install_contact; //solar_install_contact[primary_address_state];
             $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
             $(self.emailComposeView).find('#parent_type').val(populateModule);
             $(self.emailComposeView).find('#parent_name').val(populateModuleName);
             $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
             $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
             $(self.emailComposeView).find('#parent_id').after("<input type='hidden' name='lead_id' value='"+lead_id+"' >")
             
             //input hidden type seek-install-date-from-leads
             var record_id = $("input[name='record']").val();
             var html_checkbox_Convert_Solar_Opportunity = 
             '<div class="col-xs-12 col-sm-12 edit-view-row-item hidden ">'
             + '<div class="col-xs-12 col-sm-2 label" data-label="">'
             + 'Seek_Install_Date_From_Quotes_Check:</div>'
             + '<div class="col-xs-12 col-sm-8 edit-view-field " type="bool" field="send_sms" colspan="3">'
             + '<input type="text" checked id="Seek_Install_Date_From_Quotes_Check" name="Seek_Install_Date_From_Quotes_Check" value="'+record_id +'" title="" tabindex="0">'                              
             +'</div>'
             +'</div>';
             $(self.emailComposeView).find('#EditView_tabs .tab-content .edit-view-row').append(html_checkbox_Convert_Solar_Opportunity);
             
             var phone_number_customer = $(source).attr('data-phone-number')
             phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
             //tu-code
             $('#number_client').val('');
 
             $('#sms_message').after("<br><button class='button' type='button' id='get_mess' >Get SMS</button>");
             $("#get_mess").click(function(){
                 //thienpb code update sms message from custom changes to the email.
                 $('#sms_message').val('');
                 var sms_body = '';
                 var sms_body_split = '';
                     var sms_body = $('#ComposeView').find('textarea#description').val();
                     sms_body_split = sms_body.split('Regards');
                     if(sms_body_split.length > 1 ){
                         sms_body =  sms_body_split[0].trim();
                     }else{
                         sms_body = sms_body.split('Thanks in advance');
                         sms_body =  sms_body[0].trim();
                     }
                     $("#sms_message").val(sms_body.replace(', ',', \n').replace('? ','? \n').replace('Customer Name','\nCustomer  Name').replace('Customer Address','\nCustomer Address').replace('Network','\nNetwork').replace('Notes','\nNotes'));
             //end
             })
             if(phone_number_customer != ''){
                 //$('#send_sms').attr('checked',true);
                 $('#number_receive_sms').val('matthew_paul_client');
             }
             //tu-code uncheckbox 
             $('#send_sms').prop('checked', false);
             }
             $(self.emailComposeView).on('sentEmail', function (event, composeView) {
                 var Seek_Install_Date_From_Quotes_Check = $('#Seek_Install_Date_From_Quotes_Check').val();
                   if(Seek_Install_Date_From_Quotes_Check !== ''){
                     
                     var currentdate = new Date();
                     
                     var datetime = currentdate.getDate() + "/" + ("0" + (currentdate.getMonth() + 1)).slice(-2) + "/" + currentdate.getFullYear() ;
                   
                     $('#seek_install_date_c_date').val(datetime);
             
                     var hours = currentdate.getHours();
                     hours = ("0" + hours).slice(-2);
                     $('#seek_install_date_c_hours').val(hours);
             
                     var minutes = currentdate.getMinutes();
                     minutes = minutes - (minutes % 15);
                     if (minutes == 0)
                     {
                         minutes = '00';
                     }
                     $('#seek_install_date_c_minutes').val(minutes);
             
                     $('#seek_install_date_c').val(datetime + ' ' + hours + ':' + minutes);        
                   }
                 // change status 
                 $("#stage").val('Install_Date_Requested');
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
             // composeBox.on('ok', function () {
             //     url = 'index.php?module=AOS_Quotes&action=EditView&record=' + record_id;
 
             //   location.href = url;
             // });
             composeBox.on('hide.bs.modal', function () {
                 composeBox.remove();
             });
         }).fail(function (data) {
             composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
         });
         return $(self);
       
     };
     //end
     
     var record_id = $("input[name='record']").val();
     //Tri Truong
     $.fn.openComposeViewModalRequestClient = function (source) {
         "use strict";
         var module_name = $(source).attr('data-module');
         var record_id= $(source).attr('data-record-id') ;
         var email_address = $(source).attr('data-email-address');
         
         if(email_address == '') {
             alert('Please fill Leads!');
             $("#leads_aos_quotes_1_name").focus();
             return;
         }
         if(record_id == ''){
             alert('Please Save before !');
             return;
         }
         
         var quote_number= $('#solargain_quote_number_c').val() ;
    
         // alert("ok");
         var build_url_quote = "?entryPoint=customProposedInstallDate";
         build_url_quote += "&quoteSG_ID="+quote_number;
         build_url_quote += "&proposed_date="+ $("#proposed_install_date_c").val();
         $.ajax({
             url: build_url_quote,
             type : 'POST',
             success: function (data) {
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
             var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+  ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") ;
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
             var populateEmailAddress = $(source).attr('data-email-address');
            
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
     
     
     //End Code
 
     //thienpb code - update quote date to SG Quote
 
     //dung code - button push quote date to solargain
     $('#quote_date_c').next().after('<button class="button primary" id="pushQuoteDateToSG"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Push to SG</button>');
     $("#pushQuoteDateToSG").click(function(){
         if($("#quote_date_c").val() == '') {
             alert('Not value Quote Date!');
             return false;
         }
         $('#pushQuoteDateToSG span.glyphicon-refresh').removeClass('hidden');
         var build_url_quote = "?entryPoint=updateQuoteToSGQuote";
         build_url_quote += '&record='+ encodeURIComponent($('input[name="record"]').val());
         build_url_quote += "&leadSG_ID="+encodeURIComponent($("#solargain_lead_number_c").val());
         build_url_quote += "&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
 
         build_url_quote += "&quoteDate="+encodeURIComponent($("#quote_date_c").val());
         $.ajax({
             url: build_url_quote,
             type : 'POST',
             success: function (data) {
                 $('#pushQuoteDateToSG span.glyphicon-refresh').addClass('hidden');
             },
         });
         return false;
     });
 
 
     //dung code  -- copy function design complete
     $("#CANCEL").after(
         ' <button type="button" id="sendDesignsComplete" class="button sendDesignsComplete" title="Designs Complete" onClick="sendEmailDesignsComplete();" > Designs Complete <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
     );
     
     //thienpb code - add button ACCEPT JOB
     $("#save_and_email_pdf").after(' <button class="button primary" id="acceptJob"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Accept Job</button>');
     $("#acceptJob").click(function(){
         $('#acceptJob span.glyphicon-refresh').removeClass('hidden');
         var record_id = $("input[name='record']").val();
         var account_id = $("#billing_account_id").val();
         var solargain_quote_number_c = $("#solargain_quote_number_c").val();
         if(account_id == '') {
             alert('Please Select Account!');
             $("#billing_account").focus();
             $('#acceptJob span.glyphicon-refresh').addClass('hidden');
             return false;
         }
         if(solargain_quote_number_c == '') {
             alert('Please Insert Number Quote!');
             $("#solargain_quote_number_c").focus();
             $('#acceptJob span.glyphicon-refresh').addClass('hidden'); 
             return false;
         }
         //save quote before acceptJob
         $("#EditView input[name='action']").val('Save');
         setTimeout(function () {
             $.ajax({
                 type: $("#EditView").attr('method'),
                 url: $("#EditView").attr('action'),
                 data: $("#EditView").serialize(),
                 async: false, 
                 success: function () {
                     console.log('Saved');
                 }
             });
         },100);
 
 
         var build_url_quote = "?entryPoint=customQuotesAcceptJob&record_id="+record_id;
         $.ajax({
             url: build_url_quote,
             type: "GET",
             success: function (data) {
                 if(typeof(data) !== 'undefined'){
                     try {
                         data = JSON.parse(data);
                     } catch (error) {
                         $('#acceptJob span.glyphicon-refresh').addClass('hidden');
                         return false;
                     }
                     
                 }else{
                     $('#acceptJob span.glyphicon-refresh').addClass('hidden');
                     return;
                 }
                 if(data['message'] != 'done'){
                     alert(data['message']);
                 }else{
                     var currentdate =  new Date().toLocaleString("en-US", {timeZone: "Australia/Melbourne"})
                     
                     currentdate =  new Date(currentdate);
                     var datetime = currentdate.getDate() + "/" + ("0" + (currentdate.getMonth() + 1)).slice(-2) + "/" + currentdate.getFullYear() ;
                   
                     $('#time_accepted_job_c_date').val(datetime);
             
                     var hours = currentdate.getHours();
                     hours = ("0" + hours).slice(-2);
                     $('#time_accepted_job_c_hours').val(hours);
             
                     var minutes = currentdate.getMinutes();
                     minutes = minutes - (minutes % 15);
                     if (minutes == 0)
                     {
                         minutes = '00';
                     }
                     $('#time_accepted_job_c_minutes').val(minutes);
                     $('#time_accepted_job_c').val(datetime + ' ' + hours + ':' + minutes);
 
                     $("#designer_c").val(data['user_name']);
                     $("#user_id_c").val(data['user_id']);
                     $("#stage").val('JobAccepted_InProgress');
 
                 }
                 $('#acceptJob span.glyphicon-refresh').addClass('hidden');
             },
         });
         return false;
     })
 
     //thienpb code - add button QUOTE FOLLOW UP
     $("#btn_view_change_log").after('<button data-email-type="follow_up" data-quote-name="'+$("#name").val()+'" data-email-address="" data-lead-id="'+$('#leads_aos_quotes_1leads_ida').val()+'" style="margin-left: 4px;"   data-record-id="'+$('input[name="record"]').val()+'" type="button" id="quote_follow_up" class="button quote_follow_up" title="Quote Follow Up" data-module="Lead" onClick="SUGAR.quoteFollowUp(this);" >Quote Follow Up<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
     $("#btn_view_change_log").after('<button type="button" class="button primary" data-email-address="" id="send_site_inspection_request" onclick="$(document).openComposeViewModal_Inspection_request(this);" data-email-type="send_site_inspection_request" data-module="AOS_Quotes" data-module-name="'+$("#name").val()+'" data-contact-name="'+$('#billing_contact').val()+'" data-record-id="'+$("input[name='record']").val()+'">Send Inspection Request</button>')

     var lead_id_solar = $('#leads_aos_quotes_1leads_ida').val();
     //tuan code - add button Sanden Quoter
    //  $("#btn_view_change_log").after('<a class="button moveSandenForm" href="https://pure-electric.com.au/pe-sanden-quote-form?lead-id='+lead_id_solar+'" target="_blank" style="color:white;padding:8.3px;margin-left: 4px;background:#945596;">SANDEN QUOTE FORM</a>')
    // //  $("#btn_view_change_log").after('<a class="button moveDaikinForm" href="https://pure-electric.com.au/pedaikinform?lead-id='+lead_id_solar+'" target="_blank" style="color:white;padding:8.3px;margin-left: 4px;background:#009acf;">DAIKIN QUOTE FORM</a>')
    //  $("#btn_view_change_log").after('<br><a class="button moveSolarForm" href="https://pure-electric.com.au/pesolarform?lead-id='+lead_id_solar+'" target="_blank" style="color:white;padding:8.3px;background:#f48c21;">SOLAR QUOTE FORM</a>')
    //  $("#btn_view_change_log").after('<a class="button moveDaikinNewForm" href="https://pure-electric.com.au/pedaikinform-new?lead-id='+lead_id_solar+'" target="_blank" style="color:white;padding:8.3px;margin-left: 4px;background:#009acf;">DAIKIN QUOTE FORM</a>')
     $("#btn_view_change_log").after('<button style="margin: 0px 3px;background:#945596;" type="button" id="btn_pe_sanden_form_new" class="button btn_pe_sanden_form_new" title="PE Sanden Form">Sanden Quote Form</button>');
     $("#btn_view_change_log").after('<button style="margin: 0px 3px;background:#f48c21;" type="button" id="btn_pe_solar_form" class="button btn_pe_solar_form" title="PE Solar Form">Solar Quote Form</button>');
     $("#btn_view_change_log").after('<button style="margin: 0px 3px;background:#009acf;" type="button" id="btn_pe_daikin_new_form" class="button btn_pe_daikin_new_form" title="PE Daikin Form">Daikin Quote Form</button>');
     $("#btn_view_change_log").after('<button style="margin: 0px 3px;background-image: linear-gradient(to right, #0acffe 0%, #495aff 100%);" type="button" id="btn_pe_daikin_tool" class="button btn_pe_daikin_tool" title="PE Daikin Tool">Daikin Design Tool</button>');
     $("#btn_view_change_log").after('<button style="margin: 0px 3px;background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" type="button" id="btn_pe_sanden_tool" class="button btn_pe_sanden_tool" title="PE Sanden Tool">Sanden Design Tool</button>');
     $("#btn_view_change_log").after('<button style="margin: 0px 3px;background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" type="button" id="btn_pe_solar_tool" class="button btn_pe_solar_tool" title="PE Solar Tool">Solar Design Tool</button>');
     $("#btn_pe_daikin_new_form").click(function(e) {
        if(lead_id_solar != '') {
            window.open(
                'https://pure-electric.com.au/pedaikinform-new?lead-id='+lead_id_solar,
                '_blank' // <- This is what makes it open in a new window.
            );
        } else {
            alert('No leads in quote, please add lead information !')
            // window.open(
            //     'https://pure-electric.com.au/pedaikinform',
            //     '_blank' // <- This is what makes it open in a new window.
            // );
        }
    });
    $("#btn_pe_daikin_tool").click(function(e) {
        if(lead_id_solar != '') {
            window.open(
                'http://daikintool.pure-electric.com.au/index.php?quote_id='+record_id,
                '_blank' // <- This is what makes it open in a new window.
            );
        } else {
            alert('No leads in quote, please add lead information !')
            // window.open(
            //     'https://pure-electric.com.au/pedaikinform',
            //     '_blank' // <- This is what makes it open in a new window.
            // );
        }
    });
    $("#btn_pe_sanden_tool").click(function(e) {
        if(lead_id_solar != '') {
            window.open(
                'http://sandentool.pure-electric.com.au/index.php?quote_id='+record_id,
                '_blank' // <- This is what makes it open in a new window.
            );
        } else {
            alert('No leads in quote, please add lead information !')
            // window.open(
            //     'https://pure-electric.com.au/pedaikinform',
            //     '_blank' // <- This is what makes it open in a new window.
            // );
        }
    });
    $("#btn_pe_sanden_form_new").click(function(e) {
        if(lead_id_solar != '') {
            window.open(
                'https://pure-electric.com.au/pe-sanden-quote-form?lead-id='+lead_id_solar,
                '_blank' // <- This is what makes it open in a new window.
            );
        } else {
            alert('No leads in quote, please add lead information !')
            // window.open(
            //     'https://pure-electric.com.au/pedaikinform',
            //     '_blank' // <- This is what makes it open in a new window.
            // );
        }
        
    });
    $("#btn_pe_solar_form").click(function(e) {
        let quoteId = $('input[name="record"]').val();
        if(quoteId != '') {
            window.open(
                'https://pure-electric.com.au/pesolarform?quote-id='+quoteId,
                '_blank' // <- This is what makes it open in a new window.
            );
        } else {
            alert('No quote exist, please check again !')
            // window.open(
            //     'https://pure-electric.com.au/pedaikinform',
            //     '_blank' // <- This is what makes it open in a new window.
            // );
        }
    });
    $("#btn_pe_solar_tool").on('click',function(e) {
        if( $("#solar_design_tool_id_c").val() == ''){
            SUGAR.ajaxUI.showLoadingPanel();
            var address = [$("#install_address_c").val(),$("#install_address_city_c").val()+' '+$("#install_address_state_c").val(),$("#install_address_postalcode_c").val(),'Australia'];
            address = address.join(', ');
            var first_name = $("#account_firstname_c").val();
            var family_name = $("#account_lastname_c").val();
            var email = $(".phone-number").children("a").attr('data-email-address');
            var phone = $(".phone-number").children(".account_phone_number").text().replace(/ /g,'');
            $.ajax({
                url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' 
                +address
                + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
                type: 'GET',
                success: function(result) {
                    if (result.status == "OK"){
                        var location = result.results[0].geometry.location;
                        var quote_id = $("#EditView input[name='record']").val();
                        $.ajax({
                            type: "GET",
                            url: 'index.php?entryPoint=customCreateProjectAPISolarDesignTool',
                            data : {"quote_id": quote_id,"mapAPI":result,"first_name":first_name,"family_name":family_name,"email":email,"phone":phone},
                            success : function(data){
                                if(data != ''){
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                    $("#solar_design_tool_id_c").val(data);
                                    window.open(
                                        'https://solardesign.pure-electric.com.au/#/studio/'+data,
                                        '_blank' // <- This is what makes it open in a new window.
                                    );
                                }else{
                                    alert("Can't create solar design project");
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                }
                            }
                        });
                    }else{
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }
                }
            });
        }else{
            window.open(
                'https://solardesign.pure-electric.com.au/#/studio/'+$("#solar_design_tool_id_c").val(),
                '_blank' // <- This is what makes it open in a new window.
            );
        }
    });
 });


 SUGAR.quoteFollowUp =  function(elem){
     $(document).openComposeViewModal_quoteFollowUp(elem);
 }
 
 $.fn.openComposeViewModal_quoteFollowUp = function (source) {
     var self = this;
     self.emailComposeView = null;
     var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
     var composeBox = $('<div></div>').appendTo(opts.contentSelector);
     composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
     composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
     composeBox.show();
     var record_id= $(source).attr('data-record-id');
     var email_type = $(source).attr('data-email-type');
     var lead_id = $(source).attr('data-lead-id');
     var email = $("#detail_email").attr('data-email-address');
     var product_type = $("#quote_type_c").val();
     var lead_source_company = $("#lead_source_co_c").val();
     var name_quote = $(source).attr('data-quote-name');
     $.ajax({
         type: "GET",
         cache: false,
         url: 'index.php?module=Emails&action=ComposeView&in_popup=1&quote_id='+record_id+((email_type!="")? ("&email_type="+email_type):"")+((lead_id!="")? ("&lead_id="+lead_id):"")+((email!='') ? "&email_address="+email : "")+((product_type!='') ? "&product_type="+product_type : "")+((lead_source_company!='') ? "&lead_source_company="+lead_source_company : ""),
     }).done(function (data) {
         if (data.length === 0) {
         console.error("Unable to display ComposeView");
         composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
         return;
         }
         composeBox.setBody(data);
         self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();
         var quote_id =  $(self.emailComposeView).find('input[name="email_return_id"]').val();
         $(self.emailComposeView).find('#parent_type').val('AOS_Quotes');
         $(self.emailComposeView).find('#parent_name').val(name_quote);
         $(self.emailComposeView).find('#parent_id').val(quote_id);
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
 
 function sendEmailDesignsComplete() {
     //dung code - alert confirm
     var dialog_message = "<ul style='list-style-type: circle; padding: 0 40px;'>";
     if($('#time_completed_job_c').val() !== '') 
         dialog_message += "<li>We sent Designs Complete email to assigned user before. Do you want to resend a new Designs Complete email to assigned user again?</li>";
     if($("#do_not_email_c").is(':checked') == false)
         dialog_message += '<li>"Do Not Send Design Complete Email To Client" checkbox is not checked. Do you want to send design to client?</li>';
     if(($("#time_sent_to_client_c").val() != "") && ($("#do_not_email_c").is(':checked') == false))
         dialog_message += '<li>"We also sent design to client before. Do you want to send design to client again?</li>';
     //dung code - check suggest_price field
    //  var select_option = $("#solargain_options_c").val();
    //  var html_option = '<li>Pricing Options ';
    //  var check_empty_suggest_price = false;
    //  select_option.forEach(function(element){
    //      element++;
    //      if($("#suggest_price_"+(element+1)).val() == ''){
    //          html_option = html_option + element +',';
    //          check_empty_suggest_price = true;
    //      } 
    //  });
    //  html_option +=' is NOT FILLED IN! Are you sure you want to continue?</li>';
    //  if(check_empty_suggest_price){
    //      dialog_message += html_option; 
    //  }
     dialog_message += "</ul>";
     if(dialog_message !== "<ul style='list-style-type: circle; padding: 0 40px;'></ul>") {
         var dialog = $(dialog_message).dialog({
             buttons: {
                 "Yes": function() { 
                     send_email_design_complete(); dialog.dialog('close');
                 },
                 "Cancel":  function() {
                     dialog.dialog('close');
                     return;
                 }
             }
             });
     } else {
         send_email_design_complete();
     }
     function send_email_design_complete() {        
 
         $('#SAVE').prop('disabled', true);
         $('#save_and_edit').prop('disabled', true);
 
     
         $('#sendDesignsComplete span.glyphicon-refresh').removeClass('hidden');
     
         var _url = "?entryPoint=customQuoteSendDesignsComplete&record_id="
         + $('input[name="record"]').val()
         + "&do_not_email_c=" + $("#do_not_email_c").is(":checked")
         + "&assigned_user_id=" + $('input[name="assigned_user_id"]').val()
         + "&assigned_user_name=" + $('input[name="assigned_user_name"]').val()
         + "&billing_address_street=" + $("#billing_address_street").val()
         + "&billing_address_city=" + $("#billing_address_city").val()
         + "&billing_address_state=" + $("#billing_address_state").val()
         + "&billing_address_postalcode=" + $("#billing_address_postalcode").val();
     
         $.ajax({
             url: _url,
             type: 'GET',
             success : function(data){
                if(data == '' && typeof data == undefined)return;
                 var jsonObject = $.parseJSON(data);
                 var vals = jsonObject.time_complete.split(' ');
         
                 var date = vals[0];
                 $('#time_completed_job_c_date').val(date);
                 $("#stage").val('Designs_Complete');
         
                 var times = vals[1].split(':');
         
                 var hours = parseInt(times[0]);
                 if (hours < 10)
                 {
                     hours = '0' + hours;
                 }
                 $('#time_completed_job_c_hours').val(hours);
         
                 var minutes = parseInt(times[1]) - parseInt(times[1] % 15);
                 if (minutes == 0)
                 {
                     minutes = '00';
                 }
                 $('#time_completed_job_c_minutes').val(minutes);
         
                 $('#time_completed_job_c').val(date + ' ' + hours + ':' + minutes);
                 
                 // Time send client
                 if(jsonObject.time_sent_client !== "undefined" && jsonObject.time_sent_client != ""){
                     var vals = jsonObject.time_sent_client.split(' ');
             
                     var date = vals[0];
                     $('#time_sent_to_client_c_date').val(date);
             
                     var times = vals[1].split(':');
             
                     var hours = parseInt(times[0]);
                     if (hours < 10)
                     {
                         hours = '0' + hours;
                     }
                     $('#time_sent_to_client_c_hours').val(hours);
             
                     var minutes = parseInt(times[1]) - parseInt((times[1] % 15));
                     if (minutes == 0)
                     {
                         minutes = '00';
                     }
                     $('#time_sent_to_client_c_minutes').val(minutes);
             
                     $('#time_sent_to_client_c').val(date + ' ' + hours + ':' + minutes);
                 }
                 $('#sendDesignsComplete span.glyphicon-refresh').addClass('hidden');
         
                 $('#SAVE').prop('disabled', false);
                 $('#save_and_edit').prop('disabled', false);
             }
         })
     
         //dung code - ajax download file pdf from solargain
         if($('#solargain_quote_number_c').val() !== ''){
             var url_download = "?entryPoint=CustomDownloadPDF&module=AOS_Quotes&type=quote&record_id="
             + $('input[name="record"]').val()
             + '&quote_solorgain='+$('#solargain_quote_number_c').val()
             + '&folder_id='+$("input[name='pre_install_photos_c']").val();
             $.ajax({
                 url: url_download,
                 async: false,
                 type: 'GET',
                 success: function(data){
                     console.log(data);
                 }
             })
         }else{
             alert('Not have number quote solargain!');
         }
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
         // button Update Related
         // Tri Add Daikin Link
         var type_quote = $('#quote_type_c').val();
         if(type_quote.indexOf("daikin") != '-1') {
             var id_quote_daikin = $("input[name='record']").val();
             var daikin_link = '<div id="daikin_path"><span><strong>Daikin Design Path: </strong></span><span id="daikin_design_path">http://daikintool.pure-electric.com.au/index.php?quote_id='+id_quote_daikin+'</span><button id="copi_daikin_path_tool" type="button">Copy Path</button></div>';
             $('.other_path').append(daikin_link);
             $('#copi_daikin_path_tool').on('click', function() {
                 clip_aboard_s('daikin_design_path');
             })
         }
 
         function clip_aboard_s(absolute_path){
             var textarea = document.createElement('textarea');
             textarea.id = 'temp_element';
             textarea.style.height = 0;
             document.body.appendChild(textarea);
             textarea.value = document.getElementById(absolute_path).innerText;
             var selector = document.querySelector('#temp_element');
             selector.select();
             document.execCommand('copy');
             document.body.removeChild(textarea);
         }
         if(module_sugar_grp1 == 'AOS_Quotes') {
             $("#btn_clr_assigned_user_name").after('<button type="button" style="width: 218px;" id="update_relates" class="button update_relates">Update Related <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
         }
         if(typeof record !== "undefined" && record != ""){
             $("#update_relates").click(function(){
                 var assigned_id = $("#assigned_user_id").val();
                 var record = $("input[name='record']").val();
                 $('#update_relates span.glyphicon-refresh').removeClass('hidden');
                 $.ajax({
                     url: "/index.php?entryPoint=customUpdateRelated&bean_type=AOS_Quotes&record="+record +"&assigned_id="+assigned_id,
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
 
         //hidden field not use
         if(module_sugar_grp1 == 'AOS_Quotes'){
             $('#solargain_inverter_model_c,#phone_num_registered_account_c,#build_account_c').closest('.edit-view-row-item').hide();
         }
         $("#subpanel_aos_quotes_pe_internal_note_1 tr").each(function() {
             var recordId = getParameterByName('record', $(this).find("td:nth-child(4) a").attr('href'));
             var module_name = getParameterByName('module', $(this).find("td:nth-child(4) a").attr('href'));
             var url = "/index.php?entryPoint=customLeadHoverEmail&record=" + recordId + "&module=" + module_name ;
             if(recordId != null) {
                 $(this).find("td:nth-child(3)").append('<a class="various fancybox.ajax" data-fancybox-type="ajax" href="' + url + '"> Preview</a>');
             }   
         });
         //tuan code check water quality
         if(module_sugar_grp1 == 'AOS_Quotes'){
             $('#install_address_postalcode_c').css('width','35%');
             $('#install_address_postalcode_c').after( '&nbsp;<button  type="button" id="check_water_quality_quote" class="button primary" title="Check Your Water Quality">CHECK WATER QUALITY<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
             $('#check_water_quality_quote').click(function () {
                 $('#check_water_quality_quote span.glyphicon-refresh').removeClass('hidden');
                 $('#link-sanden-hot-water-quote').remove();
                 var postcode_num = $('#install_address_postalcode_c').val();
                 $.ajax({
                     url: "?entryPoint=customCheckPostalCodeSandenWater&postcode_num="+postcode_num,
                     type : 'POST',
                     success: function (data) {
                         $('#check_water_quality_quote span.glyphicon-refresh').addClass('hidden');
                         if( data == "There are no known water quality issues with this postcode."){
                             $('#check_water_quality_quote').after('<br><a target="_blank" id="link-sanden-hot-water-quote" title="Link Sanden Hot Water" href="https://www.sanden-hot-water.com.au/check-your-water-quality?r=549&postcode=' + postcode_num +'">Yes good !</a>')
                         }else {
                             $('#check_water_quality_quote').after('<br><a target="_blank" id="link-sanden-hot-water-quote" title="Link Sanden Hot Water" href="https://www.sanden-hot-water.com.au/check-your-water-quality?r=549&postcode=' + postcode_num +'">FQV !</a>');
                         }
                     },
                 });
             })
         }
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
 
     //thienpb code - for get and push data to solar VIC
         $("#slv_net_payable_c").closest(".tab-content").append("<br><button  class='button primary' type='button' name='btn_fill_form' id='btn_fill_form'><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span>Auto fill Form</button>");
         if($("#slv_solar_vic_id_c").val() == ''){
             $("#slv_net_payable_c").closest(".tab-content").append("<button  class='button primary' type='button' name='btn_push_to_solarVIC' id='btn_push_to_solarVIC' data-process='create'><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span>PUSH To SOLAR VIC</button>");
         }else{
             $("#slv_net_payable_c").closest(".tab-content").append("<button  class='button primary' type='button' name='btn_push_to_solarVIC' id='btn_push_to_solarVIC' data-process='update'><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span>UPDATE To SOLAR VIC</button>");
         }
         //$("#slv_net_payable_c").closest(".tab-content").append("<button  class='button primary hidden' type='button' name='btn_get_solarVIC' id='btn_get_solarVIC'><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span>Get from SOLAR VIC</button>")
         $("#slv_net_payable_c").closest(".tab-content").append("<button  class='button primary' type='button' name='btn_get_status_solarVIC' id='btn_get_status_solarVIC'><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span>Get Status</button>");
 
         $("#slv_solar_vic_id_c").on("change",function(){
             if($(this).val() != ''){
                 $("#btn_push_to_solarVIC").remove();
                 $("#btn_fill_form").after("<button  class='button primary' type='button' name='btn_push_to_solarVIC' id='btn_push_to_solarVIC' data-process='update'><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span>UPDATE To SOLAR VIC</button>")
             }else{
                 $("#btn_push_to_solarVIC").remove();
                 $("#btn_fill_form").after("<button  class='button primary' type='button' name='btn_push_to_solarVIC' id='btn_push_to_solarVIC' data-process='create'><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span>PUSH To SOLAR VIC</button>")
             }
         });
 
         var check_fill_form = false;
         $("#btn_fill_form").click(function(e){
             SUGAR.ajaxUI.showLoadingPanel();
             $("#ajaxloading_mask").css("position",'fixed');            
             var option_choose;
             setTimeout(function(){
                 $("#calculatePrice").trigger("click");
                 $("#get_STCs_SG").trigger("click");
                 $('#slv_firstname_c').val($("#account_firstname_c").val());
                 $('#slv_lastname_c').val($("#account_lastname_c").val());
                 $('#slv_email_c').val($(".phone-number").children("a").data('email-address'));
                 var full_address = '';
                 if($("#install_address_c").val() != ''){
                     full_address =  $("#install_address_c").val()+', '+$("#install_address_city_c").val()+' '+$("#install_address_state_c").val()+' '+$("#install_address_postalcode_c").val();
                 }else{
                     full_address =  $("#billing_address_street").val()+', '+$("#billing_address_city").val()+' '+$("#billing_address_state").val()+' '+$("#billing_address_postalcode").val();
                 }
                 $('#slv_installation_address_c').val(full_address);
                 
                 if($("#customer_benefits_c").prop("checked") && $("#slv_dnsp_approval_c").prop("checked") && $('#slv_interested_solar_loan_c').prop("checked") && $("#slv_ebate_type_c").val() == 'Owner_Occupier'){
                     $("#estimated_financial_saving_c").val((Math.floor(Math.random() * (800 - 650 + 1)) + 650));
                 }else{
                     $("#estimated_financial_saving_c").val('');
                 }
 
                 $("input[name='sl_quote_option']").each(function(i){
                     if(this.checked){
                         option_choose = i;
                         var indx = i+1;
                         $("#slv_panel_type_c").val($("#panel_type_"+indx).val());
                         $("#slv_inverter_type_c").val($("#inverter_type_"+indx).val());
                         $("#slv_total_panel_c").val($("#total_panels_"+indx).val());
                         $("#slv_total_price_c").val($("#sgp_system_price_"+indx).val());
                         $("#slv_estimated_value_c").val($("#stc_value_"+indx).val());
                         $("#slv_estimated_rebate_c").val(1850);
                         $("#slv_estimated_free_loan_c").val(0);
                         $("#slv_quote_sg_number_c").val($("#solargain_quote_number_c").val());
                         var net_payable = parseFloat($("#suggest_price_"+indx).val()) - parseFloat($("#slv_estimated_rebate_c").val()) - parseFloat($("#slv_estimated_free_loan_c").val());
                         $("#slv_net_payable_c").val(net_payable);
                         check_fill_form =  true;
                         return false;
                     }
                 });
                 if(check_fill_form){
                     if($('#solargain_quote_number_c').val() !== ''){
                         var url_download = "?entryPoint=CustomDownloadPDF&module=AOS_Quotes&type=quote&record_id="
                         + $('input[name="record"]').val()
                         + '&quote_solorgain='+$('#solargain_quote_number_c').val()
                         + '&SGleadID='+$("#solargain_lead_number_c").val()
                         + '&folder_id='+$("input[name='pre_install_photos_c']").val();
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
                                     $('#get_sg_image_options span.glyphicon-refresh').addClass('hidden');
                                 });
                             }
                         });
                         if(option_choose >= 0){
                             var url = "?entryPoint=getEstimatedOuput&quoteSG_ID="+$('#solargain_quote_number_c').val()+"&option_choose="+option_choose;
                             $.ajax({
                                 url: url,
                                 async:false,
                                 success: function(data){
                                     $("#estimate_energy_yield_c").val(parseInt(data));
                                 }
                             });
                         }
                     }
                 }else{
                     alert("Please Selected Quote Option.");
                     $('html, body').animate({
                         scrollTop: (($('#sl_option_1').closest(".panel-default").offset().top) - 100)
                     },100);
                 }
                 SUGAR.ajaxUI.hideLoadingPanel();
             },100);
         });
 
         if($("#slv_solar_vic_id_c").val() != ''){
             var href = "<div class='solar_vic_id'><a target='_blank' href='https://solarvic.force.com/industry/s/installation/" + $("#slv_solar_vic_id_c").val() +"'>Open Solar VIC</a></div>";
             $(".solar_vic_id").remove();
             $("#slv_solar_vic_id_c").after(href);
         }
 
         $(document).on("click","#btn_push_to_solarVIC",function(){
             var check_exist_file = false;
             if($(document).find('a[data-gallery]').length > 0){
                 $(document).find('a[data-gallery]').each(function(e){
                     if($(this).text().indexOf('Quote_#') >= 0){
                         check_exist_file = true;
                     }
                 });
             }else{
                 check_exist_file = false;
             }
             if(!check_exist_file){
                 var check_push = confirm("Are you sure you want to push to Solar Vic Portal?");
                 if(check_push){
                     check_exist_file = true;
                 }else{
                     check_exist_file = false;
                 }
             }
             if(check_exist_file){
                 SUGAR.ajaxUI.showLoadingPanel();
                 $("#ajaxloading_mask").css("position",'fixed');            
                 setTimeout(function(){
                     if( $("#billing_address_state").val() == 'VIC'){
                         var url_push = "?entryPoint=pushtoSolarVIC";
                         url_push += "&slv_firstname_c="+$("#slv_firstname_c").val();
                         url_push += "&slv_lastname_c="+$("#slv_lastname_c").val();
                         url_push += "&slv_email_c="+$("#slv_email_c").val();
                         url_push += "&slv_ebate_type_c="+$("#slv_ebate_type_c option:selected").attr('label');
                         url_push += "&slv_installation_address_c="+$("#slv_installation_address_c").val();
                         url_push += "&slv_panel_type_c="+$("#slv_panel_type_c").val();
                         url_push += "&slv_inverter_type_c="+$("#slv_inverter_type_c").val();
                         url_push += "&slv_total_panel_c="+$("#slv_total_panel_c").val();
                         url_push += "&customer_benefits_c="+$("#customer_benefits_c").prop("checked");
                         url_push += "&estimate_energy_yield_c="+$("#estimate_energy_yield_c").val();
                         url_push += "&estimated_financial_saving_c="+$("#estimated_financial_saving_c").val();
                         url_push += "&slv_quote_sg_number_c="+$("#slv_quote_sg_number_c").val();
                         url_push += "&slv_total_price_c="+$("#slv_total_price_c").val();
                         url_push += "&slv_estimated_value_c="+$("#slv_estimated_value_c").val();
                         url_push += "&slv_estimated_rebate_c="+$("#slv_estimated_rebate_c").val();
                         url_push += "&slv_estimated_free_loan_c="+$("#slv_estimated_free_loan_c").val();
                         url_push += "&slv_net_payable_c="+$("#slv_net_payable_c").val();
                         url_push += "&crm_quoteID="+$('input[name="record"]').val();
                         url_push += "&slv_interested_solar_loan_c="+$('#slv_interested_solar_loan_c').prop("checked");
                         url_push += "&slv_dnsp_approval_c="+$('#slv_dnsp_approval_c').prop("checked");
                         url_push += "&assigned_user_id="+$('#assigned_user_id').val();
                         url_push += "&process="+$("#btn_push_to_solarVIC").data("process");
                         url_push += "&solarvicID="+$("#slv_solar_vic_id_c").val();
                         
                         $.ajax({
                             url: url_push,
                             type: "GET",
                             async:false,
                             success:function(data){
                                 if(data != '' && typeof data !== undefined){
                                     var data_parse = $.parseJSON(data);
                                     if(data_parse.error == ""){
                                         $("#slv_solar_vic_id_c").val(data_parse.recordId+'/'+data_parse.recordName);
                                         var href = "<div class='solar_vic_id'><a target='_blank' href='https://solarvic.force.com/industry/s/installation/" + data_parse.recordId +"/"+ data_parse.recordName +"'>Open Solar VIC("+ data_parse.recordName +")</a></div>";
                                         $(".solar_vic_id").remove();
                                         $("#slv_solar_vic_id_c").after(href);
                                         $("#slv_status_c").val(data_parse.status);
                                         $("#slv_solar_vic_id_c").focus();
                                         
                                     }else if(data_parse.error == "The Solargain Quote number is existed"){
                                         if(confirm("The Solargain Quote number is existed.Do you want apply Solar VIC ID ?")){
                                             $("#slv_solar_vic_id_c").val(data_parse.recordId+'/'+data_parse.recordName);
                                             var href = "<div class='solar_vic_id'><a target='_blank' href='https://solarvic.force.com/industry/s/installation/" + data_parse.recordId +"/"+ data_parse.recordName +"'>Open Solar VIC("+ data_parse.recordName +")</a></div>";
                                             $(".solar_vic_id").remove();
                                             $("#slv_solar_vic_id_c").after(href);
                                             $("#slv_status_c").val(data_parse.status);
                                             $("#slv_solar_vic_id_c").focus();
                                         }else{
                                             var url = "https://solarvic.force.com/industry/s/installation/" + data_parse.recordId +"/"+ data_parse.recordName
                                             var win = window.open(url, '_blank');
                                             window.focus();
                                         }
                                     }
                                 }else{
                                     alert("Process push to solar vic fail.");
                                 }
                             }
                         });
                         $("#slv_solar_vic_id_c").trigger("change");
                     }else{
                         alert("State isn't VIC. Can't push to Solar VIC");
                     }
                     SUGAR.ajaxUI.hideLoadingPanel();
                 },100);
             }
         });
 
         $("#btn_get_status_solarVIC").click(function(){
             get_data_from_SV('status');
         });
 
         $("#btn_get_solarVIC").click(function(){
             //comming soon            
         });
     //end
 
         if(module_sugar_grp1 == 'AOS_Quotes') {
             var folder_id  = $('body').find('input[name="pre_install_photos_c"]').val();
             $('body').find('#block_image_site_detail').remove();
             $(document).find('#popup_image_site_detail').remove();
             var url_img = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime();
          
             var image_html_site_details = '<div id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:198px;height:auto;margin-bottom:5px;text-align:center;" >Map Template Image</div>';
            
             var img_html ='<div id="block_image_site_detail"><div class="clear"></div><div class="clear"></div><div class="col-xs-12 edit-view-row-item image_site_detail"><div class="col-md-6 col-sm-6 col-xs-6"> \
                             <div class="col-md-6 col-sm-6 col-xs-6" id="maptemplate-img">' 
                             + image_html_site_details + 
                             '</div> \
                             <div class="col-md-12 col-sm-12 col-xs-12"> \
                                 <button type="button" id="Upload_Image_Site_Detail" class="button" title="Upload Image Site Detail" >Upload</button> \
                             </div> \
                             <input style="display:none;" type="file" name="image_site_detail" id="image_site_detail" "/> \
                         </div></div></div>';
             
             var popup_image_html = '<div style="display:none;position:fixed;z-index:100000;background:burlywood;width:100%;height:100%;text-align:center;top:0;" id="popup_image_site_detail">'
             +'<button onclick="hidePopup()" id="popup_bottom_close" type="button" class="btn btn-info">X</button>'                   
             +'<div style="height:100%;width:100%;padding:5%;" id="popup_image_site_detail_image">'
             + '<div id="Map_Template_Image_popup"></div>'
             +'</div>'
             +'</div>';
             $('body').after(popup_image_html);
             $('body').find('#solargain_tesla_quote_number_c').closest('.edit-view-row-item').after(img_html);
         }
 
         YAHOO.util.Event.addListener("Upload_Image_Site_Detail", "click", function(){
             $('body').find('#image_site_detail').trigger('click');
         });
     
         YAHOO.util.Event.addListener("image_site_detail",'change',function(){ 
             var fd = new FormData(); 
             var files = $('#image_site_detail')[0].files[0]; 
             fd.append('file', files); 
     
             $.ajax({ 
                 url: '?entryPoint=Image_Site_Details&id='+ $('body').find('input[name="pre_install_photos_c"]').val(), 
                 type: 'post', 
                 data: fd, 
                 contentType: false, 
                 processData: false, 
                 success: function(response){ 
                     if(response != 0){ 
                         var folder_id  = $('body').find('input[name="pre_install_photos_c"]').val();
                         debugger
                         $('body').find("#Map_Template_Image").remove();
                         var image_html = '<img id="Map_Template_Image" onclick="showPopup()" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:220px;height:auto;margin-top: 5em;margin-bottom:5px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime()+'">';
                         $('body').find("#maptemplate-img").append(image_html);
     
                         document.getElementById("Map_Template_Image_popup").src = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime();
                         //reload list files
                         $(".files").empty();
                         $.ajax({
                             url: $('#fileupload').fileupload('option', 'url'),
                             dataType: 'json',
                             context: $('#fileupload')[0]
                         }).always(function () {
                             $(this).removeClass('fileupload-processing');
                 
                         }).done(function (result) {
                             $('button[type="resize_all"]').trigger('click');
                         });
                     } 
                 }, 
             }); 
         })
 
         if(module_sugar_grp1 == 'AOS_Quotes') {
             setTimeout(function() {
                 var address_site = $("#install_address_c").val()+','+$("#install_address_city_c").val()+','+$("#install_address_state_c").val()+','+$("#install_address_postalcode_c").val();  
                 var group_address_install = '<div id="group_address_site_detail" class="col-xs-12 edit-view-row-item col-sm-6">';
                 group_address_install += '<fieldset> <legend> Site Address </legend></fieldset>';
                 group_address_install += '</div>';
                 $(".image_site_detail").children().before(group_address_install);
                 $("#install_address_c").before(
                     '<div style="background-color: white;border:1px solid;display:none;position:absolute; padding:3px;margin-top:-16px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_site" class="show-open-map hide_map">'+
                         '<ul>'+
                         '<li><a style="cursor:pointer;" onclick="openSiteMap(); return false;">Open Map</a></li>'+
                         '<li><a style="cursor:pointer;"  href="http://maps.nearmap.com?addr='+ address_site +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                         '</ul>'+
                     '</div>'
                 );
                 $("#install_address_c").parent().parent().parent().children().each(function(index,item){
                    
                     if(index >=10 && index <= 28){
                         //console.log($(this))
                             if($(this).has('div').length){
                                 $(this).removeClass('col-sm-6').detach().appendTo("#group_address_site_detail");
                             }else{
                                 $(this).hide();
                             }
                     }
                 });
                 $(document).find('#check_addr_site_detail_c').parent().detach().appendTo("#group_address_site_detail").show();
                //  $('body').find('#distance_to_electrician_c').closest('.edit-view-row-item').after('<div class="clear"></div><div class="col-xs-12 col-sm-6 edit-view-row-item"></div><div id="div_for_distance_to_sg_c"></div>');
                 $('body').find('#distance_to_daikin_installer_c').closest('.edit-view-row-item').after('<div class="clear"></div><div class="col-xs-12 col-sm-6 edit-view-row-item"></div><div id="div_for_distance_to_sg_c"></div>');

                 $("#distance_to_sg_c").closest(".edit-view-row-item").detach().appendTo("#div_for_distance_to_sg_c");
             }, 3000)
         }
     //THIEN FIX
         $("body").find("#Upload_Image_Site_Detail").after('<button type="button" id="import_map" class="button" >Import</button>');
         $("#Upload_Image_Site_Detail").before(
             '<div style="background-color: white;border: 1px solid #9E9E9E;position: absolute;padding: 3px 3px 0px 3px;margin-top: 0px;left: 205px;z-index: 999;width: 150px;display:none" id="import_button">'+
                 '<ul>'+
                 '<li><button type="button" id="open_map_google" style= "width:100%" class="button" >Map Google</button></li>'+
                 '<li><button type="button" id="open_nearmap" style= "width:100%" class="button" >Near Map</button></li>'+
                 '</ul>'+
             '</div>'
         );
         $("#import_map").click(function(){
             $('#import_button').fadeToggle()
         });
         //get image from google map 
         $("#open_map_google").click(function(e){
             $(this).closest("#import_button").hide();
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
                        //VUT - S - add street view
                        $('#div_street_view').remove();
                        var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                        $('#maptemplate-img').after('<div class="col-md-6 col-sm-6 col-xs-6" style="margin-top: 5em;" id="div_street_view"><iframe id="street-view" src="'+urlStreetView+'" height="223"  title="Street View"></iframe></div>');
                        //VUT - E - add street view
                         $.ajax({
                             url: "index.php?entryPoint=Image_Site_Details_Get_From_Google&lat="
                             +  location.lat
                             + "&lng="  + location.lng,
                             type: 'GET',
                             success: function(result) {
                                 $("#Map_Template_Image").hide();
                                 $("#map").hide();
                                 $("#Map_Template_Image").after(result);
                                 $("#download").remove();
                                 $('#import_map').after("<button type='button' class='button'  id='download' onclick='CopyToClipboard()'>Save</button>");  
                                 SUGAR.ajaxUI.hideLoadingPanel();
                             }
                         });
                     }else{
                         SUGAR.ajaxUI.hideLoadingPanel();
                     }
     
                 }
             }); 
         });
 
         //get image from nearmap
         $("#open_nearmap").click(function(e){
             $(this).closest("#import_button").hide();
             SUGAR.ajaxUI.showLoadingPanel();
             $("#ajaxloading_mask").css("position",'fixed');
             $.ajax({
                 url: "index.php?entryPoint=Image_Site_Details_Get_From_Nearmap&quoteSG_ID="
                 + $("#solargain_quote_number_c").val()
                 + "&siteDetail_ID="+$("#sg_site_details_no_c").val(),
                 type: 'GET',
                 success: function(result) {
                     $("#Map_Template_Image").hide();
                     $("#map").hide();
                     $("#Map_Template_Image").after(result);
                     $("#download").remove();
                     $('#import_map').after("<button type='button' class='button'  id='download' onclick='CopyToClipboard()'>Save</button>");                            
                     var _DRAGGGING_STARTED = 0;
                     var _LAST_MOUSE_POSITION = { x: null, y: null };
                     var _DIV_OFFSET = $(document).find("#map").offset();
                     var _CONTAINER_WIDTH = $(document).find("#map").outerWidth();
                     var _CONTAINER_HEIGHT =$(document).find("#map").outerHeight();
                     var _IMAGE_WIDTH;
                     var _IMAGE_HEIGHT;
                     var _IMAGE_LOADED = 0;
 
                     // Check whether image is cached or wait for the image to load 
                     // This is necessary before calculating width and height of the image
                     if($(document).find("#drag-image").get(0).complete) {
                     ImageLoaded();
                     }
                     else {
                     $(document).find("#drag-image").on('load', function() {
                         ImageLoaded();
                     });
                     }
 
                     // Image is loaded
                     function ImageLoaded() {
                     _IMAGE_WIDTH = $(document).find("#drag-image").width();
                     _IMAGE_HEIGHT = $(document).find("#drag-image").height();
                     _IMAGE_LOADED = 1;
                     $(document).find("#drag-image").css({ top: 'calc(-50% - 100px)', left: 'calc(-50% - 100px)'});
                     }
 
                     $(document).find("#map").on('mousedown', function(event) {
                     /* Image should be loaded before it can be dragged */
                     if(_IMAGE_LOADED == 1) { 
                         _DRAGGGING_STARTED = 1;
 
                         /* Save mouse position */
                         _LAST_MOUSE_POSITION = { x: event.pageX - _DIV_OFFSET.left, y: event.pageY - _DIV_OFFSET.top };
                     }
                     });
 
                     $(document).find("#map").on('mouseup', function() {
                     _DRAGGGING_STARTED = 0;
                     });
 
                     $(document).find("#map").on('mousemove', function(event) {
                     if(_DRAGGGING_STARTED == 1) {
                         var current_mouse_position = { x: event.pageX - _DIV_OFFSET.left, y: event.pageY - _DIV_OFFSET.top };
                         var change_x = current_mouse_position.x - _LAST_MOUSE_POSITION.x;
                         var change_y = current_mouse_position.y - _LAST_MOUSE_POSITION.y;
 
                         /* Save mouse position */
                         _LAST_MOUSE_POSITION = current_mouse_position;
 
                         var img_top = parseInt($(document).find("#drag-image").css('top'), 10);
                         var img_left = parseInt($(document).find("#drag-image").css('left'), 10);
 
                         var img_top_new = img_top + change_y;
                         var img_left_new = img_left + change_x;
 
                         /* Validate top and left do not fall outside the image, otherwise white space will be seen */
                         if(img_top_new > 0)
                         img_top_new = 0;
                         if(img_top_new < (_CONTAINER_HEIGHT - _IMAGE_HEIGHT))
                         img_top_new = _CONTAINER_HEIGHT - _IMAGE_HEIGHT;
 
                         if(img_left_new > 0)
                         img_left_new = 0;
                         if(img_left_new < (_CONTAINER_WIDTH - _IMAGE_WIDTH))
                         img_left_new = _CONTAINER_WIDTH - _IMAGE_WIDTH;
 
                         $(document).find("#drag-image").css({ top: img_top_new + 'px', left: img_left_new + 'px' });
                     }
                     });
                     SUGAR.ajaxUI.hideLoadingPanel();
                 }
             });
         });
          
     //END
 
     //dung code-- popup template Specail Notes
     $( "#dialog_special_notes" ).dialog({
         autoOpen: false,
         width: 712,
         height:478,
         buttons: {
             Save: function(){
                 SUGAR.ajaxUI.showLoadingPanel();
                 $("#ajaxloading_mask").css("position",'fixed');
                 //create new
                 if($("#id_template").val() == '') {
                     if($("#title_special_template").val() == ''){
                         alert('Could you insert title please?');
                         SUGAR.ajaxUI.hideLoadingPanel();
                         return false;
                     };
                     $.ajax({
                         url: 'index.php?entryPoint=CRUD_Special_Notes' ,
                         type: 'POST',
                         data: 
                         {
                             id: $("#id_template").val(),
                             action: 'create',
                             content: encodeURIComponent($("#content_template").val()),
                             title: encodeURIComponent($("#title_special_template").val())
                         },
                         success: function(result) {              
                             render_select_template(result);
                             SUGAR.ajaxUI.hideLoadingPanel();
                         }
                     }); 
                 }   
                 //update
                 else{
                     $.ajax({
                         url: 'index.php?entryPoint=CRUD_Special_Notes' ,
                         type: 'POST',
                         data: 
                         {
                             id: $("#id_template").val(),
                             action: 'update',
                             content: encodeURIComponent($("#content_template").val()),
                             title: encodeURIComponent($("#title_special_template").val())
                         },
                         success: function(result) {                         
                             render_select_template(result);
                             SUGAR.ajaxUI.hideLoadingPanel();
                         }
                     }); 
                 }
                 $("#special_notes_c").val( $("#content_template").val());  
                 $(this).dialog('close');
             },
             Create: function(){
                 $("#id_template").val('');
                 $("#title_special_template").val('');
                 $("#content_template").val('');
             },
             Insert: function(){
                 $("#special_notes_c").val( $("#content_template").val());      
                 $(this).dialog('close');
             },
             Delete: function(){
                 var ok = confirm('Do you want delete Template !');
                 if (ok){
                     SUGAR.ajaxUI.showLoadingPanel();
                     $("#ajaxloading_mask").css("position",'fixed');
                     $.ajax({
                         url: 'index.php?entryPoint=CRUD_Special_Notes' ,
                         type: 'POST',
                         data: 
                         {
                             id: $("#id_template").val(),
                             action: 'delete',
                             content: encodeURIComponent($("#content_template").val()),
                             title: encodeURIComponent($("#title_special_template").val())
                         },
                         success: function(result) {
                            if(data == '' && typeof data == undefined)return;                         
                             render_select_template(result);
                             SUGAR.ajaxUI.hideLoadingPanel();
                             $("#content_template").val('');
                             $("#title_special_template").val('');
                             $("#id_template").val('');
                         }
                     }); 
                 }
             },
             Cancel: function(){
                 $(this).dialog('close');
             },
         }
     });
     $('#special_notes_c').parent().siblings('.label').append('<br> <button class="button primary" id="dialog_special_notes_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Edit Templates</button>');
 
     $("#dialog_special_notes_button").click(function(e){
         SUGAR.ajaxUI.showLoadingPanel();
         $("#ajaxloading_mask").css("position",'fixed');
         $.ajax({
             url: 'index.php?entryPoint=CRUD_Special_Notes' ,
             type: 'POST',
             data: 
             {
                 action: 'read',
             },
             async: true,
             success: function(result) { 
                if(data == '' && typeof data == undefined){SUGAR.ajaxUI.hideLoadingPanel();return;}                      
                 render_select_template(result);
                 SUGAR.ajaxUI.hideLoadingPanel();
                 $( "#dialog_special_notes" ).dialog("open");
             }
         }); 
         return false;
     })
 
     $('#select_title_template_special_notes').change(function(){
         var id = $('#select_title_template_special_notes').val();
         if(id == '') return false;
         var title = $('#select_title_template_special_notes option:selected').text();
         $("#title_special_template").val(title);
         $("#id_template").val(id);
         $("#content_template").val(window.data_special_notes[id].content);
     });
     //end dung code-- popup template Specail Notes
     });
 
     //thienpb code -- check quote sg type
     check_sg_quote_type($("#quote_type_c").val());
     $("#quote_type_c").change(function(){
         check_sg_quote_type($(this).val());
     });
 
     //thienpb code  -- show button preview pdf
     $(document).on('click','#preview_pdf',function(){
         var quote_type = '';
         if($("#quote_type_c").val() == 'quote_type_solar'){
             quote_type = 'quote';
         }else if($("#quote_type_c").val() == 'quote_type_tesla'){
             quote_type = 'tesla';
         }
         if(quote_type != ''){
             SUGAR.ajaxUI.showLoadingPanel();
             $.ajax({
                 url: 'index.php?entryPoint=CustomDownloadPDF&module=AOS_Quotes&type='+quote_type+'&record_id='+$("input[name='record']").val()+'&preview=true&folder_id='+$("input[name='pre_install_photos_c']").val(),
                 async: true,
                 success: function(result) {
                    if(data == '' && typeof data == undefined){SUGAR.ajaxUI.hideLoadingPanel();return;}
                     var data = $.parseJSON(result);                      
                     $(".modal_preview_pdf").remove();
                     var html = '<div class="modal fade modal_preview_pdf" tabindex="-1" role="dialog">'+
                                     '<div class="modal-dialog" style="width:60%">'+
                                         '<div class="modal-content">'+
                                             '<div class="modal-header" style="padding:5px;">'+
                                                 '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                 '<h4 class="modal-title" id="title-generic"><center>'+data['pdf_file']+'</center></h4>'+
                                             '</div>'+
                                             '<div class="modal-body" style="padding:0px;">'+
                                                 '<embed style="height:calc('+$('body').height()+'px - 50px);width:100%;" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+data['generate_ID']+'/'+encodeURIComponent(data['pdf_file'])+'" type="application/pdf"  />'+
                                             '</div>'+
                                         '</div>'+
                                     '</div>'+
                                 '</div>';
                     $("body").append(html);
                     $(".modal_preview_pdf").modal('show');
                     SUGAR.ajaxUI.hideLoadingPanel();
                 }
             });
         }
     });
 
     //thienpb code -- API Generate to quote design
//      $("#btn_view_change_log").before('<button style="margin: 10px 0;background:#945596;" type="button" id="btn_quote_design_tool" class="button btn_quote_design_tool" title="Quote Design Tool">Quote Design Tool</button>');
//      $("#btn_quote_design_tool").click(function(e) {
//          if($("#quote_type_c").val() != "quote_type_solar"){
//              alert("Quote type isn't a solar!");
//              return false;
//          }
//          var dsg_lat,dsg_lng;
//          $.ajax({
//              url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' 
//              + encodeURIComponent($('#install_address_c').val()) +", " 
//              + encodeURIComponent($('#install_address_city_c').val())  + ", " 
//              + encodeURIComponent($('#install_address_state_c').val())  
//              + ", " +  encodeURIComponent($('#install_address_postalcode_c').val()) 
//              + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
//              type: 'GET',
//              success: function(result) {
//                  var location = result.results[0].geometry.location;
//                  if (result.status == "OK"){
//                      dsg_lat = location.lat;
//                      dsg_lng = location.lng;
//                  }else{
//                      alert("Address invalid! Please check Quote address.")
//                  }
//              }
//          });
//          if($("#billing_account_id").val() == ''){
//              alert("Account is invalid for create quote design tool.");
//              return;
//          }
//          $.ajax({
//              type: "GET",
//              cache: false,
//              url: "?entryPoint=getLeadFromAccount&account_id="+$("#billing_account_id").val()+'&lead_id='+$("#leads_aos_quotes_1leads_ida").val(),
//          }).done(function (data) {
//             if(data == '' && typeof data == undefined)return;
//              var json = $.parseJSON(data);
//              if(json.id == ''){
//                  alert("Please check acount field or lead source field!");
//                  return false;
//              }
//              var roof_type_arr ={"Tin"       :2,
//                              "Tile"          :3,
//                              "klip_loc"      :4,
//                              "Concrete"      :5,    
//                              "Trim_Deck"     :6,
//                              "Insulated"     :7,
//                              "Asbestos"      :8,
//                              "Ground_Mount"  :9,
//                              "Terracotta"    :10,
//                              "Other"         :1};
//              var data_quote = {
//                  "LeadID"        : parseInt(json.number),
//                  "ID"            : parseInt($("div[field='number']").text().trim('')),
//                  "Name"          : html_entity_decode((json.first_name +' '+json.last_name)),
//                  "FirstName"     : html_entity_decode(json.first_name),
//                  "LastName"      : html_entity_decode(json.last_name),
//                  "Mobile"        : html_entity_decode(json.phone_number),
//                  "Email"         : html_entity_decode(json.email),
//                  "Address"       :{
//                      "ID"                    :1,
//                      "Street1"               :html_entity_decode($("#install_address_c").val()),
//                      "Locality"              :html_entity_decode($("#install_address_city_c").val()),
//                      "State"                 :html_entity_decode($("#install_address_state_c").val()),
//                      "PostCode"              :parseInt(html_entity_decode($("#install_address_postalcode_c").val())),
//                      "Street1NotProvided"    :false,
//                      "LocalityNotProvided"   :false,
//                      "Latitude"              :dsg_lat,
//                      "Longitude"             :dsg_lng,
//                      "Value"                 :$("#install_address_c").val()+', '+$("#install_address_city_c").val()+' '+$("#install_address_state_c").val()+' '+$("#install_address_postalcode_c").val(),
//                  },
//                  "RoofType"     : encodeURIComponent(roof_type_arr[$("#roof_type").val()]),
//                  "CustomerTypeID": parseInt($("#customer_type_c").val()),
//                  "BuildHeight"   : $("#gutter_height_c").val(),
//                  "EnergyRetailer": $("#energy_retailer_c").val(),
//                  "NetworkOperator"   : $("#distributor_c").val(),
//                  "ConnectionType": $("#connection_type_c").val(),
//                  "Photo"         : {
//                      "Name"      : "Image_Site_Detail.jpg",
//                      "Data"      : $("#Map_Template_Image").attr("src"),
//                  }
//              }
//              $.ajax({
//                  type: "GET",
//                  cache: false,
//                  url: "http://loc.designtools.com/APIv2/SAM_API.php?query=quotes/"+$("div[field='number']").text().trim('')+'&data_quote='+JSON.stringify(data_quote),
//                  //url: "https://designtool.pure-electric.com.au/APIv2/SAM_API.php?query=quotes/"+$("div[field='number']").text().trim('')+'&data_quote='+JSON.stringify(data_quote),
//                  success: function(result) {
//                      if(result == 'done'){
//                          $("#link_quote_design").remove();
//                          window.open("http://loc.designtools.com/quote.php?quoteID="+$("div[field='number']").text().trim(''));
//                          //window.open("https://designtool.pure-electric.com.au/quote.php?quoteID="+$("div[field='number']").text().trim(''));
//                      }
//                  }
//              })
 
//          });
//      });
//      //end
     //thienpb - code button get dnsp approval_number_c
     $("body").find("#dnsp_approval_number_c").after("<button type='button' class='button' id='get_dnsp_approval' name='get_dnsp_approval'>GET DNSP Approval</button>")
     $("body").on("click","#get_dnsp_approval", function(e){
        var nmi_number_meter =  $("#nmi_c").val();
        var distributor_meter =  $("#distributor_c").val();
        var dnsp_number = $("#dnsp_approval_number_c").val();
        var record_id = $("input[name='record']").val();

        if($("#phases").val() == ''){
           alert("Please select Meter Pharse");
           $("html, body").animate({
               scrollTop: $("#phases").offset().top - 300
           }, 1000);
            return;
        }
        var meter_phase_c = (($("#phases").val() == 'Three Phases' && $("#phases").val() != 'Unsure') ? 3 : (($("#phases").val() == 'Single Phase') ? 1 : (($("#phases").val() != 'Unsure') ? 2 : ''))) ;

        if(distributor_meter == 4 ||  distributor_meter == 6){
           SUGAR.ajaxUI.showLoadingPanel();
           $.ajax({
               url: "/index.php?entryPoint=customGetMeter&dnsp="+dnsp_number+"&nmi_number=" + nmi_number_meter +"&record="+record_id+"&meter_phase_c="+meter_phase_c+"&type=GET_DNSP",
               type: 'GET',
                success: function(data)
                {
                    if(data != '' && typeof data !== "undefined"){
                        SUGAR.ajaxUI.hideLoadingPanel();
                        $("#dnsp_approval_number_c").val(data);
                        $(".files").empty();
                         $.ajax({
                             url: $('#fileupload').fileupload('option', 'url'),
                             dataType: 'json',
                             context: $('#fileupload')[0]
                         }).always(function () {
                             $(this).removeClass('fileupload-processing');
                 
                         }).done(function (result) {
                             $('button[type="resize_all"]').trigger('click');
                         });
                    }else{
                        //tuan cope thien code ===================
                        SUGAR.ajaxUI.hideLoadingPanel();
                        $(".modal_meter_number").remove();
                        var html = '<div class="modal fade modal_meter_number" tabindex="-1" role="dialog">'+
                                        '<div class="modal-dialog">'+
                                            '<div class="modal-content">'+
                                                '<div class="modal-header">'+
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                    '<h5 class="modal-title" id="title-generic"><center>The address you have nominated cannot be found in our system. Please check your address and Search again.</center></h5>'+
                                                '</div>'+
                                                '<div class="modal-body">'+
                                                    '<div class="container-fluid" style="margin-left:30px;font-size:13px;text-align:center;"><a target="_blank" href="https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage"> Citipower Powercor\'s Link</a>'
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                        $("body").append(html);
                        $(".modal_meter_number").modal('show');
                        // tuan and ==============================
                        //alert ("The address you have nominated cannot be found in our system. Please check your address and Search again.");
                    }
                },
                error: function(response){
                    alert('Get DNSP Number Fail! Please check NMI Number OR METER Number and try to get it again.');
                    SUGAR.ajaxUI.hideLoadingPanel();
                }
            })
        }else{
            alert("Please sure Distributor option is 'Citipower' OR 'Powercor'");
            SUGAR.ajaxUI.hideLoadingPanel();
        }
     })
 });
 $( window ).load(function() {
     // auto import image map
     $("#check_addr_site_detail_c").prop('checked', true);
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
                     $('#div_street_view').remove();
                     var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                     $('#maptemplate-img').after('<div class="col-md-6 col-sm-6 col-xs-6" style="margin-top: 5em;" id="div_street_view"><iframe id="street-view" src="'+urlStreetView+'" height="223"  title="Street View"></iframe></div>');
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
                             // $('#import_map').after("<button type='button' class='button'  id='download' onclick='CopyToClipboard()'>Save</button>");                            
                         }
                     }).done(function(data){
                         setTimeout(function(){ 
                             CopyToClipboard();
                         },5000);
                     });
                 }else{
                     SUGAR.ajaxUI.hideLoadingPanel();
                 }
             }
         });
     } else {
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
                    $('#div_street_view').remove();
                    var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                    $('#maptemplate-img').after('<div class="col-md-6 col-sm-6 col-xs-6" style="margin-top: 5em;" id="div_street_view"><iframe id="street-view" src="'+urlStreetView+'" height="223"  title="Street View"></iframe></div>');
                }
            }
        });

     }
     // $('#import_map').trigger('click'); 
     // $('#open_map_google').trigger('click');
     // setTimeout(function(){ 
     //     $('body').find('#download').trigger('click');
     // },6000);
 
     ///
 });


 /// THIENPB CODE MERGE GROUP GET DISTANCE BUTTON
 $( window ).load(function() {
    $('#distance_to_travel_c').closest('.edit-view-row-item').prepend('<div><button class="button primary" type="button" id="getDistance"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get All Distance </button><button style="margin: 0px 2px;" class="button primary" type="button" id="getDistance_selected"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Distance Selected</button></div>');
    //VUT - S - button GET DISTANCE SELECTED
    $(document).on('focus', '#plumber_electrician_c, #plumber_new_c, #daikin_installer_c', function(){
        window.accountFocus = $(this).siblings('input[type=hidden]');
    });
    $(document).on('click', '#getDistance_selected', function () {
        $('#getDistance_selected span.glyphicon-refresh').removeClass('hidden');
        let accFocus = window.accountFocus;
        let accFocus_idElement = accFocus.attr('id');
        let account_id = $(`#${accFocus_idElement}`).val();
        let field_distance_idElement;
        let distance_selected = get_distance_by_account_id(account_id);
        switch (accFocus_idElement) { 
            case 'account_id4_c': //daikin_installer_c
                field_distance_idElement = 'distance_to_daikin_installer_c';
                break;
            case 'account_id2_c': //plumber_electrician_c
                field_distance_idElement = 'distance_to_electrician_c';
                break;
            case 'account_id3_c': //plumber_new_c
                field_distance_idElement = 'distance_to_travel_c';
                break;
            default:
                break;
        }
        if (typeof(distance_selected) == 'string') {
            $("#"+field_distance_idElement).val(`${distance_selected}`);
        } else {
            $("#"+field_distance_idElement).val(`${distance_selected} km`);
        }
        $('#getDistance_selected span.glyphicon-refresh').addClass('hidden');
    });
    //VUT - E - button GET DISTANCE SELECTED
    $(document).on("click","#getDistance",function(){
        var from_address =  $("#install_address_c").val() +", " + 
                            $("#install_address_city_c").val() + ", " + 
                            $("#install_address_state_c").val() + ", " + 
                            $("#install_address_postalcode_c").val();
        SUGAR.ajaxUI.showLoadingPanel();
        getDistances(from_address);
    });
});

function renderShortest(info,field_distance_id,field_account_name,field_account_id){
    $("#"+field_distance_id).after("<b class='suggest'> Suggest: </b><br/>");
    $("#"+field_distance_id).after("</br><b class='shortest'> Shortest: </b><br/><b class='shortest-suggest'></b>");
    info.sort(function(a,b){
        return a.distance - b.distance;
    });
    for ( var i = 0 ; i < 3 ; i++){  
        var addr = info[0][0];
        var name_lum =info[0][1];
        var id_nearest = info[0][2];
        var str_dis =  info[0][3];

        $("#"+field_distance_id).nextAll('.shortest-suggest').html('<a class="selected-suggest" dist="'+str_dis+'" rel="'+addr+'" href="#">'+name_lum+': '+addr+':<span style="color:green">'+str_dis+'</span></a> <br>');
        $("#"+field_distance_id).val(str_dis);
        $('#'+field_account_name).val(name_lum);
        $('#'+field_account_id).val(id_nearest);
        $("#"+field_distance_id).nextAll('.suggest').append('<a class="selected-suggest" dist="'+info[i][3]+'" rel="'+info[i][0]+'" href="#">'+info[i][1]+': '+info[i][0]+':<span style="color:green">'+info[i][3]+'</span></a> <br>');
    }  
}
function getDistances(from_address){
    setTimeout(() => {
        var solargain_address = {
            "2":"Unit 7, 88 Dynon Road, West Melbourne VIC 3003",
            "14":"963/1002 Grand Junction Road, Holden Hill SA 5088",
            "0":"10 Milly Court, Malaga WA 6090",
            "1":"Unit 2, 7 Beale Way, Rockingham WA 6168",
            "3":"Unit 1, 5-7 Imboon Street, Deception Bay QLD 4508",
            "4":"21C Richmond Road, Homebush NSW 2140",
            "5":"244 Fitzgerald Street, Northam WA 6401",
            "6":"117 Lockyer Avenue, Albany WA 6330",
            "7":"Unit 2, 18 Bourke Street, Bunbury WA 6230",
            "8":"25 Wright Street, Busselton WA 6280",
            "9":"Lot 10 Reg Clarke Road, Geraldton WA 6530",
            "10":"23-49 Parfitt Road, Wangaratta VIC 3676",
            "11":"Shed 16B, 22 Walsh Road, Warrnambool VIC 3280",
            "12":"Unit 7, 8-10 Boat Harbour Drive, Pialba QLD 4655",
            "13":"14 Ipswich St, Fyshwick ACT 2609"
        }
    
        var selected_offices = $("#solargain_offices_c").val();
        var to_address = solargain_address[selected_offices];//"7/88 Dynon Road, West Melbourne VIC 3003";
        var suggest_address = [];
        for (var key in solargain_address) {
            suggest_address.push(solargain_address[key]);
        };
    
    
        // Solve suggest
        var shortest = 0;
        var option_office_shortest = '';
        var promises = [];
        if($("#quote_type_c").val() == 'quote_type_solar'){
            $("#distance_to_sg_c").after("<b class='suggest'> Suggest: </b><br/>");
            $("#distance_to_sg_c").after("<br/><b class='shortest'> Shortest: </b><br/><b class='shortest-suggest'></b>");
            for ( var i = 0; i < suggest_address.length; i ++){
                var addr = suggest_address[i];
                promises.push(
                    $.ajax({
                        url: "/index.php?entryPoint=customDistance&address_from=" + from_address + "&address_to=" + addr,
                        type: 'GET',
                        //async: false,
                        success: function(data){
                            try {
                                var jsonObject = $.parseJSON(data);
                                var l_distance = parseFloat(jsonObject.routes[0].legs[0].distance.text.replace(/[^\d.-]/g, ''));
                                var addr = jsonObject.toAddress;
                                if (shortest == 0 || shortest > l_distance){
                                    shortest = l_distance
                                    $("#distance_to_sg_c").nextAll('.shortest-suggest').html('<a class="selected-suggest" dist="'+jsonObject.routes[0].legs[0].distance.text+'" rel="'+addr+'" href="#">'+addr+':<span style="color:green">'+jsonObject.routes[0].legs[0].distance.text+'</span></a> <br>');
                                    //dung code - new logic -auto fill short disance 
                                    $("#distance_to_sg_c").val(shortest+' km');
                                    option_office_shortest = addr;
                                    $("#Solar-PV-Pricing input[id^=travel_km_]").val(shortest);
                                }
                                $("#distance_to_sg_c").nextAll('.suggest').next().after('<a class="selected-suggest" dist="'+jsonObject.routes[0].legs[0].distance.text+'" rel="'+addr+'" href="#">'+addr+':<span style="color:green">'+jsonObject.routes[0].legs[0].distance.text+'</span></a> <br>');
                            } catch (error) {
                                console.log(error);
                            }     
                        }, error: function(response){
                            alert("Cannot get distance");
                        },
                    })
                );
            }
        }
        /// Inpormiss
       
            Promise.all(promises).then(responseList => {
                if($("#quote_type_c").val() == 'quote_type_solar'){
                    $("#solargain_offices_c").val($('option[label="'+option_office_shortest+'"]').val());
                    if(shortest > 100){
                        alert('Distance to installer >100 km seek advice as to whether to proceed with quote');
                    }
                    SUGAR.ajaxUI.hideLoadingPanel();
                }else{
                    $.ajax({
                        url: "?entryPoint=customFilterPlumber&address_from=" + from_address,
                        success: function (data) {
                            try {
                                var infor = $.parseJSON(data);
                                renderShortest(infor['daikin_instaler'],'distance_to_daikin_installer_c','daikin_installer_c','account_id4_c');
                                renderShortest(infor['electrician'],'distance_to_electrician_c','plumber_electrician_c','account_id2_c')
                                renderShortest(infor['plumber'],'distance_to_travel_c','plumber_new_c','account_id3_c');
                                SUGAR.ajaxUI.hideLoadingPanel();
                            } catch (error) {
                                console.log(error);
                                SUGAR.ajaxUI.hideLoadingPanel();

                            }
                        }
                    });
                }
            })
    }, 1000);
    
}
 /// END
////Comment function (copied to Invoice)
//  function get_distance_by_account_id(id_account){
//     if(id_account == '') return '';
//     if( $('#install_address_c').val() == "" ){  
//         var from_address =  $("#billing_address_street").val() +", " +
//                             $("#billing_address_city").val() + ", " +
//                             $("#billing_address_state").val() + ", " +
//                             $("#billing_address_postalcode").val();
     
//     }else {
//         var from_address =  $("#install_address_c").val() +", " +
//                             $("#install_address_city_c").val() + ", " +
//                             $("#install_address_state_c").val() + ", " +
//                             $("#install_address_postalcode_c").val();
//     }
//     var result_distance = '';
//     $.ajax({
//         url: "/index.php?entryPoint=getdistance_Flum_or_Elec_to_Suite&ac_id="+id_account,
//         type: 'GET',
//         async:false,
//         success: function(data)
//             {
//                 if(data == ', , , '){
//                     alert('Sorry! - Not see address');
//                 }else {       
//                     $.ajax({
//                             url: "/index.php?entryPoint=customDistance&address_from=" + from_address + "&address_to=" + data,
//                             type: 'GET',
//                             async:false,
//                             success: function(result)
//                             {
//                                 try {
//                                     var jsonObject = $.parseJSON(result);
//                                     var l_distance = parseFloat(jsonObject.routes[0].legs[0].distance.text.replace(/[^\d.-]/g, ''));
//                                     result_distance = l_distance;
//                                 } catch (error) {
//                                     result_distance = 'not found';
//                                 }
                                
//                             } 
                        
//                     });
//                 }
//             },
//     })
//     return result_distance;
// };

 function showPopup(){
     $('#popup_image_site_detail').show();
 }
 function hidePopup(){
     $('#popup_image_site_detail').hide();
 }
 function CopyToClipboard(){
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
                 var generateUUID = $('input[name="pre_install_photos_c"]').val();
                 $.ajax({
                     type: "POST", 
                     url: "index.php?entryPoint=Image_Site_Details_Clipboard_Popup", 
                     data: { img: image_data, id: generateUUID}      
                     }).done(function(data){
                         var folder_id  = $('body').find('input[name="pre_install_photos_c"]').val();
                         debugger
                         $('body').find("#Map_Template_Image").remove();
                         $('body').find("#nearmap").remove();
                         var image_html = '<img id="Map_Template_Image" onclick="showPopup()" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:220px;height:auto;margin-top: 5em;margin-bottom:5px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime()+'">';
                         $('body').find("#maptemplate-img").append(image_html);
                         document.getElementById("Map_Template_Image_popup").src = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime();
                         //reload list files
                         $(document).find("#map").remove();
                         $(document).find("#download").remove();
                         $('#install_address_c').focus();
                        
                     });
                 document.body.removeChild(canvas);
             }
         });
     },100);
 }
 
 
 function get_data_from_SV(datatype){
     SUGAR.ajaxUI.showLoadingPanel();
     $("#ajaxloading_mask").css("position",'fixed');
     setTimeout(function(){
         if($("#slv_solar_vic_id_c").val() != '' && $("#slv_solar_vic_id_c").val() != '/'){
             var url_get = "?entryPoint=getDataSolarVIC&solarvicID="+$("#slv_solar_vic_id_c").val().split('/')[0]+"&type="+datatype;
             $.ajax({
                 url: url_get,
                 type: "GET",
                 // async:false,
                 success:function(data){
                     if(data){
                         var data_return = $.parseJSON(data);
                         if(data_return['error'] != ''){
                             alert(data_return['error']);
                         }else{
                             var solarVIC = data_return['data'];
                             if(datatype == 'status'){
                                 $("#slv_status_c").val(solarVIC['Status__c']['displayValue']);
                             }else{
                                 // $("#slv_firstname_c").val(solarVIC['First_Name__c']['value'] ? solarVIC['First_Name__c']['value'] : '');
                                 // $("#slv_lastname_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#slv_email_c").val(solarVIC['Email__c']['value'] ? solarVIC['Email__c']['value'] : '');
                                 // // $("#slv_ebate_type_c option:selected").attr('label');
                                 // $("#slv_installation_address_c").val(solarVIC['Property_Address__c']['value'] ? solarVIC['Property_Address__c']['value'] : '');
                                 // $("#slv_panel_type_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#slv_inverter_type_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#slv_total_panel_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#customer_benefits_c").prop("checked");
                                 // $("#estimate_energy_yield_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#estimated_financial_saving_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#slv_quote_sg_number_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#slv_total_price_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#slv_estimated_value_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#slv_estimated_rebate_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#slv_estimated_free_loan_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $("#slv_net_payable_c").val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $('input[name="record"]').val(solarVIC['Last_Name__c']['value'] ? solarVIC['Last_Name__c']['value'] : '');
                                 // $('#slv_interested_solar_loan_c').prop("checked");
                                 // $('#slv_dnsp_approval_c').prop("checked");
                                 //$("#slv_status_c").val(solarVIC['Status__c']['value']);
                             }
                         }
                         SUGAR.ajaxUI.hideLoadingPanel();
                     }else{
                         alert("Please fill Solar VIC ID for get data.");
                         SUGAR.ajaxUI.hideLoadingPanel();
                     }
                     
                 }
             });
         }else{
             alert("Please fill Solar VIC ID for get data.");
             SUGAR.ajaxUI.hideLoadingPanel();
         }
     },100);
 }
 
 function render_select_template(result){
     var data_result = $.parseJSON(result);
     window.data_special_notes = data_result;
     $('#select_title_template_special_notes').empty();
     $('#select_title_template_special_notes').append($('<option>', {
         value: '',
         text: ''
     }));
     $.each(data_result,function(k,v){
         $('#select_title_template_special_notes').append($('<option>', {
             value: k,
             text: v.title
         }));
     });
 }
 
 //Thienpb optimate code and change logic for solargain quote number
 function SolarGainLeadNumberLink() {
     if ($('#solargain_lead_number_c').val() == "") return;
     var href = "<div class='open-solargain--number'>Open SolarGain Lead <a target='_blank' href='https://crm.solargain.com.au/lead/edit/" + $('#solargain_lead_number_c').val()+"'>https://crm.solargain.com.au/lead/edit/" + $('#solargain_lead_number_c').val() + "</a></div>";
     $('#solargain_lead_number_c').siblings().empty();
     $('#solargain_lead_number_c').parent().append(href);
 }
 
 function SolarGainQuoteNumberLink() {
     if ($('#solargain_quote_number_c').val() == "") return;
     var href = "<div class='open-solargain-quote-number'>Open SolarGain Quote <a target='_blank' href='https://crm.solargain.com.au/quote/edit/" + $('#solargain_quote_number_c').val()+"'>https://crm.solargain.com.au/quote/edit/" + $('#solargain_quote_number_c').val() + "</a>";
     href += "<br>Change Owner <a id='changeowner_matt' href='javascript:void(0);addButton_owner();$(\"#sg_assigned_user_c\").val(\"Matthew Wright\");$(\"#user_id2_c\").val(\"8d159972-b7ea-8cf9-c9d2-56958d05485e\");$(\"#change_Owner\").trigger(\"click\");'>MW</a> <a id='changeowner_matt' href='javascript:void(0);addButton_owner();$(\"#sg_assigned_user_c\").val(\"Paul Szuster\");$(\"#user_id2_c\").val(\"61e04d4b-86ef-00f2-c669-579eb1bb58fa\");$(\"#change_Owner\").trigger(\"click\");'>PS</a>";
     href += "<br><button type='button' class='button' id='preview_pdf'>Preview Quote PDF</button></div>";
     $('.open-solargain-quote-number').remove();
     $('.open-solargain-quote-tesla-number').remove();
     $('#solargain_quote_number_c').parent().append(href);
 }
 
 function SolarGainQuoteNumberLinkTesla() {
     if ($('#solargain_tesla_quote_number_c').val() == "") return;
     var href = "<div class='open-solargain-quote-tesla-number'>Open SolarGain Quote Tesla <a target='_blank' href='https://crm.solargain.com.au/quote/edit/" + $('#solargain_tesla_quote_number_c').val()+"'>https://crm.solargain.com.au/quote/edit/" + $('#solargain_tesla_quote_number_c').val() + "</a>";
     href += "<br>Change Owner <a id='changeowner_matt' href='javascript:void(0);addButton_owner();$(\"#sg_assigned_user_c\").val(\"Matthew Wright\");$(\"#user_id2_c\").val(\"8d159972-b7ea-8cf9-c9d2-56958d05485e\");$(\"#change_Owner\").trigger(\"click\");'>MW</a> <a id='changeowner_matt' href='javascript:void(0);addButton_owner();$(\"#sg_assigned_user_c\").val(\"Paul Szuster\");$(\"#user_id2_c\").val(\"61e04d4b-86ef-00f2-c669-579eb1bb58fa\");$(\"#change_Owner\").trigger(\"click\");'>PS</a>";
     href += "<br><button type='button' class='button' id='preview_pdf'>Preview Quote PDF</button></div>";
     $('.open-solargain-quote-number').remove();
     $('.open-solargain-quote-tesla-number').remove();
     $('#solargain_tesla_quote_number_c').parent().append(href);
 }
 
 function check_sg_quote_type(quote_type){
     var solargain_lead_number_c_detach = $("#solargain_lead_number_c").closest(".edit-view-row-item").detach();
     if(quote_type == 'quote_type_solar'){
         $("#solargain_quote_number_c").closest(".edit-view-row-item").show().next().show();
         $("#solargain_tesla_quote_number_c").closest(".edit-view-row-item").hide();
         solargain_lead_number_c_detach.appendTo( $("#solargain_quote_number_c").closest(".edit-view-row-item").next());
         SolarGainQuoteNumberLink();
     }else if(quote_type == 'quote_type_tesla'){
         $("#solargain_quote_number_c").closest(".edit-view-row-item").hide().next().hide();
         $("#solargain_tesla_quote_number_c").closest(".edit-view-row-item").show();
         solargain_lead_number_c_detach.appendTo( $("#solargain_tesla_quote_number_c").closest(".edit-view-row-item").next());
         SolarGainQuoteNumberLinkTesla();
     }else{
         $("#solargain_quote_number_c").closest(".edit-view-row-item").hide().next().hide();
         $("#solargain_tesla_quote_number_c").closest(".edit-view-row-item").hide();
         $('.open-solargain-quote-number').remove();
         $('.open-solargain-quote-tesla-number').remove();
     }
 }
 //end

 /**VUT-S-Open Compose view Email Inspection Request */
(function ($) {
    $.fn.openComposeViewModal_Inspection_request = function (source) {
        "use strict";
        var module_name = $(source).attr('data-module');
        var record_id= $(source).attr('data-record-id') ;
        var emailType = $(source).data('email-type');
        if(record_id == ''){
            alert('Please Save before !');
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
        var  email_module  =  $(source).attr('data-module');
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
            // var populateEmailAddress = $(source).attr('data-email-address');
            // // get email address
            // if(typeof(pulateEmailAddress) == 'undefined'){
            //     populateEmailAddress = $('div[field="billing_account"]').find('.phone-number a').attr('data-email-address');
            // }
            
            // if(typeof($(source).attr('data-contact-name')) != 'undefined'){
            //     populateEmailAddress = $(source).attr('data-contact-name') + ' <' + populateEmailAddress + '>';
            // }else if (populateModuleName !== '' && typeof($(source).attr('data-contact-name')) == 'undefined') {
            //     populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            // }
        
            // $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
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
/**VUT-E-Open Compose view Email Inspection Request */

//S- ONLY FOR GP CALCULATION --
$(function() {
    'use strict';
    //VUT-S-Calculate Sanden profit in BILL */

    /**
     * VUT - get PO from related newest Invoice for Quote
     * @param {string} quote_id
     * @param  {array} result : return value
     */
    function getPOforQuote(quote_id) {
        let total_amt;
        $.ajax({
            url: "?entryPoint=GetPOForCalculation&type=gp_profit_quote&quote_id="+quote_id,
            async:false
        }).done(function (data) {
            if(data == '' && typeof data == undefined)return;
            total_amt = JSON.parse(data);
        });
        return total_amt;
    }

    /**
     * VUT-get quantity STCs/VEECs from line item for GP Calculation
     */
    function getSTCsLineItem() {
        let products = $('#line_items_span').find('.product_group').children('tbody');
        let i; 
        let qty={
            STCs : 0,
            VEECs : 0,
        };
        for (i=0;i< products.length; i++) {
            if ($(`#product_name${i}`).val() == 'STCs') {
                qty['STCs'] = Number($(`#product_product_qty${i}`).val().replace(/[^0-9\.]+/g,""));
            }
            if ($(`#product_name${i}`).val() == 'VEECs') {
                qty['VEECs'] = Number($(`#product_product_qty${i}`).val().replace(/[^0-9\.]+/g,""));
            }
        }
        return qty;
    }

    /**
     * VUT - Calculate Sanden Equipment Cost for GP Calculation
     */
    function calculate_equipment_cost_gp() {
        // let sanden_product = [
        //     "GAUS-160FQS",
        //     "GAUS-250FQS",
        //     "GAUS-300FQS",
        //     "GAUS-315FQS",
        // ];
        // let sanden_hpump = [
        //     "QIK15−HPUMP", 
        //     "QIK20−HPUMP",
        // ];
        let lineItems_products = $('#line_items_span').find('.product_group').children('tbody');
        let i; 
        let sanden_groups={};
        //get product in LineItem at Invoice
        for (i=0; i < lineItems_products.length ; i++) {
            let product_partNumber = $(`#product_part_number${i}`).val();
            let product_id = $(`#product_product_id${i}`).val();
            let product_qty = $(`#product_product_qty${i}`).val();
            // if (sanden_product.includes(product_partNumber) || sanden_hpump.includes(product_partNumber)) {
            if (product_partNumber.indexOf("GAUS-") != -1 || product_partNumber.indexOf("−HPUMP") != -1) {
                    if (sanden_groups.hasOwnProperty(product_id)) {
                    sanden_groups[product_id].qty += parseInt(product_qty);
                } else {
                    sanden_groups[product_id] = {
                        'partNumber': product_partNumber,
                        'qty': parseInt(product_qty),
                    };
                }
            }
        }
        //get product's price, cost in AOS_Product
        var total_equipment_cost = 0;

        $.each(sanden_groups, function(key,value){
            SUGAR.ajaxUI.showLoadingPanel();
            let price, cost;
            $.ajax({
                url: "?entryPoint=getProductInfos&type=gp_profit&product_id="+key,
                async:false
            }).done(function (data) {
                if(data == '' && typeof data == undefined)return;
                let jsonObj = JSON.parse(data);
                price = jsonObj.price;
                cost = jsonObj.cost;
            });
            sanden_groups[key].price = parseFloat(price);
            sanden_groups[key].cost = parseFloat(cost);
            total_equipment_cost += value.cost*value.qty;
        });
        SUGAR.ajaxUI.hideLoadingPanel();
        return total_equipment_cost;
    }
    /**
     * VUT - fill input follow line items
     */
    function calculateGP() {
        //STC = $36.55 && VEECs = $30 :: fixed
        var qty = getSTCsLineItem();
        var sanden_STCs_revenue = parseFloat(qty.STCs*36.55);
        var sanden_VEECs_revenue = parseFloat(qty.VEECs*30);
        $('#sanden_stcs').val(sanden_STCs_revenue);
        $('#veec_revenue').val(sanden_VEECs_revenue);
        //field "sanden_supply_bill_c" Sanden Equipment Cost
        if ($('#quote_type_c').val() == 'quote_type_sanden') {
            var sanden_equipment_cost = calculate_equipment_cost_gp();
            $('#sanden_supply_bill').val(sanden_equipment_cost);
        }
        $('#sanden_revenue').val($('#total_amt').val()).trigger('change');
        calculation_gross_profit_sanden_quote();
    }
    /**
     * Check GP Manual 
     */
    function GP_manual() {
        if ($("#gb_manual").is(":checked")) {
            $(fields_auto_fill).removeAttr('readonly').css('background', '#d8f5ee');
            calculation_gross_profit_sanden_quote();
        } else {
            SUGAR.ajaxUI.showLoadingPanel();
            setTimeout(function(){
                $(fields_auto_fill).attr('readonly', 'readonly').css('background', '#ffffff');
                //get po plumb/elec total_amt
                var po_total_amt = getPOforQuote($("input[name='record']").val());
                $('#plumbing_bill').val(po_total_amt['plumb_po']);
                $('#electrician_bill').val(po_total_amt['elec_po']);
                calculateGP();
                SUGAR.ajaxUI.hideLoadingPanel();
            }, 100);
        }
    }

    /**
     * GP Calculation in Quote
     */
    function calculation_gross_profit_sanden_quote(){
        var SandenEquipmentCost = Number($("#sanden_supply_bill").val().replace(/[^0-9\.]+/g,""));
        var SandenShippingCost =  Number($("#sanden_shipping_bill").val().replace(/[^0-9\.]+/g,""));
        var SandenPlumbingInstallationCost = Number($("#plumbing_bill").val().replace(/[^0-9\.]+/g,""));
        var SandenElectricalInstallationCost = Number($("#electrician_bill").val().replace(/[^0-9\.]+/g,""));
        var SubTotalCOSTS  = SandenEquipmentCost+ SandenShippingCost+ SandenPlumbingInstallationCost+SandenElectricalInstallationCost;
        
        var CustomerRevenue= Number($("#sanden_revenue").val().replace(/[^0-9\.]+/g,""));
        var STCRevenue= Number($("#sanden_stcs").val().replace(/[^0-9\.]+/g,""));

        //VUT-Revenue
        var VEECRevenue = Number($("#veec_revenue").val().replace(/[^0-9\.]+/g,""));
        var Solar_Vic_Revenue = Number($("#solar_vic_revenue").val().replace(/[^0-9\.]+/g,""));
        var SA_Reps_Revenue = Number($("#sa_reps_revenue").val().replace(/[^0-9\.]+/g,""));
        //VUT-Revenue
        
        var SubTotalREVENUE = CustomerRevenue + STCRevenue + VEECRevenue + Solar_Vic_Revenue + SA_Reps_Revenue;
       
        var GrossProfit = SubTotalREVENUE - SubTotalCOSTS;
        var GrossProfit_percent = GrossProfit/SubTotalCOSTS*100;
        
        $('#sanden_supply_bill').val(parseFloat(SandenEquipmentCost).formatMoney(2,',','.'));
        $('#sanden_shipping_bill').val( parseFloat(SandenShippingCost).formatMoney(2,',','.'));
        $('#plumbing_bill').val( parseFloat(SandenPlumbingInstallationCost).formatMoney(2,',','.'));
        $('#electrician_bill').val( parseFloat(SandenElectricalInstallationCost).formatMoney(2,',','.'));
        $('#sanden_total_costs').val(parseFloat(SubTotalCOSTS).formatMoney(2,',','.'));
        //VUT-Revenue
        $('#veec_revenue').val( parseFloat(VEECRevenue).formatMoney(2,',','.'));
        $('#solar_vic_revenue').val( parseFloat(Solar_Vic_Revenue).formatMoney(2,',','.'));
        $('#sa_reps_revenue').val( parseFloat(SA_Reps_Revenue).formatMoney(2,',','.'));
        //VUT-Revenue
        
        $('#sanden_revenue').val( parseFloat(CustomerRevenue).formatMoney(2,',','.'));
        $('#sanden_stcs').val( parseFloat(STCRevenue).formatMoney(2,',','.'));
        $('#sanden_total_revenue').val( parseFloat(SubTotalREVENUE).formatMoney(2,',','.'));

        $('#sanden_gross_profit').val( parseFloat(GrossProfit).formatMoney(2,',','.'));
        $('#sanden_gprofit_percent').val(`${parseFloat(GrossProfit_percent).formatMoney(2,',','.')} %`);
    }
    $('#sanden_gross_profit').parent().parent().append('<br><button type="button" class="button" id="calculation_profit_sanden_quote">Calculation Profit Sanden</button>');
    $("#calculation_profit_sanden_quote").on('click',function(){
        GP_manual();
    })
    var string_selector_calculation_sanden = '#sanden_stcs, #sanden_supply_bill,#sanden_shipping_bill,#plumbing_bill,#electrician_bill,#sanden_revenue,#STCRevenue,#veec_revenue, #solar_vic_revenue, #sa_reps_revenue';
    var selector_sub_grofit_sanden = '#sanden_gprofit_percent,#sanden_total_revenue,#sanden_gross_profit,#sanden_total_costs,';
    $(selector_sub_grofit_sanden+string_selector_calculation_sanden).css('width','35%');
    $(string_selector_calculation_sanden).on("change", calculation_gross_profit_sanden_quote);
    //VUT-E-Calculate Sanden profit in BILL */

    //VUT- GP Calculation - update Equipment Cost  && Sanden Customer/STCs/VEEC Revenue when lineItem change
    $('#line_items_span').find('.product_group').on('change', 'input',function() {
        setTimeout(function(){
            calculateGP();
            // //STC = $36.55 && VEECs = $30 :: fixed
            // var qty = getSTCsLineItem();
            // var sanden_STCs_revenue = parseFloat(qty.STCs*36.55);
            // var sanden_VEECs_revenue = parseFloat(qty.VEECs*30);
            // $('#sanden_stcs').val(sanden_STCs_revenue);
            // $('#veec_revenue').val(sanden_VEECs_revenue);
            // //field "sanden_supply_bill_c" Sanden Equipment Cost
            // if ($('#quote_type_c').val() == 'quote_type_sanden') {
            //     var sanden_equipment_cost = calculate_equipment_cost_gp();
            //     $('#sanden_supply_bill').val(sanden_equipment_cost);
            // }
            // $('#sanden_revenue').val($('#total_amt').val()).trigger('change');
            // calculation_gross_profit_sanden_quote();
        }, 100);
    });

    var fields_auto_fill = '#sanden_supply_bill, #plumbing_bill, #electrician_bill, #sanden_total_costs, #sanden_gross_profit, #sanden_revenue, #sanden_stcs, #veec_revenue, #sanden_total_revenue, #sanden_gprofit_percent';
    $(document).on('change', '#gb_manual', function(){
        if ($("#gb_manual").is(":checked")) {
            $(fields_auto_fill).removeAttr('readonly').css('background', '#d8f5ee');
        } else {
            $(fields_auto_fill).attr('readonly', 'readonly').css('background', '#ffffff');
        }
    });
    //load page
    GP_manual();

});

Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,z
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};
//E - ONLY FOR GP CALCULATION --

/**
 * VUT - check product has/hasn't in line item
 * @param {STRING} product  full name product
 */
function checkLineItem(product) {
    let products = $('#lineItems').find('.product_group').children('tbody');
    let i; 
    for (i=0;i< products.length; i++) {
        if ($(`#product_name${i}`).val().toLowerCase() == product.toLowerCase() && products[i].getAttribute('style') != "display: none;") {
            return true;
        }
    }
    return false;
}

