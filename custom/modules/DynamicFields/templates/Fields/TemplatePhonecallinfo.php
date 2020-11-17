<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/DynamicFields/templates/Fields/TemplateText.php');
class TemplatePhonecallinfo extends TemplateText{
    var $type='phonecallinfo';

    function get_field_def(){
        $def = parent::get_field_def();
        $def['dbType'] = 'text';
        return $def;
    }

}

?>