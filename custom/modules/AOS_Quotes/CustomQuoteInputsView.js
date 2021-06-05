// .:nhantv:. Solar Products need to calculate on back-end
const solarProductCal = {};
solarProductCal["Solar_PV_Site_Visit_Module_Load_Up"] = "PV-SV-MLU";
solarProductCal["Single_Phase_1Ph_Inverter_Installation"] = "PV-1Ph-Inverter_Install";
solarProductCal["Standard_PV_Module_Installation"] = "PV-Module-Installation";
solarProductCal["Solar_PV_Balance_of_System"] = "PV-BOS";
solarProductCal["Smart_Meter_Solar_Monitoring_Installation"] = "PV-SM-Solar-Monitoring-In";

/** JS LOAD CUSTOM QUOTE INPUT  */
    $(function () {
        'use strict';
        // .:nhantv:. Update to show "Choose combination" field
        $("#quote_note_inputs_c, #quote_input_daikin_data_c").closest('.edit-view-row-item').hide();
        var html_group_custom_quote_inputs = 
        '<div id="group_custom_quote_inputs_checklist" class="row detail-view-row"></div>';
        var html_group_custom_quote_extra = 
        '<div id="group_custom_quote_inputs_checklist_extra" class="row detail-view-row"></div>';
        // .:nhantv:. Update to set order before "Choose combination" field
        $("#quote_note_inputs_c").closest('.tab-content').prepend(html_group_custom_quote_inputs, html_group_custom_quote_extra);
        var btn_generate_quote = '<button type="button" id="generate_quote" class="button primary">Save and Generate Quote</button>';
        $("#quote_note_inputs_c").closest('.tab-content').append(btn_generate_quote);
        
        switch($("#quote_type_c").val()){
            case "quote_type_solar":
                renderQuoteInputHTML('quote_type_solar');
                break;
            case "quote_type_off_grid_system":
                renderQuoteInputHTML('quote_type_solar');
                break;
            case "quote_type_sanden":
                renderQuoteInputHTML('quote_type_sanden');
                renderQuoteInputExtra('quote_type_sanden');
                break;
            default: break;
        }

        $("#quote_type_c").on("change", function(){
            SUGAR.ajaxUI.showLoadingPanel();
            $("#quote_note_inputs_c").val("");
            if($(this).val() == "quote_type_solar"){
                renderQuoteInputHTML('quote_type_solar');
                init_table_own_solar();
                getDataProductSolar();
                getOwnSolarPricing($("#solar_pv_pricing_input_c").val(), 'solar_input');
            }else if($(this).val() == "quote_type_sanden"){
                renderQuoteInputHTML('quote_type_sanden');
                $('body').find("#own_solar_pv_pricing_table").remove();
                // dynamic();
            } else {
                $('#group_custom_quote_inputs_checklist .edit-view-row').empty();
                $('body').find("#own_solar_pv_pricing_table").remove();
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        });

        $("#generate_quote").on('click',function(){
            let productType = $("#quote_type_c").val();
            switch (productType) {
                case "quote_type_solar":
                    generateLineItem();
                    generateJSONForInput();
                    break;
                case "quote_type_sanden":
                    generateJSONForInput();
                    generate_quote_by_input('quote_type_sanden');
                    // dynamic();
                    break;
                case "quote_type_off_grid_system":
                    generateOffgridItem();
                    break;
                default: break;
            }
        });

        $(document).on("change","#group_custom_quote_inputs_checklist .custom_fields",function(){
            generateJSONForInput();
        });

        // .:nhantv:. Hide solar panel when Product Type !== Solar
        hideSolarPanel();

        // .:nhantv:. Grand Total on change
        var old_total_amount = 0;
        $(document).on('focusin', "#total_amount", function(){
            old_total_amount = get_value('total_amount');
        }).on('change', "#total_amount", function(){
            let new_total_amount = get_value('total_amount');
            let product0 = $('.product_group').find('tbody[id*=product_body]:visible')[0];
            let list0 = $(product0).find('input[id*=product_product_list_price]');
            let qty = get_value($(product0).find('input[id*=product_product_qty]').attr('id'));
            let old_price = get_value($(product0).find('input[id*=product_product_list_price]').attr('id'));
            set_value(list0.attr('id'), old_price - (((old_total_amount - new_total_amount) / 1.1)/qty));
            list0.trigger("blur");
        });
    });
    // Tri 
    function dynamic(){
        $('body').on('change', '#group_custom_quote_inputs_checklist select.custom_fields', function() {
            var choosed = $('option:selected', this).attr('data-value-item').replace('-','').replace('(', '').replace(')', '').replace(/\s+/g, '_').toLowerCase();
            var next_step = $(this).parent().attr('data-next-step');
            if(next_step != '') {
                $('#'+next_step).closest('.edit-view-row-item').show();
            }
            switch(choosed) {
                case "mains_water": case "tank_water": case "mains_tank": 
                    if(next_step != '') {
                        $('#'+next_step).closest('.edit-view-row-item').show();
                    }
                    break;
                case "1": case "2": 
                    break;
                case "sanden_315fqs": case "sanden_300fqs": case "sanden_250fqs": case "sanden_160fqs": case "sanden_315fqv": 
                    
                    break;
                case "yes_plumbing":
                    break;
                case "no_quick_connection_kit": 
                    break;
                case "15mm_quick_connection_kit_qik15":  case "20mm_quick_connection_kit_qik25": 
                    break;
                case "15mm_quick_connection_kit_qik15":  case "20mm_quick_connection_kit_qik25": 
                    break;
                case "yes_electrical":
                    break;
            };
                
        })
    }
    // .:nhantv:. Init select Option checkbox and line item
    function initOptionAndGenLineItem(){
        // init Options
        let quote_note_inputs_c = $('#quote_note_inputs_c').text();
        let ownSolarPvJSON = quote_note_inputs_c ? JSON.parse(quote_note_inputs_c) : {};
        var data;
        for (let key in ownSolarPvJSON) {  
            if(key.indexOf('own_sl_option') !== -1) {
                // Set Own Select Option
                $('#'+key).prop('checked', ownSolarPvJSON[key]);
                // Set PV Pricing Section
                data = $('#'+key).attr('data-attr');
                $('#sl_option_'+data).prop('checked', ownSolarPvJSON[key]);
            }
        }
    }

    // .:nhantv:. Generate Line Item from Quote Options
    async function generateLineItem() {

        SL_saveCurrentState();

        // Get Option Quote
        let optSelected = $('input[name="solar_option"]:checked').attr('data-attr');
        if (!optSelected){
            alert("You must choose the Option to generate line item");
            return;
        }
        // Show loading
        SUGAR.ajaxUI.showLoadingPanel();
        // Mark line deleted
        for (var i = 0; i < prodln; i++){
            markLineDeleted(i,"product_");
        }
        // Check exist any line item
        if ($("#lineItems").find(".group_body").length == 0){
            insertGroup(0);
            $("#group0name").val("Solar");
        } else {
            $("#group_body"+($("#lineItems").find(".group_body").length -1)).show();
        }

        let currState = SL_getCurrentOptionState(optSelected);

        // Get value
        // let panelSelected = $('#panel_sl_type_' + optSelected).val();
        // let panelTotal = $('#total_sl_panels_' + optSelected).val();
        // let totalKw = $('#total_sl_kW_' + optSelected).val();
        // let inverterSelected = $('#inverter_sl_type_' + optSelected).val();
        // let extra1Selected = $('#sl_accessory1_' + optSelected).val();
        // let extra2Selected = $('#sl_accessory2_' + optSelected).val();
        // let stcTotal = $('#number_sl_stcs_' + optSelected).val();
        // Create line item
        try{
            // Alway add this product: Solar PV Supply and Install
            await autoCreateLineItem("Solar PV Supply and Install", solar_extra, 1);
            currState.panel_type && await autoCreateLineItem(currState.panel_type, sol_panel, currState.total_panels);
            currState.solar_inverter && await autoCreateLineItem(currState.solar_inverter, sol_inverter, 1);

            currState.accessory1 && await autoCreateLineItem(currState.accessory1, sol_accessory, 1);
            currState.accessory2 && await autoCreateLineItem(currState.accessory2, sol_accessory, 1);

            await autoCreateLineItem("Solar PV Balance Of System", solar_extra, 1, currState.total_kw * 1000);
            await autoCreateLineItem("Solar PV Standard Install", solar_extra, 1, currState.total_kw);
            // Alway add this product: STCs
            await autoCreateLineItem("STCs", solar_extra, currState.number_stcs);
            // Calculate
            await calculatePrice(currState.total_panels, currState.total_kw, currState);

        } catch(err) {
            console.log(err);
        } finally {
            // Hide loading
            setTimeout(function (){
                SUGAR.ajaxUI.hideLoadingPanel();
            }, 300);
        }
    }
    // .:nhantv:. Calculate total price
    async function calculatePrice(panelTotal, totalKw, currState = {}){
        // await wait(200);
        let productVisible = $('.product_group').find('tbody[id*=product_body]:visible');
        var totalList = 0, totalDiscount = 0, totalAmount = 0;
        var list, dis, amount, tax;
        // For each
        productVisible.each((index, el) => {
            // get target
            list = $(el).find('input[id*=product_product_list_price]');
            dis = $(el).find('input[id*=product_product_discount]');
            amount = $(el).find('input[id*=product_product_total_price]');
            tax = $(el).find('select[id*=product_vat]');
    
            if(index !== 0 && index < productVisible.length - 1){
                // calculate line item exclude first line and last line
                totalList += get_value(list.attr('id'));
                totalDiscount += get_value(dis.attr('id'));
                totalAmount += get_value(amount.attr('id'));
                set_value(list.attr('id'), "");
                $(tax).val('0.0');
            } else if (index == productVisible.length - 1){
                // reset last line Tax to 0%
                $(tax).val('0.0');
            }
            // blur
            list.trigger("blur");
        });
        
        // // Sunpower Split Panel Fee
        // if (currState.panel_type.toLowerCase().indexOf("sunpower") != -1) {
        //     totalAmount += parseFloat(getAttributeFromName(extra_solar_products[2], solar_extra, "cost"));
        // }
    
        // PE Admin
        totalAmount += totalAmount * (parseFloat($('#sl_pe_admin_percent').val()) / 100);
    
        // PM price
        if(currState.pm != undefined && currState.pm != ''){
            totalAmount += parseFloat(currState.pm);
        }
    
        // Set value to first line
        list = $(productVisible[0]).find('input[id*=product_product_list_price]');
        set_value(list.attr('id'), totalAmount);
        list.trigger("blur");
    
        // Set value to grand total
        $("#total_amount").trigger("focusin");
        let grandTotal = $("#total_amount").val();
        $("#total_amount").val(parseFloat(roundTo90(grandTotal)).formatMoney(2, ',', '.'));
        $("#total_amount").trigger("change");
    
        // Scroll top offset
        jQuery('html,body').animate({scrollTop: jQuery('.panel-heading a div:contains(Line Items)').offset().top - 200}, 500);
    }

    // .:nhantv:. Round to 90
    function roundTo90(val){
        let strNum = typeof val == "String" ? val.replaceAll(',','').split('.')[0] : val.toString().replaceAll(',','').split('.')[0];
        let firstDigit = strNum.substr(0, strNum.length - 2);
        let last2Digit = strNum.substr(strNum.length - 2, strNum.length);
        // alway UP if last 2 digit > 90
        if(parseInt(last2Digit) > 90){
            return ((parseFloat(firstDigit) + 1) * 100).toFixed(2);
        }
        // round to **90.00
        return parseFloat(firstDigit + "90.00").toFixed(2);
    }
    // .:nhantv:. Get Product Line Item info 
    // async function autoCreateLineItem(shortName, target, total_item, total_kw = 1){
    //     let productInfo = {};
    //     // Get Product info by Short name
    //     await $.ajax({
    //         url: "/index.php?entryPoint=APIGetProductInfoByShortName&short_name=" + shortName,
    //         type: 'GET'})
    //     .then(function(data) {
    //         if(data !== undefined || data !== ""){
    //             productInfo = JSON.parse(data);
    //         }
    //     });
    //     // Case: id == ""
    //     if(productInfo.id === undefined || productInfo.id === ""){
    //         return;
    //     }
    //     // Case: id !== ""
    //     await $.ajax({
    //         url: "/index.php?entryPoint=getInfoProduct&product_id=" + productInfo.id,
    //         type: 'GET'})
    //         .then(function(data) {
    //             var info_pro = JSON.parse(data);
    //             if(info_pro['product_product_id'] !== undefined){
    //                 insertProductLine('product_group0', '0');
    //                 lineno  = prodln-1;
    //                 var popupReplyData = {};
    //                 popupReplyData.form_name = "EditView";
    //                 var name_to_value_array = {};
    //                 name_to_value_array["product_currency"+lineno] = info_pro['product_currency'];
    //                 name_to_value_array["product_item_description"+lineno] = info_pro['product_item_description'];
    //                 name_to_value_array["product_name"+lineno] = info_pro['product_name'];
    //                 name_to_value_array["product_part_number"+lineno] =  info_pro['product_part_number'];
    //                 name_to_value_array["product_product_cost_price"+lineno] = info_pro['product_product_cost_price'];
        
    //                 name_to_value_array["product_product_id"+lineno] = info_pro['product_product_id'];
    //                 name_to_value_array["product_product_list_price"+lineno] = "" + (info_pro['product_product_cost_price'] * parseFloat(total_kw));
    //                 name_to_value_array["product_product_qty"+lineno] = "" + parseFloat(total_item);
    //                 popupReplyData["name_to_value_array"] = name_to_value_array;            
    //                 $('#product_product_list_price'+lineno).focus();
    //                 $('#product_product_id'+lineno).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ productInfo.id +'">Link</a></div>');
    //                 setProductReturn(popupReplyData);
    //             }
    //         });
    // }

    // .:nhantv:. Get Product Line Item info 
    async function autoCreateLineItem(shortName, target, total_item, total_kw = 1){
        var info_pro = getItemFromName(shortName, target);
        if(info_pro['id'] !== undefined){
            insertProductLine('product_group0', '0');
            lineno  = prodln-1;
            var popupReplyData = {};
            popupReplyData.form_name = "EditView";
            var name_to_value_array = {};
            name_to_value_array["product_currency"+lineno] = info_pro['currency'];
            name_to_value_array["product_item_description"+lineno] = info_pro['description'];
            name_to_value_array["product_name"+lineno] = info_pro['name'];
            name_to_value_array["product_part_number"+lineno] =  info_pro['part_number'];
            name_to_value_array["product_product_cost_price"+lineno] = info_pro['cost'];

            name_to_value_array["product_product_id"+lineno] = info_pro['id'];
            name_to_value_array["product_product_list_price"+lineno] = "" + (info_pro['cost'] * parseFloat(total_kw));
            name_to_value_array["product_product_qty"+lineno] = "" + parseFloat(total_item);
            popupReplyData["name_to_value_array"] = name_to_value_array;            
            $('#product_product_list_price'+lineno).focus();
            $('#product_product_id'+lineno).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ info_pro['id'] +'">Link</a></div>');
            setProductReturn(popupReplyData);
        }
    }
    
    // .:nhantv:. Wait function
    const wait = ms => {
        return new Promise(res => setTimeout(res, ms));
    };

    // .:nhantv:. If Product Type is not Solar -> hide all solar panels: "SOLARGAIN INFORMATION" / "PRICING PV SECTION" / "SOLAR VICTORIA PROVIDER STATEMENT"
    function hideSolarPanel(){
        let product_type = $("#quote_type_c").val();
        if ($.inArray(product_type, ["quote_type_solar"]) == -1 ){
            // Hide panels
            $('.panel-default a div:visible').each((index, item) =>{
                var divText = item.innerText.toUpperCase();
                if(divText == 'SOLARGAIN INFOMATION' || divText == 'PRICING PV SECTION' || divText == 'SOLAR VICTORIA PROVIDER STATEMENT'){
                    // console.log('true', divText, jQuery(item).closest('.panel.panel-default'));
                    $(item).closest('.panel.panel-default').hide();
                }
            });
            // Hide field: "Choose Panel/Inverter Combination"
            $("#pricing_option_type_c").closest('.edit-view-row-item').hide();
        }
    }

    function renderQuoteInputHTML(type){
        
        $.ajax({
            url: '/index.php?entryPoint=APIRenderListQuoteInputs&type='+type,
            success: function (result) {
                try {
                    var json_data = JSON.parse(result);
                    $("#group_custom_quote_inputs_checklist").empty().append(json_data['template_html']);
                } catch (error) {
                    console.log(error)
                }
            }
        }).done(function (data) {
            parseJSONValueToFields();
            SUGAR.ajaxUI.hideLoadingPanel();
        });
    }
    function renderQuoteInputExtra(type){
        $.ajax({
            url: '/index.php?entryPoint=APIRenderExtraField&type='+type,
            success: function (result) {
                try {
                    var json_data = JSON.parse(result);
                    $("#group_custom_quote_inputs_checklist_extra").empty().append(json_data['template_html']);
                } catch (error) {
                    console.log(error)
                }
            }
        }).done(function (data) {
            parseJSONValueToFieldsExtra();
            SUGAR.ajaxUI.hideLoadingPanel();
        });
    }

    function generate_quote_by_input(dataType){
        SUGAR.ajaxUI.showLoadingPanel();
        if(dataType == "quote_type_sanden") {
            var data = new Object();
            var checkList =  $("#group_custom_quote_inputs_checklist .custom_fields");
            data['quote_generate_type'] = 'bySuite';
            data['quote_id'] = $('input[name=record]').val();
            checkList.each(function() {
                data[$(this).attr('id')] = $(this).val();
            });
            data['quote_sanden_extra'] = {};
            $("#group_custom_quote_inputs_checklist_extra .item-extras").each(function (){
                var id = $(this).attr('id');
                var partNumber = $(this).attr('data-partnumber');
                data['quote_sanden_extra'][id] = {};
                data['quote_sanden_extra'][id]['partnumber'] = partNumber;
                $('#'+id+' .custom_fields').each(function (){
                    // data['quote_sanden_extra'][id] = {};
                    var id_extra = $(this).attr('id');
                    if($('#'+id_extra).attr('type') == 'checkbox') {
                        if( $('#'+id_extra).is(":checked") == true){
                            var ob = {[id_extra]: 'yes'};
                            data['quote_sanden_extra'][id] = Object.assign(data['quote_sanden_extra'][id], ob);
                        } else {
                            var ob = {[id_extra]: 'no'};
                            data['quote_sanden_extra'][id] =  Object.assign(data['quote_sanden_extra'][id], ob);
                        }
                    } else {
                        var ob = {[id_extra]: $('#'+id_extra).val()};
                        data['quote_sanden_extra'][id] =  Object.assign(data['quote_sanden_extra'][id], ob);
                    }
                })
            });
            if(data != '') {
                $.ajax({
                    type: "POST",
                    url: '/index.php?entryPoint=APICreateQuoteNewFromSandenPE',
                    data: data,
                    success: function (result) {
                        try {
                            var data_quote = JSON.parse(result);
                            $("#name").val(data_quote['quote_name'])
                            $("#EditView input[name='action']").val('Save');
                            $.ajax({
                                type: $("#EditView").attr('method'),
                                url: $("#EditView").attr('action'),
                                data: $("#EditView").serialize(),
                                success: function (data) {
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                }
                            });
                        } catch (error) {
                            console.log(error)
                        }
                    }
                }).done(function (){
                    location.reload();
                });
            }
        }else{

        }        
    }

    function parseJSONValueToFields(){
        if ($("#quote_note_inputs_c").val() == '')  return;
        var dataJSON = JSON.parse($("#quote_note_inputs_c").val());
        for (let key in dataJSON) {
            if(typeof(dataJSON[key]) === Boolean){
                $("#"+key).prop('checked', dataJSON[key]);
                $("#"+key).closest('.edit-view-row-item').show();
            } else {
                $("#"+key).val(dataJSON[key]);  
                if(dataJSON[key] !== '' || key == 'quote_main_tank_water'){
                    $("#"+key).closest('.edit-view-row-item').show();
                }
            }
        }
    }
    function parseJSONValueToFieldsExtra(){
        if ($("#quote_note_inputs_c").val() == '')  return;
        var dataJSON = JSON.parse($("#quote_note_inputs_c").val());
        for (let key in dataJSON['quote_sanden_extra']) {
            for (let k in dataJSON['quote_sanden_extra'][key]) {
                if((dataJSON['quote_sanden_extra'][key][k]) === 'yes'){
                    $("#"+k).prop('checked', true);
                } else {
                    $("#"+k).val(dataJSON['quote_sanden_extra'][key][k]);
                }
            }
        }

        // Parse Data Product Get From Suite 
        // var arrPartNumber = ['Sanden_Complex_Install', 'SANDEN_ELEC_EXTRA', 'RCBO', 'SwitchUpgrade', 'HWS_R', 'Sanden_Tank_Slab', 'Sanden_HP_Pavers', 'Site_Delivery', 'Spec_Trade_Disc', 'san_wall_bracket', 'Travel'];
        $.ajax({
            url: '/index.php?entryPoint=APIGetDataProduct',
            data: {type_get: 'quote_input'},
            method: 'POST',
            success: function (result) {
                try {
                    var json_data = JSON.parse(result);
                    for (let key in json_data) {
                        console.log(json_data[key], $('div[data-partnumber='+json_data[key]["part_number"]+']'));
                        if($('div[data-partnumber='+json_data[key].part_number+']').find('input[type=number]').val() == '') {
                            $('div[data-partnumber='+json_data[key].part_number+']').find('input[type=number]').val(json_data[key].cost)
                        }
                        if($('div[data-partnumber='+json_data[key].part_number+']').find('input.added_field').is(":checked") == false) {
                            $('div[data-partnumber='+json_data[key].part_number+']').find('input:nth-child(4)').attr('disabled', 'disabled');
                            $('div[data-partnumber='+json_data[key].part_number+']').find('input[type=number]').attr('disabled', 'disabled');
                        } else {
                            $('div[data-partnumber='+json_data[key].part_number+']').find('input:nth-child(4)').removeAttr('disabled');
                            $('div[data-partnumber='+json_data[key].part_number+']').find('input[type=number]').attr('disabled');
                        }
                    }
                    $('body').on('change', '.added_field', function() {
                        var current_input = $(this).parent().find('.value_field').val();
                        if ($(this).is(':checked')) {
                            $(this).parent().find('.itemise_field').removeAttr('disabled');
                            $(this).parent().find('.value_field').removeAttr('disabled');
                        } else {
                            $(this).parent().find('.itemise_field').attr('disabled', 'disabled');
                            $(this).parent().find('.itemise_field').prop("checked", false);
                            $(this).parent().find('.value_field').attr('disabled', 'disabled');
                            $(this).parent().find('.value_field').val(current_input);
                        }
                    })
                    // $("#group_custom_checklist_rebate_provided").empty().append(json_data['template_html_rebate']);
                } catch (error) {
                    console.log(error)
                }
            }
        }).done(function (data) {
            // SUGAR.ajaxUI.hideLoadingPanel();
        });
    }

    function generateJSONForInput(){
        var values = {};
        $("#group_custom_quote_inputs_checklist .custom_fields").each(function (){
            var id_name = $(this).attr("id");
            values[id_name] = $(this).val();
        });
        // .:nhantv:. Generate Select Options
        $('input[id*=own][name="sl_quote_option"]').each((index, el) => {
            let id = $(el).attr('id');
            values[id] = $(el).prop('checked');
        });
        $("#quote_note_inputs_c").val(JSON.stringify(values));
    }

