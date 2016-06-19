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

class LeoBlogConfig
{
	public $params;
	public $cat_image_dir = '';

	/** @var int id_lang current language in for, while */
	public $cur_id_lang = '';

	/** @var int id_lang current language in for, while */
	public $cur_prefix_rewrite = '';

	public static function getInstance()
	{
		static $instance;
		if (!$instance)
		{
			# validate module
			$instance = new LeoBlogConfig();
		}
		return $instance;
	}

	public function __construct()
	{
		$data = self::getConfigValue('cfg_global');

		if ($data && $tmp = unserialize($data))
		{
			# validate module
			$this->params = $tmp;
		}
	}

	public function mergeParams($params)
	{
		# validate module
		unset($params);
	}

	public function setVar($key, $value)
	{
		$this->params[$key] = $value;
	}

	public function get($name, $value = '')
	{
		if (isset($this->params[$name]))
		{
			# validate module
			return $this->params[$name];
		}
		return $value;
	}

	public static function getConfigName($name)
	{
		return Tools::strtoupper(_LEO_BLOG_PREFIX_.$name);
	}

	public static function updateConfigValue($name, $value = '')
	{
		Configuration::updateValue(self::getConfigName($name), $value, true);
	}

	public static function getConfigValue($name)
	{
		return Configuration::get(self::getConfigName($name));
	}

}