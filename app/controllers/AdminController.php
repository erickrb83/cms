<?php
namespace App\Controllers;
use Core\{Controller, Session, Router};
use App\Models\Users;

class AdminController extends Controller{
    
    public function onConstruct(){
        $this->view->setLayout('admin');
        $this->currentUser = Users::getCurrentUser();
    }

    public function articlesAction(){
        Router::permRedirect(['author, admin'], 'blog/index');
        $this->view->render();
    }

    public function usersAction(){
        Router::permRedirect('admin', 'admin/articles');
        $params = ['order' => 'lname, fname'];
        $params = Users::mergeWithPagination($params);
        $this->view->users = Users::find($params);
        $this->view->total = Users::findTotal($params);
        $this->view->render();
    }

    public function toggleBlockUserAction($userId){
        Router::permRedirect('admin', 'admin/articles');
        $user = Users::findById($userId);
        if($user){
            $user->blocked = $user->blocked ? 0 : 1;
            $user->save();
            $msg = $user->blocker ? "User Blocked" : "User Unblocked";
        }
        Session::msg($msg, 'success');
        Router::redirect('admin/users');
    }

    public function deleteUserAction($userId){
        Router::permRedirect('admin', 'admin/articles');
        $user = Users::findById($userId);
        $msgType = 'danger';
        $msg = 'User can not be deleted';
        if($user && $user->id !== Users::getCurrentUser()->id){
            $user->delete();
            $msgType = 'success';
            $msg = 'User Deleted';
        }
        Session::msg($msg, $msgType);
        Router::redirect('admin/users');
    }
}