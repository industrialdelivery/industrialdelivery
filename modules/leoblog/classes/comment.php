<?php
/**
 *  Leo Prestashop Blockleoblogs for Prestashop 1.6.x
 *
 * @package   blockleoblogs
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class LeoBlogComment extends ObjectModel
{
	/** @var string Name */
	public $user;
	public $comment;
	public $active;
	public $id_leoblog_blog;
	public $date_add;
	public $email;
	public $id_shop;
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'leoblog_comment',
		'primary' => 'id_comment',
		'fields' => array(
			'id_leoblog_blog' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'user' => array('type' => self::TYPE_STRING, 'required' => false),
			'email' => array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'size' => 128, 'required' => true),
			'comment' => array('type' => self::TYPE_STRING, 'required' => true),
			'active' => array('type' => self::TYPE_BOOL),
			'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false)
		),
	);

	public function add($autodate = true, $null_values = false)
	{
		// $this->position = self::getLastPosition((int)$this->id_leoblogcat);
		$context = Context::getContext();
		$this->id_shop = $context->shop->id;
		return parent::add($autodate, $null_values);
	}

	public static function countComments($id_leoblog_blog = 0, $is_active = false, $id_shop = null)
	{
		if (!$id_shop)
		{
			$context = Context::getContext();
			$id_shop = $context->shop->id;
		}

		$query = ' SELECT count(id_comment) as total FROM '._DB_PREFIX_.'leoblog_comment WHERE 1=1 ';

		if ($id_leoblog_blog > 0)
		{
			# validate module
			$query .= ' AND id_leoblog_blog='.(int)$id_leoblog_blog;
		}
		if ($is_active)
		{
			# validate module
			$query .= ' AND active=1 ';
		}
		if ($id_shop)
			$query .= ' AND id_shop='.(int)$id_shop;

		$data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
		return $data[0]['total'];
	}

	public static function getComments($id_leoblog_blog = null, $limit = 10, $id_lang, $order = null, $by = null, $id_shop = null)
	{
		# validate module
		unset($id_leoblog_blog);
		unset($order);
		unset($by);
		if (!$id_shop)
		{
			$context = Context::getContext();
			$id_shop = $context->shop->id;
		}
		$query = ' SELECT c.*, b.meta_title FROM '._DB_PREFIX_.'leoblog_comment c';
		$query .= ' LEFT JOIN '._DB_PREFIX_.'leoblog_blog_lang b ON c.id_leoblog_blog=b.id_leoblog_blog AND b.id_lang='.$id_lang;
		$query .= ' WHERE 1=1 AND id_shop='.(int)$id_shop;
		$query .= ' LIMIT '.$limit;

		$data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

		return $data;
	}

	public function getList($id_leoblog_blog = null, $id_lang, $page_number = 0, $nb_products = 10, $order_by = null, $order_way = null, $id_shop = null)
	{
		if (!$id_shop)
		{
			$context = Context::getContext();
			$id_shop = $context->shop->id;
		}

		if (empty($id_lang))
			$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		if ($page_number < 1)
			$page_number = 1;

		if ($nb_products < 1)
			$nb_products = 10;
		if (empty($order_by) || $order_by == 'position')
			$order_by = 'date_add';
		if (empty($order_way))
			$order_way = 'DESC';
		if ($order_by == 'id_leoblog_blog' || $order_by == 'date_add' || $order_by == 'date_upd')
			$order_by_prefix = 'c';
		else if ($order_by == 'title')
			$order_by_prefix = 'c';
		if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			die(Tools::displayError());
		if (strpos($order_by, '.') > 0)
		{
			$order_by = explode('.', $order_by);
			$order_by_prefix = $order_by[0];
			$order_by = $order_by[1];
		}

		$query = ' SELECT c.* FROM '._DB_PREFIX_.'leoblog_comment c';
		$query .= ' WHERE 1=1 AND id_shop='.(int)$id_shop;

		$query .= ' AND active=1 AND id_leoblog_blog='.(int)$id_leoblog_blog;
		$query .= '  ORDER BY '.(isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').pSQL($order_by).' '.pSQL($order_way)
				.' LIMIT '.(($page_number - 1) * $nb_products).', '.(int)$nb_products; # validate module

		$data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

		return $data;
	}

}