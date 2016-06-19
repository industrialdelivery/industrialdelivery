<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#lofjcarousel").flexisel({
			visibleItems: <?php echo $limit; ?>,
			animationSpeed: <?php echo $animate_time; ?>,
			autoPlay: <?php echo $auto_play; ?>,
			autoPlaySpeed: <?php echo $auto_time; ?>,
			pauseOnHover: <?php echo $pause_on_hover; ?>,
			enableResponsiveBreakpoints: <?php echo $enable_responsive; ?>,
	    	responsiveBreakpoints: { 
	    		portrait: { 
	    			changePoint:<?php echo $portraint_change_point; ?>,
	    			visibleItems: <?php echo $portraint_visible_items; ?>
	    		},
	    		landscape: { 
	    			changePoint:<?php echo $landscape_change_point; ?>,
	    			visibleItems: <?php echo $landscape_visible_items; ?>
	    		},
	    		tablet: {
	    			changePoint:<?php echo $tablet_change_point; ?>,
	    			visibleItems: <?php echo $tablet_visible_items; ?>
	    		}
	    	}
	    });
	});
</script>