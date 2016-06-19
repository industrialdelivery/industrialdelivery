<?php /* Smarty version Smarty-3.1.19, created on 2016-02-27 23:02:19
         compiled from "/home/miscal5/industrialdelivery.net/public_html/themes/default-bootstrap/modules/blockwishlist/blockwishlist-extra.tpl" */ ?>
<?php /*%%SmartyHeaderCode:198838825956d21ceb7c0061-74754960%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2022a35de329444c9d2943d674768eb10e70765f' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/themes/default-bootstrap/modules/blockwishlist/blockwishlist-extra.tpl',
      1 => 1455984257,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '198838825956d21ceb7c0061-74754960',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'id_product' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56d21ceb7cc4a1_95586950',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56d21ceb7cc4a1_95586950')) {function content_56d21ceb7cc4a1_95586950($_smarty_tpl) {?>

<p class="buttons_bottom_block no-print">
	<a id="wishlist_button" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '<?php echo intval($_smarty_tpl->tpl_vars['id_product']->value);?>
', $('#idCombination').val(), document.getElementById('quantity_wanted').value); return false;" rel="nofollow"  title="<?php echo smartyTranslate(array('s'=>'Add to my wishlist','mod'=>'blockwishlist'),$_smarty_tpl);?>
">
		<?php echo smartyTranslate(array('s'=>'Add to wishlist','mod'=>'blockwishlist'),$_smarty_tpl);?>

	</a>
</p>
<?php }} ?>
