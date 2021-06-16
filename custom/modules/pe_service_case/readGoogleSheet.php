<?php

$db = DBManagerFactory::getInstance();
$sql_set_count = "SET @count = 0;";
$result = $db->query($sql_set_count);
$sql_number = "UPDATE pe_pricing_options SET number = @count:=@count+1 ORDER BY date_entered ASC";
$result = $db->query($sql_number);
