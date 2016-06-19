<?php

	require_once dirname(__FILE__).'/../libs/twitteroauth/twitteroauth.php';
	require_once dirname(__FILE__).'/../libs/twitter-text/Autolink.php';
	require_once dirname(__FILE__).'/../libs/twitter-text/Extractor.php';
	require_once dirname(__FILE__).'/../libs/twitter-text/HitHighlighter.php';

	class LeoTwitterHelper
	{
		private $data;
		private $cacheFile;
        private $name;
        private $params;
		public function __construct($name, $params) {
            $this->name = $name;
            $this->cacheFile = _PS_CACHEFS_DIRECTORY_.$this->name;
            if( !file_exists(_PS_CACHEFS_DIRECTORY_) ) {
                mkdir( _PS_CACHEFS_DIRECTORY_, 0777 );
            }
            if( !file_exists($this->cacheFile) ) {
                mkdir( $this->cacheFile, 0777 );
            }
            $filename = md5($params->get('con_key').'-'.$params->get('con_secret').'-'.$params->get('access_token').'-'.$params->get('access_token_secret').'-'.$params->get('widget_type').'-'.$params->get('username').'-'.$params->get('search_query').'-'.$params->get('search_title').'-'.$params->get('link_search').'-'.$params->get('tweet_number')).'.txt';
            $this->cacheFile .= '/'.$filename;
            $this->params = $params;
		}

		public function getData() {
            $params = $this->params;
            $minute = (int)$params->get('cache_time',90);
            if (!$params->get('use_cache') || !file_exists($this->cacheFile) || (filemtime($this->cacheFile) <= (time() - 60 * $minute ))) {
                $twitterConnection = new TwitterOAuth(
                    trim($params->get('con_key', '')), // Consumer Key
                    trim($params->get('con_secret', '')), // Consumer secret
                    trim($params->get('access_token', '')), // Access token
                    trim($params->get('access_token_secret', ''))	// Access token secret
                );

                if($params->get('widget_type') == 'timeline') {
                    $twitterData = $twitterConnection->get(
                        'statuses/user_timeline',
                        array(
                            'screen_name' => trim($params->get('username', 'leotheme')),
                            'count' => trim($params->get('tweet_number', 5)),
                        )
                    );
                }else {
                    $twitterData = $twitterConnection->get(
                        'search/tweets',
                        array(
                            'q' => trim($params->get('search_query', '')),
                            'count' => trim($params->get('tweet_number', 5)),
                        )
                    );
                    if(!isset($twitterData->errors))
                        $twitterData = $twitterData->statuses;
                }
                // if there are no errors
                if(!isset($twitterData->errors)) {
                    $tweets = array();
                    foreach($twitterData as $tweet) {
                        $tweetDetails = new stdClass();
                        $tweetDetails->text = Twitter_Autolink::create($tweet->text)->setNoFollow(false)->addLinks();
                        $tweetDetails->time = $this->getTime($tweet->created_at);
                        $tweetDetails->id = $tweet->id_str;
                        $tweetDetails->screenName = $tweet->user->screen_name;
                        $tweetDetails->displayName = $tweet->user->name;
                        $tweetDetails->profileImage = $tweet->user->profile_image_url_https;

                        $tweets[] = $tweetDetails;
                    }
                    $data = new stdClass();
                    $data->tweets = $tweets;

                    $this->data = $data;
                    if($params->get('use_cache'))
                        $this->setCache();
                    return $data;
                }else {
                    return '';
                }
			}else {
                return $this->getCache();
            }

		}
		
		public function addStyles() {
            $params = $this->params;
			$styles = '';
			$border = $params->get('border_color', '#ccc');
			$styles .= '#itw-tweets a {color: ' . $params->get('link_color', '#0084B4') . '}';
			$styles .= '#itw-container {background-color: ' . $params->get('bg_color', '#fff') . '}';
			$styles .= '#itw-header {border-bottom-color: ' . $border . '}';
			$styles .= '#itw-container {border-color: ' . $border . '}';
			$styles .= '.itw-copyright {border-top-color: ' . $border . '}';
			$styles .= '.itw-tweet-container {border-bottom-color: ' . $border . '}';
			$styles .= '#itw {color: ' . $params->get('text_color', '#333') . '}';
			$styles .= 'a .itw-display-name {color: ' . $params->get('hname_color', '#333') . '}';
			$styles .= 'a .itw-screen-name {color: ' . $params->get('husername_color', '#666') . '}';
			$styles .= 'a:hover .itw-screen-name {color: ' . $params->get('husername_hcolor', '#999') . '}';
			$styles .= '#itw-header, #itw-header a {color: ' . $params->get('search_color', '#333') . '}';
			if($params->get('width', '') && Validate::isUnsignedInt($params->get('width'))) {
				$styles .= '#itw-container {width: ' . intval($params->get('width', '')) . 'px;}';
			}
			if($params->get('height', '') && Validate::isUnsignedInt($params->get('height'))) {
				$styles .= '#itw {height: ' . intval($params->get('height', '')) . 'px; overflow: auto;}';
			}
			return $styles;
		}

		private function setCache() {
            $fp = fopen($this->cacheFile, 'w');
            fwrite($fp, serialize($this->data));
            fclose($fp);
		}

		private function getCache() {
			if(file_exists($this->cacheFile)) {
				$cache = Tools::file_get_contents($this->cacheFile);
				if($cache !== false)
					return unserialize($cache);
			}
			return false;
		}

		// parse time in a twitter style
		private function getTime($date)
		{
			$timediff = time() - strtotime($date);
			if($timediff < 60)
				return $timediff . 's';
			else if($timediff < 3600)
				return intval(date('i', $timediff)) . 'm';
			else if($timediff < 86400)
				return round($timediff/60/60) . 'h';
			else
				return date('M d',strtotime($date));
		}
	}
