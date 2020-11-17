<?php
    return;
    $db = DBManagerFactory::getInstance();

    global $current_user;
    $user_id ='';
    $module = '';
    $entity_id = '';


    $user_id =  $current_user->id;
    $module = $_REQUEST['module'];
    $entity_id = $_REQUEST['entity_id'];
    $action = $_REQUEST['action'];

   if($action =='delete'){ //check acction when closed or refreshed tab
        $sql = "SELECT count_link FROM session_log WHERE user_id ='$user_id' AND module = '$module' AND entity_id ='$entity_id'";
        $result = $db->query($sql);
        $row = $db->fetchByAssoc($result);

        if($row['count_link'] > 1){ // check count current tab is open
            $count_link = $row['count_link'] - 1;
            $sql = "UPDATE session_log set count_link = $count_link WHERE user_id ='$user_id' AND module = '$module' AND entity_id ='$entity_id'";
            $result = $db->query($sql);
        }else{
            $sql = "DELETE FROM session_log WHERE user_id ='$user_id' AND module = '$module' AND entity_id ='$entity_id'";
            $result = $db->query($sql);
        }

   }else if($action == 'getData'){ //check have Exists user is editing
       $sql_select = "SELECT * FROM session_log WHERE module = '$module' AND entity_id ='$entity_id' AND user_id != '$user_id'";
       $result = $db->query($sql_select);
       if($result->num_rows >0){
           echo 'error';
       }
   }else{ // insert data
       $id = create_guid();
       $sql_select = "SELECT * FROM session_log WHERE user_id ='$user_id' AND module = '$module' AND entity_id ='$entity_id'";
       $result = $db->query($sql_select);
       $sql = '';

    if($result->num_rows >0){ 
        while($row = $db->fetchByAssoc($result)){
            if($row['entity_id'] == $entity_id && $row['module'] == $module && $row['module'] != ''){
                $count_link = $row['count_link'] + 1;
                $sql = "UPDATE session_log set module='$module',entity_id =  '$entity_id',count_link =  $count_link WHERE user_id ='$user_id' AND module = '$module' AND entity_id ='$entity_id'";
            }else{
                $sql = "INSERT INTO session_log (id,user_id,module,entity_id,count_link) VALUES('$id', '$user_id', '$module', '$entity_id',1)";
            }
        }
    }else{
        $sql = "INSERT INTO session_log (id,user_id,module,entity_id,count_link) VALUES('$id', '$user_id', '$module', '$entity_id',1)";
    }
       $result = $db->query($sql);
    }   
   
?>
