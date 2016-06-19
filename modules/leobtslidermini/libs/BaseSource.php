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

if( !class_exists("LeoBaseSourcemini") ) { 
	/**
	 * LeoBaseSourcemini Class
	 */
	class LeoBaseSourcemini {
		
		/**
		 * @var $name;
		 */
		public $name = "BaseSourcemini";
		
		/**
		 * @var $collection
		 */
		static $collection = array();
		
		/**
		 * 
		 */
		public function setTable(){ return $this; }
		
		/**
		 * 
		 */
		/**
		 * 
		 */
		public function getParams(){ return array(); }
		
		public $module = '';
		public $_thumbnaiURL='';
		public $_thumbnailPath='';
		/**
		 * 
		 */
		public static function getSourceList(){
			static $folders;
			if( !$folders ){
				$path = (dirname(__FILE__)).'/sources/';
				$folders = self::getFolderList( $path );
			}
			return $folders; 
		}
	   
	   public function setModuleName( $module,$path, $url ){
			$this->module = $module;
			$this->_thumbnaiURL = $url;
			$this->_thumbnailPath = $path;
			return $this; 
	   }
	   /**
		* Get list of sub folder's name 
		*/
		public static function getFolderList( $path ) {
			$items = array();
			$handle = opendir($path);
			if (! $handle) {
				return $items;
			}
			while (false !== ($file = readdir($handle))) {
				if (is_dir($path . $file))
					$items[$file] = $file;
			}
			unset($items['.'], $items['..'], $items['.svn']);
			return $items;
		}
	 
	 	/**
		 * 
		 */
		static function getSource( $name ){
			if( !isset(self::$collection[$name]) ){
				require_once( dirname(__FILE__).'/sources/'.$name.'/source.php') ;
				$class = 'Leo'.ucfirst($name).'Sourcemini';
				self::$collection[$name] = new $class();
			}
			return self::$collection[$name];
		}
		
		/**
		 * 
		 */
		public function renderForm( $params ){}
		
		public function renderThumb( $path, $width, $height ){
			
			$protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http"). "://" . $_SERVER['HTTP_HOST'];
			$tpath      = str_replace( $protocol, "", $path );
			if(__PS_BASE_URI__ == '/')
				$tpath      = ltrim( $tpath, '/' );
			else
				$tpath      = str_replace( __PS_BASE_URI__, "", $tpath );
			$sourceFile = _PS_ROOT_DIR_.'/'.$tpath;
			
			if( file_exists($sourceFile) ){  // return $path; 
				
				$tmp        = explode("/",$path);                    
				$path       = $this->_thumbnaiURL."/".$width."_".$height."_".$tmp[count($tmp)-1];
				$savePath   = $this->_thumbnailPath."/".$width."_".$height."_".$tmp[count($tmp)-1];
				if( !file_exists($savePath) ) {
					$thumb = PhpThumbFactory::create($sourceFile);
					$thumb->adaptiveResize( $width, $height);
					$thumb->save($savePath);
				}
			}
			
			return $path;
		}
	}
}
?>