/** END LOAD */

/** START JS LOAD REBATE PROVIDED */
$(function() {
    'use strict';
    
    //Hide field textArea Json
    $("#quote_cl_rebate_c").closest('.edit-view-field').parent().parent().hide();

    var html_checklist_rebate_provided = 
    '<div id="group_custom_checklist_rebate_provided" class="row detail-view-row"></div>';
    $("#quote_cl_rebate_c").closest('.tab-content').append(html_checklist_rebate_provided);
    var btn_generate_quote = '<button type="button" id="generate_quote_from_rebate" class="button primary">Generate Line Item</button>';
    $("#quote_cl_rebate_c").closest('.tab-content').append(btn_generate_quote);
    renderRebateProvidedHTML($("#quote_type_c").val());

    $("#generate_quote_from_rebate").on('click',function(){
        SUGAR.ajaxUI.showLoadingPanel();
        generateJSONForRebateProvided();
        let dataJSON = JSON.parse($("#quote_cl_rebate_c").val());
        for (let key in dataJSON) { 
            processLineItemRebate(key,dataJSON[key]);
        }
        setTimeout(function (){
            SUGAR.ajaxUI.hideLoadingPanel();
        },5000)
    });
    $(document).on("change","#group_custom_checklist_rebate_provided .custom_rebate_fields",function(){
        generateJSONForRebateProvided();
    });



});
/** END JS LOAD REBATE PROVIDED */
/** START - DECLARE FUNCTION FOR REBATE PROVIDED */

