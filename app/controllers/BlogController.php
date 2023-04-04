<?php

namespace App\Controllers;

use Core\{DB, Controller, H};

class BlogController extends Controller{

    public function indexAction(){
        $db = DB::getInstance();
        //$sql = "INSERT INTO articles (`title`, `body`) VALUES (:title, :body)";
        //$bind = ['title' => 'new article', 'body' => 'article body'];
        //$query = $db->query($sql, $bind);
        //$lastID = $query->lastInsertId();
        //$db->execute($sql, $bind);
        //$sql ="SELECT * FROM articles";
        //$articles = $db->query($sql) -> results();
        //$count = $query->count();
        //H::dnd($lastID);
        //$db->insert('articles', ['title' => 'article', 'body' => 'article body']);
        $db->update('articles', ['title' => 'article updated', 'body' => 'body updated'], ['id' => '7']);
        $this->view->setSiteTitle('Newest Articles');
        $this->view->render();
    }
}