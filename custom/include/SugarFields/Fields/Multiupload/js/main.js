
function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function openBillingMap(){
    var url = 'https://www.google.com/maps/place/'+ $("#billing_address_street").val() +", " + $("#billing_address_city").val() + ", " + $("#billing_address_state").val() + ", " + $("#billing_address_postalcode").val(); //26 Walsh Ave, St Marys SA 5042, Úc;
    window.open(url, '_blank');
    return false;
}

function openShippingMap(){
    var url = 'https://www.google.com/maps/place/'+ $("#shipping_address_street").val() +", " + $("#shipping_address_city").val() + ", " + $("#shipping_address_state").val() + ", " + $("#shipping_address_postalcode").val(); //26 Walsh Ave, St Marys SA 5042, Úc;
    window.open(url, '_blank');
    return false;
}
function openInstallMap(){
    var url = 'https://www.google.com/maps/place/'+ $("#install_address_c").val() +", " + $("#install_address_city_c").val() + ", " + $("#install_address_state_c").val() + ", " + $("#install_address_postalcode_c").val(); //26 Walsh Ave, St Marys SA 5042, Úc;
    window.open(url, '_blank');
    return false;
}
function openSiteMap(){
    var url = 'https://www.google.com/maps/place/'+ $("#site_detail_addr__c").val()+','+$("#site_detail_addr__city_c").val()+','+$("#site_detail_addr__state_c").val()+','+$("#site_detail_addr__postalcode_c").val(); 
    window.open(url, '_blank');
    return false;
}
function openSupplierMap(){
   var url = 'https://www.google.com/maps/place/'+ $("#billing_address_street").val() +", " + $("#billing_address_city").val() + ", " + $("#billing_address_state").val() + ", " + $("#billing_address_postalcode").val();
   window.open(url, '_blank');
   return false;
}
function genRebateNumberFunc(elem){
    $(elem).find('span.glyphicon-refresh').removeClass('hidden');
    var post_code = $("#install_address_postalcode_c").val();
    if(post_code == ""){
        alert("Please enter install postcode");
        $(elem).find('span.glyphicon-refresh').addClass('hidden'); 
        return;
    }
    if($("#product_part_number0").val() == 'SSI' || $("#product_part_number0").val() == 'SANDEN_SUPPLY_ONLY'){
        var partnumber = $("#product_part_number1").val();
    }else{
        var partnumber = $("#product_part_number0").val();
    }

    if(partnumber == "") {
        alert("Please enter partnumber");
        $(elem).find('span.glyphicon-refresh').addClass('hidden'); 
        return;
    }
    var product_type = ($("#group0name").val().indexOf("Daikin") !== -1)? "daikin": "sanden";
    var veet_array = [
        "FTXZ25N / RXZ25N",
        "FTXZ35N / RXZ35N",
        "FTXZ50N / RXZ50N",
        "FTXM25Q / RXM25Q",
        "FTXM20Q / RXM20Q",
        "FTXM35Q / RXM35Q",
        "FTXM46Q / RXM46Q",
        "FTXM50Q / RXM50Q",
        "FTXM60Q / RXM60Q",
    ];
    var real_veet_array = [];
    var real_veet_param = [];
    if(product_type == "daikin"){
        $.each(veet_array, function(evt){
            $(".product_part_number").each(function(){
                if(veet_array[evt].indexOf($(this).val()) !== -1){
                    real_veet_array.push(veet_array[evt]);
                };
            });
            //veet_array[evt]);
        });
    }
    
    $(".product_part_number").each(function(){
        if($(this).val() == "FVXG25K2V1B"){
            real_veet_array.push("FVXG25K2 / RXG25L2");
        }
    });

    if(real_veet_array.length >= 0){
        real_veet_param = real_veet_array.join();
    }

    var sanden_info = $("#sanden_model_c").val();
    var veet_code = $("input[name='veet_code']").val();
    SUGAR.ajaxUI.showLoadingPanel();
    $.ajax({
        url: "/index.php?entryPoint=customGetRebateNumber&post_code=" + post_code+ "&part_number=" + partnumber + "&product_type=" + product_type +"&sanden_info="+sanden_info+"&veet_code="+ encodeURIComponent(real_veet_param),
        //data: {q:request.term},
        //crossOrigin: true,
        type: 'GET',
        //async: false,
        //crossDomain: true,
        //dataType: 'jsonp',

        success: function(data)
        {

            if(data == '' && typeof data == undefined)return;
            $('#genRebateNumber span.glyphicon-refresh').addClass('hidden');
            var suggest =[];
            var jsonObject = $.parseJSON(data);
            if(jsonObject.length == 0){
                alert("Cannot get rebate number for this product. Please re-check!")
                return;
            }

            $('#genRebateNumber').parent().find('span.rebateGroup').remove();
            $('#genRebateNumber').after('<span class="rebateGroup"></span>');
            var check_number_err = false;
            if(typeof jsonObject.stcs_number !== "undefined" && jsonObject.stcs_number != ""){
                $("span.rebateGroup").append('<br><span>&nbsp;&nbsp;Number of STCs:&nbsp; '+jsonObject.stcs_number+'</span>');
                $("[id^=product_product_id]").each(function (){
                    if($(this).val()== "4efbea92-c52f-d147-3308-569776823b19"){
                        $(this).parent().prev('td').find('[id^=product_product_qty]').val(jsonObject.stcs_number);
                        if(typeof jsonObject.stc !== "undefined") {
                            stc_rebate_price = parseInt(jsonObject.stc) - 1;
                            $(this).parent().next().next().next().find('[id^=product_product_list_price]').val(stc_rebate_price * -1).trigger('blur');
                        }
                    }
                });
                check_number_err = false;
            }else{
                check_number_err = true;
            }
            if(typeof jsonObject.eligible_veecs !== "undefined") {
                var veec_numb = 0;
                $.each(jsonObject.eligible_veecs, function(evt){
                    $("span.rebateGroup").append('<br><span>&nbsp;&nbsp;Eligible VEECs for '+ evt +':&nbsp; '+jsonObject.eligible_veecs[evt]+'</span>');
                    veec_numb += parseInt(jsonObject.eligible_veecs[evt]);
                });
                $("[id^=product_product_id]").each(function (){
                    if($(this).val()== "cbfafe6b-5e84-d976-8e32-574fc106b13f"){
                        $(this).parent().prev('td').find('[id^=product_product_qty]').val(veec_numb);
                        if(typeof jsonObject.veec !== "undefined") {
                            veec_rebate_price = parseInt(jsonObject.veec) - 2;
                            $(this).parent().next().next().next().find('[id^=product_product_list_price]').val(veec_rebate_price* -1).trigger('blur');
                        }
                    }
                });
                check_number_err = false;
            }else{
                check_number_err = true;
            }
            var check_error = false;
            if(typeof jsonObject.stc !== "undefined"){
                $("span.rebateGroup").append('<br><span>&nbsp;&nbsp;STC Price:&nbsp; '+jsonObject.stc+'</span>');
                check_error = false;
            }else{
                check_error = true;
            }
            if(typeof jsonObject.veec !== "undefined"){
                $("span.rebateGroup").append('<br><span>&nbsp;&nbsp;VEEC Price:&nbsp; '+jsonObject.veec+'</span>');
                check_error = false;
            }else{
                check_error = true;
            }

            SUGAR.ajaxUI.hideLoadingPanel();

            if(check_error || check_number_err){
                alert('We couldn\'t get it (Price | Number). Please check the following fields: (PostCode | PartNumber | Product Type)  or reload with the "Absolute Path"  on top.')
            }
        },
        error: function(response){
            console.log("Fail");
            $(elem).find('span.glyphicon-refresh').addClass('hidden'); 
            SUGAR.ajaxUI.hideLoadingPanel();
        },
    });
    return false;
}

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

