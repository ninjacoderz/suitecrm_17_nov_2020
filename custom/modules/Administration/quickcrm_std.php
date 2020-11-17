<?php
global $sugar_config,$moduleList;

// SugarCRM CE or SuiteCRM
$found_aos = (isset($sugar_config['aos']) || in_array ('AOS_Products_Quotes',$moduleList));

$QuickCRM_modules= array('Accounts','Contacts','Opportunities','Leads','Calls','Meetings','Tasks','Cases','Project','Notes','Documents');
$QuickCRM_simple_modules = array('Users','Currencies');
$QuickCRM_AddressDef = array('street','city','state','postalcode','country');
$QuickCRM_google_AddressDef = array('street','city','state','postalcode','country');

$QuickCRMTitleFields = array(
	'Accounts' => array(
			'name',
		),
	'Contacts' => array(
			'first_name',
			'last_name',
		),
	'Leads' => array(
			'first_name',
			'last_name',
		),
	'Opportunities' => array(
			'name',
		),
	'Calls' => array(
			'name',
		),
	'Meetings' => array(
			'name',
		),
	'Tasks' => array(
			'name',
		),
	'Notes' => array(
			'name',
		),
	'Documents' => array(
			'document_name',
		),
	'Cases' => array(
			'name',
		),
	'Project' => array(
			'name',
		),
/*
	'ProjectTask' => array(
			'name',
		),
*/
	'Employees' => array(
			'first_name',
			'last_name',
		),
); 

$QuickCRMDetailsFields = array(
	'Accounts' => array(
			'name',
			'phone_office',
			'website',
			'email1',
			'description',
		),
	'Contacts' => array(
			'first_name',
			'last_name',
			'title',
			'account_name',
			'email1',
			'phone_work',
			'phone_mobile',
			'description',
		),
	'Leads' => array(
			'first_name',
			'last_name',
			'title',
			'account_name',
			'status',
			'email1',
			'phone_work',
			'phone_mobile',
			'description',
		),
	'Opportunities' => array(
			'name','amount','account_name','date_closed','sales_stage','description',
		),
	'Calls' => array(
			'name','direction','status','date_start','duration_hours','duration_minutes','description','parent_name','reminder_time',
		),
	'Meetings' => array(
			'name','status','date_start','duration_hours','duration_minutes','description','parent_name','reminder_time',
		),
	'Tasks' => array(
			'name','status','date_start','date_due','priority','description','contact_name','parent_name',
		),
	'Notes' => array(
			'name','description','parent_name','filename'
		),
	'Documents' => array(
			'filename','document_name','description','status_id','category_id',
		),
	'Cases' => array(
		'name','case_number','status','priority','description','account_name'
		),
	'Emails' => array(
		'parent_name','name','date_sent','from_addr_name','to_addrs_names','cc_addrs_names','description_html',
		),
	'Project' => array(
			'name','status','priority','description'
	),
	'ProjectTask' => array(
			'name','status','priority','project_name','description'
	),
	'Employees' => array(
			'first_name',
			'last_name',
			'address_city',
			'address_state',
			'email1',
			'phone_work',
			'phone_mobile',
		),
	'jjwg_Markers' => array(
			"name","city","description","marker_image","jjwg_maps_lat","jjwg_maps_lng"
		),
	'Calls_Reschedule' => array(
			"reason",
		),
); 

$QuickCRMDefEdit = array(
	'Emails' => array(
		'parent_name','name','to_addrs_names','cc_addrs_names','bcc_addrs_names','description_html',
	),
);

$QuickCRMDefTotals = array(
	'Opportunities' => array(
			array('field' => 'amount','fnct' => array('SUM')),
		),
	'AOS_Quotes' => array(
			array('field' => 'total_amt','fnct' => array('SUM')),
		),
	'AOS_Invoices' => array(
			array('field' => 'total_amt','fnct' => array('SUM')),
		),
);
$QuickCRMDefGroupby = array(
	'Opportunities' => "sales_stage",
	'AOS_Quotes' => "stage",
);

