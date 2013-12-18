# Twitter Module

## Maintainer Contact

* Carlos Barberis
  <carlos (at) cbarberis (dot) com>
	
## Requirements

* Silverstripe 3.1.x

## Module Status

Still under active development.

## Overview

This module lets you post messages to Twitter from the CMS. 

You need to apply TwitterDecorator to the DataObject you want to use to generate and post statues to Twitter.You can apply this decorator to SiteTrees or DataObjects.

The decorator adds a checkbox used to confirm you want to post to Twitter (this preference is not stored in the DB), 
and a text field to store the last time you posted to Twitter.

There are a few ways to use the decorator. Add this in your mysite/_config.php

* 1. Post content from one field in your DataObject:

DataObject::add_extension('MyDO','TwitterExtension'); 
TwitterExtension::set_twitter_fields(array('MyDO' => 'Title'));

* 2. Post content from multiple fields in Pages: 

DataObject::add_extension('Page','TwitterExtension'); 
TwitterExtension::set_twitter_fields(array('Page' => array('Title','OtherField')));

* 3. You can create a method in your class to generate content to post (this will post what MyFunction returns, make sure this is an string!).

DataObject::add_extension('MyClass','TwitterExtension'); 
TwitterExtension::set_twitter_fields(array('MyClass' => array('MyFunction')));

# Installation

1. Simply add the module to the top level of your SilverStripe installation and
perform a dev/build.

2. You need a Twitter account to post to, login to Twitter and get (http://dev.twitter.com/pages/auth):
* Register an App
* Consumer Key
* Consumer Secret Key
* Access Token
* Access Secret Token
(access token needs read/write permission)

3. Add this to your mysite/_config.php OR see point 4

PostToTwitter::set_twitter_consumer_secret('1234');
PostToTwitter::set_twitter_access_token('1234');
PostToTwitter::set_twitter_consumer_key('1234');
PostToTwitter::set_twitter_access_token_secret('1234');

4. I have added a new DO called 'TwitterAccount', you can use this to add multiple Twitter account and then choose one of them when posting a new status.
 To add a new Twitter account go to the 'Social media' tab


