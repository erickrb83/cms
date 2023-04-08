<?php
namespace App\Models;

use Core\{Model, Session, Cookie};
use Core\Validators\{RequiredValidator, EmailValidator, MatchesValidator, MinValidator, MaxValidator, NumericValidator, UniqueValidator};

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
        Session::get('logged_in_user', $this->id); 
        self::$_current_user = $this->id; 
    }
}