function processLineItemRebate(id, status) {
    let id_product = '';
    switch (id) {
        case 'cl_stcs':
            id_product = '4efbea92-c52f-d147-3308-569776823b19';
            break;
        case 'cl_veecs':
            id_product = 'cbfafe6b-5e84-d976-8e32-574fc106b13f';
            break;
        case 'cl_sl_hotwater_rebate': 
            id_product = '431a9064-7cbb-6a44-e7ba-5d5b794137c7';
            break;
        case 'cl_sl_pv_rebate': 
            id_product = '709310c8-c214-599b-d1e8-5f21061b0928';
            break;
        case 'cl_sa_reps_cl1_reti_gas_conn': 
            id_product = '7a745194-689f-8896-64d7-6033347a1e17';
            break;
        case 'cl_sa_reps_cl1_no_gas_cl2': 
            id_product = 'b054e7d4-8c42-b544-e37d-60333c268459';
            break;
        case 'cl_sl_battery_store_rebate':
            id_product = '4837bd0e-bfb4-56e0-0c2d-6049db9a8cb8';
            break;
        default:
            break;
    }
    let ln_index = deleteLineItemRebate(id_product);
    if (id_product != '') {
        if (status) {
            if (ln_index == 'no') {
                autoCreateLineItem_Rebate(id_product,1);
            }
        } else {
            if (ln_index != 'no') {
                markLineDeleted(ln_index,"product_");
            }
        }
    }
}

