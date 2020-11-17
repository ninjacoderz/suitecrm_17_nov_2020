$(document).ready(function(){
    return;
    var url_module = '';
    var url_record = '';
    var url_action = '';
    var have_data = false;

    //set session storage url
    if(typeof(Storage) !== "undefined") {
       
        if(location.href.indexOf('module')>=0){
            localStorage.url = location.href;
        }else{
            localStorage.url = $("#absolute_path").text();
        }
    }

    //get param from url
    var url = decodeURIComponent(localStorage.url);
    matches_module = url.match('module' + '=([^&#]+)');
    if(matches_module !== null){
        url_module = matches_module[1];
    }
   
    matches_record = url.match('record' + '=([^&#]+)');
    if(matches_record !== null){
        url_record = matches_record[1];
    }

    matches_action = url.match('action' + '=([^&#]+)');
    if(matches_action !== null){
        if(matches_action[1] == 'ajaxui'){
            matches_action = url.match('&action' + '=([^&#]+)');
            url_action = matches_action[1];
        }else{
            url_action = matches_action[1];
        }

    }
   
    
    //set ajax before close or change url browser

    window.addEventListener("beforeunload", function (e) {        
        $.ajax({
            url: 'index.php?entryPoint=setSessionLog',
            type: 'POST',
            data:{'action':'delete','module':url_module.trim(),'entity_id':url_record.trim()},
            dataType: 'html',
            success: function(data){
                console.log('success!!!');
            }
        });
    })

    //call check ajax when action = editview
    if(url_action =='EditView'){
        $.ajax({
            url: 'index.php?entryPoint=setSessionLog',
            type: 'POST',
            data:{'action':'getData','module':url_module.trim(),'entity_id':url_record.trim()},
            dataType: 'html',
            success: function(data1){
                if(data1 == 'error'){
                    $('body').append('<div id="myModal_new" class="modal_new"><div class="modal-content_new"><span class="close">&times;</span><p>Exists user is editing in this entity.</p></div></div>');
                    $("#myModal_new").show();
                    have_data = true;
                }else{
                    have_data = false;
                }
            }
        });
    }

    // check if not exits user editing and update to db
    if(!have_data){
        $.ajax({
            url: 'index.php?entryPoint=setSessionLog',
            type: 'POST',
            data:{'action':'','module':url_module,'entity_id':url_record},
            dataType: 'html',
            success: function(data){
                console.log('success!!!');
            }
        });
    }
    // event for click close button
    $(document).on('click','.modal-content_new .close',function(){
        $("#myModal_new").hide();
        $(location).attr('href',("index.php?module="+url_module+"&action=index"));
    })

});
