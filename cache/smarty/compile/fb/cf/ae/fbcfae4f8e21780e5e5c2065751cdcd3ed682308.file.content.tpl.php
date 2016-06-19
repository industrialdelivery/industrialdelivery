<?php /* Smarty version Smarty-3.1.19, created on 2016-02-27 23:06:28
         compiled from "/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/controllers/emails/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:211937490156d21de4925c51-41037276%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fbcfae4f8e21780e5e5c2065751cdcd3ed682308' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/admin123/themes/default/template/controllers/emails/content.tpl',
      1 => 1455984264,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '211937490156d21de4925c51-41037276',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'token' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56d21de498cf01_87838660',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56d21de498cf01_87838660')) {function content_56d21de498cf01_87838660($_smarty_tpl) {?>

<script type="text/javascript">
	var textMsg = "<?php echo smartyTranslate(array('s'=>'This is a test message. Your server is now configured to send email.','js'=>1),$_smarty_tpl);?>
";
	var textSubject = "<?php echo smartyTranslate(array('s'=>'Test message -- Prestashop','js'=>1),$_smarty_tpl);?>
";
	var textSendOk = "<?php echo smartyTranslate(array('s'=>'A test email has been sent to the email address you provided.','js'=>1),$_smarty_tpl);?>
";
	var textSendError= "<?php echo smartyTranslate(array('s'=>'Error: Please check your configuration','js'=>1),$_smarty_tpl);?>
";
	var token_mail = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['token']->value, ENT_QUOTES, 'UTF-8', true);?>
';
	var errorMail = "<?php echo smartyTranslate(array('s'=>'This email address is not valid','js'=>1),$_smarty_tpl);?>
";
	$(document).ready(function() {
		if ($('input[name=PS_MAIL_METHOD]:checked').val() == 2)
			$('#configuration_fieldset_smtp').show();
		else
			$('#configuration_fieldset_smtp').hide();
	});
</script>

<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
	<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

<?php }?>

<?php }} ?>
