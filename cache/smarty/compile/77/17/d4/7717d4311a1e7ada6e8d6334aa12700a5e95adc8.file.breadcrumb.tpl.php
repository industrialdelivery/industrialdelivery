<?php /* Smarty version Smarty-3.1.19, created on 2016-02-20 19:18:28
         compiled from "/home/miscal5/industrialdelivery.net/public_html/themes/default-bootstrap/breadcrumb.tpl" */ ?>
<?php /*%%SmartyHeaderCode:109648549956c8adf4004188-96015665%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7717d4311a1e7ada6e8d6334aa12700a5e95adc8' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/themes/default-bootstrap/breadcrumb.tpl',
      1 => 1455984256,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '109648549956c8adf4004188-96015665',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_dir' => 0,
    'path' => 0,
    'category' => 0,
    'navigationPipe' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56c8adf40508b1_17111395',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56c8adf40508b1_17111395')) {function content_56c8adf40508b1_17111395($_smarty_tpl) {?>

<!-- Breadcrumb -->
<?php if (isset(Smarty::$_smarty_vars['capture']['path'])) {?><?php $_smarty_tpl->tpl_vars['path'] = new Smarty_variable(Smarty::$_smarty_vars['capture']['path'], null, 0);?><?php }?>
<div class="breadcrumb clearfix">
	<a class="home" href="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
" title="<?php echo smartyTranslate(array('s'=>'Return to Home'),$_smarty_tpl);?>
"><i class="icon-home"></i></a>
	<?php if (isset($_smarty_tpl->tpl_vars['path']->value)&&$_smarty_tpl->tpl_vars['path']->value) {?>
		<span class="navigation-pipe" <?php if (isset($_smarty_tpl->tpl_vars['category']->value)&&isset($_smarty_tpl->tpl_vars['category']->value->id_category)&&$_smarty_tpl->tpl_vars['category']->value->id_category==1) {?>style="display:none;"<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['navigationPipe']->value, ENT_QUOTES, 'UTF-8', true);?>
</span>
		<?php if (!strpos($_smarty_tpl->tpl_vars['path']->value,'span')) {?>
			<span class="navigation_page"><?php echo $_smarty_tpl->tpl_vars['path']->value;?>
</span>
		<?php } else { ?>
			<?php echo $_smarty_tpl->tpl_vars['path']->value;?>

		<?php }?>
	<?php }?>
</div>
<?php if (isset($_GET['search_query'])&&isset($_GET['results'])&&$_GET['results']>1&&isset($_SERVER['HTTP_REFERER'])) {?>
<div class="pull-right">
	<strong>
		<a href="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES, 'UTF-8', true);?>
" name="back">
			<i class="icon-chevron-left left"></i> <?php echo smartyTranslate(array('s'=>'Back to Search results for "%s" (%d other results)','sprintf'=>array($_GET['search_query'],$_GET['results'])),$_smarty_tpl);?>

		</a>
	</strong>
</div>
<?php }?>
<!-- /Breadcrumb --><?php }} ?>
