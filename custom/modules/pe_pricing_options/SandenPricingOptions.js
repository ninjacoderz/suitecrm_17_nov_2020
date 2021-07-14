function renderSandenHTML(type){
        
    $.ajax({
        url: '/index.php?entryPoint=APIRenderListQuoteInputs&type='+type,
        success: function (result) {
            try {
                var json_data = JSON.parse(result);
                var html_group_custom_quote_inputs = 
                '<div id="group_custom_quote_inputs_checklist" class="row detail-view-row"></div>';
                // var html_group_custom_quote_extra = 
                // '<div id="group_custom_quote_inputs_checklist_extra" class="row detail-view-row"></div>';
                $('.panel-content .panel-default').each(function(){
                    var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
                    if(title_panel_default == 'pricing options'){
                        seletor_panel_pricing_pv = '#' + $(this).find('.panel-body').attr('id');
                    }
                })
                $(seletor_panel_pricing_pv).find(".tab-content").html(html_group_custom_quote_inputs);
                $("#group_custom_quote_inputs_checklist").empty().append(json_data['template_html']);
                $('div[id="group_custom_quote_inputs_checklist"]').after('<br><button type="button" class="button primary" id="save_options"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Save Options </button><div class="clearfix"></div>');
                $("#save_options").on("click",function(){
                    save_sanden_option('group_custom_quote_inputs_checklist','select');
                })
            } catch (error) {
                console.log(error)
            }
        }
    }).done(function (data) {
        parseJSONValueToFields();
        // SUGAR.ajaxUI.hideLoadingPanel();
    });
}
function parseJSONValueToFields(){
    if ($("#pricing_option_input_c").val() == '')  return;
    var dataJSON = JSON.parse($("#pricing_option_input_c").val());
    for (let key in dataJSON) {
        if(typeof(dataJSON[key]) === Boolean){
            $("#"+key).prop('checked', dataJSON[key]);
            $("#"+key).closest('.edit-view-row-item').show();
        } else {
            $("#"+key).val(dataJSON[key]);  
            if(dataJSON[key] !== '' || key == 'quote_main_tank_water'){
                $("#"+key).closest('.edit-view-row-item').show();
            }
        }
    }
}
function save_sanden_option(){
    var values = {};
    $("#group_custom_quote_inputs_checklist select").each(function (){
        var id_name = $(this).attr("id");
        if($("#"+id_name).attr('type')== 'checkbox'){
            values[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
        } else {
            values[id_name] = $(this).val();
        }
        
    });
    $("#pricing_option_input_c").val(JSON.stringify(values));
}
