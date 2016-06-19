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
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Block permanent links module HEADER -->
<section class="header-box blockpermanentlinks-header">
    <ul id="header_links">
        <li><a href="{$link->getPageLink('index.php')}" class="header_links_home">{l s='home' mod='blockpermanentlinks'}</a></li>
        <li><a  href="{$link->getPageLink('stores')}" class="header_links_store">{l s='Our stores'}</a></li>
        <li ><a href="{$link->getCMSLink('4', 'About Us')}" class="header_links_about">{l s='About Us'}</a></li>
        <li ><a href="{$link->getCMSLink('1', 'Delivery')}" class="header_links_delivery">{l s='Delivery'}</a></li>
        <li><a href="{$link->getPageLink('contact', true)}" class="header_links_contact"  title="{l s='Contact us' mod='blockpermanentlinks'}">{l s='Contact us' mod='blockpermanentlinks'}</a></li>

        
    </ul>

    <div class="mobile-link-top">
        <h4>
             <span class="title-hed"></span><span class="arrow_header_top_menu"></span>
        </h4>
        <ul id="mobilelink" class="list_header">
            <li><a href="{$link->getPageLink('index.php')}" class="header_links_home">{l s='home' mod='blockpermanentlinks'}</a></li>
        <li><a  href="{$link->getPageLink('stores')}" class="header_links_store">{l s='Our stores'}</a></li>
               <li ><a href="{$link->getCMSLink('4', 'About Us')}" class="header_links_about">{l s='About Us'}</a></li>
    	    <li ><a href="{$link->getCMSLink('1', 'Delivery')}" class="header_links_delivery">Delivery</a></li>
        <li><a href="{$link->getPageLink('contact', true)}" class="header_links_contact"  title="{l s='Contact us' mod='blockpermanentlinks'}">{l s='Contact us' mod='blockpermanentlinks'}</a></li>

        </ul>
    </div>
</section>
<!-- /Block permanent links module HEADER -->


