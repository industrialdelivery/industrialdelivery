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
			<div class="carousel slide">
				<div class="carousel-inner" id="{$mytab|escape:'html':'UTF-8'}">
				{$mblogs=array_chunk($blogs,$owl_rows)}
				{foreach from=$mblogs item=blogs name=mypLoop}
					<div class="item {if $smarty.foreach.mypLoop.first}active{/if}">
							{foreach from=$blogs item=blog name=blogs}
					 
								<article class="blog-item clearfix">
										{if $config->get('blockleo_blogs_title',1)}
										<h4 class="title"><a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}">{$blog.title|escape:'html':'UTF-8'}</a></h4>
										{/if}	
										<div class="meta">
											{if $config->get('blockleo_blogs_cat',1)}
											<span class="category"> <span class="icon-list">{l s='In' mod='blockleoblogs'}</span> 
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
											<span class="nbcomment">
												<span class="icon-comment"> {l s='Comment' mod='blockleoblogs'}:</span> {$blog.comment_count|intval}
											</span>
											{/if}  

											{if $config->get('blockleo_blogs_aut',1)} 
											<span class="author">
												<span class="icon-author"> {l s='Author' mod='blockleoblogs'}:</span> {$blog.author|escape:'html':'UTF-8'}
											</span>
											{/if}
											{if $config->get('blockleo_blogs_hits',1)} 
											<span class="hits">
												<span class="icon-hits"> {l s='Hits' mod='blockleoblogs'}:</span> {$blog.hits|intval}
											</span>	
											{/if}
										</div>
										{if $blog.image && $config->get('blockleo_blogs_img',1)}
										<div class="image">
											{if $owl_lazyLoad}
												<img data-src="{$blog.preview_url|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" class="img-responsive lazyOwl" alt="{$blog.title|escape:'html':'UTF-8'}" />
											{else}
												<img src="{$blog.preview_url|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" class="img-responsive" alt="{$blog.title|escape:'html':'UTF-8'}" />
											{/if}
										</div>
										{/if}

										<div class="shortinfo">
											{if $config->get('blockleo_blogs_des',1)} 
											{$blog.description|strip_tags:'UTF-8'|truncate:200:'...'}{* HTML form , no escape necessary *}
											{/if}  
											<p>
												<a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" class="more btn btn-default">{l s='Read more' mod='blockleoblogs'}</a>
											</p>
										</div>
									</article> 
 

							{/foreach}
					</div>		
				{/foreach}
				</div>
			</div>
		{/if}
	</div>
		{if $config->get('blockleo_blogs_show',1)}
		<div><a class="pull-right" href="{$view_all_link|escape:'html':'UTF-8'}" title="{l s='View All' mod='blockleoblogs'}">{l s='View All' mod='blockleoblogs'}</a></div>
		{/if}	
</div>
<!-- /MODULE Block blockleoblogstabs -->
