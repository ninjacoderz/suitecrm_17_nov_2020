<?php
date_default_timezone_set('Australia/Melbourne');
$filename = 'severinfo.txt';
$time_file = strtotime(date("F d Y H:i:s", filemtime($filename)))+24*60*60;
$current_time = strtotime(date('F d Y H:i:s', time()));
$folder_info = shell_exec('df -h');
file_put_contents($filename, $folder_info);
