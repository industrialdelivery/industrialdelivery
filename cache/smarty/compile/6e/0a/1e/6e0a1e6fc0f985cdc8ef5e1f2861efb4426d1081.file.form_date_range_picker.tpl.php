<?php /* Smarty version Smarty-3.1.19, created on 2016-02-28 14:03:42
         compiled from "/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/form_date_range_picker.tpl" */ ?>
<?php /*%%SmartyHeaderCode:185076259356d2f02ea0e132-91779859%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6e0a1e6fc0f985cdc8ef5e1f2861efb4426d1081' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/form_date_range_picker.tpl',
      1 => 1455984263,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185076259356d2f02ea0e132-91779859',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'action' => 0,
    'translations' => 0,
    'datepickerFrom' => 0,
    'datepickerTo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56d2f02ea3ecb5_08618045',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56d2f02ea3ecb5_08618045')) {function content_56d2f02ea3ecb5_08618045($_smarty_tpl) {?><div id="calendar" class="panel">
	<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>
" method="post" id="calendar_form" name="calendar_form" class="form-inline">
		<div class="row">
			<div class="col-lg-6">
				<div class="btn-group">
					<button type="submit" name="submitDateDay" class="btn btn-default submitDateDay"><?php echo $_smarty_tpl->tpl_vars['translations']->value['Day'];?>
</button>
					<button type="submit" name="submitDateMonth" class="btn btn-default submitDateMonth"><?php echo $_smarty_tpl->tpl_vars['translations']->value['Month'];?>
</button>
					<button type="submit" name="submitDateYear" class="btn btn-default submitDateYear"><?php echo $_smarty_tpl->tpl_vars['translations']->value['Year'];?>
</button>
					<button type="submit" name="submitDateDayPrev" class="btn btn-default submitDateDayPrev"><?php echo $_smarty_tpl->tpl_vars['translations']->value['Day'];?>
-1</button>
					<button type="submit" name="submitDateMonthPrev" class="btn btn-default submitDateMonthPrev"><?php echo $_smarty_tpl->tpl_vars['translations']->value['Month'];?>
-1</button>
					<button type="submit" name="submitDateYearPrev" class="btn btn-default submitDateYearPrev"><?php echo $_smarty_tpl->tpl_vars['translations']->value['Year'];?>
-1</button>
				</div>					
			</div>
			<div class="col-lg-6">
				<div class="row">
					<div class="col-md-8">
						<div class="row">
							<div class="col-xs-6">
								<div class="input-group">
									<label class="input-group-addon"><?php if (isset($_smarty_tpl->tpl_vars['translations']->value['From'])) {?><?php echo $_smarty_tpl->tpl_vars['translations']->value['From'];?>
<?php } else { ?><?php echo smartyTranslate(array('s'=>'From:'),$_smarty_tpl);?>
<?php }?></label>
									<input type="text" name="datepickerFrom" id="datepickerFrom" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['datepickerFrom']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="datepicker  form-control">
								</div>
							</div>
							<div class="col-xs-6">
								<div class="input-group">
									<label class="input-group-addon"><?php if (isset($_smarty_tpl->tpl_vars['translations']->value['To'])) {?><?php echo $_smarty_tpl->tpl_vars['translations']->value['To'];?>
<?php } else { ?><?php echo smartyTranslate(array('s'=>'From:'),$_smarty_tpl);?>
<?php }?></label>
									<input type="text" name="datepickerTo" id="datepickerTo" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['datepickerTo']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="datepicker  form-control">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<button type="submit" name="submitDatePicker" id="submitDatePicker" class="btn btn-default"><i class="icon-save"></i> <?php echo smartyTranslate(array('s'=>'Save'),$_smarty_tpl);?>
</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		if ($("form#calendar_form .datepicker").length > 0)
			$("form#calendar_form .datepicker").datepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd'
			});
	});
</script><?php }} ?>
