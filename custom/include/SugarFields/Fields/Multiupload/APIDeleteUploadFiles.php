<?php

date_default_timezone_set('Australia/Sydney');
set_time_limit ( 0 );
ini_set('memory_limit', '-1');

$db = DBManagerFactory::getInstance();
//Get id and file name as key=> value that parent is email template
$sql = "
SELECT id, filename FROM `notes` WHERE 1
AND `parent_type` = 'Emails'
AND parent_id NOT IN (
    '12fb3725-0581-cf2c-18ed-5bbbfe6b0089',
    '180953f6-3dda-b10e-8f39-5bbbfe2bec38',
    '2316382f-a235-beb5-e12e-5c1862686a24',
    '3742953d-1318-43cb-00e3-5bbaab707bcd',
    '383cde5c-de72-3902-2a9a-5b5008c452d0',
    '3c143527-67a2-6190-1565-5d5b3809767e',
    '4f86b77f-94a4-1523-5194-59ed8f28e5c0',
    '58230a56-82cd-03ae-1d60-59eec0f8582d',
    '5a36a733-f6c1-39b3-a736-5a940feae542',
    '5ad80115-b756-ea3e-ca83-5abb005602bf',
    '766ffd48-8fa3-2d5a-55c1-59ed521e9cef',
    '7ad49555-86b7-1d86-93e3-5c876238eefb',
    '7c189f2f-19a9-c2c1-23fa-59f922602067',
    '8d17bb23-939f-d480-8a75-5a940f8a7fe5',
    '8d9e9b2c-e05f-deda-c83a-59f97f10d06a',
    '8f508557-71e9-9254-9762-59fa97523fc2',
    '98ea8922-6a3f-42b0-e426-5bd15b13c7cb',
    '9d79b285-37de-a9b5-2898-59ed2b99d217',
    '9d9f03ae-fe75-68d0-72ad-5d5b95cda15b',
    '9e6b03dd-52d2-a034-c9cb-5cb6aa76ab0d',
    '9f734c58-532d-86c0-f3a8-5a262bbf3b8c',
    'a0ea5c1f-b73d-da8d-da20-5d476f382688',
    'a4b812fe-b317-4c97-44d4-5bbc176b5274',
    'a51b3690-a5f6-7b43-fafd-59e7ebff1aef',
    'a8dbc136-588b-7213-9cbf-5bd0063f4de9',
    'acb45043-691d-9bfc-432e-59f9cc15a870',
    'ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5',
    'b230b9b0-9bf6-5c41-fa63-5d4ba9aaeaf6',
    'bcc9253b-663c-7100-1476-59ed7f8b6627',
    'bd902f3b-e281-6764-ac50-5d50bea88378',
    'c537f9f6-99d8-231d-3e80-5d50acd8af6a',
    'd89d6ef0-411d-395a-710f-5bd15b3f34c0',
    'dbf622ae-bb45-cb79-eb97-5cd287c48ac3',
    'ec302586-cd96-e843-bd9b-5b25c5b0b321',
    'fc0302d3-0953-5fa0-66c4-5d6478659062'
)
"; 

$ret = $db->query($sql);
$note_files = array();
while($row = $db->fetchByAssoc($ret)){
    array_push($note_files,$row['id']);
}

global $sugar_config;
$FOLDER_NAME  = $sugar_config['upload_dir'];

$file_array = scandir($FOLDER_NAME);
$file_array = array_diff($file_array, array('.', '..'));

foreach($file_array as $file){  
    if(is_link($FOLDER_NAME.$file)) {
        continue;
    } else {
        if(in_array($file,$note_files)){
            $source = $FOLDER_NAME.$file;
            $modified = filemtime($source);
            if($modified <= strtotime('-3 months')){
                //echo $source;
                unlink($source);
            }
        }else{
            continue;
        }
    }
}

die;