$(function(){

    'use strict';

    var panel_type = ['',/*'Jinko 370W Cheetah Plus JKM370M-66H'*/'Jinko 330W Mono PERC HC','Q CELLS Q.MAXX-G2 350W',/*'Longi Hi-MO X 350W''Q CELLS Q.MAXX 330W''Q CELLS Q.PEAK DUO G6+ 350W','Sunpower P3 325 BLACK'*/'Sunpower P3 370 BLACK',/*'Sunpower X22 360W',*/'Sunpower Maxeon 3 400'/*'Sunpower Maxeon 2 350','Sunpower Maxeon 3 395'*/];
    var inverter_type = ['','Primo 3','Primo 4','Primo 5','Primo 6','Primo 8.2','Symo 5','Symo 6','Symo 8.2','Symo 10','Symo 15','SYMO 20','S Edge 3','S Edge 5','S Edge 6','S Edge 8','S Edge 8 3P','S Edge 10','IQ7 plus',/*'IQ7',*/'IQ7X',/*'Growatt 3','Growatt 5','Growatt 6','Growatt8','Growatt 8.2',*/'Sungrow 3','Sungrow 5','Sungrow 8','Sungrow 10 3P','Sungrow 15 3P'];
    var bool_val = ["No", "Yes"];
    var data = [["Option", "1", "2", "3", "4", "5", "6"], //headers
                ["", "<button data-option ='1' id='btn_clear_option_1' class='button default'>Clear Option 1</button>", "<button data-option ='2' id='btn_clear_option_2' class='button default'>Clear Option 2</button>", "<button data-option ='3' id='btn_clear_option_3' class='button default'>Clear Option 3</button>", "<button data-option ='4' id='btn_clear_option_4' class='button default'>Clear Option 4</button>", "<button data-option ='5' id='btn_clear_option_5' class='button default'>Clear Option 5</button>", "<button data-option ='6' id='btn_clear_option_6' class='button default'>Clear Option 6</button>"],
                ["Panel Type", makeSelectBox(panel_type,"panel_type_1 solar_pv_pricing_input", "panel_type_1"), makeSelectBox(panel_type,"panel_type_2 solar_pv_pricing_input", "panel_type_2"), makeSelectBox(panel_type,"panel_type_3 solar_pv_pricing_input", "panel_type_3"), makeSelectBox(panel_type,"panel_type_4 solar_pv_pricing_input", "panel_type_4"), makeSelectBox(panel_type,"panel_type_5 solar_pv_pricing_input", "panel_type_5"), makeSelectBox(panel_type,"panel_type_6 solar_pv_pricing_input", "panel_type_6")],
                ["Inverter Type", makeSelectBox(inverter_type,"inverter_type_1 solar_pv_pricing_input", "inverter_type_1"), makeSelectBox(inverter_type,"inverter_type_2 solar_pv_pricing_input", "inverter_type_2"), makeSelectBox(inverter_type,"inverter_type_3 solar_pv_pricing_input", "inverter_type_3"), makeSelectBox(inverter_type,"inverter_type_4 solar_pv_pricing_input", "inverter_type_4"), makeSelectBox(inverter_type,"inverter_type_5 solar_pv_pricing_input", "inverter_type_5"), makeSelectBox(inverter_type,"inverter_type_6 solar_pv_pricing_input", "inverter_type_6")],
                ["Total Panels", makeInputBox("total_panels_1 solar_pv_pricing_input","total_panels_1"), makeInputBox("total_panels_2 solar_pv_pricing_input", "total_panels_2"), makeInputBox("total_panels_3 solar_pv_pricing_input", "total_panels_3"), makeInputBox("total_panels_4 solar_pv_pricing_input", "total_panels_4"), makeInputBox("total_panels_5 solar_pv_pricing_input", "total_panels_5"), makeInputBox("total_panels_6 solar_pv_pricing_input", "total_panels_6")],
               ]
    var solar_pv_pricing_table = $("<div id='solar_pv_pricing_table'></div>");
    var seletor_panel_pricing_pv = '';
    $('.panel-content .panel-default').each(function(){
        var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
        if(title_panel_default == 'pricing options'){
            seletor_panel_pricing_pv = '#' + $(this).find('.panel-body').attr('id');
        }
    })
    $(seletor_panel_pricing_pv).find(".tab-content").html(solar_pv_pricing_table);
    var cityTable = makeTable(solar_pv_pricing_table, data, "Solar-PV-Pricing", "Solar-PV-Pricing"  );
    $(".Solar-PV-Pricing select").css("width",'185px').parent().css('vertical-align','top');
    $("tr td:first-child").css("padding-right",'20px');

    $("button[id*='btn_clear_option_']").click(function(e){
        e.preventDefault();
        clear_option($(this).data('option')+1);
    });

    $('table[id="Solar-PV-Pricing"]').after('<br><button type="button" class="button primary" id="save_options"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Save Options </button><div class="clearfix"></div>');
    $("#save_options").on("click",function(){
        save_values();
    })
    
    // Parse json and set value
    if($("#pricing_option_input_c")!=""){
        var json_val = JSON.parse(($("#pricing_option_input_c").val() != "")?$("#pricing_option_input_c").val():"{}");
        for (let key in json_val) {  
            if($("#"+key).attr('type') == 'checkbox'){
                $("#"+key).prop( "checked", json_val[key] );
            } else 
                $("#"+key).val(json_val[key]);
        }
    }

    //max panels and event click
    $('table[id="Solar-PV-Pricing"]').after("</br><button class='button primary' type='button' id='max_panels' name='max_panels'>MAX PANELS</button>")
    $('body').on("click","#max_panels",function(){
        for(var i = 1 ; i < 7 ; i++){
            var panel_type_ = $("#panel_type_"+i).val();
            var inverter_type_ = $("#inverter_type_"+i).val();
            if(inverter_type_ == 'IQ7 plus') inverter_type_ = 'IQ7+';
            if(panel_type_ != '' && inverter_type_ != ""){
                var suggested_val = getSuggestedPanel(panel_type_,inverter_type_,'');
                console.log(suggested_val);
                if(suggested_val === undefined) return;
                $(".suggest_total_panel_"+i).remove();
                for(var j = 0 ; j < suggested_val.length ; j++){
                    $("#inverter_type_"+i).after('<div class="suggest_total_panel_'+i+'" id="suggest_total_panel_'+j+'_'+j+'">0 - '+suggested_val[j]+' panels</div>');
                    $("#total_panels_"+i).val(suggested_val[j]);
                }
            }
           
        }
    })
    
});


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

