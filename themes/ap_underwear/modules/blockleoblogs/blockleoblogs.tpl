{*
* 2007-2013 PrestaShop
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
{*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- MODULE Block blockleoblogstabs -->
{$tabname="blockleoblogs"}
<div id="blockleoblogstabs" class="blogs_block exclusive blockleoblogs products_block">
	<h4 class="page-subheading">{l s='Latest blog' mod='blockleoblogs'}</h4>
	<div class="block_content">	
		{if !empty($blogs )}
			{$tabname="blockleoblogs"}
			{if !empty($blogs)}
<div class="carousel slide" id="{$tabname}">
	 {if count($blogs)>$itemsperpage}	 
		<div class="carousel-btn">
		 	<a class="carousel-control left" href="#{$tabname}"   data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#{$tabname}"  data-slide="next">&rsaquo;</a>
		</div>
	{/if}
	<div class="carousel-inner">
	{$mblogs=array_chunk($blogs,$itemsperpage)}
	{foreach from=$mblogs item=blogs name=mypLoop}
		<div class="item {if $smarty.foreach.mypLoop.first}active{/if}">
				{foreach from=$blogs item=blog name=blogs}
				{if $blog@iteration%$columnspage==1&&$columnspage>1}
				  <div class="row">
				{/if}
				<div class="col-xs-12 col-sm-12 col-md-{$scolumn} col-lg-{$scolumn} blog_block ajax_block_blog {if $smarty.foreach.blogs.first}first_item{elseif $smarty.foreach.blogs.last}last_item{/if}">
					<div class="blog_container clearfix">
							
							{if $blog.image && $config->get('blockleo_blogs_img',1)}
							<div class="blog-image">
								<img src="{$blog.preview_url}" title="{$blog.title}" class="img-responsive" alt="{$blog.title}"/>
							</div>
							{/if}

							<div class="blog-meta text-left">
								
								{if $config->get('blockleo_blogs_cat',1)}
								<span class="blog-cat"> 
									<a href="{$blog.category_link}" title="{$blog.category_title|escape:'html':'UTF-8'}" class="btn btn-outline btn-sm">{$blog.category_title}</a>
								</span>
								{/if}
								


								{if $config->get('blockleo_blogs_cre',1)} 
								<span class="blog-created">
									{strtotime($blog.date_add)|date_format:"%B %e, %Y"}
								</span>
								{/if} 
								
								{if $config->get('blockleo_blogs_cout',1)} 
								<span class="blog-ctncomment">
									<span class="fa fa-comment"> {l s='Comment' mod='blockleoblogs'}:</span> {$blog.comment_count}
								</span>
								{/if}  
								
								{if $config->get('blockleo_blogs_aut',1)} 
								<span class="blog-author">
									<span class="fa fa-user"> {l s='Author' mod='blockleoblogs'}:</span> {$blog.author}
								</span>
								{/if}
								{if $config->get('blockleo_blogs_hits',1)} 
								<span class="blog-hits">
									<span class="fa fa-heart"> {l s='Hits' mod='blockleoblogs'}:</span> {$blog.hits}
								</span>	
								{/if}
								{if $config->get('blockleo_blogs_title',1)}
									<h5><a href="{$blog.link}" title="{$blog.title}">{$blog.title|strip_tags:'UTF-8'|truncate:30:''}</a></h5>
								{/if}
								<div class="blog-shortinfo">
									{if $config->get('blockleo_blogs_des',1)} 
									{$blog.description|strip_tags:'UTF-8'|truncate:70:''}
									{/if}  
								</div>
								<!-- <p>
									<a href="{$blog.link}" title="{$blog.title|escape:'html':'UTF-8'}" class="view">{l s='Read more' mod='blockleoblogs'}</a>
								</p> -->
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
		<div><a class="pull-right btn btn-default" href="{$view_all_link}" title="{l s='View All' mod='blockleoblogs'}">{l s='View All' mod='blockleoblogs'}</a></div>
		{/if}	
</div>
<!-- /MODULE Block blockleoblogstabs -->
<script type="text/javascript">
{literal}
$(document).ready(function() {
    $('#{/literal}{$tabname}{literal}').each(function(){
        $(this).carousel({
            pause: 'hover',
            interval: {/literal}{$interval}{literal}
        });
    });
});
{/literal}
</script>
 