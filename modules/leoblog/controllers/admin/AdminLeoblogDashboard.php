<?php 
	/******************************************************
	 *  Leo Blog Content Management
	 *
	 * @package   leoblog
	 * @version   1.0
	 * @author    http://www.leotheme.com
	 * @copyright Copyright (C) December 2013 LeoThemes.com <@emai:leotheme@gmail.com>
	 *               <info@leotheme.com>.All rights reserved.
	 * @license   GNU General Public License version 2
	 * ******************************************************/
	include_once(_PS_MODULE_DIR_ . 'leoblog/loader.php');
	require_once(_PS_MODULE_DIR_ . 'leoblog/classes/comment.php');

	class AdminLeoblogDashboardController extends ModuleAdminControllerCore {
 		

	 	public function __construct()
		{
			$this->bootstrap = true;
			$this->display = 'view';
			$this->addRowAction('view');
			parent::__construct();
			
			 
		}

		public function initPageHeaderToolbar()
		{
			parent::initPageHeaderToolbar();
        	$this->context->controller->addJS( __PS_BASE_URI__.'modules/leoblog/assets/admin/form.js' ); 
			$this->page_header_toolbar_title = $this->l('Dashboard');
			$this->page_header_toolbar_btn = array();
		}
		
		protected function isValidPostData(){
			return is_array($_POST); 
		}

		/**
		 *
		 */
		public function postProcess(){ 

			if( Tools::isSubmit('saveConfiguration') && $this->isValidPostData() && Tools::getValue('link_rewrite') ){
				 
				LeoBlogConfig::updateConfigValue( 'cfg_global', serialize($_POST) ) ;
			}
		}
		public function setMedia()
		{
			parent::setMedia();
			$this->addJqueryUi('ui.widget');
			$this->addJqueryPlugin('tagify');
		}
		 
		public function renderView(){
		 	
		 	$link = $this->context->link;

			$quicktools = array();

			$quicktools[] = array(
				'title' => $this->l('Categories'),
				'href' => $link->getAdminLink('AdminLeoblogCategories'),
				'icon' => 'icon-desktop',
				'class'	=> '',
			);	

			$quicktools[] = array(
				'title' => $this->l('Add Category'),
				'href' => $link->getAdminLink('AdminLeoblogCategories'),
				'icon' => 'icon-list',
				'class'	=> '',
			);	


			$quicktools[] = array(
				'title' => $this->l('Blogs'),
				'href' => $link->getAdminLink('AdminLeoblogBlogs'),
				'icon' => 'icon-list',
				'class'	=> '',
			);	

			$quicktools[] = array(
				'title' => $this->l('Add Blog'),
				'href' => $link->getAdminLink('AdminLeoblogBlogs').'&addleoblog_blog',
				'icon' => 'icon-list',
				'class'	=> '',
			);	

			$quicktools[] = array(
				'title' => $this->l('Comments'),
				'href' => $link->getAdminLink('AdminLeoblogBlogs').'&listcomments',
				'icon' => 'icon-list',
				'class'	=> '',
			);	


			$onoff = array(
				array(
					'id' => 'indexation_on',
					'value' => 1,
					'label' => $this->l('Enabled')
				),
				array(
					'id' => 'indexation_off',
					'value' => 0,
					'label' => $this->l('Disabled')
				)
			);


			$obj           = new Leoblogcat();
	        $menus         = $obj->getDropdown(null, $obj->id_parent);
	        $templates = LeoBlogHelper::getTemplates();
			$url_rss = Tools::htmlentitiesutf8('http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__).'modules/leoblog/rss.php';
			$form = '';

				$this->fields_form[0]['form'] = array(
						'tinymce' => true, 
						'legend' => array(
							'title' => $this->l('General Setting'),
							'icon' => 'icon-folder-close',

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
							'class' => 'btn btn-danger'
						)
					);
					
					$this->fields_form[1]['form'] = array(
						'tinymce' => true,
						'default' => '',
						'legend' => array(
							'title' => $this->l('Listing Blog Setting'),
							'icon' => 'icon-folder-close'
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
								'class' => 'btn btn-danger'
							)
			         );   

			         $this->fields_form[2]['form'] = array(
						'tinymce' => true,
						'default' => '',
						'legend' => array(
							'title' => $this->l('Item Blog Setting'),
							'icon' => 'icon-folder-close'
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
								'class' => 'btn btn-danger'
							)
			         );   
			
			$data = LeoBlogConfig::getConfigValue( 'cfg_global' );	
			$obj = new stdClass();

			if( $data && $tmp=unserialize($data) ){
				foreach( $tmp as $key => $value ){
					$obj->{$key} = $value;
				}
			}

			$fields_value = $this->getConfigFieldsValues( $obj  ); 		
			$helper = new HelperForm($this);
			$this->setHelperDisplay($helper);
			$helper->fields_value = $fields_value;
			$helper->tpl_vars = $this->tpl_form_vars;
			!is_null($this->base_tpl_form) ? $helper->base_tpl = $this->base_tpl_form : '';
			if ($this->tabAccess['view'])
			{  	
				$helper->tpl_vars['show_toolbar'] = false;
				$helper->tpl_vars['submit_action'] = 'saveConfiguration';
				if (Tools::getValue('back'))
					$helper->tpl_vars['back'] = '';
				else
					$helper->tpl_vars['back'] =  '';
			}
			$form = $helper->generateForm($this->fields_form);



			$template = $this->createTemplate('panel.tpl');

			$comments = LeoBlogComment::getComments( null, 10,  $this->context->language->id );
			$blogs    = LeoBlogBlog::getListBlogs( null, $this->context->language->id ,   0, 10, 
			 'hits', 'DESC' );


			$template->assign( array(
				'quicktools' => $quicktools,
				'showed' => 1,
				'comment_link' => $link->getAdminLink('AdminLeoblogComments'),
				'blog_link'   => $link->getAdminLink('AdminLeoblogBlogs'),
				'blogs' 	  => $blogs,
				'count_blogs' => LeoBlogBlog::countBlogs( null,  $this->context->language->id ),
				'count_cats' => Leoblogcat::countCats(),
				'count_comments' => LeoBlogComment::countComments(),
				'latest_comments' => $comments,
 				'globalform' => $form,
			));

			return $template->fetch();
		}
		 

		 /**
	     * Asign value for each input of Data form
	     */
	    public function getConfigFieldsValues( $obj ) {      
	  
	        $languages = Language::getLanguages(false);
	        $fields_values = array();


	        foreach(  $this->fields_form as $k=> $f ){ 

	            foreach( $f['form']['input']  as $j=> $input ){
	               	
               	   if( isset($input['lang']) ) {
                        foreach ( $languages as $lang ){
                        	if( isset($obj->{trim($input['name'])."_".$lang['id_lang']}) ){
                        		$data = $obj->{trim($input['name'])."_".$lang['id_lang']};  
                            	$fields_values[$input['name']][$lang['id_lang']] = $data;
                            }else{
                            	$fields_values[$input['name']][$lang['id_lang']] = $input['default'];
                            }
                        }
                    }else {

		                if( isset($obj->{trim($input['name'])}) ){
		                    $data = $obj->{trim($input['name'])};  
		          
		                    if( $input['name'] == 'image' &&  $data  ){ 
		                        $thumb = __PS_BASE_URI__.'modules/'.$this->name.'/img/'. $data;   
		                        $this->fields_form[$k]['form']['input'][$j]['thumb'] =  $thumb; 
		                    }

		  
		                    $fields_values[$input['name']] = $data;    
		                }else{  
	                        $fields_values[$input['name']] = $input['default'];
		            		                    
		                } 
		            }    
	            }   
	        }

	        return $fields_values;
	    }

	 }
?>