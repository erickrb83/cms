<?php
namespace App\Models;

use Core\{Model, Session, Config, H};
use Core\Cookie;
use Core\Validators\{RequiredValidator, EmailValidator, MatchesValidator, MinValidator, MaxValidator, NumericValidator, UniqueValidator};
use App\Models\{UserSessions};

class Users extends Model{
    protected static $table = 'users', $_current_user = false;
    public $id, $created_at, $updated_at, $fname, $lname, $email, $password, $acl, $blocked = 0, $confirm, $remember = '';

    const AUTHOR_PERMISSION = 'author';
    const ADMIN_PERMISSION = 'admin';

    public function beforeSave(){
        $this->timeStamps();

        $this->runValidation(new RequiredValidator($this, ['field' => 'fname', 'msg' => "First Name is a required field"]));
        $this->runValidation(new RequiredValidator($this, ['field' => 'lname', 'msg' => "Last Name is a required field"]));
        $this->runValidation(new RequiredValidator($this, ['field' => 'email', 'msg' => "email is a required field"]));
        $this->runValidation(new EmailValidator($this, ['field' =>'email', 'msg' => "must provide valid email"]));
        $this->runValidation(new RequiredValidator($this, ['field' => 'acl', 'msg' => "role is a required field"]));
        // $this->runValidation(new NumericValidator($this, ['field' => 'fname', 'msg' => "Field must be numeric"]));
        $this->runValidation(new UniqueValidator($this, ['field' => ['email', 'lname'], 'msg' => "A user with this email already exists"]));
 
        if($this->isNew() || $this->resetPassword){
            $this->runValidation(new RequiredValidator($this, ['field' => 'password', 'msg' => "password is a required field"]));
            $this->runValidation(new RequiredValidator($this, ['field' => 'confirm', 'msg' => "Confirm password is a required field"]));
            $this->runValidation(new MatchesValidator($this, ['field' => 'confirm', 'rule' =>$this->password, 'msg' => "Passwords do not match"]));
            $this->runValidation(new MinValidator($this, ['field' => 'password', 'rule' =>8, 'msg' => "Password must be at least 8 characters"]));
            // $this->runValidation(new MaxValidator($this, ['field' => 'password', 'rule' =>10, 'msg' => "Password must be shorter than 10 characters"]));

            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }else {
            $this->_skipUpdate = ['password'];
        }
    }

    public function validateLogin(){
        $this->runValidation(new RequiredValidator($this, ['field' => 'email', 'msg' => "email is a required field"]));
        $this->runValidation(new RequiredValidator($this, ['field' => 'password', 'msg' => "password is a required field"]));    
    }

    public function login($remember = false){
        Session::set('logged_in_user', $this->id); 
        self::$_current_user = $this; 
        if($remember){
            $now = time();
            $newHash = md5("{$this->id}_{$now}");
            $session = UserSessions::findByUserId($this->id);
            if(!$session){
                $session = new UserSessions();
            }
            $session->user_id = $this->id;
            $session->hash = $newHash;
            $session->save();
            Cookie::set(Config::get('login_cookie_name'), $newHash, 60 * 60 * 24 * 30); //30 day cookie length
        }
    }

    public static function loginFromCookie(){
        $cookieName = Config::get('login_cookie_name');
        if(!Cookie::exists($cookieName)){
            return false;
        } 
        $hash = Cookie::get($cookieName);
        $session = UserSessions::findByHash($hash);
        if(!$session) {
            return false;
        } 
        $user = self::findById($session->user_id);
        if($user){
            $user->login(true);
        }
    }

    public function logout(){
        Session::delete('logged_in_user');
        self::$_current_user = false;
        $session = UserSessions::findByUserId($this->id);
        if($session){
            $session->delete();
        }
        Cookie::delete(Config::get('login_cookie_name'));
    }

    public static function getCurrentUser(){
        if(!self::$_current_user && Session::exists('logged_in_user')){
            $user_id = Session::get('logged_in_user');
            self::$_current_user = self::findById($user_id);
        }
        if(!self::$_current_user) {
            self::loginFromCookie();
        }
        if(self::$_current_user && self::$_current_user->blocked){
            self::$_current_user->logout();
            Session::msg("You are currently blocked, please talk to admin");
        }
        return self::$_current_user;
    }

    public function hasPermission($acl){
        if(is_array($acl)){
            return in_array($this->acl, $acl);	
        }
        return $this->acl == $acl;
    }

    public function displayName(){
        return trim($this->fname . ' ' . $this->lname);
    }

    public static function findAuthorsWithArticles(){
        $params = [
            'columns' => "users.fname, users.lname, users.id",
            'conditions' => "articles.status = 'public'",
            'joins' => [
                ['articles', 'articles.user_id = users.id']
            ],
            'group' => 'users.id',
            'order' => 'users.lname, users.fname'
        ];
        return self::find($params);
    }
}