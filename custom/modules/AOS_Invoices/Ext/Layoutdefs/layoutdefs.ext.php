<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2019-05-24 12:28:45
$layout_defs["AOS_Invoices"]["subpanel_setup"]['aos_invoices_pe_internal_note_1'] = array (
  'order' => 100,
  'module' => 'pe_internal_note',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'date_entered',
  'title_key' => 'LBL_AOS_INVOICES_PE_INTERNAL_NOTE_1_FROM_PE_INTERNAL_NOTE_TITLE',
  'get_subpanel_data' => 'aos_invoices_pe_internal_note_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2017-12-27 14:27:35
$layout_defs["AOS_Invoices"]["subpanel_setup"]['aos_invoices_pe_service_case_1'] = array (
  'order' => 100,
  'module' => 'pe_service_case',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'number',
  'title_key' => 'LBL_AOS_INVOICES_PE_SERVICE_CASE_1_FROM_PE_SERVICE_CASE_TITLE',
  'get_subpanel_data' => 'aos_invoices_pe_service_case_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2017-12-27 14:27:35
$layout_defs["AOS_Invoices"]["subpanel_setup"]['aos_invoices_po_purchase_order_1'] = array (
  'order' => 100,
  'module' => 'PO_purchase_order',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'number',
  'title_key' => 'LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE',
  'get_subpanel_data' => 'aos_invoices_po_purchase_order_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 //VUT-override config 2020/03/12
 $layout_defs["AOS_Invoices"]["subpanel_setup"]['aos_quotes_aos_invoices'] = array (
  'order' => 99,
  'module' => 'AOS_Quotes',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'number',
  'title_key' => 'AOS_Quotes',
  'get_subpanel_data' => 'aos_quotes_aos_invoices',
  'top_buttons' =>
    array(
            0 =>
            array(
                  'widget_class' => 'SubPanelTopCreateButton',
            ),
            1 =>
            array(
                  'widget_class' => 'SubPanelTopSelectButton',
                  'popup_module' => 'AOS_Quotes',
                  'mode' => 'MultiSelect',
            ),
    ),

);


 // created: 2018-07-04 09:14:55
$layout_defs["AOS_Invoices"]["subpanel_setup"]['pe_smsmanager_aos_invoices'] = array (
  'order' => 100,
  'module' => 'pe_smsmanager',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PE_SMSMANAGER_AOS_INVOICES_FROM_PE_SMSMANAGER_TITLE',
  'get_subpanel_data' => 'pe_smsmanager_aos_invoices',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


//auto-generated file DO NOT EDIT
$layout_defs['AOS_Invoices']['subpanel_setup']['aos_invoices_pe_internal_note_1']['override_subpanel_name'] = 'AOS_Invoices_subpanel_aos_invoices_pe_internal_note_1';


//auto-generated file DO NOT EDIT
$layout_defs['AOS_Invoices']['subpanel_setup']['pe_smsmanager_aos_invoices']['override_subpanel_name'] = 'AOS_Invoices_subpanel_pe_smsmanager_aos_invoices';


//auto-generated file DO NOT EDIT
$layout_defs['AOS_Invoices']['subpanel_setup']['aos_invoices_po_purchase_order_1']['override_subpanel_name'] = 'AOS_Invoices_subpanel_aos_invoices_po_purchase_order_1';

?>