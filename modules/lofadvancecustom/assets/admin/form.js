$(document).ready(function() {
	
	$('.select-option').each(function() {		
		name = $(this).attr("name");		
		elemens = $.find('input[name="'+name+'"]');		
		for(i =0; i< elemens.length; i++){			
			if(!$(elemens[i]).attr("checked")){				
				$('.' + name + '-' +$(elemens[i]).val()).hide();
			}					
			
			$(elemens[i]).click(function() {
				subNameb   = $(this).attr("name");
				subElemens = $.find('input[name="'+subNameb+'"]');
				for(j =0; j< subElemens.length; j++){					
					if(!$(subElemens[j]).attr("checked")){						
						$('.' + $(subElemens[j]).attr("name") + '-' +$(subElemens[j]).val()).hide();
					}else{						
						$('.' + $(subElemens[j]).attr("name") + '-' +$(subElemens[j]).val()).show();
					}
				}				
			});
		}			
	});	
		
	
	$('.select-group').each(function() {
		currentValue = $(this).val();
		name = $(this).attr("name");		
		$(this).find("option").each(function(index,Element) {		
		    if($(Element).val() == currentValue){		    	
		    	$('.' + name + '-' + $(Element).val()).show();
		    }else{		    	
		    	$('.' + name + '-' + $(Element).val()).hide();
		    }
		});
	});	
	
	$('.select-group').change(function() {	   
		currentValue = $(this).val();
		name = $(this).attr("name");        		
		$(this).find("option").each(function(index,Element) {		
		    if($(Element).val() == currentValue){		          
		    	$('.' + name + '-' + $(Element).val()).show();
		    }else{
		    	$('.' + name + '-' + $(Element).val()).hide();
		    }
		});		
	});
	var value = $('#lof-type').find("option:selected").val();
	$('.lof-custom').css('display','none');
	$('.custom_'+ value ).css('display','inline');
	$('#lof-type').change(function(){
		var value = $(this).find("option:selected").val();
		$('.lof-custom').css('display','none');
		$('.custom_'+ value ).css('display','inline');
	});
	
	var group = $('input[name=group]:checked').val();
	if( group == 0){
		$('.group_no').css('display','block');
	}else{
		$('.group_no').css('display','none');
	}
	$('input[name=group]').click(function(){
		var group = $('input[name=group]:checked').val();
		if( group == 0){
			$('.group_no').css('display','block');
		}else{
			$('.group_no').css('display','none');
		}
	});
	
	var linktype = $('#linktype').val();
	$('.link_type').css('display','none');
	$('.link_type_'+linktype).css('display','block');
	$('#linktype').change(function(){
		var linktype = $(this).val();
		$('.link_type').css('display','none');
		$('.link_type_'+linktype).css('display','block');
	});
	
	$('#lofform .lof-block').mouseover(function(){
		$(this).find('.lof-delete-block').css('display','block');
	}).mouseout(function(){
		$(this).find('.lof-delete-block').css('display','none');
	});
});

function lofDelete( id ) {
	var divid = document.getElementById( id );
	divid.parentNode.removeChild( divid );
}
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
function select_innerHTML(objeto,innerHTML){
	objeto.innerHTML = ""
	var selTemp = document.createElement("micoxselect")
	var opt;
	selTemp.id="micoxselect1"
	document.body.appendChild(selTemp)
	selTemp = document.getElementById("micoxselect1")
	selTemp.style.display="none"
	if(innerHTML.toLowerCase().indexOf("<option")<0){//se não é option eu converto
		innerHTML = "<option>" + innerHTML + "</option>"
	}
	innerHTML = innerHTML.toLowerCase().replace(/<option/g,"<span").replace(/<\/option/g,"</span")
	selTemp.innerHTML = innerHTML
	
	for(var i=0;i<selTemp.childNodes.length;i++){
  var spantemp = selTemp.childNodes[i];
  
		if(spantemp.tagName){     
			opt = document.createElement("OPTION")
	
   if(document.all){ //IE
	objeto.add(opt)
   }else{
	objeto.appendChild(opt)
   }
   //getting attributes
   for(var j=0; j<spantemp.attributes.length ; j++){
	var attrName = spantemp.attributes[j].nodeName;
	var attrVal = spantemp.attributes[j].nodeValue;
	if(attrVal){
	 try{
	  opt.setAttribute(attrName,attrVal);
	  opt.setAttributeNode(spantemp.attributes[j].cloneNode(true));
	 }catch(e){}
	}
   }
   //getting styles
   /*
   if(spantemp.style){
	for(var y in spantemp.style){
	 try{opt.style[y] = spantemp.style[y];}catch(e){}
	}
   }
   */
   //value and text
   opt.value = spantemp.getAttribute("value")
   opt.text = spantemp.innerHTML
   //IE
   opt.selected = spantemp.getAttribute('selected');
   //opt.className = spantemp.className;
  }
 }
 document.body.removeChild(selTemp)
 selTemp = null
}