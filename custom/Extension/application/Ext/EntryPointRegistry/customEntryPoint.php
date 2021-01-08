<?php

$entry_point_registry['getContactPhoneNumber'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/GetContactInfo.php',
    'auth' => true
);

$entry_point_registry['getContactFromAccount'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/GetContactFromAccount.php',
    'auth' => true
);


$entry_point_registry['customSendEmail'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomSendEmail.php',
    'auth' => true
);

$entry_point_registry['customCreateEmailPopupContent'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomSendEmailPopup.php',
    'auth' => true
);

$entry_point_registry['customCreateAssignment'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomCreateAssignment.php',
    'auth' => true
);

$entry_point_registry['customCreateSolarGain'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomCreateSolarGain.php',
    'auth' => true
);

$entry_point_registry['customUpdateQuoteToSolarGain'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomUpdateQuoteToSolarGain.php',
    'auth' => true
);

$entry_point_registry['sendRequestAddressSMS'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomSendRequestAddressSMS.php',
    'auth' => true
);

$entry_point_registry['customGetAddress'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetAddress.php',
    'auth' => true
);

$entry_point_registry['customUpdateRelated'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomUpdateRelated.php',
    'auth' => true
);

$entry_point_registry['customGetRetailer'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetRetailer.php',
    'auth' => true
);

$entry_point_registry['customCreateXeroInvoice'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/xero/private.php',
    'auth' => true
);

$entry_point_registry['sendGeoReminder'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/SendGeoReminder.php',
    'auth' => true
);
//customCreateSolarGainQuote

$entry_point_registry['customCreateSolarGainQuote'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomCreateSolarGainQuote.php',
    'auth' => true
);


$entry_point_registry['customGetSolarGainValues'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetSolarGainValues.php',
    'auth' => true
);

$entry_point_registry['customGetRebateNumber'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetRebateNumber.php',
    'auth' => true
);

$entry_point_registry['customRunCustomCode'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomRunCustomCode.php',
    'auth' => true
);

$entry_point_registry['customReceiveMail'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomReceiveMail.php',
    'auth' => true
);

$entry_point_registry['customReadXero'] = array(
    'file' => 'custom/Extension/modules/Schedulers/Ext/custom_readxero_manual.php',
    'auth' => true
);

$entry_point_registry['customLeadHoverEmail'] = array(
    'file' => 'custom/modules/Leads/CustomLeadHoverEmail.php',
    'auth' => true
);

$entry_point_registry['customLeadSendEmailToAdmin'] = array(
    'file' => 'custom/modules/Leads/CustomLeadSendEmailToAdmin.php',
    'auth' => true
);


$entry_point_registry['customLeadAcceptJob'] = array(
    'file' => 'custom/modules/Leads/CustomLeadAcceptJob.php',
    'auth' => true
);

$entry_point_registry['customOppEmailPreview'] = array(
    'file' => 'custom/modules/Opportunities/CustomOpportunityEmailPreview.php',
    'auth' => true
);

$entry_point_registry['customLeadSendDesignsComplete'] = array(
    'file' => 'custom/modules/Leads/CustomLeadSendDesignsComplete.php',
    'auth' => true
);

$entry_point_registry['getProductInfos'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetProductInfos.php',
    'auth' => true
);

$entry_point_registry['customCreatePOXeroInvoice'] = array(
    'file' => 'modules/PO_purchase_order/xero/private.php',
    'auth' => true
);

$entry_point_registry['customAlterEmail'] = array(
    'file' => 'add_more_info_to_email.php',
    'auth' => false,
);

$entry_point_registry['customCreateAcceptanceLink'] = array(
    'file' => 'CustomCreateAcceptanceLink.php',
    'auth' => true,
);


$entry_point_registry['getInvoiceInfo'] = array(
    'file' => 'modules/PO_purchase_order/GetInvoiceInfo.php',
    'auth' => true,
);

$entry_point_registry['customDistance'] = array(
    'file' => 'custom/modules/Leads/CustomDistance.php',
    'auth' => true,
);


$entry_point_registry['createPurchaseOrder'] = array(
    'file' => 'modules/PO_purchase_order/CreatePurchaseOrder.php',
    'auth' => true,
);

$entry_point_registry['seekInstallationDate'] = array(
    'file' => 'custom/modules/AOS_Invoices/SeekInstallationDate.php',
    'auth' => true,
);

$entry_point_registry['reorderLineItems'] = array(
    'file' => 'custom/modules/AOS_Invoices/ReorderLineItems.php',
    'auth' => true,
);

$entry_point_registry['mettingWithInstaller'] = array(
    'file' => 'custom/modules/AOS_Invoices/MettingWithInstaller.php',
    'auth' => true,
);

$entry_point_registry['sendSolargainQuotePDF'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/SendSolargainQuotePDF.php',
    'auth' => true
);
$entry_point_registry['checkSwitchBoardAttached'] = array(
    'file' => 'custom/modules/AOS_Quotes/CheckSwitchBoardAttached.php',
    'auth' => true
);

$entry_point_registry['getPOInfo'] = array(
    'file' => 'custom/modules/AOS_Invoices/GetPOInfo.php',
    'auth' => true
);
//tu-code coverted in leads
$entry_point_registry['CustomConverted'] = array(
    'file' => 'custom/modules/Leads/CustomConverted.php',
    'auth' => true
);
///Users/nguyenbinh/Documents/Sites/PureElectric/pe_crm_10_april_copy_2/custom/modules/Emails/CustomGetEmailTemplates.php


$entry_point_registry['customGetEmailTemplates'] = array(
    'file' => 'custom/modules/Emails/CustomGetEmailTemplates.php',
    'auth' => true
);

