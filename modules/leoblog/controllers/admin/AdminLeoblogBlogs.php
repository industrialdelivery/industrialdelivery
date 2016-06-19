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

include_once(_PS_MODULE_DIR_.'leoblog/loader.php');

class AdminLeoblogBlogsController extends ModuleAdminControllerCore
{
	protected $max_image_size = 1048576;
	protected $position_identifier = 'id_leoblog_blog';

	public function __construct()
	{
		parent::__construct();
		$this->bootstrap = true;
		$this->table = 'leoblog_blog';
		//$this->list_id = 'id_leoblog_blog';		// must be set same value $this->table to delete multi rows
		$this->identifier = 'id_leoblog_blog';
		$this->className = 'LeoBlogBlog';
		$this->lang = true;
		$this->addRowAction('edit');
		$this->addRowAction('delete');
		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?'), 'icon' => 'icon-trash'));
		$this->fields_list = array(
			'id_leoblog_blog' => array('title' => $this->l('ID'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'meta_title' => array('title' => $this->l('Blog Title'), 'filter_key' => 'b!meta_title'),
			'title' => array('title' => $this->l('Category Title'), 'filter_key' => 'cl!title'),
			'active' => array('title' => $this->l('Displayed'), 'align' => 'center', 'active' => 'status', 'class' => 'fixed-width-sm', 'type' => 'bool', 'orderby' => false)
		);
		$this->_select .= ' cl.title ';
		$this->_join .= ' LEFT JOIN '._DB_PREFIX_.'leoblogcat c ON a.id_leoblogcat = c.id_leoblogcat
								  LEFT JOIN '._DB_PREFIX_.'leoblogcat_lang cl ON cl.id_leoblogcat=c.id_leoblogcat AND cl.id_lang=b.id_lang 
			    ';
		if (Shop::getContext() == Shop::CONTEXT_SHOP)
			$this->_join .= ' INNER JOIN `'._DB_PREFIX_.'leoblog_blog_shop` sh ON (sh.`id_leoblog_blog` = b.`id_leoblog_blog` AND sh.id_shop = '.(int)Context::getContext()->shop->id.') ';
		$this->_where = '';
		$this->_group = ' GROUP BY (a.id_leoblog_blog) ';
		$this->_orderBy = 'a.position';
	}

	public function initPageHeaderToolbar()
	{
		$link = $this->context->link;
		if (Tools::getValue('id_leoblog_blog'))
		{
			$this->page_header_toolbar_btn['save-and-preview'] = array(
				'href' => $link->getAdminLink('AdminLeoblogComments').'&id_leoblog_blog='.Tools::getValue('id_leoblog_blog'),
				'desc' => $this->l('Manage Comments'),
				'icon' => 'icon-comment icon-3x process-icon-comment'
			);

			$this->page_header_toolbar_btn['save-and-stay'] = array(
				'short' => 'SaveAndStay',
				'href' => '#',
				'desc' => $this->l('Save and stay'),
			);
		}
		return parent::initPageHeaderToolbar();
	}

	public function renderForm()
	{
		if (!$this->loadObject(true))
			if (Validate::isLoadedObject($this->object))
				$this->display = 'edit';
			else
				$this->display = 'add';

		$this->initToolbar();
		$this->initPageHeaderToolbar();

		$id_leoblogcat = (int)(Tools::getValue('id_leoblogcat'));
		$obj = new leoblogcat($id_leoblogcat);
//		$tree = $obj->getTree();	# validate module
		$obj->getTree();
		$menus = $obj->getDropdown(null, $obj->id_parent);

		if ($this->object->image)
		{
			# validate module
			$thumb = __PS_BASE_URI__.'modules/leoblog/views/img/b/'.$this->object->image;
		}
		else
		{
			# validate module
			$thumb = '';
		}

		$this->multiple_fieldsets = true;

		$this->fields_form[0]['form'] = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('Blog Form'),
				'icon' => 'icon-folder-close'
			),
			'input' => array(
				// custom template

				array(
					'type' => 'select',
					'label' => $this->l('Category'),
					'name' => 'id_leoblogcat',
					'options' => array('query' => $menus,
						'id' => 'id',
						'name' => 'title'),
					'default' => $id_leoblogcat,
				),
				array(
					'type' => 'text',
					'label' => $this->l('Meta title:'),
					'name' => 'meta_title',
					'id' => 'name', // for copyMeta2friendlyURL compatibility
					'lang' => true,
					'required' => true,
					'class' => 'copyMeta2friendlyURL',
					'hint' => $this->l('Invalid characters:').' &lt;&gt;;=#{}'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Friendly URL'),
					'name' => 'link_rewrite',
					'required' => true,
					'lang' => true,
					'hint' => $this->l('Only letters and the minus (-) character are allowed')
				),
				array(
					'type' => 'tags',
					'label' => $this->l('Tags'),
					'name' => 'tags',
					'lang' => true,
					'hint' => array(
						$this->l('Invalid characters:').' &lt;&gt;;=#{}',
						$this->l('To add "tags" click in the field, write something, and then press "Enter."')
					)
				),
				array(
					'type' => 'file',
					'label' => $this->l('Image'),
					'name' => 'image',
					'display_image' => true,
					'default' => '',
					'desc' => $this->l(''),
					'thumb' => $thumb,
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Video Code'),
					'name' => 'video_code',
					'rows' => 5,
					'cols' => 30,
					'hint' => $this->l('Enter Video Code Copying From Youtube, Vimeo').' <>;=#{}'
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Blog description'),
					'name' => 'description',
					'autoload_rte' => true,
					'lang' => true,
					'rows' => 5,
					'cols' => 30,
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Blog Content'),
					'name' => 'content',
					'autoload_rte' => true,
					'lang' => true,
					'rows' => 5,
					'cols' => 40,
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Indexation (by search engines):'),
					'name' => 'indexation',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'indexation_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'indexation_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Displayed:'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
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
					),
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right'
			)
		);

		$this->fields_form[1]['form'] = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('SEO'),
				'icon' => 'icon-folder-close'
			),
			'input' => array(
				// custom template
				array(
					'type' => 'textarea',
					'label' => $this->l('Meta description'),
					'name' => 'meta_description',
					'lang' => true,
					'cols' => 40,
					'rows' => 10,
					'hint' => $this->l('Invalid characters:').' &lt;&gt;;=#{}'
				),
				array(
					'type' => 'tags',
					'label' => $this->l('Meta keywords'),
					'name' => 'meta_keywords',
					'lang' => true,
					'hint' => array(
						$this->l('Invalid characters:').' &lt;&gt;;=#{}',
						$this->l('To add "tags" click in the field, write something, and then press "Enter."')
					)
				),
			)
		);

		/* 		if (Shop::isFeatureActive())
		  {
		  $this->fields_form['input'][] = array(
		  'type' => 'shop',
		  'label' => $this->l('Shop association:'),
		  'name' => 'checkBoxShopAsso',
		  );
		  }
		 */
		$this->tpl_form_vars = array(
			'active' => $this->object->active,
			'PS_ALLOW_ACCENTED_CHARS_URL', (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL')
		);
		$html = '
							<script type="text/javascript">
								var PS_ALLOW_ACCENTED_CHARS_URL = '.(int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL').';
							</script>
					';
		return $html.parent::renderForm();
	}

	public function renderList()
	{
		$this->toolbar_title = $this->l('Blogs Management');
		$this->toolbar_btn['new'] = array(
			'href' => self::$currentIndex.'&add'.$this->table.'&id_leoblog_blog_category='.(int)'9'.'&token='.$this->token,
			'desc' => $this->l('Add new')
		);

		return parent::renderList();
	}

	public function displayList($token = null)
	{
		/* Display list header (filtering, pagination and column names) */
		$this->displayListHeader($token);

		if (!count($this->_list))
			echo '<tr><td class="center" colspan="'.(count($this->fields_list) + 2).'">'.$this->l('No items found').'</td></tr>';

		/* Show the content of the table */
		$this->displayListContent($token);

		/* Close list table and submit button */
		$this->displayListFooter($token);
	}

	public function postProcess()
	{
		if (Tools::isSubmit('viewblog') && ($id_leoblog_blog = (int)Tools::getValue('id_leoblog_blog')) && ($blog = new LeoBlogBlog($id_leoblog_blog, $this->context->language->id)) && Validate::isLoadedObject($blog))
			$this->redirect_after = $this->getPreviewUrl($blog);

		if (Tools::isSubmit('submitAddleoblog_blog') || Tools::isSubmit('submitAddleoblog_blogAndPreview'))
		{
			parent::validateRules();
			if (count($this->errors))
				return false;

			if (!$id_leoblog_blog = (int)Tools::getValue('id_leoblog_blog'))
			{
				$blog = new LeoBlogBlog();
				$this->copyFromPost($blog, 'blog');

				if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name']))
				{
					if (!$image = $this->_uploadImage($_FILES['image'], '', ''))
						return false;
					$blog->image = $image;
				}
				$blog->id_employee = $this->context->employee->id;

				if (!$blog->add())
				{
					$this->errors[] = Tools::displayError('An error occurred while creating an object.')
							.' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
				}
				else
				{
					# validate module
					$this->updateAssoShop($blog->id);
				}
			}
			else
			{
				$blog = new LeoBlogBlog($id_leoblog_blog);
				$this->copyFromPost($blog, 'blog');
				//$folder = _LEOBLOG_BLOG_IMG_DIR_; # validate module

				if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name']))
				{
					if (file_exists(_LEOBLOG_CACHE_IMG_DIR_.'b/'.$id_leoblog_blog))
						LeoBlogHelper::rrmdir(_LEOBLOG_CACHE_IMG_DIR_.'b/'.$id_leoblog_blog);
					if (!$image = $this->_uploadImage($_FILES['image'], '', ''))
						return false;
					$blog->image = $image;
				}

				if (!$blog->update())
				{
					$this->errors[] = Tools::displayError('An error occurred while updating an object.')
							.' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
				}
				else
				{
					# validate module
					$this->updateAssoShop($blog->id);
				}
			}

			if (Tools::isSubmit('submitAddblogAndPreview'))
			{
				# validate module
				$this->redirect_after = $this->previewUrl($blog);
			}
			elseif (Tools::isSubmit('submitAdd'.$this->table.'AndStay'))
			{
				# validate module
				Tools::redirectAdmin(self::$currentIndex.'&'.$this->identifier.'='.$blog->id.'&conf=4&update'.$this->table.'&token='.Tools::getValue('token'));
			}
			else
			{
				# validate module
				Tools::redirectAdmin(self::$currentIndex.'&id_leoblogcat='.$blog->id_leoblogcat.'&conf=4&token='.Tools::getValue('token'));
			}
		}
		else
			parent::postProcess(true);
	}

	protected function _uploadImage($image, $image_w = '', $image_h = '')
	{
		$res = false;
		if (is_array($image) && (ImageManager::validateUpload($image, $this->max_image_size) === false) && ($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) && move_uploaded_file($image['tmp_name'], $tmp_name))
		{
			$type = Tools::strtolower(Tools::substr(strrchr($image['name'], '.'), 1));
			$img_name = 'b-'.Tools::strtolower(str_replace('.'.$type, '', $image['name']).'.'.$type);

			Configuration::set('PS_IMAGE_QUALITY', 'png_all');
			if (ImageManager::resize($tmp_name, _LEOBLOG_BLOG_IMG_DIR_.'b/'.$img_name, $image_w, $image_h))
				$res = true;
		}

//		if (isset($temp_name))
//				@unlink($tmp_name);
		if (!$res)
		{
			# validate module
			return false;
		}

		return $img_name;
	}

	public function setMedia()
	{
		parent::setMedia();
		$this->addJqueryUi('ui.widget');
		$this->addJqueryPlugin('tagify');
	}

	public function ajaxProcessUpdateblogPositions()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$id_leoblog_blog = (int)Tools::getValue('id_leoblog_blog');
			$id_category = (int)Tools::getValue('id_leoblog_blog_category');
			$way = (int)Tools::getValue('way');
			$positions = Tools::getValue('blog');
			if (is_array($positions))
				foreach ($positions as $key => $value)
				{
					$pos = explode('_', $value);
					if ((isset($pos[1]) && isset($pos[2])) && ($pos[1] == $id_category && $pos[2] == $id_leoblog_blog))
					{
						$position = $key;
						break;
					}
				}
			$blog = new blog($id_leoblog_blog);
			if (Validate::isLoadedObject($blog))
			{
				if (isset($position) && $blog->updatePosition($way, $position))
					die(true);
				else
					die('{"hasError" : true, "errors" : "Can not update blog position"}');
			}
			else
				die('{"hasError" : true, "errors" : "This blog can not be loaded"}');
		}
	}

	public function ajaxProcessUpdateblogCategoriesPositions()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			$id_leoblog_blog_category_to_move = (int)Tools::getValue('id_leoblog_blog_category_to_move');
			$id_leoblog_blog_category_parent = (int)Tools::getValue('id_leoblog_blog_category_parent');
			$way = (int)Tools::getValue('way');
			$positions = Tools::getValue('blog_category');
			if (is_array($positions))
				foreach ($positions as $key => $value)
				{
					$pos = explode('_', $value);
					if ((isset($pos[1]) && isset($pos[2])) && ($pos[1] == $id_leoblog_blog_category_parent && $pos[2] == $id_leoblog_blog_category_to_move))
					{
						$position = $key;
						break;
					}
				}
			$blog_category = new blogCategory($id_leoblog_blog_category_to_move);
			if (Validate::isLoadedObject($blog_category))
			{
				if (isset($position) && $blog_category->updatePosition($way, $position))
					die(true);
				else
					die('{"hasError" : true, "errors" : "Can not update blog categories position"}');
			}
			else
				die('{"hasError" : true, "errors" : "This blog category can not be loaded"}');
		}
	}

	public function ajaxProcessPublishblog()
	{
		if ($this->tabAccess['edit'] === '1')
		{
			if ($id_leoblog_blog = (int)Tools::getValue('id_leoblog_blog'))
			{
				$bo_blog_url = dirname($_SERVER['PHP_SELF']).'/index.php?tab=AdminblogContent&id_leoblog_blog='.(int)$id_leoblog_blog.'&updateblog&token='.$this->token;

				if (Tools::getValue('redirect'))
					die($bo_blog_url);

				$blog = new blog((int)(Tools::getValue('id_leoblog_blog')));
				if (!Validate::isLoadedObject($blog))
					die('error: invalid id');

				$blog->active = 1;
				if ($blog->save())
					die($bo_blog_url);
				else
					die('error: saving');
			}
			else
				die('error: parameters');
		}
	}

}
?>