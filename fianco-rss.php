<?php 
/*
Plugin Name: Fianco RSS Widget
Plugin URI: htt://www.fianco.net/fianco-rss
Description: Plugin to show rss feeds as widget
Version: 1.0
Author: Davi Koslosky
Author: URI: http://www.fianco.net
*/

class FiancoRSSWidget extends WP_Widget {

	function FiancoRSSWidget () {
		$widget_ops = array(
				'classname' => 'fianco_rss_widget_class',
				'description' => 'Simple Plugin to insert RSS feeds from somewhere',
		);
		$control_ops = array('width' => 400,'height' => 300);
		parent::WP_Widget(false, $name = 'Fianco RSS Widget',$widget_ops,$control_ops);
	}

	function widget ($args, $instance){
		extract($args);
		$title = apply_filters('widget_title',$instance['title']);
		$rssUri = $instance['rssUri'];
		$showDate=$instance['showDate']?$instance['showDate']:"0";
		echo $before_widget;
		if ($title){
			echo $before_title . $title. $after_title;
		}
		// Display RSS info
		if ($rssUri){
			fiancoRSSDisplay($rssUri,$showDate);
		}
		echo $after_widget;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		//Fields
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['rssUri'] = strip_tags($new_instance['rssUri']);
		$instance['showDate'] = strip_tags($new_instance['showDate']);
		return $instance;
	}

	function  form($instance) {
		if ($instance){
			$title = esc_attr($instance['title']);
			$rssUri = esc_attr($instance['rssUri']);
			$showDate = esc_attr($instance['showDate']);
		}else{
			$title = "";
			$rssUri = "";
			$showDate = "";
		}

		echo '<label for="'.$this->get_field_id('title').'"><strong>TITLE</strong></label><br \>';
		echo '<input id="'.$this->get_field_id('title').
			'"type="text" name="'.$this->get_field_name('title').
			'" value="'.$title.'" class="widefat" />';

		echo '<label for="'.$this->get_field_id('rssUri').'"><strong>RSS URI</strong></label>';
		echo '<input id="'.$this->get_field_id('rssUri').
			'"type="text" name="'.$this->get_field_name('rssUri').
			'" value="'.$rssUri.'" class="widefat" />';

		echo '<input id="'.$this->get_field_id('showDate').
			'"type="checkbox" name="'.$this->get_field_name('showDate').
			'" value="1" '.checked($showDate,'1',false).' class="widefat" />';
		echo '<label for="'.$this->get_field_id('showDate').'"><strong> Show Date</strong></label>';
	}
}
function fiancoRSSDisplay($rssUri,$showDate) {
	$doc = new DOMDocument();
	//$doc->load('http://www.itcuties.com/feed/');
	$doc->load($rssUri);
	echo "<ul>";
	foreach ($doc->getElementsByTagName('item') as $node){
		$postTitle = $node->getElementsByTagName('title')->item(0)->nodeValue;
		$postLink= $node->getElementsByTagName('link')->item(0)->nodeValue;
		$postDate = $node->getElementsByTagName('pubDate')->item(0)->nodeValue;
		echo "<li>";
		echo '<strong><a href="'.$postLink.'">'.$postTitle.'</a></strong>';
		if ($showDate=="1"){
			echo '<em>'.$postDate.'</em>';
		}
		echo "</li>";
	}
	echo "</ul>";
}

add_action('widgets_init','fiancoRSSWidgetInit');
function fiancoRSSWidgetInit(){
	register_widget('FiancoRSSWidget');
}
?>
