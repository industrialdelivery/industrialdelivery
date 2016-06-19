<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class LeoWidgetCategoriestabs extends LeoWidgetBase
{
	public $widget_name = 'Categoriestabs';
	public $for_module = 'manage';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Categories Tabs'), 'explain' => $this->l('Create Categories Tabs !'));
	}

	public function renderForm($args, $data)
	{
		# validate module
		unset($args);
		$orderby = array(
			array(
				'order' => 'date_add', // The value of the 'value' attribute of the <option> tag.
				'name' => $this->l('Date Add')			 // The value of the text content of the  <option> tag.
			),
			array(
				'order' => 'date_upd', // The value of the 'value' attribute of the <option> tag.
				'name' => $this->l('Date Update')			 // The value of the text content of the  <option> tag.
			),
			array(
				'order' => 'name',
				'name' => $this->l('Name')
			),
			array(
				'order' => 'id_product',
				'name' => $this->l('Product Id')
			),
			array(
				'order' => 'price',
				'name' => $this->l('Price')
			),
		);

		$orderway = array(
			array(
				'orderway' => 'ASC', // The value of the 'value' attribute of the <option> tag.
				'name' => $this->l('Ascending')			 // The value of the text content of the  <option> tag.
			),
			array(
				'orderway' => 'DESC', // The value of the 'value' attribute of the <option> tag.
				'name' => $this->l('Descending')			 // The value of the text content of the  <option> tag.
			),
		);

		$selected_cat = array();
		if ($data)
		{
			if ($data['params'] && $data['params']['categories'])
				$selected_cat = $data['params']['categories'];
		}
		$helper = $this->getFormHelper();

		$this->fields_form[1]['form'] = array(
	            'legend' => array(
	                'title' => $this->l('Carousel Form.'),
	            ),
	            'input' => array(
	                array(
		                'type' => 'html',
		                'html_content' => 'Please access <a href="http://apollotheme.com/" target="_blank" title="apollo site">Apollotheme.com</a> to buy professional version to use this ',
		            ),
		            array(
		                'type' => 'html',
		                'html_content' => '<a target="_blank" href="http://apollotheme.com/how-to-buy-pro-version/" target="_blank" title="How to buy">How to buy Professional Version</a>',
		            ),
		            array(
		                'type' => 'html',
		                'html_content' => '<a target="_blank" href="http://apollotheme.com/different-between-free-pro-version/" target="_blank" title="Why should use">Why should use Professional Version</a>',
		            )
	            ),
	      		 'buttons' => array(
                            array(
                                'title' => $this->l('Save And Stay'),
                                'icon' => 'process-icon-save',
                                'class' => 'pull-right',
                                'type' => 'submit',
                                'name' => 'saveandstayleotempcp'
                            ),
                            array(
                                'title' => $this->l('Save'),
                                'icon' => 'process-icon-save',
                                'class' => 'pull-right',
                                'type' => 'submit',
                                'name' => 'saveleotempcp'
                            ),
                        )
	        );


		// Add library owl carousel
		$owl_carousel = new LeomanagewidgetsOwlCarousel();
		$arrays = $owl_carousel->getOwlCarouselAdminFormOptions();
		foreach ($arrays as $key => $array)
		{
			# validate module
			unset($key);
			$this->fields_form[1]['form']['input'][] = $array;
		}
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues($data),
			'languages' => Context::getContext()->controller->getLanguages(),
			'id_language' => $default_lang
		);
		return $helper->generateForm($this->fields_form);
	}

	public function renderContent($args, $setting)
	{
		# validate module
		unset($args);
		$t = array(
			'name' => '',
			'html' => '',
		);
		$setting = array_merge($t, $setting);
		$nb = ($setting['itemstab']) ? (int)($setting['itemstab']) : 6;
		$catids = ($setting['categories']) ? ($setting['categories']) : array();
		$orderby = ($setting['orderby']) ? ($setting['orderby']) : 'position';
		$orderway = ($setting['orderway']) ? ($setting['orderway']) : 'ASC';
		$items_page = ($setting['itemspage']) ? (int)($setting['itemspage']) : 3;
		$columns_page = ($setting['columns']) ? (int)($setting['columns']) : 3;
		$categories = array();
		foreach ($catids as $catid)
		{
			$category = new Category($catid, (int)Context::getContext()->language->id);
			if ($category->id)
			{
				$categories[$catid]['id'] = $category->id;
				$categories[$catid]['name'] = $category->name;
				$categories[$catid]['link'] = $category->getLink();
				$products = $category->getProducts((int)Context::getContext()->language->id, 1, $nb, $orderby, $orderway);
				Context::getContext()->controller->addColorsToProductList($products);
				$categories[$catid]['products'] = $products;
			}
		}

		$setting['leocategories'] = $categories;
		$setting['itemsperpage'] = $items_page;
		$setting['columnspage'] = $columns_page;
		$setting['scolumn'] = 12 / $columns_page;
		$setting['myTab'] = 'leocategorytab'.rand(20, rand());
		$output = array('type' => 'categoriestabs', 'data' => $setting);

		return $output;
	}

	/**
	 * 0 no multi_lang
	 * 1 multi_lang follow id_lang
	 * 2 multi_lnag follow code_lang
	 */
	public function getConfigKey($multi_lang = 0)
	{
		if ($multi_lang == 0)
		{
			return array(
				'categories',
				'orderby',
				'orderway',
				'itemstab',
				'carousel_type',
				'itemspage',
				'columns',
				'owl_items',
				'owl_rows',
				'owl_autoPlay',
				'owl_stopOnHover',
				'owl_autoHeight',
				'owl_responsive',
				'owl_mouseDrag',
				'owl_touchDrag',
				'owl_navigation',
				'owl_slideSpeed',
				'owl_itemsDesktop',
				'owl_itemsDesktopSmall',
				'owl_itemsTablet',
				'owl_itemsTabletSmall',
				'owl_itemsMobile',
				'owl_itemsCustom',
				'owl_lazyLoad',
				'owl_lazyEffect',
				'owl_lazyFollow',
				'owl_pagination',
				'owl_paginationNumbers',
				'owl_paginationSpeed',
				'owl_rewindNav',
				'owl_rewindSpeed',
				'owl_scrollPerPage',
			);
		}
		elseif ($multi_lang == 1)
		{
			return array(
			);
		}
		elseif ($multi_lang == 2)
		{
			return array(
			);
		}
	}

}