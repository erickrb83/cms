<?php

namespace App\Controllers;

use Core\Controller;

class BlogController extends Controller{

    public function indexACtion(){
        $this->view->setSiteTitle('Newest Articles');
        $this->view->render();
    }
}