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

include_once(_PS_MODULE_DIR_ . 'leosliderlayer/grouplayer.php');
include_once(_PS_MODULE_DIR_ . 'leosliderlayer/sliderlayer.php');
include_once(_PS_MODULE_DIR_ . 'leosliderlayer/status.php');

class LeoSliderLayer extends Module
{

	private $_html = '';
	private $_currentGroup = array('id_group' => 0, 'title' => '');
	public $groupData = array(
		'id_leosliderlayer_groups' => '',
		'title' => '',
		'id_shop' => '',
		'hook' => '',
		'active' => '',
		'auto_play' => '1',
		'delay' => '9000',
		'fullwidth' => '',
		'width' => '960',
		'height' => '350',
		'md_width' => '12',
		'sm_width' => '12',
		'xs_width' => '12',
		'touch_mobile' => '1',
		'stop_on_hover' => '1',
		'shuffle_mode' => '1',
		'image_cropping' => '0',
		'shadow_type' => '2',
		'show_time_line' => '1',
		'time_line_position' => 'top',
		'background_color' => '#d9d9d9',
		'margin' => '0px 0px 18px',
		'padding' => '5px 5px',
		'background_image' => '0',
		'background_url' => '',
		'navigator_type' => 'none',
		'navigator_arrows' => 'verticalcentered',
		'navigation_style' => 'round',
		'offset_horizontal' => '0',
		'offset_vertical' => '20',
		'show_navigator' => '0',
		'hide_navigator_after' => '200',
		'thumbnail_width' => '100',
		'thumbnail_height' => '50',
		'thumbnail_amount' => '5',
		'group_class' => '',
		'start_with_slide' => '0',
	);
	private $_hookSupport = array(
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
		'productFooter',
		'displayRightColumnProduct');
	private $_currentSlider = array();
	public $_sliderData = array(
		'transition' => 'random',
		'slot' => 7,
		'rotation' => 0,
		'duration' => 300,
		'delay' => 0,
		'enable_link' => 1,
		'target' => '',
		'start_date_time' => '',
		'end_date_time' => '',
	);
	public $themeName;
	public $img_path;
	public $img_url;
	public $_error_text = '';

	public function __construct()
	{
		$this->name = 'leosliderlayer';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'LeoTheme';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Leo Slider Layer for your homepage.');
		$this->description = $this->l('Adds image or text slider to your homepage.');

		$this->themeName = Context::getContext()->shop->getTheme();
		$this->img_path = _PS_ALL_THEMES_DIR_ . $this->themeName . '/img/modules/' . $this->name . '/';
		$this->img_url = __PS_BASE_URI__ . 'themes/' . $this->themeName . '/img/modules/' . $this->name . '/';
	}

	/**
	 * @see Module::install()
	 */
	public function install()
	{
		// Prepare tab
		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = "AdminLeoSliderLayer";
		$tab->name = array();
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = 'LeoSliderLayer';
		$tab->id_parent = -1;
		$tab->module = $this->name;

		/* Adds Module */
		if ($tab->add() && parent::install() && Configuration::updateValue('LEOSLIDERLAYER_GROUP_DE', '1')) {
			$res = true;
			$res &= $this->registerHook("header");
			$res &= $this->registerHook("actionShopDataDuplication");
			foreach ($this->_hookSupport as $value)
			{
				$res &= $this->registerHook($value);
			}
			/* Sets up configuration */

			/* Creates tables */
			$res &= $this->createTables();

			return (bool) $res;
		}
		return false;
	}

	/**
	 * Adds samples
	 */
	private function installSamples()
	{
		if ($this->checkExistAnyGroup())
			return true;
		//insearch demo for group slider
		$group = new LeoSliderGroup();
		$context = Context::getContext();
		//sample for group
		$group->title = 'Sample Group';
		$group->hook = 'displaySlideshow';
		if ($context->shop->id)
			$group->id_shop = $context->shop->id;
		else
			$group->id_shop = $context->tmpOldShop->id;
		$group->active = 1;
		$group->params = SliderLayer::base64Encode(Tools::jsonEncode($this->groupData));
		$group->add();

		//sample for slider
		$languages = Language::getLanguages(false);
		for ($i = 1; $i <= 2; ++$i)
		{
			$slide = new SliderLayer();
			$slide->position = $i;
			$slide->active = 1;
			$slide->params = SliderLayer::base64Encode(Tools::jsonEncode($this->_sliderData));
			$slide->id_group = $group->id;
			foreach ($languages as $language)
			{
				$slide->title[$language['id_lang']] = "Sample slider " . $i;
				$slide->link[$language['id_lang']] = "";
				$slide->image[$language['id_lang']] = "";
				$slide->thumb[$language['id_lang']] = "";
				$slide->video[$language['id_lang']] = 'a:4:{s:8:"usevideo";s:1:"0";s:7:"videoid";s:0:"";s:9:"videoauto";s:1:"0";s:16:"background_color";s:0:"";}';
				$slide->video[$language['id_lang']] = $this->converParams($slide->video[$language['id_lang']]);
				if ($i == 1)
					$slide->layersparams[$language['id_lang']] = 'a:2:{i:0;a:24:{s:16:"layer_video_type";s:7:"youtube";s:14:"layer_video_id";s:0:"";s:18:"layer_video_height";s:3:"200";s:17:"layer_video_width";s:3:"300";s:17:"layer_video_thumb";s:0:"";s:8:"layer_id";s:3:"1_2";s:13:"layer_content";s:0:"";s:10:"layer_type";s:4:"text";s:11:"layer_class";s:10:"big_orange";s:13:"layer_caption";s:20:"Slider Sample Demo 1";s:15:"layer_font_size";s:5:"100px";s:22:"layer_background_color";s:0:"";s:11:"layer_color";s:0:"";s:10:"layer_link";s:0:"";s:15:"layer_animation";s:4:"fade";s:12:"layer_easing";s:11:"easeOutExpo";s:11:"layer_speed";s:3:"350";s:9:"layer_top";s:2:"68";s:10:"layer_left";s:3:"554";s:13:"layer_endtime";s:1:"0";s:14:"layer_endspeed";s:3:"300";s:18:"layer_endanimation";s:4:"auto";s:15:"layer_endeasing";s:7:"nothing";s:10:"time_start";s:4:"1200";}i:1;a:24:{s:16:"layer_video_type";s:7:"youtube";s:14:"layer_video_id";s:0:"";s:18:"layer_video_height";s:3:"200";s:17:"layer_video_width";s:3:"300";s:17:"layer_video_thumb";s:0:"";s:8:"layer_id";s:3:"1_3";s:13:"layer_content";s:0:"";s:10:"layer_type";s:4:"text";s:11:"layer_class";s:9:"big_black";s:13:"layer_caption";s:19:"Your Caption Here 4";s:15:"layer_font_size";s:5:"100px";s:22:"layer_background_color";s:0:"";s:11:"layer_color";s:0:"";s:10:"layer_link";s:0:"";s:15:"layer_animation";s:4:"fade";s:12:"layer_easing";s:11:"easeOutExpo";s:11:"layer_speed";s:3:"350";s:9:"layer_top";s:3:"140";s:10:"layer_left";s:3:"555";s:13:"layer_endtime";s:1:"0";s:14:"layer_endspeed";s:3:"300";s:18:"layer_endanimation";s:4:"auto";s:15:"layer_endeasing";s:7:"nothing";s:10:"time_start";s:4:"1600";}}';
				else
					$slide->layersparams[$language['id_lang']] = 'a:3:{i:0;a:24:{s:16:"layer_video_type";s:7:"youtube";s:14:"layer_video_id";s:11:"VA770wpLX-Q";s:18:"layer_video_height";s:3:"200";s:17:"layer_video_width";s:3:"300";s:17:"layer_video_thumb";s:48:"http://i1.ytimg.com/vi/VA770wpLX-Q/hqdefault.jpg";s:8:"layer_id";s:3:"1_1";s:13:"layer_content";s:0:"";s:10:"layer_type";s:5:"video";s:11:"layer_class";s:0:"";s:13:"layer_caption";s:17:"Your Video Here 1";s:15:"layer_font_size";s:5:"100px";s:22:"layer_background_color";s:0:"";s:11:"layer_color";s:0:"";s:10:"layer_link";s:0:"";s:15:"layer_animation";s:4:"fade";s:12:"layer_easing";s:11:"easeOutExpo";s:11:"layer_speed";s:3:"350";s:9:"layer_top";s:2:"47";s:10:"layer_left";s:3:"515";s:13:"layer_endtime";s:1:"0";s:14:"layer_endspeed";s:3:"300";s:18:"layer_endanimation";s:4:"auto";s:15:"layer_endeasing";s:7:"nothing";s:10:"time_start";s:3:"400";}i:1;a:24:{s:16:"layer_video_type";s:7:"youtube";s:14:"layer_video_id";s:11:"VA770wpLX-Q";s:18:"layer_video_height";s:3:"200";s:17:"layer_video_width";s:3:"300";s:17:"layer_video_thumb";s:48:"http://i1.ytimg.com/vi/VA770wpLX-Q/hqdefault.jpg";s:8:"layer_id";s:3:"1_2";s:13:"layer_content";s:0:"";s:10:"layer_type";s:4:"text";s:11:"layer_class";s:10:"big_orange";s:13:"layer_caption";s:19:"Your Caption Here 2";s:15:"layer_font_size";s:5:"100px";s:22:"layer_background_color";s:0:"";s:11:"layer_color";s:0:"";s:10:"layer_link";s:0:"";s:15:"layer_animation";s:4:"fade";s:12:"layer_easing";s:11:"easeOutExpo";s:11:"layer_speed";s:3:"350";s:9:"layer_top";s:3:"122";s:10:"layer_left";s:2:"61";s:13:"layer_endtime";s:1:"0";s:14:"layer_endspeed";s:3:"300";s:18:"layer_endanimation";s:4:"auto";s:15:"layer_endeasing";s:7:"nothing";s:10:"time_start";s:3:"800";}i:2;a:24:{s:16:"layer_video_type";s:7:"youtube";s:14:"layer_video_id";s:11:"VA770wpLX-Q";s:18:"layer_video_height";s:3:"200";s:17:"layer_video_width";s:3:"300";s:17:"layer_video_thumb";s:48:"http://i1.ytimg.com/vi/VA770wpLX-Q/hqdefault.jpg";s:8:"layer_id";s:3:"1_3";s:13:"layer_content";s:0:"";s:10:"layer_type";s:4:"text";s:11:"layer_class";s:21:"very_large_black_text";s:13:"layer_caption";s:19:"Your Caption Here 3";s:15:"layer_font_size";s:5:"100px";s:22:"layer_background_color";s:0:"";s:11:"layer_color";s:0:"";s:10:"layer_link";s:0:"";s:15:"layer_animation";s:4:"fade";s:12:"layer_easing";s:11:"easeOutExpo";s:11:"layer_speed";s:3:"350";s:9:"layer_top";s:3:"261";s:10:"layer_left";s:2:"25";s:13:"layer_endtime";s:1:"0";s:14:"layer_endspeed";s:3:"300";s:18:"layer_endanimation";s:4:"auto";s:15:"layer_endeasing";s:7:"nothing";s:10:"time_start";s:4:"1200";}}';
				$slide->layersparams[$language['id_lang']] = $this->converParams($slide->layersparams[$language['id_lang']]);
			}
			$slide->add();
		}
		return true;
	}

