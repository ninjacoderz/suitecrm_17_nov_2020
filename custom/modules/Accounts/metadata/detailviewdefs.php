<?php
// created: 2020-11-09 09:13:09
$viewdefs['Accounts']['DetailView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'buttons' => 
      array (
        0 => 'EDIT',
        1 => 'DUPLICATE',
        2 => 'DELETE',
        3 => 'FIND_DUPLICATES',
        'AOS_GENLET' => 
        array (
          'customCode' => '<input type="button" class="button" onClick="showPopup();" value="{$APP.LBL_GENERATE_LETTER}">',
        ),
      ),
    ),
    'maxColumns' => '2',
    'widths' => 
    array (
      0 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
      1 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
    ),
    'includes' => 
    array (
      0 => 
      array (
        'file' => 'modules/Accounts/Account.js',
      ),
      1 => 
      array (
        'file' => 'custom/modules/Accounts/CustomAccountHoverEmail.js',
      ),
    ),
    'useTabs' => true,
    'tabDefs' => 
    array (
      'LBL_ACCOUNT_INFORMATION' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
      'LBL_PANEL_ADVANCED' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
    ),
  ),
  'panels' => 
  array (
    'lbl_account_information' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'number',
          'label' => 'LBL_ACCOUNTS_NUMBER',
        ),
        1 => 
        array (
          'name' => 'daikin_account_number_c',
          'label' => 'LBL_DAIKIN_ACCOUNT_NUMBER_C',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'name',
          'comment' => 'Name of the Company',
          'label' => 'LBL_NAME',
        ),
        1 => 
        array (
          'name' => 'website',
          'type' => 'link',
          'label' => 'LBL_WEBSITE',
          'displayParams' => 
          array (
            'link_target' => '_blank',
          ),
        ),
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'check_account_type_c',
          'studio' => 'visible',
          'label' => 'LBL_CHECK_ACCOUNT_TYPE',
        ),
        1 => 
        array (
          'name' => 'mobile_phone_c',
          'label' => 'LBL_MOBILE_PHONE',
        ),
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'email1',
          'studio' => 'false',
          'label' => 'LBL_EMAIL',
        ),
        1 => 
        array (
          'name' => 'phone_office',
          'comment' => 'The office phone number',
          'label' => 'LBL_PHONE_OFFICE',
        ),
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'phone_fax',
          'comment' => 'The fax phone number of this company',
          'label' => 'LBL_FAX',
        ),
        1 => 
        array (
          'name' => 'home_phone_c',
          'label' => 'LBL_HOME_PHONE',
        ),
      ),
      5 => 
      array (
        0 => 
        array (
          'name' => 'billing_address_street',
          'label' => 'LBL_BILLING_ADDRESS',
          'type' => 'address',
          'displayParams' => 
          array (
            'key' => 'billing',
          ),
        ),
        1 => 
        array (
          'name' => 'shipping_address_street',
          'label' => 'LBL_SHIPPING_ADDRESS',
          'type' => 'address',
          'displayParams' => 
          array (
            'key' => 'shipping',
          ),
        ),
      ),
      6 => 
      array (
        0 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_ASSIGNED_TO',
        ),
      ),
      7 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'comment' => 'Full text of the note',
          'label' => 'LBL_DESCRIPTION',
        ),
      ),
    ),
    'LBL_PANEL_ADVANCED' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'account_type',
          'comment' => 'The Company is of this type',
          'label' => 'LBL_TYPE',
        ),
        1 => 
        array (
          'name' => 'industry',
          'comment' => 'The company belongs in this industry',
          'label' => 'LBL_INDUSTRY',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'annual_revenue',
          'comment' => 'Annual revenue for this company',
          'label' => 'LBL_ANNUAL_REVENUE',
        ),
        1 => 
        array (
          'name' => 'employees',
          'comment' => 'Number of employees, varchar to accomodate for both number (100) or range (50-100)',
          'label' => 'LBL_EMPLOYEES',
        ),
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'parent_name',
          'label' => 'LBL_MEMBER_OF',
        ),
      ),
      3 => 
      array (
        0 => 'campaign_name',
      ),
    ),
    'LBL_PANEL_ASSIGNMENT' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'date_entered',
          'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
        ),
        1 => 
        array (
          'name' => 'date_modified',
          'label' => 'LBL_DATE_MODIFIED',
          'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
        ),
      ),
    ),
  ),
);