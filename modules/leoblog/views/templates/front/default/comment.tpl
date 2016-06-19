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
		<form class="form-horizontal" method="post" id="comment-form" action="{$blog_link|escape:'html':'UTF-8'}" onsubmit="return false;">
			
			<div class="form-group">
				<label class="col-lg-3 control-label" for="inputFullName">{l s='Full Name' mod='leoblog'}</label>
				<div class="col-lg-9">
					<input type="text" name="fullname" placeholder="{l s='Enter your full name' mod='leoblog'}" id="inputFullName" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 control-label" for="inputEmail">{l s='Email' mod='leoblog'}</label>
				<div class="col-lg-9">
					<input type="text" name="email" placeholder="{l s='Enter your email' mod='leoblog'}" id="inputEmail" class="form-control">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-lg-3 control-label" for="inputComment">{l s='Comment' mod='leoblog'}</label>
				<div class="col-lg-9">
					<textarea type="text" name="comment" rows="6" placeholder="{l s='Enter your comment' mod='leoblog'}" id="inputComment" class="form-control"></textarea>
				</div>
			</div>
			 <div class="form-group">
			 	 <img src="{$captcha_image|escape:'html':'UTF-8'}" alt="" align="left"/>
				 <input class="form-control" type="text" name="captcha" value="" size="10" />
			 </div>
			 <input type="hidden" name="id_leoblog_blog" value="{$id_leoblog_blog|intval}">
			<div class="form-group">
					<div class="col-lg-9 col-lg-offset-3"><button class="btn btn-default" name="submitcomment" type="submit">{l s='Submit' mod='leoblog'}</button></div>
			</div>
		</form>
</div>