<?php

//Namespaces 1. Prevent name collisions
// 2. Create Alias with shorter names for readability.
namespace Core;

class Config {
    private static $config = [
        'version'               => '0.1',
        'root_dir'              => '/cms/',        // Most likely be just a forward slash on live server. 
        'default_controller'    => 'Blog',         // Default Home Controller
        'default_layout'        => 'default',      // Default Layout
        'default_site_title'    => 'My Project',   // Default Site Title
        'db_host'               => '127.0.0.1',    // Default host. USE IP ADDRESS, then no need for a DNS Lookup
        'db_name'               => 'cms',          // Database Name
        'db_user'               => 'root',         // Database User
        'db_password'           => 'root',         // Database Password
        'login_cookie_name'     => 'jklasdf324qds' // Login Cookie Name
    ];    

    // Can't call on Array above anywhere outside of Config, because it's private.
    public static function get($key){
        // PHP Built in function. 
        // :: (Scope Resolution Operator) Allows access to static variables or methods. 
        return array_key_exists($key, self::$config) ? self::$config[$key] : NULL;
    }
}