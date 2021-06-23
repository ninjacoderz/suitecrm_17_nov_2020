var sol_accessory,solar_extra = [];
const extra_solar_products = ["Solar PV Standard Install", "Solar PV Balance Of System", "STCs","Solar PV Supply and Install"];

$(function(){

    'use strict';
    //Disable field number 
    $("#number").prop("disabled", true);
    
    var quote_type = $("#product_type_c").val();
    if( quote_type != ""){
        switch (quote_type) {
            case 'off_grid':
                // Init table grid
                init_table_offgrid();
                break;
            case 'solar':
                // Init table grid
                
                init_table_solar();
                
                break;
            case 'daikin':
                init_table_daikin();
                break;
            case 'sanden':
                var seletor_panel_pricing_pv = '';
                $('.panel-content .panel-default').each(function(){
                    var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
                    if(title_panel_default == 'pricing options'){
                        seletor_panel_pricing_pv = '#' + $(this).find('.panel-body').attr('id');
                    }
                })
                $(seletor_panel_pricing_pv).find(".tab-content").html('');
                // init_table_solar();
                break;
            default: break;
        }
    }

    $(document).on('click', '*[id*="clear_sl_option"]', function(e){
        e.preventDefault();
        SL_clearOption($(this).data('option'));
    }); 

    $(document).on('click', '#calculate_sl', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
            var panel_type = $("#panel_sl_type_"+i).val();
            // Get suggested
            if(panel_type != '' && SL_isInverterHasValue(i)){
                // Calculate option
                SL_autoFillAccessory(i);
                SL_calcOption(i);
            }
        }
    });

    $(document).on('click', '#sl_inverter_add, #sl_accessory_add', function(e){
        e.preventDefault();
        let attr_id = $(e.target).attr('id');
        if (attr_id.indexOf('inverter') != -1) {
            SL_createNewLine('sl_inverter');
        } else {
            SL_createNewLine('sl_accessory');
        }
    });
});

