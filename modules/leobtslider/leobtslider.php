<?php
/**
 * Leo Slideshow Module
 * 
 * @version		$Id: file.php $Revision
 * @package		modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) September 2012 LeoTheme.Com <@emai:leotheme@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
 */
 
/**
 * @since 1.5.0
 * @version 1.2 (2012-03-14)
 */

if (!defined('_PS_VERSION_'))
	exit;
if( !class_exists("LeoBaseSource") ){
	include_once(_PS_MODULE_DIR_.'leobtslider/libs/BaseSource.php');	
}
include_once(_PS_MODULE_DIR_.'leobtslider/libs/Slideshow.php');
include_once(_PS_MODULE_DIR_.'leobtslider/libs/Params.php');
if (!class_exists('PhpThumbFactory')) {
    require  _PS_MODULE_DIR_.'leobtslider/libs/phpthumb/ThumbLib.inc.php';
}

class leobtslider extends Module
{
	private $_html = '';
	
	private $_configs = '';
	
	protected $params = null; 
	
	public function __construct()
	{
		$this->name 	= 'leobtslider';
		$this->tab	    = 'front_office_features';
		$this->version  = '1.2';
		$this->author 	= 'LeoTheme';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);
		
		parent::__construct();

		$this->displayName = $this->l('Leo bootstrap slider');
		$this->description = $this->l('Adds an image slider to your homepage.');
		$this->_prepareForm();
	 
