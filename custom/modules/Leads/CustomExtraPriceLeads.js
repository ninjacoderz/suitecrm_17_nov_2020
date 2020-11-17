$(function () {
   
    'use strict';
    // Generate uinique id
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

    $( document ).ready(function() {
        return;
        var panel_type = ['','Jinko 315W Cheetah Mono PERC','Q CELLS Q.PEAK DUO 325W','Sunpower E327W','Jinko 275W','LG NeON R 360W','LG NeON R 350W'];
        var inverter_type = ['','Primo 5','Primo 6','Primo 8.2','Symo 5','Symo 6','Symo 10','S Edge 5','S Edge 6','S Edge 10','ENP IQ7 plus','ENPS230','ENP IQ7'];
        var extra_1 = ["Fro. Smart Meter (1P)","Fro. Smart Meter (3P)", "Switchboard UPG", "ENPHS Envoy-S Met.", "SE Smart Meter", "SE Wifi", "Switchboard UPG", "ENPHS Envoy-S Met."];
        var extra_2 = ["","Fro. Smart Meter (1P)","Fro. Smart Meter (3P)", "Switchboard UPG", "ENPHS Envoy-S Met.", "SE Smart Meter", "SE Wifi", "Switchboard UPG", "ENPHS Envoy-S Met."];
        var extra_3 = ["","Fro. Smart Meter (1P)","Fro. Smart Meter (3P)", "Switchboard UPG", "ENPHS Envoy-S Met.", "SE Smart Meter", "SE Wifi", "Switchboard UPG", "ENPHS Envoy-S Met."];
        var bool_val = ["No", "Yes"];
        var data = [["Option", "1", "2", "3", "4", "5", "6"], //headers
                    ["PM", makeInputBox("pm_1 solar_pv_pricing_input","pm_1"), makeInputBox("pm_2 solar_pv_pricing_input", "pm_2"), makeInputBox("pm_3 solar_pv_pricing_input", "pm_3"), makeInputBox("pm_4 solar_pv_pricing_input", "pm_4"), makeInputBox("pm_5 solar_pv_pricing_input", "pm_5"), makeInputBox("pm_6 solar_pv_pricing_input", "pm_6")],
                    ["Total kW:", makeInputBox("total_kW_1 solar_pv_pricing_input", "total_kW_1", true), makeInputBox("total_kW_2 solar_pv_pricing_input", "total_kW_2", true), makeInputBox("total_kW_3 solar_pv_pricing_input", "total_kW_3",true), makeInputBox("total_kW_4 solar_pv_pricing_input", "total_kW_4",true), makeInputBox("total_kW_5 solar_pv_pricing_input", "total_kW_5",true), makeInputBox("total_kW_6 solar_pv_pricing_input", "total_kW_6",true)],
                    ["Panel Type", makeSelectBox(panel_type,"panel_type_1 solar_pv_pricing_input", "panel_type_1"), makeSelectBox(panel_type,"panel_type_2 solar_pv_pricing_input", "panel_type_2"), makeSelectBox(panel_type,"panel_type_3 solar_pv_pricing_input", "panel_type_3"), makeSelectBox(panel_type,"panel_type_4 solar_pv_pricing_input", "panel_type_4"), makeSelectBox(panel_type,"panel_type_5 solar_pv_pricing_input", "panel_type_5"), makeSelectBox(panel_type,"panel_type_6 solar_pv_pricing_input", "panel_type_6")],
                    ["Inverter Type", makeSelectBox(inverter_type,"inverter_type_1 solar_pv_pricing_input", "inverter_type_1"), makeSelectBox(inverter_type,"inverter_type_2 solar_pv_pricing_input", "inverter_type_2"), makeSelectBox(inverter_type,"inverter_type_3 solar_pv_pricing_input", "inverter_type_3"), makeSelectBox(inverter_type,"inverter_type_4 solar_pv_pricing_input", "inverter_type_4"), makeSelectBox(inverter_type,"inverter_type_5 solar_pv_pricing_input", "inverter_type_5"), makeSelectBox(inverter_type,"inverter_type_6 solar_pv_pricing_input", "inverter_type_6")],
                    ["Total Panels", makeInputBox("total_panels_1 solar_pv_pricing_input","total_panels_1"), makeInputBox("total_panels_2 solar_pv_pricing_input", "total_panels_2"), makeInputBox("total_panels_3 solar_pv_pricing_input", "total_panels_3"), makeInputBox("total_panels_4 solar_pv_pricing_input", "total_panels_4"), makeInputBox("total_panels_5 solar_pv_pricing_input", "total_panels_5"), makeInputBox("total_panels_6 solar_pv_pricing_input", "total_panels_6")],
                    ["Base Price", makeInputBox("base_price_1 solar_pv_pricing_input", "base_price_1"), makeInputBox("base_price_2 solar_pv_pricing_input", "base_price_2"), makeInputBox("base_price_3 solar_pv_pricing_input", "base_price_3"), makeInputBox("base_price_4 solar_pv_pricing_input", "base_price_4"), makeInputBox("base_price_5 solar_pv_pricing_input", "base_price_5"), makeInputBox("base_price_6 solar_pv_pricing_input", "base_price_6")],
                    ["Extra 1", makeSelectBox(extra_1,"extra_1_1 solar_pv_pricing_input", "extra_1_1"), makeSelectBox(extra_1,"extra_1_2 solar_pv_pricing_input", "extra_1_2"), makeSelectBox(extra_1,"extra_1_3 solar_pv_pricing_input", "extra_1_3"), makeSelectBox(extra_1,"extra_1_4 solar_pv_pricing_input", "extra_1_4"), makeSelectBox(extra_1,"extra_1_5 solar_pv_pricing_input", "extra_1_5"), makeSelectBox(extra_1,"extra_1_6 solar_pv_pricing_input", "extra_1_6")],
                    ["Extra 2", makeSelectBox(extra_2,"extra_2_1 solar_pv_pricing_input", "extra_2_1"), makeSelectBox(extra_2,"extra_2_2 solar_pv_pricing_input", "extra_2_2"), makeSelectBox(extra_2,"extra_2_3 solar_pv_pricing_input", "extra_2_3"), makeSelectBox(extra_2,"extra_2_4 solar_pv_pricing_input", "extra_2_4"), makeSelectBox(extra_2,"extra_2_5 solar_pv_pricing_input", "extra_2_5"), makeSelectBox(extra_2,"extra_2_6 solar_pv_pricing_input", "extra_2_6")],
                    ["Extra 3", makeSelectBox(extra_3,"extra_3_1 solar_pv_pricing_input", "extra_3_1"), makeSelectBox(extra_3,"extra_3_2 solar_pv_pricing_input", "extra_3_2"), makeSelectBox(extra_3,"extra_3_3 solar_pv_pricing_input", "extra_3_3"), makeSelectBox(extra_3,"extra_3_4 solar_pv_pricing_input", "extra_3_4"), makeSelectBox(extra_3,"extra_3_5 solar_pv_pricing_input", "extra_3_5"), makeSelectBox(extra_3,"extra_3_6 solar_pv_pricing_input", "extra_3_6")],
                    ["How many double storey panels?", makeInputBox("number_double_storey_panel_1 solar_pv_pricing_input", "number_double_storey_panel_1"), makeInputBox("number_double_storey_panel_2 solar_pv_pricing_input", "number_double_storey_panel_2"), makeInputBox("number_double_storey_panel_3 solar_pv_pricing_input", "number_double_storey_panel_3"), makeInputBox("number_double_storey_panel_4 solar_pv_pricing_input", "number_double_storey_panel_4"), makeInputBox("number_double_storey_panel_5 solar_pv_pricing_input", "number_double_storey_panel_5"), makeInputBox("number_double_storey_panel_6 solar_pv_pricing_input", "number_double_storey_panel_6")],
                    ["Landscape Panels >15 deg?", makeInputBox("landscape_panel_deg_1 solar_pv_pricing_input", "landscape_panel_deg_1"), makeInputBox("landscape_panel_deg_2 solar_pv_pricing_input", "landscape_panel_deg_2"), makeInputBox("landscape_panel_deg_3 solar_pv_pricing_input", "landscape_panel_deg_3"), makeInputBox("landscape_panel_deg_4 solar_pv_pricing_input", "landscape_panel_deg_4"), makeInputBox("landscape_panel_deg_5 solar_pv_pricing_input", "landscape_panel_deg_5"), makeInputBox("landscape_panel_deg_6 solar_pv_pricing_input", "landscape_panel_deg_6")],
                    ["Raked Ceiling?", makeSelectBox(bool_val,"raked_ceiling_1 solar_pv_pricing_input", "raked_ceiling_1"), makeSelectBox(bool_val,"raked_ceiling_2 solar_pv_pricing_input", "raked_ceiling_2"), makeSelectBox(bool_val,"raked_ceiling_3 solar_pv_pricing_input", "raked_ceiling_3"), makeSelectBox(bool_val,"raked_ceiling_4 solar_pv_pricing_input", "raked_ceiling_4"), makeSelectBox(bool_val,"raked_ceiling_5 solar_pv_pricing_input", "raked_ceiling_5"), makeSelectBox(bool_val,"raked_ceiling_6 solar_pv_pricing_input", "raked_ceiling_6")],
                    ["Travel (km)", makeInputBox("travel_km_1 solar_pv_pricing_input", "travel_km_1"), makeInputBox("travel_km_2 solar_pv_pricing_input", "travel_km_2"), makeInputBox("travel_km_3 solar_pv_pricing_input", "travel_km_3"), makeInputBox("travel_km_4 solar_pv_pricing_input", "travel_km_4"), makeInputBox("travel_km_5 solar_pv_pricing_input", "travel_km_5"), makeInputBox("travel_km_6 solar_pv_pricing_input", "travel_km_6")],
                    ["Tilting", makeInputBox("tilting_1 solar_pv_pricing_input", "tilting_1"), makeInputBox("tilting_2 solar_pv_pricing_input", "tilting_2"), makeInputBox("tilting_3 solar_pv_pricing_input", "tilting_3"), makeInputBox("tilting_4 solar_pv_pricing_input", "tilting_4"), makeInputBox("tilting_5 solar_pv_pricing_input", "tilting_5"), makeInputBox("tilting_6 solar_pv_pricing_input", "tilting_6")],
                    ["Groups of Panels?", makeInputBox("groups_of_panels_1 solar_pv_pricing_input", "groups_of_panels_1"), makeInputBox("groups_of_panels_2 solar_pv_pricing_input", "groups_of_panels_2"), makeInputBox("groups_of_panels_3 solar_pv_pricing_input", "groups_of_panels_3"), makeInputBox("groups_of_panels_4 solar_pv_pricing_input", "groups_of_panels_4"), makeInputBox("groups_of_panels_5 solar_pv_pricing_input", "groups_of_panels_5"), makeInputBox("groups_of_panels_6 solar_pv_pricing_input", "groups_of_panels_6")],
                    ["Terracotta", makeInputBox("terracotta_1 solar_pv_pricing_input", "terracotta_1"), makeInputBox("terracotta_2 solar_pv_pricing_input", "terracotta_2"), makeInputBox("terracotta_3 solar_pv_pricing_input", "terracotta_3"), makeInputBox("terracotta_4 solar_pv_pricing_input", "terracotta_4"), makeInputBox("terracotta_5 solar_pv_pricing_input", "terracotta_5"), makeInputBox("terracotta_6 solar_pv_pricing_input", "terracotta_6")],
                    ["Steep Roof bettween 25 and 30 deg?", makeSelectBox(bool_val,"steep_roof_1 solar_pv_pricing_input", "steep_roof_1"), makeSelectBox(bool_val,"steep_roof_2 solar_pv_pricing_input", "steep_roof_2"), makeSelectBox(bool_val,"steep_roof_3 solar_pv_pricing_input", "steep_roof_3"), makeSelectBox(bool_val,"steep_roof_4 solar_pv_pricing_input", "steep_roof_4"), makeSelectBox(bool_val,"steep_roof_5 solar_pv_pricing_input", "steep_roof_5"), makeSelectBox(bool_val,"steep_roof_6 solar_pv_pricing_input", "steep_roof_6")],
                    ["Extras Sub Total", makeInputBox("extras_sub_total_1 solar_pv_pricing_input", "extras_sub_total_1", true), makeInputBox("extras_sub_total_2 solar_pv_pricing_input", "extras_sub_total_2", true), makeInputBox("extras_sub_total_3 solar_pv_pricing_input", "extras_sub_total_3",true), makeInputBox("extras_sub_total_4 solar_pv_pricing_input", "extras_sub_total_4",true), makeInputBox("extras_sub_total_5 solar_pv_pricing_input", "extras_sub_total_5",true), makeInputBox("extras_sub_total_6 solar_pv_pricing_input", "extras_sub_total_6",true)],
                    ["Total Price", makeInputBox("total_price_1 solar_pv_pricing_input", "total_price_1", true), makeInputBox("total_price_2 solar_pv_pricing_input", "total_price_2", true), makeInputBox("total_price_3 solar_pv_pricing_input", "total_price_3",true), makeInputBox("total_price_4 solar_pv_pricing_input", "total_price_4",true), makeInputBox("total_price_5 solar_pv_pricing_input", "total_price_5",true), makeInputBox("total_price_6 solar_pv_pricing_input", "total_price_6",true)],
                    ["Suggest Price", makeInputBox("suggest_price_1 solar_pv_pricing_input", "suggest_price_1", true), makeInputBox("suggest_price_2 solar_pv_pricing_input", "suggest_price_2", true), makeInputBox("suggest_price_3 solar_pv_pricing_input", "suggest_price_3",true), makeInputBox("suggest_price_4 solar_pv_pricing_input", "suggest_price_4",true), makeInputBox("suggest_price_5 solar_pv_pricing_input", "suggest_price_5",true), makeInputBox("suggest_price_6 solar_pv_pricing_input", "suggest_price_6",true)],
                    ["$/W :", makeInputBox("price_kw_1 solar_pv_pricing_input", "price_kw_1", true), makeInputBox("price_kw_2 solar_pv_pricing_input", "price_kw_2", true), makeInputBox("price_kw_3 solar_pv_pricing_input", "price_kw_3",true), makeInputBox("price_kw_4 solar_pv_pricing_input", "price_kw_4",true), makeInputBox("price_kw_5 solar_pv_pricing_input", "price_kw_5",true), makeInputBox("price_kw_6 solar_pv_pricing_input", "price_kw_6",true)],

                ]
        var solar_pv_pricing_table = $("<div id='solar_pv_pricing_table'></div>");
        $("#detailpanel_5").find(".tab-content").html(solar_pv_pricing_table);
        var cityTable = makeTable(solar_pv_pricing_table, data, "Solar-PV-Pricing", "Solar-PV-Pricing"  );
        $(".Solar-PV-Pricing select").css("width",'180px');
        //dung code - fix logic display add button
        $('table[id="Solar-PV-Pricing"]').after('<br><button type="button" style="float:left;" class="button primary" id="calculatePrice"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Calculate Price </button><div class="clearfix"></div>');

        function display_button_price(){
            //$('body').find('#link_solargain_quote,#updateToQuotesSolarGain_position_price,#convertToQuotesSolarGain_position_price ,#getSGPrice_table_price ,#calculatePrice').remove();
            if($("#solargain_quote_number_c").val() == "" && $("#solargain_tesla_quote_number_c").val() == ""  ){
                $('table[id="Solar-PV-Pricing"]').after(
                '&nbsp;<br> <button type="button" id="convertToQuotesSolarGain_position_price" class="button convertToQuotesSolarGain" title="Convert To Solargain Quote" onClick="SUGAR.pushToSolargain(this);" > Push To SG <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                )
            }else{
                
                $('table[id="Solar-PV-Pricing"]').after(
                '<button type="button" id="updateToQuotesSolarGain_position_price"  class="button updateToQuotesSolarGain" title="Update Price To Solargain Quote" onClick="SUGAR.updateQuotePriceToSolargain(this);" > Update Price To Solargain Quote <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                );
                $('table[id="Solar-PV-Pricing"]').after('<button type="button" style="float:left;" class="button primary" id="getSGPrice_table_price"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get SG Price </button><div class="clearfix"></div>');
                if($("#solargain_quote_number_c").val() !== ''){
                    $('#solar_pv_pricing_table').append('</br><a id="link_solargain_quote" target="_blank" href="https://crm.solargain.com.au/quote/edit/'+$("#solargain_quote_number_c").val() +'">Solargain Quote Link</a>');
                }else {
                    $('#solar_pv_pricing_table').append('</br><a id="link_solargain_quote" target="_blank" href="https://crm.solargain.com.au/quote/edit/'+$("#solargain_tesla_quote_number_c").val() +'">Solargain Quote Link</a>');
                }
              
            }
        }
        display_button_price();
        $('body').on('change','#solargain_quote_number_c, #solargain_tesla_quote_number_c',function(){
            display_button_price();
        })

        //dung code - Double Storey Checkbox
        var html_checkbox_Convert_Solar_Opportunity = 
         //tu code  Add text Install Year
         '<div class="col-xs-12  col-sm-12 label" >Install Year = 2019</div>'
        + '<div class="col-xs-12 col-sm-12 edit-view-row-item">'
        + '<div class="col-xs-12 col-sm-2 label" data-label="">'
        + 'Double Storey:</div>'
        + '<div class="" type="bool" field="send_sms" colspan="3">'
        + '<input type="checkbox" class="solar_pv_pricing_input" id="Double_Storey" name="Double_Storey" value="1" title="" tabindex="0">'                  
        +'</div>'
        +'</div>';
        $('#solar_pv_pricing_table').parent().before(html_checkbox_Convert_Solar_Opportunity); 

        $('#Double_Storey').on('click',function(){
            if($(this).prop('checked') == true){
               for (let i = 1; i < 7; i++) {   
                   $('#number_double_storey_panel_'+i).val($('#total_panels_'+i).val());
                   //if ($('#number_double_storey_panel_'+i).val() != "") $('#number_double_storey_panel_'+i).trigger("change");
               }
            }
        })

        // dung code - Terracotta checkbox
        var html_checkbox_Terracotta = 
        '<div class="col-xs-12 col-sm-12 edit-view-row-item">'
        + '<div class="col-xs-12 col-sm-2 label" data-label="">'
        + 'Terracotta:</div>'
        + '<div type="bool" colspan="3">'
        + '<input type="checkbox" class="solar_pv_pricing_input" id="Terracotta_checkbox" name="Terracotta_checkbox" value="1" title="" tabindex="0">'                  
        +'</div>'
        +'</div>';
        $('#solar_pv_pricing_table').parent().before(html_checkbox_Terracotta); 

        $('#Terracotta_checkbox').on('click',function(){
            if($(this).prop('checked') == true){
               for (let i = 1; i < 7; i++) {   
                   $('#terracotta_'+i).val($('#total_panels_'+i).val());
                   //if ($('#terracotta_'+i).val() !== "") $('#terracotta_'+i).trigger("change");
               }
            }
        })

        $('body').on("click","#calculatePrice",function(){
            for(i=1; i<= 6; i ++){
                calculation_for_option(i);
            }
            total_kw();
            price_kw();
            save_values();
        });
        var values = {};
        function save_values(){
            $("#detailpanel_5 .solar_pv_pricing_input").each(function (){
                var id_name = $(this).attr("id");
                if($("#"+id_name).attr('type')== 'checkbox'){
                    values[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
                } else {
                    values[id_name] = $(this).val();
                }
                
            });
            $("#solar_pv_pricing_input_c").val(JSON.stringify(values));
        }
        var Base_Price_Input_Table = 
        {
            "VIC":{
                "option1":{
                    'price':'7490',
                    'inverter':'Primo 5',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'20',
                    'max_number':'20'
                },
                "option2":{
                    'price':'8590',
                    'inverter':'Primo 6',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'24',
                    'max_number':'24'
                },
                "option3":{
                    'price':'10690',
                    'inverter':'Primo 8.2',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'33',
                    'max_number':'33'
                },
                "option4":{
                    'price':'9790',
                    'inverter':'Primo 5',
                    'panel':'Sunpower E327W',
                    'number':'20',
                    'max_number':'20'
                },
                "option5":{
                    'price':'11390',
                    'inverter':'Primo 6',
                    'panel':'Sunpower E327W',
                    'number':'24',
                    'max_number':'24'
                },
                "option6":{
                    'price':'14590',
                    'inverter':'Primo 8.2',
                    'panel':'Sunpower E327W',
                    'number':'33',
                    'max_number':'33'
                },
            },
            "SA":{
                "option1":{
                    'price':'6890',
                    'inverter':'Primo 5',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'20',
                    'max_number':'20'
                },
                "option2":{
                    'price':'7790',
                    'inverter':'Primo 6',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'24',
                    'max_number':'24'
                },
                "option3":{
                    'price':'9490',
                    'inverter':'Primo 8.2',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'33',
                    'max_number':'33'
                },
                "option4":{
                    'price':'8990',
                    'inverter':'Primo 5',
                    'panel':'Sunpower E327W',
                    'number':'20',
                    'max_number':'20'
                },
                "option5":{
                    'price':'10590',
                    'inverter':'Primo 6',
                    'panel':'Sunpower E327W',
                    'number':'24',
                    'max_number':'24'
                },
                "option6":{
                    'price':'13290',
                    'inverter':'Primo 8.2',
                    'panel':'Sunpower E327W',
                    'number':'33',
                    'max_number':'33'
                },
            },
            "NSW":{
                "option1":{
                    'price':'7290',
                    'inverter':'Primo 5',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'20',
                    'max_number':'20'
                },
                "option2":{
                    'price':'8290',
                    'inverter':'Primo 6',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'24',
                    'max_number':'24'
                },
                "option3":{
                    'price':'9990',
                    'inverter':'Primo 8.2',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'33',
                    'max_number':'33'
                },
                "option4":{
                    'price':'9690',
                    'inverter':'Primo 5',
                    'panel':'Sunpower E327W',
                    'number':'20',
                    'max_number':'20'
                },
                "option5":{
                    'price':'10990',
                    'inverter':'Primo 6',
                    'panel':'Sunpower E327W',
                    'number':'24',
                    'max_number':'24'
                },
                "option6":{
                    'price':'14490',
                    'inverter':'Primo 8.2',
                    'panel':'Sunpower E327W',
                    'number':'33',
                    'max_number':'33'
                },
            },
            "ACT":{
                "option1":{
                    'price':'7890',
                    'inverter':'Primo 5',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'20',
                    'max_number':'20'
                },
                "option2":{
                    'price':'8790',
                    'inverter':'Primo 6',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'24',
                    'max_number':'24'
                },
                "option3":{
                    'price':'10590',
                    'inverter':'Primo 8.2',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'33',
                    'max_number':'33'
                },
                "option4":{
                    'price':'9990',
                    'inverter':'Primo 5',
                    'panel':'Sunpower E327W',
                    'number':'20',
                    'max_number':'20'
                },
                "option5":{
                    'price':'11590',
                    'inverter':'Primo 6',
                    'panel':'Sunpower E327W',
                    'number':'24',
                    'max_number':'24'
                },
                "option6":{
                    'price':'14490',
                    'inverter':'Primo 8.2',
                    'panel':'Sunpower E327W',
                    'number':'33',
                    'max_number':'33'
                },
            },
            "QLD":{
                "option1":{
                    'price':'6390',
                    'inverter':'Primo 5',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'20',
                    'max_number':'20'
                },
                "option2":{
                    'price':'7290',
                    'inverter':'Primo 6',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'24',
                    'max_number':'24'
                },
                "option3":{
                    'price':'8990',
                    'inverter':'Primo 8.2',
                    'panel':'Q CELLS Q.PEAK DUO 325W',
                    'number':'33',
                    'max_number':'33'
                },
                "option4":{
                    'price':'8690',
                    'inverter':'Primo 5',
                    'panel':'Sunpower E327W',
                    'number':'20',
                    'max_number':'20'
                },
                "option5":{
                    'price':'9990',
                    'inverter':'Primo 6',
                    'panel':'Sunpower E327W',
                    'number':'24',
                    'max_number':'24'
                },
                "option6":{
                    'price':'12990',
                    'inverter':'Primo 8.2',
                    'panel':'Sunpower E327W',
                    'number':'33',
                    'max_number':'33'
                },
            },
        };
 
        function f_total_price(BasePrice,ExtrasSubTotal){
            var result = parseFloat(BasePrice) + parseFloat(ExtrasSubTotal);
            return result;
        };

        function Extras_Sub_Total (DoublestoreyExtraCalculation, LandscapePanels , RakedCeiling, Travel, GroupsofPanels, Terracotta, SteepRoof,Extra_1, Extra_2, Extra_3,Tilting){
            var result = parseFloat(!isNaN(DoublestoreyExtraCalculation)?DoublestoreyExtraCalculation:0) + parseFloat(!isNaN(LandscapePanels)?LandscapePanels:0) + 
            parseFloat(!isNaN(RakedCeiling)?RakedCeiling:0) + parseFloat(!isNaN(Travel)?Travel:0)+ parseFloat(!isNaN(GroupsofPanels)?GroupsofPanels:0)
            + parseFloat(!isNaN(Terracotta)?Terracotta:0) + parseFloat(!isNaN(SteepRoof)?SteepRoof:0) + parseFloat(!isNaN(Extra_1)?Extra_1:0) + parseFloat(!isNaN(Extra_2)?Extra_2:0) + parseFloat(!isNaN(Extra_3)?Extra_3:0) + parseFloat(!isNaN(Tilting)?Tilting:0);
            return parseFloat(result).toFixed(2);
        }
        var storey_charge =  {
            "WA":{'base':220,'per_panel':15,'per_panel1':0,'per_panel2':0},
            "ACT":{'base':120,'per_panel':0,'per_panel1':0,'per_panel2':0},
            "SA":{'base':343.75,'per_panel':10.3125,'per_panel1':0,'per_panel2':0},
            "QLD":{'base':250,'per_panel':0,'per_panel1':0,'per_panel2':0},
            "NT":{'base':'','per_panel':'','per_panel1':0,'per_panel2':0},
            "VIC":{'base':343.75,'per_panel':10.3125,'per_panel1':15,'per_panel2':15},
            "NSW":{'base':343.75,'per_panel':10.3125,'per_panel1':0,'per_panel2':0},
        };

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
                    data_return =  data[state]['per_panel1'] * input1;
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
                    data_return =  data[state]['per_panel2'] * input1;
                    break;
                case 9 :
                    if(input1 == 'Yes'){
                        data_return = 165;
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
                        data_return = 990;
                    }
                    else if(input1 == 'ENPHS Envoy-S Met.'){
                        data_return = 300;
                    }
                    else if(input1 == 'SE Smart Meter'){
                        data_return = 0;
                    }
                    else if(input1 == 'Switchboard UPG'){
                        data_return = 990;
                    }
                    else if(input1 == 'ENPHS Envoy-S Met.'){
                        data_return = 300;
                    }
                    break;

                default : break;
            }
            return parseFloat(data_return);
        }

        function convert_state(){
            var primary_address_state = $("#primary_address_state").val().toUpperCase();
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

        function calculation_for_option(option){
            var state = convert_state();
            var number_double_storey_panel_1 = parseFloat(($('#number_double_storey_panel_'+option).val() != "")?$('#number_double_storey_panel_'+option).val():0);
            var Double_Storey_Extra_Fixed = calculation(1, number_double_storey_panel_1, state, storey_charge);
            var Double_Storey_Extra_variable = calculation(2, number_double_storey_panel_1, state, storey_charge);
            var DoublestoreyExtraCalculation = Double_Storey_Extra_variable + Double_Storey_Extra_Fixed; //calculation(3,$('#number_double_storey_panel_1').val(),state,storey_charge);
            
            var landscape_panel_deg_1 = parseFloat(($('#landscape_panel_deg_'+option).val() != "")?$('#landscape_panel_deg_'+option).val():0);
            var LandscapePanels = calculation(4, landscape_panel_deg_1, state, storey_charge);

            var RakedCeiling = calculation(5,$('#raked_ceiling_'+option).val(),state,storey_charge);

            var travel_km_1 = parseFloat(($('#travel_km_'+option).val() != "")?$('#travel_km_'+option).val():0);
            var Travel = calculation(6, travel_km_1, state, storey_charge);

            var groups_of_panels_1 = parseFloat(($('#groups_of_panels_'+option).val() != "")?$('#groups_of_panels_'+option).val():0);
            var GroupsofPanels = calculation(7,groups_of_panels_1,state,storey_charge);

            var m_terracotta = parseFloat(($('#terracotta_'+option).val() != "")?$('#terracotta_'+option).val():0);
            var Terracotta = calculation(8, m_terracotta, state, storey_charge);

            var SteepRoof =  calculation(9,$('#steep_roof_'+option).val(),state,storey_charge);
            
            //thienpb fix tilting price
            var Tilting = parseInt($("#tilting_"+option).val())*35;
            
            var Extra_1 = calculation(10,$('#extra_1_'+option).val(),state,storey_charge);
            var Extra_2 = calculation(11,$('#extra_2_'+option).val(),state,storey_charge);
            var Extra_3 = calculation(12,$('#extra_3_'+option).val(),state,storey_charge);
            var ExtrasSubTotal=  Extras_Sub_Total(DoublestoreyExtraCalculation, LandscapePanels , RakedCeiling, Travel, GroupsofPanels, Terracotta, SteepRoof, Extra_1, Extra_2, Extra_3, Tilting);
            $("#extras_sub_total_"+option).val(ExtrasSubTotal);
            var BasePrice = ($('#base_price_'+option).val()!="")?$('#base_price_'+option).val():0;

            BasePrice = parseFloat(BasePrice);
            
            var total_price = f_total_price(BasePrice,ExtrasSubTotal);
            $("#total_price_"+option).val(total_price);
            //tu-code 
            var suggest_price  = parseInt($("#total_price_"+option).val())+"";
            $("#suggest_price_"+option).val(suggest_price.substring(0,suggest_price.length-2)+90);
            // $("#suggest_price_"+option).val($("#total_price_"+option).val().substring(0,$("#total_price_"+option).val().length-2)+90);
        }
        var values = ($("#solar_pv_pricing_input_c").val() != "")?JSON.parse($("#solar_pv_pricing_input_c").val()):{};
        
        //thienpb code - fix max total panel
        $('body').on("change","input[id*='total_panels_']",function(){
            var class_name = $(this).attr("class").split(" ");
            var state = convert_state();
            var option = class_name[0][class_name[0].length -1];
            var option_count = 'option'+option;
            if($("#panel_type_"+option).val() == 'Jinko 315W Cheetah Mono PERC'){
                if(parseInt($("#total_panels_"+option).val()) > 34){
                    $(this).val('');
                    alert("Not allowed to have this many panels!");
                    return;
                }
            }else if($("#panel_type_"+option).val() == 'Q CELLS Q.PEAK DUO 325W' || $("#panel_type_"+option).val() == 'Sunpower E327W'){
                if(parseInt($("#total_panels_"+option).val()) > 33){
                    $(this).val('');
                    alert("Not allowed to have this many panels!");
                    return;
                }
            }
        });

        //thienpb code - popular data for SOLAR PV PRICING
        function ajaxGetBasePrice(state,data_panel_type,data_inverter,data_total_panel){
            var data_return = 0;
            $.ajax({
                url: 'index.php?entryPoint=popularSolarBasePrice&state='+state+'&panel_type='+data_panel_type+'&inverter_type='+data_inverter+'&total_panels='+data_total_panel,
                async: false,
                success: function (data) {
                    data_return = data;
                }
            });
            if(data_return !='' && typeof data_return !== "undefined" ){
                return data_return;
            }else{
                return  0 ;
            }
        }

        $('body').on("change","select[id*='panel_type_'], select[id*='inverter_type_'], input[id*='total_panels_'], input[id*='pm_']",function(){
            var index  = $(this).attr("id").split('_');
            if($(this).attr("id").indexOf('pm_') >= 0){
                index = index[1];
            }else{
                index = index[2];
            }
           
            var panel_type = $("#panel_type_"+index).val();
            var inverter_type = $("#inverter_type_"+index).val();
            var total_panels = $("#total_panels_"+index).val();
            var state_ = convert_state();
            var option_count = 'option'+(index);
            if(state_ == '') return;
            if(panel_type != '' && inverter_type !='' && total_panels!=''){
                var data_return = ajaxGetBasePrice(state_,panel_type,inverter_type,total_panels);
                if(data_return == 0){
                    var base_price = Base_Price_Input_Table[state_][option_count]['price'];
                    if(base_price !='' && typeof base_price !== "undefined" ){
                        console.log('We cannot generate base price for this option!');
                        alert("We cannot generate base price for this option!");
                        base_price = 0;
                    }
                }else{
                    base_price = data_return;
                }
                if(Number($("#pm_"+index).val()) != "NaN" && base_price != 0){
                    base_price = Number(base_price) + Number($("#pm_"+index).val().trim());
                }
                $("#base_price_"+index).val(base_price);
            }
        });
        //thienpb end code

        //tu-code - max Tilting
        $('body').on("change","input[id*='tilting_']",function(){
            var class_name = $(this).attr("class").split(" ");
            var option = class_name[0][class_name[0].length -1];
            if($("#panel_type_"+option).val() == 'Jinko 315W Cheetah Mono PERC'){
                if(parseInt($("#tilting_"+option).val()) > 34 ){
                    $(this).val(0);
                    alert("Not allowed to have this many panels!");
                    return;
                }
            }else if($("#panel_type_"+option).val() == 'Q CELLS Q.PEAK DUO 325W' || $("#panel_type_"+option).val() == 'Sunpower E327W'){
                if(parseInt($("#tilting_"+option).val()) > 33 ){
                    $(this).val(0);
                    alert("Not allowed to have this many panels!");
                    return;
                }
            }
        });

        $('body').on('change','.solar_pv_pricing_input',function(evt){
            return;
            // get end of character class
            var class_name = $(this).attr("class").split(" ");
            var id_name = $(this).attr("id");
            values[id_name] = $(this).val();
            var option = class_name[0][class_name[0].length -1];
            //tu-code MAX PANELS 
            var state = convert_state();
            var option_count = 'option'+option;
            if(parseInt($("#total_panels_"+option).val()) >parseInt( Base_Price_Input_Table [state][option_count]['max_number'])&& $("#panel_type_"+option).val() != "J315"){
                alert("Not allowed to have this many panels!");
                return;
            }
            /*var state = convert_state();
            var number_double_storey_panel_1 = parseFloat(($('#number_double_storey_panel_1').val() != "")?$('#number_double_storey_panel_1').val():0);
            var Double_Storey_Extra_Fixed = calculation(1, number_double_storey_panel_1, state, storey_charge);
            var Double_Storey_Extra_variable = calculation(2, number_double_storey_panel_1, state, storey_charge);
            var DoublestoreyExtraCalculation = Double_Storey_Extra_variable + Double_Storey_Extra_Fixed; //calculation(3,$('#number_double_storey_panel_1').val(),state,storey_charge);
            
            var landscape_panel_deg_1 = parseFloat(($('#landscape_panel_deg_1').val() != "")?$('#landscape_panel_deg_1').val():0);
            var LandscapePanels = calculation(4, landscape_panel_deg_1, state, storey_charge);

            var RakedCeiling = calculation(5,$('#raked_ceiling_1').val(),state,storey_charge);

            var travel_km_1 = parseFloat(($('#travel_km_1').val() != "")?$('#travel_km_1').val():0);
            var Travel = calculation(6, travel_km_1, state, storey_charge);

            var groups_of_panels_1 = parseFloat(($('#groups_of_panels_1').val() != "")?$('#groups_of_panels_1').val():0);
            var GroupsofPanels = calculation(7,$('#groups_of_panels_1').val(),state,storey_charge);

            var ExtrasSubTotal=  Extras_Sub_Total(DoublestoreyExtraCalculation, LandscapePanels , RakedCeiling, Travel, GroupsofPanels);
            $("#extras_sub_total_1").val(ExtrasSubTotal);
            var BasePrice = ($('#base_price_1').val()!="")?$('#base_price_1').val():0;

            BasePrice = parseFloat(BasePrice);

            var total_price = f_total_price(BasePrice,ExtrasSubTotal);
            $("#total_price_1").val(total_price);
            */
          
            calculation_for_option(option);
            //$("#solar_pv_pricing_input_c").val(JSON.stringify(values));
            total_kw();
            price_kw();
        })   
        
        // Parse json and set value
        if($("#solar_pv_pricing_input_c")!=""){
            var json_val = JSON.parse(($("#solar_pv_pricing_input_c").val() != "")?$("#solar_pv_pricing_input_c").val():"{}");
            for (let key in json_val) {  
                console.log($("#"+key).val());
                if($("#"+key).attr('type') == 'checkbox'){
                    $("#"+key).prop( "checked", json_val[key] );
                } else 
                    $("#"+key).val(json_val[key]);
                //$("#"+key).trigger("change");
            }
        }
        
        function pupulate_data(data,is_first = true){
            // Get Distance
            if(($("#solar_pv_pricing_input_c").val() !== "") && is_first ){
                return
            }

            var distance = $("#distance_to_sg_c").val().replace(/[^0-9.,]/g, '');

            var options_selected = $("#solargain_options_c").val();
            var state_ = convert_state();
            if(state_ == '') return;

            $.each(options_selected, function( index, value ) {
                    // console.log(data['VIC']['option1']['panel']);
                    var option_count = 'option'+(parseInt(value)+1);
                    //dung code - check exist state in option default
                    var array_state_exist = ['VIC','SA','NSW','ACT','QLD'];
                    if(!array_state_exist.includes(state_))return;
                    
                    var data_panel_type = data[state_][option_count]['panel'];
                    var data_total_panel = data[state_][option_count]['number'];
                    var data_inverter = data[state_][option_count]['inverter'];
                    
                    //thienpb code -- auto popular
                    var data_base_price = 0;
                    data_base_price = ajaxGetBasePrice(state_,data_panel_type,data_inverter,data_total_panel);
                    if(data_base_price == 0){
                        data_base_price = data[state_][option_count]['price'];
                        if(data_base_price !='' && typeof data_base_price !== "undefined" ){
                            alert("We cannot generate base price for this option!");
                            data_base_price = 0;
                        }
                    }

                    $("#travel_km_"+(parseInt(value) + 1)).val(distance);

                    switch(value){
                        case "0":
                            $("#panel_type_1").val(data_panel_type);
                            $("#total_panels_1").val(data_total_panel);
                            $("#inverter_type_1").val(data_inverter);
                            $("#base_price_1").val(data_base_price);
                            //$("#panel_type_1").trigger('change');
                        break;
                        case "1":
                            $("#panel_type_2").val(data_panel_type);
                            $("#total_panels_2").val(data_total_panel);
                            $("#inverter_type_2").val(data_inverter);
                            $("#base_price_2").val(data_base_price);
                            //$("#panel_type_2").trigger('change');
                        break;
                        case "2":
                            $("#panel_type_3").val(data_panel_type);
                            $("#total_panels_3").val(data_total_panel);
                            $("#inverter_type_3").val(data_inverter);
                            $("#base_price_3").val(data_base_price);
                            //$("#panel_type_3").trigger('change');
                        break;
                        case "3":
                            $("#panel_type_4").val(data_panel_type);
                            $("#total_panels_4").val(data_total_panel);
                            $("#inverter_type_4").val(data_inverter);
                            $("#base_price_4").val(data_base_price);
                            //$("#panel_type_4").trigger('change');
                        break;
                        case "4":
                        $("#panel_type_5").val(data_panel_type);
                            $("#total_panels_5").val(data_total_panel);
                            $("#inverter_type_5").val(data_inverter);
                            $("#base_price_5").val(data_base_price);
                            //$("#panel_type_5").trigger('change');
                        break;
                        case "5":
                        $("#panel_type_6").val(data_panel_type);
                            $("#total_panels_6").val(data_total_panel);
                            $("#inverter_type_6").val(data_inverter);
                            $("#base_price_6").val(data_base_price);
                            //$("#panel_type_6").trigger('change');
                        break;
                    }
            });
        }
        $("#solargain_options_c").change(function(){
            $('#Solar-PV-Pricing .solar_pv_pricing_input').each(function(){
                if(this.type == 'select-one'){
                    $(this).prop("selectedIndex", 0);
                }else{
                    $(this).val('');
                }
            });
            pupulate_data(Base_Price_Input_Table, false);
        });
        pupulate_data(Base_Price_Input_Table);

        $("#travel_km_1").change(function(){
            if($(this).val() !== ""){
                var val = $(this).val();
                $("#travel_km_2").val(val);
                $("#travel_km_3").val(val);
                $("#travel_km_4").val(val);
                $("#travel_km_5").val(val);
                $("#travel_km_6").val(val);

            }
        });

        //dung code - function calculate total KW
        function total_kw(){
            for (let i = 1; i < 7; i++) {   
                var panel_kw = parseInt($('#panel_type_'+i).val().slice(1));
                var number_panel = parseInt($('#total_panels_'+i).val());
                var total_kw = panel_kw*number_panel/1000;
                if(!isNaN(total_kw))$('#total_kW_'+i).val(total_kw);
            }
        }
        
        function price_kw() {
            for (let i = 1; i < 7; i++) {   
                var total_kw = parseFloat($('#total_kW_'+i).val());
                var suggest_price = parseFloat($('#suggest_price_'+i).val());
                var price_kw = (suggest_price/(total_kw*1000)).toFixed(2);
                if(!isNaN(price_kw))$('#price_kw_'+i).val(price_kw);
            }
        }
    });
})