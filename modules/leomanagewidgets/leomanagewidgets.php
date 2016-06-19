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
if (file_exists(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeoManagewidgetsInstall.php'))
	require_once(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeoManagewidgetsInstall.php');
if (file_exists(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeoManageWidgetColumn.php'))
	require_once(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeoManageWidgetColumn.php');
if (file_exists(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeoManageWidgetGroup.php'))
	require_once(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeoManageWidgetGroup.php');
if (file_exists(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeoManagerWidgetContent.php'))
	require_once(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeoManagerWidgetContent.php');

require_once(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeomanagewidgetsHelper.php');
require_once(_PS_MODULE_DIR_.'leomanagewidgets/classes/LeomanagewidgetsOwlCarousel.php');

class LeoManagewidgets extends Module
{
	private $_html = '';
	private $_postErrors = array();
	private $_hooksPos = array();
	private $_hooksException = array();
	private $_widgets = array();
	private $_groupField = array();
	private $_columnField = array();
	private $_groupList = array();
	private $_columnList = array();
	private $_rowField = array();
	private $_widgetObj;
	private $_themeName;
	private $_hookAssign = '';
	private $_leotype = '';
	private $_enable_config_animate_style = 1;
	private $_has_animate_style = 0;
	private $_animate_style_config_data = array();
	private $_groups_show_config = array();
	private $_has_bg_style = 0;
	private $_has_bg_style_parallax = 0;
	private $_has_bg_style_mouseparallax = 0;
	private $_has_bg_style_vimeo = 0;
	private $_has_bg_style_youtube = 0;
	private $_bg_style_fullwidth = 0;
	private $_bg_style_config_data = array();
	private $_load_owl_carousel_lib = false;	 # only load library carousel when have widget use owl carousel
	private $cache_param = array();

	public function __construct()
	{
		if (file_exists(_PS_MODULE_DIR_.'leotempcp'))
		{
			$this->_leotype = 1;
			if (file_exists(_PS_MODULE_DIR_.'leotempcp/classes/widgetbase.php'))
				require_once(_PS_MODULE_DIR_.'leotempcp/classes/widgetbase.php');
			if (file_exists(_PS_MODULE_DIR_.'leotempcp/classes/widget.php'))
				require_once(_PS_MODULE_DIR_.'leotempcp/classes/widget.php');
		} 
		else
		{
			$this->_leotype = 0;
			if (file_exists(_PS_MODULE_DIR_.'leomanagewidgets/classes/widgetbase.php'))
				require_once(_PS_MODULE_DIR_.'leomanagewidgets/classes/widgetbase.php');
			if (file_exists(_PS_MODULE_DIR_.'leomanagewidgets/classes/widget.php'))
				require_once(_PS_MODULE_DIR_.'leomanagewidgets/classes/widget.php');
		}
		if ($this->_leotype == 1)
		{
			$this->version = '4.0.0';
			$this->_hooksPos = array(
				'displayBanner',
				'displayNav',
				'displayTop',
				'displaySlideshow',
				'topNavigation',
				'displayTopColumn',
				'displayLeftColumn',
				'displayHome',
				'displayContentBottom',
				'displayRightColumn',
				'displayBottom',
				'displayFooterTop',
				'displayfooter',
				'displayFooterBottom',
				'displayFootNav',
				'productTabContent',
				'displayFooterProduct',
				'displayRightColumnProduct');
			$this->_hookAssign = array('rightcolumn', 'leftcolumn', 'topcolumn', 'home', 'top', 'footer', 'nav');
		}
		else
		{
			$this->version = '4.0.0';
			$this->_hooksPos = array(
				'displayTop',
				'displayNav',
				'displayTopColumn',
				'displayLeftColumn',
				'displayHome',
				'displayHomeTab',
				'displayHomeTabContent',
				'displayRightColumn',
				'displayfooter',
				'displayRightColumnProduct',
				'displayLeftColumnProduct',
				'productTab'
			);
			$this->_hookAssign = array('rightcolumn', 'leftcolumn', 'topcolumn', 'home', 'top', 'footer');
		}
		$this->name = 'leomanagewidgets';
		$this->module_key = '233c96e3ba02bcb27be92a2509f19198';
		$this->tab = 'front_office_features';
		$this->author = 'LeoTheme';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);
		$this->bootstrap = true;
		$this->_themeName = Context::getContext()->shop->getTheme();
		parent::__construct();

		if ($this->_leotype == 1 && Module::isInstalled($this->name))
		{
			$this_version = Configuration::get('LEO_MANAGERWIDGETS_VERSION') ? Configuration::get('LEO_MANAGERWIDGETS_VERSION') : '';
			LeoManagewidgetsInstall::checkVersion($this_version);
		}
		//hook name is lower
		//$this->_hooksException = array('displayRightColumn'=>array(""));
		$langList = Language::getLanguages(false);
		$gParam = array('class', 'active', 'hook_name', 'animate_offset', 'delay_animate', 'skin_animate', 'background_style', 'background_style_color',
			'background_style_image_url', 'background_style_position', 'background_style_repeat', 'background_style_parallax_speed',
			'background_style_parallax_offsetx', 'background_style_parallax_offsety', 'background_style_mouseparallax_strength',
			'background_style_mouseparallax_axis', 'background_style_mouseparallax_offsetx', 'background_style_mouseparallax_offsety',
			'background_video_source', 'background_video_vid', 'background_video_mp4', 'background_video_webm', 'background_video_ogg',
			'background_style_fullwidth');

		foreach ($langList as $lang)
			$gParam[] = 'title_'.$lang['id_lang'];

		$this->_groupField = array('id', 'active', 'hook_name', 'position', 'params' => $gParam);
		$this->_columnField = array('id', 'active', 'id_group', 'position', 'params' => array('class', 'lg', 'md', 'sm', 'xs', 'sp', 'background', 'pages', 'specific', 'controllerids', 'skinanimate', 'animateoffset', 'delayanimate'));
		$this->_rowField = array('id', 'id_column', 'position', 'active', 'key_widget', 'module_name', 'hook_name', 'type', 'params' => array());

		if (Tools::strtolower(Tools::getValue('controller')) == 'adminmodules' && Tools::getValue('configure') == $this->name)
		{
			$leoWidget = new LeoTempcpWidget();
			$this->_widgets = $leoWidget->getWidgets();
		}

		$this->displayName = $this->l('Leo Manage Widget');
		$this->description = $this->l('Leo Manage Widget support Leo FrameWork Verion 4.0.0');
	}

	/**
	 * @see Module::install()
	 */
	public function install()
	{
		if ($this->_leotype == 0)
		{
			LeoManagewidgetsInstall::installModuleTab($this->name, 'Leo Manage widgets', 'widgets', 'AdminParentModules');
			LeoManagewidgetsInstall::installModuleTab($this->name, 'Leo Manage Widget Images', 'images', -1);
		}
		/* Adds Module */
		$res = true;
		if (parent::install())
		{
			$res &= $this->registerHook('header');
			$res &= $this->registerHook('actionShopDataDuplication');
			$res &= $this->registerHook('actionAdminPerformanceControllerAfter');	// clear cache
			foreach ($this->_hooksPos as $value)
				$res &= $this->registerHook($value);
		}
		/* Creates tables */
		if ($this->_leotype == 1)
		{
			Configuration::updateValue('LEO_MANAGERWIDGETS_VERSION', $this->version);
			require_once(_PS_MODULE_DIR_.'leotempcp/libs/DataSample.php');

			$sample = new Datasample(1);
			return $sample->processImport($this->name);
		}
		else
			$res &= LeoManagewidgetsInstall::createTables();

		return (bool)$res;
	}

	public function uninstall()
	{
		if ($this->_leotype == 1)
		{
			if (parent::uninstall())
			{
				/* Deletes tables */
				Configuration::deleteByName('LEO_MANAGERWIDGETS_VERSION');
				$res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'leomanagewidget_backup`,`'._DB_PREFIX_.'leomanagewidget_column`,`'._DB_PREFIX_.'leomanagewidget_content`,`'._DB_PREFIX_.'leomanagewidget_group`;');
				return $res;
			}
		}
		else
		{
			if (parent::uninstall())
			{
				$res = LeoManagewidgetsInstall::uninstallModuleTab($this->name, 'widgets');
				$res = LeoManagewidgetsInstall::uninstallModuleTab($this->name, 'images');
				$res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'leomanagewidget_group`,`'._DB_PREFIX_.'leomanagewidget_column`,`'._DB_PREFIX_.'leomanagewidget_content`,`'._DB_PREFIX_.'leowidgets`;');
				return $res;
			}
		}
	}

	/**
	 * Uninstall
	 */
	public function getContent()
	{
		if ($this->_leotype == 1)
		{
			if (!file_exists(_PS_MODULE_DIR_.'leotempcp/classes/widgetbase.php'))
				return $this->l('Please install leotemcp module');

			if (!file_exists(_PS_MODULE_DIR_.'leotempcp/classes/widgetbase.php') || !file_exists(_PS_MODULE_DIR_.'leotempcp/classes/widget.php'))
				return '<div class="alert alert-danger">'.$this->l('Please install module leotemcp').'</div>';
		}

		if (Tools::isSubmit('correctData'))
			$this->correctData();
		$this->headerHTML();

		if (Tools::isSubmit('submitMWidget'))
			$this->_postProcess();

		return $this->renderForm();
	}

	public function correctData()
	{
		$groups = LeoManageWidgetGroup::getAllGroupId(-1);
		foreach ($groups as $group_id)
		{
			$groupObj = new LeoManageWidgetGroup($group_id);
			if (Validate::isLoadedObject($groupObj))
			{
				$tmp = Tools::unSerialize($groupObj->params);
				if ($tmp)
				{
					$groupObj->params = call_user_func('base64'.'_encode', Tools::jsonEncode($tmp));
					$groupObj->save();
				}
			}
		}
		$columns = LeoManageWidgetColumn::getAllColumnId(-1);
		foreach ($columns as $column_id)
		{
			$columnObj = new LeoManageWidgetColumn($column_id);
			if (Validate::isLoadedObject($columnObj))
			{
				$tmp = Tools::unSerialize($columnObj->params);
				if ($tmp)
				{
					$columnObj->params = call_user_func('base64'.'_encode', Tools::jsonEncode($tmp));
					$columnObj->save();
				}
			}
		}
		$this->_html = $this->displayConfirmation($this->l('Correct data done.'));
	}

	private function _postProcess()
	{
		Configuration::updateValue('LEO_ANIMATELOAD', Tools::getValue('LEO_ANIMATELOAD'));
		$listGroupId = LeoManageWidgetGroup::getAllGroupId();
		$listColumnId = LeoManageWidgetColumn::getAllColumnId();
		$listRowId = LeoManagerWidgetContent::getAllRowId();
		$res = 1;
		$data_form = Tools::getValue('data_form');
		$data_form = Tools::jsonDecode($data_form, true);
		$context = Context::getContext();
		$id_shop = $context->shop->id;
		if ($data_form['deletedObj'])
		{
			//delete row
			if (isset($data_form['deletedObj']['deletedRow']) && $data_form['deletedObj']['deletedRow'])
			{
				$rowList = explode(',', $data_form['deletedObj']['deletedRow']);
				//remove empty element
				$rowList = array_filter($rowList);
				foreach ($rowList as $value)
				{
					if ($value && ($key = array_search($value, $listRowId)) !== false)
					{
						$rowModel = new LeoManagerWidgetContent();
						$rowModel->id = $value;
						if ($rowModel->delete())
							unset($listRowId[$key]);
					}
				}
			}
			//delete column
			if (isset($data_form['deletedObj']['deletedColumn']) && $data_form['deletedObj']['deletedColumn'])
			{
				$columnList = explode(',', $data_form['deletedObj']['deletedColumn']);
				//remove empty element
				$columnList = array_filter($columnList);
				foreach ($columnList as $value)
				{
					if ($value && ($key = array_search($value, $listColumnId)) !== false)
					{
						$columnModel = new LeoManageWidgetColumn();
						$columnModel->id = $value;
						if ($columnModel->delete())
							unset($listColumnId[$key]);
					}
				}
			}
			//delete group
			if (isset($data_form['deletedObj']['deletedGroup']) && $data_form['deletedObj']['deletedGroup'])
			{
				$groupList = explode(',', $data_form['deletedObj']['deletedGroup']);
				$groupList = array_filter($groupList);
				foreach ($groupList as $value)
				{
					if ($value && ($key = array_search($value, $listGroupId)) !== false)
					{
						$groupModel = new LeoManageWidgetGroup();
						$groupModel->id = $value;
						if ($groupModel->delete())
							unset($listGroupId[$key]);
					}
				}
			}
		}
		$positionGroupByHook = array();
		$positionColumnByGroup = array();
		$positionRowByColumn = array();

		if ($data_form['groups'])
		{
			foreach ($data_form['groups'] as $group)
			{

				if (!isset($group['params']) || !$group['params'])
					continue;
				//get all group value
				$params = $group['params'];

				$groupModel = new LeoManageWidgetGroup();
				//asign group value to model object
				foreach ($this->_groupField as $gKey => $gField)
				{
					if (is_array($gField))
					{
						$tmpObj = array();
						foreach ($gField as $gF)
						{
							if (isset($params[$gF]))
								$tmpObj[$gF] = $params[$gF];
						}
						$groupModel->{$gKey} = call_user_func('base64'.'_encode', Tools::jsonEncode($tmpObj));
					}
					else
						$groupModel->{$gField} = $params[$gField];
				}

				//assign postion number for group in each hook
				if (!isset($positionGroupByHook[$groupModel->hook_name]))
				{
					$groupModel->position = 1;
					$positionGroupByHook[$groupModel->hook_name] = 1;
				}
				else
				{
					$positionGroupByHook[$groupModel->hook_name] = (int)$positionGroupByHook[$groupModel->hook_name] + 1;
					$groupModel->position = $positionGroupByHook[$groupModel->hook_name];
				}
				$groupModel->id_shop = $id_shop;
				$groupModel->hook_name = Tools::strtolower($groupModel->hook_name);

				//add new group

				if ($groupModel->id == 0 || !in_array($groupModel->id, $listGroupId))
				{
					if (!$groupModel->add())
					{
						$res = 0;
						$this->_html .= $this->displayError('Could add new Group in hook %s.', $groupModel->hook_name);
					}
				}
				else
				{
					if (!$groupModel->update())
					{
						$res = 0;
						$this->_html .= $this->displayError('Could update Group in hook %s.', $groupModel->hook_name);
					}
				}
				if (isset($group['columns']) && $group['columns'])
					foreach ($group['columns'] as $column)
					{
						$columnModel = new LeoManageWidgetColumn();
						//asign group value to model object
						foreach ($this->_columnField as $cKey => $cField)
						{
							if (is_array($cField))
							{
								$tmpObj = array();
								foreach ($cField as $cF)
								{
									if (isset($column[$cF]))
										$tmpObj[$cF] = $column[$cF];
								}
								$columnModel->{$cKey} = call_user_func('base64'.'_encode', Tools::jsonEncode($tmpObj));
							}
							else
								$columnModel->{$cField} = $column[$cField];
						}
						//assign grop ID
						$columnModel->id_group = $groupModel->id;

						//assign postion number for column in each group
						if (!isset($positionColumnByGroup[$columnModel->id_group]))
						{
							$columnModel->position = 1;
							$positionColumnByGroup[$columnModel->id_group] = 1;
						}
						else
						{
							$positionColumnByGroup[$columnModel->id_group] = (int)$positionColumnByGroup[$columnModel->id_group] + 1;
							$columnModel->position = $positionColumnByGroup[$columnModel->id_group];
						}

						$columnModel->id_shop = $id_shop;

						if ($columnModel->id == 0 || !in_array($columnModel->id, $listColumnId))
						{

							if (!$columnModel->add())
							{
								$res = 0;

								$this->_html .= $this->displayError('Add process is error');
							}
						}
						else
						{
							if (!$columnModel->update())
							{
								$res = 0;
								$this->_html .= $this->displayError('Update process is error');
							}
						}//close else
						//rows of the column
						if (isset($column['rows']) && $column['rows'])
						{
							foreach ($column['rows'] as $row)
							{
								$rowModel = new LeoManagerWidgetContent();
								//asign row value to model object
								foreach ($this->_rowField as $cKey => $cField)
								{
									if (is_array($cField))
									{
										$tmpObj = array();
										foreach ($cField as $cF)
										{
											if (isset($row[$cF]))
												$tmpObj[$cF] = $row[$cF];
										}
										$rowModel->{$cKey} = call_user_func('base64'.'_encode', Tools::jsonEncode($tmpObj));
									}
									else
										$rowModel->{$cField} = $row[$cField];
								}
								//assign grop ID
								$rowModel->id_column = $columnModel->id;
								$rowModel->id_shop = $id_shop;
								//assign postion number for row in each column
								if (!isset($positionRowByColumn[$rowModel->id_column]))
								{
									$rowModel->position = 1;
									$positionRowByColumn[$rowModel->id_column] = 1;
								}
								else
								{
									$positionRowByColumn[$rowModel->id_column] = (int)$positionRowByColumn[$rowModel->id_column] + 1;
									$rowModel->position = $positionRowByColumn[$rowModel->id_column];
								}

								if ($rowModel->id == 0 || !in_array($rowModel->id, $listRowId))
								{
									if (!$rowModel->add())
									{
										$res = 0;
										$this->_html .= $this->displayError('Add process is error');
									}
								}
								else
								{
									if (!$rowModel->update())
									{
										$res = 0;
										$this->_html .= $this->displayError('Update process is error');
									}
								}
								if (isset($row['deleteModule']) && $row['deleteModule'] == '1')
									$this->deleteModuleFromHook($row['hook_name'], $row['module_name']);
							}//close a row
						}//close  rows
					}//close a column
			}//close a group
		}//close group

		$this->clearHookCache();
		Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
		//$this->_html .= ($res ? $this->displayConfirmation($this->l('Configuration updated')) : $this->displayError($this->l('The configuration could not be updated.')));
		# validate module
		unset($res);
	}

	public function makeFieldsOptions()
	{
		$soption = array(
			array(
				'id' => 'active_on',
				'value' => 1,
				'label' => $this->l('Enabled')
			),
			array(
				'id' => 'active_off',
				'value' => 0,
				'label' => $this->l('Disabled')
			)
		);

		$fields_form = array();
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Manage Widget control Page'),
				'icon' => 'icon-cogs'
			),
			'description' => $this->l('You can create new column from Avail Widget.'),
			'submit' => array(
				'title' => $this->l('Save'),
			),
			'input' => array(
				array(
					'type' => 'switch',
					'label' => $this->l('Enable Animate Load'),
					'name' => 'LEO_ANIMATELOAD',
					'default' => 0,
					'values' => $soption,
					'desc' => $this->l('Where there to display Animate Load appearing on left of site.'),
				),
				array(
					'type' => 'hook_list',
					'name' => 'hook_list',
				),
				array(
					'type' => 'setting_form',
					'name' => 'setting_form',
				),
			),
			'buttons' => array(
				array(
					'id' => 'closeoropen',
					'class' => 'closeoropen',
					'title' => $this->l('Close all Forms'),
					'icon' => 'process-icon-minus',
				),
				array(
					'id' => 'openorclose',
					'class' => 'closeoropen',
					'title' => $this->l('Expand all Forms'),
					'icon' => 'process-icon-plus',
				),
				array(
					'id' => 'correctdata',
					'title' => $this->l('Correct Data'),
					'icon' => 'process-icon-edit',
					'name' => 'correctData',
					'type' => 'submit',
				),
				array(
					'title' => $this->l('Manage Widget'),
					'icon' => 'process-icon-cogs',
					'class' => 'button btn btn-addnewwidget'
				)
			),
			'submit' => array(
				'id' => 'leobtnsave',
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right leobtnsave'
			),
		);
		$i = 1;
		foreach ($this->_hooksPos as $hook)
		{
			$hook = Tools::strtolower($hook);
			$fields_form[$i]['form'] = array(
				'input' => array(
					array(
						'type' => 'hook_data',
						'name' => $hook,
						'lang' => true,
					),
				),
				'buttons' => array(
					array(
						'title' => $this->l('Manage Widget'),
						'icon' => 'process-icon-cogs',
						'class' => 'button btn btn-addnewwidget'
					)
				),
				'submit' => array(
					'id' => 'leobtnsave_'.$hook,
					'title' => $this->l('Save'),
					'class' => 'btn btn-default pull-right leobtnsave'
				),
			);
			$i++;
		}

		return $fields_form;
	}

	public function headerHTML()
	{
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;

		$this->context->controller->addJqueryUI('ui.sortable');
		$this->context->controller->addCSS($this->_path.'assets/admin/style.css');
		$this->context->controller->addJS($this->_path.'assets/admin/script.js');
		$this->context->controller->addJS($this->_path.'assets/admin/bootbox.js');
		$this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/jquery.colorpicker.js');
		//$this->context->controller->addJqueryUI('ui.resizable');
	}

	public function formatWidget()
	{
		$widgets = array();
		$widgetsByID = array();
		foreach ($this->_widgets as $key => $value)
		{
			# validate module
			unset($key);
			$widgets[$value['type']][] = array('id' => $value['key_widget'], 'name' => $value['name']);
			$widgetsByID[$value['name']] = $value;
		}
		return $widgets;
	}

	public function parseColumnByGroup($dataColumn, $isfont = 0)
	{
		$result = array();
		$currentPage = Dispatcher::getInstance()->getController();
		foreach ($dataColumn as $row)
		{
			$row['id'] = $row['id_column'];
			unset($row['id_column']);
			if ($row['params'])
			{
				$myParam = Tools::jsonDecode(call_user_func('base64'.'_decode', $row['params']), true);
				if ($isfont)
				{
					if (isset($myParam['skinanimate']) && $myParam['skinanimate'])
					{
						$classAnimation = 'animation_col_'.$row['id'];
						$myParam['class'] .= ' '.$classAnimation;
						$this->_animate_style_config_data[$classAnimation] = array(
							'skin' => isset($myParam['skinanimate']) ? $myParam['skinanimate'] : '',
							'offset' => (isset($myParam['animateoffset']) && !empty($myParam['animateoffset'])) ? $myParam['animateoffset'] : 75,
							'delay' => (isset($myParam['delayanimate']) && !empty($myParam['delayanimate'])) ? $myParam['delayanimate'] : 0.3
						);
						unset($this->_groups_show_config[$row['id_group']]);
						$this->_has_animate_style = 1;
					}
					else
						$this->_groups_show_config[] = $row['id_group'];
				}
				if ($myParam)
				{
					foreach ($myParam as $key => $value)
						$row[$key] = $value;
				}
				//set class for column
				$tmpArray = array('lg', 'md', 'sm', 'xs', 'sp');
				$row['col_value'] = '';
				foreach ($tmpArray as $col)
					if (isset($row[$col]) && $row[$col])
					{
						$valCol = $row[$col];
						if (strpos($valCol, '.') !== false)
							$valCol = str_replace('.', '-', $valCol);
						$row['col_value'] .= ' col-'.$col.'-'.$valCol;
					}
			}
			unset($row['params']);
			//call from font-office
			if ($isfont)
			{
				//check column with specific
				if (isset($row['specific']) && $row['specific'] != 'all')
				{
					//dont get column when controller diffirent
					if ($currentPage != $row['specific'])
						continue;
					//dont get column when don have id
					if (!$this->allowShowInController($row['specific'], $row['controllerids']))
						continue;
				}
				//check column with page
				if (isset($row['pages']) && $row['pages'])
				{
					$row['pages'] = str_replace(', ', ',', $row['pages']);
					$pages = explode(',', $row['pages']);
					if (in_array($currentPage, $pages) || (in_array('module', $pages) && Tools::getIsset('fc')))
						continue;
				}
				$result[$row['id_group']][] = $row;
			}
			else
				$result[$row['id_group']][] = $row;
		}
		return $result;
	}

	public function parseGroupByHook($dataGroup, $isFont = 0)
	{
		$result = array();
		$titleLang = 'title_'.$this->context->language->id;
		foreach ($dataGroup as $row)
		{
			$row['id'] = $row['id_leomanagewidget_group'];
			unset($row['id_leomanagewidget_group']);
			if ($row['params'])
			{
				$myParam = Tools::jsonDecode(call_user_func('base64'.'_decode', $row['params']), true);

				if ($isFont)
				{
					if (isset($myParam['skin_animate']) && $myParam['skin_animate'])
					{
						$classAnimation = 'animation_group_'.$row['id'];
						$myParam['class'] .= ' '.$classAnimation;
						$this->_animate_style_config_data[$classAnimation] = array(
							'skin' => isset($myParam['skin_animate']) ? $myParam['skin_animate'] : '',
							'offset' => (isset($myParam['animate_offset']) && !empty($myParam['animate_offset'])) ? $myParam['animate_offset'] : 75,
							'delay' => (isset($myParam['delay_animate']) && !empty($myParam['delay_animate'])) ? $myParam['delay_animate'] : 0.3
						);
						$this->_has_animate_style = 1;
					}

					if (isset($myParam['background_style']) && $myParam['background_style'])
					{
						$classBgStyle = 'background_style_'.$row['id'];
						$myParam['class'] .= ' bg_style_row '.$classBgStyle;

						if (isset($myParam['background_style_fullwidth']) && $myParam['background_style_fullwidth'])
						{
							$myParam['class'] .= ' full-bg-screen';
							$this->_bg_style_fullwidth = 1;
						}

						$bgStyle = (isset($myParam['background_style']) && !empty($myParam['background_style'])) ? $myParam['background_style'] : '';
						$bgStyleFullwidth = (isset($myParam['background_style_fullwidth']) && !empty($myParam['background_style_fullwidth'])) ? $myParam['background_style_fullwidth'] : '0';
						$bgStyleColor = (isset($myParam['background_style_color']) && !empty($myParam['background_style_color'])) ? $myParam['background_style_color'] : '';
						$bgStyleImageUrl = (isset($myParam['background_style_image_url']) && !empty($myParam['background_style_image_url'])) ? $myParam['background_style_image_url'] : '';
						$bgStylePosition = (isset($myParam['background_style_position']) && !empty($myParam['background_style_position'])) ? $myParam['background_style_position'] : '';
						$bgStyleRepeat = (isset($myParam['background_style_repeat']) && !empty($myParam['background_style_repeat'])) ? $myParam['background_style_repeat'] : '';
						$bgstyleParallaxSpeed = (isset($myParam['background_style_parallax_speed']) && !empty($myParam['background_style_parallax_speed'])) ? $myParam['background_style_parallax_speed'] : '0.5';
						$bgstyleParallaxOffsetx = (isset($myParam['background_style_parallax_offsetx']) && !empty($myParam['background_style_parallax_offsetx'])) ? $myParam['background_style_parallax_offsetx'] : '0';
						$bgstyleParallaxOffsety = (isset($myParam['background_style_parallax_offsety']) && !empty($myParam['background_style_parallax_offsety'])) ? $myParam['background_style_parallax_offsety'] : '0';
						$bgstyleMouseParallaxStrength = (isset($myParam['background_style_mouseparallax_strength']) && !empty($myParam['background_style_mouseparallax_strength'])) ? $myParam['background_style_mouseparallax_strength'] : '0.5';
						$bgstyleMouseParallaxAxis = (isset($myParam['background_style_mouseparallax_axis']) && !empty($myParam['background_style_mouseparallax_axis'])) ? $myParam['background_style_mouseparallax_axis'] : 'both';
						$bgstyleMouseParallaxOffsetX = (isset($myParam['background_style_mouseparallax_offsetx']) && !empty($myParam['background_style_mouseparallax_offsetx'])) ? $myParam['background_style_mouseparallax_offsetx'] : '0';
						$bgstyleMouseParallaxOffsetY = (isset($myParam['background_style_mouseparallax_offsety']) && !empty($myParam['background_style_mouseparallax_offsety'])) ? $myParam['background_style_mouseparallax_offsety'] : '0';
						$bgStyleVideoSource = (isset($myParam['background_video_source']) && !empty($myParam['background_video_source'])) ? $myParam['background_video_source'] : '';
						$bgStyleVideoId = (isset($myParam['background_video_vid']) && !empty($myParam['background_video_vid'])) ? $myParam['background_video_vid'] : '';
						$bgstyleVideoMp4 = (isset($myParam['background_video_mp4']) && !empty($myParam['background_video_mp4'])) ? $myParam['background_video_mp4'] : '';
						$bgstyleVideoWebm = (isset($myParam['background_video_webm']) && !empty($myParam['background_video_webm'])) ? $myParam['background_video_webm'] : '';
						$bgstyleVideoOgg = (isset($myParam['background_video_ogg']) && !empty($myParam['background_video_ogg'])) ? $myParam['background_video_ogg'] : '';

						$bgStyleVideoData = array();
						$bgStyleImgData = array();
						$bgStyleVideoHtml = '';
						$bgStyleImgHtml = '';
						if ($bgStyle === 'video')
						{
							$bgStyleVideoData['group_class'] = $classBgStyle;
							$bgStyleVideoData['type'] = $bgStyleVideoSource;
							$bgStyleVideoData['id'] = $bgStyleVideoId;
							$bgStyleVideoData['url_mp4'] = $bgstyleVideoMp4;
							$bgStyleVideoData['url_webm'] = $bgstyleVideoWebm;
							$bgStyleVideoData['url_ogg'] = $bgstyleVideoOgg;

							$bgStyleVideoHtml = $this->getBgStyleVideo($bgStyleVideoData);
						}
						elseif ($bgStyle !== '')
						{
							$bgStyleImgData['type'] = $bgStyle;
							$bgStyleImgData['full_width'] = $bgStyleFullwidth;
							$bgStyleImgData['img_url'] = $bgStyleImageUrl;
							$bgStyleImgData['img_position'] = $bgStylePosition;
							$bgStyleImgData['img_repeat'] = $bgStyleRepeat;
							$bgStyleImgData['bg_color'] = $bgStyleColor;
							$bgStyleImgData['parallax_speed'] = $bgstyleParallaxSpeed;
							$bgStyleImgData['parallax_offsetx'] = $bgstyleParallaxOffsetx;
							$bgStyleImgData['parallax_offsety'] = $bgstyleParallaxOffsety;
							$bgStyleImgData['mparallax_strength'] = $bgstyleMouseParallaxStrength;
							$bgStyleImgData['mparallax_axis'] = $bgstyleMouseParallaxAxis;
							$bgStyleImgData['mparallax_offsetx'] = $bgstyleMouseParallaxOffsetX;
							$bgStyleImgData['mparallax_offsety'] = $bgstyleMouseParallaxOffsetY;
							$bgStyleImgData['mparallax_rid'] = 'mparallax_'.$row['id'];

							$bgStyleImgHtml = $this->getBgStyleImage($bgStyleImgData);
						}

						$this->_bg_style_config_data[$row['id']] = array(
							'group_id' => $row['id'],
							'group_class' => $classBgStyle,
							'hook_name' => $myParam['hook_name'],
							'background_style' => $bgStyle,
							'background_image_html' => $bgStyleImgHtml,
							'background_video_source' => $bgStyleVideoSource,
							'background_video_html' => $bgStyleVideoHtml,
							'background_fullwidth' => $bgStyleFullwidth,
						);

						$this->_has_bg_style = 1;
					}
				}

				if ($myParam)
				{
					foreach ($myParam as $key => $value)
					{
						$row[$key] = $value;
						if ($key == $titleLang)
							$row['title'] = $value;
					}
				}
			}
			unset($row['params']);
			//add column to group
			if (!$isFont || isset($this->_columnList[$row['id']]))
			{
				$row['columns'] = isset($this->_columnList[$row['id']]) ? $this->_columnList[$row['id']] : array();
				$result[$row['hook_name']][] = $row;
			}
		}

		return $result;
	}

	public function getBgStyleImage($bgStyleImgData = array())
	{
		$resultHtml = 'style="';

		if ($bgStyleImgData['img_url'])
			$resultHtml .= 'background-image: url('.$bgStyleImgData['img_url'].');';
		if ($bgStyleImgData['img_repeat'])
			$resultHtml .= 'background-repeat: '.$bgStyleImgData['img_repeat'].';';
		if ($bgStyleImgData['bg_color'])
			$resultHtml .= 'background-color: '.$bgStyleImgData['bg_color'].';';
		if ($bgStyleImgData['type'] == 'parallax' || $bgStyleImgData['type'] == 'fixed')
			$resultHtml .= 'background-attachment: fixed;';
		if ($bgStyleImgData['img_position'] && $bgStyleImgData['type'] != 'mouseparallax')
			$resultHtml .= 'background-position: '.$bgStyleImgData['img_position'].';';
		elseif ($bgStyleImgData['type'] == 'mouseparallax')
		{
			$mparallax_posX = !$bgStyleImgData['full_width'] ? $bgStyleImgData['mparallax_offsetx'] : ($bgStyleImgData['mparallax_offsetx'] + 1000);

			$resultHtml .= 'background-position: '.$mparallax_posX.'px ';
			$resultHtml .= $bgStyleImgData['mparallax_offsety'].'px;';
		}

		$resultHtml .= '"';

		if ($bgStyleImgData['type'] == 'parallax')
		{
			$resultHtml .= ' data-stellar-background-ratio="'.$bgStyleImgData['parallax_speed'].'" 
                            data-stellar-horizontal-offset="'.$bgStyleImgData['parallax_offsetx'].'" 
                            data-stellar-vertical-offset="'.$bgStyleImgData['parallax_offsety'].'"
                            ';
			$this->_has_bg_style_parallax = 1;
		}
		elseif ($bgStyleImgData['type'] == 'mouseparallax')
		{
			$resultHtml .= ' data-mouse-parallax-strength="'.$bgStyleImgData['mparallax_strength'].'" 
                            data-mouse-parallax-axis="'.$bgStyleImgData['mparallax_axis'].'" 
                            data-mouse-parallax-rid="'.$bgStyleImgData['mparallax_rid'].'"
                            ';
			$this->_has_bg_style_mouseparallax = 1;
		}

		return $resultHtml;
	}

	public function getBgStyleVideo($bgStyleVideoData = array())
	{
		$resultHtml = '';
		$iframeId = 'video_'.$bgStyleVideoData['group_class'];

		if (isset($bgStyleVideoData['type']) && $bgStyleVideoData['type'] === 'html5' &&
				((isset($bgStyleVideoData['url_mp4']) && $bgStyleVideoData['url_mp4'] != '') ||
				(isset($bgStyleVideoData['url_webm']) && $bgStyleVideoData['url_webm'] != '') ||
				(isset($bgStyleVideoData['url_ogg']) && $bgStyleVideoData['url_ogg'] != '')))
		{
			//embed mp4, webm, ogg video background HTML
			$resultHtml .= '<div class="background_style_video_content">
                                <div class="wrapper_background_style_video"></div>
                                <video id="'.$iframeId.'" height="100%" width="100%" autoplay loop muted 
                                    style="position: absolute; width: 100%; left: 0px; top: 0px; z-index: 0;">';
			if (isset($bgStyleVideoData['url_mp4']) && $bgStyleVideoData['url_mp4'] != '')
				$resultHtml .= '        <source type="video/mp4" src="'.$bgStyleVideoData['url_mp4'].'"></source>';
			if (isset($bgStyleVideoData['url_webm']) && $bgStyleVideoData['url_webm'] != '')
				$resultHtml .= '        <source type="video/webm" src="'.$bgStyleVideoData['url_webm'].'"></source>';
			if (isset($bgStyleVideoData['url_ogg']) && $bgStyleVideoData['url_ogg'] != '')
				$resultHtml .= '        <source type="video/ogg" src="'.$bgStyleVideoData['url_ogg'].'"></source>';
			$resultHtml .= '    </video>
                            </div>';
		}elseif (isset($bgStyleVideoData['type']) && $bgStyleVideoData['type'] === 'vimeo' && isset($bgStyleVideoData['id']) && $bgStyleVideoData['id'] !== '')
		{
			// embed vimeo video bacskground HTML
			$resultHtml .= '<div class="background_style_video_content">
                                <div class="wrapper_background_style_video"></div>
                                <iframe id="vm-'.$iframeId.'" width="100%" height="100%"
                                    class="iframe-vimeo-api-tag" data-vimeo-video-id="'.$bgStyleVideoData['id'].'" 
                                    src="http://player.vimeo.com/video/'.$bgStyleVideoData['id'].'?autoplay=1&title=0&badge=0&byline=0
                                    &loop=1&portrait=0&player_id=vm-'.$iframeId.'&api=1" 
                                    frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style=""></iframe>
                            </div>';
			$this->_has_bg_style_vimeo = 1;
		}
		elseif (isset($bgStyleVideoData['type']) && $bgStyleVideoData['type'] === 'youtube' && isset($bgStyleVideoData['id']) && $bgStyleVideoData['id'] !== '')
		{
			//embed youtube video background HTML
			$resultHtml .= '<div class="background_style_video_content">
                                <div class="wrapper_background_style_video"></div>
                                <div id="yt-'.$iframeId.'" class="iframe-youtube-api-tag" data-youtube-video-id="'.$bgStyleVideoData['id'].'"></div>
                            </div>';
			$this->_has_bg_style_youtube = 1;
		}

		return $resultHtml;
	}

	public function displayModuleExceptionList()
	{
		$file_list = array();
//		$shop_id = 0;
		$content = '<p><input type="text" name="column_pages" value="" class="em_text"/></p>';

		$content .= '<p>
                                    <select size="25" name="column_pages_select" class="em_list" multiple="multiple">
                                    <option disabled="disabled">'.$this->l('___________ CUSTOM ___________').'</option>';

		// @todo do something better with controllers
		$controllers = Dispatcher::getControllers(_PS_FRONT_CONTROLLER_DIR_);
		$controllers['module'] = $this->l('Module Page');
		ksort($controllers);

		foreach ($file_list as $k => $v)
			if (!array_key_exists($v, $controllers))
				$content .= '<option value="'.$v.'">'.$v.'</option>';

		$content .= '<option disabled="disabled">'.$this->l('____________ CORE ____________').'</option>';

		foreach ($controllers as $k => $v)
			$content .= '<option value="'.$k.'">'.$k.'</option>';
		$modules_controllers_type = array('admin' => $this->l('Admin modules controller'), 'front' => $this->l('Front modules controller'));
		foreach ($modules_controllers_type as $type => $label)
		{
			$content .= '<option disabled="disabled">____________ '.$label.' ____________</option>';
			$all_modules_controllers = Dispatcher::getModuleControllers($type);
			foreach ($all_modules_controllers as $module => $modules_controllers)
				foreach ($modules_controllers as $cont)
					$content .= '<option value="module-'.$module.'-'.$cont.'">module-'.$module.'-'.$cont.'</option>';
		}

		$content .= '</select>
                                    </p>';
		return $content;
	}

	public function renderForm()
	{
		//get all group and column
		$this->_columnList = $this->parseColumnByGroup(LeoManageWidgetColumn::getAllColumn('', 0, 1));
		$this->_groupList = $this->parseGroupByHook(LeoManageWidgetGroup::getAllGroup());
		$fields_form = $this->makeFieldsOptions();
		$widthArray = array(
			1 => array('12' => $this->l('1/1')),
			2 => array('6' => $this->l('1/2')),
			3 => array('4' => $this->l('1/3'), '8' => $this->l('2/3')),
			4 => array('3' => $this->l('1/4'), '9' => $this->l('3/4')),
			5 => array('2.4' => $this->l('1/5'), '4.8' => $this->l('2/5'), '7.2' => $this->l('3/5'), '9.6' => $this->l('4/5')),
			6 => array('2' => $this->l('1/6'), '10' => $this->l('5/6'))
		);
		$widthArray = array('12', '10', '9.6', '9', '8', '7.2', '6', '4.8', '4', '3', '2.4', '2', '1');

		$skinAnimate = array(
			'group1' => array(
				'name' => 'Attention Seekers',
				'items' => array('______Attention Seekers_____', 'bounce', 'flash', 'pulse', 'rubberBand', 'swing', 'shake', 'tada', 'wobble'
				)
			),
			'group2' => array(
				'name' => 'Bouncing Entrances',
				'items' => array('_____Bouncing Entrances_____', 'bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp'
				)
			),
			'group3' => array(
				'name' => 'Fading Entrances',
				'items' => array('_____Fading Entrances_____', 'fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInRight', 'fadeInRightBig', 'fadeInUp', 'fadeInUpBig'
				)
			),
			'group4' => array(
				'name' => 'Fading Exits',
				'items' => array('_____Fading Exits_____', 'fadeOut', 'fadeOutDown', 'fadeOutDownBig', 'fadeOutLeft', 'fadeOutLeftBig', 'fadeOutRightBig', 'fadeOutRight', 'fadeOutUp', 'fadeOutUpBig'
				)
			),
			'group5' => array(
				'name' => 'Rotating Entrances',
				'items' => array('_____Rotating Entrances_____', 'rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight'
				)
			),
			'group6' => array(
				'name' => 'Flippers',
				'items' => array('_____Flippers_____', 'flip', 'flipInX', 'flipInY', 'flipOutX', 'flipOutY'
				)
			),
			'group7' => array(
				'name' => 'Lightspeed',
				'items' => array('_____Rotating Entrances_____', 'lightSpeedIn', 'lightSpeedOut'
				)
			),
			'group8' => array(
				'name' => 'Rotating Exits',
				'items' => array('_____Rotating Exits_____', 'rotateOut', 'rotateOutDownLeft', 'rotateOutDownRight', 'rotateOutUpLeft', 'rotateOutUpRight'
				)
			),
			'group9' => array(
				'name' => 'Specials',
				'items' => array('_____Specials_____', 'hinge', 'rollIn', 'rollOut'
				)
			),
			'group10' => array(
				'name' => 'Zoom Entrances',
				'items' => array('_____Zoom Entrances_____', 'zoomIn', 'zoomInDown', 'zoomInLeft', 'zoomInRight', 'zoomInUp'
				)
			),
		);
		$widgets = $this->formatWidget();
		$helper = new HelperForm();
		if ($this->_leotype == 1)
			$helper->base_tpl = 'leo_form.tpl';
		else
			$helper->base_tpl = 'default_form.tpl';

		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitMWidget';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$groupField = 'new Array(';
		//set widget field
		foreach ($this->_groupField as $val)
		{
			if (!is_array($val))
				$groupField .= '"'.$val.'",';
			else
				foreach ($val as $paramv)
					$groupField .= '"'.$paramv.'",';
		}
		$groupField = Tools::substr($groupField, 0, -1).');';
		//die($groupField);
		$columnField = 'new Array(';
		//set widget field
		foreach ($this->_columnField as $val)
		{
			if (!is_array($val))
				$columnField .= '"'.$val.'",';
			else
				foreach ($val as $paramv)
					$columnField .= '"'.$paramv.'",';
		}
		$columnField = Tools::substr($columnField, 0, -1).');';

		$rowField = 'new Array(';
		foreach ($this->_rowField as $val)
		{
			if (!is_array($val))
				$rowField .= '"'.$val.'",';
			else
				foreach ($val as $paramv)
					$rowField .= '"'.$paramv.'",';
		}
		$rowField = Tools::substr($rowField, 0, -1).');';

		$hidden_config = array('hidden-lg' => $this->l('Hidden in Large devices'), 'hidden-md' => $this->l('Hidden in Medium devices'),
			'hidden-sm' => $this->l('Hidden in Small devices'), 'hidden-xs' => $this->l('Hidden in Extra small devices'), 'hidden-sp' => $this->l('Hidden in Smart Phone'));

		$leo_json_data = Tools::jsonEncode($this->_groupList);
		$leo_json_data = str_replace(array('\n', '\r', '\t', "'", '"'), array('', '', '', "\\'", '\"'), $leo_json_data);

		$helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
				'id_lang' => $language->id,
				'iso_code' => $language->iso_code
			),
			'fields_value' => $this->getAddFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'leo_widgets' => $widgets,
			'leo_modules' => $this->getModules(),
			'leo_width' => $widthArray,
			'skin_animate_load' => $skinAnimate,
			'img_admin_url' => _PS_ADMIN_IMG_,
			'leo_groupField' => $groupField,
			'leo_columnField' => $columnField,
			'leo_rowField' => $rowField,
			'leo_group_list' => $this->_groupList,
			'leo_json_data' => $leo_json_data,
			'leo_tpl_group' => _PS_MODULE_DIR_.$this->name.'/views/templates/admin/_configure/helpers/form/form_grouplist.tpl',
			'exception_list' => $this->displayModuleExceptionList(),
			'leo_submit_link' => $this->context->shop->physical_uri.$this->context->shop->virtual_uri.'modules/'.$this->name.'/ajax_'.$this->name.'.php?secure_key='.$this->secure_key.'&action=submitForm',
			'widget_link' => $this->_leotype == 1 ? Context::getContext()->link->getAdminLink('AdminLeotempcpWidgets') : Context::getContext()->link->getAdminLink('AdminLeomanagewidgetsWidgets'),
			'module_link' => Context::getContext()->link->getAdminLink('AdminModules'),
			'languages' => $this->context->controller->getLanguages(),
			'hidden_config' => $hidden_config
		);
		$helper->override_folder = '/';
		return $helper->generateForm($fields_form);
	}

	public function deleteModuleFromHook($hook_name, $module_name)
	{
		$res = true;
		$sql = 'DELETE FROM `'._DB_PREFIX_.'hook_module`
				WHERE `id_hook` IN(
					SELECT `id_hook` FROM `'._DB_PREFIX_."hook`
					WHERE NAME ='".pSQL($hook_name)."'".') 
				AND `id_module` IN(SELECT`id_module`
					FROM `'._DB_PREFIX_."module`
					WHERE NAME ='".pSQL($module_name)."')";

		$res &= Db::getInstance()->execute($sql);
		return $res;
	}

	public function getAddFieldsValues()
	{
		$field = array(
			'LEO_ANIMATELOAD' => Configuration::get('LEO_ANIMATELOAD')
		);
		return $field;
	}

	public function clearCache()
	{

	}

	public function getConfigFieldsValues()
	{
		return array(
			'HOME_FEATURED_NBR' => Tools::getValue('HOME_FEATURED_NBR', Configuration::get('HOME_FEATURED_NBR')),
		);
	}

	private function _setGroupData($groupsList, $hook_name)
	{
		foreach ($groupsList as &$group)
		{
			if (isset($group['columns']))
				foreach ($group['columns'] as &$column)
				{
					$pages = array();
					if (isset($column['pages']) && $column['pages'])
					{
						$column['pages'] = str_replace(', ', ',', $column['pages']);
						$pages = explode(',', $column['pages']);
					}
					if (isset($column['rows']))
					{
						foreach ($column['rows'] as &$row)
						{

							//is a widget
							if ($row['type'] == '0')
							{
								$content = $this->_widgets->renderContent($row['key_widget']);
								$content['type'] = LeomanagewidgetsHelper::processWidgetType($hook_name, $row['key_widget'], $content['type'], $content['data']);
								//if ($this->_load_owl_carousel_lib == false)
								//	$this->_load_owl_carousel_lib = LeomanagewidgetsHelper::enableLoadOwlCarouselLib($content['data']);
								$row['content'] = $this->getWidgetContent($hook_name, $row['key_widget'], $content['type'], $content['data']);
								//is a module
							}
							else
							{
								if (isset($row['module_name']) && isset($row['hook_name']) && $row['module_name'] && $row['hook_name'])
									$row['content'] = $this->execModuleHook($row['hook_name'], array(), $row['module_name'], false, $this->context->shop->id);
							}
						}
					}
				}
		}
		# validate module
		unset($pages);
		return $groupsList;
	}

	/**
	 *
	 */
	public function getWidgetContent($hook_name, $key_widget, $type, $data)
	{
		$data['id_lang'] = $this->context->language->id;
		$data['owl_rtl'] = $this->context->language->is_rtl;
		$this->smarty->assign($data);
		//check override widget key
		if (file_exists(_PS_ALL_THEMES_DIR_.$this->_themeName.'/modules/leomanagewidgets/views/widgets/'.$hook_name.'/'.$key_widget.'/widget_'.$type.'.tpl'))
			$output = $this->display(__FILE__, 'views/widgets/'.$hook_name.'/'.$key_widget.'/widget_'.$type.'.tpl');
		elseif (file_exists(dirname(__FILE__).'/views/widgets/'.$hook_name.'/'.$key_widget.'/widget_'.$type.'.tpl'))
			$output = $this->display(__FILE__, 'views/widgets/'.$hook_name.'/'.$key_widget.'/widget_'.$type.'.tpl');
		elseif (file_exists(_PS_ALL_THEMES_DIR_.$this->_themeName.'/modules/leomanagewidgets/views/widgets/'.$hook_name.'/widget_'.$type.'.tpl'))
			$output = $this->display(__FILE__, 'views/widgets/'.$hook_name.'/widget_'.$type.'.tpl');
		elseif (file_exists(dirname(__FILE__).'/views/widgets/'.$hook_name.'/widget_'.$type.'.tpl'))
			$output = $this->display(__FILE__, 'views/widgets/'.$hook_name.'/widget_'.$type.'.tpl');
		else
			$output = $this->display(__FILE__, 'views/widgets/widget_'.$type.'.tpl');

		return $output;
	}

	private function _processHook($hook_name)
	{
		$hook_name = Tools::strtolower($hook_name);

		$this->context->controller->addCSS($this->_path.'assets/owl-carousel/owl.carousel.css', 'all');
		$this->context->controller->addCSS($this->_path.'assets/owl-carousel/owl.theme.css', 'all');
			
		if ($this->_leotype == 1)
		{
			if (!file_exists(_PS_MODULE_DIR_.'leotempcp/classes/widgetbase.php'))
				return $this->l('Please install leotemcp module');
		}
		else
		{
			if (!file_exists(_PS_MODULE_DIR_.'leomanagewidgets/classes/widgetbase.php'))
				return $this->l('Please install leotemcp module');
		}
		if (file_exists(_PS_ALL_THEMES_DIR_.$this->_themeName.'/modules/leomanagewidgets/views/widgets/'.$hook_name.'/group.tpl'))
			$tplFile = 'views/widgets/'.$hook_name.'/group.tpl';
		elseif (file_exists(dirname(__FILE__).'/views/widgets/'.$hook_name.'/group.tpl'))
			$tplFile = 'views/widgets/'.$hook_name.'/group.tpl';
		else
			$tplFile = 'views/widgets/group.tpl';

		$this->setParams($hook_name);
		$cache_id = $this->getCacheId();
		if (!$this->isCached($tplFile, $cache_id))
		{
			# generate cache
			if (!$this->_widgets)
			{
				$this->_widgets = new LeoTempcpWidget();
				$this->_widgets->setTheme(Context::getContext()->shop->getTheme());
				$this->_widgets->langID = Context::getContext()->language->id;
				$this->_widgets->loadWidgets();
				$this->_widgets->loadEngines();
			}

			if (!$this->_columnList)
			{
				$this->_columnList = $this->parseColumnByGroup(LeoManageWidgetColumn::getAllColumn(' AND `active`=1', 0, 1, 1), 1);
				$this->_groupList = $this->parseGroupByHook(LeoManageWidgetGroup::getAllGroup(' AND `active`=1'), 1);
			}

			//return if don't exist
			if (!isset($this->_groupList[$hook_name]))
				return false;
			$groups = array();
			$groups = $this->_setGroupData($this->_groupList[$hook_name], $hook_name);

			$this->smarty->assign('leoGroup', $groups);

			if ($this->_has_bg_style)
				$this->smarty->assign('LEO_BG_STYLE_DATA', $this->_bg_style_config_data);
	
//		    if ($this->_load_owl_carousel_lib){
//		    }
			return $this->display(__FILE__, $tplFile, $this->getCacheId());
			
		}
		else
		{
			# load cache
			return $this->display(__FILE__, $tplFile, $cache_id);
			
		}
		
//		return $this->display(__FILE__, $tplFile);
	}

	public function allowShowInController($controller, $ids)
	{
		//if do not input
		if ($controller == 'index' || !$ids)
			return true;
		$ids = explode(',', $ids);
		switch ($controller)
		{
			case 'product':
				$currentID = Tools::getValue('id_product');
				if (in_array($currentID, $ids))
					return true;
			case 'category':
				$currentID = Tools::getValue('id_category');
				if (in_array($currentID, $ids))
					return true;
			case 'cms':
				$currentID = Tools::getValue('id_cms');
				if (in_array($currentID, $ids))
					return true;
			default:
				return false;
		}
	}

	public static function execModuleHook($hook_name, $hook_args = array(), $module_name, $use_push = false, $id_shop = null)
	{
		static $disable_non_native_modules = null;
		if ($disable_non_native_modules === null)
			$disable_non_native_modules = (bool)Configuration::get('PS_DISABLE_NON_NATIVE_MODULE');
		// Check arguments validity
		if (!Validate::isModuleName($module_name) || !Validate::isHookName($hook_name))
			throw new PrestaShopException('Invalid module name or hook name');

		// If no modules associated to hook_name or recompatible hook name, we stop the function
		if (!Hook::getHookModuleExecList($hook_name))
			return '';
		// Check if hook exists
		if (!$id_hook = Hook::getIdByName($hook_name))
			return false;

		// Store list of executed hooks on this page
		Hook::$executed_hooks[$id_hook] = $hook_name;

//		$live_edit = false;
		$context = Context::getContext();
		if (!isset($hook_args['cookie']) || !$hook_args['cookie'])
			$hook_args['cookie'] = $context->cookie;
		if (!isset($hook_args['cart']) || !$hook_args['cart'])
			$hook_args['cart'] = $context->cart;

		$retro_hook_name = Hook::getRetroHookName($hook_name);
		// Look on modules list
		$altern = 0;
		$output = '';

		if ($disable_non_native_modules && !isset(Hook::$native_module))
			Hook::$native_module = Module::getNativeModuleList();

		$different_shop = false;
		if ($id_shop !== null && Validate::isUnsignedId($id_shop) && $id_shop != $context->shop->getContextShopID())
		{
//			$old_context_shop_id = $context->shop->getContextShopID();
			$old_context = $context->shop->getContext();
			$old_shop = clone $context->shop;
			$shop = new Shop((int)$id_shop);
			if (Validate::isLoadedObject($shop))
			{
				$context->shop = $shop;
				$context->shop->setContext(Shop::CONTEXT_SHOP, $shop->id);
				$different_shop = true;
			}
		}

		// Check errors
		if ((bool)$disable_non_native_modules && Hook::$native_module && count(Hook::$native_module) && !in_array($module_name, self::$native_module))
			return;

		if (!($moduleInstance = Module::getInstanceByName($module_name)))
			return;

		if ($use_push && !$moduleInstance->allow_push)
			continue;
		// Check which / if method is callable
		$hook_callable = is_callable(array($moduleInstance, 'hook'.$hook_name));
		$hook_retro_callable = is_callable(array($moduleInstance, 'hook'.$retro_hook_name));

		if (($hook_callable || $hook_retro_callable) && Module::preCall($moduleInstance->name))
		{
			$hook_args['altern'] = ++$altern;

			if ($use_push && isset($moduleInstance->push_filename) && file_exists($moduleInstance->push_filename))
				Tools::waitUntilFileIsModified($moduleInstance->push_filename, $moduleInstance->push_time_limit);

			// Call hook method
			if ($hook_callable)
				$display = $moduleInstance->{'hook'.$hook_name}($hook_args);

			elseif ($hook_retro_callable)
				$display = $moduleInstance->{'hook'.$retro_hook_name}($hook_args);

			$output .= $display;
		}


		if ($different_shop)
		{
			$context->shop = $old_shop;
			$context->shop->setContext($old_context, $shop->id);
		}
		return $output; // Return html string
	}

	public function getModules()
	{
		$notModule = array($this->name, 'leoblog', 'themeconfigurator', 'leotempcp', 'themeinstallator', 'cheque');
		$where = '';
		if (count($notModule) == 1)
			$where = ' WHERE m.`name` <> \''.$notModule[0].'\'';
		elseif (count($notModule) > 1)
			$where = ' WHERE m.`name` NOT IN (\''.implode("','", $notModule).'\')';

		$context = Context::getContext();
		$id_shop = $context->shop->id;
		$modules = Db::getInstance()->ExecuteS('
        SELECT m.name, m.id_module
        FROM `'._DB_PREFIX_.'module` m
        JOIN `'._DB_PREFIX_.'module_shop` ms ON (m.`id_module` = ms.`id_module` AND ms.`id_shop` = '.(int)($id_shop).')
        '.$where);
		$result = array();
		foreach ($modules as $m)
		{
			$m['hook_list'] = '';
			$arrHooks = $this->getHooksByModuleId($m['id_module'], $id_shop);
			if ($arrHooks)
			{
				$strArrHooks = '';
				if (count($arrHooks) > 0)
				{
					$strArrHooks = $arrHooks[0]['name'];
					//find if exist a row of module-hook in database
					if ($this->checkModuleInHook($m['id_module'], $arrHooks[0]['id_hook']))
						$strArrHooks .= '-'.'1';
					else
						$strArrHooks .= '-'.'0';
				}
				$count_arrHooks = count($arrHooks);
				if ($count_arrHooks > 1)
					for ($i = 1; $i < $count_arrHooks; $i++)
					{
						$strArrHooks .= ','.$arrHooks[$i]['name'];
						if ($this->checkModuleInHook($m['id_module'], $arrHooks[$i]['id_hook']))
							$strArrHooks .= '-'.'1';
						else
							$strArrHooks .= '-'.'0';
					}
				$m['hook_list'] = $strArrHooks;
			}
			$result[] = $m;
		}
		return $result;
	}

	/**
	 * Get list of all registered hooks with modules
	 *
	 * @since 1.5.0
	 * @return array
	 */
	public static function checkModuleInHook($module_id, $id_hook)
	{
		if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT * FROM `'._DB_PREFIX_.'hook_module` hm
            WHERE hm.id_module = '.(int)$module_id.
				' AND hm.id_hook = '.(int)$id_hook))
			return false;
		else
			return true;
	}

	public function getModulById($id_module, $id_shop)
	{
		return Db::getInstance()->getRow('
        SELECT m.*
        FROM `'._DB_PREFIX_.'module` m
        JOIN `'._DB_PREFIX_.'module_shop` ms ON (m.`id_module` = ms.`id_module` AND ms.`id_shop` = '.(int)($id_shop).')
        WHERE m.`id_module` = '.(int)$id_module);
	}

	public static function getHookByArrName($arrName)
	{
		$result = Db::getInstance()->ExecuteS('
            SELECT `id_hook`, `name`
            FROM `'._DB_PREFIX_.'hook`
            WHERE `name` IN (\''.implode("','", $arrName).'\')');
		return $result;
	}

	public function getHooksByModuleId($id_module, $id_shop)
	{
		$module = self::getModulById($id_module, $id_shop);
		$moduleInstance = Module::getInstanceByName($module['name']);
		//echo "<pre>";print_r($moduleInstance);die;
		$hooks = array();
		if ($this->_hookAssign)
			foreach ($this->_hookAssign as $hook)
			{
				$retro_hook_name = Hook::getRetroHookName($hook);
				if ($hook == 'topcolumn')
					$retro_hook_name = 'displayTopColumn';
				if ($hook == 'nav')
					$retro_hook_name = 'displayNav';
				if (is_callable(array($moduleInstance, 'hook'.$hook)) || is_callable(array($moduleInstance, 'hook'.$retro_hook_name)))
					$hooks[] = $retro_hook_name;
			}
		$results = self::getHookByArrName($hooks);
		return $results;
	}

	public function hookDisplayBanner()
	{
		return $this->_processHook('displayBanner');
	}

	public function hookDisplayNav()
	{
		return $this->_processHook('displayNav');
	}

	public function hookDisplayTop()
	{
		return $this->_processHook('displayTop');
	}

	public function hookDisplayHeaderRight()
	{
		return $this->_processHook('displayHeaderRight');
	}

	public function hookDisplaySlideshow()
	{
		return $this->_processHook('displaySlideshow');
	}

	public function hookTopNavigation()
	{
		return $this->_processHook('topNavigation');
	}

	public function hookDisplayPromoteTop()
	{
		return $this->_processHook('displayPromoteTop');
	}

	public function hookDisplayRightColumn()
	{
		return $this->_processHook('displayRightColumn');
	}

	public function hookDisplayLeftColumn()
	{
		return $this->_processHook('displayLeftColumn');
	}

	public function hookDisplayHome()
	{
		return $this->_processHook('displayHome');
	}

	public function hookDisplayFooter()
	{
		return $this->_processHook('displayFooter');
	}

	public function hookHeader()
	{
		$result = array();
		$result['javascript'] = '';
		$this->context->controller->addJS($this->_path.'assets/owl-carousel/owl.carousel.js');
		$this->context->controller->addCSS($this->_path.'assets/styles.css');
		$this->context->controller->addJS($this->_path.'assets/script.js');

		if (!$this->_columnList)
		{
			$this->_columnList = $this->parseColumnByGroup(LeoManageWidgetColumn::getAllColumn(' AND `active`=1', 0, 1, 1), 1);
			$this->_groupList = $this->parseGroupByHook(LeoManageWidgetGroup::getAllGroup(' AND `active`=1'), 1);
		}

		$this->_enable_config_animate_style = Configuration::get('LEO_ANIMATELOAD');

		if ($this->_enable_config_animate_style && $this->_has_animate_style)
		{
			/** include animation js * */
			$this->context->controller->addJS(($this->_path).'assets/admin/waypoints.min.js', 'all');
			$this->context->controller->addCss(($this->_path).'/assets/admin/animate.css', 'all');

			foreach ($this->_animate_style_config_data as $classAnimation => $animationData)
			{
				if ($classAnimation != '')
				{
					$result['javascript'] .= '$(document).ready(function(){
                                    $(".'.$classAnimation.'").waypoint(function () {
                                        $(this).addClass("animated '.$animationData['skin'].'");
                                        $(this).css("animation-delay", "'.$animationData['delay'].'s");
                                        $(this).css("-webkit-animation-delay", "'.$animationData['delay'].'s");
                                    }, {offset: "'.$animationData['offset'].'%"});
                                });';
				}
			}
			/** end include animation js * */
		}

		if ($this->_has_bg_style)
		{
			/** include background style js * */
			if ($this->_bg_style_fullwidth)
			{
				$result['javascript'] .= '$(document).ready(function(){
                                $("#page").css("overflow","hidden");
                            });';
			}
			if ($this->_has_bg_style_parallax)
			{
				$this->context->controller->addJS($this->_path.'assets/jquery.stellar.js', 'all');
				$result['javascript'] .= '$(document).ready(function(){
                                $.stellar({horizontalScrolling:false});
                            });';
			}
			if ($this->_has_bg_style_mouseparallax)
			{
				$result['javascript'] .= '$(document).ready(function(){
                                currentPosX = [];
                                currentPosY = [];
                                $("div[data-mouse-parallax-strength]").each(function(){
                                    currentPos = $(this).css("background-position");
                                    if (typeof currentPos == "string")
                                    {
                                        currentPosArray = currentPos.split(" ");
                                    }else
                                    {
                                        currentPosArray = [$(this).css("background-position-x"),$(this).css("background-position-y")];
                                    }
                                    currentPosX[$(this).data("mouse-parallax-rid")] = parseFloat(currentPosArray[0]);
                                    currentPosY[$(this).data("mouse-parallax-rid")] = parseFloat(currentPosArray[1]);
                                    $(this).mousemove(function(e){
                                        newPosX = currentPosX[$(this).data("mouse-parallax-rid")];
                                        newPosY = currentPosY[$(this).data("mouse-parallax-rid")];
                                        if($(this).data("mouse-parallax-axis") != "axis-y"){
                                            mparallaxPageX = e.pageX - $(this).offset().left;
                                            if($(this).hasClass("full-bg-screen"))
                                            {
                                                mparallaxPageX = mparallaxPageX - 1000;
                                            }
                                            newPosX = (mparallaxPageX * $(this).data("mouse-parallax-strength") * -1) + newPosX;
                                        }
                                        if($(this).data("mouse-parallax-axis") !="axis-x"){
                                            mparallaxPageY = e.pageY - $(this).offset().top;
                                            newPosY = mparallaxPageY * $(this).data("mouse-parallax-strength") * -1;
                                        }
                                        $(this).css("background-position",newPosX+"px "+newPosY+"px");
                                    });
                                });
                            });';
			}
			if ($this->_has_bg_style_vimeo)
			{
				$this->context->controller->addJS('http://f.vimeocdn.com/js/froogaloop2.min.js');
				$result['javascript'] .= '$(document).ready(function(){
                                $("iframe.iframe-vimeo-api-tag").each(function(){
                                    Froogaloop(this).addEvent("ready", function(player_id){
                                        Froogaloop(player_id).api("setVolume",0);
                                    })
                                });
                            });';
			}
			if ($this->_has_bg_style_youtube)
			{
				$this->context->controller->addJS('https://www.youtube.com/iframe_api');
				$result['javascript'] .= 'var ytIframeId; var ytVideoId;
                                function onYouTubeIframeAPIReady() {
                                    $("div.iframe-youtube-api-tag").each(function(){
                                        ytIframeId = $(this).attr("id");
                                        ytVideoId = $(this).data("youtube-video-id");

                                        new YT.Player(ytIframeId, {
                                            videoId: ytVideoId,
                                            width: "100%",
                                            height: "100%",
                                            playerVars :{autoplay:1,controls:0,disablekb:1,fs:0,cc_load_policy:0,
                                                        iv_load_policy:3,modestbranding:0,rel:0,showinfo:0,start:0},
                                            events: {
                                                "onReady": function(event){
                                                    event.target.mute();
                                                    setInterval(
                                                        function(){event.target.seekTo(0);},
                                                        (event.target.getDuration() - 1) * 1000
                                                    );
                                                }
                                            }
                                        });
                                    });
                                }';
			}
			/** end include background style js * */
		}

		$this->smarty->assign('leo_managewidget_header', $result);
		return $this->display(__FILE__, 'views/widgets/header.tpl');
	}

	# product hook list
	public function hookProductTab()
	{
		return ($this->display(__FILE__, '/tab.tpl'));
	}

	public function hookProductTabContent($params)
	{
		return $this->_processHook('productTabContent');
	}

	public function hookDisplayBottom()
	{
		return $this->_processHook('displayBottom');
	}

	public function hookDisplayFooterProduct($params)
	{
		return $this->_processHook('displayFooterProduct');
	}

	public function hookDisplayTopColumn()
	{
		return $this->_processHook('displayTopColumn');
	}

	public function hookDisplayRightColumnProduct($params)
	{
		return $this->_processHook('displayRightColumnProduct');
	}

	public function hookDisplayLeftColumnProduct($params)
	{
		return $this->_processHook('displayLeftColumnProduct');
	}

	public function hookDisplayMaintenance()
	{
		return $this->_processHook('displayMaintenance');
	}

	public function hookDisplayOrderConfirmation()
	{
		return $this->_processHook('displayOrderConfirmation');
	}

	public function hookDisplayOrderDetail()
	{
		return $this->_processHook('displayOrderDetail');
	}

	public function hookDisplayPayment()
	{
		return $this->_processHook('displayPayment');
	}

	public function hookDisplayPaymentReturn()
	{
		return $this->_processHook('displayPaymentReturn');
	}

	public function hookDisplayProductComparison()
	{
		return $this->_processHook('displayProductComparison');
	}

	public function hookDisplayShoppingCartFooter()
	{
		return $this->_processHook('displayShoppingCartFooter');
	}

	public function hookDisplayContentBottom()
	{
		return $this->_processHook('displayContentBottom');
	}

	public function hookDisplayFootNav()
	{
		return $this->_processHook('displayFootNav');
	}

	public function hookDisplayFooterTop()
	{
		return $this->_processHook('displayFooterTop');
	}

	public function hookDisplayFooterBottom()
	{
		return $this->_processHook('displayFooterBottom');
	}

	public function hookdisplayHomeTab($params)
	{
		return $this->display(__FILE__, 'htab.tpl', $this->getCacheId($this->name.'-htab'));
	}

	public function hookdisplayHomeTabContent($params)
	{
		return $this->_processHook('displayHomeTabContent');
	}

	public function displayFootNav()
	{
		return $this->_processHook('displayFootNav');
	}

	public function hookActionShopDataDuplication($params)
	{
		//select all group
		$listGroupId = LeoManageWidgetGroup::getAllGroupId((int)$params['old_id_shop']);
		foreach ($listGroupId as $groupId)
		{
			$group = new LeoManageWidgetGroup($groupId);
			$oldID = $group->id;
			$group->id_shop = (int)$params['new_id_shop'];
			$group->id = 0;
			if ($group->add())
			{
				$columns = LeoManageWidgetColumn::getAllColumn(' AND `id_group` = '.$oldID, (int)$params['old_id_shop']);
				if ($columns)
					foreach ($columns as $columnID)
					{
						$column = new LeoManageWidgetColumn($columnID['id_column']);
						$oldColumnId = $column->id;
						$column->id = 0;
						$column->id_group = $group->id;
						$column->id_shop = (int)$params['new_id_shop'];
						$column->add();
						$rows = LeoManagerWidgetContent::getAllRowColumn(' AND `id_column` = '.$oldColumnId, (int)$params['old_id_shop']);
						if ($rows)
							foreach ($rows as $rowID)
							{
								$row = new LeoManagerWidgetContent($rowID['id_content']);
								$row->id = 0;
								$row->id_column = $column->id;
								$row->id_shop = (int)$params['new_id_shop'];
								$row->add();
							}
					}
			}
		}

		$this->clearHookCache();
	}

	public function clearHookCache()
	{
		return true;
		//$template, $cache_id
//		$this->_clearCache('group.tpl', $this->name);
	}

	public function hookCategoryAddition($params)
	{
		$this->clearHookCache();
	}

	public function hookCategoryUpdate($params)
	{
		$this->clearHookCache();
	}

	public function hookCategoryDeletion($params)
	{
		$this->clearHookCache();
	}

	public function hookAddProduct($params)
	{
		$this->clearHookCache();
	}

	public function hookUpdateProduct($params)
	{
		$this->clearHookCache();
	}

	public function hookDeleteProduct($params)
	{
		$this->clearHookCache();
	}
	
	public function setParams($hook_name)
	{
		$params = array();
		
		$params['hook'] = $hook_name;
		$controller = $params['controller'] = Tools::getValue('controller');

		if ($controller == 'category')
		{
			# validate module
			$params['id'] = 'cateogry_id_'.Tools::getValue('id_category');
		}
		elseif ($controller == 'product')
		{
			# validate module
			$params['id'] = 'product_id_'.Tools::getValue('id_product');
		}
		elseif ($controller == 'cms')
		{
			# validate module
			$params['id'] = 'cms_id_'.Tools::getValue('id_cms');
		}
		
		Configuration::updateValue('LEO_CURRENT_RANDOM_CACHE', '0');
		if ($params)
		{
			# validate module
			$this->cache_param = $params;
		}
	}

	/**
	 * use this code
	 * Configuration::updateValue('LEO_CURRENT_RANDOM_CACHE', '1');
	 * where you want to have RANDOM cache
	 */
	protected function getCacheId($name = null)
	{
		$cache_array = array();
		$cache_array[] = $name !== null ? $name : $this->name;
		
		if (isset($this->cache_param) && $this->cache_param)
		{
			if (isset($this->cache_param['controller']) && $this->cache_param['controller'])
				$cache_array[] = $this->cache_param['controller'];
			if (isset($this->cache_param['id']) && $this->cache_param['id'])
				$cache_array[] = $this->cache_param['id'];
			if (isset($this->cache_param['hook']) && $this->cache_param['hook'])
				$cache_array[] = $this->cache_param['hook'];
			
			// save to next time
			if (Configuration::get('LEO_CURRENT_RANDOM_CACHE') == 1)
			{
				$random_cache = Configuration::get('LEO_RANDOM_CACHE');
				if (!$random_cache)
				{
					# validate module
					$random_cache = new stdClass();
				}
				else
				{
					# validate module
					$random_cache = Tools::jsonDecode($random_cache);
				}
				$key = implode('|', $cache_array);
				$random_cache->$key = date('Ymd');
				$leo_random_cache = Tools::jsonEncode($random_cache);
				Configuration::updateValue('LEO_RANDOM_CACHE', $leo_random_cache);
				
			}
			// Check RANDOM PRODUCT
			if ($random_cache = Configuration::get('LEO_RANDOM_CACHE'))
			{
				$key = implode('|', $cache_array);
				$value = date('Ymd');
				$random_cache = Tools::jsonDecode($random_cache);
				if (isset($random_cache->$key) && $random_cache->$key == $value)
				{
					// cache in one day
					$random = date('Ymd').'_'.rand(1, LeomanagewidgetsHelper::NUMBER_CACHE_FILE);
					$cache_array[] = 'random_'.$random;
				}
			}
			
		}
		if (Configuration::get('PS_SSL_ENABLED'))
			$cache_array[] = (int)Tools::usingSecureMode();
		if (Shop::isFeatureActive())
			$cache_array[] = (int)$this->context->shop->id;
		if (Group::isFeatureActive())
			$cache_array[] = (int)Group::getCurrent()->id;
		if (Language::isMultiLanguageActivated())
			$cache_array[] = (int)$this->context->language->id;
		if (Currency::isMultiCurrencyActivated())
			$cache_array[] = (int)$this->context->currency->id;
		$cache_array[] = (int)$this->context->country->id;
		return implode('|', $cache_array);
	}
	
	public function hookActionAdminPerformanceControllerAfter($params)
	{
		if ((bool)Tools::getValue('empty_smarty_cache'))
		{
			# click to clear_cache button
			Configuration::updateValue('LEO_CURRENT_RANDOM_CACHE', 0);
			Configuration::updateValue('LEO_RANDOM_CACHE', '');
		}
	}

}