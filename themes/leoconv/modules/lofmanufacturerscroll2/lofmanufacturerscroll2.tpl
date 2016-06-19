<div id="mycarouselHolder" align="center" class="block">
	<div class="row-fluid">
		{if $show_title}
			<div class="span3">
				<h2>{$module_title}</h2>
			</div>
		{/if}		
		<div class="{if $show_title}span9{/if} jcarousel-wrap">		
			<div id="wrap">
			  <ul id="lofjcarousel" class="jcarousel-skin-tango">
				{foreach from=$lofmanufacturers item=manufacturer name=manufacturers}
					<li class="lof-item">
						<a href="{$manufacturer.link}">
							<img src="{$manufacturer.linkIMG}" alt="{$manufacturer.name}" vspace="0" border="0" />
							
						</a>
					</li>
				{/foreach}
			  </ul>
			</div>
		</div>
	</div>
</div>