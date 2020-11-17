<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2016 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

class QCRM_gen_pdf_letter{
	
function gen_pdf($module,$uid,$templateID,$save = False){
require_once('modules/AOS_PDF_Templates/PDF_Lib/mpdf.php');
require_once('modules/AOS_PDF_Templates/templateParser.php');
require_once('modules/AOS_PDF_Templates/AOS_PDF_Templates.php');

global $sugar_config, $current_user;

	$bean = BeanFactory::getBean($module, $uid);

	if(!$bean){
    	return false;
	}

	$recordIds = array($uid);


	$template = BeanFactory::getBean('AOS_PDF_Templates',$templateID);

	if(!$template){
		return false;
	}

	$page_def = 'A4';
	if (isset($sugar_config['quickcrm_pdf_format'])){
    	$page_def = $sugar_config['quickcrm_pdf_format'];
	}
    else if (isset($sugar_config['suitecrm_version']) && version_compare($sugar_config['suitecrm_version'], '7.8.3', '>=')) {
	        $orientation = ($template->orientation == "Landscape") ? "-L" : "";
	        $page_def = $template->page_size . $orientation;
    }

	$pdf = new mPDF('en', $page_def, '', 'DejaVuSansCondensed', $template->margin_left, $template->margin_right, $template->margin_top, $template->margin_bottom, $template->margin_header, $template->margin_footer);

	foreach ($recordIds as $recordId) {
    	$bean->retrieve($recordId);

    	$object_arr = array();
    	$object_arr[$bean->module_dir] = $bean->id;

    	if ($bean->module_dir === 'Contacts') {
        	$object_arr['Accounts'] = $bean->account_id;
    	}

    	$search = array('@<script[^>]*?>.*?</script>@si',        // Strip out javascript
        	'@<[\/\!]*?[^<>]*?>@si',        // Strip out HTML tags
	        '@([\r\n])[\s]+@',            // Strip out white space
    	    '@&(quot|#34);@i',            // Replace HTML entities
        	'@&(amp|#38);@i',
	        '@&(lt|#60);@i',
    	    '@&(gt|#62);@i',
	        '@&(nbsp|#160);@i',
    	    '@&(iexcl|#161);@i',
        	'@<address[^>]*?>@si'
	    );

    	$replace = array('',
        	'',
	        '\1',
    	    '"',
	        '&',
    	    '<',
	        '>',
    	    ' ',
        	chr(161),
	        '<br>'
    	);

	    $text = preg_replace($search, $replace, $template->description);
    	$text = preg_replace_callback('/\{DATE\s+(.*?)\}/',
        	function ($matches) {
            	return date($matches[1]);
	        },
    	    $text);
	    $header = preg_replace($search, $replace, $template->pdfheader);
    	$footer = preg_replace($search, $replace, $template->pdffooter);

		$text = $this->parse_custom_types($text,$bean);
	    
	    $converted = templateParser::parse_template($text, $object_arr);
	    $header = templateParser::parse_template($header, $object_arr);
	    $footer = templateParser::parse_template($footer, $object_arr);

	    $printable = str_replace("\n", "<br />", $converted);

	    ob_clean();
	    try {
	        $pdf->SetHTMLHeader($header);
	        $pdf->AddPage();
	        $pdf->setAutoFont();
	        $pdf->SetHTMLFooter($footer);
	        $pdf->writeHTML($printable);
	        
			if (isset($sugar_config['quickcrm_prerelease']) && $sugar_config['quickcrm_prerelease']){
		        if ($save){
			        $file_name = $template->name;
        			$note = new Note();
        			$note->modified_user_id = $current_user->id;
        			$note->created_by = $current_user->id;
	        		$note->name = $file_name;
    	    		$note->parent_type = $bean->module_dir;
        			$note->parent_id = $bean->id;
        			$note->file_mime_type = 'application/pdf';
        			$note->filename = $file_name . '.pdf';
	        		if ($bean->module_dir == 'Contacts') {
    	        		$note->contact_id = $bean->id;
        	    		$note->parent_type = 'Accounts';
            			$note->parent_id = $bean->account_id;
        			}
        			$note->save();

	        		$fp = fopen($sugar_config['upload_dir'] . 'nfile.pdf', 'wb');
    	    		fclose($fp);

			        $pdf->Output($sugar_config['upload_dir'] . 'nfile.pdf', 'F');

        			rename($sugar_config['upload_dir'] . 'nfile.pdf', $sugar_config['upload_dir'] . $note->id);
				}
        	}

	    } catch (mPDF_exception $e) {
	        return False;
	    }
	}

    return array('name' => str_replace(" ", "_", $template->name), 'contents' => $pdf->Output("", "S"));
}
function parse_custom_types($str,$bean){
	global $sugar_config;
	
	$variableName = strtolower($bean->module_dir);

	foreach($bean->field_name_map as $field_name => $field_defs)
	// manage custom image field types
	{
			if ($field_defs['type'] == 'Drawing'){
				$height = empty($field_defs['height'])?'300px':$field_defs['height'];
				$width = empty($field_defs['width'])?'400px':$field_defs['width'];
				// load file contents
				$file = "upload://{$bean->id}_{$field_name}.png";
				if(file_exists( $file )) {
					$res= base64_encode(file_get_contents($file,true));
					$str = str_replace("\$" . $variableName . "_" . $field_name, '<img width="'.$width.'" height="'.$height.'" src="data:image/png;base64,'.$res.'">' , $str);
				}
			}
			else if ($field_defs['type'] == 'Signature'){
				if (!empty($bean->$field_name)){
					$height = $field_defs['height'];
					$width = $field_defs['width'];
					$str = str_replace("\$" . $variableName . "_" . $field_name, '<img width="'.$width.'px" height="'.$height.'px" src="'.$bean->$field_name.'">' , $str);
				}
			}
	}
	return $str;
}

}