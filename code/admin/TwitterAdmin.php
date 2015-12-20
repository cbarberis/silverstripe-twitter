<?php

class TwitterAdmin extends ModelAdmin
{

    public static $url_segment = 'social-media';

    public static $menu_title = 'Social Media';

    public static $managed_models = array(
        'TwitterAccount'
    );
}