$entry_point_registry['customGetDataForEmailTemplate'] = array(
    'file' => 'custom/modules/Leads/CustomGetDataForEmailTemplate.php',///Users/nguyenbinh/Documents/Sites/PureElectric/pe_crm_10_april_copy_2/custom/modules/Leads/CustomGetDataForEmailTemplate.php
    'auth' => true
);

//dung code - create entrypoint get data ABN
$entry_point_registry['getdata_ABN'] = array(
    'file' => 'custom/modules/AOS_Invoices/getDataABN.php',
    'auth' => false
);

$entry_point_registry['customGetLeadfromMessApp'] = array(
    'file' => 'custom/modules/Leads/customGetLeadfromMessApp.php',///Users/nguyenbinh/Documents/Sites/PureElectric/pe_crm_10_april_copy_2/custom/modules/Leads/CustomGetDataForEmailTemplate.php
    'auth' => false
);
//dung code - create entrypoint send sms
$entry_point_registry['sendSMS'] = array(
    'file' => 'custom/modules/pe_smsmanager/Send_SMS.php',
    'auth' => false
);

$entry_point_registry['customGetDataForWareHouse'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetDataForWareHouse.php',
    'auth' => true
);

//thien code - create entrypoint get meter
$entry_point_registry['customGetMeter'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetMeter.php',
    'auth' => true
);

$entry_point_registry['getWarehouseLogStatus'] = array(
    'file' => 'custom/modules/pe_warehouse_log/getWarehouseLogStatus.php',
    'auth' => true,
);
//dung code - update field open_new_tag_c lead
$entry_point_registry['customPostFieldOpenTag'] = array(
    'file' => 'custom/modules/Leads/customPostFieldOpenTag.php',
    'auth' => true
);
//dung code - download file from solargain 
$entry_point_registry['CustomDownloadPDF'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomDownloadPDF.php',
    'auth' => true
);
//dung code - get status GEO 
$entry_point_registry['getStatusGEO'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customGetStatusGEO.php',
    'auth' => true
);

//thien code
$entry_point_registry['setSessionLog'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/setSessionLog.php',
    'auth' => true
);

$entry_point_registry['getAllFilesAttachments'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/getAllFilesAttachments.php',
    'auth' => true
);

$entry_point_registry['getAusnetApproval'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/getAusnetApproval.php',
    'auth' => true
);

$entry_point_registry['isAuthenticated'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/isAuthenticated.php',
    'auth' => false
);
//end

//dung code - Rotated image
$entry_point_registry['CustomInsertRotate'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomInsertRotate.php',
    'auth' => true
);

//dung code - Clip Board
$entry_point_registry['CustomClipBoard'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomClipBoard.php',
    'auth' => true
);

//dung code - button Check Meter Number -Leads
$entry_point_registry['CustomCheckNumberMeter'] = array(
    'file' => 'custom/modules/Leads/CustomCheckNumberMeter.php',
    'auth' => true
);

$entry_point_registry['callXeroByCURL'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CallXeroByCURL.php',
);
//Thienpb code - Get sg state option price
$entry_point_registry['customGetSolarGainStateOptionPrice'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetSolarGainStateOptionPrice.php',
);
//dung code - button Resize Image -Leads
$entry_point_registry['CustomResizeImage'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomResizeImage.php',
    'auth' => true
);

// Thienpb code - get PO by Crawl PO number
$entry_point_registry['customGetPOByNumber'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetPOByNumber.php',
);
//dung code - preview button - detail Account

$entry_point_registry['customAccountHoverEmail'] = array(
    'file' => 'custom/modules/Accounts/customAccountHoverEmail.php',
    'auth' => true
);

//dung code - event trigger change Destination Warehouse - Sold To Invoice
$entry_point_registry['customDeliverInformation'] = array(
    'file' => 'custom/modules/pe_warehouse_log/customDeliverInformation.php',
    'auth' => true
);

// Thienpb code - convert PO to WareHouseLog
$entry_point_registry['customConvertPOToWareHouseLog'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomConvertPOToWareHouseLog.php',
    'auth' => true
);

// Thienpb code - Split WareHouseLog
$entry_point_registry['customSplitWHLog'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customSplitWHLog.php',
    'auth' => true
);

// Thienpb code - register jemena account
$entry_point_registry['registrationJemena'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/registrationJemena.php',
    'auth' => false
);
// Dung code - Add Block File to json
$entry_point_registry['BlockFileJSON'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/BlockFileJSON.php',
    'auth' => false
);
//entryPoint Bulk Action Print PDF In module Invoices - listview
$entry_point_registry['customBulkActionPrintPDF'] = array(
    'file' => 'custom/modules/AOS_Invoices/customBulkActionPrintPDF.php',
    'auth' => true
);
//entryPoint customCreateQuoteTalest
$entry_point_registry['customCreateQuoteTalest'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customCreateQuoteTalest.php',
    'auth' => true
);

//thienpb code - check plumber
$entry_point_registry['checkPlumberInvoice'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/checkPlumberInvoice.php',
    'auth' => false
);

$entry_point_registry['customPe'] = array(
    'file' => 'custom/modules/pe_stock_items/customPe.php',
    'auth' => true
);

//thienpb code -  lookup abn
$entry_point_registry['lookupABN'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/lookupABN.php',
    'auth' => true
);

//thienpb code -  download image design of Options
$entry_point_registry['downloadSGImageOptions'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/downloadSGImageOptions.php',
    'auth' => true
);

//dung code -  custom get price state option price in table price SG
$entry_point_registry['customgetpricefromSG'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customgetpricefromSG.php',
    'auth' => true
);

