<?php

/**
 * class to send message to Twitter. 
 */
class PostToTwitter extends Controller {
	
	private static $twitter_consumer_key;
	
	private static $twitter_consumer_secret;
	
	private static $twitter_access_token;
	
	private static $twitter_access_token_secret;
	
	public static function set_twitter_consumer_key($key) {
		self::$twitter_consumer_key = $key;
	}
	
	protected static function get_twitter_consumer_key() {
		return self::$twitter_consumer_key;
	}
	
	public static function set_twitter_consumer_secret($key) {
		self::$twitter_consumer_secret = $key;
	}
	
	protected static function get_twitter_consumer_secret() {
		return self::$twitter_consumer_secret;
	}
	
	public static function set_twitter_access_token($key) {
		self::$twitter_access_token = $key;
	}
	
	protected static function get_twitter_access_token() {
		return self::$twitter_access_token;
	}
	
	public static function set_twitter_access_token_secret($key) {
		self::$twitter_access_token_secret = $key;
	}
	
	protected static function get_twitter_access_token_secret() {
		return self::$twitter_access_token_secret;
	}

	public function __construct($twitterAccount = null) {
		if($twitterAccount) {
			self::set_twitter_consumer_key($twitterAccount->ConsumerKey);
			self::set_twitter_consumer_secret($twitterAccount->ConsumerSecret);
			self::set_twitter_access_token($twitterAccount->AccessToken);
			self::set_twitter_access_token_secret($twitterAccount->TokenSecret);
		}
    }
	
	static function ready_to_tweet() {
		return ((self::$twitter_consumer_key && self::$twitter_consumer_secret && self::$twitter_access_token && self::$twitter_access_token_secret) || (TwitterAccount::twitter_accounts_set()));
	}	
	
	function postStatus($message = null){

		if(!self::ready_to_tweet() && $message) {
			return false;
		}

		// create instance
		$twitter = new Twitter(self::$twitter_consumer_key, self::$twitter_consumer_secret);

		// set tokens
		$twitter->setOAuthToken(self::$twitter_access_token);
		$twitter->setOAuthTokenSecret(self::$twitter_access_token_secret);

		// make sure message is 140 chars and send message
		$message = substr(strip_tags($message),0,140);
		$response = $twitter->statusesUpdate($message);
		return $response;
	}
}

?>