$(function () {
    'use strict';
    // Thienpb add code for open pdf
    var current_link='';
    $(document).on('click','[data-gallery]',function(){
        if($(this).attr('href').toLowerCase().indexOf('pdf')>=0){
            current_link = $(this).attr('href');
        }else{
            current_link ='';
        }
    })
    $('#blueimp-gallery')
    .on('opened', function (event) {
        if(current_link.toLowerCase().indexOf('pdf')>=0){
            $('.slide-content').remove();
            $('.slide-error').empty();
            $(this).find(".slide").append('<iframe  style="width:100%;height:100%;" src="'+current_link+'"></iframe>');
            $(".prev").hide();
            $(".next").hide();
            $(".indicator").hide();
        }else{
            $(".prev").show();
            $(".next").show();
            $(".indicator").show();
        } 
    })
    //end
    function prepare_file_upload(){
        var file_rename_pairs = [];
        $('#fileupload tr').each(function () {
            var rename_option = $(this).find('.rename-option').val();
            //if (rename_option != "0") {
                var file = {};
                var file_name = $(this).find('p.name').text();
                file.file_name = file_name;
                file.rename_option = rename_option;

                var suffix = $(this).find("input[name='rename_suffix']").val();
                file.suffix = suffix;
                file.is_attachment = $(this).find("input[name='send_attachment']").is(':checked');
                file.is_block = $(this).find("input[name='block_files_for_email']").is(':checked'); // thienpb code
                file_rename_pairs.push(file);

            //}
        }); 

        $('#file_rename_c').val(JSON.stringify(file_rename_pairs));

    }
    //dung code - button block files for email in LEADS
    if(module_sugar_grp1 == 'Leads'){
        $("#fileupload").prepend('<button type="button" id="Block_Files_For_Email" class="button primary" title="Block Files For Email">Block Files For Email<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        $('#Block_Files_For_Email').click(function(){
            $('#fileupload tr').each(function () {
                var file_name = $(this).find('p.name').text().toLowerCase();
                if(!file_name.includes('quote') && !file_name.includes('design') ){
                    $(this).find("input[name='block_files_for_email']").prop('checked', true);
                }
            }); 
            prepare_file_upload();
        })
    }
    $( document ).ready(function() {

        $('#file_rename_c').val("");
        $('#fileupload').on('change', '.rename-option', function (e) {
            $(this).nextAll("input[name='rename_suffix']").remove();
            $(this).nextAll("#distributor_option").remove();
            if ($(this).val() == "1" || $(this).val() == "2" || $(this).val() == "8" || $(this).val() == "10" || $(this).val() == "13"
                || $(this).val() == "15" || $(this).val() == "16" || $(this).val() == "17"
                || $(this).val() == "20"
                || $(this).val() == "21"
                || $(this).val() == "22"
                || $(this).val() == "25"
                || $(this).val() == '30'
                || $(this).val() == '34'
                || $(this).val() == '36'
            ) {
                
                $(this).after('&nbsp; &nbsp; <input style="width: 30%;" placeholder="Suffix" type="text" name="rename_suffix" size="30" maxlength="30" title="" tabindex="">')
            }
            if ($(this).val() == "6" || $(this).val() == "7") {
                $(this).after('&nbsp; &nbsp; <input style="width: 30%;" placeholder="Amount" type="text" name="rename_suffix" size="30" maxlength="30" title="" tabindex="">')
            }
            if ($(this).val() == '30') {
                $(this).after('&nbsp; &nbsp;<span id="distributor_option">' + $('#distributor_c option[selected="selected"]').attr("label") +'</span>');
            }
            prepare_file_upload();
        });

        $('#fileupload').on('change', 'input[name="rename_suffix"]', function (e) {
            prepare_file_upload();
        });

        $('#fileupload').on('change', 'input[name="send_attachment"]', function (e) {
            prepare_file_upload();
        });
        //thienpb code
        $('#fileupload').on('change', 'input[name="block_files_for_email"]', function (e) {
            prepare_file_upload();
        });
        $('#fileupload').on('change', 'input[name="block_file"]', function (e) {
            if (module_sugar_grp1 == 'Leads'){
                var file_name = $(this).parent().parent().parent().find('p.name').text();
            }else {
                var file_name = $(this).parent().parent().find('p.name').text();
            }
            
            var is_block = $(this).is(':checked');
                console.log(file_name);
            $.ajax({
                url: 'index.php?entryPoint=BlockFileJSON&file_name=' + encodeURIComponent(file_name) + '&is_block=' + is_block,
                success: function(data){
                    console.log('success');
                }
            })
            
        });
        //dung code - button resize all file 
        $('#fileupload').on("click",'button[type="resize_all"]',function(event){
            event.preventDefault();   
             if(module_sugar_grp1 == 'Leads' || module_sugar_grp1 == 'AOS_Invoices') {
                var id_folder_file = $('input[name="installation_pictures_c"]').val();
             } else if(module_sugar_grp1 == 'AOS_Quotes'){
                var id_folder_file = $('input[name="pre_install_photos_c"]').val();
             } else if(module_sugar_grp1 == 'pe_warehouse_log'){
                var id_folder_file = $('input[name="installation_pdf_c"]').val();
             } else {
                var id_folder_file = '';
             }
             if(typeof(id_folder_file) == 'undefined') id_folder_file = '';
             
            $.ajax({
                url: 'index.php?entryPoint=ResizeAllFile&id_folder_file='+id_folder_file ,
                success: function(result){
                    if(result == 'success'){
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
                    }
                }
            })
        })
        $('#file_rename_c').parent().parent().hide();
        $('#file_attachment_c').parent().parent().hide();
        
        //thienpb code
        $('#block_files_for_email_c').parent().parent().hide();
        var recordID = $('input[name="installation_pictures_c"]').val();
        if (typeof recordID === "undefined")
            recordID = $('input[name="pre_install_photos_c"]').val();
        //VUT-Check folder file Service Case
        if (typeof recordID === "undefined" && module_sugar_grp1 == 'pe_service_case') {
            recordID = $('input[name="installation_photos_c"]').val();
        }
        
        if(module_sugar_grp1 == 'pe_warehouse_log'){
            if (typeof recordID === "undefined")
            recordID = $('input[name="installation_pdf_c"]').val();
        }

        if(module_sugar_grp1 == 'PO_purchase_order'){
            if (typeof recordID === "undefined")
            recordID = $('input[name="installation_pdf_c"]').val();
        }
        // Initialize the jQuery File Upload widget:
        var file_upload_name = [];
        $('#fileupload').fileupload({
            
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: '/custom/include/SugarFields/Fields/Multiupload/server/php/index.php?folder=' + recordID,
            filesContainer: $('table tbody.files'),
            uploadTemplateId: null,
            downloadTemplateId: null,
            disableImageResize:false,
            //imageMaxWidth: 800,
            imageMaxHeight: 1024,
            done: function (e, data) {
                var extFileInfos = [];
                extFileInfos = data.result.files;
                if( JSON.stringify(extFileInfos).indexOf('VBA') >= 0 ){
                    $.ajax({
                            url: "/index.php?entryPoint=APIRenameVBAtoPCOC&installation_id="+recordID,
                            type : 'POST',
                            success: function(data)
                            {     
                                var new_data = jQuery.parseJSON(data);
                                console.log(new_data);
                            }
                           
                    }); 
                }
                for( var i = 0; i < extFileInfos.length ;i++){
                    if( extFileInfos[i].name.indexOf("VBA") >= 0 ){
                        extFileInfos[i].name = extFileInfos[i].name.replace("VBA","PCOC");
                        extFileInfos[i].deleteUrl = extFileInfos[i].deleteUrl.replace("VBA","PCOC");
                        extFileInfos[i].thumbnailUrl = extFileInfos[i].thumbnailUrl.replace("VBA","PCOC");
                        extFileInfos[i].url= extFileInfos[i].url.replace("VBA","PCOC");
                    }
                }
                if (extFileInfos && $.isArray(extFileInfos))
                    for (var i = 0; i< extFileInfos.length; i++ ){
                    var fileInfo = extFileInfos[i];
                    if(fileInfo.name == 'Image_Site_Detail.jpg' && (module_sugar_grp1 == 'AOS_Quotes' || module_sugar_grp1 == 'Leads' || module_sugar_grp1 == 'AOS_Invoices')) { 
                        switch (module_sugar_grp1) {
                            case 'AOS_Quotes':
                                var folder_id  = $('body').find('input[name="pre_install_photos_c"]').val();
                                break;
                            case 'AOS_Invoices':
                                var folder_id  = $('body').find('input[name="installation_pictures_c"]').val();
                                break;
                            case 'Leads':
                                var folder_id  = $('body').find('input[name="installation_pictures_c"]').val();
                                break;
                            default:
                                break;
                        }
                      
                        var url_img = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime();
                        var image_html_site_details = '<img id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:220px;height:auto;margin-top: 5em;" onclick="showPopup()" alt="Map Template Image" src="'+url_img+'">';
                        $(document).find('#Map_Template_Image').remove();
                        $(document).find('#maptemplate-img').append(image_html_site_details);
                        var image_html_site_details_popup = '<img id="Map_Template_Image_popup" alt="Map Template Image" src="'+url_img+'">';
                        $(document).find('#Map_Template_Image_popup').remove();
                        $(document).find('#popup_image_site_detail_image').append(image_html_site_details_popup);
                    }
                    if ( typeof fileInfo.ces_cert !== "undefined" && fileInfo.ces_cert != ""){
                        if(typeof  $("#ces_cert_c") !== "undefined"){
                            $("#ces_cert_c").val(fileInfo.ces_cert);
                        }
                    }
                    // for invoice
                    if ( typeof fileInfo.carrier !== "undefined" && fileInfo.carrier != ""){
                        if(typeof  $("#carrier_c") !== "undefined"){
                            $("#carrier_c").val(fileInfo.carrier);
                        }
                    }
                    if ( typeof fileInfo.con_note !== "undefined" && fileInfo.con_note != ""){
                        if(typeof  $("#con_note_c") !== "undefined"){
                            $("#con_note_c").val(fileInfo.con_note);
                        }
                    }
                    if(module_sugar_grp1 == 'pe_warehouse_log'){
                        // for warehouse log
                        if ( typeof fileInfo.carrier !== "undefined" && fileInfo.carrier != ""){
                            if(typeof  $("#carrier") !== "undefined"){
                                $("#carrier").val(fileInfo.carrier);
                            }
                        }
                        if ( typeof fileInfo.con_note !== "undefined" && fileInfo.con_note != ""){
                            if(typeof  $("#connote") !== "undefined"){
                                $("#connote").val(fileInfo.con_note);
                            }
                        }

                        //thien code call entrypoint
                        if ( typeof fileInfo.deliver_to !== "undefined" && fileInfo.deliver_to != "" && typeof fileInfo.sold_to !== "undefined" && fileInfo.sold_to != ""){
                            $.ajax({
                                url: "/index.php?entryPoint=customGetDataForWareHouse&deliver_to="+fileInfo.deliver_to+ "&sold_to="+fileInfo.sold_to,
                                type: 'GET',
                                //dataType: 'json',
                                success: function(data)
                                {         
                                    data =  $.parseJSON(data);

                                    $("#billing_account").val(fileInfo.deliver_to);
                                    $("#billing_account_id").val(data[0].deliver_to);

                                    $("#billing_address_street").val(data[0].billing_address_street);
                                    $("#billing_address_city").val(data[0].billing_address_city);
                                    $("#billing_address_state").val(data[0].billing_address_state);
                                    $("#billing_address_postalcode").val(data[0].billing_address_postalcode);

                                    $("#shipping_account").val(fileInfo.sold_to);
                                    $("#shipping_account_id").val(data[1].sold_to);
                                    $("#shipping_address_street").val(data[1].shipping_address_street);
                                    $("#shipping_address_city").val(data[1].shipping_address_city);
                                    $("#shipping_address_state").val(data[1].shipping_address_state);
                                    $("#shipping_address_postalcode").val(data[1].shipping_address_postalcode);
                                }
                            });
                        }

                        if ( typeof fileInfo.estimate_ship_date !== "undefined" && fileInfo.estimate_ship_date != ""){
                            if(typeof  $("#estimate_ship_date") !== "undefined"){
                                $("#estimate_ship_date").val(fileInfo.estimate_ship_date);
                            }
                        }
                        
                        if ( typeof fileInfo.warehouse_order_number !== "undefined" && fileInfo.warehouse_order_number != ""){
                            if(typeof  $("#warehouse_order_number") !== "undefined"){
                                $("#warehouse_order_number").val(fileInfo.warehouse_order_number);
                            }
                        }
                        if ( typeof fileInfo.delivery_docket_rep !== "undefined" && fileInfo.delivery_docket_rep != ""){
                            if(typeof  $("#delivery_docket_rep") !== "undefined"){
                                $("#delivery_docket_rep").val(fileInfo.delivery_docket_rep);
                            }
                        }
                        //fileInfo.PO_number = '446';
                        if ( typeof fileInfo.PO_number !== "undefined" && fileInfo.PO_number != ""){
                            var url ='';
                            if($("#po_purchase_order_pe_warehouse_log_1po_purchase_order_ida").val() == '' &&  typeof $("#group0id").val() === "undefined"){
                                url = "/index.php?entryPoint=customGetPOByNumber&PO_number="+fileInfo.PO_number+"&PO_ID=&record="+$("input[name='record']").val();
                            }
                            if(url != ''){
                                $.ajax({
                                    url: url,
                                    type: 'GET',
                                    success: function(data){
                                       
                                        $("#lineItems").empty();
                                        lineno;
                                        prodln = 0;
                                        servln = 0;
                                        groupn = 0;
                    
                                        group_ids = {};
                                        var jsonObject = $.parseJSON(data);
                                        var groups = jsonObject.groups;
                                        var products = jsonObject.products;
                                        $("#pe_purchase_order_no_c").val(jsonObject.po_number);
                                        if($("#po_purchase_order_pe_warehouse_log_1po_purchase_order_ida").val()==''){
                                            $("#po_purchase_order_pe_warehouse_log_1po_purchase_order_ida").val(jsonObject.po_id);
                                            $("#po_purchase_order_pe_warehouse_log_1_name").val(jsonObject.po_name);
                                        }
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
                                });
                            }
                           
                        }
                    }
                    
                    if(module_sugar_grp1 == 'PO_purchase_order'){
                        if ( typeof fileInfo.order_number !== "undefined" && fileInfo.order_number != ""){
                            var title_po = $("#name").val(); 
                            $("#name").val(title_po.replace('TBC',fileInfo.order_number));
                        }
                    }

                    if ( typeof fileInfo.completion_date !== "undefined" && fileInfo.completion_date != ""){
                        if(typeof  $("#ces_cert_date_c") !== "undefined"){
                            var real_cert_date = fileInfo.completion_date;
                            var cer_date_splited = fileInfo.completion_date.split("/");
                            if(cer_date_splited.length == 3){
                                if(cer_date_splited[2].length == 2){
                                    cer_date_splited[2] = "20" + cer_date_splited[2];
                                    real_cert_date = cer_date_splited[0] + "/" + cer_date_splited[1] + "/" + cer_date_splited[2]
                                }
                            }
                            $("#ces_cert_date_c").val(real_cert_date);
                        }
                    }

                    if ( typeof fileInfo.completion_date !== "undefined" && fileInfo.completion_date != ""){
                        if(typeof  $("#installation_date_c") !== "undefined"){
                            var real_completion_date= fileInfo.completion_date;
                            var completion_date_splited = fileInfo.completion_date.split("/");
                            if(completion_date_splited.length == 3){
                                if(completion_date_splited[2].length == 2){
                                    completion_date_splited[2] = "20" + completion_date_splited[2];
                                    real_completion_date = completion_date_splited[0] + "/" + completion_date_splited[1] + "/" + completion_date_splited[2]
                                }
                            }
                            
                            $("#installation_date_c_date").val(real_completion_date).trigger("change");
                           
                        }
                    }

                    if ( typeof fileInfo.plumber_cert_date !== "undefined" && fileInfo.plumber_cert_date != ""){
                        if(typeof  $("#vba_pic_date_c") !== "undefined"){
                            $("#vba_pic_date_c").val(fileInfo.plumber_cert_date);
                        }
                    }
                    if ( typeof fileInfo.compliance_number !== "undefined" && fileInfo.compliance_number != ""){
                        if(typeof  $("#vba_pic_cert_c") !== "undefined"){
                            $("#vba_pic_cert_c").val(fileInfo.compliance_number);
                        }
                    }

                    //dung code - add field name by name file 
                    if ( typeof fileInfo.name !== "undefined" && fileInfo.name !== "" && module_sugar_grp1 == "pe_warehouse_log"){
                        $.ajax({
                            url: 'index.php?entryPoint=checkDuplicateDD&name='+fileInfo.name.slice(0,-4)+'&record='+$("input[name='record']").val(),
                            success: function(result){
                                if(result == 'existed'){
                                    alert("Delivery Docket is existed.");
                                }else{
                                    if( $("#name").val() == ""){
                                        $("#name").val(fileInfo.name.slice(0,-4));
                                    }
                                }
                            }
                        });
                        
                    }
                    // dung code- get VBA and CES
                    if ( typeof fileInfo.name !== "undefined" && fileInfo.name !== "" && module_sugar_grp1 == "AOS_Invoices"){
                        
                        if((typeof fileInfo.ces_cert == "undefined" || fileInfo.ces_cert == '' ) && fileInfo.name.includes('CES_') && $("#ces_cert_c").val() == ''){
                            var array_filename = fileInfo.name.split('.');
                            var number_ces = array_filename[0].split('CES_');
                            $("#ces_cert_c").val(number_ces[1]);
                        }
                        if((typeof fileInfo.compliance_number == "undefined" || fileInfo.compliance_number == '' ) && fileInfo.name.includes('PCOC_') && $("#vba_pic_cert_c").val() == ''){
                            var array_filename_vba = fileInfo.name.split('.');
                            var number_vba = array_filename_vba[0].split('PCOC_');
                            $("#vba_pic_cert_c").val(number_vba[1]);
                        }
                    }
                };

                if (e.isDefaultPrevented()) {
                    return false;
                }
                var that = $(this).data('blueimp-fileupload') ||
                        $(this).data('fileupload'),
                    getFilesFromResponse = data.getFilesFromResponse ||
                        that.options.getFilesFromResponse,
                    files = getFilesFromResponse(data),
                    template,
                    deferred;
                if (data.context) {
                    data.context.each(function (index) {
                        var file = files[index] ||
                            {error: 'Empty file upload result'};
                        deferred = that._addFinishedDeferreds();
                        that._transition($(this)).done(
                            function () {
                                var node = $(this);
                                /*template = that._renderDownload([file])
                                 .replaceAll(node);
                                 that._forceReflow(template);
                                 that._transition(template).done(
                                 function () {
                                 data.context = $(this);
                                 that._trigger('completed', e, data);
                                 that._trigger('finished', e, data);
                                 deferred.resolve();
                                 }
                                 );*/
                                node.remove();
                            }
                        );
                    });
                }
                //} else {
                template = that._renderDownload(files)[
                    that.options.prependFiles ? 'prependTo' : 'appendTo'
                    ](that.options.filesContainer);
                that._forceReflow(template);
                deferred = that._addFinishedDeferreds();
                that._transition(template).done(
                    function () {
                        data.context = $(this);
                        that._trigger('completed', e, data);
                        that._trigger('finished', e, data);
                        deferred.resolve();
                    }
                );

                addEmailWarning();
                //}
            },

            uploadTemplate: function (o) {

                var rows = $();
                // Make the selected attachment checked typeof jsonObject.stcs_number !== "undefined") 
                var file_attachmens ="";
                if (typeof $('#file_attachment_c') !== "undefined" && typeof $('#file_attachment_c').val() !== "undefined" && $('#file_attachment_c').val() != "" ){
                    file_attachmens = JSON.parse($('#file_attachment_c').val())
                }
                else {
                    file_attachmens = [];
                }
                
                //Thienpb code -  Block list file for email
                var block_files_for_email ="";
                if (typeof $('#block_files_for_email_c') !== "undefined" && typeof $('#block_files_for_email_c').val() !== "undefined" && $('#block_files_for_email_c').val() != "" ){
                    block_files_for_email = JSON.parse($('#block_files_for_email_c').val())
                }
                else {
                    block_files_for_email = [];
                }

                $.each(o.files, function (index, file) {
                    var row = $('<tr class="template-upload fade">' +
                        '<td><span class="preview"></span></td>' +
                        '<td><p class="name"></p> <br>' +
                        '<strong class="error text-danger"></strong>' +

                        '<select class="rename-option">' +
                        (( module_sugar_grp1 == 'AOS_Invoices') ? (
                                '<option value="0">- Rename Option -</option>' +
                                '<option value="1">[Invoice #] Old [#]</option>' +
                                '<option value="8">[Invoice #] New [#]</option>' +
                                '<option value="2">[Invoice #] Photo [#]</option>' +
                                '<option value="34">[Invoice #] New Install Photo[#]</option>'+
                                '<option value="3">[Invoice #] PCOC [CERT #][#]</option>' +
                                '<option value="4">[Invoice #] CES [CERT #][#]</option>' +
                                '<option value="5">[Invoice #] HP [HP_Serial][#]</option>' +
                                '<option value="9">[Invoice #] Tank [Tank_Serial][#]</option>' +
                                '<option value="6">[Invoice #] Payment [#]</option>' +
                                '<option value="7">[Invoice #] Invoice [#]</option>' +
                                '<option value="10">[Invoice #] Diagram [#]</option>' +
                                '<option value="11">[Invoice #] Old Serial[Old Tank serial] [#]</option>' +
                                '<option value="12">[Invoice #] ElectInvoice[#]</option>' +
                                '<option value="14">[Invoice #] PlumbInvoice[#]</option>' +
                                '<option value="13">[Invoice #] Switchboard[#]</option>' +
                                '<option value="19">[Invoice #] DeliveryDocket[#]</option>'+
                                '<option value="20">[Invoice #] System Owner Tax Invoice[#]</option>'+
                                '<option value="36">[Invoice #] Proposed Install Location[#]</option>'+
                                '<option value="37">[Invoice #] Remittance Advice[#]</option>'+
                                '<option value="39">[Invoice #] New Install Water Pressure Property[#]</option>'+
                                '<option value="40">[Invoice #] New Install Water Pressure NRIPRV[#]</option>'
                                ) :
                                (
                                    ( module_sugar_grp1 == 'AOS_Quotes') ? 
                                    (   '<option value="0">- Rename Option -</option>' +
                                        '<option value="15">Q[Quote #]Old[Sequence #]</option>' +
                                        '<option value="16">Q[Quote #]New[Sequence #]</option>' +
                                        '<option value="17">Q[Quote #]Diagram[Sequence #]</option>' +
                                        '<option value="18">Q[Quote #]Switchboard[Sequence #]</option>' +
                                        '<option value="33">Q[Quote #]Shipping confirmation[Sequence #]</option>'+//thienpb
                                        '<option value="20">Blank [Address]</option>' +
                                        '<option value="21">Design [Address]</option>' +
                                        '<option value="22">[Switchboard #]</option>' +
                                        '<option value="23">[Map #]</option>' +
                                        '<option value="24">[Street View #]</option>' +
                                        '<option value="25">[Bill #]</option>' +
                                        '<option value="26">Meter Box</option>' + //dung code - add option Meter Box
                                        '<option value="35">Meter [Up close #]</option>'+
                                        '<option value="27">Acceptance</option>' + 
                                        '<option value="28">Pricing</option>' +
                                        '<option value="29">House Plans</option>' +
                                        '<option value="30">Grid approval</option>'+ //dung code - add option Grid approval
                                        '<option value="32">[Pitch]</option>'+//thien code
                                        '<option value="36">Proposed Install Location[#]</option>'+
                                        '<option value="37">Remittance Advice[#]</option>'+
                                        '<option value="38">Existing HWS[#]</option>'
                                    )
                                    :(
                                        //tu code add option Methven package
                                        ( module_sugar_grp1 == 'pe_warehouse_log') ? 
                                            (
                                                '<option value="0">- Rename Option -</option>' +
                                                '<option value="31">Methven package</option>'

                                            )
                                        :(
                                            '<option value="0">- Rename Option -</option>' +
                                            '<option value="20">Blank [Address]</option>' +
                                            '<option value="21">Design [Address]</option>' +
                                            '<option value="22">[Switchboard #]</option>' +
                                            '<option value="23">[Map #]</option>' +
                                            '<option value="24">[Street View #]</option>' +
                                            '<option value="25">[Bill #]</option>' +
                                            '<option value="26">Meter Box</option>' + //dung code - add option Meter Box
                                            '<option value="27">Acceptance</option>' + 
                                            '<option value="28">Pricing</option>' +
                                            '<option value="29">House Plans</option>' +
                                            '<option value="30">Grid approval</option>'+ //dung code - add option Grid approval
                                            '<option value="32">[Pitch]</option>'+//thien code
                                            '<option value="36">Proposed Install Location[#]</option>'+
                                            '<option value="37">Remittance Advice[#]</option>'+
                                            '<option value="38">Existing HWS[#]</option>'
                                            )
                                    )
                                )
                        )
                        +
                        

                        '</select>' 
                        +
                        (( module_sugar_grp1 == 'Leads') ? '<div>\
                            &nbsp; &nbsp; \
                            <label class="">Send As Attachment</label>\
                            <input type="checkbox" name="send_attachment" checked="checked" title="Send As Attachment" id="" class="send-as-attachment" value="1" enabled="true" tabindex="0">\
                            &nbsp; &nbsp; \
                            <label class="">Block File for Email</label>\
                            <input type="checkbox" name="block_files_for_email" checked="checked" title="Block File for Email" id="" class="block_files_for_email" value="1" enabled="true" tabindex="0">\
                        ':'') +       
                            '&nbsp; &nbsp; \
                            <label class="">Block File for Download</label>\
                            <input type="checkbox" name="block_file" title="Block File for Download" id="" class="block_file" value="1" enabled="true" tabindex="0">\
                           </div>\
                        '+
                        '</td>' +
                        '<td><p class="size">Processing...</p>' +
                        '<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">' +
                        '<div class="progress-bar progress-bar-success" style="width:0%;"></div>' +
                        '</div>' +
                        '</td>' +
                        '<td>' +
                        (!index && !o.options.autoUpload ?
                            '<button class="btn btn-primary start" disabled> <i class="glyphicon glyphicon-upload"></i><span>Start</span></button>' : '') +
                        (!index ? '<button class="btn btn-warning cancel">' +
                            ' <i class="glyphicon glyphicon-ban-circle"></i><span>Cancel</span>' +
                            '</button>' : '') +
                        '</td>' +
                        '</tr>');

                    row.find('.name').text(file.name);
                    row.find('.size').text(o.formatFileSize(file.size));
                    if (file.error) {
                        row.find('.error').text(file.error);
                    }
                    rows = rows.add(row);
                });

                //addEmailWarning();
                return rows;
            },
            downloadTemplate: function (o) {
                //addEmailWarning()
                var rows = $();
                // if(file_upload_name.length > 0){
                //     if (confirm('Please remember to rename file uploaded?')) {
                //         // Save it!
                //     } else {
                //         // Do nothing!
                //     }
                // }
                var file_attachmens ="";
                if (typeof $('#file_attachment_c') !== "undefined" && typeof $('#file_attachment_c').val() !== "undefined" && $('#file_attachment_c').val() != ""){
                    file_attachmens = JSON.parse($('#file_attachment_c').val())
                }
                else {
                    file_attachmens = [];
                }

                //Thienpb code -  Block list file for email
                var block_files_for_email ="";
                if (typeof $('#block_files_for_email_c') !== "undefined" && typeof $('#block_files_for_email_c').val() !== "undefined" && $('#block_files_for_email_c').val() != "" ){
                    block_files_for_email = JSON.parse($('#block_files_for_email_c').val())
                }
                else {
                    block_files_for_email = [];
                } 

                $.each(o.files, function (index, file) {
                    var row = $('<tr class="template-download fade">' +
                        '<td><span class="preview"></span></td>' +
                        '<td><p class="name"></p>  <br>' +
                        '<button type="button" title="Roated 90&#176;" class="click_rotated">Click Rotated</button>'+
                        '<button type="button" title="Resize 15%" class="resize_image">Click Resize</button>'+
                        (file.error ? '<div class="error"></div>' : '') +

                        '<select class="rename-option">' +
                        (( module_sugar_grp1 == 'AOS_Invoices') ? (
                            '<option value="0">- Rename Option -</option>' +
                            '<option value="1">[Invoice #] Old [#]</option>' +
                            '<option value="8">[Invoice #] New [#]</option>' +
                            '<option value="2">[Invoice #] Photo [#]</option>' +
                            '<option value="34">[Invoice #] New Install Photo[#]</option>'+
                            '<option value="3">[Invoice #] PCOC [CERT #][#]</option>' +
                            '<option value="4">[Invoice #] CES [CERT #][#]</option>' +
                            '<option value="5">[Invoice #] HP [HP_Serial][#]</option>' +
                            '<option value="9">[Invoice #] Tank [Tank_Serial][#]</option>' +
                            '<option value="6">[Invoice #] Payment [#]</option>' +
                            '<option value="7">[Invoice #] Invoice [#]</option>' +
                            '<option value="10">[Invoice #] Diagram [#]</option>' +
                            '<option value="11">[Invoice #] Old Serial[Old Tank serial] [#]</option>' +
                            '<option value="12">[Invoice #] ElectInvoice[#]</option>' +
                            '<option value="14">[Invoice #] PlumbInvoice[#]</option>' +
                            '<option value="13">[Invoice #] Switchboard[#]</option>' +
                            '<option value="19">[Invoice #] DeliveryDocket[#]</option>'+
                            '<option value="20">[Invoice #] System Owner Tax Invoice[#]</option>'+
                            '<option value="36">[Invoice #] Proposed Install Location[#]</option>'+
                            '<option value="37">[Invoice #] Remittance Advice[#]</option>'+
                            '<option value="39">[Invoice #] New Install Water Pressure Property[#]</option>'+
                            '<option value="40">[Invoice #] New Install Water Pressure NRIPRV[#]</option>'
                            ) :
                            (
                                ( module_sugar_grp1 == 'AOS_Quotes') ? 
                                (   '<option value="0">- Rename Option -</option>' +
                                        '<option value="15">Q[Quote #]Old[Sequence #]</option>' +
                                        '<option value="16">Q[Quote #]New[Sequence #]</option>' +
                                        '<option value="17">Q[Quote #]Diagram[Sequence #]</option>' +
                                        '<option value="18">Q[Quote #]Switchboard[Sequence #]</option>' +
                                        '<option value="33">Q[Quote #]Shipping confirmation[Sequence #]</option>'+//thienpb
                                        '<option value="20">Blank [Address]</option>' +
                                        '<option value="21">Design [Address]</option>' +
                                        '<option value="22">[Switchboard #]</option>' +
                                        '<option value="23">[Map #]</option>' +
                                        '<option value="24">[Street View #]</option>' +
                                        '<option value="25">[Bill #]</option>' +
                                        '<option value="26">Meter Box</option>' + //dung code - add option Meter Box
                                        '<option value="35">Meter [Up close #]</option>'+
                                        '<option value="27">Acceptance</option>' + 
                                        '<option value="28">Pricing</option>' +
                                        '<option value="29">House Plans</option>' +
                                        '<option value="30">Grid approval</option>'+ //dung code - add option Grid approval
                                        '<option value="32">[Pitch]</option>'+//thien code
                                        '<option value="36">Proposed Install Location[#]</option>'+
                                        '<option value="37">Remittance Advice[#]</option>'+
                                        '<option value="38">Existing HWS[#]</option>'
                                    )
                                :(
                                    //tu code add option Methven package
                                    ( module_sugar_grp1 == 'pe_warehouse_log') ? 
                                        (
                                            '<option value="0">- Rename Option -</option>' +
                                            '<option value="31">Methven package</option>'

                                        )
                                    :(
                                        '<option value="0">- Rename Option -</option>' +
                                        '<option value="20">Blank [Address]</option>' +
                                        '<option value="21">Design [Address]</option>' +
                                        '<option value="22">[Switchboard #]</option>' +
                                        '<option value="23">[Map #]</option>' +
                                        '<option value="24">[Street View #]</option>' +
                                        '<option value="25">[Bill #]</option>' +
                                        '<option value="26">Meter Box</option>' + //dung code - add option Meter Box
                                        '<option value="27">Acceptance</option>' + 
                                        '<option value="28">Pricing</option>' +
                                        '<option value="29">House Plans</option>' +
                                        '<option value="30">Grid approval</option>'+ //dung code - add option Grid approval
                                        '<option value="32">[Roof Pitch #]</option>'+//thien code
                                        '<option value="32">[Pitch]</option>'+//thien code
                                        '<option value="36">Proposed Install Location[#]</option>'+
                                        '<option value="37">Remittance Advice[#]</option>'+
                                        '<option value="38">Existing HWS[#]</option>'
                                    )
                                )
                            )
                        )
                        +
                        '</select>' 
                        +
                        (( module_sugar_grp1 == 'Leads') ? '<div>\
                            &nbsp; &nbsp; \
                            <label class="">Send As Attachment</label>\
                            <input type="checkbox" name="send_attachment" '
                            + 
                            (($.inArray(file.name, file_attachmens )!= -1)? ' checked="checked" ':'' )
                            +
                            ' title="Send As Attachment" id="" class="send-as-attachment" value="1" enabled="true" tabindex="0">\
                            &nbsp; &nbsp; \
                            <label class="">Block File for Email</label>\
                            <input type="checkbox" name="block_files_for_email"'
                            + 
                            (($.inArray(file.name, block_files_for_email )!= -1)? ' checked="checked" ':'' )
                            +
                            'title="Block File for Email" id="" class="block_files_for_email" value="1" enabled="true" tabindex="0">\
                        ':'') +
                        '   &nbsp; &nbsp; \
                            <label class="">Block File for Download</label>\
                            <input type="checkbox" name="block_file"  title="Block File for Download" id="" class="block_file" value="1" enabled="true" tabindex="0">\
                            </div>\
                        '+
                        '</td>' +
                        '<td><span class="size"></span></td>' +
                        '<td><button class="btn btn-danger delete"> <i class="glyphicon glyphicon-trash"></i> <span>Delete </span></button> ' +
                        '<br><button type="button" class="btn btn-success download" data-type="DOWNLOAD" ><i class="glyphicon glyphicon-download-alt"></i><span>Download</span></button>' +
                        '<input type="checkbox" name="delete" value="1" class="toggle"><br/> <button class="btn btn-danger edit-image"> <i class="glyphicon glyphicon-edit"></i> <span>Edit </span></button> </td>' +
                        '' +
                        '</tr>');

                    row.find('.size').text(o.formatFileSize(file.size));
                    if (file.error) {
                        row.find('.name').text(file.name);
                        row.find('.error').text(file.error);
                    } else {
                        row.find('.name').append($('<a></a>').text(file.name));
                        if (file.thumbnailUrl) {
                            row.find('.preview').append(
                                $('<a></a>').append(
                                    $('<img>').prop('src', file.thumbnailUrl)
                                )
                            );
                        }

                        // Thienpb add code for open pdf
                        var group_type = '';
                        if(file.url.toLowerCase().indexOf('pdf')>=0){
                            group_type = 'pdf';
                        }else if(file.url.toLowerCase().indexOf('jpg') >= 0 || file.url.toLowerCase().indexOf('jpeg') >= 0 ||file.url.toLowerCase().indexOf('gif') >= 0 || file.url.toLowerCase().indexOf('png') >= 0){
                            group_type = 'image';
                        }else{
                            group_type = '';
                        }

                        row.find('a')
                            .attr('data-gallery',group_type)
                            .prop('href', file.url);
                        row.find('button.delete')
                            .attr('data-type', file.deleteType)
                            .attr('data-url', file.deleteUrl + "&folder=" + recordID);
                        row.find('button.download')
                            .attr('file-name',file.name)
                            .attr('data-url', file.url);
                        row.find('button.edit-image')
                            .attr('data-url', file.url);
                        if( module_sugar_grp1 == 'AOS_Quotes' ) {
                            if(file.name.indexOf('switchboard') >=0 ){
                                row.find('.rename-option option[value="18"]')
                                    .attr("selected", 'selected');
                            }else if(file.name.indexOf('Electricity_bill') >=0 ){
                                row.find('.rename-option option[value="25"]')
                                    .attr("selected", 'selected');
                            }else if(file.name.indexOf('meterbox') >=0 ){
                                row.find('.rename-option option[value="26"]')
                                    .attr("selected", 'selected');
                            }else if(file.name.indexOf('upclose') >=0 ){
                                row.find('.rename-option option[value="35"]')
                                    .attr("selected", 'selected');
                            }
                        }
                        
                        
                        //end
                    }
                   
                    rows = rows.add(row);
                    
                });
                //addEmailWarning();
                //$('#fileupload table').tableDnD();
                return rows;
            },
            change: function (e, data) {
                $.each(data.files, function (index, file) {
                    file_upload_name.push(file.name);
                });
            }
        });
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');

        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
            // auto open panel "FILES AND PHOTOS" when data in module Quotes
            if (typeof result.files !== 'undefined' && result.files.length > 0 && module_sugar_grp1 == 'AOS_Quotes') {
                var id_file_and_photo = '#' + $(this).parents('div[class^="panel-body"]').eq(0).attr('id');
                $(id_file_and_photo).addClass('in');
                $(id_file_and_photo).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
                prepare_file_upload();
            }
            
        });

        $("#tentative_c").parent().append('<i> &nbsp;&nbsp; (Click Tentative before entering date) </i>');
    });

});


