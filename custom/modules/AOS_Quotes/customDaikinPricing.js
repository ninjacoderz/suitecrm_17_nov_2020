var dk_main,dk_extra;
// const extra_solar_products = ["Solar PV Standard Install", "Solar PV Balance Of System", "STCs","Solar PV Supply and Install"];

$(function () {
    'use strict';
    //INIT
    // $(document).find('#own_solar_pv_pricing_c').attr('readonly', 'readonly');
    var quote_type = $("#quote_type_c").val();
    if(quote_type == 'quote_type_daikin'){
        init_table_daikin();
        $(document).find('#generate_quote').hide();
    }

    //************************************************ THIENPB ************************************************ */
    /** Extra Add Button Click handle */ 
    $(document).on('click', '#extra_add, #main_add', function(e){
        e.preventDefault();
        let attr_id = $(e.target).attr('id');
        if (attr_id.indexOf('extra') != -1) {
            DK_createNewLine('extra');
        } else {
            DK_createNewLine('main');
        }
    });

    /** Clear sl button */
    $(document).on('click', '*[id*="clear_dk_option"]', function(e){
        e.preventDefault();
        DK_clearOption($(this).data('option'));
    });
   
    // /** Clear sl button */
    // $(document).on('click', '#calculate_dk', function(e){
    //     e.preventDefault();
    //     for (var i = 1; i < 7; i++) {
    //         var panel_type = $("#main_dk_type_"+i).val();
    //         var inverter_type = $("#extra_dk_type_"+i).val();
    //         // Get suggested
    //         if(panel_type != '' && inverter_type != ""){
    //             // Calculate option
    //             DK_autoFillAccessory(i);
    //             DK_calcOption(i);
    //         }
    //     }
    // });

    $(document).on("change", "select[id*=extra_dk_type]", function(e){
        var index  = $(this).attr("id").split('_');
        index = index[index.length -1];
        DK_autoFillAccessory(index);
        DK_calcOption(index);
    });

    $(document).on("change", "select[id*=extra_dk_type] ,select[id*=main_dk_type_]", function(e){
        var index  = $(this).attr("id").split('_');
        index = index[index.length -1];
        if($("#main_dk_type_"+index).val() != "" && $("#extra_dk_type_"+index).val() != ""){
            DK_autoFillAccessory(index);
            DK_calcOption(index);
        }
    });

    $(document).on("change", "input[id*=total_dk_type_]", function(e){
        var index  = $(this).attr("id").split('_');
        index = index[index.length -1];
        DK_autoFillAccessory(index);
        DK_calcOption(index,true);
    });

    //************************************************ END THIENPB ************************************************ */

    // .:nhantv:. Add a checkbox to Itemise in LINE ITEMS
    $('input[name="dk_quote_option"]').on('change', function() {
        // Set change
        let dataAttr = $(this).attr('data-attr');
        let propVal = $(this).prop('checked');
        if(propVal){
            $('input[name="dk_quote_option"]').each(function(){
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
        generateJSONForInput();});
    });
//***************************************** THIENPB FUNCTION *********************************************************** */
async function init_table_daikin() {
    // Call API get Offgrid Product
    try{
        // DK_getExtraProduct();

        await $.ajax({
            url: '/index.php?entryPoint=APIGetDaikinProduct'
        }).then(function(result) {
            let dataJSON = JSON.parse(result);
            // Set global var
            dk_main = dataJSON.dk_main;
            dk_extra = dataJSON.dk_extra;
        });
    } catch (ex) {
        console.log(ex);
    }

    let daikin_pricing_table   = $('<div id="daikin_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row" style="margin-bottom: 20px;"></div>');
    let data = [
        ["Selected Option"
            ,"<input data-attr='1' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_1' style='margin-bottom:5px'> Option 1"
            ,"<input data-attr='2' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_2' style='margin-bottom:5px'> Option 2"
            ,"<input data-attr='3' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_3' style='margin-bottom:5px'> Option 3"
            ,"<input data-attr='4' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_4' style='margin-bottom:5px'> Option 4"
            ,"<input data-attr='5' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_5' style='margin-bottom:5px'> Option 5"
            ,"<input data-attr='6' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_6' style='margin-bottom:5px'> Option 6"],
        [""
            , "<button data-option ='1' id='clear_dk_option_1' class='button default'>Clear Option 1</button>"
            , "<button data-option ='2' id='clear_dk_option_2' class='button default'>Clear Option 2</button>"
            , "<button data-option ='3' id='clear_dk_option_3' class='button default'>Clear Option 3</button>"
            , "<button data-option ='4' id='clear_dk_option_4' class='button default'>Clear Option 4</button>"
            , "<button data-option ='5' id='clear_dk_option_5' class='button default'>Clear Option 5</button>"
            , "<button data-option ='6' id='clear_dk_option_6' class='button default'>Clear Option 6</button>"],
        ["PM:"
            , makeInputBox("dk_pm1 daikin_pricing", "dk_pm1", false)
            , makeInputBox("dk_pm2 daikin_pricing", "dk_pm2", false)
            , makeInputBox("dk_pm3 daikin_pricing", "dk_pm3", false)
            , makeInputBox("dk_pm4 daikin_pricing", "dk_pm4", false)
            , makeInputBox("dk_pm5 daikin_pricing", "dk_pm5", false)
            , makeInputBox("dk_pm6 daikin_pricing", "dk_pm6", false)],
        ["Daikin Type 1"
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_1 daikin_pricing", "main_dk_type1_1") 
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_2 daikin_pricing", "main_dk_type1_2")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_3 daikin_pricing", "main_dk_type1_3")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_4 daikin_pricing", "main_dk_type1_4")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_5 daikin_pricing", "main_dk_type1_5")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_6 daikin_pricing", "main_dk_type1_6")],
        ["Number Daikin 1"
            , makeInputBox("total_dk_type_1 daikin_pricing", "total_dk_type1_1", false)
            , makeInputBox("total_dk_type_2 daikin_pricing", "total_dk_type1_2", false)
            , makeInputBox("total_dk_type_3 daikin_pricing", "total_dk_type1_3", false)
            , makeInputBox("total_dk_type_4 daikin_pricing", "total_dk_type1_4", false)
            , makeInputBox("total_dk_type_5 daikin_pricing", "total_dk_type1_5", false)
            , makeInputBox("total_dk_type_6 daikin_pricing", "total_dk_type1_6", false)],
        ["<button id='main_add' class='button default'>+</button>"
            , "<input type='hidden' class='daikin_pricing' name='main_line' id='main_line' value='1' />"],
        ["Daikin Wifi"
            , makeInputBox("total_dk_wifi_1 daikin_pricing", "total_dk_wifi_1", false)
            , makeInputBox("total_dk_wifi_2 daikin_pricing", "total_dk_wifi_2", false)
            , makeInputBox("total_dk_wifi_3 daikin_pricing", "total_dk_wifi_3", false)
            , makeInputBox("total_dk_wifi_4 daikin_pricing", "total_dk_wifi_4", false)
            , makeInputBox("total_dk_wifi_5 daikin_pricing", "total_dk_wifi_5", false)
            , makeInputBox("total_dk_wifi_6 daikin_pricing", "total_dk_wifi_6", false)],
        ["Daikin Install"
            , makeSelectBox(['Yes', 'No'], "install_dk_1 daikin_pricing", "install_dk_1")
            , makeSelectBox(['Yes', 'No'], "install_dk_2 daikin_pricing", "install_dk_2")
            , makeSelectBox(['Yes', 'No'], "install_dk_3 daikin_pricing", "install_dk_3")
            , makeSelectBox(['Yes', 'No'], "install_dk_4 daikin_pricing", "install_dk_4")
            , makeSelectBox(['Yes', 'No'], "install_dk_5 daikin_pricing", "install_dk_5")
            , makeSelectBox(['Yes', 'No'], "install_dk_6 daikin_pricing", "install_dk_6")],

        ["", "&nbsp;"],
        // ["<button id='calculate_dk' class='button default'>Max</button>", "&nbsp;"],
        ["", "&nbsp;"],
        ["Extra 1"
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_1 daikin_pricing", "extra_dk_type1_1")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_2 daikin_pricing", "extra_dk_type1_2")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_3 daikin_pricing", "extra_dk_type1_3")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_4 daikin_pricing", "extra_dk_type1_4")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_5 daikin_pricing", "extra_dk_type1_5")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_6 daikin_pricing", "extra_dk_type1_6")],
        ["Extra (number/value) 1"
            , makeTwoInputBox("expand_ext extra_dk_type_1 daikin_pricing", "ext_no1_1", "ext_val1_1" ,false)
            , makeTwoInputBox("expand_ext extra_dk_type_2 daikin_pricing", "ext_no1_2", "ext_val1_2", false)
            , makeTwoInputBox("expand_ext extra_dk_type_3 daikin_pricing", "ext_no1_3", "ext_val1_3", false)
            , makeTwoInputBox("expand_ext extra_dk_type_4 daikin_pricing", "ext_no1_4", "ext_val1_4", false)
            , makeTwoInputBox("expand_ext extra_dk_type_5 daikin_pricing", "ext_no1_5", "ext_val1_5", false)
            , makeTwoInputBox("expand_ext extra_dk_type_6 daikin_pricing", "ext_no1_6", "ext_val1_6", false)],
        ["<button id='extra_add' class='button default'>+</button>"
            , "<input type='hidden' class='daikin_pricing' name='extra_line' id='extra_line' value='1' />"],
        ["Grand total:"
            , makeInputBox("daikin_pricing", "total_dk_1", true)
            , makeInputBox("daikin_pricing", "total_dk_2", true)
            , makeInputBox("daikin_pricing", "total_dk_3", true)
            , makeInputBox("daikin_pricing", "total_dk_4", true)
            , makeInputBox("daikin_pricing", "total_dk_5", true)
            , makeInputBox("daikin_pricing", "total_dk_6", true)],
        ["PE Admin (%)", "<input type='number' class='daikin_pricing' name='dk_pe_admin_percent' id='dk_pe_admin_percent' value='20' />"],
    ];
    
    //  Update to set order before "Save and Generate Quote" field
    $('body').find("#generate_quote").before(daikin_pricing_table);

    makeTable(daikin_pricing_table, data, "daikin_pricing", "daikin_pricing");
    //css Table
    $(".daikin_pricing td").css({"padding":"0px 5px"});
    $(".daikin_pricing th").css({"padding":"0px 5px"});
    $(".daikin_pricing th:first-child").css({"width":"160px"});
    $(".daikin_pricing select, .daikin_pricing input[class*='daikin_pricing']:not([type='checkbox'])").css({"width":"100%"});
    $(".expand_ext").css({"width":"48%"});

    // Load Solar Option
    DK_loadOption();
}

