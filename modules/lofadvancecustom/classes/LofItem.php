<?php

class LofItem extends ObjectModel {
 	/** @var string Name */
 	public 		$id;
	public 		$id_loffc_block_item;
	
	public  	$id_loffc_block;
	public 		$type;
	public 		$linktype;
	public 		$link_content;
	public 		$module_name;
	public 		$hook_name;
	public 		$latitude;
	public 		$longitude;
	public 		$addthis;
	public 		$show_title=1;
	public		$target;
	public		$position;
	
	public		$title;
	public		$text;
	
	public static $definition = array(
		'table' => 'loffc_block_item',
		'primary' => 'id_loffc_block_item',
		'multilang' => true,
		'fields' => array(
			'id_loffc_block' => 				array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'type' => 				array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'linktype' => 				array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'link_content' => 				array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'module_name' => 				array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'hook_name' => 				array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'latitude' => 				array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'longitude' => 				array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'addthis' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'show_title' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'target' => 			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'position' => 			array('type' => self::TYPE_INT),

			// Lang fields
			'title' => 				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'text' => 				array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString')
		),
	);
	
	
	public function add($autodate = true, $nullValues = false){ 
		$context = Context::getContext();
		$id_shop = $context->shop->id;
		
		$this->position = LofItem::getLastPosition((int)$this->id_loffc_block, $id_shop);
		$res = parent::add($autodate, $nullValues);
		$res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'loffc_block_item_shop` (`id_loffc_block_item`, `id_shop`)
			VALUES('.(int)$this->id.', '.(int)$id_shop.')'
		);
		return $res;
	}
	
	public function update($nullValues = false){
		return parent::update($nullValues);
	}
	
	public function delete(){
		$res = Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'loffc_block_item_shop`
			WHERE `id_loffc_block_item` = '.(int)$this->id
		);
		$id_loffc_block = $this->id_loffc_block;
		$res &= parent::delete();
		if ($res)
			return $this->cleanPositions($id_loffc_block);
		return false;
	}
	/**
	 * Delete several categories from database
	 *
	 * return boolean Deletion result
	 */
	public function deleteSelection($customfields){
		$return = 1;
		foreach ($customfields AS $id_loffc_block_item){
			$customfield = new LofItem((int)($id_loffc_block_item));
			$return &= $customfield->delete();
		}
		return $return;
	}
	
	public static function getLastPosition($id_loffc_block, $id_shop = null){
		if(!$id_shop){
			$context = Context::getContext();
			$id_shop = $context->shop->id;
		}
		return (Db::getInstance()->getValue('SELECT MAX(i.`position`)+1 
			FROM `'._DB_PREFIX_.'loffc_block_item` i, `'._DB_PREFIX_.'loffc_block_item_shop` lbis
			WHERE i.`id_loffc_block` = '.(int)($id_loffc_block).' AND lbis.`id_shop` = '.(int)($id_shop)));
	}
	
	public function updatePosition($way, $position){
		$context = Context::getContext();
		$id_shop = $context->shop->id;
		
		if (!$res = Db::getInstance()->ExecuteS('
			SELECT cp.`id_loffc_block_item`, cp.`position`, cp.`id_loffc_block` 
			FROM `'._DB_PREFIX_.'loffc_block_item` cp
			JOIN `'._DB_PREFIX_.'loffc_block_item_shop` cps ON(cp.`id_loffc_block_item` = cps.`id_loffc_block_item` AND cps.`id_shop` = '.(int)($id_shop).')
			WHERE cp.`id_loffc_block` = '.(int)$this->id_loffc_block.' 
			ORDER BY cp.`position` ASC'
		))
			return false;
		$ids = array();
		foreach ($res AS $custom_field)
			if ((int)($custom_field['id_loffc_block_item']) == (int)($this->id))
				$movedField = $custom_field;
			else
				$ids[] = (int)$custom_field['id_loffc_block_item'];
		
		if (!isset($movedField) || !isset($position))
			return false;
		
		return (Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'loffc_block_item`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position` 
			'.($way 
				? '> '.(int)($movedField['position']).' AND `position` <= '.(int)($position)
				: '< '.(int)($movedField['position']).' AND `position` >= '.(int)($position)).'
			AND `id_loffc_block`='.(int)($movedField['id_loffc_block']).' AND `id_loffc_block_item` IN ('.implode(',',$ids).')')
		AND Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'loffc_block_item`
			SET `position` = '.(int)($position).'
			WHERE `id_loffc_block_item` = '.(int)($movedField['id_loffc_block_item']).'
			AND `id_loffc_block`='.(int)($movedField['id_loffc_block'])));
	}
	
	public static function cleanPositions($id_loffc_block, $id_shop = null) {
		if(!$id_shop){
			$context = Context::getContext();
			$id_shop = $context->shop->id;
		}
		$result = Db::getInstance()->ExecuteS('
		SELECT cp.`id_loffc_block_item`
		FROM `'._DB_PREFIX_.'loffc_block_item` cp
		JOIN `'._DB_PREFIX_.'loffc_block_item_shop` cps ON(cp.`id_loffc_block_item` = cps.`id_loffc_block_item` AND cps.`id_shop` = '.(int)($id_shop).')
		WHERE cp.`id_loffc_block` = '.(int)($id_loffc_block).'
		ORDER BY cp.`position`');
		$sizeof = sizeof($result);
		for ($i = 0; $i < $sizeof; ++$i){
			$sql = '
			UPDATE `'._DB_PREFIX_.'loffc_block_item`
			SET `position` = '.(int)($i).'
			WHERE `id_loffc_block` = '.(int)($id_loffc_block).'
			AND `id_loffc_block_item` = '.(int)($result[$i]['id_loffc_block_item']);
			Db::getInstance()->Execute($sql);
		}
		return true;
	}
	
	public static function getFooterItems($id_loffc_block_item = false, $id_loffc_block = false, $active = true){
		$context = Context::getContext();
		$id_shop = $context->shop->id;
			
		$sql = 'SELECT value
				FROM `'._DB_PREFIX_.'loffc_block_item` ll
				JOIN `'._DB_PREFIX_.'loffc_block_item_shop` lis ON(ll.`id_loffc_block_item` = lis.`id_loffc_block_item` AND lis.`id_shop` = '.(int)($id_shop).')
				LEFT JOIN `'._DB_PREFIX_.'loffc_block_item_lang` lll ON (ll.`id_loffc_block_item` = lll.`id_loffc_block_item`)
				WHERE 1 '.($id_loffc_block_item ? ' AND ll.`id_loffc_block_item` = '.(int)($id_loffc_block_item) : '').($id_loffc_block ? ' AND ll.`id_loffc_block` = '.(int)($id_loffc_block) : '').
				($active ? ' AND ll.`active`='.(int)($active) : '');
				
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
	}
}
?>