async function init_table_solar() {
    // Call API get Offgrid Product
    try{
        SL_getExtraProduct();
        await $.ajax({
            url: '/index.php?entryPoint=APIGetSolarProduct'
        }).then(function(result) {
            let dataJSON = JSON.parse(result);
            // Set global var
            sol_panel = dataJSON.panel_data;
            sol_inverter = dataJSON.inverter_data;
            sol_accessory = dataJSON.accessory_data;
        });
    } catch (ex) {
        console.log(ex);
    }

    let solar_pricing_table   = $('<div id="solar_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row" style="margin-bottom: 20px;"></div>');
    let data = [
        ["Option"
            ,"1"
            ,"2"
            ,"3"
            ,"4"
            ,"5"
            ,"6"],
        [""
            , "<button data-option ='1' id='clear_sl_option_1' class='button default'>Clear Option 1</button>"
            , "<button data-option ='2' id='clear_sl_option_2' class='button default'>Clear Option 2</button>"
            , "<button data-option ='3' id='clear_sl_option_3' class='button default'>Clear Option 3</button>"
            , "<button data-option ='4' id='clear_sl_option_4' class='button default'>Clear Option 4</button>"
            , "<button data-option ='5' id='clear_sl_option_5' class='button default'>Clear Option 5</button>"
            , "<button data-option ='6' id='clear_sl_option_6' class='button default'>Clear Option 6</button>"],
        ["PM:"
            , makeInputBox("sl_pm1 solar_pricing", "sl_pm1", false)
            , makeInputBox("sl_pm2 solar_pricing", "sl_pm2", false)
            , makeInputBox("sl_pm3 solar_pricing", "sl_pm3", false)
            , makeInputBox("sl_pm4 solar_pricing", "sl_pm4", false)
            , makeInputBox("sl_pm5 solar_pricing", "sl_pm5", false)
            , makeInputBox("sl_pm6 solar_pricing", "sl_pm6", false)],
        // ["Module Capacity kW:"
        //     , makeInputBox("total_sl_kW_1 solar_pricing", "total_sl_kW_1", true)
        //     , makeInputBox("total_sl_kW_2 solar_pricing", "total_sl_kW_2", true)
        //     , makeInputBox("total_sl_kW_3 solar_pricing", "total_sl_kW_3", true)
        //     , makeInputBox("total_sl_kW_4 solar_pricing", "total_sl_kW_4", true)
        //     , makeInputBox("total_sl_kW_5 solar_pricing", "total_sl_kW_5", true)
        //     , makeInputBox("total_sl_kW_6 solar_pricing", "total_sl_kW_6", true)],
        // ["Inverter Capacity kW:"
        //     , makeInputBox("total_inverter_sl_kW_1 solar_pricing", "total_inverter_sl_kW_1", true)
        //     , makeInputBox("total_inverter_sl_kW_2 solar_pricing", "total_inverter_sl_kW_2", true)
        //     , makeInputBox("total_inverter_sl_kW_3 solar_pricing", "total_inverter_sl_kW_3", true)
        //     , makeInputBox("total_inverter_sl_kW_4 solar_pricing", "total_inverter_sl_kW_4", true)
        //     , makeInputBox("total_inverter_sl_kW_5 solar_pricing", "total_inverter_sl_kW_5", true)
        //     , makeInputBox("total_inverter_sl_kW_6 solar_pricing", "total_inverter_sl_kW_6", true)],
        ["Panel Type"
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_sl_type_1 solar_pricing", "panel_sl_type_1")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_sl_type_2 solar_pricing", "panel_sl_type_2")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_sl_type_3 solar_pricing", "panel_sl_type_3")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_sl_type_4 solar_pricing", "panel_sl_type_4")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_sl_type_5 solar_pricing", "panel_sl_type_5")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_sl_type_6 solar_pricing", "panel_sl_type_6")],
        ["Inverter Type 1"
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type1_1 solar_pricing", "inverter_sl_type1_1")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type1_2 solar_pricing", "inverter_sl_type1_2")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type1_3 solar_pricing", "inverter_sl_type1_3")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type1_4 solar_pricing", "inverter_sl_type1_4")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type1_5 solar_pricing", "inverter_sl_type1_5")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type1_6 solar_pricing", "inverter_sl_type1_6")],
        ["Inverter Type 2"
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type2_1 solar_pricing", "inverter_sl_type2_1")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type2_2 solar_pricing", "inverter_sl_type2_2")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type2_3 solar_pricing", "inverter_sl_type2_3")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type2_4 solar_pricing", "inverter_sl_type2_4")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type2_5 solar_pricing", "inverter_sl_type2_5")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_sl_type2_6 solar_pricing", "inverter_sl_type2_6")],
        ["<button id='sl_inverter_add' class='button default'>+</button>"
            , "<input type='hidden' class='solar_pricing' name='sl_inverter_line' id='sl_inverter_line' value='2' />"],
        ["Total Panels"
            , makeInputBox("total_sl_panels_1 solar_pricing", "total_sl_panels_1", false)
            , makeInputBox("total_sl_panels_2 solar_pricing", "total_sl_panels_2", false)
            , makeInputBox("total_sl_panels_3 solar_pricing", "total_sl_panels_3", false)
            , makeInputBox("total_sl_panels_4 solar_pricing", "total_sl_panels_4", false)
            , makeInputBox("total_sl_panels_5 solar_pricing", "total_sl_panels_5", false)
            , makeInputBox("total_sl_panels_6 solar_pricing", "total_sl_panels_6", false)],
        ["", "&nbsp;"],
        ["<button id='calculate_sl' class='button default'>Max</button>", "&nbsp;"],
        ["", "&nbsp;"],
        ["Solar Accessory 1"
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory1_1 solar_pricing", "sl_accessory1_1")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory1_2 solar_pricing", "sl_accessory1_2")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory1_3 solar_pricing", "sl_accessory1_3")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory1_4 solar_pricing", "sl_accessory1_4")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory1_5 solar_pricing", "sl_accessory1_5")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory1_6 solar_pricing", "sl_accessory1_6")],
        ["Solar Accessory 2"
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory2_1 solar_pricing", "sl_accessory2_1")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory2_2 solar_pricing", "sl_accessory2_2")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory2_3 solar_pricing", "sl_accessory2_3")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory2_4 solar_pricing", "sl_accessory2_4")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory2_5 solar_pricing", "sl_accessory2_5")
            , makeSelectBox(convertJSONToArrayInit(sol_accessory), "sl_accessory2_6 solar_pricing", "sl_accessory2_6")],
        ["<button id='sl_accessory_add' class='button default'>+</button>"
            , "<input type='hidden' class='solar_pricing' name='sl_accessory_line' id='sl_accessory_line' value='2' />"],
        ];
    
    //  Update to set order before "Save and Generate Quote" field
    var solar_pv_pricing_table = $("<div id='solar_pv_pricing_table'></div>");
    var seletor_panel_pricing_pv = '';
    $('.panel-content .panel-default').each(function(){
        var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
        if(title_panel_default == 'pricing options'){
            seletor_panel_pricing_pv = '#' + $(this).find('.panel-body').attr('id');
        }
    })
    $(seletor_panel_pricing_pv).find(".tab-content").html(solar_pv_pricing_table);
    makeTable(solar_pv_pricing_table, data, "solar_pricing", "solar_pricing");
    //css Table
    $(".solar_pricing td").css({"padding":"0px 5px"});
    $(".solar_pricing th").css({"padding":"0px 5px"});
    $(".solar_pricing th:first-child").css({"width":"160px"});
    $(".solar_pricing select, .solar_pricing input[class*='solar_pricing']:not([type='checkbox'])").css({"width":"100%"});

    // Load Solar Option
    SL_loadOption();
    parseandsetvalue();

    $('table[id="solar_pricing"]').after('<br><button type="button" class="button primary" id="save_options"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Save Options </button><div class="clearfix"></div>');
    $("#save_options").on("click",function(){
        save_values('solar_pricing','solar_pricing');
    })
}

