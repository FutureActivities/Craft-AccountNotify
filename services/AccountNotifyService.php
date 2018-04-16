<?php
namespace Craft;

class AccountNotifyService extends BaseApplicationComponent
{
    /**
     * Check if a user account has had changes
     */
    public function check($user)
    {
        // Get the user before these changes
        $userRecord = UserRecord::model()->findById($user->id);
        $original = UserModel::populateModel($userRecord);
        
        if (!$original)
            return;
        
        // Exclude certain fields
        $excludes = ['admin'];
        if ($useEmailAsUsername = craft()->config->get('useEmailAsUsername'))
            $excludes[] = 'username';
        
        // Get list of fields that have changed
        $diff = $this->diff($original, $user, $excludes);
        
        if (empty($diff))
            return;
        
        // Send the email
        craft()->email->sendEmailByKey($original, 'user_notify', ['diff' => $diff]);
    }
    
    /**
     * Gets the differences between the original user object and the new one
     */
    protected function diff($original, $user, $excludes = [])
    {
        $result = [];
        
        foreach ($user AS $key => $value) {
            if (in_array($key, $excludes)) continue;
            
            if ($value != $original->$key) {
                $result[$key] = [
                    'was' => $original->$key,
                    'now' => $value
                ];
            }
        }
        
        return $result;
    }
}