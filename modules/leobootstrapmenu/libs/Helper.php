<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class LeoBtmegamenuHelper
{

	public static function getCategories()
	{
		$children = self::getIndexedCategories();
		$list = array();
		self::treeCategory(1, $list, $children);
		return $list;
	}

	public static function treeCategory($id, &$list, $children, $tree = "")
	{
		if (isset($children[$id]))
		{
			if ($id != 0)
			{
				$tree = $tree." - ";
			}
			foreach ($children[$id] as $v)
			{
				$v["tree"] = $tree;
				$list[] = $v;
				self::treeCategory($v["id_category"], $list, $children, $tree);
			}
		}
	}

	public static function getIndexedCategories()
	{
		global $cookie;
		$id_lang = $cookie->id_lang;
		$id_shop = Context::getContext()->shop->id;

		$join = 'JOIN `'._DB_PREFIX_.'category_shop` cs ON(c.`id_category` = cs.`id_category` AND cs.`id_shop` = '.(int)$id_shop.')';

		$allCat = Db::getInstance()->ExecuteS('
		SELECT c.*, cl.id_lang, cl.name, cl.description, cl.link_rewrite, cl.meta_title, cl.meta_keywords, cl.meta_description
		FROM `'._DB_PREFIX_.'category` c
		'.$join.'
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND `id_lang` = '.(int)$id_lang.' AND cl.`id_shop` = '.(int)$id_shop.')
		LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = c.`id_category`)
		WHERE `active` = 1 
		GROUP BY c.`id_category`
		ORDER BY `id_category` ASC');
		$children = array();
		if ($allCat)
		{
			foreach ($allCat as $v)
			{
				$pt = $v["id_parent"];
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}
			return $children;
		}
		return array();
	}

	public static function getFieldValue($obj, $key, $id_lang = NULL, $id_shop = null)
	{
		if (!$id_shop && $obj->isLangMultishop())
			$id_shop = Context::getContext()->shop->id;

		if ($id_lang)
			$defaultValue = ($obj->id && isset($obj->{$key}[$id_lang])) ? $obj->{$key}[$id_lang] : '';
		else
			$defaultValue = isset($obj->{$key}) ? $obj->{$key} : '';

		return Tools::getValue($key.($id_lang ? '_'.$id_shop.'_'.$id_lang : ''), $defaultValue);
	}
	
	public static function getPost($keys = array(), $lang = false )
	{
		$post = array();
		if($lang === false)
		{
			foreach ($keys as $key)
			{
				// get value from $_POST
				$post[$key] = Tools::getValue($key);
			}
		}
		if ($lang === true)
		{
			foreach ($keys as $key)
			{
				// get value multi language from $_POST
				foreach (Language::getIDs(false) as $id_lang)
					$post[$key.'_'.(int)$id_lang] = Tools::getValue($key.'_'.(int)$id_lang);
			}
		}
		return $post;
	}

	public static function getConfigKey($multi_lang = false)
	{
		if ($multi_lang == false)
		{
			return array(
				'saveleobootstrapmenu',
				'id_btmegamenu',
				'id_parent',
				'active',
				'show_title',
				'type',
				'product_type',
				'cms_type',
				'category_type',
				'manufacture_type',
				'supplier_type',
				'target',
				'menu_class',
				'icon_class',
				'filename',
				'is_group',
				'colums',
				'tab',
			);
		}
		else
		{
			return array(
				'title',
				'text',
				'url',
				'content_text',
			);
		}
	}
	
}