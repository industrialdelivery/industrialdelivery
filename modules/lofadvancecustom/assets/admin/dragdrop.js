/*
* 2007-2011 PrestaShop 
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 6594 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

function LofBlocksDnD(secure_key)
{
	$(document).ready(function()
	{
		$("#lofform").delegate('.loftable','hover',function(){
			$(this).tableDnD({
				onDragStart: function(table, row) {
					originalOrder = $.tableDnD.serialize();
					reOrder = ':even';
					if (table.tBodies[0].rows[1] && $('#' + table.tBodies[0].rows[1].id).hasClass('alt_row'))
						reOrder = ':odd';
				},
				dragHandle: 'dragHandle',
				onDragClass: 'myDragClass',
				onDrop: function(table, row) {
					if (originalOrder != $.tableDnD.serialize())
					{
						var tableDrag = $('#' + table.id);
						$.ajax({
							type: 'POST',
							async: false,
							url: '../modules/lofadvancecustom/ajax.php?lofajax&task=positionItem&' + $.tableDnD.serialize(),
							data: 'action=dnd&secure_key='+secure_key,
							success: function(data) {
								tableDrag.find('tbody tr').removeClass('alt_row');
								tableDrag.find('tbody tr' + reOrder).addClass('alt_row');
								tableDrag.find('tbody td.positions').each(function(i) {
									$(this).html(i+1);
								});
								tableDrag.find('tbody td.dragHandle a:hidden').show();
								tableDrag.find('tbody td.dragHandle:last a:even').hide();
								tableDrag.find('tbody td.dragHandle:first a:odd').hide();
								var reg = /_[0-9]$/g;
								tableDrag.find('tbody tr').each(function(i) {
									$(this).attr('id', $(this).attr('id').replace(reg, '_' + i));
								});
							}
						});
					}
				}
			});
		});
	});
}

function LofDelete(class_tr, id_table){
	$.ajax({
		type: 'POST',
		async: false,
		url: '../modules/lofadvancecustom/ajax.php?lofajax&task=deleteItem&class_tr=' + class_tr,
		data: 'action=lofdeleteItem',
		dataType: 'json',
		success: function(json_data) {
			if(json_data.result == 1){
				$('.' + class_tr).remove();
				$('#'+id_table).find('tr').each(function(i){
					if(i%2 == 0){
						$(this).removeClass('alt_row');
					}else{
						$(this).removeClass('alt_row').addClass('alt_row');
					}
				});
			}else{
				alert(json_data.error);
			}
		}
	});
}

function submitForm(id_form){
	if(!checkWidth(id_form)){
		alert('width total is invalid (>100)');
		return false;
	}
	$('#'+id_form).find('.lof-load').css('display','block');
	$.ajax({
		type: 'POST',
		async: false,
		url: '../modules/lofadvancecustom/ajax.php?lofajax&task=updateBlock&' + $('#'+id_form).serialize(),
		dataType: 'json',
		success: function(json_data) {
			if(json_data.result == 1){
				$('#'+id_form).find('.lof-load').css('display','none');
			}else{
				alert(json_data.error);
			}
		}
	});
}

function checkWidth(id_form){
	var totalW = 0;
	$('#'+id_form).parent().parent().find('input.lof-width').each(function(){
		var width = parseFloat($(this).val());
		if(isNaN(width)){
			$(this).val(0);
		}else{
			$(this).val(width);
			totalW += width;
		}
	});
	if(totalW > 100){
		return false;
	}
	return true;
}