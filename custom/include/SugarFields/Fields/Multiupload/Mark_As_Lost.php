<?php 
$db =  DBManagerFactory::getInstance();
$query = "UPDATE leads
    INNER JOIN leads_cstm ON  leads.id = leads_cstm.id_c 
    SET leads.status = 'Lost_No_Longer_Interested' 
    WHERE leads.deleted = 0 
        AND leads.status NOT IN ('Lost_Competitor','Lost_Uncontactable','Lost_Unsuitable_Roof','Lost_Enquiry_Only','Lost_No_Longer_Interested','Lost_Outside_Service_Area','Lost_Duplicate','Lost_Council','Lost_Reassigned_To_Solorgain')
        AND leads_cstm.sent_follow_up_on_old_quote__c = 1
            ";
$ret = $db -> query($query);


