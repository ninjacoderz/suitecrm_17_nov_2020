// var sol_accessory,solar_extra = [];
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
    /** Clear sl button */
    $(document).on('click', '*[id*="clear_dk_option"]', function(e){
        e.preventDefault();
        DK_clearOption($(this).data('option'));
    });
   
    /** Clear sl button */
    $(document).on('click', '#calculate_dk', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
            var panel_type = $("#panel_dk_type_"+i).val();
            var inverter_type = $("#inverter_dk_type_"+i).val();
            // Get suggested
            if(panel_type != '' && inverter_type != ""){
                // Calculate option
                DK_autoFillAccessory(i);
                DK_calcOption(i);
            }
        }
    });

    $(document).on("change", "select[id*=inverter_dk_type]", function(e){
        var index  = $(this).attr("id").split('_');
        index = index[index.length -1];
        DK_autoFillAccessory(index);
        DK_calcOption(index);
    });

    $(document).on("change", "select[id*=inverter_dk_type] ,select[id*=panel_dk_type_]", function(e){
        var index  = $(this).attr("id").split('_');
        index = index[index.length -1];
        if($("#panel_dk_type_"+index).val() != "" && $("#inverter_dk_type_"+index).val() != ""){
            DK_autoFillAccessory(index);
            DK_calcOption(index);
        }
    });

    $(document).on("change", "input[id*=total_dk_panels_]", function(e){
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
        ["Module Capacity kW:"
            , makeInputBox("total_dk_kW_1 daikin_pricing", "total_dk_kW_1", true)
            , makeInputBox("total_dk_kW_2 daikin_pricing", "total_dk_kW_2", true)
            , makeInputBox("total_dk_kW_3 daikin_pricing", "total_dk_kW_3", true)
            , makeInputBox("total_dk_kW_4 daikin_pricing", "total_dk_kW_4", true)
            , makeInputBox("total_dk_kW_5 daikin_pricing", "total_dk_kW_5", true)
            , makeInputBox("total_dk_kW_6 daikin_pricing", "total_dk_kW_6", true)],
        ["Inverter Capacity kW:"
            , makeInputBox("total_inverter_dk_kW_1 daikin_pricing", "total_inverter_dk_kW_1", true)
            , makeInputBox("total_inverter_dk_kW_2 daikin_pricing", "total_inverter_dk_kW_2", true)
            , makeInputBox("total_inverter_dk_kW_3 daikin_pricing", "total_inverter_dk_kW_3", true)
            , makeInputBox("total_inverter_dk_kW_4 daikin_pricing", "total_inverter_dk_kW_4", true)
            , makeInputBox("total_inverter_dk_kW_5 daikin_pricing", "total_inverter_dk_kW_5", true)
            , makeInputBox("total_inverter_dk_kW_6 daikin_pricing", "total_inverter_dk_kW_6", true)],
        ["Panel Type"
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "panel_dk_type_1 daikin_pricing", "panel_dk_type_1")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "panel_dk_type_2 daikin_pricing", "panel_dk_type_2")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "panel_dk_type_3 daikin_pricing", "panel_dk_type_3")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "panel_dk_type_4 daikin_pricing", "panel_dk_type_4")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "panel_dk_type_5 daikin_pricing", "panel_dk_type_5")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "panel_dk_type_6 daikin_pricing", "panel_dk_type_6")],
        ["Inverter Type"
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "inverter_dk_type_1 daikin_pricing", "inverter_dk_type_1")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "inverter_dk_type_2 daikin_pricing", "inverter_dk_type_2")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "inverter_dk_type_3 daikin_pricing", "inverter_dk_type_3")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "inverter_dk_type_4 daikin_pricing", "inverter_dk_type_4")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "inverter_dk_type_5 daikin_pricing", "inverter_dk_type_5")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "inverter_dk_type_6 daikin_pricing", "inverter_dk_type_6")],
        ["Total Panels"
            , makeInputBox("total_dk_panels_1 daikin_pricing", "total_dk_panels_1", false)
            , makeInputBox("total_dk_panels_2 daikin_pricing", "total_dk_panels_2", false)
            , makeInputBox("total_dk_panels_3 daikin_pricing", "total_dk_panels_3", false)
            , makeInputBox("total_dk_panels_4 daikin_pricing", "total_dk_panels_4", false)
            , makeInputBox("total_dk_panels_5 daikin_pricing", "total_dk_panels_5", false)
            , makeInputBox("total_dk_panels_6 daikin_pricing", "total_dk_panels_6", false)],
        ["Number of STCs"
            , makeInputBox("number_dk_stcs_1 daikin_pricing", "number_dk_stcs_1", true)
            , makeInputBox("number_dk_stcs_2 daikin_pricing", "number_dk_stcs_2", true)
            , makeInputBox("number_dk_stcs_3 daikin_pricing", "number_dk_stcs_3", true)
            , makeInputBox("number_dk_stcs_4 daikin_pricing", "number_dk_stcs_4", true)
            , makeInputBox("number_dk_stcs_5 daikin_pricing", "number_dk_stcs_5", true)
            , makeInputBox("number_dk_stcs_6 daikin_pricing", "number_dk_stcs_6", true)],
        ["", "&nbsp;"],
        ["<button id='calculate_dk' class='button default'>Max</button>", "&nbsp;"],
        ["", "&nbsp;"],
        ["Solar Accessory 1"
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory1_1 daikin_pricing", "dk_accessory1_1")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory1_2 daikin_pricing", "dk_accessory1_2")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory1_3 daikin_pricing", "dk_accessory1_3")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory1_4 daikin_pricing", "dk_accessory1_4")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory1_5 daikin_pricing", "dk_accessory1_5")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory1_6 daikin_pricing", "dk_accessory1_6")],
        ["Solar Accessory 2"
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory2_1 daikin_pricing", "dk_accessory2_1")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory2_2 daikin_pricing", "dk_accessory2_2")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory2_3 daikin_pricing", "dk_accessory2_3")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory2_4 daikin_pricing", "dk_accessory2_4")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory2_5 daikin_pricing", "dk_accessory2_5")
            , makeSelectBox(DK_convertJSONToArrayInit(sol_accessory), "dk_accessory2_6 daikin_pricing", "dk_accessory2_6")],
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



