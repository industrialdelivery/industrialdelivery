<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

class LeoWidgetCustomerCarousel extends LeoWidgetBase
{
	public $name = 'customercarousel';
	public $for_module = 'manage';

	public function getWidgetInfo()
	{
		return array('label' => $this->l('Customer HTML Carousel'), 'explain' => $this->l('Create Customer HTML Carousel'));
	}

	public function renderForm($args, $data)
	{
		# validate module
		unset($args);
		$helper = $this->getFormHelper();

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

		// Add library owl carousel
		$owl_carousel = new LeomanagewidgetsOwlCarousel();
		$arrays = $owl_carousel->getOwlCarouselAdminFormOptions();
		foreach ($arrays as $key => $array)
		{
			# validate module
			unset($key);
			$this->fields_form[1]['form']['input'][] = $array;
		}
		if (!isset($data['params']['nbcusthtml']) || !$data['params']['nbcusthtml'])
			$nbcusthtml = 5;
		else
			$nbcusthtml = $data['params']['nbcusthtml'];
		for ($i = 1; $i <= $nbcusthtml; $i++)
		{
			$tmpArray = array(
				'type' => 'text',
				'label' => $this->l('Title '.$i),
				'name' => 'title_'.$i,
				'default' => 'Title Sample '.$i,
				'lang' => true
			);
			$this->fields_form[1]['form']['input'][] = $tmpArray;
			$tmpArray = array(
				'type' => 'text',
				'label' => $this->l('Header '.$i),
				'name' => 'header_'.$i,
				'default' => 'Header Sample '.$i,
				'lang' => true
			);
			$this->fields_form[1]['form']['input'][] = $tmpArray;
			$tmpArray = array(
				'type' => 'textarea',
				'label' => $this->l('Content '.$i),
				'name' => 'content_'.$i,
				'default' => 'Content Sample '.$i,
				'cols' => 40,
				'rows' => 10,
				'value' => true,
				'lang' => true,
				'autoload_rte' => true,
				'desc' => $this->l('Enter Content '.$i)
			);
			$this->fields_form[1]['form']['input'][] = $tmpArray;
		}

		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues($data),
			'languages' => Context::getContext()->controller->getLanguages(),
			'id_language' => $default_lang
		);
		// echo "<pre>";print_r($nbcusthtml);die;
		return $helper->generateForm($this->fields_form);
	}

	public function renderContent($args, $setting)
	{
		# validate module
		unset($args);
		$header = '';
		$content = '';

		$cs = array();
		$languageID = Context::getContext()->language->id;
		for ($i = 1; $i <= $setting['nbcusthtml']; $i++)
		{
			$title = isset($setting['title_'.$i.'_'.$languageID]) ? $setting['title_'.$i.'_'.$languageID] : '';
			$header = isset($setting['header_'.$i.'_'.$languageID]) ? $setting['header_'.$i.'_'.$languageID] : '';

			if (!empty($header) && !empty($title))
			{
				$content = isset($setting['content_'.$i.'_'.$languageID]) ? Tools::stripslashes($setting['content_'.$i.'_'.$languageID]) : '';
				$cs[] = array('title' => trim($title), 'header' => trim($header), 'content' => trim($content));
			}
		}
		if ($setting['auto_play'])
			$setting['interval'] = (isset($setting['interval'])) ? (int)($setting['interval']) : 4000;
		else
			$setting['interval'] = 'false';
		$setting['startSlide'] = ($setting['startSlide']) ? $setting['startSlide'] : '0';
		$setting['customercarousel'] = $cs;
		$setting['id'] = rand() + count($cs);
		$setting['random_number'] = rand(20, rand());

		$output = array('type' => 'customercarousel', 'data' => $setting);
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
				'show_controls',
				'startSlide',
				'nbcusthtml',
				'carousel_type',
				'interval',
				'auto_play',
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
			$number_html = Tools::getValue('nbcusthtml');
			$array = array();
			for ($i = 1; $i <= $number_html; $i++)
			{
				$array[] = 'title_'.$i;
				$array[] = 'header_'.$i;
				$array[] = 'content_'.$i;
			}
			return $array;
		}
		elseif ($multi_lang == 2)
		{
			return array(
			);
		}
	}

}
