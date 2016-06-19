<?php /* Smarty version Smarty-3.1.19, created on 2016-02-20 19:18:40
         compiled from "/home/miscal5/industrialdelivery.net/public_html/modules/paypal/views/templates/hook/column.tpl" */ ?>
<?php /*%%SmartyHeaderCode:179924940256c8ae00b65d63-93008019%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'edcd9a58c4421d3664adbf7cd56f157af5818951' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/modules/paypal/views/templates/hook/column.tpl',
      1 => 1455984267,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '179924940256c8ae00b65d63-93008019',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_dir_ssl' => 0,
    'logo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56c8ae00b922b4_88126756',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56c8ae00b922b4_88126756')) {function content_56c8ae00b922b4_88126756($_smarty_tpl) {?>

<div id="paypal-column-block">
	<p><a href="<?php echo $_smarty_tpl->tpl_vars['base_dir_ssl']->value;?>
modules/paypal/about.php" rel="nofollow"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" alt="PayPal" title="<?php echo smartyTranslate(array('s'=>'Pay with PayPal','mod'=>'paypal'),$_smarty_tpl);?>
" style="max-width: 100%" /></a></p>
</div>
<?php }} ?>
