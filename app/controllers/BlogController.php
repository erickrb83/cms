<?php

namespace App\Controllers;

use Core\Controller;

class BlogController extends Controller{

    public function indexACtion($param1, $param2){
        die("made it to index action {$param1} and {$param2}");
    }

    public function fooAction(){
        die("made to to foo action");
    }
}