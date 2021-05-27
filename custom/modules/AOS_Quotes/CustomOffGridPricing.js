var og_extra = [], sol_panel, sol_inverter, og_inverter, og_battery, og_accessory, re_generator;
const extra_products = ["Microgrid Solar PV Supply and Install", "Microgrid Standard Install", "Sunpower Split Panel Fee", "Microgrid Balance Of System", "STCs"];

$(function () {
    'use strict';
    //INIT
    $("#offgrid_option_c").closest('.edit-view-row-item').hide();

    //Variables
    var quote_type = $("#quote_type_c").val();
    switch (quote_type) {
        case 'quote_type_off_grid_system':
            init_table_offgrid();
            break;
        default: break;
    }

    // .:nhantv:. Add a checkbox to Itemise in LINE ITEMS
    $('input[name="sl_quote_option"]').on('change', function() {
        checkBoxOptionHandle($(this), "sl_quote_option");
    });

    // .:nhantv:. Clear Off Grid Option
    $(document).on('click', '*[id*="clear_og_option"]', function(e){
        e.preventDefault();
        clearOgOption($(this).data('option'));
    });

    // .:nhantv:. Offgrid Checkbox handle 
    $(document).on('change', 'input[id*="offgrid_option"]', function(){
        checkBoxOptionHandle($(this), "offgrid_option");
    });

    // .:nhantv:. PE Admin % handle 
    $(document).on('change', '#pe_admin_percent', function(){
        $('#calculate_og').trigger('click');
    });

    // .:nhantv:. Calculate Button Click handle 
    $(document).on('click', '#calculate_og', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
            var panel_type = $("#panel_og_type_"+i).val();
            var inverter_type1 = $("#inverter_og_type1_"+i).val();
            var inverter_type2 = $("#inverter_og_type2_"+i).val();
            // Get suggested
            if(panel_type != '' && (inverter_type1 != "" || inverter_type2 != "")){
                // Calculate option
                calcOption(i);
            }
        }
    });

});

// .:nhantv:. Clear Offgrid Option
function clearOgOption(option){
    $("#offgrid_option_"+(option)).prop('checked', false);
    $('#offgrid_pricing_table td:nth-child('+ (option + 1) +')').find('input').val('');
    $('#offgrid_pricing_table td:nth-child('+ (option + 1) +')').find('select').prop("selectedIndex", 0);
}

// .:nhantv:. Option Changed handle
function optionChangeHandle(el){
    let attrId = $(el).attr('id');
    let isTotalPanel = attrId.indexOf('total_og_panels_') != -1;
    let index = attrId.substr(attrId.length - 1);
    if(index != "") {
        let currState = getCurrentOptionState(index);
        // Check condition to validate panel - inverter
        if(currState.panel_type != '' && currState.inverter_type != ''){
            calcOption(index, isTotalPanel);
        }
    }
    return;
}

// .:nhantv:. Check box Option handle
function checkBoxOptionHandle(el, inputClass){
    // Set change
    let dataAttr = $(el).attr('data-attr');
    let propVal = $(el).prop('checked');
    if(propVal){
        $('input[name="'+ inputClass +'"]').each(function(){
            $(this).attr('data-attr') && $(this).attr('data-attr') === dataAttr
            ? $(this).prop('checked', propVal) 
            : $(this).prop('checked', !propVal);
        })
    } else {
        // Mark line deleted
        for (var i = 0; i < prodln; i++){
            markLineDeleted(i,"product_");
        };
        // Delete group
        $("#lineItems").find(".group_body").each((index) => {
            markGroupDeleted(index);
        });
    }
    if(inputClass == "sl_quote_option"){
        generateJSONForInput();
    }
}
// .:nhantv:. Convert JSON to Array data
function convertJSONToArrayInit(jsonData){
    let result = [''];
    if (typeof(jsonData) != 'undefined') {
        jsonData.forEach(element => {
            result.push(element.short_name);
        });
    }
    return result;
}

