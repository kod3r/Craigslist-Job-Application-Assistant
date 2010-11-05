<?php
require_once(dirname(__FILE__) . '/magpierss/rss_fetch.inc');

class CraigslistFeed
{
	private $posts;

	function CraigslistFeed()
	{
		$this->posts = array();
	}
	
	public function addFeed($url)
	{
		$rss = fetch_rss($url);
		
		if (!$rss) {
			return;
		}
		
		foreach ($rss->items as $item) {
			$test = strtolower($item['title']);
			if ( strpos($test, "re:") === 0) {
				continue;
			}
			$current_post['feed_url'] = $url;
			$current_post['date'] = strtotime($item['dc']['date']);
			$current_post['link'] = $item['link'];
			$current_post['title'] = $item['title'];
			$current_post['body'] = strip_tags($item['description'], '<br><a>');
			
			array_push ($this->posts, $current_post);
		}
	}
	
	public function sortFeedsByDate()
	{
		usort( $this->posts, array('CraigslistFeed', 'comparePostsByDate') );
	}
	
	public function getPosts()
	{
		return ($this->posts);
	}
	
	private function comparePostsByDate($a, $b)
	{
		if ($a['date'] == $b['date']) {
			return 0;
		}
		if ($a['date'] < $b['date']) {
			return 1;
		}
		
		return -1;
	}
}
?>