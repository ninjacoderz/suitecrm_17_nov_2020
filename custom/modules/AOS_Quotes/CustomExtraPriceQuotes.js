$(function () {
    'use strict';

    // DECLARE GLOBAL VARIABLE
    var state = convert_state();
    var installYear = 0;
    
    //INIT BASE PRICE JSON BY STATE
    if(state != ''){
        loadJSON(state);
        if(state == 'VIC' && state == 'NSW' && state == 'ACT'){
            installYear = 2021;
        }else{
            installYear = 2021;
        }
        if($("#distance_to_sg_c").val() != "" && $("#distance_to_sg_c").val() !== undefined){
            $("input[id*='travel_km_']").val($("#distance_to_sg_c").val().replace(" km",""));
        }
    }
    $('body').on("change","#install_address_state_c",function(e){
        e.preventDefault();
        if($(this).val() == "VIC" && $(this).val() != ''){
            installYear = 2021;
        }else{
            installYear = 2021;
        }
        $("#installYear").html(installYear);
        $("#table_pricing_state").val(convert_state($(this).val()));
    });

    //INIT
    init(state,installYear);
    loadOptionPricing();
    
    //SET EVENTLISTIONER
    $('body').on("change","select[id*='panel_type_'], select[id*='inverter_type_'], input[id*='total_panels_'], input[id*='pm_']",function(){
        var index  = $(this).attr("id").split('_');
        if($(this).attr("id").indexOf('pm_') >= 0){
            index = index[1];
        }else{
            index = index[2];
        }

        action_changed(index);
    });

    //CHANGE EXTRA PRICING
    $('body').on("change","select[id*='inverter_type_']",function(){
        var index  = $(this).attr("id").split('_');
        if($(this).attr("id").indexOf('extra_2_') >= 0){
            index = index[1];
        }else{
            index = index[2];
        }
        action_changed_extra(index);

        //NEW LOGIC
        if($("#inverter_type_"+index).val().indexOf("3P") >= 0){
            var choose = confirm("Change site to 3P");
            if(choose){
                $("#meter_phase_c").val(3);
                SUGAR.ajaxUI.showLoadingPanel();
                setTimeout(function() {
                    var build_url_quote = "?entryPoint=updateQuoteToSGQuote&meter_phase_c="+encodeURIComponent($("#meter_phase_c").val())+"&type=updateMeterPhase&record="+ encodeURIComponent($('input[name="record"]').val())+"&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
                    $.ajax({
                        url: build_url_quote,
                        type : 'POST',
                        async: false,
                        success: function (data) {
                            SUGAR.ajaxUI.hideLoadingPanel();
                        },
                    });
                },1000);
            }
        }
        
    });

    //CHANGE TRAVEL KM
    $("input[id*='travel_km_").change(function(){
        if($(this).val()){
            var val = $(this).val();
            $("input[id*='travel_km_").val(val);
        }
    });

    //Double_Storey CLICK    
    $('#Double_Storey').on('click',function(){
        if($(this).prop('checked') == true){
            $('#double_storey_c').prop('checked',true);
            for (let i = 1; i < 7; i++) {   
                if($("#total_panels").val() != ''){
                    $('#number_double_storey_panel_'+i).val($('#total_panels_'+i).val());
                }
            }
        }else {
            $('#double_storey_c').prop('checked',false);
        }
    })

    //Double_Storey CLICK    
    $('#Rough_in_Stage').on('click',function(){
        if($(this).prop('checked') == true){
            var id_file_and_photo = '#' + $("#special_notes_c").parents('div[class^="panel-body"]').eq(0).attr('id');
            $(id_file_and_photo).addClass('in');
            $(id_file_and_photo).siblings().find('a[data-toggle="collapse-edit"]').removeClass('collapsed');
            $("#special_notes_c").val("Rough in stage site visit if required +$350").focus();

        }else {
            $("#special_notes_c").val("").focus();
        }
    })

    // thienpb - update - Terracotta checkbox 
    
    $('#Terracotta_checkbox').on('click',function(){
        if($(this).prop('checked') == true){
            for (let i = 1; i < 7; i++) {   
                if($("#total_panels").val() != ''){
                    $('#terracotta_'+i).val($('#total_panels_'+i).val());
                }
                //if ($('#terracotta_'+i).val() !== "") $('#terracotta_'+i).trigger("change");
            }
        }
    })
    $('#Vic_Rebate').on('click',function(){
        if($(this).prop('checked') == true){
            $('#vic_rebate_c').prop('checked',true);
            var vic_html = '<tr>'
            +'<td>Vic Rebate</td>'
            +'<td><input class="vic_rebate_1 solar_pv_pricing_input" id="vic_rebate_1" value="-1850" disabled=""></td>'
            +'<td><input class="vic_rebate_2 solar_pv_pricing_input" id="vic_rebate_2" value="-1850" disabled=""></td>'
            +'<td><input class="vic_rebate_3 solar_pv_pricing_input" id="vic_rebate_3" value="-1850" disabled=""></td>'
            +'<td><input class="vic_rebate_4 solar_pv_pricing_input" id="vic_rebate_4" value="-1850" disabled=""></td>'
            +'<td><input class="vic_rebate_5 solar_pv_pricing_input" id="vic_rebate_5" value="-1850" disabled=""></td>'
            +'<td><input class="vic_rebate_6 solar_pv_pricing_input" id="vic_rebate_6" value="-1850" disabled=""></td>'
            +'</tr>';
            $('#Solar-PV-Pricing tbody').append(vic_html)
        }else {
            $('#vic_rebate_c').prop('checked',false);
            $('#vic_rebate_1').parent().parent().remove();
        }
    })
    $('#Loan_Rebate').on('click',function(){
        if($(this).prop('checked') == true){
            $('#vic_loan_c').prop('checked',true);
            var loan_html = '<tr>'
            +'<td>Loan Rebate</td>'
            +'<td><input class="loan_rebate_1 solar_pv_pricing_input" id="loan_rebate_1" value="-1850" disabled=""></td>'
            +'<td><input class="loan_rebate_2 solar_pv_pricing_input" id="loan_rebate_2" value="-1850" disabled=""></td>'
            +'<td><input class="loan_rebate_3 solar_pv_pricing_input" id="loan_rebate_3" value="-1850" disabled=""></td>'
            +'<td><input class="loan_rebate_4 solar_pv_pricing_input" id="loan_rebate_4" value="-1850" disabled=""></td>'
            +'<td><input class="loan_rebate_5 solar_pv_pricing_input" id="loan_rebate_5" value="-1850" disabled=""></td>'
            +'<td><input class="loan_rebate_6 solar_pv_pricing_input" id="loan_rebate_6" value="-1850" disabled=""></td>'
            +'</tr>';
            $('#Solar-PV-Pricing tbody').append(loan_html)
        }else {
            $('#vic_loan_c').prop('checked',false);
            $('#loan_rebate_1').parent().parent().remove();
        }
    })
    // if($('#vic_rebate_c').prop('checked') == true ){
    //     $('#Vic_Rebate').trigger('click');
    // }
    // if($('#vic_loan_c').prop('checked') == true ){
    //     $('#Loan_Rebate').trigger('click');
    // }
    // if($('#double_storey_c').prop('checked') == true ){
    //     $('#Double_Storey').trigger('click');
    // }
    //button getSGPrice_table_price
    $('body').on("click","#getSGPrice_table_price", function(){
        var sg_quote = $("#solargain_quote_number_c").val();
        $('#getSGPrice_table_price span.glyphicon-refresh').removeClass('hidden');
        $(".sg_price_get").remove();
        if(sg_quote != ''){
            $.ajax({
                url: '?entryPoint=customgetpricefromSG&sg_quote_id='+sg_quote,
                type : 'GET',
                dataType : 'json',
                success: function (data) {
                    $('#Solar-PV-Pricing tbody').after(data.html);
                    $('#Solar-PV-Pricing tbody').after(data.html_inverter);
                    if(data.error == 'error'){
                        alert("Different Inverter Model in Options");
                        $("#solargain_inverter_model_c").val('');
                    }else{
                        $("#solargain_inverter_model_c").val(data.model);
                    }
                    $('#getSGPrice_table_price span.glyphicon-refresh').addClass('hidden');
                },
            })
        }else{
            alert("Solargain Quote Number is required!");
            $('#getSGPrice_table_price span.glyphicon-refresh').addClass('hidden');
        }
    })

    //button max panels and event click
    $("#inverter_type_1").closest('tr').find("td").eq(0).append("</br><button class='button primary' type='button' id='max_panels' name='max_panels'>MAX</button>")
    $('body').on("click","#max_panels", async function(){
        if(state == ''){
            state = convert_state();
            if(state == ''){
                alert('Please filling full address.'); return;
            }
            await loadJSON(state);
            $("#table_pricing_state").val(state);
            if(state == 'VIC'){
                installYear = 2021;
                $("#installYear").html("2021");
            }else{
                installYear = 2021;
                $("#installYear").html("2021");
            }
        }
        for(var i = 1 ; i < 7 ; i++){
            var panel_type_ = $("#panel_type_"+i).val();
            var inverter_type_ = $("#inverter_type_"+i).val();
            if(inverter_type_ == 'IQ7 plus') inverter_type_ = 'IQ7+';
            // .:nhantv:. Add event extra change
            action_changed_extra(i);
            if(panel_type_ != '' && inverter_type_ != ""){
                var suggested_val = getSuggestedPanel(panel_type_,inverter_type_,'');
                // console.log(suggested_val);
                if(suggested_val === undefined) return;
                $(".suggest_total_panel_"+i).remove();
                for(var j = 0 ; j < suggested_val.length ; j++){
                    $("#inverter_type_"+i).after('<div class="suggest_total_panel_'+i+'" id="suggest_total_panel_'+j+'_'+j+'">0 - '+suggested_val[j]+' panels</div>');
                    $("#total_panels_"+i).val(suggested_val[j]).change();
                }
            }
        }
        //console.log("ddddd");
        // $("input[id*='total_panels_']").trigger("change");
        // for(var j = 0 ; j < suggested_val.length ; j++){
        //     $("#total_panels_"+i).trigger("change");
        // }
        // total_kw();
        save_values();
        getOwnSolarPricing($("#solar_pv_pricing_input_c").val(), 'solar_changed');
    });

    //trigger change caculate price when click Double Storey
    $('body').on('change','#Double_Storey',function(){
        $('#calculatePrice').trigger('click');
    })

    $("button[id*='btn_clear_option_']").click(function(e){
        e.preventDefault();
        // alert($(this).data('option'));
        clear_option($(this).data('option')+1);
    });
    
    $('table[id="Solar-PV-Pricing"]').after('<br><button type="button" class="button primary" id="calculatePrice"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Calculate Price </button><div class="clearfix"></div>');
    
    $('#calculatePrice').after(
        '<br><button type="button" id="copy_suggested_price"  class="button copy_suggested_price" title="Copy Suggested Price" onClick="copySuggestedPrice()" >CUSTOMER PRICE<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
    );

    //VUT
    $('#copy_suggested_price').after(
        '<br><button type="button" id="send_solar_pricing_option"  class="button send_solar_pricing" title="Send Solar pricing option" >SEND SOLAR PRICING OPTION<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
    );
    //VUT 

    $('body').on("click","#calculatePrice",function(){
        $('#calculatePrice span.glyphicon-refresh').removeClass('hidden');
        for(i=1; i<= 6; i ++){
            if(parseInt($("#base_price_"+i).val()) == 0){
                alert("Option "+ i + " config wrong! Please config it again.");
                return false;
            }else{
                calculation_for_option(i);
            }
        }
        total_kw();
        calc_total_price();
        save_values();
        getOwnSolarPricing($("#solar_pv_pricing_input_c").val(), 'solar_changed');

        setTimeout(function() {
            $('#calculatePrice span.glyphicon-refresh').addClass('hidden');
        },200);
        // if( $('#Vic_Rebate').prop('checked') == true ){
        //     for (let i = 1; i < 7; i++) {   
        //         if($("#total_panels").val() != ''){
        //             var vic_rebate = parseInt($('#customer_price_'+i).val()) - 1850;
        //             $('#customer_price_'+i).val(vic_rebate);
        //             $('#total_price_'+i).val(vic_rebate);
        //         }
        //     }
        // }
        // if( $('#Loan_Rebate').prop('checked') == true ){
        //     for (let i = 1; i < 7; i++) {   
        //         if($("#total_panels").val() != ''){
        //             var vic_rebate = parseInt($('#customer_price_'+i).val()) + 1850;
        //             $('#customer_price_'+i).val(vic_rebate);
        //             $('#total_price_'+i).val(vic_rebate);
        //         }
        //     }
        // }
    });

    display_button_price();

    $('body').on('change','#solargain_quote_number_c, #solargain_tesla_quote_number_c',function(){
        display_button_price();
    });

    // .:nhantv:. Comment to do the task: Add a checkbox to Itemise in LINE ITEMS
    // $('input[name="sl_quote_option"]').on('change', function() {
    //     $('input[name="sl_quote_option"]').not(this).prop('checked', false);
    // });

    
    if($("#solar_pv_pricing_input_c").val() == '' && $("#quote_type_c").val() == 'quote_type_solar'){
        if($("#pe_pricing_options_id_c").val() == ''){
            $("#pe_pricing_options_id_c").val('406fbeb4-0614-3bcd-7e15-5fbdea690303');
            $("#pricing_option_type_c").val('Sunpower Single Phase STRING');
            loadPricingOptions('406fbeb4-0614-3bcd-7e15-5fbdea690303');
        }else{
            loadPricingOptions();
        }
    }

    $("#quote_type_c").on("change",function(){
        if($(this).val() == 'quote_type_solar'){
            $("#pe_pricing_options_id_c").val('406fbeb4-0614-3bcd-7e15-5fbdea690303');
            $("#pricing_option_type_c").val('Sunpower Single Phase STRING');
            $("#link_pricing_option").remove();
            loadPricingOptions('406fbeb4-0614-3bcd-7e15-5fbdea690303');
        }
    })

    $(document).on("change","#checkall",function(){
        if($(this).prop("checked")){
            $("input[id*='sl_option_push_']").prop("checked",true);
        }else{
            $("input[id*='sl_option_push_']").prop("checked",false);
        }
    });

    // .:nhantv:. Auto change Panel Type on "PRICING PV SECTION"
    $("#copy_Panel").on("change",function(){
        if($(this).prop("checked") == true){
            // warning
            warningLineItem(0);

            let panel_type_1 = $('#panel_type_1').val();
            $('#panel_type_2').val(panel_type_1);
            $('#panel_type_3').val(panel_type_1);

            let panel_type_4 = $('#panel_type_4').val();
            $('#panel_type_5').val(panel_type_4);
            $('#panel_type_6').val(panel_type_4);

            // Trigger max_panels calculate
            $('#max_panels').trigger('click');
        }
    });
    $('#solar_pv_pricing_table *[id*=panel_type_]:visible').on("change",function(){
        if($("#copy_Panel").prop("checked") == true){
            let panel_type = $(this).val();
            let _id = $(this).attr('id');
            let target = parseInt(_id.substr(_id.length - 1));
            switch (target) {
                case 1:
                    $('#panel_type_2').val(panel_type);
                    $('#panel_type_3').val(panel_type);
                    break;
                case 4:
                    $('#panel_type_5').val(panel_type);
                    $('#panel_type_6').val(panel_type);
                    break;
                default: break;
            }
            // warning
            warningLineItem(target);
            // Trigger max_panels calculate
            $('#max_panels').trigger('click');
        }
    });

    // .:nhantv:. Apply all value for PM input change
    $('#solar_pv_pricing_table *[id*=pm_]:visible').on("change",function(){
        // warning
        warningLineItem(0);

        let pmVal = $(this).val();
        $('#solar_pv_pricing_table *[id*=pm_]:visible').each(function() {
            $(this).val(pmVal);
        });
    });
});


