<?php
// $record = '789f75aa-6918-dca7-d563-5ea248f01d4c';
// $module = 'Accounts';
require_once('include/DetailView/DetailView2.php');
$record = $_REQUEST['record'];
$module = $_REQUEST['module'];
$bean = BeanFactory::getBean($module,$record);
if ($bean->id) {
    $array_address = [
        'selector' => '',
        'contents' => [],
    ];
    $html_option = '';
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT pe_address.* FROM pe_address 
            LEFT JOIN accounts ON accounts.id = pe_address.billing_account_id
            WHERE accounts.id ='{$bean->id}' AND pe_address.deleted = 0
            ORDER BY pe_address.number DESC";
    $ret = $db->query($sql);
    while ($row = $db->fetchByAssoc($ret)) {
        $address = new pe_address();
        $address->retrieve($row['id']);
        if ($address->id) {
            $detailview = new DetailView2();
            $metadataFile = 'custom/modules/pe_address/metadata/detailviewdefs.php';
            $tpl = 'custom/modules/pe_address/subpanel_address_in_other_module.tpl';
            $ss = new Sugar_Smarty();
            $detailview->ss = $ss;
            $detailview->setup('pe_address',$address, $metadataFile , $tpl, false, 'detailviewdefs' );
            $detailview->process();
            $html_option .= "<option label='{$row['name']}' value='{$row['number']}'>{$row['number']}</option>";
            $array_address['contents'][$row['number']] = $detailview->display();
        }
    }
    $array_address['selector'] = "<span><strong>Select Address: </strong><select class='custom_fields' id='detail_pe_address' style='width:50%;' title=''>{$html_option}</select></span><div id='content_address_selected'></div>";
    echo json_encode($array_address);
}
die;
