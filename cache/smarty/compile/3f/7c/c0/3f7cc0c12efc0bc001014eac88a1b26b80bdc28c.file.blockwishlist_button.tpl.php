<?php /* Smarty version Smarty-3.1.19, created on 2016-02-28 16:39:18
         compiled from "/home/miscal5/industrialdelivery.net/public_html/themes/default-bootstrap/modules/blockwishlist/blockwishlist_button.tpl" */ ?>
<?php /*%%SmartyHeaderCode:201826492756d314a63b4374-86866097%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f7cc0c12efc0bc001014eac88a1b26b80bdc28c' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/themes/default-bootstrap/modules/blockwishlist/blockwishlist_button.tpl',
      1 => 1455984257,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '201826492756d314a63b4374-86866097',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'product' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56d314a63c3c64_11299518',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56d314a63c3c64_11299518')) {function content_56d314a63c3c64_11299518($_smarty_tpl) {?>

<div class="wishlist">
	<a class="addToWishlist wishlistProd_<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
" href="#" rel="<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
" onclick="WishlistCart('wishlist_block_list', 'add', '<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
', false, 1); return false;">
		<?php echo smartyTranslate(array('s'=>"Add to Wishlist",'mod'=>'blockwishlist'),$_smarty_tpl);?>

	</a>
</div><?php }} ?>
