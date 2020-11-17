<?php 

if($_GET['session_id'] == $_COOKIE['PHPSESSID']) {
    die("1");
}
else die("0");