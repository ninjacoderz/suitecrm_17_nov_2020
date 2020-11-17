<?php

require_once('include/MVC/View/views/view.list.php');
require_once('modules/AOS_Invoices/AOS_InvoicesListViewSmarty.php');

class AOS_InvoicesViewList extends ViewList
{
    /**
     * @see ViewList::preDisplay()
     */
    public function preDisplay()
    {
        require_once('modules/AOS_PDF_Templates/formLetter.php');
        formLetter::LVPopupHtml('AOS_Invoices');
        parent::preDisplay();

        $this->lv = new AOS_InvoicesListViewSmarty();
    }

    //VUT-Sort by number in Invoice's listview
    function listViewPrepare() {
        if (empty($_REQUEST['orderBy'])) {
            $_REQUEST['orderBy'] = strtoupper('number');
            $_REQUEST['sortOrder'] = 'DESC';
        }
        parent::listViewPrepare();
    }
}
