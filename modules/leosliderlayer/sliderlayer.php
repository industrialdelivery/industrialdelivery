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

class SliderLayer extends ObjectModel
{
	public $title;
	public $link;
	public $image;
	public $id_group;
	public $position;
	public $active;
	public $params;
	public $thumbnail;
	public $video;
	public $layersparams;
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'leosliderlayer_slides',
		'primary' => 'id_leosliderlayer_slides',
		'multilang' => true,
		'fields' => array(
			'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'id_group' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'position' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			# Lang fields
			'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
			'link' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => false, 'size' => 255),
			'image' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'thumbnail' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'params' => array('type' => self::TYPE_HTML, 'lang' => false),
			'video' => array('type' => self::TYPE_HTML, 'lang' => true),
			'layersparams' => array('type' => self::TYPE_HTML, 'lang' => true)
		)
	);

	public function __construct($id_slide = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_slide, $id_lang, $id_shop);
	}

	public function add($autodate = true, $null_values = false)
	{
		$res = parent::add($autodate, $null_values);
		return $res;
	}

	public function delete()
	{
		$res = true;

		/* $images = $this->image;
		  foreach ($images as $image)
		  {
		  if (preg_match('/sample/', $image) === 0)
		  if ($image && file_exists(dirname(__FILE__).'/images/'.$image))
		  $res &= @unlink(dirname(__FILE__).'/images/'.$image);
		  }
		 */

		$res &= $this->reOrderPositions();

		$res &= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'leosliderlayer_slides`
			WHERE `id_leosliderlayer_slides` = '.(int)$this->id
		);

		$res &= parent::delete();
		return $res;
	}

	public static function sliderExist($id_slider)
	{
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT gr.`id_leosliderlayer_slides` as id
                    FROM `'._DB_PREFIX_.'leosliderlayer_slides` gr
                            WHERE gr.`id_leosliderlayer_slides` = '.(int)$id_slider);
	}

	public function reOrderPositions()
	{
		$id_slide = $this->id;
//        $context = Context::getContext();
//        $id_shop = $context->shop->id;

		$max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT MAX(hss.`position`) as position
			FROM `'._DB_PREFIX_.'leosliderlayer_slides` hss
			WHERE hss.`id_group` = '.$this->id_group
		);

		if ((int)$max == (int)$id_slide)
			return true;

		$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hss.`position` as position, hss.`id_leosliderlayer_slides` as id_slide
			FROM `'._DB_PREFIX_.'leosliderlayer_slides` hss
			WHERE hss.`id_group` = '.(int)$this->id_group.' AND hss.`position` > '.(int)$this->position
		);

		foreach ($rows as $row)
		{
			$current_slide = new SliderLayer($row['id_slide']);
			--$current_slide->position;
			$current_slide->update();
			unset($current_slide);
		}

		return true;
	}

	public function getDelay()
	{
		$temp_result = Tools::jsonDecode(SliderLayer::base64Decode($this->params), true);
		$result = $temp_result['delay'];

		return $result;
	}

	/**
	 * System get Delay value from GROUP when SLIDER's Delay <= 0
	 */
	public static function showDelay($slide_id = 0, $delay = null, $group_delay = null)
	{
		$default = 9000;

		# Get Delay form SLIDER
		if ($delay > 0)
			return $delay;

		if (!empty($slide_id))
		{
			$slider = new SliderLayer($slide_id);
			$s_delay = $slider->getDelay();
			if ($s_delay > 0)
				return $s_delay;
		}
		# Get Delay form GROUP
		if ($group_delay > 0)
			return $group_delay;

		if (!empty($slide_id))
		{
			$slider = new SliderLayer($slide_id);
			$group = new LeoSliderGroup($slider->id_group);
			$g_delay = $group->getDelay();
			if ($g_delay > 0)
				return $g_delay;
		}

		return $default;
	}

	public static function renderTarget($target = '')
	{
		$html = '_self';
		if (!empty($target))
		{
			if (Status::SLIDER_TARGET_SAME == $target)
				$html = '_self';
			elseif (Status::SLIDER_TARGET_NEW == $target)
				$html = '_blank';
		}
		return $html;
	}

	public function mergeData($data = array())
	{
		if (is_array($data))
		{
			foreach ($data as $key => $value)
				$this->$key = $value;
		}
		return $this;
	}

	public function mergeSlider($data = array())
	{
		return $this->mergeData($data);
	}

	public function mergeParams($pattern)
	{
		$params_data = array_merge($pattern, Tools::jsonDecode(SliderLayer::base64Decode($this->params), true));
		$this->mergeData($params_data);
		return $this;
	}

	public function validate($module)
	{
		$start_timestamp = strtotime($this->start_date_time);
		$end_timestamp = strtotime($this->end_date_time);

		if ($end_timestamp == 0 && $start_timestamp == 0)
		{
			# validate module
			# validate module
		}
		elseif ($end_timestamp > $start_timestamp && $end_timestamp != 0 && $start_timestamp != 0)
		{
			# validate module
			# validate module
		}
		else
		{
			# validate module
			throw new Exception($module->l("'Start End Time' must be equal or more than 'Start Date Time'"));
		}
	}

	public function getStatusTime()
	{
		$timestamp = time();
		$start_date_time = strtotime($this->start_date_time);
		$end_date_time = strtotime($this->end_date_time);

		if ($this->active == Status::SLIDER_STATUS_DISABLE)
		{
			# validate module
			return Status::SLIDER_STATUS_DISABLE;
		}
		# NOT SET TIME
		if ($this->active == Status::SLIDER_STATUS_ENABLE && $start_date_time == 0 && $end_date_time == 0)
		{
			# validate module
			return Status::SLIDER_STATUS_ENABLE;
		}
		// HAVE SET TIME
		if ($this->active == Status::SLIDER_STATUS_ENABLE && $start_date_time <= $timestamp && $timestamp <= $end_date_time)
		{
			# validate module
			return Status::SLIDER_STATUS_ENABLE;
		}

		if ($this->active == Status::SLIDER_STATUS_ENABLE && $timestamp < $start_date_time)
		{
			# validate module
			return Status::SLIDER_STATUS_COMING;
		}

		# DEFAULT
		return Status::SLIDER_STATUS_DISABLE;
	}

	public static function filterSlider($sliders = array(), $_sliderData = array())
	{
		foreach ($sliders as $key => $slider)
		{
			$mod_slider = new SliderLayer();
			$mod_slider->mergeSlider($slider)->mergeParams($_sliderData);
			if ($mod_slider->getStatusTime() == Status::SLIDER_STATUS_ENABLE)
			{
				# validate module
				# validate module
			}
			else
			{
				# validate module
				unset($sliders[$key]);
			}
		}

		return $sliders;
	}

	public static function base64Decode($data)
	{
		return call_user_func('base64_decode', $data);
	}

	public static function base64Encode($data)
	{
		return call_user_func('base64_encode', $data);
	}

}