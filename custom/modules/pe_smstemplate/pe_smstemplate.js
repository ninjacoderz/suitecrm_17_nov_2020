$(document).ready(function(){
    var group_variables = ['first_name','last_name','phone_number','quote_number','address','assigned_user_first_name','assigned_user_email','assigned_user_phone_number','product_type'];
    $html_appent = '<div class="col-xs-12 col-sm-12 edit-view-row-item"><div class="col-xs-12 col-sm-2 label" >Insert Variable :</div><select id="select_insert_value" name="select_insert_value">';
    $.each(group_variables, function(i,k){
        $html_appent += '<option value="$'+k+ '">$' + k +'</option>';
    })
    $html_appent += '</select><input id="button_insert" type="button" class="button" value="Insert" style=""></div>';
    $('#body_c').parents().eq(2).before($html_appent );
    $('body').find("#button_insert").on('click',function(){
        tinyMCE.activeEditor.execCommand('mceInsertRawHTML', false, $("#select_insert_value").val());
    }); 
});  
