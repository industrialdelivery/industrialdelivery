<?php
/**
 *  Leo Prestashop SliderShow for Prestashop 1.6.x
 *
 * @package   leosliderlayer
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

if (!defined('_PS_VERSION_'))
	exit;

class LeoSliderGroup extends ObjectModel
{
	public $title;
	public $active;
	public $hook;
	public $id_shop;
	public $params;
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'leosliderlayer_groups',
		'primary' => 'id_leosliderlayer_groups',
		'fields' => array(
			'title' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
			'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'hook' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 64),
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'params' => array('type' => self::TYPE_HTML, 'lang' => false)
		)
	);

	public function __construct($id_group = null, $id_shop = null)
	{
		parent::__construct($id_group, $id_shop);
	}

	public function add($autodate = true, $null_values = false)
	{
		$res = parent::add($autodate, $null_values);

		return $res;
	}

	public static function groupExists($id_group)
	{
		$req = 'SELECT gr.`id_leosliderlayer_groups` as id_group
                FROM `'._DB_PREFIX_.'leosliderlayer_groups` gr
                        WHERE gr.`id_leosliderlayer_groups` = '.(int)$id_group;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}

	public function getGroups($active = null, $id_shop)
	{
		$this->context = Context::getContext();
		if (!isset($id_shop))
			$id_shop = $this->context->shop->id;
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                    SELECT *
                    FROM `'._DB_PREFIX_.'leosliderlayer'.'_groups` gr
                    WHERE (`id_shop` = '.(int)$id_shop.')'.
						($active ? ' AND gr.`active` = 1' : ' '));
	}

	public static function deleteAllSlider($id_group)
	{
		$sliders = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT sl.`id_leosliderlayer_slides` as id
                        FROM `'._DB_PREFIX_.'leosliderlayer_slides` sl
                        WHERE sl.`id_group` = '.(int)$id_group);

		if ($sliders)
		{
			$res = Db::getInstance()->execute('
                        DELETE FROM `'._DB_PREFIX_.'leosliderlayer_slides`
                        WHERE `id_group` = '.(int)$id_group
			);

			$where = '';
			foreach ($sliders as $sli)
				$where .= $where ? ','.$sli['id'] : $sli['id'];

			$res &= Db::getInstance()->execute('
                        DELETE FROM `'._DB_PREFIX_.'leosliderlayer_slides_lang`
                        WHERE `id_leosliderlayer_slides` IN ('.$where.')'
			);
		}
	}

	public function delete()
	{
		$res = true;

		$res &= Db::getInstance()->execute('
                    DELETE FROM `'._DB_PREFIX_.'leosliderlayer_groups`
                    WHERE `id_leosliderlayer_groups` = '.(int)$this->id
		);
		$this->deleteAllSlider((int)$this->id);
		$res &= parent::delete();
		return $res;
	}

	/**
	 * Get and validate StartWithSlide field.
	 */
	public static function showStartWithSlide($start_with_slide = 0, $slider = array())
	{
		$result = 1;
		if (is_array($slider))
		{
			$start_with_slide = (int)$start_with_slide;
			$slider_num = count($slider);
			// 1 <= $start_with_slide <= $slider_num
			if (1 <= $start_with_slide && $start_with_slide <= $slider_num)
				$result = $start_with_slide;
		}

		$result--; // index begin from 0
		return $result;
	}

	public function getDelay()
	{
		$temp_result = Tools::jsonDecode(SliderLayer::base64Decode($this->params), true);
		$result = $temp_result['delay'];

		return $result;
	}

	public static function getGroupOption()
	{
		$result = array();
		$obj = new LeoSliderGroup();
		$groups = $obj->getGroups(null, null);

		foreach ($groups as $group)
		{
			$temp = array();
			$temp['id'] = $group['id_leosliderlayer_groups'];
			$temp['name'] = $group['title'];
			$result[] = $temp;
		}
		return $result;
	}

}