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

if (!class_exists('BlockleoblogsTools'))
{

	class BlockleoblogsTools
	{

		public static function base64Decode($data)
		{
			return call_user_func('base64_decode', $data);
		}

		public static function base64Encode($data)
		{
			return call_user_func('base64_encode', $data);
		}

		public static function encode($data = array())
		{
			return call_user_func('base64_encode', Tools::jsonEncode($data));
		}

		public static function decode($data = array())
		{
			return Tools::jsonDecode(call_user_func('base64_decode', $data), true);
		}

	}
}