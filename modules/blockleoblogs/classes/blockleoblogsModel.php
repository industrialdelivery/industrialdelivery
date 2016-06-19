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

if (!class_exists('BlockleoblogsModel'))
{

	/**
	 * Model
	 */
	class BlockleoblogsModel extends ObjectModel
	{
		public $params;
		public $params_owl_carousel;

		public function getParam()
		{
			return array();
		}

		public function setParam($config = array())
		{
			return $config;
		}

		public static function getParamOwlCarousel()
		{
			$data = Configuration::get('BLEOBLOGS_PARAM');
			$data = BlockleoblogsTools::decode($data);
			if (empty($data))
			{
				# validate
				$data = array();
			}

			return $data;
		}

		public static function setParamOwlCarousel($config = array())
		{
			return $config;
		}

	}
}