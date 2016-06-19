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
 /**
	type : 'product','category','cms','link','manufacturer','supplier','module'
 */
if (!defined('_CAN_LOAD_FILES_')){
	define('_CAN_LOAD_FILES_',1);
}
/**
 * lofadvancecustom Class
 */	
require_once( dirname(__FILE__).'/defines.php' );
class lofadvancecustom extends Module
{
	/**
	 * @var LofMegaMenuParams $_params;
	 *
	 * @access private;
	 */
	private $_params = '';	
	/**
	 * @var array $_postErrors;
	 *
	 * @access private;
	 */
	private $_postErrors = array();	
	/**
	* @var array $_languages;
	*
	* @access private;
	*/
	public $_languages = NULL;
	public $_defaultFormLanguage = NULL;
	/**
	 * @var string $base_config_url is stored path;
	 *
	 * @access public 
	 */	
	public $base_config_url;
	public $type;
	public $linktype;
	/**
	* @var array $hookAssign;
	*
	* @access public;
	*/
	public $hookAssign = array();
   /**
    * Constructor 
    */
	function __construct()
	{
		global $currentIndex;
		$this->name = 'lofadvancecustom';
		parent::__construct();			
		$this->tab = 'LandOfCoder';	
		$this->author = 'leotheme';
		$this->version = '1.2';
		$this->displayName = $this->l('Lof Advance Footer Module');
		$this->description = $this->l('Lof Advance Footer Module');
		$this->secure_key = Tools::encrypt($this->name);
		if( file_exists( _PS_ROOT_DIR_.'/modules/'.$this->name.'/libs/params.php' ) && !class_exists( "LofFooterCustomParams", false ) ){
			if( !defined("LOF_LOAD_LIB_PARAMS_FOOTER_CUSTOM") ){
				require_once( _PS_ROOT_DIR_.'/modules/'.$this->name.'/libs/params.php' );
				define("LOF_LOAD_LIB_PARAMS_FOOTER_CUSTOM",true);
			}
		}
		if( file_exists( _PS_ROOT_DIR_.'/modules/'.$this->name.'/classes/LofBlock.php' ) && file_exists( _PS_ROOT_DIR_.'/modules/'.$this->name.'/classes/LofItem.php' )){
			if( !defined("LOF_LOAD_CLASSES_FOOTER_CUSTOM") ){
				require_once( _PS_ROOT_DIR_.'/modules/'.$this->name.'/classes/LofBlock.php' );
				require_once( _PS_ROOT_DIR_.'/modules/'.$this->name.'/classes/LofItem.php' );
				define("LOF_LOAD_CLASSES_FOOTER_CUSTOM",true);
			}
		}
		$this->Languages();
		$this->base_config_url = $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getValue('token');		
		$this->type = array('link','custom_html','module','gmap','addthis');
		
		$this->linktype = array('product','category','cms','link','manufacturer','supplier');
		$this->hookAssign = array('rightcolumn','leftcolumn','home','top','footer');
		$this->_params = new LofFooterCustomParams( $this->name, $this->hookAssign);
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
    * process installing 
    */
	function install() {
		if(!parent::install() || !$this->registerHook('header') || !$this->registerHook('footer') || !$this->registerHook('actionShopDataDuplication') || !$this->_installTradDone())
			return false;
		$this->checkFolderPermission();

		return true;
	}
	private function checkFolderPermission(){
		$module_path =  _PS_ROOT_DIR_.'/modules/'.$this->name;
		$dir_writable = substr(sprintf('%o', fileperms($module_path)), -4) == "0755" ? true : false;
		if(!$dir_writable){
			return $this->chmodr($module_path, 0755);
		}
		
		return true;
	}
	private function chmodr($path, $filemode) {
	    if (!is_dir($path))
	        return chmod($path, $filemode);

	    $dh = opendir($path);
	    while (($file = readdir($dh)) !== false) {
	        if($file != '.' && $file != '..') {
	            $fullpath = $path.'/'.$file;
	            if(is_link($fullpath))
	                return FALSE;
	            elseif(!is_dir($fullpath) && !chmod($fullpath, $filemode))
	                    return FALSE;
	            elseif(!$this->chmodr($fullpath, $filemode))
	                return FALSE;
	        }
	    }

	    closedir($dh);

	    if(chmod($path, $filemode))
	        return TRUE;
	    else
	        return FALSE;
	}
	public function uninstall() {
		if (!parent::uninstall())
			return false;
		/*
		if(!$this->_uninstallTradDone())
            return false;
		*/
		return true;
	}
	/**
	* CREATE database
	*
	*/
	private function _installTradDone() {
		require_once( dirname(__FILE__)."/install/sql.tables.php" );
	 	$error=true;
		if( isset($query) && !empty($query) ){
			if(  !($data=Db::getInstance()->ExecuteS( "SHOW TABLES LIKE '"._DB_PREFIX_."loffc_block'" )) ){
				$query = str_replace( "_DB_PREFIX_", _DB_PREFIX_, $query );
				$query = str_replace( "_MYSQL_ENGINE_", _MYSQL_ENGINE_, $query );
				$db_data_settings = preg_split("/;\s*[\r\n]+/",$query);
				foreach ($db_data_settings as $query){
					$query = trim($query);
					if (!empty($query))	{
						if (!Db::getInstance()->Execute($query)){
							 $error = false;
						}
					}
				}
			}
		} else { $error = false; }
		if(!$error)
			return $error;
		$return = true;
		$idShops = Shop::getShops(false, null , true);
		if(!($data = Db::getInstance()->ExecuteS( "SHOW TABLES LIKE '"._DB_PREFIX_."loffc_block_shop'" ))){
			$sql = "
				CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."loffc_block_shop` (
				  `id_loffc_block` int(11) NOT NULL,
				  `id_shop` int(11) NOT NULL,
				  PRIMARY KEY (`id_loffc_block`,`id_shop`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				" ;
			$result = Db::getInstance()->Execute($sql);
			if(!$result)
				return $result;
			$sql = 'SELECT id_loffc_block FROM `'._DB_PREFIX_.'loffc_block`';
			$idBlocks = Db::getInstance()->ExecuteS($sql);
			if($idBlocks && $idShops){
				foreach($idShops as $id_shop)
					foreach($idBlocks as $block)
						$return &= Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'loffc_block_shop` (`id_loffc_block`, `id_shop`) VALUES('.(int)($block['id_loffc_block']).', '.(int)($id_shop).')');
			}
		}
		if(!($data = Db::getInstance()->ExecuteS( "SHOW TABLES LIKE '"._DB_PREFIX_."loffc_block_item_shop'" ))){
			$sql = "
				CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."loffc_block_item_shop` (
				  `id_loffc_block_item` int(11) NOT NULL,
				  `id_shop` int(11) NOT NULL,
				  PRIMARY KEY (`id_loffc_block_item`,`id_shop`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				" ;
			$result = Db::getInstance()->Execute($sql);
			if(!$result)
				return $result;
			$sql = 'SELECT id_loffc_block_item FROM `'._DB_PREFIX_.'loffc_block_item`';
			$idItems = Db::getInstance()->ExecuteS($sql);
			if($idItems && $idShops){
				foreach($idShops as $id_shop)
					foreach($idItems as $item)
						$return &= Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'loffc_block_item_shop` (`id_loffc_block_item`, `id_shop`) VALUES('.(int)($item['id_loffc_block_item']).', '.(int)($id_shop).')');
			}
		}
		
		return $return;
	}
	/**
	* DROP Table
	*/
	private function _uninstallTradDone() {
		$query = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'loffc_block`,`'._DB_PREFIX_.'loffc_block_item`,`'._DB_PREFIX_.'loffc_block_item_lang`,`'._DB_PREFIX_.'loffc_block_lang`,`'._DB_PREFIX_.'loffc_block_item_shop`,`'._DB_PREFIX_.'loffc_block_shop`;';
		return Db::getInstance()->Execute($query);
	}
	/**
	* Hook Header
	*/
	public function hookHeader($params) {
		$params = $this->_params;
		if(_PS_VERSION_ < "1.5"){
			Tools::addCSS(($this->_path).'/tmpl/'.$params->get('theme','default').'/assets/style.css', 'all');
		}else{
			$this->context->controller->addCSS(($this->_path).'/tmpl/'.$params->get('theme','default').'/assets/style.css', 'all');
		}
	}
	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'loffc_block_shop (id_loffc_block, id_shop)
		SELECT id_loffc_block, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.'loffc_block_shop
		WHERE id_shop = '.(int)$params['old_id_shop']);
		
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'loffc_block_item_shop (id_loffc_block_item, id_shop)
		SELECT id_loffc_block_item, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.'loffc_block_item_shop
		WHERE id_shop = '.(int)$params['old_id_shop']);
	}
	/**
	* Hook Left
	*/
	function hookfooter($params) {
		return $this->processHook( $params, "footer" );
	}
	/**
    * Proccess module by hook
    * $pparams: param of module
    * $pos: position call
    */
	function processHook($pparams = array(), $pos="footer"){
		global $smarty, $cookie, $lofPosition;                  
		//load param
		$params = $this->_params;
		$module_theme = $params->get('theme','default');
		$module_class = $params->get('class','customfooter');
		if(!$lofPosition)
			return '';
		$positions = array();
		$widths = array();
		foreach($lofPosition as $key=>$p){
			$blocks = LofBlock::getBlocks($p,$cookie->id_lang);
			if($blocks){
				$nbw = 0;
				$tw = 0;
				$arrblocks = array();
				foreach($blocks as &$b){
					$items = LofBlock::getItems($b['id_loffc_block'], $cookie->id_lang);
					if($items){
						foreach($items as &$i){
							if($i['type'] == 'link'){
								$i['link_item'] = htmlentities($this->getLinkItem( $i['link_content'], $i['linktype'] ));
							}elseif($i['type'] == 'module'){
								$i['module'] = $this->getModuleAssign($i['module_name'],$i['hook_name']);
							}
						}
						if($b['width'] > 0)
							$nbw += 1;
						$tw += $b['width'];
						$b['items'] = $items;
						$arrblocks[] = $b;
					}
				}
				if($arrblocks){
					$widths[$key]['nbw'] = $nbw;
					$widths[$key]['tw'] = $tw;
					$positions[$key] = $arrblocks;
				}
			}
		}
		$posis = $this->calWidth($positions,$widths);
		//echo "<pre>".print_r($posis,1); die;
		$smarty->assign( array(
			  'lofpositions'     	 => $posis,
			  'module_theme'     => $module_theme,
			  'pos'     		 => $pos,
			  'module_class'     => $module_class
		));
		return $this->display(__FILE__, 'tmpl/'.$module_theme.'/default.tpl');
	}
	/**
	* width calculator
	*/
	public function calWidth($positions, $widths){
		foreach($positions as $key=>&$bls){
			$nbw = $widths[$key]['nbw'];
			$tw = $widths[$key]['tw'];
			$w  = Tools::floorf((100 - $tw)/(count($bls)-$nbw <= 0 ? 1 : count($bls)-$nbw),2);
			foreach($bls as &$bl){
				$bl['width'] = ($bl['width'] > 0 ? $bl['width'] : $w);
			}
		}
		return $positions;
	}
	