//dung code -  custom get last date file
$entry_point_registry['customgetlastdatefile'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customgetlastdatefile.php',
    'auth' => true
);

//dung code -  custom button Solargain STATUS EMAIL in Home Page
$entry_point_registry['button_Solargain_STATUS_EMAIL'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/button_Solargain_STATUS_EMAIL.php',
    'auth' => true
);

//dung code -  custom button get link realestate  in Leads
$entry_point_registry['Button_Get_Link_Realestate'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/Button_Get_Link_Realestate.php',
    'auth' => true
);
// Tu Code
$entry_point_registry['CustomPDF'] = array(
    'file' => 'custom/modules/AOS_Invoices/text/CustomPDF.php',
);
//dung code -  custom button get link domain in Leads
$entry_point_registry['Button_Get_Link_Domain'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/Button_Get_Link_Domain.php',
    'auth' => true
);

//thienpb code - update xero
$entry_point_registry['customPushXeroPaymentDate'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/xero/private.php',
    'auth' => true
);
//tu-code update xero invoice
$entry_point_registry['customUpdateXeroInvoice'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/xero/private.php',
    'auth' => false
);
//tu-code show lead number and link 
$entry_point_registry['ShowLeadNumberAndLink'] = array(
    'file' => 'custom/modules/AOS_Quotes/ShowLeadNumberAndLink.php',
    'auth' => true
);
//thienpb code - check sg order number
$entry_point_registry['check_sg_order'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/checkSolargainOrderNumber.php',
    'auth' => true
);

//tu code - show sologain number
$entry_point_registry['show_sr_number'] = array( 
    'file' => 'custom/modules/AOS_Invoices/ShowSerialNumber.php', 
    'auth' => true
 );

 //thienpb code - check serial number exits
$entry_point_registry['check_serial_number_stock_item'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/checkSerialNumberStockItem.php',
    'auth' => true
);

//thienpb code - get stock by serial number
$entry_point_registry['getStockItemBySerialNumber'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/getStockItemBySerialNumber.php',
    'auth' => true
);

//thienpb code - custom Update Quote Price to SG
$entry_point_registry['customUpdateQuotePriceToSolarGain'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customUpdateQuotePriceToSolarGain.php',
    'auth' => true
);

//thienpb code - custom Update Tesla Quote  to SG
$entry_point_registry['customUpdateTeslaQuoteToSolarGain'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customUpdateTeslaQuoteToSolarGain.php',
    'auth' => true
);

//thienpb code - rename file
$entry_point_registry['customRenameFileUpload'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customRenameFileUpload.php',
    'auth' => true
);

//thienpb code - add link forward acceptance email to sg sam
$entry_point_registry['customCreateForwardAcceptanceLink'] = array(
    'file' => 'CustomCreateForwardAcceptanceLink.php',
    'auth' => true,
);
//dung code - Upload Special Note Quote To SolarGain From Module Quote
$entry_point_registry['CustomUploadSpecialNoteQuoteToSolarGain'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomUploadSpecialNoteQuoteToSolarGain.php',
    'auth' => true
);

//dung code - Custom quote registration Jemena Copy From Module Lead
$entry_point_registry['CustomQuoteRegistrationJemena'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomQuoteRegistrationJemena.php',
    'auth' => true
);

//dung code - Custom quote Ausnet Approval Copy From Module Lead
$entry_point_registry['CustomQuoteGetAusnetApproval'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomQuoteGetAusnetApproval.php',
    'auth' => true
);

//dung code - Check exist lead before  action save
$entry_point_registry['CustomCheckExistLeadBeforeSave'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomCheckExistLeadBeforeSave.php',
    'auth' => true
);

//dung code - auto fill data from SAM in Invoice
$entry_point_registry['CustomAutoFillDataFromSAM'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomAutoFillDataFromSAM.php',
    'auth' => true
);

//dung code - auto send email from report "SOLAR DESIGN COMPLETE TO BE SENT"
$entry_point_registry['CustomSGDesignCompleteToSent'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomSGDesignCompleteToSent.php',
    'auth' => true
);

//dung code - button get all file from message app
$entry_point_registry['getAllFilesMessageApp'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/getAllFilesMessageApp.php',
    'auth' => true
);

//dung code - entry point get default value electric daikin from account
$entry_point_registry['customGetValueElectricDefault'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customGetValueElectricDefault.php',
    'auth' => true
);

//thienpb code - popular base price for SOLAR PV PRICING
$entry_point_registry['popularSolarBasePrice'] = array(
    'file' => 'convert_json.php',
    'auth' => false
);

//dung code - button "Mark As Lost" lost report name "SENT FOLLOW UP ON OLD QUOTE"
$entry_point_registry['Mark_As_Lost'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/Mark_As_Lost.php',
    'auth' => true
);

//dung code - button "Mark As Lost" lost report name "SENT FOLLOW UP ON OLD QUOTE"
$entry_point_registry['GetInfoInvoice'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/GetInfoInvoice.php',
    'auth' => true
);
//tuan
$entry_point_registry['update_invoice'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/update_invoice.php',
    'auth' => true
);

//dung code - button "Resize All" in file upload
$entry_point_registry['ResizeAllFile'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/ResizeAllFile.php',
    'auth' => true
);

//dung code - button Check NMI Number -Leads
$entry_point_registry['CustomCheckNumberNMI'] = array(
    'file' => 'custom/modules/Leads/CustomCheckNumberNMI.php',
    'auth' => true
);

