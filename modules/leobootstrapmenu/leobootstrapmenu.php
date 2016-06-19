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

include_once(_PS_MODULE_DIR_.'leobootstrapmenu/classes/Btmegamenu.php');
include_once(_PS_MODULE_DIR_.'leobootstrapmenu/libs/Helper.php');
if (file_exists(_PS_MODULE_DIR_.'leotempcp/classes/widgetbase.php'))
	require_once( _PS_MODULE_DIR_.'leotempcp/classes/widgetbase.php' );
if (file_exists(_PS_MODULE_DIR_.'leotempcp/classes/widget.php'))
	require_once( _PS_MODULE_DIR_.'leotempcp/classes/widget.php' );

class Leobootstrapmenu extends Module
{
	private $_html = '';
	private $configs = '';
	protected $params = null;
	public $_languages;
	public $_defaultFormLanguage;
	public $base_config_url;
	public $widget;
	public $theme_name;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->name = 'leobootstrapmenu';
		$this->tab = 'front_office_features';
		$this->version = '3.0.0';
		$this->author = 'LeoTheme';
		$this->need_instance = 0;
		$this->bootstrap = true;

		$this->secure_key = Tools::encrypt($this->name);

		parent::__construct();
		if (Module::isInstalled($this->name))
		{
			$this_version = Configuration::get('LEO_MENUSIDEBAR_VERSION') ? Configuration::get('LEO_MENUSIDEBAR_VERSION') : '';
			$this->checkVersion($this_version);
		}
		$current_index = AdminController::$currentIndex;

		$this->base_config_url = $current_index.'&configure='.$this->name.'&token='.Tools::getValue('token');

		$this->displayName = $this->l('Leo Bootstrap Megamenu');
		$this->description = $this->l('Leo Bootstrap Megamenu Support Leo Framework Version 3.0.0');
		$this->languages();
		$this->theme_name = Context::getContext()->shop->getTheme();
		$this->img_path = _PS_ALL_THEMES_DIR_.$this->theme_name.'/img/modules/'.$this->name.'/icons/';