$QuickCRMDefSearch = array(
	'Contacts' => array(
			'email1',
		),
	'Leads' => array(
			'status',
		),
	'Opportunities' => array(
			'date_closed','sales_stage',
		),
	'Calls' => array(
			'status','date_start',
		),
	'Meetings' => array(
			'status','date_start',
		),
	'Tasks' => array(
			'status','date_due','priority',
		),
	'Cases' => array(
		'status','priority',
		),
	'ProjectTask' => array(
			'status','priority',
	),
	'Documents' => array(
			'status_id','category_id',
		),
	'jjwg_Markers' => array(
			"city","marker_image"
		),

); 

$QuickCRMDefList = array(
	'Accounts' => array(
			"billing_address_city","billing_address_state"
		),
	'Contacts' => array(
			"account_name","title"
		),
	'Leads' => array(
			"account_name","title",'status',
		),
	'Opportunities' => array(
			"amount","account_name","sales_stage","date_closed"
		),
	'Calls' => array(
			'status','parent_name','date_start',
		),
	'Meetings' => array(
			'status','parent_name','date_start',
		),
	'Tasks' => array(
			'status','date_start','date_due','priority',
		),
	'Cases' => array(
		'case_number','status','priority',
		),
	'ProjectTask' => array(
			'status','priority',"assigned_user_name"
	),
	'Notes' => array(
			'filename'
	),
	'Employees' => array(
			"address_city","address_state"
		),
	'Emails' => array(
		"from_addr_name','to_addrs_names","date_entered"
		),
	'QCRM_SavedSearch' => array(
			"name","description","fields","shared"
		),
	'QCRM_Homepage' => array(
			"name","description","shared","dashlets","icons","hidden","creates"
		),
); 

$QuickCRMDefColors = array(
	'Opportunities' => 'sales_stage',
	'Cases' => 'status',
); 

$QuickCRMDefSubPanels = array(
	'Accounts' => array(
			'contacts',
			'opportunities',
			'calls',
			'meetings',
			'tasks',
			'notes'
		),
	'Contacts' => array(
			'calls',
			'meetings',
			'tasks',
			'emails',
			'opportunities',
			'notes'
		),
	'Leads' => array(
			'calls',
			'meetings',
			'tasks',
			'emails',
			'notes'
		),
	'Opportunities' => array(
			'contacts',
			'calls',
			'meetings',
			'tasks',
			'notes'
		),
	'Calls' => array(
			'contacts',
			'users',
			'leads',
			'notes'
		),
	'Meetings' => array(
			'contacts',
			'users',
			'leads',
			'notes'
		),
	'Cases' => array(
			'contacts',
		),
	'Tasks' => array(
			'notes'
		),
	'Notes' => array(
		),
	'Project' => array(
			'projecttask',
			'notes',
		),
	'ProjectTask' => array(
			'notes',
		),
	'Employees' => array(
		),
); 

$QuickCRMAddressesFields = array(
	'Accounts' => array(
			'billing',
			'shipping',
		),
	'Contacts' => array(
			'primary',
			'alt',
		),
	'Leads' => array(
			'primary',
			'alt',
		),
); 
$QuickCRMExtraFields = array(// field definitions required by the app
	'Accounts' => array(
			"billing_address_street","billing_address_city","billing_address_state","billing_address_city","billing_address_postalcode",
			"shipping_address_street","shipping_address_city","shipping_address_state","shipping_address_city","shipping_address_postalcode",
		),
	'Opportunities' => array(
			'amount_usdollar','date_closed'
		),
	'Notes' => array(
			'filename',
		),
	'Leads' => array(
			'converted',
		),
	'Documents' => array(
			'name','filename','revision','doc_type','last_rev_mime_type',
		),
	'Emails' => array(
			'parent_name','name','to_addrs_names','cc_addrs_names','bcc_addrs_names','description_html','type',
		),
	'jjwg_Maps' => array(
			'parent_name','parent_type','distance','module_type','unit_type',
		),
	'Calls' => array(
			'date_end','reminder_time','reminder_checked','repeat_type','repeat_interval','repeat_count','repeat_dow','repeat_until','recurring_source','repeat_parent_id',
		),
	'Meetings' => array(
			'date_end','reminder_time','reminder_checked','repeat_type','repeat_interval','repeat_count','repeat_dow','repeat_until','recurring_source','repeat_parent_id',
		),
	'Employees' => array(
		),
	'SugarFeed' => array(
			"created_by_name","related_module","related_id"
		),
	'jjwg_Markers' => array(
			"city","description","jjwg_maps_lat","jjwg_maps_lng","marker_image"
		),
	'QCRM_Tracker' => array(
			'assigned_user_id','jjwg_maps_lat_c','jjwg_maps_lng_c','jjwg_maps_address_c','jjwg_maps_geocode_status_c'
		),
	'QCRM_SavedSearch' => array(
			"name","description","fields","shared"
		),
	'QCRM_Homepage' => array(
			"name","description","shared","dashlets","icons","hidden","creates"
		),
	'Calls_Reschedule' => array(
			"call_name",
		),
); 

