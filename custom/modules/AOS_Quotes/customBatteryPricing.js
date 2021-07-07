var battery_main, battery_install;
const install_battery = ["Battery Storage Install"];
const BOS_battery = ["Battery Storage BOS Components"];
const supply_battery = ["Battery Storage Supply and Install"];

$(function () {
    'use strict';

     //TUAN - ++++++++++++++++++++++++ BATTERY QUOTE INPUTS ++++++++++++++++++++++++++++++++++

     $(document).on('click', '*[id*="clear_bat_option"]', function(e){
        e.preventDefault();
        Bat_clearOption($(this).data('option'));
    });
    // Calculate Price Button Click handle 
    $(document).on('click', '#calculate_bat_price', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
                Bat_calcOption(i);
        }
        Bat_calcHint();
    });
    $(document).on("change", "select[id*='main_battery_type'],input[id*='qty_bat']", function(e){
        var index  = $(this).attr("id").split('_');
        // let item_no = $(this).attr('id').charAt($(this).attr('id').length-3);
        // let selector = '', type = '', qty_id ='';
        // let num_of_line = 1;
        // let value_selected = $(this).val();
        index = index[index.length -1];

        Bat_calcOption(index);
    });
    /** Extra Add Button Click handle */ 
    $(document).on('click', '#main_add_battery', function(e){
        e.preventDefault();
        let attr_id = $(e.target).attr('id');
        Bat_createNewLine('main');
    });

});