function deleteLineItemRebate(id_item) {
    let products = $('#lineItems').find('.product_group').children('tbody');
    let i; 
    for (i=0;i< products.length; i++) {
        if ($(`#product_product_id${i}`).val() == id_item && products[i].getAttribute('style') != "display: none;") {
            // markLineDeleted(i,"product_");
            return i;
        }
    }
    return 'no';
}

/**
 * Render HTML for Rebate Provided subpanel
 * @param {String} type quote_type_c
 * @param {String} state state
 */
function renderRebateProvidedHTML(type){
    $.ajax({
        url: '/index.php?entryPoint=APIRenderRebateProvided&type='+type,
        success: function (result) {
            try {
                var json_data = JSON.parse(result);
                $("#group_custom_checklist_rebate_provided").empty().append(json_data['template_html_rebate']);
            } catch (error) {
                console.log(error)
            }
        }
    }).done(function (data) {
        parseJSONValueToFields_RebateProvided();
        SUGAR.ajaxUI.hideLoadingPanel();
    });
}
/**
 * parse value into subpanel rebate provided
 */
function parseJSONValueToFields_RebateProvided(){
    if ($("#quote_cl_rebate_c").val() == '' || typeof $("#quote_cl_rebate_c").val() == 'undefined')  return;
    var dataJSON = JSON.parse($("#quote_cl_rebate_c").val());
    for (let key in dataJSON) {  
        $("#"+key).val(dataJSON[key]);
        $("#"+key).attr('checked', dataJSON[key]);
    }
}

