<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/DynamicFields/templates/Fields/TemplateField.php');
class TemplateTimeStamp extends TemplateField{
    var $type='Timestamp';
    
    function get_field_def(){
        $def = parent::get_field_def();
        $def['dbType'] = 'varchar';
        $def['default'] = time(); //set default value = time()

        return $def;
    }



}

?>