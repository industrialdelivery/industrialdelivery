<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

if (!class_exists('LeomanagewidgetsHelper'))
{

	class LeomanagewidgetsHelper
	{
		const NUMBER_CACHE_FILE = 4;
		/**
		 * Check file tpl for new library : Owl_carousel
		 */
		public static function processWidgetType($hook_name, $key_widget, $type, $data)
		{
			# validate module
			unset($hook_name);
			unset($key_widget);
			if (!isset($data['carousel_type']))
				return $type; // version doesnt has owl carousel

			if ($data['carousel_type'] == LeomanagewidgetsOwlCarousel::CAROUSEL_OWL)
			{
				// widget_carousel_owl.tpl
				$type .= '_owl';
			}

			return $type;
		}

		public static function enableLoadOwlCarouselLib($data)
		{
			if (!isset($data['carousel_type']))
				return false; // version doesnt has owl carousel

			if ($data['carousel_type'] == LeomanagewidgetsOwlCarousel::CAROUSEL_OWL)
				return true;

			return false;
		}

		/**
		 * id_lang, name, active, iso_code, language_code, date_format_lite, date_format_full, is_rtl, id_shop, shops (array)
		 */
		public static function getLangAtt($attribute = 'iso_code')
		{
			$languages = array();
			foreach (Language::getLanguages(false, false, false) as $lang)
			{
				# validate module
				$languages[] = $lang[$attribute];
			}
			return $languages;
		}

		public static function getCookie()
		{
			$data = $_COOKIE;
			return $data;
		}

		/**
		 * 0 no multi_lang
		 * 1 multi_lang follow id_lang
		 * 2 multi_lnag follow code_lang
		 */
		public static function getPost($keys = array(), $multi_lang = 0 )
		{
			$post = array();
			if ($multi_lang == 0)
			{
				foreach ($keys as $key)
				{
					// get value from $_POST
					$post[$key] = Tools::getValue($key);
				}
			}
			elseif ($multi_lang == 1)
			{

				foreach ($keys as $key)
				{
					// get value multi language from $_POST
					foreach (Language::getIDs(false) as $id_lang)
						$post[$key.'_'.(int)$id_lang] = Tools::getValue($key.'_'.(int)$id_lang);
				}
			}
			elseif ($multi_lang == 2)
			{
				$languages = self::getLangAtt();
				foreach ($keys as $key)
				{
					// get value multi language from $_POST
					foreach ($languages as $id_code)
						$post[$key.'_'.$id_code] = Tools::getValue($key.'_'.$id_code);
				}
			}

			return $post;
		}

	}
}
