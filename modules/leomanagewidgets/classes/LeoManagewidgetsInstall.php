<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

if (!class_exists('LeoManagewidgetsInstall'))
{

	class LeoManagewidgetsInstall
	{
		/**
		 * Creates tables
		 */
		public static function createTables()
		{
			// if ($this->_leotype == 1){
			//     if ($this->_installDataSample()) return true;
			// }
			$res = Db::getInstance()->execute('
	            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'leomanagewidget_group` (
	                `id_leomanagewidget_group` int(10) unsigned NOT NULL AUTO_INCREMENT,
	                `id_shop` int(10) unsigned NOT NULL,
	                                `position` int(10) unsigned NOT NULL DEFAULT \'0\',
	                                `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
	                                `hook_name` varchar(64) NOT NULL,
	                                `type` int(1) unsigned,
	                                `params` text,
	                PRIMARY KEY (`id_leomanagewidget_group`, `id_shop`)
	            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
	        ');

			/* widget configuration */
			$res = Db::getInstance()->execute('
	            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'leomanagewidget_column` (
	              `id_column` int(10) unsigned NOT NULL AUTO_INCREMENT,
	              `id_group` int(10) unsigned NOT NULL,
	              `id_shop` int(10) unsigned NOT NULL,
	              `position` int(10) unsigned NOT NULL DEFAULT \'0\',
	              `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
	              `params` text,
	              PRIMARY KEY (`id_column`)
	            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
	        ');

			$res = Db::getInstance()->execute('
	            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'leomanagewidget_content` (
	               `id_content` int(10) unsigned NOT NULL AUTO_INCREMENT,
	               `id_column` int(10) unsigned NOT NULL,
	               `id_shop` int(10) unsigned NOT NULL,
	               `position` int(10) unsigned NOT NULL DEFAULT \'0\',
	               `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
	               `key_widget` int(10) unsigned NOT NULL,
	               `module_name` varchar(64) NOT NULL,
	               `hook_name` varchar(64) NOT NULL, 
	               `type` int(1) unsigned,
	               `params` text,
	               PRIMARY KEY (`id_content`)
	            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
	            
	        ');
			$res &= Db::getInstance()->execute('
	            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'leowidgets`(
	              `id_leowidgets` int(11) NOT NULL AUTO_INCREMENT,
	              `name` varchar(250) NOT NULL,
	              `type` varchar(250) NOT NULL,
	              `params` MEDIUMTEXT,
	          `id_shop` int(11) unsigned NOT NULL,
	          `key_widget` int(11)  NOT NULL,
	           PRIMARY KEY (`id_leowidgets`,`id_shop`)
	            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
	        ');

			return $res;
		}

		public static function checkVersion($version)
		{
			$versions = array('3.0');
			$res = true;
			if ($version && $version == $versions[count($versions) - 1])
				return;
			foreach ($versions as $ver)
			{
				if (!$version || ($version && $version < $ver))
				{
					$res = LeoManagewidgetsInstall::createTables();
					if ($res && LeoManagewidgetsInstall::checkTable('leomanagewidget'))
					{
						$res = LeoManagewidgetsInstall::getOldData();
						if ($res)
							$res = Db::getInstance()->execute('RENAME TABLE `'._DB_PREFIX_.'leomanagewidget` TO `'._DB_PREFIX_.'leomanagewidget_backup`');
						if ($res)
							Configuration::updateValue('LEO_MANAGERWIDGETS_VERSION', pSQL($ver));
					}
				}
			}
		}

		public static function checkTable($table_name)
		{
			$checktable = Db::getInstance()->executeS("
				SELECT TABLE_NAME FROM information_schema. TABLES
				WHERE TABLE_NAME = '"._DB_PREFIX_.pSQL($table_name)."'
				AND TABLE_SCHEMA = '"._DB_NAME_."'
			");

			if (count($checktable) < 1)
				return false;
			else
				return true;
		}

		public static function getOldData()
		{
			$res = 1;
			$columns = LeoManageWidgetColumn::getAllOldColumn();
			//echo "<pre>";print_r($columns);die;

			if ($columns)
				foreach ($columns as $column)
				{
					$newColumn = new LeoManageWidgetColumn();
					//echo "<pre>";print_r($columnID);die;
					$newColumn->id_group = $column['id_group'];
					$newColumn->id_shop = $column['id_shop'];
					$newColumn->position = $column['position'];
					$newColumn->active = $column['active'];
					$newColumn->params = $column['params'];
					if ($newColumn->add())
					{
						$row = new LeoManagerWidgetContent();
						$row->id_column = $newColumn->id;
						$row->id_shop = $newColumn->id_shop;
						$row->position = 1;
						$row->active = $newColumn->active;
						$row->key_widget = $column['key_widget'];
						if ($column['key_widget'] != 0)
						{
							$row->type = '0';
							if (!$row->add())
								$res = 0;
						}
						else
						{
							if ($newColumn->params)
							{
								$myParam = Tools::jsonDecode(call_user_func('base64'.'_decode', $newColumn->params), true);
								if ($myParam)
									foreach ($myParam as $key => $value)
									{
										if ($key == 'module' && $value)
											$row->module_name = $value;
										if ($key == 'hook' && $value)
											$row->hook_name = $value;
									}
							}
							if (isset($row->module_name) && isset($row->hook_name))
							{
								$row->type = '1';
								if (!$row->add())
									$res = 0;
								
							}
						}
					}
					else
						$res = 0;
				}
			return $res;
		}

		public static function installModuleTab($module_name, $title, $class_sfx = '', $parent = '')
		{
			$class = 'Admin'.Tools::ucfirst($module_name).Tools::ucfirst($class_sfx);
			@Tools::copy(_PS_MODULE_DIR_.$module_name.'/logo.gif', _PS_IMG_DIR_.'t/'.$class.'.gif');
			if ($parent == '')
				$position = Tab::getCurrentTabId();
			else
				$position = Tab::getIdFromClassName($parent);
			$tab1 = new Tab();
			$tab1->class_name = $class;
			$tab1->module = $module_name;
			$tab1->id_parent = call_user_func('int'.'val', $position);
			$langs = Language::getLanguages(false);
			foreach ($langs as $l)
				$tab1->name[$l['id_lang']] = $title;
			if ($parent == -1)
			{
				$tab1->id_parent = -1;
				$id_tab1 = $tab1->add();
			}
			else
				$id_tab1 = $tab1->add(true, false);
			unset($id_tab1);
		}

		public static function uninstallModuleTab($module_name, $class_sfx = '')
		{
			$tabClass = 'Admin'.Tools::ucfirst($module_name).Tools::ucfirst($class_sfx);

			$idTab = Tab::getIdFromClassName($tabClass);
			if ($idTab != 0)
			{
				$tab = new Tab($idTab);
				$tab->delete();
				return true;
			}
			return false;
		}

	}
}
?>