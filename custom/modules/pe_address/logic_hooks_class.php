<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class CreateFolderUpload
    {
        function before_save_method($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            if($old_fields == false ||$bean->installation_pictures_c == ""){
                $uuid = md5(uniqid(mt_rand(), true));
                $guid =  $prefix.substr($uuid,0,8)."-".
                substr($uuid,8,4)."-".
                substr($uuid,12,4)."-".
                substr($uuid,16,4)."-".
                substr($uuid,20,12);
                $bean->installation_pictures_c  = $guid;
                // $bean->save();
            }
        }
    }
