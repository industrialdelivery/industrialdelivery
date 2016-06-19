{*
 *  Leo Prestashop SliderShow for Prestashop 1.6.x
 *
 * @package   leosliderlayer
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<!-- MODULE Block blockleoblogstabs -->
<div class="block blogs_block exclusive blockleoblogs">
	<h3>{l s='Latest Blogs' mod='blockleoblogs'}</h3>
	<div class="block_content">	
		{if !empty($blogs )}
			{if !empty($blogs)}
<div class="carousel slide" id="{$tab|escape:'html':'UTF-8'}">
	 {if count($blogs)>$itemsperpage}	 
		
	 	<a class="carousel-control left" href="#{$tab|escape:'html':'UTF-8'}"   data-slide="prev">&lsaquo;</a>
		<a class="carousel-control right" href="#{$tab|escape:'html':'UTF-8'}"  data-slide="next">&rsaquo;</a>
	{/if}
	<div class="carousel-inner">
	{$mblogs=array_chunk($blogs,$itemsperpage)}
	{foreach from=$mblogs item=blogs name=mypLoop}
		<div class="item {if $smarty.foreach.mypLoop.first}active{/if}">
				{foreach from=$blogs item=blog name=blogs}
				{if $blog@iteration%$columnspage==1&&$columnspage>1}
				  <div class="row">
				{/if}
				<div class="{if $columnspage|intval>1} col-sp-12 col-xs-6 col-sm-6 col-md-{$scolumn|intval} col-lg-{$scolumn|intval} {/if} blog_block ajax_block_blog {if $smarty.foreach.blogs.first}first_item{elseif $smarty.foreach.blogs.last}last_item{/if}">
					<div class="blog-item blog_container clearfix">
							{if $config->get('blockleo_blogs_title',1)}
							<h4 class="title"><a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}">{$blog.title|escape:'html':'UTF-8'}</a></h4>
							{/if}	
							<div class="blog-meta">								 
								{if $config->get('blockleo_blogs_cat',1)}
								<span class="blog-cat"> <span class="fa fa-list">{l s='In' mod='blockleoblogs'}</span>
									<a href="{$blog.category_link|escape:'html':'UTF-8'}" title="{$blog.category_title|escape:'html':'UTF-8'}">{$blog.category_title|escape:'html':'UTF-8'}</a>
								</span>
								{/if}
								
								{if $config->get('blockleo_blogs_cre',1)} 
								<span class="blog-created"><span class=""></span>
									<span class="fa fa-calendar"> {l s='On' mod='blockleoblogs'} </span> 
									<time class="date" datetime="{strtotime($blog.date_add)|date_format:"%Y"|escape:'html':'UTF-8'}">
										{l s=strtotime($blog.date_add)|date_format:"%A"|escape:'html':'UTF-8' mod='blockleoblogs'},	<!-- day of week -->
										{l s=strtotime($blog.date_add)|date_format:"%B"|escape:'html':'UTF-8' mod='blockleoblogs'}		<!-- month-->
										{l s=strtotime($blog.date_add)|date_format:"%e"|escape:'html':'UTF-8' mod='blockleoblogs'},	<!-- day of month -->
										{l s=strtotime($blog.date_add)|date_format:"%Y"|escape:'html':'UTF-8' mod='blockleoblogs'}		<!-- year -->
									</time>
								</span>
								{/if} 
								
								{if $config->get('blockleo_blogs_cout',1)} 
								<span class="blog-ctncomment">
									<span class="fa fa-comments-o"> {l s='Comment' mod='blockleoblogs'}:</span> {$blog.comment_count|intval}
								</span>
								{/if}  
								
								{if $config->get('blockleo_blogs_aut',1)} 
								<span class="blog-author">
									<span class="fa fa-user"> {l s='Author' mod='blockleoblogs'}:</span> {$blog.author|escape:'html':'UTF-8'}
								</span>
								{/if}
								{if $config->get('blockleo_blogs_hits',1)} 
								<span class="blog-hits">
									<span class="fa fa-heart"> {l s='Hits' mod='blockleoblogs'}:</span> {$blog.hits|intval}
								</span>	
								{/if}
							</div>
							{if $blog.image && $config->get('blockleo_blogs_img',1)}
							<div class="blog-image">
								<img src="{$blog.preview_url|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" class="img-responsive" alt="{$blog.title|escape:'html':'UTF-8'}" />
							</div>
							{/if}

							<div class="blog-shortinfo">
								{if $config->get('blockleo_blogs_des',1)} 
								{$blog.description|strip_tags:'UTF-8'|truncate:200:'...'}{* HTML form , no escape necessary *}
								{/if}  
								<p>
									<a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" class="more btn btn-default">{l s='Read more' mod='blockleoblogs'}</a>
								</p>
						 
							</div>
						</div>
				</div>
				
				{if ($blog@iteration%$columnspage==0||$smarty.foreach.blogs.last)&&$columnspage>1}
				</div>
				{/if}
					
				{/foreach}
		</div>		
	{/foreach}
	</div>
</div>
{/if}
		{/if}
	</div>
		{if $config->get('blockleo_blogs_show',1)}
		<div><a class="pull-right" href="{$view_all_link|escape:'html':'UTF-8'}" title="{l s='View All' mod='blockleoblogs'}">{l s='View All' mod='blockleoblogs'}</a></div>
		{/if}	
</div>
<!-- /MODULE Block blockleoblogstabs -->
<script type="text/javascript">
{literal}
$(document).ready(function() {
    $('#{/literal}{$tab|escape:'html':'UTF-8'}{literal}').each(function(){
        $(this).carousel({
            pause: 'hover',
            interval: {/literal}{$interval}{literal}
        });
    });
});
{/literal}
</script>
 
<!---
	Translation Day of Week - NOT REMOVE
	{l s='Sunday' mod='blockleoblogs'}
	{l s='Monday' mod='blockleoblogs'}
	{l s='Tuesday' mod='blockleoblogs'}
	{l s='Wednesday' mod='blockleoblogs'}
	{l s='Thursday' mod='blockleoblogs'}
	{l s='Friday' mod='blockleoblogs'}
	{l s='Saturday' mod='blockleoblogs'}
-->
<!---
	Translation Month - NOT REMOVE
		{l s='January' mod='blockleoblogs'}
		{l s='February' mod='blockleoblogs'}
		{l s='March' mod='blockleoblogs'}
		{l s='April' mod='blockleoblogs'}
		{l s='May' mod='blockleoblogs'}
		{l s='June' mod='blockleoblogs'}
		{l s='July' mod='blockleoblogs'}
		{l s='August' mod='blockleoblogs'}
		{l s='September' mod='blockleoblogs'}
		{l s='October' mod='blockleoblogs'}
		{l s='November' mod='blockleoblogs'}
		{l s='December' mod='blockleoblogs'}
-->