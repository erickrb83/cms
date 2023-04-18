<?php

namespace Core;

use Core\Session;
use App\Models\Users;

class Router {

    public static function route($url){
        // This find which controller will be used (BlogController, etc.)
        $urlParts = explode('/', $url);

        // Set Controller
        $controller = !empty($urlParts[0]) ? $urlParts[0] : Config::get('default_controller');
        $controllerName = $controller;
        $controller = '\App\Controllers\\' .ucwords($controller). 'Controller';

        // Set Action
        array_shift($urlParts);
        $action = !empty($urlParts[0])? $urlParts[0] : 'index';
        $actionName = $action;
        $action .= "Action";
        array_shift($urlParts);
        
        if(!class_exists($controller)){
            $controller = Config::get('default_controller');
            $controllerName = $controller;
            $controller = '\App\Controllers\\' .ucwords($controller). 'Controller';
            array_shift($urlParts);
            $action = "fourOhFour";
            $actionName = $action;
            $action .= "Action";
            array_shift($urlParts); 
            // throw new \Exception("Controller class \"{$controller}\" not found");
        }
        $controllerClass = new $controller($controllerName, $actionName);
        
        if(!method_exists($controllerClass, $action)){
            throw new \Exception("The method \"{$action}\" does not exist on the \"{$controller}\" controller");
        }
        // php library function
        call_user_func_array([$controllerClass, $action], $urlParts);
    }

    public static function redirect($location){
        if(!headers_sent()){
            // PHP way
            header('Location: ' . ROOT . $location);
        } else {
            //JavaScript way
            echo '<script>; type="text/javascript">';
            echo 'window.location.href = "' . ROOT . $location . ' " ';
            echo '</script>';
            //If Javascript is disabled. 
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . ROOT . $location . ' " ';
            echo '</noscript>';
        }
        exit();
    }

    public static function permRedirect($perm, $redirect, $msg = 'You do not have access to this page '){
        $user = Users::getCurrentUser();
        $allowed = $user && $user->hasPermission($perm);
        if(!$allowed){
            Session::msg($msg);
            self::redirect($redirect);
        }
    }
}