		$this->widget = new LeoTempcpWidget();
	}

	/**
	 *
	 */
	public function languages()
	{
		//global $cookie;
		$cookie = $this->context->cookie;
		$allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		if ($allow_employee_form_lang && !$cookie->employee_form_lang)
			$cookie->employee_form_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		$use_lang_from_cookie = false;
		$this->_languages = Language::getLanguages(false);
		if ($allow_employee_form_lang)
			foreach ($this->_languages as $lang)
				if ($cookie->employee_form_lang == $lang['id_lang'])
					$use_lang_from_cookie = true;
		if (!$use_lang_from_cookie)
			$this->_defaultFormLanguage = (int)Configuration::get('PS_LANG_DEFAULT');
		else
			$this->_defaultFormLanguage = (int)$cookie->employee_form_lang;
	}

	public function install()
	{
		/* Adds Module */
		if (parent::install() &&
				$this->registerHook('topNavigation') && Configuration::updateValue('btmenu_iscache', 1) && Configuration::updateValue('btmenu_cachetime', 24) && Configuration::updateValue('LEO_MEGAMENU_CAVAS', 0))
		{
			$res = true;
			$res = $this->createTables();
			return (bool)$res;
		}

		return false;
	}

	/**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{
		if (parent::uninstall())
		{
			return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'btmegamenu`, `'._DB_PREFIX_.'btmegamenu_lang`, `'._DB_PREFIX_.'btmegamenu_shop`');
		}
		return false;
	}

	/**
	 * Creates tables
	 */
	protected function createTables()
	{
		if ($this->installDataSample())
			return true;
		$res = 1;
		include_once( dirname(__FILE__).'/install/install.php' );
		return $res;
	}

	private function installDataSample()
	{
		if (!file_exists(_PS_MODULE_DIR_.'leotempcp/libs/DataSample.php'))
			return false;
		require_once( _PS_MODULE_DIR_.'leotempcp/libs/DataSample.php' );

		$sample = new Datasample(1);
		return $sample->processImport($this->name);
	}

	/**
	 * render content info
	 */
	public function getContent()
	{
//        $resultCheck = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT `id_btmegamenu` as id FROM `' . _DB_PREFIX_ . 'btmegamenu_shop` WHERE `id_btmegamenu` = 1 AND `id_shop`=' . (int) ($this->context->shop->id));
//        if ($resultCheck["id"] != 1){
//            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'btmegamenu_shop`(`id_btmegamenu`,`id_shop`) VALUES( 1, '.(int)$this->context->shop->id.' )');
//        }
		$output = '';
		$this->_html .= $this->headerHTML();
		$this->_html .= '<h2>'.$this->displayName.'.</h2>';

		/* update tree megamenu positions */
		if (Tools::getValue('doupdatepos') && Tools::isSubmit('updatePosition'))
		{
			$list = Tools::getValue('list');
			$root = 1;
			$child = array();
			foreach ($list as $id => $parent_id)
			{
				if ($parent_id <= 0)
				{
					# validate module
					$parent_id = $root;
				}
				$child[$parent_id][] = $id;
			}
			$res = true;
			foreach ($child as $id_parent => $menus)
			{
				$i = 0;
				foreach ($menus as $id_btmegamenu)
				{
					$res &= Db::getInstance()->execute('
                        UPDATE `'._DB_PREFIX_.'btmegamenu` SET `position` = '.(int)$i.', id_parent = '.(int)$id_parent.' 
                        WHERE `id_btmegamenu` = '.(int)$id_btmegamenu
					);
					$i++;
				}
			}
			$this->clearCache();
			die($this->l('Update Positions Done'));
		}

		if (Tools::getValue('show_cavas') && Tools::isSubmit('updatecavas'))
		{
			$show = Tools::getValue('show') ? Tools::getValue('show') : 0;
			if (Configuration::updateValue('LEO_MEGAMENU_CAVAS', $show))
			{
				$this->clearCache();
				die($this->l('Update Done'));
			}
			else
				die($this->l('Can not Update'));
		}

		/* delete megamenu item */
		if (Tools::getValue('dodel'))
		{
			$obj = new Btmegamenu((int)Tools::getValue('id_btmegamenu'));
			$res = $obj->delete();
			$this->clearCache();
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		if (Tools::isSubmit('save'.$this->name) && Tools::isSubmit('active'))
		{
			// if( Tools::getValue('type') == 'url' && !Tools::getValue('url')){
			//              $errors[] = $this->l('Account details are required.');
			//          }
			//			if (!isset($errors) AND !sizeof($errors)){
			if ($id_btmegamenu = Tools::getValue('id_btmegamenu'))
			{
				# validate module
				$megamenu = new Btmegamenu((int)$id_btmegamenu);
			}
			else
			{
				# validate module
				$megamenu = new Btmegamenu();
			}

			$keys = LeoBtmegamenuHelper::getConfigKey(false);
			$post = LeoBtmegamenuHelper::getPost($keys, false);
			$keys = LeoBtmegamenuHelper::getConfigKey(true);
			$post += LeoBtmegamenuHelper::getPost($keys, true);

			$megamenu->copyFromPost($post);
			$megamenu->id_shop = $this->context->shop->id;

			if ($megamenu->type && $megamenu->type != 'html' && Tools::getValue($megamenu->type.'_type'))
			{
				# validate module
				$megamenu->item = Tools::getValue($megamenu->type.'_type');
			}
			$url_default = '';
			foreach ($megamenu->url as $menu_url)
			{
				if ($menu_url)
				{
					$url_default = $menu_url;
					break;
				}
			}
			if ($url_default)
				foreach ($megamenu->url as &$menu_url)
					if (!$menu_url)
						$menu_url = $url_default;

			if ($megamenu->validateFields(false) && $megamenu->validateFieldsLang(false))
			{
				$megamenu->save();
				if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name']))
				{
					$this->checkFolderIcon();
					if (ImageManager::validateUpload($_FILES['image']))
						return false;
					elseif (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['image']['tmp_name'], $tmp_name))
						return false;
					elseif (!ImageManager::resize($tmp_name, $this->img_path.$_FILES['image']['name']))
						return false;
					unlink($tmp_name);
					$megamenu->image = $_FILES['image']['name'];
					$megamenu->save();
				}
				else if (Tools::getIsset('delete_icon'))
				{
					if ($megamenu->image)
					{
						unlink($this->img_path.$megamenu->image);
						$megamenu->image = '';
						$megamenu->save();
					}
				}
				Tools::redirectAdmin(AdminController::$currentIndex.'&configure=leobootstrapmenu&save'.$this->name.'&token='.Tools::getValue('token').'&id_btmegamenu='.$megamenu->id);
			}
			else
			{
				# validate module
				$errors = array();
				$errors[] = $this->l('An error occurred while attempting to save.');
			}
//			}
			if (isset($errors) && count($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else
			{
				$this->clearCache();
				$output .= $this->displayConfirmation($this->l('Settings updated.'));
			}
		}

		return $output.$this->displayForm();
	}

	/**
	 * show megamenu item configuration.
	 */
	protected function showFormSetting()
	{
		$this->context->controller->addJS(__PS_BASE_URI__.'modules/leobootstrapmenu/assets/admin/jquery.nestable.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'modules/leobootstrapmenu/assets/admin/form.js');

		$this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/plugins/jquery.cookie-plugin.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/ui/jquery.ui.tabs.min.js');
		$this->context->controller->addCss(__PS_BASE_URI__.'js/jquery/ui/themes/base/jquery.ui.tabs.css');
		$this->context->controller->addCss(__PS_BASE_URI__.'modules/leobootstrapmenu/assets/admin/form.css');
//		$action_widget = $this->base_config_url.'&widgets=1';

		$this->widget->loadEngines();
//		$widget = $this->widget;

		$id_lang = $this->context->language->id;
		$id_btmegamenu = (int)Tools::getValue('id_btmegamenu');
		$obj = new Btmegamenu($id_btmegamenu);
		$tree = $obj->getTree();
		$categories = LeoBtmegamenuHelper::getCategories();
		$manufacturers = Manufacturer::getManufacturers(false, $id_lang, true);
		$suppliers = Supplier::getSuppliers(false, $id_lang, true);
		$cmss = CMS::listCms($this->context->language->id, false, true);
		$menus = $obj->getDropdown(null, $obj->id_parent);

		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

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

		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Create New MegaMenu Item.'),
			),
			'input' => array(
				array(
					'type' => 'hidden',
					'label' => $this->l('Megamenu ID'),
					'name' => 'id_btmegamenu',
					'default' => 0,
				),
				array(
					'type' => 'text',
					'label' => $this->l('Title:'),
					'name' => 'title',
					'value' => true,
					'lang' => true,
					'default' => '',
				),
				array(
					'type' => 'text',
					'label' => $this->l('Sub Title:'),
					'lang' => true,
					'name' => 'text',
					'cols' => 40,
					'rows' => 10,
					'default' => '',
				),
				array(
					'type' => 'select',
					'label' => $this->l('Parent ID'),
					'name' => 'id_parent',
					'options' => array('query' => $menus,
						'id' => 'id',
						'name' => 'title'),
					'default' => 'url',
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Is Active'),
					'name' => 'active',
					'values' => $soption,
					'default' => '1',
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Show Title'),
					'name' => 'show_title',
					'values' => $soption,
					'default' => '1',
				),
				array(
					'type' => 'select',
					'label' => $this->l('Menu Type'),
					'name' => 'type',
					'id' => 'menu_type',
					'desc' => $this->l('Select a menu link type and fill data for following input'),
					'options' => array('query' => array(
							array('id' => 'url', 'name' => $this->l('Url')),
							array('id' => 'category', 'name' => $this->l('Category')),
							array('id' => 'product', 'name' => $this->l('Product')),
							array('id' => 'manufacture', 'name' => $this->l('Manufacture')),
							array('id' => 'supplier', 'name' => $this->l('Supplier')),
							array('id' => 'cms', 'name' => $this->l('Cms')),
							array('id' => 'html', 'name' => $this->l('Html'))
						),
						'id' => 'id',
						'name' => 'name'),
					'default' => 'url',
				),
				array(
					'type' => 'text',
					'label' => $this->l('Product ID'),
					'name' => 'product_type',
					'id' => 'product_type',
					'class' => 'menu-type-group',
					'default' => '',
				),
				array(
					'type' => 'select',
					'label' => $this->l('CMS Type'),
					'name' => 'cms_type',
					'id' => 'cms_type',
					'options' => array('query' => $cmss,
						'id' => 'id_cms',
						'name' => 'meta_title'),
					'default' => '',
					'class' => 'menu-type-group',
				),
				array(
					'type' => 'text',
					'label' => $this->l('URL'),
					'name' => 'url',
					'id' => 'url_type',
					'required' => true,
					'lang' => true,
					'class' => 'url-type-group-lang',
					'default' => '',
				),
				array(
					'type' => 'select',
					'label' => $this->l('Category Type'),
					'name' => 'category_type',
					'id' => 'category_type',
					'options' => array('query' => $categories,
						'id' => 'id_category',
						'name' => 'name'),
					'default' => '',
					'class' => 'menu-type-group',
				),
				array(
					'type' => 'select',
					'label' => $this->l('Manufacture Type'),
					'name' => 'manufacture_type',
					'id' => 'manufacture_type',
					'options' => array('query' => $manufacturers,
						'id' => 'id_manufacturer',
						'name' => 'name'),
					'default' => '',
					'class' => 'menu-type-group',
				),
				array(
					'type' => 'select',
					'label' => $this->l('Supplier Type'),
					'name' => 'supplier_type',
					'id' => 'supplier_type',
					'options' => array('query' => $suppliers,
						'id' => 'id_supplier',
						'name' => 'name'),
					'default' => '',
					'class' => 'menu-type-group',
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('HTML Type'),
					'name' => 'content_text',
					'desc' => $this->l('This menu is only for display content,PLease do not select it for menu level 1'),
					'lang' => true,
					'default' => '',
					'autoload_rte' => true,
					'class' => 'menu-type-group-lang',
				),
				array(
					'type' => 'select',
					'label' => $this->l('Target Open'),
					'name' => 'target',
					'options' => array('query' => array(
							array('id' => '_self', 'name' => $this->l('Self')),
							array('id' => '_blank', 'name' => $this->l('Blank')),
							array('id' => '_parent', 'name' => $this->l('Parent')),
							array('id' => '_top', 'name' => $this->l('Top'))
						),
						'id' => 'id',
						'name' => 'name'),
					'default' => '_self',
				),
				array(
					'type' => 'text',
					'label' => $this->l('Menu Class'),
					'name' => 'menu_class',
					'display_image' => true,
					'default' => ''
				),
				array(
					'type' => 'text',
					'label' => $this->l('Menu Icon Class'),
					'name' => 'icon_class',
					'display_image' => true,
					'default' => '',
					'desc' => $this->l('The module integrated with FontAwesome').'. '
					.$this->l('Check list of icons and class name in here')
					.' <a href="http://fontawesome.io/" target="_blank">http://fontawesome.io/</a> or your icon class'
				),
				array(
					'type' => 'file',
					'label' => $this->l('Or Menu Icon Image'),
					'name' => 'image',
					'display_image' => true,
					'default' => '',
					'desc' => $this->l('Use image icon if no use con Class'),
					'thumb' => '',
					'title' => $this->l('Icon Preview'),
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Group Submenu'),
					'name' => 'is_group',
					'values' => $soption,
					'default' => '0',
					'desc' => $this->l('Group all sub menu to display in same level')
				),
				array(
					'type' => 'text',
					'label' => $this->l('Column'),
					'name' => 'colums',
					'values' => $soption,
					'default' => '1',
					'desc' => $this->l('Set each sub menu item as column')
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'button btn btn-danger'
			)
		);

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		foreach (Language::getLanguages(false) as $lang)
			$helper->languages[] = array(
				'id_lang' => $lang['id_lang'],
				'iso_code' => $lang['iso_code'],
				'name' => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
			);

		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'save'.$this->name;
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues($obj),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		$live_editor_url = AdminController::$currentIndex.'&configure='.$this->name.'&liveeditor=1&token='.Tools::getAdminTokenLite('AdminModules');

		$action = AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules');
		$helper->toolbar_btn = array(
//			'ssave' =>
//				array(
//				'desc' => $this->l('Save'),
//				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
//				),
			'back' =>
			array(
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			)
		);

		$html = $this->_html.'<div class="col-lg-12"> <div class="alert alert-info clearfix"><div class="pull-right">Using <a href="'.$live_editor_url.'" class="btn btn-danger"> '
				.$this->l('Live Edit Tools').'</a> '.$this->l('To Make Rich Content For Megamenu').'</div></div></div>';

		$output = $html.'
                 <ul class="nav nav-tabs clearfix">
                  <li class="active"><a href="#megamenu" data-toggle="tab">'.$this->l('Megamenu').'</a></li>
                </ul>

 
            <div class="tab-content clearfix">
              <div class="tab-pane active" id="megamenu">
        ';
		$show_cavas = Configuration::get('LEO_MEGAMENU_CAVAS');
		$addnew = AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure='.$this->name.'&tab_module=front_office_features&module_name='.$this->name;
		$output .= '<div class="col-md-4"><div class="panel panel-default"><h3 class="panel-title">'.$this->l('Tree Megamenu Management').'</h3>
				<div class="panel-content">'.$this->l('To sort orders or update parent-child, you drap and drop expected menu, then click to Update button to Save')
				.'<hr><p><input type="button" value="'.$this->l('New Menu Item').'" id="addcategory" data-loading-text="'.$this->l('Processing ...').'" class="btn btn-danger" name="addcategory">
					<a   href="'.Context::getContext()->link->getAdminLink('AdminLeotempcpWidgets').'" class="leo-modal-action btn btn-modeal btn-success btn-info">'.$this->l('List Widget').'</a></p>
					<hr><p><input type="button" value="'.$this->l('Update').'" id="show_cavas" data-loading-text="'.$this->l('Processing ...').'" class="btn btn-info" ></p>
						<label>'.$this->l('Show Cavas').'</label>
						<select name="show_cavas" class="show_cavas">
							<option value="1" '.((isset($show_cavas) && $show_cavas == 1) ? 'checked' : null).'>'.$this->l('Yes').'</option>
							<option value="0" '.((isset($show_cavas) && $show_cavas == 0) ? 'checked' : null).'>'.$this->l('No').'</option>
						</select>
					<hr><p><input type="button" value="'.$this->l('Update Positions').'" id="serialize" data-loading-text="'.$this->l('Processing ...').'" class="btn btn-danger" name="serialize"></p><hr>'.$tree.'</div></div></div>
				<div class="col-md-8">'.$helper->generateForm($this->fields_form).'</div>
				<script type="text/javascript">var addnew ="'.$addnew.'"; var action="'.$action.'";$("#content").PavMegaMenuList({action:action,addnew:addnew});</script>';
		$output .= '</div>';
		$output .= '</div><script>$(\'#myTab a[href="#profile"]\').tab(\'show\')</script>';
		return $output;
	}

	public function getConfigFieldsValues($obj)
	{
		$languages = Language::getLanguages(false);
		$fields_values = array();
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
		$this->image_base_url = Tools::htmlentitiesutf8($protocol.$_SERVER['HTTP_HOST'].__PS_BASE_URI__).'themes/'.$this->theme_name.'/img/modules/leobootstrapmenu/icons/';
//		$a = array();

		foreach ($this->fields_form as $k => $f)
		{
			foreach ($f['form']['input'] as $j => $input)
			{

				if (isset($obj->{trim($input['name'])}))
				{
					$data = $obj->{trim($input['name'])};

					if ($input['name'] == 'image' && $data)
					{
						$thumb = $this->image_base_url.$data;
						$this->fields_form[$k]['form']['input'][$j]['thumb'] = $thumb;
					}

					if (isset($input['lang']))
					{
						foreach ($languages as $lang)
						{
							# validate module
							$fields_values[$input['name']][$lang['id_lang']] = isset($data[$lang['id_lang']]) ? $data[$lang['id_lang']] : $input['default'];
						}
					}
					else
					{
						# validate module
						$fields_values[$input['name']] = isset($data) ? $data : $input['default'];
					}
				}
				else
				{
					if (isset($input['lang']))
					{
						foreach ($languages as $lang)
						{
							$v = Tools::getValue('title', Configuration::get($input['name'], $lang['id_lang']));
							$fields_values[$input['name']][$lang['id_lang']] = $v ? $v : $input['default'];
						}
					}
					else
					{
						$v = Tools::getValue($input['name'], Configuration::get($input['name']));
						$fields_values[$input['name']] = $v ? $v : $input['default'];
					}

					if ($input['name'] == $obj->type.'_type')
					{
						# validate module
						$fields_values[$input['name']] = $obj->item;
					}
				}
			}
		}

		return $fields_values;
	}

	/**
	 * render menu tree using for editing
	 */
	protected function ajxgenmenu()
	{
		$parent = '1';
		$params = array('params' => array());
		/* unset mega menu configuration */
		if (Tools::getValue('doreset'))
		{
			# validate module
			Configuration::updateValue('LEO_MEGAMENU_PARAMS', '');
		}

		$params['params'] = Configuration::get('LEO_MEGAMENU_PARAMS');
		if (isset($params['params']) && !empty($params['params']))
		{
			# validate module
			$params['params'] = Tools::jsonDecode($params['params']);
		}
		$obj = new Btmegamenu($parent);
		$tree = $obj->getFrontTree(1, true, $params['params']);
		echo ' <div class="navbar navbar-default">
                    <nav id="mainmenutop" class="megamenu" role="navigation">
                        <div class="navbar-header">
                        <div class="collapse navbar-collapse navbar-ex1-collapse">
                                '.$tree.'
                         </div></div>
                    </nav>
            </div>';
	}

	/**
	 * Ajax Menu Information Action
	 */
	public function ajxmenuinfo()
	{
		if (Tools::getValue('params'))
		{
			$params = trim(html_entity_decode(Tools::getValue('params')));
//			$a = Tools::jsonDecode(($params));
			Configuration::updateValue('LEO_MEGAMENU_PARAMS', $params, true);
			$this->clearCache();
		}
		return $this->ajxgenmenu();
	}

	/**
	 * show live editor tools 
	 */
	protected function showLiveEditorSetting()
	{
		$this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/ui/jquery.ui.dialog.min.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/ui/jquery.ui.draggable.min.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/ui/jquery.ui.droppable.min.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'modules/leobootstrapmenu/assets/admin/form.js');
		$this->context->controller->addCss(__PS_BASE_URI__.'modules/leobootstrapmenu/assets/admin/liveeditor.css');
		$this->context->controller->addJS(__PS_BASE_URI__.'modules/leobootstrapmenu/assets/admin/liveeditor.js');
		$tcss = _PS_ROOT_DIR_.'/themes/'.$this->context->shop->getTheme().'/css/modules/leobootstrapmenu/megamenu.css';

		if (file_exists($tcss))
		{
			# validate module
			$this->context->controller->addCss(_THEMES_DIR_.$this->context->shop->getTheme().'/css/modules/leobootstrapmenu/megamenu.css');
		}
		else
		{
			# validate module
			$this->context->controller->addCss(__PS_BASE_URI__.'modules/leobootstrapmenu/megamenu.css');
		}

		$liveedit_action = $this->base_config_url.'&liveeditor=1&do=livesave';
		$action_backlink = $this->base_config_url;
		$action_widget = _MODULE_DIR_.$this->name.'/widget.php';
		$action_addwidget = $this->base_config_url.'&liveeditor=1&do=addwidget';
		$ajxgenmenu = $this->base_config_url.'&liveeditor=1&do=ajxgenmenu';
		$ajxmenuinfo = $this->base_config_url.'&liveeditor=1&do=ajxmenuinfo';
		$id_shop = $this->context->shop->id;
		$shop = Shop::getShop($id_shop);
		if (!empty($shop))
			$live_site_url = $shop['uri'];
		else
			$live_site_url = __PS_BASE_URI__;
		$model = $this->widget;
		$widgets = $model->getWidgets($id_shop);
		$type_menu = array('carousel', 'categoriestabs', 'manucarousel', 'map', 'producttabs', 'tab', 'accordion', 'specialcarousel');
		foreach ($widgets as $key => $widget)
		{
			if (in_array($widget['type'], $type_menu))
				unset($widgets[$key]);
		}
		ob_start();
		$this_module = $this;
		require_once ( dirname(__FILE__).'/liveeditor.php' );
		$output = ob_get_contents();
		ob_end_clean();

		# validate module
		unset($liveedit_action);
		unset($action_backlink);
		unset($action_widget);
		unset($action_addwidget);
		unset($ajxgenmenu);
		unset($ajxmenuinfo);
		unset($live_site_url);
		unset($this_module);

		return $output;
	}

	private function displayForm()
	{
		if (Tools::getValue('liveeditor'))
		{

			if (Tools::getValue('do'))
			{
				switch (Tools::getValue('do'))
				{
					case 'ajxmenuinfo':
						echo $this->ajxmenuinfo();
						break;
					case 'ajxgenmenu':
						echo $this->ajxgenmenu();
						break;
					default:
						break;
				}
				die;
			}
			else
			{
				# validate module
				return $this->showLiveEditorSetting();
			}
		}
		else
		{
			# validate module
			return $this->showFormSetting();
		}
	}

	/**
	 *
	 */
	private function postProcess()
	{
//		$errors = array();
	}

	public function hookDisplayTopColumn()
	{
		return $this->hookDisplayTop();
	}

	public function hookDisplayHeaderRight()
	{
		return $this->hookDisplayTop();
	}

	public function hookDisplaySlideshow()
	{
		return $this->hookDisplayTop();
	}

	public function hookTopNavigation()
	{
		return $this->hookDisplayTop();
	}

	public function hookDisplayPromoteTop()
	{
		return $this->hookDisplayTop();
	}

	public function hookDisplayBottom()
	{
		return $this->hookDisplayTop();
	}

	public function hookDisplayContentBottom()
	{
		return $this->hookDisplayTop();
	}

	public function hookRightColumn()
	{
		return $this->hookDisplayTop();
	}

	public function hookLeftColumn()
	{
		return $this->hookDisplayTop();
	}

	public function hookdisplayHome()
	{
		return $this->hookDisplayTop();
	}

	public function hookFooter()
	{
		return $this->hookDisplayTop();
	}

	/**
	 * Display Bootstrap MegaMenu
	 */
	public function hookDisplayTop()
	{
		$this->context->controller->addCSS($this->_path.'megamenu.css', 'all');
		$tpl = 'views/templates/hook/megamenu.tpl';
		if (!$this->isCached($tpl, $this->getCacheId()))
		{
			$link = new Link();
			$params = array();
			$params['params'] = Configuration::get('LEO_MEGAMENU_PARAMS');
			$show_cavas = Configuration::get('LEO_MEGAMENU_CAVAS');
			$current_link = $link->getPageLink('', false, $this->context->language->id);
			if (isset($params['params']) && !empty($params['params']))
			{
				# validate module
				$params['params'] = Tools::jsonDecode($params['params']);
			}

			$obj = new Btmegamenu();
			$obj->setModule($this);
			$boostrapmenu = $obj->getFrontTree(1, false, $params['params']);
			$this->smarty->assign('boostrapmenu', $boostrapmenu);
			$this->smarty->assign('current_link', $current_link);
			$this->smarty->assign('show_cavas', $show_cavas);
			return $this->display(__FILE__, $tpl, $this->getCacheId());
		}
		return $this->display(__FILE__, $tpl, $this->getCacheId());
	}

	protected function getCacheId($name = null, $hook = '')
	{
		$cache_array = array(
			$name !== null ? $name : $this->name,
			$hook,
			date('Ymd'),
			(int)Tools::usingSecureMode(),
			(int)$this->context->shop->id,
			(int)Group::getCurrent()->id,
			(int)$this->context->language->id,
			(int)$this->context->currency->id,
			(int)$this->context->country->id,
			(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
		);
		return implode('|', $cache_array);
	}

	/**
	 * render widgets
	 */
	public function renderwidget($id_shop)
	{
		if (!$id_shop)
			$id_shop = Context::getContext()->shop->id;
		$widgets = Tools::getValue('widgets');

		$widgets = explode('|wid-', '|'.$widgets);
		if (!empty($widgets))
		{
			unset($widgets[0]);
			$model = $this->widget;
			$model->setTheme(Context::getContext()->shop->getTheme());
			$model->langID = $this->context->language->id;
			$model->loadWidgets($id_shop);
			$model->loadEngines();
			$output = '';
			foreach ($widgets as $wid)
			{
				$content = $model->renderContent($wid);
				$output .= $this->getWidgetContent($wid, $content['type'], $content['data']);
			}
			echo $output;
		}
		die;
	}

	/**
	 *
	 */
	public function getWidgetContent($id, $type, $data, $show_widget_id = 1)
	{
		$data['id_lang'] = $this->context->language->id;

		$this->smarty->assign($data);
		$id_text = '';
		if ($show_widget_id)
			$id_text = ' id="wid-'.$id.'"';
		$output = '<div class="leo-widget"'.$id_text.'>';
		$output .= $this->display(__FILE__, 'views/widgets/widget_'.$type.'.tpl');
		$output .= '</div>';
		return $output;
	}

	/**
	 *
	 */
	public function clearCache()
	{
		$tpl = 'views/templates/hook/megamenu.tpl';
		$this->_clearCache($tpl);
	}

	/**
	 *
	 */
	public function headerHTML()
	{
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;
		$this->context->controller->addJqueryUI('ui.sortable');
		$html = '';
		return $html;
	}

	public function checkVersion($version)
	{
		$versions = array(
			'3.0.0'
		);
		if ($version && $version == $versions[count($versions) - 1])
			return;
		foreach ($versions as $ver)
		{
			if (!$version || ($version && $version < $ver))
			{
				if ($this->checktable())
				{
					$checkcolumn = Db::getInstance()->executeS("
        				SELECT * FROM INFORMATION_SCHEMA.COLUMNS
        					WHERE TABLE_SCHEMA = '"._DB_NAME_."'
        						AND TABLE_NAME='"._DB_PREFIX_."btmegamenu_lang'
        						AND COLUMN_NAME ='url'
    				");
					if (count($checkcolumn) < 1)
					{
						Db::getInstance()->execute('
    						ALTER TABLE `'._DB_PREFIX_.'btmegamenu_lang` 
    							ADD `url` varchar(255) DEFAULT NULL');
						$menus = Db::getInstance()->executeS('SELECT `id_btmegamenu`,`id_parent`,`url` FROM `'._DB_PREFIX_.'btmegamenu`');
						if ($menus)
							foreach ($menus as $menu)
							{
								if ($menu['id_parent'] != 0)
								{
									$megamenu = new Btmegamenu((int)$menu['id_btmegamenu']);
									foreach ($megamenu->url as &$url)
									{
										$url = $menu['url'] ? $menu['url'] : '';
										# validate module
										$validate_module = $url;
										unset($validate_module);
									}
									$megamenu->update();
								}
							}
						Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'btmegamenu` DROP `url`');
						Configuration::updateValue('LEO_MEGAMENU_VERSION', $ver);
					}
				}
			}
		}
	}

	public function checktable()
	{
		$checktable = Db::getInstance()->executeS("
						SELECT * FROM INFORMATION_SCHEMA.COLUMNS
						WHERE TABLE_SCHEMA = '"._DB_NAME_."'
						AND TABLE_NAME='"._DB_PREFIX_."btmegamenu_lang'
				");
		if (count($checktable) < 1)
			return false;
		else
			return true;
	}

	public function checkFolderIcon()
	{
		if (file_exists($this->img_path) && is_dir($this->img_path))
			return;
		if (!file_exists($this->img_path) && !is_dir($this->img_path))
		{
			@mkdir(_PS_ALL_THEMES_DIR_.$this->theme_name.'/img/modules/', 0777, true);
			@mkdir(_PS_ALL_THEMES_DIR_.$this->theme_name.'/img/modules/'.$this->name.'/', 0777, true);
			@mkdir($this->img_path, 0777, true);
		}
	}

}