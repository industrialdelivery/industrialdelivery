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

class AdminLeoSliderLayerController extends ModuleAdminController
{
	protected $max_image_size = null;
	public $themeName;
	public $img_path;
	public $img_url;

	public function __construct()
	{
		$this->bootstrap = true;
		$this->max_image_size = (int)Configuration::get('PS_PRODUCT_PICTURE_MAX_SIZE');
		parent::__construct();
		$this->themeName = Context::getContext()->shop->getTheme();
		$this->img_path = _PS_ALL_THEMES_DIR_.$this->themeName.'/img/modules/leosliderlayer/';
		$this->img_url = __PS_BASE_URI__.'themes/'.$this->themeName.'/img/modules/leosliderlayer/';
	}

	public function setMedia()
	{
		$this->addCss(__PS_BASE_URI__.str_replace('//', '/', 'modules/leosliderlayer').'/assets/admin/admincontroller.css', 'all');
		//_PS_THEME_DIR_
		return parent::setMedia();
	}

	public function postProcess()
	{
		if (($imgName = Tools::getValue('imgName', false)) !== false)
			unlink($this->img_path.$imgName);

		//export process
		if (Tools::getValue('exportGroup'))
		{
			$group = $this->getSliderGroupByID(Tools::getValue('id_group'));
			$sliders = $this->getSlidesByGroup(Tools::getValue('id_group'));
			$languageField = array('title', 'link', 'image', 'thumbnail', 'video', 'layersparams');

			$languages = Language::getLanguages();
			$langList = array();
			foreach ($languages as $lang)
				$langList[$lang['id_lang']] = $lang['iso_code'];

			foreach ($sliders as $slider)
			{
				$curentLang = 'en';
				foreach ($slider as $key => $value)
				{
					if ($key == 'id_lang')
					{
						$curentLang = $langList[$value];
						continue;
					}
					if (in_array($key, $languageField))
						$group['sliders'][$slider['id']][$key][$curentLang] = $value;
					else
						$group['sliders'][$slider['id']][$key] = $value;
				}
			}
			header('Content-Type: plain/text');
			header('Content-Disposition: Attachment; filename=export_group_'.Tools::getValue('id_group').'_'.time().'.txt');
			header('Pragma: no-cache');
			die(SliderLayer::base64Encode(Tools::jsonEncode($group)));
		}

		parent::postProcess();
	}

	public function importGroup()
	{
		$type = Tools::strtolower(Tools::substr(strrchr($_FILES['import_file']['name'], '.'), 1));

		if (isset($_FILES['import_file']) && $type == 'txt' && isset($_FILES['import_file']['tmp_name']) && !empty($_FILES['import_file']['tmp_name']))
		{
			include_once(_PS_MODULE_DIR_.'leosliderlayer/grouplayer.php');
			include_once(_PS_MODULE_DIR_.'leosliderlayer/sliderlayer.php');

			$content = Tools::file_get_contents($_FILES['import_file']['tmp_name']);
			$content = Tools::jsonDecode(SliderLayer::base64Decode($content), true);


			$languageField = array('title', 'link', 'image', 'thumbnail', 'video', 'layersparams');
			$languages = Language::getLanguages();
			$langList = array();
			foreach ($languages as $lang)
				$langList[$lang['iso_code']] = $lang['id_lang'];

			$override_group = Tools::getValue('override_group');

			//override or edit
			if ($override_group && LeoSliderGroup::groupExists($content['id_leosliderlayer_groups']))
			{
				$group = new LeoSliderGroup($content['id_leosliderlayer_groups']);
				//edit group
				$group = $this->setDataForGroup($group, $content);
				if (!$group->update())
					return false;
				
				LeoSliderGroup::deleteAllSlider($content['id_leosliderlayer_groups']);

				foreach ($content['sliders'] as $slider)
				{
					$obj = new SliderLayer();
					foreach ($slider as $key => $val)
					{
						if (in_array($key, $languageField))
						{
							foreach ($val as $keyLang => $valLang)
								$obj->{$key}[$langList[$keyLang]] = $valLang;
						}
						else
							$obj->{$key} = $val;
					}
					$obj->id_group = $group->id;
					if (isset($slider['id']) && $slider['id'] && SliderLayer::sliderExist($slider['id']))
						$obj->update();
					else
						$obj->add();
				}
			}
			else
			{
				$group = new LeoSliderGroup();
				$group = $this->setDataForGroup($group, $content);

				if (!$group->add())
					return false;

				foreach ($content['sliders'] as $slider)
				{
					$obj = new SliderLayer();
					foreach ($slider as $key => $val)
					{
						if (in_array($key, $languageField))
						{
							foreach ($val as $keyLang => $valLang)
								$obj->{$key}[$langList[$keyLang]] = $valLang;
						}
						else
							$obj->{$key} = $val;
					}
					$obj->id_group = $group->id;
					$obj->id = 0;
					$obj->add();
				}
			}
			//add new
			//return true;
		}
		Tools::redirectAdmin('index.php?controller=AdminModules&token='.Tools::getAdminTokenLite('AdminModules').'&configure=leosliderlayer&tab_module=leotheme&module_name=leosliderlayer&conf=4');
		//return false;
	}