//dung code - rotated image
$(document).ready(function(){
    var current_img_click = "";
    var Object_img ;
    var Obejct_thub;
    
    $(document).on('click','.click_rotated',function(){
        var URL_file_img = $(this).parent().find('a').attr('href');
        var URL_file_img_thub = $(this).parent().parent().first().find('span a img').attr('src');
         Object_img = $(this).parent().parent().first().find('span a');
         Obejct_thub = $(this).parent().parent().first().find('span a img');
        if(current_img_click == URL_file_img || current_img_click == ''){
            current_img_click = URL_file_img;
            $(this).after('<p id="id_loading">Loading...</p>');
            $.ajax({
                url: 'index.php?entryPoint=CustomInsertRotate&url_img='+URL_file_img,
                success: function(data){ 
                    var randomNumber = Math.random().toString(36).substring(2, 15);
                    Object_img.attr('href',URL_file_img+'?'+randomNumber);
                    Obejct_thub.attr('src',URL_file_img_thub+'?'+randomNumber);
                    $('#id_loading').remove();
                }
            });
        }else {
            current_img_click = '';
            current_img_click = URL_file_img;
            $(this).after('<p id="id_loading">Loading...</p>');
            $.ajax({
                url: 'index.php?entryPoint=CustomInsertRotate&url_img='+URL_file_img,
                success: function(data){
                    var randomNumber = Math.random().toString(36).substring(2, 15);
                    Object_img.attr('href',URL_file_img+'?'+randomNumber);  
                    Obejct_thub.attr('src',URL_file_img_thub+'?'+randomNumber);
                    $('#id_loading').remove();      
                }
            });
        }
    })

    $(document).on('click','button.download',function(){

        var URL_file_img = $(this).attr('data-url');
        var filename = $(this).attr('file-name');
        var element = document.createElement('a');
        element.setAttribute('href', URL_file_img);
        element.setAttribute('download', filename);
        element.setAttribute('custom_link_download', filename);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    })
})

