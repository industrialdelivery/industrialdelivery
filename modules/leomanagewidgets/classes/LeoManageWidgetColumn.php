<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class LeoManageWidgetColumn extends ObjectModel
{
	public $id_group;
	public $active;
	public $params;
	public $position;
	public $id_shop;
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'leomanagewidget_column',
		'primary' => 'id_column',
		'multilang' => false,
		'fields' => array(
			'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'id_group' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'params' => array('type' => self::TYPE_HTML, 'lang' => false)
		)
	);

	public function __construct($id_slide = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		# validate module
		unset($context);
		parent::__construct($id_slide, $id_lang, $id_shop);
	}

	public static function getAllColumn($where = '', $id_shop = 0, $widgetInfo = 0, $isfont = 0)
	{
		$context = Context::getContext();
		if (!$id_shop)
			$id_shop = $context->shop->id;
		$tmpWhere = ' WHERE mw.`id_shop` = '.(int)$id_shop.pSQL($where);
		$orderBy = ' ORDER BY `id_group`,`position`';

		$sql = 'SELECT mw.* FROM `'._DB_PREFIX_.'leomanagewidget_column` as mw'.$tmpWhere.$orderBy;
		$resultMW = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		//echo "<pre>";print_r($resultMW);die;
		if (!$widgetInfo)
			return $resultMW;

		$orderBy = ' ORDER BY lc.`position`';
		//get all widget
		$sql = 'SELECT
                        lc.`id_content` AS id,
                        lc.`id_column`,
                        lc.`position`,
                        lc.`active`,
                        lc.`key_widget`,
                        lc.`module_name`,
                        lc.`hook_name`,
                        lc.`type`,
                        lc.`params`,
                        lc.`id_shop`,
                        lw.`id_leowidgets`,
                        lw.`name`
                FROM
                    `'._DB_PREFIX_.'leomanagewidget_content` lc
                LEFT JOIN `'._DB_PREFIX_.'leowidgets` lw ON lc.key_widget = lw.key_widget
                AND lw.id_shop = '.(int)$id_shop.
				' WHERE lc.id_shop =  '.(int)$id_shop.pSQL($where).$orderBy;
		$resultW = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		//get all module in shop
		$modules = Db::getInstance()->ExecuteS('
            SELECT m.name, m.id_module
            FROM `'._DB_PREFIX_.'module` m
            JOIN `'._DB_PREFIX_.'module_shop` ms ON (m.`id_module` = ms.`id_module` AND ms.`id_shop` = '.(int)($id_shop).')
            ');
		$widgetList = array();
		//check if module in shop.
		foreach ($resultW as $row)
		{
			if ($row['module_name'])
			{
				foreach ($modules as $module)
				{
					$active = 0;
					if ($row['module_name'] == $module['name'])
					{
						$active = 1;
						break;
					}
				}
				if ($active == 0)
					$row['active'] = 0;
			}
			if (!$isfont || ($isfont && $row['active'] == 1))
				$widgetList[$row['id_column']][] = $row;
			
		}
		foreach ($resultMW as &$row)
		{

			if (isset($widgetList[$row['id_column']]))
				$row['rows'] = $widgetList[$row['id_column']];
		}
		return $resultMW;
	}

	public static function getAllColumnId($id_shop = 0)
	{
		$context = Context::getContext();
		if (!$id_shop)
			$id_shop = $context->shop->id;
		$orderBy = ' ORDER BY `id_group`,`position`';
		$tmpWhere = '';
		if ($id_shop != -1)
			$tmpWhere = ' WHERE `id_shop` = '.(int)$id_shop;
		$sql = 'SELECT `id_column` FROM `'._DB_PREFIX_.'leomanagewidget_column` '.$tmpWhere.$orderBy;

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if (!$result)
			return array();
		$tmpData = array();
		
		foreach ($result as $val)
			$tmpData[] = $val['id_column'];

		return $tmpData;
	}

	public static function getAllOldColumn()
	{
		$sql = 'SELECT mw.* FROM `'._DB_PREFIX_.'leomanagewidget` as mw';
		$resultMW = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		return $resultMW;
	}

}