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
        
        $controllerClass = new $controller($controllerName, $actionName);
        call_user_func_array([$controllerClass, $action], $urlParts);
    }
}