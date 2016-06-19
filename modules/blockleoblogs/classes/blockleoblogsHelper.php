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

class BlockleoblogsHelper
{
	public static function getInstance()
	{
		static $instance = null;
		if (!$instance)
		{
			# validate module
			$instance = new BlockleoblogsHelper();
		}

		return $instance;
	}

	public static function getPost($keys = array(), $lang = false )
	{
		$post = array();
		if ($lang === false)
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
				'submitBlockLeoBlogs',
				'BLEOBLOGS_WIDTH',
				'BLEOBLOGS_HEIGHT',
				'BLEOBLOGS_SHOW',
				'BLEOBLOGS_STITLE',
				'BLEOBLOGS_SDES',
				'BLEOBLOGS_SIMA',
				'BLEOBLOGS_SAUT',
				'BLEOBLOGS_SCAT',
				'BLEOBLOGS_SCRE',
				'BLEOBLOGS_SCOUN',
				'BLEOBLOGS_SHITS',
				'BLEOBLOGS_NBR',
				'carousel_type',
				'BLEOBLOGS_PAGE',
				'BLEOBLOGS_COL',
				'BLEOBLOGS_INTV',
				'owl_items',
				'owl_rows',
				'owl_autoPlay',
				'owl_stopOnHover',
				'owl_autoHeight',
				'owl_responsive',
				'owl_mouseDrag',
				'owl_touchDrag',
				'owl_navigation',
				'owl_slideSpeed',
				'owl_itemsDesktop',
				'owl_itemsDesktopSmall',
				'owl_itemsTablet',
				'owl_itemsTabletSmall',
				'owl_itemsMobile',
				'owl_itemsCustom',
				'owl_lazyLoad',
				'owl_lazyEffect',
				'owl_lazyFollow',
				'owl_pagination',
				'owl_paginationNumbers',
				'owl_paginationSpeed',
				'owl_rewindNav',
				'owl_rewindSpeed',
				'owl_scrollPerPage',
				'tab',
			);
		}
		else
		{
			return array(
			);
		}
	}

}