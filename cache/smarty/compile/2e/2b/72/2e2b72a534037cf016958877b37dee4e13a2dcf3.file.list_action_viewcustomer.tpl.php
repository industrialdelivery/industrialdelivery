<?php /* Smarty version Smarty-3.1.19, created on 2016-03-03 08:23:57
         compiled from "/home/miscal5/industrialdelivery.net/public_html/modules/blocknewsletter/views/templates/admin/list_action_viewcustomer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:199880282056d7e68d0a49d9-71013713%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2e2b72a534037cf016958877b37dee4e13a2dcf3' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/modules/blocknewsletter/views/templates/admin/list_action_viewcustomer.tpl',
      1 => 1455984266,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '199880282056d7e68d0a49d9-71013713',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'href' => 0,
    'disable' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56d7e68d0e46a8_47728063',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56d7e68d0e46a8_47728063')) {function content_56d7e68d0e46a8_47728063($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['href']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="edit btn btn-default <?php if ($_smarty_tpl->tpl_vars['disable']->value) {?>disabled<?php }?>" title="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" >
	<i class="icon-search-plus"></i> <?php echo $_smarty_tpl->tpl_vars['action']->value;?>

</a><?php }} ?>
