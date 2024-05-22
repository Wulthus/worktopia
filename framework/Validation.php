<?php

namespace Framework;
class Validation {
//------------------------------------------------------------------------------STRING
    /**
     * Method that validates a string
     * 
     * @param string $string
     * @param int $minValue
     * @param int $maxValue
     * @return bool
     */
    public static function validateString($string, $minValue = 1, $maxValue = INF) {
        if (!is_string($string)) {
            return false;
        } else {
            $string = trim($string);
            $strLength = strlen($string);
            return $strLength >= $minValue && $strLength <= $maxValue;
        }
    }
//------------------------------------------------------------------------------EMAIL
    /**
     * Method do validate email adress
     * 
     * @param string $email
     * @return mixed
     */

     public static function validateEmail($email){
        $email = trim($email);
        return filter_var($email, FILTER_VALIDATE_EMAIL);
     }

//------------------------------------------------------------------------------

    /**
     * Method compares two strings, returns true if they are identical
     * 
     * @param string $leftString
     * @param string $rightString
     * @return bool
     */

     public static function compareStrings($leftString, $rightString){
        $leftString = trim($leftString);
        $rightString = trim($rightString);
        return $leftString == $rightString;
     }
}