<?php

class TwitterExtension extends DataExtension {
	

	static $db = array(
		"LastPostedToTwitter" => "Varchar(255)",
		"LastTweet" => "Varchar(255)"
	);

	static $has_one = array(
		'Account' => 'TwitterAccount'
	);


	
	function updateCMSFields(FieldList $fields) {
		$tabName = 'Root.SocialMedia';
		$fields->addFieldToTab($tabName, new HeaderField('TwitterHeader', 'Twitter', 4));
		// if(TwitterAccount::twitter_accounts_set()) {
		// 	$twitterAccounts = DataObject::get('TwitterAccount','', '"AccountName" ASC');
		// 	$fields->addFieldToTab($tabName, new DropdownField('twitterAccount', 'Twitter Account', $twitterAccounts->map('ID', 'AccountName')));	
		// }
		if(!PostToTwitter::ready_to_tweet())
			$fields->addFieldToTab($tabName, new LiteralField('NotGoodToTweet', '<p>ATTENTION: This will NOT make it to Twitter, you need to set your public and private keys, please see module documentation or <a href="http://dev.twitter.com/pages/auth" target="_blank">http://dev.twitter.com/pages/auth</a></p>'));
		$fields->addFieldToTab($tabName, DropdownField::create('AccountID', 'Twitter Account', TwitterAccount::get()->map('ID', 'AccountName'))->setEmptyString(' '));
		//'GalleryID', 'Gallery', Gallery::get()->map('ID', 'Title')
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
	
	static function set_twitter_fields($fields = array()) {
		self::$twitterField = $fields;
	}
	
	
	function onBeforeWrite(){
		parent::onBeforeWrite();
		
		if($this->getTwitterField() && $this->PostToTwitter) {
			$this->owner->LastPostedToTwitter = date('d/m/Y g:ia');
			$this->owner->LastTweet = $this->getTwitterField();
		}
	}
	
	function onAfterWrite(){
		parent::onAfterWrite();
		if($this->getTwitterField() && $this->PostToTwitter && $this->owner->AccountID) {
			$message = $this->getTwitterField();
			$twitter = new PostToTwitter($this->owner->Account());
			$resp = $twitter->postStatus($message);
		}
	}
}