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

    class CreateInternalNoteForAddress
    {
        function after_save_method($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            global $current_user;
            // $format = 'Y-m-d H:i:s';
            // $date = DateTime::createFromFormat($format, $bean->date_modified);
            // // $test = DateTime::createFromFormat($format, "2020-09-28 17:51:57");
            // $date_note = $date->format("d/m/Y h:ia");

            if ($old_fields == false) { 
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT pe_internal_note.id FROM pe_internal_note 
                        LEFT JOIN pe_address_pe_internal_note_1_c ON pe_address_pe_internal_note_1_c.pe_address_pe_internal_note_1pe_internal_note_idb = pe_internal_note.id 
                        LEFT JOIN pe_internal_note_cstm ON pe_internal_note_cstm.id_c = pe_internal_note.id
                        WHERE pe_internal_note_cstm.type_inter_note_c  = 'status_updated'  
                        AND pe_internal_note.description ='Create New Address'
                        AND pe_address_pe_internal_note_1_c.pe_address_pe_internal_note_1pe_address_ida ='$bean->id'  
                        AND pe_internal_note.deleted = 0
                        ORDER BY `pe_internal_note`.`date_modified` DESC";    
                $ret = $db->query($sql);
                if ($ret->num_rows == 0) {
                    $bean_intenal_notes = new  pe_internal_note();
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = 'Create New Address';
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                    
                    $bean_intenal_notes->load_relationship('pe_address_pe_internal_note_1');
                    $bean_intenal_notes->pe_address_pe_internal_note_1->add($bean->id);
                }
            } else {
                if ($old_fields['assigned_user_id'] != $bean->assigned_user_id) {
                    $bean_intenal_notes = new  pe_internal_note();
                    if ($old_fields['assigned_user_id']) {
                        $old_user = new User();
                        $old_user->retrieve($old_fields['assigned_user_id']);
                        if ($old_user->id) {
                            $old_fields['assigned_user_name'] = $old_user->name;
                        }
                    }
                    $bean_intenal_notes->type_inter_note_c = 'status_updated';
                    $decription_internal_notes = "Change Assigned User from {$old_fields['assigned_user_name']} to {$bean->assigned_user_name}";
                    $bean_intenal_notes->description =  $decription_internal_notes;
                    $bean_intenal_notes->created_by = $current_user->id;
                    $bean_intenal_notes->save();
                    $bean_intenal_notes->load_relationship('pe_address_pe_internal_note_1');
                    $bean_intenal_notes->pe_address_pe_internal_note_1->add($bean->id);
                }
            }
        }
    }