//TUAN - ++++++++++++++++++++++++ BATTERY QUOTE INPUTS FUNCTION++++++++++++++++++++++++++++++++++
async function init_table_battery() {
    // Call API get Offgrid Product
    try{

        await $.ajax({
            url: '/index.php?entryPoint=APIGetBatteryProduct'
        }).then(function(result) {
            let dataJSON = JSON.parse(result);
            // Set global var
            battery_main = dataJSON.battery_main;
            battery_install = dataJSON.battery_install;

        });
    } catch (ex) {
        console.log(ex);
    }

    let battery_pricing_table   = $('<div id="battery_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row" style="margin-bottom: 20px;"></div>');
    let data = [
        ["Selected Option"
            ,"<input data-attr='1' type='checkbox' class='battery_option battery_pricing' name='battery_option' id='battery_option_1' style='margin-bottom:5px'> Option 1"
            ,"<input data-attr='2' type='checkbox' class='battery_option battery_pricing' name='battery_option' id='battery_option_2' style='margin-bottom:5px'> Option 2"
            ,"<input data-attr='3' type='checkbox' class='battery_option battery_pricing' name='battery_option' id='battery_option_3' style='margin-bottom:5px'> Option 3"
            ,"<input data-attr='4' type='checkbox' class='battery_option battery_pricing' name='battery_option' id='battery_option_4' style='margin-bottom:5px'> Option 4"
            ,"<input data-attr='5' type='checkbox' class='battery_option battery_pricing' name='battery_option' id='battery_option_5' style='margin-bottom:5px'> Option 5"
            ,"<input data-attr='6' type='checkbox' class='battery_option battery_pricing' name='battery_option' id='battery_option_6' style='margin-bottom:5px'> Option 6"],
        [""
            , "<button data-option ='1' id='clear_bat_option_1' class='button default'>Clear Option 1</button>"
            , "<button data-option ='2' id='clear_bat_option_2' class='button default'>Clear Option 2</button>"
            , "<button data-option ='3' id='clear_bat_option_3' class='button default'>Clear Option 3</button>"
            , "<button data-option ='4' id='clear_bat_option_4' class='button default'>Clear Option 4</button>"
            , "<button data-option ='5' id='clear_bat_option_5' class='button default'>Clear Option 5</button>"
            , "<button data-option ='6' id='clear_bat_option_6' class='button default'>Clear Option 6</button>"],
        ["Battery Storage Capacity (kWh):"
            , makeInputBox("battery_pricing battery_storage_capacity_1", "battery_storage_capacity_1", true)
            , makeInputBox("battery_pricing battery_storage_capacity_2", "battery_storage_capacity_2", true)
            , makeInputBox("battery_pricing battery_storage_capacity_3", "battery_storage_capacity_3", true)
            , makeInputBox("battery_pricing battery_storage_capacity_4", "battery_storage_capacity_4", true)
            , makeInputBox("battery_pricing battery_storage_capacity_5", "battery_storage_capacity_5", true)
            , makeInputBox("battery_pricing battery_storage_capacity_6", "battery_storage_capacity_6", true)],
        ["Battery Type 1"
            , makeSelectBox(Bat_convertJSONToArrayInit(battery_main), "main_battery_type1_1 battery_pricing", "main_battery_type1_1") 
            , makeSelectBox(Bat_convertJSONToArrayInit(battery_main), "main_battery_type1_2 battery_pricing", "main_battery_type1_2")
            , makeSelectBox(Bat_convertJSONToArrayInit(battery_main), "main_battery_type1_3 battery_pricing", "main_battery_type1_3")
            , makeSelectBox(Bat_convertJSONToArrayInit(battery_main), "main_battery_type1_4 battery_pricing", "main_battery_type1_4")
            , makeSelectBox(Bat_convertJSONToArrayInit(battery_main), "main_battery_type1_5 battery_pricing", "main_battery_type1_5")
            , makeSelectBox(Bat_convertJSONToArrayInit(battery_main), "main_battery_type1_6 battery_pricing", "main_battery_type1_6")],
        ["Number Battery 1"
            , makeInputBox("qty_bat1_1 battery_pricing", "qty_bat1_1", false)
            , makeInputBox("qty_bat1_2 battery_pricing", "qty_bat1_2", false)
            , makeInputBox("qty_bat1_3 battery_pricing", "qty_bat1_3", false)
            , makeInputBox("qty_bat1_4 battery_pricing", "qty_bat1_4", false)
            , makeInputBox("qty_bat1_5 battery_pricing", "qty_bat1_5", false)
            , makeInputBox("qty_bat1_6 battery_pricing", "qty_bat1_6", false)],
        ["<button id='main_add_battery' class='button default'>+</button>"
            , "<input type='hidden' class='battery_pricing' name='main_line' id='main_line' value='1' />"],
        // ["NOCT"
        //     , makeInputBox( "noct_bat_type_1 battery_pricing", "noct_bat_type_1")
        //     , makeInputBox( "noct_bat_type_2 battery_pricing", "noct_bat_type_2")
        //     , makeInputBox( "noct_bat_type_3 battery_pricing", "noct_bat_type_3")
        //     , makeInputBox( "noct_bat_type_4 battery_pricing", "noct_bat_type_4")
        //     , makeInputBox( "noct_bat_type_5 battery_pricing", "noct_bat_type_5")
        //     , makeInputBox( "noct_bat_type_6 battery_pricing", "noct_bat_type_6")],
        ["", "&nbsp;"],
        ["Battery Install"
        , makeSelectBox(['Yes', 'No'], "install_bat_1 battery_pricing", "install_bat_1")
        , makeSelectBox(['Yes', 'No'], "install_bat_2 battery_pricing", "install_bat_2")
        , makeSelectBox(['Yes', 'No'], "install_bat_3 battery_pricing", "install_bat_3")
        , makeSelectBox(['Yes', 'No'], "install_bat_4 battery_pricing", "install_bat_4")
        , makeSelectBox(['Yes', 'No'], "install_bat_5 battery_pricing", "install_bat_5")
        , makeSelectBox(['Yes', 'No'], "install_bat_6 battery_pricing", "install_bat_6")],
        // // ["<button id='calculate_dk' class='button default'>Max</button>", "&nbsp;"],
        // ["", "&nbsp;"],
        // ["Extra 1"
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_1 daikin_pricing", "extra_dk_type1_1")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_2 daikin_pricing", "extra_dk_type1_2")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_3 daikin_pricing", "extra_dk_type1_3")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_4 daikin_pricing", "extra_dk_type1_4")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_5 daikin_pricing", "extra_dk_type1_5")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_6 daikin_pricing", "extra_dk_type1_6")],
        // ["Extra (number/price) 1"
        //     , makeTwoInputBox("expand_ext extra_dk_type_1 daikin_pricing", "qty_ext_dk1_1", "price_ext_dk1_1" ,false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_2 daikin_pricing", "qty_ext_dk1_2", "price_ext_dk1_2", false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_3 daikin_pricing", "qty_ext_dk1_3", "price_ext_dk1_3", false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_4 daikin_pricing", "qty_ext_dk1_4", "price_ext_dk1_4", false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_5 daikin_pricing", "qty_ext_dk1_5", "price_ext_dk1_5", false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_6 daikin_pricing", "qty_ext_dk1_6", "price_ext_dk1_6", false)],
        // ["<button id='extra_add' class='button default'>+</button>"
        //     , "<input type='hidden' class='daikin_pricing' name='extra_line' id='extra_line' value='1' />"],
        ["", "&nbsp;"],
        ["Grand total:"
            , makeInputBox("battery_pricing", "grandtotal_bat_1", true)
            , makeInputBox("battery_pricing", "grandtotal_bat_2", true)
            , makeInputBox("battery_pricing", "grandtotal_bat_3", true)
            , makeInputBox("battery_pricing", "grandtotal_bat_4", true)
            , makeInputBox("battery_pricing", "grandtotal_bat_5", true)
            , makeInputBox("battery_pricing", "grandtotal_bat_6", true)],
        // ["PE Admin (%)", "<input type='number' class='daikin_pricing' name='dk_pe_admin_percent' id='dk_pe_admin_percent' value='19' />"],
    ];
    
    //  Update to set order before "Save and Generate Quote" field
    $('body').find("#generate_quote").before(battery_pricing_table);
    $('body').find("#generate_quote").before("<button type='button' id='calculate_bat_price' class='button default' style='display: block'>Calculate Price </button>");
    makeTable(battery_pricing_table, data, "battery_pricing", "battery_pricing");
    //css Table
    $(".battery_pricing td").css({"padding":"0px 5px"});
    $(".battery_pricing th").css({"padding":"0px 5px"});
    $(".battery_pricing th:first-child").css({"width":"160px"});
    $(".battery_pricing select, .battery_pricing input[class*='battery_pricing']:not([type='checkbox'])").css({"width":"100%"});
    $(".expand_ext").css({"width":"48%"});

    // Load Battery Option
    Bat_loadOption();

    Bat_initHint();
}
function Bat_initHint(){
    // Show button
    // $('body').find("#generate_quote").before("<button type='button' id='show_bat_hint' class='button default' style='display: block'>Hide Calc Hint</button>");
    // Append texarea
    $('body').find("#generate_quote").before("<div id='bat_hint'>"
        +"<div id='bat_hint1' style='display: inline-block;width: 500px;'></div>"
        +"<div id='bat_hint2' style='display: inline-block;width: 500px;'></div>"
        +"</div>");
}
//Convert JSON to Array data
function Bat_convertJSONToArrayInit(jsonData){
    let result = [''];
    if (typeof(jsonData) != 'undefined') {
        jsonData.forEach(element => {
            if( element.short_name != "" ){
                result.push(element.short_name);
            }else {
                result.push(element.name);
            }
        });
    }
    return result;
}

