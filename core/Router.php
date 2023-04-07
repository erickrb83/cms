<?php

namespace Core;

use App\Controllers\BlogController;

class Router {

    public static function route($url){
        // This find which controller will be used (BlogController, etc.)
        $urlParts = explode('/', $url);

        // Set Controller
        /**Below would be longer
         * = if(!empty($urlParts)){
         * return $urlParts[0];
         * }else{
         * return Config::get('default_controller)};
         */
        $controller = !empty($urlParts[0])? $urlParts[0]: Config::get('default_controller');
        $controllerName = $controller;
        $controller = '\App\Controllers\\' .ucwords($controller). 'Controller';

        // Set Action
        array_shift($urlParts);
        $action = !empty($urlParts[0])? $urlParts[0] : 'index';
        $actionName = $action;
        // $var .= 'word' is the same $var = $var . 'word';
        $action .= "Action";
        array_shift($urlParts);
        
        if(!class_exists($controller)){
            throw new \Exception("Controller class \"{$controller}\" not found");
        }
        $controllerClass = new $controller($controllerName, $actionName);
        

        if(!method_exists($controllerClass, $action)){
            throw new \Exception("The method \"{$action}\" does not exist on the \"{$controller}\" controller");
        }
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
}