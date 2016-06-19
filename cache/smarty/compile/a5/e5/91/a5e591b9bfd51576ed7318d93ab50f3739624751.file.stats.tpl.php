<?php /* Smarty version Smarty-3.1.19, created on 2016-02-28 14:03:42
         compiled from "/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/controllers/stats/stats.tpl" */ ?>
<?php /*%%SmartyHeaderCode:183918214656d2f02ea51180-44272691%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a5e591b9bfd51576ed7318d93ab50f3739624751' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/controllers/stats/stats.tpl',
      1 => 1455984264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183918214656d2f02ea51180-44272691',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'module_name' => 0,
    'module_instance' => 0,
    'hook' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56d2f02ea63f38_28195793',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56d2f02ea63f38_28195793')) {function content_56d2f02ea63f38_28195793($_smarty_tpl) {?>

		<div class="panel">
			<?php if ($_smarty_tpl->tpl_vars['module_name']->value) {?>
				<?php if ($_smarty_tpl->tpl_vars['module_instance']->value&&$_smarty_tpl->tpl_vars['module_instance']->value->active) {?>
					<?php echo $_smarty_tpl->tpl_vars['hook']->value;?>

				<?php } else { ?>
					<?php echo smartyTranslate(array('s'=>'Module not found'),$_smarty_tpl);?>

				<?php }?>
			<?php } else { ?>
				<h3 class="space"><?php echo smartyTranslate(array('s'=>'Please select a module from the left column.'),$_smarty_tpl);?>
</h3>
			<?php }?>
		</div>
	</div>
</div><?php }} ?>
