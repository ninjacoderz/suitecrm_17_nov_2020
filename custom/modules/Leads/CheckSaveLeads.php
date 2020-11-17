<?php 
  $first_name = $_GET['first_name'];
  $last_name  = $_GET['last_name'];
  $email = $_GET['email'];
  $db = DBManagerFactory::getInstance();
  $sql = "SELECT id FROM leads where first_name='$first_name'AND last_name= '$last_name' AND deleted= 0 ";
  $ret = $db->query($sql);
  if($ret->num_rows > 0){
    while($row = $ret ->fetch_assoc()){
        $id    = $row ['id'];
    }
  }
  $lead = new Lead();
  $lead ->retrieve($id);
  $email1= $lead->email1;
    if($email == $email1){
        $result = array(
            'id'=> $id,            
        );
        echo json_encode($result);
        die();
    }