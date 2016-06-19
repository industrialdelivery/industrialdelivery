<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class LeoManageWidgetGroup extends ObjectModel
{
	public $active;
	public $hook_name;
	public $type;
	public $position;
	public $params;
	public $id_shop;
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'leomanagewidget_group',
		'primary' => 'id_leomanagewidget_group',
		'multilang' => false,
		'fields' => array(
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'hook_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true),
			'params' => array('type' => self::TYPE_HTML, 'lang' => false, 'required' => false)
		)
	);

	public function __construct($id_group = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		# validate module
		unset($context);
		parent::__construct($id_group, $id_lang, $id_shop);
	}

	public static function getAllGroup($where = '')
	{
		$context = Context::getContext();
		$id_shop = $context->shop->id;
		$orderBy = ' ORDER BY `hook_name`,`position`';
		$tmpWhere = ' WHERE `id_shop` = '.(int)$id_shop.pSQL($where);
		$sql = 'SELECT * FROM `'._DB_PREFIX_.'leomanagewidget_group` '.$tmpWhere.$orderBy;

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
	}

	public static function getAllGroupId($id_shop = 0)
	{
		$context = Context::getContext();
		if (!$id_shop)
			$id_shop = $context->shop->id;
		$orderBy = ' ORDER BY `hook_name`,`position`';
		$tmpWhere = '';
		if ($id_shop != -1)
			$tmpWhere = ' WHERE `id_shop` = '.(int)$id_shop;
		$sql = 'SELECT `id_leomanagewidget_group` FROM `'._DB_PREFIX_.'leomanagewidget_group` '.$tmpWhere.$orderBy;

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if (!$result)
			return array();
		$tmpData = array();
		foreach ($result as $val)
			$tmpData[] = $val['id_leomanagewidget_group'];

		return $tmpData;
	}

}