function Bat_getCountLine(target){
    return parseInt($('#'+ target +'_line').val());
}

//Load option
function Bat_loadOption(){
    
    if($("#quote_note_inputs_c").val() != ""){
        try{
            var json_val = JSON.parse($("#quote_note_inputs_c").val());
            // Create main line
            let current_line = Bat_getCountLine('main');
            let item_line = (json_val.main_line != undefined && json_val.main_line != '') ? json_val.main_line : 1;
            if (item_line > current_line) {
                for (let i = 0; i < (item_line - current_line); i++) {
                    Bat_createNewLine('main');
                }
            }
            
            for (const [key, v] of Object.entries(json_val)) {
                if (isNaN(key)) {
                        if($("#"+key).attr('type') == 'checkbox'){
                            $("#"+key).prop( "checked", json_val[key] );
                        } else {
                            $("#"+key).val(json_val[key]);
                        }
                } else {
                    for (const [vkey, vvalue] of Object.entries(v)) {
                        if (typeof vvalue == "object") {
                            for (const [vvkey, vvvalue] of Object.entries(vvalue)) {
                                for (const [vvvkey, vvvvalue] of Object.entries(vvvalue)) {
                                    if($("#"+vvvkey).attr('type') == 'checkbox'){
                                        $("#"+vvvkey).prop( "checked", vvvvalue);
                                    } else {
                                        $("#"+vvvkey).val(vvvvalue);
                                    }
                                }
                            }
                        } else {
                            if($("#"+vkey).attr('type') == 'checkbox'){
                                $("#"+vkey).prop( "checked", vvalue);
                            } else {
                                $("#"+vkey).val(vvalue);
                            }
                        }
                    }
                }
            }
        } catch (err) {
            console.log(err);
        }
    }
}

