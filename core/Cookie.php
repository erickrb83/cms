<?php
namespace Core;

class Cookie{
    public static function get($name){
        if(self::exists($name)){
            return $_COOKIE[$name];
        }
        return false;
    }

    public static function set($name, $value, $expiration){
            if(setCookie($name, $value, time() + $expiration, '/')){
                return true;
            }
            return false;
    }

    public static function delete($name){
        return self::set($name, '', -1); // sets time to a second before when implemented, essentially deleting cookie. 
    }

    public static function exists($name){
        return isset($_COOKIE[$name]);
    }
}