//dung code - button Check Status Order SAM 
$entry_point_registry['getStatusSAM'] = array(
    'file' => 'custom/modules/AOS_Invoices/getStatusSAM.php',
);
//Thienpb code -
$entry_point_registry['updateQuoteToSGQuote'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/updateQuoteToSGQuote.php',
    'auth' => true
);

//Thienpb code -
$entry_point_registry['quoteCreateSGQuote'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/quoteCreateSGQuote.php',
    'auth' => true
);

//dung code - button send solar design
$entry_point_registry['customQuoteSendEmailToAdmin'] = array(
    'file' => 'custom/modules/AOS_Quotes/customQuoteSendEmailToAdmin.php',
    'auth' => true
);
//getLeadFromAccount

$entry_point_registry['getLeadFromAccount'] = array(
    'file' => 'custom/modules/AOS_Quotes/GetLeadFromAccount.php',
    'auth' => true
);
//Thienpb code -
$entry_point_registry['quoteCreateSGTeslaQuote'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/quoteCreateSGTeslaQuote.php',
    'auth' => true
);

//Thienpb code - button update tesla quote from Quote
$entry_point_registry['quoteUpdateToSGTeslaQuote'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/quoteUpdateToSGTeslaQuote.php',
    'auth' => true
);

//dung code - fix error:  button Site_Inspections in Home Page
$entry_point_registry['button_Solargain_Site_Inspections'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/button_Solargain_Site_Inspections.php',
    'auth' => true
);

//dung code - copy fucntion  button Designs Complete from lead to quote
$entry_point_registry['customQuoteSendDesignsComplete'] = array(
    'file' => 'custom/modules/AOS_Quotes/customQuoteSendDesignsComplete.php',
    'auth' => true
);

//Thienpb code - button accept job 
$entry_point_registry['customQuotesAcceptJob'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customQuotesAcceptJob.php',
    'auth' => true
);
//dung code - Custom Update Meeting From Invoice
$entry_point_registry['CustomUpdateMeetingFromInvoice'] = array(
    'file' => 'custom/modules/AOS_Invoices/CustomUpdateMeetingFromInvoice.php',
    'auth' => true
);

//dung code - Custom Button Convert In Detail Lead
$entry_point_registry['CustomButtonConvertLead'] = array(
    'file' => 'custom/modules/Leads/CustomButtonConvertLead.php',
    'auth' => false
);

//dung code - Button Send Email From Report
$entry_point_registry['CustomSendEmailReport'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomSendEmailReport.php',
    'auth' => true
);

//tuan code - 
$entry_point_registry['customMarkAsSG_sent'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomMarkAsSG_sent.php',
    'auth' => true
);
$entry_point_registry['customProposedInstallDate'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomProposedInstallDate.php',
    'auth' => true
);
$entry_point_registry['customGetSGAssignedUser'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetSGAssignedUser.php',
    'auth' => true
);
$entry_point_registry['customChangeQuotedByUser'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomChangeQuotedByUser.php',
    'auth' => true
);
$entry_point_registry['customPushNextActionDate'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomPushNextActionDate.php',
    'auth' => true
);
$entry_point_registry['getdistance_Flum_or_Elec_to_Suite'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/Getdistance_Flum_or_Elec_to_Suite.php',
    'auth' => true
);
$entry_point_registry['customGetSTCFromSG'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetSTCFromSG.php',
    'auth' => true
);
$entry_point_registry['customGetAgeDays'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomGetAgeDays.php',
    'auth' => true
);
$entry_point_registry['customChmod777'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomChmod777.php',
    'auth' => true
);
$entry_point_registry['customCheckPostalCodeSandenWater'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomCheckPostalCodeSandenWater.php',
    'auth' => true
);
$entry_point_registry['customFilterPlumber'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomFilterPlumber.php',
    'auth' => true
);
$entry_point_registry['createMettingWHLog'] = array(
    'file' => 'custom/modules/pe_warehouse_log/createMettingWHLog.php',
    'auth' => true
);
$entry_point_registry['APIGetPhotoCaseStudy'] = array(
    'file' => 'custom/modules/AOS_Invoices/APIGetPhotoCaseStudy.php',
    'auth' => true
);
$entry_point_registry['customOriginalPrice'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomOriginalPrice.php',
    'auth' => true
);
$entry_point_registry['getContentDetailSolar'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/GetContentDetailSolar.php',
    'auth' => false
);
$entry_point_registry['getPhotosFloorPlan'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/GetPhotosFloorPlan.php',
    'auth' => true
);
$entry_point_registry['SG_lead_sources'] = array(
    'file' => 'custom/modules/Leads/SG_lead_sources.php',
    'auth' => true
);
$entry_point_registry['APIRenameVBAtoPCOC'] = array(
    'file' => 'custom/modules/AOS_Invoices/APIRenameVBAtoPCOC.php',
    'auth' => false
);
$entry_point_registry['converToInvoice'] = array(
    'file' => 'modules/AOS_Quotes/converToInvoice.php',
    'auth' => false
);
$entry_point_registry['APISendPhotoInstallToInstaller'] = array(
    'file' => 'custom/modules/AOS_Invoices/APISendPhotoInstallToInstaller.php',
    'auth' => false
);
$entry_point_registry['APIGetContactEmail'] = array(
    'file' => 'custom/modules/AOS_Invoices/APIGetContactEmail.php',
    'auth' => false
);
// api create facebook post 
$entry_point_registry['APICreateFacebookPost'] = array(
    'file' => 'custom/modules/AOS_Invoices/APICreateFacebookPost.php',
    'auth' => true
); // cert template
$entry_point_registry['CRUD_Cert_Template'] = array(
    'file' => 'custom/modules/AOS_Invoices/CRUD_Cert_Template.php',
    'auth' => false
);
//tu code -  custom button SANDEN  EMAIL DAILY in Home Page
$entry_point_registry['button_Sanden_Dealers'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/button_Sanden_Dealers.php',
    'auth' => true
);

