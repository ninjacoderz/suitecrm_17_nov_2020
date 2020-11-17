<?php
//tu-code
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
        $db =  DBManagerFactory::getInstance();
        $sql = " UPDATE leads SET deleted = '1' WHERE first_name='$first_name' AND last_name ='$last_name' ORDER BY date_entered ASC ";
        $ret = $db->query($sql);

