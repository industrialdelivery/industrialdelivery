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
if( !class_exists('LeoParams', false) ){
class LeoParams{
  
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
  
  
	protected $currentMod = null;
	/**
	 * Constructor
    */
	public function LeoParams( $current, $name, $configs   ){
		global $cookie;
		$this->currentMod = $current; 
		$this->name = $name;
		
		
		foreach( $configs as $key => $config ){
			$d = Configuration::get( strtoupper($this->name.'_'.($key)) );
			$d = $d != ""? $d:$config;
			
			$this->_data[strtoupper($this->name.'_'.$key)] = $d ;
		}
	}
	
	public function refreshConfig(){
		foreach( $this->_data as $key => $value ){
			$this->_data[$key] = Configuration::get( $key );
		}
		return $this;
	}
	/**
	 * Get configuration's value
	 */
	public function get( $name, $default="" ){
		
		$name = strtoupper($this->name.'_'.($name));	
		if(isset($this->_data[$name])){			
			return $this->_data[$name];
		}else{
			if(Configuration::get($name) != ''){
				$this->_data[$name] = Configuration::get($name);
				return Configuration::get($name);
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
	
	public function delete(){
		$res=true;
		if( !empty($this->_data) ){
			foreach( $this->_data as $key => $value ){
				$res &= Configuration::deleteByName( $key );
			}
		}
		
		return $res;
	}
	/**
	 * Update value for single configuration.
	 */
	public function update( $name ){
		$name = strtoupper($this->name.'_'.$name);
		Configuration::updateValue($name , Configuration::updateValue($name), true);
	}
  
    /**
    * Update value for list of configurations.
    */
    public function batchUpdate( $configurations=array() ){  	
        $res = true;
		foreach( $configurations as $config => $key ){    		
			$v1 = Tools::getValue(strtoupper($this->name.'_'.$config));
			$v = is_array($v1)?implode(',',$v1):$v1;
		
          $res &= Configuration::updateValue(strtoupper($this->name.'_'.$config), $v , true);
        }  
		return $res;
    }
    
	public function l( $lang ){
		return $this->currentMod->l( $lang );
	}
	
	public function inputTag( $label, $name, $value, $note="", $attrs='size="5"' ){
		$html = '
		<label>'.$this->l( $label ).'</label>
		<div class="margin-form">
			<input type="text" name="'.strtoupper($this->name.'_'.$name).'" id="'.$name.'" '.$attrs.' value="'.$value.'" /> '.$note.'
		</div>';
		
		return $html; 
	}
    public function statusTag( $label, $name, $value, $id ){
		$html = '
			<label for="'.$name.'_on">'.$this->l($label ).'</label>
			<div class="margin-form">
				<img src="../img/admin/enabled.gif" alt="Yes" title="Yes" />
				<input type="radio" name="'.strtoupper($this->name.'_'.$name).'" id="'.$id.'_on" '.( $value == 1 ? 'checked="checked"' : '').' value="1" />
				<label class="t" for="'.$name.'_on">'.$this->l('Yes').'</label>
				<img src="../img/admin/disabled.gif" alt="No" title="No" style="margin-left: 10px;" />
				<input type="radio" name="'.strtoupper($this->name.'_'.$name).'" id="'.$id.'_off" '.( $value == 0 ? 'checked="checked" ' : '').' value="0" />
				<label class="t" for="loop_off">'.$this->l('No').'</label>
			</div>';
		return $html;	
	}
	
	/**
	 *
	 */
	public function getSourceDataTag( $current ){

		$path = (dirname(__FILE__)).'/sources/';
		
		if( !is_dir($path) ){
			return $this->l( "Could not found any themes in 'themes' folder" );
		}
		
		$sources = $this->getFolderList( $path );
		
		$html = '<label for="source">'.$this->l( 'Source:' ).'</label>
			<div class="margin-form">';
		$html .='<select name="'.$this->getFieldName('source').'" id="source">';
		foreach( $sources as $source ) {
			$selected = ($source == $current ) ? 'selected="selected"' : '';
			$html .= '<option value="'.$source.'" '. $selected .'>'.$source.'</option>';
		}
		
		$html .='</select>';
		$html .= '</div>'; 
		
		$html .='<div class="group_configs" id="groupconfigs">';
				foreach( $sources as $source ){
					$html .= '<div class="source-group source'.$source.'">';
						$html .= LeoBaseSourcemini::getSource( $source )->renderForm( $this );
					$html .= '</div>';
				}
		$html .='</div>';
		
		$html .= '<script>';
			$html .= '
				$(document).ready( function(){
					$(".source-group").hide();
					$(".source"+$("#source").val() ).show();
					$("#source").change(function(){
					$(".source-group").hide();
					$(".source"+$(this).val() ).show();
				} );	
			} )';
		$html .= '</script>';
		return $html;
		
	}
	
	private function getFieldName( $name ){
		return strtoupper( $this->name.'_'.$name );
	}
	
	/**
	 *
	 */
	public function getThemesTag( $current ){
		$path = dirname(dirname(__FILE__)).'/themes/';
 
		if( !is_dir($path) ){
			return $this->l( "Could not found any themes in tmpl folder" );
		}
		
		$themes = $this->getFolderList( $path );
		$name = $this->getFieldName('theme');
		$html = '<label for="'.$name.'">'.$this->l( 'Theme:' ).'</label>
			<div class="margin-form">';
		$html .='<select name="'.$this->getFieldName('theme').'" id="'.$name.'">';
		foreach( $themes as $theme ) {
			$selected = ($theme == $current ) ? 'selected="selected"' : '';
			$html .= '<option value="'.$theme.'" '. $selected .'>'.$theme.'</option>';
		}
		
		$html .='</select>';
		$html .= '</div>'; 
		return $html;
		
	}
	
	public function selectTag( $data, $label, $name, $value, $note='', $attrs='' ){
		
		$html = '<label for="'.$name.'">'.$this->l( $label ).'</label>
			<div class="margin-form">';
		$html .='<select name="'.$this->getFieldName($name).'" id="'.$name.'" '.$attrs.'>';
		foreach( $data as $key => $item ) {
			$selected = ($key == $value ) ? 'selected="selected"' : '';
			$html .= '<option value="'.$key.'" '. $selected .'>'.$item.'</option>';
		}
		$html .='</select>'.$note;
		$html .= '</div>';
		
		return $html;
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
    * Get List Categories Tree source
	* 
	* @access public
	* @static method
	* return array contain list of categories source 
    */ 
	public function getIndexedCategories(){		
		global $cookie;
		$id_lang = intval($cookie->id_lang);
		
		$allCat = Db::getInstance()->ExecuteS('
		SELECT c.*, cl.id_lang, cl.name, cl.description, cl.link_rewrite, cl.meta_title, cl.meta_keywords, cl.meta_description
		FROM `'._DB_PREFIX_.'category` c
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND `id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = c.`id_category`)
		WHERE `active` = 1		
		GROUP BY c.`id_category`
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
	 * render textarea html tag.
	 */
	public function categoryTag( $name, $value, $title, $attr='', $liAtrr="", $ulAttr = "", $tooltip="", $textAllCat = ""){
	//	echo '<pre>'.print_r($value,1 ); die;
        $children  = $this->getIndexedCategories();
        $list = array();			
        $this->treeCategory( 0, $list , $children );        
        $catArray  = explode(",",$value);
        
        $id = "params_".$name;
        $id = str_replace("[]","",$id);
        
        $isSelected = (in_array("",$catArray))?'selected="selected"':"";        
        $options  = '';        
        foreach($list as $cat){
            $isSelected = (in_array($cat["id_category"],$catArray) || in_array("",$catArray))?'selected="selected"':"";
            $options  .= '<option value="'.$cat["id_category"].'" '.$isSelected.'>---| '.$cat["tree"].$cat["name"].'</option>';                                       
        }
         $html = '<label for="theme">'.$this->l( $title ).'</label><div class="margin-form">';
			   $html .= ' <select '.$attr.' id="'.$id.'" name="'.$this->getFieldName($name).'[]">'.$options.'</select>';
		$html .= '</div>';
        return $html;               		
	}
 }
}
?>