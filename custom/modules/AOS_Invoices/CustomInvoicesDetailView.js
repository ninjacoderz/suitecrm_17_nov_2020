$(function () {
    'use strict';
    // Generate uinique id

    $('#tab-actions').after('<input type="button" name="Email Invoice" value="Email Invoice" class="button primary" onclick="popupEmailInvoice(this)" />');
    // $('#tab-actions').after($('#tab-actions li:first').clone());
    $('#tab-actions').after('<li><input type="button" id="send_trustpilop" value="TrustPilot" class="button primary"/></li>');
    $('#tab-actions').after('<li><input type="button" id="product_review" value="Product Review" class="button primary" data-email-type="product_review" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('#tab-actions').after('<li><input type="button" id="google_review" value="Google Review" class="button primary" data-email-type="google_review" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('#tab-actions').after('<li><input type="button" id="survey_form_email" value="Sanden STC Survey" class="button primary" data-email-type="survey_form_email" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+ $('#billing_contact_id').text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('#tab-actions').after("<li><button type='button' class='button' id='detail_preview_pdf_invoice'>Preview Invoice PDF</button></li>");
    $('#tab-actions').parent().append('<li><input type="button" id="delivery_coming" value="Delivery coming" class="button primary" data-email-type="delivery_coming" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").text() +'" data-contact-name="'+$('#billing_contact_id').text()+'"  data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('#tab-actions').parent().append('<li><input type="button" id="delivery_schedule" value="Delivery Schedule" class="button primary" data-email-type="delivery_schedule" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").text() +'" data-contact-name="'+$('#billing_contact_id').text()+'"  data-record-id="'+ $("input[name='record']").val() +'" /></li>');
   // $('#tab-actions').parent().append('<li><input type="button" id="updateXeroInvoice" value="Update Xero Invoice" class="button primary updateXeroInvoice" title="UPDATE XERO INVOICE" onClick="SUGAR.updateXeroInvoice(this);" ><span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button></li>');
   $('#tab-actions').after('<li><input type="button" id="" value="EMAIL GET ROT Agreement" class="button primary" data-email-type="EMAIL_GET_ROT_Agreement" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+ $('#billing_contact_id').text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');

    $('#tab-actions').parent().append('<li><button style="background:#009acf;" type="button" id="CRUD_Xero_Invoice" class="button CRUD_Xero_Invoice" title="Create And Update Xero Invoice" onClick="SUGAR.CRUD_Xero_Invoice(this);" >Create & Update Xero <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button></li>');
    $('#tab-actions').parent().append('<li><button style="background:#945596;" type="button" id="sanden_health_check" class="button sanden_health_check" value="Sanden health check" data-email-type="sanden_health_check" data-module="AOS_Invoices" data-module-name="'+ $("#name").text() +'" data-contact-name="'+$('#billing_contact_id').text()+'"  data-record-id="'+ $("input[name='record']").val() +'" title="Sanden Health Check" onClick="$(document).openComposeViewModal(this);" >Sanden Health Check <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button></li>');
    $('#tab-actions').parent().append('<li><button style="background:#009acf;" type="button" id="CreateInvoiceOSTI" class="button CreateInvoiceOSTI" title="Create Invoice SOTI" onClick="SUGAR.CreateInvoiceOSTI(this);" > Create Invoice SOTI <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button></li>');
    
    //fix task Luyen
    if( $("#quote_type_c").val() == 'quote_type_solar' || $("#quote_type_c").val() == 'quote_type_tesla'){
        $('#tab-actions').after('<input type="button" name="Email Invoice Solar" value="Email Invoice Solar" class="button primary" onclick="document.getElementById(\'popupDivBack_ara\').style.display=\'none\';document.getElementById(\'popupDiv_ara\').style.display=\'none\';var form=document.getElementById(\'popupForm\');if(form!=null){$(form).attr(\'target\', \'_blank\');form.task.value=\'emailpdf\'; form.templateID.value=\'13e05dc5-5d61-9898-6a07-5918de5ff9e4\';form.submit();}else{alert(\'Error!\');}" />');
    }
    SUGAR.email_invoice = function(e){
        if( $('#quote_type_c').val() == 'quote_type_sanden' &&   
            ($('#status').val() == 'Variation_Unpaid' || $('#status').val() == 'STC_VEEC_Unpaid' || $('#status').val() == 'STC_Unpaid' || $('#status').val() == 'VEEC_Unpaid') &&
            ( $("#handheld_1_c").text() != "" || $("#handheld_2_c").text() != "" || $("#handheld_3_c").text() != "") ){ 
            alert('Email Free Methven Promo Code to Customer')
        }
        var email_invoice_type = $(e).attr('data-email-invoice-type');
        document.getElementById('popupDivBack_ara').style.display='none';
        document.getElementById('popupDiv_ara').style.display='none';
        var form=document.getElementById('popupForm');
        if(form!=null){$(form).attr('target', '_blank');
        form.task.value='emailpdf';
        switch (email_invoice_type) {
            case 'overdue' :
                form.templateID.value='585adb50-6580-d9a6-584a-60ed4ed7883c'; //live : 585adb50-6580-d9a6-584a-60ed4ed7883c ; local: 9968c6a4-c6c3-ceaa-caa3-60e805c8d8bf
                break;
            default:
                form.templateID.value='91964331-fd45-e2d8-3f1b-57bbe4371f9c';
                break;
        }
        // form.templateID.value='91964331-fd45-e2d8-3f1b-57bbe4371f9c';
        form.submit();}else{alert('Error!');}
    }
    SUGAR.CRUD_Xero_Invoice= function(elemt){
        var html_alert = '';
        if($('#due_date').text() == ''){
            html_alert += '<h4 class="text-danger">Please insert Due Date!</h4>';
        }
        if($('#invoice_date').text() == ''){
            html_alert += '<h4 class="text-danger">Please insert Invoice Date!</h4>';
        }
        if( html_alert != ''){
            $('#alert_modal').find('.modal-body').empty();
            $('#alert_modal').find('.modal-body').append(html_alert); 
            $('#alert_modal').modal('show'); 
            return false;
        }
        SUGAR.ajaxUI.showLoadingPanel();                   
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

    SUGAR.CreateInvoiceOSTI = function(e){
        var html_alert = '';
        if($('#stc_aggregator_serial_c').text() == ""){
            html_alert += '<h4 class="text-danger">STC Aggregator Serial is empty. Do you want to continue ?</h4>';
        }

        if( html_alert != ''){
            $('#alert_modal').find('.modal-body').empty();
            $('#alert_modal').find('.modal-body').append(html_alert); 
            $('#alert_modal').modal('show'); 
            return false;
        }

        SUGAR.ajaxUI.showLoadingPanel();
     
        // create and update invoice xero
        var url_xero_invoice = "/index.php?entryPoint=CreateInvoiceOSTI&recordID="+ encodeURIComponent($('input[name="record"]').val());
        $.ajax({
            url:url_xero_invoice,
            success:function(data){   
                SUGAR.ajaxUI.hideLoadingPanel();                             
                    try {
                        var json = $.parseJSON(data);
                        $('#alert_modal').find('.modal-body').empty();
                        $('#alert_modal').find('.modal-body').append(json.msg); 
                        $('#alert_modal').modal('show'); 
                        if(json.IdInvoiceOSTI != ''){
                            window.open('/index.php?module=AOS_Invoices&action=EditView&record='+json.IdInvoiceOSTI.trim(),'_blank');
                        }
                        return false;

                    } catch (e) {

                        $('#alert_modal').find('.modal-body').empty();
                        $('#alert_modal').find('.modal-body').append('<h3 class="text-danger text-center">Unsuccess !</h3>'); 
                        $('#alert_modal').modal('show'); 
                        return false;
                    }                   
            }
        });

    }

    $('#tab-actions').after('<li><button style="background:#46a049;" type="button"  data-module-quote-id="'+ $("input[name='record']").val()+'" name="Create_Call" value="Create Call" id="Create_Call" class="button primary" onclick="$(document).create_call_Immediate_Post_Install(this);"><i class="glyphicon glyphicon-phone-alt"></i> Create Call</button></li>');
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


        $(document).on('click','#detail_preview_pdf_invoice',function(){
            var quote_type = '' ;
            if( $("#quote_type_c").val() == 'quote_type_solar'){
                quote_type = 'quote';
            }else if($("#quote_type_c").val() == 'quote_type_tesla'){
                quote_type = 'tesla';
            }
            if(quote_type != ''){
                SUGAR.ajaxUI.showLoadingPanel();
                $.ajax({
                    url: 'index.php?entryPoint=CustomDownloadPDF&type='+quote_type+'&module=AOS_Invoices&record_id='+$("input[name='record']").val()+'&preview=true',
                    async: true,
                    success: function(result) {
                        if(result == '' || typeof result === undefined)return;
                        var data = $.parseJSON(result);                      
                        $(".modal_preview_pdf").remove();
                        var html = '<div class="modal fade modal_preview_pdf" tabindex="-1" role="dialog">'+
                                        '<div class="modal-dialog" style="width:60%">'+
                                            '<div class="modal-content">'+
                                                '<div class="modal-header" style="padding:5px;">'+
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                    '<h4 class="modal-title" id="title-generic"><center>'+data['file_name']+'</center></h4>'+
                                                '</div>'+
                                                '<div class="modal-body" style="padding:1px;">'+
                                                    '<embed style="height:calc('+$('body').height()+'px - 100px);width:100%;" src="data:application/pdf;base64,'+data['pdf_content']+'" type="application/pdf"  />'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                        $("body").append(html);
                        $(".modal_preview_pdf").modal('show');
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }
                });
            }else{
                SUGAR.ajaxUI.showLoadingPanel();
                var filecontent;
                $.ajax({
                    url: "/index.php?entryPoint=generatePdf&templateID=91964331-fd45-e2d8-3f1b-57bbe4371f9c&task=pdf&module=AOS_Invoices&uid="+$("input[name='record']").val()+'&preview=yes',
                    type: "GET",
                    async: false,
                    success: function(result, text, xhr){
                        filecontent = result;
                        var today = new Date();
                        var date = today.getDate()+(today.toLocaleString('default', { month: 'short' }))+today.getFullYear();
                        var file_name  = "Invoice_"+$("#number").text().trim() +"_"+ $("#name").text().replace(" ","_").trim() + date+".pdf";
                        $(".modal_preview_pdf").remove();
                        var html = '<div class="modal fade modal_preview_pdf" tabindex="-1" role="dialog">'+
                                        '<div class="modal-dialog" style="width:60%">'+
                                            '<div class="modal-content">'+
                                                '<div class="modal-header" style="padding:5px;">'+
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                    '<h4 class="modal-title" id="title-generic"><center>'+file_name+'</center></h4>'+
                                                '</div>'+
                                                '<div class="modal-body" style="padding:1px;">'+
                                                    '<embed style="height:calc('+$('body').height()+'px - 100px);width:100%;" src="data:application/pdf;base64,'+filecontent+'" type="application/pdf"  />'+
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
    // $.ajax({
    //     url: "/index.php?entryPoint=GetInfoInvoice&record_id=" + $('input[name="uid"]').val(),
    //     success: function(data){
    //         $("div[class='tab-content']").first().append(data);
    //     }
    // })
    //Start - custom template ALL VIEW tab
    function render_field_data(value,fulldata){
        //value[0] id, value[1] value, value[2]  label ,fulldata json data 

        var html = '<div class="col-xs-12 col-sm-12 detail-view-row-item">'
       + '<div class="col-xs-12 col-sm-4 label col-1-label" style="padding-top: 15px;">'
       + value[2]
        + '</div>'
        +'<div class="col-xs-12 col-sm-8 detail-view-field inlineEdit" type="'+value[0]+'" field="'+value[0]+'">';
        
        switch (value[0]) {
            case "billing_account_email":
                html +='<a class="email-link"  onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-record-id="'+ $("input[name='record']").val() +'" data-module-name="'+ $("#billing_account").text() +'" data-email-address="'+value[1]+'">'+value[1]+'</a>';
                html +='<br><a style="color:blue;" class="email-link"  target="_blank" href="https://mail.google.com/#search/'+value[1]+'">GSearch </a>';
                html += '<a class="copy-email-link" data-email-address="'+value[1]+'" \
                        title="Copy '+value[1]+'" onclick="$(document).copy_email_address(this);"\
                        style="cursor: pointer; position: relative;display: inline-block;border-bottom: 1px dotted black;" data-toggle="tooltip">&nbsp;<span class="glyphicon glyphicon-copy"></span>\
                        <span class="tooltiptext" style="display:none;width:200px;background:#94a6b5;color:#fff;text-align: center;border-radius: 6px;padding: 5px 0; position: absolute;z-index: 1;">Copied '+value[1]+'</span></a>';
                break;
            case "mobile_phone_c":
                    html +='<span class="sugar_field" id="'+value[0]+'">'+value[1]+'</span>  ' + ' <img class="sms_icon" src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14">';
                    html +='&nbsp;<a target="_blank" href="http://message.pure-electric.com.au/'+value[1].replace(/^0/g, "#61").replace(/^61/g,"#61").replace(/\s+/g,'')+'" title="Message Portal"><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="14" width="14"></a>'
                    break;
            case "solargain_quote_number_c":
                html +='<a target="_blank" href="https://crm.solargain.com.au/quote/edit/'+ value[1] +'">'+value[1]+'</a>';
                break;
            case "solargain_invoices_number_c":
                html +='<a target="_blank" href="https://crm.solargain.com.au/order/edit/'+ value[1] +'">'+value[1]+'</a>';
                break;
            default:
                html +='<span class="sugar_field" id="'+value[0]+'">'+value[1]+'</span>';
                break;
        }
      
        html +='<div class="inlineEditIcon col-xs-hidden">'
        +'</div>'
        +'</div>'
        +'</div>';
        return html;
    };
    var html_group_custom_invoice_template = 
    '<div id="group_custom_invoice_template" class="row detail-view-row">'
       + '<div id="group_custom_invoice_template_col_1" class="col-xs-12 col-sm-6 detail-view-row-item">'
       + '</div>'
       + '<div id="group_custom_invoice_template_col_2" class="col-xs-12 col-sm-6 detail-view-row-item">'
            + '<div id="group_custom_invoice_template_col_2_1" class="col-xs-12 col-sm-6 detail-view-row-item">'
            + '</div>'
            + '<div id="group_custom_invoice_template_col_2_2" class="col-xs-12 col-sm-6 detail-view-row-item">'
            + '</div>'
       + '</div>'
    +'</div>';
    $("#custom_detail_in_detail_view").parent().hide();
    $("#custom_detail_in_detail_view").parent().parent().find('.label').hide();
    $('#tab-content-0 #custom_detail_in_detail_view').closest('.detail-view-row-item').after(html_group_custom_invoice_template);
    $.ajax({
        url: "/index.php?entryPoint=APIGetDataInvoice&record_id=" + $('input[name="uid"]').val(),
        success: function(data){
            if(data == '' && typeof data == undefined)return;
            var json_data = JSON.parse(data);
            window.data_invoice = json_data;
            console.log(json_data);
        //     //Account 
        //     $("body").find('#group_custom_invoice_template_col_1').append(render_field_data(json_data.billing_account),json_data);
        //     $("body").find('#group_custom_invoice_template_col_1').append(render_field_data(json_data.mobile_phone_c),json_data);
        //     $("body").find('#group_custom_invoice_template_col_1').append(render_field_data(json_data.billing_account_email),json_data);
        //     $("body").find('#group_custom_invoice_template_col_1').append(render_field_data(json_data.address),json_data);
        //     //Solar gain
        //     $("body").find('#group_custom_invoice_template_col_1').append(render_field_data(json_data.solargain_quote_number_c),json_data);
        //     $("body").find('#group_custom_invoice_template_col_1').append(render_field_data(json_data.solargain_invoices_number_c),json_data);
        //     //add link xero invoice 
        //     var html_group_link_xero = '';
        //     (json_data.xero_invoice_c[1] != '')? html_group_link_xero += '<a href="https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID='+json_data.xero_invoice_c[1]+'" target="_blank">'+json_data.xero_invoice_c[2]+'</a><br>' :html_group_link_xero +='';
        //     (json_data.xero_stc_rebate_invoice_c[1] != '')? html_group_link_xero += '<a href="https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID='+json_data.xero_stc_rebate_invoice_c[1]+'" target="_blank">'+json_data.xero_stc_rebate_invoice_c[2]+'</a><br>' :html_group_link_xero +='';
        //     (json_data.xero_shw_rebate_invoice_c[1] != '')? html_group_link_xero += '<a href="https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID='+json_data.xero_shw_rebate_invoice_c[1]+'" target="_blank">'+json_data.xero_shw_rebate_invoice_c[2]+'</a><br>' :html_group_link_xero +='';
        //     (json_data.xero_veec_rebate_invoice_c[1] != '')? html_group_link_xero += '<a href="https://go.xero.com/AccountsReceivable/Edit.aspx?InvoiceID='+json_data.xero_veec_rebate_invoice_c[1]+'" target="_blank">'+json_data.xero_veec_rebate_invoice_c[2]+'</a><br>' :html_group_link_xero +='';
        //     $("#description").closest('.detail-view-row-item').append(html_group_link_xero);

        //     //site details
        //     $("body").find('#group_custom_invoice_template_col_2_1').append(render_field_data(json_data.address_site_details),json_data);
        //     $("body").find('#group_custom_invoice_template_col_2_1').append(render_field_data(json_data.roof_type_c),json_data);
        //     $("body").find('#group_custom_invoice_template_col_2_1').append(render_field_data(json_data.nmi_c),json_data);
        //     $("body").find('#group_custom_invoice_template_col_2_1').append(render_field_data(json_data.distributor_c),json_data);
         
        //    if(json_data.installation_pictures_c[2]) {
        //     var html_image_site_detail = '<img id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;height:auto;width:100%;max-width:220px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+ json_data.installation_pictures_c[1]+'/Image_Site_Detail.jpg?'+Date.now()+'">';
        //    }else{
        //     var html_image_site_detail = '<div id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:198px;height:auto;margin-bottom:5px;text-align:center;">Map Template Image</div>';
        //    }
        //    if (json_data.file_design[2]) {
        //         var html_img_design = '<img style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:198px;height:auto;" src="'+json_data.file_design[1]+'">';
        //    } else {
        //        var html_img_design ='';
        //    }
        //    $("body").find('#group_custom_invoice_template_col_2_2').append(html_image_site_detail+html_img_design);
        //    //Auto loading image detail 
        //    $("body").find('#group_custom_invoice_template_col_2_2').append('<canvas hidden="" id="clipboard"></canvas>');
           var generateUUID = json_data.installation_pictures_c[1];
           if(generateUUID == '') {
               generateUUID = create_generateUUID();
           }
           $('#tab-actions').parent().append('<li><input type="button" data-install="'+generateUUID+'" id="create_fb_post" value="Create Fb post" class="button primary"/></li>');
           var check_image = $('img#Map_Template_Image').attr('src');
           if( json_data.billing_address_street[1] != "" && check_image == undefined) {

               SUGAR.ajaxUI.showLoadingPanel();
               $("#ajaxloading_mask").css("position",'fixed');
               $.ajax({
                   url: 'https://maps.googleapis.com/maps/api/geocode/json?address='
                        + encodeURIComponent(json_data.address_site_image[1]) 
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
                               async: false,
                               success: function(result) {
                                   $("#Map_Template_Image").hide();
                                   $("#map").hide();
                                   $("#Map_Template_Image").after(result);                
                               }
                           }).done(function(data){
                               CopyToClipboard(generateUUID);                          
                           });
                       }
                       else{
                           SUGAR.ajaxUI.hideLoadingPanel();
                       }
                   }    
               })
           }  
        }
    })
    if( $('#quote_type_c').val() != "" ){
        var link_upload = '<div class="row detail-view-row">'
                            +'<div class="col-xs-12 col-sm-6 detail-view-row-item">'
                                +'<div class="col-xs-12 col-sm-4 label col-1-label">'
                                +'Upload Install Photos:'
                                +'</div>'
                                +'<div class="col-xs-12 col-sm-8 detail-view-field inlineEdit"><a target="_blank" href="https://pure-electric.com.au/upload_file_to_invoice?invoice_id='+$('input[name="record"]').val()+'">Link Open Upload File</a></div>'
                            +'</div></div>';
        $('#tab-content-0').append(link_upload);
    }
    $.fn.copy_link_RCTI = function (element){
        var data =$(element).attr('data-link');
        var dummy = document.createElement("input");
        document.body.appendChild(dummy);
        dummy.setAttribute('value', data);
        dummy.select();
        console.log(document.execCommand('copy'));
        document.body.removeChild(dummy);
        var ParentElement =  $(element).parent();
        ParentElement.find('span.tooltiptext').show();
        setTimeout(function() {
            ParentElement.find('span.tooltiptext').hide();
        },600);
        return false;
    }
    
     function createPopupGenerateLink(){
         
        var InvoiceID = $('input[name="record"]').val();
        var pureUrl = 'https://pure-electric.com.au/pesignaturepad?invoiceID='+InvoiceID;
        var GEO_RCTI_Link = pureUrl+'&method=getCustomerInfo';
        var Solor_RCTI_Link = pureUrl+'&method=getCustomerInfoSolar'
        var array_suggested = {
            'GEO RCTI Link' : GEO_RCTI_Link,
            'Solar RCTI Link' : Solor_RCTI_Link,
        };
        var popupListHtml = '<div id="popupGenerate_RCTI_Link" title="Select Option Send Review" class="row">';
        $.each( array_suggested, function( key, value ) {
            popupListHtml += '<div class="form-check form-check-inline checkbox-primary">'
              +'<label class="form-check-label"><a target="_blank" href="'+value+'">'+key+'</a></label>'
              +'<button type="button" class="button" id="'+key+'" data-link="'+value+'" onClick="$(document).copy_link_RCTI(this);" ><span class="glyphicon glyphicon-copy"></span>'+key+'</button>'
              +'<span class="tooltiptext" style="width: 200px; background: rgb(148, 166, 181); color: rgb(255, 255, 255); text-align: center; border-radius: 6px; padding: 5px 0px; position: absolute; z-index: 1; display: none;">Copied '+key+'</span>'
              +'</div>';
        });
        popupListHtml += '</div>';

        $('#tab-content-0').append(popupListHtml);
    }
   createPopupGenerateLink();
    //end - custom template ALL VIEW tab
//VUT-S-Click Update Xero
    SUGAR.updateXeroInvoice = function(elemt){
        var invoice_type = window.data_invoice.invoice_type_c[1];
        var xero_invoice = window.data_invoice.xero_invoice_c[1];
        var xero_stc_rebate_invoice = window.data_invoice.xero_stc_rebate_invoice_c[1];
        var xero_veec_rebate_invoice = window.data_invoice.xero_veec_rebate_invoice_c[1];

        if($('#installation_date_c').text() == ''){
            if($('#due_date').text() == ''){
                alert('Please insert Due Date !');
                return false;
            }
        }

        // $("#EditView input[name='action']").val('Save');
        // $('#updateXeroInvoice span.glyphicon-refresh').removeClass('hidden');
        var url_xero_invoice = "/index.php?entryPoint=customUpdateXeroInvoice&invoice=1&method=post" + '&record='+ encodeURIComponent($('input[name="record"]').val())
        + '&invoice_type='+ encodeURIComponent(invoice_type) + "&xero_payment_date=yes&xero_invoice="+ encodeURIComponent(xero_invoice);
        
        if($("#xero_stc_rebate_invoice_c").val() != ''){
            url_xero_invoice += '&xero_stc_rebate_invoice='+xero_stc_rebate_invoice;
        }
        if($("#xero_veec_rebate_invoice_c").val() != ''){
            url_xero_invoice += '&xero_veec_rebate_invoice='+xero_veec_rebate_invoice;
        }
        $.ajax({
            url:url_xero_invoice,
            success:function(data){
                 var patt_mess_err = /<ValidationError>[\s\S].*<Message>(.*)<\/Message>/;
                 var res_mess_err = patt_mess_err.exec(data);
                 if(data.indexOf("One or more line items must be specified") >=0){
                     alert("One or more line items must be specified");
                    //  $('#updateXeroInvoice span.glyphicon-refresh').addClass('hidden');
                 }
                 else if(data.indexOf("This document cannot be edited as it has a payment or credit note allocated to it") >=0){
                        alert("This document cannot be edited as it has a payment or credit note allocated to it");
                        // $('#updateXeroInvoice span.glyphicon-refresh').addClass('hidden');
                 }
                 else if(res_mess_err !== null && typeof res_mess_err === 'object'){
                     if(res_mess_err[1] !='')
                         alert(res_mess_err[1]);
                        //  $('#updateXeroInvoice span.glyphicon-refresh').addClass('hidden');
                 }else{
                    //  $('#updateXeroInvoice span.glyphicon-refresh').addClass('hidden');                        
                     try {
                         var json = $.parseJSON(data);
                         console.log(json);
                         if(json.error !=""){
                             alert(json.error);
                         }else{
                             alert('Update Xero Success!');
                         }
                     } catch (e) {
                         return false;
                     }                   
                 }
            }
        });
    }
//VUT-E-Click Update Xero
    })

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
        $('body').on('click','#send_trustpilop',function(){
            var answer = confirm("Are you want to send email TrustPilot to customer?")
            if (answer) {
                $.ajax({
                    url : "?entryPoint=SendEmailTrustPilop&module=AOS_Invoices&action=DetailView&record_id="+$('input[name="record"]').val(),
                    success: function (data) {
                        if(data.trim() != 'success'){
                            alert('UnSuccess!');
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



    });
});

(function ($) {
    $.fn.openComposeViewModal = function (source) {
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
            var populateEmailAddress = $(source).attr('data-email-address');
            // get email address
            if(typeof(pulateEmailAddress) == 'undefined'){
                // populateEmailAddress = $("#group_custom_invoice_template_col_1").find(".email-link").attr('data-email-address'); //old logic
                populateEmailAddress = $('div[field="email_site_details"]').find(".email-link").attr('data-email-address');
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

// //VUT-S-Show invoice checklist in Invoice detailview
$(function () {
    'use strict';
    var html_group_custom_invoice_checklist = 
    '<div id="group_custom_invoice_checklist" class="row detail-view-row">'
       + '<div id="group_custom_invoice_checklist_col_1" class="col-xs-12 col-sm-12 detail-view-row-item">'
       + '</div>'
       + '<div id="group_custom_invoice_checklist_col_2" hidden class="col-xs-12 col-sm-6 detail-view-row-item">'
       + '</div>'
    +'</div>';
    $("#data_checklist_invoice_c").closest('.tab-content').append(html_group_custom_invoice_checklist);
    $('#data_checklist_invoice_c').closest('.detail-view-row').hide();
    
    $('#data_checklist_invoice_c').closest('.panel.panel-default').css("display","block");
    $('#group_custom_invoice_checklist').parent().before('<input type="button" id="save_invoice_checklist" value="Save Checklist" class="button primary" data-module="AOS_Invoices" data-module-name="'+ $("#name").text() +'" data-record-id="'+ $("input[name='record']").val() + '"/>')
    if (module_sugar_grp1 == 'AOS_Invoices' && action_sugar_grp1 == 'DetailView') {
        $('#tab-actions').parent().append('<li><input type="button" id="create_service_case" value="Create Service Case" class="button primary"/></li>');
        $('#tab-actions').parent().append('<li><input type="button" id="better_sg_solar_date" value="BETTER SG SOLAR DATE" class="button primary" data-email-type="better_sg_solar_date" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+ $("#name").text() +'" data-contact-name="'+$('#billing_contact_id').text()+'"  data-record-id="'+ $("input[name='record']").val() +'"/></li>');
        $('#tab-actions').parent().append('<li><input type="button" id="genarate_yes_sa_reps" value="GENERATE YESS SA REPS INVOICE" class="button primary" onclick="document.getElementById(\'popupDivBack_ara\').style.display=\'none\';document.getElementById(\'popupDiv_ara\').style.display=\'none\';var form=document.getElementById(\'popupForm\');if(form!=null){$(form).attr(\'target\', \'_blank\');form.task.value=\'emailpdf\'; form.templateID.value=\'d7919d5e-c9fc-58f9-0448-603360469ccb\';form.submit();}else{alert(\'Error!\');}"/></li>'); //local 5c732964-ddde-dffa-a503-603373d19734
        //Button SA REPS to YESS 
        $("#tab-actions").parent().append('<li><button type="button" id="email_sa_reps_to_yes" style="background:#090979;" class="button primary" data-email-type="email_sa_reps_to_yess" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+$('#name').text()+'" data-contact-name="'+$('#billing_contact_id').text()+'" data-record-id="'+ $("input[name='record']").val() +'"   ><span class="glyphicon glyphicon-envelope"></span>SA REPS to YES<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button></li>');    
        //Button SA REPS CUSTOMER EMAIL
        $("#tab-actions").parent().append('<li><button type="button" id="email_sa_reps_customer" style="background:#090979;" class="button primary" data-email-type="email_sa_reps_customer" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+$('#name').text()+'" data-contact-name="'+$('#billing_contact_id').text()+'" data-record-id="'+ $("input[name='record']").val() +'" ><span class="glyphicon glyphicon-envelope"></span>SA REPS CUSTOMER EMAIL<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button></li>');   
        // var link_servicecase = '<div id="link_servicecase"><a href="/index.php?module=pe_service_case&action=EditView&record='+trim(data)+'" target="_blank">Open link Service Case</></div>';
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
                    // link_servicecase = '<div id="link_servicecase"><a href="/index.php?module=pe_service_case&action=EditView&record='+trim(data)+'" target="_blank">Open link Service Case</></div>';
                    // $("#description").closest('.detail-view-row-item').append(link_servicecase);
                }
            }
        });
    });
    $('#create_fb_post').click(function() {
        SUGAR.ajaxUI.showLoadingPanel();
        var pre_install_photos = $(this).attr('data-install');
        var record_id = $("input[name='record']").val();
        $.ajax({
            url: "/login_fb.php?pre_install_photos_c="+pre_install_photos+"&record_id="+record_id,   //+"&login_facebook=facebook_login",
            success: function (data) {
                // debugger;
                if (trim(data) == "" || trim(data) == null) {
                    console.log('No data return');
                } else {
                    $(".modal_meter_number").remove();
                    var html = '<div class="modal fade modal_meter_number" id="toDrafFb" tabindex="-1" role="dialog">'+
                                    '<div class="modal-dialog">'+
                                        '<div class="modal-content" >'+
                                            '<div class="modal-header" style="padding:15px">'+
                                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                '<h5 class="modal-title"  style="font-size:27px;" id="title-generic"><center>Post To Draft Facebook</center></h5>'+
                                            '</div>'+
                                            '<div class="modal-body">'+
                                                '<div class="container-fluid" style="font-size:13px;text-align:center;margin: 10px 0px 10px 0px;"><iframe style="border:unset;height: 160px;width: 100%;" id="successtodraft" src="' + data + '" frameborder="0"></iframe>'
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                    $("body").append(html);
                       setTimeout(function(){
                        SUGAR.ajaxUI.hideLoadingPanel();
                        $(".modal_meter_number").modal('show');
                    }, 3000);
                }
            }
        });
        
    });


    // var list_checkbox_autofilled_data = '#cl_deposit_received,#cl_send_remittance_advice,#cl_quote_accepted,#cl_seek_customer_preferred_install_date,#cl_plumber_seek_install_date,#cl_electrician_seek_install_date,#cl_Send_invoice_to_customer';
    // $("body").on('click',list_checkbox_autofilled_data,function(){  
    //     var element_id = $(this).attr('id');
    //     console.log(element_id);
    //     if($(this).is(":checked") && $("#"+element_id+'_textarea').val() == ''){
    //         console.log($(this).is(":checked"));
    //         var dt = new Date();
    //         var time = dt.getDate() + "/" +(+ dt.getMonth()+1) + "/" + dt.getFullYear() + " " +  dt.getHours() + ":" +(dt.getMinutes() < 10 ? '0' : '') + dt.getMinutes();
    //         $("#"+element_id+'_textarea').val(time + ' ' + $("#globalLinks span").text());
    //     }
    // })
  
    YAHOO.util.Event.addListener("group_custom_invoice_checklist",'change',function(){
        render_data_checkbox_invoice_checklist();
    });

    $('#save_invoice_checklist').click(function () {
        $.ajax({
            url: '/index.php?entryPoint=API_Invoice_WarehouseLog&type=invoice_checklist',
            type: "POST",
            data: {
                module: "AOS_Invoices",
                module_id: $("input[name='record']").val(),
                data_checklist: $("#data_checklist_invoice_c").val(),
            },
            success: function() {
                alert('Invoice checklist have saved!');
            }
        });
        
    });


    function render_checkbox_invoice_checklist(){
        $.ajax({
            url: '/index.php?entryPoint=API_CheckList_Invoice&action=render',
            success: function (result) {
                try {
                   var data_string = $("#data_checklist_invoice_c").text();
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

       $('#group_custom_invoice_checklist_col_1 input[type="checkbox"]').css({"border-style":"solid",'border-width': '2px','height': '20px','width': '20px'});
    }
    /**Show link GEO STC  */
    function createSTCAggregatorSerial() {
        if ($('#stc_aggregator_serial_c').text() == "") return;
        var href = "<div class='open-stc-rebate'><a target='_blank' href='https://geocreation.com.au/assignments/" + $('#stc_aggregator_serial_c').text() +
            "/edit'>Open link GEO STC</a></div>";
        $('#stc_aggregator_serial_c').parent().append(href);
    }
    createSTCAggregatorSerial();



});
//VUT-E-Show invoice checklist in Invoice detailview
function create_generateUUID() {
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
                        $('body').find("#image_site_details_span").append(html_image_site_detail);
                        SUGAR.ajaxUI.hideLoadingPanel();                 
                    });
                document.body.removeChild(canvas);
            }
        });
    },10000);
}

//VUT-S-Create popup when click Sandan Tip
function popupEmailInvoice(e) {
    var popupList = $('<div id="emailInvoice" title="Email Invoice">'
                    + '<input name="emailInvoiceType" type="radio" value="normal"> Normal <br>'
                    + '<input name="emailInvoiceType" type="radio" value="overdue"> Over due date <br>'
                    + '</div>');
    popupList.dialog({
        modal:true,
        buttons: {
            Cancel : function(){
                $(this).dialog("close");
            },
            OK : function() {
                $(e).attr('data-email-invoice-type', $('input[name="emailInvoiceType"]:checked').val());
                SUGAR.email_invoice(e);
                $(this).dialog("close");

            }
        }
    });
}
//VUT-S-Create popup when click Sandan Tip
