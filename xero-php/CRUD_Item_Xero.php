<?php
include 'xeroConfig.php';
//Param request
$method = $_REQUEST['method'];
$record = $_REQUEST['record'];
$from_action = $_REQUEST['from_action'];

$result_data = array(
    'msg' => '',
    'item_code_xero' => ''
);

$Product_CRM = new AOS_Products();
$Product_CRM->retrieve($record);
try {
    if ($Product_CRM->id != "") {
        $API_Custom_Xero = new API_Custom_Xero($xero);  
        $info_product = array(
            'name' => $Product_CRM->name,
            'item_code' => $Product_CRM->part_number,
            'description' => $Product_CRM->description,
            'price' => $Product_CRM->price,
            'cost' => $Product_CRM->cost,
            'category_product' => $Product_CRM->aos_product_category_id,
        );
        //create/ update invoice
       
        if ($method == 'create' || $method == 'update') {
            // create
            if ($Product_CRM->part_number != '' && $Product_CRM->item_code_xero == '') {
                $Item_Xero = $API_Custom_Xero->Create_Item($info_product);
            }else{
            // update
                $Item_Xero = $API_Custom_Xero->Get_Item_By_PartNumber($info_product['item_code']);
                $API_Custom_Xero->Update_Item($info_product,$Item_Xero);
            }

            $result_data['item_code_xero'] = $Item_Xero->getCode();
            $Product_CRM->item_code_xero = $result_data['item_code_xero'];
            $Product_CRM->save();
            echo json_encode($result_data);die();
        }
    }
} catch (\Throwable $th) {
    $message_error = $th;
    preg_match('/A validation exception occurred \((.*?)\)/s', $message_error, $msg_output);
    if (isset($msg_output[1])) {
        $result_data['msg'] = $msg_output[1];
        echo json_encode($result_data);die();
    }
}
