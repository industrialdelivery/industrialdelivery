{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<nav id="cavas_menu" class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">{l s='Toggle navigation' mod='leobootstrapmenu'}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div id="leo-top-menu" class="collapse navbar-collapse navbar-ex1-collapse">
            {$boostrapmenu}
        </div>
</nav>
<script type="text/javascript">{literal}
// <![CDATA[
    var current_link = "{/literal}{$current_link}{literal}";
    //alert(request);
    var currentURL = window.location;
    currentURL = String(currentURL);
    currentURL = currentURL.replace("https://","").replace("http://","").replace("www.","").replace( /#\w*/, "" );
    current_link = current_link.replace("https://","").replace("http://","").replace("www.","");
    isHomeMenu = 0;
    if($("body").attr("id")=="index") isHomeMenu = 1;
    $(".megamenu > li > a").each(function() {
        menuURL = $(this).attr("href").replace("https://","").replace("http://","").replace("www.","").replace( /#\w*/, "" );
        if( (currentURL == menuURL) || (currentURL.replace(current_link,"") == menuURL) || isHomeMenu){
            $(this).parent().addClass("active");
            return false;
        }
    });
// ]]>
{/literal}</script>
{if $show_cavas && $show_cavas == 1}
<script type="text/javascript">{literal}
    (function($) {
        $.fn.OffCavasmenu = function(opts) {
            // default configuration
            var config = $.extend({}, {
                opt1: null,
                text_warning_select: "{/literal}{l s='Please select One to remove?' mod='leobootstrapmenu'}",{literal}
                text_confirm_remove: "{/literal}{l s='Are you sure to remove footer row?' mod='leobootstrapmenu'}",{literal}
                JSON: null
            }, opts);
            // main function
            // initialize every element
            this.each(function() {
                var $btn = $('#cavas_menu .navbar-toggle');
                var $nav = null;
                if (!$btn.length)
                    return;
                var $nav = $('<section id="off-canvas-nav"><nav class="offcanvas-mainnav" ><div id="off-canvas-button"><span class="off-canvas-nav"></span>{/literal}{l s='Close' mod='leobootstrapmenu'}{literal}</div></nav></section>');
                var $menucontent = $($btn.data('target')).find('.megamenu').clone();
                $("body").append($nav);
                $("#off-canvas-nav .offcanvas-mainnav").append($menucontent);
                $("html").addClass ("off-canvas");
                $("#off-canvas-button").click( function(){
                        $btn.click();   
                } );
                $btn.toggle(function() {
                    $("body").removeClass("off-canvas-inactive").addClass("off-canvas-active");
                }, function() {
                    $("body").removeClass("off-canvas-active").addClass("off-canvas-inactive");
                });
            });
            return this;
        }
    })(jQuery);
    $(document).ready(function() {
        jQuery("#cavas_menu").OffCavasmenu();
        $('#cavas_menu .navbar-toggle').click(function() {
            $('body,html').animate({
                scrollTop: 0
            }, 0);
            return false;
        });
        
        $(window).resize(function() {
            if( $(window).width() > 767 ){
                $("body").removeClass("off-canvas-active").addClass("off-canvas-inactive");
            }
        });
    });
    $(document.body).on('click', '[data-toggle="dropdown"]' ,function(){
        if(!$(this).parent().hasClass('open') && this.href && this.href != '#'){
            window.location.href = this.href;
        }
    });
{/literal}</script>
{else}
<script type="text/javascript">{literal}
        $(document).ready(function() {
            $('#leo-top-menu .dropdown-toggle').removeAttr("disabled");
            $(".dropdown-toggle").click(function() {
                if($(window).width() <= 767){
                    if($(this).parent("li").find("div:first").hasClass("level2"))
                        return false;
                    else
                        return true;
                }
            });
        });
{/literal}</script>
{/if}