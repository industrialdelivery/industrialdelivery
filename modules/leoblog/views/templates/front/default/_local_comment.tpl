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

<div id="blog-localengine">
		<h3>{l s='Comments' mod='leoblog'}</h3>
		
		<div class="comments">
			{foreach from=$comments item=comment name=comment} {$default=''}
			<div class="comment-item" id="comment{$comment.id_comment|escape:'html':'UTF-8'}">
				<img src="http://www.gravatar.com/avatar/{md5(strtolower(trim($comment.email|escape:'html':'UTF-8')))}?d={urlencode($default|escape:'html':'UTF-8')}&s=60" align="left"/>
				<div class="comment-wrap">
					<div class="comment-meta">
						<span class="comment-created">{l s='Created On' mod='leoblog'}<span> {strtotime($comment.date_add)|date_format:"%A, %B %e, %Y"|escape:'html':'UTF-8'}</span></span>
						<span class="comment-postedby">{l s='Posted By' mod='leoblog'}<span> {$comment.user|escape:'html':'UTF-8'}</span></span>
						<span class="comment-link"><a href="{$blog_link|escape:'html':'UTF-8'}#comment{$comment.id_comment|intval}">{l s='Comment Link' mod='leoblog'}</a></span>
					</div>

					<div class="comment-content">
						{$comment.comment|nl2br}{* HTML form , no escape necessary *}
					</div>
				</div>
			</div>
			{/foreach}
			{if $blog_count_comment}
			<div class="top-pagination-content clearfix bottom-line">
				{include file="{$module_tpl}_pagination.tpl"}
	        </div>
	        {/if}
		</div>

		<h3>{l s='Leave your comment' mod='leoblog'}</h3>
		<form class="form-horizontal" method="post" id="comment-form" action="{$blog_link|escape:'html':'UTF-8'}" onsubmit="return false;">
			<div class="form-group">
				<label class="col-lg-3 control-label" for="inputFullName">{l s='Full Name' mod='leoblog'}</label>
				<div class="col-lg-9">
					<input type="text" name="user" placeholder="{l s='Enter your full name' mod='leoblog'}" id="inputFullName" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 control-label" for="inputEmail">{l s='Email' mod='leoblog'}</label>
				<div class="col-lg-9">
					<input type="text" name="email"  placeholder="{l  s='Enter your email' mod='leoblog'}" id="inputEmail" class="form-control">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-lg-3 control-label" for="inputComment">{l  s='Comment' mod='leoblog'}</label>
				<div class="col-lg-9">
					<textarea type="text" name="comment" rows="6"  placeholder="{l  s='Enter your comment' mod='leoblog'}" id="inputComment" class="form-control"></textarea>
				</div>
			</div>
			 <div class="form-group">
			 	<label class="col-lg-3 control-label" for="inputEmail">{l s='Captcha' mod='leoblog'}</label>
			 	<div class="col-lg-8 col-md-8 ipts-captcha">
			 		 <img src="{$captcha_image|escape:'html':'UTF-8'}" class="comment-capcha-image" align="left"/>
				 	<input class="form-control" type="text" name="captcha" value="" size="10"  />
				</div>
			 </div>
			 <input type="hidden" name="id_leoblog_blog" value="{$id_leoblog_blog|intval}">
			<div class="form-group">
				<div class="col-lg-9 col-lg-offset-3"><button class="btn btn-default" name="submitcomment" type="submit">{l s='Submit' mod='leoblog'}</button></div>
			</div>
		</form>
</div>