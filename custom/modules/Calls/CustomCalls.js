$(function () {
    'use strict';
    // Generate uinique id
    
    $( document ).ready(function() {
        var record_id = $("#parent_id").val();
        var module_name = $("#parent_type").val();
        $.ajax({
            url: "?entryPoint=getContactPhoneNumber&module_name=" + module_name + "&record_id=" + record_id,
            context: document.body,
            async: false
        }).done(function (data) {
            $("#parent_type").parent().after("<b>"+data+"</b>");
        });

        //thienpb code event change for field `next call` -- thienpb update
        $("#next_call_c_date").change(function(){
            $("#status").val('Held');
        })
        YAHOO.util.Event.addListener("next_call_c_date", "change", function(){
            $("#status").val('Held');
        });
    });

        $(document).ready(function() {
            $('#description').parent().siblings('.label').append("<br> <select style='width:68%;' name='slb_suggested_note' id='slb_suggested_note' style='width:100%;'><option></option></select>");
            $('#description').parent().siblings('.label').append('<br> <button type="button" class="button primary" id="edit_suggested_note_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Insert Suggested Notes</button>');
            $('#description').parent().siblings('.label').append('<br> <button type="button" class="button primary" id="dialog_suggested_note_button"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Edit Suggested Notes</button>');
            window.cursorPos_description = $('#description').length;
            $( "#description" ).focusout(function() {
                window.cursorPos_description = $('#description').prop('selectionStart');
                console.log(window.cursorPos_description);
            });

            //add button +1,+7 , +30 days for next call 
            var add_button_1_7_30_days_next_call_c = '<button type="button" data-type="1" class="button button-get-day-next-call" title="Get 1 days" >+1 Day</button>';
            add_button_1_7_30_days_next_call_c +=  '<button type="button" data-type="7" class="button button-get-day-next-call" title="Get 7 days" >+7 Days</button>';
            add_button_1_7_30_days_next_call_c +=  '<button type="button" data-type="30" class="button button-get-day-next-call" title="Get 7 days" >+30 Days</button>';
            $('input[name="next_call_c"]').after(add_button_1_7_30_days_next_call_c);

            //add button +1, +7 days
            var add_button_1_7_days = '<button type="button" data-type="1" class="button button-get-day" title="Get 1 days" >+1 Day</button>';
            add_button_1_7_days +=  '<button type="button" data-type="7" class="button button-get-day" title="Get 7 days" >+7 Days</button>';
            $('input[name="date_start"]').after(add_button_1_7_days);
            //add button today held
            var add_button_today_held =  '<button type="button" data-type="TODAY_HELD" class="button button-get-day" title="TODAY HELD" >TODAY HELD</button>';
            $('input[name="date_start"]').after(add_button_today_held);
            var defaultDateTime = function(date){
                var now     = date;
                var year    = now.getFullYear();
                var month   = now.getMonth()+1; 
                var day     = now.getDate();
                var hours     = now.getHours();
                var minutes     = now.getMinutes();
                return {'day':day,'month':month,'year':year, 'hours': hours , 'minutes':minutes}
            }
            $('.button-get-day').click(function() {
                var type  = $(this).attr('data-type');
                switch (type) {
                    case 'TODAY_HELD':
                        var today   = new Date();
                        var month   = addZero(today.getMonth() + 1); 
                        var day     = addZero(today.getDate());
                        var year    = today.getFullYear();
                        var hours   = addZero(today.getHours());
                        var minutes = today.getMinutes();
                        var newdate     = day + "/" + month + "/" + year;  
                        minutes = Math.round(minutes/5)*5;
                        minutes = addZero(minutes);
                        $("#date_start_date").val(newdate);
                        $("#date_start").val(newdate+" "+hours+":"+ minutes);
                        $("#date_start_minutes").val(minutes);
                        $("#date_start_hours").val(hours);
                        $("#status").val('Held');
        
                        break;
                
                    default:
                        var date_changed =getDateTime(type);
                        var minutes = date_changed['minutes'];
                        minutes = Math.round(minutes/5)*5;
                        minutes = addZero(minutes);
                        $("#date_start_date").val(date_changed['date']);
                        $("#date_start_minutes").val(minutes);
                        $("#date_start_hours").val(addZero(date_changed['hours']));
                        $("#date_start").val(date_changed['date']+" "+date_changed['hours']+":"+date_changed['minutes']);
                        break;
                }
            });

            $('.button-get-day-next-call').click(function() {
                var type  = $(this).attr('data-type');
                var date_changed =getDateTime(type);
                var minutes = date_changed['minutes'];
                minutes = Math.round(minutes/15)*15;
                minutes = addZero(minutes);
                $("#next_call_c_date").val(date_changed['date']);
                $("#next_call_c_minutes").val(minutes);
                $("#next_call_c_hours").val(addZero(date_changed['hours']));
                $("#next_call_c").val(date_changed['date']+" "+date_changed['hours']+":"+date_changed['minutes']);
            });
            //Function Get Day
            var getDateTime = function(type){
                var date_return = '';
                var date = new Date();
                switch(type){
                    case '1':
                        var data = defaultDateTime(new Date(date.getTime() + 1*(24*60*60*1000)));
                        break;
                    case '7':
                        var data = defaultDateTime(new Date(date.getTime() + 7*(24*60*60*1000)));
                        break;
                    case '30':
                        var data = defaultDateTime(new Date(date.getTime() + 30*(24*60*60*1000)));
                        break;
                    default:
                        var data = defaultDateTime(new Date(date.getTime()));
                        break;
                }

                if(data['day'] < 10) {
                    data['day'] = '0'+data['day'];
                }
                if(data['month'] < 10) {
                    data['month'] = '0' + data['month'];
                }
                date_return = data['day']+'/'+data['month']+'/'+data['year']; 

                return {'date':date_return,'minutes':data['minutes'],'hours':data['hours']}

            }
            
            //end add button +1, +7 days
            /**Auto first run */
            $.ajax({
                url: 'index.php?entryPoint=CRUD_suggested_note' ,
                type: 'POST',
                data: 
                {
                    action: 'read',
                },
                async: true,
                success: function(result) {                         
                    render_select_template_suggested_note(result);
                }
            }); 
        
            $("#edit_suggested_note_button").click(function(){
                if($("#slb_suggested_note").val() != ''){
                    var cursorPos = $('#description').prop('selectionStart');
                    var v = $(document).find("#description").val();
                    var textBefore = v.substring(0,  cursorPos);
                    var textAfter  = v.substring(cursorPos, v.length);
                    var textContent = textBefore +' '+ $("#slb_suggested_note").val()+' '+ textAfter;
                    $('#description').val(textContent.trim());
                }else{
                    alert("Please select the description dropdown before click insert suggested note");
                }
            });

            $("#dialog_suggested_note_button").click(function(e){
                SUGAR.ajaxUI.showLoadingPanel();
                $("#ajaxloading_mask").css("position",'fixed');
                $.ajax({
                    url: 'index.php?entryPoint=CRUD_suggested_note' ,
                    type: 'POST',
                    data: 
                    {
                        action: 'read',
                    },
                    async: true,
                    success: function(result) {        
                        // debugger;                 
                        render_select_template_suggested_note(result);
                        SUGAR.ajaxUI.hideLoadingPanel();
                        $("#dialog_suggested_note").dialog("open");
                    }
                }); 
                return false;
            })

            $("#dialog_suggested_note").dialog({
                autoOpen: false,
                width: 712,
                height:478,
                buttons: {
                    Save: function(){
                        SUGAR.ajaxUI.showLoadingPanel();
                        $("#ajaxloading_mask").css("position",'fixed');
                        //create new
                        if($("#id_template_dialog_suggested_note").val() == '') {
                            if($("#title_dialog_suggested_note").val() == ''){
                                alert('Could you insert title please?');
                                SUGAR.ajaxUI.hideLoadingPanel();
                                return false;
                            };
                            $.ajax({
                                url: 'index.php?entryPoint=CRUD_suggested_note' ,
                                type: 'POST',
                                data: 
                                {
                                    id: $("#id_template_dialog_suggested_note").val(),
                                    action: 'create',
                                    content: encodeURIComponent($("#content_suggested_note").val()),
                                    title: encodeURIComponent($("#title_dialog_suggested_note").val())
                                },
                                success: function(result) {              
                                    render_select_template_suggested_note(result);
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                }
                            }); 
                        }   
                        //update
                        else{
                            $.ajax({
                                url: 'index.php?entryPoint=CRUD_suggested_note' ,
                                type: 'POST',
                                data: 
                                {
                                    id: $("#id_template_dialog_suggested_note").val(),
                                    action: 'update',
                                    content: encodeURIComponent($("#content_suggested_note").val()),
                                    title: encodeURIComponent($("#title_dialog_suggested_note").val())
                                },
                                success: function(result) {                         
                                    render_select_template_suggested_note(result);
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                }
                            }); 
                        }
                        // $("#description").val( $("#content_suggested_note").val());  
                        $(this).dialog('close');
                    },
                    Create: function(){
                        $("#id_template_dialog_suggested_note").val('');
                        $("#content_suggested_note").val('');
                        $("#title_dialog_suggested_note").val('');
                    },
                    Insert: function(){
                        var v = $(document).find("#description").val();
                        var textBefore = v.substring(0,   window.cursorPos_description);
                        var textAfter  = v.substring( window.cursorPos_description, v.length);
                        var textContent = textBefore +' '+ $("#content_suggested_note").val()+' '+ textAfter;
                        $('#description').val(textContent.trim());
                        $(this).dialog('close');
                    },
                    Delete: function(){
                        var ok = confirm('Do you want delete Suggested Note !');
                        if (ok){
                            SUGAR.ajaxUI.showLoadingPanel();
                            $("#ajaxloading_mask").css("position",'fixed');
                            $.ajax({
                                url: 'index.php?entryPoint=CRUD_suggested_note' ,
                                type: 'POST',
                                data: 
                                {
                                    id: $("#id_template_dialog_suggested_note").val(),
                                    action: 'delete',
                                    content: encodeURIComponent($("#content_suggested_note").val()),
                                    title: encodeURIComponent($("#title_dialog_suggested_note").val())
                                },
                                success: function(result) {                         
                                    render_select_template_suggested_note(result);
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                    $("#content_suggested_note").val('');
                                    $("#title_dialog_suggested_note").val('');
                                    $("#id_template_dialog_suggested_note").val('');
                                }
                            }); 
                        }
                    },
                    Cancel: function(){
                        $(this).dialog('close');
                    },
                }
            });
            var today   = new Date();
            var month   = addZero(today.getMonth() + 1); 
            var day     = addZero(today.getDate());
            var year    = today.getFullYear();
            var hours   = addZero(today.getHours());
            var minutes = today.getMinutes();
            var newdate     = day + "/" + month + "/" + year;   
            var dialogBody = '<button type="button" id="get_today" class="button" title="Get Today" >Today</button>';
            $('input[name="date_start"]').after(dialogBody);
            $("#get_today").click(function(){
                if(minutes<15){
                    minutes = $("#date_start_minutes option:eq(1)").val();
               }else if(minutes>=15 && minutes < 30){
                    minutes = $("#date_start_minutes option:eq(2)").val();
               }else if(minutes>=30 && minutes < 45){
                    minutes = $("#date_start_minutes option:eq(3)").val();
               }else{
                    minutes = $("#date_start_minutes option:eq(4)").val();      
               }
               $("#date_start_minutes").val(minutes);
               $("#date_start_date").val(newdate);
               $("#date_start_hours").val(hours);
               $("#date_start").val(newdate+" "+hours+":"+minutes);
               $("#date_start_date").val(newdate);
               $("#date_start_hours").val(hours);
            });
            $('#select_title_dialog_suggested_note').change(function(){
                var id = $('#select_title_dialog_suggested_note').val();
                if(id == '') return false;
                var title = $('#select_title_dialog_suggested_note option:selected').text();
                $("#title_dialog_suggested_note").val(title);
                $("#id_template_dialog_suggested_note").val(id);
                $("#content_suggested_note").val(window.data_suggested_note[id].content);
            });
            function addZero(i){
                if (i < 10) {
                  i = "0" + i;
                }
                return i; 
              }
            function render_select_template_suggested_note(result){
                var data_result = $.parseJSON(result);
                // debugger;
                window.data_suggested_note = data_result;
                $('#select_title_dialog_suggested_note').empty();
                $('#select_title_dialog_suggested_note').append($('<option>', {
                    value: '',
                    text: ''
                }));
                $(document).find("#slb_suggested_note").empty();
                $('#slb_suggested_note').append($('<option>', {
                    value: '',
                    text: ''
                }));
                $.each(data_result,function(k,v){
                    // debugger;
                    $('#select_title_dialog_suggested_note').append($('<option>', {
                        value: k,
                        text: v.title
                    }));
                    $(document).find("#slb_suggested_note").append($('<option>', {
                        value: v.content,
                        text: v.title,
                        IdTemplate: k
                    }));
                    });
                autosize.update($('#description'));
            }

            //create link edit quote 
            function render_edit_link_quote(){
                var quote_id = $("#aos_quotes_id_c").val();
                $('body').find('.link_edit_quote').remove();
                if(quote_id != '') $("#aos_quotes_id_c").parent().append('<br><a class="link_edit_quote" target="_blank" href="/index.php?module=AOS_Quotes&action=EditView&record='+quote_id +'">Edit Quote</a>')
            }
            render_edit_link_quote();
            YAHOO.util.Event.addListener("aos_quotes_id_c", "change", function(){
                render_edit_link_quote();
            });
        });

})