//Convert JSON to Array data
function DK_convertJSONToArrayInit(jsonData){
    let result = [''];
    if (typeof(jsonData) != 'undefined') {
        jsonData.forEach(element => {
            result.push(element.name);
        });
    }
    return result;
}

function makeTwoInputBox(iclass,iid, iid1, disabled = false){
    var read = disabled == false ? '' : 'disabled'
    var input = `   <input class="${iclass}" id="${iid}" ${read} style="width: 48%;" />
                    <input class="${iclass}" id="${iid1}" ${read} style="width: 48%;" />
                `;
    // var input = $("<input/>").addClass(iclass).attr("id",iid).prop('disabled', disabled).css('width', '50%');
    // var input1 = $("<input/>").addClass(iclass).attr("id",iid1).prop('disabled', disabled).css('width', '50%');
    return input;
}

//Create new line
function DK_createNewLine(target = 'extra'){
    var label, id, list, label1, label2, id1, id2;
    if (target == 'main') {
        label = "Daikin Type ";
        id = "main_dk_type";
        list = dk_main;
        label1 = "Number Daikin ";
        id1 = "total_dk_type";
        // list1 = dk_main;
    } else {
        label = "Extra ";
        id = "extra_dk_type";
        list = dk_extra;
        label1 = "Extra (number/value) ";
        id1 = "ext_no";
        id2 = "ext_val";
        // list1 = dk_main;
    }

    

    let next_index = getCountLine(target) + 1;
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
            let select = makeSelectBox(DK_convertJSONToArrayInit(list), `${id}_${next_index} daikin_pricing`, id + next_index + "_" + i);
            select.css({"width":"100%"});
            if (target == 'main') {
                input = makeInputBox(`${id1}_${next_index} daikin_pricing`, `${id1}${next_index}_${i}`, false);
                input.css({"width":"100%"});
            } else {
                input = makeTwoInputBox(`${id}_${next_index} daikin_pricing`, `${id1}${next_index}_${i}`, `${id2}${next_index}_1`, false);
                // input.css({"width":"100%"});
            }
            $(td).html(select);
            $(td1).html(input);
        }
        new_tr.appendChild(td);
        new_tr1.appendChild(td1);
    }
    $('#'+ target +'_add').closest('tr').before(new_tr, new_tr1);
    $('#'+ target +'_line').val(next_index);
}



