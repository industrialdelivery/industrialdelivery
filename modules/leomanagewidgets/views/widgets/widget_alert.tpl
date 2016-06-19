{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($html)&& !empty($html)}
<div class="alert {$alert_type|escape:'html':'UTF-8'}">
	{$html}{* HTML form , no escape necessary *}
</div>
{/if}