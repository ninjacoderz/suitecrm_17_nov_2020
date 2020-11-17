function EncodePanel(str){
	return str.replace(/\s/g,"_").replace(/,/g,"XX");
}

function QShowStatus(txt){
	$('#StatusDiv').html('<em style="color:red;font-size:16px;">'+txt+'</em>');
}
function QHideStatus(){
	$('#StatusDiv').html('');
}

function QdisplayError(str){
    QShowStatus(str);
    	setTimeout(function(){
            QHideStatus();
    },20000);
}

function QdisplaySuccess(str){
    QShowStatus(str);
    	setTimeout(function(){
            QHideStatus();
    },4000);
}


function SaveModuleConfig(button,conf_module){
	function onSuccess(res){
		QdisplaySuccess(SUGAR.language.get('app_strings','LBL_SAVED'));
	}
	
	var data = {conf_module:conf_module,response_type:'html'};
	data.chkshow_icon = $('#chkshow_icon').is(':checked')?'1':'0'
	data.chksubpanel_only = $('#chksubpanel_only').is(':checked')?'1':'0'
	QShowStatus(SUGAR.language.get('app_strings','LBL_SAVING'));
	
	$.ajax({
			url: 'index.php?module=Administration&action=configmodule_save&to_pdf=1',
			dataType: 'json',
			data: data,
			type: 'post', 
			cache: false,
			error: function(xmlHttpRequest, textStatus, errorThrown) {
				QShowStatus('An error occured while saving');
			},
			success: onSuccess // callback
	});
}	


function SaveFields(button,conf_module,conf_type,profile,profileName){
	function onSuccess(res){

        def=def2;
        if(!res){
        	QdisplayError('Unexpected error');
        }
        else if(button=='changeModule'){
            conf_get(moduletoredirect,typetoredirect,profiletoredirect,profileNametoredirect||defaultProfileName)
        }
        else if(conf_type=='module'){
            conf_tree();
            conf_get(conf_module,conf_type,profile,profileName||defaultProfileName);
        }else {
        	if (res.success){
				QdisplaySuccess(SUGAR.language.get('app_strings','LBL_SAVED'));

            	$('#conf_sortableD2 li').each(function(indx, element){
                	$(element).addClass('ui-state-default');
                	$(element).removeClass('ui-state-highlight');
            	});
	            $('#conf_sortableD1 li').each(function(indx, element){
    	            $(element).removeClass('ui-state-default');
        	        $(element).addClass('ui-state-highlight');
            	});
            	$('#conf_sortableD3 li').each(function(indx, element){
                	$(element).removeClass('ui-state-default');
                	$(element).addClass('ui-state-highlight');
            	});
            }
            else {
            	QdisplayError(res.error_messages.join('<br>'));
            }
        }
	}
	
	function getSelectedFields(selector){
		return selector.sortable('toArray').toString();	
	}
	
	if (conf_type == 'none') return;
	
	var data = {profile:(profile || '_default'),conf_module:conf_module,conf_type:conf_type,input_type:'JSON',response_type:'html',sel_fields:getSelectedFields($('#conf_sortableD1'))};
	try{
		data.extra_fields = getSelectedFields($('#conf_sortableD3'));
	}
	catch(err){}
	if (conf_type == 'list') {
		data.colorfield = $('#colorfield').val();
		data.totalsfield0 = $('#totalsfield0').val();
		data.totalsfield1 = $('#totalsfield1').val();
		data.totalsfield2 = $('#totalsfield2').val();
		data.totalsfunction0 = $('#totalsfunction0').val();
		data.totalsfunction1 = $('#totalsfunction1').val();
		data.totalsfunction2 = $('#totalsfunction2').val();
		data.groupfield = $('#groupfield').val();

		data.LV = $('#LV').is(':checked')?'1':'0';
		data.SP = $('#SP').is(':checked')?'1':'0';
		data.DASHLET = $('#DASHLET').is(':checked')?'1':'0';

	}
	else if (conf_type == 'module'){
		data.copy_from = $('#copy_from').val();
		data.new_profile = $('#new_profile').val();
	}
	else if (conf_type == 'fields') {
		data.syncCheckbox = $('#syncCheckbox').is(':checked')?'1':'0';
		data.chkshow_field_names = $('#chkshow_field_names').is(':checked')?'1':'0';
	}
	else if (conf_type == 'detail') {
		data.chkshow_field_names = $('#chkshow_field_names').is(':checked')?'1':'0';
	}


	QShowStatus(SUGAR.language.get('app_strings','LBL_SAVING'));
	
	$.ajax({
			url: 'index.php?module=Administration&action=configquickcrm_save&to_pdf=1',
			dataType: 'json',
			data: data,
			type: 'post', 
			cache: false,
			error: function(xmlHttpRequest, textStatus, errorThrown) {
				QShowStatus('An error occured while saving');
			},
			success: onSuccess // callback
	});
}	

function conf_get(module,type,profile,profile_name){
	function onSuccess(res){
		QHideStatus();
		$('#confpage').html(res);
	}
	
	QShowStatus(SUGAR.language.get('app_strings','LBL_LOADING'));
	
	var data = {profile_name:profile_name};
	$.ajax(
		{
			url: 'index.php?module=Administration&action=quickcrm_'+type+'&conf_module='+module+'&profile='+profile+'&to_pdf=true',
			dataType: 'html',
			data: data,
			type: 'post', 
			cache: false,
			error: function(xmlHttpRequest, textStatus, errorThrown) {
            	QdisplayError(textStatus);
 			},
			success: onSuccess // callback
		}
	);
}

function removeLayout(module,profile,profile_name,removeLabel){
	if (confirm(removeLabel+ ': '+profile_name)){
		$.ajax(
			{
				url: 'index.php?module=Administration&action=quickcrm_remove_view&profile='+profile+'&to_pdf=true',
				dataType: 'html',
				type: 'get', 
				cache: false,
				error: function(xmlHttpRequest, textStatus, errorThrown) {
            		QdisplayError(textStatus);
				},
				success: function(res){
					conf_tree();
				}
			}
		);
	}
}

function conf_tree(){
	function onSuccess(res){
		QHideStatus();
		$('#conftree').html(res);
	}
	
	QShowStatus(SUGAR.language.get('app_strings','LBL_LOADING'));
	
	$.ajax(
		{
			url: 'index.php?module=Administration&action=quickcrm_tree&to_pdf=true',
			dataType: 'html',
			type: 'get', 
			cache: false,
			error: function(xmlHttpRequest, textStatus, errorThrown) {
            	QdisplayError(textStatus);
			},
			success: onSuccess // callback
		}
	);
}

$(function() {
	conf_tree();
	// workaround for Chrome
	if ($('.sidebar').is(':visible'))
			$('#buttontoggle').click();
});