function makeTable(container, data, tclass, tid) {
    var table = $("<table/>").addClass(tclass).attr("id",tid);
    $.each(data, function(rowIndex, r) {
        var row = $("<tr/>");
        $.each(r, function(colIndex, c) { 
            row.append($("<t"+(rowIndex == 0 ?  "h" : "d")+"/>").html(c));
        });
        table.append(row);
    });
    return container.append(table);
}

function makeSelectBox(data, sclass, sid) {
    var select = $("<select/>").addClass(sclass).attr("id",sid);
    $.each(data, function(index, key) {
        select.append( $('<option></option>').val(key).html(key) );
    });
    return select;
}

function makeInputBox(iclass,iid, disabled = false){
    var input = $("<input/>").addClass(iclass).attr("id",iid).prop('disabled', disabled);;
    return input;
}

function SL_loadOption(){
    if($("#own_solar_pv_pricing_c").val() != ""){
        try{
            
            var json_val = JSON.parse($("#pricing_option_input_c").val());
            // Check number of inverter line
            let curr_line_num = SL_getCountLine('sl_inverter');
            let line_num = (json_val.sl_inverter_line != undefined && json_val.sl_inverter_line != '') ? json_val.sl_inverter_line : 2;
            if (line_num > curr_line_num) {
                // Create new Inverter line
                for (let i = 0; i < (line_num - curr_line_num); i++) {
                    SL_createNewLine('sl_inverter');
                }
            }

            // Check number of accesssory line
            curr_line_num = SL_getCountLine('sl_accessory');
            line_num = (json_val.sl_accessory_line != undefined && json_val.sl_accessory_line != '') ? json_val.sl_accessory_line : 2;
            if (line_num > curr_line_num) {
                // Create new Accessory line
                for (let i = 0; i < (line_num - curr_line_num); i++) {
                    SL_createNewLine('sl_accessory');
                }
            }

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

function SL_clearOption(option){
    $("#solar_option_"+(option)).prop('checked', false);
    $('#solar_pv_pricing_table td:nth-child('+ (option + 1) +') input:not(input[id="sl_pe_admin_percent"],input[id="sl_inverter_line"],input[id="sl_accessory_line"])').val('');
    $('#solar_pv_pricing_table td:nth-child('+ (option + 1) +')').find('select').prop("selectedIndex", 0);
}

function SL_getCurrentOptionState(index){
    let result = {};
    result['total_kw'] = $('#total_sl_kW_' + index).val();
    result['total_inverter'] = $('#total_inverter_sl_kW_' + index).val();
    result['panel_type'] = $('#panel_sl_type_' + index).val();
    // Inverter line
    let num_of_line = SL_getCountLine('sl_inverter');
    for (var i = 0; i < num_of_line; i++) {
        result['inverter_type' + (i + 1)] = $('#inverter_sl_type' + (i + 1) + '_' + index).val();
    }    
    result['total_panels'] = $('#total_sl_panels_' + index).val();
    result['number_stcs'] = $('#number_sl_stcs_' + index).val();
    result['accessory1'] = $('#sl_accessory1_' + index).val();
    result['accessory2'] = $('#sl_accessory2_' + index).val();
    // Accessory line
    num_of_line = SL_getCountLine('sl_accessory');
    for (var i = 0; i < num_of_line; i++) {
        result['accessory' + (i + 1)] = $('#sl_accessory' + (i + 1) + '_' + index).val();
    }
    result['pm'] = ($("#sl_pm"+index).val() != '') ? parseFloat($("#sl_pm"+index).val()) : 0;
    return result;
}

function SL_getMaxPanelAndTotalKw(currState, isTotalPanel){
    //const ratio = 1.333;
    const panel_kw = parseFloat(getAttributeFromName(currState.panel_type, sol_panel, "capacity")) / 1000;
    // Get inverter kw
    let inverter_kw = 0;
    let num_of_line = SL_getCountLine('sl_inverter');
    for (let i = 0; i < num_of_line; i++) {
        if (currState['inverter_type' + (i + 1)] != "") {
            inverter_kw += parseFloat(getAttributeFromName(currState['inverter_type' + (i + 1)], sol_inverter, "capacity"));
        }
    }
    const maxPanel = Math.floor((inverter_kw / 0.75) / panel_kw);
    const maxKw = parseFloat((panel_kw * maxPanel).toFixed(3));
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

async function SL_calcOption(index, isTotalPanel = false,isloading = true) {
    var postcode = $("#install_address_postalcode_c").val();
    if(index != '' && index != undefined){
        let currState = SL_getCurrentOptionState(index);
        if(currState.panel_type != '' && currState.inverter_type != ''){
            // Get max panels and total kw
            let maxPnAndTotalKw = SL_getMaxPanelAndTotalKw(currState, isTotalPanel);
            // Set value
            $("#total_sl_panels_"+index).val(maxPnAndTotalKw['max']);
            currState.total_panels = maxPnAndTotalKw['max'];
            $('#total_sl_kW_'+index).val(maxPnAndTotalKw['kw']);
            currState.total_kw = maxPnAndTotalKw['kw'];
            $('#total_inverter_sl_kW_'+index).val(maxPnAndTotalKw['inverter_kw']);
            currState.inverter_kw = maxPnAndTotalKw['inverter_kw'];

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
                        $("#number_sl_stcs_"+index).val(result['NumberOfSTCs']);
                        currState.number_stcs = result['NumberOfSTCs'];
                    }
                });
            } catch (err) {
                console.log(err);
            } finally {
                // Hide loading
                setTimeout(function (){
                    if(isloading){
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }
                }, 300);
            }
            // Save current option
            // Grand Total
            // let grandTotal = SL_calcGrandTotal(currState);
            // $("#total_sl_"+index).val(parseFloat(roundTo90(grandTotal)).formatMoney(2, ',', '.'));
            SL_saveCurrentState();

        }
    }
}

