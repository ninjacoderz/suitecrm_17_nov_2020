<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class Update_Name_And_Assigned
{
    function After_Update_Name_And_Assigned($bean, $event, $arguments)
    {
        global $current_user;
        if($bean->name == '' && $bean->assigned_user_id == ''){
            $bean->name =  $current_user->name;
            $bean->assigned_user_id =  $current_user->id;
            $bean->assigned_user_name =  $current_user->name;
            $bean->save();
        }

    }
}

class Save_Internal_Note_Relate_Quotes
{
    function After_Save_Internal_Note_Relate_Quotes($bean, $event, $arguments)
    {
        // global $current_user;
        // thêm điều kiện rquesst, cho chạy 1 lần thôi 
        $relate_quotes_id = json_decode(html_entity_decode($_REQUEST["quote_checklist_id"])); 
        $quote_main_id = $_REQUEST["return_id"]; //( làm gì ?) => bỏ 
        if ($relate_quotes_id != "" || $relate_quotes_id != null) {
            foreach ($relate_quotes_id as $key => $value) { // $value is quote'id => cú pháp
                if ($quote_main_id != $value) {
                    $bean->load_relationship('aos_quotes_pe_internal_note_1');
                    $bean->aos_quotes_pe_internal_note_1->add($value);
                }
            }
            $_REQUEST["quote_checklist_id"] = "";
        }
    }
}

?>