//dung code -  custom button in Home Page
$entry_point_registry['seekInstallationDate_elec'] = array(
    'file' => 'custom/modules/AOS_Invoices/seekInstallationDate_elec.php',
    'auth' => true
);

//thienpb code -  custom button quote follow up
$entry_point_registry['followUpdateQuoteStage'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/followUpdateQuoteStage.php',
    'auth' => true
);

//dung code -- api CRUD SMS From app messsage 
$entry_point_registry['API_CRUD_SMS'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/API_CRUD_SMS.php',
    'auth' => false
);
//TriTruong - Form Information 
$entry_point_registry['formInformationClient'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/informationFormClient.php',
    'auth' => false
);
$entry_point_registry['sendFormClientInfo'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/sendFormClientInfo.php',
);
//dung code -- get information Quote from module Invoice
$entry_point_registry['get_info_quote'] = array(
    'file' => 'custom/modules/AOS_Quotes/get_info_quote.php',
    'auth' => false
);
//get information create link open PO, Quote, Invoice,Account,Contact
$entry_point_registry['get_info_create_link'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/get_info_create_link.php',
    'auth' => false
);

//get information contact with relationship with account
$entry_point_registry['get_list_contact_from_account'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/get_list_contact_from_account.php',
);
//thienpb code -- API for create Lead and Quote fromPE
$entry_point_registry['APICreateLeadFromPE'] = array(
    'file' => 'custom/modules/Leads/APICreateLeadFromPE.php',
    'auth' => false
);

$entry_point_registry['customCreatePOXeroContact'] = array(
    'file' => 'modules/Accounts/xero/private.php',
    'auth' => true
);
//thienbp code  -- logic push data to solar vic
$entry_point_registry['pushtoSolarVIC'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/pushtoSolarVIC.php',
    'auth' => true
);

//thienbp code  -- logic get data to solar vic
$entry_point_registry['getDataSolarVIC'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/getDataSolarVIC.php',
    'auth' => true
);

//thienbp code  -- logic get Estimated Output from sg
$entry_point_registry['getEstimatedOuput'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/getEstimatedOuput.php',
    'auth' => true
);


$entry_point_registry['get_lead_source_company'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/get_lead_source_company.php',
    'auth' => false
);

//dung code -- API for create Quote from Get Free Quote PE Site
$entry_point_registry['APICreateQuoteFromPE'] = array(
    'file' => 'custom/modules/Leads/APICreateQuoteFromPE.php',
    'auth' => false
);

//dung code -- Button send email trustpilot
$entry_point_registry['SendEmailTrustPilop'] = array(
    'file' => 'custom/modules/AOS_Invoices/SendEmailTrustPilop.php',
    'auth' => false
);

//dung code -- Image Site Details
$entry_point_registry['Image_Site_Details'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/Image_Site_Details.php',
    'auth' => false
);

//dung code -- Image Site Details --get google map
$entry_point_registry['Image_Site_Details_Get_From_Google'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/Image_Site_Details_Get_From_Google.php',
    'auth' => false
);

//dung code -- Image Site Details --get nearmap
$entry_point_registry['Image_Site_Details_Get_From_Nearmap'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/Image_Site_Details_Get_From_Nearmap.php',
    'auth' => false
);

//dung code -- Image Site Details -- clipboard image from popup
$entry_point_registry['Image_Site_Details_Clipboard_Popup'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/Image_Site_Details_Clipboard_Popup.php',
    'auth' => false
);

//dung code -- CURL special notes
$entry_point_registry['CRUD_Special_Notes'] = array(
    'file' => 'custom/modules/AOS_Quotes/CRUD_Special_Notes.php',
    'auth' => false
);
//thien 
$entry_point_registry['customGetSMSTemplate'] = array(
    'file' => 'custom/modules/Emails/CustomGetSMSTemplate.php',
    'auth' => true
);
//dung code -- get data sms template 
$entry_point_registry['SMSTemplateData'] = array(
    'file' => 'custom/modules/pe_smstemplate/SMSTemplateData.php',
    'auth' => true
);
//Tri Truong code -- get data sms template 
$entry_point_registry['APIUpdateQuoteSandenFromPE'] = array(
    'file' => 'custom/modules/AOS_Quotes/APIUpdateQuoteSandenFromPE.php',
    'auth' => true
);

$entry_point_registry['checkDuplicateDD'] = array(
    'file' => 'modules/pe_warehouse_log/checkDuplicateDeliveryDocket.php',
    'auth' => true,
);

$entry_point_registry['gettempalte_sitedetails_invoices'] = array(
    'file' => 'custom/modules/AOS_Invoices/gettempalte_sitedetails_invoices.php',
    'auth' => true,
);

$entry_point_registry['customComparePricing'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/customComparePricing.php',
    'auth' => true,
);
//Tri Truong Code -- Send Files to Quote from PE 
$entry_point_registry['APIUpdateFilesToQuoteFromPE'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APIUpdateFilesToQuoteFromPE.php',
    'auth' => false,
);
//Tri Truong Code -- Create Quote PE
$entry_point_registry['APICreateQuoteFromSandenPE'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APICreateQuoteFromPE.php',
    'auth' => false,
);
//thienpb code -- save file
$entry_point_registry['saveCommentFile'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/saveCommentFile.php',
    'auth' => true
);
//Tri Truong Code -- Delete Quote PE
$entry_point_registry['APIDeleteQuote'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APIDeleteQuote.php',
    'auth' => false,
);
//Tri Truong Code -- Create Lead Account Contact
$entry_point_registry['APICreateLeadAccountContact'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APICreateLeadAccountContact.php',
    'auth' => false
);

