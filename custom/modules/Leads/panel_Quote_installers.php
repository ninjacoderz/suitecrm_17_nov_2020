<?php 
    global $app_list_strings;
    $record = $_REQUEST['record'];
    $module = $_REQUEST['module'];
    $bean = BeanFactory::getBean($module,$record);
    $html = '';

if ($bean->id) {
    $db = DBManagerFactory::getInstance();
    $sql = "  SELECT 
                        aos_quotes.*,
                        aos_quotes_cstm.*,
                        accounts.id as acc_id,
                        accounts.name as acc_name,
                        accounts_cstm.base_install_rate_c as acc_base_install_rate,
                        accounts_cstm.bend_c as acc_bend,
                        accounts_cstm.additional_piping_c as acc_add_piping,
                        accounts_cstm.wall_bracket_c as acc_wall_bracket,
                        accounts_cstm.entry_for_wall_bracket_c as acc_entry_wall_bracket
                    FROM aos_quotes 
                    INNER JOIN aos_quotes_cstm
                        ON aos_quotes.id = aos_quotes_cstm.id_c 
                    INNER JOIN leads_aos_quotes_1_c 
                        ON leads_aos_quotes_1_c.leads_aos_quotes_1aos_quotes_idb = aos_quotes_cstm.id_c 
                    LEFT JOIN accounts 
                        ON  (accounts.id = aos_quotes_cstm.account_id3_c 
                            OR accounts.id = aos_quotes_cstm.account_id2_c 
                            OR accounts.id = aos_quotes_cstm.account_id4_c 
                            OR accounts.id = aos_quotes.proposed_solar_installer_acccount_id) 
                    LEFT JOIN accounts_cstm 
                        ON accounts_cstm.id_c = accounts.id
                    WHERE 
                        leads_aos_quotes_1_c.leads_aos_quotes_1leads_ida ='{$bean->id}' 
                        AND aos_quotes.deleted = 0
                    ORDER BY aos_quotes.number DESC";
    $ret = $db->query($sql);
    $table = '';
    if ($ret->num_rows > 0) {
        $tr = "";
        $i=0;
        while ($row = $db->fetchByAssoc($ret)) {
            if ($row['acc_id'] != '') {
                switch ($row['acc_id']) {
                    case $row['account_id3_c']:
                        $installer_type = "Plumber";
                        break;
                    case $row['account_id2_c']:
                        $installer_type = "Electrician";
                        break;
                    case $row['proposed_solar_installer_acccount_id']:
                        $installer_type = "Solar Installer";
                        break;
                    case $row['account_id4_c']:
                        $installer_type = "Daikin Installer";
                        break;
                    default:
                        $installer_type = "";
                        break;
                } 
                $tr .= " <tr class='ListRowS{$i}'>
                            <td class='footable-first-visible' style='display: table-cell;'>&nbsp;</td>
                            <td style='display: table-cell;'>
                                <a target='_blank' href='?module=AOS_Quotes&action=EditView&record={$row['id']}'>[E]</a>
                                </td>
                            <td style='display: table-cell;'>{$row['number']}</td>
                            <td style='display: table-cell;'>{$app_list_strings['quote_type_list'][$row['quote_type_c']]}</td>
                            <td style='display: table-cell;'>
                                <a target='_blank' href='?module=Accounts&action=EditView&record={$row['acc_id']}'>{$row['acc_name']}</a>
                                </td>
                            <td style='display: table-cell;'>{$installer_type}</td>
                            <td style='display: table-cell;'>{$row['acc_base_install_rate']}</td>
                            <td style='display: table-cell;'>{$row['acc_bend']}</td>
                            <td style='display: table-cell;'>{$row['acc_add_piping']}</td>
                            <td style='display: table-cell;'>{$row['acc_wall_bracket']}</td>
                            <td style='display: table-cell;'>{$row['acc_entry_wall_bracket']}</td>
                        </tr>
                ";
                $i++;
            }
        }
        $tr = "<tbody>{$tr}</tbody>";
        
        $th = " <thead>
                    <tr class='footable-header'>
                        <th data-type='html' class='footable-first-visible' style='display: table-cell;'><!-- extra th for the plus button -->&nbsp;</th>
                        <th data-type='html' style='display: table-cell;'><!-- extra th for the plus button -->&nbsp;</th>
                        <th data-breakpoints='' data-type='html' style='display: table-cell;'>Number</th>
                        <th data-breakpoints='' data-type='html' style='display: table-cell;'>Quote Type</th>
                        <th data-breakpoints='' data-type='html' style='display: table-cell;'>Installer</th>
                        <th data-breakpoints='' data-type='html' style='display: table-cell;'>Type</th>
                        <th data-breakpoints='' data-type='html' style='display: table-cell;'>Base Rate</th>
                        <th data-breakpoints='' data-type='html' style='display: table-cell;'>Bend</th>
                        <th data-breakpoints='' data-type='html' style='display: table-cell;'>Piping</th>
                        <th data-breakpoints='' data-type='html' style='display: table-cell;'>Wall Bracket</th>
                        <th data-breakpoints='' data-type='html' style='display: table-cell;'>Entry W-Bracket</th>
                    </tr>
                </thead>
        ";

        $table = "<table cellpadding='0' cellspacing='0' border='0' class='list view table-responsive subpanel-table footable footable-2 breakpoint-lg' data-empty='No results found.' data-breakpoints='{ 'xs': 754, 'sm': 750, 'md': 768, 'lg': 992}' style=''>";
        $table .= "{$th}{$tr}</table>";

    }
    $html = $table;
}
echo $html;