//dung code - clip board 
/**
 * This handler retrieves the images from the clipboard as a blob and returns it in a callback.
 * 
 * @see http://ourcodeworld.com/articles/read/491/how-to-retrieve-images-from-the-clipboard-with-javascript-in-the-browser
 * @param pasteEvent 
 * @param callback 
 */
$(document).ready(function(){
    $('#fileupload .fileupload-buttonbar').append('<canvas hidden id="clipboard"></canvas>');
})
function retrieveImageFromClipboardAsBlob(pasteEvent, callback){
	if(pasteEvent.clipboardData == false){
        if(typeof(callback) == "function"){
            callback(undefined);
        }
    };

    var items = pasteEvent.clipboardData.items;

    if(items == undefined){
        if(typeof(callback) == "function"){
            callback(undefined);
        }
    };

    for (var i = 0; i < items.length; i++) {
        // Skip content if not image
        if (items[i].type.indexOf("image") == -1) continue;
        // Retrieve image on clipboard as blob
        var blob = items[i].getAsFile();
        if(typeof(callback) == "function"){
            callback(blob);
        }
    }
}

window.addEventListener("paste", function(e){
    if($(document).find(".fancybox-iframe").length != 0) return;
    var content_image = '';
    // Handle the event
    retrieveImageFromClipboardAsBlob(e, function(imageBlob){
        // If there's an image, display it in the canvas
        
        if(imageBlob){
            
            var canvas = document.getElementById("clipboard");
            var ctx = canvas.getContext('2d');
            // Create an image to render the blob on the canvas
            var img = new Image();

            // Once the image loads, render the img on the canvas
            img.onload = function(){
                // Update dimensions of the canvas with the dimensions of the image
                canvas.width = this.width;
                canvas.height = this.height;
                // canvas.width = '200';
                // canvas.height = '100';

                // Draw the image
                ctx.drawImage(img, 0, 0);
                if(module_sugar_grp1 == 'AOS_Quotes') {
                    var generateUUID =  $('input[name="pre_install_photos_c"]').val();
                }else{
                    var generateUUID = $('input[name="installation_pictures_c"]').val();
                }               
                var dataURL = canvas.toDataURL("image/png");
                $.ajax({
                    type: "POST", 
                    url: "index.php?entryPoint=CustomClipBoard", 
                    data: { img: dataURL, id: generateUUID}      
                 }).done(function(msg){ 
                    var result = JSON.parse(msg);
                    if(result['img'] !== '' && result['thub'] !=='') {
                        var html_clipboard = $('<tr class="template-download fade in">' +
                        '<td><span class="preview">'+
                            '<a href="' + result['img'] +'" title="'+ result['img_name']+'" download="'+ result['img_name']+'">'
                                +'<img src="' + result['thub'] +'">'
                            +'</a>'+
                        '</span></td>' +
                        '<td><p class="name">'
                            +'<a href="'+ result['img'] +'" title="'+ result['img_name']+'" download="'+result['img_name']+'">'+result['img_name']+'</a>'
                            +'<input hidden="" class="group_img_link" name="group_img[]" value="' + result['img']+'">'                
                        +'</p>  <br>' +
                        '<button type="button" title="Roated 90&#176;" class="click_rotated">Click Rotated</button>'+
                        '<button type="button" title="Resize 15%" class="resize_image">Click Resize</button>'+
                        '<select class="rename-option">' +
                        (( module_sugar_grp1 == 'AOS_Invoices') ? (
                            '<option value="0">- Rename Option -</option>' +
                            '<option value="1">[Invoice #] Old [#]</option>' +
                            '<option value="8">[Invoice #] New [#]</option>' +
                            '<option value="2">[Invoice #] Photo [#]</option>' +                           
                            '<option value="34">[Invoice #] New Install Photo[#]</option>'+
                            '<option value="3">[Invoice #] PCOC [CERT #][#]</option>' +
                            '<option value="4">[Invoice #] CES [CERT #][#]</option>' +
                            '<option value="5">[Invoice #] HP [HP_Serial][#]</option>' +
                            '<option value="9">[Invoice #] Tank [Tank_Serial][#]</option>' +
                            '<option value="6">[Invoice #] Payment [#]</option>' +
                            '<option value="7">[Invoice #] Invoice [#]</option>' +
                            '<option value="10">[Invoice #] Diagram [#]</option>' +
                            '<option value="11">[Invoice #] Old Serial[Old Tank serial] [#]</option>' +
                            '<option value="12">[Invoice #] ElectInvoice[#]</option>' +
                            '<option value="14">[Invoice #] PlumbInvoice[#]</option>' +
                            '<option value="13">[Invoice #] Switchboard[#]</option>' +
                            '<option value="19">[Invoice #] DeliveryDocket[#]</option>'+
                            '<option value="20">[Invoice #] System Owner Tax Invoice[#]</option>'+
                            '<option value="36">[Invoice #] Proposed Install Location[#]</option>'+
                            '<option value="37">[Invoice #] Remittance Advice[#]</option>'+
                            '<option value="39">[Invoice #] New Install Water Pressure Property[#]</option>'+
                            '<option value="40">[Invoice #] New Install Water Pressure NRIPRV[#]</option>'
                            ) :
                            (
                                ( module_sugar_grp1 == 'AOS_Quotes') ? 
                                (   '<option value="0">- Rename Option -</option>' +
                                        '<option value="15">Q[Quote #]Old[Sequence #]</option>' +
                                        '<option value="16">Q[Quote #]New[Sequence #]</option>' +
                                        '<option value="17">Q[Quote #]Diagram[Sequence #]</option>' +
                                        '<option value="18">Q[Quote #]Switchboard[Sequence #]</option>' +
                                        '<option value="33">Q[Quote #]Shipping confirmation[Sequence #]</option>'+//thienpb
                                        '<option value="20">Blank [Address]</option>' +
                                        '<option value="21">Design [Address]</option>' +
                                        '<option value="22">[Switchboard #]</option>' +
                                        '<option value="23">[Map #]</option>' +
                                        '<option value="24">[Street View #]</option>' +
                                        '<option value="25">[Bill #]</option>' +
                                        '<option value="26">Meter Box</option>' + //dung code - add option Meter Box
                                        '<option value="35">Meter [Up close #]</option>'+
                                        '<option value="27">Acceptance</option>' + 
                                        '<option value="28">Pricing</option>' +
                                        '<option value="29">House Plans</option>' +
                                        '<option value="30">Grid approval</option>'+ //dung code - add option Grid approval
                                        '<option value="32">[Pitch]</option>'+//thien code
                                        '<option value="36">Proposed Install Location[#]</option>'+
                                        '<option value="37">Remittance Advice[#]</option>'+
                                        '<option value="38">Existing HWS[#]</option>'
                                    )
                                :(
                                    //tu code add option Methven package
                                    ( module_sugar_grp1 == 'pe_warehouse_log') ? 
                                        (
                                            '<option value="0">- Rename Option -</option>' +
                                            '<option value="31">Methven package</option>'

                                        )
                                    :(
                                        '<option value="0">- Rename Option -</option>' +
                                        '<option value="20">Blank [Address]</option>' +
                                        '<option value="21">Design [Address]</option>' +
                                        '<option value="22">[Switchboard #]</option>' +
                                        '<option value="23">[Map #]</option>' +
                                        '<option value="24">[Street View #]</option>' +
                                        '<option value="25">[Bill #]</option>' +
                                        '<option value="26">Meter Box</option>' + //dung code - add option Meter Box
                                        '<option value="27">Acceptance</option>' + 
                                        '<option value="28">Pricing</option>' +
                                        '<option value="29">House Plans</option>' +
                                        '<option value="30">Grid approval</option>'+ //dung code - add option Grid approval
                                        '<option value="32">[Pitch]</option>'+//thien code
                                        '<option value="36">Proposed Install Location[#]</option>'+
                                        '<option value="37">Remittance Advice[#]</option>'+
                                        '<option value="38">Existing HWS[#]</option>'
                                    )
                                )
                            )
                        )
                        +
                        '</select>' 
                        +
                        (( module_sugar_grp1 == 'Leads') ? '<div>\
                            &nbsp; &nbsp; \
                            <label class="">Send As Attachment</label>\
                            <input type="checkbox" name="send_attachment"'
                            +' title="Send As Attachment" id="" class="send-as-attachment" value="1" enabled="true" tabindex="0">\
                            &nbsp; &nbsp;\
                            <label class="">Block File for Email</label>\
                            <input type="checkbox" name="block_files_for_email" title="Block File for Email" id="" class="block_files_for_email" value="1" enabled="true" tabindex="0">\
                        ':'') +          
                            '&nbsp; &nbsp; \
                            <label class="">Block File for Download</label>\
                            <input type="checkbox" name="block_file"  title="Block File for Download" id="" class="block_file" value="1" enabled="true" tabindex="0">\
                           </div>\
                        '+
                        '</td>' +
                        '<td><span class="size">Clip Board</span></td>' +
                        '<td><button class="btn btn-danger delete" data-type="DELETE" data-url="/custom/include/SugarFields/Fields/Multiupload/server/php/index.php?file='+ result['img_name']+'&folder='+generateUUID+'"><i class="glyphicon glyphicon-trash"></i><span>Delete </span></button>'
                        +'<br><button type="button" class="btn btn-success download" data-type="DOWNLOAD" file-name="'+ result['img_name']+'" data-url="'+document.location.origin+'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+generateUUID+'/'+ result['img_name']+'"><i class="glyphicon glyphicon-download-alt"></i><span>Download</span></button>'
                        +'<input type="checkbox" name="delete" value="1" class="toggle"><br/> <button class="btn btn-danger edit-image"> <i class="glyphicon glyphicon-edit"></i> <span>Edit </span></button> </td>' +
                        '' +
                        '</tr>');

                        $('table[role="presentation"] .files').append(html_clipboard);
                        $('#send_message').prop('disabled', false);
                    }
                 });
            };

            // Crossbrowser support for URL
            var URLObj = window.URL || window.webkitURL;

            // Creates a DOMString containing a URL representing the object given in the parameter
            // namely the original Blob
            img.src = URLObj.createObjectURL(imageBlob);

        }  
    });
}, false);


//dung code - function Resize image 
var Object_img_resize;
$(document).ready(function(){
    $(document).on('click','.resize_image',function(){
        var URL_file_img_resize = $(this).parent().find('a').attr('href');
        Object_img_resize;
        Object_img_resize = $(this).parent().parent().find('td:nth-child(3) span[class="size"]'); 
        $(this).after('<p id="id_loading">Loading...</p>');
        $.ajax({
            url: 'index.php?entryPoint=CustomResizeImage&url_img='+URL_file_img_resize,
            success: function(data){ 
                if(data !== 'ERROR'){
                    Object_img_resize.text(data);
                    $('#id_loading').remove();
                }else{
                    alert("Image vary Small.Can't not resize");
                    $('#id_loading').remove();
                    return;
                }
            }
        });
        
    });
});