// .:nhantv:. Get current option state
function getCurrentOptionState(index){
    let result = {};
    result['total_kw'] = $('#total_og_kW_' + index).val();
    result['panel_type'] = $('#panel_og_type_' + index).val();
    result['inverter_type1'] = $('#inverter_og_type1_' + index).val();
    result['inverter_type2'] = $('#inverter_og_type2_' + index).val();
    result['total_panels'] = $('#total_og_panels_' + index).val();
    result['number_stcs'] = $('#number_og_stcs_' + index).val();
    result['offgrid_inverter'] = $('#offgrid_inverter_' + index).val();
    result['offgrid_batery'] = $('#offgrid_batery_' + index).val();
    result['offgrid_howmany'] = $('#offgrid_howmany_' + index).val();
    result['offgrid_accessory1'] = $('#offgrid_accessory1_' + index).val();
    result['offgrid_accessory2'] = $('#offgrid_accessory2_' + index).val();
    result['re_generator'] = $('#re_generator_' + index).val();
    return result;
}

// .:nhantv:. Get attr from name
function getAttributeFromName(name, target, attribute){
    for (var item in target) {
        if (target[item].short_name == name || target[item].name == name) {
            return target[item][attribute];
        }
    }
    return null;
}

// .:nhantv:. Get item from name
function getItemFromName(name, target){
    for (var item in target) {
        if (target[item].short_name == name || target[item].name == name) {
            return target[item];
        }
    }
    return null;
}

// .:nhantv:. Calc Grand Total
function calcGrandTotal(currState){
    let grandTotal = 0;
    // Equipment cost
    grandTotal += calcEquipmentCost(currState);
    // Microgrid Solar PV Supply and Install
    grandTotal += parseFloat(getAttributeFromName(extra_products[0], og_extra, "cost"));
    // Microgrid Standard Install
    grandTotal += parseFloat(getAttributeFromName(extra_products[1], og_extra, "cost")) * parseFloat(currState.total_kw) * 1000;
    // Sunpower Split Panel Fee
    if (currState.panel_type.toLowerCase().indexOf("sunpower") != -1) {
      grandTotal += parseFloat(getAttributeFromName(extra_products[2], og_extra, "cost"));
    }
    // PE Admin %
    grandTotal += grandTotal * (parseFloat($('#pe_admin_percent').val()) / 100);
    // GST 10%
    let gst = grandTotal * 0.1;
    // STCs
    grandTotal += parseFloat(getAttributeFromName(extra_products[4], og_extra, "cost")) * parseFloat(currState.number_stcs);
    // Include GST above
    grandTotal += gst;

    return grandTotal;
}

// .:nhantv:. Calc Equipment Cost
function calcEquipmentCost(currState){
    let cost = 0;
    if(currState.panel_type != ''){
        cost += parseFloat(getAttributeFromName(currState.panel_type, sol_panel, "cost")) * parseFloat(currState.total_panels);
    }
    if(currState.inverter_type1 != ''){
        cost += parseFloat(getAttributeFromName(currState.inverter_type1, sol_inverter, "cost"));
    }
    if(currState.inverter_type2 != ''){
        cost += parseFloat(getAttributeFromName(currState.inverter_type2, sol_inverter, "cost"));
    }
    if(currState.offgrid_inverter != ''){
        cost += parseFloat(getAttributeFromName(currState.offgrid_inverter, og_inverter, "cost"));
    }
    if(currState.offgrid_batery != ''){
        cost += parseFloat(getAttributeFromName(currState.offgrid_batery, og_battery, "cost")) * parseFloat(currState.offgrid_howmany);
    }
    if(currState.offgrid_accessory1 != ''){
        cost += parseFloat(getAttributeFromName(currState.offgrid_accessory1, og_accessory, "cost"));
    }
    if(currState.offgrid_accessory2 != ''){
        cost += parseFloat(getAttributeFromName(currState.offgrid_accessory2, og_accessory, "cost"));
    }
    if(currState.re_generator != ''){
        cost += parseFloat(getAttributeFromName(currState.re_generator, re_generator, "cost"));
    }
    // Microgrid Balance Of System
    cost += parseFloat(getAttributeFromName(extra_products[3], og_extra, "cost")) * parseFloat(currState.total_kw) * 1000;
    return cost;
}

// .:nhantv:. Calc Installation Cost
function calcInstallationCost(currState){
    let cost = 0;
    // Microgrid Solar PV Supply and Install
    cost += parseFloat(getAttributeFromName(extra_products[0], og_extra, "cost"));
    // Microgrid Standard Install
    cost += parseFloat(getAttributeFromName(extra_products[1], og_extra, "cost")) * parseFloat(currState.total_kw) * 1000;
    // Sunpower Split Panel Fee
    if (currState.panel_type.toLowerCase().indexOf("sunpower") != -1) {
        cost += parseFloat(getAttributeFromName(extra_products[2], og_extra, "cost"));
    }
    return cost;
}