//Get current option state
function Bat_getCurrentOptionState(index){
    let result = {};
    let state = $('#install_address_state_c').val();
    result['state'] = state;
    result['index'] = index;
    result['install_bat'+ index] =  $('#install_bat'+ '_' + index).val();
    let num_of_line = Bat_getCountLine('main');
    for (var i = 0; i < num_of_line; i++) {
        result['main_battery_type' + (i + 1) ] = $('#main_battery_type' + (i + 1)+ '_' + index).val() ;
        result['battery_storage_capacity' + (i + 1) ] = parseFloat(getAttributeFromName($('#main_battery_type' + (i + 1) + '_' + index).val(), battery_main, 'capacity')).formatMoney(2, ',', '.');
        result['qty_bat' + (i + 1) ] = $('#qty_bat' + (i + 1) + '_' + index).val();
    }
    
    return result;
}


function Bat_calcOption(index) {
    if(index != '' && index != undefined){
        let currState = Bat_getCurrentOptionState(index);
        // Grand Total
        let grandTotal = Bat_calcGrandTotal(currState);
        $("#grandtotal_bat_"+index).val(parseFloat(roundTo90(grandTotal)).formatMoney(2, ',', '.'));
        // Save current option
        // Bat_saveCurrentState();
    }
}

function Bat_calcGrandTotal(currState){
    let grandTotal = 0;
    // Equipment cost
    grandTotal += Bat_calcEquipmentCost(currState);
    // Install + Delivery cost
    // grandTotal += DK_calcInstallCost(currState);
    // GST 10%
    let gst = grandTotal * 0.1;
    // Include GST above
    grandTotal += gst;

    return grandTotal;
}

function Bat_calcEquipmentCost(currState){
    let main_cost = 0,total_storage_capacity = 0;
    // Battery main cost
    let num_of_line = Bat_getCountLine('main');
    for (var i = 0; i < num_of_line; i++) {
        main_cost += parseFloat(getAttributeFromName(currState['main_battery_type' + (i + 1)], battery_main, "cost")) * parseFloat(currState['qty_bat'+ (i + 1)]);
        total_storage_capacity += parseFloat(currState['battery_storage_capacity'+ (i + 1)]) * parseFloat(currState['qty_bat'+ (i + 1)]);
    }
    $(`#battery_storage_capacity_${currState['index']}`).val(total_storage_capacity.formatMoney(2, ',', '.'));

    return main_cost;
}

