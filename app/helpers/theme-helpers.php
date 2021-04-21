<?php
// check the darklight cookie seting & set some values. a THEME function only related to frontend design
function darklight_cookie() {
	
	$dark_light_def = '';
	
	if(isset($_COOKIE['darklightswitch'])) {
		$dark_light_def = array(
			'body_class' => 'uk-light',
			'sun_link_show_hide' => '',
			'moon_link_show_hide' => 'hidden',
		);
	} elseif (!isset($_COOKIE['darklightswitch'])) {
		$dark_light_def = array(
			'body_class' => '',
			'sun_link_show_hide' => 'hidden',
			'moon_link_show_hide' => '',
		);    
	}
	
	return $dark_light_def;
	
}