//Dung Code -- API Get Data Display Detail Invoices
$entry_point_registry['APIGetDataInvoice'] = array( 
    'file' => 'custom/modules/AOS_Invoices/APIGetDataInvoice.php',
    'auth' => true
);

//Dung Code -- Updated All Phone Number
$entry_point_registry['custom_update_relative_phone_number'] = array( 
    'file' => 'custom/modules/Emails/custom_update_relative_phone_number.php',
    'auth' => true
);

//Dung Code -- API Get Data Display Detail Leads
$entry_point_registry['APIGetDataLeads'] = array( 
    'file' => 'custom/modules/Leads/APIGetDataLeads.php',
    'auth' => false
);


//Dung Code -- API Get Data Display Detail Quotes
$entry_point_registry['APIGetDataQuotes'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APIGetDataQuotes.php',
    'auth' => true
);

//Tri Truong Code -- Get Data Product
$entry_point_registry['APIGetDataProduct'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APIGetDataProduct.php',
    'auth' => false
);
//Thienpb Code -- API download image from aws
$entry_point_registry['APIUploadImageToAWS'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/APIUploadImageToAWS.php',
    'auth' => true
);
//Thienpb Code -- API delete image from folder file
$entry_point_registry['APIDeleteFiles'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/APIDeleteFiles.php',
    'auth' => true
);
//Thienpb Code -- API delete image from folder Upload
$entry_point_registry['APIDeleteUploadFiles'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/APIDeleteUploadFiles.php',
    'auth' => true
);

//Thienpb Code -- API upload image from folder Upload to AWS
$entry_point_registry['APIUploadFilesFromUpload'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/APIUploadFilesFromUpload.php',
    'auth' => true
);

//Dung Code -- autocomplete get phone number from popupemail
$entry_point_registry['customGetPhoneNumber'] = array( 
    'file' => 'custom/modules/Emails/customGetPhoneNumber.php',
    'auth' => true
);
//Tri Truong -- API generate PDF for Quote Form
$entry_point_registry['generatePDFQuoteForm'] = array( 
    'file' => 'modules/AOS_PDF_Templates/generatePDFQuoteForm.php',
    'auth' => true
);
//Tri Truong -- API Get Product Line
$entry_point_registry['APIGetDataProductLinesFromQuote'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APIGetDataProductLinesFromQuote.php',
    'auth' => false
);
//Tri Truong -- API Update Internal Note - API Sanden Quote
$entry_point_registry['APISandenQuoteConfirm'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APISandenQuoteConfirm.php',
    'auth' => false
);

//Tri Truong -- API Get Link and Send to Gmail
$entry_point_registry['APIGetLinkSendToGmail'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/APIGetLinkSendToGmail.php',
    'auth' => false
);
// DAIKIN FORM API
//Tri Truong -- API Gen Quote Summary Daikin
$entry_point_registry['APICreateQuoteFromDaikinPE'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APICreateQuoteFromDaikinPE.php',
    'auth' => false
);

//dung code -- custom api get template checklist invoices
$entry_point_registry['API_CheckList_Invoice'] = array( 
    'file' => 'custom/modules/AOS_Invoices/API_CheckList_Invoice.php',
    'auth' => true
);

//dung code -- custom api get template checklist invoices
$entry_point_registry['CRUD_Quick_Comment'] = array( 
    'file' => 'custom/modules/Emails/CRUD_Quick_Comment.php',
    'auth' => true
);

//dung code -- custom api Sanden STC Form 
$entry_point_registry['API_DATA_Invoice_SuiteCRM'] = array( 
    'file' => 'custom/modules/AOS_Invoices/API_DATA_Invoice_SuiteCRM.php',
    'auth' => false
);

//thienpb_code -- custom generate email for Installation calendar
$entry_point_registry['generateInstallationCalendarEmail'] = array( 
    'file' => 'custom/modules/AOS_Invoices/generateInstallationCalendarEmail.php',
    'auth' => true
);

//thienpb_code -- custom send email when Installation calendar saved
$entry_point_registry['send_IC_saved'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/send_IC_saved.php',
    'auth' => false
);

//VuT code -- custom API Invoice >> Warehouselog
$entry_point_registry['API_Invoice_WarehouseLog'] = array( 
    'file' => 'custom/modules/AOS_Invoices/API_Invoice_WarehouseLog.php',
    'auth' => false
);

//thienpb code -- custom update geo status from report
$entry_point_registry['update_GEO_Status_From_Report_SUBSIDY_UNPAID'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/update_GEO_Status_From_Report_SUBSIDY_UNPAID.php',
);
//Tri Truong Code -- Create Quote Sanden New PE
$entry_point_registry['APICreateQuoteNewFromSandenPE'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APICreateQuoteNewFromPE.php',
    'auth' => false
);
//Tri Truong Code -- Create LEAD ACCOUNT CONTACT PE
$entry_point_registry['APICreateLeadAccountContactByForm'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APICreateLeadAccountContactByForm.php',
    'auth' => false
);
//Tri Truong Code -- Generate PDF By Form PE
$entry_point_registry['generatePDFByQuoteForm'] = array( 
    'file' => 'modules/AOS_PDF_Templates/generatePDFByQuoteForm.php',
    'auth' => true
);

