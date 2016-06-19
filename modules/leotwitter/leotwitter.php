<?php
/**
 * Leo Twitter Module
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
include_once(_PS_MODULE_DIR_.'leotwitter/libs/Params.php');
include_once(_PS_MODULE_DIR_.'leotwitter/libs/helper.php');

class leotwitter extends Module
{
	private $_html = '';
	private $_configs = '';
	protected $params = null; 
	
	public function __construct()
	{
		$this->name 	= 'leotwitter';
		$this->tab	    = 'LeoTheme';
		$this->version  = '1.0';
		$this->author 	= 'LeoTheme';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);
		
		parent::__construct();

		$this->displayName = $this->l('Leo Twitter');
		$this->description = $this->l('Leo Twitter.');
		$this->_prepareForm();
	 
		$this->params =  new LeoTwitterParams( $this, $this->name, $this->_configs  );
	
	}
	
	public function _prepareForm(){
		
		$this->_configs = array(
			'con_key'=>'7DmYcCtp4JNYBncbvCV9A',
			'con_secret'  => 'jJ342xmouF1xnjeeNoSVfCA5Du3bQJ49F5d4IK4U',
			'access_token'  => '304303474-8wmFvufhV03BPLLVoE0Pyw48rWHdbfMECmRSkxr0',
			'access_token_secret'  => 'AbJey46lJj6TuISvPS7cobzYc2ItOsBcDoAlIh9BxPE',

			'widget_type'  => 'timeline',
			'username'  => 'leotheme',
			'search_query'  => '',
			'search_title'  => '',
			'link_search'  => '1',
			'tweet_number'  => '2',

			'width'  => '300',
			'height'  => '192',
			'show_header'  => '0',
			'twitter_icon'  => '1',

			'bg_color'  => '#ffffff',
			'link_color'  => '#1BA1E2',
			'border_color'  => '#cccccc',
			'text_color'  => '#505050',
			'hname_color'  => '#505050',
			'husername_color'  => '#999999',
			'husername_hcolor'  => '#666666',
			'search_color'  => '#505050',

			'display_name'  => '0',
			'display_avatars'  => '1',
			'display_time'  => '0',
			'reply_link'  => '0',
			'retweet_link'  => '0',
			'ravorite_link'  => '0',

            'use_cache' => 1,
            'cache_time' => 90,

		);
	}
	
	public function getParams(){
		return $this->params;
	}
	/**
	 * @see Module::install()
	 */
	public function install() {
		/* Adds Module */
		if (parent::install() && $this->registerHook('footer')) {
			$this->getParams()->batchUpdate( $this->_configs );
			return true;
		}
		return false;
	}
	/**
	 * @see Module::uninstall()
	 */
	public function uninstall() {
		/* Deletes Module */
		if (parent::uninstall()){
			$res = $this->getParams()->delete();
			return $res;
		}
		return false;
	}
	
	public function getContent()
	{
		$this->_html .= '<h2>'.$this->displayName.'.</h2>';

		/* Validate & process */
		if (Tools::isSubmit('submitUpdate')) {
			if ($this->_postValidation())
				$this->_postProcess();
		}
		$this->_displayForm();
		return $this->_html;
	}

	private function _displayForm() {
		$params = $this->params;
		/* Gets Slides */
		require_once ( dirname(__FILE__).'/form.php' );
	}

	private function _postValidation() {
		$errors = array();
		/* Validation for Slider configuration */
		if (Tools::isSubmit('submitUpdate')){
			
		}
		/* Display errors if needed */
		if (count($errors)) {
			$this->_html .= $this->displayError(implode('<br />', $errors));
			return false;
		}
		/* Returns if validation is ok */
		return true;
	}

	private function _postProcess() {
		/* Processes Slider */
		if (Tools::isSubmit('submitUpdate')){
			$res = $this->getParams()->batchUpdate( $this->_configs );
			$this->getParams()->refreshConfig(); 
			if (!$res)
                $this->_html .= $this->displayError($this->l('Configuration could not be updated'));
			else
				$this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
		}
	}

	private function _prepareHook() {
		$slider = array();
		foreach( $this->_configs as $key => $config ){
			$slider[$key] = $this->getParams()->get( $key, $config );
		}
        $helper = new LeoTwitterHelper($this->name, $this->getParams());
        $style = $helper->addStyles();
        $this->smarty->assign('leotwitter', $slider);
        $this->smarty->assign('leotwitter_style', $style);
        $this->smarty->assign('mod_id', $this->id);

        if(function_exists('curl_version')){
            $data = $helper->getData();
            if(!$data)
                return;
        }else{
            return false;
        }
		$this->smarty->assign('data', $data);

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
        $theme = 'default';
        $this->context->controller->addCSS($this->_path.'themes/'.$theme.'/assets/style.css');
        $tpl = 'themes/'.$theme.'/default.tpl';
        $return = $this->_prepareHook();
        if(!function_exists('curl_version')){
            $tpl = 'themes/'.$theme.'/error.tpl';
            return $this->display(__FILE__, $tpl );
        }
		if(!$return)
			return;
		return $this->display(__FILE__, $tpl );
	}

}