/**
 * Create Json
 */
function generateJSONForRebateProvided(){
    var values = {};
    $("#group_custom_checklist_rebate_provided .custom_rebate_fields").each(function (){
        var id_name = $(this).attr("id");
        values[id_name] = $(this).is(":checked");
    });
    $("#quote_cl_rebate_c").val(JSON.stringify(values));
}

function autoCreateLineItem_Rebate(id,total_item){
    $.ajax({
        url: "/index.php?entryPoint=getInfoProduct&product_id="+id,
        type: 'GET',
        // async: false,
        success: function(data)
        {   
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
            name_to_value_array["product_vat"+lineno] = '0%';

            popupReplyData["name_to_value_array"] = name_to_value_array;            
            $('#product_product_list_price'+lineno).focus();
            $('#product_product_id'+lineno).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ id +'">Link</a></div>');
            setProductReturn(popupReplyData);
        },
        error: function(response){console.log("Fail");},
    });
}

function DynamicField(id) {
    var sellector = $('#'+id).find('select');
}
/** END - DECLARE FUNCTION FOR REBATE PROVIDED */


// $(document).ready(function() {
//     var dataType = "Sanden";
//     $("#quote_note_inputs_c").closest('.edit-view-field').parent().parent().hide();
//     var html_group_custom_quote_inputs = 
//     '<div id="group_custom_quote_inputs_checklist" class="row detail-view-row"></div>';
//     $("#quote_note_inputs_c").closest('.tab-content').append(html_group_custom_quote_inputs);
//     var btn_generate_quote = '<button type="button" id="generate_quote" class="button primary">Generate Quote</button>';
//     $("#quote_note_inputs_c").closest('.tab-content').append(btn_generate_quote);
//     $('#generate_quote').on('click', function() {
//         generate_quote_by_input();
//     });
//     function render_json_data_quoteInputs(){
//         var data = new Object();
//         data.quote_main_tank_water = $('#quote_main_tank_water').val();
//         data.quote_number_sanden = $('#quote_number_sanden').val();
//         data.quote_tank_size = $('#quote_tank_size').val();
//         data.quote_plumbing_installation_by_pure = $('#quote_plumbing_installation_by_pure').val();
//         data.quote_electrical_installation_by_pure = $('#quote_electrical_installation_by_pure').val();
//         data.quote_quick_connection_kit = $('#quote_quick_connection_kit').val();
//         data.quote_provide_stcs = $('#quote_provide_stcs').val();
//         data.quote_pickup_site_delivery = $('#quote_pickup_site_delivery').val();
//         data.quote_choice_type_install = $("#quote_choice_type_install").val();
//         data.quote_replacement_urgent = $('#quote_replacement_urgent').val();
//         data.quote_choice_type_product = $('#quote_choice_type_product').val();
//         data.quote_product_choice_type_gas = $('#quote_product_choice_type_gas').val();
//         data.quote_gas_connection = $('#quote_gas_connection').val();
//         data.quote_product_choice_type_electric = $('#quote_product_choice_type_electric').val();
//         data.quote_electric_storage_located = $('#quote_electric_storage_located').val();
//         data.quote_about_outside = $('#quote_about_outside').val();
//         data.quote_about_inside = $('#quote_about_inside').val();
//         data.quote_product_choice_type_solar = $('#quote_product_choice_type_solar').val();
//         data.quote_solar_boosted = $('#quote_solar_boosted').val();
//         data.quote_product_choice_type_wood = $('#quote_product_choice_type_wood').val();
//         data.quote_product_choice_type_lpg = $('#quote_product_choice_type_lpg').val();
//         data.quote_new_place_choice = $('#quote_new_place_choice').val();
//         data.quote_existing_install_location = $('#quote_existing_install_location').val();
//         data.quote_install_location_access = $('#quote_install_location_access').val();
//         data.quote_stair_access = $('#quote_stair_access').val();
//         data.quote_alectrical_already = $('#quote_alectrical_already').val();
//         data.quote_hot_cold_connections = $('#quote_hot_cold_connections').val();
//         data.quote_located_within = $('#quote_located_within').val();
//         data.quote_additional_untempered = $('#quote_additional_untempered').val();
//         data.quote_concrete_slab = $('#quote_concrete_slab').val();
//         data.quote_concrete_pavers = $('#quote_concrete_pavers').val();
//         data.quote_hot_water_rebate = $('#quote_hot_water_rebate').val();
//         var jsonString= JSON.stringify(data);
//         return jsonString;
//     }
//     function render_checkbox_quote_checklist(){
//         $.ajax({
//             url: '/index.php?entryPoint=APIRenderListQuoteInputs&action=render',
//             success: function (result) {
//                 try {
//                    var json_data = $.parseJSON(result);
//                    $("#group_custom_quote_inputs_checklist").append(json_data['template_html']);
                   
