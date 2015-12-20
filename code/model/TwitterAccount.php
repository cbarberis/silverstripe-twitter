<?php

class TwitterAccount extends DataObject
{

    public static $db = array(
        'AccountName' => 'Varchar(255)',
        'ConsumerSecret' => 'Varchar(255)',
        'AccessToken' => 'Varchar(255)',
        'ConsumerKey' => 'Varchar(255)',
        'TokenSecret' => 'Varchar(255)'
    );

    public static function twitter_accounts_set()
    {
        return DataObject::get('TwitterAccount')->Count();
    }

    public static $summary_fields = array(
        'AccountName'
    );
}