//FUNCTION CLEAR OPTION
function clear_option(option){
    $('#Solar-PV-Pricing td:nth-child('+option+')').find('input').val('');
    $('#Solar-PV-Pricing td:nth-child('+option+')').find('select').prop("selectedIndex", 0);
    $(".suggest_total_panel_"+(option-1)).remove();
    $("#sl_option_"+(option-1)).prop('checked',false);
}
//END

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
    $("#pricing_option_input_c").val(JSON.stringify(values));
}

function loadJSON(state){
    $.ajax({
        url: 'index.php?entryPoint=popularSolarBasePrice&state='+state,
        type : 'GET',
        success: function (data) {
            if(data === undefined){
                localStorage.setItem('basePrice','');
                return;
            }else{
                localStorage.setItem('basePrice',JSON.stringify($.parseJSON(data)));
            }
        }
    });
}


function getSuggestedPanel(panel_type,inverter_type,total_panel){
    if(panel_type == '')   return;
    
    dataJSON = JSON.parse(localStorage.basePrice);
    
    if(dataJSON == '') return;
    
    var list_panel = dataJSON[panel_type];
    var list_suggest = [];
    for (var item in list_panel) {
        if(item != 'one_per_panel'){
            var total_base = parseInt(item.split('-')[1].replace("panels",'').trim());
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