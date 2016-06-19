<?php
/**
 * $ModDesc
 * 
 * @version		$Id: file.php $Revision
 * @package		modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) December 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
 */
if (!defined('_CAN_LOAD_FILES_')){
	define('_CAN_LOAD_FILES_',1);
}    
/**
 * lofmanufacturerscroll Class
 */	
class lofmanufacturerscroll2 extends Module
{
	public $entities = array();
	private $_params = '';	
	private $_defaultFormLanguage;
	private $_languages;
	
	public $param_prefix = 'lofmnsc';
	/**
	 * @var array $_postErrors;
	 *
	 * @access private;
	 */
	private $_postErrors = array();		
   /**
    * Constructor 
    */
	function __construct()
	{
		$this->name = 'lofmanufacturerscroll2';
		parent::__construct();			
		$this->tab = 'LandOfCoder';			
		$this->version = '1.0.1';
		$this->displayName = $this->l('Lof Manufacturers Scroll 2');
		$this->description = $this->l('Lof Manufacturers Scroll 2 - Support Responsive');	
		$this->Languages();   
	}
   /**
    * process installing 
    */
	function install(){
		global $cookie;
		if (!parent::install())
			return false;
		if(!$this->registerHook('bottomManufacturer'))
			return false;
		foreach($this->_languages as $language){
			Configuration::updateValue($this->param_prefix.'_module_title_'.$language['id_lang'], 'Featured Brands', true);
		}
		Configuration::updateValue($this->param_prefix.'_show_title', 0, true);
		Configuration::updateValue($this->param_prefix.'_num_of_page', 7, true);
		Configuration::updateValue($this->param_prefix.'_enable_responsive', 1, true);
		Configuration::updateValue($this->param_prefix.'_portraint_change_point', 480, true);
		Configuration::updateValue($this->param_prefix.'_portraint_visible_items', 1, true);
		Configuration::updateValue($this->param_prefix.'_landscape_change_point', 640, true);
		Configuration::updateValue($this->param_prefix.'_landscape_visible_items', 2, true);
		Configuration::updateValue($this->param_prefix.'_tablet_change_point', 768, true);
		Configuration::updateValue($this->param_prefix.'_tablet_visible_items', 3, true);
		Configuration::updateValue($this->param_prefix.'_pause_on_hover', 1, true);
		Configuration::updateValue($this->param_prefix.'_auto_time', 3000, true);
		Configuration::updateValue($this->param_prefix.'_animate_time', 1000, true);
		/*
		$manufacturers = Manufacturer::getManufacturers(false,$cookie->id_lang, true);
		$manus =array();
		foreach($manufacturers as $m){
			$manus[] = $m['id_manufacturer'];
		}
		if($manus){
			$catList = implode(",",$manus);
			Configuration::updateValue($this->param_prefix.'_id_manufacturer', $catList, true);
		}else			
			Configuration::updateValue($this->param_prefix.'_id_manufacturer', '', true);
		*/
		Configuration::updateValue($this->param_prefix.'_navigator',1);
		Configuration::updateValue( $this->param_prefix.'_image_type', 'leo_manufacture' );
		return true;
	}
	public function Languages(){
		global $cookie;
		$allowEmployeeFormLang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		if ($allowEmployeeFormLang && !$cookie->employee_form_lang)
			$cookie->employee_form_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$useLangFromCookie = false;
		$this->_languages = Language::getLanguages(false);
		if ($allowEmployeeFormLang)
			foreach ($this->_languages AS $lang)
				if ($cookie->employee_form_lang == $lang['id_lang'])
					$useLangFromCookie = true;
		if (!$useLangFromCookie)
			$this->_defaultFormLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		else
			$this->_defaultFormLanguage = (int)($cookie->employee_form_lang);
	}
   /**
    * Render processing form && process saving data.
    */	
	public function getContent(){
		$html = "";
		if (Tools::isSubmit('submit'))
		{
			$this->_postValidation();
			if (!sizeof($this->_postErrors))
			{
				$definedConfigs = array(
					'show_title' => '',
					'image_type' => '',
					'num_of_page' => '',
					'animate_time' =>'',
					'auto_play' =>'',
					'enable_responsive'=>'',
					'portraint_change_point'=>'',
					'portraint_visible_items'=>'',
					'landscape_change_point'=>'',
					'landscape_visible_items'=>'',
					'tablet_change_point'=>'',
					'tablet_visible_items'=>'',
					'pause_on_hover'=>'',
					'auto_time'=>'',
					'navigator' => ''
				);
				foreach($this->_languages as $language){
					$definedConfigs['module_title_'.$language['id_lang']] = '';
				}
				if( $definedConfigs ){
					foreach( $definedConfigs as $config => $value ){    
						Configuration::updateValue($this->param_prefix.'_'.$config, Tools::getValue($config), true);
					}
				}
				if(Tools::getValue('id_manufacturer')){
    		        if(in_array("",Tools::getValue('id_manufacturer'))){
    		          $catList = "";
    		        }else{
    		          $catList = implode(",",Tools::getValue('id_manufacturer'));  
    		        }
                    Configuration::updateValue($this->param_prefix.'_id_manufacturer', $catList, true);
                }
				$html .= '<div class="conf confirm">'.$this->l('Settings updated success').'</div>';
				
			}else{
				foreach ($this->_postErrors AS $err)
				{
					$html .= '<div class="alert error">'.$err.'</div>';
				}
			}
		}
		return $html.$this->displayForm();
	}
	/**
     * Process vadiation before saving data 
     */
	private function _postValidation(){
		if ( Validate::isString(Tools::getValue('entity')) )
			$this->_postErrors[] = $this->l('Please, choice Entity');
	}
	/**
	* Form config
	*/
	public function displayForm()
	{
		global $smarty, $cookie;
		$divLangName = 'module_title';
		$this->site_url = Tools::htmlentitiesutf8('http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__);
		// META KEYWORDS
		$str = '';
		$str .= '
			<script type="text/javascript">
				id_language='.$this->_defaultFormLanguage.';
			</script>
		';
		$str .= '	<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<label>'.$this->l('Module Title').' </label>
				<div class="margin-form">';
		foreach ($this->_languages as $language){
			$title = Configuration::get( $this->param_prefix.'_module_title_'.$language['id_lang'] );
			$str .= '	<div id="module_title_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
						<input size="50" type="text" name="module_title_'.$language['id_lang'].'" value="'.$title.'" />
					</div>';
		}
		$str .= $this->displayFlags($this->_languages, $this->_defaultFormLanguage, $divLangName, 'module_title',true);
		$str .= '	</div><div class="clear space">&nbsp;</div>';
		$str .= ' <label>'.$this->l('Scroll number').' </label>
				<div class="margin-form">';
		$num_of_page = Configuration::get( $this->param_prefix.'_num_of_page' );
		$str .= '
				<input size="20" type="text" name="num_of_page" value="'.$num_of_page.'" />
			';
		$str .= ' </div>';
		$show_title = Configuration::get( $this->param_prefix.'_show_title' );
		$str .= '	<label>'.$this->l('Show Title:').' </label>
				<div class="margin-form">
					<input type="radio" name="show_title" id="show_title_on" onclick="toggleDraftWarning(false);" value="1" '.($show_title ? 'checked="checked" ' : '').'/>
					<label class="t" for="show_title_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Yes').'" title="'.$this->l('Yes').'" /></label>
					<input type="radio" name="show_title" id="show_title_off" onclick="toggleDraftWarning(true);" value="0" '.(!$show_title ? 'checked="checked" ' : '').'/>
					<label class="t" for="show_title_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('No').'" title="'.$this->l('No').'" /></label>
				</div>';
		/*Enable responsive*/
		$enable_responsive = Configuration::get( $this->param_prefix.'_enable_responsive' );
		$str .= '	<label>'.$this->l('Enable Responsive:').' </label>
				<div class="margin-form">
					<input type="radio" name="enable_responsive" id="enable_responsive_on" onclick="toggleDraftWarning(false);" value="1" '.($enable_responsive ? 'checked="checked" ' : '').'/>
					<label class="t" for="enable_responsive_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Yes').'" title="'.$this->l('Yes').'" /></label>
					<input type="radio" name="enable_responsive" id="enable_responsive_off" onclick="toggleDraftWarning(true);" value="0" '.(!$enable_responsive ? 'checked="checked" ' : '').'/>
					<label class="t" for="enable_responsive_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('No').'" title="'.$this->l('No').'" /></label>
				</div>';
		$str .= '<div class="item_wrap">';
		$str .= '<p><label style="color:#0000FF">'.$this->l('Portrait').'</label></p><br/>';
		$str .= ' <label>'.$this->l('Change Point').' </label>
				<div class="margin-form">';
		$change_point1 = Configuration::get( $this->param_prefix.'_portraint_change_point' );
		$str .= '
				<input size="20" type="text" name="portraint_change_point" value="'.$change_point1.'" />
				<br/><label class="t">'.$this->l('For example: 480').'</label>
			';
		$str .= ' </div><br/>';
		$str .= ' <label>'.$this->l('Visible Items').' </label>
				<div class="margin-form">';
		$visible_items1 = Configuration::get( $this->param_prefix.'_portraint_visible_items' );
		$str .= '
				<input size="20" type="text" name="portraint_visible_items" value="'.$visible_items1.'" />
				<br/><label class="t">'.$this->l('For example: 1').'</label>
			';
		$str .= ' </div><hr/>';
		$str .= ' </div>';
		///
		$str .= '<div class="item_wrap">';
		$str .= '<p><label style="color:#0000FF">'.$this->l('Landscape').'</label></p><br/>';
		$str .= ' <label>'.$this->l('Change Point').' </label>
				<div class="margin-form">';
		$change_point2 = Configuration::get( $this->param_prefix.'_landscape_change_point' );
		$str .= '
				<input size="20" type="text" name="landscape_change_point" value="'.$change_point2.'" />
				<br/><label class="t">'.$this->l('For example: 640').'</label>
			';
		$str .= ' </div>';
		$str .= ' <label>'.$this->l('Visible Items').' </label>
				<div class="margin-form">';
		$visible_items2 = Configuration::get( $this->param_prefix.'_landscape_visible_items' );
		$str .= '
				<input size="20" type="text" name="landscape_visible_items" value="'.$visible_items2.'" />
				<br/><label class="t">'.$this->l('For example: 2').'</label>
			';
		$str .= ' </div><hr/>';
		$str .= ' </div>';
		///
		$str .= '<div class="item_wrap">';
		$str .= '<p><label style="color:#0000FF">'.$this->l('Tablet').'</label></p><br/>';
		$str .= ' <label>'.$this->l('Change Point').' </label>
				<div class="margin-form">';
		$change_point3 = Configuration::get( $this->param_prefix.'_tablet_change_point' );
		$str .= '
				<input size="20" type="text" name="tablet_change_point" value="'.$change_point3.'" />
				<br/><label class="t">'.$this->l('For example: 768').'</label>
			';
		$str .= ' </div>';
		$str .= ' <label>'.$this->l('Visible Items').' </label>
				<div class="margin-form">';
		$visible_items3 = Configuration::get( $this->param_prefix.'_tablet_visible_items' );
		$str .= '
				<input size="20" type="text" name="tablet_visible_items" value="'.$visible_items3.'" />
				<br/><label class="t">'.$this->l('For example: 3').'</label>
			';
		$str .= ' </div><hr/>';
		$str .= ' </div>';
		/*End enable responsive*/
		$auto_play = Configuration::get( $this->param_prefix.'_auto_play' );
		$str .= '	<label>'.$this->l('Auto play:').' </label>
				<div class="margin-form">
					<input type="radio" name="auto_play" id="auto_play_on" onclick="toggleDraftWarning(false);" value="1" '.($auto_play ? 'checked="checked" ' : '').'/>
					<label class="t" for="auto_play_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Yes').'" title="'.$this->l('Yes').'" /></label>
					<input type="radio" name="auto_play" id="auto_play_off" onclick="toggleDraftWarning(true);" value="0" '.(!$auto_play ? 'checked="checked" ' : '').'/>
					<label class="t" for="auto_play_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('No').'" title="'.$this->l('No').'" /></label>
				</div>';
		$pause_on_hover = Configuration::get( $this->param_prefix.'_pause_on_hover' );
		$str .= '	<label>'.$this->l('Pause on hover:').' </label>
				<div class="margin-form">
					<input type="radio" name="pause_on_hover" id="pause_on_hover_on" onclick="toggleDraftWarning(false);" value="1" '.($pause_on_hover ? 'checked="checked" ' : '').'/>
					<label class="t" for="auto_play_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Yes').'" title="'.$this->l('Yes').'" /></label>
					<input type="radio" name="pause_on_hover" id="pause_on_hover_off" onclick="toggleDraftWarning(true);" value="0" '.(!$pause_on_hover ? 'checked="checked" ' : '').'/>
					<label class="t" for="auto_play_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('No').'" title="'.$this->l('No').'" /></label>
				</div>';
		$str .= ' <label>'.$this->l('Auto play time').' </label>
				<div class="margin-form">';
		$auto_time = Configuration::get( $this->param_prefix.'_auto_time' );
		$str .= '
				<input size="20" type="text" name="auto_time" value="'.(int)$auto_time.'" />
				<br/><label class="t">'.$this->l('For example: 3000').'</label>
			';
		$str .= ' </div>';
		$animate_time = Configuration::get( $this->param_prefix.'_animate_time' );
		$str .= '	<label>'.$this->l('Animate Time:').' </label>
				<div class="margin-form">
					<input type="text" name="animate_time" id="animate_time" value="'.(int)$animate_time.'"/>
					<br/><label class="t">'.$this->l('For example: 1000').'</label>
					<br/>
				</div>';
		$manu = Configuration::get($this->param_prefix.'_id_manufacturer');
		$selected = array();
		$isSelected = 'selected="selected"';
		if($manu){
			$selected = explode(',',$manu); 
			$isSelected = (in_array("",$selected))?'selected="selected"':""; 
		}
		$manufacturers = Manufacturer::getManufacturers(false,$language['id_lang'], true);
		$str .= '	<label>'.$this->l('Manufacturers:').' </label>
				<div class="margin-form">
					<select name="id_manufacturer[]" multiple="multiple" size="10" id="params_manufactures">
						<option value="" onclick="lofSelectAll(\'#params_manufactures\');" '.$isSelected.'>'.$this->l('All Manufacturers').'</option>';
			foreach ($manufacturers AS $manufacturer)
				$str .= '<option value="'.$manufacturer['id_manufacturer'].'"'.(in_array($manufacturer['id_manufacturer'],$selected) ? ' selected="selected"' : '').' '.$isSelected.'>&nbsp;&nbsp;&nbsp;'.$manufacturer['name'].'</option>';
				$str .= '</select> <sup>*</sup>
				</div>';
		$imagesTypes = ImageType::getImagesTypes('manufacturers');
		$str .= '	<label>'.$this->l('Image Type:').' </label>
				<div class="margin-form">
					<select name="image_type">';
			foreach ($imagesTypes AS $i)
				$str .= '<option value="'.$i['name'].'"'.($i['name'] == Configuration::get($this->param_prefix.'_image_type') ? ' selected="selected"' : '').'>&nbsp;&nbsp;'.$i['name'].'&nbsp;&nbsp;</option>';
				$str .= '</select>
				</div>';
		$navigator = array( 1=> $this->l('Enable'), 2=> $this->l('Enable When mouse over'), 3=> $this->l('Disable'));
		$str .= '	<label>'.$this->l('Navigation:').' </label>
				<div class="margin-form">
					<select name="navigator">';

			foreach ($navigator AS $key=>$i)
				$str .= '<option value="'.$key.'"'.($key == Configuration::get($this->param_prefix.'_navigator') ? ' selected="selected"' : '').'>&nbsp;&nbsp;'.$i.'&nbsp;&nbsp;</option>';
				$str .= '</select>
				</div>';
			
		$str .= '
			<center><input type="submit" name="submit" value="'.$this->l('Save').'" class="button" /></center>
		</form>
		<script type="text/javascript">
			function lofSelectAll(obj){	
				$(obj).find("option").each(function(index,Element) {
					$(Element).attr("selected","selected");
				});	
			}
		</script>';
		return $str;
	}
	/*
	 * register hook right comlumn to display slide in right column
	 */
	function hookHeader($params) {
		$params = $this->_params;
		
		if(_PS_VERSION_ <= "1.4"){
			$cssjs  = "<link href='"._MODULE_DIR_.$this->name."/assets/style.css' rel='stylesheet' type='text/css' media='all' />";
			$cssjs .= "<script type=\"text/javascript\" src=\""._MODULE_DIR_.$this->name."/assets/jquery.flexisel.js\"></script>";
			return $cssjs;
		}elseif(_PS_VERSION_ < "1.5"){
			Tools::addCSS(_MODULE_DIR_.$this->name."/assets/style.css", 'all');
			Tools::addJS(_MODULE_DIR_.$this->name."/assets/jquery.flexisel.js");
		}else{
			$this->context->controller->addCSS(_MODULE_DIR_.$this->name."/assets/style.css", 'all');
			$this->context->controller->addJS(_MODULE_DIR_.$this->name."/assets/jquery.flexisel.js");
		}
	}
	/*
	 * register hook right comlumn to display slide in right column
	 */
	function hookrightColumn($params)
	{
		return $this->processHook( $params,"rightColumn");
	}
	
	function hooklofBottom($params)
	{
		return $this->processHook( $params,"lofBottom");
	}
	
	/*
	 * register hook left comlumn to display slide in left column
	 */
	function hookleftColumn($params)
	{
		return $this->processHook( $params,"leftColumn");
	}
	function hookbottomManufacturer($params)
	{
		return $this->processHook( $params,"bottomManufacturer");
	}
	
	function hooktop($params)
	{
		return '</div><div class="clearfix"></div><div>'.$this->processHook( $params,"top");
	}
	
	function hookfooter($params)
	{		
		return $this->processHook( $params,"footer");
	}
	function hookDisplayBottom($params){ 		
		return $this->processHook( $params,"bottom");
	}
	
	function hookHome($params)
	{
		return $this->processHook( $params,"home");
	}
	public function processHook( $params, $pos = 'home' ){
		global $smarty, $cookie,$link;
		$this->site_url = Tools::htmlentitiesutf8('http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__);
		
		$module_title = Configuration::get( $this->param_prefix.'_module_title_'.$cookie->id_lang );
		$show_title = Configuration::get( $this->param_prefix.'_show_title' );
		$limit = Configuration::get( $this->param_prefix.'_num_of_page' );
		$limit = empty($limit)?5:$limit;
		$auto_play = Configuration::get( $this->param_prefix.'_auto_play' );
		$auto_play = empty($auto_play)?0:$auto_play;
		$animate_time = Configuration::get( $this->param_prefix.'_animate_time' );
		$animate_time = empty($animate_time)?1000:$animate_time;
		$enable_responsive = Configuration::get( $this->param_prefix.'_enable_responsive' );
		$enable_responsive = empty($enable_responsive)?0:$enable_responsive;
		$portraint_change_point = Configuration::get( $this->param_prefix.'_portraint_change_point' );
		$portraint_change_point = empty($portraint_change_point)?480:$portraint_change_point;
		$portraint_visible_items = Configuration::get( $this->param_prefix.'_portraint_visible_items' );
		$portraint_visible_items = empty($portraint_visible_items)?1:$portraint_visible_items;
		$landscape_change_point = Configuration::get( $this->param_prefix.'_landscape_change_point' );
		$landscape_change_point = empty($landscape_change_point)?640:$landscape_change_point;
		$landscape_visible_items = Configuration::get( $this->param_prefix.'_landscape_visible_items' );
		$landscape_visible_items = empty($landscape_visible_items)?2:$landscape_visible_items;
		$tablet_change_point = Configuration::get( $this->param_prefix.'_tablet_change_point' );
		$tablet_change_point = empty($tablet_change_point)?768:$tablet_change_point;
		$tablet_visible_items = Configuration::get( $this->param_prefix.'_tablet_visible_items' );
		$tablet_visible_items = empty($tablet_visible_items)?3:$tablet_visible_items;
		$pause_on_hover = Configuration::get( $this->param_prefix.'_pause_on_hover' );
		$pause_on_hover = empty($pause_on_hover)?0:$pause_on_hover;
		$auto_time = Configuration::get( $this->param_prefix.'_auto_time' );
		$auto_time = empty($auto_time)?3000:$auto_time;


		$id_manufacturers = Configuration::get( $this->param_prefix.'_id_manufacturer' );
		
		if(!$id_manufacturers){
			$manus = Manufacturer::getManufacturers(false,$cookie->id_lang, true);
			foreach($manus as $m){
				$id_manufacturers[] = $m['id_manufacturer'];
			}
		}else{
			$id_manufacturers = explode(',',$id_manufacturers);
		}
		$manufacturers = array();
		foreach($id_manufacturers as $id_manufacturer){
			$manufacturer = new Manufacturer($id_manufacturer,$cookie->id_lang);
			if(Validate::isLoadedObject($manufacturer)){
				$manufacturers[$id_manufacturer]['link'] = $link->getManufacturerLink($id_manufacturer,$manufacturer->link_rewrite,$cookie->id_lang);
				$image_type = Configuration::get( $this->param_prefix.'_image_type' );
				$id_images = (!file_exists(_PS_MANU_IMG_DIR_.'/'.$id_manufacturer.'-'.$image_type.'.jpg')) ? Language::getIsoById((int)$cookie->id_lang).'-default' : $id_manufacturer;
				$manufacturers[$id_manufacturer]['linkIMG'] = _THEME_MANU_DIR_.$id_images.'-'.$image_type.'.jpg';
				$manufacturers[$id_manufacturer]['id_manufacturer'] = $id_manufacturer;
				$manufacturers[$id_manufacturer]['name'] = $manufacturer->name;
			}
		}
		$smarty->assign(
			array(
				'modname' 		=> $this->name,
				'pos' 			=> $pos,	
				'animate_time' => $animate_time,
				'auto_play'	=> $animate_time,
				'limit' 		=> $limit,	
				'module_title' 	=> $module_title,	
				'show_title' 	=> $show_title,	
				'lofmanufacturers' => $manufacturers
			)
		);
		ob_start();
			require( dirname(__FILE__).'/initjs.php' );		
			$initjs = ob_get_contents();
		ob_end_clean();
		return $this->display(__FILE__,'lofmanufacturerscroll2.tpl').$initjs;
	}
	
	public function displayFlags($languages, $default_language, $ids, $id, $return = false, $use_vars_instead_of_ids = false)
	{
		if (sizeof($languages) == 1)
			return false;
		$output = '
		<div class="displayed_flag">
			<img src="../img/l/'.$default_language.'.jpg" class="pointer" id="language_current_'.$id.'" onclick="toggleLanguageFlags(this);" alt="" />
		</div>
		<div id="languages_'.$id.'" class="language_flags">
			'.$this->l('Choose language:').'<br /><br />';
		foreach ($languages as $language)
			if($use_vars_instead_of_ids)
				$output .= '<img src="../img/l/'.(int)($language['id_lang']).'.jpg" class="pointer" alt="'.$language['name'].'" title="'.$language['name'].'" onclick="changeLanguage(\''.$id.'\', '.$ids.', '.$language['id_lang'].', \''.$language['iso_code'].'\');" /> ';
			else
				$output .= '<img src="../img/l/'.(int)($language['id_lang']).'.jpg" class="pointer" alt="'.$language['name'].'" title="'.$language['name'].'" onclick="changeLanguage(\''.$id.'\', \''.$ids.'\', '.$language['id_lang'].', \''.$language['iso_code'].'\');" /> ';
		$output .= '</div>';

		if ($return)
			return $output;
		echo $output;
	}
} 