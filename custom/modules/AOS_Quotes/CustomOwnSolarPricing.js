var sol_accessory,solar_extra = [];
const extra_solar_products = ["Solar PV Standard Install", "Solar PV Balance Of System", "STCs","Solar PV Supply and Install"];

$(function () {
    'use strict';
    //INIT
    $(document).find('#own_solar_pv_pricing_c').attr('readonly', 'readonly');
    var quote_type = $("#quote_type_c").val();
    if(quote_type == 'quote_type_solar'){
        init_table_solar();
        $(document).find("#generate_quote").after('<br/><button type="button" class="button primary" id="send_pe_solar_pricing" class="button send_solar_pricing" onclick="$(document).openComposeViewModal_SendPESolarPricing(this);" data-email-type="send_pe_solar_pricing"  data-module="AOS_Quotes" data-module-name="'+$("#name").val()+'" data-record-id="'+$("input[name='record']").val()+'">SEND PE SOLAR PRICING OPTIONS</button></button>')
    }

    //************************************************ THIENPB ************************************************ */
    /** Clear sl button */
    $(document).on('click', '*[id*="clear_sl_option"]', function(e){
        e.preventDefault();
        SL_clearOption($(this).data('option'));
    }); 
   
    /** Clear sl button */
    $(document).on('click', '#calculate_sl', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
            var panel_type = $("#panel_sl_type_"+i).val();
            // Get suggested
            if(panel_type != '' && SL_isInverterHasValue(i)){
                // Calculate option
                SL_autoFillAccessory(i);
                SL_calcOption(2.5,i);
            }
        }
    });

    $(document).on("change", "select[id*=inverter_sl_type] ,select[id*=panel_sl_type_], select[id*=sl_accessory]", function(e){
        var index  = $(this).attr("id").split('_');
        index = index[index.length -1];
        if($("#panel_sl_type_"+index).val() != "" && SL_isInverterHasValue(index)){
            SL_autoFillAccessory(index);
            SL_calcOption(2.5,index);
        }
    });

    $(document).on("change", "input[id*=total_sl_panels_]", function(e){
        var index  = $(this).attr("id").split('_');
        index = index[index.length -1];
        SL_autoFillAccessory(index);
        SL_calcOption(2.5,index,true);
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

    $.fn.openComposeViewModal_SendPESolarPricing = function (source) {
        "use strict";
        var module_name = $(source).attr('data-module');
        var record_id = $(source).attr('data-record-id');
        var emailType = $(source).data('email-type');
        if (record_id == '') {
            alert('Please Save before !');
            return;
        }

        /**Save before*/
        $('#save_and_edit').trigger('click');

        var self = this;

        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({ "showHeader": false, "showFooter": false, "size": 'lg' });
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id = $(source).attr('data-record-id');
        var email_type = $(source).attr('data-email-type');
        var email_module = $(source).attr('data-module');
        var address = $('#install_address_c').val() + ' ' + $('#install_address_city_c').val() + ' ' + $('#install_address_state_c').val() + ' ' + $('#install_address_postalcode_c').val();
        if ($('#vic_rebate_c').prop('checked') == true) {
            var vic_rebate = "Yes";
        } else {
            var vic_rebate = "No";
        }
        if ($('#vic_loan_c').prop('checked') == true) {
            var vic_loan = "Yes";
        } else {
            var vic_loan = "No";
        }
        var storey = $('#storeys').val();
        var url_email = 'index.php?module=Emails&action=ComposeView&address=' + address + '&storey=' + storey + '&vic_rebate=' + vic_rebate + '&vic_loan=' + vic_loan + '&in_popup=1' + ((record_id != "") ? ("&record_id=" + record_id) : "") + ((email_type != "") ? ("&email_type=" + email_type) : "") + ((email_module != "") ? ("&email_module=" + email_module) : "");

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

            //$(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
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
                    var mb = messageBox({ size: 'lg' });
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
    //************************************************ END THIENPB ************************************************ */

    // .:nhantv:. Add a checkbox to Itemise in LINE ITEMS
    $('input[name="sl_quote_option"]').on('change', function() {
        // Set change
        let dataAttr = $(this).attr('data-attr');
        let propVal = $(this).prop('checked');
        if(propVal){
            $('input[name="sl_quote_option"]').each(function(){
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
        ["Selected Option"
            ,"<input data-attr='1' type='checkbox' class='solar_option solar_pricing' name='solar_option' id='solar_option_1' style='margin-bottom:5px'> Option 1"
            ,"<input data-attr='2' type='checkbox' class='solar_option solar_pricing' name='solar_option' id='solar_option_2' style='margin-bottom:5px'> Option 2"
            ,"<input data-attr='3' type='checkbox' class='solar_option solar_pricing' name='solar_option' id='solar_option_3' style='margin-bottom:5px'> Option 3"
            ,"<input data-attr='4' type='checkbox' class='solar_option solar_pricing' name='solar_option' id='solar_option_4' style='margin-bottom:5px'> Option 4"
            ,"<input data-attr='5' type='checkbox' class='solar_option solar_pricing' name='solar_option' id='solar_option_5' style='margin-bottom:5px'> Option 5"
            ,"<input data-attr='6' type='checkbox' class='solar_option solar_pricing' name='solar_option' id='solar_option_6' style='margin-bottom:5px'> Option 6"],
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
        ["Module Capacity kW:"
            , makeInputBox("total_sl_kW_1 solar_pricing", "total_sl_kW_1", true)
            , makeInputBox("total_sl_kW_2 solar_pricing", "total_sl_kW_2", true)
            , makeInputBox("total_sl_kW_3 solar_pricing", "total_sl_kW_3", true)
            , makeInputBox("total_sl_kW_4 solar_pricing", "total_sl_kW_4", true)
            , makeInputBox("total_sl_kW_5 solar_pricing", "total_sl_kW_5", true)
            , makeInputBox("total_sl_kW_6 solar_pricing", "total_sl_kW_6", true)],
        ["Inverter Capacity kW:"
            , makeInputBox("total_inverter_sl_kW_1 solar_pricing", "total_inverter_sl_kW_1", true)
            , makeInputBox("total_inverter_sl_kW_2 solar_pricing", "total_inverter_sl_kW_2", true)
            , makeInputBox("total_inverter_sl_kW_3 solar_pricing", "total_inverter_sl_kW_3", true)
            , makeInputBox("total_inverter_sl_kW_4 solar_pricing", "total_inverter_sl_kW_4", true)
            , makeInputBox("total_inverter_sl_kW_5 solar_pricing", "total_inverter_sl_kW_5", true)
            , makeInputBox("total_inverter_sl_kW_6 solar_pricing", "total_inverter_sl_kW_6", true)],
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
        ["Number of STCs"
            , makeInputBox("number_sl_stcs_1 solar_pricing", "number_sl_stcs_1", true)
            , makeInputBox("number_sl_stcs_2 solar_pricing", "number_sl_stcs_2", true)
            , makeInputBox("number_sl_stcs_3 solar_pricing", "number_sl_stcs_3", true)
            , makeInputBox("number_sl_stcs_4 solar_pricing", "number_sl_stcs_4", true)
            , makeInputBox("number_sl_stcs_5 solar_pricing", "number_sl_stcs_5", true)
            , makeInputBox("number_sl_stcs_6 solar_pricing", "number_sl_stcs_6", true)],
        ["", "&nbsp;"],
        ["<button id='calculate_sl' class='button default'>Max 2.5</button>", "&nbsp;", "<input type='hidden' class='solar_pricing' name='sl_max_ratio' id='sl_max_ratio' value='2,5' />"],
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
        ["Grand total:"
            , makeInputBox("solar_pricing", "total_sl_1", true)
            , makeInputBox("solar_pricing", "total_sl_2", true)
            , makeInputBox("solar_pricing", "total_sl_3", true)
            , makeInputBox("solar_pricing", "total_sl_4", true)
            , makeInputBox("solar_pricing", "total_sl_5", true)
            , makeInputBox("solar_pricing", "total_sl_6", true)],
        ["PE Admin (%)", "<input type='number' class='solar_pricing' name='sl_pe_admin_percent' id='sl_pe_admin_percent' value='20' />"],
    ];
    
    //  Update to set order before "Save and Generate Quote" field
    $('body').find("#generate_quote").before(solar_pricing_table);

    makeTable(solar_pricing_table, data, "solar_pricing", "solar_pricing");
    //css Table
    $(".solar_pricing td").css({"padding":"0px 5px"});
    $(".solar_pricing th").css({"padding":"0px 5px"});
    $(".solar_pricing th:first-child").css({"width":"160px"});
    $(".solar_pricing select, .solar_pricing input[class*='solar_pricing']:not([type='checkbox'])").css({"width":"100%"});

    // Load Solar Option
    SL_loadOption();
}

async function SL_calcOption(ratio,index, isTotalPanel = false,isloading = true) {
    var postcode = $("#install_address_postalcode_c").val();
    if(index != '' && index != undefined){
        let currState = SL_getCurrentOptionState(index);
        if(currState.panel_type != '' && SL_isInverterHasValue(index)){
            // Get max panels and total kw
            let maxPnAndTotalKw = SL_getMaxPanelAndTotalKw(currState, isTotalPanel,ratio);
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
            let grandTotal = SL_calcGrandTotal(currState);
            $("#total_sl_"+index).val(parseFloat(roundTo90(grandTotal)).formatMoney(2, ',', '.'));
            SL_saveCurrentState();

        }
    }
}

//Get current option state
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
//Load solar option
function SL_loadOption(){
    if($("#own_solar_pv_pricing_c").val() != ""){
        try{
            var json_val = JSON.parse($("#own_solar_pv_pricing_c").val());
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
//
function SL_clearOption(option){
    $("#solar_option_"+(option)).prop('checked', false);
    $('#solar_pricing_table td:nth-child('+ (option + 1) +') input:not(input[id="sl_pe_admin_percent"],input[id="sl_inverter_line"],input[id="sl_accessory_line"])').val('');
    $('#solar_pricing_table td:nth-child('+ (option + 1) +')').find('select').prop("selectedIndex", 0);
}

function SL_calcEquipmentCost(currState){
    let cost = 0;
    if(currState.panel_type != ''){
        cost += parseFloat(getAttributeFromName(currState.panel_type, sol_panel, "cost")) * parseFloat(currState.total_panels);
    }
    // Inverter cost
    let num_of_line = SL_getCountLine('sl_inverter');
    for (var i = 0; i < num_of_line; i++) {
        if(currState['inverter_type' + (i + 1)] != ''){
            cost += parseFloat(getAttributeFromName(currState['inverter_type' + (i + 1)], sol_inverter, "cost"));
        }
    }
    // Accessory cost
    num_of_line = SL_getCountLine('sl_accessory');
    for (var i = 0; i < num_of_line; i++) {
        if(currState['accessory' + (i + 1)] != ''){
            cost += parseFloat(getAttributeFromName(currState['accessory' + (i + 1)], sol_accessory, "cost"));
        }
    }
    // PV Balance of System
    cost += parseFloat(getAttributeFromName(extra_solar_products[1], solar_extra, "cost")) * parseFloat(currState.total_kw) * 1000;
    return cost;
}

function SL_getMaxPanelAndTotalKw(currState, isTotalPanel , ratio){
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
    let maxPanel;
    if(ratio == 0.75){
         maxPanel = Math.floor((inverter_kw / 0.75) / panel_kw);
    }else{
         maxPanel = Math.floor((inverter_kw * ratio) / panel_kw);
    }
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

function SL_calcGrandTotal(currState){
    let grandTotal = 0;
    // Equipment cost
    grandTotal += SL_calcEquipmentCost(currState);
    // Solar Standard Install
    grandTotal += parseFloat(getAttributeFromName(extra_solar_products[0], solar_extra, "cost")) * parseFloat(currState.total_kw);
    // PE Admin %
    grandTotal += grandTotal * (parseFloat($('#sl_pe_admin_percent').val()) / 100);
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

function SL_saveCurrentState(){
    var values = {};
    $("#solar_pricing_table .solar_pricing").each(function (){
        var id_name = $(this).attr("id");
        if($("#"+id_name).attr('type')== 'checkbox'){
            values[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
        } else {
            values[id_name] = $(this).val();
        }
    });

    $("#own_solar_pv_pricing_c").val(JSON.stringify(values));
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

// Solar Checkbox handle 
$(document).on('change', 'input[id*="solar_option"]', function(){
    checkBoxOptionHandle($(this), "solar_option");
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

function getOwnSolarPricing(data, string='') {
    return;
    let json_data;
    productSolar = JSON.parse(localStorage.productSolar);

    if (string == '') {
        json_data = $.parseJSON(data, true);
        loadOwnOptionsPricing();
    } else {
        json_data = getInputTypes(data);
        for (let i=1; i<7; i++) {
            json_data[`own_basePrice_${i}`] = calc_own_basePrice(productSolar, json_data, i);
        }
        $('#own_solar_pv_pricing_c').val(JSON.stringify(json_data));
        loadOwnOptionsPricing();
    }
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

function SL_isInverterHasValue(index){
    let num_of_line = SL_getCountLine('sl_inverter');
    for (let i = 0; i < num_of_line; i++) {
        if ($("#inverter_sl_type" + (i + 1) + "_" + index).val() != '') {
            return true;
        }
    }
    return false;
}

//***************************************** END THIENPB FUNCTION *********************************************************** */

    // //test
    // $('#detailpanel_11').append('<button id="testVUT">CLICK</button>');
    // $(document).on('click','#testVUT',function(){
    //     // debugger;
    //     getOwnSolarPricing($("#solar_pv_pricing_input_c").val(), 'solar_input');
    // });
    // //test

    //Variables
//     var quote_type = $("#quote_type_c").val();
//     var solar_input = $("#solar_pv_pricing_input_c").val();
//     var own_solar = $("#own_solar_pv_pricing_c").val();

//     if (quote_type == 'quote_type_solar') {
//         init_table_own_solar();
//         getDataProductSolar();
//         if (own_solar != '') {
//             getOwnSolarPricing(own_solar);
//         } else if (solar_input != '') {
//             getOwnSolarPricing(solar_input, 'solar_input');
//         }
//     }

//     // .:nhantv:. Add a checkbox to Itemise in LINE ITEMS
//     $('input[name="sl_quote_option"]').on('change', function() {
//         // Set change
//         let dataAttr = $(this).attr('data-attr');
//         let propVal = $(this).prop('checked');
//         if(propVal){
//             $('input[name="sl_quote_option"]').each(function(){
//                 $(this).attr('data-attr') && $(this).attr('data-attr') === dataAttr
//                 ? $(this).prop('checked', propVal) 
//                 : $(this).prop('checked', !propVal);
//             })
//         } else {
//             // Mark line deleted
//             for (var i = 0; i < prodln; i++){
//                 markLineDeleted(i,"product_");
//             };
//             // Delete group
//             $("#lineItems").find(".group_body").each((index) => {
//                 markGroupDeleted(index);
//             });
//         }
//         generateJSONForInput();
//     });

// });


// //DECLARE FUNCTIONS
// /**
//  * VUT - get All Data Product Solar (panel + inverter + std solar install)
//  */
// function getDataProductSolar() {
//     let panel_type = [
//         'Jinko 330W Mono PERC HC',
//         // 'Jinko 370W Cheetah Plus JKM370M-66H',
//         'Q CELLS Q.MAXX-G2 350W',
//         /*'Longi Hi-MO X 350W''Q CELLS Q.MAXX 330W''Q CELLS Q.PEAK DUO G6+ 350W',*/
//         'Sunpower P3 370 BLACK',
//         /*'Sunpower X22 360W',*/
//         'Sunpower Maxeon 3 400',
//         'Solar PV Standard Install',
//         /*'Sunpower Maxeon 2 350','Sunpower Maxeon 3 395'*/
//     ];
//     let inverter_type = [
//         'Primo 3',
//         'Primo 4',
//         'Primo 5',
//         'Primo 6',
//         'Primo 8.2',
//         'Symo 5',
//         'Symo 6',
//         'Symo 8.2',
//         'Symo 10',
//         'Symo 15',
//         'SYMO 20',
//         'S Edge 3',
//         'S Edge 5',
//         'S Edge 6',
//         'S Edge 8',
//         'S Edge 8 3P',
//         'S Edge 10',
//         'IQ7 plus',
//         /*'IQ7',*/
//         'IQ7X',
//         /*'Growatt 3',
//         'Growatt 5',
//         'Growatt 6',
//         'Growatt8',
//         'Growatt 8.2',*/
//         'Sungrow 3',
//         'Sungrow 5',
//         'Sungrow 8',
//         'Sungrow 10 3P',
//         'Sungrow 15 3P',
//     ];

//     $.ajax({
//         url: "index.php?entryPoint=getOwnSolarPricing",
//         type : 'POST',
//         async: false,
//         data: 
//         {
//             panel_type: panel_type,
//             inverter_type: inverter_type,
//         },
//         success: function (data) {
//             if(data === undefined){
//                 localStorage.setItem('productSolar','');
//                 return;
//             }else{
//                 localStorage.setItem('productSolar',JSON.stringify($.parseJSON(data)));
//             }
//     }
//     }); 
// }

// /**
//  * VUT - get data own solar
//  * @param {'json'} data 
//  * @param {String} string >> solar_input / own_solar
//  */
// function getOwnSolarPricing(data, string='') {
//     let json_data;
//     productSolar = JSON.parse(localStorage.productSolar);

//     if (string == '') {
//         json_data = $.parseJSON(data, true);
//         loadOwnOptionsPricing();
//     } else {
//         json_data = getInputTypes(data);
//         for (let i=1; i<7; i++) {
//             json_data[`own_basePrice_${i}`] = calc_own_basePrice(productSolar, json_data, i);
//         }
//         $('#own_solar_pv_pricing_c').val(JSON.stringify(json_data));
//         loadOwnOptionsPricing();
//     }
// }

// /**
//  * VUT - calc base Price for Own solar
//  * @param {'localStorage.productSolar'} products 
//  * @param {'json_own'} own_data 
//  * @param {'number'} index 
//  * @return {'float'} total cost
//  */
// function calc_own_basePrice(products, own_data, index) {
//     let panel_cost = 0, inverter_cost = 0, std_solar_install_cost = 0;
//     // let inv_regex = '/'+own_data[`own_inverterType_${index}`]+'/';
//     panel_cost = parseFloat(own_data[`own_totalPanels_${index}`]) * parseFloat(!isNaN(products.panels[own_data[`own_panelType_${index}`]])?products.panels[own_data[`own_panelType_${index}`]]:0);
//     std_solar_install_cost = parseFloat(own_data[`own_totalkW_${index}`]) * parseFloat(products.panels['Solar PV Standard Install']);
//     $.each(products.inverters, function (name, cost) {
//         if (name.indexOf(own_data[`own_inverterType_${index}`]) !== -1) {
//             inverter_cost = parseFloat(cost);
//             return false;
//         }
//     });
//     return (panel_cost + inverter_cost + std_solar_install_cost).toFixed(2);
// }

// /**
//  * VUT - get input type from Solar PV Pricing Input 
//  * @param {'json'} solar_input 
//  * @returns {'json'} json_res
//  */
// function getInputTypes(solar_input) {
//     let json_val = JSON.parse(solar_input);
//     let json_res = {};
//     for (let key in json_val) {
//         if (key.indexOf('total_kW_') != -1) {
//             json_res['own_totalkW_'+key[key.length - 1]] = json_val[key]; 
//         } else if (key.indexOf('panel_type_') != -1 ) {
//             json_res['own_panelType_'+key[key.length - 1]] = json_val[key]; 
//         } else if (key.indexOf('inverter_type_') != -1) {
//             json_res['own_inverterType_'+key[key.length - 1]] = json_val[key]; 
//         } else if (key.indexOf('total_panels_') != -1) {
//             json_res['own_totalPanels_'+key[key.length - 1]] = json_val[key]; 
//         } 
//     }
//     return json_res;
// } //end function getInputTypes

// /**
//  * VUT - init table own solar pv pricing  
//  */
// function init_table_own_solar() {
//     // let own_solar_pv_pricing_table   = $("<div id='own_solar_pv_pricing_table'></div>"); 
//     let own_solar_pv_pricing_table   = $('<div id="own_solar_pv_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row"></div>'); 
    
//     let data = [
//         ["", 'Option 1', 'Option 2', 'Option 3', 'Option 4', 'Option 5', 'Option 6'],
//         ["Selected Quote Option"
//             , "<input data-attr='1' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_1' style='margin-bottom:5px'>"
//             ,"<input data-attr='2' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_2' style='margin-bottom:5px'>"
//             ,"<input data-attr='3' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_3' style='margin-bottom:5px'>"
//             ,"<input data-attr='4' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_4' style='margin-bottom:5px'>"
//             ,"<input data-attr='5' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_5' style='margin-bottom:5px'>"
//             ,"<input data-attr='6' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_6' style='margin-bottom:5px'>"],
//         ["Total kW:", makeInputBox("own_totalkW_1 own_solar_pv_pricing", "own_totalkW_1", true), makeInputBox("own_totalkW_2 own_solar_pv_pricing", "own_totalkW_2", true), makeInputBox("own_totalkW_3 own_solar_pv_pricing", "own_totalkW_3",true), makeInputBox("own_totalkW_4 own_solar_pv_pricing", "own_totalkW_4",true), makeInputBox("own_totalkW_5 own_solar_pv_pricing", "own_totalkW_5",true), makeInputBox("own_totalkW_6 own_solar_pv_pricing", "own_totalkW_6",true)],
//         ["Panel Type:", makeInputBox("own_panelType_1 own_solar_pv_pricing", "own_panelType_1", true), makeInputBox("own_panelType_2 own_solar_pv_pricing", "own_panelType_2", true), makeInputBox("own_panelType_3 own_solar_pv_pricing", "own_panelType_3", true), makeInputBox("own_panelType_4 own_solar_pv_pricing", "own_panelType_4", true), makeInputBox("own_panelType_5 own_solar_pv_pricing", "own_panelType_5", true), makeInputBox("own_panelType_6 own_solar_pv_pricing", "own_panelType_6", true)],
//         ["Inverter Type:", makeInputBox("own_inverterType_1 own_solar_pv_pricing", "own_inverterType_1", true), makeInputBox("own_inverterType_2 own_solar_pv_pricing", "own_inverterType_2", true), makeInputBox("own_inverterType_3 own_solar_pv_pricing", "own_inverterType_3", true), makeInputBox("own_inverterType_4 own_solar_pv_pricing", "own_inverterType_4", true), makeInputBox("own_inverterType_5 own_solar_pv_pricing", "own_inverterType_5", true), makeInputBox("own_inverterType_6 own_solar_pv_pricing", "own_inverterType_6", true)],
//         // ["Panel Type:", makeSelectBox(panel_type,"own_panelType_1 own_solar_pv_pricing", "own_panelType_1"), makeSelectBox(panel_type,"own_panelType_2 own_solar_pv_pricing", "own_panelType_2"), makeSelectBox(panel_type,"own_panelType_3 own_solar_pv_pricing", "own_panelType_3"), makeSelectBox(panel_type,"own_panelType_4 own_solar_pv_pricing", "own_panelType_4"), makeSelectBox(panel_type,"own_panelType_5 own_solar_pv_pricing", "own_panelType_5"), makeSelectBox(panel_type,"own_panelType_6 own_solar_pv_pricing", "own_panelType_6")],
//         // ["Inverter Type:", makeSelectBox(inverter_type,"own_inverterType_1 own_solar_pv_pricing", "own_inverterType_1"), makeSelectBox(inverter_type,"own_inverterType_2 own_solar_pv_pricing", "own_inverterType_2"), makeSelectBox(inverter_type,"own_inverterType_3 own_solar_pv_pricing", "own_inverterType_3"), makeSelectBox(inverter_type,"own_inverterType_4 own_solar_pv_pricing", "own_inverterType_4"), makeSelectBox(inverter_type,"own_inverterType_5 own_solar_pv_pricing", "own_inverterType_5"), makeSelectBox(inverter_type,"own_inverterType_6 own_solar_pv_pricing", "own_inverterType_6")],
//         ["Total Panels:", makeInputBox("own_totalPanels_1 own_solar_pv_pricing","own_totalPanels_1", true), makeInputBox("own_totalPanels_2 own_solar_pv_pricing", "own_totalPanels_2", true), makeInputBox("own_totalPanels_3 own_solar_pv_pricing", "own_totalPanels_3", true), makeInputBox("own_totalPanels_4 own_solar_pv_pricing", "own_totalPanels_4", true), makeInputBox("own_totalPanels_5 own_solar_pv_pricing", "own_totalPanels_5", true), makeInputBox("own_totalPanels_6 own_solar_pv_pricing", "own_totalPanels_6", true)],
//         ["Base Price:", makeInputBox("own_basePrice_1 own_solar_pv_pricing", "own_basePrice_1", true), makeInputBox("own_basePrice_2 own_solar_pv_pricing", "own_basePrice_2", true), makeInputBox("own_basePrice_3 own_solar_pv_pricing", "own_basePrice_3", true), makeInputBox("own_basePrice_4 own_solar_pv_pricing", "own_basePrice_4", true), makeInputBox("own_basePrice_5 own_solar_pv_pricing", "own_basePrice_5", true), makeInputBox("own_basePrice_6 own_solar_pv_pricing", "own_basePrice_6", true)],
//     ];
//     //select Panel OWN SOLAR
//     // let selector_panel_own_solar = '';
//     // $('.panel-content .panel-default').each(function(){
//     //     let title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
//     //     if(title_panel_default.indexOf('own solar') >= 0 ){
//     //         selector_panel_own_solar = '#' + $(this).find('.panel-body').attr('id');
//     //     } 

//     // $(selector_panel_own_solar).find(".tab-content").html(own_solar_pv_pricing_table);
    
//     // .:nhantv:. Update to set order before "Save and Generate Quote" field
//     $('body').find("#generate_quote").before(own_solar_pv_pricing_table);

//     makeTable(own_solar_pv_pricing_table, data, "Own-Solar-PV-Pricing", "Own-Solar-PV-Pricing");
//     //css Table
//     $(".Own-Solar-PV-Pricing td").css({"padding":"0px 5px"});
//     $(".Own-Solar-PV-Pricing th").css({"text-align":"center"});
//     // .:nhantv:. Init Options and gen Line Items
//     initOptionAndGenLineItem();
// } //end function init_table_own_solar

// /**
//  * VUT - load Options Own Solar PV Pricing
//  */
// function loadOwnOptionsPricing(){
//     if($("#own_solar_pv_pricing_c").val()!=""){
//         var json_val = JSON.parse(($("#own_solar_pv_pricing_c").val() != "")?$("#own_solar_pv_pricing_c").val():"{}");
//         for (let key in json_val) {  
//             $("#"+key).val(json_val[key]);
//         }
//     }
// }
