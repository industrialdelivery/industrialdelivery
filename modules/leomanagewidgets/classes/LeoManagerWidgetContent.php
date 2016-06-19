<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class LeoManagerWidgetContent extends ObjectModel
{
	public $id_content;
	public $id_column;
	public $id_shop;
	public $position;
	public $active;
	public $key_widget;
	public $module_name;
	public $hook_name;
	public $type;
	public $params;
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'leomanagewidget_content',
		'primary' => 'id_content',
		'multilang' => false,
		'fields' => array(
			'id_column' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'position' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'key_widget' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'module_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
			'hook_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
			'type' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'params' => array('type' => self::TYPE_HTML, 'lang' => false)
		)
	);

	public function __construct($id_slide = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		# validate module
		unset($context);
		parent::__construct($id_slide, $id_lang, $id_shop);
	}

	public static function getAllRowId($id_shop = 0)
	{
		$context = Context::getContext();
		if (!$id_shop)
			$id_shop = $context->shop->id;
		$orderBy = ' ORDER BY `id_column`,`position`';
		$tmpWhere = '';
		if ($id_shop != -1)
			$tmpWhere = ' WHERE `id_shop` = '.(int)$id_shop;
		$sql = 'SELECT `id_content` FROM `'._DB_PREFIX_.'leomanagewidget_content` '.$tmpWhere.$orderBy;

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if (!$result)
			return array();
		$tmpData = array();
		foreach ($result as $val)
			$tmpData[] = $val['id_content'];
		return $tmpData;
	}

	public static function getAllRowColumn($where = '', $id_shop = 0)
	{
		$context = Context::getContext();
		if (!$id_shop)
			$id_shop = $context->shop->id;
		$tmpWhere = ' WHERE lc.`id_shop` = '.(int)$id_shop.pSQL($where);
		$orderBy = ' ORDER BY `id_column`,`position`';

		$sql = 'SELECT lc.* FROM `'._DB_PREFIX_.'leomanagewidget_content` as lc'.$tmpWhere.$orderBy;
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

		return $result;
	}

}