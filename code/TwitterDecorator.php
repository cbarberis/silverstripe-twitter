<?php

class TwitterDecorator extends DataObjectDecorator {
	
	function extraStatics() {
		return array(
			"db" => array(
				"LastPostedToTwitter" => "Varchar(255)",
				"LastTweet" => "Varchar(255)"
			)
		);
	}
	
	function updateCMSFields(&$fields) {
		$tabName = ($this->owner instanceof SiteTree) ? 'Root.Content.SocialMedia' : 'Root.SocialMedia';
		$fields->addFieldToTab($tabName, new HeaderField('TwitterHeader', 'Twitter', 4));
		if(!PostToTwitter::ready_to_tweet())
			$fields->addFieldToTab($tabName, new LiteralField('NotGoodToTweet', '<p>ATTENTION: This will NOT make it to Twitter, you need to set your public and private keys, please see module documentation or <a href="http://dev.twitter.com/pages/auth" target="_blank">http://dev.twitter.com/pages/auth</a></p>'));
		$fields->addFieldToTab($tabName, new CheckboxField('PostToTwitter', 'Post to Twitter'));
		$fields->addFieldToTab($tabName, new ReadonlyField('LastPostedToTwitter', 'Last Posted To Twitter'));
		$fields->addFieldToTab($tabName, new ReadonlyField('LastTweet','Last Tweet Content'));
		
	}
	
	public $PostToTwitter = false;
	
	function setPostToTwitter($value) { $this->PostToTwitter = $value; }
	
	public static $twitterField = array();
	
	function getTwitterField() {
		foreach(self::$twitterField as $k => $v) {
			if($this->owner instanceof $k) {
				if(is_array($v)) {
					$message = '';
					foreach($v as $value) {
						if($this->owner->$value) $message .= $this->owner->$value . " ";
						elseif(method_exists($this->owner,$value)) $message .= $this->owner->$value() . " ";
					}
					return $message;
				}
				else return $this->owner->$v;
				break;
			}
		}
	}
	
	function set_twitter_fields($fields = array()) {
		self::$twitterField = $fields;
	}
	
	
	function onBeforeWrite(){
		if($this->getTwitterField() && $this->PostToTwitter) {
			$this->owner->LastPostedToTwitter = date('d/m/Y g:ia');
			$this->owner->LastTweet = $this->getTwitterField();
		}
	}
	
	function onAfterWrite(){
		if($this->getTwitterField() && $this->PostToTwitter) {
			$message = $this->getTwitterField();
			$twitter = new PostToTwitter();
			$resp = $twitter->postToTwitter($message);
		}
	}
}