	public function getLinkItem( $value, $type ){
		global $link, $cookie;
		$result = '';
		switch ( $type ){
			case 'product':
				if(Validate::isLoadedObject($objPro = new Product($value,true, $cookie->id_lang)))
					$result = $link->getProductLink((int)$objPro->id, $objPro->link_rewrite, NULL, NULL, $cookie->id_lang);
			break;
			case 'category':
				if(Validate::isLoadedObject($objCate = new Category($value, $cookie->id_lang)))
					$result = $link->getCategoryLink((int)$objCate->id, $objCate->link_rewrite, $cookie->id_lang);
			break;
			case 'cms':
				if(Validate::isLoadedObject($objCMS = new CMS($value, $cookie->id_lang)))
					$result = $link->getCMSLink((int)$objCMS->id, $objCMS->link_rewrite, $cookie->id_lang);
			break;
			case 'link':
				$result = strrpos($value, "http://") === false ? $link->getPageLink($value,false) : $value;
			break;
			case 'manufacturer':
				if(Validate::isLoadedObject($objManu = new Manufacturer($value, $cookie->id_lang)))
					$result = $link->getManufacturerLink((int)$objManu->id, $objManu->link_rewrite, $cookie->id_lang);
			break;
			case 'supplier':
				if(Validate::isLoadedObject($objSupp = new Supplier($value, $cookie->id_lang)))
					$result = $link->getSupplierLink((int)$objSupp->id, $objSupp->link_rewrite, $cookie->id_lang);
			break;
		}
		return $result;
	}
	