function Bat_clearOption(option){
    $("#battery_option_"+(option)).prop('checked', false);
    $('#battery_pricing_table td:nth-child('+ (option + 1) +') input').val('');
    $('#battery_pricing_table td:nth-child('+ (option + 1) +')').find('select').prop("selectedIndex", 0);
}
//Create new line
function Bat_createNewLine(target = 'extra'){
    var label, id, list, label1, id1, id2;
    if (target == 'main') {
        label = "Battery Type ";
        id = "main_battery_type";
        list = battery_main;
        label1 = "Number Battery ";
        id1 = "qty_bat";
    }
    let next_index = Bat_getCountLine(target) + 1;
    let new_tr = document.createElement('tr');
    let new_tr1 = document.createElement('tr');
    for (var i = 0; i < 7; i++) {
        let td = document.createElement('td');
        td.style.padding = "0px 5px";
        let td1 = document.createElement('td');
        td1.style.padding = "0px 5px";
        if(i == 0){
            // First td
            td.style.width = "160px";
            td.innerHTML = label + next_index;
            td1.style.width = "160px";
            td1.innerHTML = label1 + next_index;

        } else {
            // Other td
            let input;
            let select = makeSelectBox(Bat_convertJSONToArrayInit(list), `${id}${next_index}_${i} battery_pricing`, id + next_index + "_" + i);
            select.css({"width":"100%"});
            if (target == 'main') {
                input = makeInputBox(`${id1}${next_index}_${i} battery_pricing`, `${id1}${next_index}_${i}`, false);
                input.css({"width":"100%"});
            } else {
                input = makeTwoInputBox(`${id}${next_index}_${i} battery_pricing`, `${id1}${next_index}_${i}`, `${id2}${next_index}_${i}`, false);
                // input.css({"width":"100%"});
            }
            $(td).html(select);
            $(td1).html(input);
        }
        new_tr.appendChild(td);
        new_tr1.appendChild(td1);
    }
    $('#'+ target +'_add_battery').closest('tr').before(new_tr, new_tr1);
    $('#'+ target +'_line').val(next_index);
}
function Bat_getCountLine(target){
    return parseInt($('#'+ target +'_line').val());
}
function Bat_writeHint(key, value, number = '', isBreakLine = false, isHeader = false){
    return (isBreakLine ? '<p style="width: 400px; text-align: center;display: block;">-----------------------------------------------------------------------</p>' : '') 
        + '<'+ (isHeader ? 'h3' : 'p') +' style="width: 250px; display: inline-block;margin:0;">'
        + ' ' + (number != '' ?  `${number} x ` : '') + key 
        + '</'+ (isHeader ? 'h3' : 'p') +'><p style="width: 150px; text-align: right;display: inline-block;">' + (value != '' ? parseFloat(value).toFixed(2) : '') + '</p></br>' 
        + (isBreakLine ? '</br>' : '');
}

