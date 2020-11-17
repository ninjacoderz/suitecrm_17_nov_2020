<?php
$sanden_equipment_type = $_POST['sanden_equipment_type'];

if ($sanden_equipment_type != '' && isset($sanden_equipment_type)) { /**Sanden error */
    $db = DBManagerFactory::getInstance();
        $array_result = array();
            $sql = 'SELECT id, error_code, error_content, manufacturer_diagnostic,manufacturer_judgement
                    FROM  pe_message_servicecase
                    INNER JOIN pe_message_servicecase_cstm ON pe_message_servicecase.id = pe_message_servicecase_cstm.id_c
                    WHERE deleted != 1 AND pe_message_servicecase_cstm.quote_type_c ="quote_type_sanden" AND pe_message_servicecase_cstm.sanden_equipment_type_c ="'.$sanden_equipment_type.'"            
                    ORDER BY pe_message_servicecase.error_code ASC
                    ';
        $ret = $db->query($sql);
        while ($row = $db->fetchByAssoc($ret)) {
            $array_result[] =  $row;
        }
        echo json_encode($array_result);

} else { /**Fault type */
    $action =  $_POST['action'];
    if(!isset($action) && $action == '') return;

    $id = $_POST['id'];
    $message = urldecode($_POST['message']);
    $title = urldecode($_POST['title']);

    $db = DBManagerFactory::getInstance();
    $result = array();
    switch ($action) {
        case 'read':
            break;
        case 'update':
            if($id == '' || $title == '') break;
            custom_message_servicecase($status ='update',$id,$title,$message);
            break;    
        case 'create':
            custom_message_servicecase($status ='create',$id,$title,$message);
            break;
        case 'delete':
            if($id == '') break;
            custom_message_servicecase($status ='delete',$id,$title,$message);
            // $sql =  "   DELETE FROM pe_message_servicecase 
            //             WHERE id='$id';
            //         ";
            // $db->query($sql);
            break;
        default:
            # code...
            break;
    }

    $sql =  "   SELECT id, name, message
                FROM pe_message_servicecase
                INNER JOIN pe_message_servicecase_cstm ON pe_message_servicecase.id = pe_message_servicecase_cstm.id_c
                WHERE pe_message_servicecase.deleted = 0 AND pe_message_servicecase_cstm.quote_type_c ='quote_type_solar'
            ";
    $ret = $db->query($sql);
    while ($row = $db->fetchByAssoc($ret)) {
        $result[$row['id']]['name'] = $row['name'];
        $result[$row['id']]['message'] = $row['message'];
    }    

    /**Sort name */
    $data_sort = [];
    $array_sort_name = [];
    foreach ($result as $key=>$value) {
        $array_sort_name[$key] = $value['name'];
    }
    asort($array_sort_name);
    foreach ($array_sort_name as $key=>$value) {
        $data_sort[$key] = $result[$key];
    }
    $json_encode_data = json_encode($data_sort);
    echo $json_encode_data;
}

//Function
function custom_message_servicecase($status,$id, $title,$message) {
    $bean = new pe_message_servicecase();
    if ($id != '') {
        $bean->retrieve($id);
        if ($status == 'delete' && $bean->id !='') {
            $bean->mark_deleted($id);
            $bean->save();
            return;
        }
        if ($status == 'update' && $bean->id !='') {
            $bean->name = $title;
            $bean->message = $message;
            $bean->save();
            return;
        }
    } else {
        $bean->name = $title;
        $bean->message = $message;
        $bean->save();
        return;
    }
}   