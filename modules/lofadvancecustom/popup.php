<?php
global $cookie, $link;

$module = new lofadvancecustom();
if(empty($link))
	$link = new Link();
$adminfolder = $module->getFolderAdmin();
$id_lang = Tools::getValue('id_lang');
if(Tools::getValue('secure_key') != $module->secure_key)
	die('Secure key is invalid.');
?>
<html>
	<head>
		<title><?php echo $module->l('Pop up');?></title>
		<link href="<?php echo __PS_BASE_URI__;?>css/admin.css"  type="text/css" rel="stylesheet"/>
		<style>
			body{height:100%;background-color: #FFFFFF;}
			#container{height:100%}
			#content{height:90%;border: none;padding: 0px;}
		</style>
		<script src="<?php echo _MODULE_DIR_.$module->name;?>/assets/admin/jquery-1.4.4.min.js" type="text/javascript"></script>	
		<script src="<?php echo _MODULE_DIR_.$module->name;?>/assets/admin/form.js" type="text/javascript"></script>	
		<script type="text/javascript">
			var helpboxes = false;
			var id_language = <?php echo $module->_defaultFormLanguage;?>;
			function changeLofLanguage(field, fieldsString, id_language_new, iso_code)
			{
				var fields = fieldsString.split('¤');
				for (var i = 0; i < fields.length; ++i)
				{
					getE(fields[i] + '_' + id_language).style.display = 'none';
					getE(fields[i] + '_' + id_language_new).style.display = 'block';
					getE('language_current_' + fields[i]).src = 'img/l/' + id_language_new + '.jpg';
				}
				getE('languages_' + field).style.display = 'none';
				id_language = id_language_new;
			}
		</script>
		<script src="<?php echo __PS_BASE_URI__;?>js/admin.js" type="text/javascript"></script>	
	</head>
	<body>
	<div id="container">
		<div id="content">
<?php
$errors = array();
$defaultLanguage = new Language((int)($module->_defaultFormLanguage));
$id_shop = Tools::getValue('id_shop', (int)Context::getContext()->shop->id);
if( isset($_GET['addBlock'])){
	$blocks = LofBlock::getBlocks(Tools::getValue('id_position'), $cookie->id_lang, $id_shop);
	$nb = count($blocks);
	if($nb >= LOF_MODULE_ADVANCE_CUSTOM_LIMIT_BLOCK){
		$errors[] = $module->l('Block number was limited');
	}
	$totalWidth = 0;
	if($blocks)
		foreach($blocks as $bl){
			$totalWidth += $bl['width'];
		}
	if(Tools::isSubmit('submitAddBlock') && $nb < LOF_MODULE_ADVANCE_CUSTOM_LIMIT_BLOCK){
		$titles = array();
		foreach($module->_languages as $language)
			$titles[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
		if(!Tools::getValue('title_'.$module->_defaultFormLanguage))
			$errors[] = $module->l('the field Title is required at least in '.$defaultLanguage->name);
		foreach($titles as $key => $val)
			if(!Validate::isGenericName($val))
				$errors[] = $module->l('the field Title is invalid');
		if(Tools::getValue('width') && !Validate::isFloat(Tools::getValue('width')))
			$errors[] = $module->l('the field Width is invalid');
		if($totalWidth + Tools::getValue('width') > 100)
			$errors[] = $module->l('Width total is invalid(>100)');
		if(!Tools::getValue('id_position'))
			$errors[] = $module->l('the field Position is invalid');
			
		if (!sizeof($errors)){
			$objBlock = new LofBlock();
			$objBlock->title = $titles;
			$objBlock->width = Tools::getValue('width');
			$objBlock->show_title = Tools::getValue('show_title');
			$objBlock->id_position = Tools::getValue('id_position');
			if(!$objBlock->add()){
				$errors[] = $module->l('An error occurred while creating object');
			}else{?>
				<script type="text/javascript">
					window.parent.location.href = '<?php echo __PS_BASE_URI__.$adminfolder.'/index.php?tab=AdminModules&configure=' . $module->name . '&token=' . Tools::getValue('token');?>';
				</script>
			<?php 
			}
		}
	}
	$divLangName="title";
	?>
	<fieldset>
		<legend><img src="img/admin/contact.gif"><?php echo $module->l('Add New A Block');?></legend>
		<?php if (sizeof($errors)){?>
			<div class="error">
				<img src="img/admin/error2.png" alt="<?php echo $module->l('error');?>"/><?php echo count($errors).' '.(count($errors) <= 1 ? $module->l('error') : $module->l('errors'));?>
				<br/>
				<ol>
					<?php foreach($errors as $e){?>
					<li><?php echo $e;?></li>
					<?php } ?>
				</ol>
			</div>
		<?php } ?>
		<form action="" method="post">
			<input type="hidden" value="<?php echo Tools::getValue('id_position');?>" name="id_position"/>
			<label><?php echo $module->l('Title:');?></label>
			<div class="margin-form">
				<?php
				foreach($module->_languages as $language)
					echo '	<div id="title_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $module->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
						<input size="30" type="text" id="input_title_'.$language['id_lang'].'" name="title_'.$language['id_lang'].'" /><sup> *</sup>
					</div>';
				echo $module->displayLofFlags($module->_languages, $module->_defaultFormLanguage, $divLangName, 'title');
				?>
			</div>
			<div class="clear"></div>
			<label><?php echo $module->l('Show Title:');?></label>
			<div class="margin-form">
				<input type="radio" name="show_title" id="show_title_on" onclick="toggleDraftWarning(false);" value="1" checked="checked"/>
				<label class="t" for="show_title_on"> <img src="img/admin/enabled.gif" alt="<?php echo $module->l('Enabled');?>" title="<?php echo $module->l('Enabled');?>" /></label>
				<input type="radio" name="show_title" id="show_title_off" onclick="toggleDraftWarning(true);" value="0"/>
				<label class="t" for="show_title_off"> <img src="img/admin/disabled.gif" alt="<?php echo $module->l('Disabled');?>" title="<?php echo $module->l('Disabled');?>" /></label>
			</div>
			<div class="clear"></div>
			<label><?php echo $module->l('Width:');?></label>
			<div class="margin-form">
				<input type="text" name="width" size="10"/> <span>%</span>
				<p class="clear"><?php echo $module->l('enter a number <=').' '.(100 - $totalWidth);?></p>
			</div>
			<div class="margin-form space">
				<input type="submit" value="<?php echo $module->l('Save');?>" name="submitAddBlock" class="button" />
			</div>
		</form>
	</fieldset>
	<?php
}elseif( isset($_GET['addItem'])){
	if(Tools::isSubmit('submitAddItem') && Tools::getValue('id_loffc_block')){
		$titles = array();
		$texts = array();
		foreach($module->_languages as $language){
			$titles[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
			$texts[$language['id_lang']] = Tools::getValue('text_'.$language['id_lang']);
		}
		if(!Tools::getValue('title_'.$module->_defaultFormLanguage))
			$errors[] = $module->l('the field Title is required at least in '.$defaultLanguage->name);
		foreach($titles as $key => $val)
			if(!Validate::isGenericName($val))
				$errors[] = $module->l('the field Title is invalid');
		if(Tools::getValue('type') == 'link'){
			if(Tools::getValue('linktype') == 'product' && !Validate::isUnsignedId(Tools::getValue('link_type_product'))){
				$errors[] = $module->l('the field Product is invalid');
			}
		}
		if(Tools::getValue('type') == 'module'){
			if( !Tools::getValue('module_name') || !Validate::isGenericName(Tools::getValue('module_name')) || !Tools::getValue('hook_name') || !Validate::isGenericName(Tools::getValue('hook_name'))){
				$errors[] = $module->l('the field Module is invalid');
			}
		}
		if (!sizeof($errors)){
			$objItem = new LofItem();
			$objItem->id_loffc_block_item = Tools::getValue('id_loffc_block_item');
			$objItem->id_loffc_block = Tools::getValue('id_loffc_block');
			$objItem->type = Tools::getValue('type');
			if(Tools::getValue('type') == 'link'){
				$objItem->linktype = Tools::getValue('linktype');
				$objItem->target = Tools::getValue('target');
				if(Tools::getValue('linktype') == 'product')
					$objItem->link_content = Tools::getValue('link_type_product');
				if(Tools::getValue('linktype') == 'category')
					$objItem->link_content = Tools::getValue('link_type_category');
				if(Tools::getValue('linktype') == 'cms')
					$objItem->link_content = Tools::getValue('link_type_cms');
				if(Tools::getValue('linktype') == 'link')
					$objItem->link_content = Tools::getValue('link_type_link');
				if(Tools::getValue('linktype') == 'manufacturer')
					$objItem->link_content = Tools::getValue('link_type_manufacturer');
				if(Tools::getValue('linktype') == 'supplier')
					$objItem->link_content = Tools::getValue('link_type_supplier');
			}elseif(Tools::getValue('type') == 'module'){
				$objItem->module_name = Tools::getValue('module_name');
				$objItem->hook_name = Tools::getValue('hook_name');
			}elseif(Tools::getValue('type') == 'custom_html'){
				$objItem->text = $texts;
			}elseif(Tools::getValue('type') == 'gmap'){
				$objItem->latitude = Tools::getValue('latitude');
				$objItem->longitude = Tools::getValue('longitude');
			}elseif(Tools::getValue('type') == 'addthis'){
				$objItem->addthis = 1;
			}
			
			$objItem->title = $titles;
			$objItem->show_title = Tools::getValue('show_title');
			if(!$objItem->id_loffc_block_item){
				if(!$objItem->add())
					$errors[] = $module->l('An error occurred while creating object');
				else{
					$items = LofBlock::getItems($objItem->id_loffc_block, $cookie->id_lang);
					$id_loftable = Tools::getValue('id_loftable');
					if ($id_loftable){ ?>
					<script type="text/javascript">
						jQuery(document).ready(function(){
							var parentWindow = $( parent.document.body );  
							parentWindow.find("#<?php echo $id_loftable;?> tbody").append('\
							<tr id="tr_<?php echo $objItem->id_loffc_block;?>_<?php echo $objItem->id;?>_<?php echo $objItem->position;?>" class="tr_0_<?php echo $objItem->id;?>_<?php echo $objItem->id_loffc_block;?> <?php echo (count($items)%2 ? 'alt_row' : '');?>">\
								<td class="dragHandle"><?php echo $objItem->title[Tools::getValue('id_lang',$cookie->id_lang)];?></td>\
								<td class="dragHandle"><?php echo $objItem->type;?></td>\
								<td><a class="display lofaddnew-block-item" href="<?php echo $link->getModuleLink("lofadvancecustom","popup",array('id_shop'=>(int)($this->context->shop->id),'id_loffc_block'=>$objItem->id_loffc_block,'addItem'=>1,'bo_theme'=>Tools::getValue('bo_theme'),'id_loffc_block_item'=>$objItem->id,'id_loftr'=>'tr_0_'.$objItem->id.'_'.$objItem->id_loffc_block,'token'=>Tools::getValue('token'),'secure_key'=>$module->secure_key));?>" title="edit">\
										<img title="edit" alt="edit" src="../img/admin/edit.gif">\
									</a>\
									<a class="display" onclick="LofDelete(\'tr_0_<?php echo $objItem->id;?>_<?php echo $objItem->id_loffc_block;?>\',\'loftable-<?php echo $objItem->id_loffc_block;?>\')" href="javascript:void(0)" title="delete">\
										<img title="delete" alt="delete" src="../img/admin/delete.gif">\
									</a>\
								</td>\
							</tr>\
							'); 
							parent.$.fancybox.close();
						});
					</script>
				<?php }
				}
			}else{
				$objItem->id = $objItem->id_loffc_block_item;
				if(!$objItem->update())
					$errors[] = $module->l('An error occurred while update object');
				else{
					$id_loftr = Tools::getValue('id_loftr');
					if ($id_loftr){ ?>
					<script type="text/javascript">
						jQuery(document).ready(function(){
							var parentWindow = $( parent.document.body );  
							parentWindow.find(".<?php echo $id_loftr;?>").html('\
							<td class="dragHandle"><?php echo $objItem->title[Tools::getValue('id_lang', $cookie->id_lang)];?></td>\
							<td class="dragHandle"><?php echo $objItem->type;?></td>\
							<td><a class="display lofaddnew-block-item" href="<?php echo $link->getModuleLink("lofadvancecustom","popup",array('id_shop'=>(int)($this->context->shop->id),'id_loffc_block'=>$objItem->id_loffc_block,'addItem'=>1,'bo_theme'=>Tools::getValue('bo_theme'),'id_loffc_block_item'=>$objItem->id,'id_loftr'=>'tr_0_'.$objItem->id.'_'.$objItem->id_loffc_block,'token'=>Tools::getValue('token'),'secure_key'=>$module->secure_key));?>" title="edit">\
									<img title="edit" alt="edit" src="../img/admin/edit.gif">\
								</a>\
								<a class="display" onclick="LofDelete(\'tr_0_<?php echo $objItem->id;?>_<?php echo $objItem->id_loffc_block;?>\',\'loftable-<?php echo $objItem->id_loffc_block;?>\')" href="javascript:void(0)" title="delete">\
									<img title="delete" alt="delete" src="../img/admin/delete.gif">\
								</a>\
							</td>\
							');
							parent.$.fancybox.close();
						});
					</script>
				<?php }
				}
			}
			
		}
	}
	$divLangName = 'title¤ctext';
	$id_loffc_block_item = Tools::getValue('id_loffc_block_item');
	$obj = new LofItem($id_loffc_block_item);
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			var module_name = $('#lofmodule option:selected').val();
			var hook_name = $('#lofhook option:selected').val();
			var id_loffc_block = $('#id_loffc_block').val();
			if(module_name !== undefined ){
				if(id_loffc_block){
					lof_megamenu_ajax(module_name, hook_name);
				}else
					lof_megamenu_ajax(module_name, '');
			}
			$('#lofmodule').change(function(){
				var module_name = $('#lofmodule option:selected').val();
				lof_megamenu_ajax(module_name);
			});
		});
		function lof_megamenu_ajax(module_name, hook_name){
			if(hook_name != '')
				var str = "&hook_name="+hook_name;
			$.ajax({
				type: "POST",
				url: "<?php echo _MODULE_DIR_.$module->name;?>/ajax.php",
				data: "module_name="+module_name+str+"&lofajax=1&task=gethook",
				success: function(data){
					select_innerHTML(document.getElementById("lofhook"),data);
				}
			});
		}
	</script>
	<fieldset>
		<legend><img src="img/admin/contact.gif"><?php echo $module->l('Add New A Block');?></legend>
		<?php if (sizeof($errors)){?>
			<div class="error">
				<img src="img/admin/error2.png" alt="<?php echo $module->l('error');?>"/><?php echo count($errors).' '.(count($errors) <= 1 ? $module->l('error') : $module->l('errors'));?>
				<br/>
				<ol>
					<?php foreach($errors as $e){?>
					<li><?php echo $e;?></li>
					<?php } ?>
				</ol>
			</div>
		<?php } ?>
		<form action="" method="post">
			<input type="hidden" value="<?php echo $obj->id_loffc_block_item;?>" name="id_loffc_block_item"/>
			<input type="hidden" value="<?php echo Tools::getValue('id_loffc_block');?>" name="id_loffc_block" id="id_loffc_block"/>
			<label><?php echo $module->l('Title:');?></label>
			<div class="margin-form">
				<?php
				foreach($module->_languages as $language)
					echo '	<div id="title_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $module->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
						<input size="30" type="text" id="input_title_'.$language['id_lang'].'" name="title_'.$language['id_lang'].'" value="'.htmlentities((isset($obj->{'title'}[(int)($language['id_lang'])]) ? $obj->{'title'}[(int)($language['id_lang'])] : ''), ENT_COMPAT, 'UTF-8').'"/><sup> *</sup>
					</div>';
				echo $module->displayLofFlags($module->_languages, $module->_defaultFormLanguage, $divLangName, 'title');
				?>
			</div>
			<div class="clear"></div>
			<label><?php echo $module->l('Show Title:');?></label>
			<div class="margin-form">
				<input type="radio" name="show_title" id="show_title_on" onclick="toggleDraftWarning(false);" value="1" <?php echo ($obj->show_title ? 'checked="checked" ' : '');?>/>
				<label class="t" for="show_title_on"> <img src="img/admin/enabled.gif" alt="<?php echo $module->l('Enabled');?>" title="<?php echo $module->l('Enabled');?>" /></label>
				<input type="radio" name="show_title" id="show_title_off" onclick="toggleDraftWarning(true);" value="0" <?php echo (!$obj->show_title ? 'checked="checked" ' : '');?>/>
				<label class="t" for="show_title_off"> <img src="img/admin/disabled.gif" alt="<?php echo $module->l('Disabled');?>" title="<?php echo $module->l('Disabled');?>" /></label>
			</div>
			<label><?php echo $module->l('Type:');?></label>
			<div class="margin-form">
				<select name="type" id="lof-type">
					<?php foreach($module->type as $t){ ?>
					<option value="<?php echo $t;?>" <?php echo ($obj->type == $t ? 'selected="selected" ' : '');?>><?php echo $t;?></option>
					<?php } ?>
				</select>
			</div>
			<div class="lof-custom custom_link">
				<label><?php echo $module->l('Target:');?></label>
				<div class="margin-form">
					<select name="target" size="4">
						<option value="_self"<?php echo ($obj->target == '_self' || !$obj->id ? ' selected="selected"' : '' );?>>&nbsp;<?php echo $module->l('Parent Window with Browser Navigation');?></option>
						<option value="_blank"<?php echo ($obj->target == '_blank' ? ' selected="selected"' : '' );?>>&nbsp;<?php echo $module->l('New Window with Browser Navigation');?></option>
						<option value="_newwithout"<?php echo ($obj->target == '_newwithout' ? ' selected="selected"' : '' );?>>&nbsp;<?php echo $module->l('New Window without Browser Navigation');?></option>
					</select>
				</div>
				<label><?php echo $module->l('Link Type:');?></label>
				<div class="margin-form">
					<select name="linktype" id="linktype">
						<?php foreach($module->linktype as $l){?>
							<option value="<?php echo $l;?>"<?php echo ($l == $obj->linktype ? 'selected=slected' : '');?>><?php echo $l;?></option>
						<?php } ?>
					</select>
				</div>
				<div class="link_type link_type_product">
					<label><?php echo $module->l('Product:');?></label>
					<div class="margin-form">
						<input type="text" name="link_type_product" value="<?php echo (($obj->linktype == 'product') ? $obj->link_content : '');?>"/>
					</div>
				</div>
				<div class="link_type link_type_link">
					<label><?php echo $module->l('Link:');?></label>
					<div class="margin-form">
						<input type="text" name="link_type_link" value="<?php echo (($obj->linktype == 'link') ? $obj->link_content : '');?>" size="50"/>
					</div>
				</div>
				<div class="link_type link_type_category">
					<label><?php echo $module->l('Category:');?></label>
					<div class="margin-form">
						<?php
						$cates = $module->getCategories();
						
						?>
						<select name="link_type_category">
						<?php
						if($cates){
							foreach($cates as $c){?>
								<option value="<?php echo $c['id_category'];?>"<?php echo (($obj->linktype == 'category') && ($c['id_category'] == $obj->link_content)? 'selected=selected' : '');?>><?php echo $c['name'];?></option>
							<?php }
						}
						?>
						</select>
					</div>
				</div>
				<div class="link_type link_type_manufacturer">
					<label><?php echo $module->l('Manufacturer:');?></label>
					<div class="margin-form">
						<select name="link_type_manufacturer">
						<?php
						$manufacturers = Manufacturer::getManufacturers(false, $id_lang, true);
						if($cates){
							foreach($manufacturers as $c){?>
								<option value="<?php echo $c['id_manufacturer'];?>"<?php echo (($obj->linktype == 'manufacturer') && ($c['id_manufacturer'] == $obj->link_content) ? 'selected=selected' : '');?>><?php echo $c['name'];?></option>
							<?php }
						}
						?>
						</select>
					</div>
				</div>
				<div class="link_type link_type_supplier">
					<label><?php echo $module->l('Supplier:');?></label>
					<div class="margin-form">
						<select name="link_type_supplier">
						<?php
						$suppliers = Supplier::getSuppliers(false, $id_lang, true);
						if($cates){
							foreach($suppliers as $c){?>
								<option value="<?php echo $c['id_supplier'];?>"<?php echo (($obj->linktype == 'supplier') && ($c['id_supplier'] == $obj->link_content) ? 'selected=selected' : '');?>><?php echo $c['name'];?></option>
							<?php }
						}
						?>
						</select>
					</div>
				</div>
				<div class="link_type link_type_cms">
					<label><?php echo $module->l('CMS:');?></label>
					<div class="margin-form">
						<select name="link_type_cms">
						<?php
						$cmss = CMS::listCms($id_lang, false, true);
						if($cates){
							foreach($cmss as $c){?>
								<option value="<?php echo $c['id_cms'];?>"<?php echo (($obj->linktype == 'cms') && ($c['id_cms'] == $obj->link_content) ? 'selected=selected' : '');?>><?php echo $c['meta_title'];?></option>
							<?php }
						}
						?>
						</select>
					</div>
				</div>
			</div>
			<div class="lof-custom custom_module">
			<?php $mods = $module->getModules(); ?>
				<label><?php echo $module->l('Module:');?></label>
				<div class="margin-form">
					<select name="module_name" id="lofmodule">
					<?php foreach($mods as $m){?>
						<option value="<?php echo $m['name'];?>"<?php echo ($obj->module_name == $m['name'] ? ' selected="selected"' : '' );?>>&nbsp;<?php echo $m['name'];?></option>
					<?php }?>
					</select>
				</div>
				<label><?php echo $module->l('Hook:');?></label>
				<div class="margin-form">
					<select name="hook_name" id="lofhook">
					<?php foreach($module->hookAssign as $h){?>
						<option value="<?php echo $h;?>"<?php echo (strtolower($obj->hook_name) == strtolower($h) ? ' selected="selected"' : '' );?>>&nbsp;<?php echo $h;?></option>
					<?php }?>
					</select>
				</div>
			</div>
			<div class="lof-custom custom_custom_html">
				<label><?php echo $module->l('Custom Text:');?></label>
				<div class="margin-form">
				<?php
				foreach ($module->_languages as $language)
					echo '	<div id="ctext_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $module->_defaultFormLanguage ? 'block' : 'none').';float: left;">
								<textarea class="rte" cols="80" rows="30" id="text_'.$language['id_lang'].'" name="text_'.$language['id_lang'].'">'.htmlentities((isset($obj->{'text'}[$language['id_lang']]) ? stripslashes($obj->{'text'}[$language['id_lang']]) : ''), ENT_COMPAT, 'UTF-8').'</textarea>
							</div>';
				echo  $module->displayLofFlags($module->_languages, $module->_defaultFormLanguage, $divLangName, 'ctext');
				?>
				</div>
			</div>
			<div class="lof-custom custom_gmap">
				<label><?php echo $module->l('Latitude:');?></label>
				<div class="margin-form">
					<input type="text" name="latitude" value="<?php echo $obj->latitude;?>"/>
				</div>
				<label><?php echo $module->l('Longitude:');?></label>
				<div class="margin-form">
					<input type="text" name="longitude" value="<?php echo $obj->longitude;?>"/>
				</div>
			</div>
			<div class="clear"></div>
			<div class="margin-form space">
				<input type="submit" value="<?php echo $module->l('Save');?>" name="submitAddItem" class="button" />
			</div>
			<?php
			// TinyMCE
			if(_PS_VERSION_ < "1.5"){
				$iso = Language::getIsoById((int)($cookie->id_lang));
				$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
				$ad = dirname($_SERVER["PHP_SELF"]);
				echo '
					<script type="text/javascript">	
					var iso = \''.$isoTinyMCE.'\' ;
					var pathCSS = \''._THEME_CSS_DIR_.'\' ;
					var ad = \''.__PS_BASE_URI__.$adminfolder.'\' ;
					</script>
					<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
					<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce.inc.js"></script>';
			}else{
				echo '<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
					<script type="text/javascript" src="'._PS_JS_DIR_.'tinymce.inc.js"></script>';
				
				global $cookie, $currentIndex;
				$iso = Language::getIsoById((int)($cookie->id_lang));
				$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
				$ad = dirname($_SERVER["PHP_SELF"]);
				echo '
					<script type="text/javascript">	
					var iso = \''.$isoTinyMCE.'\' ;
					var pathCSS = \''._THEME_CSS_DIR_.'\' ;
					var ad = \''.__PS_BASE_URI__.$adminfolder.'\' ;
					$(document).ready(function(){
						tinySetup();
					});
					</script>';
			}
			?>
		</form>
	</fieldset>
<?php } ?>
		</div>
	</div>
	</body>
</html>