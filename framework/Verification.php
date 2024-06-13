<?php

namespace Framework;
use Framework\Session;

class Verification {
    /**
     * Method to check wether current user owns a resource
     * 
     * @param int $resourceID
     * @return boolean
     */

     public static function isOwner($resourceID){
        $userID = Session::getValue('user')['id'];

        if ($userID === $resourceID && $userID !== null && isset($userID)) {
            return true;
        }
        
        return false;
     }
}