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

{if isset($no_follow) AND $no_follow}
    {assign var='no_follow_text' value='rel="nofollow"'}
{else}
    {assign var='no_follow_text' value=''}
{/if} 
<div id="blog-listing" class="blogs-container box">
 	{if isset($filter.type)}
	 	{if $filter.type=='tag'}
			<h1>{l s='Filter Blogs By Tag' mod='leoblog'} : <span>{$filter.tag|escape:'html':'UTF-8'}</span></h1>
		{elseif $filter.type=='author'}
			<h1>{l s='Filter Blogs By Blogger' mod='leoblog'} : <span>{$filter.employee->firstname|escape:'html':'UTF-8'} {$filter.employee->lastname|escape:'html':'UTF-8'}</span></h1>
		{/if}
	{else}
	<h1>{l s='Lastest Blogs' mod='leoblog'}</h1>
	{/if}

	<div class="inner">
		{if count($leading_blogs)+count($secondary_blogs)>0}
			{if count($leading_blogs)}
			<div class="leading-blog">  
				{foreach from=$leading_blogs item=blog name=leading_blog}
				 
					{if ($blog@iteration%$listing_leading_column==1)&&$listing_leading_column>1}
					  <div class="row">
					{/if}
					<div class="{if $listing_leading_column<=1}no{/if}col-lg-{floor(12/$listing_leading_column|escape:'html':'UTF-8')}">
						 {include file="./_listing_blog.tpl"}
					</div>	
					{if ($blog@iteration%$listing_leading_column==0||$smarty.foreach.leading_blog.last)&&$listing_leading_column>1}
						</div>
					{/if}
				{/foreach}
			</div>
			{/if}


			{if count($secondary_blogs)}
			<div class="secondary-blog">

				{foreach from=$secondary_blogs item=blog name=secondary_blog}
					{if ($blog@iteration%$listing_secondary_column==1)&&$listing_secondary_column>1}
					  <div class="row">
					{/if}
					<div class="{if $listing_secondary_column<=1}no{/if}col-lg-{floor(12/$listing_secondary_column|escape:'html':'UTF-8')}">
						 {include file="./_listing_blog.tpl"}
					</div>	
					{if ($blog@iteration%$listing_secondary_column==0||$smarty.foreach.secondary_blog.last)&&$listing_secondary_column>1}
					</div>
					{/if}
				{/foreach}
			</div>
			{/if}
			<div class="top-pagination-content clearfix bottom-line">
				{include file="{$module_tpl}_pagination.tpl"}
	        </div>
	    {else}
	    	<div class="alert alert-warning">{l s='Sorry, We are update data, please come back late!!!!' mod='leoblog'}</div>
	    {/if}    

	</div>
</div>