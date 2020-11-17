<?php
// created: 2020-11-09 09:15:27
$sugar_config = array (
  'SAML_X509Cert' => '',
  'SAML_loginurl' => '',
  'SAML_logouturl' => '',
  'addAjaxBannedModules' => false,
  'admin_access_control' => false,
  'admin_export_only' => false,
  'aod' => 
  array (
    'enable_aod' => false,
  ),
  'aop' => 
  array (
    'distribution_method' => 'roundRobin',
    'case_closure_email_template_id' => '29c9f493-2b41-530b-fc8c-5694e1267eed',
    'joomla_account_creation_email_template_id' => '2b25ebd0-4c17-a3ca-d689-5694e1730700',
    'case_creation_email_template_id' => '2c6db0d1-6843-feb9-5583-5694e1f46257',
    'contact_email_template_id' => '2e166ef8-a8df-34e9-82d7-5694e1f008c3',
    'user_email_template_id' => '2f7901b7-135c-afb9-7cee-5694e1c60100',
  ),
  'aos' => 
  array (
    'version' => '5.3.3',
    'contracts' => 
    array (
      'renewalReminderPeriod' => '14',
    ),
    'lineItems' => 
    array (
      'totalTax' => false,
      'enableGroups' => true,
    ),
    'invoices' => 
    array (
      'initialNumber' => '1',
    ),
    'quotes' => 
    array (
      'initialNumber' => '1',
    ),
  ),
  'authenticationClass' => '',
  'cache_dir' => 'cache/',
  'calculate_response_time' => true,
  'calendar' => 
  array (
    'default_view' => 'week',
    'show_calls_by_default' => true,
    'show_tasks_by_default' => true,
    'show_completed_by_default' => true,
    'editview_width' => 990,
    'editview_height' => 485,
    'day_timestep' => 15,
    'week_timestep' => 30,
    'items_draggable' => true,
    'items_resizable' => true,
    'enable_repeat' => true,
    'max_repeat_count' => 1000,
  ),
  'chartEngine' => 'Jit',
  'common_ml_dir' => '',
  'create_default_user' => false,
  'cron' => 
  array (
    'max_cron_jobs' => 10,
    'max_cron_runtime' => 30,
    'min_cron_interval' => 30,
    'allowed_cron_users' => 
    array (
      0 => 'root',
      1 => 'apache',
    ),
  ),
  'currency' => '',
  'dashlet_auto_refresh_min' => '300',
  'dashlet_display_row_options' => 
  array (
    0 => '1',
    1 => '3',
    2 => '5',
    3 => '10',
    4 => '20',
    5 => '30',
  ),
  'date_formats' => 
  array (
    'Y-m-d' => '2010-12-23',
    'm-d-Y' => '12-23-2010',
    'd-m-Y' => '23-12-2010',
    'Y/m/d' => '2010/12/23',
    'm/d/Y' => '12/23/2010',
    'd/m/Y' => '23/12/2010',
    'Y.m.d' => '2010.12.23',
    'd.m.Y' => '23.12.2010',
    'm.d.Y' => '12.23.2010',
  ),
  'datef' => 'm/d/Y',
  'dbconfig' => 
  array (
    'db_host_name' => 'database-1.crz4vavpmnv9.ap-southeast-2.rds.amazonaws.com',
    'db_host_instance' => 'SQLEXPRESS',
    'db_user_name' => 'root',
    'db_password' => 'binhmatt2018',
    'db_name' => 'suitecrm',
    'db_type' => 'mysql',
    'db_port' => '3306',
    'db_manager' => 'MysqliManager',
  ),
  'dbconfigoption' => 
  array (
    'persistent' => true,
    'autofree' => false,
    'debug' => 0,
    'ssl' => false,
    'collation' => 'utf8_general_ci',
  ),
  'default_action' => 'index',
  'default_charset' => 'UTF-8',
  'default_currencies' => 
  array (
    'AUD' => 
    array (
      'name' => 'Australian Dollars',
      'iso4217' => 'AUD',
      'symbol' => '$',
    ),
    'BRL' => 
    array (
      'name' => 'Brazilian Reais',
      'iso4217' => 'BRL',
      'symbol' => 'R$',
    ),
    'GBP' => 
    array (
      'name' => 'British Pounds',
      'iso4217' => 'GBP',
      'symbol' => '£',
    ),
    'CAD' => 
    array (
      'name' => 'Canadian Dollars',
      'iso4217' => 'CAD',
      'symbol' => '$',
    ),
    'CNY' => 
    array (
      'name' => 'Chinese Yuan',
      'iso4217' => 'CNY',
      'symbol' => '￥',
    ),
    'EUR' => 
    array (
      'name' => 'Euro',
      'iso4217' => 'EUR',
      'symbol' => '€',
    ),
    'HKD' => 
    array (
      'name' => 'Hong Kong Dollars',
      'iso4217' => 'HKD',
      'symbol' => '$',
    ),
    'INR' => 
    array (
      'name' => 'Indian Rupees',
      'iso4217' => 'INR',
      'symbol' => '₨',
    ),
    'KRW' => 
    array (
      'name' => 'Korean Won',
      'iso4217' => 'KRW',
      'symbol' => '₩',
    ),
    'YEN' => 
    array (
      'name' => 'Japanese Yen',
      'iso4217' => 'JPY',
      'symbol' => '¥',
    ),
    'MXM' => 
    array (
      'name' => 'Mexican Pesos',
      'iso4217' => 'MXM',
      'symbol' => '$',
    ),
    'SGD' => 
    array (
      'name' => 'Singaporean Dollars',
      'iso4217' => 'SGD',
      'symbol' => '$',
    ),
    'CHF' => 
    array (
      'name' => 'Swiss Franc',
      'iso4217' => 'CHF',
      'symbol' => 'SFr.',
    ),
    'THB' => 
    array (
      'name' => 'Thai Baht',
      'iso4217' => 'THB',
      'symbol' => '฿',
    ),
    'USD' => 
    array (
      'name' => 'US Dollars',
      'iso4217' => 'USD',
      'symbol' => '$',
    ),
    'MXN' => 
    array (
      'name' => 'Mexican Pesos',
      'iso4217' => 'MXN',
      'symbol' => '$',
    ),
  ),
  'default_currency_iso4217' => 'AUD',
  'default_currency_name' => 'AU Dollars',
  'default_currency_significant_digits' => 2,
  'default_currency_symbol' => '$',
  'default_date_format' => 'd/m/Y',
  'default_decimal_seperator' => '.',
  'default_email_charset' => 'UTF-8',
  'default_email_client' => 'sugar',
  'default_email_editor' => 'html',
  'default_export_charset' => 'UTF-8',
  'default_language' => 'en_us',
  'default_locale_name_format' => 'f l',
  'default_max_tabs' => 10,
  'default_module' => 'Home',
  'default_module_favicon' => false,
  'default_navigation_paradigm' => 'gm',
  'default_number_grouping_seperator' => ',',
  'default_password' => '',
  'default_permissions' => 
  array (
    'dir_mode' => 1528,
    'file_mode' => 432,
    'user' => '',
    'group' => '',
  ),
  'default_subpanel_links' => false,
  'default_subpanel_tabs' => true,
  'default_swap_last_viewed' => false,
  'default_swap_shortcuts' => false,
  'default_theme' => 'SuiteP',
  'default_time_format' => 'h:ia',
  'default_user_is_admin' => false,
  'default_user_name' => '',
  'demoData' => 'no',
  'developerMode' => true,
  'disable_convert_lead' => false,
  'disable_count_query' => true,
  'disable_export' => false,
  'disable_persistent_connections' => false,
  'disabled_themes' => '',
  'display_email_template_variable_chooser' => false,
  'display_inbound_email_buttons' => false,
  'dump_slow_queries' => true,
  'email_address_separator' => ',',
  'email_allow_send_as_user' => false,
  'email_confirm_opt_in_email_template_id' => '',
  'email_default_client' => 'sugar',
  'email_default_delete_attachments' => false,
  'email_default_editor' => 'html',
  'email_enable_auto_send_opt_in' => false,
  'email_enable_confirm_opt_in' => 'not-opt-in',
  'email_warning_notifications' => true,
  'email_xss' => 'YToxMzp7czo2OiJhcHBsZXQiO3M6NjoiYXBwbGV0IjtzOjQ6ImJhc2UiO3M6NDoiYmFzZSI7czo1OiJlbWJlZCI7czo1OiJlbWJlZCI7czo0OiJmb3JtIjtzOjQ6ImZvcm0iO3M6NToiZnJhbWUiO3M6NToiZnJhbWUiO3M6ODoiZnJhbWVzZXQiO3M6ODoiZnJhbWVzZXQiO3M6NjoiaWZyYW1lIjtzOjY6ImlmcmFtZSI7czo2OiJpbXBvcnQiO3M6ODoiXD9pbXBvcnQiO3M6NToibGF5ZXIiO3M6NToibGF5ZXIiO3M6NDoibGluayI7czo0OiJsaW5rIjtzOjY6Im9iamVjdCI7czo2OiJvYmplY3QiO3M6MzoieG1wIjtzOjM6InhtcCI7czo2OiJzY3JpcHQiO3M6Njoic2NyaXB0Ijt9',
  'enable_action_menu' => true,
  'enable_line_editing_detail' => true,
  'enable_line_editing_list' => true,
  'export_delimiter' => ',',
  'export_excel_compatible' => false,
  'filter_module_fields' => 
  array (
    'Users' => 
    array (
      0 => 'show_on_employees',
      1 => 'portal_only',
      2 => 'is_group',
      3 => 'system_generated_password',
      4 => 'external_auth_only',
      5 => 'sugar_login',
      6 => 'authenticate_id',
      7 => 'pwd_last_changed',
      8 => 'is_admin',
      9 => 'user_name',
      10 => 'user_hash',
      11 => 'password',
      12 => 'last_login',
      13 => 'oauth_tokens',
    ),
    'Employees' => 
    array (
      0 => 'show_on_employees',
      1 => 'portal_only',
      2 => 'is_group',
      3 => 'system_generated_password',
      4 => 'external_auth_only',
      5 => 'sugar_login',
      6 => 'authenticate_id',
      7 => 'pwd_last_changed',
      8 => 'is_admin',
      9 => 'user_name',
      10 => 'user_hash',
      11 => 'password',
      12 => 'last_login',
      13 => 'oauth_tokens',
    ),
  ),
  'google_auth_json' => 'eyJ3ZWIiOnsiY2xpZW50X2lkIjoiMjIxOTM4NTUxOTIyLXB0cmI0amRya2lidHBjbXZ0Z3F0M2ZzbDdjMzRqbjZmLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwicHJvamVjdF9pZCI6InNwZWVkeS1hdG9tLTIzNDMwMiIsImF1dGhfdXJpIjoiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLCJ0b2tlbl91cmkiOiJodHRwczovL29hdXRoMi5nb29nbGVhcGlzLmNvbS90b2tlbiIsImF1dGhfcHJvdmlkZXJfeDUwOV9jZXJ0X3VybCI6Imh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL29hdXRoMi92MS9jZXJ0cyIsImNsaWVudF9zZWNyZXQiOiJyWVQ4VXNsQ0tIbUQtU0pRcnI5M2VsSUsiLCJyZWRpcmVjdF91cmlzIjpbImh0dHBzOi8vc3VpdGVjcm0ucHVyZS1lbGVjdHJpYy5jb20uYXUvaW5kZXgucGhwP2VudHJ5UG9pbnQ9c2F2ZUdvb2dsZUFwaUtleSJdfX0=',
  'hide_history_contacts_emails' => 
  array (
    'Cases' => false,
    'Accounts' => false,
    'Opportunities' => false,
  ),
  'hide_subpanels' => false,
  'history_max_viewed' => 50,
  'host_name' => '54.169.108.55',
  'http_referer' => 
  array (
    'list' => 
    array (
      0 => 'suitecrm.pure-electric.com.au',
    ),
    'actions' => 
    array (
      0 => 'index',
      1 => 'ListView',
      2 => 'DetailView',
      3 => 'EditView',
      4 => 'oauth',
      5 => 'authorize',
      6 => 'Authenticate',
      7 => 'Login',
      8 => 'SupportPortal',
      9 => 'Upgrade',
      10 => 'index',
      11 => 'ListView',
      12 => 'DetailView',
      13 => 'EditView',
      14 => 'oauth',
      15 => 'authorize',
      16 => 'Authenticate',
      17 => 'Login',
      18 => 'SupportPortal',
      19 => 'repair',
    ),
  ),
  'imap_test' => false,
  'import_max_execution_time' => 3600,
  'import_max_records_per_file' => 100,
  'import_max_records_total_limit' => '',
  'inbound_email_case_subject_macro' => '[CASE:%1]',
  'installer_locked' => true,
  'jobs' => 
  array (
    'min_retry_interval' => 30,
    'max_retries' => 5,
    'timeout' => 86400,
  ),
  'js_custom_version' => 1,
  'js_lang_version' => 56,
  'languages' => 
  array (
    'en_us' => 'English (US)',
    'es_es' => 'Español (ES)',
  ),
  'large_scale_test' => false,
  'lead_conv_activity_opt' => 'donothing',
  'list_max_entries_per_page' => '50',
  'list_max_entries_per_subpanel' => 10,
  'lock_default_user_name' => false,
  'lock_homepage' => false,
  'lock_subpanels' => false,
  'log_dir' => '/var/www/suitecrm/log',
  'log_file' => 'suitecrm.log',
  'log_memory_usage' => true,
  'logger' => 
  array (
    'level' => 'debug',
    'file' => 
    array (
      'ext' => '.log',
      'name' => 'suitecrm',
      'dateFormat' => '%c',
      'maxSize' => '1M',
      'maxLogs' => 10,
      'suffix' => '',
    ),
  ),
  'mail_dir' => '/var/www/suitecrm/email',
  'max_dashlets_homepage' => '15',
  'message_command_dir' => '/var/www/message',
  'name_formats' => 
  array (
    's f l' => 's f l',
    'f l' => 'f l',
    's l' => 's l',
    'l, s f' => 'l, s f',
    'l, f' => 'l, f',
    's l, f' => 's l, f',
    'l s f' => 'l s f',
    'l f s' => 'l f s',
  ),
  'oauth2' => 
  array (
    'refresh_token_lifetime' => 3600,
    'access_token_lifetime' => 3600,
  ),
  'oauth2_encryption_key' => 'zNwpbYBAal42slGWgDbxgp5nWPGmdhhJbooFRXMPW98=',
  'outfitters_licenses' => 
  array (
    'quickcrm' => 'f28feea8d65dc0b3f5662cb3e95fba8a',
  ),
  'passwordsetting' => 
  array (
    'SystemGeneratedPasswordON' => '1',
    'generatepasswordtmpl' => '6bdc587e-d826-12ce-5a8a-5694e1a590c8',
    'lostpasswordtmpl' => '6d1dbc2e-38f4-27d6-e08b-5694e1497038',
    'forgotpasswordON' => true,
    'linkexpiration' => true,
    'linkexpirationtime' => 24,
    'linkexpirationtype' => 60,
    'systexpiration' => '',
    'systexpirationtime' => '',
    'systexpirationtype' => '1',
    'systexpirationlogin' => '',
    'minpwdlength' => 6,
    'oneupper' => false,
    'onelower' => false,
    'onenumber' => false,
    'factoremailtmpl' => '48814103-cc14-4a36-7cde-5aacd5a22264',
    'onespecial' => '0',
  ),
  'pe' => 
  array (
    'warehouse' => 
    array (
      'initialNumber' => '1',
    ),
  ),
  'po' => 
  array (
    'purchase_order' => 
    array (
      'initialNumber' => '1',
    ),
  ),
  'portal_view' => 'single_user',
  'quickcrm_max' => 0,
  'quickcrm_server_version' => '6.0',
  'quickcrm_trial' => false,
  'require_accounts' => true,
  'resource_management' => 
  array (
    'special_query_limit' => 50000,
    'special_query_modules' => 
    array (
      0 => 'Reports',
      1 => 'Export',
      2 => 'Import',
      3 => 'Administration',
      4 => 'Sync',
    ),
    'default_limit' => 20000,
  ),
  'rss_cache_time' => '10800',
  'save_query' => 'all',
  'search' => 
  array (
    'defaultEngine' => 'BasicSearchEngine',
    'controller' => 'UnifiedSearch',
  ),
  'search_wildcard_char' => '%',
  'search_wildcard_infront' => false,
  'securitysuite_additive' => true,
  'securitysuite_filter_user_list' => false,
  'securitysuite_inherit_assigned' => true,
  'securitysuite_inherit_creator' => true,
  'securitysuite_inherit_parent' => true,
  'securitysuite_popup_select' => false,
  'securitysuite_strict_rights' => false,
  'securitysuite_user_popup' => true,
  'securitysuite_user_role_precedence' => true,
  'securitysuite_version' => '6.5.17',
  'session_dir' => './sessions',
  'showDetailData' => true,
  'showThemePicker' => true,
  'site_url' => 'http://suitecrm.pure-electric.com.au',
  'slow_query_time_msec' => '100',
  'stack_trace_errors' => false,
  'strict_id_validation' => false,
  'sugar_version' => '6.5.25',
  'sugarbeet' => false,
  'suitecrm_version' => '7.11.18',
  'system_email_templates' => 
  array (
    'confirm_opt_in_template_id' => '61557a5b-c02e-4b48-8a19-5aacd5aee6b0',
  ),
  'theme_settings' => 
  array (
    'SuiteP' => 
    array (
      'display_sidebar' => true,
    ),
  ),
  'time_formats' => 
  array (
    'H:i' => '23:00',
    'h:ia' => '11:00pm',
    'h:iA' => '11:00PM',
    'h:i a' => '11:00 pm',
    'h:i A' => '11:00 PM',
    'H.i' => '23.00',
    'h.ia' => '11.00pm',
    'h.iA' => '11.00PM',
    'h.i a' => '11.00 pm',
    'h.i A' => '11.00 PM',
  ),
  'timef' => 'H:i',
  'tmp_dir' => 'cache/xml/',
  'tracker_max_display_length' => 15,
  'translation_string_prefix' => false,
  'unique_key' => '60d689ad7c64ecb61462677dd1ab7247',
  'upload_badext' => 
  array (
    0 => 'php',
    1 => 'php3',
    2 => 'php4',
    3 => 'php5',
    4 => 'pl',
    5 => 'cgi',
    6 => 'py',
    7 => 'asp',
    8 => 'cfm',
    9 => 'js',
    10 => 'vbs',
    11 => 'html',
    12 => 'htm',
    13 => 'phtml',
  ),
  'upload_dir' => 'upload/',
  'upload_maxsize' => 30000000,
  'use_common_ml_dir' => false,
  'use_real_names' => true,
  'vcal_time' => '2',
  'verify_client_ip' => false,
);
