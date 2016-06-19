<div class="toolspanel" id="toolspanel">
  <form action="{$content_dir}index.php" method="get">
    <div style="min-height: 300px; width: 200px; left: 0px;" class="pn-content " id="toolspanelcontent">
      <div class="pn-button open"></div>
      <div id="template_theme"> 
	  
	    
      	 <h5>{l s="Theme skin"}</h5>
        <select name="skin">
        {foreach from=$LEO_THEMEINFO.skins item=skin}
          <option value="{$skin}" {if $LEO_SKIN_DEFAULT==$skin}selected="selected"{/if}>{$skin}</option>
        {/foreach} 
        </select>
       
      </div>
      <div id="template_direction">
        <h5>Layout Direction</h5>
        <select name="layout">
          <option value="-lcr"{if $LEO_LAYOUT_DIRECTION == '-lcr'} selected="selected"{/if}>Content - Right</option> 
        </select>
        <div class="clearfix"></div>
      </div>
      {if isset($LEO_THEMEINFO.patterns)}
      <div id="pnpartterns">
        <h5>Pattern</h5>
		<input type="hidden" value="" name="bgpattern" id="bgpattern"/>
        {foreach from=$LEO_THEMEINFO.patterns item=p}
        	<a style="background:url('{$content_dir}themes/{$LEO_THEMENAME}/img/patterns/{$p}')" onclick="return false;" href="#" title="{$p}" id="{$p}" class="{if $LEO_PATTERN == $p}active{/if}">
                </a>
        {/foreach}
        <div class="clearfix"></div>
      </div>
      {/if}
      <div class="clearfix" id="bottombox">
        <input type="submit" name="usercustom" value="Apply" />
        <a href="{$content_dir}index.php?leoaction=reset">Reset</a> </div>
      <div class="clearfix"></div>
    </div>
  </form>
</div>
<script type="text/javascript">
	$("#toolspanelcontent").animate( {ldelim}"left": -206{rdelim} ).addClass("inactive");
	$("#toolspanel .pn-button").click(function(){ldelim} 
		if(  $("#toolspanelcontent").hasClass("inactive")  ){ldelim} 													 
			$("#toolspanelcontent").animate( {ldelim}"left": 0{rdelim} ).addClass("active").removeClass("inactive");
			$(this).removeClass("open").addClass("close");
		{rdelim}else {ldelim}
			$("#toolspanelcontent").animate( {ldelim}"left": -206{rdelim} ).addClass("inactive").removeClass("active");
			$(this).removeClass("close").addClass("open");
		{rdelim}
	{rdelim}	);
	
	$("#pn-boxed-layout").change( function(){ $('body').toggleClass("boxed-layout"); } );
	var classBody = "{$LEO_PATTERN}";
	//$("body").addClass( classBody.replace(/\.\w+$/,"")  );
	//$("#bgpattern").val( classBody.replace(/\.\w+$/,"")  );
	$("#pnpartterns a").click( function(){ldelim}   
			$("#pnpartterns a").removeClass("active");
			$(this).addClass("active");
 			  $("#leo-page").removeAttr('class');
			  $("#leo-page").addClass( $(this).attr("id").replace(/\.\w+$/,"")  );	
			$("#bgpattern").val( $(this).attr("id").replace(/\.\w+$/,"")  );
	{rdelim} );
</script>