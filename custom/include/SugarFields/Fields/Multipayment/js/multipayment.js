
Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,z
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

function populatePaymentAmout(){
    var grossTotal = Number($("#total_amount").val().replace(/[^0-9\.]+/g,""));
    var patialPayment = 0;
    $("#multipayment input[name='payment_amount']").each(function(){
        patialPayment += Number($(this).val());

    });
    var nextPaymentAmount = parseFloat(grossTotal - patialPayment).formatMoney(2,',','.');
    if ($('#next_payment_amount_c').val() == "" || $('#next_payment_amount_c').val() == "0.00" || $("#next_payment_state_c").prop("checked") === false){
        $('#next_payment_amount_c').val(nextPaymentAmount);
    }
   // $('#next_payment_amount_c').val(nextPaymentAmount);
    if (Number($("#next_payment_amount_c").val().replace(/[^0-9\.]+/g,"")) > Number(nextPaymentAmount.replace(/[^0-9\.]+/g,""))){
        $('#next_payment_amount_c').val(nextPaymentAmount);
    }

    $('#total_balance_owing_c').val(nextPaymentAmount);
    $('#sanden_revenue').val(nextPaymentAmount);
    display_text_payment();
}

function updatePaymentInfo(){
    var listPaymentObject = new Array();
    $("#multipayment tr").each(function() {
        if($( this ).find("input[name='payment_amount']").val() !== undefined && $( this ).find("input[name='payment_amount']").val() != "") {

            var payment = new Object();
            payment.payment_amount = $(this).find("input[name='payment_amount']").val();
            payment.payment_description = $(this).find("input[name='payment_description']").val();
            payment.payment_date = $(this).find("input[name='payment_date']").val();
            payment.payment_brankref = $(this).find("input[name='payment_brankref']").val();

            listPaymentObject.push(payment);
        }
    });

    $('input[name="payments_c"]').val(encodeURIComponent(JSON.stringify(listPaymentObject)));
}

function display_text_payment() {
    $('.link_text_payment').remove();
    var next_payment_amount = $('#next_payment_amount_c').val();
    var total_balance_owing = $('#total_balance_owing_c').val();
    $('#next_payment_amount_c').parent().append('<p class="link_text_payment" style="color:#f08377">'+convert_number(next_payment_amount) +'</p>');
    $('#total_balance_owing_c').parent().append('<p class="link_text_payment" style="color:#f08377">'+convert_number(total_balance_owing) +'</p>');
}

function convert_number(params) {
    var array_params = params.split(".");
    if(array_params[1] == '00'){
        params = array_params[0];
    }
    array_params = params.split(',');
    params = array_params.join("");
    return params;

} 
function populatePaymentAmout_change_total(){
    var grossTotal = Number($("#total_amount").val().replace(/[^0-9\.]+/g,""));
    var patialPayment = 0;
    $("#multipayment input[name='payment_amount']").each(function(){
        patialPayment += Number($(this).val());

    });
    var nextPaymentAmount = parseFloat(grossTotal - patialPayment).formatMoney(2,',','.');
    $('#next_payment_amount_c').val(nextPaymentAmount);
    if (Number($("#next_payment_amount_c").val().replace(/[^0-9\.]+/g,"")) > Number(nextPaymentAmount.replace(/[^0-9\.]+/g,""))){
        $('#next_payment_amount_c').val(nextPaymentAmount);
    }

    $('#total_balance_owing_c').val(nextPaymentAmount);
    $('#sanden_revenue').val(nextPaymentAmount);
    display_text_payment();
}
$(function () {
    'use strict';
    $('#multipayment tr input[name="payment_date"]').each(function(){
        YAHOO.util.Event.addListener($(this).attr('id'), "change", function(){
            updatePaymentInfo();
            //
            populatePaymentAmout();
        });
    });

    $("#multipayment").on("focusout", "input", function() {
        updatePaymentInfo();
        //
        //dung fix - console error js -Report module
        if(typeof module_sugar_grp1  !== 'undefined' &&  module_sugar_grp1 !== 'AOR_Reports') populatePaymentAmout();
    });
    $("#line_items_span").on("change", ".service_list_price", function() {
        updatePaymentInfo();
        //
        populatePaymentAmout();
    });

    //$("#total_balance_owing_c").attr('disabled', 'disabled');
    YAHOO.util.Event.addListener('total_amount', "change", populatePaymentAmout_change_total);
    // Next payment amount
    YAHOO.util.Event.addListener('next_payment_amount_c', "change", function(){
        var next_payment_amount = Number($("#next_payment_amount_c").val().replace(/[^0-9\.]+/g,""));
        $('#next_payment_amount_c').val(parseFloat(next_payment_amount).formatMoney(2,',','.'));
        //thienpb change logic
        // if($("#next_payment_state_c").prop("checked") != true){
        //     $("#next_payment_state_c").prop("checked","true").trigger("change");
        //     $("#next_payment_state_c").trigger("change");
        // }
    });
    //dung fix - console error js -Report module
    if(typeof module_sugar_grp1  !== 'undefined' &&  module_sugar_grp1 !== 'AOR_Reports') populatePaymentAmout();
});
 //Dung code