$QuickCRMFieldDefs = array(// special field definitions (bugs in old versions of SuiteCRM/Sugar)
	'Emails' => array(
		'description_html' => array (
			'type'=> 'text',
			'label'=> 'LBL_HTML_BODY',
			'html'=> 'True',
			'source' => '',
		),
		'parent_name' => array (
			'type'=> 'parent',
			'label'=> 'LBL_EMAIL_RELATE',
			'id_name'=> 'parent_id',
			'id_type'=> 'parent_type',
			'options'=> 'record_type_display',
			'source' => '',
		),
		'from_addr_name' => array (
			'type'=> 'varchar',
			'label'=> 'LBL_FROM',
			'tagType' => 'email',
			'source' => '',
		),
		'to_addrs_names' => array (
			'type'=> 'varchar',
			'label'=> 'LBL_TO',
			'tagType' => 'email',
			'source' => '',
			'req' => true,
		),
		'cc_addrs_names' => array (
			'type'=> 'varchar',
			'label'=> 'LBL_CC',
			'tagType' => 'email',
			'source' => '',
		),
		'bcc_addrs_names' => array (
			'type'=> 'varchar',
			'label'=> 'LBL_BCC',
			'tagType' => 'email',
			'source' => '',
		),
	),
); 


$QuickCRM_ExcludedModules= array('Calls_Reschedule','AOD_IndexEvent','AOD_Index','AM_TaskTemplates','OutboundEmailAccounts','SurveyQuestionOptions','SurveyQuestions','SurveyResponses','SurveyQuestionResponses','QCRM_Homepage','QCRM_SavedSearch','QCRM_Tracker','SecurityGroups','EmailTemplates','Users','AOS_Products_Quotes','AOS_PDF_Templates','AOW_WorkFlow','Spots','jjwg_Address_Cache','jjwg_Areas','Favorites','Surveys','AM_ProjectTemplates','AOBH_BusinessHours','AOR_Scheduled_Reports');
if (!file_exists("custom/service/vAlineaSolReports/rest.php")) $QuickCRM_ExcludedModules[]= 'asol_Reports';
$QuickCRM_ExcludedFields= array (
	'Employees' => array (
		'user_name',
		'user_hash',
		'system_generated_password',
		'pwd_last_changed',
		'authenticate_id',
		'sugar_login',
		'is_admin',
		'external_auth_only',
		'receive_notifications',
		'portal_only',
		'show_on_employees',
		'is_group',
		'messenger_type',
		'email_link_type',
	),
	'Emails' => array (
		'uid',
	),
);