async function DK_calcOption(index, isTotalPanel = false) {
    var postcode = $("#install_address_postalcode_c").val();
    if(index != '' && index != undefined){
        let currState = DK_getCurrentOptionState(index);
        if(currState.panel_type != '' && currState.inverter_type != ''){
            // Get max panels and total kw
            let maxPnAndTotalKw = DK_getMaxPanelAndTotalKw(currState, isTotalPanel);
            // Set value
            $("#total_dk_panels_"+index).val(maxPnAndTotalKw['max']);
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
    result['panel_type'] = $('#panel_dk_type_' + index).val();
    result['inverter_type'] = $('#inverter_dk_type_' + index).val();
    result['total_panels'] = $('#total_dk_panels_' + index).val();
    result['number_stcs'] = $('#number_dk_stcs_' + index).val();
    result['solar_inverter'] = $('#inverter_dk_type_' + index).val();
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
    if($("#inverter_dk_type_"+index).val().toLowerCase().indexOf('primo ') >= 0 ){
        if($("#phases").val() == 'Single Phase'){
            $("#dk_accessory1_"+index).val('Fro. Smart Meter (1P)'); 
        }else if($("#phases").val() == 'Three Phases'){
            $("#dk_accessory1_"+index).val('Fro. Smart Meter (3P)');
        }else{
            
        }
        $("#dk_accessory2_"+index).val('');
    }else if( $("#inverter_dk_type_"+index).val().toLowerCase().indexOf('symo ') >= 0){
        $("#dk_accessory1_"+index).val('Fro. Smart Meter (3P)');
        $("#dk_accessory2_"+index).val('');
    }else if($("#inverter_dk_type_"+index).val().toLowerCase().indexOf('s edge ') >= 0){
        $("#dk_accessory1_"+index).val('SE Wifi');
        $("#dk_accessory2_"+index).val('SE Smart Meter');
    }else if($("#inverter_dk_type_"+index).val().toLowerCase().indexOf('sungrow ') >= 0){
        if($("#phases").val() == 'Three Phases'  ){
            $("#dk_accessory1_"+index).val('Sungrow Smart Meter (3P)');//'Sungrow Smart Meter (3P)');
            $("#dk_accessory2_"+index).val('');
        }else if($("#phases").val() == 'Two Phases'){

        }else {
            if( $("#inverter_dk_type_"+index).val().indexOf('3P') >= 0){
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
