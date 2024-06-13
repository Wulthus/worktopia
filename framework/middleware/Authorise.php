<?php

namespace Framework\Middleware;
use Framework\Session;

class Authorise {
    /**
     * Function to check weather user is authenticated
     * 
     * @return boolean
     */

     public function hasClearence(){
        return Session::hasValue('user');
     }


    /**
     * Function to handle user type
     * 
     * @param string $role
     * @return boolean
     */
    public function handle($userRole){
        match (true) {
            $userRole === "guest" && $this->hasClearence() => redirect("/"),
            $userRole === "auth" && !$this->hasClearence() => redirect("/login"),
            default => null,
        };
    }
}