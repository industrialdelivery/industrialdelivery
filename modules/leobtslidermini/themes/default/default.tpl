<div class="span4">
<div id="leobttslidermini{$leobtslidermini_modid}" class="carousel slide leobttslidermini block_box">
	<div class="carousel-inner">
		{foreach from=$leobtslidermini_slides item=slidemini name=slidenamemini}
			<div class="item{if $smarty.foreach.slidenamemini.index == 0} active{/if}">
				{if $slidemini.url}
					<a href="{$slidemini.url}"><img src="{$slidemini.mainimage}" alt="{$slidemini.title}" /></a>
				{else}
					<img src="{$slidemini.mainimage}" alt="{$slidemini.title}" />
				{/if}
				{if $slidemini.title  || $slidemini.description}
					<div class="slide-info">
						<h1>{$slidemini.title}</h1>
						<div class="desc">{$slidemini.description}</div>
					</div>
				{/if}
			</div>
		{/foreach}
	</div>
	{if count($leobtslidermini_slides) > 1}
	<a class="carousel-control left" href="#leobttslidermini{$leobtslidermini_modid}" data-slide="prev">&lsaquo;</a>
	<a class="carousel-control right" href="#leobttslidermini{$leobtslidermini_modid}" data-slide="next">&rsaquo;</a>
	{/if}

	{if count($leobtslidermini_slides) > 1}
		{if $leobtslidermini.image_navigator}
			<ol class="carousel-indicators thumb-indicators hidden-phone">
			{foreach from=$leobtslidermini_slides item=item name=itemname}
				<li data-target="#leobttslidermini{$leobtslidermini_modid}" data-slide-to="{$smarty.foreach.itemname.index}" class="{if $smarty.foreach.itemname.index == 0}active{/if}">
					<img src="{$item.thumbnail}"/>
				</li>
			{/foreach}
			</ol> 
		{/if}
	{/if}
	</div>
</div>
{if $leobtslidermini.auto}
<script type="text/javascript">
	{literal}
	jQuery(document).ready(function(){
		$('#leobttslidermini{/literal}{$leobtslidermini_modid}{literal}').carousel({
		  interval: {/literal}{$leobtslidermini.delay}{literal}
		});
	});
	{/literal}
</script>
{/if}