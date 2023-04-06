<?php
namespace Core;

class FH{
    public static function inputBlock($label, $id, $value, $inputAttributes = [], $wrapperAttributes = [], $errors = []){
        $wrapperStr = self::processAttributes($wrapperAttributes);
        $inputAttributes = self::appendErrors($id, $inputAttributes, $errors);
        $inputAttributes = self::processAttributes($inputAttributes);
        $errorMsg = array_key_exists($id, $errors) ? $errors[$id] : "";
        $html = "<div {$wrapperStr}>";
        $html .= "<label for='{$id}'>{$label}</label>";
        $html .= "<input id='{$id}' name='{$id}' value='{$value}' {$inputAttributes}/>";
        $html .= "<div class='invalid-feedback'>{$errorMsg}</div></div>";
        return $html;
    }

    public static function appendErrors($key, $inputAttributes, $errors){
        if(array_key_exists($key, $errors)){
            if(array_key_exists('class', $inputAttributes)){
                $inputAttributes['class'] .= ' is-invalid';
            }else{
                $inputAttributes['class'] = 'is-invalid';
            }
        }
        return $inputAttributes;
    }

    public static function processAttributes($attributes){
        $html = "";
        foreach($attributes as $key => $value){
            $html .= " {$key}='{$value}'";
        }
        return $html;
    }
}