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

if( !class_exists("LeoImagesSourcemini") ){	
	
	class LeoImagesSourcemini extends LeoBaseSourcemini{
		
		public $name = "ImagesSourcemini";
		
		public function getData( $params , $table='leobtslidermini'){
			
			$this->context = Context::getContext();
			$id_shop = $this->context->shop->id;
			$id_lang = $this->context->language->id;

			$slideminirs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
				SELECT hs.`id_'.$table.'_slides` as id_slide,
						   hssl.`image`,
						   hss.`position`,
						   hss.`active`,
						   hssl.`title`,
						   hssl.`url`,
						   hssl.`legend`,
						   hssl.`description`
				FROM '._DB_PREFIX_.$table.' hs
				LEFT JOIN '._DB_PREFIX_.$table.'_slides hss ON (hs.id_leobtslidermini_slides = hss.id_'.$table.'_slides)
				LEFT JOIN '._DB_PREFIX_.$table.'_slides_lang hssl ON (hss.id_leobtslidermini_slides = hssl.id_'.$table.'_slides)
				WHERE (id_shop = '.(int)$id_shop.')
				AND hssl.id_lang = '.(int)$id_lang.' AND hss.`active` = 1
				ORDER BY hss.position DESC');
			
			
			$iwidth  = $params->get('imgwidth',960);
			$iheight = $params->get('imgheight',360);
			$twidth  = $params->get('thumbwidth',160);
			$theight = $params->get('thumbheight',90);
			 
			$site_url = Tools::htmlentitiesutf8('http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__).'modules/'.$this->module.'/images/';
		
			foreach( $slideminirs as $i => $slideminir ){
				$slideminir['image'] = $site_url.$slideminir['image'];
				$slideminirs[$i]['mainimage'] = $this->renderThumb( $slideminir['image'], $iwidth, $iheight );
				$slideminirs[$i]['thumbnail'] =  $this->renderThumb( $slideminir['image'], $twidth, $theight );		
				
			}
			return $slideminirs;
		}
		
		/**
		 * render its parameters 
		 */
		public function renderForm( $params ){
			 
			return '';
		}
		
		public function getParams(){
			return array();
		}
		
	}
}
