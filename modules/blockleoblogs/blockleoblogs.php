<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class blockleoblogs extends Module
{
	private $_html = '';
	private $_postErrors = array();

	public $isInstalled = false;
	public $fields_form = array();

	public function __construct()
	{
		$this->name = 'blockleoblogs';
		$this->tab = 'front_office_features';
		$this->version = 0.9;
		$this->author = 'PrestaShop';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('Leo Blogs Latest Modules');
		$this->description = $this->l('This modules works with leoblog module.');

	}

	public function install()
	{
		if (parent::install()
			&& $this->registerHook('displayHome')
			&& $this->registerHook('header')
			&& $this->registerHook('displayBottom')
			&& Configuration::updateValue('BLEOBLOGS_NBR', 6)
			&& Configuration::updateValue('BLEOBLOGS_PAGE', 3)
			&& Configuration::updateValue('BLEOBLOGS_COL', 3)
			&& Configuration::updateValue('BLEOBLOGS_INTV', 8000)
			&& Configuration::updateValue('BLEOBLOGS_SHOW', 1)
			&& Configuration::updateValue('BLEOBLOGS_WIDTH',690)
			&& Configuration::updateValue('BLEOBLOGS_HEIGHT', 300) 
			&& Configuration::updateValue('BLEOBLOGS_SCATE', 1) 
			&& Configuration::updateValue('BLEOBLOGS_SIMA', 1) 
			&& Configuration::updateValue('BLEOBLOGS_SAUT', 1) 
			&& Configuration::updateValue('BLEOBLOGS_SCAT', 1) 
			&& Configuration::updateValue('BLEOBLOGS_SCRE', 1) 
			&& Configuration::updateValue('BLEOBLOGS_STITLE', 1) 
			&& Configuration::updateValue('BLEOBLOGS_SCOUN', 1)
			&& Configuration::updateValue('BLEOBLOGS_SHITS', 1) ){
				$res = true;
				$res = $this->_installDataSample();
				return (bool)$res;
			}
			return false;
	}
        
        private function _installDataSample(){
            if (!file_exists(_PS_MODULE_DIR_ . 'leotempcp/libs/DataSample.php')) return true;        
            require_once( _PS_MODULE_DIR_ . 'leotempcp/libs/DataSample.php' );

            $sample = new Datasample(1);
            return $sample->processImport($this->name);
        }

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitBlockLeoBlogs'))
		{
			if (!($productNbr = Tools::getValue('BLEOBLOGS_NBR')) || empty($productNbr))
				$output .= $this->displayError($this->l('You must fill in the \'Products displayed\' field.'));
			elseif ((int)($productNbr) == 0)
				$output .= $this->displayError($this->l('Invalid number.'));
			else
			{
				Configuration::updateValue('BLEOBLOGS_NBR', (int)$productNbr);
				Configuration::updateValue('BLEOBLOGS_WIDTH', (int)Tools::getValue('BLEOBLOGS_WIDTH') );
				Configuration::updateValue('BLEOBLOGS_HEIGHT', (int)Tools::getValue('BLEOBLOGS_HEIGHT') );
				Configuration::updateValue('BLEOBLOGS_PAGE', (int)Tools::getValue('BLEOBLOGS_PAGE') );
				Configuration::updateValue('BLEOBLOGS_COL', (int)Tools::getValue('BLEOBLOGS_COL') );
				Configuration::updateValue('BLEOBLOGS_INTV', (int)Tools::getValue('BLEOBLOGS_INTV') );
				Configuration::updateValue('BLEOBLOGS_SHOW', (int)Tools::getValue('BLEOBLOGS_SHOW') );
				Configuration::updateValue('BLEOBLOGS_SDES', (int)Tools::getValue('BLEOBLOGS_SDES') );
				Configuration::updateValue('BLEOBLOGS_SIMA', (int)Tools::getValue('BLEOBLOGS_SIMA') );
				Configuration::updateValue('BLEOBLOGS_SAUT', (int)Tools::getValue('BLEOBLOGS_SAUT') );
				Configuration::updateValue('BLEOBLOGS_SCAT', (int)Tools::getValue('BLEOBLOGS_SCAT') );
				Configuration::updateValue('BLEOBLOGS_SCRE', (int)Tools::getValue('BLEOBLOGS_SCRE') );
				Configuration::updateValue('BLEOBLOGS_STITLE', (int)Tools::getValue('BLEOBLOGS_STITLE') );
				Configuration::updateValue('BLEOBLOGS_SCOUN', (int)Tools::getValue('BLEOBLOGS_SCOUN') );
				Configuration::updateValue('BLEOBLOGS_SHITS', (int)Tools::getValue('BLEOBLOGS_SHITS') );
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
		}
		return $output.$this->renderForm();
	}

	public function hookDisplayHome( $params ){

		return $this->hookRightColumn( $params );
	}

	public function hookDisplayLeftColumn( $params  ) 
    {
    	return $this->hookRightColumn( $params );
    }

    public function hookDisplayFooter( $params  ) 
    {
    	return $this->hookRightColumn( $params );
    }


    public function hookDisplayPromoteTop( $params  ) 
    {
    	return $this->hookRightColumn( $params );
    }

    public function hookDisplayContentBottom( $params  ) 
    {
    	return $this->hookRightColumn( $params );
    }

    public function hookDisplayBottom( $params  ) 
    {
    	return $this->hookRightColumn( $params );
    }

    public function hookDisplayFooterTop( $params  ) 
    {
    	return $this->hookRightColumn( $params );
    }

     public function hookDisplayFooterBottom( $params ) 
    {
    	return $this->hookRightColumn( $params );
    }

	public function hookRightColumn( $params )
	{
		 	

	 	if( file_exists(_PS_MODULE_DIR_ . 'leoblog/classes/config.php') ){
			$this->isInstalled = true;
			require_once( _PS_MODULE_DIR_ . 'leoblog/loader.php' );

			
			$authors = array();
			$config = LeoBlogConfig::getInstance();

			$config->setVar( 'blockleo_blogs_height', Configuration::get('BLEOBLOGS_HEIGHT') );	
			$config->setVar( 'blockleo_blogs_width', Configuration::get('BLEOBLOGS_WIDTH') );	
			$config->setVar( 'blockleo_blogs_limit', Configuration::get('BLEOBLOGS_NBR') );	
			$config->setVar( 'blockleo_blogs_page', Configuration::get('BLEOBLOGS_PAGE') );	
			$config->setVar( 'blockleo_blogs_col', Configuration::get('BLEOBLOGS_COL') );	
			$config->setVar( 'blockleo_blogs_intv', Configuration::get('BLEOBLOGS_INTV') );
			$config->setVar( 'blockleo_blogs_show', Configuration::get('BLEOBLOGS_SHOW') );	
			$config->setVar( 'blockleo_blogs_des', Configuration::get('BLEOBLOGS_SDES') );	
			$config->setVar( 'blockleo_blogs_img', Configuration::get('BLEOBLOGS_SIMA') );	
			$config->setVar( 'blockleo_blogs_aut', Configuration::get('BLEOBLOGS_SAUT') );	
			$config->setVar( 'blockleo_blogs_cat', Configuration::get('BLEOBLOGS_SCAT') );	
			$config->setVar( 'blockleo_blogs_cre', Configuration::get('BLEOBLOGS_SCRE') );	
			$config->setVar( 'blockleo_blogs_cout', Configuration::get('BLEOBLOGS_SCOUN') );	
			$config->setVar( 'blockleo_blogs_title', Configuration::get('BLEOBLOGS_STITLE') );
			$config->setVar( 'blockleo_blogs_hits', Configuration::get('BLEOBLOGS_SHITS') );
			$limit = (int)$config->get( 'blockleo_blogs_limit', 6 );
		 

			$blogs = LeoBlogBlog::getListBlogs(  null, $this->context->language->id , 0,  $limit, 'date_add', 'DESC',  array(), true );
			$helper = LeoBlogHelper::getInstance();

			

			$image_w = (int)$config->get( 'blockleo_blogs_width', 690 );
			$image_h = (int)$config->get( 'blockleo_blogs_height', 300 );
      	    $link = LeoBlogHelper::getInstance()->getFontBlogLink( );

			foreach( $blogs as $key => $blog ){
				$blog =  LeoBlogHelper::buildBlog( $helper , $blog, $image_w, $image_h , $config );
				if( $blog['id_employee'] ){
					if( !isset($authors[$blog['id_employee']]) ){
						$authors[$blog['id_employee']] = new Employee( $blog['id_employee'] );
					}
				 
					$blog['author'] 	 = $authors[$blog['id_employee']]->firstname . " " . $authors[$blog['id_employee']]->lastname; 
					$blog['author_link'] = $helper->getBlogAuthorLink( $authors[$blog['id_employee']]->id ); 	
				}else {
					$blog['author'] = '';
					$blog['author_link'] = '';	
				}	 
				 
				$blogs[$key] = $blog;	
			}
			
			$itemsperpage = (int)$config->get( 'blockleo_blogs_page', 3 );
			$columnspage = (int)$config->get( 'blockleo_blogs_col', 3 );
//			$interval = (int)$config->get( 'blockleo_blogs_inteval', 8000 );
			$interval = (int)Configuration::get('BLEOBLOGS_INTV');
 			$this->smarty->assign( 'view_all_link', $link );	
			$this->smarty->assign( 'blogs', $blogs );
			$this->smarty->assign( 'config', $config );
			$this->smarty->assign(array(
                'itemsperpage' => $itemsperpage ,
                'columnspage'  => $columnspage,
                'scolumn'      => 12 / $columnspage,
                'interval'     => $interval
            ));
			$this->context->controller->addCSS(($this->_path) . 'blogcarousel.css', 'all');
			return $this->display(__FILE__, 'blockleoblogs.tpl');
		}else{

			return ;
		}
	}

	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'blockleoblogs.css', 'all');
	}
	
	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
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
				'submit' => array(
					'title' => $this->l('Save'),
					'class' => 'btn btn-default')
				),
			);
			
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitBlockLeoBlogs';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}
	
	public function getConfigFieldsValues()
	{		
		return array(
			'BLEOBLOGS_NBR' 	=> Tools::getValue('BLEOBLOGS_NBR', Configuration::get('BLEOBLOGS_NBR')),
			'BLEOBLOGS_WIDTH' 	=> Tools::getValue('BLEOBLOGS_WIDTH', Configuration::get('BLEOBLOGS_WIDTH')),
			'BLEOBLOGS_HEIGHT' 	=> Tools::getValue('BLEOBLOGS_HEIGHT', Configuration::get('BLEOBLOGS_HEIGHT')),
			'BLEOBLOGS_PAGE' 	=> Tools::getValue('BLEOBLOGS_PAGE', Configuration::get('BLEOBLOGS_PAGE')),
			'BLEOBLOGS_COL' 	=> Tools::getValue('BLEOBLOGS_COL', Configuration::get('BLEOBLOGS_COL')),
			'BLEOBLOGS_INTV' 	=> Tools::getValue('BLEOBLOGS_INTV', Configuration::get('BLEOBLOGS_INTV')),
			'BLEOBLOGS_SHOW' 	=> Tools::getValue('BLEOBLOGS_SHOW', Configuration::get('BLEOBLOGS_SHOW')),
			'BLEOBLOGS_SDES' 	=> Tools::getValue('BLEOBLOGS_SDES', Configuration::get('BLEOBLOGS_SDES')),
			'BLEOBLOGS_SIMA' 	=> Tools::getValue('BLEOBLOGS_SIMA', Configuration::get('BLEOBLOGS_SIMA')),
			'BLEOBLOGS_SAUT' 	=> Tools::getValue('BLEOBLOGS_SAUT', Configuration::get('BLEOBLOGS_SAUT')),
			'BLEOBLOGS_SCAT' 	=> Tools::getValue('BLEOBLOGS_SCAT', Configuration::get('BLEOBLOGS_SCAT')),
			'BLEOBLOGS_SCRE' 	=> Tools::getValue('BLEOBLOGS_SCRE', Configuration::get('BLEOBLOGS_SCRE')),
			'BLEOBLOGS_SCOUN' 	=> Tools::getValue('BLEOBLOGS_SCOUN', Configuration::get('BLEOBLOGS_SCOUN')),
			'BLEOBLOGS_SHITS' 	=> Tools::getValue('BLEOBLOGS_SHITS', Configuration::get('BLEOBLOGS_SHITS')),
			'BLEOBLOGS_STITLE' 	=> Tools::getValue('BLEOBLOGS_STITLE', Configuration::get('BLEOBLOGS_STITLE')),
		);
	}
}