	public function getModuleAssign( $module_name = '', $hook_name = '' ){
		$module = Module::getInstanceByName($module_name);
		if(_PS_VERSION_ <= "1.5"){
			$id_hook = Hook::get($hook_name);
		}else{
			$id_hook = Hook::getIdByName($hook_name);
		}
		if( Validate::isLoadedObject($module) && $module->id ){
			$array = array();
			$array['id_hook']   = $id_hook;
			$array['module'] 	= $module_name;
			$array['id_module'] = $module->id;
			if(_PS_VERSION_ < "1.5"){
				return self::lofHookExec( $hook_name, array(), $module->id, $array );
			}else{
				$hook_name = substr($hook_name, 7, strlen($hook_name));
				return self::lofHookExecV15( $hook_name, array(), $module->id, $array );
			}
		}
		return '';			
	}
	
	public static function lofHookExec( $hook_name, $hookArgs = array(), $id_module = NULL, $array = array() ){
		global $cart, $cookie;
		if ((!empty($id_module) AND !Validate::isUnsignedId($id_module)) OR !Validate::isHookName($hook_name))
			die(Tools::displayError());

		$live_edit = false;
		if (!isset($hookArgs['cookie']) OR !$hookArgs['cookie'])
			$hookArgs['cookie'] = $cookie;
		if (!isset($hookArgs['cart']) OR !$hookArgs['cart'])
			$hookArgs['cart'] = $cart;
		$hook_name = strtolower($hook_name);
		$altern = 0;
		
		if ($id_module AND $id_module != $array['id_module'])
			return;
		if (!($moduleInstance = Module::getInstanceByName($array['module'])))
			return;
		/*
		$exceptions = $moduleInstance->getExceptions((int)$array['id_hook'], (int)$array['id_module']);
		foreach ($exceptions AS $exception)
			if (strstr(basename($_SERVER['PHP_SELF']).'?'.$_SERVER['QUERY_STRING'], $exception['file_name']) && !strstr($_SERVER['QUERY_STRING'], $exception['file_name']))
				return;
		*/
		if (is_callable(array($moduleInstance, 'hook'.$hook_name)))
		{
			$hookArgs['altern'] = ++$altern;
			$output = call_user_func(array($moduleInstance, 'hook'.$hook_name), $hookArgs);
		}
		return $output;
	}
	public static function lofHookExecV15( $hook_name, $hookArgs = array(), $id_module = NULL, $array = array() ){
		global $cart, $cookie;
		
		if ((!empty($id_module) AND !Validate::isUnsignedId($id_module)) OR !Validate::isHookName($hook_name))
			die(Tools::displayError());
		
		if (!isset($hookArgs['cookie']) OR !$hookArgs['cookie'])
			$hookArgs['cookie'] = $cookie;
		if (!isset($hookArgs['cart']) OR !$hookArgs['cart'])
			$hookArgs['cart'] = $cart;
		
		if ($id_module AND $id_module != $array['id_module'])
			return ;
		if (!($moduleInstance = Module::getInstanceByName($array['module'])))
			return ;
		$retro_hook_name = Hook::getRetroHookName($hook_name);
		
		$hook_callable = is_callable(array($moduleInstance, 'hook'.$hook_name));
		$hook_retro_callable = is_callable(array($moduleInstance, 'hook'.$retro_hook_name));
		
		$output = '';
		if (($hook_callable || $hook_retro_callable) && Module::preCall($moduleInstance->name))
		{
			if ($hook_callable)
				$output = $moduleInstance->{'hook'.$hook_name}($hookArgs);
			else if ($hook_retro_callable)
				$output = $moduleInstance->{'hook'.$retro_hook_name}($hookArgs);
		}
		return $output;
	}
   /**
    * Get list of sub folder's name 
    */
	public function getFolderList( $path ) {
		$items = array();
		$handle = opendir($path);
		if (! $handle) {
			return $items;
		}
		while (false !== ($file = readdir($handle))) {
			if (is_dir($path . $file))
				$items[$file] = $file;
		}
		unset($items['.'], $items['..'], $items['.svn']);
		
		return $items;
	}
   /**
    * Render processing form && process saving data.
    */	
	public function getContent(){
		global $link;
		$html = "";
		if( Tools::isSubmit('submit') ){
			if (!sizeof($this->_postErrors)){
		        $definedConfigs = array(
                  'theme'=> '', 'class'=> ''
		        );
                foreach( $definedConfigs as $config => $key ){
		            if(strlen($this->name.'_'.$config)>=32){
		              echo $this->name.'_'.$config;
		            }else{
		              Configuration::updateValue($this->name.'_'.$config, Tools::getValue($config), true);  
		            }
		    	}
		        $html .= '<div class="conf confirm">'.$this->l('Settings updated successful').'</div>';
			}else{
				foreach ($this->_postErrors AS $err){
					$html .= '<div class="alert error">'.$err.'</div>';
				}
			}
		}elseif(isset($_GET['submitDeleteBlock'])){
			if(Tools::getValue('id_loffc_block') && Validate::isLoadedObject($obj = new LofBlock(Tools::getValue('id_loffc_block'))) && $obj->delete()){
				Tools::redirectAdmin($this->base_config_url.'&lofconf=1');
			}else{
				Tools::redirectAdmin($this->base_config_url.'&lofconf=2');
			}
		}elseif(Tools::isSubmit('submitDeleteData')){
			$shops = Tools::getValue('shops');
			$items = Db::getInstance()->ExecuteS(' SELECT * FROM `'._DB_PREFIX_.'loffc_block_item`' );
			
			foreach($items as $item){
				$sql = 'DELETE FROM `'._DB_PREFIX_.'loffc_block_item_shop` WHERE `id_loffc_block_item` = '.(int)($item['id_loffc_block_item']).' AND `id_shop` IN ('.implode(',',$shops).')';
				Db::getInstance()->execute($sql);
				$sql = 'SELECT * FROM `'._DB_PREFIX_.'loffc_block_item_shop` WHERE `id_loffc_block_item` = '.(int)($item['id_loffc_block_item']);
				if(!($result = Db::getInstance()->executes($sql))){
					$obj = new LofItem( (int)($item['id_loffc_block_item']) );
					$obj->delete();
				}
			}
			$blocks = Db::getInstance()->ExecuteS(' SELECT * FROM `'._DB_PREFIX_.'loffc_block`' );
			if($blocks){
				foreach($blocks as $block){
					$sql = 'DELETE FROM `'._DB_PREFIX_.'loffc_block_shop` WHERE `id_loffc_block` = '.(int)($block['id_loffc_block']).' AND `id_shop` IN ('.implode(',',$shops).')';
					Db::getInstance()->execute($sql);
					$sql = 'SELECT * FROM `'._DB_PREFIX_.'loffc_block_shop` WHERE `id_loffc_block` = '.(int)($block['id_loffc_block']);
					if(!($result = Db::getInstance()->executes($sql))){
						$obj = new LofBlock( (int)($block['id_loffc_block']) );
						$obj->delete();
					}
				}
			}
		}
		$this->_params = new LofFooterCustomParams( $this->name, $this->hookAssign);
		if(Tools::getValue('lofconf') == 1){
			$html .= '<div class="conf confirm">'.$this->l('Delete Block successful').'</div>';
		}elseif(Tools::getValue('lofconf') == 2){
			$html .= '<div class="alert error">'.$this->l('Delete Block error').'</div>';
		}
		if (sizeof($this->_postErrors)){
			foreach ($this->_postErrors AS $err){
				$html .= '<div class="alert error">'.$err.'</div>';
			}
		}
		return $html.$this->_getFormConfig();
	}
	/**
	 * Render Configuration From for user making settings.
	 *
	 * @return context
	 */
	private function _getFormConfig(){		
		global $link;

		$html = '';
	    $themes = $this->getFolderList( dirname(__FILE__)."/tmpl/" );
	    $link = new Link();
	    ob_start();
	    include_once dirname(__FILE__).'/config/lofadvancecustom.php'; 
	    $html .= ob_get_contents();
	    ob_end_clean(); 
		return $html;
	}
   /**
    * Get value of parameter following to its name.
    * 
	* @return string is value of parameter.
	*/
	public function getParamValue($name, $default=''){
		return $this->_params->get( $name, $default );	
	}
	/**
	* get Hook by module id
	*
	*/
	public 	function getParamHooks( $module_name,$hook_name ){
		return $this->_params->getStrHook( $module_name, $hook_name );
	}
	
