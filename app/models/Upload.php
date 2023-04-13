<?php 
namespace App\Models;
use Core\H;

class Upload {
    public $file, $field, $errors = [];
    public $size, $tmp, $ext;
    public $maxSize = 2000000;
    public $allowedFileTypes = ['jpeg' => 'image/jpeg','jpg' => 'image/jpg', 'png' => 'image/png', 'gif' => 'image/gif'];
    public $required = true;

    public function __construct($field){
        $this->field = $field;
        $this->checkInitialError();
        $this->file = $_FILES[$field];
        $this->size = $this->file['size'];
        $this->tmp = $this->file['tmp_name'];
        $this->ext = pathinfo($this->file['name'], PATHINFO_EXTENSION); //Flag, more reliable way to check extension.

    }

    public function validate(){
        $this->errors = [];
        if(empty($this->tmp) && $this->required){
            $this->errors[$this->field] = "File is Required";
        }
        //check size
        if($this->size > $this->maxSize){
            $this->errors[$this->field] = "File max size is " . $this->formatBytes($this->size);
        }
        //Check file type
        if(empty($this->errors)){
            $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
            $type = $fileInfo->file($this->tmp);
            echo($type);
            if(array_search($type, $this->allowedFileTypes) === false){
                $this->errors[$this->field] = "File type is not allowed. Must be: " . implode(', ', array_keys($this->allowedFileTypes));
            }
        }
        
        return $this->errors;
    }

    public function upload($fileName){
        $test = move_uploaded_file($this->tmp, $fileName);
        return $test;
    }


    // Finds how to find which unit to return based on file size. 1 GB is cleaner that 1,000,000,000 B
    public function formatBytes($bytes, $precision = 2){
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0 ) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes, $precision) . $units[$pow];
    }

    private function checkInitialError(){
        if(!isset($_FILES[$this->field]) || is_array($_FILES[$this->field]['error'])){ 
            throw new \RuntimeException('Something is wrong with the file.');
        };
    }
}