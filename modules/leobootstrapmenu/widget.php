<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

include_once('../../config/config.inc.php');
include_once('../../init.php');
require_once( _PS_MODULE_DIR_.'leobootstrapmenu/leobootstrapmenu.php' );
$context = Context::getContext();
$module = new leobootstrapmenu();
$id_shop = Tools::getValue('id_shop') ? Tools::getValue('id_shop') : 0;
echo $module->renderwidget($id_shop);
die;