async function DK_calcOption(index, isTotalPanel = false) {
    var postcode = $("#install_address_postalcode_c").val();
    if(index != '' && index != undefined){
        let currState = DK_getCurrentOptionState(index);
        if(currState.panel_type != '' && currState.inverter_type != ''){
            // Get max panels and total kw
            let maxPnAndTotalKw = DK_getMaxPanelAndTotalKw(currState, isTotalPanel);
            // Set value
            $("#total_dk_type_"+index).val(maxPnAndTotalKw['max']);
            currState.total_panels = maxPnAndTotalKw['max'];
            $('#total_dk_kW_'+index).val(maxPnAndTotalKw['kw']);
            currState.total_kw = maxPnAndTotalKw['kw'];
            $('#total_inverter_dk_kW_'+index).val(maxPnAndTotalKw['inverter_kw']);
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
                        $("#number_dk_stcs_"+index).val(result['NumberOfSTCs']);
                        currState.number_stcs = result['NumberOfSTCs'];
                    }
                });
            } catch (err) {
                console.log(err);
            } finally {
                // Hide loading
                setTimeout(function (){
                    SUGAR.ajaxUI.hideLoadingPanel();
                }, 300);
            }
            // Save current option
            // Grand Total
            let grandTotal = DK_calcGrandTotal(currState);
            $("#total_dk_"+index).val(parseFloat(roundTo90(grandTotal)).formatMoney(2, ',', '.'));
            DK_saveCurrentState();

        }
    }
}