///// DECLARE FUNCTION /////
    const warningLineItem = function(target) {
        let isExistLineItem = $("#lineItems").find(".group_body").length != 0;
        if(target == 0 && isExistLineItem){
            alert('This action will affect LINE ITEM pricing. \nPlease click the "SAVE AND CREATE QUOTE" button again after making your changes');
            return;
        }

        let id = parseInt($('#Own-Solar-PV-Pricing').find('input:checked').attr('data-attr'));
        if(isExistLineItem && ((target == 1 && id < 4) || (target == 4 && (id < 7 && id > 3)))){
            alert('This action will affect LINE ITEM pricing. \nPlease click the "SAVE AND CREATE QUOTE" button again after making your changes');
        }
        return;
    }
    //////// MAKE A TABLE //////////
    function makeTable(container, data, tclass, tid) {
        var table = $("<table/>").addClass(tclass).attr("id",tid);
        table.append("<tbody></tbody");
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

    function init(state,installYear){

        var panel_type = ['','Jinko Tiger P-type Mono 370',/*'Jinko 330W Mono PERC HC',*/'Q CELLS Q.MAXX-G3 385W',/*'Longi Hi-MO X 350W''Q CELLS Q.MAXX 330W''Q CELLS Q.PEAK DUO G6+ 350W','Sunpower P3 325 BLACK'*/'Sunpower P3 370 BLACK',/*'Sunpower X22 360W',*/'Sunpower Maxeon 3 400W'/*'Sunpower Maxeon 2 350','Sunpower Maxeon 3 395'*/];
        var inverter_type = ['','Primo 3','Primo 4','Primo 5','Primo 6','Primo 8.2','Symo 5','Symo 6','Symo 8.2','Symo 10','Symo 15','SYMO 20','S Edge 3G','S Edge 5G','S Edge 6G','S Edge 8G','S Edge 8 3P','S Edge 10G','IQ7 plus',/*'IQ7',*/'IQ7X',/*'Growatt 3','Growatt 5','Growatt 6','Growatt8','Growatt 8.2',*/'Sungrow 3','Sungrow 5','Sungrow 8','Sungrow 10 3P','Sungrow 15 3P'];
        var extra_1 = ["","Fro. Smart Meter (1P)","Fro. Smart Meter (3P)","Fronius Service Partner Plus 10YR Warranty", "Switchboard UPG", "ENPHS Envoy-S Met.", "SE Smart Meter", "SE Wifi",'Sungrow Smart Meter (1P)','Sungrow Three Phase Smart Meter DTSU666'/*,'Sungrow Smart Meter (3P)'*/];
        var extra_2 = ["","Fro. Smart Meter (1P)","Fro. Smart Meter (3P)","Fronius Service Partner Plus 10YR Warranty", "Switchboard UPG", "ENPHS Envoy-S Met.", "SE Smart Meter", "SE Wifi",'Sungrow Smart Meter (1P)','Sungrow Three Phase Smart Meter DTSU666'/*,'Sungrow Smart Meter (3P)'*/];
        var extra_3 = ["","Fro. Smart Meter (1P)","Fro. Smart Meter (3P)","Fronius Service Partner Plus 10YR Warranty", "Switchboard UPG", "ENPHS Envoy-S Met.", "SE Smart Meter", "SE Wifi",'Sungrow Smart Meter (1P)','Sungrow Three Phase Smart Meter DTSU666'/*,'Sungrow Smart Meter (3P)'*/];
        var battery = ["","LG Chem RESU 10H SolarEdge & Fronius"];
        var bool_val = ["No", "Yes"];
        var data = [
            ["Option <input type='checkbox' id='checkall' style='float: right; clear: both; margin-right: 15px;'>", "1 <input type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_option_push' id='sl_option_push_1' checked style='margin-bottom:5px'>", "2 <input type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_option_push' id='sl_option_push_2' checked style='margin-bottom:5px'>", "3 <input type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_option_push' id='sl_option_push_3' checked style='margin-bottom:5px'>", "4 <input type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_option_push' id='sl_option_push_4' checked style='margin-bottom:5px'>", "5  <input type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_option_push' id='sl_option_push_5' checked style='margin-bottom:5px'>", "6  <input type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_option_push' id='sl_option_push_6' checked style='margin-bottom:5px'>"],
            ["", "<button data-option ='1' id='btn_clear_option_1' class='button default'>Clear Option 1</button>", "<button data-option ='2' id='btn_clear_option_2' class='button default'>Clear Option 2</button>", "<button data-option ='3' id='btn_clear_option_3' class='button default'>Clear Option 3</button>", "<button data-option ='4' id='btn_clear_option_4' class='button default'>Clear Option 4</button>", "<button data-option ='5' id='btn_clear_option_5' class='button default'>Clear Option 5</button>", "<button data-option ='6' id='btn_clear_option_6' class='button default'>Clear Option 6</button>"],
            ["Selected Quote Option"
                , "<input data-attr='1' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='sl_option_1' style='margin-bottom:5px'>"
                ,"<input data-attr='2' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='sl_option_2' style='margin-bottom:5px'>"
                ,"<input data-attr='3' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='sl_option_3' style='margin-bottom:5px'>"
                ,"<input data-attr='4' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='sl_option_4' style='margin-bottom:5px'>"
                ,"<input data-attr='5' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='sl_option_5' style='margin-bottom:5px'>"
                ,"<input data-attr='6' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='sl_option_6' style='margin-bottom:5px'>"],
            ["PM", makeInputBox("pm_1 solar_pv_pricing_input","pm_1"), makeInputBox("pm_2 solar_pv_pricing_input", "pm_2"), makeInputBox("pm_3 solar_pv_pricing_input", "pm_3"), makeInputBox("pm_4 solar_pv_pricing_input", "pm_4"), makeInputBox("pm_5 solar_pv_pricing_input", "pm_5"), makeInputBox("pm_6 solar_pv_pricing_input", "pm_6")],
            ["Total kW:", makeInputBox("total_kW_1 solar_pv_pricing_input", "total_kW_1", true), makeInputBox("total_kW_2 solar_pv_pricing_input", "total_kW_2", true), makeInputBox("total_kW_3 solar_pv_pricing_input", "total_kW_3",true), makeInputBox("total_kW_4 solar_pv_pricing_input", "total_kW_4",true), makeInputBox("total_kW_5 solar_pv_pricing_input", "total_kW_5",true), makeInputBox("total_kW_6 solar_pv_pricing_input", "total_kW_6",true)],
            ["Panel Type <input type='checkbox' id='copy_Panel' style='float: right; clear: both; margin-right: 15px;'>", makeSelectBox(panel_type,"panel_type_1 solar_pv_pricing_input", "panel_type_1"), makeSelectBox(panel_type,"panel_type_2 solar_pv_pricing_input", "panel_type_2"), makeSelectBox(panel_type,"panel_type_3 solar_pv_pricing_input", "panel_type_3"), makeSelectBox(panel_type,"panel_type_4 solar_pv_pricing_input", "panel_type_4"), makeSelectBox(panel_type,"panel_type_5 solar_pv_pricing_input", "panel_type_5"), makeSelectBox(panel_type,"panel_type_6 solar_pv_pricing_input", "panel_type_6")],
            ["Inverter Type", makeSelectBox(inverter_type,"inverter_type_1 solar_pv_pricing_input", "inverter_type_1"), makeSelectBox(inverter_type,"inverter_type_2 solar_pv_pricing_input", "inverter_type_2"), makeSelectBox(inverter_type,"inverter_type_3 solar_pv_pricing_input", "inverter_type_3"), makeSelectBox(inverter_type,"inverter_type_4 solar_pv_pricing_input", "inverter_type_4"), makeSelectBox(inverter_type,"inverter_type_5 solar_pv_pricing_input", "inverter_type_5"), makeSelectBox(inverter_type,"inverter_type_6 solar_pv_pricing_input", "inverter_type_6")],
            ["Total Panels", makeInputBox("total_panels_1 solar_pv_pricing_input","total_panels_1"), makeInputBox("total_panels_2 solar_pv_pricing_input", "total_panels_2"), makeInputBox("total_panels_3 solar_pv_pricing_input", "total_panels_3"), makeInputBox("total_panels_4 solar_pv_pricing_input", "total_panels_4"), makeInputBox("total_panels_5 solar_pv_pricing_input", "total_panels_5"), makeInputBox("total_panels_6 solar_pv_pricing_input", "total_panels_6")],
            ["Base Price", makeInputBox("base_price_1 solar_pv_pricing_input", "base_price_1"), makeInputBox("base_price_2 solar_pv_pricing_input", "base_price_2"), makeInputBox("base_price_3 solar_pv_pricing_input", "base_price_3"), makeInputBox("base_price_4 solar_pv_pricing_input", "base_price_4"), makeInputBox("base_price_5 solar_pv_pricing_input", "base_price_5"), makeInputBox("base_price_6 solar_pv_pricing_input", "base_price_6")],
            ["Extra 1", makeSelectBox(extra_1,"extra_1_1 solar_pv_pricing_input", "extra_1_1"), makeSelectBox(extra_1,"extra_1_2 solar_pv_pricing_input", "extra_1_2"), makeSelectBox(extra_1,"extra_1_3 solar_pv_pricing_input", "extra_1_3"), makeSelectBox(extra_1,"extra_1_4 solar_pv_pricing_input", "extra_1_4"), makeSelectBox(extra_1,"extra_1_5 solar_pv_pricing_input", "extra_1_5"), makeSelectBox(extra_1,"extra_1_6 solar_pv_pricing_input", "extra_1_6")],
            ["Extra 2", makeSelectBox(extra_2,"extra_2_1 solar_pv_pricing_input", "extra_2_1"), makeSelectBox(extra_2,"extra_2_2 solar_pv_pricing_input", "extra_2_2"), makeSelectBox(extra_2,"extra_2_3 solar_pv_pricing_input", "extra_2_3"), makeSelectBox(extra_2,"extra_2_4 solar_pv_pricing_input", "extra_2_4"), makeSelectBox(extra_2,"extra_2_5 solar_pv_pricing_input", "extra_2_5"), makeSelectBox(extra_2,"extra_2_6 solar_pv_pricing_input", "extra_2_6")],
            ["Extra 3", makeSelectBox(extra_3,"extra_3_1 solar_pv_pricing_input", "extra_3_1"), makeSelectBox(extra_3,"extra_3_2 solar_pv_pricing_input", "extra_3_2"), makeSelectBox(extra_3,"extra_3_3 solar_pv_pricing_input", "extra_3_3"), makeSelectBox(extra_3,"extra_3_4 solar_pv_pricing_input", "extra_3_4"), makeSelectBox(extra_3,"extra_3_5 solar_pv_pricing_input", "extra_3_5"), makeSelectBox(extra_3,"extra_3_6 solar_pv_pricing_input", "extra_3_6")],
            ["Battery", makeSelectBox(battery,"battery_1 solar_pv_pricing_input", "battery_1"), makeSelectBox(battery,"battery_2 solar_pv_pricing_input", "battery_2"), makeSelectBox(battery,"extra_3_3 solar_pv_pricing_input", "extra_3_3"), makeSelectBox(battery,"battery_4 solar_pv_pricing_input", "battery_4"), makeSelectBox(battery,"battery_5 solar_pv_pricing_input", "battery_5"), makeSelectBox(battery,"battery_6 solar_pv_pricing_input", "battery_6")],
            ["Double storey panels?", makeInputBox("number_double_storey_panel_1 solar_pv_pricing_input", "number_double_storey_panel_1"), makeInputBox("number_double_storey_panel_2 solar_pv_pricing_input", "number_double_storey_panel_2"), makeInputBox("number_double_storey_panel_3 solar_pv_pricing_input", "number_double_storey_panel_3"), makeInputBox("number_double_storey_panel_4 solar_pv_pricing_input", "number_double_storey_panel_4"), makeInputBox("number_double_storey_panel_5 solar_pv_pricing_input", "number_double_storey_panel_5"), makeInputBox("number_double_storey_panel_6 solar_pv_pricing_input", "number_double_storey_panel_6")],
            ["Raked Ceiling?", makeSelectBox(bool_val,"raked_ceiling_1 solar_pv_pricing_input", "raked_ceiling_1"), makeSelectBox(bool_val,"raked_ceiling_2 solar_pv_pricing_input", "raked_ceiling_2"), makeSelectBox(bool_val,"raked_ceiling_3 solar_pv_pricing_input", "raked_ceiling_3"), makeSelectBox(bool_val,"raked_ceiling_4 solar_pv_pricing_input", "raked_ceiling_4"), makeSelectBox(bool_val,"raked_ceiling_5 solar_pv_pricing_input", "raked_ceiling_5"), makeSelectBox(bool_val,"raked_ceiling_6 solar_pv_pricing_input", "raked_ceiling_6")],
            ["Travel (km)", makeInputBox("travel_km_1 solar_pv_pricing_input", "travel_km_1"), makeInputBox("travel_km_2 solar_pv_pricing_input", "travel_km_2"), makeInputBox("travel_km_3 solar_pv_pricing_input", "travel_km_3"), makeInputBox("travel_km_4 solar_pv_pricing_input", "travel_km_4"), makeInputBox("travel_km_5 solar_pv_pricing_input", "travel_km_5"), makeInputBox("travel_km_6 solar_pv_pricing_input", "travel_km_6")],
            ["Tilting", makeInputBox("tilting_1 solar_pv_pricing_input", "tilting_1"), makeInputBox("tilting_2 solar_pv_pricing_input", "tilting_2"), makeInputBox("tilting_3 solar_pv_pricing_input", "tilting_3"), makeInputBox("tilting_4 solar_pv_pricing_input", "tilting_4"), makeInputBox("tilting_5 solar_pv_pricing_input", "tilting_5"), makeInputBox("tilting_6 solar_pv_pricing_input", "tilting_6")],
            ["Groups of Panels?", makeInputBox("groups_of_panels_1 solar_pv_pricing_input", "groups_of_panels_1"), makeInputBox("groups_of_panels_2 solar_pv_pricing_input", "groups_of_panels_2"), makeInputBox("groups_of_panels_3 solar_pv_pricing_input", "groups_of_panels_3"), makeInputBox("groups_of_panels_4 solar_pv_pricing_input", "groups_of_panels_4"), makeInputBox("groups_of_panels_5 solar_pv_pricing_input", "groups_of_panels_5"), makeInputBox("groups_of_panels_6 solar_pv_pricing_input", "groups_of_panels_6")],
            ["Steep Roof 25-30 deg?", makeSelectBox(bool_val,"steep_roof_1 solar_pv_pricing_input", "steep_roof_1"), makeSelectBox(bool_val,"steep_roof_2 solar_pv_pricing_input", "steep_roof_2"), makeSelectBox(bool_val,"steep_roof_3 solar_pv_pricing_input", "steep_roof_3"), makeSelectBox(bool_val,"steep_roof_4 solar_pv_pricing_input", "steep_roof_4"), makeSelectBox(bool_val,"steep_roof_5 solar_pv_pricing_input", "steep_roof_5"), makeSelectBox(bool_val,"steep_roof_6 solar_pv_pricing_input", "steep_roof_6")],
            ["Landscape Panels >15 deg?", makeInputBox("landscape_panel_deg_1 solar_pv_pricing_input", "landscape_panel_deg_1"), makeInputBox("landscape_panel_deg_2 solar_pv_pricing_input", "landscape_panel_deg_2"), makeInputBox("landscape_panel_deg_3 solar_pv_pricing_input", "landscape_panel_deg_3"), makeInputBox("landscape_panel_deg_4 solar_pv_pricing_input", "landscape_panel_deg_4"), makeInputBox("landscape_panel_deg_5 solar_pv_pricing_input", "landscape_panel_deg_5"), makeInputBox("landscape_panel_deg_6 solar_pv_pricing_input", "landscape_panel_deg_6")],
            ["Terracotta", makeInputBox("terracotta_1 solar_pv_pricing_input", "terracotta_1"), makeInputBox("terracotta_2 solar_pv_pricing_input", "terracotta_2"), makeInputBox("terracotta_3 solar_pv_pricing_input", "terracotta_3"), makeInputBox("terracotta_4 solar_pv_pricing_input", "terracotta_4"), makeInputBox("terracotta_5 solar_pv_pricing_input", "terracotta_5"), makeInputBox("terracotta_6 solar_pv_pricing_input", "terracotta_6")],
            ["Extras Sub Total", makeInputBox("extras_sub_total_1 solar_pv_pricing_input", "extras_sub_total_1", true), makeInputBox("extras_sub_total_2 solar_pv_pricing_input", "extras_sub_total_2", true), makeInputBox("extras_sub_total_3 solar_pv_pricing_input", "extras_sub_total_3",true), makeInputBox("extras_sub_total_4 solar_pv_pricing_input", "extras_sub_total_4",true), makeInputBox("extras_sub_total_5 solar_pv_pricing_input", "extras_sub_total_5",true), makeInputBox("extras_sub_total_6 solar_pv_pricing_input", "extras_sub_total_6",true)],
            ["SG Net Price", makeInputBox("suggest_price_1 solar_pv_pricing_input", "suggest_price_1", true), makeInputBox("suggest_price_2 solar_pv_pricing_input", "suggest_price_2", true), makeInputBox("suggest_price_3 solar_pv_pricing_input", "suggest_price_3",true), makeInputBox("suggest_price_4 solar_pv_pricing_input", "suggest_price_4",true), makeInputBox("suggest_price_5 solar_pv_pricing_input", "suggest_price_5",true), makeInputBox("suggest_price_6 solar_pv_pricing_input", "suggest_price_6",true)],
            ["Number of STCs", makeInputBox("number_of_stcs_1 solar_pv_pricing_input", "number_of_stcs_1", true), makeInputBox("number_of_stcs_2 solar_pv_pricing_input", "number_of_stcs_2", true), makeInputBox("number_of_stcs_3 solar_pv_pricing_input", "number_of_stcs_3", true), makeInputBox("number_of_stcs_4 solar_pv_pricing_input", "number_of_stcs_4", true), makeInputBox("number_of_stcs_5 solar_pv_pricing_input", "number_of_stcs_5", true), makeInputBox("number_of_stcs_6 solar_pv_pricing_input", "number_of_stcs_6", true)],
            ["STC value", makeInputBox("stc_value_1 solar_pv_pricing_input", "stc_value_1", true), makeInputBox("stc_value_2 solar_pv_pricing_input", "stc_value_2", true), makeInputBox("stc_value_3 solar_pv_pricing_input", "stc_value_3", true), makeInputBox("stc_value_4 solar_pv_pricing_input", "stc_value_4", true), makeInputBox("stc_value_5 solar_pv_pricing_input", "stc_value_5", true), makeInputBox("stc_value_6 solar_pv_pricing_input", "stc_value_6", true)],
            ["Gross System Price SG", makeInputBox("sgp_system_price_1 solar_pv_pricing_input", "sgp_system_price_1", true), makeInputBox("sgp_system_price_2 solar_pv_pricing_input", "sgp_system_price_2", true), makeInputBox("sgp_system_price_3 solar_pv_pricing_input", "sgp_system_price_3",true), makeInputBox("sgp_system_price_4 solar_pv_pricing_input", "sgp_system_price_4",true), makeInputBox("sgp_system_price_5 solar_pv_pricing_input", "sgp_system_price_5",true), makeInputBox("sgp_system_price_6 solar_pv_pricing_input", "sgp_system_price_6",true)],
            // ["PE $ Increase", makeInputBox("pe_increase_1 solar_pv_pricing_input", "pe_increase_1", true), makeInputBox("pe_increase_2 solar_pv_pricing_input", "pe_increase_2", true), makeInputBox("pe_increase_3 solar_pv_pricing_input", "pe_increase_3", true), makeInputBox("pe_increase_4 solar_pv_pricing_input", "pe_increase_4", true), makeInputBox("pe_increase_5 solar_pv_pricing_input", "pe_increase_5", true), makeInputBox("pe_increase_6 solar_pv_pricing_input", "pe_increase_6", true)],
            ["Total Price", makeInputBox("total_price_1 solar_pv_pricing_input", "total_price_1", true), makeInputBox("total_price_2 solar_pv_pricing_input", "total_price_2", true), makeInputBox("total_price_3 solar_pv_pricing_input", "total_price_3",true), makeInputBox("total_price_4 solar_pv_pricing_input", "total_price_4",true), makeInputBox("total_price_5 solar_pv_pricing_input", "total_price_5",true), makeInputBox("total_price_6 solar_pv_pricing_input", "total_price_6",true)],
            ["Customer Price", makeInputBox("customer_price_1 solar_pv_pricing_input", "customer_price_1"), makeInputBox("customer_price_2 solar_pv_pricing_input", "customer_price_2"), makeInputBox("customer_price_3 solar_pv_pricing_input", "customer_price_3"), makeInputBox("customer_price_4 solar_pv_pricing_input", "customer_price_4"), makeInputBox("customer_price_5 solar_pv_pricing_input", "customer_price_5"), makeInputBox("customer_price_6 solar_pv_pricing_input", "customer_price_6")],
            ["$/W", makeInputBox("price_kw_1 solar_pv_pricing_input", "price_kw_1", true), makeInputBox("price_kw_2 solar_pv_pricing_input", "price_kw_2", true), makeInputBox("price_kw_3 solar_pv_pricing_input", "price_kw_3",true), makeInputBox("price_kw_4 solar_pv_pricing_input", "price_kw_4",true), makeInputBox("price_kw_5 solar_pv_pricing_input", "price_kw_5",true), makeInputBox("price_kw_6 solar_pv_pricing_input", "price_kw_6",true)],
        ];

        var solar_pv_pricing_table   = $("<div id='solar_pv_pricing_table'></div>");
        var seletor_panel_pricing_pv = '';
        $('.panel-content .panel-default').each(function(){
            var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
            if(title_panel_default == 'pricing pv section'){
                seletor_panel_pricing_pv = '#' + $(this).find('.panel-body').attr('id');
            }
        });

        $(seletor_panel_pricing_pv).find(".tab-content").html(solar_pv_pricing_table);
        makeTable(solar_pv_pricing_table, data, "Solar-PV-Pricing", "Solar-PV-Pricing");
        $(".Solar-PV-Pricing select").css({"width":"180px","font-size":"font-size: 12px;"}).parent().css('vertical-align','top');   
        
        //set default PM = 100
        $("input[id*='pm_").val(100);

        var html_checkbox_Terracotta = 
        '<div class="col-xs-12 col-sm-12 edit-view-row-item">'
        + '<div class="col-xs-12 col-sm-2 label" data-label="">'
        + 'Terracotta:</div>'
        + '<div type="bool" colspan="3">'
        + '<input type="checkbox" class="solar_pv_pricing_input" id="Terracotta_checkbox" name="Terracotta_checkbox" value="1" title="" tabindex="0">'                  
        +'</div>'
        +'</div>';
        $('#solar_pv_pricing_table').parent().before(html_checkbox_Terracotta); 
        if( $('#install_address_state_c').val() == "VIC"){
            // Vic Rebate
            var html_checkbox_Vic_Rebate = 
            '<div class="col-xs-12 col-sm-12 edit-view-row-item">'
            + '<div class="col-xs-12 col-sm-2 label" data-label="">'
            + 'VIC Rebate:</div>'
            + '<div class="" type="bool" field="send_sms" colspan="3">'
            + '<input type="checkbox" class="solar_pv_pricing_input" id="Vic_Rebate" name="Vic_Rebate" title="" tabindex="0">'                  
            +'</div>'
            +'</div>';

            // Vic Loan
            var html_checkbox_Loan_Rebate = 
            '<div class="col-xs-12 col-sm-12 edit-view-row-item">'
            + '<div class="col-xs-12 col-sm-2 label" data-label="">'
            + 'Loan Rebate:</div>'
            + '<div class="" type="bool" field="send_sms" colspan="3">'
            + '<input type="checkbox" class="solar_pv_pricing_input" id="Loan_Rebate" name="Loan_Rebate" title="" tabindex="0">'                  
            +'</div>'
            +'</div>';

            $('#solar_pv_pricing_table').parent().before(html_checkbox_Vic_Rebate); 
            $('#solar_pv_pricing_table').parent().before(html_checkbox_Loan_Rebate); 
        }
        //Double Storey Checkbox
        var html_checkbox_Convert_Solar_Opportunity = 
        '<div class="col-xs-12  col-sm-12 label" >Install Year = <span id="installYear">'+installYear+'</span></div>'
        + '<div class="col-xs-6 col-sm-6 edit-view-row-item">'
        + '<div class="col-xs-12 col-sm-2 label" data-label="">'
        + 'Double Storey:</div>'
        + '<div class="" type="bool" field="send_sms" colspan="3">'
        + '<input type="checkbox" class="solar_pv_pricing_input" id="Double_Storey" name="Double_Storey" value="1" title="" tabindex="0">'
        +'</div>'
        + '<div class="col-xs-12 col-sm-2 label" data-label="">Rough in Stage:</div>'
        + '<div class="" type="bool" field="send_sms" colspan="3">'
        + '<input type="checkbox" class="solar_pv_pricing_input" id="Rough_in_Stage" name="Rough_in_Stage"  title="" tabindex="0">'               
        +'</div>'
        +'</div>';

        //add Text state  
        html_checkbox_Convert_Solar_Opportunity += 
        '<div class="col-xs-6 col-sm-6 edit-view-row-item">'
        + '<div class="col-xs-12 col-sm-2 label" data-label="">'
        + 'State:</div>'
        + '<div class="" type="bool" field="send_sms" colspan="3">'
        + '<input disabled type="text" class=" solar_pv_pricing_input table_pricing_state" id="table_pricing_state" name="table_pricing_state" value="'+state+'" title="" tabindex="0">'                  
        +'</div>'
        +'</div>';

        $('#solar_pv_pricing_table').parent().before(html_checkbox_Convert_Solar_Opportunity); 

    }
    ///////////// END MAKE A TABLE //////////////////

    function convert_state(stateBase = ''){
        var primary_address_state = '';
        if(stateBase == ''){
            if($("#install_address_state_c").val() == ''){
                primary_address_state = $("#billing_address_state").val().toUpperCase();
            }else{
                primary_address_state = $("#install_address_state_c").val().toUpperCase();
            }
        }else{
            primary_address_state = stateBase.toUpperCase()
        }
        if(primary_address_state !== '') {
            switch (primary_address_state) {
                case 'VICTORIA':
                    primary_address_state = 'VIC';
                    break;
                case 'QUEENSLAND':
                    primary_address_state = 'QLD';
                    break;
                case 'NEW SOUTH WALES':
                    primary_address_state = 'NSW' ;
                    break;
                case 'AUSTRALIAN CAPITAL TERRITORY':
                    primary_address_state = 'ACT';
                    break;
                case 'SOUTH AUSTRALIA':
                    primary_address_state = 'SA';
                    break;
            }
          
        }
        return primary_address_state;
    }
    
    // .:nhantv:. convert to async function
    function loadJSON(state){
        return $.ajax({
            url: 'index.php?entryPoint=popularSolarBasePrice&state='+state,
            type : 'GET'}).then(function (data) {
                if(data === undefined){
                    localStorage.setItem('basePrice','');
                    return;
                }else{
                    localStorage.setItem('basePrice',JSON.stringify($.parseJSON(data)));
                }
            });
    }

    function getBasePrice(panel_type,inverter_type,total_panel){
        if(panel_type == '')   return;

        dataJSON = JSON.parse(localStorage.basePrice);

        if(dataJSON == '') return;

        switch (panel_type) {
            case 'Sunpower Maxeon 3 400W':
                panel_type = 'Sunpower Maxeon 3 400';
                break;
            case 'Jinko Tiger P-type Mono 370':
                panel_type = 'Jinko 370W Tiger P-type JKM370M-6HLM';
                break;
        }

        var list_panel = dataJSON[panel_type];
        var list_suggest = '';
        var temp = [];
        var check = '';
        loop_parent :
        for (var item in list_panel) {
            var list_item = list_panel[item];
            if(item != 'one_per_panel'){
                var total_base = parseInt(item.split('-')[1].replace("panels","").replace("_","").trim());
            }
            if(inverter_type != '' && total_panel != ''){
                if(item.indexOf(total_panel+' panels') >= 0){
                    for(var i = 0 ; i < list_item.length ; i++){
                        if(list_item[i]['inverter'] == inverter_type){
                            list_suggest = list_item[i]['price'] + ',' + total_panel;
                            break loop_parent;
                        }
                    }
                }else{
                    for(var i = 0 ; i < list_item.length ; i++){
                        if(list_item[i]['inverter'] == inverter_type){
                            if(parseInt(total_panel) < parseInt(total_base)){
                                var one_per_panel = list_panel['one_per_panel'];
                                list_suggest = calc_base_price(total_base,total_panel,one_per_panel,list_item[i]['price']) + ',' +total_panel + '|';
                            }
                            check = total_base;
                        }
                    }
                }
            }else if(inverter_type != '' && total_panel == ''){
                for(var i = 0 ; i < list_item.length ; i++){
                    if(list_item[i]['inverter'] == inverter_type){
                        list_suggest += 0 + ',' +total_base + '|';
                    }
                }
            }

        }
        if(list_suggest == '' && check == ''){
            return '0,0';
        }else if(list_suggest == '' && check != '' ){
            return check;
        }else{
            return list_suggest;
        }
       

    }

    function getSuggestedPanel(panel_type,inverter_type,total_panel,check=false){
        if(panel_type == '')   return;
        
        dataJSON = JSON.parse(localStorage.basePrice);
        
        if(dataJSON == '') return;

        switch (panel_type) {
            case 'Sunpower Maxeon 3 400W':
                panel_type = 'Sunpower Maxeon 3 400';
                break;
            case 'Jinko Tiger P-type Mono 370':
                panel_type = 'Jinko 370W Tiger P-type JKM370M-6HLM';
                break;
        }
        
        var list_panel = dataJSON[panel_type];
        var list_suggest = [];
        for (var item in list_panel) {
            if(item != 'one_per_panel'){
                var total_base = item.split('-')[1].replace("panels","").replace("_","").trim();
            }
            var list_item = list_panel[item];
            if(inverter_type != '' && total_panel == ''){
                for(var i = 0 ; i < list_item.length ; i++){
                    if(list_item[i]['inverter'] == inverter_type){
                        list_suggest.push(total_base);
                    }
                }
            }
        }
        return list_suggest;
    }
    function calc_base_price(total_base,total_panel,one_per_price,price_base){
        if(parseInt(total_base) >= parseInt(total_panel) && parseInt(total_panel) > 0){
            var per_price = (parseInt(total_base) - parseInt(total_panel))*parseInt(one_per_price);
            var price_return = parseInt(price_base) - parseInt(per_price);
        }else{
            var price_return = 0;
        }
        return price_return;
    }

    function clear_option(option){
        $('#Solar-PV-Pricing td:nth-child('+option+')').find('input').val('');
        $('#Solar-PV-Pricing td:nth-child('+option+')').find('select').prop("selectedIndex", 0);
        $(".suggest_total_panel_"+(option-1)).remove();
        $("#sl_option_"+(option-1)).prop('checked',false);
        $("#sl_option_push_"+(option-1)).prop('checked',true);
    }

    function price_extra(BasePrice,ExtrasSubTotal){
        var result = parseFloat(BasePrice) + parseFloat(ExtrasSubTotal);
        return result;
    };

    function total_kw(index){
        var postcode = $("#install_address_postalcode_c").val();
        var json_val = JSON.parse(($("#solar_pv_pricing_input_c").val() != "")?$("#solar_pv_pricing_input_c").val():"{}");
        if(index != '' && index != undefined){
            if($('#panel_type_'+index).val() != '' && $('#panel_type_'+index).val() != null){
                var panel_kw = $('#panel_type_'+index).val().match(/\d+/g);

                if(panel_kw.length >1){
                    panel_kw = panel_kw[1];
                }else{
                    panel_kw = panel_kw[0];
                }
                var default_suggest = [];
                var number_panel = 0;
                // if($('#panel_type_'+index).val() == 'Sunpower Maxeon 3 395'){
                //     default_suggest = getSuggestedPanel('Sunpower Maxeon 3 395',$('#inverter_type_'+index).val(),'',true);
                //     number_panel = Math.max(...default_suggest);
                // }else{
                number_panel = parseInt($('#total_panels_'+index).val());
                // }
               
                var total_kw = panel_kw*number_panel/1000;
                if(!isNaN(total_kw))$('#total_kW_'+index).val(total_kw);
                if(json_val['total_kW_'+(index-1)] == total_kw){
                    calc_total_price(index);
                }else{
                    $.ajax({
                        url: 'index.php?entryPoint=getSTCsNumberForQuotePricing&total_kw='+total_kw+'&postcode='+postcode,
                        type : 'GET',
                        dataType: 'text',
                        success: function (data) {
                            var result = JSON.parse(data);
                            if(result['NumberOfSTCs'] != ''){
                                $("#number_of_stcs_"+index).val(result['NumberOfSTCs']);
                                calc_total_price(index);
                            }
                        }
                    });
                }
            }
        }else{
            for (let i = 1; i < 7; i++) {
                if($('#panel_type_'+i).val() != '' && $('#panel_type_'+i).val() != null){
                    var panel_kw = $('#panel_type_'+i).val().match(/\d+/g);
                    if(panel_kw.length >1){
                        panel_kw = panel_kw[1];
                    }else{
                        panel_kw = panel_kw[0];
                    }
                    var default_suggest = [];
                    var number_panel = 0;
                    // if($('#panel_type_'+index).val() == 'Sunpower Maxeon 3 395'){
                    //     default_suggest = getSuggestedPanel('Sunpower Maxeon 3 395',$('#inverter_type_'+index).val(),'',true);
                    //     number_panel = Math.max(...default_suggest);
                    // }else{
                    number_panel = parseInt($('#total_panels_'+i).val());
                    // }
                    var total_kw = panel_kw*number_panel/1000;
                    if(!isNaN(total_kw))$('#total_kW_'+i).val(total_kw);
                    if(json_val['total_kW_'+i] == total_kw && !isNaN(parseInt($('#stc_value_'+i).val()))){
                        calc_total_price();
                    }else{
                        $.ajax({
                            url: 'index.php?entryPoint=getSTCsNumberForQuotePricing&total_kw='+total_kw+'&postcode='+postcode,
                            type : 'GET',
                            dataType: 'text',
                            success: function (data) {
                                var result = JSON.parse(data);
                                if(result['NumberOfSTCs'] != ''){
                                    $("#number_of_stcs_"+i).val(result['NumberOfSTCs']);
                                    calc_total_price();
                                }
                            }
                        });
                    }
                }
            }
        }
    }

    function price_kw() {
        for (let i = 1; i < 7; i++) {
            var total_kw = parseFloat($('#total_kW_'+i).val());
            var customer_price = parseFloat($('#customer_price_'+i).val());
            var price_kw = (customer_price/(total_kw*1000)).toFixed(2);
            if(!isNaN(price_kw))$('#price_kw_'+i).val(price_kw);
        }
    }

    function calc_total_price(index){
        for (let i = 1; i < 7; i++) {
            if(index !='' && index != undefined  && parseInt(index) != i) {
                continue;
            }
            if($("#suggest_price_"+i).val() == '') continue;
            var stc_value = parseInt($("#number_of_stcs_"+i).val())*35;
            $("#stc_value_"+i).val(stc_value);
            var inc_per = 0;
            
            switch (convert_state()) {
                case 'VIC':case 'NSW':
                    inc_per = 0.055;
                    break;
                case 'WA':
                    inc_per = 0.05;
                    break;
                case 'QLD':case 'ACT':
                    inc_per = 0.053;
                    break;
                case 'SA':
                    inc_per = 0.054;
                    break;
            }
            // var pe_increase = ((parseInt($("#suggest_price_"+i).val()) + stc_value)*inc_per);
            // $("#pe_increase_"+i).val(pe_increase.toFixed(3));
            
            var total_price =  parseInt($("#suggest_price_"+i).val());// + parseInt($("#pe_increase_"+i).val());
            
            if(total_price != 0){
                $("#total_price_"+i).val(total_price.toString().substring(0,total_price.toString().length-2)+90);
            }

            var gross_price = parseInt(stc_value + parseInt($("#suggest_price_"+i).val()));
            $("#sgp_system_price_"+i).val(gross_price);

            copySuggestedPrice();
        }
    }

    function save_values(){
        var values = {};
        $("#Solar-PV-Pricing .solar_pv_pricing_input").each(function (){
            var id_name = $(this).attr("id");
            if($("#"+id_name).attr('type')== 'checkbox'){
                values[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
            } else {
                values[id_name] = $(this).val();
            }
        });
        values['Double_Storey']  = ($("#Double_Storey").is(":checked") == true) ? 1 : 0;
        values['Rough_in_Stage']  = ($("#Rough_in_Stage").is(":checked") == true) ? 1 : 0;
        values['Terracotta_checkbox']  = ($("#Terracotta_checkbox").is(":checked") == true) ? 1 : 0;
        values['Vic_Rebate']  = ($("#Vic_Rebate").is(":checked") == true) ? 1 : 0;
        values['Loan_Rebate']  = ($("#Loan_Rebate").is(":checked") == true) ? 1 : 0;

        $("#solar_pv_pricing_input_c").val(JSON.stringify(values));
    }

    async function loadOptionPricing(){
        if($("#solar_pv_pricing_input_c")!=""){
            var json_val = JSON.parse(($("#solar_pv_pricing_input_c").val() != "")?$("#solar_pv_pricing_input_c").val():"{}");
            // .:nhantv:. FOR block
            const promise = new Promise((resolve, reject) => { 
                setTimeout(()=>{
                    for (let key in json_val) {
                        if($("#"+key).attr('type') == 'checkbox'){
                            $("#"+key).prop( "checked", json_val[key] );
                            if(key == 'Vic_Rebate' && json_val[key]){
                                var vic_html = '<tr>'
                                +'<td>Vic Rebate</td>'
                                +'<td><input class="vic_rebate_1 solar_pv_pricing_input" id="vic_rebate_1" value="-1850" disabled=""></td>'
                                +'<td><input class="vic_rebate_2 solar_pv_pricing_input" id="vic_rebate_2" value="-1850" disabled=""></td>'
                                +'<td><input class="vic_rebate_3 solar_pv_pricing_input" id="vic_rebate_3" value="-1850" disabled=""></td>'
                                +'<td><input class="vic_rebate_4 solar_pv_pricing_input" id="vic_rebate_4" value="-1850" disabled=""></td>'
                                +'<td><input class="vic_rebate_5 solar_pv_pricing_input" id="vic_rebate_5" value="-1850" disabled=""></td>'
                                +'<td><input class="vic_rebate_6 solar_pv_pricing_input" id="vic_rebate_6" value="-1850" disabled=""></td>'
                                +'</tr>';
                                $('#Solar-PV-Pricing tbody').append(vic_html)
                            }else if(key == 'Loan_Rebate' && json_val[key]){
                                var loan_html = '<tr>'
                                +'<td>Loan Rebate</td>'
                                +'<td><input class="loan_rebate_1 solar_pv_pricing_input" id="loan_rebate_1" value="-1850" disabled=""></td>'
                                +'<td><input class="loan_rebate_2 solar_pv_pricing_input" id="loan_rebate_2" value="-1850" disabled=""></td>'
                                +'<td><input class="loan_rebate_3 solar_pv_pricing_input" id="loan_rebate_3" value="-1850" disabled=""></td>'
                                +'<td><input class="loan_rebate_4 solar_pv_pricing_input" id="loan_rebate_4" value="-1850" disabled=""></td>'
                                +'<td><input class="loan_rebate_5 solar_pv_pricing_input" id="loan_rebate_5" value="-1850" disabled=""></td>'
                                +'<td><input class="loan_rebate_6 solar_pv_pricing_input" id="loan_rebate_6" value="-1850" disabled=""></td>'
                                +'</tr>';
                                $('#Solar-PV-Pricing tbody').append(loan_html)
                            }
                        } else {
                            let value_field = json_val[key];
                            if(value_field !== undefined && value_field != ''){
                                const regex = /S Edge [\d]{1,2}$/;
                                let m;
                                if(m = regex.exec(value_field) !== null){
                                    value_field = value_field+'G';
                                    $("#"+key).val(value_field);
                                }else{
                                    $("#"+key).val(value_field);
                                }
                            }else{
                                $("#"+key).val(value_field);
                            }
                        }
                    }
                    resolve();
                }, 500);
            });
            // .:nhantv:. Tracking FOR block success and trigger max panel button
            await promise.then(()=>{
                $('#max_panels').trigger('click');
            });
        }
    }

    function copySuggestedPrice() {
        var count_Stc = $('#suggest_price_1').parent().parent().find('td').length;
        for(var i = 1 ; i < count_Stc; i++) {
            if($("#base_price_"+i).val() != '' || !isNaN(parseInt($("#base_price_"+i).val()))){
                var customer_price_extra = parseInt($('#suggest_price_'+i).val());// + parseInt($("#pe_increase_"+i).val());
                var customer_price = customer_price_extra.toString().substring(0,customer_price_extra.toString().length-2)+90
                $('#customer_price_'+i).val((customer_price != 'N90') ? customer_price : '' );
            }
        }
        price_kw();    
    }
    
    function getSTCsFromSolargain () {
        $('#get_STCs_SG span.glyphicon-refresh').removeClass('hidden');
        $(".sg_STCs_get").remove();
        $(".sg_per_STCs").remove();
        $(".sg_STCs_price").remove();
        var quote_stc = $('#solargain_quote_number_c').val();
        var get_stc = "?entryPoint=customGetSTCFromSG";
            get_stc += "&quoteSG_ID="+quote_stc;
            get_stc += "&state="+ $('#table_pricing_state').val();;
        if( quote_stc != ''){
            $.ajax({
                url: get_stc,
                type : 'GET',
                dataType : 'json',
                
                success: function (data) {
                    $('#Solar-PV-Pricing tbody').after(data.stc_price);
                    $('#Solar-PV-Pricing tbody').after(data.per_stc);
                    $('#Solar-PV-Pricing tbody').after(data.html1);
                    $('#get_STCs_SG span.glyphicon-refresh').addClass('hidden');
                },
            })
        }else{
            alert("Solargain Quote Number is required!");
            $('#get_STCs_SG span.glyphicon-refresh').addClass('hidden');
        }
        
    }

    function calculation(type,input1,state,data){
        if(state == "") return 0;
        var data_return ;
        switch(type){
            case 1 :
                if(input1 > 0)
                    data_return = data[state]['base'];
                else data_return = 0;
                break;
            case 2 :
                data_return = data[state]['per_panel']*input1;
                break;
            case 3 :
                data_return =  data[state]['base'] + data[state]['per_panel']*input1;
                break;
            case 4 :
                data_return =  8.25 * input1;
                break;
            case 5 :
                if(input1 == 'Yes'){
                    data_return = 165;
                }else{
                    data_return = 0;
                }
                break;
            case 6 :
                if(input1 > 50){
                    data_return = (input1-50)*2;
                }else{
                    data_return =0;
                }
                break;
            case 7 :
                if(input1 > 2){
                    data_return = (input1-2)*150;
                }else{
                    data_return = 0;
                }
                break;
            case 8 :
                data_return =  8.25 * input1;
                break;
            case 9 :
                if(input1 == 'Yes'){
                    data_return = 220;
                }else{
                    data_return = 0;
                }
                break;
            case 10 :
            case 11 :
            case 12 :
                if(input1 == 'Fro. Smart Meter (1P)'){
                    data_return = 300;
                }
                else if(input1 == 'Fro. Smart Meter (3P)'){
                    data_return = 500;
                }
                else if(input1 == 'Switchboard UPG'){
                    data_return = 900;
                }
                else if(input1 == 'ENPHS Envoy-S Met.'){
                    data_return = 300;
                }
                else if(input1 == 'SE Smart Meter'){
                    data_return = 0;
                }
                else if(input1 == 'SE Wifi'){
                    data_return = 0;
                }
                else if(input1 == 'Fronius Service Partner Plus 10YR Warranty'){
                    data_return = 100;
                }
                else if(input1 == 'Sungrow Smart Meter (1P)'){
                    data_return = 300;
                }
                else if(input1 == 'Sungrow Three Phase Smart Meter DTSU666'){//'Sungrow Smart Meter (3P)'){
                    data_return = 400;
                }
                break;
            case 13 :
                if(state == 'VIC') {
                    if(input1 != '' && input1 > 0){
                        return input1;
                    }else{
                        data_return = 0;
                    }
                }else{
                    data_return = 0;
                }   
                break; 
            case 14 :
                if(state == 'VIC') {
                    if(input1 != '' && input1 > 0){
                        return input1;
                    }else{
                        data_return = 1000;
                    }
                }else{
                    data_return = 0;
                }
                break;
            default : break;
        }
        return parseFloat(data_return);
    }

    function Extras_Sub_Total (DoublestoreyExtraCalculation, LandscapePanels , RakedCeiling, Travel, GroupsofPanels, Terracotta, SteepRoof, Extra_1, Extra_2, Extra_3,Tilting,Extra_VIC_1,Extra_VIC_2,Solar_vic_rebate){
        var result = parseFloat(!isNaN(DoublestoreyExtraCalculation)?DoublestoreyExtraCalculation:0) + parseFloat(!isNaN(LandscapePanels)?LandscapePanels:0) + 
        parseFloat(!isNaN(RakedCeiling)?RakedCeiling:0) + parseFloat(!isNaN(Travel)?Travel:0)+ parseFloat(!isNaN(GroupsofPanels)?GroupsofPanels:0)
        + parseFloat(!isNaN(Terracotta)?Terracotta:0) + parseFloat(!isNaN(SteepRoof)?SteepRoof:0) 
        + parseFloat(!isNaN(Extra_VIC_1)?Extra_VIC_1:0) + parseFloat(!isNaN(Extra_VIC_2)?Extra_VIC_2:0)
        + parseFloat(!isNaN(Extra_1)?Extra_1:0) + parseFloat(!isNaN(Extra_2)?Extra_2:0) + parseFloat(!isNaN(Extra_3)?Extra_3:0) + parseFloat(!isNaN(Tilting)?Tilting:0)
        + parseFloat(!isNaN(Solar_vic_rebate)?Solar_vic_rebate:0)
        return parseFloat(result).toFixed(2);
    }

    function display_button_price(){
        if($("#solargain_quote_number_c").val() == "" && $("#solargain_tesla_quote_number_c").val() == ""  ){
            
            $('table[id="Solar-PV-Pricing"]').after(
            '&nbsp;<br> <button type="button" id="convertToQuotesSolarGain_position_price" class="button convertToQuotesSolarGain" title="Convert To Solargain Quote" onClick="SUGAR.addAllEventPushSGButton(this);" > Push To SG <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            );

        }else{

            $('#calculatePrice').after('<br><button type="button"  class="button primary" id="getSGPrice_table_price"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get SG Price </button><div class="clearfix"></div>');

            $('#getSGPrice_table_price').after(
            '<br><button type="button" id="updateToQuotesSolarGain_position_price"  class="button updateToQuotesSolarGain" title="Update Price To Solargain Quote" onClick="SUGAR.updateQuotePriceToSolargain(this);" > Update Price To Solargain Quote <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            );

            $('#updateToQuotesSolarGain_position_price').after(
                '<br><button type="button" id="get_STCs_SG"  class="button get_STC_QuotesSolarGain" title="Get STC From Solargain Quote" onClick="getSTCsFromSolargain();" > GET PV STC <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            );

            if($("#solargain_quote_number_c").val() !== ''){
                $('#solar_pv_pricing_table').append('</br><a id="link_solargain_quote" target="_blank" href="https://crm.solargain.com.au/quote/edit/'+$("#solargain_quote_number_c").val() +'">Solargain Quote Link</a>');
            }else {
                $('#solar_pv_pricing_table').append('</br><a id="link_solargain_quote" target="_blank" href="https://crm.solargain.com.au/quote/edit/'+$("#solargain_tesla_quote_number_c").val() +'">Solargain Quote Link</a>');
            }
          
        }
    }

    function calculation_for_option(option){
        var storey_charge =  {
            "WA":{'base':285.7,'per_panel':14.29,'per_panel1':0,'per_panel2':0},
            "ACT":{'base':171.43,'per_panel':0,'per_panel1':0,'per_panel2':0},
            "SA":{'base':285.7,'per_panel':14.29,'per_panel1':0,'per_panel2':0},
            "QLD":{'base':257.1,'per_panel':0,'per_panel1':0,'per_panel2':0},
            "VIC":{'base':500,'per_panel':0,'per_panel1':0,'per_panel2':0},
            "NSW":{'base':300,'per_panel':0,'per_panel1':0,'per_panel2':0},
        };
        
        var state = convert_state();
        var number_double_storey_panel_1 = parseFloat(($('#number_double_storey_panel_'+option).val() != "")?$('#number_double_storey_panel_'+option).val():0);
        var Double_Storey_Extra_Fixed = calculation(1, number_double_storey_panel_1, state, storey_charge);
        var Double_Storey_Extra_variable = calculation(2, number_double_storey_panel_1, state, storey_charge);
        var DoublestoreyExtraCalculation = Double_Storey_Extra_variable + Double_Storey_Extra_Fixed;
        var LandscapePanels = 0;
        var RakedCeiling = calculation(5,$('#raked_ceiling_'+option).val(),state,storey_charge);
        var travel_km_1 = parseFloat(($('#travel_km_'+option).val() != "")?$('#travel_km_'+option).val():0);
        var Travel = calculation(6, travel_km_1, state, storey_charge);
        var groups_of_panels_1 = parseFloat(($('#groups_of_panels_'+option).val() != "")?$('#groups_of_panels_'+option).val():0);
        var GroupsofPanels = calculation(7,groups_of_panels_1,state,storey_charge);
        var Terracotta = 0;
        var SteepRoof =  calculation(9,$('#steep_roof_'+option).val(),state,storey_charge);
        var Solar_vic_rebate = 0;
        var Tilting = parseInt($("#tilting_"+option).val())*35;
        var Extra_1 = calculation(10,$('#extra_1_'+option).val(),state,storey_charge);
        var Extra_2 = calculation(11,$('#extra_2_'+option).val(),state,storey_charge);
        var Extra_3 = calculation(12,$('#extra_3_'+option).val(),state,storey_charge);
        var Extra_VIC_1 = 0;
        var Extra_VIC_2 = 0;
        var landscape_panel_deg_1 = parseFloat(($('#landscape_panel_deg_'+option).val() != "")?$('#landscape_panel_deg_'+option).val():0);
        var m_terracotta = parseFloat(($('#terracotta_'+option).val() != "")?$('#terracotta_'+option).val():0);
        
        LandscapePanels = calculation(8,landscape_panel_deg_1,state,storey_charge);
        var Terracotta = calculation(8, m_terracotta, state, storey_charge);

        var ExtrasSubTotal=  Extras_Sub_Total(DoublestoreyExtraCalculation, LandscapePanels , RakedCeiling, Travel, GroupsofPanels, Terracotta, SteepRoof, Extra_1, Extra_2, Extra_3, Tilting ,Extra_VIC_1 ,Extra_VIC_2 ,Solar_vic_rebate);
        var BasePrice = ($('#base_price_'+option).val()!="")?$('#base_price_'+option).val():0;
        BasePrice = parseFloat(BasePrice);
        
        if(BasePrice != 0){
            $("#extras_sub_total_"+option).val(ExtrasSubTotal);
            var total_price_extra = price_extra(BasePrice,ExtrasSubTotal);// + parseInt($("#pe_increase_"+option).val());
            // var total_price = total_price_extra.toString().substring(0,total_price_extra.toString().length-2)+90;
            // $("#total_price_"+option).val((total_price != 'N90') ? total_price : '');
            $("#table_pricing_state").val(state);
            $("#suggest_price_"+option).val(price_extra(BasePrice,ExtrasSubTotal));
        }else{
            $("#total_price_"+option).val('');
        }
    }

    function action_changed(index){
        $("#suggest_total_panel_"+index).remove();
        var panel_type = $("#panel_type_"+index).val();
        var inverter_type = $("#inverter_type_"+index).val();
        var total_panels = $("#total_panels_"+index).val();

        if(this.state == '') return;

        if(panel_type != '' && inverter_type != ''){
            var base_price = '';
            var error_price = '';
            if(inverter_type == 'IQ7 plus') inverter_type = 'IQ7+';
            var data_return = getBasePrice(panel_type,inverter_type,total_panels);
            // console.log(data_return);
            if(data_return === undefined) return;

            data_return = data_return.toString().split('|').filter(function(e){return e});

            $(".suggest_total_panel_"+index).remove();
            for(var i = 0 ; i < data_return.length ; i++){
                var data_arr = data_return[i].split(',');
                if(data_arr.length > 1 && data_arr[0] != 0 && data_arr[1] != 0){
                    base_price = data_arr[0];
                    if(Number($("#pm_"+index).val()) != "NaN" && base_price != 0){
                        base_price = Number(base_price) + Number($("#pm_"+index).val().trim());
                    }
                    $("#total_panels_"+index).val(data_return[0].split(',')[1]);
                    $("#base_price_"+index).val(base_price);
                    break;
                }else if(data_arr.length > 1 && data_arr[0] == 0 && data_arr[1] != 0){
                    $("#total_panels_"+index).val('');
                    $("#base_price_"+index).val('');
                    $("#inverter_type_"+index).after('<div class="suggest_total_panel_'+index+'" id="suggest_total_panel_'+index+'_'+i+'">0 - '+data_arr[1]+' panels</div>');
                }else if(data_arr.length == 1  && data_arr[0] != 0){
                    $("#total_panels_"+index).val('');
                    $("#base_price_"+index).val('');
                    $("#inverter_type_"+index).after('<div class="suggest_total_panel_'+index+'" id="suggest_total_panel_'+index+'_'+i+'">0 - '+data_arr[0]+' panels</div>');
                    alert("You have exceeded the maximum number of panels for that panel type.");
                    break;
                }else{
                    $("#total_panels_"+index).val('');
                    $("#base_price_"+index).val('');
                    alert("The inverter type not match for that panel type.");
                    break;
                }
            }
            if(panel_type != '' && inverter_type != '' &&  total_panels != ''){
                calculation_for_option(index);
                total_kw(index);
                save_values();
                getOwnSolarPricing($("#solar_pv_pricing_input_c").val(), 'solar_changed');
            }
            
        }
    }

    function action_changed_extra(index){
        if($("#inverter_type_"+index).val().toLowerCase().indexOf('primo ') >= 0 ){
            if($("#meter_phase_c").val() == '1'){
                $("#extra_1_"+index).val('Fro. Smart Meter (1P)'); 
            }else if($("#meter_phase_c").val() == '3'){
                $("#extra_1_"+index).val('Fro. Smart Meter (3P)');
            }
            // $("#extra_2_"+index).val('Fronius Service Partner Plus 10YR Warranty');
            $("#extra_2_"+index).val('');
        }else if( $("#inverter_type_"+index).val().toLowerCase().indexOf('symo ') >= 0){
            $("#extra_1_"+index).val('Fro. Smart Meter (3P)');
            // $("#extra_2_"+index).val('Fronius Service Partner Plus 10YR Warranty');
            $("#extra_2_"+index).val('');
        }
        else if($("#inverter_type_"+index).val().toLowerCase().indexOf('s edge ') >= 0){
            $("#extra_1_"+index).val('SE Wifi');
            $("#extra_2_"+index).val('SE Smart Meter');
        }else if($("#inverter_type_"+index).val().toLowerCase().indexOf('sungrow ') >= 0){
            if($("#phases").val() == 'Three Phases'  ){
                $("#extra_1_"+index).val('Sungrow Three Phase Smart Meter DTSU666');//'Sungrow Smart Meter (3P)');
                $("#extra_2_"+index).val('');
            }else if($("#phases").val() == 'Two Phases'){

            }else {
                if( $("#inverter_type_"+index).val().indexOf('3P') >= 0){
                    $("#extra_1_"+index).val('Sungrow Three Phase Smart Meter DTSU666');//'Sungrow Smart Meter (3P)');
                    $("#extra_2_"+index).val('');
                }else {
                    $("#extra_1_"+index).val('Sungrow Smart Meter (1P)');
                    $("#extra_2_"+index).val('');
                }   
            }
        }else{
            $("#extra_1_"+index).val('');
            $("#extra_2_"+index).val(''); 
        }
    }

     //FUNCTION UPDATE PRICING
     SUGAR.updateQuotePriceToSolargain = function(elem) {
        alert('Please Update SG Quote Info first if you have some other changes relate with Quote Info');
        $("#calculatePrice").trigger("click");
        $('#updateToQuotesSolarGain_position_price span.glyphicon-refresh').removeClass('hidden');

        if($("#solargain_lead_number_c").val() == ''){
            alert('Please fill up Lead on SolarGain before clicking this button.');
            ("#solargain_lead_number_c").focus();
            $('#updateToQuotesSolarGain_position_price span.glyphicon-refresh').addClass('hidden');
            return;
        }
        
        if($("#solargain_quote_number_c").val() == ''){
            alert('Please create a Quote on SolarGain before clicking this button.');
            ("#solargain_quote_number_c").focus();
            $('#updateToQuotesSolarGain_position_price span.glyphicon-refresh').addClass('hidden');
            return;
        }
        
        if($("#billing_address_state").val() == '' && $("#install_address_state_c").val()){
            alert('Please Enter State Field before clicking this button.');
            $("#billing_address_state").focus();
            $('#updateToQuotesSolarGain_position_price span.glyphicon-refresh').addClass('hidden');
            return;
        }
        var build_url_quote_price = "?entryPoint=customUpdateQuotePriceToSolarGain";
        build_url_quote_price += '&process=quote';
        build_url_quote_price += '&record='+ encodeURIComponent($('input[name="record"]').val());
        build_url_quote_price += "&leadSG_ID="+encodeURIComponent($("#solargain_lead_number_c").val());
        build_url_quote_price += "&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
        
        if($("#install_address_state_c").val() == ''){
            build_url_quote_price += '&state='+ encodeURIComponent($("#billing_address_state").val());
        }else{
            build_url_quote_price += '&state='+ encodeURIComponent($("#install_address_state_c").val());
        }
        var option_models =    {
                                'Jinko Tiger P-type Mono 370': '195',
                                // 'Jinko 370W Cheetah Plus JKM370M-66H' : '171',
                                //'Longi Hi-MO X 350W':'162',
                                // 'Q CELLS Q.MAXX 330W':'156',
                                'Q CELLS Q.MAXX-G3 385W': '202',
                                // 'Q CELLS Q.PEAK DUO G6+ 350W':'173',
                                // 'Sunpower Maxeon 2 350':'144',
                                // 'Sunpower Maxeon 3 395':'167',
                                // 'Sunpower X22 360W':'110',
                                'Sunpower P3 370 BLACK':'193',
                                'Sunpower Maxeon 3 400W':'145',
                                // 'Sunpower P3 325 BLACK':'174',                                
                                }

        var option_inverters = {'Primo 3':'274',
                                'Primo 4':'275',
                                'Primo 5':'269',
                                'Primo 6':'277',
                                'Primo 8.2':'278',
                                'Symo 5':'273',
                                'Symo 6':'282',
                                'Symo 8.2':'284',
                                'Symo 10':'285',
                                'Symo 15':'287',
                                'SYMO 20':'289',
                                'S Edge 3G':'292',
                                'S Edge 5G':'292',
                                'S Edge 6G':'292',
                                'S Edge 8G':'292',
                                'S Edge 8 3P':'292',
                                'S Edge 10G':'292',
                                'IQ7 plus':'201',
                                //'IQ7':'200',
                                'IQ7X':'229',
                                'SolarEdge with P500':'168',
                                'SolarEdge with P401':'292',
                                'SolarEdge with P370':'203',
                                //'Growatt 3':'233',
                                // 'Growatt 5':'213',
                                // 'Growatt 6':'230',
                                // 'Growatt 8.2':'247',
                                'Sungrow 3':'223',
                                'Sungrow 5':'259',
                                'Sungrow 8':'257',
                                'Sungrow 10 3P':'226',
                                'Sungrow 15 3P':'241',
                            };
        var option_extras = {   'Fro. Smart Meter (1P)':'1',
                                'Fro. Smart Meter (3P)':'2',
                                'Fronius Service Partner Plus 10YR Warranty':'387',
                                'Switchboard UPG':'',
                                'ENPHS Envoy-S Met.':'13',
                                'SE Smart Meter':'22',
                                'SE Wifi': '17',
                                'Sungrow Smart Meter (1P)': '413',
                                // 'Sungrow Smart Meter (3P)': '414'
                                'Sungrow Three Phase Smart Meter DTSU666' : '524'
                                };
        var option_battery = {  "LG Chem RESU 10H SolarEdge & Fronius":'40',};
        var j = 0;
        for(var i = 1; i<=6 ;i++){
            var option_panel_type = $("#panel_type_"+i).val().trim();
            var option_inverter_type = $("#inverter_type_"+i).val().trim();
            var option_total_panels = $("#total_panels_"+i).val().trim();
            var option_battery_type = $("#battery_"+i).val();
            var travel_km  = $("#travel_km_"+i).val().trim();
            var price_option = $("#customer_price_"+i).val().trim();

            if(price_option != ''){
                price_option = price_option;
            }else{
                price_option = $("#suggest_price_"+i).val().trim();
            }
           
            var number_double_storey_panel = ($("#number_double_storey_panel_"+i).val().trim() == '') ? 0 : $("#number_double_storey_panel_"+i).val().trim();
            var groups_of_panels  = $("#groups_of_panels_"+i).val().trim();
            var additional ="";
            var groups="";
            var option_extra_1 = option_extras[$("#extra_1_"+i).val()];
            var option_extra_2 = option_extras[$("#extra_2_"+i).val()];
            var option_extra_3 = option_extras[$("#extra_3_"+i).val()];
            if($("#base_price_"+i).val() !=''){
                build_url_quote_price += '&number_double_storey_panel_'+ j +'='+ encodeURIComponent(parseInt(number_double_storey_panel));
                build_url_quote_price += '&price_option_'+ j +'='+ encodeURIComponent(price_option);
                build_url_quote_price += '&option_model_'+ j +'='+ encodeURIComponent(option_models[option_panel_type])
                
                //if( (option_panel_type == 'Sunpower Maxeon 2 350' || option_panel_type == 'Sunpower P3 325 BLACK') && (option_inverter_type == 'S Edge 3' || option_inverter_type == 'S Edge 5' || option_inverter_type == 'S Edge 6' || option_inverter_type == 'S Edge 8' || option_inverter_type == 'S Edge 8 3P'|| option_inverter_type == 'S Edge 10')){
                if(option_panel_type == 'Sunpower Maxeon 3 400W' && (option_inverter_type == 'S Edge 3G' || option_inverter_type == 'S Edge 5G' || option_inverter_type == 'S Edge 6G' || option_inverter_type == 'S Edge 8G' || option_inverter_type == 'S Edge 8 3P' || option_inverter_type == 'S Edge 10G') ){
                    build_url_quote_price += '&option_inverter_'+ j +'='+ encodeURIComponent(option_inverters['SolarEdge with P500']);
                }else{
                    build_url_quote_price += '&option_inverter_'+ j +'='+ encodeURIComponent(option_inverters[option_inverter_type]);
                }

                //code for battery
                build_url_quote_price += '&option_battery_'+ j +'='+ ((option_battery_type != '') ? encodeURIComponent(option_battery[option_battery_type]) : 0);

                build_url_quote_price += '&option_total_panel_'+ j +'='+ encodeURIComponent(option_total_panels);
                build_url_quote_price += '&option_extra_1_'+j +'='+ encodeURIComponent(option_extra_1);
                build_url_quote_price += '&option_extra_2_'+j +'='+ encodeURIComponent(option_extra_2);
                build_url_quote_price += '&option_extra_3_'+j +'='+ encodeURIComponent(option_extra_3);
                build_url_quote_price += '&option_inverter_type_name_'+ j +'='+ encodeURIComponent(option_inverter_type);
                var tilting_frame = $("#tilting_"+i).val().trim();
                if(tilting_frame != ''){
                    build_url_quote_price += '&option_tilting_'+ j +'='+parseInt(tilting_frame);
                }else{
                    build_url_quote_price += '&option_tilting_'+ j +'=0';
                }
                if(travel_km == ''){
                    travel_km = 0;
                }else if(parseInt(travel_km) > 50) {
                    travel_km = parseInt(travel_km) - 50;
                } else{
                    travel_km = 0;
                }
                if(number_double_storey_panel == "" ){
                    additional = 0 ;
                }else if(parseInt(number_double_storey_panel) > 0 ){
                    additional = 1;
                }else {
                    additional = 0;
                }
                if(parseInt(groups_of_panels) > 2){
                    groups = parseInt(groups_of_panels) - 2;
                }else{
                    groups = 0;
                }
                build_url_quote_price +=  '&splits_'+ j + '='+ encodeURIComponent(parseInt(groups));
                build_url_quote_price += '&travel_km_'+ j +'='+ encodeURIComponent(parseInt(travel_km));
                build_url_quote_price += '&additional_'+ j + '='+additional;
                j++;
            }
            
            if($('#sl_option_push_'+i).prop('checked') == true){
                build_url_quote_price += "&sl_option_"+(i-1)+"=yes";
            }else{
                build_url_quote_price += "&sl_option_"+(i-1)+"=no";
            }
           
        }
        //logic Push missing option
        build_url_quote_price += "&number_of_option="+ j;

        if($('#Vic_Rebate').prop('checked') == true){
            build_url_quote_price += "&vicRebate=yes";
        }else{
            build_url_quote_price += "&vicRebate=no";
        }

        if( $('#Loan_Rebate').prop('checked') == true ){
            build_url_quote_price += "&loanRebate=yes";
        }else {
            build_url_quote_price += "&loanRebate=no";
        }

        $.ajax({
            url: build_url_quote_price,
            type : 'POST',
            success: function (data) {
                
                if(data != ''){
                    alert(data);
                }else{
                    alert("Update Pricing Successfully.")
                }
                $('#updateToQuotesSolarGain_position_price span.glyphicon-refresh').addClass('hidden');
            },
        });
    }
    //end

    YAHOO.util.Event.addListener(["pe_pricing_options_id_c"], "change", function(){
        loadPricingOptions();
    });

    function loadPricingOptions(option_pricing_id){
        var json_val = '';
        if($("#pe_pricing_options_id_c").val() != ''){
            $("#link_pricing_option").remove();
            $("#pricing_option_type_c").parent().append('<p id="link_pricing_option"><a href="/index.php?module=pe_pricing_options&action=EditView&record='+$("#pe_pricing_options_id_c").val()+'" target="_blank">Open Pricing Option</a></p>');
        }else{
            $("#link_pricing_option").remove();
        }
        var url = '';
        if(option_pricing_id != '' && option_pricing_id != undefined){
            url = "index.php?entryPoint=loadPricingOption&id="+option_pricing_id;
        }else{
            url = 'index.php?entryPoint=loadPricingOption&id='+$("#pe_pricing_options_id_c").val();
        }
        $.ajax({
            url: url,
            type : 'POST',
            async: false,
            success: function (data) {
                if(data != ''){
                    json_val = JSON.parse($("<div />").html(data).text());
                }else{
                    json_val = JSON.parse(($("#pricing_option_input_c").val() != "")?$("#pricing_option_input_c").val():"{}");
                }
                //debugger
                for(var i = 1 ; i <= 7 ; i++){
                    clear_option(i);
                }
                for (let key in json_val) {
                    if($("#"+key).attr('type') == 'checkbox'){
                        $("#"+key).prop( "checked", json_val[key] );
                    } else {
                        let value_field = json_val[key];
                        if(value_field !== undefined && value_field != ''){
                            const regex = /S Edge [\d]{1,2}$/;
                            let m;
                            if(m = regex.exec(value_field) !== null){
                                value_field = value_field+'G';
                                $("#"+key).val(value_field);
                            }else{
                                $("#"+key).val(value_field);
                            }
                        }else{
                            $("#"+key).val(value_field);
                        }
                    }
                }
                $("input[id*='pm_").val(100);
                for(i = 1 ;i < 7;i++){
                    if($("#panel_type_"+i).val() !='' && $("#inverter_type_"+i).val() != '' && $("#total_panels_"+i).val() != ''){
                        action_changed(i);
                        action_changed_extra(i);
                    }
                }
                $("#calculatePrice").trigger("click");
            }
        }); 
    }
///// END FUNCTION /////