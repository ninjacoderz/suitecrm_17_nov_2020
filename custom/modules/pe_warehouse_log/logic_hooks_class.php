<?php
/**
 * User: thienpb
 * Date Updated: 6/12/2018 
 **/

    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class UpdateLineItem
    {   
        function before_save_method($bean, $event, $arguments){
            return;
            $old_fields = $bean->fetched_row;
            if($old_fields["po_purchase_order_pe_warehouse_log_1po_purchase_order_ida"] != $bean->po_purchase_order_pe_warehouse_log_1po_purchase_order_ida){
                $db = DBManagerFactory::getInstance();
                $sql_update_Line_Item = "UPDATE aos_line_item_groups SET deleted = 1 WHERE parent_id = '$bean->id'";
                $result = $db->query($sql_update_Line_Item);
                $sql_update_Stock_Item = "UPDATE pe_stock_items SET deleted = 1 WHERE parent_id = '$bean->id'";
                $result = $db->query($sql_update_Stock_Item);
                return;
            }
        }

        function after_relationship_delete_method($bean, $event, $arguments){
            if($arguments['relationship'] == 'pe_warehouse_log_pe_warehouse'){
                $db = DBManagerFactory::getInstance();
                $sql_update_stock_related = "UPDATE pe_warehouse_pe_stock_items_1_c SET deleted = 1 WHERE pe_warehouse_pe_stock_items_1pe_warehouse_ida = '".$arguments['related_id']."'";
                $db->query($sql_update_stock_related);
                return;
            }
            
        }
        function after_save_method($bean, $event, $arguments)
        {
            //logic

            $files = json_decode(html_entity_decode($bean->file_rename_c));
            $number = $bean->number;
            //$current_file_path = dirname(__FILE__);
            $current_file_path =  dirname(__FILE__) . '/../../include/SugarFields/Fields/Multiupload/server/php/files/' . $bean->installation_pdf_c ;
            $address = $bean->primary_address_street;

            // Thienpb fix for add city to name
            $city = $bean->primary_address_city;
            $address_city = $address.'_'.strtolower($city);

            
            $file_attachmens = array();
            $block_files_for_email = array();
            if(count($files)) foreach($files as $file){
                if($file->rename_option != 0){
                    $extension=end(explode(".", $file->file_name));
                    $new_name = "";
                    //print($file->rename_option);
                    if ($file->rename_option == "31"){
                            $new_name = $number."Methven_Package";
                    }
                    // If new name look like old name

                    if ($new_name.'.'.$extension == $file->file_name) return;
                    $i = 1;
                    $will_rename = $new_name;
                    while( !empty(glob($current_file_path.'/'.$will_rename."*")) || !empty(glob($current_file_path.'/'.$will_rename.("_".str_replace(' ', '_', $file->suffix))."*")) )
                    {
                        $will_rename = $new_name.$i;
                        if ($will_rename.".".$extension == $file->file_name) return;
                        $i++;
                    }

                    if(isset($file->suffix) && $file->suffix != "") {
                        $will_rename .= ("_".str_replace(' ', '_', $file->suffix));
                    }

                    $will_rename .= ('.'.$extension);
                    // If the file posted have same name that we generated ( this file is rename one time  )
                    if ($will_rename == $file->file_name) return;

                    rename($current_file_path."/".$file->file_name, $current_file_path."/". $will_rename);
                    rename($current_file_path."/thumbnail/".$file->file_name, $current_file_path."/thumbnail/". $will_rename);
                }
            }
                //$bean->save();
        }
    }

?>