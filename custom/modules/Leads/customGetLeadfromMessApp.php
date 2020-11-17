<?php
   $db = DBManagerFactory::getInstance();
   $text_search = $_REQUEST['text_search'];
   $query  = "SELECT * FROM leads WHERE deleted = 0 AND (first_name like '%".$text_search."%' 
   OR last_name like '%".$text_search."%'
   OR CONCAT(first_name,' ',last_name) like '%".$text_search."%'
   OR phone_home LIKE '%$text_search%'
   OR phone_mobile LIKE '%$text_search%'
   OR phone_work LIKE '%$text_search%'
   OR phone_other LIKE '%$text_search%'
   OR phone_fax LIKE '%$text_search%')";
   $ret = $db->query($query);
   $leads_arr = array();
   $i=0;
   while($row = $db->fetchByAssoc($ret)){
       $leads_arr[] = $row;
   }
   
   echo isset($_GET['callback']) ? $_GET['callback'] . '('.json_encode($leads_arr).')' : json_encode($leads_arr);
?>