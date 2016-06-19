<script type="text/javascript">
	ddsmoothmenu.init({
		mainmenuid: "<?php echo 'lofmegamenu'.$blockid.$pos; ?>",
		overtime:<?php echo $params->get("overtime", 300);?>, 
		outtime:<?php echo $params->get("outtime", 300);?>,
		showdelay:<?php echo $params->get("showdelay", 100);?>, 
		hidedelay:<?php echo $params->get("hidedelay", 100);?>,
		lofZindex: 200,
		orientation: '<?php echo ($pos == 'top' ? 'h' : 'v');?>',
		classname: 'ddsmoothmenu<?php echo ($pos != 'top' ? '-v' : '');?>',
		contentsource: 'markup' 
	});
	var i = 0;
	$("#<?php echo 'lofmegamenu'.$blockid.$pos; ?> ul li.lofitem0 > a").each(function() {
		var hreflink = $(this).attr("href");
		var link = hreflink.toLowerCase();
		var urllink = location.href.toLowerCase();
		if ((link == urllink || urllink.search(link) != -1) && i == 0) {
			i = 1;
			$(this).parent().addClass("active");
		}
	});
	if(i == 0){
		$("#<?php echo 'lofmegamenu'.$blockid.$pos; ?> ul li.lofitem0 > a").each(function() {
			var hreflink = $(this).attr("href");
			var link = hreflink.toLowerCase();
			var urllinkIndex = location.href.toLowerCase()+'index.php';
			if ((urllinkIndex.search(link) != -1) && i == 0) {
				i = 1;
				$(this).parent().addClass("active");
			}
		});
	}
	$("#<?php echo 'lofmegamenu'.$blockid.$pos; ?> ul").each(function(){
		$(this).find("li.lofitem0:first").addClass('itemfirst');
		$(this).find("li.lofitem1:first").addClass('itemfirst');
		$(this).find("li.lofitem2:first").addClass('itemfirst');
		$(this).find("li.lofitem3:first").addClass('itemfirst');
		
		$(this).find("li.lofitem0:last").addClass('itemlast');
		$(this).find("li.lofitem1:last").addClass('itemlast');
		$(this).find("li.lofitem2:last").addClass('itemlast');
		$(this).find("li.lofitem3:last").addClass('itemlast');
	});
	$("#<?php echo 'lofmegamenu'.$blockid.$pos; ?> div.col1 li.menugroup").each(function(){
		$(this).css({'z-index':'200'});
	});
	$("#<?php echo 'lofmegamenu'.$blockid.$pos; ?> div.col2 li.menugroup").each(function(){
		$(this).css({'z-index':'195'});
	});
	$("#<?php echo 'lofmegamenu'.$blockid.$pos; ?> div.col3 li.menugroup").each(function(){
		$(this).css({'z-index':'190'});
	});
	$("#<?php echo 'lofmegamenu'.$blockid.$pos; ?> div.col4 li.menugroup").each(function(){
		$(this).css({'z-index':'185'});
	});
	$("#<?php echo 'lofmegamenu'.$blockid.$pos; ?> div.col5 li.menugroup").each(function(){
		$(this).css({'z-index':'180'});
	});
	var zindexOld = 'z-index: 200;';
	var zindexNew = '300';
	$("li.menunongroup").mouseover(function(){
		zindexOld = $(this).attr('style');
		$(this).css('z-index',zindexNew);
	}).mouseout(function(){
		$(this).attr('style',zindexOld);
	});
</script>