	public function setDataForGroup($group, $content)
	{
		$group->title = $content['title'];
		$group->id_shop = $this->context->shop->id;
		$group->hook = $content['hook'];
		$group->active = $content['active'];
		$group->params = $content['params'];
		$group->sliders = $content['sliders'];
		return $group;
	}

	public function getSliderGroupByID($id_group)
	{
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
                    SELECT *
                    FROM '._DB_PREFIX_.'leosliderlayer_groups gr
                    WHERE gr.id_leosliderlayer_groups = '.(int)$id_group);
	}

	/**
	 * get all slider data
	 */
	public function getSlidesByGroup($id_group)
	{
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                    SELECT lsll.`id_lang`, lsl.`id_leosliderlayer_slides` as id, lsl.*,lsll.*
                    FROM '._DB_PREFIX_.'leosliderlayer_slides lsl
                    LEFT JOIN '._DB_PREFIX_.'leosliderlayer_slides_lang lsll ON (lsl.id_leosliderlayer_slides = lsll.id_leosliderlayer_slides)
                    WHERE lsl.id_group = '.(int)$id_group.'
                    ORDER BY lsl.position');
	}

	/**
	 * renderForm contains all necessary initialization needed for all tabs
	 *
	 * @return void
	 */
	public function renderList()
	{
		//this code for typo
		$typo = Tools::getValue('typo');
		if ($typo)
		{
			//check css file in theme
			if (file_exists(_PS_THEME_DIR_.'css/modules/leosliderlayer/css/typo.css'))
				$typoDir = _THEME_DIR_.'css/'.str_replace('//', '/', 'modules/leosliderlayer').'/css/typo.css';
			else
				$typoDir = __PS_BASE_URI__.str_replace('//', '/', 'modules/leosliderlayer').'/css/typo.css';

			$this->addCss($typoDir, 'all');
			$this->addJS(__PS_BASE_URI__.'modules/leosliderlayer/assets/admin/jquery-ui-1.10.3.custom.min.js');

			$content = Tools::file_get_contents($this->context->link->getMediaLink($typoDir));
			preg_match_all('#\.tp-caption\.(\w+)\s*{\s*#', $content, $matches);

			if (isset($matches[1]))
				$captions = $matches[1];

			$tpl = $this->createTemplate('typo.tpl');
			$tpl->assign(array(
				'typoDir' => $typoDir,
				'captions' => $captions,
				'field' => Tools::getValue('field')
			));
			return $tpl->fetch();
		}

		//this code for select or upload IMG
		$tpl = $this->createTemplate('imagemanager.tpl');
		$sortBy = Tools::getValue('sortBy');
		$reloadSliderImage = Tools::getValue('reloadSliderImage');
		$images = $this->getImageList($sortBy);
		$tpl->assign(array(
			'images' => $images,
			'reloadSliderImage' => $reloadSliderImage,
		));
		if ($reloadSliderImage)
			die(Tools::jsonEncode($tpl->fetch()));

		$image_uploader = new HelperImageUploader('file');
		$image_uploader->setSavePath($this->img_path);
		$image_uploader->setMultiple(true)->setUseAjax(true)->setUrl(
				Context::getContext()->link->getAdminLink('AdminLeoSliderLayer').'&ajax=1&action=addSliderImage');

		$tpl->assign(array(
			'countImages' => count($images),
			'images' => $images,
			'max_image_size' => $this->max_image_size / 1024 / 1024,
			'image_uploader' => $image_uploader->render(),
			'imgManUrl' => Context::getContext()->link->getAdminLink('AdminLeoSliderLayer'),
			'token' => $this->token,
			'imgUploadDir' => $this->img_path
		));

		return $tpl->fetch();
	}

