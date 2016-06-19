<?php
/**
 *  Leo Prestashop Blockleoblogs for Prestashop 1.6.x
 *
 * @package   blockleoblogs
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

define('_LEO_BLOG_PREFIX_', 'LEOBLG_');
require_once(_PS_MODULE_DIR_.'leoblog/classes/config.php');

$config = LeoBlogConfig::getInstance();


define('_LEOBLOG_BLOG_IMG_DIR_', _PS_MODULE_DIR_.'leoblog/views/img/');
define('_LEOBLOG_BLOG_IMG_URI_', __PS_BASE_URI__.'modules/leoblog/views/img/');


define('_LEOBLOG_CATEGORY_IMG_URI_', _PS_MODULE_DIR_.'leoblog/views/img/');
define('_LEOBLOG_CATEGORY_IMG_DIR_', __PS_BASE_URI__.'modules/leoblog/views/img/');

define('_LEOBLOG_CACHE_IMG_DIR_', _PS_IMG_DIR_.'leoblog/');
define('_LEOBLOG_CACHE_IMG_URI_', _PS_IMG_.'leoblog/');

$link_rewrite = 'link_rewrite'.'_'.Context::getContext()->language->id;
define('_LEO_BLOG_REWRITE_ROUTE_', $config->get($link_rewrite, 'blog'));

if (!is_dir(_LEOBLOG_BLOG_IMG_DIR_.'c'))
{
	# validate module
	mkdir(_LEOBLOG_BLOG_IMG_DIR_.'c', 0777, true);
}

if (!is_dir(_LEOBLOG_BLOG_IMG_DIR_.'b'))
{
	# validate module
	mkdir(_LEOBLOG_BLOG_IMG_DIR_.'b', 0777, true);
}

if (!is_dir(_LEOBLOG_CACHE_IMG_DIR_))
{
	# validate module
	mkdir(_LEOBLOG_CACHE_IMG_DIR_, 0777, true);
}
if (!is_dir(_LEOBLOG_CACHE_IMG_DIR_.'c'))
{
	# validate module
	mkdir(_LEOBLOG_CACHE_IMG_DIR_.'c', 0777, true);
}
if (!is_dir(_LEOBLOG_CACHE_IMG_DIR_.'b'))
{
	# validate module
	mkdir(_LEOBLOG_CACHE_IMG_DIR_.'b', 0777, true);
}

require_once(_PS_MODULE_DIR_.'leoblog/classes/helper.php');
require_once(_PS_MODULE_DIR_.'leoblog/classes/leoblogcat.php');
require_once(_PS_MODULE_DIR_.'leoblog/classes/blog.php');
require_once(_PS_MODULE_DIR_.'leoblog/classes/link.php');
require_once(_PS_MODULE_DIR_.'leoblog/classes/comment.php');
require_once(_PS_MODULE_DIR_.'leoblog/classes/LeoblogOwlCarousel.php');