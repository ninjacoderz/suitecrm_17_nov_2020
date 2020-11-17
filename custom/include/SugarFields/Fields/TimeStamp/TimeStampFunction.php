<?php

function get_Ages($ss) {
    $timestamp = $ss->get_template_vars('value');    
    if ($timestamp != '') {
      $date_create = date('d/m/Y H:i:s',$timestamp);
      $date_create = DateTime::createFromFormat('d/m/Y H:i:s',$date_create);
    } else { //for old beans
      $bean = $ss->get_template_vars('bean');
      $date_create = DateTime::createFromFormat('d/m/Y H:i',$bean->date_entered);
      //update bean
      $field_name = $ss->get_template_vars('name_own_field');
      $bean->$field_name = $date_create->getTimestamp();
      $bean->save();
    }
    $today = new DateTime();
    $date_diff = date_diff($date_create, $today, true)->format('%a Days %h Hours %i Minutes');
    $ss->assign('date_diff', $date_diff);
    // var_dump($bean);
}