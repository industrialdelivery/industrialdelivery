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

include_once(_PS_MODULE_DIR_.'leoblog/loader.php');

class LeobloglistModuleFrontController extends ModuleFrontController
{
	public $php_self;
	protected $template_path = '';

	public function __construct()
	{
		parent::__construct();
		$this->context = Context::getContext();
		$this->template_path = _PS_MODULE_DIR_.'leoblog/views/templates/front/';
	}

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		$config = LeoBlogConfig::getInstance();
		$authors = array();

		/* Load Css and JS File */
		LeoBlogHelper::loadMedia($this->context, $this);

		parent::initContent();

		$helper = LeoBlogHelper::getInstance();

		$limit_leading_blogs = (int)$config->get('listing_leading_limit_items', 1);
		$limit_secondary_blogs = (int)$config->get('listing_secondary_limit_items', 6);
		//$latest_limit_items = (int)$config->get( 'latest_limit_items', 20 );
		$author = (int)Tools::getValue('author');
		$tag = trim(Tools::getValue('tag'));
		$n = (int)$limit_leading_blogs + (int)$limit_secondary_blogs;
		$p = abs((int)(Tools::getValue('p', 1)));
		$template = $config->get('template', 'default');
		$this->template_path .= $template.'/';

		$condition = array();

		if ($author)
		{
			$condition = array(
				'type' => 'author',
				'id_employee' => $author,
				'employee' => new Employee($author)
			);
			$r = $helper->getPaginationLink('module-leoblog-list', 'list', array('author' => $author));
		}
		if ($tag)
		{
			$condition = array(
				'type' => 'tag',
				'tag' => urldecode($tag)
			);
			$r = $helper->getPaginationLink('module-leoblog-list', 'list', array('tag' => $tag));
		}

		$blogs = LeoBlogBlog::getListBlogs(null, $this->context->language->id, $p, $n, 'id_leoblog_blog', 'DESC', $condition, true);
		$count = LeoBlogBlog::countBlogs(null, $this->context->language->id, $condition, true);

		$leading_blogs = array();
		$secondary_blogs = array();
//		$links 	   		 =  array();

		if (count($blogs))
		{
			$leading_blogs = array_slice($blogs, 0, $limit_leading_blogs);
			$secondary_blogs = array_splice($blogs, $limit_leading_blogs, count($blogs));
		}
		$image_w = (int)$config->get('listing_leading_img_width', 690);
		$image_h = (int)$config->get('listing_leading_img_height', 300);

		foreach ($leading_blogs as $key => $blog)
		{
			$blog = LeoBlogHelper::buildBlog($helper, $blog, $image_w, $image_h, $config);
			if ($blog['id_employee'])
			{
				if (!isset($authors[$blog['id_employee']]))
				{
					# validate module
					$authors[$blog['id_employee']] = new Employee($blog['id_employee']);
				}

				$blog['author'] = $authors[$blog['id_employee']]->firstname.' '.$authors[$blog['id_employee']]->lastname;
				$blog['author_link'] = $helper->getBlogAuthorLink($authors[$blog['id_employee']]->id);
			}
			else
			{
				$blog['author'] = '';
				$blog['author_link'] = '';
			}

			$leading_blogs[$key] = $blog;
		}

		$image_w = (int)$config->get('listing_secondary_img_width', 390);
		$image_h = (int)$config->get('listing_secondary_img_height', 200);

		foreach ($secondary_blogs as $key => $blog)
		{
			$blog = LeoBlogHelper::buildBlog($helper, $blog, $image_w, $image_h, $config);
			if ($blog['id_employee'])
			{
				if (!isset($authors[$blog['id_employee']]))
				{
					# validate module
					$authors[$blog['id_employee']] = new Employee($blog['id_employee']);
				}

				$blog['author'] = $authors[$blog['id_employee']]->firstname.' '.$authors[$blog['id_employee']]->lastname;
				$blog['author_link'] = $helper->getBlogAuthorLink($authors[$blog['id_employee']]->id);
			}
			else
			{
				$blog['author'] = '';
				$blog['author_link'] = '';
			}
			$secondary_blogs[$key] = $blog;
		}

		$module_tpl = $this->template_path;

		//$nbBlogs = $count > $latest_limit_items?$latest_limit_items:$count;
		$nb_blogs = $count;
		$range = 2; /* how many pages around page selected */
		if ($p > (($nb_blogs / $n) + 1))
			Tools::redirect(preg_replace('/[&?]p=\d+/', '', $_SERVER['REQUEST_URI']));
		$pages_nb = ceil($nb_blogs / (int)($n));
		$start = (int)($p - $range);
		if ($start < 1)
			$start = 1;
		$stop = (int)($p + $range);
		if ($stop > $pages_nb)
			$stop = (int)($pages_nb);

		if (!isset($r))
			$r = $helper->getPaginationLink('module-leoblog-list', 'list', array(), false, true);

		$module_tpl = $this->template_path;

		/* breadcrumb */
		$path = '<a href="'.$helper->getFontBlogLink().'">'.htmlentities($config->get('blog_link_title_'.$this->context->language->id, 'Blog'), ENT_NOQUOTES, 'UTF-8').'</a>';
		$this->context->smarty->assign(array(
			'leading_blogs' => $leading_blogs,
			'secondary_blogs' => $secondary_blogs,
			'listing_leading_column' => $config->get('listing_leading_column', 1),
			'listing_secondary_column' => $config->get('listing_secondary_column', 3),
			'filter' => $condition,
			'meta_title' => $config->get('meta_title', 'Blog').' - '.$this->context->shop->name,
			'meta_description' => $config->get('meta_description'),
			'meta_keywords' => $config->get('meta_keywords'),
			'module_tpl' => $module_tpl,
			'nb_items' => $count,
			'range' => $range,
			'path' => $path,
			'start' => $start,
			'stop' => $stop,
			'pages_nb' => $pages_nb,
			'config' => $config,
			'p' => (int)$p,
			'n' => (int)$n,
			'meta_title' => $config->get('meta_title_'.Context::getContext()->language->id).' - '.$this->context->shop->name,
			'meta_keywords' => $config->get('meta_keywords_'.Context::getContext()->language->id),
			'meta_description' => $config->get('meta_description_'.Context::getContext()->language->id),
			'requestPage' => $r['requestUrl'],
			'requestNb' => $r,
			'controller' => 'latest'
		));

		$this->setTemplate($template.'/listing.tpl');
	}

}
?>