// .:nhantv:. Get max panels
function getMaxPanelAndTotalKw(currState, isTotalPanel){
    const ratio = 1.5;
    const panel_kw = parseFloat(getAttributeFromName(currState.panel_type, sol_panel, "capacity")) / 1000;
    // Get inverter kw
    let inverter_kw = 0;
    for (let i = 1; i < 3; i++) {
        if (currState['inverter_type' + i] != "") {
            inverter_kw += parseFloat(getAttributeFromName(currState['inverter_type' + i], sol_inverter, "capacity"));
        }
    }
    const maxPanel = Math.floor(inverter_kw * ratio / panel_kw);
    const maxKw = (panel_kw * maxPanel).toFixed(3);
    let result = [];
    result['max'] = maxPanel;
    result['kw'] = maxKw;
    result['inverter_kw'] = inverter_kw;
    // check total panel manually input
    if (isTotalPanel) {
        // Check inut panel is greater than max value
        if(maxPanel < currState.total_panels){
            alert("You have exceeded the maximum number of panels for that panel type. Max = " + maxPanel);
            return result;
        }
        result['max'] = currState.total_panels;
        result['kw'] = parseFloat((panel_kw * currState.total_panels).toFixed(3));
    }
    return result;
}

// .:nhantv:. Calculate Total Kwh and STCs of Option
async function calcOption(index, isTotalPanel = false) {
    var postcode = $("#install_address_postalcode_c").val();
    if(index != '' && index != undefined){
        let currState = getCurrentOptionState(index);
        if(currState.panel_type != '' && (currState.inverter_type1 != '' || currState.inverter_type2 != '')){
            // Get max panels and total kw
            let maxPnAndTotalKw = getMaxPanelAndTotalKw(currState, isTotalPanel);
            // Set value
            $("#total_og_panels_"+index).val(maxPnAndTotalKw['max']);
            currState.total_panels = maxPnAndTotalKw['max'];
            $('#total_og_kW_'+index).val(maxPnAndTotalKw['kw']);
            currState.total_kw = maxPnAndTotalKw['kw'];
            $('#total_inverter_og_kW_'+index).val(maxPnAndTotalKw['inverter_kw']);

            // Get STCs value
            try {
                // Show loading
                SUGAR.ajaxUI.showLoadingPanel();
                await $.ajax({
                    url: 'index.php?entryPoint=getSTCsNumberForQuotePricing&total_kw='+maxPnAndTotalKw['kw']+'&postcode='+postcode,
                    type : 'GET',
                    dataType: 'text',
                }).then(function (data) {
                    var result = JSON.parse(data);
                    if(result['NumberOfSTCs'] != ''){
                        $("#number_og_stcs_"+index).val(result['NumberOfSTCs']);
                        currState.number_stcs = result['NumberOfSTCs'];
                    }
                    // Save current option
                    saveCurrentState();
                    // Grand Total
                    let grandTotal = calcGrandTotal(currState);
                    $("#og_total_"+index).val(parseFloat(roundTo90(grandTotal)).formatMoney(2, ',', '.'));
                });
            } catch (err) {
                console.log(err);
            } finally {
                // Hide loading
                setTimeout(function (){
                    SUGAR.ajaxUI.hideLoadingPanel();
                }, 300);
            }
        }
    }
}
// .:nhantv:. Save curent state
function saveCurrentState(){
    var values = {};
    $("#offgrid_pricing_table .offgrid_pricing").each(function (){
        var id_name = $(this).attr("id");
        if($("#"+id_name).attr('type')== 'checkbox'){
            values[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
        } else {
            values[id_name] = $(this).val();
        }
    });

    $("#offgrid_option_c").val(JSON.stringify(values));
}

// .:nhantv:. Load Offgrid option
function loadOffgridOption(){
    if($("#offgrid_option_c").val() != ""){
        try{
            var json_val = JSON.parse($("#offgrid_option_c").val());
            for (let key in json_val) {
                if($("#"+key).attr('type') == 'checkbox'){
                    $("#"+key).prop( "checked", json_val[key] );
                } else {
                    $("#"+key).val(json_val[key]);
                }
            }
        } catch (err) {
            console.log(err);
        }
    }
}

// .:nhantv:. Generate Off-grid Item
async function generateOffgridItem(){
    // Save current state
    saveCurrentState();

    // Get Option Quote
    let index = $('input[name="offgrid_option"]:checked').attr('data-attr');
    if (!index){
        alert("You must choose the Option to generate line item");
        return;
    }

    // Mark line deleted
    for (var i = 0; i < prodln; i++){
        markLineDeleted(i,"product_");
    }

    // Check exist any line item
    if ($("#lineItems").find(".group_body").length == 0){
        insertGroup(0);
    } else {
        $("#group_body"+($("#lineItems").find(".group_body").length -1)).show();
    }
    $("#group0name").val("Off-Grid Option " + index);

    // Get value
    let currState = getCurrentOptionState(index);
    // Validate current State
    if (currState.offgrid_howmany == '') {
        alert("You must enter the number of Battery!");
        return;
    }
    // Create line item
    try{
        // Show loading
        SUGAR.ajaxUI.showLoadingPanel();
        await autoCreateLineItemOg("Microgrid Solar PV Supply and Install", og_extra, 1);
        currState.panel_type && await autoCreateLineItemOg(currState.panel_type, sol_panel, currState.total_panels);
        currState.inverter_type1 && await autoCreateLineItemOg(currState.inverter_type1, sol_inverter, 1);
        currState.inverter_type2 && await autoCreateLineItemOg(currState.inverter_type2, sol_inverter, 1);
        currState.offgrid_inverter && await autoCreateLineItemOg(currState.offgrid_inverter, og_inverter, 1);
        currState.offgrid_batery && await autoCreateLineItemOg(currState.offgrid_batery, og_battery, currState.offgrid_howmany);
        currState.offgrid_accessory1 && await autoCreateLineItemOg(currState.offgrid_accessory1, og_accessory, 1);
        currState.offgrid_accessory2 && await autoCreateLineItemOg(currState.offgrid_accessory2, og_accessory, 1);
        currState.re_generator && await autoCreateLineItemOg(currState.re_generator, re_generator, 1);
        await autoCreateLineItemOg("Microgrid Balance Of System", og_extra, 1, currState.total_kw * 1000);
        await autoCreateLineItemOg("Microgrid Standard Install", og_extra, 1, currState.total_kw * 1000);
        await autoCreateLineItemOg("STCs", og_extra, currState.number_stcs);
        // Calculate
        await calculatePrice(currState.total_panels, currState.total_kw, "off-grid", currState);
        // Calc Equipment Cost
        let equipmentCost = calcEquipmentCost(currState);
        $('#sanden_supply_bill').val(parseFloat(equipmentCost).formatMoney(2, ',', '.'));
        $('#sanden_supply_bill').trigger('change');
        // Calc Installation Cost
        let installationCost = calcInstallationCost(currState);
        $('#electrician_bill').val(parseFloat(installationCost).formatMoney(2, ',', '.'));
        $('#electrician_bill').trigger('change');
    } catch(err) {
        console.log(err);
    } finally {
        // Hide loading
        setTimeout(function (){
            SUGAR.ajaxUI.hideLoadingPanel();
        }, 300);
    }
}

// .:nhantv:. Get Product Line Item info 
async function autoCreateLineItemOg(shortName, target, total_item, total_kw = 1){
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

// .:nhantv:. Get Extra Product: Microgrid Solar PV Supply and Install, Microgrid Standard Install...
function getExtraProduct(){
    extra_products.forEach((element, index) => {
        $.ajax({
            url: "/index.php?entryPoint=APIGetProductInfoByShortName&short_name=" + element,
            type: 'GET'})
        .then(function(data) {
            if(data !== undefined || data !== ""){
                og_extra.push(JSON.parse(data));
            }
        });
    });
}

// .:nhantv:. Initable Offgrid
async function init_table_offgrid() {
    // Call API get Offgrid Product
    try{
        // Get Extra product
        getExtraProduct();
        // Get Equipment
        await $.ajax({
            url: '/index.php?entryPoint=APIGetOffGridProduct'
        }).then(function(result) {
            let dataJson = JSON.parse(result);
            // Set global var
            sol_panel = dataJson.panel_data;
            sol_inverter = dataJson.inverter_data;
            og_inverter = dataJson.og_inverter_data;
            og_battery = dataJson.battery_data;
            og_accessory = dataJson.accessory_data;
            re_generator = dataJson.re_generator_data;
        });
    } catch (ex) {
        console.log(ex);
    }

    let offgrid_pricing_table = $('<div id="offgrid_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row" style="margin-bottom: 20px;"></div>');
    let data = [
        ["Selected Option"
            ,"<input data-attr='1' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_1' style='margin-bottom:5px'> Option 1"
            ,"<input data-attr='2' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_2' style='margin-bottom:5px'> Option 2"
            ,"<input data-attr='3' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_3' style='margin-bottom:5px'> Option 3"
            ,"<input data-attr='4' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_4' style='margin-bottom:5px'> Option 4"
            ,"<input data-attr='5' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_5' style='margin-bottom:5px'> Option 5"
            ,"<input data-attr='6' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_6' style='margin-bottom:5px'> Option 6"],
        [""
            , "<button data-option ='1' id='clear_og_option_1' class='button default'>Clear Option 1</button>"
            , "<button data-option ='2' id='clear_og_option_2' class='button default'>Clear Option 2</button>"
            , "<button data-option ='3' id='clear_og_option_3' class='button default'>Clear Option 3</button>"
            , "<button data-option ='4' id='clear_og_option_4' class='button default'>Clear Option 4</button>"
            , "<button data-option ='5' id='clear_og_option_5' class='button default'>Clear Option 5</button>"
            , "<button data-option ='6' id='clear_og_option_6' class='button default'>Clear Option 6</button>"],
        ["Module Capacity kW:"
            , makeInputBox("total_og_kW_1 offgrid_pricing", "total_og_kW_1", true)
            , makeInputBox("total_og_kW_2 offgrid_pricing", "total_og_kW_2", true)
            , makeInputBox("total_og_kW_3 offgrid_pricing", "total_og_kW_3", true)
            , makeInputBox("total_og_kW_4 offgrid_pricing", "total_og_kW_4", true)
            , makeInputBox("total_og_kW_5 offgrid_pricing", "total_og_kW_5", true)
            , makeInputBox("total_og_kW_6 offgrid_pricing", "total_og_kW_6", true)],
        ["Inverter Capacity kW:"
            , makeInputBox("offgrid_pricing", "total_inverter_og_kW_1", true)
            , makeInputBox("offgrid_pricing", "total_inverter_og_kW_2", true)
            , makeInputBox("offgrid_pricing", "total_inverter_og_kW_3", true)
            , makeInputBox("offgrid_pricing", "total_inverter_og_kW_4", true)
            , makeInputBox("offgrid_pricing", "total_inverter_og_kW_5", true)
            , makeInputBox("offgrid_pricing", "total_inverter_og_kW_6", true)],
        ["Panel Type"
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_1 offgrid_pricing", "panel_og_type_1")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_2 offgrid_pricing", "panel_og_type_2")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_3 offgrid_pricing", "panel_og_type_3")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_4 offgrid_pricing", "panel_og_type_4")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_5 offgrid_pricing", "panel_og_type_5")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_6 offgrid_pricing", "panel_og_type_6")],
        ["Inverter Type 1"
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type1_1")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type1_2")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type1_3")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type1_4")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type1_5")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type1_6")],
        ["Inverter Type 2"
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type2_1")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type2_2")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type2_3")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type2_4")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type2_5")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "offgrid_pricing", "inverter_og_type2_6")],
        ["Total Panels"
            , makeInputBox("total_og_panels_1 offgrid_pricing", "total_og_panels_1", false)
            , makeInputBox("total_og_panels_2 offgrid_pricing", "total_og_panels_2", false)
            , makeInputBox("total_og_panels_3 offgrid_pricing", "total_og_panels_3", false)
            , makeInputBox("total_og_panels_4 offgrid_pricing", "total_og_panels_4", false)
            , makeInputBox("total_og_panels_5 offgrid_pricing", "total_og_panels_5", false)
            , makeInputBox("total_og_panels_6 offgrid_pricing", "total_og_panels_6", false)],
        ["Number of STCs"
            , makeInputBox("number_og_stcs_1 offgrid_pricing", "number_og_stcs_1", true)
            , makeInputBox("number_og_stcs_2 offgrid_pricing", "number_og_stcs_2", true)
            , makeInputBox("number_og_stcs_3 offgrid_pricing", "number_og_stcs_3", true)
            , makeInputBox("number_og_stcs_4 offgrid_pricing", "number_og_stcs_4", true)
            , makeInputBox("number_og_stcs_5 offgrid_pricing", "number_og_stcs_5", true)
            , makeInputBox("number_og_stcs_6 offgrid_pricing", "number_og_stcs_6", true)],
        ["", "&nbsp;"],
        ["<button id='calculate_og' class='button default'>Max</button>", "&nbsp;"],
        ["", "&nbsp;"],
        ["Off-Grid Inverter"
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_1")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_2")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_3")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_4")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_5")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_6")],
        ["Battery Storage"
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_1 offgrid_pricing", "offgrid_batery_1",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_2 offgrid_pricing", "offgrid_batery_2",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_3 offgrid_pricing", "offgrid_batery_3",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_4 offgrid_pricing", "offgrid_batery_4",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_5 offgrid_pricing", "offgrid_batery_5",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_6 offgrid_pricing", "offgrid_batery_6",)],
        ["How Many Batteries"
            , makeInputBox("offgrid_howmany_1 offgrid_pricing", "offgrid_howmany_1", false)
            , makeInputBox("offgrid_howmany_2 offgrid_pricing", "offgrid_howmany_2", false)
            , makeInputBox("offgrid_howmany_3 offgrid_pricing", "offgrid_howmany_3", false)
            , makeInputBox("offgrid_howmany_4 offgrid_pricing", "offgrid_howmany_4", false)
            , makeInputBox("offgrid_howmany_5 offgrid_pricing", "offgrid_howmany_5", false)
            , makeInputBox("offgrid_howmany_6 offgrid_pricing", "offgrid_howmany_6", false)],
        ["OG Accessory 1"
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_1 offgrid_pricing", "offgrid_accessory1_1")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_2 offgrid_pricing", "offgrid_accessory1_2")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_3 offgrid_pricing", "offgrid_accessory1_3")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_4 offgrid_pricing", "offgrid_accessory1_4")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_5 offgrid_pricing", "offgrid_accessory1_5")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_6 offgrid_pricing", "offgrid_accessory1_6")],
        ["OG Accessory 2"
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_1 offgrid_pricing", "offgrid_accessory2_1")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_2 offgrid_pricing", "offgrid_accessory2_2")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_3 offgrid_pricing", "offgrid_accessory2_3")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_4 offgrid_pricing", "offgrid_accessory2_4")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_5 offgrid_pricing", "offgrid_accessory2_5")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_6 offgrid_pricing", "offgrid_accessory2_6")],
        ["RE Generator"
            , makeSelectBox(convertJSONToArrayInit(re_generator), "re_generator_1 offgrid_pricing", "re_generator_1")
            , makeSelectBox(convertJSONToArrayInit(re_generator), "re_generator_2 offgrid_pricing", "re_generator_2")
            , makeSelectBox(convertJSONToArrayInit(re_generator), "re_generator_3 offgrid_pricing", "re_generator_3")
            , makeSelectBox(convertJSONToArrayInit(re_generator), "re_generator_4 offgrid_pricing", "re_generator_4")
            , makeSelectBox(convertJSONToArrayInit(re_generator), "re_generator_5 offgrid_pricing", "re_generator_5")
            , makeSelectBox(convertJSONToArrayInit(re_generator), "re_generator_6 offgrid_pricing", "re_generator_6")],
        ["", "&nbsp;"],
        ["Grand total"
            , makeInputBox("offgrid_pricing", "og_total_1", true)
            , makeInputBox("offgrid_pricing", "og_total_2", true)
            , makeInputBox("offgrid_pricing", "og_total_3", true)
            , makeInputBox("offgrid_pricing", "og_total_4", true)
            , makeInputBox("offgrid_pricing", "og_total_5", true)
            , makeInputBox("offgrid_pricing", "og_total_6", true)],
        ["PE Admin (%)", "<input type='number' class='offgrid_pricing' name='pe_admin_percent' id='pe_admin_percent' value='30' />"],
    ];
    
    // .:nhantv:. Update to set order before "Save and Generate Quote" field
    $('body').find("#generate_quote").before(offgrid_pricing_table);

    // .:nhantv:. Add button "Calculate Price"
    // $('body').find("#generate_quote").before("<button id='calculate_og_price' class='button default' style='display: block'>Calculate Price</button>");

    makeTable(offgrid_pricing_table, data, "offgrid_pricing", "offgrid_pricing");
    //css Table
    $(".offgrid_pricing td").css({"padding":"0px 5px"});
    $(".offgrid_pricing th").css({"padding":"0px 5px"});
    $(".offgrid_pricing th:first-child").css({"width":"160px"});
    $(".offgrid_pricing select, .offgrid_pricing input[class*='offgrid_pricing']:not([type='checkbox'])").css({"width":"100%"});

    // Load Off-Grid Option
    loadOffgridOption();
}
