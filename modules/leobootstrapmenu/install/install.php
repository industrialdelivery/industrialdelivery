<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

if (!defined('_PS_VERSION_'))
	exit;
$path = dirname(_PS_ADMIN_DIR_);

include_once( $path.'/config/config.inc.php');
include_once( $path.'/init.php');


$res = (bool)Db::getInstance()->execute('
	CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'btmegamenu` (
	  `id_btmegamenu` int(11) NOT NULL AUTO_INCREMENT,
	  `image` varchar(255) NOT NULL,
	  `id_parent` int(11) NOT NULL,
	  `is_group` tinyint(1) NOT NULL,
	  `width` varchar(255) DEFAULT NULL,
	  `submenu_width` varchar(255) DEFAULT NULL,
	  `submenu_colum_width` varchar(255) DEFAULT NULL,
	  `item` varchar(255) DEFAULT NULL,
	  `colums` varchar(255) DEFAULT NULL,
	  `type` varchar(255) NOT NULL,
	  `is_content` tinyint(1) NOT NULL,
	  `show_title` tinyint(1) NOT NULL,
	  `level_depth` smallint(6) NOT NULL,
	  `active` tinyint(1) NOT NULL,
	  `position` int(11) NOT NULL,
	  `submenu_content` text NOT NULL,
	  `show_sub` tinyint(1) NOT NULL,
	  `target` varchar(25) DEFAULT NULL,
	  `privacy` smallint(6) DEFAULT NULL,
	  `position_type` varchar(25) DEFAULT NULL,
	  `menu_class` varchar(25) DEFAULT NULL,
	  `content` text,
	  `icon_class` varchar(255) DEFAULT NULL,
	  `level` int(11) NOT NULL,
	  `left` int(11) NOT NULL,
	  `right` int(11) NOT NULL,
	  `submenu_catids` text,
	  `is_cattree` tinyint(1) DEFAULT \'1\',
	  `date_add` datetime DEFAULT NULL,
	  `date_upd` datetime DEFAULT NULL,
	  PRIMARY KEY (`id_btmegamenu`)
	) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8;
');
$res &= (bool)Db::getInstance()->execute('
	CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'btmegamenu_lang` (
	  `id_btmegamenu` int(11) NOT NULL,
	  `id_lang` int(11) NOT NULL,
	  `title` varchar(255) DEFAULT NULL,
      `text` varchar(255) DEFAULT NULL,
		`url` varchar(255) DEFAULT NULL,
	  `description` text,
	  `content_text` text,
	  `submenu_content_text` text,
	  PRIMARY KEY (`id_btmegamenu`,`id_lang`)
	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
');

$res &= (bool)Db::getInstance()->execute('
	CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'btmegamenu_shop` (
	  `id_btmegamenu` int(11) NOT NULL DEFAULT \'0\',
	  `id_shop` int(11) NOT NULL DEFAULT \'0\',
	  PRIMARY KEY (`id_btmegamenu`,`id_shop`)
	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
');
$res &= (bool)Db::getInstance()->execute('
	INSERT INTO `'._DB_PREFIX_.'btmegamenu`(`image`,`id_parent`,`is_group`,`colums`) VALUES(\'\', 0, 1, 1)
');



$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT id_btmegamenu FROM `'._DB_PREFIX_.'btmegamenu`');

if (count($rows) <= 0)
{
	# validate module : not use this in this file
	$languages = Language::getLanguages(false);
	foreach ($languages as $lang)
	{
		$res &= (bool)Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'btmegamenu_lang`(`id_btmegamenu`,`id_lang`,`title`) VALUES(1, '.(int)$lang['id_lang'].', \'Root\')
		');
	}
	# validate module : not use this in this file
	$context = Context::getContext();
	$res &= (bool)Db::getInstance()->execute('
		INSERT INTO `'._DB_PREFIX_.'btmegamenu_shop`(`id_btmegamenu`,`id_shop`) VALUES( 1, '.(int)$context->shop->id.' )
	');
}

/* install sample data */
$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT id_btmegamenu FROM `'._DB_PREFIX_.'btmegamenu`');
if (count($rows) == 1 && file_exists(_PS_MODULE_DIR_.'leobootstrapmenu/install/sample.php'))
{
	# validate module
	include_once( _PS_MODULE_DIR_.'leobootstrapmenu/install/sample.php' );
}
/* END REQUIRED */

