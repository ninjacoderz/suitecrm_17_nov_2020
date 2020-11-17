<?php

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldMultipayment extends SugarFieldBase
{
    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        //$this->ss->assign('', uniqid());payments

        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return parent:: getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }
}