<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/classes/LofBlock.php');
global $cookie;

include(dirname(__FILE__).'/lofadvancecustom.php');
$module = new lofadvancecustom(); 
if( isset($_POST['lofajax']) && ($_POST['task'] == 'gethook')){
	$module_name = Tools::getValue('module_name');
	$hook_name = Tools::getValue('hook_name');
	$content = $module->getParamHooks( $module_name, $hook_name );
	die( $content );
}
if( isset($_GET['lofajax']) && ($_GET['task'] == 'positionItem') && Tools::getValue('action') == 'dnd'){
	$blocks = LofBlock::getBlocks(false, $cookie->id_lang);
	if($blocks){
		foreach($blocks as $b){
			if(Tools::getValue('loftable-'.$b['id_loffc_block'])){
				$table = Tools::getValue('loftable-'.$b['id_loffc_block']);
			}
		}
	}
	if (isset($table)){
		$pos = 0;
		foreach ($table as $key =>$row){
			$ids = explode('_', $row);
			Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'loffc_block_item` 
			SET `position` = '.(int)$pos.' 
			WHERE `id_loffc_block_item` = '.(int)$ids[2].' AND `id_loffc_block` = '.(int)$ids[1]);
			$pos++;
		}
	}
}
if( isset($_GET['lofajax']) && ($_GET['task'] == 'deleteItem')){
	$json_data = array();
	$json_data['result'] = 1;
	if ($table = Tools::getValue('class_tr')){
		$ids = explode('_', $table);
		if(Validate::isLoadedObject($obj = new LofItem($ids[2]))){
			if(!$obj->delete()){
				$json_data['result'] = 0;
				$json_data['error'] = $module->l('can\'t delete');
			}
		}else{
			$json_data['result'] = 0;
			$json_data['error'] = $module->l('can\'t create object');
		}
	}
	die(json_encode($json_data));
}
if( isset($_GET['lofajax']) && ($_GET['task'] == 'updateBlock')){
	$json_data = array();
	$json_data['result'] = 1;
	if(Validate::isLoadedObject($obj = new LofBlock(Tools::getValue('id_loffc_block')))){
		$errors = array();
		$titles = array();
		foreach($module->_languages as $language)
			$titles[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
		if(!Tools::getValue('title_'.$module->_defaultFormLanguage))
			$errors[] = $module->l('the field Title is required at least in '.$defaultLanguage->name);
		foreach($titles as $key => $val)
			if(!Validate::isGenericName($val))
				$errors[] = $module->l('the field Title is invalid');
		
		if(Tools::getValue('width') && !Validate::isFloat(Tools::getValue('width')))
			$errors[] = $module->l('the field Width is invalid');
			
		$obj->title = $titles;
		$obj->width = Tools::getValue('width');
		$obj->show_title = Tools::getValue('show_title');
		if (!sizeof($errors)){
			if(!$obj->update()){
				$json_data['result'] = 0;
				$json_data['error'] = $module->l('can\'t update object');
			}
		}else{
			$json_data['result'] = 0;
			$json_data['error'] = implode(', ',$errors);
		}
	}else{
		$json_data['result'] = 0;
		$json_data['error'] = $module->l('can\'t create object');
	}

	die(json_encode($json_data));
}

?>