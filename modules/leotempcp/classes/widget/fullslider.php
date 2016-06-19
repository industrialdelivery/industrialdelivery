<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class LeoWidgetFullslider extends LeoWidgetBase
{
	public $widget_name = 'fullslider';
	public $for_module = 'manage';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Full Slider'), 'explain' => $this->l('Create Slideshow'));
	}

	public function renderForm($args, $data)
	{
		# validate module
		unset($args);
		$this->checkFolderImage();
		$helper = $this->getFormHelper();
		$items = '';
		$slide_edit = '';
		if ($data['params'] && isset($data['params']['leoslide']) && $data['params']['leoslide'])
		{
			$slides = $data['params']['leoslide'];
			$items = $this->getSlide($slides);
			if (Tools::getValue('id_slide'))
			{
				$id_slide = Tools::getValue('id_slide');
				$slide_edit = $items[$id_slide] ? $items[$id_slide] : '';
			}
		}
		$this->fields_form[1]['form'] = array(
	            'legend' => array(
	                'title' => $this->l('Widget Form'),
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
		$theme_dir = Context::getContext()->shop->theme_directory;
		$images = array();
		$thums = array();
		$images = LeoWidgetBase::getImageList(_PS_ROOT_DIR_.'/themes/'.$theme_dir.'/img/modules/'.$this->name.'/image');
		$thums = LeoWidgetBase::getImageList(_PS_ROOT_DIR_.'/themes/'.$theme_dir.'/img/modules/'.$this->name.'/thum');
		$iso = Context::getContext()->language->iso_code;
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues($data),
			'languages' => Context::getContext()->controller->getLanguages(),
			'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
			'iso_code' => Context::getContext()->language->iso_code,
			'iso' => file_exists(_PS_CORE_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en',
			'path_css' => _THEME_CSS_DIR_,
			'ad' => __PS_BASE_URI__.basename(_PS_ADMIN_DIR_),
			'images' => $images,
			'thums' => $thums,
			'items' => $items,
			'slide_edit' => $slide_edit,
			'url' => AdminController::$currentIndex.'&id_leowidgets='.Tools::getValue('id_leowidgets').'&updateleowidgets&token='.Tools::getValue('token').'&conf=4',
			'pathimg' => __PS_BASE_URI__.'themes/'.$theme_dir.'/img/modules/'.$this->name.'/image/',
			'paththum' => __PS_BASE_URI__.'themes/'.$theme_dir.'/img/modules/'.$this->name.'/thum/',
		);
		return $helper->generateForm($this->fields_form);
	}

	public function renderContent($args, $setting)
	{
		# validate module
		unset($args);
		Context::getContext()->controller->addJs(__PS_BASE_URI__.'modules/'.$this->name.'/assets/admin/script.js', 'all');
		$t = array(
			'img_width' => 1170,
			'img_height' => 400,
			'thumb_width' => 100,
			'thumb_height' => 100,
			'interval' => 400,
		);
		$items = array();
		$setting = array_merge($t, $setting);
		if ($setting['leoslide'])
		{
			$items = $setting['leoslide'];
			$slides = $this->getSlide($items);
		}
		$setting['iso_code'] = Context::getContext()->language->iso_code;
		$setting['pathimg'] = __PS_BASE_URI__.'themes/'._THEME_NAME_.'/img/modules/'.$this->name.'/image/';
		$setting['paththum'] = __PS_BASE_URI__.'themes/'._THEME_NAME_.'/img/modules/'.$this->name.'/thum/';
		$setting['iso_code'] = Context::getContext()->language->iso_code;
		$setting['slides'] = $slides;
		$output = array('type' => 'fullslider', 'data' => $setting);

		return $output;
	}

	public function getSlide($slides)
	{
		$datas = array();
		foreach ($slides as $slide)
		{
			if ($slide)
			{
				$data = Tools::jsonDecode($slide, true);
				$data['slide'] = $slide;
			}
			$datas[$data['id_slide']] = $data;
		}
		return $datas;
	}

	public function checkFolderImage()
	{
		$theme_dir = Context::getContext()->shop->theme_directory;
		$pathimg = __PS_BASE_URI__.'themes/'.$theme_dir.'/img/modules/'.$this->name.'/image/';
		$paththum = __PS_BASE_URI__.'themes/'.$theme_dir.'/img/modules/'.$this->name.'/thum/';
		if (file_exists($pathimg) && is_dir($pathimg) && file_exists($paththum) && is_dir($paththum))
			return;
		@mkdir(_PS_ALL_THEMES_DIR_.$theme_dir.'/img/modules/', 0777, true);
		@mkdir(_PS_ALL_THEMES_DIR_.$theme_dir.'/img/modules/'.$this->name.'/', 0777, true);
		@mkdir($pathimg, 0777, true);
		@mkdir($paththum, 0777, true);
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
				'img_width',
				'img_height',
				'thumb_width',
				'thumb_height',
				'interval',
				'transitionSpeed',
				'displayProgressBar',
				'displayThumbnailNumbers',
				'displayThumbnailBackground',
				'leoslide'
			);
		}
		elseif ($multi_lang == 1)
		{
			return array(
				'title',
				'link',
			);
		}
		elseif ($multi_lang == 2)
		{
			return array(
				'description',
				'image',
				'thum',
			);
		}
	}

}