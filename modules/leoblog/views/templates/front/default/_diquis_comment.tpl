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

<div id="disqus_thread"></div>
<script type="text/javascript">
var disqus_shortname = '{$config->get('item_diquis_account','demo4leotheme')|escape:'html':'UTF-8'}';
var disqus_url = '{$blog_link|escape:'html':'UTF-8'}';
(function() {
	var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
	(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();
</script>
<noscript>{l s='Please enable JavaScript to view the.' mod='leoblog'} <a href="http://disqus.com/?ref_noscript">{l s='Comments powered by Disqus.' mod='leoblog'}.</a></noscript>