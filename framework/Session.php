<?php

namespace Framework;

class Session {

    public static $successKey = 'success_message';
    public static $errorKey = 'error_message';

    /**
     * Method to start new session
     * 
     * @return void
     */
    public static function startSession(){
        if (session_status() !== PHP_SESSION_NONE) return;

        session_start();
    }
    /**
     * Method to add values to the session
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */

     public static function setValue($key, $value){
        $_SESSION[$key] = $value;
     }
     
     /**
      * Method to retrieve values from a session. If no values is found, function will return specified default value. Default value returns false by default
      * 
      * @param string $key
      * @param mixed $default
      * @return mixed
      */

      public static function getValue($key, $defaultValue = false){
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
      }

      /**
       * Method to check weather a session value exists
       * 
       * @param string $key
       * @return boolean
       */

       public static function hasValue($key){
            return isset($_SESSION[$key]);
       }

       /**
        * Method to remove valeu from a session
        *
        * @param string $key
        * @return void
        */

        public static function clearValue($key){
            if (Session::hasValue($key) === false) return;
            unset($_SESSION[$key]);
        }
        
        /**
         * Method to remove ALL session data
         * 
         * @return void
         */

         public static function dropSession(){
            session_unset();
            session_destroy();
         }

         /**
          * Method to set a flash message
          * 
          * @param string $message
          * @param string $key
          * @return void
          */

          public static function setMessage($message, $key){
            self::setValue($key, $message);
          }

          /**
           * Method retrieves flash message and insets it afterwards
           * 
           * @param string $key
           * @param mixed default
           * @return string
           */

           public static function getMessage($key, $default = null){
            $message = self::getValue($key, $default);
            self::clearValue($key);
            return $message;
           }






}