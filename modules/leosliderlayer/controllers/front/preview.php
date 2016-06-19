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

class LeoSliderLayerPreviewModuleFrontController extends ModuleFrontController
{
	private $_nameModule = 'leosliderlayer';
	public $themeName;
	public $img_path;
	public $img_url;

	public function __construct()
	{
		parent::__construct();
		$this->themeName = Context::getContext()->shop->getTheme();
		$this->img_path = _PS_ALL_THEMES_DIR_.$this->themeName.'/img/modules/'.$this->_nameModule.'/';
		$this->img_url = __PS_BASE_URI__.'themes/'.$this->themeName.'/img/modules/'.$this->_nameModule.'/';

		include_once($this->module->getLocalPath().$this->_nameModule.'.php');
	}

	/**
	 * @see FrontController::initContent()
	 */
	public function display()
	{
		if (!is_dir(_PS_ROOT_DIR_.'/cache/'.$this->_nameModule))
			mkdir(_PS_ROOT_DIR_.'/cache/'.$this->_nameModule, 0755);

		$id_group = Tools::getValue('id_group');
		$id_lang = $this->context->language->id;

		//preview group
		if ($id_group)
		{
			$group = $this->getSliderGroupByID($id_group);
			if (!Tools::getValue('id_slider') && !Tools::getValue('preview'))
				$sliders = $this->getSlides($id_group, $id_lang, 1);
		}

		$id_slider = Tools::getValue('id_slide');
		if ($id_slider && !Tools::getValue('preview'))
			$sliders = $this->getSlide($id_slider, $id_lang);

		if (Tools::getValue('preview'))
		{
			$slider_preview_data = trim(html_entity_decode((Tools::getValue('slider_preview_data'))));
			$slider_preview_data = Tools::jsonDecode($slider_preview_data);

			foreach ($slider_preview_data as $key => $val)
				$sliders[0][$key] = $val;

			$tmpSlider = array();
			$tmpSlider = $sliders[0]['params'];
			$sliders[0]['params'] = array();
			foreach ($tmpSlider as $key => $val)
				$sliders[0]['params'][$key] = $val;

			$tmpSlider = $sliders[0]['video'];
			$sliders[0]['video'] = array();
			foreach ($tmpSlider as $key => $val)
				$sliders[0]['video'][$key] = $val;

			$tmpSlider = $sliders[0]['layers'];
			$sliders[0]['layers'] = array();
			foreach ($tmpSlider as $key => $val)
			{
				foreach ($val as $k => $v)
					$sliders[0]['layersparams'][$key][$k] = $v;

			}
		}

		if (!isset($group) || !$group)
			return false;

		if (!$sliders)
			return false;

		$sliderObj = new LeoSliderLayer();
		$groupData = $sliderObj->groupData;
		$this->_sliderData = $sliderObj->_sliderData;
		$sliderParams = Tools::jsonDecode(SliderLayer::base64Decode($group['params']), true);
		$sliderParams = array_merge($groupData, $sliderParams);
		if (isset($sliderParams['fullwidth']) && (!empty($sliderParams['fullwidth']) || $sliderParams['fullwidth'] == 'boxed'))
			$sliderParams['image_cropping'] = false;

		$sliderParams['hide_navigator_after'] = $sliderParams['show_navigator'] ? 0 : $sliderParams['hide_navigator_after'];
		$sliderParams['slider_class'] = trim(isset($sliderParams['fullwidth']) && !empty($sliderParams['fullwidth']) ? $sliderParams['fullwidth'] : 'boxed');
		$sliderFullwidth = $sliderParams['slider_class'] == 'boxed' ? 'off' : 'on';

		//generate back-ground
		if ($sliderParams['background_image'])
			$sliderParams['background'] = 'background: url('.__PS_BASE_URI__.'modules/'.$this->_nameModule.'/images/'.$sliderParams['background_url'].') no-repeat scroll left 0 '.$sliderParams['background_color'].';';
		else
			$sliderParams['background'] = 'background-color:'.$sliderParams['background_color'];

		//include library genimage
		if (!class_exists('PhpThumbFactory'))
			require_once _PS_MODULE_DIR_.'leosliderlayer/libs/phpthumb/ThumbLib.inc.php';

		//echo "<pre>";print_r($sliders);die;
		//process slider
		foreach ($sliders as $key => $slider)
		{
			if (!Tools::getValue('preview'))
			{
				$slider['layers'] = array();
				$slider['params'] = array_merge($this->_sliderData, Tools::jsonDecode(SliderLayer::base64Decode($slider['params']), true));
				$slider['layersparams'] = Tools::jsonDecode(SliderLayer::base64Decode($slider['layersparams']), true);
				$slider['video'] = Tools::jsonDecode(SliderLayer::base64Decode($slider['video']), true);
			}

			$slider['data_link'] = '';
			if ($slider['params']['enable_link'] && $slider['link'])
			{
				$slider['data_link'] = 'data-link="'.$slider['link'].'"';
				$slider['data_target'] = 'data-target="'.SliderLayer::renderTarget($slider['params']['target']).'"';
			}
			else
				$slider['data_target'] = '';

			$slider['data_delay'] = $slider['params']['delay'] ? 'data-delay="'.(int)$slider['params']['delay'].'"' : '';

			//videoURL
			$slider['videoURL'] = '';
			if ($slider['video']['usevideo'] == 'youtube' || $slider['video']['usevideo'] == 'vimeo')
			{
				$slider['videoURL'] = 'http://player.vimeo.com/video/'.$slider['video']['videoid'].'/';
				if ($slider['video']['usevideo'] == 'youtube')
					$slider['videoURL'] = 'http://www.youtube.com/embed/'.$slider['video']['videoid'].'/';
			}
			$slider['background_color'] = '';
			if (isset($slider['video']['background_color']) && $slider['video']['background_color'])
				$slider['background_color'] = $slider['video']['background_color'];

			if ($sliderParams['image_cropping'])
			{
				//gender main_image
				if ($slider['image'] && file_exists($this->img_path.$slider['image']))
					$slider['main_image'] = $this->renderThumb($slider['image'], $sliderParams['width'], $sliderParams['height']);
				else
					$slider['main_image'] = '';

				if ($slider['thumbnail'] && file_exists($this->img_path.$slider['thumbnail']))
					$slider['thumbnail'] = $this->renderThumb($slider['thumbnail'], $sliderParams['thumbnail_width'], $sliderParams['thumbnail_height']);
				else if ($slider['image'] && file_exists($this->img_path.$slider['image']))
					$slider['thumbnail'] = $this->renderThumb($slider['image'], $sliderParams['thumbnail_width'], $sliderParams['thumbnail_height']);
				else
					$slider['thumbnail'] = '';
			}
			else
			{
				$slider['main_image'] = '';

				if ($slider['image'] && file_exists($this->img_path.$slider['image']))
					$slider['main_image'] = $this->img_url.$slider['image'];

				if ($slider['thumbnail'] && file_exists($this->img_path.$slider['thumbnail']))
					$slider['thumbnail'] = $this->img_url.$slider['thumbnail'];
				else if ($slider['image'] && file_exists($this->img_path.$slider['image']))
					$slider['thumbnail'] = $slider['main_image'];
				else
					$slider['thumbnail'] = '';
			}
			if (isset($slider['layersparams']) && $slider['layersparams'])
				foreach ($slider['layersparams'] as &$layerCss)
				{
					$layerCssVal = '';
					if (isset($layerCss['layer_font_size']) && $layerCss['layer_font_size'])
						$layerCssVal = 'font-size:'.$layerCss['layer_font_size'];
					if (isset($layerCss['layer_background_color']) && $layerCss['layer_background_color'])
						$layerCssVal .= ($layerCssVal != '' ? ';' : '').'background-color:'.$layerCss['layer_background_color'];
					if (isset($layerCss['layer_color']) && $layerCss['layer_color'])
						$layerCssVal .= ($layerCssVal != '' ? ';' : '').'color:'.$layerCss['layer_color'];
					$layerCss['css'] = $layerCssVal;
					if (!isset($layerCss['layer_link']))
						$layerCss['layer_link'] = $slider['link'];
					$layerCss['layer_target'] = SliderLayer::renderTarget($slider['params']['target']);
				}

			$sliders[$key] = $slider;
		}

		if (file_exists(_PS_THEME_DIR_.'modules/leosliderlayer/leosliderlayer.tpl'))
			$leosliderlayer_tpl = _PS_THEME_DIR_.'modules/leosliderlayer/leosliderlayer.tpl';
		else
			$leosliderlayer_tpl = _PS_MODULE_DIR_.'leosliderlayer/leosliderlayer.tpl';

		//add js + css
		$this->addJS(__PS_BASE_URI__.str_replace('//', '/', 'modules/leosliderlayer').'/js/jquery.themepunch.plugins.min.js');
		$this->addJS(__PS_BASE_URI__.str_replace('//', '/', 'modules/leosliderlayer').'/js/jquery.themepunch.revolution.min.js');
		if (file_exists(_PS_THEME_DIR_.'css/modules/leosliderlayer/css/typo.css'))
			$this->context->controller->addCSS(_PS_THEME_DIR_.'css/modules/leosliderlayer/css/typo.css');
		else
			$this->addCSS(__PS_BASE_URI__.str_replace('//', '/', 'modules/leosliderlayer').'/css/typo.css', 'all');

		$sliderParams['slider_start_with_slide'] = LeoSliderGroup::showStartWithSlide($sliderParams['start_with_slide'], $sliders);
		$this->context->smarty->assign(array(
			'sliderParams' => $sliderParams,
			'sliders' => $sliders,
			'sliderIDRand' => rand(20, rand()),
			'sliderFullwidth' => $sliderFullwidth,
			'leosliderlayer_tpl' => $leosliderlayer_tpl,
			'sliderImgUrl' => $this->img_url
		));
		$this->setTemplate('preview.tpl');

		parent::display();
	}

