$(document).ready(function(){
    $('#sms_signature_c').parents().siblings('.label').append('<br> <button class="button primary" id="dialog_setting_sms_signture_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span><img src="themes/default/images/icon_email_settings.gif" align="absmiddle" border="0"> Setting Signature SMS</button>'); 
  
    $("#dialog_setting_sms_signture_button").click(function(e){
        SUGAR.ajaxUI.showLoadingPanel();
        $("#ajaxloading_mask").css("position",'fixed');
        $.ajax({
            url: 'index.php?entryPoint=CRUD_SMS_Signture' ,
            type: 'POST',
            data: 
            {
                action: 'read',
            },
            async: true,
            success: function(result) {                   
                render_select_sms_signture(result);
                SUGAR.ajaxUI.hideLoadingPanel();
                $("#dialog_sms_signture").dialog("open");
            }
        }); 
        return false;
    })

    $("#dialog_sms_signture").dialog({
        autoOpen: false,
        width: 712,
        height:478,
        buttons: {
            Save: function(){
                SUGAR.ajaxUI.showLoadingPanel();
                $("#ajaxloading_mask").css("position",'fixed');
                //create new
                if($("#id_template_dialog_sms_signture").val() == '') {
                    if($("#title_dialog_sms_signture").val() == ''){
                        alert('Could you insert title please?');
                        SUGAR.ajaxUI.hideLoadingPanel();
                        return false;
                    };
                    $.ajax({
                        url: 'index.php?entryPoint=CRUD_SMS_Signture' ,
                        type: 'POST',
                        data: 
                        {
                            id: $("#id_template_dialog_sms_signture").val(),
                            action: 'create',
                            content: encodeURIComponent($("#content_sms_signture").val()),
                            title: encodeURIComponent($("#title_dialog_sms_signture").val())
                        },
                        success: function(result) {              
                            render_select_sms_signture(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                        }
                    }); 
                }   
                //update
                else{
                    $.ajax({
                        url: 'index.php?entryPoint=CRUD_SMS_Signture' ,
                        type: 'POST',
                        data: 
                        {
                            id: $("#id_template_dialog_sms_signture").val(),
                            action: 'update',
                            content: encodeURIComponent($("#content_sms_signture").val()),
                            title: encodeURIComponent($("#title_dialog_sms_signture").val())
                        },
                        success: function(result) {                         
                            render_select_sms_signture(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                        }
                    }); 
                }
            },
            Create: function(){
                $("#id_template_dialog_sms_signture").val('');
                $("#content_sms_signture").val('');
                $("#title_dialog_sms_signture").val('');
            },
            Delete: function(){
                var ok = confirm('Do you want delete SMS Signture !');
                if (ok){
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    $.ajax({
                        url: 'index.php?entryPoint=CRUD_SMS_Signture' ,
                        type: 'POST',
                        data: 
                        {
                            id: $("#id_template_dialog_sms_signture").val(),
                            action: 'delete',
                            content: encodeURIComponent($("#content_sms_signture").val()),
                            title: encodeURIComponent($("#title_dialog_sms_signture").val())
                        },
                        success: function(result) {                         
                            render_select_sms_signture(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                            $("#content_sms_signture").val('');
                            $("#title_dialog_sms_signture").val('');
                            $("#id_template_dialog_sms_signture").val('');
                        }
                    }); 
                }
            },
            Cancel: function(){
                $(this).dialog('close');
            },
        }
    });

    $('#select_title_dialog_sms_signture').change(function(){
        var id = $('#select_title_dialog_sms_signture').val();
        if(id == '') return false;
        var title = $('#select_title_dialog_sms_signture option:selected').text();
        $("#title_dialog_sms_signture").val(title);
        $("#id_template_dialog_sms_signture").val(id);
        $("#content_sms_signture").val(window.data_sms_signture[id].content);
    });

    function render_select_sms_signture(result){
        var data_result = $.parseJSON(result);
        window.data_sms_signture = data_result;
        $('#select_title_dialog_sms_signture').empty();
        $('#select_title_dialog_sms_signture').append($('<option>', {
            value: '',
            text: ''
        }));
        $.each(data_result,function(k,v){
            $('#select_title_dialog_sms_signture').append($('<option>', {
                value: k,
                text: v.title
            }));
        });
    };

})