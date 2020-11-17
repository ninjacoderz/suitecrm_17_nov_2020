<?php

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldDaikininfo extends SugarFieldBase  
{
    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return parent:: getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }
}