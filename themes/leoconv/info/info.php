<?php
/**
 * $THEMEDESC
 * 
 * @version		$Id: file.php $Revision
 * @package		modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) Jan 2012 leotheme.com <@emai:leotheme@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
 */  
if( !class_exists('LeoThemeInfo') ){ 
	class LeoThemeInfo{
		
		/**
		 *
		 */
		public static function onGetInfo( $output=array() ){
			 $output["patterns"] = array();
			 $path = _PS_ALL_THEMES_DIR_. _THEME_NAME_."/img/patterns";
				
			$regex = '/(\.gif)|(.jpg)|(.png)|(.bmp)$/i';

		$dk =  opendir ( $path );
		$files = array();
		while ( false !== ($filename = readdir ( $dk )) ) {
			if (preg_match ( $regex, $filename )) {
				$files[] = $filename;	
			}
		}  
	 	$output["patterns"] = $files;
	 
		return $output;
	}
	
	/**
	 *
	 */
	public static function onRenderForm( $html, $thmskins ){
		
		$baseURL =  _PS_BASE_URL_.__PS_BASE_URI__."themes/"._THEME_NAME_."/img/patterns/";

		$pt = '
		
		<link rel="stylesheet" href="'._PS_BASE_URL_.__PS_BASE_URI__."themes/"._THEME_NAME_."/info/assets/form.css".'" type="text/css" media="screen" charset="utf-8" />
		<script type="text/javascript" src="'._PS_BASE_URL_.__PS_BASE_URI__."themes/"._THEME_NAME_."/info/assets/form.js".'"></script>
		<label>'.$thmskins->l('Background Pattern').'</label>
			
		';
		$ps = $thmskins->themeInfo["patterns"];
	//	echo '<Pre>'.print_r( $ps,1); die;
		
		$pt .= '<div class="bgpattern"> <input type="hidden" class="hdval" name="leobgpattern" value="'.Configuration::get('leobgpattern').'"/>';
		foreach( $ps as $p ){  
			$pt .='<div style="background:url(\''.$baseURL.$p.'\');" onclick="return false;" href="#" title="'.$p.'" id="'.preg_replace("#\.\w+$#","",$p).'"'.((preg_replace("#\.\w+$#","",$p)) == Configuration::get('leobgpattern') ? ' class="active"' : '').'>
                </div>';
		}
		$pt  .= "</div>";
		
		$html .= $pt;
		return $html;
	}
	
	public static function onUpdateConfig(  ){
		$leobgpattern = (Tools::getValue('leobgpattern')); 
		Configuration::updateValue('leobgpattern', $leobgpattern);
	}
	
	public static function onProcessHookTop( $params ){
		global $cookie;
		$params["LEO_BGPATTERN"] = $cookie->__get(  "leou_bgpattern", Configuration::get('leobgpattern'));
		return $params; 
	}
}	

}
?>