	public function renderThumb($src_file, $width, $height)
	{
		$subFolder = '/';
		if (!$src_file)
			return '';
		if (strpos($src_file, '/') !== false)
		{
			$path = @pathinfo($src_file);
			if (strpos($path['dirname'], '/') !== -1)
			{
				$subFolder = $path['dirname'].'/';
				$folderList = explode('/', $path['dirname']);
				$tmpPFolder = '/';
				foreach ($folderList as $value)
				{
					if ($value)
					{
						if (!is_dir(_PS_ROOT_DIR_.'/cache/'.$this->_nameModule.$tmpPFolder.$value))
							mkdir(_PS_ROOT_DIR_.'/cache/'.$this->_nameModule.$tmpPFolder.$value, 0755);
						$tmpPFolder .= $value.'/';
					}
				}
			}
			$imageName = $path['basename'];
		}
		else
			$imageName = $src_file;

		$path = '';
		if (file_exists($this->img_path.$src_file))
		{
			//return image url
			$path = __PS_BASE_URI__.'cache/'.$this->_nameModule.$subFolder.$width.'_'.$height.'_'.$imageName;
			$savePath = _PS_ROOT_DIR_.'/cache/'.$this->_nameModule.$subFolder.$width.'_'.$height.'_'.$imageName;
			if (!file_exists($savePath))
			{
				$thumb = PhpThumbFactory::create($this->img_path.$src_file);
				$thumb->adaptiveResize($width, $height);
				$thumb->save($savePath);
			}
		}

		return $path;
	}