//                    render_select_option_quote_input();
//                 } catch (error) {
//                     console.log(error)
//                 }
//             }
//         });
//     }
//     render_checkbox_quote_checklist();
//     function render_data_quoteInputs(data) {
//         if(data == '') {
//             data = '{"quote_main_tank_water":"","quote_number_sanden":"","quote_tank_size":"","quote_plumbing_installation_by_pure":"","quote_electrical_installation_by_pure":"","quote_quick_connection_kit":"","quote_provide_stcs":"","quote_pickup_site_delivery":"","quote_choice_type_install":"","quote_replacement_urgent":"","quote_choice_type_product":"","quote_gas_connection":"","quote_product_choice_type_gas":"","quote_electric_storage_located":"","quote_about_outside":"","quote_about_inside":"","quote_product_choice_type_solar":"","quote_solar_boosted":"","quote_product_choice_type_wood":"","quote_product_choice_type_lpg":"","quote_new_place_choice":"","quote_existing_install_location":"","quote_install_location_access":"","quote_stair_access":"","quote_alectrical_already":"","quote_hot_cold_connections":"","quote_product_choice_type_electric":"","quote_located_within":"Underground","quote_additional_untempered":"","quote_concrete_slab":"","quote_concrete_pavers":"","quote_hot_water_rebate":""}';
//             data =  $.parseJSON(data);
//         }else{
//             data =  $.parseJSON(data);
//         }  
//         return data;
//     }
    
    
//     function render_select_option_quote_input(){
//         if(dataType == "Sanden") {
//             var data = $("#quote_note_inputs_c").val();
//             const parseData = render_data_quoteInputs(data);
//             if(parseData != '') {
//                 for (const [key, value] of Object.entries(parseData)) {
//                     selectElement(key, value);
//                 }
//             }
//         }
//     }

//     function generate_quote_by_input(){
//         SUGAR.ajaxUI.showLoadingPanel();
//         if(dataType == "Sanden") {
//             var data = new Object();
//             var checkList = jQuery('#group_custom_quote_inputs_checklist .edit-view-field select');
//             data['quote_generate_type'] = 'bySuite';
//             data['quote_id'] = $('input[name=record]').val();
//             checkList.each(function() {
//                 data[$(this).attr('id')] = $(this).val();
//             });
//             if(data != '') {
//                 $.ajax({
//                     type: "POST",
//                     url: '/index.php?entryPoint=APICreateQuoteNewFromSandenPE',
//                     data: data,
//                     success: function (result) {
//                         try {
//                             SUGAR.ajaxUI.hideLoadingPanel();
//                             location.reload();
//                             // location.href = window.location.href + "#detailpanel_3";
//                         } catch (error) {
//                             console.log(error)
//                         }
//                     }
//                 });
//             }
//         }
//     }
// });
// function selectElement(id, valueToSelect) {
//     if(id != 'module' && id != 'action' && id != 'entryPoint' && id != 'quote_generate_type' && id != 'quote_id') {
//         var element = document.getElementById(id);
//         element.value = valueToSelect;
//     }
// }