function Bat_calcHint(){
    $('#bat_hint1').html('');
    $('#bat_hint2').html('');
    // Check index
    let index = $('input[name="battery_option"]:checked').attr('data-attr');
    if (!index){
        $('#bat_hint1').html("You must choose the Option to see calc hint");
        alert("You must choose the Option to see calc hint");
        return;
    }
    let currState = Bat_getCurrentOptionState(index);
    let str = "";
    /** ==S== HINT 1 ==== */
         /** S - Equipment Cost */ 
        let numbers_battery =0 ,main_cost = 0, supply_cost = 0,install_cost = 0, bos_cost = 0;
        let num_of_line = Bat_getCountLine('main');
        for (var i = 0; i < num_of_line; i++) {
            if(currState['main_battery_type' + (i + 1)] != '' && currState['qty_bat'+(i+1)] != ''){
                main_cost += parseFloat(getAttributeFromName(currState['main_battery_type' + (i + 1)], battery_main, "cost")) * parseFloat(currState['qty_bat'+ (i + 1)]);
                str += Bat_writeHint(currState['main_battery_type' + (i + 1)], parseFloat(getAttributeFromName(currState['main_battery_type' + (i + 1)], battery_main, "cost")) * parseFloat(currState['qty_bat'+(i+1)]), parseFloat(currState['qty_bat'+(i+1)]));
                numbers_battery += parseFloat(currState['qty_bat'+(i + 1)]);
            }
        }
    // total equipment cost
    let equipment = main_cost;
    str += Bat_writeHint(
        "TOTAL EQUIPMENT COST"
        , equipment
        , ''
        , true
        , true
    );
    /** S - Install and supply */
        //Battery supply 
        supply_cost = parseFloat(getAttributeFromName(supply_battery[0], battery_install, 'cost'));
        str += Bat_writeHint(supply_battery[0],supply_cost);
        bos_cost = parseFloat(getAttributeFromName(BOS_battery[0], battery_install, 'cost'));
        str += Bat_writeHint(BOS_battery[0],bos_cost);
        // Battery install
        if (currState['install_bat' + index] == 'Yes') {
            install_cost = parseFloat(getAttributeFromName(install_battery[0], battery_install, 'cost'));
            str+= Bat_writeHint(install_battery[0],install_cost, install_cost);
        }
        let ins_delivery = supply_cost + install_cost + bos_cost;
        // str += Bat_writeHint(
        //     "TOTAL SUPPLY AND INSTALL COST"
        //     , ins_delivery
        //     , ''
        //     , true
        //     , true
        // );
    /** E - Install and supply */

    /** S - Subtotal = Equipment + install + supply */
        str += Bat_writeHint(
            "SUBTOTAL (Supply + Install + BOS Components)"
            , (equipment + ins_delivery)
            , ''
            , true
            , true
        );
    /** E - Subtotal = Equipment + install + supply */

    // Subtotal + PE Admin
    let grandTotal = equipment + ins_delivery;

    // GST 10%
    let gst = grandTotal * 0.1;
    str += Bat_writeHint(
        'GST 10%'
        , gst
    );
    // Include GST
    grandTotal += gst;
    str += Bat_writeHint(
        'Grand Total inclue GST'
        , roundTo90(grandTotal)
    );

    // Return
    $('#bat_hint1').append(str);
    // $('#dk_hint2').append(str2);
    // Save current option
    Bat_saveCurrentState();
}
function Bat_saveCurrentState(){
    let result = {};
    let state = $("#install_address_state_c").val();
    let check_main = {};
    $("#battery_pricing_table .battery_pricing").each(function (){
        let opt = {};
        let id_product = '', partNumber_product = '', name_product = '';
        var id_name = $(this).attr("id");
        let item_no = id_name.charAt(id_name.length-3);
        let option = id_name.split('_').pop();
        // if (!isNaN(option) && option > 1) {
        //     return true;
        // }

        if (isNaN(option)) {
            result[id_name] = $(this).val();
            return true;
        }
        if($("#"+id_name).attr('type')== 'checkbox'){
            opt[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
        } else {
            opt[id_name] = $(this).val();
        }

        //Main
        if (id_name.indexOf('main_battery_type') != -1 || id_name.indexOf('qty_bat') != -1 || id_name.indexOf('battery_storage_capacity') != -1) {
            if (!result[option].hasOwnProperty('products')) {
                result[option].products = {};
            }
            if (id_name.indexOf('battery_storage_capacity') != -1) {
                if (!check_main.hasOwnProperty(option)) {
                    check_main[option] = 0;
                }
                check_main[option] += parseInt(opt[id_name] != '' ? opt[id_name] : 0);
            }
            if(id_name.indexOf('main_battery_type') != -1) {
                if(opt[id_name] != ""){
                    id_product = getAttributeFromName(opt[id_name], battery_main, 'id') != '' ?  getAttributeFromName(opt[id_name], battery_main, 'id') : '';
                    partNumber_product = getAttributeFromName(opt[id_name], battery_main, 'part_number') != '' ? getAttributeFromName(opt[id_name], battery_main, 'part_number') : '';
                    name_product = getAttributeFromName(opt[id_name], battery_main, 'name') != '' ? getAttributeFromName(opt[id_name], battery_main, 'name') : '';
                }
                result[option].products[item_no] = {...result[option].products[item_no], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};

            }
            if (id_name.indexOf('qty_bat') != -1) {
                if (!check_main.hasOwnProperty(option)) {
                    check_main[option] = 0;
                }
                check_main[option] += parseInt(opt[id_name] != '' ? opt[id_name] : 0);
            }

            result[option].products[item_no] = {...result[option].products[item_no], ...opt};
            return true;
        }
        //Check install
        if (id_name.indexOf('install_bat') != -1) {
            $(this).val() == 'Yes' ? opt['install_standard'] = install_battery[0] : opt['install_standard'] = '';
        }

        result[option] = {...result[option], ...opt};
    });
    //check send email battery pricing option
    for (const [k, qty] of Object.entries(check_main)) {
        if (qty > 0) {
            result[k] = {...result[k],...{'isSend': 1}};
        } else {
            result[k] = {...result[k],...{'isSend': 0}};
        }
        result[k] = {...result[k],...{'install_qty': qty}};
    }
    
    //add state
    result = {...result, ...{'state': state}};
    $("#quote_note_inputs_c").val(JSON.stringify(result));
}
async function Battery_generateItem(){
    // Save current state
    Bat_saveCurrentState();

    // Get Option Quote
    let index = $('input[name="battery_option"]:checked').attr('data-attr');
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
    // $("#group0name").val("Off-Grid Option " + index);

    // Get option

    // await wait(300);

    let currState = Bat_getCurrentOptionState(index);
    // Create line item
    try{
        // Show loading
        SUGAR.ajaxUI.showLoadingPanel();
        // supply 
        await Bat_autoCreateLineItem(supply_battery[0], battery_install, 1);
        // Main line
        let num_of_line = getCountLine('main');
        for (let i = 0; i < num_of_line; i++) {
            if (currState['main_battery_type' + (i + 1)] != "" && parseInt(currState['qty_bat' + (i + 1)]) != 0) {
                await Bat_autoCreateLineItem(currState['main_battery_type' + (i + 1)], battery_main, currState['qty_bat' + (i + 1)]);
            }
        }
        // BOS 
        await Bat_autoCreateLineItem(BOS_battery[0], battery_install, 1);
        if (currState['install_bat'+index] == 'Yes') {
            await Bat_autoCreateLineItem(install_battery[0], battery_install, 1);
        }
        // Calculate
        await Bat_calculatePrice(currState);

        
    } catch(err) {
        console.log(err);
    }
     finally {
        // Hide loading
        setTimeout(function (){
            SUGAR.ajaxUI.hideLoadingPanel();
            $("#generate_quote").removeAttr('style');
        }, 300);
    }
}

async function Bat_autoCreateLineItem(shortName, target, total_item, price_item = ''){
    var info_pro = getItemFromName(shortName, target);
    if(info_pro['id'] !== undefined){
        insertProductLine('product_group0', '0');
        lineno  = prodln-1;
        var popupReplyData = {};
        popupReplyData.form_name = "EditView";
        var name_to_value_array = {};
        name_to_value_array["product_currency"+lineno] = info_pro['currency'];
        name_to_value_array["product_item_description"+lineno] = (info_pro['description'] == null) ? '' : info_pro['description'];
        name_to_value_array["product_name"+lineno] = info_pro['name'];
        name_to_value_array["product_part_number"+lineno] =  info_pro['part_number'];
        name_to_value_array["product_product_id"+lineno] = info_pro['id'];
        if (price_item != '') { //User enter price extra
            name_to_value_array["product_product_cost_price"+lineno] = price_item;
            name_to_value_array["product_product_list_price"+lineno] = "" + price_item;
        } else {
            name_to_value_array["product_product_cost_price"+lineno] = info_pro['cost'];
            name_to_value_array["product_product_list_price"+lineno] = "" + info_pro['cost'];
        }
        name_to_value_array["product_product_qty"+lineno] = "" + parseFloat(total_item);
        popupReplyData["name_to_value_array"] = name_to_value_array;            
        $('#product_product_list_price'+lineno).focus();
        $('#product_product_id'+lineno).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ info_pro['id'] +'">Link</a></div>');
        setProductReturn(popupReplyData);
    }
}
async function Bat_calculatePrice(currState = {}){
    
    let productVisible = $('.product_group').find('tbody[id*=product_body]:visible');
    var totalList = 0, totalDiscount = 0, totalAmount = 0;
    var list, dis, amount, tax;
    let grandTotal = parseFloat(roundTo90(Bat_calcGrandTotal(currState))).formatMoney(2, ',', '.')
    // For each
    productVisible.each((index, el) => {
        // // get target
        list = $(el).find('input[id*=product_product_list_price]');
        // dis = $(el).find('input[id*=product_product_discount]');
        // amount = $(el).find('input[id*=product_product_total_price]');
        tax = $(el).find('select[id*=product_vat]');

        if(index !== 0 && index < productVisible.length){
            // // calculate line item exclude first line and last line
            // totalList += get_value(list.attr('id'));
            // totalDiscount += get_value(dis.attr('id'));
            // totalAmount += get_value(amount.attr('id'));
            set_value(list.attr('id'), "");
            $(tax).val('0.0');
        }
        // blur
        list.trigger("blur");
    });
    await wait(200);
    $("#total_amount").trigger("focusin");
    
    // Set value to first line
    list = $(productVisible[0]).find('input[id*=product_product_list_price]');
    set_value(list.attr('id'), parseFloat(grandTotal));
    list.trigger("blur");

    // Set value to grand total
    $("#total_amount").trigger("focusin");
    $("#total_amount").val(grandTotal);
    $("#total_amount").trigger("change");

    // Scroll top offset
    jQuery('html,body').animate({scrollTop: jQuery('.panel-heading a div:contains(Line Items)').offset().top - 200}, 500);
}


//***************************************** ??? *********************************************************** */