$(document).ready(function() {
    display_text_payment();

    //thienpb code - change logic 
    if($("#next_payment_state_c").prop("checked")){
        $(".warning_payment_status").remove()
        $("#next_payment_state_c").after("<span class='warning_payment_status' style='color:red;margin-left:5px'>The next payment amount is not filled automatically</span>")
    }else{
        $("#next_payment_state_c").after("")
    }

    $("#next_payment_state_c").change(function(){
        if($(this).prop("checked")){
            $(".warning_payment_status").remove()
            $(this).after("<span class='warning_payment_status' style='color:red;margin-left:5px'>The next payment amount is not filled automatically</span>")
        }else{
            $(".warning_payment_status").remove();
            $("#next_payment_amount_c").val($("#total_balance_owing_c").val());
            display_text_payment();
        }
    });

    // calculation gross profit sanden 
    function calculation_gross_profit_sanden(){
        var SandenEquipmentCost = Number($("#sanden_supply_bill_c").val().replace(/[^0-9\.]+/g,""));
        var SandenShippingCost =  Number($("#sanden_shipping_bill_c").val().replace(/[^0-9\.]+/g,""));
        var SandenPlumbingInstallationCost = Number($("#plumbing_bill_c").val().replace(/[^0-9\.]+/g,""));
        var SandenElectricalInstallationCost = Number($("#electrician_bill_c").val().replace(/[^0-9\.]+/g,""));
        var SubTotalCOSTS  = SandenEquipmentCost+ SandenShippingCost+ SandenPlumbingInstallationCost+SandenElectricalInstallationCost;
        
        var CustomerRevenue= Number($("#sanden_revenue_c").val().replace(/[^0-9\.]+/g,""));
        var STCRevenue= Number($("#sanden_stcs_c").val().replace(/[^0-9\.]+/g,""));
        //VUT-Revenue
        var VEECRevenue = Number($("#veec_revenue_c").val().replace(/[^0-9\.]+/g,""));
        var Solar_Vic_Revenue = Number($("#solar_vic_revenue_c").val().replace(/[^0-9\.]+/g,""));
        var SA_Reps_Revenue = Number($("#sa_reps_revenue_c").val().replace(/[^0-9\.]+/g,""));
        //VUT-Revenue
        var SubTotalREVENUE = CustomerRevenue + STCRevenue + VEECRevenue + Solar_Vic_Revenue + SA_Reps_Revenue;
       
        var GrossProfit = SubTotalREVENUE - SubTotalCOSTS;
        var GrossProfit_percent = GrossProfit/SubTotalCOSTS*100;
        
        $('#sanden_supply_bill_c').val(parseFloat(SandenEquipmentCost).formatMoney(2,',','.'));
        $('#sanden_shipping_bill_c').val( parseFloat(SandenShippingCost).formatMoney(2,',','.'));
        $('#plumbing_bill_c').val( parseFloat(SandenPlumbingInstallationCost).formatMoney(2,',','.'));
        $('#electrician_bill_c').val( parseFloat(SandenElectricalInstallationCost).formatMoney(2,',','.'));
        $('#sanden_total_costs_c').val(parseFloat(SubTotalCOSTS).formatMoney(2,',','.'));
        
        $('#sanden_revenue_c').val( parseFloat(CustomerRevenue).formatMoney(2,',','.'));
        $('#sanden_stcs_c').val( parseFloat(STCRevenue).formatMoney(2,',','.'));
        //VUT-Revenue
        $('#veec_revenue_c').val( parseFloat(VEECRevenue).formatMoney(2,',','.'));
        $('#solar_vic_revenue_c').val( parseFloat(Solar_Vic_Revenue).formatMoney(2,',','.'));
        $('#sa_reps_revenue_c').val( parseFloat(SA_Reps_Revenue).formatMoney(2,',','.'));
        //VUT-Revenue
        $('#sanden_total_revenue_c').val( parseFloat(SubTotalREVENUE).formatMoney(2,',','.'));

        $('#sanden_gross_profit_c').val( parseFloat(GrossProfit).formatMoney(2,',','.'));
        $('#sanden_gprofit_percent_c').val( `${parseFloat(GrossProfit_percent).formatMoney(2,',','.')} %`);
    }
    $('#sanden_gross_profit_c').parent().parent().append('<br><button type="button" class="button" id="calculation_profit_sanden">Calculation Profit Sanden</button>');
    $("#calculation_profit_sanden").on('click',function(){
        calculation_gross_profit_sanden();
    })
    var string_selector_calculation_sanden = '#sanden_stcs_c, #sanden_supply_bill_c,#sanden_shipping_bill_c,#plumbing_bill_c,#electrician_bill_c,#sanden_revenue_c,#STCRevenue, #sanden_total_costs_c, #veec_revenue_c, #solar_vic_revenue_c, #sa_reps_revenue_c';
    $(string_selector_calculation_sanden).css('width','35%');
    $(string_selector_calculation_sanden).on("change", calculation_gross_profit_sanden);
    //VUT- GP Calculation - update Equipment Cost  && Sanden Customer/STCs/VEEC Revenue when lineItem change
    $('#line_items_span').find('.product_group').on('change', 'input',function() {
        setTimeout(function(){
            //STC = $36.55 && VEECs = $30 :: fixed
            var qty = getSTCsLineItem();
            var sanden_STCs_revenue = parseFloat(qty.STCs*36.55);
            var sanden_VEECs_revenue = parseFloat(qty.VEECs*30);
            $('#sanden_stcs_c').val(sanden_STCs_revenue);
            $('#veec_revenue_c').val(sanden_VEECs_revenue).trigger('change');
            //field "sanden_supply_bill_c" Sanden Equipment Cost
            if ($('#quote_type_c').val() == 'quote_type_sanden') {
                var sanden_equipment_cost = calculate_sanden_equipment_cost_gp();
                $('#sanden_supply_bill_c').val(sanden_equipment_cost);
            }
            calculation_gross_profit_sanden();
            // $('#sanden_revenue_c').val($('#total_amt').val()).trigger('change');
        }, 100);
    });
    //VUT-fill GP Calculation first time
    setTimeout(function(){
        //VUT- get from PO
        var po_plumb_id = $('#plumber_po_c').val();
        var po_electrical_id = $('#electrical_po_c').val();
        if (po_plumb_id != '' ) {
            var plumb_total = parseFloat(getPO_Total(po_plumb_id));
        } 
        if (po_electrical_id != '') {
            var electrical_total = parseFloat(getPO_Total(po_electrical_id));
        }
        //GP Calculation - Plumbing/Electrical Installation Cost
        $('#plumbing_bill_c').val(plumb_total);
        $('#electrician_bill_c').val(electrical_total);
        //VUT- get Customer/STC/VEEC Revenue from Line Item
        var qty = getSTCsLineItem();
        //STC = $36.55 && VEECs = $30 :: fixed
        var sanden_STCs_revenue =parseFloat(qty.STCs*36.55);
        var sanden_VEECs_revenue = parseFloat(qty.VEECs*30);
        $('#sanden_stcs_c').val(sanden_STCs_revenue);
        $('#veec_revenue_c').val(sanden_VEECs_revenue);

        //GP Calculation - fill field "sanden_supply_bill_c" Sanden Equipment Cost
        if ($('#quote_type_c').val() == 'quote_type_sanden') {
            var sanden_equipment_cost = calculate_sanden_equipment_cost_gp();
            $('#sanden_supply_bill_c').val(sanden_equipment_cost);
        }
        calculation_gross_profit_sanden();
        // $('#sanden_revenue_c').val($('#total_amt').val()).trigger('change');
    }, 500);

    /**
     * VUT-get quantyti STCs/VEECs from line item for GP Calculation
     */
    function getSTCsLineItem() {
        let products = $('#line_items_span').find('.product_group').children('tbody');
        let i; 
        let qty={
            STCs : 0,
            VEECs : 0,
        };
        for (i=0;i< products.length; i++) {
            if ($(`#product_name${i}`).val() == 'STCs') {
                qty['STCs'] = Number($(`#product_product_qty${i}`).val().replace(/[^0-9\.]+/g,""));
            }
            if ($(`#product_name${i}`).val() == 'VEECs') {
                qty['VEECs'] = Number($(`#product_product_qty${i}`).val().replace(/[^0-9\.]+/g,""));
                // qty['VEECs'] = parseFloat($(`#product_product_qty${i}`).val().replace(',',''));
            }
        }
        return qty;
    }

    /**
     * VUT-Get total Po Plumping and Electrical for GP Calculation
     */
    function getPO_Total(po_id) {
        let result;
        $.ajax({
            url: "?entryPoint=GetPOForCalculation&type=gp_profit&po_id="+po_id,
            async:false
        }).done(function (data) {
            result = data;
        });
        return result;
    }

    /**
     * VUT - Calculate Sanden Equipment Cost for GP Calculation
     */
    function calculate_sanden_equipment_cost_gp() {
        // let sanden_product = [
        //     "GAUS-160FQS",
        //     "GAUS-250FQS",
        //     "GAUS-300FQS",
        //     "GAUS-315FQS",
        // ];
        // let sanden_hpump = [
        //     "QIK15−HPUMP", 
        //     "QIK20−HPUMP",
        // ];
        let lineItems_products = $('#line_items_span').find('.product_group').children('tbody');
        let i; 
        let sanden_groups={};
        //get product in LineItem at Invoice
        for (i=0; i < lineItems_products.length ; i++) {
            let product_partNumber = $(`#product_part_number${i}`).val();
            let product_id = $(`#product_product_id${i}`).val();
            let product_qty = $(`#product_product_qty${i}`).val();
            // if (sanden_product.includes(product_partNumber) || sanden_hpump.includes(product_partNumber)) {
            if (product_partNumber.indexOf("GAUS-") != -1 || product_partNumber.indexOf("−HPUMP") != -1) {
                if (sanden_groups.hasOwnProperty(product_id)) {
                    sanden_groups[product_id].qty += parseInt(product_qty);
                } else {
                    sanden_groups[product_id] = {
                        'partNumber': product_partNumber,
                        'qty': parseInt(product_qty),
                    };
                }
            }
        }
        //get product's price, cost in AOS_Product
        var total_equipment_cost = 0;

        $.each(sanden_groups, function(key,value){
            SUGAR.ajaxUI.showLoadingPanel();
            let price, cost;
            $.ajax({
                url: "?entryPoint=getProductInfos&type=gp_profit&product_id="+key,
                async:false
            }).done(function (data) {
                if(data == '' && typeof data == undefined)return;
                let jsonObj = JSON.parse(data);
                price = jsonObj.price;
                cost = jsonObj.cost;
            });
            sanden_groups[key].price = parseFloat(price);
            sanden_groups[key].cost = parseFloat(cost);
            total_equipment_cost += value.cost*value.qty;
        });
        SUGAR.ajaxUI.hideLoadingPanel();
        return total_equipment_cost;
    }

});