//dung code -  custom button Update Install Dates SG in Home Page
$entry_point_registry['button_update_install_dates_from_SG'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/button_update_install_dates_from_SG.php',
    'auth' => true
);
//Thienpb Code -- Api add file to invoice
$entry_point_registry['APIAddImageToInvoice'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/apiAddImageToInvoice.php',
    'auth' => false
);
//Tri Truong Code -- API Add Node ID To Quote
$entry_point_registry['APIAddNodeIDToQuote'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APIAddNodeIDToQuote.php',
    'auth' => false
);

//dung code -- CURL plumbing notes
$entry_point_registry['CRUD_Plumbing_Notes'] = array(
    'file' => 'custom/modules/AOS_Invoices/CRUD_Plumbing_Notes.php',
    'auth' => false
);
//Thienpb code -- get stcs from https://www.rec-registry.gov.au/rec-registry/app/calculators/sgu-stc-calculator
$entry_point_registry['getSTCsNumberForQuotePricing'] = array(
    'file' => 'custom/modules/AOS_Quotes/getSTCsNumberForQuotePricing.php',
    'auth' =>true
);
//dung code -- CURL electrical notes
$entry_point_registry['CRUD_Electrical_Notes'] = array(
    'file' => 'custom/modules/AOS_Invoices/CRUD_Electrical_Notes.php',
    'auth' => false
);

//Create New Contact
$entry_point_registry['create_new_contact'] = array(
    'file' => 'custom/modules/Contacts/create_new_contact.php',
    'auth' => false
);

// Tri Truong : Send InPacks Sanden Quote Form
$entry_point_registry['APISandenInPacks'] = array(
    'file' => 'custom/modules/AOS_Quotes/APISandenInPacks.php',
    'auth' => false
);

$entry_point_registry['create_call_back_pe_internal_note'] = array(
    'file' => 'custom/modules/pe_internal_note/create_call_back_pe_internal_note.php',
);
//thienpb code -- load option default
$entry_point_registry['loadPricingOption'] = array(
    'file' => 'custom/modules/pe_pricing_options/loadPricingOption.php',
    'auth' => false
);
//thienpb code -- API Pricing PV Solar Form generate
$entry_point_registry['APIPricingPVSolarForm'] = array(
    'file' => 'custom/modules/AOS_Quotes/APIPricingPVSolarForm.php',
    'auth' => false
);

//Tri Truong code -- API Create Quote Solar Form FOrm
$entry_point_registry['APISolarQuoteForm'] = array(
    'file' => 'custom/modules/AOS_Quotes/APISolarQuoteForm.php',
    'auth' => true
);

//VUT -- API get Contact for Internal note edit
$entry_point_registry['APIgetContactFromInternalNote'] = array(
    'file' => 'custom/modules/pe_internal_note/APIgetContactFromInternalNote.php',
    'auth' => false
);

//VUT -- API update information button at Account detail relate to Contact, Lead
$entry_point_registry['UpdateInfomationAccount'] = array(
    'file' => 'custom/modules/Accounts/UpdateInfomationAccount.php',
    'auth' => true
);

//VUT -- API get Quotes relate to Account for Internal note
$entry_point_registry['getQuoteRelateAccount'] = array(
    'file' => 'custom/modules/pe_internal_note/getQuoteRelateinAccount.php',
    'auth' => true
);

//VUT -- CRUD quote note for PDF
$entry_point_registry['CRUD_quote_note'] = array(
    'file' => 'custom/modules/AOS_Quotes/CRUD_quote_note_pdf.php',
    'auth' => false
);

//VUT-Get link realestate 
$entry_point_registry['getLinkRealestate'] = array(
    'file' => 'custom/modules/AOS_Quotes/getLinkRealestate.php',
    'auth' => false,
);

//VUT-Service Case-Populate Contact/Invoice Address
$entry_point_registry['populateAddress'] = array(
    'file' => 'custom/modules/pe_service_case/populateAddress.php',
    'auth' => true,
);

//VUT-Service Case- Get infomation Message
$entry_point_registry['getMessageServiceCase'] = array(
    'file' => 'custom/modules/pe_service_case/getMessageServiceCase.php',
    'auth' => true,
);

//VUT-Quotes- Seek install date > Sanden Electrician
$entry_point_registry['seekInstallationDate_Quote'] = array(
    'file' => 'custom/modules/AOS_Quotes/seekInstallationDate_Quote.php',
    'auth' => true,
);

//VUT-Service Case- Read Google Sheet (CSV)
$entry_point_registry['readGoogleSheet'] = array(
    'file' => 'custom/modules/pe_service_case/readGoogleSheet.php',
    'auth' => true,
);

//dung code -- CURL special notes
$entry_point_registry['CRUD_SMS_Signture'] = array(
    'file' => 'custom/modules/Users/CRUD_SMS_Signture.php',
    'auth' => true
);

//VUT-SMS Template- Get template for sms_icon
$entry_point_registry['getSMSTemplatePopup'] = array(
    'file' => 'custom/modules/pe_smstemplate/getSMSTemplatePopup.php',
    'auth' => false,
);

//thienpb - push and update po to xero
$entry_point_registry['xeroAPI'] = array(
    'file' => 'xero-php/xeroAPI.php',
    'auth' => true
);

//thienpb - push and update invoice stc to xero
$entry_point_registry['xeroInvoiceSTC'] = array(
    'file' => 'xero-php/xeroInvoiceSTC.php',
    'auth' => true
);
// create and update xero solar
$entry_point_registry['CRUD_Invoice_Xero'] = array(
    'file' => 'xero-php/CRUD_Invoice_Xero.php',
    'auth' => false
);