	public function getSliderGroupByID($id_group)
	{
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                    SELECT *
                    FROM '._DB_PREFIX_.'leosliderlayer_groups gr
                    WHERE gr.id_leosliderlayer_groups = '.(int)$id_group);
	}

	/*
	 * get all slider data
	 */

	public function getSlides($id_group, $id_lang, $active = null)
	{
		$this->context = Context::getContext();
		if (!$id_lang)
			$id_lang = $this->context->language->id;

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                    SELECT lsl.`id_leosliderlayer_slides` as id_slide,
                                      lsl.*,lsll.*
                    FROM '._DB_PREFIX_.'leosliderlayer_slides lsl
                    LEFT JOIN '._DB_PREFIX_.'leosliderlayer_slides_lang lsll ON (lsl.id_leosliderlayer_slides = lsll.id_leosliderlayer_slides)
                    WHERE lsl.id_group = '.(int)$id_group.'
                    AND lsll.id_lang = '.(int)$id_lang.
						($active ? ' AND lsl.`active` = 1' : ' ').'
                    ORDER BY lsl.position');
	}

	/**
	 * get all slider data
	 */
	public function getSlide($id_slider, $id_lang)
	{
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                    SELECT lsl.`id_leosliderlayer_slides` as id_slide,
                                      lsl.*,lsll.*
                    FROM '._DB_PREFIX_.'leosliderlayer_slides lsl
                    LEFT JOIN '._DB_PREFIX_.'leosliderlayer_slides_lang lsll ON (lsl.id_leosliderlayer_slides = lsll.id_leosliderlayer_slides)
                    WHERE lsl.id_leosliderlayer_slides= '.(int)$id_slider.' AND lsll.id_lang = '.(int)$id_lang);
	}

}