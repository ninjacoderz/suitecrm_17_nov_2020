<?php
/**
 *
 * @package Advanced OpenPortal
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author Salesagility Ltd <support@salesagility.com>
 * Extended by NS-Team for QuickCRM
 */
if (file_exists('custom/QuickCRM/customCase_Updates.php')){
	include('custom/QuickCRM/customCase_Updates.php');
} 
// JAVASCRIPT AND IMAGES REMOVED FOR QUICKCRM
function display_updates($focus, $field, $value, $view){
    global $mod_strings;

    $updates = $focus->get_linked_beans('aop_case_updates',"AOP_Case_Updates");
    if(!$updates){
        return '';
    }
    $html = "<div>";

    usort($updates,function($a,$b){
        $aDate = $a->fetched_row['date_entered'];
        $bDate = $b->fetched_row['date_entered'];
        if($aDate < $bDate){
            return -1;
        }elseif($aDate > $bDate){
            return 1;
        }
        return 0;
    });

    foreach($updates as $update){
        $html .= display_single_update($update);
    }
    $html .= "</div>";
    return $html;
}

function getUpdateDisplayHead(SugarBean $update){
    global $mod_strings,$timedate,$sugar_config,$current_user;
    if($update->contact_id){
        $name = $update->getUpdateContact()->name;
    }elseif($update->assigned_user_id){
        $name = $update->getUpdateUser()->name;
    }else{
        $name = "Unknown";
    }

    $datef = $sugar_config['default_date_format'];
    $timef = $sugar_config['default_time_format'];

    $html = "<span>" .$name . " ".$update->date_entered ."</span><br>";
    return $html;
}

function display_single_update(AOP_Case_Updates $update){

    /*if assigned user*/
    if($update->assigned_user_id){
        /*if internal update*/
        if ($update->internal){
            $html = "<div id='caseStyleInternal'>".getUpdateDisplayHead($update);
            $html .= "<div id='caseUpdate".$update->id."' class='caseUpdate'>";
            $html .= nl2br(html_entity_decode($update->description));
            $html .= "</div></div>";
            return $html;
        }
        /*if standard update*/
        else {
        $html = "<div id='lessmargin'><div id='caseStyleUser'>".getUpdateDisplayHead($update);
        $html .= "<div id='caseUpdate".$update->id."' class='caseUpdate'>";
        $html .= nl2br(html_entity_decode($update->description));
        $html .= "</div></div></div>";
        return $html;
        }
    }else{
        $html = "<div id='extramargin'><div id='caseStyleContact'>".getUpdateDisplayHead($update);
        $html .= "<div id='caseUpdate".$update->id."' class='caseUpdate'>";
        $html .= nl2br(html_entity_decode($update->description));
        $html .= "</div></div></div>";
        return $html;
    }

}

