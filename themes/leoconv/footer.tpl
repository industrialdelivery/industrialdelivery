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
*  @version  Release: $Revision: 6594 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
	{if !$content_only}
							</div><!-- 5-->
						</div><!-- 4-->
						
						{if $page_name != "index"}
						<div id="leo-rightcol" class="span3">
							{$HOOK_RIGHT_COLUMN}
						</div><!--righttcol-->
						{/if}	
					</div><!-- End Of Fluid-Width -->
				</div><!-- 4-->
			</div>
			</div>

			<div id="leo-manufac" class="leo-manufac">
			
				<div class="row-fluid">
					<div class="container">
						{Hook::exec('bottomManufacturer')}
					</div>
				</div>
			</div>
		<!-- Footer -->	
   		<div id="leo-footer" class=" leo-footer">
			<div class="footer-wrapper">
			</div>
			<div class="container">	
				<div class="row-fluid"  >
							{$HOOK_FOOTER}	
				</div>
			</div>  <!--footer-->	
			
		</div>
	{/if}
		{if $LEO_PANELTOOL}
		   {include file="$tpl_dir./info/paneltool.tpl"}
		{/if}
	</div>	<!--leopage-->
	</body>
</html>