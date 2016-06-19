<?php 
/**
 * $ModDesc
 * 
 * @version   $Id: file.php $Revision
 * @package   modules
 * @subpackage  $Subpackage.
 * @copyright Copyright (C) November 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */
if( !class_exists('LofFooterCustomParams', false) ){
class LofFooterCustomParams{
  
    /**
    * @var string name ;
    *
    * @access public;
    */
    public  $name   = '';	
	
    /**
    * @var string name ;
    *
    * @protected public;
    */
    protected $_data= array();
	private $_languages = NULL;
    private $_defaultFormLanguage = NULL;
	public  $hookAssign   = array();
	public  $context;
	/**
	 * Constructor
    */
	public function LofFooterCustomParams( $name, $hookAssign = array()){
		global $cookie;
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_shop_group = $this->context->shop->id_shop_group;
		$this->name  = $name;		
		$this->hookAssign  = $hookAssign;
		
		$id_lang = intval($cookie->id_lang);
		$result = Db::getInstance()->ExecuteS('
		SELECT c.name,IFNULL('.($id_lang ? 'cl' : 'c').'.`value`, c.`value`) AS value
		FROM `'._DB_PREFIX_.'configuration` c
		'.($id_lang ? ('LEFT JOIN `'._DB_PREFIX_.'configuration_lang` cl ON (c.`id_configuration` = cl.`id_configuration` AND cl.`id_lang` = '.intval($id_lang).')') : '').'
		WHERE `name` LIKE \''.pSQL($name).'%\' AND (`id_shop_group` = NULL OR `id_shop_group` = '.(int)($id_shop_group).') AND (`id_shop` = NULL OR `id_shop` = '.(int)($id_shop).')');
		
		foreach ($result as $row) {
			$this->_data[$row["name"]] = $row["value"];
		}
		
		$this->Languages();
	}
	/**
	 * Get configuration's value
	 */
	public function get( $name, $default="" ){		
		if(isset($this->_data[$this->name.'_'.$name])){			
			return $this->_data[$this->name.'_'.$name];
		}else{
			if(Configuration::get($this->name.'_'.$name) != ''){
				$this->_data[$this->name.'_'.$name] = Configuration::get($this->name.'_'.$name);
				return Configuration::get($this->name.'_'.$name);
			}elseif( isset($this->_data[$name]) ){
				return $this->_data[$name];	
			}		
		}				
		
    	return $default;
	}
  
	/**
	 * Store configuration's value as temporary.
	 */
	public function set( $key, $value ){
	  $this->_data[$key] = $value;
    }
  
	/**
	 * Update value for single configuration.
	 */
	public function update( $name ){
		Configuration::updateValue($this->name.'_'.$name, Tools::getValue($name), true);
	}
  
    /**
    * Update value for list of configurations.
    */
    public function batchUpdate( $configurations=array() ){  	
        foreach( $configurations as $config => $key ){    
          Configuration::updateValue($this->name.'_'.$config, Tools::getValue($config), true);
        }  
    }
    
    /**
	 * render input.
    */  
    public function fileTag($name, $fileOption, $fileValues, $fileLangArr, $title, $attr, $liAtrr="", $ulAttr = ""){
        $id       = "params_".$name;
        $options  = '';
                                                                     
        $str = '<ul '.$ulAttr.'>
                    <li class="lof-config-left">
                        <label for="'.$id.'">'.$title.'</label>
                    </li>
                    <li class="lof-config-right">
                        <div><label><b>'.$fileLangArr["is_ena"].'</b></label><b> 
                            <select name="'.$name.'-enable" id="lof-group'.$name.'" class="select-group">';                        
        foreach ($fileOption["enable"] as $val=> $text){
            $isSelected = ($val == $fileValues["enable"])?'selected="selected"':"";
            $str .= '<option '.$isSelected.' value="'.$val.'">'.$text."</option>";                 
        }                            
        $str .='            </select></b>
                        </div>
                        <div id="lof-groupfile1container" class="lof-container '.$name.'-enable-1" style="display: block;">
                            <fieldset class="lof-fsi">                                                                                            
                                <div class="lof-cols"><label>'.$fileLangArr["preview"].'</label>
                                    <input type="text" name="'.$name.'-preview" id="" value="'.$fileValues["preview"].'">
                                </div>                                                                                                                                                                                          
                                <div class="lof-cols"><label>'.$fileLangArr["link"].'</label>
                                    <input type="text" name="'.$name.'-link" id="" value="'.$fileValues["link"].'">
                                </div>
                                <div class="lof-cols"><label>'.$fileLangArr["title"].'</label>
                                    <input type="text" name="'.$name.'-title" id="" value="'.$fileValues["title"].'">
                                </div>
                                <textarea style="width:94%;min-height:60px" name="'.$name.'-desc">'.$fileValues["desc"].'</textarea></b>
                            </fieldset>                            
                        </div>                          
                    </li>
                </ul>    
                ';
        if($liAtrr){
            $str = '<li '.$liAtrr.'>'.$str.'</li>';
        }               
        return $str;  	
    }
    
    /**
	 * render input.
    */  
    public function inputTag( $name, $value, $title, $attr, $liAtrr="", $ulAttr = "", $tooltip=""){
        $id       = "params_".$name;
        $options  = '';
                                       
        $str = '<ul '.$ulAttr.'>
                    <li class="lof-config-left">
                        <label for="'.$id.'">'.$title.'</label>
                    </li>
                    <li class="lof-config-right">
                        <input name="'.$name.'" id="'.$id.'" value="'.$value.'" '.$attr.' type="text">';
        if($tooltip){
            $str .= '<i class="clearfix">'.$tooltip.'</i>';   
        }                                                  
        $str .=     '</li>
                </ul>    
                ';
        if($liAtrr){
            $str = '<li '.$liAtrr.'>'.$str.'</li>';
        }               
        return $str;  	
    }
    /**
	 * render input.
    */  
    public function inputTagLang($divLangName, $name, $arrvalue, $title, $attr, $liAtrr="", $ulAttr = "", $tooltip=""){
		self::Languages();
		$strValue = '';
		foreach ($this->_languages as $language){
			$strValue .= '<div id="'.$name.'_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').';float: left;width: 230px;">
					<input size="29" type="text" id="c'.$name.'_'.$language['id_lang'].'" name="'.$name.'_'.$language['id_lang'].'" value="'.$arrvalue[$language['id_lang']].'" />
				</div>';
		}
        $options  = '';
        
        $str = '<ul '.$ulAttr.'>
                    <li class="lof-config-left">
                        <label>'.$title.'</label>
                    </li>
                    <li class="lof-config-right">'.$strValue.$this->displayFlags($this->_languages, $this->_defaultFormLanguage, $divLangName, $name);
        if($tooltip){
            $str .= '<i class="clearfix">'.$tooltip.'</i>';   
        }
        $str .=     '</li>
                </ul>    
                ';
        if($liAtrr){
            $str = '<li '.$liAtrr.'>'.$str.'</li>';
        }               
        return $str;  	
    }
    /**
	 * render textarea html tag.
	 */
	public function getCategory( $name, $value, $title, $attr, $liAtrr="", $ulAttr = "", $tooltip="", $textAllCat = ""){
        $children  = $this->getIndexedCategories();
        $list = array();			
        if(_PS_VERSION_ < "1.5")
			$this->treeCategory( 0, $list , $children );
		else
			$this->treeCategory( 1, $list , $children );
		
        $catArray  = explode(",",$value);
        
        $id = "params_".$name;
        $id = str_replace("[]","",$id);
        
        $isSelected = (in_array("",$catArray))?'selected="selected"':"";        
        $options  = '<option value="" onclick="lofSelectAll(\'#params_category\');" '.$isSelected.'>-- '.$textAllCat.'</option>';        
        foreach($list as $cat){
            $isSelected = (in_array($cat["id_category"],$catArray) || in_array("",$catArray))?'selected="selected"':"";
            $options  .= '<option value="'.$cat["id_category"].'" '.$isSelected.'>---| '.$cat["tree"].$cat["name"].'</option>';                                       
        }
                          
        $str = '<ul '.$ulAttr.'>
                    <li class="lof-config-left">
                        <label for="'.$id.'">'.$title.'</label>
                    </li>
                    <li class="lof-config-right">
                        <select '.$attr.' id="'.$id.'" name="'.$name.'">'.$options.'</select>';
                
        if($tooltip){
            $str .= '<i class="clearfix">'.$tooltip.'</i>';   
        }
        $str .= "</li></ul>";
        
        if($liAtrr){
            $str = '<li '.$liAtrr.'>'.$str.'</li>';
        }            
        return $str;               		
	}
    
    /**
	 * render radio html tag.
	 */
	public function radioBooleanTag( $name, $yesNoLang, $value, $title, $attr , $liAtrr="", $ulAttr = "" , $tooltip=""){		
        $str = '<ul '.$ulAttr.'>
                    <li class="lof-config-left">
                        <label>'.$title.'</label>
                    </li>
                    <li class="lof-config-right">';
                    
        foreach($yesNoLang as $key=>$val){            
            $isChecked = ($key == $value)?'checked="checked"':"";                  
            $str .= '<input type="radio" value="'.$key.'" id="params'.$name.$key.'" name="'.$name.'" '.$attr.' '.$isChecked.'><label for="params'.$name.$key.'">'.$val.'</label>';
            $attr ="";    
        }              
        if($tooltip){
            $str .= '<i class="clearfix">'.$tooltip.'</i>';   
        }
        $str .= "</li></ul>";
        
        if($liAtrr){
            $str = '<li '.$liAtrr.'>'.$str.'</li>';
        }               
        return $str;
	}    
    
    /**
	 * render textarea html tag.
	 */
	public function textareaTag( $name, $values=array(), $value, $title, $attr, $liAtrr="", $ulAttr = "" , $tooltip="" ){
		$string = __($title).'<textarea name="%s" id="%s" %s>%s</textarea>';
		$id =  $obj->get_field_id($name);
		return '<p class="">'
				.$this->labelTag($id,sprintf($string,$obj->get_field_name($name),$id,'',$value) )
			 	.'</p>';
	}
    
    /**
	 * render lof group tag.
	 */
    public function lofGroupTag($title, $class, $liAtrr="", $ulAttr = ""){
        $str = '<ul '.$ulAttr.'>
                    <li class="lof-config-left">&nbsp;</li>
                    <li class="lof-config-right">
                        <div class="'.$class.'">'.$title.'</div>                                    
                    </li>
                </ul>';
        if($liAtrr){
            $str = '<li '.$liAtrr.'>'.$str.'</li>';
        }               
        return $str; 
    }
    
    /**
	 * render lof group tag.
	 */
    public function lofOverrideLinksTag($value, $title, $addRowText, $liAtrr="", $ulAttr = "", $tooltip=""){    
        $str = '<ul '.$ulAttr.'>
                    <li class="lof-config-left">
                        <label>'.$title.'</label>
                    </li>
                    <li class="lof-config-right">
                        <fieldset class="it-addrow-block">
                            <legend><span class="add" id="btna-override_links">'.$addRowText.'</span></legend>';
        
        if($value){                        
			$linkArray  = explode(",",$value);
            $row = "";
            foreach( $linkArray as $key=> $value ){
    			$str .= '
    				<div class="row">
    					<span class="spantext">'.($key+1).'</span>
    					<input type="text" value="'.$value.'" name="override_links[]">
    					<span class="remove"></span>
    				</div>
    			';
    		}            
        }                            
        $str .= '</fieldset>';        
        if($tooltip){            
            $str .= '<i class="clearfix">'.$tooltip.'</i>';  
        }
        $str .= "</li></ul>";
        
        if($liAtrr){
            $str = '<li '.$liAtrr.'>'.$str.'</li>';
        }               
        return $str;
    }
    
    /**
	 * render select html tag.
	 */
	public function selectTag($name, $values=array(), $value, $title, $attr, $liAtrr="", $ulAttr = "" , $tooltip=""){
	    $id       = "params_".$name;
        $options  = '';
        
        foreach ($values as $val=> $text){
            $isSelected = ($val == $value)?'selected="selected"':"";
            $options .= '<option '.$isSelected.' value="'.$val.'">'.$text."</option>";                 
        }
                          
        $str = '<ul '.$ulAttr.'>
                    <li class="lof-config-left">
                        <label for="'.$id.'">'.$title.'</label>
                    </li>
                    <li class="lof-config-right">
                        <select '.$attr.' id="'.$id.'" name="'.$name.'">'.$options.'</select>';
                
        if($tooltip){
            $str .= '<i class="clearfix">'.$tooltip.'</i>';   
        }
        $str .= "</li></ul>";
        
        if($liAtrr){
            $str = '<li '.$liAtrr.'>'.$str.'</li>';
        }               
        return $str;                                  
	}
    
    /**
    * Build category tree list
    */
	public static function treeCategory($id, &$list, $children, $tree=""){		
		if (isset($children[$id])){			
			if($id != 0){
				$tree = $tree." - ";
			}
			foreach ($children[$id] as $v)
			{	
				$v["tree"] = $tree;				
				$list[] = $v;							
				self::treeCategory( $v["id_category"], $list, $children,$tree);
			}
		}		
	}
    
    /**
    * Get List Categories Tree source
	* 
	* @access public
	* @static method
	* return array contain list of categories source 
    */ 
	public function getIndexedCategories(){		
		global $cookie;
		$id_lang = intval($cookie->id_lang);
		if(_PS_VERSION_ < "1.5")
			$join = '';
		else
			$join = 'JOIN `'._DB_PREFIX_.'category_shop` cs ON(c.`id_category` = cs.`id_category` AND cs.`id_shop` = '.(int)(Context::getContext()->shop->id).')';
			
		$allCat = Db::getInstance()->ExecuteS('
		SELECT c.*, cl.id_lang, cl.name, cl.description, cl.link_rewrite, cl.meta_title, cl.meta_keywords, cl.meta_description
		FROM `'._DB_PREFIX_.'category` c
		'.$join.'
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND `id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = c.`id_category`)
		WHERE `active` = 1 
		GROUP BY c.`id_category`
		ORDER BY `id_category` ASC');
		$children = array();
		if ( $allCat )
		{
			foreach ( $allCat as $v )
			{
				$pt 	= $v["id_parent"];
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
			return $children;
		}		
		return array();
	}
	/**
    * Get List Categories Tree source
	* 
	* @access public
	* @static method
	* return array contain list of categories source 
    */ 
	public function getIndexedCMSCategories(){		
		global $cookie;
		$id_lang = intval($cookie->id_lang);
		
		$allCat = Db::getInstance()->ExecuteS('
		SELECT c.*, cl.id_lang, cl.name, cl.description, cl.link_rewrite, cl.meta_title, cl.meta_keywords, cl.meta_description
		FROM `'._DB_PREFIX_.'cms_category` c
		LEFT JOIN `'._DB_PREFIX_.'cms_category_lang` cl ON (c.`id_cms_category` = cl.`id_cms_category` AND `id_lang` = '.intval($id_lang).')
		WHERE `active` = 1		
		GROUP BY c.`id_cms_category`
		ORDER BY `name` ASC');		
		$children = array();
		if ( $allCat )
		{
			foreach ( $allCat as $v )
			{				
				$pt 	= $v["id_parent"];
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
			return $children;
		}	

		return array();
	}
	
    /**
    * Build category tree list
    */
	public static function treeCMSCategory($id, &$list, $children, $tree=""){		
		if (isset($children[$id])){			
			if($id != 0){
				$tree = $tree." - ";
			}
			foreach ($children[$id] as $v)
			{	
				$v["tree"] = $tree;				
				$list[] = $v;							
				self::treeCMSCategory( $v["id_cms_category"], $list, $children,$tree);
			}
		}		
	}
	/**
	* get modules
	* 
	*/
	public function getModules(){
		$notModule = array( $this->name );
		$where = '';
		if( count($notModule) == 1){
			$where = ' WHERE m.`name` <> \''.$notModule[0].'\'';
		}elseif( count($notModule) > 1){
			$where = ' WHERE m.`name` NOT IN (\''.implode("','",$notModule).'\')';
		}
		
		$id_shop = $this->context->shop->id;
		
		return Db::getInstance()->ExecuteS('
		SELECT m.*
		FROM `'._DB_PREFIX_.'module` m
		JOIN `'._DB_PREFIX_.'module_shop` ms ON (m.`id_module` = ms.`id_module` AND ms.`id_shop` = '.(int)($id_shop).')
		'.$where );
	}
	/**
	* get modules
	* 
	*/
	public function getModulById( $id_module ){
		return Db::getInstance()->getRow('
		SELECT m.*
		FROM `'._DB_PREFIX_.'module` m
		JOIN `'._DB_PREFIX_.'module_shop` ms ON (m.`id_module` = ms.`id_module` AND ms.`id_shop` = '.(int)($this->context->shop->id).')
		WHERE m.`id_module` = '.$id_module);
	}
	/**
	* get Hooks in module 
	* 
	*/
	public function getHooksByModuleId( $id_module ){
		$module = self::getModulById( $id_module );
		$moduleInstance = Module::getInstanceByName( $module['name'] );
		$hooks = array();
		if($this->hookAssign)
			foreach( $this->hookAssign as $hook){
				if(_PS_VERSION_ < "1.5"){
					if (is_callable(array($moduleInstance, 'hook'.$hook))){
						$hooks[] = $hook;
					}
				}else{
					$retro_hook_name = Hook::getRetroHookName($hook);
					if (is_callable(array($moduleInstance, 'hook'.$hook)) || is_callable(array($moduleInstance, 'hook'.$retro_hook_name))){
						$hooks[] = $retro_hook_name;
					}
				}
			}
		$results = self::getHookByArrName( $hooks );
		return $results;
	}
	/**
	* get Hook by Id_hook
	*
	*/
	public static function getHookById( $id_hook ){
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
		SELECT *
		FROM `'._DB_PREFIX_.'hook` 
		WHERE `id_hook` = '.$id_hook);
	}
	/**
	* get Hook by name array
	*	
	*/
	public static function getHookByArrName($arrName){
		$result = Db::getInstance()->ExecuteS('
		SELECT `id_hook`, `name`
		FROM `'._DB_PREFIX_.'hook` 
		WHERE `name` IN (\''.implode("','",$arrName).'\')');
		return $result ;
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
	
    public function displayFlags( $languages, $defaultLanguage, $ids, $id, $return = false )
	{
		$megamenu = new lofmegamenu();
		if (sizeof($languages) == 1)
			return false;
		$output = '
		<div class="displayed_flag">
			<img src="../img/l/'.$defaultLanguage.'.jpg" class="pointer" id="language_current_'.$id.'" onclick="toggleLanguageFlags(this);" alt="" />
		</div>
		<div id="languages_'.$id.'" class="language_flags">
			'.$megamenu->l('Choose language:').'<br />';
		foreach ($languages as $language)
			$output .= '<img src="../img/l/'.(int)($language['id_lang']).'.jpg" class="pointer" alt="'.$language['name'].'" title="'.$language['name'].'" onclick="changeLanguage1(\''.$id.'\', \''.$ids.'\', '.$language['id_lang'].', \''.$language['iso_code'].'\');" /> ';
		$output .= '</div>';
			return $output;
	}
	
	public function getStrHook( $module_name, $hook_name ){
		$module = Module::getInstanceByName($module_name);
		if(!$module->id)
			return '';
		$hooks = self::getHooksByModuleId( $module->id );
		$options = '';
		if( !empty($hooks)){
			foreach( $hooks as $hook ){
				$options .= '<option value="'.$hook['name'].'"'.(strtolower($hook['name']) == strtolower($hook_name) ? 'selected="selected"' : '').'>'.$hook['name']."</option>";
			}
		}
		return $options;
	}
	/**
	* get categories
	*/
	public function lofGetCategories(){
		$children  = $this->getIndexedCategories();
		$array_keys = array_keys($children);
		$first_key = array_shift($array_keys);
        $list = array();			
        $this->treeCategory( $first_key, $list , $children );
        return $list;
	}
}
}
?>