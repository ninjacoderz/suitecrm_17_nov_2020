<?php
global $current_user;
 date_default_timezone_set('Australia/Melbourne');
$vardefs_array = array(
    /*
    "id" => [
                "Label", 
                "value checkbox", 
                "id", 
                "calue textarea" 
            ], */
   "cl_quote_accepted" => [
        "Quote accepted", 
        "true", 
        "cl_quote_accepted", 
        date("d/m/Y H:i") . ' ' . $current_user->name
    ], 
    "cl_deposit_received" => [
        "Deposit Received", 
        "", 
        "cl_deposit_received", 
        "" 
    ],
    "cl_send_remittance_advice" => [
        "Send Remittance Advice", 
        "", 
        "cl_send_remittance_advice", 
        "" 
    ],
    "cl_seek_customer_preferred_install_date" => [
        "Seek customer preferred install date", 
        "", 
        "cl_seek_customer_preferred_install_date", 
        "" 
        ], 
    "cl_plumber_seek_install_date" => [
            "Pumber seek install date", 
            "", 
            "cl_plumber_seek_install_date", 
            "" 
        ], 
    "cl_electrician_seek_install_date" => [
                "Electrician seek install date", 
                "", 
                "cl_electrician_seek_install_date", 
                "" 
            ], 
    "cl_send_customer_install_date" => [
                    "Send customer install date", 
                    "", 
                    "cl_send_customer_install_date", 
                    "" 
                ], 
    "cl_Send_Plumber_PO" => [
                    "Send Plumber PO", 
                    "", 
                    "cl_Send_Plumber_PO", 
                    "" 
                    ], 
    "cl_Send_Electrician_PO" => [
                        "Send Electrician PO", 
                        "", 
                        "cl_Send_Electrician_PO", 
                        "" 
                    ], 
    "cl_Send_supply_PO" => [
                            "Send supply PO", 
                            "", 
                            "cl_Send_supply_PO", 
                            "" 
                        ], 
    "cl_Send_invoice_to_customer" => [
                            "Send Invoice To Customer", 
                            "", 
                            "cl_Send_invoice_to_customer", 
                            "" 
                        ], 
    "cl_Send_US7_Tips" => [
                            "Send US7 Tips", 
                            "", 
                            "cl_Send_US7_Tips", 
                            "" 
                        ], 
    "cl_Send_Sanden_Tips" => [
                            "Send Sanden Tips", 
                            "", 
                            "cl_Send_Sanden_Tips", 
                            "" 
                        ],
    "cl_Send_Sanden_STC_Survey" => [
                            "Send Sanden STC Survey", 
                            "", 
                            "cl_Send_Sanden_STC_Survey", 
                            "" 
                        ],  
    "cl_deposit_received_4_day_all_client" => [
                                "- 4 day call client", 
                                "", 
                                "cl_deposit_received_4_day_all_client", 
                                "" 
                            ],  
    "cl_deposit_received_4_day_call_installer" => [
                                "- 4 day call installer", 
                                "", 
                                "cl_deposit_received_4_day_call_installer", 
                                "" 
                            ],  
    "cl_deposit_received_1_day_call_client" => [
                                "- 1 day call client", 
                                "", 
                                "cl_deposit_received_1_day_call_client", 
                                "" 
                            ], 
    "cl_deposit_received_1_day_call_installer" => [
                                "- 1 day call installer", 
                                "", 
                                "cl_deposit_received_1_day_call_installer", 
                                "" 
                            ],
    "cl_install_day_call_client" => [
                                    "Install day call client", 
                                    "", 
                                    "cl_install_day_call_client", 
                                    "" 
                                ], 
    "cl_install_day_call_installer" => [
                                    "Install day call installer", 
                                    "", 
                                    "cl_install_day_call_installer", 
                                    "" 
                                ], 
    "cl_install_photos_uploaded" => [
                                    "Install Photos Uploaded", 
                                    "", 
                                    "cl_install_photos_uploaded", 
                                    "" 
                                ], 
    "cl_certificate_numbers_uploaded" => [
                                    "Certificate Numbers Uploaded", 
                                    "", 
                                    "cl_certificate_numbers_uploaded", 
                                    "" 
                                ], 
    "cl_serial_numbers_entered_for_tank_and_hp" => [
                                    "Serial Numbers Entered For Tank and HP", 
                                    "", 
                                    "cl_serial_numbers_entered_for_tank_and_hp", 
                                    "" 
                                ],
    // VuT- No show "Paperwork summited" in Invoices
    // "cl_paperwork_submitted" => [
    //                             "Paperwork submitted", 
    //                             "", 
    //                             "cl_paperwork_submitted", 
    //                             "" 
    //                             ], 
    "cl_Warehouse_log_updated" => [
                                    "Warehouse log updated", 
                                    "", 
                                    "cl_Warehouse_log_updated", 
                                    "" 
                                ], 
    "cl_client_follow_up_call_made" => [
                                    "Client follow up call made", 
                                    "", 
                                    "cl_client_follow_up_call_made", 
                                    "" 
                                ],                                
    "cl_Final_payment_received" => [
                                        "Final payment received", 
                                        "", 
                                        "cl_Final_payment_received", 
                                        "" 
                                    ], 
    "cl_Thankyou_letter_sent_to_client" => [
                                            "Thankyou letter sent to client", 
                                            "", 
                                            "cl_Thankyou_letter_sent_to_client", 
                                            "" 
    ],
    "cl_Send_out_the_Certificates" => [
                                            "Send out the Certificates", 
                                            "", 
                                            "cl_Send_out_the_Certificates", 
                                            "" 
                                        ] 
);