async function SL_autoFillAccessory(index){
    let inverterNo = SL_getCountLine('sl_inverter');
    let accessoryArr = [];
    for(var i = 1; i <= inverterNo ; i++){
        let inverterID = $("#inverter_sl_type"+i+'_'+index);
        if(inverterID.val().toLowerCase().indexOf('primo ') >= 0 ){
            if($("#phases").val() == 'Single Phase'){
                accessoryArr.push('Fro. Smart Meter (1P)'); 
            }else if($("#phases").val() == 'Three Phases'){
                accessoryArr.push('Fro. Smart Meter (3P)');
            }
        }else if( inverterID.val().toLowerCase().indexOf('symo ') >= 0){
            accessoryArr.push('Fro. Smart Meter (3P)');
        }else if(inverterID.val().toLowerCase().indexOf('s edge ') >= 0){
            accessoryArr.push('SE Wifi');
            accessoryArr.push('SE Smart Meter');
        }else if(inverterID.val().toLowerCase().indexOf('sungrow ') >= 0){
            if($("#phases").val() == 'Three Phases'  ){
                accsessoryArr.push('Sungrow Smart Meter (3P)');//'Sungrow Smart Meter (3P)');
            }else if($("#phases").val() == 'Two Phases'){
    
            }else {
                if( inverterID.val().indexOf('3P') >= 0){
                    accessoryArr.push('Sungrow Smart Meter (3P)');//'Sungrow Smart Meter (3P)');
                }else {
                    accessoryArr.push('Sungrow Smart Meter (1P)');
                }   
            }
        }
    }

    let accessoryNo = SL_getCountLine('sl_accessory');

    for(var i = 1; i <= accessoryArr.length ; i++ ){
        if(i <= accessoryNo){
            $("#sl_accessory"+i+'_'+index).val(accessoryArr[i-1]);
        }else{
            SL_createNewLine("sl_accessory");
            await wait(100);
            $("#sl_accessory"+i+'_'+index).val(accessoryArr[i-1]);
        }
    }
    if(accessoryNo > accessoryArr.length){
        for(var i = accessoryArr.length+1 ; i <= accessoryNo; i++ ){
            $("#sl_accessory"+i+'_'+index).val("");
        }
    }
    
}
const wait = ms => {
    return new Promise(res => setTimeout(res, ms));
};
function SL_isInverterHasValue(index){
    let num_of_line = SL_getCountLine('sl_inverter');
    for (let i = 0; i < num_of_line; i++) {
        if ($("#inverter_sl_type" + (i + 1) + "_" + index).val() != '') {
            return true;
        }
    }
    return false;
}

