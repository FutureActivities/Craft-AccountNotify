<?php
namespace Craft;

class AccountNotifyService extends BaseApplicationComponent
{
    public function check($user)
    {
        // Get the user before these changes
        $userRecord = UserRecord::model()->findById($user->id);
        $original = UserModel::populateModel($userRecord);
        
        // Exclude certain fields
        $excludes = ['admin'];
        if ($useEmailAsUsername = craft()->config->get('useEmailAsUsername'))
            $excludes[] = 'username';
        
        // Get list of fields that have changed
        $diff = $this->diff($original, $user, $excludes);
        
        // Send the email
        craft()->email->sendEmailByKey($original, 'user_notify', ['diff' => $diff]);
    }
    
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
    
    protected function send($to, $subject, $body)
    {
        $email = new EmailModel();
        $email->toEmail = $to;
        $email->subject = $subject;
        $email->body = $body;
        
        try {
            craft()->email->sendEmail($email);
        } catch (\Exception $e) {
            return false;
        }
    }
}