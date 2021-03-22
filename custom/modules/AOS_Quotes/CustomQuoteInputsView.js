/** PRODUCT MAPPER */
const productMapper = [{
    name: "Solar PV Supply and Install",
    nameDb: "Solar PV Supply and Install",
    id: "24049a0d-6338-4b87-195e-602b0e92eb62",
},{
    name: "STCs",
    nameDb: "STCs",
    id: "4efbea92-c52f-d147-3308-569776823b19",
},{
    name: "Sunpower P3 325 BLACK",
    nameDb: "Sunpower P3 325W BLACK",
    id: "ddccc2e2-8673-167a-486d-5ea65e73b911",
},{
    name: "Sunpower Maxeon 3 400W",
    nameDb: "Sunpower Maxeon 3 400W",
    id: "94ad880e-feee-bf20-54ca-5ccfe9e7a0de",
},{
    name: "Q CELLS Q.MAXX-G2 350W",
    nameDb: "",
    id: "",
},{
    name: "Jinko 370W Cheetah Plus JKM370M-66H",
    nameDb: "",
    id: "",
},{
    name: "Sungrow 5",
    nameDb: "",
    id: "",
},{
    name: "Sungrow 8",
    nameDb: "",
    id: "",
},{
    name: "Primo 5",
    nameDb: "Fronius Primo 5kW Inverter",
    id: "2b6dd740-ab28-1cde-012e-5d2e89ed82cd",
},{
    name: "S Edge 5G",
    nameDb: "Solaredge HDWave 5kW 1P",
    id: "e76b3404-3b16-6e43-0560-5d3ee1b7e0d5",
},{
    name: "S Edge 6G",
    nameDb: "SolarEdge 6kW Genesis HD-Wave 1Ph Inverter",
    id: "f02c1d4b-2d97-fb32-c9c0-602b6ac5da42",
},{
    name: "S Edge 8G",
    nameDb: "SolarEdge 6kW Genesis HD-Wave 1Ph Inverter",
    id: "",
},{
    name: "S Edge 10G",
    nameDb: "Solaredge HDWave 10kW 1P",
    id: "e8450cf9-13d9-f85d-5436-5d3ee1e39a4b",
},{
    name: "Sungrow 10 3P",
    nameDb: "",
    id: "",
},{
    name: "Primo 8.2",
    nameDb: "Fronius Primo 8.2 SCERT Inverter",
    id: "2b6dd740-ab28-1cde-012e-5d2e89ed82cd",
},{
    name: "Symo 10",
    nameDb: "",
    id: "",
},{
    name: "Symo 15",
    nameDb: "",
    id: "d051d35d-7a84-e4f1-b39f-5d42c9358989",
},{
    name: "Primo 6",
    nameDb: "",
    id: "",
},{
    name: "Sungrow Smart Meter (1P)",
    nameDb: "",
    id: "",
},{
    name: "Fro. Smart Meter (1P)",
    nameDb: "",
    id: "",
},{
    name: "SE Wifi",
    nameDb: "",
    id: "",
},{
    name: "Fro. Smart Meter (3P)",
    nameDb: "Fronius Smart Meter 3P 50kA--3",
    id: "5d53735c-a394-2bd5-8032-5d42d1e93929",
},{
    name: "SE Smart Meter",
    nameDb: "",
    id: "",
}]

