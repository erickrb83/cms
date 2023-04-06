<?php
namespace App\Models;

use Core\Model;
use Core\Validators\{RequiredValidator, EmailValidator, MatchesValidator};

class Users extends Model{
    protected static $table = 'users';
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

        if($this->isNew()){
            $this->runValidation(new RequiredValidator($this, ['field' => 'password', 'msg' => "password is a required field"]));
            $this->runValidation(new RequiredValidator($this, ['field' => 'confirm', 'msg' => "Confirm password is a required field"]));
            $this->runValidation(new MatchesValidator($this, ['field' => 'confirm', 'rule' =>$this->password, 'msg' => "Passwords do not match"]));

            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
    }
}