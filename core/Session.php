<?php
namespace Core;
use Core\Request;

class Session {
    public static function exists($name){
        return isset($_SESSION[$name]);
    }

    public static function set($name, $value){
        $_SESSION[$name] = $value;
    }

    public static function get($name){
        if(self::exists($name) && !empty($_SESSION[$name])){
            return $_SESSION[$name];
        }
        return false;
    }

    public static function delete($name){
        unset($_SESSION[$name]);    
    }

    public static function createCsrfToken(){
        $token = md5('csrf' . time());
        self::set('csrfToken', $token);
        return $token;
    }

    public static function csrfCheck(){
        $request = new Request();
        $check = $request->get('csrfToken');
        if(self::exists('csrfToken') && self::get('csrfToken') == $check){
            return true;
        }
        Router::redirect('auth/badToken');
    }

    // $type can be primary, secondary, success, danger, warning, info, light, and dark
    public static function msg($msg, $type ='danger'){
        $alerts = self::get('session_alerts') ? self::get('session_alerts') : [];
        $alerts[$type][] = $msg;
        self::set('session_alerts', $alerts);
    }

    public static function displaySessionAlert(){
        $alerts = self::exists('session_alerts') ? self::get('session_alerts') : [];
        $html ='';
        foreach($alerts as $type => $msgs){
            foreach($msgs as $msg){
                $html .= "<div class='alert alert-success alert-dismissible alert-{$type}'>
                {$msg}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'></span></button>
                </div>";
            }
        }
        self::delete('session_alerts');
        return $html;
    }
}
