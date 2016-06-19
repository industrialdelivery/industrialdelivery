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
include_once(_PS_MODULE_DIR_.'blockleoprodcarousel/Params.php');

class BlockLeoProdCarousel extends Module
{
	private $_html = '';
	private $_postErrors = array();
	private $_configs = array();
	private $catids = array();
    function __construct()
    {
        $this->name = 'blockleoprodcarousel';
        $this->tab = 'pricing_promotion';
        $this->version = '1.0';
		$this->author = 'leotheme';
		$this->need_instance = 0;
		
		parent::__construct();
		
		$this->_prepareForm();
		$this->displayName = $this->l('Leo Products Carousel Block');
		$this->description = $this->l('Display Products of Categories in Carousel.');
		$this->params =  new LeoParams( $this, 'LEOPRODCA', $this->_configs  );
 
	}
	public function _prepareForm(){
		
		$this->_configs = array(
			'modclass'=>'',
			'theme'  => 'default',
			'catids' => '2,3',
			'itemspage' => 4,
			'columns'   => 4,
			'itemstab' => 8,
			'porder'   => 'date_add',
		);		
	}	
	public function install()
	{
		$a =  (parent::install() AND $this->registerHook('home')  AND $this->registerHook('header'));
 
			
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
					'.$this->params->categoryTag('catids', $this->params->get('catids'), 'Categories', ' size="10" multiple="multiple"').'
					<p class="clear">'.$this->l('The maximum number of products in each page Carousel (default: 4).').'</p>
				</div>
				<div class="row-form">
					'.$this->params->selectTag( $orders, "Order By", 'porder',  $this->params->get('porder') ).'
					<p class="clear">'.$this->l('The maximum number of products in each page Carousel (default: 4).').'</p>
				</div>
			
				<div class="row-form">
					'.$this->params->inputTag( 'Items Per Page', 'itemspage', $this->params->get('itemspage') ).'
					<p class="clear">'.$this->l('The maximum number of products in each page Carousel (default: 4).').'</p>
				</div>
				<div class="row-form">
					'.$this->params->inputTag( 'Colums In Tab', 'columns', $this->params->get('columns') ).'
					<p class="clear">'.$this->l('The maximum column products in each page Carousel (default: 4).').'</p>
				</div>
				<div class="row-form">
					'.$this->params->inputTag( 'Items In Tab', 'itemstab', $this->params->get('itemstab') ).'
					<p class="clear">'.$this->l('The maximum number of products in each Carousel (default: 8).').'</p>
				</div>
				
				 
				<center><input type="submit" name="submitSpecials" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
	}

	public function hookDisplayHome( $params ){
		return $this->hookRightColumn( $params );
	}
	public function hookDisplaySlideshow( $params ){
		return $this->hookRightColumn( $params );
	}
	public function hookDisplayPromoteTop( $params ){
		return $this->hookRightColumn( $params );
	}
	public function hookDisplayBottom( $params ){
		return $this->hookRightColumn( $params );
	}
	public function hookDisplayContentBottom( $params ){
		return $this->hookRightColumn( $params );
	}
	
	
	public function hookRightColumn($params)
	{		 
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
		$products = $this->getProducts((int)Context::getContext()->language->id, 1, $nb, $porder[0], $porder[1] );
		
		
		$dir = dirname(__FILE__)."/products.tpl";
		$tdir = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$this->name.'/products.tpl';
	
		if( file_exists($tdir) ){
			$dir = $tdir;
		}
	 
		$this->smarty->assign(array(
			'itemsperpage'=> $items_page,
			'columnspage' => $columns_page,
			'product_tpl' => $dir,
			'products'	 => $products,
			'scolumn'     => 12/$columns_page,
		 
		 
			'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
		));
		
		return $this->display(__FILE__, 'blockleoprodcarousel.tpl');
	}
	
	/**
	  * Return current category products
	  *
	  * @param integer $id_lang Language ID
	  * @param integer $p Page number
	  * @param integer $n Number of products per page
	  * @param boolean $get_total return the number of results instead of the results themself
	  * @param boolean $active return only active products
	  * @param boolean $random active a random filter for returned products
	  * @param int $random_number_products number of products to return if random is activated
	  * @param boolean $check_access set to false to return all products (even if customer hasn't access)
	  * @return mixed Products or number of products
	  */
	public function getProducts($id_lang, $p, $n, $order_by = null, $order_way = null, $get_total = false, $active = true, $random = false, $random_number_products = 1, $check_access = true, Context $context = null)
	{
		if (!$context)
			$context = Context::getContext();
		 
		
		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
			$front = false;
			
		if ($p < 1) $p = 1;

		if (empty($order_by))
			$order_by = 'position';
		else
			/* Fix for all modules which are now using lowercase values for 'orderBy' parameter */
			$order_by = strtolower($order_by);

		if (empty($order_way))
			$order_way = 'ASC';
		if ($order_by == 'id_product' || $order_by == 'date_add' || $order_by == 'date_upd')
			$order_by_prefix = 'p';
		elseif ($order_by == 'name')
			$order_by_prefix = 'pl';
		elseif ($order_by == 'manufacturer')
		{
			$order_by_prefix = 'm';
			$order_by = 'name';
		}
		elseif ($order_by == 'position')
			$order_by_prefix = 'cp';

		if ($order_by == 'price')
			$order_by = 'orderprice';

		if (!Validate::isBool($active) || !Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way))
			die (Tools::displayError());

		$id_supplier = (int)Tools::getValue('id_supplier');

		/* Return only the number of products */
		if ($get_total)
		{
			$sql = 'SELECT COUNT(cp.`id_product`) AS total
					FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').'
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON p.`id_product` = cp.`id_product`
					WHERE cp.`id_category` IN("'.implode('","',$this->catids).'") '.
					($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').
					($active ? ' AND product_shop.`active` = 1' : '').
					($id_supplier ? 'AND p.id_supplier = '.(int)$id_supplier : '');
			return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
		}

		$sql = 'SELECT DISTINCT p.id_product, p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, product_attribute_shop.`id_product_attribute`, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, image_shop.`id_image`,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
				FROM `'._DB_PREFIX_.'category_product` cp
				LEFT JOIN `'._DB_PREFIX_.'product` p
					ON p.`id_product` = cp.`id_product`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
					ON (product_shop.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il
					ON (image_shop.`id_image` = il.`id_image`
					AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
					ON m.`id_manufacturer` = p.`id_manufacturer`
				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.'
				AND (pa.id_product_attribute IS NULL OR product_attribute_shop.id_shop='.(int)$context->shop->id.') 
				AND (i.id_image IS NULL OR image_shop.id_shop='.(int)$context->shop->id.')
					AND cp.`id_category` IN("'.implode('","',$this->catids).'") '
					.($active ? ' AND product_shop.`active` = 1' : '')
					.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
					.($id_supplier ? ' AND p.id_supplier = '.(int)$id_supplier : '');

		if ($random === true)
		{
			$sql .= ' ORDER BY RAND()';
			$sql .= ' LIMIT 0, '.(int)$random_number_products;
		}
		else
			$sql .= ' ORDER BY '.(isset($order_by_prefix) ? $order_by_prefix.'.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way).'
			LIMIT '.(((int)$p - 1) * (int)$n).','.(int)$n;

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if ($order_by == 'orderprice')
			Tools::orderbyPrice($result, $order_way);

		if (!$result)
			return array();

		/* Modify SQL result */
		return Product::getProductsProperties($id_lang, $result);
	}
	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'blockleoprodcarousel.css', 'all');
	}
}

