{"def_title_fields":{"Accounts":["name"],"Contacts":["first_name","last_name"],"Opportunities":["name"],"Leads":["first_name","last_name"],"Calls":["name"],"Meetings":["name"],"Tasks":["name"],"Cases":["name"],"Project":["name"],"ProjectTask":["name"],"Notes":["name"],"Documents":["document_name"],"AOR_Reports":["name"],"pe_warehouse":["name"],"Emails":["name"],"Employees":["first_name","last_name"],"AOS_Quotes":["name"],"PO_purchase_order":["name"],"AOS_Invoices":["name"],"pe_warehouse_log":["name"],"AOS_Products":["name"],"AOS_Product_Categories":["name"]},"def_details_fields":{"Accounts":["name","phone_office","website","email1","description"],"Contacts":["first_name","last_name","title","account_name","email1","phone_work","phone_mobile","description"],"Opportunities":["name","amount","account_name","date_closed","sales_stage","description"],"Leads":["first_name","last_name","title","account_name","status","email1","phone_work","phone_mobile","description"],"Calls":["name","direction","status","date_start","duration_hours","duration_minutes","description","parent_name","reminder_time"],"Meetings":["name","status","date_start","duration_hours","duration_minutes","description","parent_name","reminder_time"],"Tasks":["name","status","date_start","date_due","priority","description","contact_name","parent_name"],"Cases":["name","case_number","state","status","priority","description","account_name","aop_case_updates_threaded"],"Project":["name","status","priority","description"],"ProjectTask":["name","status","priority","project_name","description"],"Notes":["name","description","parent_name","filename"],"Documents":["filename","document_name","description","status_id","category_id"],"Emails":["parent_name","name","date_sent","from_addr_name","to_addrs_names","cc_addrs_names","description_html"],"Employees":["first_name","last_name","address_city","address_state","email1","phone_work","phone_mobile"],"AOS_Quotes":["name","number","billing_account","billing_contact","stage","expiration","total_amt"],"AOS_Invoices":["name","number","billing_account","billing_contact","invoice_date","status","total_amt"],"AOS_Products":["part_number","name","price","description","aos_product_category_name"]},"modules":["Accounts","Contacts","Opportunities","Leads","Calls","Meetings","Tasks","Cases","Project","ProjectTask","Notes","Documents","AOR_Reports","pe_warehouse","Emails","Employees","AOS_Quotes","PO_purchase_order","AOS_Invoices","pe_warehouse_log","AOS_Products","AOS_Product_Categories"],"fields":{"Accounts":["name","phone_office","phone_fax","website","email1","description","$ADDbilling","$ADDshipping"],"Contacts":["first_name","last_name","title","account_name","email1","phone_work","phone_mobile","description","$ADDprimary","$ADDalt"],"Opportunities":["name","amount","date_closed","sales_stage","account_name","description"],"Leads":["first_name","last_name","title","account_name","status","email1","phone_work","phone_mobile","description","$ADDprimary","$ADDalt"],"Calls":["name","direction","status","date_start","duration_hours","duration_minutes","description","parent_name"],"Meetings":["name","status","date_start","duration_hours","duration_minutes","description","parent_name"],"Tasks":["name","status","date_start","date_due","priority","description","contact_name","parent_name"],"Cases":["name","case_number","status","priority","description","account_name","update_text","internal"],"Project":["name","status","priority","description"],"ProjectTask":["name","status","priority","project_name","description"],"Notes":["name","description","filename"],"Documents":["document_name","description","status_id","category_id"],"Employees":["first_name","last_name","address_city","address_state","email1","phone_work","phone_mobile"],"AOS_Quotes":["name","number","billing_account","stage","expiration","total_amount","$ADDbilling","$ADDshipping"],"AOS_Invoices":["name","number","billing_account","due_date","total_amount","$ADDbilling","$ADDshipping"],"AOS_Products":["name","part_number","price","aos_product_category_name"],"AOS_Product_Categories":["name"],"AOR_Reports":["name"],"pe_warehouse":["name"],"Emails":["name","description","description_html","type"],"PO_purchase_order":["name"],"pe_warehouse_log":["name"]},"detail":{"Cases":["name","case_number","status","priority","description","account_name","aop_case_updates_threaded"]},"addresses":{"Accounts":["billing","shipping"],"Contacts":["primary","alt"],"Leads":["primary","alt"],"AOS_Quotes":["billing","shipping"],"AOS_Invoices":["billing","shipping"]},"search":{"Accounts":[],"Contacts":["email1"],"Opportunities":["date_closed","sales_stage"],"Leads":["status"],"Calls":["status","date_start"],"Meetings":["status","date_start"],"Tasks":["status","date_due","priority"],"Cases":["status","priority"],"Project":[],"ProjectTask":["status","priority"],"Notes":[],"Documents":["status_id","category_id"],"Employees":[],"AOS_Quotes":["number","stage"],"AOS_Invoices":["number","status"],"AOS_Products":["part_number"],"AOS_Product_Categories":[]},"list":{"Accounts":["billing_address_city","billing_address_state"],"Contacts":["account_name","title"],"Opportunities":["amount","account_name","sales_stage","date_closed"],"Leads":["status","account_name","title"],"Calls":["status","date_start"],"Meetings":["status","date_start"],"Tasks":["status","date_start","date_due","priority"],"Cases":["case_number","status","priority"],"Project":[],"ProjectTask":["status","priority","assigned_user_name"],"Notes":["filename"],"Documents":[],"Employees":["address_city","address_state"],"AOS_Quotes":["number","billing_account","total_amount","stage"],"AOS_Invoices":["number","billing_account","total_amount"],"AOS_Products":["part_number","part_number","price","aos_product_category_name"],"AOS_Product_Categories":[],"AOR_Reports":[],"pe_warehouse":[],"Emails":[],"PO_purchase_order":[],"pe_warehouse_log":[]},"marked":[],"subpanels":{"Accounts":["contacts","opportunities","calls","meetings","tasks","leads","notes"],"Contacts":["calls","meetings","tasks","opportunities","notes"],"Opportunities":["contacts","calls","meetings","tasks","leads","notes"],"Leads":["calls","meetings","tasks","notes"],"Calls":["contacts","users","leads","notes"],"Meetings":["contacts","users","leads","notes"],"Tasks":["notes"],"Cases":["tasks","notes","meetings","documents","calls","contacts","project"],"Project":["projecttask","notes"],"ProjectTask":["notes"],"Notes":[],"Documents":["leads","accounts","contacts","opportunities","cases"],"Employees":[],"AOS_Quotes":[],"AOS_Invoices":[],"AOS_Products":[],"AOS_Product_Categories":["aos_products","sub_categories"],"AOR_Reports":[],"pe_warehouse":[],"Emails":[],"PO_purchase_order":[],"pe_warehouse_log":[]},"rowsperpage":"20","rowspersubpanel":"5","rowsperdashlet":"5","groupusers":false,"groupmode":"SecurityGroups","groupviews":[],"share_search":"All","native_cal":true,"force_lock":false,"documents_sync":true,"audio_notes":true,"mod_def":{"Accounts":{"type":"company","table":"accounts"},"Contacts":{"type":"person","table":"contacts"},"Opportunities":{"type":"basic","table":"opportunities"},"Leads":{"type":"person","table":"leads"},"Calls":{"type":"basic","table":"calls"},"Meetings":{"type":"basic","table":"meetings"},"Tasks":{"type":"basic","table":"tasks"},"Cases":{"type":"issue","table":"cases"},"Project":{"type":"basic","table":"project"},"ProjectTask":{"type":"basic","table":"project_task"},"Notes":{"type":"basic","table":"notes"},"Documents":{"type":"basic","table":"documents"},"AOR_Reports":{"type":"basic","table":"aor_reports"},"pe_warehouse":{"type":"basic","table":"pe_warehouse"},"Emails":{"type":"basic","table":"emails"},"Employees":{"type":"person","table":"users"},"AOS_Quotes":{"type":"basic","table":"aos_quotes"},"PO_purchase_order":{"type":"basic","table":"po_purchase_order"},"AOS_Invoices":{"type":"basic","table":"aos_invoices"},"pe_warehouse_log":{"type":"basic","table":"pe_warehouse_log"},"AOS_Products":{"type":"basic","table":"aos_products"},"AOS_Product_Categories":{"type":"basic","table":"aos_product_categories"}},"links":{"Accounts":{"members":{"module":"Accounts","vname":"LBL_MEMBERS","label":"LBL_MEMBERS"},"cases":{"module":"Cases","vname":"LBL_CASES","label":"Cases"},"tasks":{"module":"Tasks","vname":"LBL_TASKS","label":"Tasks"},"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"},"meetings":{"module":"Meetings","vname":"LBL_MEETINGS","label":"Meetings"},"calls":{"module":"Calls","vname":"LBL_CALLS","label":"LBL_CALLS"},"emails":{"module":"Emails","vname":"LBL_EMAILS","label":"Emails"},"documents":{"module":"Documents","vname":"LBL_DOCUMENTS_SUBPANEL_TITLE","label":"Documents"},"contacts":{"module":"Contacts","vname":"LBL_CONTACTS","label":"Contacts"},"opportunities":{"module":"Opportunities","vname":"LBL_OPPORTUNITY","label":"Opportunities"},"project":{"module":"Project","vname":"LBL_PROJECTS","label":"Project"},"leads":{"module":"Leads","vname":"LBL_LEADS","label":"Leads"},"aos_quotes":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES","label":"LBL_AOS_QUOTES"},"po_purchase_order":{"module":"PO_purchase_order","vname":"LBL_PO_PURCHASE_ORDER","label":"PO_purchase_order"},"aos_invoices":{"module":"AOS_Invoices","vname":"LBL_AOS_INVOICES","label":"AOS_Invoices"},"accounts_aos_quotes_1":{"module":"AOS_Quotes","vname":"LBL_ACCOUNTS_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE","label":"LBL_ACCOUNTS_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE"},"calls_accounts_1":{"module":"Calls","vname":"LBL_CALLS_ACCOUNTS_1_FROM_CALLS_TITLE","label":"LBL_CALLS_ACCOUNTS_1_FROM_CALLS_TITLE","id_name":"calls_accounts_1calls_ida"},"calls_accounts_1calls_ida":{"module":"Calls","vname":"LBL_CALLS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE","label":"LBL_CALLS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE"}},"Contacts":{"opportunities":{"module":"Opportunities","vname":"LBL_OPPORTUNITIES","label":"Opportunities"},"calls":{"module":"Calls","vname":"LBL_CALLS","label":"Calls"},"cases":{"module":"Cases","vname":"LBL_CASES","label":"Cases"},"direct_reports":{"module":"Contacts","vname":"LBL_DIRECT_REPORTS","label":"LBL_DIRECT_REPORTS"},"emails":{"module":"Emails","vname":"LBL_EMAILS","label":"Emails"},"documents":{"module":"Documents","vname":"LBL_DOCUMENTS_SUBPANEL_TITLE","label":"Documents"},"leads":{"module":"Leads","vname":"LBL_LEADS","label":"Leads"},"meetings":{"module":"Meetings","vname":"LBL_MEETINGS","label":"Meetings"},"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"},"project":{"module":"Project","vname":"LBL_PROJECTS","label":"LBL_PROJECTS"},"tasks":{"module":"Tasks","vname":"LBL_TASKS","label":"Tasks"},"aos_quotes":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES","label":"AOS_Quotes"},"aos_invoices":{"module":"AOS_Invoices","vname":"LBL_AOS_INVOICES","label":"AOS_Invoices"},"project_contacts_1":{"module":"Project","vname":"LBL_PROJECT_CONTACTS_1_FROM_PROJECT_TITLE","label":"LBL_PROJECT_CONTACTS_1_FROM_PROJECT_TITLE"}},"Opportunities":{"contacts":{"module":"Contacts","vname":"LBL_CONTACTS","label":"Contacts"},"tasks":{"module":"Tasks","vname":"LBL_TASKS","label":"Tasks"},"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"},"meetings":{"module":"Meetings","vname":"LBL_MEETINGS","label":"Meetings"},"calls":{"module":"Calls","vname":"LBL_CALLS","label":"Calls"},"emails":{"module":"Emails","vname":"LBL_EMAILS","label":"Emails"},"documents":{"module":"Documents","vname":"LBL_DOCUMENTS_SUBPANEL_TITLE","label":"Documents"},"project":{"module":"Project","vname":"LBL_PROJECTS","label":"Project"},"leads":{"module":"Leads","vname":"LBL_LEADS","label":"Leads"},"currencies":{"module":"Currencies","vname":"LBL_CURRENCIES","label":"Currencies"},"aos_quotes":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES","label":"AOS_Quotes"},"opportunities_aos_invoices_1":{"module":"AOS_Invoices","vname":"LBL_OPPORTUNITIES_AOS_INVOICES_1_FROM_AOS_INVOICES_TITLE","label":"AOS_Invoices"},"opportunities_opportunities_1":{"module":"Opportunities","vname":"LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_L_TITLE","label":"LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_L_TITLE","id_name":"opportunities_opportunities_1opportunities_ida"},"opportunities_opportunities_1opportunities_ida":{"module":"Opportunities","vname":"LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_R_TITLE","label":"LBL_OPPORTUNITIES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_R_TITLE"}},"Leads":{"tasks":{"module":"Tasks","vname":"LBL_TASKS","label":"Tasks"},"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"},"meetings":{"module":"Meetings","vname":"LBL_MEETINGS","label":"Meetings"},"calls":{"module":"Calls","vname":"LBL_CALLS","label":"LBL_CALLS"},"emails":{"module":"Emails","vname":"LBL_EMAILS","label":"Emails"},"aos_quotes_leads_1":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES_LEADS_1_FROM_AOS_QUOTES_TITLE","label":"LBL_AOS_QUOTES_LEADS_1_FROM_AOS_QUOTES_TITLE","id_name":"aos_quotes_leads_1aos_quotes_ida"},"aos_quotes_leads_1aos_quotes_ida":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES_LEADS_1_FROM_AOS_QUOTES_TITLE","label":"LBL_AOS_QUOTES_LEADS_1_FROM_AOS_QUOTES_TITLE"},"aos_quotes_leads_2":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES_LEADS_2_FROM_AOS_QUOTES_TITLE","label":"LBL_AOS_QUOTES_LEADS_2_FROM_AOS_QUOTES_TITLE"},"calls_leads_1":{"module":"Calls","vname":"LBL_CALLS_LEADS_1_FROM_CALLS_TITLE","label":"LBL_CALLS_LEADS_1_FROM_CALLS_TITLE","id_name":"calls_leads_1calls_ida"},"calls_leads_1calls_ida":{"module":"Calls","vname":"LBL_CALLS_LEADS_1_FROM_LEADS_TITLE","label":"LBL_CALLS_LEADS_1_FROM_LEADS_TITLE"}},"Calls":{"leads":{"module":"Leads","vname":"LBL_LEADS","label":"LBL_LEADS"},"contacts":{"module":"Contacts","vname":"LBL_CONTACTS","label":"Contacts"},"users":{"module":"Users","vname":"LBL_USERS","label":"Users"},"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"},"calls_aos_quotes_1":{"module":"AOS_Quotes","vname":"LBL_CALLS_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE","label":"AOS_Quotes"},"calls_aos_invoices_1":{"module":"AOS_Invoices","vname":"LBL_CALLS_AOS_INVOICES_1_FROM_AOS_INVOICES_TITLE","label":"AOS_Invoices"},"calls_accounts_1":{"module":"Accounts","vname":"LBL_CALLS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE","label":"Accounts"},"calls_leads_1":{"module":"Leads","vname":"LBL_CALLS_LEADS_1_FROM_LEADS_TITLE","label":"LBL_CALLS_LEADS_1_FROM_LEADS_TITLE"}},"Meetings":{"contacts":{"module":"Contacts","vname":"LBL_CONTACTS","label":"Contacts"},"users":{"module":"Users","vname":"LBL_USERS","label":"Users"},"leads":{"module":"Leads","vname":"LBL_LEADS","label":"Leads"},"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"}},"Tasks":{"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"}},"Cases":{"tasks":{"module":"Tasks","vname":"LBL_TASKS","label":"Tasks"},"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"},"meetings":{"module":"Meetings","vname":"LBL_MEETINGS","label":"Meetings"},"emails":{"module":"Emails","vname":"LBL_EMAILS","label":"Emails"},"documents":{"module":"Documents","vname":"LBL_DOCUMENTS_SUBPANEL_TITLE","label":"Documents"},"calls":{"module":"Calls","vname":"LBL_CALLS","label":"Calls"},"contacts":{"module":"Contacts","vname":"LBL_CONTACTS","label":"Contacts"},"project":{"module":"Project","vname":"LBL_PROJECTS","label":"Project"}},"Project":{"accounts":{"module":"Accounts","vname":"LBL_ACCOUNTS","label":"Accounts"},"contacts":{"module":"Contacts","vname":"LBL_CONTACTS","label":"LBL_CONTACTS"},"opportunities":{"module":"Opportunities","vname":"LBL_OPPORTUNITIES","label":"Opportunities"},"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"},"tasks":{"module":"Tasks","vname":"LBL_TASKS","label":"Tasks"},"meetings":{"module":"Meetings","vname":"LBL_MEETINGS","label":"Meetings"},"calls":{"module":"Calls","vname":"LBL_CALLS","label":"Calls"},"emails":{"module":"Emails","vname":"LBL_EMAILS","label":"Emails"},"projecttask":{"module":"ProjectTask","vname":"LBL_PROJECT_TASKS","label":"ProjectTask"},"cases":{"module":"Cases","vname":"LBL_CASES","label":"Cases"},"project_users_1":{"module":"Users","vname":"LBL_PROJECT_USERS_1_FROM_USERS_TITLE","label":"Users"},"project_contacts_1":{"module":"Contacts","vname":"LBL_PROJECT_CONTACTS_1_FROM_CONTACTS_TITLE","label":"LBL_PROJECT_CONTACTS_1_FROM_CONTACTS_TITLE"},"aos_quotes_project":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES_PROJECT","label":"AOS_Quotes"}},"ProjectTask":{"notes":{"module":"Notes","vname":"LBL_NOTES","label":"Notes"},"tasks":{"module":"Tasks","vname":"LBL_TASKS","label":"Tasks"},"meetings":{"module":"Meetings","vname":"LBL_MEETINGS","label":"Meetings"},"calls":{"module":"Calls","vname":"LBL_CALLS","label":"Calls"},"emails":{"module":"Emails","vname":"LBL_EMAILS","label":"Emails"}},"Notes":[],"Documents":{"leads":{"module":"Leads","vname":"LBL_LEADS","label":"Leads"},"accounts":{"module":"Accounts","vname":"LBL_ACCOUNTS_SUBPANEL_TITLE","label":"Accounts"},"contacts":{"module":"Contacts","vname":"LBL_CONTACTS_SUBPANEL_TITLE","label":"Contacts"},"opportunities":{"module":"Opportunities","vname":"LBL_OPPORTUNITIES_SUBPANEL_TITLE","label":"Opportunities"},"cases":{"module":"Cases","vname":"LBL_CASES_SUBPANEL_TITLE","label":"Cases"}},"AOR_Reports":[],"pe_warehouse":{"pe_warehouse_log_pe_warehouse":{"module":"pe_warehouse_log","vname":"LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_LOG_TITLE","label":"pe_warehouse_log"}},"Emails":{"accounts":{"module":"Accounts","vname":"LBL_EMAILS_ACCOUNTS_REL","label":"Accounts"},"cases":{"module":"Cases","vname":"LBL_EMAILS_CASES_REL","label":"Cases"},"contacts":{"module":"Contacts","vname":"LBL_EMAILS_CONTACTS_REL","label":"Contacts"},"leads":{"module":"Leads","vname":"LBL_EMAILS_LEADS_REL","label":"Leads"},"opportunities":{"module":"Opportunities","vname":"LBL_EMAILS_OPPORTUNITIES_REL","label":"Opportunities"},"project":{"module":"Project","vname":"LBL_EMAILS_PROJECT_REL","label":"Project"},"projecttask":{"module":"ProjectTask","vname":"LBL_EMAILS_PROJECT_TASK_REL","label":"ProjectTask"},"tasks":{"module":"Tasks","vname":"LBL_EMAILS_TASKS_REL","label":"Tasks"},"users":{"module":"Users","vname":"LBL_EMAILS_USERS_REL","label":"Users"},"notes":{"module":"Notes","vname":"LBL_EMAILS_NOTES_REL","label":"Notes"},"meetings":{"module":"Meetings","vname":"LBL_EMAILS_MEETINGS_REL","label":"Meetings"}},"Employees":{"reportees":{"module":"Users","vname":"LBL_REPORTS_TO","label":"Users"},"project_users_1":{"module":"Project","vname":"LBL_PROJECT_USERS_1_FROM_PROJECT_TITLE","label":"Project"}},"AOS_Quotes":{"aos_quotes_project":{"module":"Project","vname":"LBL_AOS_QUOTES_PROJECT","label":"Project"},"aos_quotes_aos_invoices":{"module":"AOS_Invoices","vname":"LBL_AOS_QUOTES_AOS_INVOICES","label":"AOS_Invoices"},"accounts_aos_quotes_1":{"module":"Accounts","vname":"LBL_ACCOUNTS_AOS_QUOTES_1_FROM_ACCOUNTS_TITLE","label":"Accounts"},"aos_quotes_po_purchase_order_1":{"module":"PO_purchase_order","vname":"LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE","label":"PO_purchase_order"},"aos_quotes_leads_1":{"module":"Leads","vname":"LBL_AOS_QUOTES_LEADS_1_FROM_LEADS_TITLE","label":"LBL_AOS_QUOTES_LEADS_1_FROM_LEADS_TITLE","id_name":"aos_quotes_leads_1leads_idb"},"aos_quotes_leads_1leads_idb":{"module":"Leads","vname":"LBL_AOS_QUOTES_LEADS_1_FROM_LEADS_TITLE","label":"LBL_AOS_QUOTES_LEADS_1_FROM_LEADS_TITLE"},"aos_quotes_leads_2":{"module":"Leads","vname":"LBL_AOS_QUOTES_LEADS_2_FROM_LEADS_TITLE","label":"LBL_AOS_QUOTES_LEADS_2_FROM_LEADS_TITLE"},"calls_aos_quotes_1":{"module":"Calls","vname":"LBL_CALLS_AOS_QUOTES_1_FROM_CALLS_TITLE","label":"LBL_CALLS_AOS_QUOTES_1_FROM_CALLS_TITLE","id_name":"calls_aos_quotes_1calls_ida"},"calls_aos_quotes_1calls_ida":{"module":"Calls","vname":"LBL_CALLS_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE","label":"LBL_CALLS_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE"}},"PO_purchase_order":{"aos_invoices_po_purchase_order_1":{"module":"AOS_Invoices","vname":"LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_AOS_INVOICES_TITLE","label":"LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_AOS_INVOICES_TITLE","id_name":"aos_invoices_po_purchase_order_1aos_invoices_ida"},"aos_invoices_po_purchase_order_1aos_invoices_ida":{"module":"AOS_Invoices","vname":"LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE","label":"LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE"},"aos_quotes_po_purchase_order_1":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_AOS_QUOTES_TITLE","label":"LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_AOS_QUOTES_TITLE","id_name":"aos_quotes_po_purchase_order_1aos_quotes_ida"},"aos_quotes_po_purchase_order_1aos_quotes_ida":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE","label":"LBL_AOS_QUOTES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE"},"po_purchase_order_pe_warehouse_log_1":{"module":"pe_warehouse_log","vname":"LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_TITLE","label":"pe_warehouse_log"}},"AOS_Invoices":{"aos_quotes_aos_invoices":{"module":"AOS_Quotes","vname":"LBL_AOS_QUOTES_AOS_INVOICES","label":"AOS_Quotes"},"opportunities_aos_invoices_1":{"module":"Opportunities","vname":"LBL_OPPORTUNITIES_AOS_INVOICES_1_FROM_OPPORTUNITIES_TITLE","label":"LBL_OPPORTUNITIES_AOS_INVOICES_1_FROM_OPPORTUNITIES_TITLE","id_name":"opportunities_aos_invoices_1opportunities_ida"},"opportunities_aos_invoices_1opportunities_ida":{"module":"Opportunities","vname":"LBL_OPPORTUNITIES_AOS_INVOICES_1_FROM_AOS_INVOICES_TITLE","label":"LBL_OPPORTUNITIES_AOS_INVOICES_1_FROM_AOS_INVOICES_TITLE"},"calls_aos_invoices_1":{"module":"Calls","vname":"LBL_CALLS_AOS_INVOICES_1_FROM_CALLS_TITLE","label":"LBL_CALLS_AOS_INVOICES_1_FROM_CALLS_TITLE","id_name":"calls_aos_invoices_1calls_ida"},"calls_aos_invoices_1calls_ida":{"module":"Calls","vname":"LBL_CALLS_AOS_INVOICES_1_FROM_AOS_INVOICES_TITLE","label":"LBL_CALLS_AOS_INVOICES_1_FROM_AOS_INVOICES_TITLE"},"aos_invoices_po_purchase_order_1":{"module":"PO_purchase_order","vname":"LBL_AOS_INVOICES_PO_PURCHASE_ORDER_1_FROM_PO_PURCHASE_ORDER_TITLE","label":"PO_purchase_order"}},"pe_warehouse_log":{"pe_warehouse_log_pe_warehouse_log_1":{"module":"pe_warehouse_log","vname":"LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_R_TITLE","label":"LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_R_TITLE","id_name":"pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida"},"pe_warehouse_log_pe_warehouse_log_1pe_warehouse_log_ida":{"module":"pe_warehouse_log","vname":"LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_L_TITLE","label":"LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_L_TITLE"},"pe_warehouse_log_pe_warehouse":{"module":"pe_warehouse","vname":"LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_TITLE","label":"LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_TITLE","id_name":"pe_warehouse_log_pe_warehousepe_warehouse_ida"},"pe_warehouse_log_pe_warehousepe_warehouse_ida":{"module":"pe_warehouse","vname":"LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_LOG_TITLE","label":"LBL_PE_WAREHOUSE_LOG_PE_WAREHOUSE_FROM_PE_WAREHOUSE_LOG_TITLE"},"po_purchase_order_pe_warehouse_log_1":{"module":"PO_purchase_order","vname":"LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PO_PURCHASE_ORDER_TITLE","label":"LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PO_PURCHASE_ORDER_TITLE","id_name":"po_purchase_order_pe_warehouse_log_1po_purchase_order_ida"},"po_purchase_order_pe_warehouse_log_1po_purchase_order_ida":{"module":"PO_purchase_order","vname":"LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_TITLE","label":"LBL_PO_PURCHASE_ORDER_PE_WAREHOUSE_LOG_1_FROM_PE_WAREHOUSE_LOG_TITLE"}},"AOS_Products":[],"AOS_Product_Categories":{"aos_products":{"module":"AOS_Products","vname":"LBL_AOS_PRODUCT_CATEGORIES_AOS_PRODUCTS_FROM_AOS_PRODUCTS_TITLE","label":"AOS_Products"},"sub_categories":{"module":"AOS_Product_Categories","vname":"LBL_SUB_CATEGORIES","label":"LBL_SUB_CATEGORIES","id_name":"parent_category_id"}}},"profilemode":"none","profiles":[],"trackermode":"none","trackerfreq":30,"trackergroup":"","trackerrole":"","languages":"all","offline_max_days":7,"groupby":{"Opportunities":"sales_stage","AOS_Quotes":"stage"},"showtotals":{"Accounts":{"list":true,"dashlets":true,"subpanels":false},"Contacts":{"list":true,"dashlets":true,"subpanels":false},"Opportunities":{"list":true,"dashlets":true,"subpanels":false},"Leads":{"list":true,"dashlets":true,"subpanels":false},"Calls":{"list":true,"dashlets":true,"subpanels":false},"Meetings":{"list":true,"dashlets":true,"subpanels":false},"Tasks":{"list":true,"dashlets":true,"subpanels":false},"Cases":{"list":true,"dashlets":true,"subpanels":false},"Project":{"list":true,"dashlets":true,"subpanels":false},"ProjectTask":{"list":true,"dashlets":true,"subpanels":false},"Notes":{"list":true,"dashlets":true,"subpanels":false},"Documents":{"list":true,"dashlets":true,"subpanels":false},"Employees":{"list":true,"dashlets":true,"subpanels":false},"AOS_Quotes":{"list":true,"dashlets":true,"subpanels":false},"AOS_Invoices":{"list":true,"dashlets":true,"subpanels":false},"AOS_Products":{"list":true,"dashlets":true,"subpanels":false},"AOS_Product_Categories":{"list":true,"dashlets":true,"subpanels":false},"AOR_Reports":{"list":true,"dashlets":true,"subpanels":false},"pe_warehouse":{"list":true,"dashlets":true,"subpanels":false},"Emails":{"list":true,"dashlets":true,"subpanels":false},"PO_purchase_order":{"list":true,"dashlets":true,"subpanels":false},"pe_warehouse_log":{"list":true,"dashlets":true,"subpanels":false}},"totals":{"Opportunities":[{"field":"amount","fnct":["SUM"]}],"AOS_Quotes":[{"field":"total_amt","fnct":["SUM"]}],"AOS_Invoices":[{"field":"total_amt","fnct":["SUM"]}]},"basic_search":{"Accounts":["name"],"Contacts":["name"],"Opportunities":["name"],"Leads":["name"],"Calls":["name"],"Meetings":["name"],"Tasks":["name"],"Cases":["name"],"Project":["name"],"ProjectTask":["name"],"Notes":["name"],"Documents":["document_name"],"Employees":["name"],"AOS_Quotes":["name"],"AOS_Invoices":["name"],"AOS_Products":["name"],"AOS_Product_Categories":["name"],"AOR_Reports":["name"],"pe_warehouse":["name"],"Emails":["name"],"PO_purchase_order":["name"],"pe_warehouse_log":["name"]},"highlighted":{"Accounts":["name"],"Contacts":["name"],"Opportunities":["name"],"Leads":["name"],"Calls":["name"],"Meetings":["name"],"Tasks":["name"],"Cases":["name"],"Project":["name"],"ProjectTask":["name"],"Notes":["name"],"Documents":["name"],"Employees":["name"],"AOS_Quotes":["name"],"AOS_Invoices":["name"],"AOS_Products":["name"],"AOS_Product_Categories":["name"],"AOR_Reports":["name"],"pe_warehouse":["name"],"Emails":["name"],"PO_purchase_order":["name"],"pe_warehouse_log":["name"]},"version":"6.0","trackerviewer":"","show_icon":[],"show_module":[],"create_subpanel":[]}