function SL_getCountLine(target){
    return parseInt($('#'+ target +'_line').val());
}

function SL_createNewLine(target = 'sl_inverter'){
    var label = "Inverter Type ", id = "inverter_sl_type", list = sol_inverter;
    if (target == 'sl_accessory') {
        label = "Solar Accessory ";
        id = "sl_accessory";
        list = sol_accessory;
    }

    let next_index = SL_getCountLine(target) + 1;
    let new_tr = document.createElement('tr');
    for (var i = 0; i < 7; i++) {
        let td = document.createElement('td');
        td.style.padding = "0px 5px";
        
        if(i == 0){
            // First td
            td.style.width = "160px";
            td.innerHTML = label + next_index;
        } else {
            // Other td
            let select = makeSelectBox(convertJSONToArrayInit(list), "solar_pricing", id + next_index + "_" + i);
            select.css({"width":"100%"});
            $(td).html(select);
        }
        new_tr.appendChild(td);
    }
    $('#'+ target +'_add').closest('tr').before(new_tr);
    $('#'+ target +'_line').val(next_index);
}

function SL_getExtraProduct(){
    extra_solar_products.forEach((element, index) => {
        $.ajax({
            url: "/index.php?entryPoint=APIGetProductInfoByShortName&short_name=" + element,
            type: 'GET'})
        .then(function(data) {
            if(data !== undefined || data !== ""){
                solar_extra.push(JSON.parse(data));
            }
        });
    });
}

function parseandsetvalue(){
    // Parse json and set value
    if($("#pricing_option_input_c")!=""){
        var json_val = JSON.parse(($("#pricing_option_input_c").val() != "")?$("#pricing_option_input_c").val():"{}");
        for (let key in json_val) {  
            if($("#"+key).attr('type') == 'checkbox'){
                $("#"+key).prop( "checked", json_val[key] );
            } else {
                let value_field = json_val[key];
                if(value_field !== undefined && value_field != ''){
                    // const regex = /S Edge [\d]{1,2}$/;
                    // let m;
                    // if(m = regex.exec(value_field) !== null){
                    //     value_field = value_field+'G';
                    //     $("#"+key).val(value_field);
                    // }else{
                    $("#"+key).val(value_field);
                    // }
                }else{
                    $("#"+key).val(value_field);
                }
            }
        }
    }
}

function SL_saveCurrentState(){
    var values = {};
    $("#solar_pv_pricing_table .solar_pricing").each(function (){
        var id_name = $(this).attr("id");
        if($("#"+id_name).attr('type')== 'checkbox'){
            values[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
        } else {
            values[id_name] = $(this).val();
        }
    });

    $("#pricing_option_input_c").val(JSON.stringify(values));
}

function save_values(type,input){
    var values = {};
    $("#"+type+" ."+input).each(function (){
        var id_name = $(this).attr("id");
        if($("#"+id_name).attr('type')== 'checkbox'){
            values[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
        } else {
            values[id_name] = $(this).val();
        }
        
    });
    $("#pricing_option_input_c").val(JSON.stringify(values));
}