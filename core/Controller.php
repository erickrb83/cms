<?php
namespace Core;

class Controller{
    // _ first denotes private variable.
    private $_controllerName, $_actionName;
    public $view, $request;

    public function __construct($controller, $action){
        $this->_controllerName = $controller;
        $this->_actionName = $action;
        var_dump($controller);
    }
}