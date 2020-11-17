<?php

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldMultiupload extends SugarFieldBase
{
    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
            //$this->ss->assign('uniqueId', uniqid());
            $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
            return parent:: getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }
}