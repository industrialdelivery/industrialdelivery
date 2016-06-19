<?php

class LofBlock extends ObjectModel
{
	public 		$id;
	public 		$id_loffc_block;
	public		$width;
	public		$show_title=1;
	public		$id_position;
	
	public		$title;
	
	public static $definition = array(
		'table' => 'loffc_block',
		'primary' => 'id_loffc_block',
		'multilang' => true,
		'fields' => array(
			'width' => 				array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
			'show_title' => 		array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'id_position' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			
			// Lang fields
			'title' => 				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255)
		),
	);
	
	
	public function add($autodate = true, $nullValues = false){
		$context = Context::getContext();
		$id_shop = $context->shop->id;
		
		$res = parent::add($autodate, $nullValues);
		$res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'loffc_block_shop` (`id_loffc_block`, `id_shop`)
			VALUES('.(int)$this->id.', '.(int)$id_shop.')'
		);
		
		return $res; 
	}
	
	public function update($nullValues = false){
		return parent::update($nullValues);
	}
	
	public function delete(){
		global $cookie;
		$res = Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'loffc_block_shop`
			WHERE `id_loffc_block` = '.(int)$this->id
		);
		$id_loffc_block = $this->id;
		$res &= parent::delete();
		$return = true;
	 	if( $res ){
			$items = self::getItems($id_loffc_block, $cookie->id_lang);
			if($items){
				foreach($items as $i){
					$obj = new LofItem($i['id_loffc_block_item']);
					$return &= $obj->delete();
				}
			}
		}else{
			$return &= false;
		}
		return $return;
	}
	
	public static function getBlocks( $id_position = false, $id_lang, $id_shop= null ) {
		if(!$id_shop){
			$context = Context::getContext();
			$id_shop = $context->shop->id;
		}
		$res = Db::getInstance()->ExecuteS('
		SELECT fl.*, fll.`title` 
		FROM `'._DB_PREFIX_.'loffc_block` fl
		JOIN `'._DB_PREFIX_.'loffc_block_shop` lbs ON(fl.`id_loffc_block` = lbs.`id_loffc_block` AND lbs.`id_shop` = '.(int)($id_shop).')
		LEFT JOIN `'._DB_PREFIX_.'loffc_block_lang` fll ON(fll.id_loffc_block = fl.id_loffc_block AND fll.`id_lang` = '.(int)($id_lang).')
		WHERE 1 '.($id_position ? ' AND fl.`id_position` = '.(int)($id_position) : '').' 
		ORDER BY fl.`id_loffc_block` ASC' );
		
		return $res;
	}
	
	
	public static function getItems( $id_loffc_block, $id_lang, $id_shop = null){
		if(!$id_shop){
			$context = Context::getContext();
			$id_shop = $context->shop->id;
		}
		$results = Db::getInstance()->ExecuteS('
		SELECT *
		FROM `'._DB_PREFIX_.'loffc_block_item` bi
		JOIN `'._DB_PREFIX_.'loffc_block_item_shop` lbis ON(bi.`id_loffc_block_item` = lbis.`id_loffc_block_item` AND lbis.`id_shop` = '.(int)($id_shop).')
		LEFT JOIN `'._DB_PREFIX_.'loffc_block_item_lang` bil ON(bi.`id_loffc_block_item` = bil.`id_loffc_block_item` AND bil.`id_lang` = '.(int)($id_lang).')
		WHERE bi.`id_loffc_block`='.(int)$id_loffc_block.'
		ORDER BY bi.`position` ASC ');
		
		return $results; 
	}
	
}


