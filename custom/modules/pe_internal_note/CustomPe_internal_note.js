$(document).ready(function(){
    var array_module_use_create_call = ['AOS_Quotes','AOS_Invoices'];
    if( array_module_use_create_call.includes($('#form_SubpanelQuickCreate_pe_internal_note').find('input[name="return_module"]').val()) ){
        $('#form_SubpanelQuickCreate_pe_internal_note').find("#pe_internal_note_subpanel_full_form_button").after('<button type="button" id="create_call_back" class="button">Create Call Back<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
    }
    // VUT-S-Add relate quote
    var parent_module = $('#form_SubpanelQuickCreate_pe_internal_note').find('input[name="return_module"]').val();
    var parent_id = $('#form_SubpanelQuickCreate_pe_internal_note').find('input[name="return_id"]').val();
    var url = "index.php?entryPoint=getQuoteRelateAccount&parent_id=" +parent_id +"&parent_module="+parent_module;
    var position_append = $('#form_SubpanelQuickCreate_pe_internal_note').find('#EditView_tabs');
    if (parent_module == 'AOS_Quotes') {
        $.ajax({
            url: url,
            success: function (data) {
                var json_data = JSON.parse(data);
                $html_link =    '<div id="group_quote_checklist" class="row detail-view-row">'
                            +       '<div id="group_quote_checklist_c" class="col-xs-12 col-sm-12 detail-view-row-item">'
                            +       '</div>'
                            +       '<input type="hidden" name="quote_checklist_id" value="">'
                            +   '</div>';
                $(position_append).before($html_link);
                $link = '';
                $.each(json_data, function(key,value) {
                        if (key != parent_id) {
                            $link += "<li><input type='checkbox' name='checkbox_quotes' value='"+key+"'><span style='color:black;font-weight:700;padding:15px;'>"+ value +"</span></li><br>";
                        } 
                        // else {
                        //     $link += "<li><input type='checkbox' name='checkbox_quotes' checked= 'checked' value='"+key+"'><span style='color:black;font-weight:700;padding:15px;'>"+ value +"</span></li><br>";
                        // }
                });
                $('#group_quote_checklist_c').append($link);
                updateInputQuoteChecklistID();
            }
        });
    }
    // updateInputQuoteChecklistID();
    YAHOO.util.Event.addListener("group_quote_checklist",'change',function(){
        updateInputQuoteChecklistID();
    });
    // VUT-S-Add relate quote
    
    $("#create_call_back").on("click",function(e){
        var parent_id = $('#form_SubpanelQuickCreate_pe_internal_note').find('input[name="return_id"]').val();
        var parent_module = $('#form_SubpanelQuickCreate_pe_internal_note').find('input[name="return_module"]').val();
        $.ajax({
            url: "index.php?entryPoint=create_call_back_pe_internal_note&parent_id=" +parent_id +"&parent_module="+parent_module,
            success:function (data) {
                if(data != 'error'){
                    window.open('/index.php?module=Calls&action=EditView&record='+data.trim(),'_blank');
                }else{
                    window.open('/index.php?module=Calls&action=EditView&record=','_blank');
                }
            }
        })
    })  
});


//VUT-Function
function updateInputQuoteChecklistID() {
    var listIDQuoteInfo = new Array();
    $('#group_quote_checklist_c li input[name="checkbox_quotes"]').each(function(){
        if ($(this).is(":checked")) {
            listIDQuoteInfo.push($(this).val());
        }
    });
    $('input[name="quote_checklist_id"]').val(JSON.stringify(listIDQuoteInfo));
}