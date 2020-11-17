<?php
 //VUT-override config 2020/03/12
$layout_defs["Accounts"]["subpanel_setup"]['account_aos_invoices'] = array (
  'order' => 100,
  'module' => 'AOS_Invoices',
  'subpanel_name' => 'default',
  'sort_order' => 'desc',
  'sort_by' => 'number',
  'title_key' => 'AOS_Invoices',
  'get_subpanel_data' => 'aos_invoices',
);