/** JS LOAD CUSTOM QUOTE INPUT  */
    $(function () {
        'use strict';
        
        $("#quote_note_inputs_c").closest('.edit-view-field').parent().parent().hide();
        var html_group_custom_quote_inputs = 
        '<div id="group_custom_quote_inputs_checklist" class="row detail-view-row"></div>';
        $("#quote_note_inputs_c").closest('.tab-content').append(html_group_custom_quote_inputs);
        var btn_generate_quote = '<button type="button" id="generate_quote" class="button primary">Save and Generate Quote</button>';
        $("#quote_note_inputs_c").closest('.tab-content').append(btn_generate_quote);
        
        if($("#quote_type_c").val() == "quote_type_solar"){
            renderQuoteInputHTML('quote_type_solar');
        }else if($("#quote_type_c").val() == "quote_type_sanden"){
            renderQuoteInputHTML('quote_type_sanden');
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
            } else {
                $('#group_custom_quote_inputs_checklist .edit-view-row').empty();
                $('body').find("#own_solar_pv_pricing_table").remove();
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        });

        $("#generate_quote").on('click',function(){
            if($("#quote_type_c").val() == "quote_type_solar"){
                // generate_quote_by_input('quote_type_solar');
                generateLineItem();
                generateJSONForInput();
            }else{
                generateJSONForInput();
                generate_quote_by_input('quote_type_sanden');
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
        // Get Option Quote
        let optSelected = $('input[id*=own][name="sl_quote_option"]:checked').attr('data-attr');
        if (!optSelected){
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
        // Get value
        let panelSelected = $('#own_panelType_' + optSelected).val();
        let panelTotal = $('#own_totalPanels_' + optSelected).val();
        let inverterSelected = $('#inverter_type_' + optSelected).val();
        let extra1Selected = $('#extra_1_' + optSelected).val();
        let extra2Selected = $('#extra_2_' + optSelected).val();
        let extra3Selected = $('#extra_3_' + optSelected).val();
        let stcTotal = $('#number_of_stcs_' + optSelected).val();
        // Create line item
        // Alway add this product: Solar PV Supply and Install
        await autoCreateLineItem(getProductInfoFromName("Solar PV Supply and Install"), 1, 1);
        panelSelected && await autoCreateLineItem(getProductInfoFromName(panelSelected), panelTotal, 2);
        inverterSelected && await autoCreateLineItem(getProductInfoFromName(inverterSelected), 1, 3);
        extra1Selected && await autoCreateLineItem(getProductInfoFromName(extra1Selected), 1, 4);
        extra2Selected && await autoCreateLineItem(getProductInfoFromName(extra2Selected), 1, 5);
        extra3Selected && await autoCreateLineItem(getProductInfoFromName(extra3Selected), 1, 5);
        // Alway add this product: STCs
        await autoCreateLineItem(getProductInfoFromName("STCs"), stcTotal, 6);
        // Calculate
        await calculatePrice(7);
        // Hide loading
        setTimeout(function (){
            SUGAR.ajaxUI.hideLoadingPanel();
        }, 300);
    }
    // .:nhantv:. Calculate total price
    async function calculatePrice(ms){
        await wait(200 * ms);
        let productVisible = $('.product_group').find('tbody[id*=product_body]:visible');
        var totalList = 0, totalDiscount = 0, totalAmount = 0;
        var list, dis, amount;
        // For each
        productVisible.each((index, el) => {
            // get target
            list = $(el).find('input[id*=product_product_list_price]');
            dis = $(el).find('input[id*=product_product_discount]');
            amount = $(el).find('input[id*=product_product_total_price]');

            if(index !== 0 && index < productVisible.length - 1){
                totalList += get_value(list.attr('id'));
                totalDiscount += get_value(dis.attr('id'));
                totalAmount += get_value(amount.attr('id'));
                set_value(list.attr('id'), "");
            }
            // blur
            list.trigger("blur")
        });
        // Set value to grand total
        list = $(productVisible[0]).find('input[id*=product_product_list_price]');
        set_value(list.attr('id'), totalAmount);
        list.trigger("blur")
    }
    // .:nhantv:. Get Product Line Item info 
    async function autoCreateLineItem(productInfo, total_item, ms){
        await wait(200 * ms);
        // Case: id = ""
        if(productInfo === undefined || productInfo.id === ""){
            return;
        }
        // Case: id !== ""
        $.ajax({
            url: "/index.php?entryPoint=getInfoProduct&product_id=" + productInfo.id,
            type: 'GET',
            success: function(data)
            {   
                var info_pro = JSON.parse(data);
                insertProductLine('product_group0', '0');
                lineno  = prodln-1;  
                var popupReplyData = {};
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
                $('#product_product_id'+lineno).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ productInfo.id +'">Link</a></div>');
                setProductReturn(popupReplyData);
            },
            error: function(response){console.log("Fail");},
        });
    }
    // .:nhantv:. Get product id from Option selected
    function getProductInfoFromName(name){
        let result = undefined;
        productMapper.forEach(element => {
            if(element.name === name){
                result = element;
            }
        });
        return result;
    }
    // .:nhantv:. Wait function
    const wait = ms => {
        return new Promise(res => setTimeout(res, ms));
    };

    // .:nhantv:. If Product Type is not Solar -> hide all solar panels: "SOLARGAIN INFORMATION" / "PRICING PV SECTION" / "SOLAR VICTORIA PROVIDER STATEMENT"
    function hideSolarPanel(){
        if ($("#quote_type_c").val() !== "quote_type_solar"){
            // "SOLARGAIN INFORMATION"
            // $('#special_notes_c').closest('.panel.panel-default').hide();
            // "PRICING PV SECTION"
            $('#Terracotta_checkbox').closest('.panel.panel-default').hide();
            // "SOLAR VICTORIA PROVIDER STATEMENT"
            $('#slv_solar_vic_id_c').closest('.panel.panel-default').hide();
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
            if(data != '') {
                $.ajax({
                    type: "POST",
                    url: '/index.php?entryPoint=APICreateQuoteNewFromSandenPE',
                    data: data,
                    success: function (result) {
                        try {
                            SUGAR.ajaxUI.hideLoadingPanel();
                            location.reload();
                        } catch (error) {
                            console.log(error)
                        }
                    }
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
            } else {
                $("#"+key).val(dataJSON[key]);
            }
        }
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