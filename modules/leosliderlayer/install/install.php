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
$path = dirname(_PS_ADMIN_DIR_);

include_once( $path.'/config/config.inc.php');
include_once( $path.'/init.php');
$moduleName = 'leosliderlayer';

$res = (bool)Db::getInstance()->execute('
    CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$moduleName.'_groups` (
        `id_'.$moduleName.'_groups` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,    
        `id_shop` int(10) unsigned NOT NULL,
        `hook` varchar(64) NOT NULL,
        `active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
        `params` text NOT NULL,
        PRIMARY KEY (`id_'.$moduleName.'_groups`, `id_shop`)
    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
');

/* Slides configuration */
$res &= Db::getInstance()->execute('
    CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$moduleName.'_slides` (
      `id_'.$moduleName.'_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `id_group` int(11) NOT NULL,
      `position` int(10) unsigned NOT NULL DEFAULT \'0\',
      `active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
        `parent_id` int(11) NOT NULL,
        `params` text NOT NULL,
      PRIMARY KEY (`id_'.$moduleName.'_slides`)
    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
');

/* Slides lang configuration */
$res &= Db::getInstance()->execute('
    CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$moduleName.'_slides_lang` (
      `id_'.$moduleName.'_slides` int(10) unsigned NOT NULL,
      `id_lang` int(10) unsigned NOT NULL,
      `title` varchar(255) NOT NULL,
      `link` varchar(255) NOT NULL,
      `image` varchar(255) NOT NULL,
      `thumbnail` varchar(255) NOT NULL,
      `video` text,
      `layersparams` text,
      PRIMARY KEY (`id_'.$moduleName.'_slides`,`id_lang`)
    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
');

/* install sample data */
$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT `id_'.$moduleName.'_groups` FROM `'._DB_PREFIX_.'_groups` WHERE ');

if (count($rows) <= 0 && file_exists(_PS_MODULE_DIR_.'leoblog/install/sample.php'))
	include_once( _PS_MODULE_DIR_.'leoblog/install/sample.php' );
/* END REQUIRED */