//Get current option state
function DK_getCurrentOptionState(index){
    let result = {};
    result['total_kw'] = $('#total_dk_kW_' + index).val();
    result['total_inverter'] = $('#total_inverter_dk_kW_' + index).val();
    result['panel_type'] = $('#main_dk_type_' + index).val();
    result['inverter_type'] = $('#extra_dk_type_' + index).val();
    result['total_panels'] = $('#total_dk_type_' + index).val();
    result['number_stcs'] = $('#number_dk_stcs_' + index).val();
    result['solar_inverter'] = $('#extra_dk_type_' + index).val();
    result['accessory1'] = $('#dk_accessory1_' + index).val();
    result['accessory2'] = $('#dk_accessory2_' + index).val();
    result['pm'] = ($("#dk_pm"+index).val() != '') ? parseFloat($("#dk_pm"+index).val()) : 0;
    return result;
}
//Load solar option
function DK_loadOption(){
    if($("#own_solar_pv_pricing_c").val() != ""){
        try{
            var json_val = JSON.parse($("#own_solar_pv_pricing_c").val());
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
//
function DK_clearOption(option){
    $("#daikin_option_"+(option)).prop('checked', false);
    $('#daikin_pricing_table td:nth-child('+ (option + 1) +')').find('input').val('');
    $('#daikin_pricing_table td:nth-child('+ (option + 1) +')').find('select').prop("selectedIndex", 0);
}

function DK_calcEquipmentCost(currState){
    let cost = 0;
    if(currState.panel_type != ''){
        cost += parseFloat(getAttributeFromName(currState.panel_type, dk_main, "cost")) * parseFloat(currState.total_panels);
    }
    if(currState.inverter_type != ''){
        cost += parseFloat(getAttributeFromName(currState.inverter_type, dk_extra, "cost"));
    }
    // Microgrid Balance Of System
    //cost += parseFloat(getAttributeFromName(extra_products[3], og_extra, "cost")) * parseFloat(currState.total_kw) * 1000;
    return cost;
}

function DK_getMaxPanelAndTotalKw(currState, isTotalPanel){
    //const ratio = 1.333;
    const panel_kw = parseFloat(getAttributeFromName(currState.panel_type, dk_main, "capacity")) / 1000;
    const inverter_kw = parseFloat(getAttributeFromName(currState.inverter_type, dk_extra, "capacity"));
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

function DK_calcGrandTotal(currState){
    let grandTotal = 0;
    // Equipment cost
    grandTotal += DK_calcEquipmentCost(currState);35156.999999
    // Solar Standard Install
    grandTotal += parseFloat(getAttributeFromName(extra_solar_products[0], solar_extra, "cost")) * parseFloat(currState.total_kw);
    // PV Balance of System
    grandTotal += parseFloat(getAttributeFromName(extra_solar_products[1], solar_extra, "cost")) * parseFloat(currState.total_kw) * 1000;
    // Extra 1
    grandTotal += (currState.accessory1 != '') ? parseFloat(getAttributeFromName(currState.accessory1, sol_accessory , "cost")) : 0;
    // Extra 2
    grandTotal += (currState.accessory2 != '') ? parseFloat(getAttributeFromName(currState.accessory2, sol_accessory , "cost")) : 0;
    // PE Admin %
    grandTotal += grandTotal * (parseFloat($('#dk_pe_admin_percent').val()) / 100);
    // GST 10%
    let gst = grandTotal * 0.1;
    // STCs
    grandTotal += parseFloat(getAttributeFromName(extra_solar_products[2], solar_extra, "cost")) * parseFloat(currState.number_stcs);
    // Include GST above
    grandTotal += gst;

     // PM
     grandTotal += currState.pm;

    return grandTotal;
}

function DK_saveCurrentState(){
    var values = {};
    $("#daikin_pricing_table .daikin_pricing").each(function (){
        var id_name = $(this).attr("id");
        if($("#"+id_name).attr('type')== 'checkbox'){
            values[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
        } else {
            values[id_name] = $(this).val();
        }
    });

    $("#own_solar_pv_pricing_c").val(JSON.stringify(values));
}

function DK_autoFillAccessory(index){
    if($("#extra_dk_type_"+index).val().toLowerCase().indexOf('primo ') >= 0 ){
        if($("#phases").val() == 'Single Phase'){
            $("#dk_accessory1_"+index).val('Fro. Smart Meter (1P)'); 
        }else if($("#phases").val() == 'Three Phases'){
            $("#dk_accessory1_"+index).val('Fro. Smart Meter (3P)');
        }else{
            
        }
        $("#dk_accessory2_"+index).val('');
    }else if( $("#extra_dk_type_"+index).val().toLowerCase().indexOf('symo ') >= 0){
        $("#dk_accessory1_"+index).val('Fro. Smart Meter (3P)');
        $("#dk_accessory2_"+index).val('');
    }else if($("#extra_dk_type_"+index).val().toLowerCase().indexOf('s edge ') >= 0){
        $("#dk_accessory1_"+index).val('SE Wifi');
        $("#dk_accessory2_"+index).val('SE Smart Meter');
    }else if($("#extra_dk_type_"+index).val().toLowerCase().indexOf('sungrow ') >= 0){
        if($("#phases").val() == 'Three Phases'  ){
            $("#dk_accessory1_"+index).val('Sungrow Smart Meter (3P)');//'Sungrow Smart Meter (3P)');
            $("#dk_accessory2_"+index).val('');
        }else if($("#phases").val() == 'Two Phases'){

        }else {
            if( $("#extra_dk_type_"+index).val().indexOf('3P') >= 0){
                $("#dk_accessory1_"+index).val('Sungrow Smart Meter (3P)');//'Sungrow Smart Meter (3P)');
                $("#dk_accessory2_"+index).val('');
            }else {
                $("#dk_accessory1_"+index).val('Sungrow Smart Meter (1P)');
                $("#dk_accessory2_"+index).val('');
            }   
        }
    }else{
        $("#dk_accessory1_"+index).val('');
        $("#dk_accessory2_"+index).val(''); 
    }
}

function DK_getExtraProduct(){
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

// Solar Checkbox handle 
$(document).on('change', 'input[id*="daikin_option"]', function(){
    checkBoxOptionHandle($(this), "daikin_option");
});


function setInputFilter(textbox, inputFilter) {
    ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
      textbox.addEventListener(event, function() {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          this.value = "";
        }
      });
    });
  }
//***************************************** END THIENPB FUNCTION *********************************************************** */