	public function getImageList($sortBy)
	{
		$path = $this->img_path;
		$images = glob($path.'/{*.jpeg,*.JPEG,*.jpg,*.JPG,*.gif,*.GIF,*.png,*.PNG}', GLOB_BRACE);
		if (!$images)
			$images = $this->getAllImage($path);

		if ($sortBy == 'name_desc')
			rsort($images);

		if ($sortBy == 'date' || $sortBy == 'date_desc')
			array_multisort(array_map('filemtime', $images), SORT_NUMERIC, SORT_DESC, $images);
		if ($sortBy == 'date_desc')
			rsort($images);

		$result = array();
		foreach ($images as &$file)
		{
			$fileInfo = pathinfo($file);
			$result[] = array('name' => $fileInfo['basename'], 'link' => $this->img_url.$fileInfo['basename']);
		}
		return $result;
	}

	public function getAllImage($path)
	{
		$images = array();
		//error_log($path, 3, _PS_ROOT_DIR_.'/log/slideshow-errors.log');
		if (is_dir($path))
			foreach (scandir($path) as $d)
				if (preg_match('/(.*)\.(jpg|png|gif|jpeg)$/', $d))
					$images[] = $d;
		return $images;
	}

	public function ajaxProcessaddSliderImage()
	{
		if (isset($_FILES['file']))
		{
			$image_uploader = new HelperUploader('file');
			if (!is_dir($this->img_path))
			{
				if (!is_dir(_PS_ALL_THEMES_DIR_.$this->themeName.'/img'))
					mkdir(_PS_ALL_THEMES_DIR_.$this->themeName.'/img', 0755);
				if (!is_dir(_PS_ALL_THEMES_DIR_.$this->themeName.'/img/modules'))
					mkdir(_PS_ALL_THEMES_DIR_.$this->themeName.'/img/modules', 0755);
				mkdir(_PS_ALL_THEMES_DIR_.$this->themeName.'/img/modules/leosliderlayer', 0755);
			}
			$image_uploader->setSavePath($this->img_path);
			$image_uploader->setAcceptTypes(array('jpeg', 'gif', 'png', 'jpg'))->setMaxSize($this->max_image_size);
			$files = $image_uploader->process();
			$total_errors = array();

//			foreach ($files as $key => &$file)
			foreach ($files as &$file)
			{
				$errors = array();
				// Evaluate the memory required to resize the image: if it's too much, you can't resize it.
				if (!ImageManager::checkImageMemoryLimit($file['save_path']))
					$errors[] = Tools::displayError('Due to memory limit restrictions, this image cannot be loaded. Please increase your memory_limit value via your server\'s configuration settings. ');

				if (count($errors))
					$total_errors = array_merge($total_errors, $errors);

				//unlink($file['save_path']);
				//Necesary to prevent hacking
				unset($file['save_path']);

				//Add image preview and delete url
			}

			if (count($total_errors))
				$this->context->controller->errors = array_merge($this->context->controller->errors, $total_errors);

			$images = $this->getImageList('date');
			$tpl = $this->createTemplate('imagemanager.tpl');
			$tpl->assign(array(
				'images' => $images,
				'reloadSliderImage' => 1,
				'link' => Context::getContext()->link
			));
			die(Tools::jsonEncode($tpl->fetch()));
		}
	}

}