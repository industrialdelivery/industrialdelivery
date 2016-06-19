{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if !isset($content_only) || !$content_only}
								
						</section>
						{if isset($right_column_size) && !empty($right_column_size)}
						<!-- Right -->
						<section id="right_column" class="column sidebar col-md-{$right_column_size|intval}">
								{$HOOK_RIGHT_COLUMN}
						</section>
						{/if}


                	</div>
				</div>
            </section>
			<!-- Footer -->
			<footer id="footer" class="footer-container">       
				<div class="container"> 
					<div class="inner">	
						{$HOOK_FOOTER}							
					</div> 
				</div>
            </footer>
		</section><!-- #page -->
{/if}
<span id="backtop" class="fa fa-angle-double-up"></span>
{include file="$tpl_dir./global.tpl"}
{if isset($LEO_PANELTOOL) && $LEO_PANELTOOL}
    {include file="$tpl_dir./info/paneltool.tpl"}
{/if}
	</body>
</html>