	public function getModules(){
		$modules =  $this->_params->getModules();
		if(!$modules)
			return array();
		$result = array();
		foreach($modules as $m){
			if($this->_params->getHooksByModuleId($m['id_module']))
				$result[] = $m;
		}
		return $result;
	}
	
	public function getCategories(){
		return $this->_params->lofGetCategories();
	}
	
	public function displayLofFlags($languages, $defaultLanguage, $ids, $id, $return = false) {
		if (sizeof($languages) == 1)
			return false;
		$output = '
		<div class="displayed_flag">
			<img src="img/l/'.$defaultLanguage.'.jpg" class="pointer" id="language_current_'.$id.'" onclick="toggleLanguageFlags(this);" alt="" />
		</div>
		<div id="languages_'.$id.'" class="language_flags">
			'.$this->l('Choose language').'<br />';
		foreach ($languages as $language)
			$output .= '<img src="img/l/'.(int)($language['id_lang']).'.jpg" class="pointer" alt="'.$language['name'].'" title="'.$language['name'].'" onclick="changeLofLanguage(\''.$id.'\', \''.$ids.'\', '.$language['id_lang'].', \''.$language['iso_code'].'\');" /> ';
		$output .= '</div>';
			return $output;
	}
	
	public function getFolderAdmin() {
		$folders = array('cache','classes','config','controllers','css','docs','download','img','js','localization','log','mails',
		'modules','override','themes','tools','translations','upload','webservice','.','..');
		$handle = opendir(_PS_ROOT_DIR_);
		if (! $handle) {
			return false;
		}
		while (false !== ($folder = readdir($handle))) {
			if (is_dir(_PS_ROOT_DIR_ .'/'. $folder)){
				if(!in_array($folder, $folders)){
					$folderadmin = opendir(_PS_ROOT_DIR_ .'/'. $folder);
					if (!$folderadmin) 
						return $folder;
					while (false !== ($file = readdir($folderadmin))) { 
						if (is_file(_PS_ROOT_DIR_ .'/'.  $folder.'/'.$file) && ($file == 'header.inc.php')){
							return $folder;
						}
					}
				}
			}
		}
		return $false;
	}
	
} 