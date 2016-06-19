<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class LeobootstrapmenuWidgetModuleFrontController extends ModuleFrontController
{
	public $php_self;

	public function init()
	{
		parent::init();

		require_once( $this->module->getLocalPath().'leobootstrapmenu.php' );
	}

	public function initContent()
	{
		$this->php_self = 'widget';
		parent::initContent();

		$module = new leobootstrapmenu();

		echo $module->renderwidget();
		die;
	}

}