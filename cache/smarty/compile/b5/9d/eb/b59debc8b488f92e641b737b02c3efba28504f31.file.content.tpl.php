<?php /* Smarty version Smarty-3.1.19, created on 2016-02-27 22:52:25
         compiled from "/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/controllers/cms_content/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11322440856d21a99d988b4-18266588%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b59debc8b488f92e641b737b02c3efba28504f31' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/controllers/cms_content/content.tpl',
      1 => 1455984264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11322440856d21a99d988b4-18266588',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cms_breadcrumb' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56d21a99df1f92_47576989',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56d21a99df1f92_47576989')) {function content_56d21a99df1f92_47576989($_smarty_tpl) {?>

<?php if (isset($_smarty_tpl->tpl_vars['cms_breadcrumb']->value)) {?>
	<ul class="breadcrumb cat_bar">
		<?php echo $_smarty_tpl->tpl_vars['cms_breadcrumb']->value;?>

	</ul>
<?php }?>

<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

<?php }} ?>
