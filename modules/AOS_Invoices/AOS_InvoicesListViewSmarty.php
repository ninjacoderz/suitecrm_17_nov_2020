<?php
require_once('include/ListView/ListViewSmarty.php');
require_once('modules/AOS_PDF_Templates/formLetter.php');


class AOS_InvoicesListViewSmarty extends ListViewSmarty
{
    public function __construct()
    {
        parent::__construct();
        $this->targetList = true;
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function AOS_InvoicesListViewSmarty()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


    /**
     * override
     */
    protected function buildActionsLink($id = 'actions_link', $location = 'top')
    {
        $ret = parent::buildActionsLink($id, $location);

        $ret['buttons'][3] = 
        '<a class="parent-dropdown-action-handler" 
        id="invoice_pdf_button"
        ">Invoice PDF</a>
        <script>
        var not_double_run = 1;
        $("#invoice_pdf_button").click(function(){
               if(not_double_run == 1) {
                   not_double_run ++; 
                   var array_uid = [];
                   $(\'input[name="mass[]"]\').each(function(){
                       if($(this).is(\':checked\')){
                        var record_id_invoice = $(this).val();
                        array_uid.push(record_id_invoice);

                       }
                   });

                   for (let i = 0; i < array_uid.length; i++) {
                    var element = array_uid[i];
                      if(i == 0){
                          var url = "";
                        if(array_uid.length >1){
                            url = "index.php?entryPoint=customBulkActionPrintPDF&last_request=yes&send_get_list=yes&uid="+element;
                        }else{
                            url = "index.php?entryPoint=customBulkActionPrintPDF&last_file=yes&last_request=yes&send_get_list=yes&uid="+element;
                        }
                        $.ajax({
                            url: url,
                            async: false,
                            success: function(data){
                                if(data != ""  && typeof data !== "undefined" && array_uid.length == 1){
                                    window.open(data);
                                }
                            }
                        })
                      }else if(i == (array_uid.length -1)){
                        $.ajax({
                            url: \'index.php?entryPoint=customBulkActionPrintPDF&last_file=yes&send_get_list=yes&uid=\'+element,
                            async: false,
                            success: function(data){
                                if(data != "" && typeof data !== "undefined"){
                                    window.open(data);
                                }else{
                                    alert("Can not create zip for invoices");
                                }
                            }
                        })
                      }else{
                        $.ajax({
                            url: \'index.php?entryPoint=customBulkActionPrintPDF&send_get_list=yes&uid=\' +element,
                            async: false,
                            success: function(data){
                                //window.open(data);
                            }
                        })
                      }                
                  }

               }else{
                    not_double_run--;
               }
           });
        </script>' ;
        return $ret;
    }
    
}

