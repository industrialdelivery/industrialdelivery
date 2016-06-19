{if $call_owl_carousel}
<script>
$(document).ready(function() {
    $("{$call_owl_carousel}").owlCarousel({
            items : {if $owl_items}{$owl_items|intval}{else}false{/if},
            {if $owl_itemsDesktop}itemsDesktop : [1199,{$owl_itemsDesktop|intval}],{/if}
            {if $owl_itemsDesktop}itemsDesktopSmall : [979,{$owl_itemsDesktopSmall|intval}],{/if}
            {if $owl_itemsTablet}itemsTablet : [768,{$owl_itemsTablet|intval}],{/if}
            {if $owl_itemsTabletSmall}itemsTabletSmall : [640,{$owl_itemsTabletSmall|intval}],{/if}
            {if $owl_itemsMobile}itemsMobile : [479,{$owl_itemsMobile|intval}],{/if}
            {if $owl_itemsCustom}itemsCustom : {$owl_itemsCustom},{/if}
            singleItem : false,         // chỉ hiện thị 1 item
            itemsScaleUp : false,       // true sẽ hiện thị giãn ảnh nếu diện tích còn thừa, và ẩn nếu độ rộng ko đủ
            slideSpeed : {if $owl_slideSpeed}{$owl_slideSpeed|intval}{else}200{/if},  // tốc độ trôi khi thả chuột, kéo 1 nửa rồi thả ra
            paginationSpeed : {if $owl_paginationSpeed}{$owl_paginationSpeed}{else}800{/if}, // tốc độ next page
            rewindSpeed : {if $owl_rewindSpeed}{$owl_rewindSpeed}{else}1000{/if}, // tốc độ tua lại về first item
            autoPlay : {if $owl_autoPlay}{$owl_autoPlay|intval}{else}false{/if},   // thời gian show each item
            stopOnHover : {if $owl_stopOnHover}true{else}false{/if},
            navigation : {if $owl_navigation}true{else}false{/if},
            navigationText : ["&lsaquo;", "&rsaquo;"],
            rewindNav : {if $owl_rewindNav}true{else}false{/if}, // enable, disable tua lại về first item
            scrollPerPage : {if $owl_scrollPerPage}true{else}false{/if},
            
            pagination : {if $owl_pagination}true{else}false{/if}, // show bullist : nut tròn tròn
            paginationNumbers : {if $owl_paginationNumbers}true{else}false{/if}, // đổi nut tròn tròn thành số
            
            responsive : {if $owl_responsive}true{else}false{/if},
            //responsiveRefreshRate : 200,
            //responsiveBaseWidth : window,
            
            //baseClass : "owl-carousel",
            //theme : "owl-theme",
            
            lazyLoad : {if $owl_lazyLoad}true{else}false{/if},
            lazyFollow : {if $owl_lazyFollow}true{else}false{/if},  // TRUE : nhảy vào page 7 load ảnh TỪ page 1->7. FALSE : nhảy vào page 7 CHỈ load ảnh page 7
            lazyEffect : {if $owl_lazyEffect}"fade"{else}false{/if},
            
            autoHeight : {if $owl_autoHeight}true{else}false{/if},

            //jsonPath : false,
            //jsonSuccess : false,

            //dragBeforeAnimFinish
            mouseDrag : {if $owl_mouseDrag}true{else}false{/if},
            touchDrag : {if $owl_touchDrag}true{else}false{/if},
            
            addClassActive : true,
            //transitionStyle : "owl_transitionStyle",
            
            //beforeUpdate : false,
            //afterUpdate : false,
            //beforeInit : false,
            //afterInit : false,
            //beforeMove : false,
            //afterMove : false,
            //afterAction : false,
            //startDragging : false,
            //afterLazyLoad: false
    

        });
    });
</script>
{/if}
