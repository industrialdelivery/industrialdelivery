<?php
if (!defined('_CAN_LOAD_FILES_'))
	exit;
?>
<link rel="stylesheet" href="<?php echo __PS_BASE_URI__."modules/".$this->name."/assets/admin/form.css";?>" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="<?php echo __PS_BASE_URI__."modules/".$this->name."/assets/admin/form.js";?>"></script>
<?php
// TinyMCE
if(version_compare(_PS_VERSION_, '1.4', '<')){
	echo '
		<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
		<script type="text/javascript">
		function tinyMCEInit(element)
		{
			$().ready(function() {
				$(element).tinymce({
					// Location of TinyMCE script
					script_url : \''.__PS_BASE_URI__.'js/tinymce/jscripts/tiny_mce/tiny_mce.js\',
					// General options
					theme : "advanced",
					plugins : "safari,pagebreak,style,layer,table,advimage,advlink,inlinepopups,media,searchreplace,contextmenu,paste,directionality,fullscreen",
					// Theme options
					theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,,|,forecolor,backcolor",
					theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,ltr,rtl,|,fullscreen",
					theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,pagebreak",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : false,
					content_css : "'.__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/global.css",
					document_base_url : "'.__PS_BASE_URI__.'",
					width: "582",
					height: "auto",
					font_size_style_values : "8pt, 10pt, 12pt, 14pt, 18pt, 24pt, 36pt",
					elements : "nourlconvert",
					convert_urls : false,
					language : "'.(file_exists(_PS_ROOT_DIR_.'/js/tinymce/jscripts/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'"
				});
			});
		}
		tinyMCEInit(\'textarea.rte\');
		</script>
		';
}elseif(version_compare(_PS_VERSION_, '1.5', '<')){
	global $cookie;
	$iso = Language::getIsoById((int)($cookie->id_lang));
	$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
	$ad = dirname($_SERVER["PHP_SELF"]);
	echo '
		<script type="text/javascript">	
		var iso = \''.$isoTinyMCE.'\' ;
		var pathCSS = \''._THEME_CSS_DIR_.'\' ;
		var ad = \''.$ad.'\' ;
		</script>
		<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce.inc.js"></script>';
	/* Set Language */
}else{
	$this->context->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
	$this->context->controller->addJS(_PS_JS_DIR_.'tinymce.inc.js');
		
	global $cookie;
	$iso = Language::getIsoById((int)($cookie->id_lang));
	$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
	$ad = dirname($_SERVER["PHP_SELF"]);
	echo '
		<script type="text/javascript">	
		var iso = \''.$isoTinyMCE.'\' ;
		var pathCSS = \''._THEME_CSS_DIR_.'\' ;
		var ad = \''.$ad.'\' ;
		
		$(document).ready(function(){
			tinySetup();
		});
		</script>';	
}
?>
<script language='javascript'>
	id_language = <?php echo $this->_defaultFormLanguage;?>;
	function changeLanguage1(field, fieldsString, id_language_new, iso_code)
	{
		var fields = fieldsString.split('-');
		for (var i = 0; i < fields.length; ++i)
		{
			getE(fields[i] + '_' + id_language).style.display = 'none';
			getE(fields[i] + '_' + id_language_new).style.display = 'block';
			getE('language_current_' + fields[i]).src = '../img/l/' + id_language_new + '.jpg';
		}
		getE('languages_' + field).style.display = 'none';
		id_language = id_language_new;
	}
</script>
<h3><?php echo $this->l('Custom HTML Module Configuration');?></h3>
<?php 

$yesNoLang = array("0"=>$this->l('No'),"1"=>$this->l('Yes'));
?>
<form action="<?php echo $_SERVER['REQUEST_URI'].'&rand='.rand();?>" method="post" id="lofform">
 <input type="submit" name="submit" value="<?php echo $this->l('Update');?>" class="button" />
  <fieldset>
    <legend><img src="../img/admin/contact.gif" /><?php echo $this->l('Content'); ?></legend>
    <div class="clearfix">
	<?php
		echo '	<label>'.$this->l('Module title').' </label>
				<div class="margin-form">';
		foreach ($this->_languages as $language)
			echo '	<div id="module_title_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
						<input size="50" type="text" name="module_title_'.$language['id_lang'].'" value="'.$this->getParamValue('module_title_'.$language['id_lang'],'Custom HTML').'" />
					</div>';
		echo $this->_params->displayFlags1($this->languages,$this->_languages, $this->_defaultFormLanguage, $this->divLangName, 'module_title');
		echo '	</div><div class="clear space">&nbsp;</div>';
		echo '
			<label>'.$this->l('Show title:').' </label>
			<div class="margin-form">
				<input type="radio" name="show_title" id="show_title_on" value="1" '.($this->getParamValue('show_title',1) ? 'checked="checked" ' : '').'/>
				<label class="t" for="show_title_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Yes').'" title="'.$this->l('Yes').'" /></label>
				<input type="radio" name="show_title" id="show_title_off" value="0" '.($this->getParamValue('show_title',1) == 0 ? 'checked="checked" ' : '').'/>
				<label class="t" for="show_title_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('No').'" title="'.$this->l('No').'" /></label>
			</div>';
			
		echo '	<label>'.$this->l('Class prefix').' </label>
				<div class="margin-form">';
			echo '	<input size="50" type="text" name="class_prefix" value="'.$this->getParamValue('class_prefix','').'" />
				</div>';
				
		echo '	<label>'.$this->l('Content').' </label>
				<div class="margin-form">';
		$defaultC = $this->contentDefault();
		foreach ($this->_languages as $language)
			echo '<div id="ccontent_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').';float: left;">
						<textarea class="rte" cols="80" rows="30" id="content_'.$language['id_lang'].'" name="content_'.$language['id_lang'].'">'.$this->getParamValue('content_'.$language['id_lang'],($language['iso_code'] == 'fr' ? $defaultC['fr'] : $defaultC['en'])).'</textarea>
					</div>';
		echo $this->_params->displayFlags1($this->languages,$this->_languages, $this->_defaultFormLanguage, $this->divLangName, 'ccontent');
		echo '	</div><div class="clear space">&nbsp;</div>';
	?>
    </div>
  </fieldset>
<br />
  <input type="submit" name="submit" value="<?php echo $this->l('Update');?>" class="button" />
</form>