		$this->params =  new LeoParams( $this, $this->name, $this->_configs  );
	
	}
	
	public function _prepareForm(){
		
		$this->_configs = array(
			'theme'  => 'default',
			'source' => 'images',
			
			'imgwidth' => '940',
			'imgheight' => '350',
			'thumbwidth' => '180',
			'thumbheight' => '90',
			'image_navigator' => '0',
			'auto' => '0',
			'delay' => '3000',
		);
	
		$sources = LeoBaseSource::getSourceList();
		foreach( $sources as $source ){
		
			$p = LeoBaseSource::getSource($source)->getParams();
			if( is_array($p) && !empty($p)  ){
				 $this->_configs = array_merge($this->_configs,$p);
			}
		}		
	}
	
	public function getParams(){
		return $this->params;
	}
	/**
	 * @see Module::install()
	 */
	public function install()
	{
		/* Adds Module */
		if (parent::install() && $this->registerHook('home') && $this->registerHook('actionShopDataDuplication'))
		{
			/* Sets up configuration */
			
			
			$this->getParams()->batchUpdate( $this->_configs );
			$res=true;
			/* Creates tables */
			$res &= $this->createTables();

			/* Adds samples */
			if ($res)
				$this->installSamples();

			return $res;
		}
		return false;
	}

	/**
	 * Adds samples
	 */
	private function installSamples()
	{
		$languages = Language::getLanguages(false);
		for ($i = 1; $i <= 3; ++$i)
		{
			$slide = new leobtsliderControl();
			$slide->position = $i;
			$slide->active = 1;
			foreach ($languages as $language)
			{
				$slide->title[$language['id_lang']] = 'Design 2013 ';
				$slide->description[$language['id_lang']] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc facilisis fringilla nisi euismod Morbi sed adipiscing eleifend, dolor risus congue mi aliquet dolor tellus et ante. ';
				$slide->legend[$language['id_lang']] = 'sample-'.$i;
				$slide->url[$language['id_lang']] = 'http://www.leotheme.com';
				$slide->image[$language['id_lang']] = 'sample-'.$i.'.jpg';
			}
			$slide->add();
		}
	}

	/**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{
		/* Deletes Module */
		if (parent::uninstall())
		{
			/* Deletes tables */
			$res = $this->deleteTables();
			/* Unsets configuration */
			$res &= $this->getParams()->delete();
			
			return $res;
		}
		return false;
	}

	/**
	 * Creates tables
	 */
	protected function createTables()
	{
		/* Slides */
		$res = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name.'` (
				`id_'.$this->name.'_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned NOT NULL,
				PRIMARY KEY (`id_'.$this->name.'_slides`, `id_shop`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		/* Slides configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name.'_slides` (
			  `id_'.$this->name.'_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `position` int(10) unsigned NOT NULL DEFAULT \'0\',
			  `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
			  PRIMARY KEY (`id_'.$this->name.'_slides`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		/* Slides lang configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name.'_slides_lang` (
			  `id_'.$this->name.'_slides` int(10) unsigned NOT NULL,
			  `id_lang` int(10) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `description` text NOT NULL,
			  `legend` varchar(255) NOT NULL,
			  `url` varchar(255) NOT NULL,
			  `image` varchar(255) NOT NULL,
			  PRIMARY KEY (`id_'.$this->name.'_slides`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		return $res;
	}

	/**
	 * deletes tables
	 */
	protected function deleteTables()
	{
		$slides = $this->getSlides();
		foreach ($slides as $slide)
		{
			$to_del = new leobtsliderControl($slide['id_slide']);
			$to_del->delete();
		}
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'`, `'._DB_PREFIX_.$this->name.'_slides`, `'._DB_PREFIX_.$this->name.'_slides_lang`;
		');
	}

	public function getContent()
	{
		$this->_html .= $this->headerHTML();
		$this->_html .= '<h2>'.$this->displayName.'.</h2>';

		/* Validate & process */
		if (Tools::isSubmit('submitSlide') || Tools::isSubmit('delete_id_slide') ||
			Tools::isSubmit('submitSlider') ||
			Tools::isSubmit('changeStatus'))
		{
			if ($this->_postValidation())
				$this->_postProcess();
			$this->_displayForm();
		}
		elseif (Tools::isSubmit('addSlide') || (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide'))))
			$this->_displayAddForm();
		else
			$this->_displayForm();

		return $this->_html;
	}

	private function _displayForm()
	{
		$params = $this->params;
		/* Gets Slides */
		$slides = $this->getSlides();
		
		require_once ( dirname(__FILE__).'/form.php' );
	}

	private function _displayAddForm()
	{
	
		/* Sets Slide : depends if edited or added */
		$slide = null;
		if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))
			$slide = new leobtsliderControl((int)Tools::getValue('id_slide'));
			
		//	echo '<pre>'.print_r( $slide, 1 ); die;
		/* Checks if directory is writable */
		if (!is_writable('.'))
			$this->adminDisplayWarning(sprintf($this->l('modules %s must be writable (CHMOD 755 / 777)'), $this->name));

		/* Gets languages and sets which div requires translations */
		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		$languages = Language::getLanguages(false);
		$divLangName = 'image¤title¤url¤legend¤description';
		$this->_html .= '<script type="text/javascript">id_language = Number('.$id_lang_default.');</script>';

		/* Form */
		$this->_html .= '<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">';

		/* Fieldset Upload */
		$this->_html .= '
		<fieldset class="width3">
			<br />
			<legend><img src="'._PS_ADMIN_IMG_.'add.gif" alt="" />1 - '.$this->l('Upload your slide').'</legend>';
		/* Image */
		$this->_html .= '<label>'.$this->l('Select a file:').' * </label><div class="margin-form">';
		foreach ($languages as $language)
		{
			$this->_html .= '<div id="image_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">';
			$this->_html .= '<input type="file" name="image_'.$language['id_lang'].'" id="image_'.$language['id_lang'].'" size="30" value="'.(isset($slide->image[$language['id_lang']]) ? $slide->image[$language['id_lang']] : '').'"/>';
			/* Sets image as hidden in case it does not change */
			if ($slide && $slide->image[$language['id_lang']])
				$this->_html .= '<input type="hidden" name="image_old_'.$language['id_lang'].'" value="'.($slide->image[$language['id_lang']]).'" id="image_old_'.$language['id_lang'].'" />';
			/* Display image */
			if ($slide && $slide->image[$language['id_lang']])
				$this->_html .= '<img src="'.__PS_BASE_URI__.'modules/'.$this->name.'/images/'.$slide->image[$language['id_lang']].'" width="'.($this->getParams()->get('imgwidth',960)/2).'" height="'.($this->getParams()->get('imgheight',320)/2).'" alt=""/>';
			$this->_html .= '</div>';
		}
		$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'image', true);
		/* End Fieldset Upload */
		$this->_html .= '</fieldset><br /><br />';

		/* Fieldset edit/add */
		$this->_html .= '<fieldset class="width3">';
		if (Tools::isSubmit('addSlide')) /* Configure legend */
			$this->_html .= '<legend><img src="'._PS_ADMIN_IMG_.'add.gif" alt="" /> 2 - '.$this->l('Configure your slide').'</legend>';
		elseif (Tools::isSubmit('id_slide')) /* Edit legend */
			$this->_html .= '<legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> 2 - '.$this->l('Edit your slide').'</legend>';
		/* Sets id slide as hidden */
		if ($slide && Tools::getValue('id_slide'))
			$this->_html .= '<input type="hidden" name="id_slide" value="'.$slide->id.'" id="id_slide" />';
		/* Sets position as hidden */
		$this->_html .= '<input type="hidden" name="position" value="'.(($slide != null) ? ($slide->position) : ($this->getNextPosition())).'" id="position" />';

		/* Form content */
		/* Title */
		$this->_html .= '<br /><label>'.$this->l('Title:').' * </label><div class="margin-form">';
		foreach ($languages as $language)
		{
			$this->_html .= '
					<div id="title_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">
						<input type="text" name="title_'.$language['id_lang'].'" id="title_'.$language['id_lang'].'" size="30" value="'.(isset($slide->title[$language['id_lang']]) ? $slide->title[$language['id_lang']] : '').'"/>
					</div>';
		}
		$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'title', true);
		$this->_html .= '</div><br /><br />';

		/* URL */
		$this->_html .= '<label>'.$this->l('URL:').' * </label><div class="margin-form">';
		foreach ($languages as $language)
		{
			$this->_html .= '
					<div id="url_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">
						<input type="text" name="url_'.$language['id_lang'].'" id="url_'.$language['id_lang'].'" size="30" value="'.(isset($slide->url[$language['id_lang']]) ? $slide->url[$language['id_lang']] : '').'"/>
					</div>';
		}
		$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'url', true);
		$this->_html .= '</div><br /><br />';

		/* Legend */
		$this->_html .= '<label>'.$this->l('Legend:').' * </label><div class="margin-form">';
		foreach ($languages as $language)
		{
			$this->_html .= '
					<div id="legend_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">
						<input type="text" name="legend_'.$language['id_lang'].'" id="legend_'.$language['id_lang'].'" size="30" value="'.(isset($slide->legend[$language['id_lang']]) ? $slide->legend[$language['id_lang']] : '').'"/>
					</div>';
		}
		$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'legend', true);
		$this->_html .= '</div><br /><br />';

		/* Description */
		$this->_html .= '
		<label>'.$this->l('Description:').' </label>
		<div class="margin-form">';
		foreach ($languages as $language)
		{
			$this->_html .= '<div id="description_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">
				<textarea name="description_'.$language['id_lang'].'" rows="10" cols="29">'.(isset($slide->description[$language['id_lang']]) ? $slide->description[$language['id_lang']] : '').'</textarea>
			</div>';
		}
		$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'description', true);
		$this->_html .= '</div><div class="clear"></div><br />';

		/* Active */
		$this->_html .= '
		<label for="active_on">'.$this->l('Active:').'</label>
		<div class="margin-form">
			<img src="../img/admin/enabled.gif" alt="Yes" title="Yes" />
			<input type="radio" name="active_slide" id="active_on" '.(($slide && (isset($slide->active) && (int)$slide->active == 0)) ? '' : 'checked="checked" ').' value="1" />
			<label class="t" for="active_on">'.$this->l('Yes').'</label>
			<img src="../img/admin/disabled.gif" alt="No" title="No" style="margin-left: 10px;" />
			<input type="radio" name="active_slide" id="active_off" '.(($slide && (isset($slide->active) && (int)$slide->active == 0)) ? 'checked="checked" ' : '').' value="0" />
			<label class="t" for="active_off">'.$this->l('No').'</label>
		</div>';

		/* Save */
		$this->_html .= '
		<p class="center">
			<input type="submit" class="button" name="submitSlide" value="'.$this->l('Save').'" />
			<a class="button" style="position:relative; padding:3px 3px 4px 3px; top:1px" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">'.$this->l('Cancel').'</a>
		</p>';

		/* End of fieldset & form */
		$this->_html .= '
			<p>*'.$this->l('Required fields').'</p>
			</fieldset>
		</form>';
	}

	private function _postValidation()
	{
		$errors = array();

		/* Validation for Slider configuration */
		if (Tools::isSubmit('submitSlider'))
		{

			
		} /* Validation for status */
		elseif (Tools::isSubmit('changeStatus'))
		{
			if (!Validate::isInt(Tools::getValue('id_slide')))
				$errors[] = $this->l('Invalid slide');
		}
		/* Validation for Slide */
		elseif (Tools::isSubmit('submitSlide'))
		{
			$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
			/* Checks state (active) */
			if (!Validate::isInt(Tools::getValue('active_slide')) || (Tools::getValue('active_slide') != 0 && Tools::getValue('active_slide') != 1))
				$errors[] = $this->l('Invalid slide state');
			/* Checks position */
			if (!Validate::isInt(Tools::getValue('position')) || (Tools::getValue('position') < 0))
				$errors[] = $this->l('Invalid slide position');
			/* If edit : checks id_slide */
			if (Tools::isSubmit('id_slide'))
			{
				if (!Validate::isInt(Tools::getValue('id_slide')) && !$this->slideExists(Tools::getValue('id_slide')))
					$errors[] = $this->l('Invalid id_slide');
			}else{
				if (!isset($_FILES['image_'.$id_lang_default]) || empty($_FILES['image_'.$id_lang_default]['tmp_name']))
					$errors[] = $this->l('Image is not set');
				if (Tools::getValue('image_old_'.$id_lang_default) && !Validate::isFileName(Tools::getValue('image_old_'.$id_lang_default)))
					$errors[] = $this->l('Image is not set');
			}
			/* Checks title/url/legend/description/image */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				if (strlen(Tools::getValue('title_'.$language['id_lang'])) > 40)
					$errors[] = $this->l('Title is too long');
				if (strlen(Tools::getValue('legend_'.$language['id_lang'])) > 40)
					$errors[] = $this->l('Legend is too long');
				if (strlen(Tools::getValue('url_'.$language['id_lang'])) > 200)
					$errors[] = $this->l('URL is too long');
				if (strlen(Tools::getValue('description_'.$language['id_lang'])) > 400)
					$errors[] = $this->l('Description is too long');
				if (strlen(Tools::getValue('url_'.$language['id_lang'])) > 0 && !Validate::isUrl(Tools::getValue('url_'.$language['id_lang'])))
					$errors[] = $this->l('URL format is not correct');
				if (Tools::getValue('image_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename');
				if (Tools::getValue('image_old_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_old_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename');
			}

			/* Checks title/url/legend/description for default lang */
			
			if (strlen(Tools::getValue('title_'.$id_lang_default)) == 0)
				$errors[] = $this->l('Title is not set');
			if (strlen(Tools::getValue('legend_'.$id_lang_default)) == 0)
				$errors[] = $this->l('Legend is not set');
			if (strlen(Tools::getValue('url_'.$id_lang_default)) == 0)
				$errors[] = $this->l('URL is not set');
			
		} /* Validation for deletion */
		elseif (Tools::isSubmit('delete_id_slide') && (!Validate::isInt(Tools::getValue('delete_id_slide')) || !$this->slideExists((int)Tools::getValue('delete_id_slide'))))
			$errors[] = $this->l('Invalid id_slide');

		/* Display errors if needed */
		if (count($errors))
		{
			$this->_html .= $this->displayError(implode('<br />', $errors));
			return false;
		}

		/* Returns if validation is ok */
		return true;
	}

	private function _postProcess()
	{
		$errors = array();

		/* Processes Slider */
		if (Tools::isSubmit('submitSlider')){
					
			$res = $this->getParams()->batchUpdate( $this->_configs );
			$this->getParams()->refreshConfig(); 
			if (!$res)
				$errors .= $this->displayError($this->l('Configuration could not be updated'));
			$this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
		} /* Process Slide status */
		elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_slide'))
		{
			$slide = new leobtsliderControl((int)Tools::getValue('id_slide'));
			if ($slide->active == 0)
				$slide->active = 1;
			else
				$slide->active = 0;
			$res = $slide->update();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Configuration updated')) : $this->displayError($this->l('Configuration could not be updated')));
		}
		/* Processes Slide */
		elseif (Tools::isSubmit('submitSlide'))
		{
			/* Sets ID if needed */
			if (Tools::getValue('id_slide'))
			{
				$slide = new leobtsliderControl((int)Tools::getValue('id_slide'));
				if (!Validate::isLoadedObject($slide))
				{
					$this->_html .= $this->displayError($this->l('Invalid id_slide'));
					return;
				}
			}
			else
				$slide = new leobtsliderControl();
			/* Sets position */
			$slide->position = (int)Tools::getValue('position');
			/* Sets active */
			$slide->active = (int)Tools::getValue('active_slide');

			/* Sets each langue fields */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				if (Tools::getValue('title_'.$language['id_lang']) != '')
					$slide->title[$language['id_lang']] = pSQL(Tools::getValue('title_'.$language['id_lang']));
				if (Tools::getValue('url_'.$language['id_lang']) != '')
					$slide->url[$language['id_lang']] = pSQL(Tools::getValue('url_'.$language['id_lang']));
				if (Tools::getValue('legend_'.$language['id_lang']) != '')
					$slide->legend[$language['id_lang']] = pSQL(Tools::getValue('legend_'.$language['id_lang']));
				if (Tools::getValue('description_'.$language['id_lang']) != '')
					$slide->description[$language['id_lang']] = pSQL(Tools::getValue('description_'.$language['id_lang']));
				/* Uploads image and sets slide */
				$type = strtolower(substr(strrchr($_FILES['image_'.$language['id_lang']]['name'], '.'), 1));
				$imagesize = array();
				$imagesize = @getimagesize($_FILES['image_'.$language['id_lang']]['tmp_name']);
				if (isset($_FILES['image_'.$language['id_lang']]) &&
					isset($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
					!empty($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
					!empty($imagesize) &&
					in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
					in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
				{
					$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
					$salt = sha1(microtime());
					if ($error = ImageManager::validateUpload($_FILES['image_'.$language['id_lang']]))
						$errors .= $error;
					elseif (!$temp_name || !move_uploaded_file($_FILES['image_'.$language['id_lang']]['tmp_name'], $temp_name))
						return false;
					elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/images/'.Tools::encrypt($_FILES['image_'.$language['id_lang']]['name'].$salt).'.'.$type))
						$errors .= $this->displayError($this->l('An error occurred during the image upload.'));
					if (isset($temp_name))
						@unlink($temp_name);
					$slide->image[$language['id_lang']] = pSQL(Tools::encrypt($_FILES['image_'.($language['id_lang'])]['name'].$salt).'.'.$type);
				}
				elseif (Tools::getValue('image_old_'.$language['id_lang']) != '')
					$slide->image[$language['id_lang']] = pSQL(Tools::getValue('image_old_'.$language['id_lang']));
			}

			/* Processes if no errors  */
			if (!$errors)
			{
				/* Adds */
				if (!Tools::getValue('id_slide'))
				{
					if (!$slide->add())
						$errors .= $this->displayError($this->l('Slide could not be added'));
				} /* Update */
				elseif (!$slide->update())
					$errors .= $this->displayError($this->l('Slide could not be updated'));
			}
		} /* Deletes */
		elseif (Tools::isSubmit('delete_id_slide'))
		{
			$slide = new leobtsliderControl((int)Tools::getValue('delete_id_slide'));
			$res = $slide->delete();
			if (!$res)
				$this->_html .= $this->displayError('Could not delete');
			else
				$this->_html .= $this->displayConfirmation($this->l('Slide deleted'));
		}

		/* Display errors if needed */
		if (count($errors))
			$this->_html .= $this->displayError(implode('<br />', $errors));
		elseif (Tools::isSubmit('submitSlide') && Tools::getValue('id_slide'))
			$this->_html .= $this->displayConfirmation($this->l('Slide updated'));
		elseif (Tools::isSubmit('submitSlide'))
			$this->_html .= $this->displayConfirmation($this->l('Slide added'));
	}

	private function _prepareHook()
	{
		$slider = array();
		foreach( $this->_configs as $key => $config ){
			$slider[$key] = $this->getParams()->get( $key, $config );
		}
		
		$source = $this->getParams()->get( "source",'images' );
		$path = _PS_CACHEFS_DIRECTORY_.$this->name;
		if( !file_exists($path) ) {	mkdir( $path, 0777 );};
		$site_url = Tools::htmlentitiesutf8('http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__).'cache/cachefs/'.$this->name;
		
		$slides = LeoBaseSource::getSource( $source )
			->setModuleName('leobtslider', $path, $site_url )
			->getData( $this->getParams() );
 
		if (!$slides)
			return false;
		
		
		//echo '<pre>'.print_r($slides,1); die;
		$this->smarty->assign('leobtslider_slides', $slides);
		$this->smarty->assign('leobtslider', $slider);
		$this->smarty->assign('leobtslider_modid', $this->id);
		return true;
	}
	
    function hookDisplayHeaderRight() {
        return $this->hookDisplayTop();
    }

    function hookDisplaySlideshow() {
        return $this->hookDisplayTop();
    }

    function hookTopNavigation() {
        return $this->hookDisplayTop();
    }

    function hookDisplayPromoteTop() {
        return $this->hookDisplayTop();
    }

    function hookDisplayBottom() {
        return $this->hookDisplayTop();
    }

    function hookDisplayContentBottom() {
        return $this->hookDisplayTop();
    }

    function hookdisplayRightColumn() {
        return $this->hookDisplayTop();
    }

    function hookdisplayLeftColumn() {
        return $this->hookDisplayTop();
    }

    function hookdisplayHome() {
        return $this->hookDisplayTop();
    }

    function hookdisplayFooter() {
        return $this->hookDisplayTop();
    }
	
	public function hookDisplayTop()
	{
		if(!$this->_prepareHook())
			return;
		$theme = $this->getParams()->get('theme','default');
		$this->context->controller->addCSS( ($this->_path).'themes/'. $theme.'/assets/styles.css', 'all');
		$tpl = 'themes/default/default.tpl';
		if( file_exists(dirname(__FILE__)."/themes/".$theme.'/default.tpl') )
			$tpl = 'themes/'.$theme.'/default.tpl';
		
		return $this->display(__FILE__, $tpl );
	}

	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.$this->name.' (id_'.$this->name.'_slides, id_shop)
		SELECT id_'.$this->name.'_slides, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.$this->name.'
		WHERE id_shop = '.(int)$params['old_id_shop']);
	}

	public function headerHTML()
	{
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;

		$this->context->controller->addJqueryUI('ui.sortable');
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
		<script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/jquery-ui.will.be.removed.in.1.6.js"></script>
		<script type="text/javascript">
			$(function() {
				var $mySlides = $("#slides");
				$mySlides.sortable({
					opacity: 0.6,
					cursor: "move",
					update: function() {
						var order = $(this).sortable("serialize") + "&action=updateSlidesPosition";
						$.post("'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/ajax_'.$this->name.'.php?secure_key='.$this->secure_key.'", order);
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
				FROM `'._DB_PREFIX_.$this->name.'_slides` hss, `'._DB_PREFIX_.$this->name.'` hs
				WHERE hss.`id_'.$this->name.'_slides` = hs.`id_'.$this->name.'_slides` AND hs.`id_shop` = '.(int)$this->context->shop->id
		);

		return (++$row['next_position']);
	}

	public function getSlides($active = null)
	{
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_'.$this->name.'_slides` as id_slide,
					   hssl.`image`,
					   hss.`position`,
					   hss.`active`,
					   hssl.`title`,
					   hssl.`url`,
					   hssl.`legend`,
					   hssl.`description`
			FROM '._DB_PREFIX_.$this->name.' hs
			LEFT JOIN '._DB_PREFIX_.$this->name.'_slides hss ON (hs.id_'.$this->name.'_slides = hss.id_'.$this->name.'_slides)
			LEFT JOIN '._DB_PREFIX_.$this->name.'_slides_lang hssl ON (hss.id_'.$this->name.'_slides = hssl.id_'.$this->name.'_slides)
			WHERE (id_shop = '.(int)$id_shop.')
			AND hssl.id_lang = '.(int)$id_lang.
			($active ? ' AND hss.`active` = 1' : ' ').'
			ORDER BY hss.position');
	}

	public function displayStatus($id_slide, $active)
	{
		$title = ((int)$active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
		$img = ((int)$active == 0 ? 'disabled.gif' : 'enabled.gif');
		$html = '<a href="'.AdminController::$currentIndex.
				'&configure='.$this->name.'
				&token='.Tools::getAdminTokenLite('AdminModules').'
				&changeStatus&id_slide='.(int)$id_slide.'" title="'.$title.'"><img src="'._PS_ADMIN_IMG_.''.$img.'" alt="" /></a>';
		return $html;
	}

	public function slideExists($id_slide)
	{
		$req = 'SELECT hs.`id_'.$this->name.'_slides` as id_slide
				FROM `'._DB_PREFIX_.$this->name.'` hs
				WHERE hs.`id_'.$this->name.'_slides` = '.(int)$id_slide;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}
}