//VUT - check FormBay Tab in SG Order
$entry_point_registry['checkFormBayTabSG'] = array(
    'file' => 'custom/modules/AOS_Invoices/checkFormBayTabSG.php',
    'auth' => true
);

// create and update xero solar
$entry_point_registry['CRUD_suggested_note'] = array(
    'file' => 'custom/modules/Calls/CRUD_suggested_note.php',
    'auth' => true
);

// remove istore pdf
$entry_point_registry['removeIstoreFromPDF'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/removeIstoreFromPDF.php',
    'auth' => true
);

//VUT-Create Service Case from Invoice
$entry_point_registry['createServiceCase'] = array(
    'file' => 'custom/modules/pe_service_case/createServiceCase.php',
    'auth' => true
);
//dung code -- api CRUD SMS From app messsage 
$entry_point_registry['CRUD_Files'] = array(
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CRUD_Files.php',
    'auth' => false
);
//tritruong code -- api new daikin quote
$entry_point_registry['APICreateQuoteFromDaikinPENew'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APICreateQuoteFromDaikinPENew.php',
    'auth' => false
);
//tritruong code -- get Leads
$entry_point_registry['APIGetLeadByEmail'] = array( 
    'file' => 'custom/modules/Leads/APIGetLeadByEmail.php',
    'auth' => false
);
//thienpb code -- send notification
$entry_point_registry['APISendNotification'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APISendNotification.php',
    'auth' => false
);

$entry_point_registry['Caculation_Profit'] = array( 
    'file' => 'custom/modules/AOS_Invoices/Caculation_Profit.php',
    'auth' => false
);

//thienpb code -- send notification
$entry_point_registry['CustomQuoteSolarEmailPDF'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomQuoteSolarEmailPDF.php',
    'auth' => true
);

//thienpb code -- send notification when got sms
$entry_point_registry['MessageSendEmailNotification'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/MessageSendEmailNotification.php',
    'auth' => false
);

//thienpb code -- update invoice line item
$entry_point_registry['APIUpdateInvoiceLineItem'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/APIUpdateInvoiceLineItem.php',
    'auth' => true
);

//thienpb code -- update GG sheet order SAM
$entry_point_registry['APIUpdateGGSheet'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/APIUpdateGGSheet.php',
    'auth' => true
);

//Tri code -- API Download Files From Suite To DaiKin DesignTool
$entry_point_registry['APIDownloadFilesFromSuiteToDaiKinDesignTool'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/APIDownloadFilesFromSuiteToDaiKinDesignTool.php',
    'auth' => false
);

//Dung code -- API CRUD for Methven Module PE Site
$entry_point_registry['APIMethvenModulePE'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APIMethvenModulePE.php',
    'auth' => false
);

//VUT -- API get Quote Follow Up in Calls
$entry_point_registry['APIGetQuoteFollowUp'] = array( 
    'file' => 'custom/modules/Calls/APIGetQuoteFollowUp.php',
    'auth' => true
);

//Thienpb code  -- API send sanden stock summaru notification
$entry_point_registry['APISendSandenStockSummaryNotification'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload//APISendSandenStockSummaryNotification.php',
    'auth' => false
);

//Dung code  -- Link Create Email Draft
$entry_point_registry['Create_Email_Draft'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/Create_Email_Draft.php',
    'auth' => false
);


//Thienpb code  --  push and update invoice stc and veec to xero
$entry_point_registry['xeroInvoiceSTC_VEEC'] = array( 
    'file' =>  'xero-php/xeroInvoiceSTC_VEEC.php',
    'auth' => true
);

//Thienpb code  --  load base part Number parameter for report
$entry_point_registry['loadPartNumber'] = array( 
    'file' =>  'custom/modules/AOR_Reports/loadPartNumber.php',
    'auth' => true
);

//Tri code  -- Link Create Email Draft
$entry_point_registry['APIGeneratePDFDaikinTool'] = array( 
    'file' => 'modules/AOS_PDF_Templates/generatePDFDaikinTool.php',
    'auth' => true
);

//Dung code  -- API Generate Promo Code
$entry_point_registry['APIGeneratePromoCode'] = array( 
    'file' => 'custom/modules/AOS_Invoices/APIGeneratePromoCode.php',
    'auth' => true
);

$entry_point_registry['APICloneFile'] = array( 
    'file' => 'custom/modules/Leads/APICloneFile.php',
    'auth' => false
);

$entry_point_registry['APICustomerAgreement'] = array( 
    'file' => 'custom/modules/AOS_Invoices/APICustomerAgreement.php',
    'auth' => false
);

//VUT - GET PO for GP Calculation
$entry_point_registry['GetPOForCalculation'] = array( 
    'file' => 'custom/modules/AOS_Invoices/GetPOForCalculation.php',
    'auth' => false
);

//API auspost
$entry_point_registry['API_Auspost'] = array( 
    'file' => 'custom/modules/pe_warehouse_log/API_Auspost.php',
    'auth' => false
);

//CRUD Product PE Site
$entry_point_registry['CRUD_Product_PESite'] = array( 
    'file' => 'custom/modules/AOS_Products/CRUD_Product_PESite.php',
    'auth' => false
);

//thienpb code  -- convert heic file to jpg
$entry_point_registry['convertHEICtoJPG'] = array( 
    'file' => 'custom/include/SugarFields/Fields/Multiupload/CustomConvertHEICtoJPG.php',
    'auth' => true

);
///
//Tri code -- custom api get template checklist Quote Input
$entry_point_registry['APIRenderListQuoteInputs'] = array( 
    'file' => 'custom/modules/AOS_Quotes/APIRenderListQuoteInputs.php',
    'auth' => true
);