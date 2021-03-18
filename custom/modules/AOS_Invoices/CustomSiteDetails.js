$(document).ready(function(){
    $("#data_json_site_details_c").closest('.panel.panel-default').hide();
    function render_json_data_sitedetails(){
        var data = new Object();
        data.pe_site_details_no_c = $('#pe_site_details_no_c').val();
        data.sg_site_details_no_c = $('#sg_site_details_no_c').val();
        data.solargain_quote_number_c = $('#solargain_quote_number_c').val();
        data.detail_site_install_address_c = $('#detail_site_install_address_c').val();
        data.detail_site_install_address_city_c = $('#detail_site_install_address_city_c').val();
        data.detail_site_install_address_state_c = $('#detail_site_install_address_state_c').val();
        data.detail_site_install_address_postalcode_c = $('#detail_site_install_address_postalcode_c').val();
        data.detail_site_install_address_country_c = $('#detail_site_install_address_country_c').val();
        data.customer_type_c = $("input[name='customer_type_c']:checked").val();
        data.gutter_height_c = $('#gutter_height_c').val();
        data.roof_type_c = $('#roof_type_c').val();
        data.gutter_height_c = $('#gutter_height_c').val();
        data.export_meter_c = $('#export_meter_c').is(':checked');
        data.potential_issues_c = $('#potential_issues_c').val();
        data.pe_site_details_no_c = $('#pe_site_details_no_c').val();
        data.cable_size_c = $('#cable_size_c').val();
        data.connection_type_c = $('#connection_type_c').val();
        data.main_type_c = $('#main_type_c').val();
        data.meter_number_c = $('#meter_number_c').val();
        data.meter_phase_c = $('#meter_phase_c').val();
        data.nmi_c = $('#nmi_c').val();
        data.account_number_c = $('#account_number_c').val();
        data.address_nmi_c = $('#address_nmi_c').val();
        data.name_on_billing_account_c = $('#name_on_billing_account_c').val();
        data.distributor_c = $('#distributor_c').val();
        data.energy_retailer_c = $('#energy_retailer_c').val();
        data.account_holder_dob_c = $('#account_holder_dob_c').val();
        data.distance_to_travel_c = $('#distance_to_travel_c').val();
        var jsonString= JSON.stringify(data);
        return jsonString;
    }

    function render_data_sitedetails(data) {
    
        if(data == '') {
            data = '{"pe_site_details_no_c":"","sg_site_details_no_c":"","solargain_quote_number_c":"","detail_site_install_address_c":"","detail_site_install_address_city_c":"","detail_site_install_address_state_c":"","detail_site_install_address_postalcode_c":"","detail_site_install_address_country_c":"","customer_type_c":"0","gutter_height_c":"1","roof_type_c":"Tin","export_meter_c":false,"potential_issues_c":["Shading"],"cable_size_c":"","connection_type_c":"Underground","main_type_c":"1","meter_number_c":"","meter_phase_c":"1","nmi_c":"","account_number_c":"","address_nmi_c":"","name_on_billing_account_c":"","distributor_c":"0","energy_retailer_c":"0","account_holder_dob_c":""}';
            data =  $.parseJSON(data);
        }else{
            data =  $.parseJSON(data);
        }  
        $.each(data,function(k,v){
            if(k == 'customer_type_c') {
                $("input[name='customer_type_c'][value='"+v+"']").prop('checked', true);
            }
            else if(k =='export_meter_c' && v == true){
                $('#export_meter_c').attr('checked', true);
            }
            else {
                $('#'+k).val(v);
            }
        })
    }

    //start --- site details custom code
    if(module_sugar_grp1 == 'AOS_Invoices'){ 
        // var seletor_panel_pricing_pv = '';
        // $('.panel-content .panel-default').each(function(){
        //     var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
        //     if(title_panel_default == 'site details'){
        //         seletor_panel_pricing_pv = '#' + $(this).find('.panel-body').attr('id');
        //     }
        // });
        $.ajax({
            url:"?entryPoint=gettempalte_sitedetails_invoices&action=render",
            async: false,
            success : function(result){
                // $(seletor_panel_pricing_pv).find(".tab-content").after(result);   
                $('div[field="image_site_details"]').parent().empty().append(result);
                var data = $("#data_json_site_details_c").val();
                render_data_sitedetails(data);
                var group_address_install = '<div id="group_address_install" class="col-xs-12 edit-view-row-item col-sm-6"><fieldset> <legend> Install Address </legend></fieldset>';
                $("#install_address_c").parent().parent().parent().children().each(function(index,item){      
                        if(index >=8 && index <= 20){
                            if(this.className != 'clear'){
                               group_address_install += this.innerHTML; 
                            }
                            item.remove();
                        }               
                });
                group_address_install += '</div>';
                $("#group_address_site_detail").after(group_address_install);
                $("div[data-label='LBL_INSTALL_ADDRESS']").removeClass('col-sm-2');
                $("div[data-label='LBL_INSTALL_ADDRESS']").addClass('col-sm-4');
                $(document).find("#group_address_install input[type='text']").closest('.edit-view-field').css('padding-top','10px');
                $(document).find("#install_address_c").autocomplete({
                    source: function( request, response ) {
                        Math.floor((Math.random() * 3) + 1);
        
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
                        var address2 = value[1].trim();
                        $("#install_address_c").val(address1);
        
                        var address3 = address2.split("  ");
        
                        $("#install_address_city_c").val(address3[0]);
                        $("#install_address_state_c").val(address3[1]);
                        $("#install_address_postalcode_c").val(address3[2]);
                        return false;
                    }
                });
               
               
                $("#data_json_site_details_c").val(render_json_data_sitedetails());
            }
        })  
        //VUT - Add id for section site details
        $('#block_image_site_detail').parent().parent().attr('id', 'section_sitedetails');
        $('body').on('change','#section_sitedetails',function(){
            data = render_json_data_sitedetails();
           
            $("#data_json_site_details_c").val(data);
        })


    }


    /**
     * Function Upload Image In Detail (Start)
     */

        YAHOO.util.Event.addListener("Upload_Image_Site_Detail", "click", function(){
            $('body').find('#image_site_detail').trigger('click');
        });

        YAHOO.util.Event.addListener("image_site_detail",'change',function(){ 
            var fd = new FormData(); 
            var files = $('#image_site_detail')[0].files[0]; 
            fd.append('file', files); 

            $.ajax({ 
                url: '?entryPoint=Image_Site_Details&id='+ $('body').find('input[name="installation_pictures_c"]').val(), 
                type: 'post', 
                data: fd, 
                contentType: false, 
                processData: false, 
                success: function(response){ 
                    if(response != 0){ 
                        var folder_id  = $('body').find('input[name="installation_pictures_c"]').val();
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

    /**
    * Function Upload Image In Detail (END)
    */

    // dung code -- custom group invoice checklist 
    $("#data_checklist_invoice_c").closest('.edit-view-field').parent().parent().hide();
    var html_group_custom_invoice_checklist = 
    '<div id="group_custom_invoice_checklist" class="row detail-view-row">'
       + '<div id="group_custom_invoice_checklist_col_1" class="col-xs-12 col-sm-12 detail-view-row-item">'
       + '</div>'
       + '<div id="group_custom_invoice_checklist_col_2" hidden class="col-xs-12 col-sm-6 detail-view-row-item">'
       + '</div>'
    +'</div>';
    $("#data_checklist_invoice_c").closest('.tab-content').append(html_group_custom_invoice_checklist);
    
    function render_checkbox_invoice_checklist(){
         $.ajax({
             url: '/index.php?entryPoint=API_CheckList_Invoice&action=render',
             success: function (result) {
                 try {
                    var data_string = $("#data_checklist_invoice_c").val();
                    var json_data = $.parseJSON(result);
                    $("#group_custom_invoice_checklist_col_1").append(json_data['template_html']).find('input[type="checkbox"]').click(function(e){
                        var element_id = $(this).attr('id');
                        if($(this).is(":checked") && $("#"+element_id+'_textarea').val() == ''){
                            var dt = new Date();
                            var time = dt.getDate() + "/" +(+ dt.getMonth()+1) + "/" + dt.getFullYear() + " " +  dt.getHours() + ":" +(dt.getMinutes() < 10 ? '0' : '') + dt.getMinutes();
                            $("#"+element_id+'_textarea').val(time + ' ' + $("#globalLinks span").text());
                        }else{
                            $("#"+element_id+'_textarea').val("");
                        }
                    });
                    
                    if ($('#quote_type_c').val() != 'quote_type_sanden') {
                        $('#cl_Send_ROT_Link').closest('.edit-view-row-item').hide();
                   }
                    if(data_string == '') {
                        data_string = json_data['data'];
                    }
                    window.data_checklist_empty = json_data['data'];
                    var data =  $.parseJSON(data_string);
                    $.each(data,function(k,v){
                        if(v[1]){
                            $("#group_custom_invoice_checklist_col_1").find("#"+v[2]).attr( 'checked', true );
                        }
                        $("#group_custom_invoice_checklist_col_1").find("#"+v[2]+"_textarea").val(v[3]);
                    });
                    render_data_checkbox_invoice_checklist();
                 } catch (error) {
                     console.log(error)
                 }
             }
         });
     }
     render_checkbox_invoice_checklist();
     
     function render_data_checkbox_invoice_checklist(){
         if(typeof( window.data_checklist_empty) == 'undefined' || window.data_checklist_empty == '' ){
            var data = '{"cl_quote_accepted":["Quote accepted","","cl_quote_accepted",""],"cl_seek_customer_preferred_install_date":["Seek customer preferred install date","","cl_seek_customer_preferred_install_date",""],"cl_plumber_seek_install_date":["Pumber seek install date","","cl_plumber_seek_install_date",""],"cl_electrician_seek_install_date":["Electrician seek install date","","cl_electrician_seek_install_date",""],"cl_send_customer_install_date":["Send customer install date","","cl_send_customer_install_date",""],"cl_Send_Plumber_PO":["Send Plumber PO","","cl_Send_Plumber_PO",""],"cl_Send_Electrician_PO":["Send Electrician PO","","cl_Send_Electrician_PO",""],"cl_Send_supply_PO":["Send supply PO","","cl_Send_supply_PO",""],"cl_deposit_received":["Deposit Received","","cl_deposit_received",""],"cl_deposit_received_4_day_all_client":["+ 4 day call client","","cl_deposit_received_4_day_all_client",""],"cl_deposit_received_4_day_call_installer":["+ 4 day call installer","","cl_deposit_received_4_day_call_installer",""],"cl_deposit_received_1_day_call_client":["+ 1 day call client","","cl_deposit_received_1_day_call_client",""],"cl_deposit_received_1_day_call_installer":["+ 1 day call installer","","cl_deposit_received_1_day_call_installer",""],"cl_paperwork_submitted":["Paperwork submitted","","cl_paperwork_submitted",""],"cl_Warehouse_log_updated":["Warehouse log updated","","cl_Warehouse_log_updated",""],"cl_Final_payment_received":["Final payment received","","cl_Final_payment_received",""],"cl_Thankyou_letter_sent_to_client":["Thankyou letter sent to client","","cl_Thankyou_letter_sent_to_client",""]}';
         }else{
            var data = window.data_checklist_empty;
         }
       
        data =  $.parseJSON(data);
        $.each(data,function(k,v){           
            if($("#"+v[2]).is(':checked')){
                data[k][1] = 'true';
            }
            data[k][3] = $("#group_custom_invoice_checklist_col_1").find("#"+k+"_textarea").val();
        });
        var jsonString= JSON.stringify(data);
        $("#data_checklist_invoice_c").val(jsonString);
        $(".link_to_button_function").remove();

        $('#cl_plumber_seek_install_date_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="seekInstallationDate" >Button Seek Install Date</a>');
        $('#cl_electrician_seek_install_date_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="seekInstallationDate_elec">Button Seek Install Date</a>');
        $('#cl_send_customer_install_date_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;"  class="link_to_button_function" data-id-button="Send_Customer_Install_date" >Send Install Date</a>');
        $('#cl_Send_Plumber_PO_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="sendPlumberEmail" >Send Plumber PO</a>');
        $('#cl_Send_Electrician_PO_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="sendElectricalEmail" >Send Electrical PO</a>');
        $('#cl_Send_supply_PO_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="createSupplyPO1" >Send Supply PO</a>');
        $('#cl_install_photos_uploaded_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="get_all_files_invoice" >Upload File</a>');
        $('#cl_certificate_numbers_uploaded_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="vba_pic_cert_c" >Certificate</a>');
        $('#cl_serial_numbers_entered_for_tank_and_hp_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="addMoreSandenTankSerial">Tank and HP</a>');
        $('#cl_deposit_received_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="multipayment" >Deposit Received</a>');
        $('#cl_Final_payment_received_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="next_payment_amount_c" >Final Payment</a>');
        $('#cl_Send_US7_Tips_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="email_us7_tips">US7 Tips</a>');
        $('#cl_Send_Sanden_Tips_textarea').parent().parent().find('.col-button').append('<a style="font-weight:800;cursor: pointer;" class="link_to_button_function" data-id-button="email_sanden_tips" >Sanden Tips</a>');     

        $('#group_custom_invoice_checklist_col_1 input[type="checkbox"]').css({"border-style":"solid",'border-width': '2px','height': '20px','width': '20px'})
        display_link_to_calendar();
     }

     function display_link_to_calendar() {
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
    // VuT - Informartion warehouse log in invoice detail
    function show_link_warehouse() {
        var invoiceID = $("input[name='record']").val();
        // debugger;
        if (invoiceID != "") {
            $.ajax({
                url: "/index.php?entryPoint=API_Invoice_WarehouseLog&invoiceID="+invoiceID,
                success: function (data){
                    if ( typeof(data) !== null && data.length>0) {
                        var link_wahouselog ="/index.php?module=pe_warehouse_log&action=EditView&record="+data;
                        $("#cl_addLinkWarehouseLog").remove();
                        var href_warehouselog = '<a style="font-weight:800; cursor: pointer;" target="_blank" id="cl_addLinkWarehouseLog" href="'+link_wahouselog+'">Open Warehouse log</a>';
                        $('#cl_Warehouse_log_updated_textarea').parent().parent().find('.col-button').append(href_warehouselog);
                    } else {
                        var create_warehouselog ="/index.php?module=pe_warehouse_log&action=EditView&record=";
                        $("#cl_addLinkWarehouseLog").remove();
                        var href_warehouselog = '<a style="font-weight:800; cursor: pointer;" target="_blank" id="cl_addLinkWarehouseLog" href="'+create_warehouselog+'">Create Warehouse Log</a>';
                        $('#cl_Warehouse_log_updated_textarea').parent().parent().find('.col-button').append(href_warehouselog);
                    }
                }
            });
        } 
    }
    show_link_warehouse();

     // sync data two group install address and sitedetail address , but sitedetail address will hide and not use future
    function sync_data_install_address_to_site_detail_address() {
        $("#install_address_c").val($("#detail_site_install_address_c").val());
        $("#install_address_city_c").val($("#detail_site_install_address_city_c").val());
        $("#install_address_state_c").val($("#detail_site_install_address_state_c").val());
        $("#install_address_postalcode_c").val($("#detail_site_install_address_postalcode_c").val());
    }

    function sync_data_site_detail_address_to_install_address() {
        $("#detail_site_install_address_c").val($("#install_address_c").val());
        $("#detail_site_install_address_city_c").val($("#install_address_city_c").val());
        $("#detail_site_install_address_state_c").val($("#install_address_state_c").val());
        $("#detail_site_install_address_postalcode_c").val($("#install_address_postalcode_c").val());
    }
    sync_data_site_detail_address_to_install_address();
    YAHOO.util.Event.addListener("group_address_install",'change', sync_data_site_detail_address_to_install_address);
    YAHOO.util.Event.addListener("group_address_site_detail",'change', sync_data_install_address_to_site_detail_address);

    $("body").on('click','.link_to_button_function',function(){  
        var element_id = $(this).attr('data-id-button');
        $('html, body').animate({
            scrollTop: (($('body').find('#'+ element_id).offset().top) - 100)
        },100);
        $('body').find('#'+ element_id).focus();
    })
     YAHOO.util.Event.addListener("group_custom_invoice_checklist",'change',function(){
        render_data_checkbox_invoice_checklist();
    });
    // var phonecallinfo_html = $("#phonecallinfo").closest('.edit-view-row-item').detach();
    // phonecallinfo_html.appendTo($("#group_custom_invoice_checklist_col_2"));
    //end dung code -- custom group invoice checklist
})

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
                var generateUUID = $('input[name="installation_pictures_c"]').val();
                $.ajax({
                    type: "POST", 
                    url: "index.php?entryPoint=Image_Site_Details_Clipboard_Popup", 
                    data: { img: image_data, id: generateUUID}      
                    }).done(function(data){
                        var folder_id  = $('body').find('input[name="installation_pictures_c"]').val();
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

    



