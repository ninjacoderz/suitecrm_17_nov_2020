<?php
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
