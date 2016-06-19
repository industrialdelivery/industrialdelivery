<?php
/**
 * Leo Slideshow Module
 * 
 * @version		$Id: file.php $Revision
 * @package		modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) September 2012 LeoTheme.Com <@emai:leotheme@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
 */
 
/**
 * @since 1.5.0
 * @version 1.2 (2012-03-14)
 */

if (!defined('_PS_VERSION_'))
	exit;
		
		$this->_html .= '<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">';
		/* Save */
		$this->_html .= '
		<div class="margin-form">
			<input type="submit" class="button" name="submitUpdate" value="'.$this->l('Save').'" />
		</div>';
		$this->_html .= '
				<fieldset><legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Authentication settings (compulsory)').'</legend>';
				
		$this->_html .= $params->inputTag( $this->l('Consumer key:'), 'con_key', $this->getParams()->get('con_key'), '<p>'.$this->l('Consumer key for your app at').'<a href="https://dev.twitter.com/apps/new" target="_blank"> https://dev.twitter.com/apps/new</a></p>',' size="50" ' );
		$this->_html .= $params->inputTag( $this->l('Consumer secret:'), 'con_secret', $this->getParams()->get('con_secret'), '<p>'.$this->l('Consumer secret for your app at').'<a href="https://dev.twitter.com/apps/new" target="_blank"> https://dev.twitter.com/apps/new</a></p>',' size="50" ' );
		$this->_html .= $params->inputTag( $this->l('Access token:'), 'access_token', $this->getParams()->get('access_token'), '<p>'.$this->l('Access token for your app at').'<a href="https://dev.twitter.com/apps/new" target="_blank"> https://dev.twitter.com/apps/new</a></p>',' size="50" ' );
		$this->_html .= $params->inputTag( $this->l('Access token secret:'), 'access_token_secret', $this->getParams()->get('access_token_secret'), '<p>'.$this->l('Access token secret for your app at').'<a href="https://dev.twitter.com/apps/new" target="_blank"> https://dev.twitter.com/apps/new</a></p>',' size="50" ' );
		
		$this->_html .= '</fieldset>';
		$this->_html .= '<br><br>
				<fieldset><legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Widget settings').'</legend>';
		$types = array( 'timeline' => $this->l('Timeline'), 'search' => $this->l('Search'));
		$this->_html .= $params->selectTag( $types, $this->l('Widget type:'), 'widget_type', $this->getParams()->get('widget_type'), '<p>'.$this->l('Choose Timeline to display tweets of a specific user. Choose Search to get results about some query').'</p>' );
		$this->_html .= $params->inputTag( $this->l('Username:'), 'username', $this->getParams()->get('username'), '<p>'.$this->l('Twitter username for which you want to display tweets if widget type is set to Timeline').'</p>',' size="50" ' );
		$this->_html .= $params->inputTag( $this->l('Search query:'), 'search_query', $this->getParams()->get('search_query'), '<p>'.$this->l('Query to be searched if widget type is set to Search').'</p>',' size="50" ' );
		$this->_html .= $params->inputTag( $this->l('Search title:'), 'search_title', $this->getParams()->get('search_title'), '<p>'.$this->l('When set to yes, title will be link to the search query on Twitte').'</p>',' size="50" ' );
		$this->_html .= $params->statusTag( $this->l('Link search title:'), 'link_search', $this->getParams()->get('link_search'), 'link_search', '<p>'.$this->l('When set to yes, title will be link to the search query on Twitte').'</p>' );
		$this->_html .= $params->inputTag( $this->l('Tweet number:'), 'tweet_number', $this->getParams()->get('tweet_number'), '<p>'.$this->l('Module width. Set to empty to use width of the parent container').'</p>',' size="50" ' );
		$this->_html .= '</fieldset>';
		$this->_html .= '<br><br>
				<fieldset><legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Module appearance ').'</legend>';
		
		$this->_html .= $params->inputTag( $this->l('Width:'), 'width', $this->getParams()->get('width'), '<p>'.$this->l('Module width. Set to empty to use width of the parent container').'</p>',' size="50" ' );
		$this->_html .= $params->inputTag( $this->l('Height:'), 'height', $this->getParams()->get('height'), '<p>'.$this->l('Module height. If the height is smaller than the space required for tweets to fit, scrollbar will be displayed. Set to empty to never have the scrollbar and use full height').'</p>',' size="50" ' );
		$this->_html .= $params->statusTag( $this->l('Show header:'), 'show_header', $this->getParams()->get('show_header'), 'show_header', '<p>'.$this->l('Show header on top of tweets. For timeline, this will be name, username and avatar, and for search it will be the search title').'</p>' );
		$this->_html .= $params->statusTag( $this->l('Show twitter icon:'), 'twitter_icon', $this->getParams()->get('twitter_icon'), 'twitter_icon', '<p>'.$this->l('Set to yes to display small twitter icon int the header').'</p>' );
		$this->_html .= '</fieldset>';
		$this->_html .= '<br><br>
				<fieldset><legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Color options').'</legend>';
		
		$this->_html .= $params->inputTagColor( $this->l('Background color:'), 'bg_color', $this->getParams()->get('bg_color'), '<p>'.$this->l('Module background color. Default is white').'</p>',' size="50" ' );
		$this->_html .= $params->inputTagColor( $this->l('Link color:'), 'link_color', $this->getParams()->get('link_color'), '<p>'.$this->l('Link color. Default is variation of blue').'</p>',' size="50" ' );
		$this->_html .= $params->inputTagColor( $this->l('Border color:'), 'border_color', $this->getParams()->get('border_color'), '<p>'.$this->l('Border color, default is light gray').'</p>',' size="50" ' );
		$this->_html .= $params->inputTagColor( $this->l('Text color:'), 'text_color', $this->getParams()->get('text_color'), '<p>'.$this->l('Text color, default is dark gray').'</p>',' size="50" ' );
		$this->_html .= $params->inputTagColor( $this->l('Header name color:'), 'hname_color', $this->getParams()->get('hname_color'), '<p>'.$this->l('Link color for the Twitter name when widget type is set to Timeline').'</p>',' size="50" ' );
		$this->_html .= $params->inputTagColor( $this->l('Header username color:'), 'husername_color', $this->getParams()->get('husername_color'), '<p>'.$this->l('Link color for the Twitter username when widget type is set to Timeline').'</p>',' size="50" ' );
		$this->_html .= $params->inputTagColor( $this->l('Header username on hover color:'), 'husername_hcolor', $this->getParams()->get('husername_hcolor'), '<p>'.$this->l('Link color for the Twitter username on mouse hover when widget type is set to Timeline').'</p>',' size="50" ' );
		$this->_html .= $params->inputTagColor( $this->l('Search title color:'), 'search_color', $this->getParams()->get('search_color'), '<p>'.$this->l('Link color for the Search title when widget type is set to Search').'</p>',' size="50" ' );
		$this->_html .= '</fieldset>';
		$this->_html .= '<br><br>
				<fieldset><legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Tweet appearance ').'</legend>';
		
		$this->_html .= $params->statusTag( $this->l('Display username:'), 'display_name', $this->getParams()->get('display_name'), 'display_name', '<p>'.$this->l('Should the twitter username be displayed?').'</p>' );
		$this->_html .= $params->statusTag( $this->l('Display avatars:'), 'display_avatars', $this->getParams()->get('display_avatars'), 'display_avatars', '<p>'.$this->l('Should avatars be displayed?').'</p>' );
		$this->_html .= $params->statusTag( $this->l('Display timestamps:'), 'display_time', $this->getParams()->get('display_time'), 'display_time', '<p>'.$this->l('Should timestamps be shown?').'</p>' );
		$this->_html .= $params->statusTag( $this->l('Reply link:'), 'reply_link', $this->getParams()->get('reply_link'), 'reply_link', '<p>'.$this->l('Should reply link be shown?').'</p>' );
		$this->_html .= $params->statusTag( $this->l('Retweet link:'), 'retweet_link', $this->getParams()->get('retweet_link'), 'retweet_link', '<p>'.$this->l('Should retweet link be shown?').'</p>' );
		$this->_html .= $params->statusTag( $this->l('Favorite link:'), 'ravorite_link', $this->getParams()->get('ravorite_link'), 'ravorite_link', '<p>'.$this->l('Should favorite link be shown?').'</p>' );
		$this->_html .= '</fieldset>';
        $this->_html .= '<br><br>
                        <fieldset><legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Cache Manager').'</legend>';

        $this->_html .= $params->statusTag( $this->l('Use Cache:'), 'use_cache', $this->getParams()->get('use_cache'), 'use_cache' );
        $this->_html .= $params->inputTag( $this->l('Cache Time:'), 'cache_time', $this->getParams()->get('cache_time'), '',' size="50" ' );

        $this->_html .= '</fieldset>';
		
	$this->_html .= '<br><br>';
	/* Save */
		$this->_html .= '
		<div class="margin-form">
			<input type="submit" class="button" name="submitUpdate" value="'.$this->l('Save').'" />
		</div>';
		$this->_html .= '</form><br /><br />';

	
?>