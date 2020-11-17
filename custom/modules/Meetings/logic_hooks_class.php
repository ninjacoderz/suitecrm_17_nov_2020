<?php
    class MeetingStartDateChange {
        function before_save_method_changeStartDate($bean, $event, $arguments){
            $old_fields = $bean->fetched_row;
            if($old_fields['date_start'] != $bean->date_start){
                $invoices =  new AOS_Invoices();
                if($bean->aos_invoices_id_c != ''){
                    $invoices->retrieve($bean->aos_invoices_id_c);
                    if(isset($invoices->id) && isset($invoices->installation_date_c)){
                        $invoices->installation_date_c = $bean->date_start;
                        $invoices->due_date = strstr($bean->date_start,' ',true);
                        $invoices->save();
                    }
                }
            }
        }
    }
?>