if ($found_aos){
	if (isset($sugar_config['aos'])) {
		$aos_version = $sugar_config['aos']['version'];
	}
	else {
		$aos_version = '5.1';
	}
	
	$QuickCRM_simple_modules[] = 'AOS_PDF_Templates';
	
	// Begin AOS Support
	$QuickCRMTitleFields['AOS_Quotes'] = array(
			'name',
		);
		
	$QuickCRMDetailsFields['AOS_Quotes'] = array(
			'name','number','billing_account','billing_contact','stage',"expiration",'total_amt'
		);
		
	$QuickCRMDefList['AOS_Quotes'] = array(
			'number','billing_account','total_amt','stage'
		);

	$QuickCRMDefSearch['AOS_Quotes'] = array(
			'stage',
		);
		
	$QuickCRMDefColors['AOS_Quotes'] = 'stage';

	$QuickCRMExtraFields['AOS_Quotes'] = array(
			"number","total_amt","discount_amount","subtotal_amount","subtotal_tax_amount","tax_amount","tax_amount","total_amount"
		);
		
	$QuickCRMAddressesFields['AOS_Quotes'] = array(
			'billing',
			'shipping',
		);

	$QuickCRMDefSubPanels['AOS_Quotes'] = array(
		);

	$QuickCRMTitleFields['AOS_Invoices'] = array(
			'name',
		);
		
	$QuickCRMDetailsFields['AOS_Invoices'] = array(
			'name','number','billing_account','billing_contact','invoice_date','status','total_amt',
		);
		
	$QuickCRMDefList['AOS_Invoices'] = array(
			'number','billing_account','total_amt'
		);
		
	$QuickCRMDefSearch['AOS_Invoices'] = array(
			'status',
			'invoice_date',
		);
		
	$QuickCRMDefColors['AOS_Invoices'] = 'status';

	$QuickCRMExtraFields['AOS_Invoices'] = array(
			"number","total_amt","discount_amount","subtotal_amount","subtotal_tax_amount","tax_amount","tax_amount","total_amount"
		);
		
	$QuickCRMAddressesFields['AOS_Invoices'] = array(
			'billing',
			'shipping',
		);

	$QuickCRMDefSubPanels['AOS_Invoices'] = array(
		);
/*
	$QuickCRMTitleFields['AOS_Products_Quotes'] = array(
			'name',
		);
*/		
	$QuickCRMDetailsFields['AOS_Products_Quotes'] = array(
			'product_qty','product_list_price','discount','product_discount','product_discount_amount','product_unit_price','vat','vat_amt','product_total_price'
		);
		
	$QuickCRMExtraFields['AOS_Products_Quotes'] = array( // hidden or required fields
		'name','product_id','parent_name','number','product_qty','product_list_price','discount','product_discount','product_discount_amount','product_unit_price','vat','vat_amt','product_total_price',
		); 

	$QuickCRMTitleFields['AOS_Products'] = array(
			'name',
		);
		
	$QuickCRMDetailsFields['AOS_Products'] = array(
			'name','price','description',
		);
		
	$QuickCRMDefSearch['AOS_Products'] = array(
		);
		
	$QuickCRMDefList['AOS_Products'] = array(
			"part_number","price",
		);

	$QuickCRMExtraFields['AOS_Products'] = array(
			"part_number","price","description",
		);

	if ($aos_version > '5.2') {
		array_push($QuickCRMExtraFields['AOS_Products_Quotes'],'group_name','group_id');
	}
	if ($aos_version > '5.3') {
		array_push($QuickCRMExtraFields['AOS_Quotes'],"shipping_amount","shipping_tax_amt","shipping_tax");
		array_push($QuickCRMExtraFields['AOS_Invoices'],"shipping_amount","shipping_tax_amt","shipping_tax");
		array_push($QuickCRMDetailsFields['AOS_Products'],'aos_product_category_name');
		array_push($QuickCRMDefList['AOS_Products'],'aos_product_category_name');
		$QuickCRMTitleFields['AOS_Product_Categories'] = array(
			'name',
		);
		array_unshift($QuickCRMDetailsFields['AOS_Products'],'part_number');
		array_unshift($QuickCRMDefSearch['AOS_Products'],'part_number');
		array_unshift($QuickCRMDefList['AOS_Products'],'part_number');
		
		array_unshift($QuickCRMDetailsFields['AOS_Products_Quotes'],'part_number');
	}
}
// END AOS SUPPORT

if (isset($sugar_config['suitecrm_version'])){
	$QuickCRMDefEdit['Cases'] = array(
		'name','case_number','state','status','priority','description','account_name','update_text','internal',
	);
	$QuickCRMDetailsFields['Cases'] = array(
		'name','case_number','state','status','priority','description','account_name','aop_case_updates_threaded'
	);
	$QuickCRMDefSearch['Cases'] = array(
			'state','status','priority',
	);
}

foreach ($QuickCRMTitleFields as $module=>$contents){
	if (!in_array($module,$QuickCRM_modules) && $module !='Employees'){
		array_push($QuickCRM_modules,$module); // add custom modules
	}
}

if (file_exists("custom/QuickCRM/fielddefs.php")){
	include('custom/QuickCRM/fielddefs.php');
}


?>