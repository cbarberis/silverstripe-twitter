<?php

class TwitterAdmin extends ModelAdmin {

	static $url_segment = 'social-media';

	static $menu_title = 'Social Media';

	static $managed_models = array(
		'TwitterAccount'
	);
}