<?php /* Smarty version Smarty-3.1.19, created on 2016-02-20 19:16:01
         compiled from "/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/controllers/modules/warning_module.tpl" */ ?>
<?php /*%%SmartyHeaderCode:49767641656c8ad6143fb76-10805306%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d827462eeb8b016b349b1ca36faedecf29331f9' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/controllers/modules/warning_module.tpl',
      1 => 1455984263,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '49767641656c8ad6143fb76-10805306',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'module_link' => 0,
    'text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56c8ad6144bb78_68074176',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56c8ad6144bb78_68074176')) {function content_56c8ad6144bb78_68074176($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module_link']->value, ENT_QUOTES, 'UTF-8', true);?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value;?>
</a><?php }} ?>
