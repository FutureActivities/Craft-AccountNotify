<?php
namespace Craft;

class AccountNotifyPlugin extends BasePlugin
{
    function getName()
    {
         return Craft::t('Account Notify');
    }
    
    function getDescription()
    {
        return Craft::t('Notifies users when their account details are updated, such as email, password, etc.');
    }

    function getVersion()
    {
        return '1.0.1';
    }

    function getDeveloper()
    {
        return 'Future Activities';
    }

    function getDeveloperUrl()
    {
        return 'https://github.com/FutureActivities';
    }
    
    function registerEmailMessages()
    {
    	return array(
    	        'user_notify'
    	);
    }
    
    function init()
    {
        craft()->on('users.onBeforeSaveUser', function(Event $event) {
            $user = $event->params['user'];
            craft()->accountNotify->check($user);
        });
    }
}
