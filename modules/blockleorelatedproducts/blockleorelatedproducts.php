<?php
/*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;
include_once(_PS_MODULE_DIR_.'blockleorelatedproducts/Params.php');

class BlockLeoRelatedProducts extends Module
{
	private $_html = '';
	private $_postErrors = array();
	private $_configs = array();
	private $catids = array();
    function __construct()
    {
        $this->name = 'blockleorelatedproducts';
        $this->tab = 'landofcoder';
        $this->version = '1.0';
		$this->author = 'leotheme';
		$this->need_instance = 0;
		
		parent::__construct();
		
		$this->_prepareForm();
		$this->displayName = $this->l('Leo Related Products Block');
		$this->description = $this->l('Display Products In Same Category or Related by Tag.... in Carousel.');
		$this->params =  new LeoParams( $this, 'LEOREPRODS', $this->_configs  );
 
	}
	public function _prepareForm(){
		
		$this->_configs = array(
			'modclass'=>'',
			'theme'  => 'default',
			'catids' => '2,3',
			'itemspage' => 3,
			'columns'   => 3,
			'itemstab' => 6,
			'porder'   => 'date_add',
		);		
	}	
	public function install()
	{
		$a =  (parent::install() AND $this->registerHook('displayFooterProduct')  AND $this->registerHook('header'));
 
			
		return $a;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitSpecials'))
		{
			$res = $this->params->batchUpdate( $this->_configs );
			$this->params->refreshConfig(); 
 
			$output .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		 $orders = array('date_add'=>$this->l('Date Add'),'date_add DESC'=>$this->l('Date Add DESC'),
                         'name'=>$this->l('Name'),'name DESC'=>$this->l('Name DESC'),
                         'quantity'=>$this->l('Quantity'),'quantity DESC'=>$this->l('Quantity DESC'),
                         'price'=>$this->l('Price'),'price DESC'=>$this->l('Price DESC'));
								
		return '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				
			
				 
				<div class="row-form">
					'.$this->params->selectTag( $orders, "Order By", 'porder',  $this->params->get('porder') ).'
					<p class="clear">'.$this->l('The maximum number of products in each page Carousel (default: 3).').'</p>
				</div>
			
				<div class="row-form">
					'.$this->params->inputTag( 'Items Per Page', 'itemspage', $this->params->get('itemspage') ).'
					<p class="clear">'.$this->l('The maximum number of products in each page Carousel (default: 3).').'</p>
				</div>
				<div class="row-form">
					'.$this->params->inputTag( 'Colums In Each Carousel', 'columns', $this->params->get('columns') ).'
					<p class="clear">'.$this->l('The maximum column products in each page Carousel (default: 3).').'</p>
				</div>
				<div class="row-form">
					'.$this->params->inputTag( 'Items In all Carousels', 'itemstab', $this->params->get('itemstab') ).'
					<p class="clear">'.$this->l('The maximum number of products in each Carousel (default: 6).').'</p>
				</div>
				
				 
				<center><input type="submit" name="submitSpecials" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
	}
	private function getCurrentProduct($products, $id_current)
	{
		if ($products)
			foreach ($products AS $key => $product)
				if ($product['id_product'] == $id_current)
					return $key;
		return false;
	}
	public function hookDisplayFooterProduct( $params ){
		return $this->displayRightColumnProduct( $params );
	}
	public function hookDisplayLeftColumnProduct( $params ){
		return $this->displayRightColumnProduct( $params );
	}
	

	
	
	public function displayRightColumnProduct( $params )
	{
		if (Tools::getValue('controller') != "product" )
			return ;
			
		$nb =  (int)$this->params->get('itemstab');
 
		$catids = $this->params->get( 'catids', '1,2,3' );
		$catids = explode(",",$catids);
		$porder = $this->params->get('porder','date_add');
		$porder = preg_split("#\s+#",$porder);
		if( !isset($porder[1]) ) {
			$porder[1] = null;
		}
		 
		
		$items_page =  (int)$this->params->get('itemspage');
		$columns_page =  (int)$this->params->get('columns');
	 
			
		$this->catids = $catids;
		// $products = $this->getProducts((int)Context::getContext()->language->id, 1, $nb, $porder[0], $porder[1] );
		
		
		$dir = dirname(__FILE__)."/products.tpl";
		$tdir = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$this->name.'/products.tpl';
	
		if( file_exists($tdir) ){
			$dir = $tdir;
		}
		
		
		$idProduct = (int)(Tools::getValue('id_product'));
		$product = new Product((int)($idProduct));

		/* If the visitor has came to this product by a category, use this one */
		if (isset($params['category']->id_category))
			$category = $params['category'];
		/* Else, use the default product category */
		else
		{
			if (isset($product->id_category_default) AND $product->id_category_default > 1)
				$category = New Category((int)($product->id_category_default));
		}
		
		if (!Validate::isLoadedObject($category) OR !$category->active) 
			return;

		// Get infos
		$categoryProducts = $category->getProducts($this->context->language->id, 1, $nb, $porder[0], $porder[1] ); /* 100 products max. */
		$sizeOfCategoryProducts = (int)sizeof($categoryProducts);
		$middlePosition = 0;
		
		// Remove current product from the list
		if (is_array($categoryProducts) AND sizeof($categoryProducts))
		{
			foreach ($categoryProducts AS $key => $categoryProduct){
				if ($categoryProduct['id_product'] == $idProduct)
				{
					unset($categoryProducts[$key]);
					break;
				}
			}	
		}
		
		// Display tpl
		$this->smarty->assign(array(
			'itemsperpage'=> $items_page,
			'columnspage' => $columns_page,
			'product_tpl' => $dir,
			'products'	 => $categoryProducts,
			'scolumn'     => 12/$columns_page
		//	'priceWithoutReduction_tax_excl' => Tools::ps_round($special['price_without_reduction'], 2),
		///	'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
		));
		
		return $this->display(__FILE__, 'blockleorelatedproducts.tpl');
	}
	
	 
 
	public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'blockleorelatedproducts.css', 'all');
	}
}