	/**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{
		$id_tab = (int) Tab::getIdFromClassName('AdminLeoSliderLayer');
		if ($id_tab) {
			$tab = new Tab($id_tab);
			$tab->delete();
		}

		/* Deletes Module */
		if (parent::uninstall()) {
			/* Deletes tables */
			$res = $this->deleteTables();
			return $res;
		}
		return false;
	}

	/**
	 * Creates tables
	 */
	protected function createTables()
	{
		if ($this->_installDataSample())
			return true;
		/* Group */
		$res = (bool) Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_groups` (
                `id_' . $this->name . '_groups` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                `title` varchar(255) NOT NULL,    
                `id_shop` int(10) unsigned NOT NULL,
                                `hook` varchar(64) NOT NULL,
                                `active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
                                `params` text NOT NULL,
                PRIMARY KEY (`id_' . $this->name . '_groups`, `id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');

		/* Slides configuration */
		$res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_slides` (
              `id_' . $this->name . '_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `id_group` int(11) NOT NULL,
              `position` int(10) unsigned NOT NULL DEFAULT \'0\',
              `active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
                          `params` text NOT NULL,
              PRIMARY KEY (`id_' . $this->name . '_slides`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');

		/* Slides lang configuration */
		$res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_slides_lang` (
              `id_' . $this->name . '_slides` int(10) unsigned NOT NULL,
              `id_lang` int(10) unsigned NOT NULL,
              `title` varchar(255) NOT NULL,
              `link` varchar(255) NOT NULL,
              `image` varchar(255) NOT NULL,
              `thumbnail` varchar(255) NOT NULL,
              `video` text,
              `layersparams` text,
              PRIMARY KEY (`id_' . $this->name . '_slides`,`id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
		$res &= $this->installSamples();
		return $res;
	}

	private function _installDataSample()
	{
		if (!file_exists(_PS_MODULE_DIR_ . 'leotempcp/libs/DataSample.php'))
			return false;
		require_once( _PS_MODULE_DIR_ . 'leotempcp/libs/DataSample.php' );

		$DataSampleClass = 'Datasample';

		$sample = new $DataSampleClass(1);
		return $sample->processImport($this->name);
	}

	/**
	 * deletes tables
	 */
	protected function deleteTables()
	{
		//return true;
		return Db::getInstance()->execute('
          DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $this->name . '_groups`, `' . _DB_PREFIX_ . $this->name . '_slides`, `' . _DB_PREFIX_ . $this->name . '_slides_lang`;
        ');
	}

	public function getContent()
	{
		return 'Sorry, this module is commercial module. Please access <a href="http://apollotheme.com/" target="_blank" title="apollo site">Apollotheme.com</a> to buy professional version to use this';
	}

	/*
	 * this function is only for developer of leotheme.com
	 * to correct data for group + slider
	 */

	public function correctDataGroup()
	{
		$id_group = Tools::getValue('id_group');
		if ($id_group) {
			$group = new LeoSliderGroup($id_group);

			if (Validate::isLoadedObject($group)) {
				//correct group data
				$params = Tools::unSerialize($group->params);
				if ($params) {
					$group->params = SliderLayer::base64Encode(Tools::jsonEncode($params));
					$group->save();
				}

				//correct slider
				$sliders = $this->getSlides($group->id);
				foreach ($sliders as $slider)
				{
					$sliderObj = new SliderLayer($slider["id_slide"]);
					if (Validate::isLoadedObject($sliderObj)) {
						$tmp = Tools::unSerialize($sliderObj->params);
						if ($tmp)
							$sliderObj->params = SliderLayer::base64Encode(Tools::jsonEncode($tmp));

						$tmpObj = array();
						foreach ($sliderObj->video as $key => $value)
						{
							$tmp = Tools::unSerialize($value);
							if ($tmp)
								$tmpObj[$key] = SliderLayer::base64Encode(Tools::jsonEncode($tmp));
						}
						if ($tmpObj)
							$sliderObj->video = $tmpObj;

						$tmpObj = array();
						foreach ($sliderObj->layersparams as $key => $value)
						{
							$tmp = Tools::unSerialize($value);
							if ($tmp) {
								$tmpObj[$key] = SliderLayer::base64Encode(Tools::jsonEncode($tmp));
							}
						}
						if ($tmpObj)
							$sliderObj->layersparams = $tmpObj;
						//print_r($sliderObj);die;
						$sliderObj->save();
					}
				}
			}
		}
	}

	public function copyLang()
	{
		$id_group = Tools::getValue('id_group');
		if ($id_group) {
			$sliders = $this->getSlides($id_group);
			$sliderObj = new SliderLayer();
			$defined = $sliderObj->getDefinition($sliderObj);
			$defined = $defined['fields'];

			foreach ($sliders as $slider)
			{
				$sliderObj = new SliderLayer($slider["id_slide"]);
				if ($sliderObj->id) {
					$languages = Language::getLanguages(false);
					$default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

					$tmp = array();
					foreach ($languages as $language)
					{
						if ($language['id_lang'] == $default_lang) {
							foreach ($defined as $key => $val)
							{
								if (isset($val['lang']) && $val['lang'] == 1) {
									$tmp[$key] = $sliderObj->{$key}[$default_lang];
								}
							}
							break;
						}
					}

					foreach ($languages as $language)
					{
						if ($language['id_lang'] != $default_lang) {
							foreach ($tmp as $key => $val)
							{
								if ($key == "layersparams") {
									$layersParams = Tools::jsonDecode(SliderLayer::base64Decode($val), true);
									foreach ($layersParams as &$layer)
									{
										$layer['layer_id'] = str_replace($default_lang . "_", $language['id_lang'] . "_", $layer['layer_id']);
									}
									//echo "<pre>";print_r($layersParams);die;
									$sliderObj->layersparams[$language['id_lang']] = SliderLayer::base64Encode(Tools::jsonEncode($layersParams));
								} else {
									$sliderObj->{$key}[$language['id_lang']] = $val;
								}
							}
						}
					}
					$sliderObj->update();
				}
			}
		}
	}

	public function importGroup()
	{
		include_once(_PS_MODULE_DIR_ . 'leosliderlayer/controllers/admin/AdminLeoSliderLayer.php');
		$sliderController = new AdminLeoSliderLayerController();
		$res = $sliderController->importGroup();
		if (!$res)
			$this->_html .= $this->displayError('Could not import');
		else
			$this->_html .= $this->displayConfirmation($this->l('Importing was successful'));
	}

	/*
	 * add new
	 * delete
	 * duplicate
	 * changestatus
	 */

	public function processAJax()
	{
		$result = array();
		if (Tools::getValue('action') && Tools::getValue('action') == 'submitslider') {
			if (Tools::getValue('id_slide')) {
				$slide = new SliderLayer((int) Tools::getValue('id_slide'));
				if (!Validate::isLoadedObject($slide)) {
					$this->l('Invalid id_slide');
					return;
				}
			} else
				$slide = new SliderLayer();

			$slide->position = (int) Tools::getValue('position');
			$slide->id_group = (int) Tools::getValue('id_group');

			/* Sets active */
			$slide->active = (int) Tools::getValue('active_slide');
			$slide->params = SliderLayer::base64Encode(Tools::jsonEncode(Tools::getValue("slider")));

			try {
				$post_slide = Tools::getValue('slider');
				$slide->start_date_time = $post_slide['start_date_time'];
				$slide->end_date_time = $post_slide['end_date_time'];
				$slide->id_group = $post_slide['group_id'];

				$slide->validate($this);
			} catch (Exception $exc) {
				$result = array("error" => 1, "text" => $exc->getMessage());
				$this->clearCache();
				die(Tools::jsonEncode($result));
			}

			$languages = Language::getLanguages(false);
			$tmpData = array();
			$tmpBackColor = "";
			
			foreach ($languages as $language)
			{
				$slide->title[$language['id_lang']] = Tools::getValue('title_' . $language['id_lang']);
				//get data default
				$slide->link[$language['id_lang']] = Tools::getValue('link_' . $language['id_lang']);
				$slide->image[$language['id_lang']] = Tools::getValue('image_' . $language['id_lang']);
				$slide->thumbnail[$language['id_lang']] = Tools::getValue('thumbnail_' . $language['id_lang']);

				$video = array();
				$video["usevideo"] = Tools::getValue('usevideo_' . $language['id_lang']);
				$video["videoid"] = Tools::getValue('videoid_' . $language['id_lang']);
				$video["videoauto"] = Tools::getValue('videoauto_' . $language['id_lang']);
				$video["background_color"] = Tools::getValue('background_color_' . $language['id_lang']);
				if ($video["background_color"] == "" && !Tools::getValue('id_slide')) {
					$video["background_color"] = $tmpBackColor;
				} else {
					$tmpBackColor = $video["background_color"];
				}


				$slide->video[$language['id_lang']] = SliderLayer::base64Encode(Tools::jsonEncode($video));
				$layersparams = new stdClass();
				$layersparams->layers = array();

				if (Tools::getIsset('layers_' . $language['id_lang'])) {
					$times = Tools::getValue('layer_time');
					$layers = Tools::getValue('layers_' . $language['id_lang']);

					//echo "<pre>";print_r($times);

					foreach ($layers as $key => $value)
					{
						$value['time_start'] = $times[$value['layer_id']];
						//fix for php 5.2 and 5.3
						$value['layer_caption'] = utf8_encode(str_replace(array("\'", '\"'), array("'", '"'), $value['layer_caption']));
						$times[$value['layer_id']] = $value;
					}
					// echo "<pre>".$language['id_lang'];print_r($times);
					$k = 0;
					//echo "<pre>";print_r($times);
					foreach ($times as $key => $value)
					{
						if (is_array($times) && $key == @$value['layer_id']) {
							$value['layer_id'] = $language['id_lang'] . '_' . ($k + 1);
							$layersparams->layers[$k] = $value;
							$k++;
						}
					}
					$slide->layersparams[$language['id_lang']] = SliderLayer::base64Encode(Tools::jsonEncode($layersparams->layers));
				} else {
					//when add new create sample data for other language
					if (!Tools::getValue('id_slide') && isset($tmpData["layersparams"]) && $tmpData["layersparams"]) {
						//set id again
						foreach ($tmpData["layersparams"] as &$tmpLayer)
						{
							foreach ($tmpLayer as $key => &$value)
							{
								if ($key == "layer_id") {
									$valu = explode("_", $value);
									$value = str_replace($valu[0] . "_", $language['id_lang'] . "_", $value);
								}
							}
						}
						//print_r($tmpData["layersparams"]);
						$slide->layersparams[$language['id_lang']] = SliderLayer::base64Encode(Tools::jsonEncode($tmpData["layersparams"]));
					} else
						$slide->layersparams[$language['id_lang']] = "";
				}


				//get data default if add new
				if (!Tools::getValue('id_slide') && $slide->title && empty($tmpData)) {
					$tmpData["title"] = $slide->title[$language['id_lang']];
					$tmpData["link"] = $slide->link[$language['id_lang']];
					$tmpData["video"] = $slide->video[$language['id_lang']];
					$tmpData["image"] = $slide->image[$language['id_lang']];
					$tmpData["thumbnail"] = $slide->image[$language['id_lang']];
					$tmpData["id_lang"] = $language['id_lang'];
					$tmpData["image"] = $slide->image[$language['id_lang']];
				}
				if (!Tools::getValue('id_slide') && !isset($tmpData["layersparams"])) {
					$tmpData["layersparams"] = $layersparams->layers;
				}
			}
			//print_r($slide->layersparams);die();
			/* Processes if no errors  */

			/* Adds */
			if (!Tools::getValue('id_slide')) {
				//add default image
				foreach ($slide->title as &$value)
					if ($value == "")
						$value = $tmpData["title"];

				foreach ($slide->link as &$value)
					if ($value == "")
						$value = $tmpData["link"];

				foreach ($slide->image as &$value)
					if ($value == "")
						$value = $tmpData["image"];

				foreach ($slide->video as &$value)
					if ($value == "")
						$value = $tmpData["video"];

				if (!$slide->add()) {
					$result = array("error" => 1, "text" => $this->l('The slide could not be added.'));
				}
				//add slider
			}
			/* Update */ elseif (!$slide->update()) {
				$result = array("error" => 1, "text" => $this->l('The slide could not be updated.'));
			}
			$myLink = '&configure=leosliderlayer&editSlider=1&id_slide=' . $slide->id . '&id_group=' . $slide->id_group;
			$result = array("error" => 0, "text" => $myLink);

			$this->clearCache();
			die(Tools::jsonEncode($result));
		}


		if (Tools::getValue('action') && Tools::getValue('action') == 'updateSlidesPosition' && Tools::getValue('slides')) {
			$slides = Tools::getValue('slides');

			foreach ($slides as $position => $id_slide)
			{
				$result = Db::getInstance()->execute('
			UPDATE `' . _DB_PREFIX_ . 'leosliderlayer_slides` SET `position` = ' . (int) $position . '
			WHERE `id_leosliderlayer_slides` = ' . (int) $id_slide
				);
			}
			$this->clearCache();
			die(Tools::jsonEncode($result));
		}

		if (Tools::getValue('action') && Tools::getValue('action') == 'deleteSlider') {
			$id_slide = Tools::getValue('id_slide');
			$slide = new SliderLayer((int) $id_slide);
			if (!$slide->delete()) {
				$result = array("error" => 1, "text" => $this->l('The slide could not be delete.'));
			}
			$this->clearCache();
			die(Tools::jsonEncode($result));
		}

		if (Tools::getValue('action') && Tools::getValue('action') == 'duplicateSlider') {
			$slide = new SliderLayer((int) Tools::getValue('id_slide'));
			$sliderNew = new SliderLayer();

			$defined = $sliderNew->getDefinition("SliderLayer");

			foreach ($defined["fields"] as $ke => $val)
			{
				// validate module : $val is used
				unset($val);
				
				if ($ke == "id")
					continue;

				if ($ke == "title") {
					$tmp = array();
					foreach ($slide->title as $kt => $vt)
					{
						$tmp[$kt] = $this->l("Duplicate of") . " " . $vt;
					}
					$sliderNew->{$ke} = $tmp;
				} else
					$sliderNew->{$ke} = $slide->{$ke};
			}
			if (!$sliderNew->add()) {
				$result = array("error" => 1, "text" => $this->l('The slide could not be duplicate.'));
			}

			$this->clearCache();
			die(Tools::jsonEncode($result));
		}
	}

	public function renderList()
	{
		//get curent slider data
		if (Tools::isSubmit('id_slide') && $this->slideExists((int) Tools::getValue('id_slide'))) {
			$this->_currentSlider = new SliderLayer((int) Tools::getValue('id_slide'));
		} else {
			$this->_currentSlider = new SliderLayer();
		}

		$slides = $this->getSlides(Tools::getValue("id_group"));
		foreach ($slides as $key => $slide)
			$slides[$key]['status'] = $this->displayStatus($slide['id_slide'], $slide['active'], $slide['id_group'], $slide);

		$groupObj = new LeoSliderGroup((int) Tools::getValue('id_group'));
		$id_shop = $this->context->shop->id;

		if ($id_shop != $groupObj->id_shop)
			Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'));
		$this->groupData = array_merge($this->groupData, Tools::jsonDecode(SliderLayer::base64Decode($groupObj->params), true));
//		$arrayParam['secure_key'] = $this->secure_key;

		$this->context->smarty->assign(array(
			'link' => $this->context->link,
			'slides' => $slides,
			'id_group' => Tools::getValue("id_group"),
			'group_title' => $groupObj->title,
			'languages' => $this->context->controller->getLanguages(),
			'previewLink' => Context::getContext()->link->getModuleLink($this->name, 'preview', array('secure_key' => $this->secure_key)),
			'msecure_key' => $this->secure_key,
			'currentSliderID' => $this->_currentSlider->id
		));

		return $this->display(__FILE__, 'list.tpl');
	}

	/*
	 * return group config form
	 */

	public function renderConfig()
	{
		$description = $this->l('Add New Slider');

		$transition = array(array('id' => 'random', 'name' => $this->l('Random')), array('id' => 'slidehorizontal', 'name' => $this->l('Slide Horizontal')), array('id' => 'slidevertical', 'name' => $this->l('Slide Vertical')),
			array('id' => 'boxslide', 'name' => $this->l('Box Slide')), array('id' => 'boxfade', 'name' => $this->l('Box Fade')), array('id' => 'slotzoom-horizontal', 'name' => $this->l('Slot Zoom Horizontal')),
			array('id' => 'slotslide-horizontal', 'name' => $this->l('Slot Slide Horizontal')), array('id' => 'slotfade-horizontal', 'name' => $this->l('Slot Fade Horizontal')), array('id' => 'slotzoom-vertical', 'name' => $this->l('Slot Zoom Vertical')),
			array('id' => 'slotslide-vertical', 'name' => $this->l('Slot Slide Vertical')), array('id' => 'slotfade-vertical', 'name' => $this->l('Slot Fade Vertical')), array('id' => 'curtain-1', 'name' => $this->l('Curtain 1')),
			array('id' => 'curtain-2', 'name' => $this->l('Curtain 2')), array('id' => 'curtain-3', 'name' => $this->l('Curtain 3')), array('id' => 'slideleft', 'name' => $this->l('Slide Left')),
			array('id' => 'slideright', 'name' => $this->l('Slide Right')), array('id' => 'slideup', 'name' => $this->l('Slide Up')), array('id' => 'slidedown', 'name' => $this->l('Slide Down')),
			array('id' => 'papercut', 'name' => $this->l('Page Cut')), array('id' => '3dcurtain-horizontal', 'name' => $this->l('3dcurtain Horizontal')), array('id' => '3dcurtain-vertical', 'name' => $this->l('3dcurtain Vertical')),
			array('id' => 'flyin', 'name' => $this->l('Fly In')), array('id' => 'turnoff', 'name' => $this->l('Turn Off')), array('id' => 'custom-1', 'name' => $this->l('Custom 1')),
			array('id' => 'custom-2', 'name' => $this->l('Custom 2')), array('id' => 'custom-3', 'name' => $this->l('Custom 3')), array('id' => 'custom-4', 'name' => $this->l('Custom 4'))
		);


		if (!Tools::isSubmit("deleteSlider") && !Tools::isSubmit("addNewSlider") && !Tools::isSubmit("showsliders")) {

			$description = $this->l('You are editting slider:') . " " . $this->_currentSlider->title[$this->context->language->id];
		}


		//$fullWidthVideo = array(array("id"=>0,"name"=>$this->l("No")),array("id"=>"youtube","name"=>"Youtube"),array("id"=>"vimeo","name"=>"Vimeo"));
		//general config
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $description,
					'icon' => 'icon-cogs'
				),
				//'description' =>$description,
				'input' => array(
					array(
						'type' => 'slider_button',
						'name' => 'slider_button',
						'lang' => FALSE,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Slider Title:'),
						'name' => 'title',
						'class' => 'slider-title',
						'required' => 1,
						'lang' => true,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Active:'),
						'name' => 'active_slide',
						'is_bool' => true,
						'values' => $this->getSwitchValue('active'),
					),
					array(
						'type' => 'select',
						'label' => $this->l('Transition:'),
						'name' => 'slider[transition]',
						'options' => array(
							'query' => $transition,
							'id' => 'id',
							'name' => 'name',
						)
					),
					array(
						'type' => 'text',
						'label' => $this->l('Slot Amount:'),
						'name' => 'slider[slot]',
						'lang' => false,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Transition Rotation:'),
						'name' => 'slider[rotation]',
						'lang' => false,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Transition Duration:'),
						'name' => 'slider[duration]',
						'lang' => false,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Delay:'),
						'name' => 'slider[delay]',
						'lang' => FALSE,
					),
					array(
						'type' => 'select',
						'label' => $this->l('Group:'),
						'name' => 'slider[group_id]',
						'options' => array(
							'query' => LeoSliderGroup::getGroupOption(),
							'id' => 'id',
							'name' => 'name',
						)
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Enable Link:'),
						'name' => 'slider[enable_link]',
						'is_bool' => true,
						'lang' => true,
						'values' => $this->getSwitchValue('enable_link'),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Link:'),
						'name' => 'link',
						'lang' => true,
					),
					array(
						'type' => 'select',
						'label' => $this->l('Link Open in:'),
						'name' => 'slider[target]',
						'options' => array(
							'query' => Status::getInstance()->getSliderTargetOption(),
							'id' => 'id',
							'name' => 'name',
						)
					),
					array(
						'type' => 'datetime',
						'label' => $this->l('Start Date Time:'),
						'name' => 'slider[start_date_time]',
						'lang' => FALSE,
					),
					array(
						'type' => 'datetime',
						'label' => $this->l('Start End Time:'),
						'name' => 'slider[end_date_time]',
						'lang' => FALSE,
					),
					//thumb + main image
					array(
						'type' => 'file_lang',
						'label' => $this->l('Thumbnail:'),
						'name' => 'thumbnail',
						'lang' => true,
					),
					array(
						'type' => 'video_config',
						'label' => $this->l('Video:'),
						'name' => 'slider[video]',
						'lang' => true,
					)
				)
			),
		);

		if (Tools::getValue('id_slide') && $this->slideExists((int) Tools::getValue('id_slide'))) {
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_slide');
		}

		$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_group');


		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$helper->module = $this;
		$helper->name_controller = 'sliderlayer';
		$lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitSlider';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getSliderFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'sliderGroup' => $this->groupData,
			'sliderTransition' => $transition,
			'psBaseModuleUri' => $this->img_url
		);

		return $helper->generateForm(array($fields_form));
	}

	/*
	 * generate data
	 */

	public function getSliderFieldsValues()
	{
		$fields = array();
		$slide = $this->_currentSlider;

		if (isset($this->_currentSlider->id) && $this->_currentSlider->id) {
			$fields['id_slide'] = (int) $this->_currentSlider->id;
			$slide = $this->_currentSlider;
			//gendata for config
			$this->_sliderData = array_merge($this->_sliderData, Tools::jsonDecode(SliderLayer::base64Decode($slide->params), true));
		}

		$fields['active_slide'] = Tools::getValue('active_slide', $slide->active);
		$fields['has_picture'] = true;
		$fields['id_group'] = Tools::getValue('id_group', $slide->id_group);
		$fields['slider[group_id]'] = Tools::getValue('id_group', $slide->id_group);

		$languages = Language::getLanguages(false);


		foreach ($languages as $lang)
		{
			$fields['image'][$lang['id_lang']] = Tools::getValue('image_' . (int) $lang['id_lang'], $slide->image[$lang['id_lang']]);
			$fields['thumbnail'][$lang['id_lang']] = Tools::getValue('thumbnail_' . (int) $lang['id_lang'], $slide->thumbnail[$lang['id_lang']]);
			$fields['title'][$lang['id_lang']] = Tools::getValue('title_' . (int) $lang['id_lang'], $slide->title[$lang['id_lang']]);
			$fields['link'][$lang['id_lang']] = Tools::getValue('link_' . (int) $lang['id_lang'], $slide->link[$lang['id_lang']]);
			if ($slide->video) {
				if ($slide->video[(int) $lang['id_lang']])
					foreach (Tools::jsonDecode(SliderLayer::base64Decode($slide->video[(int) $lang['id_lang']]), true) as $key => $value)
					{
						$fields[$key][$lang['id_lang']] = Tools::getValue($key . "_." . (int) $lang['id_lang'], $value);
					}
			} else {
				$fields['usevideo'][$lang['id_lang']] = 0;
				$fields['videoid'][$lang['id_lang']] = "";
				$fields['videoauto'][$lang['id_lang']] = 0;
				$fields['background_color'][$lang['id_lang']] = "";
			}
		}
		//slider no lang
		foreach ($this->_sliderData as $key => $value)
		{
			$fields["slider[" . $key . "]"] = Tools::getValue("slider[" . $key . "]", $value);
		}
		//slider with lang
		return $fields;
	}

	/*
	 * slider Editor
	 */

	public function renderSliderForm()
	{
		$layerAnimation = array(array('id' => 'fade', 'name' => $this->l('Fade')), array('id' => 'sft', 'name' => $this->l('Short from Top')), array('id' => 'sfb', 'name' => $this->l('Short from Bottom')),
			array('id' => 'sfr', 'name' => $this->l('Short from Right')), array('id' => 'sfl', 'name' => $this->l('Short from Left')), array('id' => 'lft', 'name' => $this->l('Long from Top')),
			array('id' => 'lfb', 'name' => $this->l('Long from Bottom')), array('id' => 'lfr', 'name' => $this->l('Long from Right')), array('id' => 'lfl', 'name' => $this->l('Long from Left')),
			array('id' => 'randomrotate', 'name' => $this->l('Random Rotate')));
		$layers = array();
		if ($this->_currentSlider->layersparams) {
			$layers = array();
			//echo "<pre>";print_r($this->_currentSlider->layersparams);die;

			foreach ($this->_currentSlider->layersparams as $key => $val)
			{
				$layer = Tools::jsonDecode(SliderLayer::base64Decode($val), true);
				//$layer = $std->layers;
				if ($layer)
//					foreach ($layer as $k => &$l)
					foreach ($layer as &$l)
					{
						if (isset($l['layer_caption']))
							$l['layer_caption'] = addslashes(str_replace("'", '&apos;', html_entity_decode(str_replace(array("\n", "\r", "\t"), '', utf8_decode($l['layer_caption'])), ENT_QUOTES, 'UTF-8')));
					}

				$layers[] = array("langID" => $key, "content" => Tools::jsonEncode($layer));
			}
		}
		//echo "<pre>";print_r($layers);die;
		$slideImg = $this->_currentSlider->image;
		$sliderBack = array();
		if ($this->_currentSlider->video)
			foreach ($this->_currentSlider->video as $key => $val)
			{
				$video = Tools::jsonDecode(SliderLayer::base64Decode($val), true);
				$sliderBack[$key] = "";
				if (isset($video["background_color"]))
					$sliderBack[$key] = $video["background_color"];
			}
		//echo "<pre>";print_r($sliderBack);die;
		$this->context->smarty->assign(array(
			'link' => $this->context->link,
			'slideImg' => $slideImg,
			'sliderBack' => $sliderBack,
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'layerAnimation' => $layerAnimation,
			'sliderGroup' => $this->groupData,
			'layers' => $layers,
			'ajaxfilelink' => Context::getContext()->link->getAdminLink('AdminLeoSliderLayer'),
			'formLink' => _MODULE_DIR_ . $this->name . '/ajax_' . $this->name . '.php?secure_key=' . $this->secure_key . '&action=submitslider',
			'psBaseModuleUri' => $this->img_url,
			'previewLink' => Context::getContext()->link->getModuleLink($this->name, 'preview', array('secure_key' => $this->secure_key)),
			'msecure_key' => $this->secure_key,
			'id_group' => Tools::getValue("id_group"),
			'id_slide' => $this->_currentSlider->id,
			'delay' => SliderLayer::showDelay((int) Tools::getValue('id_slide'), $this->_sliderData['delay'], $this->groupData['delay']),
		));

		return $this->display(__FILE__, 'slider_editor.tpl');
	}

	public function checkExistAnyGroup()
	{
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'leosliderlayer_groups gr WHERE gr.id_shop = ' . (int) $id_shop);
	}

	/*
	 * get group via hookname
	 */

	public function getSliderGroupByHook($hookName = '', $active = 1)
	{
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                    SELECT *
                    FROM ' . _DB_PREFIX_ . 'leosliderlayer_groups gr
                    WHERE gr.id_shop = ' . (int) $id_shop . '
                    AND gr.hook = "' . $hookName . '"' .
						($active ? ' AND gr.`active` = 1' : ' ') . '
                    ORDER BY gr.id_leosliderlayer_groups');
	}

	/*
	 * get all slider data
	 */

	public function getSlides($id_group, $active = null)
	{
		$this->context = Context::getContext();
		$id_lang = $this->context->language->id;

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                    SELECT lsl.`id_leosliderlayer_slides` as id_slide,
                                      lsl.*,lsll.*
                    FROM ' . _DB_PREFIX_ . 'leosliderlayer_slides lsl
                    LEFT JOIN ' . _DB_PREFIX_ . 'leosliderlayer_slides_lang lsll ON (lsl.id_leosliderlayer_slides = lsll.id_leosliderlayer_slides)
                    WHERE lsl.id_group = ' . (int) $id_group . '
                    AND lsll.id_lang = ' . (int) $id_lang .
						($active ? ' AND lsl.`active` = 1' : ' ') . '
                    ORDER BY lsl.position');
	}

	/*
	 * return list group
	 */

	public function renderGroupList()
	{
		$obj = new LeoSliderGroup();
		$id_shop = $this->context->shop->id;
		$groups = $obj->getGroups(null, $id_shop);

		foreach ($groups as $key => $group)
		{
			if ($group['id_leosliderlayer_groups'] == Tools::getValue("id_group") || (!Tools::getValue("id_group") && !Tools::isSubmit("addNewGroup") && $group['id_leosliderlayer_groups'] == Configuration::get('LEOSLIDERLAYER_GROUP_DE'))) {
				$this->_currentGroup["id_group"] = $group['id_leosliderlayer_groups'];
				$this->_currentGroup["title"] = $group['title'];

				$params = Tools::jsonDecode(SliderLayer::base64Decode($group["params"]), true);
				if ($params)
					$groupResult = array();
					foreach ($params as $k => $v)
					{
						$groupResult[$k] = $v;
					}
				$groupResult["title"] = $group["title"];
				$groupResult["id_leosliderlayer_groups"] = $group["id_leosliderlayer_groups"];
				$groupResult["id_shop"] = $group["id_shop"];
				$groupResult["hook"] = $group["hook"];
				$groupResult["active"] = $group["active"];

				if ($groupResult)
					$this->groupData = array_merge($this->groupData, $groupResult);
			}

			$groups[$key]['status'] = $this->displayGStatus($group['id_leosliderlayer_groups'], $group['active']);
		}
		$this->context->smarty->assign(array(
			'link' => $this->context->link,
			'groups' => $groups,
			'curentGroup' => $this->_currentGroup["id_group"],
			'languages' => $this->context->controller->getLanguages(),
			'exportLink' => Context::getContext()->link->getAdminLink('AdminLeoSliderLayer') . "&ajax=1&exportGroup=1",
			'previewLink' => Context::getContext()->link->getModuleLink($this->name, 'preview', array('secure_key' => $this->secure_key)),
			'msecure_key' => $this->secure_key
		));

		return $this->display(__FILE__, 'grouplist.tpl');
	}

	/*
	 * return group config form
	 */

	public function renderGroupConfig()
	{
		$description = $this->l('Add New Group');
		if (!Tools::isSubmit("deletegroup") && !Tools::isSubmit("addNewGroup") && $this->_currentGroup["id_group"]) {
			$description = $this->l('You are editting group:') . " " . $this->_currentGroup["title"];
		}
		$selectHook = array();
		foreach ($this->_hookSupport as $value)
		{
			$selectHook[] = array("id" => $value, "name" => $value);
		}

		$fullWidth = array(array("id" => "", "name" => $this->l("Boxed")),
			array("id" => "fullwidth", "name" => $this->l("Fullwidth")),
			array("id" => "fullscreen", "name" => $this->l("Fullscreen")));

		$shadowType = array(array("id" => 0, "name" => $this->l("No Shadown")), array("id" => "1", "name" => 1),
			array("id" => "2", "name" => 2), array("id" => "3", "name" => 3));

		$timeLinerPosition = array(array("id" => "bottom", "name" => $this->l("Bottom")), array("id" => "top", "name" => $this->l("Top")));

		$arrayCol = array('12', '10', '9-6', '9', '8', '7-2', '6', '4-8', '4', '3', '2-4', '2');

		$navigatorType = array(array("id" => "none", "name" => $this->l("None")), array("id" => "bullet", "name" => $this->l("Bullet")),
			array("id" => "thumb", "name" => $this->l("Thumbnail"))
//            , array("id" => "both", "name" => $this->l("Both"))
		);
		$navigatorArrows = array(array("id" => "none", "name" => $this->l("None")), array("id" => "nexttobullets", "name" => $this->l("Next To Bullets")),
			array("id" => "verticalcentered", "name" => $this->l("Vertical Colorentered")));

		$navigationStyle = array(array("id" => "round", "name" => $this->l("Round")), array("id" => "navbar", "name" => $this->l("Navbar")),
			array("id" => "round-old", "name" => $this->l("Round Old")), array("id" => "square-old", "name" => $this->l("Square Old")), array("id" => "navbar-old", "name" => $this->l("Navbar Old")));

//		$hiden_phone = array(array("id" => "", "name" => $this->l("None")), array("id" => "hidden-xs", "name" => $this->l("hidden in phone")),
//			array("id" => "hidden-sm", "name" => $this->l("Hidden in tablet")));

		$hidden_config = array('hidden-lg' => $this->l('Hidden in Large devices'), 'hidden-md' => $this->l('Hidden in Medium devices'),
			'hidden-sm' => $this->l('Hidden in Small devices'), 'hidden-xs' => $this->l('Hidden in Extra small devices'), 'hidden-sp' => $this->l('Hidden in Smart Phone'));

		//general config
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $description,
					'icon' => 'icon-cogs'
				),
				//'description' =>$description,
				'input' => array(
					array(
						'type' => 'group_button',
						'id_group' => $this->_currentGroup["id_group"],
						'name' => 'group_button',
						'lang' => FALSE,
					),
					array(
						'type' => 'sperator_form',
						'text' => $this->l('General Setting'),
						'name' => 'sperator_form',
						'show_button' => 1,
						'lang' => FALSE,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Group Title:'),
						'name' => 'title_group',
						'lang' => FALSE,
						'required' => 1
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Active:'),
						'name' => 'active_group',
						'is_bool' => true,
						'values' => $this->getSwitchValue('active'),
					),
					array(
						'type' => 'select',
						'label' => $this->l('Show in Hook:'),
						'name' => 'hook_group',
						'options' => array(
							'query' => $selectHook,
							'id' => 'id',
							'name' => 'name',
						)
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Auto Play:'),
						'name' => 'group[auto_play]',
						'is_bool' => true,
						'values' => $this->getSwitchValue('active'),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Delay:'),
						'name' => 'group[delay]',
						'lang' => FALSE,
					),
					array(
						'type' => 'select',
						'label' => $this->l('Slideshow Width Mode:'),
						'name' => 'group[fullwidth]',
						'class' => 'slideshow-mode',
						'options' => array(
							'query' => $fullWidth,
							'id' => 'id',
							'name' => 'name',
						)
					),
					array(
						'type' => 'text',
						'label' => $this->l('Start With Slide:'),
						'name' => 'group[start_with_slide]',
						'lang' => FALSE,
					),
					array(
						'type' => 'col_width',
						'label' => $this->l('Medium and Large Desktops Width:'),
						'name' => 'group[md_width]',
						'class' => 'mode-width mode-',
						'lang' => FALSE
					),
					array(
						'type' => 'col_width',
						'label' => $this->l('Small devices Tablets Width:'),
						'name' => 'group[sm_width]',
						'class' => 'mode-width mode-',
						'arrayVal' => $arrayCol,
						'lang' => FALSE
					),
					array(
						'type' => 'col_width',
						'label' => $this->l('Extra small devices Phones:'),
						'name' => 'group[xs_width]',
						'class' => 'mode-width mode-',
						'arrayVal' => $arrayCol,
						'lang' => FALSE
					),
					array(
						'type' => 'sperator_form',
						'text' => $this->l('Mode Boxed: You can config width of slideshow. It will display float with other module'),
						'class' => 'alert alert-warning mode-width mode-',
						'name' => 'sperator_form',
						'lang' => FALSE,
					),
					array(
						'type' => 'sperator_form',
						'text' => $this->l('Mode FullScreen: The slideshow will show full in your Web browser. You have to disable other module in hook_slideshow'),
						'class' => 'alert alert-warning mode-width mode-fullscreen',
						'name' => 'sperator_form',
						'lang' => FALSE,
					),
					array(
						'type' => 'sperator_form',
						'text' => $this->l('Mode FullWidth: The slideshow will show 100% in container of hook_slideshow. You have to config width of other module in hook_slideshow'),
						'class' => 'alert alert-warning mode-width mode-fullwidth',
						'name' => 'sperator_form',
						'lang' => FALSE,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Touch Mobile'),
						'name' => 'group[touch_mobile]',
						'is_bool' => true,
						'values' => $this->getSwitchValue('touch_mobile'),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Stop On Hover'),
						'name' => 'group[stop_on_hover]',
						'is_bool' => true,
						'values' => $this->getSwitchValue('stop_on_hover'),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Shuffle Mode'),
						'name' => 'group[shuffle_mode]',
						'is_bool' => true,
						'values' => $this->getSwitchValue('shuffle_mode'),
					),
					array(
						'type' => 'sperator_form',
						'text' => $this->l('Css Setting'),
						'name' => 'sperator_form',
						'lang' => FALSE,
					),
					array(
						'type' => 'select',
						'label' => $this->l('Shadow Type:'),
						'name' => 'group[shadow_type]',
						'options' => array(
							'query' => $shadowType,
							'id' => 'id',
							'name' => 'name',
						)
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show Time Line'),
						'name' => 'group[show_time_line]',
						'is_bool' => true,
						'values' => $this->getSwitchValue('show_time_line'),
					),
					array(
						'type' => 'select',
						'label' => $this->l('Time Liner Position'),
						'name' => 'group[time_line_position]',
						'options' => array(
							'query' => $timeLinerPosition,
							'id' => 'id',
							'name' => 'name',
						)
					),
					array(
						'type' => 'text',
						'label' => $this->l('Margin:'),
						'name' => 'group[margin]',
						'lang' => FALSE,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Padding(border):'),
						'name' => 'group[padding]',
						'lang' => FALSE,
					),
					array(
						'type' => 'color',
						'label' => $this->l('Background Color:'),
						'name' => 'group[background_color]',
						'lang' => FALSE,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show Background Image:'),
						'name' => 'group[background_image]',
						'is_bool' => true,
						'values' => $this->getSwitchValue('background_image'),
						'desc' => $this->l("This configuration will override back-ground color config"),
					),
					array(
						'type' => 'group_background',
						'label' => $this->l('Background URL:'),
						'name' => 'group[background_url]',
						'id' => 'background_url',
						'lang' => FALSE
					),
					array(
						'type' => 'group_class',
						'label' => $this->l('Group Class:'),
						'name' => 'group[group_class]'
					),
					array(
						'type' => 'sperator_form',
						'text' => $this->l('Navigator'),
						'name' => 'sperator_form',
						'lang' => FALSE,
					),
					array(
						'type' => 'select',
						'label' => $this->l('Navigator Type:'),
						'name' => 'group[navigator_type]',
						'options' => array(
							'query' => $navigatorType,
							'id' => 'id',
							'name' => 'name',
						),
						'desc' => $this->l('Thumbnail   ** In Fullwidth version thumbs wont be displayed  if navigation offset set to shwop thumbs outside of the container ! Thumbs must be showen in the container!')
					),
					array(
						'type' => 'select',
						'label' => $this->l('Arrows:'),
						'name' => 'group[navigator_arrows]',
						'options' => array(
							'query' => $navigatorArrows,
							'id' => 'id',
							'name' => 'name',
						),
						'desc' => $this->l('Next to Bullets only apply for Navigator Type: Bullets')
					),
					array(
						'type' => 'select',
						'label' => $this->l('Style:'),
						'name' => 'group[navigation_style]',
						'options' => array(
							'query' => $navigationStyle,
							'id' => 'id',
							'name' => 'name',
						)
					),
//                    array(
//                        'type' => 'text',
//                        'label' => $this->l('Offset Horizontal:'),
//                        'name' => 'group[offset_horizontal]',
//                        'desc' => $this->l('The Bar is centered but could be moved this pixel count left(e.g. -10) or right (Default: 0)  ** By resizing the banner, it will be always centered !!'),
//                        'lang' => FALSE,
//                    ),
//                    array(
//                        'type' => 'text',
//                        'label' => $this->l('Offset Vertical:'),
//                        'name' => 'group[offset_vertical]',
//                        'desc' => $this->l('The Bar is bound to the bottom but could be moved this pixel count up (e. g. -20) or down (Default: 20)'),
//                        'lang' => FALSE,
//                    ),
					array(
						'type' => 'switch',
						'label' => $this->l('Always Show Navigator'),
						'name' => 'group[show_navigator]',
						'is_bool' => true,
						'values' => $this->getSwitchValue('show_navigator'),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Hide Navigator After:'),
						'name' => 'group[hide_navigator_after]',
						'lang' => FALSE,
					),
					array(
						'type' => 'sperator_form',
						'text' => $this->l('Image Setting'),
						'name' => 'sperator_form',
						'lang' => FALSE,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Image Cropping:'),
						'name' => 'group[image_cropping]',
						'is_bool' => true,
						'desc' => $this->l('Auto turn off is you use mode fullscreen for slideshow'),
						'values' => $this->getSwitchValue('image_cropping'),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Image Width:'),
						'name' => 'group[width]',
						'lang' => FALSE,
						'desc' => $this->l('Use for resize image and Max-Height')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Image Height:'),
						'name' => 'group[height]',
						'lang' => FALSE,
						'desc' => $this->l('Use for resize image and Max-Height')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Thumbnail Width:'),
						'name' => 'group[thumbnail_width]',
						'lang' => FALSE,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Thumbnail Height:'),
						'name' => 'group[thumbnail_height]',
						'lang' => FALSE,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Number of Thumbnails:'),
						'name' => 'group[thumbnail_amount]',
						'lang' => FALSE,
					)
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'class' => 'btn btn-default')
			),
		);

		if (Tools::isSubmit('id_group') && LeoSliderGroup::groupExists((int) Tools::getValue('id_group'))) {
			//$slide = new LeoSliderGroup((int)Tools::getValue('id_group'));
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_group');
		} else if ($this->_currentGroup["id_group"] && LeoSliderGroup::groupExists($this->_currentGroup["id_group"])) {
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_group');
		}


		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$helper->module = $this;
		$helper->name_controller = 'sliderlayer';
		$lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitGroup';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getGroupFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'exportLink' => Context::getContext()->link->getAdminLink('AdminLeoSliderLayer') . "&exportGroup=1",
			'psBaseModuleUri' => $this->img_url,
			'ajaxfilelink' => Context::getContext()->link->getAdminLink('AdminLeoSliderLayer'),
			'leo_width' => $arrayCol,
			'hidden_config' => $hidden_config
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getSwitchValue($id)
	{
		return array(array('id' => $id . '_on', 'value' => 1, 'label' => $this->l('Yes')),
			array('id' => $id . '_off', 'value' => 0, 'label' => $this->l('No')));
	}

	public function getGroupFieldsValues()
	{
		$group = array();
		$field = array("id_leosliderlayer_groups", "title", "id_shop", "hook", "active");
		foreach ($this->groupData as $key => $value)
		{
			if (in_array($key, $field)) {
				if ($key == "id_leosliderlayer_groups")
					$group["id_group"] = $value;
				else
					$group[$key . "_group"] = $value;
				continue;
			}
			$group["group[" . $key . "]"] = $value;
		}
		return $group;
	}

	public function postValidation()
	{
		$errors = array();

		if (Tools::isSubmit('submitGroup')) {
			if (Tools::isSubmit('id_group')) {
				if (!Validate::isInt(Tools::getValue('id_group')) && !LeoSliderGroup::groupExists(Tools::getValue('id_group')))
					$errors[] = $this->l('Invalid id_group');
			}
			$groupValue = Tools::getValue("group");
			/* Checks state (active) */
			if (!Tools::getValue('title_group'))
				$errors[] = $this->l('Invalid title group');
			if (!Validate::isInt(Tools::getValue('active_group')) || (Tools::getValue('active_group') != 0 && Tools::getValue('active_group') != 1))
				$errors[] = $this->l('Invalid group state');

			if (!Validate::isInt($groupValue['touch_mobile']) || ($groupValue['touch_mobile'] != 0 && $groupValue['touch_mobile'] != 1))
				$errors[] = $this->l('Invalid touch mobile state');
			if (!Validate::isInt($groupValue['stop_on_hover']) || ($groupValue['stop_on_hover'] != 0 && $groupValue['stop_on_hover'] != 1))
				$errors[] = $this->l('Invalid stop on hover state');
			if (!Validate::isInt($groupValue['shuffle_mode']) || ($groupValue['shuffle_mode'] != 0 && $groupValue['shuffle_mode'] != 1))
				$errors[] = $this->l('Invalid Shuffle Mode state');
			if (!Validate::isInt($groupValue['image_cropping']) || ($groupValue['image_cropping'] != 0 && $groupValue['image_cropping'] != 1))
				$errors[] = $this->l('Invalid Image Cropping state');
			if (!Validate::isInt($groupValue['show_time_line']) || ($groupValue['show_time_line'] != 0 && $groupValue['show_time_line'] != 1))
				$errors[] = $this->l('Invalid Show Time Line state');
			if (!Validate::isInt($groupValue['background_image']) || ($groupValue['background_image'] != 0 && $groupValue['background_image'] != 1))
				$errors[] = $this->l('Invalid Show Background Image state');
			if (!Validate::isInt($groupValue['show_navigator']) || ($groupValue['show_navigator'] != 0 && $groupValue['show_navigator'] != 1))
				$errors[] = $this->l('Invalid Always Show Navigator state');

			//check interger isUnsignedInt
			$intArray = array("delay" => $this->l('Invalid Delay value'), "start_with_slide" => $this->l('Invalid Start With Slide value'), "width" => $this->l('Invalid Width value'), "height" => $this->l('Invalid Height value'),
				"offset_horizontal" => $this->l('Invalid Offset Horizontal value'), "offset_vertical" => $this->l('Invalid Offset Vertical value'), "hide_navigator_after" => $this->l('Invalid Hide Navigator After value'),
				"thumbnail_width" => $this->l('Invalid Thumbnail Width value'), "thumbnail_height" => $this->l('Invalid Thumbnail Height value'), "thumbnail_amount" => $this->l('Invalid Thumbnail Amount value'));

			foreach ($intArray as $key => $value)
			{
				if (!Validate::isInt($groupValue[$key]) && $groupValue[$key] != "")
					$errors[] = $value;
			}
			if (!Validate::isColor(Tools::getValue("background_color")))
				$errors[] = $this->l('Invalid Background color value');
		}

		/* Display errors if needed */
		if (count($errors)) {
			$this->_error_text .= implode('<br>', $errors);
			$this->_html .= $this->displayError(implode('<br />', $errors));
			return false;
		}

		/* Returns if validation is ok */
		return true;
	}

	public function getErrorLog()
	{
		return $this->_error_text;
	}

	private function _postProcess()
	{
		$errors = array();
		/* Processes Slider */
		if (Tools::isSubmit('submitGroup')) {/* Sets ID if needed */
			if (Tools::getValue('id_group')) {
				$group = new LeoSliderGroup((int) Tools::getValue('id_group'));
				if (!Validate::isLoadedObject($group)) {
					$this->_html .= $this->displayError($this->l('Invalid id_group'));
					return;
				}
			} else
				$group = new LeoSliderGroup();

			/* Sets position */
			$group->title = Tools::getValue('title_group');
			/* Sets active */
			$group->active = (int) Tools::getValue('active_group');
			$context = Context::getContext();
			$group->id_shop = $context->shop->id;
			$group->hook = Tools::getValue('hook_group');

			$params = Tools::getValue("group");
			$group->params = SliderLayer::base64Encode(Tools::jsonEncode($params));


			/* Adds */
			if (!Tools::getValue('id_group')) {
				if (!$group->add())
					$errors[] = $this->displayError($this->l('The group could not be added.'));
				else {
					$this->clearCache();
					Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&editgroup=1&id_group=' . $group->id);
				}
			}
			/* Update */ else {
				if (!$group->update())
					$errors[] = $this->displayError($this->l('The group could not be updated.'));
				else {
					$this->clearCache();
					Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&editgroup=1&id_group=' . $group->id);
				}
			}
			//save in config to edit next time
			$this->clearCache();
		} /* Process Slide status */ elseif (Tools::isSubmit('changeGStatus') && Tools::isSubmit('id_group')) {
			$group = new LeoSliderGroup((int) Tools::getValue('id_group'));
			if ($group->active == 0)
				$group->active = 1;
			else
				$group->active = 0;
			$res = $group->update();
			$this->clearCache();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Change status of group was successful')) : $this->displayError($this->l('The configuration could not be updated.')));
		}elseif (Tools::isSubmit('deletegroup')) {
			$group = new LeoSliderGroup((int) Tools::getValue('id_group'));
			//delete slider of group
			$slider = $this->getSlides((int) Tools::getValue('id_group'));

			foreach ($slider as $value)
			{
				$sliderObj = new SliderLayer($value["id_leosliderlayer_slides"]);
				$sliderObj->delete();
			}

			$res = $group->delete();


			$this->clearCache();
			if (!$res)
				$this->_html .= $this->displayError('Could not delete');
			else
				$this->_html .= $this->displayConfirmation($this->l('Group deleted'));

			Tools::redirectAdmin('index.php?controller=AdminModules&token=' . Tools::getAdminTokenLite('AdminModules') . '&configure=leosliderlayer&tab_module=leotheme&module_name=leosliderlayer&conf=4');
		}else if (Tools::isSubmit("changeStatus")) {
			$slide = new SliderLayer((int) Tools::getValue('sslider'));
			if ($slide->active == 0)
				$slide->active = 1;
			else
				$slide->active = 0;
			$res = $slide->update();
			$this->clearCache();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Change status of slide was successful')) : $this->displayError($this->l('The configuration could not be updated.')));
		}

		/* Display errors if needed */
		if (count($errors))
			$this->_html .= $this->displayError(implode('<br />', $errors));
		elseif (Tools::isSubmit('submitGroup'))
			$this->_html .= $this->displayConfirmation($this->l('Slide added'));
		elseif (Tools::isSubmit('submitGroup'))
			$this->_html .= $this->displayConfirmation($this->l('Slide added'));
	}

	private function _prepareHook($hookName)
	{
		$tpl = 'leosliderlayer.tpl';
		if (!$this->isCached($tpl, $this->getCacheId($hookName . '_' . $this->name))) {
			//die('aaaa');
			if (!is_dir(_PS_ROOT_DIR_ . '/cache/' . $this->name)) {
				mkdir(_PS_ROOT_DIR_ . '/cache/' . $this->name, 0755);
			}

			//get slider via hookname
			$group = $this->getSliderGroupByHook($hookName);
			if (!$group)
				return false;
			$sliders = $this->getSlides($group["id_leosliderlayer_groups"], 1);
			$sliders = SliderLayer::filterSlider($sliders, $this->_sliderData);
			if (!$sliders)
				return false;


			$sliderParams = Tools::jsonDecode(SliderLayer::base64Decode($group["params"]), true);
			$sliderParams = array_merge($this->groupData, $sliderParams);
			if (isset($sliderParams['fullwidth']) && (!empty($sliderParams['fullwidth']) || $sliderParams['fullwidth'] == 'boxed')) {
				$sliderParams['image_cropping'] = false;
			}
			$sliderParams['hide_navigator_after'] = $sliderParams['show_navigator'] ? 0 : $sliderParams['hide_navigator_after'];
			$sliderParams['slider_class'] = trim(isset($sliderParams['fullwidth']) && !empty($sliderParams['fullwidth']) ? $sliderParams['fullwidth'] : 'boxed');
			$sliderFullwidth = $sliderParams['slider_class'] == "boxed" ? "off" : "on";

			//generate back-ground
			if ($sliderParams["background_image"] && $sliderParams["background_url"] && file_exists($this->img_path . $sliderParams['background_url']))
				$sliderParams["background"] = 'background: url(' . $this->img_url . $sliderParams["background_url"] . ') no-repeat scroll left 0 ' . $sliderParams["background_color"] . ';';
			else
				$sliderParams["background"] = 'background-color:' . $sliderParams["background_color"];

			//include library genimage
			if (!class_exists('PhpThumbFactory')) {
				require_once _PS_MODULE_DIR_ . 'leosliderlayer/libs/phpthumb/ThumbLib.inc.php';
			}
			$whiteMainImg = __PS_BASE_URI__ . 'modules/' . $this->name . '/assets/white50.png';

			//process slider
			foreach ($sliders as $key => $slider)
			{
				$slider["layers"] = array();
				$slider['params'] = array_merge($this->_sliderData, Tools::jsonDecode(SliderLayer::base64Decode($slider["params"]), true));
				$slider['layersparams'] = Tools::jsonDecode(SliderLayer::base64Decode($slider["layersparams"]), true);
				$slider['video'] = Tools::jsonDecode(SliderLayer::base64Decode($slider["video"]), true);

				$slider['data_link'] = '';
				if ($slider['params']['enable_link'] && $slider['link']) {
					$slider['data_link'] = 'data-link="' . $slider['link'] . '"';
					$slider['data_target'] = 'data-target="' . SliderLayer::renderTarget($slider['params']['target']) . '"';
				} else {
					$slider['data_target'] = '';
				}

				$slider['data_delay'] = $slider['params']['delay'] ? 'data-delay="' . (int) $slider['params']['delay'] . '"' : '';

				//videoURL
				$slider['videoURL'] = '';
				if ($slider['video']['usevideo'] == 'youtube' || $slider['video']['usevideo'] == 'vimeo') {
					$slider['videoURL'] = 'http://player.vimeo.com/video/' . $slider['video']['videoid'] . '/';
					if ($slider['video']['usevideo'] == 'youtube')
						$slider['videoURL'] = 'http://www.youtube.com/embed/' . $slider['video']['videoid'] . '/';
				}
				$slider['background_color'] = '';
				if (isset($slider['video']['background_color']) && $slider['video']['background_color'])
					$slider['background_color'] = $slider['video']['background_color'];

				if ($slider['image'] == '')
					$slider['image'] = "blank.gif";
				if ($sliderParams['image_cropping']) {
					//gender main_image
					if ($slider['image'] && file_exists($this->img_path . $slider['image'])) {
						$slider['main_image'] = $this->renderThumb($slider['image'], $sliderParams['width'], $sliderParams['height']);
					} else
						$slider['main_image'] = $whiteMainImg;

					if ($slider['thumbnail'] && file_exists($this->img_path . $slider['thumbnail'])) {
						$slider['thumbnail'] = $this->renderThumb($slider['thumbnail'], $sliderParams['thumbnail_width'], $sliderParams['thumbnail_height']);
					} else if ($slider['image'] && file_exists($this->img_path . $slider['image'])) {
						$slider['thumbnail'] = $this->renderThumb($slider['image'], $sliderParams['thumbnail_width'], $sliderParams['thumbnail_height']);
					} else {
						$slider['thumbnail'] = $whiteMainImg;
					}
				} else {
					$slider['main_image'] = '';

					if ($slider['image'] && file_exists($this->img_path . $slider['image'])) {
						$slider['main_image'] = $this->img_url . $slider['image'];
					}

					if ($slider['thumbnail'] && file_exists($this->img_path . $slider['thumbnail'])) {
						$slider['thumbnail'] = $this->img_url . $slider['thumbnail'];
					} else if ($slider['image'] && file_exists($this->img_path . $slider['image'])) {
						$slider['thumbnail'] = $slider['main_image'];
					} else {
						$slider['thumbnail'] = $whiteMainImg;
					}
				}

				if ($slider['layersparams'])
					foreach ($slider['layersparams'] as &$layerCss)
					{
						$layerCssVal = '';
						if (isset($layerCss['layer_font_size']) && $layerCss['layer_font_size'])
							$layerCssVal = 'font-size:' . $layerCss['layer_font_size'];
						if (isset($layerCss['layer_background_color']) && $layerCss['layer_background_color'])
							$layerCssVal .= ($layerCssVal != '' ? ';' : '') . 'background-color:' . $layerCss['layer_background_color'];
						if (isset($layerCss['layer_color']) && $layerCss['layer_color'])
							$layerCssVal.= ($layerCssVal != '' ? ';' : '') . 'color:' . $layerCss['layer_color'];
						$layerCss['css'] = $layerCssVal;
						if (!isset($layerCss['layer_link']))
							$layerCss['layer_link'] = str_replace("_ASM_", "&", $slider['link']);
						$layerCss['layer_target'] = SliderLayer::renderTarget($slider['params']['target']);
						if (isset($layerCss['layer_caption']) && $layerCss['layer_caption'])
							$layerCss['layer_caption'] = utf8_decode($layerCss['layer_caption']);
					}

				$sliders[$key] = $slider;
			}
			//echo "<pre>";print_r($sliders);die;
			$sliderParams['slider_start_with_slide'] = LeoSliderGroup::showStartWithSlide($sliderParams['start_with_slide'], $sliders);
			$this->smarty->assign(array(
				'sliderParams' => $sliderParams,
				'sliders' => $sliders,
				'sliderIDRand' => rand(20, rand()),
				'sliderFullwidth' => $sliderFullwidth,
				'sliderImgUrl' => $this->img_url
			));
		}

		return true;
	}

	/*
	 * 
	 */

	public function renderThumb($src_file, $width, $height)
	{
		$subFolder = '/';
		if (!$src_file)
			return '';
		if (strpos($src_file, "/") !== false) {
			$path = @pathinfo($src_file);
			if (strpos($path['dirname'], "/") !== -1) {
				$subFolder = $path['dirname'] . '/';
				$folderList = explode("/", $path['dirname']);
				$tmpPFolder = '/';
				foreach ($folderList as $value)
				{
					if ($value) {
						if (!is_dir(_PS_ROOT_DIR_ . '/cache/' . $this->name . $tmpPFolder . $value)) {
							mkdir(_PS_ROOT_DIR_ . '/cache/' . $this->name . $tmpPFolder . $value, 0755);
						}
						$tmpPFolder .= $value . '/';
					}
				}
			}
			$imageName = $path['basename'];
		} else
			$imageName = $src_file;

		$path = '';
		if (file_exists($this->img_path . $src_file)) {
			//return image url
			$path = __PS_BASE_URI__ . 'cache/' . $this->name . $subFolder . $width . "_" . $height . "_" . $imageName;
			$savePath = _PS_ROOT_DIR_ . '/cache/' . $this->name . $subFolder . $width . "_" . $height . "_" . $imageName;
			if (!file_exists($savePath)) {
				$thumb = PhpThumbFactory::create($this->img_path . $src_file);
				$thumb->adaptiveResize($width, $height);
				$thumb->save($savePath);
			}
		}

		return $path;
	}

	public function _processHook($hookName)
	{
		$this->context->controller->addJS('http://apollotheme.com/upfiledownload/slidershow/jquery.themepunch.enablelog.js');
		$this->context->controller->addJS('http://apollotheme.com/upfiledownload/slidershow/jquery.themepunch.revolution.js');
		//$this->context->controller->addJS($this->_path . 'js/jquery.themepunch.revolution.min.js');
		$this->context->controller->addJS('http://apollotheme.com/upfiledownload/slidershow/jquery.themepunch.tools.min.js');
		$this->context->controller->addCSS(($this->_path) . 'css/typo.css', 'all');

		if (!$this->_prepareHook($hookName))
			return false;

		return $this->display(__FILE__, '' . $this->name . '.tpl', $this->getCacheId($hookName . '_' . $this->name));
	}

	public function hookDisplayTop()
	{
		return $this->_processHook("displayTop");
	}

	public function hookDisplayHeaderRight()
	{
		return $this->_processHook("displayHeaderRight");
	}

	public function hookDisplaySlideshow()
	{
		return $this->_processHook("displaySlideshow");
	}

	public function hookTopNavigation()
	{
		return $this->_processHook("topNavigation");
	}

	public function hookDisplayPromoteTop()
	{
		return $this->_processHook("displayPromoteTop");
	}

	public function hookDisplayRightColumn()
	{
		return $this->_processHook("displayRightColumn");
	}

	public function hookDisplayLeftColumn()
	{
		return $this->_processHook("displayLeftColumn");
	}

	public function hookDisplayHome()
	{
		return $this->_processHook("displayHome");
	}

	public function hookDisplayFooter()
	{
		return $this->_processHook("displayFooter");
	}

	public function hookDisplayBottom()
	{
		return $this->_processHook("displayBottom");
	}

	public function hookDisplayContentBottom()
	{
		return $this->_processHook("displayContentBottom");
	}

	public function hookDisplayFootNav()
	{
		return $this->_processHook("displayFootNav");
	}

	public function hookDisplayFooterTop()
	{
		return $this->_processHook("displayFooterTop");
	}

	public function hookDisplayFooterBottom()
	{
		return $this->_processHook("displayFooterBottom");
	}

	public function hookProductTabContent($params)
	{
		return $this->_processHook("productTabContent");
	}

	public function hookProductFooter($params)
	{
		return $this->_processHook("productFooter");
	}

	public function hookDisplayTopColumn()
	{
		return $this->_processHook("displayTopColumn");
	}

	public function displayFootNav()
	{
		return $this->_processHook("displayFootNav");
	}

	/*    public function getCacheId($name = null) {
	  if ($name === null && isset($this->context->smarty->tpl_vars['page_name']))
	  return parent::getCacheId($this->context->smarty->tpl_vars['page_name']->value);
	  return parent::getCacheId($name);
	  } */

	public function clearCache()
	{
		foreach ($this->_hookSupport as $val)
		{
			$this->_clearCache('' . $this->name . '.tpl', $val . '_' . $this->name);
		}
	}

	public function hookActionShopDataDuplication($params)
	{
		$groupList = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT gr.*
                            FROM `' . _DB_PREFIX_ . 'leosliderlayer_groups` gr
                            WHERE gr.`id_shop` = ' . (int) $params['old_id_shop']);
		foreach ($groupList as $list)
		{
			$group = new LeoSliderGroup();
			foreach ($list as $key => $value)
			{
				if ($key != "id" && $key != "id_shop")
					$group->{$key} = $value;
			}
			$group->id_shop = (int) $params['new_id_shop'];
			$group->add();

			//import slider
			$sliderList = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT sl.id_leosliderlayer_slides as id
                            FROM `' . _DB_PREFIX_ . 'leosliderlayer_slides` sl
                            WHERE sl.`id_group` = ' . (int) $list["id_leosliderlayer_groups"]);

			$fields = array("active", "image", "thumbnail", "video", "title", "layersparams", "title", "position", "link", "params");
			foreach ($sliderList as $key => $value)
			{
				$sliderOld = new SliderLayer($value["id"]);
				//print_r($sliderOld);die;
				$sliderNew = new SliderLayer();
				$sliderNew->id_group = $group->id;
				foreach ($fields as $field)
				{
					$sliderNew->{$field} = $sliderOld->{$field};
				}
				$sliderNew->add();
			}
		}

		$this->clearCache();
	}

	public function headerHTML()
	{
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;
		$this->context->controller->addCSS($this->_path . 'assets/admin/style.css');
		if (file_exists(_PS_THEME_DIR_ . 'css/modules/leosliderlayer/css/typo.css')) {
			$this->context->controller->addCSS(_PS_THEME_DIR_ . 'css/modules/leosliderlayer/css/typo.css');
		} else {
			$this->context->controller->addCSS($this->_path . 'css/typo.css');
		}
		$this->context->controller->addJS(_PS_JS_DIR_ . 'jquery/plugins/jquery.colorpicker.js');
		$this->context->controller->addJS($this->_path . 'assets/admin/script.js');
		$this->context->controller->addJqueryUI('ui.core');
		$this->context->controller->addJqueryUI('ui.widget');
		$this->context->controller->addJqueryUI('ui.mouse');
		$this->context->controller->addJqueryUI('ui.draggable');
		$this->context->controller->addJqueryUI('ui.sortable');

		//$this->context->controller->addJS($this->_path . 'assets/admin/jquery-ui-1.10.3.custom.min.js');

		$this->context->controller->addCSS(_PS_JS_DIR_ . 'jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css');

		$this->context->controller->addJqueryUI('ui.dialog');
		$this->context->controller->addJqueryPlugin('cooki-plugin');
		/* Style & js for fieldset 'slides configuration' */
		$html = '
        <style>
        #slides li {
            list-style: none;
            margin: 0 0 4px 0;
            padding: 10px;
            background-color: #F4E6C9;
            border: #CCCCCC solid 1px;
            color:#000;
        }
        </style>
        
        <script type="text/javascript">
                        $(function() {
                var $mySlides = $("#slides");
                $mySlides.sortable({
                    opacity: 0.6,
                    cursor: "move",
                    update: function() {
                        var order = $(this).sortable("serialize") + "&action=updateSlidesPosition";
                        $.post("' . AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&leoajax=1' . '" , order);
                        }
                    });
                $mySlides.hover(function() {
                    $(this).css("cursor","move");
                    },
                    function() {
                    $(this).css("cursor","auto");
                });
            });
        </script>';

		return $html;
	}

	public function getNextPosition()
	{
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                SELECT MAX(hss.`position`) AS `next_position`
                FROM `' . _DB_PREFIX_ . '' . $this->name . '_slides` hss, `' . _DB_PREFIX_ . '' . $this->name . '` hs
                WHERE hss.`id_' . $this->name . '_slides` = hs.`id_' . $this->name . '_slides` AND hs.`id_shop` = ' . (int) $this->context->shop->id
		);

		return ( ++$row['next_position']);
	}

	public function displayGStatus($id_group, $active)
	{
		$title = ((int) $active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
		$img = ((int) $active == 0 ? 'disabled.gif' : 'enabled.gif');
		$html = '<a href="' . AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&changeGStatus&id_group=' . (int) $id_group . '" title="' . $title . '"><img src="' . _PS_ADMIN_IMG_ . '' . $img . '" alt="" /></a>';
		return $html;
	}

	public function displayStatus($id_slide, $active, $group_id, $slide)
	{
		$title = ((int) $active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
//        $img = ((int) $active == 0 ? 'disabled.gif' : 'enabled.gif');

		$src_img = _PS_ADMIN_IMG_;
		$mod_slider = new SliderLayer();
		$mod_slider->mergeSlider($slide)->mergeParams($this->_sliderData);

		if ($mod_slider->getStatusTime() == Status::SLIDER_STATUS_DISABLE) {
			$img = 'disabled.gif';
		} elseif ($mod_slider->getStatusTime() == Status::SLIDER_STATUS_ENABLE) {
			$img = 'enabled.gif';
		} elseif ($mod_slider->getStatusTime() == Status::SLIDER_STATUS_COMING) {
			$img = 'coming.png';
			$src_img = _MODULE_DIR_ . 'leosliderlayer/img/';
		}
		$html = '<a href="' . AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&changeStatus&sslider=' . (int) $id_slide . '&showsliders=1&id_group=' . (int) $group_id . '" title="' . $title . '"><img src="' . $src_img . '' . $img . '" alt="" /></a>';
		return $html;
	}

	public function slideExists($id_slide)
	{
		$req = 'SELECT `id_' . $this->name . '_slides`
                FROM `' . _DB_PREFIX_ . '' . $this->name . '_slides`
                WHERE `id_' . $this->name . '_slides` = ' . (int) $id_slide;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}

	protected function getCacheId($name = null, $hook = '')
	{
		$cache_array = array(
			$name !== null ? $name : $this->name,
			$hook,
			date('Ymd'),
			(int) Tools::usingSecureMode(),
			(int) $this->context->shop->id,
			(int) Group::getCurrent()->id,
			(int) $this->context->language->id,
			(int) $this->context->currency->id,
			(int) $this->context->country->id,
			(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
		);
		return implode('|', $cache_array);
	}

	public function converParams($old_params = '')
	{
		$result = '';
		if ($old_params != '') {
			$data = Tools::unSerialize($old_params);
			$result = SliderLayer::base64Encode(Tools::jsonEncode($data));
		}
		return $result;
	}

}