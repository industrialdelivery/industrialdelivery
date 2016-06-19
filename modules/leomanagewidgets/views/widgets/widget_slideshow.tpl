{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if $slides}  
{assign var="t_image" value="image_`$iso_code`"}
{assign var="t_thumb" value="thum_`$iso_code`"}
{assign var="t_title" value="title_`$iso_code`"}
{assign var="t_link" value="link_`$iso_code`"}
{assign var="t_description" value="description_`$iso_code`"}

    <!-- main slider carousel -->
    <div class="row">
        <div class="col-md-12" id="slider">
                <div id="carousel-example-generic">
                    <div id="myCarousel" class="carousel slide">
            <ol class="carousel-indicators">
              {foreach $slides as $slide name=item}
              <li data-target="#carousel-example-generic" data-slide-to="{$smarty.foreach.item.index|escape:'html':'UTF-8'}" {if $smarty.foreach.item.first}class="active"{/if}></li>
              {/foreach}  
            </ol>
                        <!-- main slider carousel items -->
                        <div class="carousel-inner">
              {foreach $slides as $slide name=slides}
                <div class="item {if $smarty.foreach.slides.first}active{/if} " data-slide-number="{$smarty.foreach.slides.index|escape:'html':'UTF-8'}">
                  {if  isset($slide[$t_image]) && $slide[$t_image]}
                    {if  isset($slide[$t_link]) && $slide[$t_link]}
                    <a href="{$slide[$t_link]|escape:'html':'UTF-8'}" title="{$slide[$t_title]|escape:'html':'UTF-8'}">
                    {/if}
                    <img src="{$pathimg|escape:'html':'UTF-8'}{$slide[$t_image]|escape:'html':'UTF-8'}" alt="" style="width:{$img_width|intval}px;height:{$img_height|intval}px" class="img-responsive">
                    {if  isset($slide[$t_link]) && $slide[$t_link]}
                    </a>
                    {/if}
                  {/if} 
                    <div class="carousel-caption">
                    {if  isset($slide[$t_title]) && $slide[$t_title]}
                      <h3>
                        {if  isset($slide[$t_link]) && $slide[$t_link]}
                        <a href="{$slide[$t_link]|escape:'html':'UTF-8'}" title="{$slide[$t_title]|escape:'html':'UTF-8'}">
                        {/if}
                          {$slide[$t_title]|escape:'html':'UTF-8'}
                        {if  isset($slide[$t_link]) && $slide[$t_link]}
                        </a>
                        {/if}
                      </h3>
                    {/if}
                    {if  isset($slide[$t_description]) && $slide[$t_description]}<p>{$slide[$t_description]}{* HTML form , no escape necessary *}</p>{/if}   
                    </div>
                </div>
              {/foreach}
                        </div>
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
              <span class="fa fa-angle-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
              <span class="fa fa-angle-right"></span>
            </a>
                    </div>
                </div>
          <!-- Controls -->

        </div>
   
    <!--/main slider carousel-->
</div>

{/if}  
<script type="text/javascript">
{literal}
$('#myCarousel').carousel({
    interval: {/literal}{$interval|intval}{literal}
});

// handles the carousel thumbnails
$('[id^=carousel-selector-]').click( function(){
  var id_selector = $(this).attr("id");
  var id = id_selector.substr(id_selector.length -1);
  id = parseInt(id);
  $('#myCarousel').carousel(id);
  $('[id^=carousel-selector-]').removeClass('selected');
  $(this).addClass('selected');
});

// when the carousel slides, auto update
$('#myCarousel').on('slid', function (e) {
  var id = $('.item.active').data('slide-number');
  id = parseInt(id);
  $('[id^=carousel-selector-]').removeClass('selected');
  $('[id^=carousel-selector-'+id+']').addClass('selected');
});
{/literal}
</script>